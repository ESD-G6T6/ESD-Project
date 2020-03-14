from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
from os import environ

# Communication patterns:
# Use a message-broker with 'direct' exchange to enable interaction
import pika

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
    parkingLot = ParkingLot.query.filter_by(parkingLotID=parkingLotID).first()
    if parkingLot:
        try:
            if parkingLot.numberOfAvailableScooters < 1:
                return jsonify({"message": "An error occurred updating the parking lot."}), 500
            else:
                parkingLot.numberOfAvailableScooters -= 1
                db.session.commit()

        except:
            return jsonify({"message": "An error occurred updating the parking lot."}), 500
        
        return jsonify(parkingLot.json()), 201
        
    return jsonify({"message": "Parking lot not found."}), 404

if __name__ == '__main__': 
    app.run(host='127.0.0.1', port=5002, debug=True)