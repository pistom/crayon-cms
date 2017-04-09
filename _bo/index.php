<?php
session_start();
include_once("verification.php");
include_once("../settings.php");
require_once '../vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array('cache' => $twigCache,'debug' => $twigDebug));
echo $twig->render('bo.html.twig', array());
