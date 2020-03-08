from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
from os import environ

app = Flask(__name__)

app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root@localhost:3306/booking'
# app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root:root@localhost:3306/booking'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
CORS(app)

class Booking(db.Model):
    __tablename__ = 'booking'

    bookingID = db.Column(db.String(5), primary_key=True)
    parkingLotID = db.Column(db.String(5), nullable=False)
    startTime = db.Column(db.DateTime, nullable=False)
    endTime = db.Column(db.DateTime, nullable=False)

    def __init__(self, bookingID, parkingLotID, startTime, endTime):
        self.bookingID = bookingID
        self.parkingLotID = parkingLotID
        self.startTime = startTime
        self.endTime = endTime

    def json(self):
        return {"bookingID": self.bookingID, "parkingLotID": self.parkingLotID, 
            "startTime": self.startTime, "endTime": self.endTime}

# return a list of all available bookings
@app.route("/booking")
def get_all(): 
    return jsonify({"bookings": [booking.json() for booking in Booking.query.all()]})

# create a new booking
@app.route("/booking/<string:bookingID>", methods=['POST'])
def create_booking(bookingID):
    if (Booking.query.filter_by(bookingID=bookingID).first()):
        return jsonify({"message": "A booking with bookingID '{}' already exists.".format(bookingID)}), 400

    data = request.get_json()
    booking = Booking(bookingID, **data)
    try:
        db.session.add(booking)
        db.session.commit()
    except:
        return jsonify({"message": "An error occurred creating the booking."}), 500

    return jsonify(booking.json()), 201

# update the endTime of a booking, return the info about the updated booking record 
@app.route("/booking/<string:bookingID>", methods=['PUT'])
def update_booking(bookingID):
    booking = Booking.query.filter_by(bookingID=bookingID).first()
    print(booking.json())
    if booking:
        data = request.get_json()
        try:
            if booking.endTime != booking.startTime:
                return jsonify({"message": "An error occurred updating the booking."}), 500
            else:
                booking.endTime = data['endTime']
                db.session.commit()
        except:
            return jsonify({"message": "An error occurred updating the booking."}), 500

        return jsonify(booking.json()), 201
        
    return jsonify({"message": "Booking not found."}), 404

if __name__ == '__main__': 
    app.run(host='0.0.0.0', port=5001, debug=True)