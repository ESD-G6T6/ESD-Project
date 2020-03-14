from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
from os import environ
import requests
import json
# Communication patterns:
# Use a message-broker with 'direct' exchange to enable interaction
# Use a reply-to queue and correlation_id to get a corresponding reply
import pika
# If see errors like "ModuleNotFoundError: No module named 'pika'", need to
# make sure the 'pip' version used to install 'pika' matches the python version used.
import uuid
import csv

parkingLotURL = "http://localhost:5002/parkinglot/"

app = Flask(__name__)

# app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root:root@localhost:3306/scooter'
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/scooter'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)

class Scooter(db.Model):
    __tablename__ = 'scooter'

    scooterID = db.Column(db.String(5), primary_key=True)
    parkingLotID = db.Column(db.String(5), nullable=False)
    availabilityStatus = db.Column(db.Boolean, nullable=False)

    def __init__(self, scooterID, parkingLotID, availabilityStatus):
        self.scooterID = scooterID
        self.parkingLotID = parkingLotID
        self.availabilityStatus = availabilityStatus

    def json(self):
        return {"scooterID": self.scooterID, "parkingLotID": self.parkingLotID, "availabilityStatus": self.availabilityStatus}

# return a list of all available scooters
@app.route("/scooter")
def get_all(): 
    return jsonify({"scooters": [scooter.json() for scooter in Scooter.query.all()]})

# return information about a scooter
@app.route("/scooter/<string:scooterID>")
def find_by_scooterID(scooterID):
    scooter = Scooter.query.filter_by(scooterID=scooterID).first()
    if scooter:
        return jsonify(scooter.json())
    return jsonify({"message": "Scooter not found."}), 404

# return a list of all scooters in a parking lot
@app.route("/scooter/parkingLot/<string:parkingLotID>")
def find_by_parkingLotID(parkingLotID):
    parkingLot = Scooter.query.filter_by(parkingLotID=parkingLotID)
    if parkingLot:
        return jsonify({"scooters": [scooter.json() for scooter in parkingLot]})

    return jsonify({"message": "Parking lot not found."}), 404

# update the parkingLotID and availabilityStatus of a scooter, return the info about the updated scooter record 
@app.route("/scooter/<string:scooterID>", methods=['PUT'])
def update_scooter(scooterID):
    result = None
    if request.is_json:
        result = request.get_json()
    else:
        result = request.get_data()
        print("Received an invalid scooter update error:")
        print(result)
        replymessage = json.dumps({"message": "Scooter update error should be in JSON", "data": result}, default=str)
        return replymessage, 400 # Bad Request

    status = result["status"]
    scooterID = result["scooterID"]
    availabilityStatus = result["availabilityStatus"]
    parkingLotID = result['parkingLotID']

    if (not(Scooter.query.filter_by(scooterID=scooterID).first())):
        status = 400
        result = {"status": status, "message": "A scooter with scooterID '{}' does not exists.".format(scooterID)}

    elif status == 201:
        dbScooter = Scooter.query.filter_by(scooterID=scooterID).first()
        if (dbScooter.availabilityStatus == availabilityStatus or dbScooter.parkingLotID == "null"):
            status = 400
            result = {"status": status, "message": "A scooter with scooterID '{}' is unavailable to rent.".format(scooterID)}
        else:
            try:
                dbScooter.parkingLotID = "null"
                dbScooter.availabilityStatus = 0
                db.session.commit()
            except Exception as e:
                status = 500
                result = {"status": status, "message": "An error occurred when updating the scooter in DB.", "error": str(e)}
        print(status)
        if status == 201:
            result = {"status": status, "scooterID": scooterID, "parkingLotID": parkingLotID}
            print(result)

            # send AMQP to parkingLot.py
            send_scooter(result)

    return jsonify(result)

def send_scooter(result):
    """inform parking lot"""
    # default username / password to the borker are both 'guest'
    hostname = "localhost" # default broker hostname. Web management interface default at http://localhost:15672
    port = 5672 # default messaging port.
    # connect to the broker and set up a communication channel in the connection
    connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))
        # Note: various network firewalls, filters, gateways (e.g., SMU VPN on wifi), may hinder the connections;
        # If "pika.exceptions.AMQPConnectionError" happens, may try again after disconnecting the wifi and/or disabling firewalls
    channel = connection.channel()

    # set up the exchange if the exchange doesn't exist
    exchangename="scooter_direct"
    channel.exchange_declare(exchange=exchangename, exchange_type='direct')

    if "scooterID" in result: # if status is successful 
        # inform Parking Lot
        corrid = str(uuid.uuid4())
        result["correlation_id"] = corrid
        # prepare the message body content
        message = json.dumps(result, default=str) # convert a JSON object to a string

        replyqueuename = "parkingLot.reply"

        channel.queue_declare(queue='parkingLot', durable=True) # make sure the queue used by the error handler exist and durable
        channel.queue_bind(exchange=exchangename, queue='parkingLot', routing_key='parkingLot.scooterInfo') # make sure the queue is bound to the exchange
        channel.basic_publish(exchange=exchangename, routing_key="parkingLot.scooterInfo", body=message,
            properties=pika.BasicProperties(delivery_mode = 2, # make message persistent within the matching queues until it is received by some receiver (the matching queues have to exist and be durable and bound to the exchange)
                reply_to=replyqueuename, # set the reply queue which will be used as the routing key for reply messages
                correlation_id=corrid) # set the correlation id for easier matching of replies
        )
        scooterID = result["scooterID"]
        # print("Scooter information of '{}' sent to parking lot.".format(scooterID))
        print("Scooter information of '{}' sent to parking lot.".format(result))
   
    # close the connection to the broker
    connection.close()

if __name__ == '__main__': 
    app.run(host='0.0.0.0', port=5000, debug=True)