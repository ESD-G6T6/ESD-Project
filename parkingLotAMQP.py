#!/usr/bin/env python3
# The above shebang (#!) operator tells Unix-like environments
# to run this file as a python3 script

import json
import sys
import os
import random
import requests

# Communication patterns:
# Use a message-broker with 'direct' exchange to enable interaction
import pika
parkingLotURL = "http://localhost:5002/parkingLot/"

def receiveScooter():
    hostname = "localhost" 
    port = 5672 

    # connect to the broker and set up a communication channel in the connection
    connection = pika.BlockingConnection(pika.ConnectionParameters(host=hostname, port=port))
    channel = connection.channel()

    # set up the exchange if the exchange doesn't exist
    exchangename="scooter_direct"
    channel.exchange_declare(exchange=exchangename, exchange_type='direct')

    # prepare a queue for receiving messages
    channelqueue = channel.queue_declare(queue="parkingLot", durable=True) 
    queue_name = channelqueue.method.queue
    channel.queue_bind(exchange=exchangename, queue=queue_name, routing_key='parkingLot.scooterInfo') 

    # set up a consumer and start to wait for coming messages
    channel.basic_qos(prefetch_count=1) 
    channel.basic_consume(queue=queue_name, on_message_callback=callback)
    channel.start_consuming() 

def callback(channel, method, properties, body): # required signature for the callback; no return
    print("Received a request from scooter")
    result = processScooter(json.loads(body))
    
    json.dump(result, sys.stdout, default=str) # convert the JSON object to a string and print out on screen
    print() 
    print() 

    # prepare the reply message and send it out
    replymessage = json.dumps(result, default=str) # convert the JSON object to a string
    replyqueuename="parkingLot.reply"
    exchangename="scooter_direct"

    channel.queue_declare(queue=replyqueuename, durable=True) 
    channel.queue_bind(exchange=exchangename, queue=replyqueuename, routing_key=replyqueuename) 
    channel.basic_publish(exchange=exchangename, routing_key=properties.reply_to, body=replymessage, 
        properties=pika.BasicProperties(delivery_mode = 2, correlation_id = properties.correlation_id)
    )
    channel.basic_ack(delivery_tag=method.delivery_tag) # acknowledge to the broker that the processing of the request message is completed

def processScooter(parkingLot):
    print("Updating parking lot information:")
    print(parkingLot)

    if "parkingLotID" in parkingLot:
        r = requests.put(parkingLotURL + str(parkingLot['parkingLotID']))
        result = {'status': r.status_code, 'scooterID': parkingLot['scooterID'], 
            'parkingLotID': parkingLot['parkingLotID'], 'correlation_id': parkingLot['correlation_id']}

        # print(r.status_code)
        if (r.status_code == 201):
            print("Successful update of Parking Lot")
        else:
            print("Unsuccessful update of Parking Lot")
        return result


if __name__ == "__main__": 
    print("Parking Lot AMQP...")
    receiveScooter()