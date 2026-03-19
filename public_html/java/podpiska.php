<?php

//print_r($_POST);
//exit;
require_once("../class/common.php");

function send_mail_welcome($user){
		$email = EmailSMTP::instance();
		$email->to($user['login'],$user['name']);
		$email->from(ADMIN_MAIL, "Академия груминга Боншери");
		$email->subject("Подписка");
			$mess=file_get_contents(ROOT_PATH."sustribe/msg_activate.tpl");
			$search  = array('%%SHASH%%', '%%NAME%%','%%HOST%%');
			$replace = array($user['shash'], $user['name'],SERVER_HOST);
			$mess=str_replace($search, $replace, $mess);		
		$email->body($mess);
				 
		return $email->send();	
}

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
		array(//подписался
			'name'=>"start",
			'text'=>"подписался:",
			'format'=>"datetime",
			'viev'=>"datetime",
			'default'=>"now"
		),
		array(
			'name'=>"sstatus",
			'format'=>"text",
			'text'=>"Статус:",
			'viev'=>"select",
			'sourse'=>array('yes'=>"активный",'new'=>"новый",'uns'=>"отписался"),
			'default'=>'new',
		),
		array(
			'name'=>"sgroup",
			'format'=>"int",
			'viev'=>"hidden",
			'default'=>1
		),	
		array(
			'name'=>"kurs",
			'format'=>"text",
			'text'=>"Курсы",
			'viev'=>"hidden",
			'default'=>"no"
		),	
		array(
			'name'=>"master",
			'format'=>"text",
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
$pitem->set_settigs($settings_array);
$_POST['id']=0;//чтобы не ругался
$pitem->check_and_fill_data();

$rez_arr=array('obj='.$_POST['obj']);//для ответов ажаксу



if($pitem->error!=""){//гдето ошибки - показываем сообщение
	$rez_arr[]='res=nok';
	$rez_arr[]='error='.$pitem->error;
}else{
	//на что подписывается
	$na_chto=substr($_POST['obj'],0,1);
	$rez_arr[]='na_chto='.$na_chto;
	//error_log($na_chto);
	if($pitem->check_unique("login",trim($_POST['login']))==false){//уже есть в списке рассылки обновляем данные
		//загружаем сохраненные данные
		$pitem->load(trim($_POST['login']), DB_PREFIX."_sustribe","",'login','text');
		
		session_start();
		$_SESSION['editpodpiska']=$pitem->my_item['shash'];//если пользователь решит изменить данные, это определит его как надо.
		
		//не был подписан на курсы
		if($pitem->my_item['kurs']!='yes' && $na_chto=='p')
				$pitem->my_item['kurs']='yes';
		if($pitem->my_item['master']!='yes' && $na_chto=='m')
				$pitem->my_item['master']='yes';				
		//было другое имя
		if($pitem->my_item['name']!=trim($_POST['name']))
				$pitem->my_item['name']=trim($_POST['name']);
		
		switch ($pitem->my_item['sstatus']) {
			case 'uns'://отписался ранее, активируем , ставим kurs=yes
				$pitem->my_item['sstatus']='yes';
				break;
			case 'new':	 //case 'new':повторное письмо
				$ml=send_mail_welcome($pitem->my_item);
				if ($ml==0){//письмо не отправилось, все равно будем поздравлять.
					error_log("Oshibka povtornoi podpiski.");
				}else{
					$rez_arr[]='mail=welcome';
				}
		}
		$pitem->action('edit',$pitem->my_item);
		
		//готовим куку
		$cc=array('u_id'=>$pitem->my_item['id'], 'master'=>$pitem->my_item['master'], 'kurs'=>$pitem->my_item['kurs']);
		$rez_arr[]='cc='.json_encode($cc);
		$rez_arr[]='res=ok';
	}else{//нет в списке Добавляем как нового
		//Велком письмо
		//при клике на Велком или любое другое становится активным.
		$_POST['shash']=md5($pitem->my_item['login'] . microtime());
		session_start();
		$_SESSION['editpodpiska']=$_POST['shash'];//если пользователь решит изменить данные, это определит его как надо.
		if($na_chto=='m'){//может подписаться на чтото одно за один раз
			$_POST['master']='yes';
			$_POST['kurs']='no';
		}else{
			$_POST['master']='no';
			$_POST['kurs']='yes';
		}
		$pitem->action('add');
		//print_r($pitem->my_item);
		
		//готовим куку
		$cc=array('u_id'=>$pitem->my_item['key'],'master'=>$_POST['master'], 'kurs'=>$_POST['kurs']);
		$rez_arr[]='cc='.json_encode($cc);
		$rez_arr[]='res=ok';
		
		$ml=send_mail_welcome($pitem->my_item);
		if ($ml==0){//письмо не отправилось, все равно будем поздравлять.
			error_log("Oshibka nachalnoy podpiski.");
		}else{
			$rez_arr[]='mail=welcome';
		}		
		
	}
}

//print http_build_query($rez_arr);//отправляем для ajax
print implode('&',$rez_arr);

?>
