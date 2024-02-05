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
            <h1 class="fw-bold display-6">Food Cart</h1>
            <hr class="p-0 m-0">
        </div>

        <div class="CartItemCardsDiv h-100 overflow-auto p-2 ">

            <?php
            $TotalAmount = 0;

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
                            <div class='CartItemCard border border-secondary my-2 p-2 rounded-1 d-flex gap-3' data-food-id='" . $foodItemId . "'>
                                <img src='data:image;base64," . base64_encode($foodImage) . "' alt='food_image' class='CartFoodImg rounded-1 border border-secondary-subtle'>
                                <div class='CartItemInfoDiv w-100 d-flex flex-column justify-content-between h-auto'>
                                    <div class=''>
                                        <h1 class='fs-6 p-0 m-0 fw-bold'>" . $value['food_name'] . "</h1>
                                        <h1 class='fs-6 p-0 m-0 fw-medium'>" . $value['category_name'] . "</h1>
                                    </div>    
                                    <div class='d-flex align-items-center justify-content-between w-100'>
                                        <div class='cartItemQuantityControlDiv border border-secondary-subtle rounded gap-2 p-0 m-0 d-flex align-items-center'>
                                            <button name='MinusBtn' class='MinusBtnCart p-2 rounded m-0 quantity-control-btn'>
                                                <img src='./icons/minus.png' alt='minus' class='MinusIconCart'/>
                                            </button>
                                            <h1 class='CartItemQualityCount h5 fw-bold text-secondary-emphasis p-0 m-0' id='CartItemQualityCount'>" . $value['quantity'] . "</h1>
                                            <button class='PlusBtnCart p-2 rounded m-0 quantity-control-btn'>
                                                <img src='./icons/plus.png' alt='plus' class='PlusIconCart'/>
                                            </button>
                                        </div>
                                        <h1 class='foodPriceCart fs-5 p-0 m-0 fw-medium' id='foodPriceCart' hidden>" . number_format($value['price'], 2) . "</h1>
                                        <h1 class='foodTotalPriceCart fs-5 p-0 m-0 fw-bold total-price-element' id='foodTotalPriceCart'>₹ " . number_format($value['total_price'], 2) . "</h1>
                                    </div>
                                    <button onclick='document.location.href=\"./cart.php?tableNo=" . $tableNo . "&action=remove&id=" . $value['id'] . "\"' class='removeFromCartBtn d-flex align-items-center justify-content-center gap-1 shadow-sm p-1 lh-base fs-6 fw-medium rounded'>
                                        <img src='./icons/trash.png' alt='remove' class='trashCanIconCart' /> Remove
                                    </button>
                                </div>
                            </div>
                    ";
                }
            } else {
                $OutPut .= ' 
                <div class="px-3 h-100 overflow-hidden d-flex flex-column align-items-center justify-content-center">
                    <h1 class="h2 fw-bold text-dark-emphasis text-decoration-underline">Nothing to Show</h1>
                    <p class="h6 w-100 lh-base text-dark-emphasis text-center">We\'re sorry, It\'s possible that there is no data to display at the moment. Please check back later.</p>
                </div> ';
                $disableCheckoutBtn = true;
            ?>

            <?php
            }

            echo $OutPut;
            ?>

        </div>

        <div class="totalAmountDivCart bg-white shadow-sm border-top border-secondary py-2 px-3 d-flex align-items-center">
            <h1 class="TotalAmountCartTitle fs-3 text-secondary-emphasis fw-bold w-50 m-0 p-0"><span id='totalAmount'></span></h1>
            <form action="./cart.php?tableNo=<?= $tableNo; ?>" method="POST" class="p-0 m-0 w-50">
                <input type="submit" name="PlaceOrderBtn" class="w-100 rounded-1 p-1 fw-bold fs-5 cartCheckoutBtn" <?php if (isset($disableCheckoutBtn) && $disableCheckoutBtn) echo ' disabled'; ?> id="cartCheckoutBtn" value="Place Order">
            </form>
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



    <!-- ORDER-FOOD CODE -->
    <?php
    if (isset($_POST['PlaceOrderBtn'])) {
        if ($cartKey && !empty($_SESSION[$cartKey])) {
            // Calculate total amount
            $TotalOrderAmount = 0;
            foreach ($_SESSION[$cartKey] as $key => $value) {
                $TotalOrderAmount += $value['total_price'];
            }
            // Generate Custom Order ID
            $UserId = $_SESSION['UserID'];
            date_default_timezone_set('Asia/Kolkata');
            $GetDate = date('dmy');
            $CleanUID = str_replace(['WH', 'EU'], '', $UserId);
            $UniqueOrderCode = mt_rand(10000000, 99999999);
            $CustomOrderID = "OD" . $GetDate . $CleanUID . $UniqueOrderCode;
            // Insert order details into the 'orders' table
            $InsertOrderQuery = "INSERT INTO orders (user_id, order_id, total_amount) VALUES ('$UserId', '$CustomOrderID', '$TotalOrderAmount')";
            $InsertOrderResult = mysqli_query($conn, $InsertOrderQuery);
            if ($InsertOrderResult) {
                // Retrieve the order ID of the inserted order
                $OrderId = mysqli_insert_id($conn);
                // Insert individual items into another table (order_items)
                foreach ($_SESSION[$cartKey] as $key => $value) {
                    $foodItemId = $value['id'];
                    $foodItemName = $value['food_name'];
                    $quantity = $value['quantity'];
                    $price = $value['price'];

                    $insertOrderItemQuery = "INSERT INTO order_items (order_id, food_item_id, food_item_name, quantity, price) VALUES ('$OrderId', '$foodItemId', '$foodItemName', '$quantity', '$price')";
                    mysqli_query($conn, $insertOrderItemQuery);
                }

                // Clear the cart after successful order placement
                unset($_SESSION[$cartKey]);
    ?>
                <script>
                    window.location.href = "./cart.php?tableNo=<?= $tableNo; ?>";
                </script>
    <?php
            }
        }
    }
    ?>



    <?php
    if (isset($_GET['action'])) {
        if ($_GET['action'] == "remove") {

            foreach ($_SESSION[$cartKey] as $key => $value) {
                if ($value['id'] == $_GET['id']) {
                    unset($_SESSION[$cartKey][$key]);
    ?>
                    <script>
                        window.location.href = "./cart.php?tableNo=<?= $tableNo; ?>";
                    </script>
    <?php
                }
            }
        }
    }
    ?>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateTotalAmount() {
                var totalPriceElements = document.getElementsByClassName('total-price-element');
                var totalAmount = 0;

                for (var i = 0; i < totalPriceElements.length; i++) {
                    var price = parseFloat(totalPriceElements[i].innerText.replace('₹', '').replace(',', '').trim());
                    totalAmount += price;
                }

                document.getElementById('totalAmount').textContent = '₹ ' + totalAmount.toFixed(2);
            }

            // Initial update
            updateTotalAmount();

            // Add event listeners to quantity control buttons
            var quantityControlButtons = document.getElementsByClassName('quantity-control-btn');

            for (var i = 0; i < quantityControlButtons.length; i++) {
                quantityControlButtons[i].addEventListener('click', function() {
                    // Delay the update slightly to allow the quantity to update
                    setTimeout(updateTotalAmount, 50);
                });
            }
        });
    </script>




    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get the elements
            var plusBtns = document.querySelectorAll(".PlusBtnCart");
            var minusBtns = document.querySelectorAll(".MinusBtnCart");

            // Add click event listener to plusBtns
            plusBtns.forEach(function(plusBtn) {
                plusBtn.addEventListener("click", function() {
                    handleQuantityChange(this, 1);
                });
            });

            // Add click event listener to minusBtns
            minusBtns.forEach(function(minusBtn) {
                minusBtn.addEventListener("click", function() {
                    handleQuantityChange(this, -1);
                });
            });

            function handleQuantityChange(btn, change) {
                // Get the parent element of the clicked button
                var parentDiv = btn.closest(".CartItemCard");

                // Get the elements within the parent element
                var countElement = parentDiv.querySelector(".CartItemQualityCount");
                var foodItemId = parentDiv.getAttribute("data-food-id");

                // Get the current count value
                var count = parseInt(countElement.textContent);

                // Update the count value
                count += change;
                count = count > 0 ? count : 1;

                // Update the quantity display
                countElement.textContent = count;

                // Send the updated quantity to the server using AJAX
                updateQuantityOnServer(foodItemId, count);
            }

            function updateQuantityOnServer(foodItemId, newQuantity) {
                // Use AJAX to send data to update_quantity.php
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "update_quantity.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Parse the JSON response
                        var response = JSON.parse(xhr.responseText);

                        // Update the total price in the HTML
                        var parentDiv = document.querySelector('[data-food-id="' + foodItemId + '"]');
                        var totalpriceTxt = parentDiv.querySelector(".foodTotalPriceCart");
                        totalpriceTxt.textContent = "₹ " + response.total_price;
                    }
                };
                xhr.send("foodItemId=" + foodItemId + "&newQuantity=" + newQuantity);
            }

        });
    </script>



    <script src="./bootstrap/js/bootstrap.bundle.js"></script>
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>