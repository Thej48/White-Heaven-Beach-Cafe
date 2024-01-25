<?php
require "../conn.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <title>White Heaven Beach Cafe</title>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/styleAdminLogin.css">
    <link rel="stylesheet" href="../font/stylesheet.css">
    <link rel="stylesheet" href="../font/lato_stylesheet.css">
</head>

<body>

    <div class="container ">

        <div class="row">

            <div class="col-sm-7 loginPageImgDiv d-flex">
                <img src="../images/logo_transparent.png" alt="White Heaven Beach Cafe" class="img-fluid loginPageLogo">
            </div>

            <div class="col-sm-5 d-flex p-2 loginFormDivContainer">
                <div class="container-fluid d-flex rounded loginFormDiv">
                    <h1 class="display-5 formHeader">Sign In</h1>
                    <hr width="85%">
                    <!-- ERROR DIV -->
                    <div class="errorDiv">
                        <!-- ERROR MESSAGE IF INPUT CREDENTIALS INVALID -->
                        <?php
                        if (isset($_SESSION["invalidCredentials"])) {
                        ?>
                            <div class="msgFailureDiv">
                                <?php echo $_SESSION["invalidCredentials"];
                                unset($_SESSION["invalidCredentials"]); ?>
                            </div>
                        <?php
                        }
                        ?>
                        <!-- ERROR MESSAGE IF INPUT CREDENTIALS ARE EMPTY -->
                        <?php
                        if (isset($_SESSION["emptyCredentials"])) {
                        ?>
                            <div class="msgFailureDiv">
                                <?php echo $_SESSION["emptyCredentials"];
                                unset($_SESSION["emptyCredentials"]); ?>
                            </div>
                        <?php
                        }
                        ?>
                        <!-- ERROR MESSAGE IF USER DOESN'T EXIST -->
                        <?php
                        if (isset($_SESSION["userInvalid"])) {
                        ?>
                            <div class="msgFailureDiv">
                                <?php echo $_SESSION["userInvalid"];
                                unset($_SESSION["userInvalid"]); ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <form method="POST" action="./checkAdminLogin.php" class="d-flex">
                        <input type="email" name="email" id="email" placeholder="Enter Email Address" class="rounded" required>
                        <input type="password" name="password" id="password" placeholder="Enter Password" class="rounded" required>
                        <input type="submit" value="Sign In" class="signInBtn rounded">
                    </form>
                    <span class="forgotPassword"><a href="">Forgot Password ?</a></span>
                    <hr width="85%">
                </div>
            </div>

        </div>

    </div>

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>



</body>

</html>