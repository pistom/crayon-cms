<?php
namespace Crayon;

class CrayonManager {

    protected $blogConfig;
    protected $mailer;
    protected $config;

    function __construct(){
        $this->blogConfig = $this->getBlogConfig();
        $this->config = $this->getSiteConfig();
        $this->mailer = new \PHPMailer;
    }

    protected function getSiteConfig(){
        $file = (file_exists('data/config.json')) ? 'data/config.json' : '../data/config.json';
        $json_data = file_get_contents($file);
        $config = json_decode($json_data, true);
        return $config;
    }

    public function getBlogConfig(){
        $file = (file_exists('data/blog/config.json')) ? 'data/blog/config.json' : '../data/blog/config.json';
        $json_data = file_get_contents($file);
        $config = json_decode($json_data, true);
        return $config;
    }

    public function getArticles($categoryId,$page,$menuName,$itemsPerPage,$publishedOnly=false){
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
        if($publishedOnly)
            foreach ($allResults as $key=>$result){
                $actualDate = new \DateTime;
                $publicationDate = \DateTime::createFromFormat('Y-m-d H:i', $result['publication_date']);
                if ($publicationDate > $actualDate)
                    unset($allResults[$key]);
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
        $requestURI = preg_replace('/\?.*$/','', $requestURI);
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
        $article['content'] = @file_get_contents('data/blog/articles/'.$article['id'].'.html.twig');
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

    public function getTranslations($lang){
        $json_data = file_get_contents('data/translations.json');
        $translations = json_decode($json_data, true);
        $translation = array();
        foreach($translations as $key=>$translate){
            $translation[$key] = $translate[$lang];
        }
        return $translation;
    }

    public function testString($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function sendEmail($to,$replayTo,$subject,$message){
        $this->getMailConfig();
        $this->mailer->addAddress($to);
        $this->mailer->addReplyTo($replayTo);
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body = $message;
        if(filter_var($replayTo, FILTER_VALIDATE_EMAIL)){
            if(!$this->mailer->send())
                $isSent = $this->mailer->ErrorInfo;
            else
                $isSent = true;
        } else
            $isSent = $this->mailer->ErrorInfo;
        $this->mailer->ClearAllRecipients();
        $this->mailer->ClearAttachments();
        return $isSent;
    }

    protected function getMailConfig(){
        $this->mailer->SMTPDebug = 0;
        if ($this->config['mail_is_smtp']) {
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['mail_host'];
            $this->mailer->SMTPAuth = $this->config['mail_smtp_auth'];
            $this->mailer->Username = $this->config['mail_username'];
            $this->mailer->Password = $this->config['mail_password'];
            $this->mailer->SMTPSecure = $this->config['mail_smtp_secure'];
            $this->mailer->Port = $this->config['mail_port'];
        }
        $this->mailer->From = $this->config['site_email'];
        $this->mailer->FromName = $this->config['site_name'];
        $this->mailer->CharSet = 'UTF-8';
    }
}
