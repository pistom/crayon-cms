<?php

class BlogController extends SiteController {
    public function articlesList($sp,$dp){
        $this->twig->addGlobal('currentPath',$this->request['currentPath']);
        $articlesPerPage = 5;
        $categoryId = (isset($sp[0]) && $sp[0] != 0) ? $sp[0] : null;
        $page = (isset($dp['page'])) ? $dp['page'] : '1';

        $articles_data = file_get_contents('data/blog/articles.json');
        $articles = json_decode($articles_data, true);

        $allResults = array();
        foreach ($articles as $article){
            if($categoryId){
                if($article['category_id'] == $categoryId )
                    array_push($allResults, $article);
            }
            else {
                array_push($allResults, $article);
            }
        }

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

        $menu_data = file_get_contents('data/menus.json');
        $menu = json_decode($menu_data, true);
        if($this->request['isAjax']){
            $res['content'] = $this->twig->render('blog/articles.html.twig', array(
                'currentPageNumber' => $page,
                'template' => 'ajax.content.html.twig',
                'articles' => $results,
                'pagination' => $paginator
            ));
            header('Content-Type: application/json');
            echo json_encode($res);
        }
        else {
            echo $this->twig->render('blog/articles.html.twig', array(
                'currentPageNumber' => $page,
                'template' => 'blog/blog.html.twig',
                'articles' => $results,
                'pageTitle' => 'Blog',
                'pageDescription' => 'Desc',
                'menu' => $menu['menu_en'],
                'pagination' => $paginator
            ));
        }

    }
}