<?php
 if(isset($_GET['mode'])){
	 $get_mode=$_GET['mode'];
 }else{
	 $get_mode="";
 }
switch ($get_mode) {
	case "active":
	   $mode_text=" - к рассылке</h1>";
	   $mode="AND a.stat='active'";
		break;
	case "finish":
	   $mode_text=" - разосланные";
	   $mode="AND a.stat='finish'";
		break;		
	default:
		$mode_text="";
		$mode=" ";
}
/////сортировка
$s_sort="";
if(isset($_GET['sort'])){
	$s_sort=$_GET['sort'];
}

$s_mode=((isset($_GET['mode']))?("&mode=".$_GET['mode']):(""));
$order= " ";
switch  ($s_sort){
	case "ea"://убывание email
		$order= " ORDER BY email ";
		break;
	case "ed"://убывание email
		$order= " ORDER BY email DESC ";
		break;
	case "na"://по алфавиту
		$order= " ORDER BY name  ";
		break;
	case "nd"://по дате добавления
		$order= " ORDER BY name  DESC ";
		break;	
	case "sa"://по статусу
		$order= " ORDER BY stat, sendtime  ";
		break;
	case "sd":
		$order= " ORDER BY stat  DESC ,sendtime DESC ";
		break;
	case "pa"://по времени прихода
		$order= " ORDER BY prihodtime  ";
		break;
	case "pd":
		$order= " ORDER BY prihodtime  DESC ";
		break;
}
?>
<div id="path"><a href="maillist.php">Рассылки</a> - <?php 
	printf("<a href='/admin/maillist.php?id=%d'>%s</a>",$item->my_item['id'],$item->my_item['subject']) 
?> - <?php 
	printf("<a href='/admin/maillist.php?list=%d'>Адреса</a>%s",$item->my_item['id'],$mode_text ) 
?></div>Статус рассылки:
<?php
	print $stat_arr[$item->my_item['stat']]
?>                                        
<?php if($error!=""){ ?>
	<div id="error"><?php echo $error ?></div>
    
<?php
}
?><br class="clear"/>
<h1 class="greentext" style="float:left">Адресаты</h1>
<div style="padding:10px 10px 0 0 ;float:right"><a  href="/admin/maillist.php?stat=ok&list=<?=$item->my_item['id']?>">Статистика</a></div>
<br class="clear"/>

<?php 

$maxrowlist=30;
$pagenum = 0;
if (isset($_GET['pn'])) {
	$pagenum = intval($_GET['pn'])-1;
}
$startrow= $pagenum * $maxrowlist;
$query = 		sprintf("SELECT COUNT(*) FROM %s_mailadres a WHERE a.id_maillist=%d %s", DB_PREFIX, $item->my_item['id'],$mode);
//$query_active = sprintf("SELECT COUNT(*) FROM %s_mailadres WHERE id_maillist=%d AND stat='finish' ", DB_PREFIX,$item->my_item['id']);
//$query_limit = 	sprintf("SELECT * FROM %s_maillist WHERE %s %s LIMIT %d, %d", DB_PREFIX, $mode, $order, $startrow, $maxrowlist);
$query_limit = 	sprintf("SELECT a.*, u.id AS uid, u.sgroup FROM %s_mailadres a, %s_sustribe u WHERE a.id_maillist=%d %s AND a.email=u.login  %s LIMIT %d, %d",
						DB_PREFIX,DB_PREFIX,$item->my_item['id'],$mode, $order, $startrow, $maxrowlist);
// print $query_limit;
//if (isset($_GET['tr'])) {
//	$totalrows = $_GET['tr'];
//} else {
	$total = mysql_query($query);
	$totalrows = mysql_fetch_row($total);
	$totalrows=$totalrows[0];
//}
//	$active = mysql_query($query_active);
//	$activerows = mysql_fetch_row($active);
//	$activerows=$activerows[0];
//print_r($activerows);
$all = mysql_query($query_limit) or die(mysql_error());
include("maillist/maillist_form_adr_filter.php");
//print_r($all_array);

//$totalrows=count($all_array);
$totalpages = ceil($totalrows/$maxrowlist)-1;
//$querystring ="";
$querystring= "";
if (!empty($_SERVER['QUERY_STRING'])) {
	$params = explode("&", $_SERVER['QUERY_STRING']);
	$newParams = array();
	//print_r($params);
	foreach ($params as $param) {
		if (stristr($param, "pn") == false && 
			stristr($param, "tr") == false&& 
			stristr($param, "id") == false)  {
			array_push($newParams, $param);
		}
	}//print_r($newParams);
	if (count($newParams) != 0) {
	$querystring = "&" . htmlentities(implode("&", $newParams));
	}
	
}
//$querystring = sprintf("&tr=%d%s", $totalrows, $querystring);
//print($querystring);
if($totalrows!=0){
//print_r($item->my_item);	
	//иконки сортировки
	$s_a='<img src="/admin/images/su.gif" width="16" height="6" alt="v"  border="0" />';
	$s_d='<img src="/admin/images/sd.gif" alt="^" width="16" height="6" border="0" />';
?><br />
<form action="" method="post" name="adr_lst_form"  id="adr_lst_form">

<table border="0" cellspacing="0" cellpadding="2" width="640">
  <tr bgcolor="#DDD" style="text-align:left">
    <th>№</th><th>&nbsp;</th>
    <th><input name="chekadr" id="chekadr" type="checkbox" value="test" onclick="checkall('adr_lst_form', this.form.chekadr.checked)" title="Отметить/Снять все"   /></th>
    <th><?=($s_sort=='ea')?("<a href='?list=".$item->my_item['id']."&sort=ed'>e-mail".$s_a."</a>"):(($s_sort=='ed')?("<a href='?list=".$item->my_item['id']."&sort=ea'>e-mail".$s_d."</a>"):("<a href='?list=".$item->my_item['id']."&sort=ea'>e-mail</a>"))?></th>
    <th><?=($s_sort=='na')?("<a href='?list=".$item->my_item['id']."&sort=nd'>Имя".$s_a."</a>"):(($s_sort=='nd')?("<a href='?list=".$item->my_item['id']."&sort=na'>Имя".$s_d."</a>"):("<a href='?list=".$item->my_item['id']."&sort=na'>Имя</a>"))?></th>
    <th><?=($s_sort=='sa')?("<a href='?list=".$item->my_item['id']."&sort=sd'>Отправлено".$s_a."</a>"):(($s_sort=='sd')?("<a href='?list=".$item->my_item['id']."&sort=sa'>Отправлено".$s_d."</a>"):("<a href='?list=".$item->my_item['id']."&sort=sa'>Отправлено</a>"))?></th>
   <th><?=($s_sort=='pa')?("<a href='?list=".$item->my_item['id']."&sort=pd'>Приход".$s_a."</a>"):(($s_sort=='pd')?("<a href='?list=".$item->my_item['id']."&sort=pa'>Приход".$s_d."</a>"):("<a href='?list=".$item->my_item['id']."&sort=pa'>Приход</a>"))?></th>
  </tr>

<?php 
$co=$maxrowlist*$pagenum;
while(($rowe = mysql_fetch_assoc($all))!=false){
//$data_send=date("d-m-Y H:i",strtotime ($rowe['timeadd']));
$co++;
?><tr>
    <td><?php echo $co ?></td>
    <td><a href='?sustribers=<?=$rowe['sgroup']?>&sstrb=<?=$rowe['uid'] ?>'><img src="/admin/images/edit_16.gif" width="16" height="16" alt="ред" border="0" /></a></td>
    <td><?php if($rowe['stat']!="finish"){?>
    <input name="ckb<?=$rowe['id'] ?>" id="ckb<?=$rowe['id'] ?>" type="checkbox" value="<?=$rowe['id'] ?>"   />
    <?php }else{
		print("&nbsp;");
	}?> 
    </td>
    <td><?=$rowe['email'] ?></td>
    <td><?=$rowe['name'] ?></td>
    <td><?php  
switch  ( $rowe['stat'] ){
	case "uns":
		print('<span style="color:#F00">отписались</span>');
		break;
	case "finish":
		print('<span style="color:#00F">'.(($rowe['sendtime']=="0000-00-00 00:00:00")?("отправлено"):($rowe['sendtime'])).'</span>');
		break;
	case "active": 
		print('<span style="color:#093">не отправлено</span>');
		break;
}
	?></td>
    <td><?=(($rowe['prihodtime']=="0000-00-00 00:00:00")?("&nbsp"):($rowe['prihodtime'])) ?></td>
  </tr>
<?php 
}//while
//$totalpages = ceil($totalrows/$maxrowlist_MESSAGES)-1; //считаем от НУЛЯ
//$querystring=(($_GET['mode']!="")?("&mode=".$_GET['mode']):(""));
//print $totalpages;
//Отсортировано:  =$r_link ><br />
?>  <tr>
	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<input name="list_h" id="list_h" value="all" type="hidden">
</form>
<div class="buttons">  
<div class="r" style="float: left;"><a href="javascript:confirmDeleteAction('all','list_h', 'Удалить отмеченные адреса?','adr_lst_form');" >Удалить выбранные</a></div></div>
<?php 
if($totalpages>0){
	include(INCLUDE_PATH."listing.php");
}
}else{//if($totalrows!=0){
?>
   <div>Список адресов не создан, выберите категорию и нажмите «Сформировать»</div>    
<?php
}
?>



