

<?php 
///СООБЩЕНИЕ ОТПРАВЛЕНО
if((isset($_SESSION['message_sent']))&&($_SESSION['message_sent']=='send')){
	?>
	<div id='send'>Сообщение отправлено</div>
	<div class='block' style="text-align:center;"  >
	  <p><strong>Для получения самой свежей информации</strong> о планирующихся мастерклассах, курсах,  о новостях Школы <strong>рекомендуем</strong>:<br />
		<a href="/_sustribe/" style="font-size:15px;color:#FEF2CD;">Подписаться на рассылку новостей Школы груминга Боншери</a></p>
	</div>
	<?php 
	unset($_SESSION['message_sent']);
}else{
	if($item->error!=""){
	?>
<div id="error"><strong>ВНИМАНИЕ! Сообщение отправить не удалось!</strong><br /><?php echo $item->error ?></div><br />
	<?php 
	}
	include_once(CLASS_PATH."function/html.function.php");
	if((isset($auth)&&($auth->is_loged==true)&&($auth_user[3]!='temp'))){
		$item->my_item['sender_name']=": ".$auth_user[1];
		$item->my_item['login']=" ".$auth_user[2];
	}
	///print_r($item->my_item);
	$select_sourse[]=array('submit'=>'Отправить','saveimg'=>'sende.gif');
	
	$template="<tr><td valign=\"top\" width=\"20%%\" >%s</td><td valign=\"top\">%s</td></tr>";
	print html_form($item->fields,$item->my_item,$select_sourse,$template);
}
?> 


