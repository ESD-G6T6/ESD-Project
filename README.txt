This file contains instructions on how to set up your Docker environment to work with the source code.

-------------------------------------------------------------------------------------------------------
Requirements:
-------------------------------------------------------------------------------------------------------
This module requires the following modules:

* Docker
* WAMP/MAMP is running

-------------------------------------------------------------------------------------------------------
Set up needed prior to running Docker:
-------------------------------------------------------------------------------------------------------
Copy and paste our project folder into your WAMP or MAMP directory (www or htdocs folder respectively).

If running on Mac, in include/ConnectionManager.php, comment off line 8 and uncomment line 9. 

In PHPMyAdmin, create a new account following the steps below.
    Click Add user account and specify the following:
    User name: (Use text field:) is213
    Host name: (Any host) %
    Password: <Change to No Password>

Then, import the following sql files found in the sql folder individually to create 3 separate databases.
    sql/booking.sql
    sql/scooter.sql
    sql/parkingLot.sql

-------------------------------------------------------------------------------------------------------
To create and run docker containers:
-------------------------------------------------------------------------------------------------------
1. Comment off line 12 to 32 in the docker/docker-compose.yml.

2. Run the following command in Power Shell if running on Windows, and in Terminal if running on Mac. 
    docker-compose up --build

3. Uncomment the previous lines and comment off line 7 to 9 in docker/docker-compose.yml. 

4. In a separate Terminal/PowerShell, run the following command:
    docker-compose up --build

-------------------------------------------------------------------------------------------------------
To run our application (preferred browser is Google Chrome):
-------------------------------------------------------------------------------------------------------
Access your localhost from your browser and access the localhost/ESD-G6T6/index.php. 

Please allow location sharing when prompted by your browser.

To make payment via PayPal, please use the following credentials:
    sandbox email: sb-5sjfv1211029@personal.example.com
    password: esd!g6t6

-------------------------------------------------------------------------------------------------------
To stop the Docker containers:
-------------------------------------------------------------------------------------------------------
Run the following command in all active terminals:
    docker-compose down