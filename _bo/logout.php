<?php session_start();
session_destroy();
$json_data = file_get_contents('../data/config.json');
$config = json_decode($json_data, true);
header("location:".$config['main_dir']."/_bo/login.php");
exit;