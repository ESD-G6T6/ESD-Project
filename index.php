<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>

    <!-- Header Section Begin -->
    <header class="header-section"  id="myHeader">
        <!-- <header class="header" id="myHeader"> -->
        <div class="container">
            <div class="logo">
                <a href="./index.php">
                    <img src="img/scooterlogo.png" alt="">
                </a>
            </div>
            <div id="mobile-menu-wrap"></div>
        </div>
    </header>

    <!-- Header End -->

    <!-- Auto scrolling function -->
    <script>
        window.onscroll = function() {myFunction()};
        
        var header = document.getElementById("myHeader");
        var sticky = header.offsetTop;
        
        function myFunction() {
          if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
          } else {
            header.classList.remove("sticky");
          }
        }
    </script>
    <!-- End of Auto scrolling function -->

    <!-- Hero Section Begin -->
    <section class="hero-section set-bg" data-setbg="img/scooterlanding.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="hero-text">
                        <span>OOTER</span>
                        <h1>Your Ride - Electrified</h1>

                        <a href="./ridenow.php" class="primary-btn signup-btn">Ride now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- About Section Begin -->
    <section class="about-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-pic">
                        <img src="img/about-pic.jpeg" alt="">
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="about-text">
                        <h2>Story About Us</h2>
                        <p class="first-para"> Good things happen when people can move, whether across town or toward their dreams. Opportunities appear, open up and become reality.
                            What started as a way to tap a button to get a ride has led to billions of moments of human connection 
                            as people around the world go to all kinds of places with the help of our technology.
                            </p> <br>
                        <p class="second-para"> With technology at the heart of our approach, we are committed to doing our part - giving riders a way to get from point A to point B.
                            We partner with safety advocates and develop new technologies and systems to help improve safety and help make it easier for everyone to get around.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section End -->

    <!-- Services Section Begin -->
    <section class="services-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="services-pic">
                        <img src="img/services/service-pic.png" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service-items">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="services-item bg-gray">
                                    <h4>A new way to ride with Ooter</h4>
                                    <p>----</p>
                                </div>
                                <div class="services-item bg-gray pd-b">
                                    <img src="img/services/two.png" alt="">
                                    <h4>Start Riding</h4>
                                    <p>Select your desired e-scooter and key in its unique ID in the Ooter rental webpage,
                                        put on a helmet and go.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="services-item">
                                    <img src="img/services/one.png" alt="">
                                    <h4>Find Your Ride</h4>
                                    <p>Open the Ooter website on your prefered browser,
                                        find a nearby e-scooter parking lot with available e-scooters.</p>
                                </div>
                                <div class="services-item pd-b">
                                    <img src="img/services/three.png" alt="">
                                    <h4>End Your Ride</h4>
                                    <p>Find the nearest e-scooter parking lot to your destination.
                                        Key in the parking lot number in the Ooter rental webpage to end your ride.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Section End -->


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