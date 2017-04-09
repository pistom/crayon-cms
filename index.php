<?php
require 'vendor/autoload.php';
include_once 'settings.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array('cache' => $twigCache,'debug' => $twigDebug));
$twig->addExtension(new Twig_Extension_Debug());
$routing = new AltoRouter();

$app = new \Crayon\Crayon($routing,$twig);
$app->run();