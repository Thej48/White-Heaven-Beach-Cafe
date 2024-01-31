<?php
require "./conn.php";
session_start();

$tableNo = $_GET['tableNo'];

$_SESSION['tableNo'] = $tableNo;

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
        <div class="loginFormModalDiv px-4 py-4">
            <div class="loginFormHeader d-flex align-items-top justify-content-between p-0 m-0">
                <h1 class="m-0 p-0">&nbsp;</h1>
                <h1 class="m-0 p-0 display-2 fw-bold text-secondary-emphasis">Sign In</h1>
                <h1 class="m-0 p-0 fs-1 loginFormModalCloseIcon text-danger" onclick="loginFormModal.close()">&Cross;</h1>
            </div>
            <hr class="my-2">
            <!-- USER SIGN-UP SUCCESS -->
            <?php if (isset($_SESSION['UserSignUpSuccess'])) { ?>
                <div class="bg-success-subtle p-0 m-0 text-center border border-success rounded-1 text-success-emphasis" id="successMessage">
                    <span class="p-0 m-0 fs-6 fw-bold d-flex gap-1 align-items-center justify-content-center py-1 px-1">
                        <img src="./icons/tick-mark.png" alt="correct" height="25" width="auto" class="successMsgIcon">
                        <?php echo $_SESSION['UserSignUpSuccess']; ?>
                    </span>
                </div>
                <script>
                    // Automatically unset the session after 3 seconds
                    setTimeout(function() {
                        var successMessage = document.getElementById('successMessage');
                        successMessage.parentNode.removeChild(successMessage);
                        <?php unset($_SESSION['UserSignUpSuccess']); ?>
                    }, 3000);
                </script>
            <?php } ?>
            <!-- USER LOGIN WITH INVALID CREDENTIALS -->
            <?php if (isset($_SESSION['InvalidCredentials'])) { ?>
                <div class="bg-danger-subtle p-0 m-0 text-center border border-danger rounded-1 text-danger-emphasis" id="failureMessage">
                    <span class="p-0 m-0 fs-6 fw-bold d-flex gap-2 align-items-center justify-content-center py-1 px-1">
                        <img src="./icons/warning.png" alt="correct" height="20" width="auto" class="FailureMsgIcon">
                        <?php echo $_SESSION['InvalidCredentials']; ?>
                    </span>
                </div>
                <script>
                    // Automatically unset the session after 3 seconds
                    setTimeout(function() {
                        var failureMessage = document.getElementById('failureMessage');
                        failureMessage.parentNode.removeChild(failureMessage);
                        <?php unset($_SESSION['InvalidCredentials']); ?>
                    }, 3000);
                </script>
            <?php } ?>
            <!-- USER LOGIN WITH EMPTY CREDENTIALS -->
            <?php if (isset($_SESSION['EmptyCredentials'])) { ?>
                <div class="bg-danger-subtle p-0 m-0 text-center border border-danger rounded-1 text-danger-emphasis" id="failureMessage">
                    <span class="p-0 m-0 fs-6 fw-bold d-flex gap-2 align-items-center justify-content-center py-1 px-1">
                        <img src="./icons/warning.png" alt="correct" height="20" width="auto" class="FailureMsgIcon">
                        <?php echo $_SESSION['EmptyCredentials']; ?>
                    </span>
                </div>
                <script>
                    // Automatically unset the session after 3 seconds
                    setTimeout(function() {
                        var failureMessage = document.getElementById('failureMessage');
                        failureMessage.parentNode.removeChild(failureMessage);
                        <?php unset($_SESSION['EmptyCredentials']); ?>
                    }, 3000);
                </script>
            <?php } ?>
            <form action="./checkUserLoginRegister.php?tableNo=<?php echo $tableNo ?>" method="post" class="d-flex flex-column ">
                <input type="text" name="signInUserName" id="signInUserName" class="w-100 rounded-1 my-2 py-2 px-3 signInUserName" placeholder="Enter Name" required>
                <input type="tel" name="signInUserPhone" id="signInUserPhone" class="w-100 rounded-1 my-2 py-2 px-3 signInUserPhone" pattern="[0-9]{10}" maxlength="15" placeholder="Enter Phone Number" required>
                <input type="submit" name="userSignInBtn" value="Sign In" class="w-100 rounded-1 my-2 py-2 px-3 signInBtn">
                <span class="text-center h6 mt-3 text-body-secondary">Don't have an account? <span class="fw-bold changeModalTxt text-secondary-emphasis" onclick="openSignUpDialog()">Sign Up</span></span>
            </form>
        </div>
    </dialog>

    <dialog class="signUpFormModal rounded" id="signUpFormModal">
        <div class="signUpFormModalDiv px-4 py-4">
            <div class="loginFormHeader d-flex align-items-top justify-content-between p-0 m-0">
                <h1 class="m-0 p-0">&nbsp;</h1>
                <h1 class="m-0 p-0 display-2 fw-bold text-secondary-emphasis">Sign Up</h1>
                <h1 class="m-0 p-0 fs-1 signUpFormModalCloseIcon text-danger" onclick="signUpFormModal.close()">&Cross;</h1>
            </div>
            <hr class="my-2">
            <!-- USER WITH PHONE NUMBER ALREADY EXISTS -->
            <?php if (isset($_SESSION['UserWithPhoneNumberExists'])) { ?>
                <div class="bg-danger-subtle p-0 m-0 text-center border border-danger rounded-1 text-danger-emphasis" id="failureMessage">
                    <span class="p-0 m-0 fs-6 fw-bold d-flex gap-2 align-items-center justify-content-center py-1 px-1">
                        <img src="./icons/warning.png" alt="correct" height="20" width="auto" class="FailureMsgIcon">
                        <?php echo $_SESSION['UserWithPhoneNumberExists']; ?>
                    </span>
                </div>
                <script>
                    // Automatically unset the session after 3 seconds
                    setTimeout(function() {
                        var failureMessage = document.getElementById('failureMessage');
                        failureMessage.parentNode.removeChild(failureMessage);
                        <?php unset($_SESSION['UserWithPhoneNumberExists']); ?>
                    }, 3000);
                </script>
            <?php } ?>
            <!-- USER REGISTRATION FAILED -->
            <?php if (isset($_SESSION['UserSignUpFailed'])) { ?>
                <div class="bg-danger-subtle p-0 m-0 text-center border border-danger rounded-1 text-danger-emphasis" id="failureMessage">
                    <span class="p-0 m-0 fs-6 fw-bold d-flex gap-2 align-items-center justify-content-center py-1 px-1">
                        <img src="./icons/warning.png" alt="correct" height="20" width="auto" class="FailureMsgIcon">
                        <?php echo $_SESSION['UserSignUpFailed']; ?>
                    </span>
                </div>
                <script>
                    // Automatically unset the session after 3 seconds
                    setTimeout(function() {
                        var failureMessage = document.getElementById('failureMessage');
                        failureMessage.parentNode.removeChild(failureMessage);
                        <?php unset($_SESSION['UserSignUpFailed']); ?>
                    }, 3000);
                </script>
            <?php } ?>
            <form action="./checkUserLoginRegister.php?tableNo=<?php echo $tableNo ?>" method="post" class="d-flex flex-column ">
                <input type="text" name="singUpUserName" id="singUpUserName" class="w-100 rounded-1 my-2 py-2 px-3 singUpUserName" placeholder="Enter Name" required>
                <input type="tel" name="singUpUserPhone" id="singUpUserPhone" class="w-100 rounded-1 my-2 py-2 px-3 singUpUserPhone" pattern="[0-9]{10}" maxlength="15" placeholder="Enter Phone Number" required>
                <input type="submit" name="userSignUpBtn" value="Sign Up" class="w-100 rounded-1 my-2 py-2 px-3 signUpBtn">
                <span class="text-center h6 mt-3 text-body-secondary">Already have an account? <span class="fw-bold changeModalTxt text-secondary-emphasis" onclick="openSignInDialog()">Sign In</span></span>
            </form>
        </div>
    </dialog>

    <script>
        function openSignUpDialog() {
            loginFormModal.close();
            signUpFormModal.showModal();
        }

        function openSignInDialog() {
            signUpFormModal.close();
            loginFormModal.showModal();
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const openSignUpDialog = urlParams.get('openSignUpDialog');

            if (openSignUpDialog === 'true') {
                const signUpFormModal = document.getElementById('signUpFormModal');
                signUpFormModal.showModal();
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const openSignInDialog = urlParams.get('openSignInDialog');

            if (openSignInDialog === 'true') {
                const loginFormModal = document.getElementById('loginFormModal');
                loginFormModal.showModal();
            }
        });
    </script>

    <script src="./bootstrap/js/bootstrap.bundle.js"></script>
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>



</body>

</html>