<?php
require_once('app/Crayon.php');
try {
    if(\Crayon\Crayon::systemIsInstalled()){
        require 'vendor/autoload.php';
        $app = new \Crayon\Crayon(new AltoRouter());
        $app->run();
    }
} catch(Error $e){
    echo "<pre>It does not work :(</pre>";
}