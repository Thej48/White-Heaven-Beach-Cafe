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
                        <li class="navBarItem activeNavItem rounded">
                            <a href="./tables.php" class="d-flex gap-1 align-items-center fw-medium py-2 px-2">
                                <img src="../icons/tables_white.png" alt="tables">
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

                <div class="py-2 m-0 d-flex align-items-center tableHeaderControlDiv border-bottom border-dark-subtle">
                    <h1 class="p-0 m-0 h2 w-50 fw-medium">Tables</h1>
                    <div class="tableSearchAdd w-50 d-flex gap-3 justify-content-end">
                        <input type="search" name="tableSearchBox" id="tableSearchBox" placeholder="Search here..." class="col-sm-8 rounded tableSearchBox">
                        <input type="submit" value="Add Table" class="col-sm-3 rounded addTableBtn" onclick="addTableModal.showModal()">
                    </div>
                </div>

                <?php
                $getTableQuery = "SELECT * FROM tables ORDER BY table_number";
                $getTableRes = $conn->query($getTableQuery);
                $table_number = array();

                if ($getTableRes->num_rows > 0) {
                ?>
                    <div class="tableCardsDiv overflow-auto py-2 px-3 d-flex flex-wrap row row-cols-auto ">
                        <?php
                        while ($row = $getTableRes->fetch_assoc()) {
                            echo "
                        <div class='card p-3 shadow-sm m-2 rounded-1 col tableCard'>
                            <h1 class='h3 fw-bold p-0 m-0'>Table " . $row['table_number'] . "</h1>
                            <hr class='my-2'/>
                            <img src='data:image;base64," . $row['table_qr'] . "' alt='qr-image' class='tableQrImg'>
                            <hr class='my-2'/>
                            ";
                            if ($row['table_status'] == 0) {
                        ?>
                                <div class='tableStatusDiv d-flex align-items-center justify-content-between'>
                                    <span class="fw-bold">Status : <span class="fw-medium">Available</span></span>
                                    <img class="tableStatusChangeBtn" src='../icons/toggle-off.svg' alt='table-available'>
                                </div>
                            <?php } elseif ($row['table_status'] == 1) { ?>
                                <div class='tableStatusDiv d-flex align-items-center justify-content-between'>
                                    <span class="fw-bold">Status : <span class="fw-medium">Occupied</span></span>
                                    <img class="tableStatusChangeBtn" src='../icons/toggle-on.svg' alt='table-available'>
                                </div>
                            <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php
                } else {
                    echo "
                    <div class='tableCardsDiv overflow-auto py-2 px-3 d-flex flex-column h-100 align-items-center justify-content-center'>
                        <h1 class='h2 fw-bolder text-decoration-underline'>Nothing to Show</h1>
                        <p class='h6 w-50 lh-base text-center'>We're sorry, but it looks like there are no details available for the tables at the moment. It's possible that there is no data to display. Please check back later.</p>
                    </div>
                ";
                }
        ?>

        <div class='tableCardsDivMsg overflow-auto py-2 px-3 flex-column h-100 align-items-center justify-content-center'>
            <h1 class='h2 fw-bolder text-decoration-underline'>Nothing to Show</h1>
            <p class='h6 w-50 lh-base text-center'>We're sorry, but it looks like there are no details available for the tables at the moment. It's possible that there is no data to display. Please check back later.</p>
        </div>

        </div>

    </div>

    </div>

    <!-- SEARCH BAR SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableSearchBox = document.getElementById('tableSearchBox');
            const tableCardsDiv = document.querySelector('.tableCardsDiv');
            const tableCardsDivMsg = document.querySelector('.tableCardsDivMsg');

            tableSearchBox.addEventListener('input', function() {
                const searchValue = tableSearchBox.value.trim().toLowerCase();
                const cards = document.querySelectorAll('.tableCard');

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
                    tableCardsDivMsg.style.display = 'none';
                } else {
                    tableCardsDivMsg.style.display = 'flex';
                }

            });
        });
    </script>


    <!-- ADD NEW TABLE -->
    <script>
        function addTable() {
            // Open Add Table Modal
            addTableModal.showModal();

            <?php
            $checkTableCount = "SELECT * FROM tables ORDER BY table_number ASC";
            $query = mysqli_query($conn, $checkTableCount);
            $res = mysqli_num_rows($query);
            if ($res == 0) {
                $tablenumber = 1;
            } else {
                // Move the internal result pointer to the last row
                mysqli_data_seek($query, $res - 1);
                $row = mysqli_fetch_array($query);
                $newtablenumber = $row['table_number'];
                $tablenumber = $newtablenumber + 1;
            }
            ?>
        }


        function generateQRCode() {
            window.location.href = "<?php echo "./generateQR.php?tableNo=$tablenumber" ?>";
        }
    </script>

    <!-- ADD TABLE MODAL -->
    <dialog class="addTableModal" id="addTableModal">
        <h1>Do you want to add <span>Table - <?php echo "$tablenumber"; ?></span></h1>
        <input type="submit" value="Yes" onclick="generateQRCode()">
        <input type="submit" value="No" onclick="addTableModal.close()">
    </dialog>

</body>

</html>