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
		),
			'login'=>array(
			'name'=>"login",
			'format'=>"email",
			'text'=>"Е-mail",
			'viev'=>"text",
			'max_length'=>64,
			'required'=>"Емайл",
			'unique'=>' - уже в списке рассылки.',
		),
		'name'=>array(
			'name'=>"name",
			'format'=>"text",
			'text'=>"Имя",
			'viev'=>"text",
			'max_length'=>64,
			'required'=>"Имя"
		),
		

		array( //message
			'name'=>"master",
			'text'=>"<span title='Мастерклассы'>МК</span>",
			'format'=>"checkbox",
			'viev'=>"checkbox",
			'default'=>"yes"
		),
		array( //message
			'name'=>"kurs",
			'text'=>"<span title='Курсы'>Кс</span>",
			'format'=>"checkbox",
			'viev'=>"checkbox",
			'default'=>"yes"
		),
//		array( //message
//			'name'=>"news",
//			'text'=>"<span title='Отчеты, новости'>ОтН</span>",
//			'format'=>"checkbox",
//			'viev'=>"checkbox",
//			'default'=>"yes"
//		),
		'shash'=>array( //message
			'name'=>"shash",
		//	'text'=>"Описание",
			'format'=>"text",
			'viev'=>"hidden",
			'max_length'=>32
		),	
//		'sgroup'=>array( //message
//			'name'=>"sgroup",
//			//'text'=>"Описание",
//			'format'=>"int",
//			'viev'=>"hidden",
//			'default'=>"1"
//			//'max_length'=>32
//		),	
		array(
			'name'=>"sstatus",
			'format'=>"text",
			'text'=>"Статус:",
			'viev'=>"select",
			'sourse'=>array('yes'=>"активный",'new'=>"новый",'uns'=>"отписался"),
			'default'=>'yes',
			//'target'=>"shop"
		),
		array(
			'name'=>"sgroup",
			'format'=>"int",
			'text'=>"Группа:",
			'viev'=>"select",
			//'sourse'=>array('yes'=>"активный",'new'=>"новый",'uns'=>"отписался"),
			//'default'=>'yes',
			// 'target'=>"sustribe_group"
		),
		array(
			'name'=>"action_sus",
			'format'=>"hidden",
			'viev'=>"hidden",
			'default'=>"add"
		)	
	),
	'entity'=>"sustribe",
	'table'=>"sustribe",
	'picture_path'=>""
	//'picture_path'=>"logotype/"
);

//print_r($settings_array);
$sustriber = new item();
$sustriber->set_settigs($settings_array);

if (isset($_POST['action_sus'])){
//	  print_r($_POST);
//	  die("jjjjjjj");
	if($_POST['action_sus']=="add")
		$_POST['shash']=md5($item->my_item['login'] . microtime());
	$sustriber->action($_POST['action_sus']);

	//print_r($sustriber);
	if($sustriber->error==""){
		if($_POST['action_sus']=="delete_item"){
			$gs= "/admin/maillist.php?sustribers=".$_POST['sgroup'];
			$_SESSION['message_result']['save']="Удалено";
		}else{
			$gs= "/admin/maillist.php?sustribers=".$_POST['sgroup'];//."&sstrb=".$sustriber->my_item['key']
			$_SESSION['message_result']['save']="Сохранено";
		}
		header("Location:".SERVER_HOST.$gs);
		exit;
	}// $item->error;
}



?>