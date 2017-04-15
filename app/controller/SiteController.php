<?php

class SiteController
{
    protected $lang;
    protected $twig;
    protected $request;

    function __construct($ctrlParams)
    {
        $this->lang = $ctrlParams['lang'];
        $this->twig = $ctrlParams['twig'];
        $this->request = $ctrlParams['request'];
    }

    /**
     * Static page
     */
    public function page($sp,$dp) {
        $page_data = file_get_contents('data/pages/'.$sp[0].'.json');
        $page = json_decode($page_data, true);
        $menu_data = file_get_contents('data/menus.json');
        $menu = json_decode($menu_data, true);
        $this->twig->addGlobal('currentPath',$this->request['currentPath']);
        if($this->request['isAjax']){
            $res['content'] = $this->twig->render('pages/'.$sp[0].'.html.twig', array('template' => 'ajax.content.html.twig'));
            $res['scripts'] = $this->twig->render('pages/'.$sp[0].'.html.twig', array('template' => 'ajax.scripts.html.twig'));
            header('Content-Type: application/json');
            echo json_encode($res);
        } else {
            echo $this->twig->render('pages/'.$sp[0].'.html.twig', array(
                'template' => 'base.html.twig',
                'pageTitle' => $page['title'],
                'pageDescription' => $page['description'],
                'menu' => $menu[$page['menu']],
            ));
        }
    }

    /**
     * 404
     */
    public function error404() {
        echo $this->twig->render('404.html.twig', array());
    }
}