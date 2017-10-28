<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfUserNotAllowed("menu.save");

$menus = $app->getManager()->getMenusList();
$oldMenuName = $app->getManager()->testString($_GET['menu']);
$menu = array();
$menuName = '';
$menuItem = '';
$menuParent = null;
$menuOrder = null;
if($_POST){
    $i = 0;
    foreach ($_POST as $item) {
        $item = $app->getManager()->testString($item);
        if($i == 0){
            $menuName = $item;
            $menu[$menuName]['items'] = array();
        }
        else if($i == 1) {
            $menu[$menuName]['lang'] = $item;
        } else {
            if($i%3 == 2){
                $menuItem = $item;
            }
            if($i%3 == 0){
                if($item == '--')
                    $menuParent = null;
                else
                    $menuParent = $item;
            }
            if($i%3 == 1){
                if($item == '')
                    $menuOrder = 0;
                else
                    $menuOrder = (int)$item;

                if(!$menuParent){
                    if(!isset($menu[$menuName]['items'][$menuItem]))
                        $menu[$menuName]['items'][$menuItem] = array();
                    if(!isset($menu[$menuName]['items'][$menuItem]['order']))
                        $menu[$menuName]['items'][$menuItem] = array();
                    $menu[$menuName]['items'][$menuItem]['order'] = $menuOrder;
                } else {
                    if(!isset($menu[$menuName]['items'][$menuParent]))
                        $menu[$menuName]['items'][$menuParent] = array('order'=>0);
                    if(!isset($menu[$menuName]['items'][$menuParent][$menuItem]))
                        $menu[$menuName]['items'][$menuParent][$menuItem] = array();

                    if(!isset($menu[$menuName]['items'][$menuParent][$menuItem]['order']))
                        $menu[$menuName]['items'][$menuParent][$menuItem] = array("order"=>$menuOrder);
                    else
                        $menu[$menuName]['items'][$menuParent][$menuItem]['order'] = $menuOrder;
                }
                $menuParent = null;
                $menuOrder = null;
                $menuItem = '';
            }
        }
        $i++;
    }
};

function cmp($a, $b)
{
    $i = (!isset($a['order'])) ? -1 : $a['order'];
    $j = (!isset($b['order'])) ? -1 : $b['order'];
    if($i == $j)
        return 0;
    return ($i < $j) ? -1 : 1;
}
foreach ($menu[$menuName]['items'] as &$subMenu){
    uasort($subMenu,"cmp");
}
uasort($menu[$menuName]['items'],"cmp");

unset($menus[$oldMenuName]);



$menus[$menuName] = $menu[$menuName];
//var_dump($menus);
$app->getManager()->saveMenusList($menus);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);