<?php
class boManager {

    public function getRoutesList(){
        $json_data = file_get_contents('../data/routing.json');
        $routes = json_decode($json_data, true);
        return $routes;
    }

    public function saveRoutesList($routes){
        copy('../data/routing.json', '../data/routing-tmp-'.date('YmdHis').'.json');
        $fp = fopen('../data/routing.json', 'w');
        fwrite($fp, json_encode($routes));
        fclose($fp);
    }

    public function getMenusList(){
        $json_data = file_get_contents('../data/menus.json');
        $menus = json_decode($json_data, true);
        return $menus;
    }

    public function getControllersList(){
        $results = array();
        $controllers = glob('../app/controller/*.php');
        require_once '../app/controller/SiteController.php';
        foreach ($controllers as $c){
            $controllerName = basename($c, ".php");
            $results[$controllerName] = array();
            $content = file_get_contents($c);
            $pattern = "/public\s*function\s*[a-zA-Z0-9_]*/";
            if(preg_match_all($pattern, $content, $matches)){
                foreach ($matches[0] as $match) {
                    $tmp = explode(' ',$match);
                    array_push($results[$controllerName],array_pop($tmp));
                }
            }
        }
        return $results;
    }

    public function testString($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}