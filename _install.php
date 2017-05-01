<?php
echo "<pre>";
// Create directories
function makeDir($dir_arr){
    foreach($dir_arr as $dir=>$mod){
        if(!is_dir($dir)){
            mkdir($dir);
            chmod($dir, ($mod===0)?0755:0777);
            echo "Directory <strong>".$dir."</strong> created<br>";
        }
    }
}
// Create json file
function createFile($file,$content){
    $fp = fopen($file, 'w');
    fwrite($fp, $content);
    fclose($fp);
    echo "File <strong>".$file."</strong> created<br>";
}

$directories = array(
// 0 - read only, 1 - writable
    'cache'=>1,
    'data'=>1,
    'data/blog'=>1,
    'data/pages'=>1,
    'data/tmp'=>1,
    'data/tmp/blog'=>1,
    'data/tmp/pages'=>1,
    'images'=>0,
    'images/pages'=>0,
    'views/blog/articles'=>1,
    'views/pages'=>1,
    'views/tmp'=>1,
    'views/tmp/pages'=>1,
    'views/tmp/blog'=>1,
    'views/tmp/blog/articles'=>1
);

makeDir($directories);


// Main config
$main_dir = str_replace('/_install.php','',$_SERVER["REQUEST_URI"]);
$config = array(
    'site_name'=>'Crayon CMS',
    'dev_mode'=>true,
    'twig_cache'=>'cache',
    'site_color'=>'#ffffff',
    'main_dir'=>$main_dir
);
createFile('data/config.json', json_encode($config));

// Files manager config
$files_config = array(
    'images_sizes'=>array(
        '800x600px'=>array(800,600)
    )
);
createFile('data/files.config.json', json_encode($files_config));


// Languages config
$languages_config = array(
    "en"=>array("English","home")
);
createFile('data/languages.json', json_encode($languages_config));

// Menus config
$menus_config = array(
    "main"=>array(
        "items"=>array(
            "home"=>array(
                "order"=>1
            )
        ),
        "lang"=>"en"
    )
);
createFile('data/menus.json', json_encode($menus_config));

// Routing
$routing_config = array(
    "home"=>array(
        "type"=>"GET",
        "path"=>"/",
        "controller"=>"SiteController",
        "function"=>"page",
        "variables"=>"home",
        "name"=>"Home page",
        "enabled"=>"true",
        "blind"=>"false"
    )
);
createFile('data/routing.json', json_encode($routing_config));

// Translations
$translations = array(
    "contact"=>array(
        "en"=>"Contact",
    )
);
createFile('data/translations.json', json_encode($translations));

// Users
$users = array(
    "admin"=>array(
        "role"=>"admin",
        "pwd"=>"21232f297a57a5a743894a0e4a801fc3"
    )
);
createFile('data/users.json', json_encode($users));

// Homepage
$homepage = array(
    "title"=>"Homepage",
    "description"=>"Homepage",
    "menu"=>"main"
);

createFile('data/pages/home.json', json_encode($homepage));

// Homepage content
$content = "{% extends template %}
{% block content %}
<h1>Homepage</h1>
{% endblock %}{# end content #}
{% block scripts %}
{% endblock %}{# end scripts #}";
createFile('views/pages/home.html.twig', $content);

// Blog articles
$articles = array(
    "0"=>array(
        "id"=>0,
        "title"=>"First article",
        "slug"=>"first-article",
        "title_color"=>"#ffffff",
        "category_id"=>"0",
        "intro"=>"Intro",
        "intro_image"=>"",
        "main_image"=>"",
        "publication_date"=>date('Y-m-d H:i', time())
    )
);
createFile('data/blog/articles.json', json_encode($articles));

// Blog articles content
$content = "{% extends 'blog/article.content.html.twig' %}
{% block content %}Article{% endblock %}{# end content #}
{% block afterContent %}{% endblock %}{# end afterContent #}";
createFile('views/blog/articles/0.html.twig', $content);

// Blog categories
$categories = array(
    "0"=>array(
        "id"=>0,
        "name"=>"First category",
        "menu"=>"main",
    )
);
createFile('data/blog/categories.json', json_encode($categories));

// Blog categories
$blog_config = array(
    "articles_per_page"=>5,
    "author_name"=>"Author",
    "author_desc"=>"",
    "author_photo"=>"",
);
createFile('data/blog/config.json', json_encode($blog_config));



echo '---<br>';
// Remove _install.php
if(unlink('_install.php'))
  echo "<strong>_install.php</strong> deleted<br>";
echo '---<br>';

echo "</pre>";
?>
<pre>
<a href="<?php echo $main_dir ?>">Homepage</a>
<a href="_bo">Back office</a> (login: admin, password: admin);
<span style="color:#d0024f"><b>Change admin password!</b></span>
<span style="color:#990000">Change .htaccess RewriteBase if necessary.</span>
</pre>
