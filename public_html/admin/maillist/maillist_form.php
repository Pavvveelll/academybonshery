<div id="path"><a href="maillist.php">Рассылки</a> <?php 
if(isset($item->my_item['id'])&&$item->my_item['id']>0){
printf("- <a href='/admin/maillist.php?id=%d'>%s</a>",
										$item->my_item['id'],$item->my_item['subject']); 
}
?><?php 
if(isset($item->my_item['stat'])&&$item->my_item['stat']!="arhive"&&$item->my_item['stat']!="new"){
	printf(" - <a href='/admin/maillist.php?list=%d'>Адреса</a>",$item->my_item['id'] ) ;
}
?></div>
Статус рассылки:
<?php
	print $stat_arr[$item->my_item['stat']];
?>                                           
<div  id="additemdiv">
        <h1  class="greentext"><?php 
		print ($item->my_item['action_item']=="edit")?("Редактирование."):("Добавление.")
		?></h1>
 
<?php 
//print_r($item);
if($item->error!=""){
?>
<div id="error"><?php echo $item->error ?></div><br />
<?php 
}
include_once(CLASS_PATH."function/html.function.php"); 
$select_sourse=array();

if(($item->my_item['action_item']=="edit")&&($item->my_item['stat']!='arhive')){
	$item->my_item['adr_full']=$item->my_item['adr_full'] . " - <a href='/admin/maillist.php?list=".$item->my_item['id']."'>список...</a>";
	$item->fields['adr_full']['text']="<a href='/admin/maillist.php?list=".$item->my_item['id']."' title='редактировать'>Всего адресатов</a>";
}
if($item->my_item['stat']=='arhive'){
	//	  print_r($item->fields['ru4naya']);
	unset($item->fields['ru4naya']);
	unset($item->fields['timeadd']);
	$item->fields['subject']['viev']="label";
	$item->fields['message']['viev']="label";
	$item->fields['stat']['viev']="hidden";
}elseif($item->my_item['stat']!='active'){
	$item->my_item['ru4naya']="рассылка неактивна или нет адресов для рассылки - <a href='/admin/maillist.php?list=".$item->my_item['id']."'>добавить...</a>";
}else{
	$item->my_item['ru4naya']="<a href='/admin/maillist.php?id=".$item->my_item['id']."&send=50'>разослать по ".MAX_MESSAGE_COUNT." шт.</a> 
							   | <a href='/admin/maillist.php?id=".$item->my_item['id']."&send=auto'>разослать полуавтоматом</a>";
}
if((isset($item->my_item['finaltime']))&&($item->my_item['finaltime']==NULL)){
	unset($item->fields['finaltime']);
}//'sourse'=>array('new'=>'- новая', 'active'=>'- активная', 'finish'=>'- завершена', 'arhive'=>'- архивная'  ),
if($item->my_item['stat']=='finish'){
	//unset($item->fields['finaltime']);
	$item->fields['stat']['sourse']['arhive']='- архивная';
}
//print_r($item->my_item);
//$template="<tr><td width=\"180\" valign=\"top\">%s
	//</td><td width=\"3\">&nbsp;</td><td width=\"467\" valign=\"top\">%s</td></tr>";
print html_form($item->fields,$item->my_item,$select_sourse );

?> 
</div>