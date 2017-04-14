<?php
require 'vendor/autoload.php';


$routing = new AltoRouter();

$app = new \Crayon\Crayon($routing);
$app->run();