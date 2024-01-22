<?php
require "../conn.php";
session_start();

unset($_SESSION['authAdmin']);
?>
<script>
    window.location.href="./index.php";
</script>
<?php
?>