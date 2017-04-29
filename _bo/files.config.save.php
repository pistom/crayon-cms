<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();
$config = $app->getManager()->getFilesConfig();
$config['images_sizes'] = array();
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $i=0;
    $type = '';
    $height = '';
    $width = '';
    foreach ($_POST as $item) {
        if($i%3 == 0) {
            $type = $item;
        }
        if($i%3 == 1) {
            $width = $item;
        }
        if($i%3 == 2) {
            $height = $item;
            $config['images_sizes'][$type] = array($width,$height);
            $height = '';
            $width = '';
        }
        $i++;
    }
}
$app->getManager()->saveFilesConfig($config);
header('Content-Type: application/json');
$res['status'] = 'success';
$res['message'] = 'Data saved';
echo json_encode($res);