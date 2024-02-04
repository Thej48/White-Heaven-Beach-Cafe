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



<?php
// Use user-specific cart session key
$userId = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;
$cartKey = ($userId) ? 'cart_' . $userId : '';

if (isset($_POST['AddToCartBtn'])) {
    if (isset($_SESSION[$cartKey])) {
        $session_array_id = array_column($_SESSION[$cartKey], "id");

        if (!in_array($_GET['id'], $session_array_id)) {
            $session_array = array(
                'id' => $_GET['id'],
                "food_name" => $_POST['FoodItemName'],
                "category_name" => $_POST['FoodItemCategory'],
                "price" => $_POST['FoodItemPrice'],
                "quantity" => $_POST['FoodItemQuantity'],
                "total_price" => $_POST['FoodItemTotalPrice']
            );

            $_SESSION[$cartKey][] = $session_array;
        }
    } else {
        $session_array = array(
            'id' => $_GET['id'],
            "food_name" => $_POST['FoodItemName'],
            "category_name" => $_POST['FoodItemCategory'],
            "price" => $_POST['FoodItemPrice'],
            "quantity" => $_POST['FoodItemQuantity'],
            "total_price" => $_POST['FoodItemTotalPrice']
        );

        $_SESSION[$cartKey][] = $session_array;
    }
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
            <h1 class="fw-bold display-6">Food Cart</h1>
            <hr class="p-0 m-0">
        </div>

        <div class="CartItemCardsDiv h-100 overflow-auto border border-danger p-2 ">

            <?php
            $OutPut = "";

            // Get the user's ID and use it to form the cart session key
            $userId = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;
            $cartKey = ($userId) ? 'cart_' . $userId : '';

            // Check if the user-specific cart session variable exists
            if ($cartKey && !empty($_SESSION[$cartKey])) {
                foreach ($_SESSION[$cartKey] as $key => $value) {
                    $foodItemId = $value['id'];
                    $getImage = "SELECT * FROM food_item WHERE id='$foodItemId' ";
                    $getImageRun = mysqli_query($conn, $getImage);
                    $row = $getImageRun->fetch_assoc();
                    $foodImage = base64_decode($row['food_image']);
                    $OutPut .= "
                <div class='CartItemCard border border-info my-1  py-2 d-flex align-items-center gap-2'>
                    <img src='data:image;base64," . base64_encode($foodImage) . "' alt='food_image' class='CartFoodImg rounded'>
                    <div class='CartItemInfoDiv d-flex flex-column  gap-1 bg-info-subtle'>
                            <h1 class='fs-5 p-0 m-0 fw-bold'>" . $value['food_name'] . "</h1>
                            <h1 class='fs-6 p-0 m-0 fw-medium'>" . $value['category_name'] . "</h1>
                            <div class='cartItemQuantityControlDiv rounded gap-2 p-0 m-0 d-flex align-items-center bg-secondary-subtle'>
                                <button class='MinusBtnCart p-2 border border-secondary rounded m-0'>
                                    <img src='./icons/minus.png' alt='minus' class='MinusIconCart'/>
                                </button>
                                <h1 class='CartItemQualityCount h4 p-0 m-0' id='CartItemQualityCount'>" . $value['quantity'] . "</h1>
                                <button class='PlusBtnCart p-2 border border-secondary rounded m-0'>
                                    <img src='./icons/plus.png' alt='plus' class='PlusIconCart'/>
                                </button>
                            </div>
                            <h1 class='fs-5 p-0 m-0 fw-medium' hidden>₹ " . number_format($value['price'], 2) . "</h1>
                            <h1 class='fs-5 p-0 m-0 fw-medium'>₹ " . number_format($value['total_price'], 2) . "</h1>
                    </div>
                </div>
            ";
                }
            }

            echo $OutPut;
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
        document.getElementById('CartIcon').src = './icons/trolley_active.png';
        document.getElementById('CartText').style.color = "white";
    </script>



    <script src="./bootstrap/js/bootstrap.bundle.js"></script>
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>