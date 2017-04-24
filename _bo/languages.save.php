<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$manager = $app->getManager();

$languages = array();
$code = '';
$name = '';
$home = '';
$i=0;
foreach ($_POST as $item){
    if($i%3 == 0)
        $code = $item;
    if($i%3 == 1)
        $name = $item;
    if($i%3 == 2){
        $home = $item;
        $languages[$code] = array($name,$home);
    }
    $i++;
}

$manager->saveLanguages($languages);


header('Content-Type: application/json');
$res['status'] = 'success';
$res['message'] = 'Data saved';
echo json_encode($res);
