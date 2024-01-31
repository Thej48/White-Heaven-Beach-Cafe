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
    <title>Document</title>
</head>

<body>
    <h1>Table : <?php echo $tableNo ?></h1>
    <h1>User ID : <?php echo $_SESSION['UserID'] ?></h1>
    <h1>Username : <?php echo $_SESSION['UserName'] ?></h1>
    <h1>Phone Number : <?php echo $_SESSION['UserPhone'] ?></h1>
    <input type="submit" value="Logout" onclick="document.location.href='./logoutEndUser.php'">
</body>

</html>