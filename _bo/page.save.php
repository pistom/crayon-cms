<?php
include_once 'app.php';

$oldPageName = (isset($_GET['page'])) ? $manager->testString($_GET['page']) : null;
if($_POST) {
    $page = ($oldPageName) ? $manager->getPage($oldPageName) : array();
    $page['oldPageName'] = $oldPageName;
    $page['pageName'] = $manager->testString($_POST['pageName']);
    $page['title'] = $manager->testString($_POST['pageTitle']);
    $page['description'] = $manager->testString($_POST['pageDescription']);
    $page['menu'] = $manager->testString($_POST['pageMenu']);
    $page['content'] = $_POST['pageContent'];
    $page['scripts'] = $_POST['pageScripts'];
    if(isset($_POST['addRoute']) && $_POST['addRoute'] == 'true'){
        $route = array(
            'route' => $manager->testString($_POST['routeRoute']),
            'path' => $manager->testString($_POST['routePath']),
            'name' => $manager->testString($_POST['routeName']),
            'type' => 'GET',
            'controller' => 'SiteController',
            'function' => 'page',
            'variables' => $manager->testString($_POST['pageName']),
            'enabled' => 'true',
            'blind' => 'false'
        );
        $manager->addRoute($route);
    }
}
$manager->savePage($page);

header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);
