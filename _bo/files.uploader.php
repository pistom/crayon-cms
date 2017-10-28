<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("files.uploader");

$config = $app->getManager()->getFilesConfig();
echo $app->twig->render('files.uploader.html.twig', array(
    'menuPage' => 'files.u',
    'config' => $config
));
