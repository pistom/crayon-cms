<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();

$mainDir = '../images/pages/';

$res = array();

$fileName = preg_replace('/(^\/|\.\.\/)/','',$app->getManager()->testString($_POST['filename']));
$file = $mainDir.$fileName;
if(file_exists($file)){
    $res['status'] = 'success';
    $res['message'] = 'File "'.$fileName.'" has been deleted.';
    unlink($file);
} else {
    $res['status'] = 'error';
    $res['message'] = 'File "'.$fileName.'" not exists.';
}

echo json_encode($res);