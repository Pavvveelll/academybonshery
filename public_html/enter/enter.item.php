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
		"tele"=>array(
			'name'=>"tele",
			'format'=>"text",
			'text'=>'<strong>Телефон</strong><br /><span style="font-size:11px">на который Вам должен позвонить администратор школы</span>',
			'viev'=>"text",
			'max_length'=>64,
			'required'=>"Телефон"
		), 
		"sender_name"=>array(
			'name'=>"sender_name",
			'format'=>"text",
			'text'=>'<strong>Ваше имя</strong><br /><span style="font-size:11px">как к Вам обращаться</span>',
			'viev'=>"text",
			'max_length'=>64,
			//'required'=>"Фамилия Имя"
		), 
//		"login"=>array(
//			'name'=>"login",
//			'text'=>"<strong>Контактный e-mail</strong>:",
//			'format'=>"email",
//			'viev'=>"text",
//			'max_length'=>64,
//			//'unique'=>' - уже зарегистрирован. Воспользуйтесь системой напоминания пароля.',
//			'required'=>"Контактный E-mail - адрес электронной почты"
//		),
//		array(
//			'name'=>"message",
//			'format'=>"text",
//			'text'=>"<strong>Текст сообщения</strong>",
//			'viev'=>"textarea",
//			'textarea'=>6,
//			'required'=>"Текст сообщения",
//			'max_length'=>1000
//		),
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
//if((isset($auth)&&($auth->is_loged==true)&&($auth_user[3]!='temp'))){
//	$settings_array['fields']['sender_name']['viev']="label";
//	$settings_array['fields']['login']['viev']="label";
//	$_POST['sender_name']=$auth_user[1];
//	$_POST['login']=$auth_user[2];
// 
//}else{
//	$gost=" (незарегистрированный пользователь)";
//}
$item->set_settigs($settings_array);
//print_r($settings_array);
//$item->target=$sufix;
//die("die");
if (isset($_POST['action_item'])){
	//print_r($_POST);
		$item->check_and_fill_data();
		$email = new maillib();	
		$mail_error="";
		//не гость записываем
		$kto_otpravil=$item->my_item['tele'];
		 $email->from(ADMIN_MAIL_CONTACT);
		//$email_from=($item->my_item['login']);
		//$email->from($item->my_item['login']);
		 $qs="/enter/";
		//Отправляем письмо
		if($item->error==""){
			
			if($mail_error==""){
			$qs="/enter/ok.php";	
			$email->to(ADMIN_MAIL_CONTACT);	
			//$nmess=strip_tags(substr($item->my_item['message'],0,1000));
$fullmessage="Позвоните мне пожалуйста:
".$item->my_item['tele']."
".$item->my_item['sender_name']."
интересуюсь экспресс курсом";
 
				//$email->encoding="7bit";
				//$email->from($item->my_item['login'],$item->my_item['guest_name']);
				$email->subject("Обратный звонок ".$item->my_item['sender_name']);
				$email->message($fullmessage);
				//print_r($email);.$email->errorlog
				$email->send();
				$err=$email->is_sent();
				if ($err==false){
					$item->error="Сообщение отправить не удалось. 
					Свяжитесь с администратором по телефону +7 (499) 994-01-40";
					error_log("Oshibka otpravki opovesheniya.");
				}else{
					$_SESSION['message_sent']='send';
					//$_SESSION['udata']=array($item->my_item['sender_name'],$item->my_item['login']);
				}
			}else{
				error_log('mail_error');
			}
		}
	
	if($item->error==""){
		//print(SERVER_HOST.$qs);
		//reload_after_event($qs);
		header("Location:".SERVER_HOST.$qs);
		exit;
	}
}


?>