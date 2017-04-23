<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$manager = $app->getManager();

$languages = array();
$code = '';
$i=1;
foreach ($_POST as $item){
    if($i%2 == 1)
        $code = $item;
    if($i%2 == 0){
        $languages[$code] = $item;
    }
    $i++;
}

$manager->saveLanguages($languages);


header('Content-Type: application/json');
$res['status'] = 'success';
$res['message'] = 'Data saved';
echo json_encode($res);
