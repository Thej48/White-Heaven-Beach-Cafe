<?php
require "../conn.php";
require "../phpqrcode/qrlib.php";
session_start();

$tablenumber = $_GET['tableNo'];

// Check if Table Number already exists
$getTableInfo = "SELECT * FROM tables WHERE table_number=?";
$prepareTableInfo = $conn->prepare($getTableInfo);
$prepareTableInfo->bind_param("i", $tablenumber);
$prepareTableInfo->execute();
$tableInfoResult = $prepareTableInfo->get_result();

if ($tableInfoResult->num_rows >= 1) {
    $_SESSION["tableNumberExixts"] = "Table number $tablenumber already exists..!";
?>
    <script>
        window.location.href = "./tables.php";
    </script>
<?php
} else {
    // Generate QR-Code to table
    $path = "../qr-images/";

    if (isset($tablenumber)) {
        $url = "http://192.168.221.6//projects/kot/home.php";
    }
?>
    <?php

    $qrcode = $path . "Table-$tablenumber" . ".png";
    QRcode::png($url, $qrcode, 'H', 10, 2);
    // $image = "<img class='qrCode' src='" . $qrcode . "'/> ";

    // Add table entry to database
    $qrImageData = file_get_contents($qrcode);
    $qrCodeImage = base64_encode($qrImageData);

    $addTableQuery = "INSERT INTO tables(table_number,table_qr) VALUES ('$tablenumber','$qrCodeImage')";
    $executeAddTableQuery = mysqli_query($conn, $addTableQuery);

    if ($executeAddTableQuery) {
        $_SESSION["tableAddedSuccessfully"] = "Table number $tablenumber Added Successfully..!";
    ?>
        <script>
            window.location.href = "./tables.php";
        </script>
    <?php
    } else {
        $_SESSION["tableEntryFailed"] = "Table number $tablenumber Entry Failed..!";
    ?>
        <script>
            window.location.href = "./tables.php";
        </script>
<?php
    }
}
?>