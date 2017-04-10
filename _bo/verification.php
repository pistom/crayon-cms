<?php
if(!isset($_SESSION['UserData']['username'])){
    header("location:login.php");
    exit;
}
else {
    $userRole = $_SESSION['UserData']['role'];
}