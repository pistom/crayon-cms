<?php
include_once 'app.php';

echo $twig->render('bo.html.twig', array(
    'userRole' => $userRole
));
