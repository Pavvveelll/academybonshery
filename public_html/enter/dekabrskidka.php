<?php 
// print_r($_GET);
require_once("../class/common.php");
 
 
 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Обучение Школа груминга Боншери</title>
 
<link href="/css/page.css" rel="stylesheet" type="text/css" />

</head>
<body class="twoColFixLt" >
<div id="container">
<div id="header"><div id="logoa" onclick="window.location.href='<?=SERVER_HOST?>'"></div>
<!-- end #header --></div>
<div id="mainContent" style="font-size:16px">
<p>итак, Вы решили стать грумером.</p>
 <p>но скидка <span style="font-size:28px; color:#F90">25%</span>  от Вас уже ушла, ибо Вы немного опоздали</p>
 <p>но раз Вы уже здесь...</p>
 <p>вы можете записаться на экспресс-курс обучения стрижке собак</p>
<p> за 35 тысяч рублей вместо 40, если сделаете это до 2 декабря</p>
 <p><a href="dekabr.php"><img src="oplata.jpg" alt="оплатить" width="184" height="33" border="0" /></a><span id="reload_timer"></span></p>
 
 <p>Экспресс-курс закончили более 300 учеников, и все они, кроме особо ленивых, работают по специальности.</p>
<p>Это Ваш уникальный шанс получить новую, интересную профессию всего за 7 дней , которые к вам вернутся после первого же месяца работы.</p>
 <p><a href="dekabr.php"><img src="oplata.jpg" alt="оплатить" width="184" height="33" border="0" /></a></p>
 <p>&nbsp;</p>
</div>
  <div id="sidebar1">
  <div  class="page" id="gmenu">
	<?php include(INCLUDE_PATH."menu.php");?>
  </div>
  <br class="clearfloat" />
<?php //include_once(INCLUDE_PATH."left_col.php"); ?>
<br class="clearfloat" />
  <!-- end #sidebar1 --></div>
<br class="clearfloat" />
<!-- end #container --></div>
<?php include_once(INCLUDE_PATH."footer.php"); ?>
</body>
</html>