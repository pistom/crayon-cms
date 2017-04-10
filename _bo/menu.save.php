<?php
include_once 'app.php';

$menu = array();
if($_POST){
    var_dump($_POST);
};



header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);