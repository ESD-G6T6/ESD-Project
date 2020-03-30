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
    <title>Gutim | Riding</title>

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
                        serviceURL, { method: 'GET'}
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

        <div class="container">

            <div class="contact-form">
                <h4 >Please enter your Parking Lot ID to end your ride :</h4>

                <!-- form section begins-->
                <form id="parkingLotIDform" method="POST">
                    <div class="row">
                        <div class="col-lg-6">

                            <div class="contact-option">
                                <span>Parking Lot ID :</span>
                            </div>
                            <input type="text" placeholder="P0001" id="parkingLotID" name="parkingLotID" required>

                            <button type = "submit" class="btn btn-dark" >Send</button>

                        </div>
                    </div>
                </form>
                <!-- form section ends -->
                <br>
                <div class="contact-option">
                    <span> Duration :</span>
                </div>
                <h1><time>00:00:00</time></h1>

            </div>
        </div>
                
    </section>
    <!-- Contact Section End -->
    
    <!-- get bookingID, scooterID, parkingLot ID, startTime begin-->
    <?php

    //-- get booking id begin --
        $bookingID = $_SESSION['bookingID'];
    //-- get booking id ends --
    
    //-- get endTime begin --
        date_default_timezone_set('Asia/Singapore');
        $endTime = date("Y-m-d H:i:s");
    //-- get endTime end --

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
            //Prevents screen from refreshing when submitting
            //event is referring to the submit event
            
            event.preventDefault();
            $("#error").hide();

            var bookingID = '<?php echo $bookingID; ?>';
            var endTime = '<?php echo $endTime; ?>';
            var scooterID = sessionStorage.getItem("scooterID");
            var parkingLotID = $('#parkingLotID').val();
            var bookingURL = "http://127.0.0.1:5001/booking/payment" + "/" + bookingID;

            console.log(scooterID);
            console.log(bookingID);
            console.log(endTime);
            console.log(parkingLotID);
            
            try {
                const response = 
                await fetch(
                    bookingURL,
                    {
                    mode: 'cors',
                    method: 'POST',
                    crossDomain: true,
                    headers: { "Content-Type": "application/json", "Access-Control-Allow-Origin": "*" },
                    body: JSON.stringify({ endTime: endTime, parkingLotID: parkingLotID, scooterID:scooterID})
                    }
                );

                const data = await response.json();

                if (!data) {
                    showError('Booking failed.')
                } else {
                    window.location = 'http://localhost/ESD-Project/ridedone.php';
                }
            }
            catch (error) {
                showError
                ('There is a problem making your booking, please try again later.<br />'+error);
                console.log(error);
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
                        <p>contactcompany@Ooster.com</p>
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
