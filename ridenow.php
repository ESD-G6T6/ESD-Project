<!DOCTYPE html>
<html lang="zxx">
<?php
require_once 'include/common.php';
?>    
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ooster Ride Now">
    <meta name="keywords" content="Gutim, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ooster | Ride Now</title>

    <link rel="stylesheet" href="">
        <!--[if lt IE 9]>
          <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!-- Bootstrap libraries -->
        <meta name="viewport" 
            content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
        integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" 
        crossorigin="anonymous">
    
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
                        <h2>Ride Now</h2>
                        <div class="breadcrumb-option">
                            <a href="./index.php"><i class="fa fa-home"></i>Home</a>
                            <span>Ride Now</span>
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

                <div id="main-container" class="container">
                    <div class="contact-form">
                        <h4>Please enter your Scooter ID and Parking Lot ID</h4>

                        <!-- form section begins-->
                        <form id="ridenowForm" action="ridenow.php" method="POST">
                            <div class="row">
                                <div class="col-lg-6">
                                    
                                    <div class="contact-option">
                                        <span>Scooter ID :</span>
                                    </div>
                                    <input type="text" placeholder="S0001" id="scooterID" name= "scooterID" required> <br>
                                    
                                    <div class="contact-option">
                                        <span>Parking Lot ID :</span>
                                    </div>
                                    <input type="text" placeholder="P0001" id="parkingLotID" name="parkingLotID" required>
                                    <button type = "submit" class="btn btn-dark" >Send</button>
                                </div>
                            </div>
                        </form>
                        <!-- form section ends -->

                    </div>
                </div>
    </section>
    <!-- Contact Section End -->
    
    <!-- get bookingID, scooterID begin-->
        <?php

        //-- get booking id begin --
            //to retrieve last bookingID and add 1
            $dao = new BookingDAO();
            $result = $dao->retrieveAll();
            $last_bookingID = $result[count($result)-1];
            $new_bookingIDnumber_int = (int) substr($last_bookingID,1) + 1;
            
            //number of 0s to add
            $new_bookingIDnumber_str = (string) $new_bookingIDnumber_int;
            $numberOfZeroToAdd = 4 - strlen($new_bookingIDnumber_str);
            $bookingID = 'B' . str_repeat("0", $numberOfZeroToAdd) . $new_bookingIDnumber_str;

            //session bookingid to pass to riding page
            $_SESSION['bookingID'] = $bookingID;
        //-- get booking id ends --
        
        //-- get startTime begin --
            date_default_timezone_set('Asia/Singapore');
            $startTime = date("Y-m-d H:i:s");
        //-- get startTime end --

        ?>
    <!-- get bookingID, startTime ends-->

    <script>
    //-- HTTP POST begins--
        function showError(message) {

            $('#ridenowForm').hide();
            $('#main-container')
            .append("<label>"+message+"</label>");
        }

        $("#ridenowForm").submit(async(event) => {  
            
            event.preventDefault();
            $("#error").hide();

            var scooterID = $('#scooterID').val();
            var parkingLotID = $('#parkingLotID').val();
            var bookingID = '<?php echo $bookingID; ?>';
            var startTime = '<?php echo $startTime; ?>';
            var endTime = null;
            sessionStorage.setItem("scooterID", scooterID);
            var bookingURL = "http://127.0.0.1:5001/booking" + "/" + bookingID;

            console.log(bookingID);
            console.log(scooterID);
            console.log(parkingLotID);
            console.log(startTime);
            console.log(endTime);
            
            try {
                const response = 
                await fetch(
                    bookingURL,
                    {
                    mode: 'cors',
                    method: 'POST',
                    crossDomain: true,
                    headers: { "Content-Type": "application/json", "Access-Control-Allow-Origin": "*" },
                    body: JSON.stringify({ scooterID: scooterID, parkingLotID: parkingLotID,startTime: startTime, endTime:endTime})
                    }
                );

                const data = await response.json();

                if (!data) {
                    showError('Booking failed.')
                } else {
                    window.location = 'http://localhost/ESD-Project/riding.php';
                }
            }
            catch (error) {
                showError
                ('There is a problem making your booking, please try again later.<br />'+error);
            }
        });
    //-- HTTP POST ends--
    </script>

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
