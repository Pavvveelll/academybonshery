<?php
//print_r($_POST);
function getmicrotime(){
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}
$time_start = getmicrotime();

//НАСТРОЙКИ
//перенесено в settings
//$servise_pages=array('glavnaya','contact' ,'subscribe' ,'subscribe_masterklass','oplata','prosrocheno','oplataerror');//служебные страницы


define("ANMIN_PAGE","yes_admin");
require_once("../class/common.php");
$page="page";
$phpself=$_SERVER['PHP_SELF'];

include_once("class/page.item.php");
if(isset($_GET['id'])){
	$viev="page";
	if(intval($_GET['id'])>0){
		//редактирование
		$id=intval($_GET['id']);
		if($item->load($id)==false){//загружаю
			$item->create_blank();
			$item->error.="Не удалось загрузить данные!";
			$item->my_item['action_item']="add";
		}else{//загружено
			$item->my_item['action_item']="edit";
            $cat=$item->my_item['parent'];
		}
	}else{//добавление
		if($item->error==""){//проверяем не возрат ли это после ошибки
			$item->create_blank();
			if(isset($_GET['cat']) ) $cat=intval($_GET['cat']);
			$item->my_item['parent']=$cat;
		}
        $cat=$item->my_item['parent'];
	}
}else{
	//просмотр
	$viev="pagelist";
	if((isset($_GET['cat']))&&(intval($_GET['cat'])>0)){
		$cat=intval($_GET['cat']);
		$item->load($cat);
	}else{
		$cat=0;//корень
	}
	$items="page";
	$mode=" p.parent=" . $cat ;
}



//print_r($_POST);
//Передвигаем итемы по rank TODO movetable
if((isset($_POST['moveid']))&&(intval($_POST['moveid'])>0)&&(isset($_POST['moveshift']))&&(is_numeric($_POST['moveshift']))){
	$query_move = sprintf("SELECT id,rank FROM %s_%s p %s ORDER BY p.rank DESC", DB_PREFIX,$items,((isset($mode)&&($mode!=""))?(" WHERE ".$mode):("")));//
	//die($query_move);
	$moveid=intval($_POST['moveid']);
	$moveshift=intval($_POST['moveshift']);
	//получаем список итемов для передвижения
	$all = mysql_query($query_move) or die(mysql_error());
	$totalrows = mysql_num_rows($all);
	$shiftarray=array();
	if($totalrows!=0){
		while(($row = mysql_fetch_assoc($all))!=false){
			$shiftarray[]=$row;
		}
	}
	do{//находим перемещаемый
		$curitem=current($shiftarray);
		if($curitem['id'] ==$moveid){
			break;
		}
	}while (each($shiftarray)!=false);

	$currank=$curitem['rank'];
	do{
		if($moveshift>0){
			$curitem=prev($shiftarray);
			$moveshift--;
		}else{
			$curitem=next($shiftarray);
			$moveshift++;
		}
		if($curitem==false)
					break;
		$upd=sprintf("UPDATE %s_%s SET rank=%d WHERE id=%d",DB_PREFIX,$items, $currank, $curitem['id']);
		mysql_query($upd) or die(mysql_error());
		$currank=$curitem['rank'];
	}while($moveshift!=0);
	//завершаем
	$upd=sprintf("UPDATE %s_%s SET rank=%d WHERE id=%d",DB_PREFIX,$items, $currank, $moveid);
	mysql_query($upd) or die(mysql_error());
	header("Location:".  $_SERVER['HTTP_REFERER']);//TODO с учетом страницы
	exit;
}


//ставим админскую куку
//print_r($_COOKIE["siteadmin"]);
if(!isset($_COOKIE["siteadmin"])) {
    if(isset($_SERVER['REMOTE_USER'])){
        $cueser= $_SERVER['REMOTE_USER'];
    }else{
        $cueser= 'admin';
    }
    setcookie("siteadmin", $cueser, 0,'/',COOKIES);  /* до закрытия браузера */
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<title><?=DB_PREFIX?>.Страницы</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
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
//case "vievfull":
//    include("include/art_list_full.php");
//    break;
case "pagelist":
	if($cat>0)
		include("include/page_viev.php");
	include("include/page_list.php");
    break;
case "page":
    include("include/page_form.php");
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
