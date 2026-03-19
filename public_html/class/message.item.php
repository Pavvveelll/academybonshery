<?php 
// print_r($_POST);
$item = new item();
$settings_array=array(
	'fields'=>array(
		array(
			'name'=>"id",
			'format'=>"key",
			'viev'=>"key",
			'default'=>0
		),
		"sender_name"=>array(
			'name'=>"sender_name",
			'format'=>"text",
			'text'=>"<strong>Фамилия Имя</strong>",
			'viev'=>"text",
			'max_length'=>64,
			'required'=>"Фамилия Имя"
		), 
		"login"=>array(
			'name'=>"login",
			'text'=>"<strong>Контактный e-mail</strong>:",
			'format'=>"email",
			'viev'=>"text",
			'max_length'=>64,
			//'unique'=>' - уже зарегистрирован. Воспользуйтесь системой напоминания пароля.',
			'required'=>"Контактный E-mail - адрес электронной почты"
		),
		array(
			'name'=>"message",
			'format'=>"text",
			'text'=>"<strong>Текст сообщения</strong>",
			'viev'=>"textarea",
			'textarea'=>6,
			'required'=>"Текст сообщения",
			'max_length'=>1000
		),
		array(
			'name'=>"action_item",
			'format'=>"hidden",
			'viev'=>"hidden",
			'default'=>"add"
		)
	),
	'entity'=>"message",
	'table'=>"message",
	'picture_path'=>""
);
$gost="";
if((isset($auth)&&($auth->is_loged==true)&&($auth_user[3]!='temp'))){
	$settings_array['fields']['sender_name']['viev']="label";
	$settings_array['fields']['login']['viev']="label";
	$_POST['sender_name']=$auth_user[1];
	$_POST['login']=$auth_user[2];
}else{
	$gost=" (незарегистрированный пользователь)";
}
$item->set_settigs($settings_array);

if (isset($_POST['action_item'])){
	$item->check_and_fill_data();
	
	if($item->error==""){
		$email = EmailSMTP::instance();
		$email->to(ADMIN_MAIL);
		$email->from(ADMIN_MAIL,$item->my_item['sender_name']);
		$email->reply($item->my_item['login'],$item->my_item['sender_name']);
		$email->subject("Сообщение с сайта Школы груминга Боншери");		
		$email->body('<p>'.nl2br( strip_tags(substr($item->my_item['message'],0,1000)) ).'</p><p>__________________<br />'.$item->my_item['sender_name'].'</p>');

		$err = $email->send();
		if ($err == 0){
			$item->error = "По технической причине сообщение отправить не удалось. Свяжитесь с администратором по телефону +7 (499) 994-01-40";
			error_log("The letter was not sent due to technical errors. Login ".$_POST["login"]);
		} else {
			$_SESSION['message_sent']='send';
			$_SESSION['udata']=array($item->my_item['sender_name'],$item->my_item['login']);
			header("Location:".SERVER_HOST."/contact/");
			exit;			
		}
	}
}


?>