<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$manager = $app->getManager();

$languages = $manager->getLanguagesList();

$code = $manager->testString($_POST['code']);
unset($languages[$code]);

$manager->saveLanguages($languages);


header('Content-Type: application/json');
$res['status'] = 'success';
$res['message'] = 'Data saved';
echo json_encode($res);
