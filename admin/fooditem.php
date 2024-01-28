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

                <div class="navLinksDiv container d-flex py-3">
                    <ul class="row row-gap-4 m-0 p-0">
                        <li class="navBarItem">
                            <a href="./dashboard.php" class="d-flex gap-1 align-items-center fw-medium py-1 px-2">
                                <img src="../icons/dashboard_black.png" alt="dashboard">
                                Dashboard
                            </a>
                        </li>
                        <li class="navBarItem">
                            <a href="./orders.php" class="d-flex gap-1 align-items-center fw-medium py-1 px-2">
                                <img src="../icons/orders_black.png" alt="orders">
                                Orders
                            </a>
                        </li>
                        <li class="navBarItem">
                            <a href="./tables.php" class="d-flex gap-1 align-items-center fw-medium py-1 px-2">
                                <img src="../icons/tables_black.png" alt="tables">
                                Tables
                            </a>
                        </li>
                        <li class="navBarItem">
                            <a href="./foodcategory.php" class="d-flex gap-1 align-items-center fw-medium py-1 px-2">
                                <img src="../icons/foodcategory_black.png" alt="food category">
                                Food Category
                            </a>
                        </li>
                        <li class="navBarItem activeNavItem rounded">
                            <a href="./fooditem.php" class="d-flex gap-1 align-items-center fw-medium py-2 px-2">
                                <img src="../icons/fooditem_white.png" alt="food item">
                                Food Item
                            </a>
                        </li>
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

                <div class="profileSection d-flex flex-column row-gap-2 container">
                    <div class="row container d-flex profileDiv" onclick="document.location.href='./adminProfile.php'">
                        <?php echo '<img src="data:image;base64,' . $photo . '" alt="Profile Picture" class="d-flex rounded m-0 p-0 justify-content-center col-sm-3 profilePic">'; ?>
                        <div class="row col-sm-8 mx-0 d-flex">
                            <span class="h6 m-0 p-0 fw-bold"><?php echo "$adminName"; ?></span>
                            <span class="h6 m-0 p-0 fw-medium"><?php echo "$adminRole"; ?></span>
                        </div>
                    </div>
                    <button onclick="document.location.href='./logoutAdmin.php'" class="logoutBtn rounded d-flex align-items-center justify-content-center gap-1">
                        <img src="../icons/logout_white.png" alt="Logout" class="logoutIcon">
                        Logout
                    </button>
                </div>
            </div>

            <div class="col-sm-10 d-flex flex-column dashboardBodyDiv align-self-center">

                <div class="py-2 m-0 d-flex align-items-center FoodHeaderControlDiv border-bottom border-dark-subtle">
                    <h1 class="p-0 m-0 h2 w-50 fw-medium">Food Items</h1>
                    <div class="FoodSearchAdd w-50 d-flex gap-3 justify-content-end">
                        <input type="search" name="FoodSearchBox" id="FoodSearchBox" placeholder="Search here..." class="col-sm-8 rounded FoodSearchBox">
                        <input type="submit" value="Add Food Item" class="col-sm-4 rounded addFoodBtn" onclick="addFoodModal.showModal()">
                    </div>
                </div>

                <?php
                $getFoodQuery = "SELECT * FROM food_item ORDER BY category_name, food_name;";
                $getFoodRes = $conn->query($getFoodQuery);
                $table_number = array();

                if ($getFoodRes->num_rows > 0) { ?>

                    <div class="FoodCardsDiv overflow-auto py-3 px-2 container align-self-center  gap-3">
                        <?php
                        while ($row = $getFoodRes->fetch_assoc()) {
                            $foodItemImage = base64_decode($row['food_image']); ?>
                            <?php if ($row['food_status'] == 0) { ?>
                                <div class='card bg-white border border-secondary-subtle shadow-sm rounded-1 col col-sm-auto w-100 p-2 FoodCard' id="<?php echo $row['id']; ?>">
                                <?php } elseif ($row['food_status'] == 1) { ?>
                                    <div class='card bg-info bg-opacity-10 border border-secondary-subtle shadow-sm rounded-1 col col-sm-auto w-100 p-2 FoodCard' id="<?php echo $row['id']; ?>">
                                    <?php } ?>
                                    <h1 class="h5 fw-bold"><?php echo $row['food_name'] ?></h1>
                                    <div class="FoodInfoDiv d-flex gap-2">
                                        <img src="data:image;base64,<?php echo base64_encode($foodItemImage) ?>" alt="food-img" class="FoodImg rounded" id="FoodImg">
                                        <div class="FoodInfo d-flex flex-column justify-content-between w-100">
                                            <div class="FoodDetails d-flex flex-column">
                                                <span class="FoodPrice fs-5 fw-bold">₹ <?php echo number_format($row['price'], 2) ?></span>
                                                <?php if ($row['quantity'] != '') { ?>
                                                    <span class="FoodQuantity fw-bold fs-6">Quantity : <span class="FoodQuantityValue fw-medium"><?php echo $row['quantity'] ?></span></span>
                                                    <span class="FoodCategory fw-bold fs-6">Category : <span class="FoodCategoryValue fw-medium"><?php echo $row['category_name'] ?></span></span>
                                                <?php } else { ?>
                                                    <span class="FoodCategory fw-bold fs-6">Category : <span class="FoodCategoryValue fw-medium"><?php echo $row['category_name'] ?></span></span>
                                                    <span class="FoodQuantity fw-bold fs-6">&nbsp;</span>
                                                <?php } ?>
                                            </div>
                                            <div class="FoodControls d-flex flex-column gap-1">
                                                <hr class="w-100 my-1">
                                                <?php if ($row['food_status'] == 0) { ?>
                                                    <span class="FoodStatus fs-6 fw-bold d-flex align-items-center justify-content-between">Available<img src="../icons/toggle-off.svg" alt="food-available" class="FoodStatusToggleIcon w-auto h-100" onclick="document.location.href='./fooditem.php?disableId=<?php echo $row['id']; ?>'"></span>
                                                <?php } elseif ($row['food_status'] == 1) { ?>
                                                    <span class="FoodStatus fs-6 fw-bold d-flex align-items-center justify-content-between">Disabled<img src="../icons/toggle-on.svg" alt="food-disabled" class="FoodStatusToggleIcon w-auto h-100" onclick="document.location.href='./fooditem.php?availId=<?php echo $row['id']; ?>'"></span>
                                                <?php } ?>
                                                <hr class="w-100 my-1">
                                                <div class="FoodControlBtnDiv d-flex gap-2">
                                                    <?php echo " <input type='submit' value='Edit' class='FoodEditBtn w-50 rounded py-1 fw-medium' id='FoodEditBtn' onClick='openEditFoodModal(\"" . $row['food_image'] . "\", \"" . $row['food_name'] . "\", \"" . $row['id'] . "\", \"" . $row['category_name'] . "\", \"" . $row['quantity'] . "\", \"" . $row['price'] . "\")'>
                                                    <input type='submit' value='Delete' class='FoodDeleteBtn bg-secondary-subtle w-50 rounded py-1 fw-bold' id='FoodDeleteBtn' onClick='openDeleteFoodModal(\"" . $row['food_image'] . "\", \"" . $row['food_name'] . "\", \"" . $row['id'] . "\")' > "; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                <?php } ?>
                                </div>
                            <?php } else { ?>
                                <script>
                                    var disableSearchInput = document.getElementById('FoodSearchBox');
                                    disableSearchInput.disabled = true;
                                </script>
                            <?php
                            echo "
                        <div class='FoodCardsDivSearchErrorMsg overflow-auto py-2 px-3 d-flex flex-column h-100 w-100 align-items-center justify-content-center'>
                            <h1 class='h2 fw-bolder text-decoration-underline'>Nothing to Show</h1>
                            <p class='h6 w-50 lh-base text-center'>We're sorry, but it looks like there are no details available for the food items at the moment. It's possible that there is no data to display. Please check back later.</p>
                        </div> ";
                        } ?>
                            <div class='FoodCardsDivMsg overflow-auto py-2 px-3 flex-column h-100 align-items-center justify-content-center'>
                                <h1 class='h2 fw-bolder text-decoration-underline'>Nothing to Show</h1>
                                <p class='h6 w-50 lh-base text-center'>We're sorry, but it looks like there are no details available for the food items at the moment. It's possible that there is no data to display. Please check back later.</p>
                            </div>
                    </div>


            </div>

        </div>



        <!-- ADD FOOD-ITEM MODAL -->
        <dialog class="addFoodModal rounded p-3" id="addFoodModal">
            <div class="addFoodModalContentDiv h-100 d-flex flex-column p-0 m-0 align-self-center align-items-center justify-content-center">
                <h1 class="m-0 p-0 display-6 fw-bold">Add Food Item</h1>
                <hr class="w-100 my-2">
                <form action="./fooditem.php" method="post" enctype="multipart/form-data" class="d-flex flex-column align-items-center w-75">
                    <input type="text" name="food_name" placeholder="Enter Food Item Name" id="food_name" class="food_name col-sm-11 my-2 py-2 px-4 rounded" required>
                    <?php $getFcList = "SELECT * FROM food_category ORDER BY category_name";
                    $getFcListRes = mysqli_query($conn, $getFcList); ?>
                    <select name="category_name" id="categoryName" class="FcSelect col-sm-11 my-2 py-2 px-4 rounded" required>
                        <?php if ($getFcListRes->num_rows > 0) { ?>
                            <option value="" class="optionTxt w-100 col-sm-11" disabled selected hidden>Select Food Category</option>
                            <?php while ($row = $getFcListRes->fetch_assoc()) { ?>
                                <option class="optionTxt w-100"><?php echo $row['category_name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <input type="text" name="food_quantity" placeholder="Enter Food Quantity" id="food_quantity" class="col-sm-11 my-2 py-2 px-4 rounded food_quantity">
                    <input type="text" name="food_price" placeholder="Enter Price" id="food_price" class="col-sm-11 my-2 py-2 px-4 rounded food_price" required>
                    <input type="file" name="food_image" id="food_image" class="col-sm-11 my-2 rounded food_image" required>
                    <div class="addFoodControlDiv col-sm-11 d-flex align-items-center justify-content-center gap-3 mt-2">
                        <input type="submit" value="Add Food Item" class="addFoodConfirmBtn w-50 py-2 fs-4 rounded">
                        <input type="submit" value="Cancel" class="addFoodCancelBtn w-50 py-2 fs-4 rounded bg-secondary-subtle" onclick="addFoodModal.close()">
                    </div>
                </form>
            </div>
        </dialog>



        <!-- DELETE FOOD-ITEM MODAL -->
        <dialog class="deleteFoodModal rounded" id="deleteFoodModal">
            <div class="deleteFoodModalContentDiv d-flex flex-column justify-content-between align-items-center h-100">
                <h1 class="deleteFoodHeaderMsg w-100 text-center h3" id="deleteFoodHeaderMsg">h</h1>
                <hr class="w-100 p-0 m-0">
                <img src="" alt="food-category" class="deleteFoodImg rounded">
                <div class=" p-0 m-0 w-100 d-flex gap-3 align-items-center justify-content-center">
                    <input type="submit" class="addTableConfirmBtn col-sm-5 p-2 fs-4 rounded" value="Yes" onclick="deleteFood()">
                    <input type="submit" class="addTableCancelBtn col-sm-5 p-2 fs-4 rounded bg-secondary-subtle" value="No" onclick="deleteFoodModal.close()">
                </div>
            </div>
        </dialog>


        <!-- EDIT FOOD-ITEM MODAL -->
        <dialog class="editFoodModal rounded p-3" id="editFoodModal">
            <div class="editFoodModalContentDiv d-flex flex-column align-items-center justify-content-between h-100">
                <h1 class="w-100 text-center display-6 fw-bold align-self-center p-0 m-0">Edit Food Item</h1>
                <hr class="w-100 p-0 my-1">
                <img src="" alt="food-category" class="editFoodModalImg rounded my-1" id="editFoodModalImg">
                <form action="./fooditem.php" method="post" enctype="multipart/form-data" class="d-flex flex-column align-items-center w-75">
                    <input type="text" name="food_name" id="editFoodName" class="editFoodName my-2 col-sm-12 rounded py-2 px-4 fw-medium" placeholder="Enter Food category">
                    <input type="text" name="food_name_compare" id="editFoodNameCompare" class="editFoodNameCompare" readonly hidden>
                    <input type="text" name="food_id" id="editFoodId" class="editFoodId" readonly hidden>
                    <?php $getFcList = "SELECT * FROM food_category ORDER BY category_name";
                    $getFcListRes = mysqli_query($conn, $getFcList); ?>
                    <select name="category_name" id="editFoodCategoryName" class="FcSelect col-sm-12 my-2 py-2 px-4 rounded" required>
                        <?php if ($getFcListRes->num_rows > 0) { ?>
                            <option value="" class="optionTxt w-100 col-sm-11" disabled hidden>Select Food Category</option>
                            <?php while ($row = $getFcListRes->fetch_assoc()) { ?>
                                <option class="optionTxt w-100"><?php echo $row['category_name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <input type="file" name="food_image" id="editFoodImg" class="editFoodImg my-2 col-sm-12 rounded fw-medium">
                    <input type="text" name="food_quantity" id="editFoodQuantity" class="editFoodQuantity my-2 col-sm-12 rounded py-2 px-4 fw-medium" placeholder="Enter Food Quantity">
                    <input type="text" name="food_price" id="editFoodPrice" class="editFoodPrice my-2 col-sm-12 rounded py-2 px-4 fw-medium" placeholder="Enter Price" required>
                    <div class="col col-sm-12 d-flex gap-3 mt-2 align-items-center justify-content-between p-0">
                        <input type="submit" value="Edit Food Category" class="editFoodBtn w-50 py-2 fs-4 rounded" id="editFoodBtn">
                        <input type="submit" value="Cancel" class="editFoodCancelBtn w-50 py-2 fs-4 rounded bg-secondary-subtle" id="editFoodCancelBtn" onclick="editFoodModal.close()">
                    </div>
                </form>
            </div>
        </dialog>



        <!-- EDIT FOOD CATEGORY MODAL -->
        <script>
            // var editFcUrl;

            function openEditFoodModal(editFoodImgData, editFoodName, editFoodID, editFoodCategory, editFoodQuantity, editFoodPrice) {
                var editFoodModal = document.getElementById('editFoodModal');
                var editFoodImg = editFoodModal.querySelector('.editFoodModalImg');
                document.getElementById('editFoodName').value = editFoodName;
                document.getElementById('editFoodId').value = editFoodID;
                document.getElementById('editFoodNameCompare').value = editFoodName;
                // document.getElementById('default_category').value = editFoodCategory;
                document.getElementById('editFoodQuantity').value = editFoodQuantity;
                document.getElementById('editFoodPrice').value = editFoodPrice;

                // Set the default category value in the dropdown
                var categoryDropdown = document.getElementById('editFoodCategoryName');
                for (var i = 0; i < categoryDropdown.options.length; i++) {
                    if (categoryDropdown.options[i].text === editFoodCategory) {
                        categoryDropdown.options[i].selected = true;
                        break;
                    }
                }

                // Create a temporary canvas
                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');

                // Create a new image element
                var image = new Image();

                // Set the source of the new image to the QR image data
                image.src = 'data:image;base64,' + editFoodImgData;

                // Wait for the image to load
                image.onload = function() {
                    // Set the canvas size to match the image
                    canvas.width = image.width;
                    canvas.height = image.height;

                    // Draw the image onto the canvas
                    context.drawImage(image, 0, 0);

                    // Convert the canvas content to a blob with JPG format
                    canvas.toBlob(function(blob) {
                        // Create a new image element with the blob as the source
                        var newImage = new Image();
                        newImage.src = URL.createObjectURL(blob);

                        // Set the source of the modal's image to the new image source
                        editFoodImg.src = newImage.src;

                        // Set the table name in the modal header
                        // editFcName.value = editFcName;

                        // editFcUrl = "./foodcategory.php?editFcId=" + editFcID;

                        // Open the modal
                        editFoodModal.showModal();
                    }, 'image/jpeg', 1.0); // 1.0 means full quality
                };
            }
        </script>



        <!-- EDIT FOOD CATEGORY -->
        <script>
            <?php
            if (isset($_POST['food_id']) && isset($_POST['food_name_compare'])) {
                $editFoodId = $_POST['food_id'];
                $editFcNameOriginal = $_POST['food_name_compare'];

                // EDIT WITHOUT IMAGE
                if (
                    isset($_POST['food_name'])
                    && isset($_POST['category_name'])
                    && isset($_POST['food_quantity'])
                    && isset($_POST['food_price'])
                    // && !isset($_FILES['edit_food_image'])
                ) {
                    $foodName = $_POST['food_name'];
                    $foodCategory = $_POST['category_name'];
                    $foodQuantity = $_POST['food_quantity'];
                    $foodPrice = $_POST['food_price'];
                    // $foodImageData = $_FILES['edit_food_image'];


                    $editFoodQuery = "UPDATE food_item SET food_name='$foodName', category_name='$foodCategory', quantity='$foodQuantity' , price='$foodPrice' WHERE id='$editFoodId' ";
                    $editFoodRun = mysqli_query($conn, $editFoodQuery);

                    if ($editFoodRun) {
            ?>
                        window.location.href = "./fooditem.php";
                    <?php
                        $_SESSION["FoodDetailsUpdated"] = "Food Item Updated Successfully..!";
                    } else {
                    ?>
                        window.location.href = "./fooditem.php";
                        <?php
                        $_SESSION["FoodDetailsUpdationFailed"] = "Failed to Update Food Item..!";
                    }
                }

                // EDIT WITH IMAGE
                if (
                    isset($_POST['food_name'])
                    && isset($_POST['category_name'])
                    && isset($_POST['food_quantity'])
                    && isset($_POST['food_price'])
                    && isset($_FILES['food_image'])
                ) {
                    $foodName = $_POST['food_name'];
                    $foodCategory = $_POST['category_name'];
                    $foodQuantity = $_POST['food_quantity'];
                    $foodPrice = $_POST['food_price'];
                    $foodImageData = $_FILES['food_image'];
                    if ($_FILES['food_image']['error'] == UPLOAD_ERR_OK) {
                        $editFoodImageGet = $_FILES['food_image']['tmp_name'];
                        $editFoodImageData = file_get_contents($editFoodImageGet);
                        $editFoodImage = base64_encode($editFoodImageData);

                        $editFoodQuery = "UPDATE food_item SET food_name='$foodName', category_name='$foodCategory', quantity='$foodQuantity' , price='$foodPrice', food_image='$editFoodImage' WHERE id='$editFoodId' ";
                        $editFoodRun = mysqli_query($conn, $editFoodQuery);

                        if ($editFoodRun) {
                        ?>
                            window.location.href = "./fooditem.php";
                        <?php
                            $_SESSION["FoodDetailsUpdated"] = "Food Item Updated Successfully..!";
                        } else {
                        ?>
                            window.location.href = "./fooditem.php";
            <?php
                            $_SESSION["FoodDetailsUpdationFailed"] = "Failed to Update Food Item..!";
                        }
                    }
                }
            }

            ?>
        </script>



        <!-- SEARCH BAR SCRIPT -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const FoodSearchBox = document.getElementById('FoodSearchBox');
                const FoodCardsDiv = document.querySelector('.FoodCardsDiv');
                const FoodCardsDivMsg = document.querySelector('.FoodCardsDivMsg');

                FoodSearchBox.addEventListener('input', function() {
                    const searchValue = FoodSearchBox.value.trim().toLowerCase();
                    const cards = document.querySelectorAll('.FoodCard');

                    let matchFound = false;

                    cards.forEach(function(card) {
                        const cardText = card.innerText.toLowerCase();

                        if (cardText.includes(searchValue)) {
                            card.style.display = 'block';
                            matchFound = true;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    if (matchFound) {
                        FoodCardsDivMsg.style.display = 'none';
                    } else {
                        FoodCardsDivMsg.style.display = 'flex';
                    }

                });
            });
        </script>



        <!-- UPDATE FOOD-CATEGORY STATUS-->
        <script>
            // Change table_status to 1
            <?php
            if (isset($_GET['disableId'])) {
                $FoodDisableId = $_GET['disableId'];
                $FoodDisableQuery = "UPDATE food_item SET food_status = '1' WHERE id=$FoodDisableId ";
                $FoodDisableRun = mysqli_query($conn, $FoodDisableQuery);
                if ($FoodDisableRun) { ?>
                    window.location.href = './fooditem.php#<?php echo $FoodDisableId ?>';
                <?php
                } else {
                ?>
                    window.location.href = './fooditem.php';
                <?php
                }
                // Change table_status to 0
            } elseif (isset($_GET['availId'])) {
                ?>
                <?php
                $FoodAvailId = $_GET['availId'];
                $FoodAvailQuery = "UPDATE food_item SET food_status = '0' WHERE id=$FoodAvailId ";
                $FoodAvailRun = mysqli_query($conn, $FoodAvailQuery);
                if ($FoodAvailRun) { ?>
                    window.location.href = './fooditem.php#<?php echo $FoodAvailId ?>';
                <?php
                } else {
                ?>
                    window.location.href = './fooditem.php';
            <?php
                }
            }
            ?>
        </script>



        <!-- ADD FOOD-ITEM -->
        <script>
            <?php
            if (isset($_POST['food_name']) && isset($_FILES['food_image']) && isset($_POST['food_quantity']) && isset($_POST['food_price']) && isset($_POST['category_name'])) {
                $FoodName = $_POST['food_name'];

                $FoodImageGet = $_FILES['food_image']['tmp_name'];
                $name = addslashes($_FILES["food_image"]["tmp_name"]);
                $FoodImageData = file_get_contents($FoodImageGet);
                $FoodImage = base64_encode($FoodImageData);

                $FoodQuantity = $_POST['food_quantity'];
                $FoodPrice = $_POST['food_price'];
                $FoodCategoryName = $_POST['category_name'];

                $checkIfFoodExists = "SELECT * FROM food_item WHERE food_name=? ";
                $checkForFood = $conn->prepare($checkIfFoodExists);
                $checkForFood->bind_param('s', $FoodName);
                $checkForFood->execute();
                $checkForFoodResult = $checkForFood->get_result();

                if ($checkForFoodResult->num_rows >= 1) { ?>
                    window.location.href = "./fooditem.php";
                    <?php $_SESSION['FoodAlreadyExists'] = "Food Item Already Exists...!";
                } else {
                    $addFoodQuery = "INSERT INTO food_item(food_name, category_name, price, quantity, food_image) VALUES ('$FoodName', '$FoodCategoryName','$FoodPrice', '$FoodQuantity','$FoodImage') ";
                    $addFoodRun = mysqli_query($conn, $addFoodQuery);

                    if ($addFoodRun) { ?>
                        window.location.href = "./fooditem.php";
                    <?php $_SESSION["FoodAddedSuccessfully"] = "Food Item Added Successfully..!";
                    } else { ?>
                        window.location.href = "./fooditem.php";
            <?php $_SESSION["FoodAdditionFailure"] = "Failed to add Food Item..!";
                    }
                }
            }
            ?>
        </script>



        <!-- DELETE FOOD CATEGORY MODAL -->
        <script>
            var deleteFoodUrl;

            function openDeleteFoodModal(deleteFoodImgData, deleteFoodName, deleteFoodID) {
                var deleteFoodModal = document.getElementById('deleteFoodModal');
                var deleteFoodImg = deleteFoodModal.querySelector('.deleteFoodImg');
                var deleteFoodHeaderMsg = deleteFoodModal.querySelector('#deleteFoodHeaderMsg');

                // Create a temporary canvas
                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');

                // Create a new image element
                var image = new Image();

                // Set the source of the new image to the QR image data
                image.src = 'data:image;base64,' + deleteFoodImgData;

                // Wait for the image to load
                image.onload = function() {
                    // Set the canvas size to match the image
                    canvas.width = image.width;
                    canvas.height = image.height;

                    // Draw the image onto the canvas
                    context.drawImage(image, 0, 0);

                    // Convert the canvas content to a blob with JPG format
                    canvas.toBlob(function(blob) {
                        // Create a new image element with the blob as the source
                        var newImage = new Image();
                        newImage.src = URL.createObjectURL(blob);

                        // Set the source of the modal's image to the new image source
                        deleteFoodImg.src = newImage.src;

                        // Set the table name in the modal header
                        deleteFoodHeaderMsg.textContent = "Are you sure you want to delete '" + deleteFoodName + "'?";

                        deleteFoodUrl = "./fooditem.php?deleteFoodId=" + deleteFoodID;

                        // Open the modal
                        deleteFoodModal.showModal();
                    }, 'image/jpeg', 1.0); // 1.0 means full quality
                };
            }


            function deleteFood() {
                window.location.href = deleteFoodUrl;
                // console.log(deleteFoodUrl);
            }
        </script>



        <!-- DELETE FOOD-ITEM -->
        <script>
            <?php
            if (isset($_GET['deleteFoodId'])) {
                $deleteFoodId = $_GET['deleteFoodId'];
                $deleteFoodQuery = "DELETE FROM food_item WHERE id='$deleteFoodId' ";
                $deleteFoodRun = mysqli_query($conn, $deleteFoodQuery);
                if ($deleteFoodRun) { ?>
                    window.location.href = "./fooditem.php";
                <?php $_SESSION["FoodDeletionSuccess"] = "Food Category Deleted Successfully..!";
                } else { ?>
                    window.location.href = "./fooditem.php";
            <?php $_SESSION["FoodDeletionFailure"] = "Failed to delete Food Category..!";
                }
            }
            ?>
        </script>
</body>

</html>