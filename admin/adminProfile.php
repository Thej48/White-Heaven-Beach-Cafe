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

                $photo = base64_encode(base64_decode($row['admin_photo']));
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

            <?php
            $randomNumber = rand(103, 999);
            $adminId = 'WHBC' . $randomNumber;

            $getAdminID = "SELECT * FROM admin WHERE admin_id=?";
            $prepareAdminID = $conn->prepare($getAdminID);
            $prepareAdminID->bind_param("i", $adminId);
            $prepareAdminID->execute();
            $AdminIDResult = $prepareAdminID->get_result();
            if ($AdminIDResult->num_rows >= 1) {
                $randomNumber = rand(103, 999);
                $adminId = 'WHBC' . $randomNumber;
            } else {
                $adminId = 'WHBC' . $randomNumber;
            }

            ?>

            <div class="col-sm-10 d-flex flex-column dashboardBodyDiv">
                <div class="py-2 m-0 d-flex align-items-center tableHeaderControlDiv border-bottom border-dark-subtle">
                    <h1 class="p-0 m-0 h2 w-50 fw-medium adminProfileHeader">Admin Profile</h1>
                    <div class="tableSearchAdd w-50 d-flex gap-3 justify-content-end">
                        <!-- <input type="search" name="tableSearchBox" id="tableSearchBox" placeholder="Search here..." class="col-sm-8 rounded tableSearchBox"> -->
                        <?php if ($row['role'] == 'SuperAdmin') { ?>
                            <input type="submit" name="addAdminBtn" value="Add Admin" class="col-sm-3 rounded addAdminBtn" onclick="openAddAdminModal('<?php echo $adminId; ?>')">
                        <?php } ?>
                    </div>
                </div>

                <?php
                $getAdminData = "SELECT * FROM admin ORDER BY id";
                $getAdminDataRes = mysqli_query($conn, $getAdminData);
                ?>

                <?php if ($getAdminDataRes->num_rows > 0) { ?>
                    <div class="overflow-auto py-3 px-2 container align-self-center  gap-3 adminCardsDiv">
                        <?php while ($data = $getAdminDataRes->fetch_assoc()) {
                            $adminProfile = base64_decode($data['admin_photo']); ?>

                            <div class="card bg-white border border-secondary-subtle shadow-sm rounded-1 col col-sm-auto w-100 p-2 FoodCard">
                                <div class="adminDetailsDiv d-flex gap-2">
                                    <img src="data:image;base64,<?php echo base64_encode($adminProfile) ?>" alt="<?php echo $data['admin_name']; ?>" class="adminProfilePic rounded-1">
                                    <div class="adminDetails d-flex flex-column justify-content-between w-100">
                                        <div class="adminInfo">

                                            <?php if ($row['role'] == 'SuperAdmin') { ?>
                                                <h1 class="m-0 p-0 fs-5 fw-bold text-light-emphasis"><?php echo $data['admin_id']; ?></h1>
                                            <?php } elseif ($row['role'] == 'Admin') { ?>
                                                <?php if ($data['admin_id'] == $admin_id) { ?>
                                                    <h1 class="m-0 p-0 fs-5 fw-bold text-light-emphasis"><?php echo $data['admin_id']; ?></h1>
                                                <?php } else { ?>
                                                    <?php $data['admin_id'] = substr_replace($data['admin_id'], 'XXX', -3); ?>
                                                    <h1 class="m-0 p-0 fs-5 fw-bold text-light-emphasis"><?php echo $data['admin_id']; ?></h1>
                                                <?php } ?>
                                            <?php } ?>

                                            <h1 class="m-0 p-0 fs-5"><?php echo $data['admin_name']; ?></h1>
                                            <h1 class="m-0 p-0 fs-6 my-1 fw-bold text-secondary">( <?php echo $data['role']; ?> )</h1>

                                            <?php if ($row['role'] == 'SuperAdmin') { ?>
                                                <h1 class="m-0 p-0 fs-6 my-1 fw-bold"><a href="mailto:<?php echo $data['admin_email']; ?>"><?php echo $data['admin_email']; ?></a></h1>
                                            <?php } elseif ($row['role'] == 'Admin') { ?>
                                                <?php if ($data['admin_id'] == $admin_id) { ?>
                                                    <h1 class="m-0 p-0 fs-6 my-1 fw-bold"><a href="mailto:<?php echo $data['admin_email']; ?>"><?php echo $data['admin_email']; ?></a></h1>
                                                <?php } else { ?>
                                                    <?php $data['admin_id'] = substr_replace($data['admin_id'], 'XXX', -3); ?>
                                                    <?php
                                                    list($username, $domain) = explode('@', $data['admin_email'], 2);
                                                    $username = str_repeat('x', strlen($username));
                                                    $domain = substr_replace(str_repeat('x', strlen($domain)), '.com', -4);
                                                    $maskedEmail = $username . '@' . $domain;
                                                    ?>
                                                    <h1 class="m-0 p-0 fs-6 my-1 fw-bold"><?php echo $maskedEmail; ?></h1>
                                                <?php } ?>
                                            <?php } ?>

                                            <?php if ($row['role'] == 'SuperAdmin') { ?>
                                                <h1 class="m-0 p-0 fs-6 my-1 fw-bold"><a href="tel:+91 <?php echo $data['admin_phone']; ?>">+91 <?php echo $data['admin_phone']; ?></a></h1>
                                            <?php } elseif ($row['role'] == 'Admin') { ?>
                                                <?php if ($data['admin_id'] == $admin_id) { ?>
                                                    <h1 class="m-0 p-0 fs-6 my-1 fw-bold"><a href="tel:+91 <?php echo $data['admin_phone']; ?>">+91 <?php echo $data['admin_phone']; ?></a></h1>
                                                <?php } else { ?>
                                                    <?php $maskedPhone = substr($data['admin_phone'], 0, -5) . 'XXXXX'; ?>
                                                    <h1 class="m-0 p-0 fs-6 my-1 fw-bold">+91 <?php echo $maskedPhone; ?></h1>
                                                <?php } ?>
                                            <?php } ?>

                                        </div>
                                        <hr class="p-0 m-0 my-1">
                                        <div class="adminControlsDiv d-flex gap-2">
                                            <?php if ($row['role'] == 'SuperAdmin') { ?>
                                                <?php if ($data['admin_id'] == $admin_id) { ?>
                                                    <?php echo "
                                                    <input type='submit' value='Edit' class='editAdminBtn w-50 rounded py-1 fw-medium' id='editAdminBtn' onClick='openEditAdminModal(\"" . $data['admin_photo'] . "\", \"" . $data['admin_name'] . "\", \"" . $data['id'] . "\", \"" . $data['admin_email'] . "\", \"" . $data['admin_phone'] . "\")'>
                                                    <input type='submit' value='Delete' class='deleteAdminBtn bg-secondary-subtle w-50 rounded py-1 fw-bold' id='deleteAdminBtn' style='pointer-events: none;' disabled readonly> "; ?>
                                                <?php } else { ?>
                                                    <?php echo "
                                                    <input type='submit' value='Edit' class='editAdminBtn w-50 rounded py-1 fw-medium' id='editAdminBtn' onClick='openEditAdminModal(\"" . $data['admin_photo'] . "\", \"" . $data['admin_name'] . "\", \"" . $data['id'] . "\", \"" . $data['admin_email'] . "\", \"" . $data['admin_phone'] . "\")'>
                                                    <input type='submit' value='Delete' class='deleteAdminBtn bg-secondary-subtle w-50 rounded py-1 fw-bold' id='deleteAdminBtn' onClick='openDeleteAdminModal(\"" . $data['admin_photo'] . "\", \"" . $data['admin_name'] . "\", \"" . $data['id'] . "\")'> "; ?>
                                                <?php } ?>
                                            <?php } elseif ($row['role'] == 'Admin') { ?>
                                                <?php if ($data['admin_id'] == $admin_id) { ?>
                                                    <?php echo "
                                                    <input type='submit' value='Edit' class='editAdminBtn w-50 rounded py-1 fw-medium' id='editAdminBtn' onClick='openEditAdminModal(\"" . $data['admin_photo'] . "\", \"" . $data['admin_name'] . "\", \"" . $data['id'] . "\", \"" . $data['admin_email'] . "\", \"" . $data['admin_phone'] . "\")'>
                                                    <input type='submit' value='Delete' class='deleteAdminBtn bg-secondary-subtle w-50 rounded py-1 fw-bold' id='deleteAdminBtn' style='pointer-events: none;' disabled readonly> "; ?>
                                                <?php } else { ?>
                                                    <?php echo "
                                                    <input type='submit' value='Edit' class='editAdminBtn w-50 rounded py-1 fw-medium' id='editAdminBtn' onClick='openEditAdminModal(\"" . $data['admin_photo'] . "\", \"" . $data['admin_name'] . "\", \"" . $data['id'] . "\", \"" . $data['admin_email'] . "\", \"" . $data['admin_phone'] . "\")' style='pointer-events: none;' disabled readonly>
                                                    <input type='submit' value='Delete' class='deleteAdminBtn bg-secondary-subtle w-50 rounded py-1 fw-bold' id='deleteAdminBtn' style='pointer-events: none;' disabled readonly> "; ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>

            </div>

        </div>

    </div>



    <script>
        function openAddAdminModal(adminId) {
            addAdminModal.showModal();
            document.getElementById('admin_id').value = adminId;
        }
    </script>



    <!-- ADD ADMIN MODAL -->
    <dialog class="addAdminModal rounded p-3" id="addAdminModal">
        <div class="addAdminModalContentDiv h-100 d-flex flex-column p-0 m-0 align-self-center align-items-center justify-content-center">
            <h1 class="m-0 p-0 display-6 fw-bold">Add Admin</h1>
            <hr class="w-100 my-2">
            <form action="./adminProfile.php" method="post" enctype="multipart/form-data" class="d-flex flex-column align-items-center w-75">
                <input type="text" name="admin_id" placeholder="Enter Admin ID" id="admin_id" class="admin_id col-sm-11 my-2 py-2 px-4 rounded" required readonly>
                <input type="text" name="admin_name" placeholder="Enter Admin Name" id="admin_name" class="admin_name col-sm-11 my-2 py-2 px-4 rounded" required>
                <input type="text" name="admin_email" placeholder="Enter Email ID" id="admin_email" class="admin_email col-sm-11 my-2 py-2 px-4 rounded" required>
                <input type="text" name="admin_phone" placeholder="Enter Phone Number" id="admin_phone" class="admin_phone col-sm-11 my-2 py-2 px-4 rounded" required>
                <input type="file" name="admin_photo" id="admin_photo" class="col-sm-11 my-2 rounded admin_photo" required>
                <input type="text" name="admin_password" placeholder="Enter Password" id="admin_password" class="admin_password col-sm-11 my-2 py-2 px-4 rounded" required>
                <div class="addAdminControlDiv col-sm-11 d-flex align-items-center justify-content-center gap-3 mt-2">
                    <input type="submit" value="Add Admin" class="addAdminConfirmBtn w-50 py-2 fs-4 rounded">
                    <input type="submit" value="Cancel" class="addAdminCancelBtn w-50 py-2 fs-4 rounded bg-secondary-subtle" onclick="addAdminModal.close()">
                </div>
            </form>
        </div>
    </dialog>



    <!-- EDIT ADMIN MODAL -->
    <dialog class="editAdminModal rounded p-3" id="editAdminModal">
        <div class="editAdminModalContentDiv h-100 d-flex flex-column p-0 m-0 align-self-center align-items-center justify-content-center">
            <h1 class="m-0 p-0 display-6 fw-bold">Add Admin</h1>
            <hr class="w-100 my-2">
            <img src="" alt="admin-image" class="adminImgEditModal rounded">
            <form action="./adminProfile.php" method="post" enctype="multipart/form-data" class="d-flex flex-column align-items-center w-75">
                <!-- <input type="text" name="admin_id" placeholder="Enter Admin ID" id="admin_id" class="admin_id col-sm-11 my-2 py-2 px-4 rounded" required readonly> -->
                <input type="text" name="edit_admin_id" placeholder="Enter Admin ID" id="edit_admin_id" class="edit_admin_id col-sm-11 my-2 py-2 px-4 rounded" readonly hidden required>
                <input type="text" name="edit_admin_name" placeholder="Enter Admin Name" id="edit_admin_name" class="edit_admin_name col-sm-11 my-2 py-2 px-4 rounded" required>
                <input type="text" name="edit_admin_email" placeholder="Enter Email ID" id="edit_admin_email" class="edit_admin_email col-sm-11 my-2 py-2 px-4 rounded" required>
                <input type="text" name="edit_admin_phone" placeholder="Enter Phone Number" id="edit_admin_phone" class="edit_admin_phone col-sm-11 my-2 py-2 px-4 rounded" required>
                <input type="file" name="edit_admin_photo" id="edit_admin_photo" class="col-sm-11 my-2 rounded edit_admin_photo">
                <!-- <input type="text" name="admin_password" placeholder="Enter Password" id="admin_password" class="admin_password col-sm-11 my-2 py-2 px-4 rounded" required> -->
                <div class="editAdminControlDiv col-sm-11 d-flex align-items-center justify-content-center gap-3 mt-2">
                    <input type="submit" value="Add Admin" class="editAdminConfirmBtn w-50 py-2 fs-4 rounded">
                    <input type="submit" value="Cancel" class="editAdminCancelBtn w-50 py-2 fs-4 rounded bg-secondary-subtle" onclick="editAdminModal.close()">
                </div>
            </form>
        </div>
    </dialog>



    <!-- DELETE ADMIN MODAL -->
    <dialog class="deleteAdminModal rounded" id="deleteAdminModal">
        <div class="deleteAdminModalContentDiv d-flex flex-column justify-content-between align-items-center h-100">
            <h1 class="deleteAdminHeaderMsg w-100 text-center h3" id="deleteAdminHeaderMsg">h</h1>
            <hr class="w-100 p-0 m-0">
            <img src="" alt="food-category" class="deleteAdminImg rounded">
            <div class=" p-0 m-0 w-100 d-flex gap-3 align-items-center justify-content-center">
                <input type="submit" class="addTableConfirmBtn col-sm-5 p-2 fs-4 rounded" value="Yes" onclick="deleteAdmin()">
                <input type="submit" class="addTableCancelBtn col-sm-5 p-2 fs-4 rounded bg-secondary-subtle" value="No" onclick="deleteAdminModal.close()">
            </div>
        </div>
    </dialog>




    <!-- DELETE FOOD CATEGORY MODAL -->
    <script>
        var deleteAdminURL;

        function openDeleteAdminModal(deleteAdminImgData, deleteAdminName, deleteAdminID) {
            var deleteAdminModal = document.getElementById('deleteAdminModal');
            var deleteAdminImg = deleteAdminModal.querySelector('.deleteAdminImg');
            var deleteAdminHeaderMsg = deleteAdminModal.querySelector('#deleteAdminHeaderMsg');

            // Create a temporary canvas
            var canvas = document.createElement('canvas');
            var context = canvas.getContext('2d');

            // Create a new image element
            var image = new Image();

            // Set the source of the new image to the QR image data
            image.src = 'data:image;base64,' + deleteAdminImgData;

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
                    deleteAdminImg.src = newImage.src;

                    // Set the table name in the modal header
                    deleteAdminHeaderMsg.textContent = "Are you sure you want to delete '" + deleteAdminName + "'?";

                    deleteAdminURL = "./adminProfile.php?deleteAdminId=" + deleteAdminID;

                    // Open the modal
                    deleteAdminModal.showModal();
                }, 'image/jpeg', 1.0); // 1.0 means full quality
            };
        }


        function deleteAdmin() {
            window.location.href = deleteAdminURL;
            // console.log(deleteAdminURL);
        }
    </script>



    <!-- DELETE FOOD-ITEM -->
    <script>
        <?php
        if (isset($_GET['deleteAdminId'])) {
            $deleteAdminId = $_GET['deleteAdminId'];
            $deleteAdminQuery = "DELETE FROM admin WHERE id='$deleteAdminId' ";
            $deleteAdminRun = mysqli_query($conn, $deleteAdminQuery);
            if ($deleteAdminRun) { ?>
                window.location.href = "./adminProfile.php";
            <?php $_SESSION["FoodDeletionSuccess"] = "Food Category Deleted Successfully..!";
            } else { ?>
                window.location.href = "./adminProfile.php";
        <?php $_SESSION["FoodDeletionFailure"] = "Failed to delete Food Category..!";
            }
        }
        ?>
    </script>



    <!-- EDIT ADMIN DATA FUNCTION -->
    <script>
        function openEditAdminModal(editAdminImgData, editAdminName, editAdminID, editAdminEmail, editAdminPhone) {
            var editAdminModal = document.getElementById('editAdminModal');
            var editAdminImg = editAdminModal.querySelector('.adminImgEditModal');
            document.getElementById('edit_admin_name').value = editAdminName;
            document.getElementById('edit_admin_id').value = editAdminID;
            document.getElementById('edit_admin_phone').value = editAdminPhone;
            document.getElementById('edit_admin_email').value = editAdminEmail;

            // Create a temporary canvas
            var canvas = document.createElement('canvas');
            var context = canvas.getContext('2d');

            // Create a new image element
            var image = new Image();

            // Set the source of the new image to the QR image data
            image.src = 'data:image;base64,' + editAdminImgData;

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
                    editAdminImg.src = newImage.src;

                    editAdminModal.showModal();
                }, 'image/jpeg', 1.0); // 1.0 means full quality
            };
        }
    </script>



    <!-- EDIT ADMIN DATA -->
    <script>
        <?php
        if (isset($_POST['edit_admin_id'])) {
            $editAdminId = $_POST['edit_admin_id'];

            // EDIT WITHOUT IMAGE
            if (
                isset($_POST['edit_admin_name'])
                && isset($_POST['edit_admin_email'])
                && isset($_POST['edit_admin_phone'])
            ) {
                $editAdminName = $_POST['edit_admin_name'];
                $editAdminEmail = $_POST['edit_admin_email'];
                $editAdminPhone = $_POST['edit_admin_phone'];


                $editAdminQuery = "UPDATE admin SET admin_name='$editAdminName', admin_email='$editAdminEmail', admin_phone='$editAdminPhone' WHERE id='$editAdminId' ";
                $editAdminRun = mysqli_query($conn, $editAdminQuery);

                if ($editAdminRun) {
        ?>
                    window.location.href = "./adminProfile.php";
                <?php
                    $_SESSION["FoodDetailsUpdated"] = "Food Item Updated Successfully..!";
                } else {
                ?>
                    window.location.href = "./adminProfile.php";
                    <?php
                    $_SESSION["FoodDetailsUpdationFailed"] = "Failed to Update Food Item..!";
                }
            }

            // EDIT WITH IMAGE
            if (
                isset($_POST['edit_admin_name'])
                && isset($_POST['edit_admin_email'])
                && isset($_POST['edit_admin_phone'])
                && isset($_FILES['edit_admin_photo'])
            ) {
                $editAdminName = $_POST['edit_admin_name'];
                $editAdminEmail = $_POST['edit_admin_email'];
                $editAdminPhone = $_POST['edit_admin_phone'];
                $adminImageData = $_FILES['edit_admin_photo'];
                if ($_FILES['edit_admin_photo']['error'] == UPLOAD_ERR_OK) {
                    $editAdminImageGet = $_FILES['edit_admin_photo']['tmp_name'];
                    $editAdminImageData = file_get_contents($editAdminImageGet);
                    $editAdminImage = base64_encode($editAdminImageData);

                    $editAdminQuery = "UPDATE admin SET admin_name='$editAdminName', admin_email='$editAdminEmail', admin_phone='$editAdminPhone' , admin_photo='$editAdminImage' WHERE id='$editAdminId' ";
                    $editAdminRun = mysqli_query($conn, $editAdminQuery);

                    if ($editAdminRun) {
                    ?>
                        window.location.href = "./adminProfile.php";
                    <?php
                        $_SESSION["FoodDetailsUpdated"] = "Food Item Updated Successfully..!";
                    } else {
                    ?>
                        window.location.href = "./adminProfile.php";
        <?php
                        $_SESSION["FoodDetailsUpdationFailed"] = "Failed to Update Food Item..!";
                    }
                }
            }
        }

        ?>
    </script>



    <!-- ADD ADMIN -->
    <script>
        <?php
        if (isset($_POST['admin_id']) && isset($_POST['admin_name']) && isset($_POST['admin_email']) && isset($_POST['admin_phone']) && isset($_FILES['admin_photo']) && isset($_POST['admin_password'])) {
            $admin_id = $_POST['admin_id'];
            $admin_name = $_POST['admin_name'];
            $admin_email = $_POST['admin_email'];
            $admin_phone = $_POST['admin_phone'];
            $admin_password = md5($_POST['admin_password']);

            $GetAdminImg = $_FILES['admin_photo']['tmp_name'];
            $name = addslashes($_FILES["admin_photo"]["tmp_name"]);
            $AdminImageData = file_get_contents($GetAdminImg);
            $admin_photo = base64_encode($AdminImageData);

            $checkIfAdminExists = "SELECT * FROM admin WHERE admin_id=? ";
            $checkForAdmin = $conn->prepare($checkIfAdminExists);
            $checkForAdmin->bind_param('s', $admin_id);
            $checkForAdmin->execute();
            $checkForAdminResult = $checkForAdmin->get_result();

            if ($checkForAdminResult->num_rows >= 1) { ?>
                window.location.href = "./adminProfile.php";
                <?php } else {
                $addAdminQuery = "INSERT INTO admin(admin_id, admin_name, admin_email, admin_phone, admin_password, admin_photo) VALUES ('$admin_id', '$admin_name','$admin_email', '$admin_phone','$admin_password','$admin_photo') ";
                $addAdminRun = mysqli_query($conn, $addAdminQuery);
                if ($addAdminRun) { ?>
                    window.location.href = "./adminProfile.php";
                <?php } else { ?>
                    window.location.href = "./adminProfile.php";
        <?php }
            }
        }
        ?>
    </script>


</body>

</html>