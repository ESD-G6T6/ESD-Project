<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Gutim Template">
    <meta name="keywords" content="Gutim, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gutim | Template</title>

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
    <!-- Page Preloder -->
    <!-- <div id="preloder">
        <div class="loader"></div>
    </div> -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb/classes-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>Ridding</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Map Section Begin -->
    <div class="map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d24112.92132811736!2d-74.20651812810036!3d40.93514309648714!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2fda38587e887%3A0xf03207815e338a0d!2sHaledon%2C%20NJ%2007508%2C%20USA!5e0!3m2!1sen!2sbd!4v1578120776078!5m2!1sen!2sbd"
            height="612" style="border:0;" allowfullscreen=""></iframe>
        <img src="img/icon/location.png" alt="">
    </div>
    <!-- Map Section End -->

    <!-- Contact Section Begin -->
    <section class="contact-section spad">

        <div class="container">

            <div class="contact-form">
                <h4 >Please enter your Parking Lot ID to end your ride :</h4>

                <!-- form section begins-->
                <form id="getscooterIDform" method="POST" action="ridenow.php">
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
    //to retrieve last bookingID and add 1
        require_once 'include/common.php';
        $dao = new BookingDAO();
        $result = $dao->retrieveAll();
        $last_bookingID = $result[count($result)-1];
        $new_bookingIDnumber_int = (int) substr($last_bookingID,1) + 1;
        
        //number of 0s to add
        $new_bookingIDnumber_str = (string) $new_bookingIDnumber_int;
        $numberOfZeroToAdd = 4 - strlen($new_bookingIDnumber_str);
        $bookingID = 'B' . str_repeat("0", $numberOfZeroToAdd) . $new_bookingIDnumber_str;
    //-- get booking id ends --
    
    //-- get startTime begin --
        date_default_timezone_set('Asia/Singapore');
        $startTime = date("Y-m-d H:i:s");
    //-- get startTime end --

    //-- get scooter and parking lot IDs begin--
        if( isset($_POST['scooterID']) && isset($_POST['parkingLotID']) ) {
            $scooterID = $_POST['scooterID'];
            $parkingLotID = $_POST['parkingLotID'];
            $result =  $dao->insertbookingdetails($bookingID,$scooterID,$parkingLotID,$startTime);
        }
    //-- get scooter and parking lot IDs end--
 
    // var_dump($bookingID);
    // var_dump($scooterID);
    // var_dump($parkingLotID);
    // var_dump($startTime);
    // var_dump($result);

    //ONLY NEED TO PASS BOOKING ID AND END TIME

    ?>
    <script>
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
    
    </script>
    <!-- get bookingID, scooterID, parkingLot ID, startTime ends-->

   <!-- Footer Section Begin -->
    <footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="contact-option">
                        <span>Phone</span>
                        <p>(123) 118 9999 - (123) 118 9999</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-option">
                        <span>Address</span>
                        <p>72 Kangnam, 45 Opal Point Suite 391</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-option">
                        <span>Email</span>
                        <p>contactcompany@Gutim.com</p>
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
