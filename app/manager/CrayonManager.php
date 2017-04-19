<?php
namespace Crayon;

class CrayonManager {

    protected $blogConfig;

    function __construct(){
        $this->blogConfig = $this->getBlogConfig();
    }

    public function getBlogConfig(){
        $file = (file_exists('data/blog/config.json')) ? 'data/blog/config.json' : '../data/blog/config.json';
        $json_data = file_get_contents($file);
        $config = json_decode($json_data, true);
        return $config;
    }

    public function getArticles($categoryId,$page,$menuName,$itemsPerPage){

        $file = (file_exists('data/blog/articles.json')) ? 'data/blog/articles.json' : '../data/blog/articles.json';
        $articles_data = file_get_contents($file);


        $articles = json_decode($articles_data, true);
        $allResults = array();
        foreach ($articles as $article){
                if($categoryId){
                    if($article['category_id'] == $categoryId)
                        array_push($allResults, $article);
                }
                else {
                    $articleCategory = $this->getCategory($article['category_id']);
                    if($menuName)
                        if($articleCategory['menu'] == $menuName)
                            array_push($allResults, $article);
                        else;
                    else
                        array_push($allResults, $article);
                }

        }

        uasort($allResults, function($a, $b) {
            if ($a['publication_date'] == $b['publication_date']) {
                return 0;
            }
            return ($a['publication_date'] > $b['publication_date']) ? -1 : 1;
        });
        $articlesPerPage = ($itemsPerPage) ? $itemsPerPage : $this->blogConfig['articles_per_page'];
        $results = array_slice($allResults,$articlesPerPage*($page-1),$articlesPerPage);

        $pagesQtt = ceil(count($allResults)/$articlesPerPage);
        $requestURI = (isset($dp['page'])) ? $_SERVER['REQUEST_URI'] : $_SERVER['REQUEST_URI'];
        $requestURI = preg_replace('/\/\d+\/$/','/', $requestURI);

        $paginator = array();
        for($i=1;$i<=$pagesQtt;$i++){
            $paginator[$i]['page'] = $i;
            $paginator[$i]['active'] = ($page == $i) ? true : false;
            $paginator[$i]['url'] = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$requestURI$i/";
        }
        return array(
            'results'=>$results,
            'paginator'=>$paginator
        );
    }

    public function getArticle($slug){
        $article = array();
        $articles_data = file_get_contents('data/blog/articles.json');
        $articles = json_decode($articles_data, true);
        foreach ($articles as $a){
            if ($a['slug'] == $slug)
                $article = $a;
        }
        $article['content'] = htmlspecialchars_decode($article['content']);
        return $article;
    }

    public function getCategory($id){
        $category = null;
        $file = (file_exists('data/blog/categories.json')) ? 'data/blog/categories.json' : '../data/blog/categories.json';
        $cat_data = file_get_contents($file);
        $categories = json_decode($cat_data, true);
        foreach ($categories as $c){
            if ($c['id'] == $id)
                $category = $c;
        }
        return $category;
    }

    public function getMenu($menuName){
        $menu_data = file_get_contents('data/menus.json');
        $menu = json_decode($menu_data, true);
        return $menu[$menuName];
    }
}
