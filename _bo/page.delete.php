<?php
include_once 'app.php';

$pageName = $manager->testString($_POST['page']);
$manager->deletePage($pageName);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);