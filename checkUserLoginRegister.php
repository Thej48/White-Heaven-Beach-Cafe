<?php
require "./conn.php";
session_start();

$tableNo = $_GET['tableNo'];

// SIGN-UP PROCESS
if (isset($_POST['userSignUpBtn'])) {
    if (isset($_POST['singUpUserName']) && isset($_POST['singUpUserPhone'])) {
        $singUpUserName = $_POST['singUpUserName'];
        $singUpUserPhone = $_POST['singUpUserPhone'];
        $userID = "WH" . mt_rand(1001, 9999) . "EU";

        $checkForUserID = "SELECT * FROM user WHERE user_id='$userID' ";
        $checkForUserIDRun = mysqli_query($conn, $checkForUserID);
        if ($checkForUserIDRun->num_rows >= 1) {
            $userID = "WH" . mt_rand(1001, 9999) . "EU";
        }

        $checkForPhone = "SELECT * FROM user WHERE user_phone='$singUpUserPhone' ";
        $checkForPhoneRun = mysqli_query($conn, $checkForPhone);
        if ($checkForPhoneRun->num_rows >= 1) {
            $_SESSION['UserWithPhoneNumberExists'] = "User with $singUpUserPhone already exists.";
?>
            <script>
                window.location.href = './home.php?tableNo=<?php echo $tableNo ?>&openSignUpDialog=true'
            </script>
            <?php
        } else {
            $registerUser = "INSERT INTO user(user_id,username,user_phone) VALUES('$userID','$singUpUserName','$singUpUserPhone')";
            $registerUserRun = mysqli_query($conn, $registerUser);
            if ($registerUserRun) {
                $_SESSION['UserSignUpSuccess'] = "User registered successfully..!"; ?>
                <script>
                    window.location.href = './home.php?tableNo=<?php echo $tableNo ?>&openSignInDialog=true';
                </script>
            <?php
            } else {
                $_SESSION['UserSignUpFailed'] = "User registration failed..!"; ?>
                <script>
                    window.location.href = './home.php?tableNo=<?php echo $tableNo ?>&openSignUpDialog=true';
                </script>
            <?php
            }
        }
    }
}


// SIGN-IN PROCESS
if (isset($_POST['userSignInBtn'])) {
    if (isset($_POST['signInUserName']) && isset($_POST['signInUserPhone'])) {
        $signInUserName = $_POST['signInUserName'];
        $signInUserPhone = $_POST['signInUserPhone'];

        $checkForUser = "SELECT * FROM user WHERE username='$signInUserName' AND user_phone='$signInUserPhone' ";
        $checkForUserRun = mysqli_query($conn, $checkForUser);
        $checkUserCount = mysqli_num_rows($checkForUserRun);
        $data = mysqli_fetch_array($checkForUserRun);

        if ($checkUserCount == 1) {
            // Generate md5 hash for the user_id
            $userIdMd5Hash = md5($data['user_id']);

            // Set md5 hash as the session_id
            session_id($userIdMd5Hash);
            // PASS USER DETAILS
            $_SESSION['AuthEndUser'] = $userIdMd5Hash;
            $_SESSION['UserID'] = $data['user_id'];
            $_SESSION['UserName'] = $data['username'];
            $_SESSION['UserPhone'] = $data['user_phone'];
            ?>
            <script>
                window.location.href = './menu.php?tableNo=<?php echo $tableNo ?>';
            </script>
        <?php
        } else {
            $_SESSION['InvalidCredentials'] = "Invalid Credentials..!"; ?>
            <script>
                window.location.href = './home.php?tableNo=<?php echo $tableNo ?>&openSignInDialog=true';
            </script>
        <?php
        }
    } else {
        $_SESSION['EmptyCredentials'] = "Credentials cannot be empty..!"; ?>
        <script>
            window.location.href = './home.php?tableNo=<?php echo $tableNo ?>&openSignInDialog=true';
        </script>
<?php
    }
}
