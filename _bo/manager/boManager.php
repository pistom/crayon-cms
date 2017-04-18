<?php
class boManager {

    public function getRoutesList(){
        $json_data = file_get_contents('../data/routing.json');
        $routes = json_decode($json_data, true);
        return $routes;
    }

    public function saveRoutesList($routes){
        copy('../data/routing.json', '../data/tmp/routing-tmp-'.date('YmdHis').'.json');
        $fp = fopen('../data/routing.json', 'w');
        fwrite($fp, json_encode($routes));
        fclose($fp);
    }

    public function addRoute($r){
        $json_data = file_get_contents('../data/routing.json');
        $routes = json_decode($json_data, true);
        $routes[$r['route']] = array(
            'route' => $r['route'],
            'path' => $r['path'],
            'name' => $r['name'],
            'type' => $r['type'],
            'controller' => $r['controller'],
            'function' => $r['function'],
            'variables' => $r['variables'],
            'enabled' => $r['enabled'],
            'blind' => $r['blind']
        );
        $this->saveRoutesList($routes);
    }

    public function getMenusList(){
        $json_data = file_get_contents('../data/menus.json');
        $menus = json_decode($json_data, true);
        return $menus;
    }

    public function saveMenusList($menus){
        copy('../data/menus.json', '../data/tmp/menus-tmp-'.date('YmdHis').'.json');
        $fp = fopen('../data/menus.json', 'w');
        fwrite($fp, json_encode($menus));
        fclose($fp);
    }

    public function getPagesList(){
        $results = array();
        $pages = glob('../data/pages/*.json');
        foreach ($pages as $p) {
            $pageName = basename($p, ".json");
            $json_data = file_get_contents($p);
            $results[$pageName] = json_decode($json_data, true);
        }
        return $results;
    }

    public function getPage($p){
        $json_data = file_get_contents('../data/pages/'.$p.'.json');
        $page = json_decode($json_data, true);
        $fileContent = file_get_contents('../views/pages/'.$p.'.html.twig');
        $contentStartsAt = strpos($fileContent, "{% block content %}") + strlen("{% block content %}");
        $contentEndsAt = strpos($fileContent, "{% endblock %}{# end content #}", $contentStartsAt);
        $contentResult = substr($fileContent, $contentStartsAt, $contentEndsAt - $contentStartsAt);
        $scriptsStartsAt = strpos($fileContent, "{% block scripts %}") + strlen("{% block scripts %}");
        $scriptsEndsAt = strpos($fileContent, "{% endblock %}{# end scripts #}", $scriptsStartsAt);
        $scriptsResult = substr($fileContent, $scriptsStartsAt, $scriptsEndsAt - $scriptsStartsAt);
        $page['content'] = $contentResult;
        $page['scripts'] = $scriptsResult;
        return $page;
    }

    public function savePage($p){
        if($p['oldPageName']){
            rename('../data/pages/'.$p['oldPageName'].'.json', '../data/tmp/pages/'.$p['oldPageName'].'-tmp-'.date('YmdHis').'.json');
            rename('../views/pages/'.$p['oldPageName'].'.html.twig', '../views/tmp/pages/'.$p['oldPageName'].'-tmp-'.date('YmdHis').'.html.twig');
        }
        $fp = fopen('../data/pages/'.$p['pageName'].'.json', 'w');
        fwrite($fp,json_encode(array(
            'title'=>$p['title'],
            'description' => $p['description'],
            'menu' => $p['menu']
        )));
        fclose($fp);
        $fp = fopen('../views/pages/'.$p['pageName'].'.html.twig', 'w');
        $fileContent = "{% extends template %}{% block content %}".$p['content']."{% endblock %}{# end content #}";
        $fileContent .= "{% block scripts %}".$p['scripts']."{% endblock %}{# end scripts #}";
        fwrite($fp,$fileContent);
        fclose($fp);
    }
    public function deletePage($p){
        rename('../data/pages/'.$p.'.json', '../data/pages/tmp/'.$p.'-tmp-'.date('YmdHis').'.json');
        rename('../views/pages/'.$p.'.html.twig', '../views/pages/tmp/'.$p.'-tmp-'.date('YmdHis').'.html.twig');

        unlink('../views/pages/'.$p.'.html.twig');
        unlink('../data/pages/'.$p.'.json');
    }

    public function getUsersList(){
        $json_data = file_get_contents('../data/users.json');
        $users = json_decode($json_data, true);
        return $users;
    }
    public function saveUsersList($users){
        copy('../data/users.json', '../data/tmp/users-tmp-'.date('YmdHis').'.json');
        $fp = fopen('../data/users.json', 'w');
        fwrite($fp, json_encode($users));
        fclose($fp);
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

    public function getBlogConfig(){
        $json_data = file_get_contents('../data/blog/config.json');
        $config = json_decode($json_data, true);
        return $config;
    }
    public function saveBlogConfig($config){
        copy('../data/blog/config.json', '../data/tmp/blog/config-tmp-'.date('YmdHis').'.json');
        $fp = fopen('../data/blog/config.json', 'w');
        fwrite($fp, json_encode($config));
        fclose($fp);
    }
    public function getBlogCategories(){
        $json_data = file_get_contents('../data/blog/categories.json');
        $categories = json_decode($json_data, true);
        return $categories;
    }
    public function getBlogCategory($id){
        $categories = $this->getBlogCategories();
        $category = null;
        foreach ($categories as $c){
            if ($c['id'] == $id)
                $category = $c;
        }
        return $category;
    }

    public function saveBlogCategory($category){
        copy('../data/blog/categories.json', '../data/tmp/blog/categories-tmp-'.date('YmdHis').'.json');
        $categories = $this->getBlogCategories();
        $categories[$category['id']] = $category;
        $fp = fopen('../data/blog/categories.json', 'w');
        fwrite($fp, json_encode($categories));
        fclose($fp);
    }
    public function deleteBlogCategory($category){
        copy('../data/blog/categories.json', '../data/tmp/blog/categories-tmp-'.date('YmdHis').'.json');
        $categories = $this->getBlogCategories();
        $k = null;
        foreach ($categories as $key=>$c) {
            if ($c['id'] == $category)
                $k=$key;
        }

        unset($categories[$k]);

        $fp = fopen('../data/blog/categories.json', 'w');
        fwrite($fp, json_encode($categories));
        fclose($fp);
    }

    public function testString($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function clearDirectory($dir){
        $files = glob($dir.'/*');
        foreach($files as $file){
            if(is_file($file) && $file != $dir.'/index.html')
                unlink($file);
        }
    }

    public function clearCache($dir){
        function rrmdir($dir) {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
                    }
                }
                reset($objects);
                rmdir($dir);
            }
        }
        rrmdir($dir);
    }


}