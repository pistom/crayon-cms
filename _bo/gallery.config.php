<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("gallery.config");

$config = $app->getManager()->getGalleryConfig();

echo $app->twig->render('gallery.config.html.twig', array(
    'menuPage' => 'gallery.s',
    'config' => $config
));
