<?php
require './conn.php';
session_start();

$tableNo = $_GET['tableNo'];

if (!isset($_SESSION['AuthEndUser'])) {
?>
    <script>
        window.location.href = "./home.php?tableNo=<?php echo $tableNo ?>";
    </script>
<?php
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./images/logo.png" type="image/x-icon">
    <title>White Heaven Beach Cafe</title>

    <!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="./css/styleHome.css">
    <link rel="stylesheet" href="./font/stylesheet.css">
    <link rel="stylesheet" href="./font/lato_stylesheet.css">
</head>

<body class="flex-column">

    <div class="container py-0 px-0 d-flex flex-column justify-content-between h-100 ">

        <div class="profileContentDiv d-flex flex-column p-2">
            <h1 class="ProfileHeaderTxt fw-bold">Profile</h1>
            <hr class="m-0 p-0">
        </div>

        <div class="profileDiv h-100 overflow-auto p-2 py-3">

            <div class="profileCard bg-body-secondary border border-secondary border-2 rounded px-4 py-4 d-flex flex-column align-items-center">
                <img src="./icons/account_inactive.png" alt="user" class="userProfilePic mb-4">
                <div class="profileDetailsDiv w-100 d-flex flex-column gap-2">
                    <h1 class="h3 m-0 p-0 text-secondary-emphasis"><span class="text-light-emphasis fw-bold">User ID : </span><?= $_SESSION['UserID']; ?></h1>
                    <h1 class="h3 m-0 p-0 text-secondary-emphasis"><span class="text-light-emphasis fw-bold">Name : </span><?= $_SESSION['UserName']; ?></h1>
                    <h1 class="h3 m-0 p-0 text-secondary-emphasis"><span class="text-light-emphasis fw-bold">Phone : </span><?= $_SESSION['UserPhone']; ?></h1>
                    <hr class="m-0 p-0 my-2">
                    <input type="submit" value="Logout" onclick="document.location.href='./logoutEndUser.php'" class="logoutUserBtn h3 fw-bold py-2 lh-base rounded shadow-sm">
                </div>
            </div>

        </div>

        <div class="bottomNavBarDiv rounded-top m-0 p-0 d-flex">
            <ul class="BottomNavMenu p-0 m-0 py-2">
                <li class="BottomMenuItem d-flex flex-column align-items-center justify-content-center " onclick="document.location.href='./menu.php?tableNo=<?php echo $tableNo; ?>'">
                    <img src="./icons/menu_inactive.png" width="20" height="20" alt="menu" id="MenuIcon">
                    <span class="user-select-none" id="MenuText">Menu</span>
                </li>
                <li class="BottomMenuItem d-flex flex-column align-items-center justify-content-center " onclick="document.location.href='./cart.php?tableNo=<?php echo $tableNo; ?>'">
                    <img src="./icons/trolley_inactive.png" width="20" height="20" alt="cart" id="CartIcon">
                    <span class="user-select-none" id="CartText">Cart</span>
                </li>
                <li class="BottomMenuItem d-flex flex-column align-items-center justify-content-center " onclick="document.location.href='./orders.php?tableNo=<?php echo $tableNo; ?>'">
                    <img src="./icons/take-away_inactive.png" width="20" height="20" alt="orders" id="OrderIcon">
                    <span class="user-select-none" id="OrderText">Orders</span>
                </li>
                <li class="BottomMenuItem d-flex flex-column align-items-center justify-content-center " onclick="document.location.href='./profile.php?tableNo=<?php echo $tableNo; ?>'">
                    <img src="./icons/account_inactive.png" width="20" height="20" alt="profile" id="ProfileIcon">
                    <span class="user-select-none" id="ProfileText">Profile</span>
                </li>
            </ul>
        </div>

    </div>



    <!-- ICON ACTIVE CODE -->
    <script>
        document.getElementById('ProfileIcon').src = './icons/account_active.png';
        document.getElementById('ProfileText').style.color = "white";
    </script>



    <script src="./bootstrap/js/bootstrap.bundle.js"></script>
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>