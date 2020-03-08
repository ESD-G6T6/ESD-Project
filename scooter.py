from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
from os import environ

app = Flask(__name__)

app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root:root@localhost:3306/scooter'
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
# @app.route("/scooter")
# def get_all(): 
#     return jsonify({"scooters": [scooter.json() for scooter in Scooter.query.all()]})

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
    scooter = Scooter.query.filter_by(scooterID=scooterID).first()
    if scooter:
        data = request.get_json()

        try:
            if scooter.availabilityStatus == data['availabilityStatus']:
                return jsonify({"message": "An error occurred updating the scooter."}), 500
            else:
                scooter.parkingLotID = data['parkingLotID']
                scooter.availabilityStatus = data['availabilityStatus']
                db.session.commit()

        except:
            return jsonify({"message": "An error occurred updating the scooter."}), 500
        
        return jsonify(scooter.json()), 201
        
    return jsonify({"message": "Scooter not found."}), 404

if __name__ == '__main__': 
    app.run(host='0.0.0.0', port=5000, debug=True)