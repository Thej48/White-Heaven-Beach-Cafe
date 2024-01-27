<?php
require "./conn.php";
session_start();

$tableNo = $_GET['tableNo'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./images/logo.png" type="image/x-icon">
    <title>White Heaven Beach Cafe</title>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="./css/styleHome.css">
    <link rel="stylesheet" href="./font/stylesheet.css">
    <link rel="stylesheet" href="./font/lato_stylesheet.css">
</head>

<body>
    <div class="homeContentBox w-100 h-100">

        <div class="homeContentDiv container py-3 w-100 h-100 d-flex flex-column">
            <div class="companyLogoDiv sticky-top">
                <img src="./images/logo_transparent.png" alt="White Heaven Beach Cafe" class="companyLogo" height="90" width="auto">
            </div>
            <div class="welcomeMsgDiv mt-5 pt-5 ">
                <h1 class="p-0 m-0 h1 fw-bold">Welcome to,</h1>
                <h1 class="p-0 m-0 display-1 brandName brandName1">White Heaven</h1>
                <h1 class="p-0 m-0 display-1 brandName brandName2">Beach Cafe</h1>
                <h1 class="p-0 m-0 h4 slogan">"Where Culinary Adventure Awaits"</h1>
                <button type="submit" class="rounded-1 my-4 py-2 px-5 fs-5 shadow-sm text-white getStartedBtn" onclick="loginFormModal.showModal()">&nbsp;&nbsp;Get Started&nbsp;&nbsp;</button>
            </div>
        </div>

    </div>

    <dialog class="loginFormModal rounded" id="loginFormModal">
        <div class="loginFormModalDiv p-3 bg-warning">
            <div class="loginFormHeader d-flex align-items-center justify-content-between">
                <h1 class="m-0 p-0"></h1>
                <h1 class="m-0 p-0">Sign In</h1>
                <h1 class="m-0 p-0 h1 loginFormModalCloseIcon text-danger" onclick="loginFormModal.close()">&Cross;</h1>
            </div>
        </div>
    </dialog>

    <script src="./bootstrap/js/bootstrap.bundle.js"></script>
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>