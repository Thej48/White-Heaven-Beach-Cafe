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
                            if ($row['table_status'] == 0) { ?>
                                <div class='card bg-white border border-secondary-subtle p-3 shadow-sm m-2 rounded-1 col tableCard' id="<?php echo $row['id']; ?>">
                                <?php } elseif ($row['table_status'] == 1) { ?>
                                    <div class='card bg-info bg-opacity-10 border border-secondary-subtle p-3 shadow-sm m-2 rounded-1 col tableCard' id="<?php echo $row['id']; ?>">
                                    <?php }
                                echo "
                            <h1 class='h3 fw-bold p-0 m-0'>Table " . $row['table_number'] . "</h1>
                            <hr class='my-2'/>
                            <img src='data:image;base64," . $row['table_qr'] . "' alt='qr-image' class='tableQrImg' onClick='openQrModal(\"" . $row['table_qr'] . "\", \"" . $row['table_number'] . "\")'>
                            <hr class='my-2'/>
                            ";
                                if ($row['table_status'] == 0) {
                                    ?>
                                        <div class='tableStatusDiv d-flex align-items-center justify-content-between'>
                                            <span class="fw-bold">Status : <span class="fw-medium">Available</span></span>
                                            <img class="tableStatusChangeBtn" src='../icons/toggle-off.svg' onclick="document.location.href='./tables.php?reserveId=<?php echo $row['id']; ?>'" alt='table-available'>
                                        </div>
                                    <?php } elseif ($row['table_status'] == 1) { ?>
                                        <div class='tableStatusDiv d-flex align-items-center justify-content-between'>
                                            <span class="fw-bold">Status : <span class="fw-medium">Occupied</span></span>
                                            <img class="tableStatusChangeBtn" src='../icons/toggle-on.svg' onclick="document.location.href='./tables.php?freeId=<?php echo $row['id']; ?>'" alt='table-occupied'>
                                        </div>
                                    <?php } ?>
                                    </div>
                                <?php } ?>
                                </div>
                            <?php
                        } else {
                            ?>
                                <script>
                                    var disableSearchInput = document.getElementById('tableSearchBox');
                                    disableSearchInput.disabled = true;
                                </script>
                            <?php
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


        <!-- SCRIPT TO UPDATE TABLE_STATUS -->
        <script>
            // Change table_status to 1
            <?php
            if (isset($_GET['reserveId'])) {
                $tableResId = $_GET['reserveId'];
                $reserveTableQuery = "UPDATE tables SET table_status = '1' WHERE id=$tableResId ";
                $reserveTableRun = mysqli_query($conn, $reserveTableQuery);
                if ($reserveTableRun) { ?>
                    window.location.href = './tables.php#<?php echo $tableResId ?>';
                <?php
                } else {
                ?>
                    window.location.href = './tables.php';
                <?php
                }
                // Change table_status to 0
            } elseif (isset($_GET['freeId'])) {
                ?>
                <?php
                $tableFreeId = $_GET['freeId'];
                $freeTableQuery = "UPDATE tables SET table_status = '0' WHERE id=$tableFreeId ";
                $freeTableRun = mysqli_query($conn, $freeTableQuery);
                if ($freeTableRun) { ?>
                    window.location.href = './tables.php#<?php echo $tableFreeId ?>';
                <?php
                } else {
                ?>
                    window.location.href = './tables.php';
            <?php
                }
            }
            ?>
        </script>



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



        <!-- OPEN QR-DOWNLOAD MODAL AND SET IMAGE AND TABLE NUMBER -->
        <script>
            var tableName;

            function openQrModal(qrImageData, tableNameValue) {

                tableName = tableNameValue;

                var qrDownloadModal = document.getElementById('qrDownloadModal');
                var qrImage = qrDownloadModal.querySelector('.modalQrImage');
                var tableNameSpan = qrDownloadModal.querySelector('#modalTableName');

                // Create a temporary canvas
                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');

                // Create a new image element
                var image = new Image();

                // Set the source of the new image to the QR image data
                image.src = 'data:image;base64,' + qrImageData;

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
                        qrImage.src = newImage.src;

                        // Set the table name in the modal header
                        tableNameSpan.textContent = "Table-" + tableName;

                        // Open the modal
                        qrDownloadModal.showModal();
                    }, 'image/jpeg', 1.0); // 1.0 means full quality
                };
            }
        </script>



        <!-- DOWNLOAD QR-CODE IMAGE -->
        <script>
            // Download QR Code 
            function downloadQRCode() {
                // Get the QR code image element
                var qrCodeImage = document.getElementById('modalQrImage');

                // Check if the image source is not empty
                if (qrCodeImage.src !== "") {
                    // Create a temporary anchor element
                    var downloadLink = document.createElement('a');

                    // Set the download link's href to the image source
                    downloadLink.href = qrCodeImage.src;

                    // Set the download attribute with a suggested filename
                    downloadLink.download = 'Table-'+tableName+'.png';

                    // Append the download link to the document body
                    document.body.appendChild(downloadLink);

                    // Trigger a click on the download link
                    downloadLink.click();

                    // Remove the download link from the document body
                    document.body.removeChild(downloadLink);
                }
            }
        </script>



        <!-- ADD TABLE MODAL -->
        <dialog class="addTableModal rounded" id="addTableModal">
            <div class="addTableModalContentDiv row row-gap-2 d-flex justify-content-center h-100">
                <h1 class="text-center display-5 fw-medium align-self-center p-0 m-0">Do you want to add <span class="fw-bold">Table <?php echo "$tablenumber"; ?> ?</span></h1>
                <hr class="p-0 m-0">
                <div class="col d-flex gap-4 align-items-center justify-content-center p-0 m-0">
                    <input type="submit" class="addTableConfirmBtn col-sm-5 p-2 fs-4 rounded" value="Yes" onclick="generateQRCode()">
                    <input type="submit" class="addTableCancelBtn col-sm-5 p-2 fs-4 rounded" value="No" onclick="addTableModal.close()">
                </div>
            </div>
        </dialog>


        <!-- QR-DOWNLOAD MODAL -->
        <dialog class="qrDownloadModal rounded" id="qrDownloadModal">
            <div class="qrDownloadModalContent p-4 row row-gap-2 d-flex flex-column align-items-center justify-content-center h-100">
                <h1 class="display-5 fw-medium text-center ">Download <span id="modalTableName" class="fw-bold"></span> QR Code</h1>
                <hr class="m-0 p-0">
                <img src="" alt="" class="modalQrImage border border-dark rounded m-2" id="modalQrImage">
                <div class="d-flex gap-4 align-items-center justify-content-center p-0 m-0">
                    <input type="submit" value="Download QR Code" class="downloadQrBtn col-sm-5 p-2 fs-4 rounded m-0" onclick="downloadQRCode()">
                    <input type="submit" value="Cancel" class="downloadQrCancelBtn col-sm-5 p-2 fs-4 rounded m-0" onclick="qrDownloadModal.close()">
                </div>
            </div>
        </dialog>

</body>

</html>