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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="./css/styleHome.css">
    <link rel="stylesheet" href="./font/stylesheet.css">
    <link rel="stylesheet" href="./font/lato_stylesheet.css">
</head>

<body class="flex-column">

    <div class="container py-0 px-0 d-flex flex-column justify-content-between h-100 ">

        <div class="headerDiv bg-danger p-2">
            <h1 class="h3 text-center">You're Ordering from <span class="fw-bold">Table-<?php echo $tableNo ?></span></h1>
            <input type="search" name="foodSearchBar" class="w-100 py-2 ps-5 pe-3 fs-6 rounded-1 foodSearchBar" id="foodSearchBar" placeholder="Search here...">
        </div>

        <div class="py-3 px-2 h-100 overflow-scroll foodCardsDiv ">
            <?php $getFoodItems = "SELECT * FROM food_item WHERE food_status='0' ORDER BY category_name, food_name";
            $getFoodItemsRun = mysqli_query($conn, $getFoodItems);
            if ($getFoodItemsRun->num_rows >= 1) { ?>
                <div class="cardsDiv gap-2">
                    <?php while ($data = $getFoodItemsRun->fetch_assoc()) {
                        $foodItemImage = base64_decode($data['food_image']); ?>
                        <div class="card border border-secondary-subtle rounded-1 shadow-sm p-2 h-auto FoodCard">
                            <img src="data:image;base64,<?php echo base64_encode($foodItemImage) ?>" alt="<?php echo $data['food_name']; ?>" class="FoodItemImg rounded-1" id="FoodItemImg">
                            <h1 class=" fw-bold pt-1 m-0 FoodName"><?php echo $data['food_name']; ?></h1>
                            <h1 class=" fw-medium py-0 m-0 text-secondary FoodCategory"><?php echo $data['category_name']; ?></h1>
                            <?php if ($data['quantity'] != "") { ?>
                                <h1 class=" fw-medium py-0 m-0 text-dark-emphasis FoodQuantity"><?php echo $data['quantity']; ?></h1>
                            <?php } else { ?>
                                <h1 class=" fw-medium py-0 m-0 text-dark-emphasis FoodQuantity">&nbsp;</h1>
                            <?php } ?>

                            <div class="FoodControlsDiv d-flex align-items-center justify-content-center LaptopControls">

                                <div class="QuantityControlDiv d-flex justify-content-between w-50 border border-secondary-subtle rounded-1">
                                    <button class='rounded-1 minusBtn d-flex align-items-center justify-content-center p-2'> <img src='./icons/minus.png' class='minusIcon' alt='minusIcon' width="25px" height="auto" /> </button>
                                    <input name='quantity' class='w-25 p-0 m-0 fs-4 fw-bold quantityCount quantityCountTxt' id='quantityCount' min='1' value='1' readonly />
                                    <button class='rounded-1 plusBtn d-flex align-items-center justify-content-center p-2'> <img src='./icons/plus.png' class='plusIcon' alt='plusIcon' width="25px" height="auto" /> </button>
                                </div>

                                <div class="Price_N_AddToCart d-flex align-items-center justify-content-between w-50 gap-2">
                                    <input type="text" name="FoodPrice" id="FoodPrice" class="FoodPrice" value="<?php echo number_format($data['price'], 2); ?>" readonly hidden>
                                    <input type="text" name="FoodTotalPrice" id="FoodTotalPrice" class="fs-5 fw-bold text-dark-emphasis FoodTotalPrice" value="₹ <?php echo number_format($data['price'], 2); ?>" readonly>
                                    <button name="addToCartBtn" class='addToCartBtn rounded-1 p-2 h-100'> <img src='./icons/addToCart.png' class='addToCartIcon' alt='addToCartIcon' width="25" height="auto" /> </button>
                                </div>

                            </div>

                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

        </div>

        <div class="bottomNavBarDiv m-0 p-0 bg-secondary d-flex">
            <a href="./logoutEndUser.php">logout</a>
            <h1 class="p-0 m-0 text-center text-white">Bottom Navigation Bar</h1>
        </div>

    </div>



    <!-- UPDATES QUANTITY PLUS AND MINUS, UPDATES PRICE -->
    <script>
        $(document).ready(function() {
            // Plus button click event
            $('.plusBtn').on('click', function() {
                var container = $(this).closest('.FoodCard');
                updateQuantityAndPrice(container, 1);
            });

            // Minus button click event
            $('.minusBtn').on('click', function() {
                var container = $(this).closest('.FoodCard');
                updateQuantityAndPrice(container, -1);
            });

            // Function to update quantity and price
            function updateQuantityAndPrice(container, change) {
                var quantityCountInput = container.find('.quantityCountTxt');
                var priceInput = container.find('.FoodPrice');
                var priceTxtInput = container.find('.FoodTotalPrice');

                var quantityCount = parseInt(quantityCountInput.val()) + change;
                if (quantityCount < 1) {
                    quantityCount = 1;
                }

                var price = parseFloat(priceInput.val());
                quantityCountInput.val(quantityCount);
                priceTxtInput.val('₹ ' + (quantityCount * price).toFixed(2));
            }
        });
    </script>




    <script src="./bootstrap/js/bootstrap.bundle.js"></script>
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>