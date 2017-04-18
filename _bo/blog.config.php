<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();
$config = $app->getManager()->getBlogConfig();

echo $app->twig->render('blog.config.html.twig', array(
    'menuPage' => 'blog',
    'config' => $config
));
