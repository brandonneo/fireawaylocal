<?php
session_start();
if (isset($_SESSION['is_logged_in'])) {
    // Block users from going forget password portion should they be logged in
    if ($_SESSION['is_logged_in'] == true) {
        header('Location: dashboard');
    }
}

require ('db_mapper_factory.php');

// If token code in URL is empty return "Can't find page"
if(!isset($_GET["token"])) {
    header('Location: error404');
}

$token = trim($_GET["token"]);
// get the object
$dbMapperFactory = new DBMapperFactory();
$token_mapper_instance = $dbMapperFactory->createMapperInstance("Token");
$conn = $token_mapper_instance->readFile();
$tokenExist = $token_mapper_instance->checkToken($conn, "Token", $token);
if (!$tokenExist) {
    header('Location: error404');
}
?>

<!DOCTYPE html>
<html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <link rel="icon" href="img/fire_icon.png">
    	<!-- meta charec set -->
        <meta charset="utf-8">
		<!-- Always force latest IE rendering engine or request Chrome Frame -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<!-- Page Title -->
        <title>Reset Password</title>
		<!-- Meta Description -->
        <meta name="description" content="Blue One Page Creative HTML5 Template">
        <meta name="keywords" content="one page, single page, onepage, responsive, parallax, creative, business, html5, css3, css3 animation">
        <meta name="author" content="Muhammad Morshed">
		<!-- Mobile Specific Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Google Font -->

		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>

		<!-- CSS
		================================================== -->
		<!-- Fontawesome Icon font -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
		<!-- Twitter Bootstrap css -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- jquery.fancybox  -->
        <link rel="stylesheet" href="css/jquery.fancybox.css">
		<!-- animate -->
        <link rel="stylesheet" href="css/animate.css">
		<!-- Main Stylesheet -->
        <link rel="stylesheet" href="css/main.css">
		<!-- media-queries -->
        <link rel="stylesheet" href="css/media-queries.css">

		<!-- Modernizer Script for old Browsers -->
        <script src="js/modernizr-2.6.2.min.js"></script>

    </head>

    <body id="body">

        <div id="preloader">
			<img src="img/preloader.gif" alt="Preloader">
		</div>

        <!--
        Fixed Navigation
        ==================================== -->
        <?php include('header.php'); ?>

        <section id="password">
            <div class="container">
                <div id="password-row" class="row justify-content-center">
                    <div id="password-column" class="col-md-6">
                        <div id="password-box" class="col-md-12">
                          <!---  <form id="password-form" class="form" action="" method="post" onsubmit="return validateForgetPassword('<?php //echo $token ?>')">---->
                                <h3 class="text-center text-danger">Reset Password</h3>
                                <div class="form-group">
                                    <label for="password" class="text-danger">Password:</label><br>
                                    <input type="password" id="resetPassword1" name="password" placeholder="Enter a new password" class="form-control">
                                    <span class="error" id="errorforgetPass1"></span>
                                </div>
                                <div class="form-group">
                                    <label for="repassword" class="text-danger">Re-enter Password:</label><br>
                                    <input type="password" id="resetPassword2" name="repassword" placeholder="Re-enter the new password" class="form-control">
                                    <span class="error" id="errorforgetPass2"></span>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submit" class="btn btn-success btn-md" onclick="validateForgetPassword('<?php echo $token ?>')" value="Submit">
                                </div>
                                <div id="back-button" class="text-right">
                                    <button id="btnBack" type="button" class="btn btn-danger btn-md" data-dismiss="modal">Back To Login</button>
                                </div>
                            <!---</form>--->
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include('footer.php'); ?>

        <!-- Essential jQuery Plugins
		================================================== -->
		<!-- Main jQuery -->
        <script src="js/jquery-1.11.1.min.js"></script>

        <!-- Contact form validation -->
        <script type="text/javascript" src="js/bootstrap.min.js"></script>

        <!-- OTP Functions -->
        <script type="text/javascript" src="js/otp.js"></script>

        <script type="text/javascript" src="js/header.js"></script>

        <!-- Custom Functions -->
        <script src="js/custom.js"></script>

        <!-- validate Functions -->
        <script src="js/validationInput.js"></script>
    </body>
</html>
