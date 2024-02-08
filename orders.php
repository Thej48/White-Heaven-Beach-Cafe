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

        <div class="cartHeaderDiv d-flex flex-column p-2">
            <h1 class="fw-bold display-6">Order History</h1>
            <hr class="p-0 m-0">
        </div>

        <div class="CartItemCardsDiv h-100 overflow-auto py-2 bg-secondary-subtle ">
            <?php
            $userID = $_SESSION['UserID'];
            $getOrdersList = "SELECT * FROM orders WHERE user_id='$userID' ORDER BY id DESC";
            $getOrdersListRun = mysqli_query($conn, $getOrdersList);
            if ($getOrdersListRun->num_rows > 0) {
                while ($OrderRow = $getOrdersListRun->fetch_assoc()) { ?>
                    <div class="card p-2 m-2 shadow-sm">

                        <h1 class="h6 fw-bold">Order ID : <span class="fs-6 fw-medium"><?= $OrderRow['order_id']; ?></span></h1>
                        <?php
                        $OrderEntryID = $OrderRow['id'];
                        $getOrderItems = "SELECT * FROM order_items WHERE order_id='$OrderEntryID' ORDER BY id DESC";
                        $getOrderItemsRun = mysqli_query($conn, $getOrderItems);
                        if ($getOrderItemsRun->num_rows > 0) {
                            while ($OrdItemRow = $getOrderItemsRun->fetch_assoc()) { ?>
                                <h1 class="h6"><?= $OrdItemRow['food_item_name']; ?></h1>
                                <h1 class="h6"><?= $OrdItemRow['quantity']; ?></h1>
                        <?php
                            }
                        } ?>
                    </div>
            <?php
                }
            }
            ?>
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
        document.getElementById('OrderIcon').src = './icons/take-away_active.png';
        document.getElementById('OrderText').style.color = "white";
    </script>



    <script src="./bootstrap/js/bootstrap.bundle.js"></script>
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>