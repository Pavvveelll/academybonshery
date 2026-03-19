<?php
//TODO ЗАПРАШИВАЕТСЯ КУЧА ДАННЫХ потом она долго перерабатывается, может както проще можно запросить
if(!isset($_GET['mode']))
			  $_GET['mode']="";//'new','active','finish','arhive

  switch ($_GET['mode']) {
	  case "new":
		 $mode_text=" - новые</h1>";
		 $mode=" stat='new' ";
		  break;
	  case "active":
		 $mode_text=" - активные";
		 $mode=" stat='active' ";
		  break;
	  case "finish":
		 $mode_text=" - завершенные";
		 $mode=" stat='finish' ";
		  break;
	  case "arhive":
		 $mode_text=" - архив ";
		 $mode=" stat='arhive' ";
		  break;
	  case "all":
		 $mode_text=" - все рассылки";
		 $mode=" 1 ";
		 break;		
	  default:
		  $mode_text=" - текущие";
		  $mode=" stat<>'arhive' ";
  }
  $sortarray= array("timeadd","item","popul");
if((isset($_GET['sort']))&&(in_array($_GET['sort'],$sortarray))){
	$sort = trim($_GET['sort']);
}else{
	$sort= "timeadd DESC";
}
$order =" ORDER BY $sort ";
///////////////////
/* $whose_add=(($whose_base=='item')?("?whose=".$whose):(""));
$whose_add2=(($whose_base=='item')?("&whose=".$whose):(""));//TODO explode*/
?>
<div id="path"><a href="maillist.php">Рассылки</a> <?=$mode_text?></div>
<?php 
 if($error!=""){ ?>
	<div id="error"><?php echo $error ?></div>
<?php
}
$pagenum = 0;
if (isset($_GET['pn'])) {
	$pagenum = intval($_GET['pn'])-1;
}
$startrow= $pagenum * MAXROWS;

$query = sprintf("SELECT COUNT(*) FROM %s_maillist WHERE %s", DB_PREFIX,$mode);	
$query_limit = 	sprintf("SELECT * FROM %s_maillist WHERE %s %s LIMIT %d, %d", DB_PREFIX, $mode, $order, $startrow, MAXROWS);		
/// print $query_limit;
if (isset($_GET['tr'])) {
	$totalrows = $_GET['tr'];
} else {
	$total = mysql_query($query);
	$totalrows = mysql_fetch_row($total);
	if(totalrows!=false){
		$totalrows=$totalrows[0];
	}else{
		$totalrows=0;
	}
}
//print_r($totalrows);
$all = mysql_query($query_limit) or die(mysql_error());

//print_r($all_array);

//$totalrows=count($all_array);
$totalpages = ceil($totalrows/MAXROWS_MESSAGES)-1;
//$querystring ="";
$chart_col_name=array();
$chart_data1_arr=array();//приходов
$chart_data2_arr=array();//разослано
$chart_max=0;
if($totalrows!=0){
while(($rowe = mysql_fetch_assoc($all))!=false){
	$chart_col_name[]=$rowe['id'];
	$chart_data1_arr[]= $rowe['prihod'];
	$chart_data2_arr[]= $rowe['adr_fin']-$rowe['prihod'];
	$chart_max=max($rowe['adr_fin'],$rowe['prihod'],$chart_max);
$data_send=date("d-m-Y H:i",strtotime ($rowe['timeadd']));
?> 
	<a href="?id=<?=($rowe['id'])?>" title="Редактировать"><h2><?php echo $rowe['id'] ?>.&nbsp;<?=$rowe['subject'] ?></h2></a>
    Статус рассылки:<?php 
	
	print $stat_arr[$rowe['stat']]
	?><br />Начало рассылки:<?php print $data_send?> <br />
<strong>Всего адресов</strong>:<?php print $rowe['adr_full']?> <strong>Разослано</strong>:<?php print $rowe['adr_fin']?> <strong>Визитов</strong>:<?php print $rowe['adr_rez']?> <strong>Приходов</strong>:<?php print $rowe['prihod']?>
    <hr size="1" color="#CCCCCC" />
<?php 
}//while
$chart_max=intval($chart_max*1.1);
//$totalpages = ceil($totalrows/MAXROWS_MESSAGES)-1; //считаем от НУЛЯ
$querystring=(($_GET['mode']!="")?("&mode=".$_GET['mode']):(""));
//print $totalpages;
if($totalpages>0){
	include(INCLUDE_PATH."listing.php");
}

?>
<?php 
}else{//if($totalrows!=0){
?>
   <div>Нет рассылок для показа</div>    
<?php
}
?>
<div class="buttons">  
<div class="g"><a href="<?php printf("%s?id=0",$phpself) ?>" >Новая рассылка...</a></div>
</div>
<?php include("chart_maillist.php"); ?>

