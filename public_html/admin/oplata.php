<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 06.12.2015
 * Time: 14:29
 *
 * TODO
 * запретить загрузку по ID
 * отбор по статусу
 * статистика переходов в.т.ч ошибочных
 * анализ REFERER
 * скрыть использованные
 *
 */
function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}
$time_start = getmicrotime();

define("ANMIN_PAGE","yes_admin");
require_once("../class/common.php");
$page="oplata";
$phpself=$_SERVER['PHP_SELF'];

include_once("class/oplata.item.php");
if(isset($_GET['id'])){
    $viev="item";
    if(intval($_GET['id'])>0){
        //редактирование
        $id=intval($_GET['id']);
        if($item->load($id)==false){//загружаю
            $item->create_blank();
            $item->error.="Не удалось загрузить данные!";
            $item->my_item['action_item']="add";
        }else{//загружено
            $item->my_item['action_item']="edit";
        }
    }else{//добавление
        if($item->error==""){//проверяем не возрат ли это после ошибки
            $item->create_blank();
        }
    }
}else{
    //просмотр
    $viev="list";
    if((isset($_GET['cat']))&&(intval($_GET['cat'])>0)){
        $cat=intval($_GET['cat']);
        $item->load($cat);
    }else{
        $cat=0;//корень
    }
    $items="page";
    $mode=" p.parent=" . $cat ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Оплата</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script type="text/javascript" src="js/admin.js"></script>
</head>
<body>
<div id="content">
    <?php
    include("include/adminmenu.php");//adminmenu ?>
    <div class="left">
        <?php
        // print $viev;
        switch ($viev) {
            case "list":
              //  if($cat>0)
//                    include("include/oplata_viev.php");
                include("include/oplata_list.php");
                break;
            case "item":
                include("include/oplata_form.php");
                break;
        }
        ?>
    </div>
    <div class="footer">
        <?php 	include("include/adminfooter.php"); ?>
    </div>
</div>
</body>
</html>
