from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
from os import environ
from datetime import timedelta
import requests
import json
import sys
import os

app = Flask(__name__)

scooterURL = "http://localhost:5000/scooter/"

# app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/booking'
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root:root@localhost:3306/booking'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)

class Booking(db.Model):
    __tablename__ = 'booking'

    bookingID = db.Column(db.String(5), primary_key=True)
    scooterID = db.Column(db.String(5), nullable=False)
    parkingLotID = db.Column(db.String(5), nullable=True)
    startTime = db.Column(db.DateTime, nullable=False)
    endTime = db.Column(db.DateTime, nullable=True)

    def __init__(self, bookingID, scooterID, parkingLotID, startTime, endTime):
        self.bookingID = bookingID
        self.scooterID = scooterID
        self.parkingLotID = parkingLotID
        self.startTime = startTime
        self.endTime = endTime

    def json(self):
        return {"bookingID": self.bookingID, "scooterID": self.scooterID, "parkingLotID": self.parkingLotID,
            "startTime": self.startTime, "endTime": self.endTime}

# return a list of all available bookings
@app.route("/booking")
def get_all(): 
    return jsonify({"bookings": [booking.json() for booking in Booking.query.all()]})

# create a new booking
@app.route("/booking/<string:bookingID>", methods=['POST'])
def create_booking(bookingID):
    status = 201
    result = {}

    # retrieve information about order and order items from the request
    # check if booking existed
    if (Booking.query.filter_by(bookingID=bookingID).first()):
        status = 400
        result = {"status": status, "message": "A booking with bookingID '{}' already exists.".format(bookingID)}

    elif status == 201:
        # check if booking is empty
        data = request.get_json()
        if (len(data) < 1):
            status = 404
            result = {"status": status, "message": "Empty booking."}
        else:
            booking = Booking(bookingID, **data)

            # check if there is any missing input fields
            if booking.scooterID == "" or booking.parkingLotID == "" or booking.startTime == "":
                status = 404
                result = {"status": status, "message": "Missing input fields."}
            # add the booking to the booking database
            else:
                try:
                    db.session.add(booking)
                    db.session.commit()
                    scooterID = booking.scooterID
                    parkingLotID = booking.parkingLotID

                except Exception as e:
                    status = 500
                    result = {"status": status, "message": "An error occurred when creating the booking in DB.", "error": str(e)}

    if status == 201:
        result = {"status": status, "scooterID": scooterID, "parkingLotID": parkingLotID, "availabilityStatus": 0}
        # HTTP call to update scooter via update_scooter function
        updateScooterStatus = update_scooter(result)
        
        return updateScooterStatus
    return result

### Update scooter microservice through HTTP call
def update_scooter(result):
    result = json.loads(json.dumps(result, default=str))
    if "scooterID" in result: 
        # inform Scooter
        scooterID = result["scooterID"]
        r = requests.put(scooterURL + str(scooterID), json = result, timeout=1)
        print("Booking of status ({:d}) sent to scooter.".format(result["status"]))
        scooterResult = json.loads(r.text)
        print(scooterResult)
        return scooterResult

# update the endTime of a booking, return the info about the updated booking record 
@app.route("/booking/<string:bookingID>", methods=['PUT'])
def update_booking(bookingID):
    status = 201
    result = {}

    # retrieve information about order and order items from the request
    # check if booking existed
    if (not (Booking.query.filter_by(bookingID=bookingID).first())):
        status = 400
        result = {"status": status, "message": "A booking with bookingID '{}' does not exists.".format(bookingID)}

    elif status == 201:
        # check if booking's enddate is empty
        data = request.get_json()
        if (len(data) < 1):
            status = 404
            result = {"status": status, "message": "Empty booking."}
        else:
            # check if there is any missing input fields
            if data["endTime"] == "":
                status = 404
                result = {"status": status, "message": "Missing input fields."}
            # add the booking to the booking database
            else:
                booking = Booking.query.filter_by(bookingID=bookingID).first()
                dbEndTime = booking.endTime
                if (dbEndTime != None):
                    status = 400
                    result = {"status": status, "message": "A booking with bookingID '{}' is unavailable to end.".format(bookingID)}
                else:    
                    try:
                        booking.endTime = data["endTime"]
                        db.session.commit()
                        scooterID = booking.scooterID
                        parkingLotID = booking.parkingLotID

                    except Exception as e:
                        status = 500
                        result = {"status": status, "message": "An error occurred when updating the booking in DB.", "error": str(e)}

    if status == 201:
        result = {"status": status, "scooterID": data["scooterID"], "parkingLotID": data["parkingLotID"], "availabilityStatus": 1}
        # HTTP call to update scooter via update_scooter function
        updateScooterStatus = update_scooter(result)

        # calculate the price of the ride
        if (updateScooterStatus["status"] == 201 and updateScooterStatus["availabilityStatus"] == 1):
            dbBooking = Booking.query.filter_by(bookingID=bookingID).first()
            endTime = dbBooking.endTime # 2020-03-07 00:06:00
            startTime = dbBooking.startTime # 2020-03-07 00:01:00
            duration = str(endTime - startTime)
            
            delta = timedelta(hours=int(duration.split(':')[0]), minutes=int(duration.split(':')[1]), seconds=int(duration.split(':')[2]))
            minutes = delta.total_seconds()/60

            price = minutes * 0.10
            status = updateScooterStatus["status"]
            message = updateScooterStatus["message"]
            updateScooterStatus = {"status": status, "message": message, "price": str(price), "duration": str(minutes)}
        return updateScooterStatus
    return result

if __name__ == '__main__': 
    app.run(host='0.0.0.0', port=5001, debug=True)