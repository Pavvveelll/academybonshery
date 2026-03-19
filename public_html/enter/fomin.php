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
  <h1 align="center">Роман Фомин</h1>
  <p><strong>Я, Роман Фомин - преподаватель «Школы Боншери»</strong>, веду экспресс-курс по обучению стрижке собак, и несколько мастерклассов по породам.</p>
  <p>Несколько слов о себе, о своей карьере, чем живу и что ценю в людях и коллегах</p>
<iframe style="padding-left:100px" width="420" height="240" src="//www.youtube-nocookie.com/embed/x_XrC1VIPak?rel=0&showinfo=0" frameborder="0" allowfullscreen></iframe> 
     <p>&nbsp;</p>
   <p>Приглашаю Вас пройти самый эффективный в России курс обучения грумингу.</p>
   <p>Хотите подробностей?</p>
  <p>Закажите обратный звонок, и с Вами свяжется администратор школы и подробно всё расскажет.</p>
  <p style="font-size:16px; color:#F90"> Прим. до 4-го ноября есть скидка, после не будет.</p>
  <p style="font-size:24px; color:#F90">Осталось: <span id="reload_timer"></span></p>
  <script type="text/javascript">
	var dn = new Date(2013, 10, 3, 18, 0, 0);
 //alert(dn);
//alert (d.toDateString()); // prints Wed Jul 28 1993 14:39:07 GMT-0600 (PDT)
//print (d.toDateString()); // prints Wed Jul 28 1993

	var interr =( dn )/1000   ; //интервал обновления страницы в секундах
	 
	//var intert = 1; //интервал обновления таймера в секундах

	var timer_text = function(sec) {
		//alert (sec);
		sec = interr-sec;
		if(sec>0){		
			var d = Math.floor(sec / 3600/ 24);
			var h = Math.floor(sec / 3600)-d*24;
			var m = (Math.floor(sec / 60) - h*60 -d*24*60);
			var s = Math.floor(sec % 60);
			var text = '';
			if(d > 0) text += d + " " +declination("дней", "день", "дня", d) + "  ";
			if(h > 0) text += h + " " +declination("часов", "час", "часа", h) + "  ";
			if(m > 0) text += m+ " " + declination("минут", "минута", "минуты", m) + " ";
			if(s > 0) text += s+" " + declination("секунд", "секунда", "секунды", s);
		}else{
			var text = '00:00';
		}
		document.getElementById("reload_timer").innerHTML = text;
	}

	var declination = function(a, b, c, s) {
	  var words = [a, b, c];
	  var index = s % 100;
	  if (index >=11 && index <= 14) { index = 0; }
	  else { index = (index %= 10) < 5 ? (index > 2 ? 2 : index): 0; }
	  return(words[index]);

	}

//	timer_start_date = new Date().getTime();
//	reload_interval = setInterval(function() { //авто обновление страницы
//		window.location.href=window.location.href;
//	}, interr*1000); //Интервал обновления  в миллисекундах, т.е 1000 это 1 секунда
	
	timer_text((new Date().getTime()  ) / 1000);
	
	setInterval(function() { //авто обновление таймера
		timer_text(Math.ceil((new Date().getTime()  ) / 1000));
	},  1000); //Интервал таймера в миллисекундах, т.е 1000 это 1 секунда

</script>


  <div style="width:500px;  ">
  <?php
  $form_name='form_item2';
   include("enter_form.php");?></div>
  <p>&nbsp;</p>
</div>
  
<br class="clearfloat" />
<!-- end #container --></div>
<?php include_once(INCLUDE_PATH."footer.php"); ?>
</body>
</html>