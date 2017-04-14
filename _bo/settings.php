<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();
$settings = $app->getConfig();

echo $app->twig->render('settings.html.twig', array(
    'menuPage' => 'settings',
    'settings' => $settings
));
