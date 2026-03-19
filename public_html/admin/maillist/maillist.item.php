<?php 
//unset($_SESSION["message_result"]);
$settings_array=array(
	'fields'=>array(
		array(
			'name'=>"id",
			'text'=>"Ключ",
			'format'=>"key",
			'viev'=>"key",
			'default'=>0
		),//`id``subject``message``timeadd``adr_full``adr_fin`	
		'subject'=>array(
			'name'=>"subject",
			'format'=>"text",
			'text'=>"Тема рассылки",
			'viev'=>"text",
			'max_length'=>64,
			'required'=>"Тема рассылки"
		),
		'message'=>array( //message
			'name'=>"message",
			'text'=>"Текст рассылки",
			'format'=>"text",
			'viev'=>"textarea",
			'max_length'=>5000,
			'textarea'=>8,//высота в строках
			'html'=>1
		),
		'otkogo'=>array(
			'name'=>"otkogo",
			'format'=>"text",
			'text'=>"От кого",
			'viev'=>"text",
			'max_length'=>64,
			'default'=>"Ольга (Академия Боншери)",
			//'required'=>"От кого"
		),
		'otemail'=>array(
			'name'=>"otemail",
			'format'=>"text",
			'text'=>"e-mail",
			'viev'=>"text",
			'max_length'=>64,
			'default'=>"olga@petsgroomer.ru",
			//'required'=>"e-mail"
		),
		'stat'=>array(
			'name'=>"stat",
			'text'=>"<strong>Статус рассылки:</strong>",
			'format'=>"text",
			'viev'=>"radio",
			'sourse'=>array('new'=>'- новая', 'active'=>'- активная', 'finish'=>'- завершена'  ),
			'default'=>'new'),	//, 'arhive'=>'- архивная'
		'timeadd'=>array(
			'name'=>"timeadd",
			'text'=>"Начать рассылку:",
			'format'=>"datetimeeditable",
			'viev'=>"datetimeeditable"
		),//////
/*		array(
			//показывать рейтинг но не сохранять!!!!
			'name'=>"rating_full",
			'format'=>"label",
			'text'=>"Рейтинг:",
			'viev'=>"label"
		),
		*/
		'ru4naya'=>array(
			'name'=>"ru4naya",
			'text'=>"Разослать:",
			'format'=>"label",
			'viev'=>"label"
		),
		'finaltime'=>array(
			'name'=>"finaltime",
			'text'=>"<strong>Рассылка завершена:</strong>",
			'format'=>"label",
			'viev'=>"label"
		),
		array(
			'format'=>"header",
			'viev'=>"header",
			'text'=>"<h2>Списки рассылки:</h2>",
		),
		'adr_full'=>array( //message
			'name'=>"adr_full",
			'text'=>"Всего адресатов:",
			'format'=>"int",
			'viev'=>"label",
			'default'=>0
		),
		array( //message
			'name'=>"adr_fin",
			'text'=>"Разослано:",
			'format'=>"int",
			'viev'=>"label",
			'default'=>0
		),
		
		array(
			'name'=>"action_item",
			'format'=>"hidden",
			'viev'=>"hidden",
			'default'=>"add"
		)	
	),
	'entity'=>"cat",
	'table'=>"maillist",
	'picture_path'=>""
	//'picture_path'=>"logotype/"
);

//парсим массив $settings_array в зависимости от страницы ADMIN USER
//include(INCLUDE_PATH."parser_user_mode_viev.php");

//print_r($settings_array);
$item = new item();
$item->set_settigs($settings_array);
//
if (isset($_POST['action_item'])){
	$is_arhive='none';
	if($_POST['action_item']=="delete_item"){
		$gs= "/admin/maillist.php";
		//Удаление рассылки удаляет все адреса
		$del_sql=sprintf("DELETE FROM %s_mailadres WHERE  id_maillist=%d",DB_PREFIX,$_POST['id']);
		mysql_query($del_sql)or die(mysql_error());
		//print_r($_POST);
	}elseif($_POST['action_item']=="edit"){
		
		//Проверяем архивность
		$is_arhive=$item->get_value('stat',$_POST['id']);
		//die($is_arhive);
		$gs= "/admin/maillist.php?id=".$_POST['id'];
		if($_POST['stat']=="arhive"){//архив удаляет все адреса
			$del_sql=sprintf("DELETE FROM %s_mailadres WHERE  id_maillist=%d",DB_PREFIX,$_POST['id']);
			mysql_query($del_sql)or die(mysql_error());
		}
	}
	
	if(!(($is_arhive=="arhive")&&($_POST['action_item']!="delete_item"))){//запрещаем редактирование архивов
		$item->action($_POST['action_item']);	
		if($_POST['action_item']=="add"){
			$gs= "/admin/maillist.php?id=".$item->my_item['key'];
		}
	}
 	
	if($item->error==""){
		header("Location:".SERVER_HOST.$gs);
		exit;
	}// $item->error;
	
}elseif (isset($_GET['send'])){
	$maillist_id=$_GET['id'];
	$sort=' a.email';
	include("maillist/maillist_query.php");
	if($_GET['send']=='auto'){
		header("Location:".SERVER_HOST. "/admin/maillist.php?id=".$maillist_id."&auto=1");
	}else{
		header("Location:".SERVER_HOST. "/admin/maillist.php?id=".$maillist_id );
	}
 
	exit;
}

?>