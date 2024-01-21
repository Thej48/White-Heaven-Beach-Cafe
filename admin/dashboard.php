<?php
require "../conn.php";
session_start();

if (!isset($_SESSION['authAdmin'])) {
?>
    <script>
        window.location.href = "./index.php";
    </script>
<?php
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <title>White Heaven Beach Cafe</title>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/styleAdminDashboard.css">
    <link rel="stylesheet" href="../font/stylesheet.css">
    <link rel="stylesheet" href="../font/lato_stylesheet.css">
</head>

<body>

    <div class="container-fluid">

        <div class="row">

            <div class="col-sm-2 d-flex justify-content-between py-4 bg-white sideNavBar">
                <img src="../images/logo_transparent.png" alt="White Heaven Beach Cafe" class="brandLogoImg d-flex align-self-center justify-content-center">

                <div class="navLinksDiv d-flex bg-secondary">
                    <ul>
                        <li><a href="http://">Dashboard</a></li>
                        <li><a href="http://">Dashboard</a></li>
                        <li><a href="http://">Dashboard</a></li>
                        <li><a href="http://">Dashboard</a></li>
                        <li><a href="http://">Dashboard</a></li>
                    </ul>
                </div>

                <?php
                $adminName = $_SESSION['adminUsername'];
                $adminRole = $_SESSION['adminRole'];
                $admin_id = $_SESSION['adminUserId'];

                $queryGetProfilePic = "SELECT * FROM admin WHERE admin_id='$admin_id'";
                $result = mysqli_query($conn, $queryGetProfilePic);
                $row = mysqli_fetch_array($result);
                $photo = base64_encode($row['admin_photo']);
                ?>

                <div class="profileSection d-flex flex-column container bg-secondary">
                    <div class="row">
                        <?php echo '<img src="data:image;base64,' . $photo . '" alt="Profile Picture" class="col-sm-4 bg-success">'; ?>
                        <div class="row col-sm-8 d-flex align-self-center justify-content-center">
                            <span class=""><?php echo "$adminName"; ?></span>
                            <span class=""><?php echo "$adminRole"; ?></span>
                        </div>
                    </div>
                    <button class="">logout</button>
                </div>
            </div>

            <div class="col-sm-10 d-flex bg-success dashboardBodyDiv">
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Perspiciatis, ipsam.
            </div>

        </div>

    </div>

</body>

</html>