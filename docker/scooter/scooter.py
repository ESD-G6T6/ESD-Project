from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
from os import environ
import os
import requests
import json
import sys

app = Flask(__name__)

parkingLotURL = "http://127.0.0.1:5002/parkingLot/"
app.config['SQLALCHEMY_DATABASE_URI'] = environ.get('dbURL')
# app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/scooter'
# app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root:root@localhost:3306/scooter'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)

class Scooter(db.Model):
    __tablename__ = 'scooter'

    scooterID = db.Column(db.String(5), primary_key=True)
    parkingLotID = db.Column(db.String(5), nullable=True)
    availabilityStatus = db.Column(db.Boolean, nullable=False)

    def __init__(self, scooterID, parkingLotID, availabilityStatus):
        self.scooterID = scooterID
        self.parkingLotID = parkingLotID
        self.availabilityStatus = availabilityStatus

    def json(self):
        return {"scooterID": self.scooterID, "parkingLotID": self.parkingLotID, "availabilityStatus": self.availabilityStatus}

# # return a list of all available scooters
# @app.route("/scooter")
# def get_all(): 
#     return jsonify({"scooters": [scooter.json() for scooter in Scooter.query.all()]})

# # return information about a scooter
# @app.route("/scooter/<string:scooterID>")
# def find_by_scooterID(scooterID):
#     scooter = Scooter.query.filter_by(scooterID=scooterID).first()
#     if scooter:
#         return jsonify(scooter.json())
#     return jsonify({"message": "Scooter not found."}), 404

# # return a list of all scooters in a parking lot
# @app.route("/scooter/parkingLot/<string:parkingLotID>")
# def find_by_parkingLotID(parkingLotID):
#     parkingLot = Scooter.query.filter_by(parkingLotID=parkingLotID)
#     if parkingLot:
#         return jsonify({"scooters": [scooter.json() for scooter in parkingLot]})

#     return jsonify({"message": "Parking lot not found."}), 404

# receive the update from booking - update scooter function
# update the parkingLotID and availabilityStatus of a scooter, return the info about the updated scooter record 
@app.route("/scooter/<string:scooterID>", methods=['POST'])
def update_scooter(scooterID):
    result = None
    status = 201
    # data pass is not in json format
    if (not (request.is_json)):
        result = request.get_data()
        status = 400 # Bad Request
        print("Received an invalid scooter update error:")
        print(result)
        replymessage = json.dumps({"status": status, "message": "Scooter update information should be in JSON", "data": result}, default=str)
        return replymessage
    
    result = request.get_json()

    status = result["status"]
    scooterID = result["scooterID"]
    availabilityStatus = result["availabilityStatus"]
    parkingLotID = result['parkingLotID']

    # Scooter does not exists in the database
    if (not(Scooter.query.filter_by(scooterID=scooterID).first())):
        status = 400
        result = {"status": status, "message": "A scooter with scooterID '{}' does not exists.".format(scooterID)}

    # Scooter exists in the database
    elif status == 201:
        dbScooter = Scooter.query.filter_by(scooterID=scooterID).first()

        # Scenario 1: renting of scooter
        if (availabilityStatus == 0):
            # Scooter is unavailble to rent beacause it is not available 
            if (dbScooter.availabilityStatus == availabilityStatus or dbScooter.parkingLotID == None):
                status = 400
                result = {"status": status, "message": "A scooter with scooterID '{}' is unavailable to rent.".format(scooterID)}
            else:
                # update scooter info (parking lot ID and availability status) in database
                try:
                    dbScooter.parkingLotID = None
                    dbScooter.availabilityStatus = 0
                    db.session.commit()
                except Exception as e:
                    status = 500
                    result = {"status": status, "message": "An error occurred when updating the scooter in DB.", "error": str(e)}

        # Scenario 2: ending scooter ride
        elif (availabilityStatus == 1):
            # Scooter is unavailble to be updated beacause it is available 
            if (dbScooter.availabilityStatus == availabilityStatus or dbScooter.parkingLotID != None):
                status = 400
                result = {"status": status, "message": "A scooter with scooterID '{}' is unavailable to be updated as it is not rented.".format(scooterID)}
            else:
                # update scooter info (parking lot ID and availability status) in database
                try:
                    dbScooter.parkingLotID = parkingLotID
                    dbScooter.availabilityStatus = 1
                    db.session.commit()
                except Exception as e:
                    status = 500
                    result = {"status": status, "message": "An error occurred when updating the scooter in DB.", "error": str(e)}

        if status == 201:
            result = {"status": status, "scooterID": scooterID, "parkingLotID": parkingLotID, "availabilityStatus": availabilityStatus}
            print(result)

            # send HTTP call to parking lot to update the number of available scooters
            parkingLotResult = send_scooter(result)
            return parkingLotResult

    return jsonify(result)

# HTTP call to parking lot 
def send_scooter(result):
    result = json.loads(json.dumps(result, default=str))
    if "parkingLotID" in result: 
        # inform Parking Lot
        parkingLotID = result["parkingLotID"]
        r = requests.post(parkingLotURL + str(parkingLotID), json = result, timeout=1)
        print("Scooter of status ({:d}) sent to parking lot.".format(result["status"]))
        parkingLotResult = json.loads(r.text)
    return parkingLotResult

if __name__ == '__main__': 
    app.run(host='0.0.0.0', port=5000, debug=True)