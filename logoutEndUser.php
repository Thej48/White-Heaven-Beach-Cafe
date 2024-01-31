<?php
require "./conn.php";
session_start();

$tableNo = $_SESSION['tableNo'];

unset($_SESSION['AuthEndUser']);
?>
<script>
    window.location.href="./home.php?tableNo=<?php echo $tableNo ?>";
</script>
<?php
?>