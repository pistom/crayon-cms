<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("file.upload");

define("FILES_DIRECTORY",'../images/pages/');
define('MB', 1048576);
$sizeLimit = 15;
$phpSizeLimit = (int)(ini_get('upload_max_filesize'));
header('Content-Type: application/json');
$res = array();

if($_FILES["file"]["name"] === ''){
    $res['status'] = 'error';
    $res['message'] = 'No file selected';
    echo json_encode($res);
    die();
}
if($_FILES['file']['error'] === 1){
    $res['status'] = 'error';
    $res['message'] = 'File too large. Your PHP file uploading limit is '.$phpSizeLimit.'MB';
    echo json_encode($res);
    die();
}


if($_FILES["file"]["size"] < $sizeLimit*MB ){
    $directory = $app->getManager()->testString($_POST['directory']);
    if(substr($directory, -1) !== '/')
        $directory .= '/';

    $filename = $_FILES["file"]["name"];
    $filename = preg_replace('/[^A-Za-z0-9_\-\.]/','',str_replace(' ', '-', $filename));

    if(file_exists(FILES_DIRECTORY.$directory.$filename)) {
        $filename = basename(pathinfo($_FILES["file"]["name"])['basename'], '.' . pathinfo($_FILES["file"]["name"])['extension']) . '-' . rand(1000, 9999) . '.' . pathinfo($_FILES["file"]["name"])['extension'];
        $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '', str_replace(' ', '-', $filename));
    }

    if(!is_dir(FILES_DIRECTORY.$directory))
        mkdir(FILES_DIRECTORY.$directory);

    $tmpFile = FILES_DIRECTORY.$directory.$filename;
    move_uploaded_file($_FILES["file"]["tmp_name"],$tmpFile);

    if(getimagesize($tmpFile) && $app->getManager()->testString($_POST['image_size']) !== 'origin'){
        $imageDimensions = $app->getManager()->getFilesConfig()['images_sizes'][$app->getManager()->testString($_POST['image_size'])];
        $image = new \Eventviva\ImageResize($tmpFile);
        $image->resizeToBestFit($imageDimensions[0], $imageDimensions[1]);
        $image->crop($imageDimensions[0], $imageDimensions[1]);
        $image->save($tmpFile);
    }
    $res['status'] = 'success';
    $res['message'] = 'File saved';

} else {
    $res['status'] = 'error';
    $res['message'] = 'File too large. Size limit is '.$sizeLimit.'MB';
}


echo json_encode($res);