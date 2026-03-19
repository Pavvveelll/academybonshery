<div  id="additemdiv">
<?php 
//print_r($item);
if($item->error!=""){
?>
<div id="error"><?php echo $item->error ?></div><br />
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
//print_r($item->my_item);
/*$get_full_tree=$catalog->get_full_tree(0);
//исключить из списка себя и дочерние категории
foreach($get_full_tree as $v){
	$add_sourse[$v['id']]=str_repeat("&nbsp;&nbsp;&nbsp;",intval($v['l'])-1).$v['name'];
}
unset($get_full_tree);
$select_sourse[]=$add_sourse;*/
//print_r($reference_array);
//$select_sourse[]=$reference_array;
//$select_sourse[]=$edu_array;
//$select_sourse[]=$reference_array;//список списков для формирования списков :)

//$item->my_item['adr_full']=$totalrows;
//$item->my_item['adr_fin']=$activerows;
//$item->fields['adr_full']['text']="<a href='/admin/maillist.php?list=".$item->my_item['id']."' titlr='редактирвать'>Всего адресатов</a>";
//
//$template="<tr><td width=\"180\" valign=\"top\">%s
	////</td><td width=\"3\">&nbsp;</td><td width=\"467\" valign=\"top\">%s</td></tr>";
	// print_r($item->my_item);
if($item->my_item['stat']!='active'){
	$item->my_item['ru4naya']="рассылка неактивна или нет  адресов для рассылки";
}else{
	$item->my_item['ru4naya']="<a href='/admin/maillist.php?list=".$item->my_item['id']."&send=50"
	.((isset($_GET['sort']))?("&sort=".$_GET['sort']):("")).
	((isset($_GET['mode']))?("&mode=".$_GET['mode']):(""))."'>разослать...</a>";
}
//$item->my_item['testovaya']="<a href='/admin/maillist.php?list=".$item->my_item['id']."&send=test' title='Разослать на администраторские адреса для проверки'>разослать...</a>";
//$button_array['delete_noviev'];
$button_array=array('submit'=>"Сформировать",'deletefieldname'=>'action_item','deleteconfirm'=>'Очистить список для рассылки?','deletetext'=>'Очистить список');
 if(($item->my_item['adr_full']<1)){//||($item->my_item['adr_full']<=$item->my_item['adr_fin'])
	$button_array['delete_noviev']="noviev";
 }
$select_sourse[]=$button_array;
print html_form($item->fields,$item->my_item,$select_sourse );

?> 
</div>