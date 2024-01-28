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

            <div class="col-sm-10 d-flex flex-column dashboardBodyDiv">
                <div class="py-2 m-0 d-flex align-items-center tableHeaderControlDiv border-bottom border-dark-subtle">
                    <h1 class="p-0 m-0 h2 w-50 fw-medium adminProfileHeader">Admin Profile</h1>
                    <div class="tableSearchAdd w-50 d-flex gap-3 justify-content-end">
                        <!-- <input type="search" name="tableSearchBox" id="tableSearchBox" placeholder="Search here..." class="col-sm-8 rounded tableSearchBox"> -->
                        <?php if ($row['role'] == 'SuperAdmin') { ?>
                            <input type="submit" value="Add Admin" class="col-sm-3 rounded addAdminBtn" onclick="addAdminModal.showModal()">
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
                            $adminProfile = base64_encode($data['admin_photo']); ?>
                            <div class="card bg-white border border-secondary-subtle shadow-sm rounded-1 col col-sm-auto w-100 p-2 FoodCard">
                                <div class="adminDetailsDiv d-flex gap-2">
                                    <img src="data:image;base64,<?php echo $adminProfile ?>" alt="<?php echo $data['admin_name']; ?>" class="adminProfilePic rounded-1">
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
                                                    <input type="submit" value="Edit" class="editAdminBtn w-50 rounded py-1 fw-medium" id="editAdminBtn">
                                                    <input type="submit" value="Delete" class="deleteAdminBtn bg-secondary-subtle w-50 rounded py-1 fw-bold" id="deleteAdminBtn" style="pointer-events: none;" disabled readonly>
                                                <?php } else { ?>
                                                    <input type="submit" value="Edit" class="editAdminBtn w-50 rounded py-1 fw-medium" id="editAdminBtn">
                                                    <input type="submit" value="Delete" class="deleteAdminBtn bg-secondary-subtle w-50 rounded py-1 fw-bold" id="deleteAdminBtn">
                                                <?php } ?>
                                            <?php } elseif ($row['role'] == 'Admin') { ?>
                                                <?php if ($data['admin_id'] == $admin_id) { ?>
                                                    <input type="submit" value="Edit" class="editAdminBtn w-50 rounded py-1 fw-medium" id="editAdminBtn">
                                                    <input type="submit" value="Delete" class="deleteAdminBtn bg-secondary-subtle w-50 rounded py-1 fw-bold" id="deleteAdminBtn" style="pointer-events: none;" disabled readonly>
                                                <?php } else { ?>
                                                    <input type="submit" value="Edit" class="editAdminBtn w-50 rounded py-1 fw-medium" id="editAdminBtn" style="pointer-events: none;" disabled readonly>
                                                    <input type="submit" value="Delete" class="deleteAdminBtn bg-secondary-subtle w-50 rounded py-1 fw-bold" id="deleteAdminBtn" style="pointer-events: none;" disabled readonly>
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

    <dialog class="addAdminModal rounded p-3" id="addAdminModal">
        <div class="addAdminModalContentDiv h-100 d-flex flex-column p-0 m-0 align-self-center align-items-center justify-content-center">
            <h1 class="m-0 p-0 display-6 fw-bold">Add Admin</h1>
            <hr class="w-100 my-2">
            <form action="" method="post" enctype="multipart/form-data" class="d-flex flex-column align-items-center w-75">
                <input type="text" name="admin_id" placeholder="Enter Admin ID" id="admin_id" class="admin_id col-sm-11 my-2 py-2 px-4 rounded" required>
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

</body>

</html>