from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
import os
import requests
import json
import sys

app = Flask(__name__)

# app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/parkingLot'
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root:root@localhost:3306/parkingLot'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)

class ParkingLot(db.Model):
    __tablename__ = 'parkingLot'

    parkingLotID = db.Column(db.String(5), primary_key=True)
    numberOfAvailableScooters = db.Column(db.Integer)
    longitude = db.Column(db.Float(precision=2), nullable=False)
    latitude = db.Column(db.Float(precision=2), nullable=False)

    def __init__(self, parkingLotID, numberOfAvailableScooters, longitude, latitude):
        self.parkingLotID = parkingLotID
        self.numberOfAvailableScooters = numberOfAvailableScooters
        self.longitude = longitude
        self.latitude = latitude

    def json(self):
        return {"parkingLotID": self.parkingLotID, "numberOfAvailableScooters": self.numberOfAvailableScooters, 
            "longitude": self.longitude, "latitude": self.latitude}

# # return a list of all available parking lots
@app.route("/parkingLot")
def get_all(): 
    return jsonify({"parkingLots": [parkingLot.json() for parkingLot in ParkingLot.query.all()]})

# update the number of available scooters of a parking lot, return the info about the updated parking lot record 
@app.route("/parkingLot/<string:parkingLotID>", methods=['PUT'])
def update_parkingLot(parkingLotID):
    result = None
    status = 201
    # data pass is not in json format
    if (not (request.is_json)):
        result = request.get_data()
        status = 400 # Bad Request
        print("Received an invalid parking lot update error:")
        print(result)
        replymessage = json.dumps({"status": status, "message": "Parking Lot update information should be in JSON", "data": result}, default=str)
        return replymessage
    
    result = request.get_json()

    status = result["status"]
    scooterID = result["scooterID"]
    parkingLotID = result['parkingLotID']
    availabilityStatus = result['availabilityStatus']

    # Parking lot does not exists in the database
    if (not(ParkingLot.query.filter_by(parkingLotID=parkingLotID).first())):
        status = 400
        result = {"status": status, "message": "A parking lot with parking lot ID of '{}' does not exists.".format(parkingLotID)}

    # Parking lot exists in the database
    elif status == 201:
        dbParkingLot = ParkingLot.query.filter_by(parkingLotID=parkingLotID).first()

        # Scenario 1: renting of scooter
        if (availabilityStatus == 0):
            # Parking Lot is unable to update database because there is no available scooters
            if dbParkingLot.numberOfAvailableScooters < 1:
                status = 400
                result = {"status": status, "message": "A parking lot with parking lot ID of '{}' does not have any scooters.".format(parkingLotID)}
            else:
                # update parking lot info (number of available scooters) in database
                try:
                    dbParkingLot.numberOfAvailableScooters -= 1
                    db.session.commit()
                except Exception as e:
                    status = 500
                    result = {"status": status, "message": "An error occurred when updating the parking lot in DB.", "error": str(e)}
        
        elif (availabilityStatus == 1):
            # update parking lot info (number of available scooters) in database
            try:
                dbParkingLot.numberOfAvailableScooters += 1
                db.session.commit()
            except Exception as e:
                status = 500
                result = {"status": status, "message": "An error occurred when updating the parking lot in DB.", "error": str(e)}
    
        if status == 201:
            result = {"status": status, "message": "Successfully updated the parking lot DB", "availabilityStatus": availabilityStatus}
    
    return result

if __name__ == '__main__': 
    app.run(host='0.0.0.0', port=5002, debug=True)