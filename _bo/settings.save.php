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
    $settings['template'] = $app->getManager()->testString($_POST['template']);
    $settings['main_dir'] = $app->getManager()->testString($_POST['main_dir']);
    $settings['site_email'] = $app->getManager()->testString($_POST['site_email']);
    $settings['admin_email'] = $app->getManager()->testString($_POST['admin_email']);
    $settings['copyright'] = $app->getManager()->testString($_POST['copyright']);

    if($_POST['mail_is_smtp']=='true')
        $settings['mail_is_smtp'] = true;
    else
        $settings['mail_is_smtp'] = false;
    $settings['mail_host'] = $app->getManager()->testString($_POST['mail_host']);
    if($_POST['mail_smtp_auth']=='true')
        $settings['mail_smtp_auth'] = true;
    else
        $settings['mail_smtp_auth'] = false;
    $settings['mail_username'] = $app->getManager()->testString($_POST['mail_username']);
    $settings['mail_password'] = $app->getManager()->testString($_POST['mail_password']);
    $settings['mail_smtp_secure'] = $app->getManager()->testString($_POST['mail_smtp_secure']);
    $settings['mail_port'] = $app->getManager()->testString($_POST['mail_port']);

};


$app->saveConfig($settings);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);