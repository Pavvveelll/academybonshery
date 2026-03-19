<div id="path"><a href="maillist.php">Рассылки</a> <?php 
/*if(isset($item->my_item['id'])&&$item->my_item['id']>0){
printf("- <a href='/admin/maillist.php?id=%d'>%s</a>",
										$item->my_item['id'],$item->my_item['subject']); 
}*/
?><?php 
//if(isset($item->my_item['stat'])&&$item->my_item['stat']!="arhive"&&$item->my_item['stat']!="new"){
//	printf(" - <a href='/admin/maillist.php?list=%d'>Адреса</a>",$item->my_item['id'] ) ;
//}
?></div>                                         
<div  id="additemdiv">
        <h1  class="greentext"><?php 
		print ($item->my_item['action_item']=="edit")?("Редактирование"):("Добавление")
		?> группы подписчиков</h1>
 
<?php 
//print_r($item);
if($item->error!=""){
?>
<div id="error"><?php echo $item->error ?></div><br />
<?php 
}
include_once(CLASS_PATH."function/html.function.php"); 
$select_sourse=array();
$button_array=array( 'deleteconfirm'=>'Удаление группы удалит всех подписчиков в ней.\nДействительно удалить?' );
$select_sourse[]=$button_array;

print html_form($item->fields,$item->my_item,$select_sourse );

?> 
</div>