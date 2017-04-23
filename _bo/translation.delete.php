<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$manager = $app->getManager();
$languages = $manager->getLanguagesList();

$key = '';
if($_POST['key']) {
    $key = $manager->testString($_POST['key']);
}

$manager->deleteTranslation($key);

header('Content-Type: application/json');
$res['status'] = 'success';
$res['message'] = 'Data saved';
echo json_encode($res);
