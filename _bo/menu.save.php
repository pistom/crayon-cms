<?php
require_once '../vendor/autoload.php';
$app = new \CrayonBo\CrayonBo();
$app->dieIfNotAdmin();

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
            $menu[$menuName] = array();
        } else {
            if($i%3 == 1){
                $menuItem = $item;
            }
            if($i%3 == 2){
                if($item == '--')
                    $menuParent = null;
                else
                    $menuParent = $item;
            }
            if($i%3 == 0){
                if($item == '')
                    $menuOrder = 0;
                else
                    $menuOrder = (int)$item;

                if(!$menuParent){
                    if(!isset($menu[$menuName][$menuItem]))
                        $menu[$menuName][$menuItem] = array();
                    if(!isset($menu[$menuName][$menuItem]['order']))
                        $menu[$menuName][$menuItem] = array();
                    $menu[$menuName][$menuItem]['order'] = $menuOrder;
                } else {
                    if(!isset($menu[$menuName][$menuParent]))
                        $menu[$menuName][$menuParent] = array('order'=>0);
                    if(!isset($menu[$menuName][$menuParent][$menuItem]))
                        $menu[$menuName][$menuParent][$menuItem] = array();

                    if(!isset($menu[$menuName][$menuParent][$menuItem]['order']))
                        $menu[$menuName][$menuParent][$menuItem] = array("order"=>$menuOrder);
                    else
                        $menu[$menuName][$menuParent][$menuItem]['order'] = $menuOrder;
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
foreach ($menu[$menuName] as &$subMenu){
    uasort($subMenu,"cmp");
}
uasort($menu[$menuName],"cmp");

unset($menus[$oldMenuName]);


$menus[$menuName] = $menu[$menuName];

$app->getManager()->saveMenusList($menus);


header('Content-Type: application/json');
$res['status'] = 'success';
echo json_encode($res);