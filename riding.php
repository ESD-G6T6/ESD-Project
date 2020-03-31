<!DOCTYPE html>
<html lang="zxx">
<?php
require_once 'include/common.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Gutim Template">
    <meta name="keywords" content="Gutim, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ooster | Riding</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900&display=swap"
        rel="stylesheet">

    <!-- Latest compiled and minified JavaScript -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script 
    src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <script
    src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
    integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
    crossorigin="anonymous"></script>
    
    <script 
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
    integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
    crossorigin="anonymous"></script>

    <!-- sandbox account -->
    <script src="https://www.paypal.com/sdk/js?client-id=AbEx5ggtWKaxfJJeM0xMO2Fy-jP_e_Grzz2ylsBFwd9X4UbcKzk-x7iUv-Bw-Wxsn1xBUuWThnNwg4BR"></script>
    
    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">

</head>

<body>

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb/classes-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>Riding</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <style>
        #map{
        height:700px;
        width:100%;
        }
    </style>

    <!-- Map Section Begin -->
        <div id="map">
        <script>
            function showError(message) {
                    // Display an error under the main container
                    $('#map')
                        .append("<label>"+message+"</label>");
            }
        
            function initMap(){
            var markers = [];
            var latitude = [] ;
            var longitude=[] ;
            var scooterNumber=[] ;
            var parkingLotID=[];
            
            $(async() => {           
                // connect to parkingLot microservice
                var serviceURL = "http://127.0.0.1:5002/parkingLot";
                try{
                const response =
                        await fetch(
                        serviceURL, { method: 'GET' }
                        );
                const data = await response.json();
                var coordinates = data.parkingLots;
                //console.log(coordinates);
                if (!coordinates || !coordinates.length) {
                    showError('coordinates list empty or undefined.');
                } else{
                    for (const coordinate of coordinates) {
                    latitude.push(coordinate.latitude);
                    longitude.push(coordinate.longitude);
                    scooterNumber.push(coordinate.numberOfAvailableScooters);
                    parkingLotID.push(coordinate.parkingLotID);
                    }
                    // console.log(latitude);
                    // console.log(longitude);
                    // console.log(scooterNumber);
                    // console.log(markers);

                    for (i=0; i<latitude.length;i++){
                    if(i==0){
                        markers.push({
                        coords:{lat:latitude[i],
                                lng:longitude[i]},
                        content:'Available Scooters: ' + scooterNumber[i] + '</br> Parking Lot ID: ' + parkingLotID[i]
                        //icon:'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'
                        });
                    }

                    else{
                        markers.push({
                        coords:{lat:latitude[i],
                                lng:longitude[i]},
                        content:'Available Scooters: ' + scooterNumber[i] + '</br> Parking Lot ID: ' + parkingLotID[i],
                        });

                    }
                    }
                    // console.log(latitude);
                    // console.log(longitude);
                    // console.log(scooterNumber);
                    console.log(markers);

                    var options = {
                    zoom:11.2,
                    center:{lat:1.3521,lng:103.8198}
                    }

                    // New map
                    var map = new google.maps.Map(document.getElementById('map'), options);

                    for(var i = 0;i < markers.length;i++){
                    // Add marker
                    addMarker(markers[i]);
                    }

                    // Add Marker Function
                    function addMarker(props){
                    var marker = new google.maps.Marker({
                        position: props.coords,
                        map: map,
                        icon:props.iconImage
                    });

                    // Check for customicon
                    if(props.iconImage){
                        // Set icon image
                        marker.setIcon(props.iconImage);
                    }

                    // Check content
                    if(props.content){
                        var infoWindow = new google.maps.InfoWindow({
                        content:props.content
                        });

                        marker.addListener('click', function(){
                        infoWindow.open(map, marker);
                        });
                    }
                    }

                }
                }catch(error){
                // Errors when calling the service; such as network error, 
                // service offline, etc
                showError('There is a problem retrieving coordinates data, please try again later.<br />'+error);
                }
            }); //end of async function
            }//end of init func
        </script>

        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWFzWbjps15KgfXTkBzk1mg0kjakbdFNY&callback=initMap">
        </script>
        </div>
    <!-- Map Section End -->

    <!-- Contact Section Begin -->
    <section class="contact-section spad">

        <div class="container" id="main-container">
            <div class="contact-form">
                <h4>Please enter your Parking Lot ID to end your ride :</h4>
                <!-- form section begins-->
                <form id="parkingLotIDform" method="POST">
                    <div class="row">
                        <div class="col-lg-6">

                            <div class="contact-option">
                                <span>Parking Lot ID :</span>
                            </div>
                            <input type="text" placeholder="P0001" id="parkingLotID" name="parkingLotID" required>
                            <button type = "submit" class="btn btn-dark" >End Ride</button>

                        </div>
                    </div>
                </form>
                <!-- form section ends -->
                <br>
                <div class="contact-option">
                    <span> Duration :</span>
                </div>

                <div id="timestamp">
                    <h1><time>00:00:00</time></h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div id="ridesummary"></div>
            <div id="paypal-button-container"> </div>
            <form id="notification"></form>
        </div>
    </section>
    <!-- Contact Section End -->
    
    <!-- get bookingID, scooterID, parkingLot ID, startTime begin-->
    <?php

    //-- get booking id begin --
        $bookingID = $_SESSION['bookingID'];
    //-- get booking id ends --

    ?>
    <script>
    //-- timer function begins --
        var h1 = document.getElementsByTagName('h1')[0],
        seconds = 0, minutes = 0, hours = 0,
        t;

        function add() {
            seconds++;
            if (seconds >= 60) {
                seconds = 0;
                minutes++;
                if (minutes >= 60) {
                    minutes = 0;
                    hours++;
                }
            }
            
            h1.textContent = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);

            timer();
        }
        
        function timer() {
            t = setTimeout(add, 1000);
        }
        timer();
    //-- timer function ends --

    //-- HTTP POST begins--
        function showError(message) {
            // Hide the table and button in the event of error
            $('#ridenowForm').hide();
            // Display an error under the main container
            $('#main-container')
                .append("<label>"+message+"</label>");
        }

        $("#parkingLotIDform").submit(async(event) => {  
            var UNIX_timestamp = Math.round((new Date()).getTime() / 1000);        
            var date = new Date(UNIX_timestamp * 1000);
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var dates = ('0' + date.getDate()).slice(-2);
            var hours = date.getHours();
            var minutes = "0" + date.getMinutes();
            var seconds = "0" + date.getSeconds();
            var formattedTime = year + '-' + month + '-' + dates + ' ' + hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
            
            event.preventDefault();
            $("#error").hide();

            var bookingID = '<?php echo $bookingID; ?>';
            var endTime = formattedTime;
            var scooterID = sessionStorage.getItem("scooterID");
            var parkingLotID = $('#parkingLotID').val();
            var bookingURL = "http://127.0.0.1:5001/booking/payment" + "/" + bookingID;
            
            // console.log(scooterID);
            // console.log(bookingID);
            // console.log(endTime);
            // console.log(parkingLotID);

            
            try {
                const response = 
                await fetch(
                    bookingURL,
                    {
                    mode: 'cors',
                    method: 'POST',
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ endTime: endTime, parkingLotID: parkingLotID, scooterID:scooterID})
                    }
                );

                const info = await response.json();
                // console.log(info);
                // console.log(info.price);
                // sessionStorage.setItem("price", info.price);

                if (info.status == 201) {
                    // console.log(info.price);
                    var cost = info.price;
                    sessionStorage.setItem("cost", cost);
                    
                    $("#main-container").hide();
                    $("#ridesummary").append(
                        
                        "<h4>For scooter <bold>" + scooterID + "</bold> parked at " + parkingLotID + ":</h4>" +
                        "<br> <div class='contact-option'><span> Your ride duration was for : </span> </div>" + info.duration + " minutes <br>" +
                        "<br> <div class='contact-option'><span> Your ride fare is : </span> </div> SGD $ " + info.price + "<br>" + "<br>"
                        // "<br> <div class='contact-option' id='paymentsuccess'><span>Please make payment below : </span> </div> <br>"
                    );
                    
                    paypal.Buttons({
                    createOrder: function(data, actions) {
                        // This function sets up the details of the transaction, including the amount and line item details.
                        return actions.order.create({
                        purchase_units: [{
                            amount: {
                            value: info.price
                            }
                        }]
                        });
                    },
                    onApprove: function(data, actions) {
                        // This function captures the funds from the transaction.
                        return actions.order.capture().then(function(details) {
                        // This function shows a transaction success message to your buyer.
                        alert('Transaction completed by ' + details.payer.name.given_name);
                        $("#paypal-button-container").hide();
                        $("#paymentsuccess").hide();
                        
                        $("#notification").append(
                            "<div class='contact-option'><span>Enter email address if you want the details below to be emailed to you: </span>" + "<br>" + '<input type="text" class="form-control" id="email" placeholder="Enter text">' +
                            '<br><button class="btn btn-dark" id="emailSubmitButton">Submit</button>'
                        );
                        });

                    }
                    }).render('#paypal-button-container');
                }
                else{
                    console.log(info);
                    showError(info.message);
                }
                // if (!info) {
                //     showError('Booking failed.')
                // } else {
                //     window.location = 'http://localhost/ESD-Project/ridedone.php';
                // }
            }
            catch (error) {
                showError
                ('There is a problem making your booking, please try again later.<br />'+error);
                console.log(error);
            }
        });
        $("#notification").submit(async (event) => {
            event.preventDefault();
            // bookingID currently hardcoded
            var bookingID = '<?php echo $bookingID; ?>';
            var serviceEmailURL = "http://127.0.0.1:5001/booking/notification/"+bookingID;
            // console.log(sessionStorage.getItem("x"));
            var emailData = $('#email').val();
            var costData = sessionStorage.getItem("cost");
            try{
            const responseEmail =
                await fetch(
                serviceEmailURL, {
                method: 'POST',
                mode: 'cors',
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email: emailData , cost: costData})
            });
            const statusEmail = await responseEmail.json();
            if (statusEmail.status == 201){
                alert(statusEmail.message);
                window.location = 'http://localhost/ESD-Project/index.php';
                $("#notification").hide();
            }

            }catch(error){
            showError("There is a problem sending email. <br/>" + error)
            }
        });
    //-- HTTP POST ends--
    </script>
    <!-- get bookingID, scooterID, parkingLot ID, startTime ends-->

   <!-- Footer Section Begin -->
   <footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="contact-option">
                        <span>Phone</span>
                        <p>(+65) 6488 9817</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-option">
                        <span>Address</span>
                        <p>81 Victoria St, Singapore 188065</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-option">
                        <span>Email</span>
                        <p>esdg6t6@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
