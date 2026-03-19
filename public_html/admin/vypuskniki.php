<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 18.02.2018
 * Time: 18:47
 */
function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}
$time_start = getmicrotime();
define("ANMIN_PAGE","yes_admin");
require_once("../class/common.php");
$page="vipysk";

$left='';
if(isset($_GET['id'])){
    $pages = new Vypusknik();
}else{
    $pages = new VypusknikList();
}

if ($pages->routing($_GET)){
    $left=$pages->render();
}else{
    $title = 'Ошибка страницы';
    //$left=$tovar->renderErrorsBlock();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Выпускники</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <script type="text/javascript" src="js/admin.js"></script>
</head>
<body>
<div id="content">
    <?php
    include("include/adminmenu.php");//adminmenu ?>
    <div class="left">
        <?php
        print $left;
        ?>
    </div>
    <div class="footer">
        <?php 	include("include/adminfooter.php"); ?>
    </div>
</div>
</body>
</html>
