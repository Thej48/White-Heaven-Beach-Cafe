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
                        <li class="navBarItem  activeNavItem rounded">
                            <a href="./foodcategory.php" class="d-flex gap-1 align-items-center fw-medium py-2 px-2">
                                <img src="../icons/foodcategory_white.png" alt="food category">
                                Food Category
                            </a>
                        </li>
                        <li class="navBarItem">
                            <a href="./fooditem.php" class="d-flex gap-1 align-items-center fw-medium py-1 px-2">
                                <img src="../icons/fooditem_black.png" alt="food item">
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
                    <div class="row container d-flex">
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

            <div class="col-sm-10 d-flex flex-column dashboardBodyDiv">

                <div class="py-2 m-0 d-flex align-items-center FcHeaderControlDiv border-bottom border-dark-subtle">
                    <h1 class="p-0 m-0 h2 w-50 fw-medium">Food Category</h1>
                    <div class="FcSearchAdd w-50 d-flex gap-3 justify-content-end">
                        <input type="search" name="FcSearchBox" id="FcSearchBox" placeholder="Search here..." class="col-sm-8 rounded FcSearchBox">
                        <input type="submit" value="Add Food Category" class="col-sm-4 rounded addFcBtn" onclick="addFcModal.showModal()">
                    </div>
                </div>

                <?php
                $getFcQuery = "SELECT * FROM food_category ORDER BY category_name";
                $getFcRes = $conn->query($getFcQuery);
                $table_number = array();

                if ($getFcRes->num_rows > 0) {
                ?>
                    <div class="FcCardsDiv overflow-auto py-2 px-3 d-flex flex-wrap row row-cols-auto ">
                        <?php
                        while ($row = $getFcRes->fetch_assoc()) {
                            if ($row['category_status'] == 0) { ?>
                                <div class='card bg-white border border-secondary-subtle p-3 shadow-sm m-2 rounded-1 col FcCard' id="<?php echo $row['id']; ?>">
                                <?php } elseif ($row['category_status'] == 1) { ?>
                                    <div class='card bg-info bg-opacity-10 border border-secondary-subtle p-3 shadow-sm m-2 rounded-1 col FcCard' id="<?php echo $row['id']; ?>">
                                    <?php }
                                echo "<h1 class='h4 fw-bold p-0 m-0'>" . $row['category_name'] . "</h1>
                                        <hr class='my-2'/>
                                        <img src='data:image;base64," . $row['category_image'] . "' alt='fc-image' class='FcImg rounded'/>
                                        <hr class='my-2'/>
                                    ";
                                if ($row['category_status'] == 0) {
                                    ?>
                                        <div class='FcStatusDiv d-flex align-items-center justify-content-between mb-1'>
                                            <span class="fw-bold">Status : <span class="fw-medium">Available</span></span>
                                            <img class="FcStatusChangeBtn" src='../icons/toggle-off.svg' alt='Fc-available' onclick="document.location.href='./foodcategory.php?disableId=<?php echo $row['id']; ?>'">
                                        </div>
                                    <?php } elseif ($row['category_status'] == 1) { ?>
                                        <div class='FcStatusDiv d-flex align-items-center justify-content-between mb-1'>
                                            <span class="fw-bold">Status : <span class="fw-medium">Disabled</span></span>
                                            <img class="FcStatusChangeBtn" src='../icons/toggle-on.svg' alt='Fc-unavailable' onclick="document.location.href='./foodcategory.php?availId=<?php echo $row['id']; ?>'">
                                        </div>
                                    <?php } ?>
                                    <div class="FcControlDiv d-flex gap-2 justify-content-between mt-2">
                                        <?php echo "    
                                        <button class='FcEditBtn w-50 py-1 rounded' onClick='openEditFcModal(\"" . $row['category_image'] . "\", \"" . $row['category_name'] . "\", \"" . $row['id'] . "\")'>
                                            <span class='fw-medium'>Edit</span>
                                        </button>
                                        
                                        <button class='FcDeleteBtn bg-secondary-subtle w-50 py-1 rounded' onClick='openDeleteFcModal(\"" . $row['category_image'] . "\", \"" . $row['category_name'] . "\", \"" . $row['id'] . "\")'>
                                            <span class='fw-bold'>Delete</span>
                                        </button> "; ?>
                                    </div>
                                    </div>
                                <?php } ?>
                                </div>
                            <?php
                        } else {
                            ?>
                                <script>
                                    var disableSearchInput = document.getElementById('FcSearchBox');
                                    disableSearchInput.disabled = true;
                                </script>
                            <?php
                            echo "
                                <div class='FcCardsDiv overflow-auto py-2 px-3 d-flex flex-column h-100 align-items-center justify-content-center'>
                                    <h1 class='h2 fw-bolder text-decoration-underline'>Nothing to Show</h1>
                                    <p class='h6 w-50 lh-base text-center'>We're sorry, but it looks like there are no details available for the food category at the moment. It's possible that there is no data to display. Please check back later.</p>
                                </div>
                            ";
                        }
                            ?>

                            <div class='FcCardsDivMsg overflow-auto py-2 px-3 flex-column h-100 align-items-center justify-content-center'>
                                <h1 class='h2 fw-bolder text-decoration-underline'>Nothing to Show</h1>
                                <p class='h6 w-50 lh-base text-center'>We're sorry, but it looks like there are no details available for the food category at the moment. It's possible that there is no data to display. Please check back later.</p>
                            </div>

                    </div>

            </div>

        </div>

    </div>


    <!-- ADD FOOD-CATEGORY MODAL -->
    <dialog class="addFcModal rounded" id="addFcModal">
        <div class="addFcModalContentDiv d-flex flex-column justify-content-center h-100">
            <h1 class="text-center display-5 fw-bold align-self-center p-0 m-0">Add Food Category</h1>
            <hr class="p-0 my-3">
            <form action="./foodcategory.php" method="post" enctype="multipart/form-data" class="d-flex flex-column align-items-center">
                <input type="text" name="food_category_name" id="FcName" class="FcName my-2 col-sm-10 rounded py-2 px-4 fw-medium" placeholder="Enter Food category" required>
                <input type="file" name="food_category_image" id="FcImage" class="FcImage my-3 col-sm-10 rounded fw-medium" required>
                <div class="col col-sm-10 d-flex gap-3 mt-2 align-items-center justify-content-between p-0">
                    <input type="submit" name="addFcBtn" class="addFcConfirmBtn w-50 py-2 fs-4 rounded" value="Add Food Category" onclick="">
                    <input type="submit" class="addFcCancelBtn w-50 py-2 fs-4 rounded bg-secondary-subtle" value="Cancel" onclick="addFcModal.close()">
                </div>
            </form>
        </div>
    </dialog>

    <!-- EDIT FOOD-CATEGORY MODAL -->
    <dialog class="editFcModal rounded p-3" id="editFcModal">
        <div class="editFcModalContentDiv d-flex flex-column align-items-center justify-content-between h-100">
            <h1 class="w-100 text-center display-6 fw-bold align-self-center p-0 m-0">Edit Food Category</h1>
            <hr class="w-100 p-0 my-1">
            <img src="" alt="food-category" class="editFcModalImg rounded" id="editFcModalImg">
            <form action="" method="post" enctype="multipart/form-data" class="d-flex flex-column align-items-center w-75">
                <input type="text" name="food_category_name" id="editFcName" class="editFcName my-2 col-sm-12 rounded py-2 px-4 fw-medium" placeholder="Enter Food category">
                <input type="text" name="food_category_name_compare" id="editFcNameCompare" class="editFcNameCompare" readonly hidden>
                <input type="text" name="food_category_id" id="editFcId" class="editFcId" readonly hidden>
                <input type="file" name="food_category_image" id="editFcImg" class="editFcImg my-3 col-sm-12 rounded fw-medium">
                <div class="col col-sm-12 d-flex gap-3 mt-2 align-items-center justify-content-between p-0">
                    <input type="submit" value="Edit Food Category" class="editFcBtn w-50 py-2 fs-4 rounded" id="editFcBtn">
                    <input type="submit" value="Cancel" class="editFcCancelBtn w-50 py-2 fs-4 rounded bg-secondary-subtle" id="editFcCancelBtn" onclick="editFcModal.close()">
                </div>
            </form>
        </div>
    </dialog>

    <!-- DELETE FOOD-CATEGORY MODAL -->
    <dialog class="deleteFcModal rounded" id="deleteFcModal">
        <div class="deleteFcModalContentDiv d-flex flex-column justify-content-between align-items-center h-100">
            <h1 class="deleteFcHeaderMsg w-100 text-center h3" id="deleteFcHeaderMsg">h</h1>
            <hr class="w-100 p-0 m-0">
            <img src="" alt="food-category" class="deleteFcImg rounded">
            <div class=" p-0 m-0 w-100 d-flex gap-3 align-items-center justify-content-center">
                <input type="submit" class="addTableConfirmBtn col-sm-5 p-2 fs-4 rounded" value="Yes" onclick="deleteFc()">
                <input type="submit" class="addTableCancelBtn col-sm-5 p-2 fs-4 rounded bg-secondary-subtle" value="No" onclick="deleteFcModal.close()">
            </div>
        </div>
    </dialog>



    <!-- SEARCH BAR SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const FcSearchBox = document.getElementById('FcSearchBox');
            const FcCardsDiv = document.querySelector('.FcCardsDiv');
            const FcCardsDivMsg = document.querySelector('.FcCardsDivMsg');

            FcSearchBox.addEventListener('input', function() {
                const searchValue = FcSearchBox.value.trim().toLowerCase();
                const cards = document.querySelectorAll('.FcCard');

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
                    FcCardsDivMsg.style.display = 'none';
                } else {
                    FcCardsDivMsg.style.display = 'flex';
                }

            });
        });
    </script>



    <!-- ADD FOOD-CATEGORY  -->
    <?php
    if (isset($_POST['addFcBtn'])) {
        $FcName = $_POST['food_category_name'];

        $FcImageGet = $_FILES['food_category_image']['tmp_name'];
        $name = addslashes($_FILES["food_category_image"]["tmp_name"]);
        $FcImageData = file_get_contents($FcImageGet);
        $FcImage = base64_encode($FcImageData);


        $checkIfFcExists = "SELECT * FROM food_category WHERE category_name=? ";
        $checkForFc = $conn->prepare($checkIfFcExists);
        $checkForFc->bind_param('s', $FcName);
        $checkForFc->execute();
        $checkForFcResult = $checkForFc->get_result();

        if ($checkForFcResult->num_rows >= 1) { ?>
            <script>
                window.location.href = "./foodcategory.php";
            </script>
            <?php
            $_SESSION['FcExists'] = "Food Category Already Exists...!";
        } else {
            $addFcQuery = "INSERT INTO food_category(category_name, category_image) VALUES ('$FcName', '$FcImage') ";
            $addFcRun = mysqli_query($conn, $addFcQuery);

            if ($addFcRun) {
            ?>
                <script>
                    window.location.href = "./foodcategory.php";
                </script>
            <?php
                $_SESSION["FcAdditionSuccess"] = "Food Category Added Successfully..!";
            } else {
            ?>
                <script>
                    window.location.href = "./foodcategory.php";
                </script>
    <?php
                $_SESSION["FcAdditionFailure"] = "Failed to add Food Category..!";
            }
        }
    }
    ?>



    <!-- UPDATE FOOD-CATEGORY STATUS-->
    <script>
        // Change table_status to 1
        <?php
        if (isset($_GET['disableId'])) {
            $FcDisableId = $_GET['disableId'];
            $FcDisableQuery = "UPDATE food_category SET category_status = '1' WHERE id=$FcDisableId ";
            $FcDisableRun = mysqli_query($conn, $FcDisableQuery);
            if ($FcDisableRun) { ?>
                window.location.href = './foodcategory.php#<?php echo $FcDisableId ?>';
            <?php
            } else {
            ?>
                window.location.href = './foodcategory.php';
            <?php
            }
            // Change table_status to 0
        } elseif (isset($_GET['availId'])) {
            ?>
            <?php
            $FcAvailId = $_GET['availId'];
            $FcAvailQuery = "UPDATE food_category SET category_status = '0' WHERE id=$FcAvailId ";
            $FcAvailRun = mysqli_query($conn, $FcAvailQuery);
            if ($FcAvailRun) { ?>
                window.location.href = './foodcategory.php#<?php echo $FcAvailId ?>';
            <?php
            } else {
            ?>
                window.location.href = './foodcategory.php';
        <?php
            }
        }
        ?>
    </script>



    <!-- DELETE FOOD CATEGORY MODAL -->
    <script>
        var deleteFcUrl;

        function openDeleteFcModal(deleteFcImgData, deleteFcName, deleteFcID) {
            var deleteFcModal = document.getElementById('deleteFcModal');
            var deleteFcImg = deleteFcModal.querySelector('.deleteFcImg');
            var deleteFcHeaderMsg = deleteFcModal.querySelector('#deleteFcHeaderMsg');

            // Create a temporary canvas
            var canvas = document.createElement('canvas');
            var context = canvas.getContext('2d');

            // Create a new image element
            var image = new Image();

            // Set the source of the new image to the QR image data
            image.src = 'data:image;base64,' + deleteFcImgData;

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
                    deleteFcImg.src = newImage.src;

                    // Set the table name in the modal header
                    deleteFcHeaderMsg.textContent = "Are you sure you want to delete '" + deleteFcName + "'?";

                    deleteFcUrl = "./foodcategory.php?deleteFcId=" + deleteFcID;

                    // Open the modal
                    deleteFcModal.showModal();
                }, 'image/jpeg', 1.0); // 1.0 means full quality
            };
        }


        function deleteFc() {
            window.location.href = deleteFcUrl;
            // console.log(deleteFcUrl);
        }
    </script>



    <!-- EDIT FOOD CATEGORY MODAL -->
    <script>
        // var editFcUrl;

        function openEditFcModal(editFcImgData, editFcName, editFcID) {
            var editFcModal = document.getElementById('editFcModal');
            var editFcModalImg = editFcModal.querySelector('.editFcModalImg');
            document.getElementById('editFcName').value = editFcName;
            document.getElementById('editFcId').value = editFcID;
            document.getElementById('editFcNameCompare').value = editFcName;

            // Create a temporary canvas
            var canvas = document.createElement('canvas');
            var context = canvas.getContext('2d');

            // Create a new image element
            var image = new Image();

            // Set the source of the new image to the QR image data
            image.src = 'data:image;base64,' + editFcImgData;

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
                    editFcModalImg.src = newImage.src;

                    // Set the table name in the modal header
                    // editFcName.value = editFcName;

                    // editFcUrl = "./foodcategory.php?editFcId=" + editFcID;

                    // Open the modal
                    editFcModal.showModal();
                }, 'image/jpeg', 1.0); // 1.0 means full quality
            };
        }
    </script>



    <!-- DELETE FOOD CATEGORY -->
    <?php
    if (isset($_GET['deleteFcId'])) {
        $deleteFcId = $_GET['deleteFcId'];

        $deleteFcQuery = "DELETE FROM food_category WHERE id='$deleteFcId' ";
        $deleteFcRun = mysqli_query($conn, $deleteFcQuery);

        if ($deleteFcRun) {
    ?>
            <script>
                window.location.href = "./foodcategory.php";
            </script>
        <?php
            $_SESSION["FcDeletionSuccess"] = "Food Category Deleted Successfully..!";
        } else {
        ?>
            <script>
                window.location.href = "./foodcategory.php";
            </script>
    <?php
            $_SESSION["FcDeletionFailure"] = "Failed to delete Food Category..!";
        }
    }
    ?>



</body>

</html>