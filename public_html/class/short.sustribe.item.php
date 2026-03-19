<?php 
// ПОДПИСКА НА КУРСЫ
/*
чел заполняет форму
отправляет
мы ему ажаксом выводим поздравление
ставим куку
отправляем активационное письмо
после подтверждения рассылки
отправляем ВЕЛКОМ

Если кука на эту рассылку стоит, форму больше не показываем

на форме есть ссылка на подробнее.

затем при визитах по куке, записываем статистику.

При переходе из рассылки - обновляем куку
*/

//print_r($_POST);
//  die("ggggggg");
$pitem = new item();
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
			//'unique'=>' - уже в списке рассылки.',
			'required'=>"E-mail - адрес электронной почты"
		),
		array(
			'name'=>"sgroup",
			'format'=>"int",
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
			'name'=>"master",
			'format'=>"checkbox",
			'default'=>"no"
		),
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
		),
	),	
	'entity'=>"sustribe",
	'table'=>"sustribe"
);
function send_mail_activate($user){
		$email = new maillib();	
		$mail_error="";
		$email->to($user['login']);	
		$email->from(ADMIN_MAIL, "Школа груминга Боншери");
		$email->subject("Подписка");
		$mess=file_get_contents(ROOT_PATH."sustribe/msg_activate.tpl");
		$search  = array('%%SHASH%%', '%%NAME%%','%%HOST%%');
		$replace = array($user['shash'], $user['name'],SERVER_HOST);
		$mess=str_replace($search, $replace, $mess);
		$email->message($mess);
		$email->send();
		$err=$email->is_sent();
		return $err;
}

$pitem->set_settigs($settings_array);
 
if (isset($_POST['action_item'])){
	//print_r($_POST);
	$pitem->check_and_fill_data();
	if($pitem->error==""){
		
		if(($_POST['action_item']=="add")&&($pitem->check_unique("login",trim($_POST['login']))==false)){
			//загружаем сохраненные данные
			$cur_item=new item();
			$cur_item->load(trim($_POST['login']), DB_PREFIX."_sustribe","",'login','text');
			 
			if($cur_item->my_item['kurs']!='yes'){
				$cur_item->set_value('kurs', $cur_item->my_item['id'], 'yes', DB_PREFIX."_sustribe" );
				$cur_item->my_item['kurs']='yes';
			}
			if($cur_item->my_item['name']!=trim($_POST['name'])){
				$cur_item->set_value('name', $cur_item->my_item['id'], trim($_POST['name']), DB_PREFIX."_sustribe" );
				$cur_item->my_item['name']=trim($_POST['name']);
			}
			
			 
			switch ($cur_item->my_item['sstatus']) {
				case 'yes'://уже в списке подписки, ставим kurs=yes					 
					break;
				case 'uns'://отписался ранее, активируем , ставим kurs=yes
					$cur_item->set_value('sstatus', $cur_item->my_item['id'], 'yes', DB_PREFIX."_sustribe" );
					break;
				default:	 //case 'new':повторное письмо
					$ml=send_mail_activate($cur_item->my_item);
					if ($ml==false){
						$pitem->error="Не удалось оформить подписку. Подайте заявку на ".ADMIN_MAIL;
						error_log("Oshibka podpiski.");
					}else{
						$_SESSION['message_sent']='send';//активационное письмо отправлено - на поздравления
						$qs="/sustribe/ok";
						header("Location:".SERVER_HOST.$qs);
						exit;						
					}					 
				 	 
			}
			
			if($pitem->error==""){
				$_SESSION['message_sent']='Подписка оформлена';//готовим сообщение
			 
				
				$cc=array('u_id'=>$cur_item->my_item['id'], 'master'=>$cur_item->my_item['master'], 'kurs'=>$cur_item->my_item['kurs']);
				setcookie("pdp", json_encode($cc), time() + 604800*20,"/", COOKIES);//20 недель//ставим куку ID подписчика
				
				//перегружаем на самого себя
				header("Location:".SERVER_HOST.$_SERVER['REQUEST_URI']);
				exit;				
			}
		 	
			
			
 
		}
	}
 
	if($pitem->error==""){
 		$_POST['shash']=md5($item->my_item['login'] . microtime());
		$pitem->action($_POST['action_item']);
		$err=send_mail_activate($pitem->my_item);
		if ($err==false){
				$pitem->error="Не удалось оформить подписку. Подайте заявку на ".ADMIN_MAIL;
				error_log("Oshibka podpiski.");
			}else{
				$_SESSION['message_sent']='send';
				$qs="/sustribe/ok";
		}
	}
	
	if($pitem->error==""){
		header("Location:".SERVER_HOST.$qs);
		exit;
	}
}


?>