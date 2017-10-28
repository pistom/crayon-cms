<?php
if(!isset($_SESSION['UserData']['username'])){
    header("location:".$this->settings['main_dir']."/_bo/login.php");
    exit;
}
else {
    $userRole = $_SESSION['UserData']['role'];
    $userName = $_SESSION['UserData']['username'];
}