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
/*		array(
			'name'=>"subject",
			'format'=>"text",
			'text'=>"Тема рассылки",
			'viev'=>"text",
			'max_length'=>64,
			'required'=>"Тема рассылки"
		),*/
//		array( //message
//			'name'=>"alluser",
//			'text'=>"<strong>Все пользователи</strong>",
//			'format'=>"checkbox",
//			'viev'=>"checkbox",
//			'default'=>"no"
//		),
		array(
			'name'=>"sgroup",
			'format'=>"int",
			'text'=>"Группа:",
			'viev'=>"select",
			//'sourse'=>array('yes'=>"активный",'new'=>"новый",'uns'=>"отписался"),
			//'default'=>'yes',
			// 'target'=>"sustribe_group"
		),
		array( //message
			'name'=>"master",
			'text'=>"Мастерклассы",
			'format'=>"checkbox",
			'viev'=>"checkbox",
			'default'=>"no"
		),
		array( //message
			'name'=>"kurs",
			'text'=>"Курсы",
			'format'=>"checkbox",
			'viev'=>"checkbox",
			'default'=>"no"
		),
//		array( //message
//			'name'=>"news",
//			'text'=>"Отчеты, новости",
//			'format'=>"checkbox",
//			'viev'=>"checkbox",
//			'default'=>"no"
//		),
//		array( //message
//			'name'=>"testir",
//			'text'=>"Тестовая группа",
//			'format'=>"checkbox",
//			'viev'=>"checkbox",
//			'default'=>"yes"
//		),
		array(
			'format'=>"header",
			'viev'=>"header",
			'text'=>"<h2>Списки рассылки:</h2>",
		),
		'adr_full'=>array( //message
			'name'=>"adr_full",
			'text'=>"Всего адресатов:",
			'format'=>"int",
			'viev'=>"label"
		),
		array( //message
			'name'=>"adr_fin",
			'text'=>"Разослано:",
			'format'=>"int",
			'viev'=>"label"
		),
		array(
			'name'=>"ru4naya",
			'text'=>"Разослать вручную:",
			'format'=>"label",
			'viev'=>"label"
		),
/*		array(
			'name'=>"testovaya",
			'text'=>"Тестовая группа:",
			'format'=>"label",
			'viev'=>"label",
			'default'=>"разослать"
		),*/
		array(
			'name'=>"stat",
			'text'=>"",
			'format'=>"text",
			'viev'=>"hidden",
			'default'=>'active'),			
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
//die("die");
//print_r($_POST);	
if (isset($_POST['action_item'])){
	if($_POST['action_item']=="delete_item"){//очищаем список кроме отправленных, тестовую группу удаляем всегда
		//$test_group=array("'deiww@mail.ru'","'deiwww@yandex.ru'");
		
		$query   = 	sprintf("DELETE FROM %s_mailadres WHERE id_maillist=%d AND 
					(stat<>'finish' OR email IN(SELECT u.login FROM %s_sustribe u, %s_sustribe_group g WHERE u.sgroup=g.id_group AND  g.gname='Тестовая'))",
							DB_PREFIX,$_POST['id'],DB_PREFIX,DB_PREFIX);//,implode(",", $test_group) 
							//
		mysql_query($query) or die(mysql_error());
		
		
		 //die( $query);
		//Проверяем все ли разосланы и меняем статус при необходимости
		$update_sql=sprintf("UPDATE %s_maillist SET stat ='finish',finaltime =NOW() WHERE id=%d ",DB_PREFIX ,$_POST['id']);	
		//die($update_sql );
		$update = mysql_query($update_sql)or die(mysql_error());
		
	}else{//проверить и отвильтровывать только п статусу без LIKE  AND u.login LIKE '%@%'
			$query   = 	"INSERT  IGNORE  INTO ".DB_PREFIX."_mailadres (id_maillist, email, name,shash) 
							SELECT ".$_POST['id'].", u.login, u.name, u.shash FROM ".DB_PREFIX."_sustribe u WHERE u.sstatus='yes'  ";
			$add_sql_array=array();
			if(isset($_POST['sgroup']))
				$query.=" AND sgroup=".intval($_POST['sgroup']);
			if(isset($_POST['master']))
				$add_sql_array[]=" u.master='yes' ";
			if(isset($_POST['kurs']))
				$add_sql_array[]=" u.kurs='yes' ";
			if(isset($_POST['news']))
				$add_sql_array[]=" u.news='yes' ";
			if(count($add_sql_array)>0){
				$query.=" AND (".implode(" OR ", $add_sql_array).")";
				
			//	die($query);			
				mysql_query($query) or die(mysql_error());			
			}
							

	
//		if (isset($_POST['alluser'])){//выбираем всех пользователей у которых не временный емайл.
//			$query   = 	"INSERT  IGNORE  INTO ".DB_PREFIX."_mailadres (id_maillist, email, name,shash) 
//							SELECT ".$_POST['id'].", u.login, u.name, u.shash FROM ".DB_PREFIX."_sustribe u WHERE u.sstatus='yes' ";
//			//	die($query);			
//			mysql_query($query) or die(mysql_error());
//		}else{//kurs",news",master
//			$add_sql="";
//			if(isset($_POST['master']))
//				$add_sql.=" AND u.master='yes'";
//			if(isset($_POST['kurs']))
//				$add_sql.=" AND u.kurs='yes'";
//			if(isset($_POST['news']))
//				$add_sql.=" AND u.news='yes'";
//			if($add_sql!=""){
//				$query   = 	"INSERT  IGNORE  INTO ".DB_PREFIX."_mailadres (id_maillist, email, name,shash) 
//							SELECT ".$_POST['id'].", u.login, u.name, u.shash FROM ".DB_PREFIX."_sustribe u WHERE u.sstatus='yes' $add_sql";
//				mysql_query($query) or die(mysql_error());
//			}
//			
//			if (isset($_POST['testir'])){//тестовые пользователи
//				$query   = 	"INSERT  IGNORE  INTO ".DB_PREFIX."_mailadres (id_maillist, email, name,shash) 
//				 				SELECT ".$_POST['id'].", u.login, u.name, u.shash FROM ".DB_PREFIX."_sustribe u WHERE u.login IN(".implode(",",$test_group).") ";
//			
////			
////				$query   = 	"INSERT  IGNORE  INTO ".DB_PREFIX."_mailadres (id_maillist, email, name) VALUES ";
////				foreach($test_group as $tg){
////					$query   .=" (". $_POST['id'].",$tg ),";
////				}
////				$query=substr($query, 0, -1);
//				// die($query);
//				mysql_query($query) or die(mysql_error());
//			}
//		}
		
	}
	//ПЕРЕСЧИТЫВАЕМ количество адресов
	//$maillist_id=$_POST['id'];
//	include_once("include/update_maillist.php");
	//ПЕРЕСЧИТЫВАЕМ количество адресов
	$query_all = sprintf("SELECT COUNT(*) FROM %s_mailadres WHERE id_maillist=%d  ", DB_PREFIX,$_POST['id']);
	$all = mysql_query($query_all)or die(mysql_error());
	$allrows = mysql_fetch_row($all);
	//print_r($query_finish);
	$allrows=$allrows[0];
	$update_sql=sprintf("UPDATE %s_maillist SET adr_full=%d WHERE  id=%d ",DB_PREFIX,$allrows,$_POST['id']);
	//print($update_sql);
	mysql_free_result($all);
	$update = mysql_query($update_sql)or die(mysql_error());
	
	
	//ПЕРЕСЧИТЫВАЕМ количество адресов//отправленные
	$maillist_id=$_POST['id'];
	$query_finish = sprintf("SELECT COUNT(*) FROM %s_mailadres WHERE id_maillist=%d AND stat='finish' ", DB_PREFIX,$maillist_id);
	$finish = mysql_query($query_finish)or die(mysql_error());
	$finishrows = mysql_fetch_row($finish);
	//print_r($query_finish);
	$finishrows=$finishrows[0];
	$update_sql=sprintf("UPDATE %s_maillist SET adr_fin=%d WHERE  id=%d ",DB_PREFIX,$finishrows,$maillist_id);
	//print($update_sql);
	mysql_free_result($finish);
	$update = mysql_query($update_sql)or die(mysql_error());
	
	//Проверяем все ли разосланы и меняем статус при необходимости
	$update_sql=sprintf("UPDATE %s_maillist SET stat ='active',finaltime =NULL WHERE  adr_full>adr_fin AND id=%d ",DB_PREFIX,$maillist_id );	
	$update = mysql_query($update_sql)or die(mysql_error());
	
  	//die($update_sql);
	//if($item->error==""){
		header("Location:".SERVER_HOST. "/admin/maillist.php?list=".$_POST['id']);
		exit;
//	}// $item->error;
	
}elseif (isset($_GET['send'])){
	$maillist_id=$_GET['list'];
	//print_r($_GET);
	if (isset($_GET['sort'])){
		switch  ($_GET['sort']){
			case "ea"://убывание email
				$sort= " a.email ";
			break;
			case "ed"://убывание email
				$sort= " a.email DESC ";
			break;
			case "sa"://по алфавиту
				$sort= " a.stat  ";
			break;
			case "sd"://по дате добавления
				$sort= "  a.stat  DESC ";
			break;	
			default: 
				$sort= " a.email ";
			break;
		}
	}else{
		$sort=' a.email';
	}
	include("maillist/maillist_query.php");
	//перегружаемся
	//print_r("Location:".SERVER_HOST. "/admin/maillist.php?list=".$maillist_id.((isset($_GET['sort']))?("&sort=".$_GET['sort']):("")).
	//((isset($_GET['mode']))?("&mode=".$_GET['mode']):("")));
	header("Location:".SERVER_HOST. "/admin/maillist.php?list=".$maillist_id.((isset($_GET['sort']))?("&sort=".$_GET['sort']):("")).
	((isset($_GET['mode']))?("&mode=".$_GET['mode']):("")));
	exit;
}elseif (isset($_POST['list_h'])&&$_POST['list_h']=='delete_all'){
	//print_r('delete_all');
	$del_arr=array();	
	foreach($_POST as $k => $v){
		$del_sql="DELETE FROM ".DB_PREFIX."_mailadres WHERE id IN(";									   
		if (substr($k,0,3)=="ckb"){
			$del_arr[]=strval($v);	
		}	
	}
	$del_sql.=implode(",",$del_arr).") ";
	if(count($del_arr)>0){
		//print_r($del_sql);
		mysql_query($del_sql)or die(mysql_error());
			//ПЕРЕСЧИТЫВАЕМ количество адресов
		$query_all = sprintf("SELECT COUNT(*) FROM %s_mailadres WHERE id_maillist=%d  ", DB_PREFIX,$_GET['list']);
		$all = mysql_query($query_all)or die(mysql_error());
		$allrows = mysql_fetch_row($all);
		//print_r($query_finish);
		$allrows=$allrows[0];
		$update_sql=sprintf("UPDATE %s_maillist SET adr_full=%d WHERE  id=%d ",DB_PREFIX,$allrows,$_GET['list']);
		//print($update_sql);
		mysql_free_result($all);
		$update = mysql_query($update_sql)or die(mysql_error());
	}
	header("Location:".SERVER_HOST. "/admin/maillist.php?list=".$_GET['list']);
	exit;
}//if (isset($_POST['list_h'])){

?>