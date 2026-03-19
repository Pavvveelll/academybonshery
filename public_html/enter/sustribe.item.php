<?php 
// print_r($_POST);
// die("ggggggg");
$item = new item();
$settings_array=array(
	'fields'=>array(
		array(
			'name'=>"id",
			'format'=>"key",
			'viev'=>"key",
			'default'=>0
		),
		"name"=>array(
			'name'=>"name",
			'format'=>"text",
			'text'=>"<strong>Ваше имя</strong>",
			'viev'=>"text",
			'max_length'=>64,
			'required'=>"Ваше имя"
		), 
		"login"=>array(
			'name'=>"login",
			'text'=>"<strong>E-mail</strong>:",
			'format'=>"email",
			'viev'=>"text",
			'max_length'=>64,
			'unique'=>' - уже в списке рассылки.',
			'required'=>"E-mail - адрес электронной почты"
		),
//		array(// (отметьте галочками те пункты, по которым хотите получать информацию)
//			'format'=>"header",
//			'text'=>"<strong>Содержание подписки</strong>",
//			'viev'=>"header"
//		),
		array(
			'name'=>"sgroup",
			'format'=>"int",
			//'text'=>"Мастерклассы",
			'viev'=>"hidden",
			'default'=>7
		),	
		array(
			'name'=>"kurs",
			'format'=>"checkbox",
			'text'=>"Курсы",
			'viev'=>"hidden",
			'default'=>"yes"
		),	
		array(
			'name'=>"news",
			'format'=>"checkbox",
			'text'=>"Отчеты, новости, вакансии",
			'viev'=>"hidden",
			'default'=>"yes"
		),		
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
			'name'=>"shash",
			'format'=>"text",
			'viev'=>"hidden"
		),
		array(
			'name'=>"action_item",
			'format'=>"hidden",
			'viev'=>"hidden",
			'default'=>"add"
		)
	),
	'entity'=>"sustribe",
	'table'=>"sustribe",
	'picture_path'=>""
);
$item->set_settigs($settings_array);
//print_r($settings_array);

//$item->target=$sufix;
//die("die");
$delete=false;
if (isset($_POST['action_item'])){
	//print_r($_POST);
	$qs="/sustribe/";
	///var_dump($item->check_unique("login",trim($_POST['login'])));
	//ЕСЛИ логин уже в базе и не активирован, удаляем старую запись и создаем новую
	//ТОЛЬКО ДЛЯ Добавления
	if(($_POST['action_item']=="add")&&($item->check_unique("login",trim($_POST['login']))==false)){
		//print_r($item->login_array);
		if($item->login_array['sstatus']!='yes'){
			$item->delete($item->login_array['id']);
		}
	}
	
	$item->check_and_fill_data();
// 	print_r($item->error);
//	die("lllllllllll");
	if($item->error==""){
		//если не подписан ни на что, подписываем на все
		//if(($_POST['master']!='yes')&&($_POST['kurs']!='yes')&&($_POST['news']!='yes')){
			//$_POST['master']='yes';
			$_POST['kurs']='yes';
			$_POST['news']='yes';
		//}
		if($_POST['action_item']=="add"){
			$_POST['shash']=md5($item->my_item['login'] . microtime());
		}elseif($_POST['action_item']=="delete_item"){
			$delete=true;
			$_POST['action_item']='edit';
		}
		$item->action($_POST['action_item']);
	}
	if($item->error==""){	
		if($_POST['action_item']=="add"){
			//отправляем письмо с активацией
			$email = new maillib();	
			$mail_error="";
			$email->to($item->my_item['login']);	
			$email->from(ADMIN_MAIL_CONTACT, "Школа груминга Боншери");
			$email->subject("Подписка");
			$mess=file_get_contents(ROOT_PATH."sustribe/msg_activate.tpl");
			$search  = array('%%SHASH%%', '%%NAME%%','%%HOST%%');
			$replace = array($item->my_item['shash'], $item->my_item['name'],SERVER_HOST);
			$mess=str_replace($search, $replace, $mess);
			$email->message($mess);
			$email->send();
			$err=$email->is_sent();
			if ($err==false){
				$item->error="Не удалось оформить подписку. Подайте заявку на ".ADMIN_MAIL_CONTACT;
				error_log("Oshibka podpiski.");
			}else{
				$_SESSION['message_sent']='send';
				$qs="/sustribe/ok";
			}
		}elseif($_POST['action_item']=="edit"){
			//die($_POST['action_item']);
			if($delete==true){
				$item->set_value("sstatus",$item->my_item['id'],"uns");
				$_SESSION['message_sent']='delete';
			}else{
				//если пользователь сохраняет значит он пришел по активационной ссылке, ставим в подтвержденные
				$item->set_value("sstatus",$item->my_item['id'],"yes");
				$qs="/sustribe/?act=edit&id=".$_POST['shash'];
				$_SESSION['message_sent']='save';
			}
		}
	}
	
	if($item->error==""){
	 //die(SERVER_HOST.$qs);
		//reload_after_event($qs);
		header("Location:".SERVER_HOST.$qs);
		exit;
	}
}


?>