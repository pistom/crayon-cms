<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("settings");

$settings = $app->getConfig();
$templates = $app->getManager()->getTemplatesList();
echo $app->twig->render('settings.html.twig', array(
    'menuPage' => 'settings',
    'settings' => $settings,
    'templates' => $templates
));
