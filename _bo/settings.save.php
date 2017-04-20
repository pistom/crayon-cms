<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

$settings = $app->getConfig();

if($_POST){
    if($_POST['dev_mode']=='true')
        $settings['dev_mode'] = true;
    else
        $settings['dev_mode'] = false;
    $settings['site_name'] = $app->getManager()->testString($_POST['site_name']);
    $settings['tinymce_api_key'] = $app->getManager()->testString($_POST['tinymce_api_key']);
    $settings['fontawesome_cdn'] = $app->getManager()->testString($_POST['fontawesome_cdn']);
    $settings['site_color'] = $app->getManager()->testString($_POST['site_color']);
    $settings['copyright'] = $app->getManager()->testString($_POST['copyright']);
};


$app->saveConfig($settings);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);