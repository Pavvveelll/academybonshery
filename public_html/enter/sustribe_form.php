<?php 
if($item->error!=""){
?>
<div id="error"><strong>ВНИМАНИЕ! Произошла ошибка:</strong><br /><?php echo $item->error ?></div><br />
<?php 
}
include_once(CLASS_PATH."function/html.function.php");
//if((isset($auth)&&($auth->is_loged==true)&&($auth_user[3]!='temp'))){
//	$item->my_item['sender_name']=": ".$auth_user[1];
//	$item->my_item['login']=" ".$auth_user[2];
//}
///print_r($item->my_item);
$select_sourse[]=array('submit'=>'Оформить','saveimg'=>'sustribe.gif','deleteconfirm'=>'Прекратить подписку?');

$template="<tr><td valign=\"top\" width=\"30%%\" >%s</td><td valign=\"top\">%s</td></tr>";
print html_form($item->fields,$item->my_item,$select_sourse,$template);

?> 


