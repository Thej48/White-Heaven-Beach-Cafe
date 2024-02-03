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

        <div class="headerDiv p-2">
            <h1 class="h3 text-center">You're Ordering from <span class="fw-bold">Table-<?php echo $tableNo ?></span></h1>
            <input type="search" name="foodSearchBar" class="w-100 py-2 ps-5 pe-3 fs-6 rounded-1 foodSearchBar" id="foodSearchBar" placeholder="Search here...">
            <?php $getCategoryList = "SELECT * FROM food_category WHERE category_status='0' ORDER BY category_name";
            $getCategoryListRes = mysqli_query($conn, $getCategoryList); ?>
            <?php if ($getCategoryListRes->num_rows >= 1) { ?>
                <div class="categoryCardsDiv  gap-3 mt-3">
                    <button class="categoryCard allBtn text-center fs-5 fw-semibold text-light-emphasis  gap-2 rounded px-5 m-0" onclick="toggleButton(this)" data-category-name="all">All</button>
                    <?php while ($row = $getCategoryListRes->fetch_assoc()) {
                        $categoryImage = base64_decode($row['category_image']); ?>
                        <button class="categoryCard  gap-2 rounded pe-3 m-0" onclick="toggleButton(this)" data-category-name="<?php echo $row['category_name']; ?>">
                            <img src="data:image;base64,<?php echo base64_encode($categoryImage) ?>" alt="<?php echo $row['category_name']; ?>" class="rounded-1 shadow-sm categoryImg" width="40" height="auto">
                            <span class="fs-5 fw-semibold text-light-emphasis categoryHeader"><?php echo $row['category_name']; ?> </span>
                        </button>
                    <?php } ?>
                </div>
            <?php } ?>


        </div>

        <div class="py-3 px-2 h-100 overflow-scroll foodCardsDiv ">
            <?php $getFoodItems = "SELECT * FROM food_item WHERE food_status='0' ORDER BY category_name, food_name";
            $getFoodItemsRun = mysqli_query($conn, $getFoodItems);
            if ($getFoodItemsRun->num_rows >= 1) { ?>
                <div class="cardsDiv gap-2">
                    <?php while ($data = $getFoodItemsRun->fetch_assoc()) {
                        $foodItemImage = base64_decode($data['food_image']); ?>
                        <div class="card border border-secondary-subtle rounded-1 shadow-sm p-2 h-auto FoodCard" data-category="<?php echo $data['category_name']; ?>">
                            <img src="data:image;base64,<?php echo base64_encode($foodItemImage) ?>" alt="<?php echo $data['food_name']; ?>" class="FoodItemImg rounded-1" id="FoodItemImg">
                            <h1 class=" fw-bold pt-1 m-0 FoodName"><?php echo $data['food_name']; ?></h1>
                            <h1 class=" fw-medium py-0 m-0 text-secondary FoodCategory"><?php echo $data['category_name']; ?></h1>
                            <?php if ($data['quantity'] != "") { ?>
                                <h1 class=" fw-medium py-0 m-0 text-dark-emphasis FoodQuantity"><?php echo $data['quantity']; ?></h1>
                            <?php } else { ?>
                                <h1 class=" fw-medium py-0 m-0 text-dark-emphasis FoodQuantity">&nbsp;</h1>
                            <?php } ?>
                            <div class="FoodControlsDiv mt-2 d-flex justify-content-between gap-1">
                                <div class="QuantityControlDiv border border-secondary-subtle rounded-1 d-flex justify-content-between w-50">
                                    <button class="MinusBtn p-2 rounded-1 d-flex align-items-center justify-content-center">
                                        <img src="./icons/minus.png" alt="minus" class="MinusIcon">
                                    </button>
                                    <input name="FoodQuantityCount" id="FoodQuantityCount" class="FoodQuantityCount fs-6 fw-bold text-center p-0" value="1" min="1" readonly>
                                    <button class="PlusBtn p-2 rounded-1 d-flex align-items-center justify-content-center">
                                        <img src="./icons/plus.png" alt="plus" class="PlusIcon">
                                    </button>
                                </div>
                                <div class="FoodPriceDiv w-50">
                                    <input type="text" name="FoodPrice" id="FoodPrice" class="FoodPrice" value="<?php echo number_format($data['price'], 2) ?>" readonly hidden>
                                    <input type="text" name="FoodTotalPrice" id="FoodTotalPrice" class="FoodTotalPrice text-end h-100 w-100" value="₹ <?php echo number_format($data['price'], 2) ?>" readonly>
                                </div>
                            </div>
                            <form action="./cart.php?tableNo=<?= $tableNo; ?>&id=<?= $data['id']; ?>" method="post">
                                <input type="text" name="FoodItemID" id="FoodItemID" class="FoodItemID" value="<?= $data['id']; ?>" readonly hidden>
                                <input type="text" name="FoodItemName" id="FoodItemName" class="FoodItemName" value="<?= $data['food_name']; ?>" readonly hidden>
                                <input type="text" name="FoodItemCategory" id="FoodItemCategory" class="FoodItemCategory" value="<?= $data['category_name']; ?>" readonly hidden>
                                <input type="text" name="FoodItemPrice" id="FoodItemPrice" class="FoodItemPrice" value="<?= $data['price']; ?>" readonly hidden>
                                <input type="text" name="FoodItemQuantity" id="FoodItemQuantity" class="FoodItemQuantity" value="1" readonly hidden>
                                <input type="text" name="FoodItemTotalPrice" id="FoodItemTotalPrice" class="FoodItemTotalPrice" value="<?= $data['price']; ?>" readonly hidden>
                                <button type="submit" name="AddToCartBtn" class="AddToCartBtn d-flex align-items-center justify-content-center w-100 gap-1 rounded-1 mt-2 py-1 fs-5 fw-medium">
                                    Add To Cart <img src="./icons/addToCart.png" alt="addtocart" width="23" height="23" class="AddToCartIcon">
                                </button>
                            </form>

                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <div class="bottomNavBarDiv rounded-top m-0 p-0 d-flex">
            <ul class="BottomNavMenu p-0 m-0 py-2">
                <li class="BottomMenuItem d-flex flex-column align-items-center justify-content-center " onclick="document.location.href='./menu.php?tableNo=<?php echo $tableNo; ?>'">
                    <img src="./icons/menu_inactive.png" width="20" height="20" alt="menu" id="MenuIcon">
                    <span class="" id="MenuText">Menu</span>
                </li>
                <li class="BottomMenuItem d-flex flex-column align-items-center justify-content-center " onclick="document.location.href='./cart.php?tableNo=<?php echo $tableNo; ?>'">
                    <img src="./icons/trolley_inactive.png" width="20" height="20" alt="cart" id="CartIcon">
                    <span class="" id="CartText">Cart</span>
                </li>
                <li class="BottomMenuItem d-flex flex-column align-items-center justify-content-center " onclick="document.location.href='./orders.php?tableNo=<?php echo $tableNo; ?>'">
                    <img src="./icons/take-away_inactive.png" width="20" height="20" alt="orders" id="OrderIcon">
                    <span class="" id="OrderText">Orders</span>
                </li>
                <li class="BottomMenuItem d-flex flex-column align-items-center justify-content-center " onclick="document.location.href='./profile.php?tableNo=<?php echo $tableNo; ?>'">
                    <img src="./icons/account_inactive.png" width="20" height="20" alt="profile" id="ProfileIcon">
                    <span class="" id="ProfileText">Profile</span>
                </li>
            </ul>
        </div>

    </div>



    <!-- ICON ACTIVE CODE -->
    <script>
        document.getElementById('MenuIcon').src = './icons/menu_active.png';
        document.getElementById('MenuText').style.color = "white";
    </script>



    <!-- SEARCH FOOD CATEGORY -->
    <script>
        // Add an event listener to the search input
        document.getElementById('foodSearchBar').addEventListener('input', function() {
            // Get the search input value and trim whitespace
            var searchValue = this.value.trim().toLowerCase();

            // Get all the table cards
            var foodCard = document.getElementsByClassName('FoodCard');

            // Loop through each card
            for (var i = 0; i < foodCard.length; i++) {
                // Get the card text and trim whitespace
                var cardText = foodCard[i].textContent.trim().toLowerCase();

                // Check if the card text contains the search value
                if (cardText.includes(searchValue)) {
                    // If it matches, show the card
                    foodCard[i].style.display = 'flex';
                } else {
                    // If it doesn't match, hide the card
                    foodCard[i].style.display = 'none';
                }
            }
        });
    </script>




    <!-- FILTER FOOD ITEMS ACCORDING TO FOOD CATEGORY -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        var selectedButton = null;

        // Initially, simulate a click on the "All" button
        $(document).ready(function() {
            toggleButton($('.allBtn')[0]);
        });

        function toggleButton(button) {
            // Get the category name from the clicked button
            var categoryName = $(button).data('category-name');

            // Handle "All" button separately
            if (categoryName === 'all') {
                // Show all food items
                $('.FoodCard').show();
            } else {
                // Hide all food items
                $('.FoodCard').hide();

                // Show only the food items with the selected category
                $('.FoodCard[data-category="' + categoryName + '"]').show();
            }

            // Toggle the active class for the buttons
            if (selectedButton !== null) {
                selectedButton.classList.remove('FcActive');
            }

            if (selectedButton !== button) {
                button.classList.add('FcActive');
                selectedButton = button;
            } else {
                selectedButton = null;
            }
        }
    </script>



    <!-- UPDATES QUANTITY PLUS AND MINUS, UPDATES PRICE -->
    <script>
        $(document).ready(function() {
            // Plus button click event
            $('.PlusBtn').on('click', function() {
                var container = $(this).closest('.FoodCard');
                updateQuantityAndPrice(container, 1);
            });

            // Minus button click event
            $('.MinusBtn').on('click', function() {
                var container = $(this).closest('.FoodCard');
                updateQuantityAndPrice(container, -1);
            });

            // Function to update quantity and price
            function updateQuantityAndPrice(container, change) {
                var quantityCountInput = container.find('.FoodQuantityCount');
                var priceInput = container.find('.FoodPrice');
                var priceTxtInput = container.find('.FoodTotalPrice');

                var quantityCount = parseInt(quantityCountInput.val()) + change;
                if (quantityCount < 1) {
                    quantityCount = 1;
                }

                var price = parseFloat(priceInput.val());
                quantityCountInput.val(quantityCount);
                priceTxtInput.val('₹ ' + (quantityCount * price).toFixed(2));

                var FoodItemQuantity = container.find('.FoodItemQuantity');
                var FoodItemPrice = container.find('.FoodItemPrice');
                var FoodItemTotalPrice = container.find('.FoodItemTotalPrice');

                var FoodItemQuantityCount = parseInt(FoodItemQuantity.val()) + change;
                if (FoodItemQuantityCount < 1) {
                    FoodItemQuantityCount = 1;
                }

                var FoodItemPriceValue = parseFloat(FoodItemPrice.val());
                FoodItemQuantity.val(FoodItemQuantityCount);
                FoodItemTotalPrice.val((FoodItemQuantityCount * FoodItemPriceValue).toFixed(2));

            }
        });
    </script>





    <script src="./bootstrap/js/bootstrap.bundle.js"></script>
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>