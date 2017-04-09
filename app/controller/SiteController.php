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
        $template = ($this->request['isAjax']) ? 'ajax.html.twig' : 'base.html.twig';

        echo $this->twig->render('pages/'.$sp[0].'.html.twig', array(
            'template' => $template,
            'pageTitle' => $page['title'],
            'pageDescription' => $page['description'],
            'menu' => $menu[$page['menu']],
        ));
    }

    /**
     * 404
     */
    public function error404() {
        echo $this->twig->render('404.html.twig', array());
    }
}