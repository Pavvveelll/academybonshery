<?php 
// print_r($_GET);
session_start();
require_once("../class/common.php");
include_once("sustribe.item.php");

if($item->error==""){
	$item->create_blank();
}
$error_item="";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Я, Роман Фомин</title>
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


<div id="mainContent" style="padding-right:150px; width:645px; ">
  <p><strong>Я, Роман Фомин - преподаватель «Школы Боншери»</strong>, веду экспресс-курс по обучению стрижке собак, и несколько мастерклассов по породам.</p>
  <p>Вот небольшой, но очень полезный урок для начинающего грумера.</p>
<iframe width="560" height="315" src="//www.youtube-nocookie.com/embed/z3WjLTuEkNI?rel=0&showinfo=0" frameborder="0" allowfullscreen></iframe> 
     <p>&nbsp;</p>
   <p>Если Вам интересна профессия грумер и Вы пока только задумываетесь над этим рекомендую, подпишитесь на рассылку Школы груминга Боншери, и Вы регулярно будете получать свежую информацию.</p>
   <p>На настоящий момент рассылку читает <?php
   $sql=sprintf("SELECT COUNT(*) AS co FROM  %s_sustribe  WHERE  sstatus ='yes'",DB_PREFIX); 				
	 // print($sql);
	$query = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	echo '<span style="font-size:18px;">',$row['co'],'</span>';
	mysql_free_result($query);
   
   ?> человек.</p>
   <p style="text-align:center"><strong>Оформить подписку:</strong></p>
  <?php include("sustribe_form.php"); ?>
</div>
  
<br class="clearfloat" />
<!-- end #container --></div>
<?php include_once(INCLUDE_PATH."footer.php"); ?>
</body>
</html>