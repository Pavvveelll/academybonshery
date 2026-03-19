<?php
$mode=" ";
// if(isset($_GET['mode'])){
//	 $get_mode=$_GET['mode'];
// }else{
//	 $get_mode="";
// }
//switch ($get_mode) {
//	case "active":
//	   $mode_text=" - к рассылке</h1>";
//	   $mode="AND a.stat='active'";
//		break;
//	case "finish":
//	   $mode_text=" - разосланные";
//	   $mode="AND a.stat='finish'";
//		break;		
//	default:
//		$mode_text="";
//		$mode=" ";
//}
/////сортировка
$s_sort="";
if(isset($_GET['sort'])){
	$s_sort=$_GET['sort'];
}

$s_mode=((isset($_GET['mode']))?("&mode=".$_GET['mode']):(""));
$order= " ORDER BY s.id DESC ";
switch  ($s_sort){
	case "ea"://убывание email
		$order= " ORDER BY s.login ";
		break;
	case "ed"://убывание email
		$order= " ORDER BY s.login DESC ";
		break;
	case "na"://по алфавиту
		$order= " ORDER BY s.name  ";
		break;
	case "nd"://по дате добавления
		$order= " ORDER BY s.name  DESC ";
		break;	
	case "sa"://по алфавиту
		$order= " ORDER BY s.sstatus  ";
		break;
	case "sd"://по дате добавления
		$order= " ORDER BY s.sstatus  DESC ";
		break;
	case "pa"://по приходам
		$order= " ORDER BY s.prihod  ";
		break;
	case "pd":
		$order= " ORDER BY s.prihod  DESC ";
		break;
	case "ra"://по рассылкам
		$order= " ORDER BY s.rs  ";
		break;
	case "rd":
		$order= " ORDER BY s.rs  DESC ";
		break;
}

?>
<div id="path"><a href="maillist.php">Рассылки</a> - 
<?php 
printf("<a href='/admin/maillist.php?sustribers=%d'>Группа: %s</a></div>",$item->my_item['id_group'],$item->my_item['gname']) ;

if($error!=""){ ?>
	<div id="error"><?php echo $error ?></div>
<?php
}
//print($sustriber->my_item['id']);
?>
<h1  class="greentext"><?=$item->my_item['gname']?></h1>
<p><?=nl2br($item->my_item['gdes'])?></p>

<?php if($sustriber->my_item['id']==0){ ?>
	<div onclick="toggleElement('additemdiv')" style="color:#093; font-weight:bold; cursor:pointer; text-decoration:underline; padding-top:10px; float:left;line-height:24px;">Добавить подписчика...</div> 
    <div style="padding-top:10px;padding-left:10px; float:left;line-height:24px;"><form action="" method="post"><input name="slogin" type="text" /><input name="s" type="submit" value="искать" /></form></div>
			<div style="padding-top:10px;float:right;line-height:24px;"><a  href="/admin/maillist.php?stat=ok&sustribers=<?=$item->my_item['id_group']?>">Статистика</a></div>
              <br class="clear"/>
<?php }else{ ?>
<a href="/admin/maillist.php?display_sstb=new&sustribers=<?=$item->my_item['id_group']?>" style="color:#093; font-weight:bold; display:block; cursor:pointer; text-decoration:underline; padding-top:10px">Добавить подписчика...</a>
<?php }  
if(isset($_GET['display_sstb'])&&$_GET['display_sstb']=="new")
	$display_sstb="block";
?>
<div  id="additemdiv" style="border:1px solid #999; margin-top:10px; width:640px; display:<?=$display_sstb?>">
<?php 
if($sustriber->error!=""){
?>

<div id="error"><?php echo $sustriber->error ?></div>
<?php 
}
include_once(CLASS_PATH."function/html.function.php"); 
$select_sourse=array();
//Группы подписчиков
$query = sprintf("SELECT * FROM %s_sustribe_group ", DB_PREFIX);
//print($query);	
$sustribe_group=array();
$pod = mysql_query($query) or die(mysql_error());
while(($rowe = mysql_fetch_assoc($pod))!=false){
	$sustribe_group[$rowe['id_group']]	=$rowe['gname'];
}
mysql_free_result($pod);
$select_sourse[]=$sustribe_group;

$button_array=array( 'deletefieldname'=>'action_sus','deleteconfirm'=>'Удалить подписчика?' );
$select_sourse[]=$button_array;
$template="<td >%s<br /> %s</td>";
print html_form($sustriber->fields,$sustriber->my_item,$select_sourse,$template);
?> 
</div>

<?php 
$maxrowlist=30;
$pagenum = 0;
if (isset($_GET['pn'])){
	$pagenum = intval($_GET['pn'])-1;
}
$startrow= $pagenum * $maxrowlist;

////$query_active = sprintf("SELECT COUNT(*) FROM %s_mailadres WHERE id_maillist=%d AND stat='finish' ", DB_PREFIX,$item->my_item['id']);
////$query_limit = 	sprintf("SELECT * FROM %s_maillist WHERE %s %s LIMIT %d, %d", DB_PREFIX, $mode, $order, $startrow, $maxrowlist);
$query_limit = 	sprintf("SELECT s.* FROM %s_sustribe s WHERE s.sgroup=%d %s %s LIMIT %d, %d",
						DB_PREFIX,$item->my_item['id_group'],$mode,$order, $startrow, $maxrowlist);
// print $query_limit;
if (isset($_GET['tr'])) {
	$totalrows = $_GET['tr'];
} else {
	$query =sprintf("SELECT COUNT(*) FROM %s_sustribe s WHERE s.sgroup=%d %s", DB_PREFIX, $item->my_item['id_group'],$mode);
	$total = mysql_query($query);
	$totalrows = mysql_fetch_row($total);
	$totalrows=$totalrows[0];
}
////	$active = mysql_query($query_active);
////	$activerows = mysql_fetch_row($active);
////	$activerows=$activerows[0];
////print_r($activerows);
//
//include("maillist/maillist_form_adr_filter.php");
////print_r($all_array);

//$totalrows=count($all_array);
$totalpages = ceil($totalrows/$maxrowlist)-1;

$querystring= "";
$q_array=array();
if (!empty($_SERVER['QUERY_STRING'])) {
	
	parse_str($_SERVER['QUERY_STRING'],$q_array);
	//print_r($q_array);
	unset($q_array['pn']);
	unset($q_array['tr']);
	unset($q_array['id']);
	unset($q_array['sstrb']);
	$querystring= http_build_query($q_array);
}


if($totalrows!=0){
	$all = mysql_query($query_limit) or die(mysql_error());	
	//иконки сортировки
	$s_a='<img src="/admin/images/su.gif" width="16" height="6" alt="v"  border="0" />';
	$s_d='<img src="/admin/images/sd.gif" alt="^" width="16" height="6" border="0" />';
?><br />
<form action="" method="post" name="adr_lst_form"  id="adr_lst_form">
<table border="0" cellspacing="0" cellpadding="2" width="640">
  <tr bgcolor="#DDD" style="text-align:left">
    <th>№</th><th>&nbsp;</th>
    <th><input name="chekadr" id="chekadr" type="checkbox" value="test" onclick="checkall('adr_lst_form', this.form.chekadr.checked)" title="Отметить/Снять все"   /></th>
    <th><?=($s_sort=='ea')?("<a href='?sustribers=".$item->my_item['id_group']."&sort=ed'>e-mail".$s_a."</a>"):(($s_sort=='ed')?("<a href='?sustribers=".$item->my_item['id_group']."&sort=ea'>e-mail".$s_d."</a>"):("<a href='?sustribers=".$item->my_item['id_group']."&sort=ea'>e-mail</a>"))?></th>
    <th><?=($s_sort=='na')?("<a href='?sustribers=".$item->my_item['id_group']."&sort=nd'>Имя".$s_a."</a>"):(($s_sort=='nd')?("<a href='?sustribers=".$item->my_item['id_group']."&sort=na'>Имя".$s_d."</a>"):("<a href='?sustribers=".$item->my_item['id_group']."&sort=na'>Имя</a>"))?></th>
    <th>МК Кс</th>
    <th><?=($s_sort=='sa')?("<a href='?sustribers=".$item->my_item['id_group']."&sort=sd'>Статус".$s_a."</a>"):(($s_sort=='sd')?("<a href='?sustribers=".$item->my_item['id_group']."&sort=sa'>Статус".$s_d."</a>"):("<a href='?sustribers=".$item->my_item['id_group']."&sort=sa'>Статус</a>"))?></th>
    <th title="Произведено рассылок"><?=($s_sort=='ra')?("<a href='?sustribers=".$item->my_item['id_group']."&sort=rd'>Рс".$s_a."</a>"):(($s_sort=='rd')?("<a href='?sustribers=".$item->my_item['id_group']."&sort=ra'>Рс".$s_d."</a>"):("<a href='?sustribers=".$item->my_item['id_group']."&sort=rd'>Рс</a>"))?></th>
     <th title="Приходов"><?=($s_sort=='pa')?("<a href='?sustribers=".$item->my_item['id_group']."&sort=pd'>Пр".$s_a."</a>"):(($s_sort=='pd')?("<a href='?sustribers=".$item->my_item['id_group']."&sort=pa'>Пр".$s_d."</a>"):("<a href='?sustribers=".$item->my_item['id_group']."&sort=pd'>Пр</a>"))?></th>
  </tr>

<?php 
$co=$maxrowlist*$pagenum;
while(($rowe = mysql_fetch_assoc($all))!=false){
//$data_send=date("d-m-Y H:i",strtotime ($rowe['timeadd']));
$co++;
?><tr>
    <td><?php echo $co ?></td>
    <td><a href='?sustribers=<?=$item->my_item['id_group']?>&sstrb=<?=$rowe['id'] ?>'><img src="/admin/images/edit_16.gif" width="16" height="16" alt="ред" border="0" /></a></td>
    <td><input name="ckb<?=$rowe['id'] ?>" id="ckb<?=$rowe['id'] ?>" type="checkbox" value="<?=$rowe['id'] ?>"   /></td>
    <td><?=$rowe['login'] ?></td>
    <td><?=$rowe['name'] ?></td>
    <td><?php
    if($rowe['master']=="yes") { 	 
		print("МК ");
	}else{
		print("&nbsp;-&nbsp;&nbsp; ");
	}
	if($rowe['kurs']=="yes") {
		print(" Кс ");
	}else{
		print(" &nbsp;-&nbsp;");
	}
 
	 
	  ?></td>
    <td><?php  
switch  ( $rowe['sstatus'] ){
	case "uns"://убывание email
		print('<span style="color:#F00">отписались</span>');
		break;
	case "new"://убывание email
		print('<span style="color:#00F">новые</span>');
		break;
	case "yes"://по алфавиту
		print('<span style="color:#093">активные</span>');
		break;
}
	?></td>
    <td><?=$rowe['rs'] ?></td>
    <td><?=$rowe['prihod'] ?></td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<input name="list_s" id="list_s" value="all" type="hidden">
</form>
<div class="buttons">  
<div class="r" style="float: left;"><a href="javascript:confirmDeleteAction('all','list_s', 'Удалить отмеченных подписчиков?','adr_lst_form');" >Удалить выбранные</a></div>
</div>
<?php 
	if($totalpages>0){
		include(INCLUDE_PATH."listing.php");
	}
}else{//if($totalrows!=0){
	?><p><br /><br />Список пуст.</p><?php
}
?>