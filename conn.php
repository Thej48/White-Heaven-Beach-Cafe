<?php

$conn = mysqli_connect("localhost","root","","WhiteHeavenBeachCafe");

if(!$conn){
    mysqli_connect_error($conn);
}else{
    // echo "Connection Successful..!";
}

?>