<?php 
// print_r($_GET);
require_once("../class/common.php");
include_once("enter.item.php");
if($item->error==""){
	$item->create_blank();
}
$error_item="";

/*$pieces = explode("/", $_GET['prs']);
$last_piece=end($pieces);
if((isset($last_piece))&&($last_piece==""))
			array_pop($pieces);
			
$last_piece=end($pieces);
//print($last_piece);
$item_viev= new catalog();
$item_viev->table=DB_PREFIX."_page";
if($item_viev->get_item_seo($last_piece,"look")==false){
		header("HTTP/1.0 404 Not Found");
		include_once("../site_stop.php");
		exit;
}
//формируем ссылку
$patch_array=$item_viev->get_path_seo_array();
//print_r($patch_array);
$patch_url="/";
foreach($patch_array as $pat){
	$patch_url.=sprintf("%s/",$pat['nik']);
}
$patch_url.=$item_viev->my_item['nik']."/";

// print_r($item_viev);

$pos = strpos($item_viev->my_item['article'], '%SUBPAGE_LIST%');
if ($pos !== false) {//список нужен
	//подчиненные страницы
	$subpage_list="";
	$mode=" look='yes'";
	$order=" ORDER BY timeadd DESC";
	$pagenum=0;
	$startrow= $pagenum * MAXROWS;
	$query="SELECT id FROM ".$item_viev->table." WHERE " . $mode;
	//$query_limit = sprintf("SELECT p.* FROM  %s p WHERE p.parent=%d AND %s %s LIMIT %d, %d", $item_viev->table, $item_viev->my_item['id'], $mode, $order, $startrow, MAXROWS);
	$query_limit = sprintf("SELECT p.* FROM  %s p WHERE p.parent=%d AND %s %s ", $item_viev->table, $item_viev->my_item['id'], $mode, $order);
	//   print $query_limit;
	$all = mysql_query($query_limit) or die(mysql_error());
	$allrows = mysql_num_rows($all);
	$total = mysql_query($query);
	$totalrows = mysql_num_rows($total);
	mysql_free_result($total);
	$totalpages = ceil($totalrows/MAXROWS)-1;
	if($allrows!=0){
		while(($row = mysql_fetch_assoc($all))!=false){
			//print($patch_url );
			$cur_patch_url=$patch_url.$row['nik']."/";
			if($row['foto']!=NULL)// width="80" height="80"
			$subpage_list.=sprintf('<a href="%s"><img src="/picture/foto%s.%s" alt="%s" width="130" height="90" align="left" border="0" /></a>'
					,$cur_patch_url,$row['id'],$row['foto'],$row['name']);
			$subpage_list.=sprintf("<h3><a href='%s'>%s</a></h3><p  align='justify'>%s</p><br class='clearfloat' />",$cur_patch_url,(($row['tlist']!="")?($row['tlist']):($row['title'])),$row['anons']);
		}
		$item_viev->my_item['article']=str_replace("%SUBPAGE_LIST%",$subpage_list, $item_viev->my_item['article']); 
	}
}

//ТУДА СЮДА
$pos =false;
//определяем, нужно ли
if($item_viev->my_item['parent']!=0){//только для третьего уровня и дальше
	$mode=" look='yes'";
	$order=" ORDER BY timeadd DESC";
	$parent_item=new catalog();	
	$parent_item->table=$item_viev->table;
	$parent_item->load($item_viev->my_item['parent'],  $item_viev->table);
	//print_r($parent_item);
	$pos = strpos($parent_item->my_item['article'], '%SUBPAGE_LIST%');//если в Паренте демонстрируется туда сюда стрелки.
	if ($pos !== false) {//список есть
		//формируем путь к родительской категории
 		$ppatch_array=$parent_item->get_path_seo_array();
		$ppatch_url="/";
		foreach($ppatch_array as $pat){
			$ppatch_url.=sprintf("%s/",$pat['nik']);
		}
		$ppatch_url.=$parent_item->my_item['nik']."/";
		
		//ССЫЛЬ на раздел
		//print_r($parent_item);
		$tudasuda=sprintf('<div id="to_list"><a href="%s">%s</a></div>',$ppatch_url,$parent_item->my_item['title']);
		
		//вставляем туда-сюда
		$tudasuda.= '<div id="tudasuda"></div>';
		//TODO - если не нужно скрывать ссылки а нужна перелинковка по кольцу, выводить через PHP
	}
}*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Экспресс обучение в Школе груминга Боншери</title>
<meta name="description" content="школа груминга боншери приглашает на экспресс обучение" />
<link href="/css/page.css" rel="stylesheet" type="text/css" />
<?php
/*if ($pos !== false){
	echo '<script type="text/javascript" src="/js/tw-sack.js" ></script>';
	echo '<script type="text/javascript" src="/js/page.js" ></script>';
}*/
?>
</head>
<body class="twoColFixLt" <?php
//if ($pos !== false)
//	echo ' onload="chekin(',$item_viev->my_item['parent'],',',$item_viev->my_item['id'].')" ';
?>
>
<div id="container">
<div id="header">
<!-- end #header --></div>


<div id="mainContent" style="width:945px">
  <h1 >Вам скоро позвонит Валентина</h1>
  <p ><strong>Валентина</strong> - самый опытный администратор школы груминга Боншери. Вы можете задать ей любые вопросы.</p>
  <p>&nbsp;</p>
  <p>Не хотите ждать?</p>
  <p>Отправьте на адрес электронной почты <a href="mailto:school@petsgroomer.ru">school@petsgroomer.ru</a> свои вопросы и получите на них ответы.</p>
  <p>&nbsp;</p>
  <p>А пока рекомендуем <a href="/_sustribe/">подписаться на рассылку новостей</a> школы Боншери. Вы будете узнавать обо всем первым.</p>
  <p>&nbsp;</p>
  <p>Или ознакомьтесь с <a href="/">нашим сайтом...</a></p>
   <p>Спасибо!</p>
</div>
  
<br class="clearfloat" />
<!-- end #container --></div>
<?php include_once(INCLUDE_PATH."footer.php"); ?>
</body>
</html>