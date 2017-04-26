<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();
$config = $app->getManager()->getFilesConfig();

echo $app->twig->render('files.config.html.twig', array(
    'menuPage' => 'files.c',
    'config' => $config
));
