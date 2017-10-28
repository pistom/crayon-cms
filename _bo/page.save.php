<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("page.save");

$oldPageName = (isset($_GET['page'])) ? $app->getManager()->testString($_GET['page']) : null;
if($_POST) {
    $page = ($oldPageName) ? $app->getManager()->getPage($oldPageName) : array();
    $page['oldPageName'] = $oldPageName;
    $page['pageName'] = $app->getManager()->testString($_POST['pageName']);
    $page['title'] = $app->getManager()->testString($_POST['pageTitle']);
    $page['description'] = $app->getManager()->testString($_POST['pageDescription']);
    $page['menu'] = $app->getManager()->testString($_POST['pageMenu']);
    $page['content'] = $_POST['pageContent'];
    $page['scripts'] = $_POST['pageScripts'];
    if(isset($_POST['addRoute']) && $_POST['addRoute'] == 'true'){
        $route = array(
            'route' => $app->getManager()->testString($_POST['routeRoute']),
            'path' => $app->getManager()->testString($_POST['routePath']),
            'name' => $app->getManager()->testString($_POST['routeName']),
            'type' => 'GET',
            'controller' => 'SiteController',
            'function' => 'page',
            'variables' => $app->getManager()->testString($_POST['pageName']),
            'enabled' => 'true',
            'blind' => 'false'
        );
        $app->getManager()->addRoute($route);
    }
    // UPDATE ROUTE TITLE
    $routes = $app->getManager()->getRoutesList();
    if(isset($routes[$page['pageName']])){
        $routes[$page['pageName']]['name'] = $page['title'];
        $app->getManager()->saveRoutesList($routes);
    }
}
$app->getManager()->savePage($page);

header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);
