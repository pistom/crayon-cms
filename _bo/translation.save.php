<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$manager = $app->getManager();
$app->dieIfUserNotAllowed("translation.save");

$languages = $manager->getLanguagesList();

$translation = array();
$key = '';
$old_key = '';
if($_POST['key']) {
    $key = $manager->testString($_POST['key']);
    $old_key = $manager->testString($_POST['old_key']);
    foreach ($languages as $code=>$language) {
        $translation[$code] = $manager->testString($_POST[$code]);
    }
}

$manager->saveTranslation($key,$translation,$old_key);

header('Content-Type: application/json');
$res['status'] = 'success';
$res['message'] = 'Data saved';
echo json_encode($res);
