<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$manager = $app->getManager();
$app->dieIfUserNotAllowed("language.delete");

$languages = $manager->getLanguagesList();

$code = $manager->testString($_POST['code']);
$res = array();
if(array_key_exists($code,$languages)){
    unset($languages[$code]);
    $res['status'] = 'success';
    $res['message'] = 'Data saved';
} else {
    $res['status'] = 'error';
    $res['message'] = 'Language is not defined';
};

$manager->saveLanguages($languages);

header('Content-Type: application/json');

echo json_encode($res);
