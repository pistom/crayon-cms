<html>
<head>
    <title>Crayon CMS</title>
</head>
<body>
<pre>
<span style="color:slategray">

  ____                                ____ __  __ ____
 / ___|_ __ __ _ _   _  ___  _ __    / ___|  \/  / ___|
| |   | '__/ _` | | | |/ _ \| '_ \  | |   | |\/| \___ \
| |___| | | (_| | |_| | (_) | | | | | |___| |  | |___) |
 \____|_|  \__,_|\__, |\___/|_| |_|  \____|_|  |_|____/
                 |___/
</span>
<span style="color:lightslategray">________________________________________________________</span><br>
<?php
// Create directories
function makeDir($dir_arr){
foreach($dir_arr as $dir=>$mod){
    if(!is_dir($dir)){
        if(mkdir($dir)){
            chmod($dir, ($mod===0)?0755:0777);
            echo "<span style='color:green'>Directory <strong>".$dir."</strong> created<br></span>";
        } else {
            echo "<span style='color:crimson'>Directory <strong>".$dir."</strong> not created<br></span>";
        }
    }
}
}
// Create json file
function createFile($file,$content){
if(!file_exists($file)){
    $fp = fopen($file, 'w');
    if(fwrite($fp, $content)){
        fclose($fp);
        echo "<span style='color:green'>File <strong>".$file."</strong> created</span><br>";
    } else {
        echo "<span style='color:crimson'>File <strong>".$file."</strong> created</span><br>";
    }
} else
    echo "<span style='color:slategray'>File <strong>".$file."</strong> exists</span><br>";
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



// Remove _install.php
if(unlink('_install.php')):?>
<span style="color:slategray">
┌ <b>SUCCESS!</b> ────────────────────────────────────────────╖
│ <span style='color:yellowgreen'>File <strong>_install.php</strong> deleted</span>                            ║
╘══════════════════════════════════════════════════════╝</span>
<span style="color:slategray">
┌ <b>IMPORTANT!</b> ──────────────────────────────────────────╖
│ <span style="color:crimson">Change admin password!</span>                               ║
╘══════════════════════════════════════════════════════╝</span>
<span style="color:slategray">
┌ <b>REMEMBER!</b> ───────────────────────────────────────────╖
│ All data are stored in twig and json files excluded  ║
│ from the GIT repository.                             ║
│ <span style="color:crimson">Make sure you back up your data!</span>                     ║
╘══════════════════════════════════════════════════════╝</span>
<span style="color:slategray"><a href="<?php echo ($main_dir !== '') ? $main_dir : '/' ?>"><span style="color:dodgerblue">Homepage</span></a></span>
<span style="color:slategray"><a href="<?php echo ($main_dir !== '') ? $main_dir.'/_bo' : '/_bo' ?>"><span style="color:dodgerblue">Back office</span></a> (login: admin, password: admin);</span>
<?php else: ?>
<span style='color:crimson'>File <strong>_install.php</strong> not deleted</span><br>
<span style='color:slategray'>Script does not have proper permissions</span><br>
<span style='color:slategray'>You can try changing settings</span><br>
<span style='color:slategray'>Eg. chown -R www-data:www-data /folder_with_page</span><br>
<?php endif; ?>

</pre>
</body>
</html>
