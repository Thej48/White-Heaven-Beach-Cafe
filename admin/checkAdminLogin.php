<?php
require "../conn.php";
session_start();

// CHECK LOGIN PROCESS

if (!empty($_POST["email"]) && !empty($_POST["password"])) {

    $email = $_POST["email"];
    $password = md5($_POST["password"]);

    $checkIfUserExists = "SELECT * FROM admin WHERE admin_email='$email' AND admin_password='$password' ";

    $result = mysqli_query($conn, $checkIfUserExists);
    $count = mysqli_num_rows($result);
    $row = mysqli_fetch_array($result);

    if ($count == 1) {
        $role = $row['role'];
        // IF ROLE IS ADMIN
        if ($role == 'Admin') {
            $adminSessionID = session_id();
            $_SESSION['authAdmin'] = $adminSessionID;
            // PASS ADMIN DETAILS
            $_SESSION['adminUserId'] = $row['admin_id'];
            $_SESSION['adminUsername'] = $row['admin_name'];
            $_SESSION['adminEmail'] = $row['admin_email'];
            $_SESSION['adminRole'] = $row['role'];
            $_SESSION['adminPhone'] = $row['admin_phone'];




?>
            <script>
                window.location.href = "./dashboard.php";
            </script>
        <?php
        } else {
            $_SESSION["invalidCredentials"] = "Incorrect Credentials..!";
        ?>
            <script>
                window.location.href = "./index.php";
            </script>
        <?php
        }
    } else {
        $_SESSION["userInvalid"] = "User doesn't exist..!";
        ?>
        <script>
            window.location.href = "./index.php";
        </script>
    <?php
    }
} else {
    $_SESSION["emptyCredentials"] = "Credentials cannot be empty..!";
    ?>
    <script>
        window.location.href = "./index.php";
    </script>
<?php
}


?>