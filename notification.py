from flask import Flask
from flask_mail import Mail, Message
import json
import sys
import os
import pika

app = Flask(__name__)

app.config['MAIL_SERVER']='smtp.gmail.com'
app.config['MAIL_PORT'] = 465
app.config['MAIL_USERNAME'] = 'esdg6t6@gmail.com'
app.config['MAIL_PASSWORD'] = 'scooter!g6t6'
app.config['MAIL_USE_TLS'] = False
app.config['MAIL_USE_SSL'] = True
app.config['DEBUG'] = True

mail = Mail(app)

def sendEmail(data):
    status = 201
    result = {}
    with app.app_context():
        content = "Dear Rider, \n\n Here is your ride summary for Booking ID " + data['bookingID']  + ": \n\t Scooter used: " + data['scooterID'] + " \n\t Start Time of Ride: " + data['startTime'] + "\n\t End Time of Ride: " + data['endTime'] + "\n\t Cost of Ride: $" + data['cost'] + "\n\n Hope you'll ride with us again! :)"

        subject = "Ride payment details for Booking ID " + data['bookingID'] 

        try:
            msg = Message(subject, sender="esdg6t6@gmail.com", recipients=[data['email']])
            msg.body = content
            mail.send(msg)
            result = {"status": status, "message": "Payment summary sent successfully"}
        except:
            status = 400
            result = {"status": status, "message": "Unable to send email"}

    print(result)
    print()

def receiveMessage():
    hostname = "localhost"
    port = 5672

    connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))

    channel = connection.channel()
    exchangename="notification_direct"
    channel.exchange_declare(exchange=exchangename, exchange_type='direct')

    channelqueue = channel.queue_declare(queue='email', durable=True)
    queue_name = channelqueue.method.queue
    channel.queue_bind(exchange=exchangename, queue=queue_name, routing_key='notification.email')
    
    channel.basic_consume(queue=queue_name, on_message_callback=callback, auto_ack=True)
    channel.start_consuming()
    print()

def callback(channel, method, properties, body): # required signature for the callback; no return

    data = json.loads(body)
    sendEmail(data)

    json.dump(data, sys.stdout, default=str) # convert the JSON object to a string and print out on screen
    print() 

if __name__ == '__main__': 
    print("This is notification...")
    receiveMessage()
    app.run(debug=True)