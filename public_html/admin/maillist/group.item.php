<?php 
//unset($_SESSION["message_result"]);
$settings_array=array(
	'fields'=>array(
		array(
			'name'=>"id_group",
			'text'=>"Ключ",
			'format'=>"key",
			'viev'=>"key",
			'default'=>0
		),
		'gname'=>array(
			'name'=>"gname",
			'format'=>"text",
			'text'=>"Группа",
			'viev'=>"text",
			'max_length'=>32,
			'required'=>"Наименование группы"
		),
		'gdes'=>array( //message
			'name'=>"gdes",
			'text'=>"Описание",
			'format'=>"text",
			'viev'=>"textarea",
			'max_length'=>255
			//,высота в строках'textarea'=>8
		),	
		array(
			'name'=>"action_item",
			'format'=>"hidden",
			'viev'=>"hidden",
			'default'=>"add"
		)	
	),
	'entity'=>"sustribe_group",
	'table'=>"sustribe_group",
	'picture_path'=>""
	//'picture_path'=>"logotype/"
);

//print_r($settings_array);
$item = new item();
$item->set_settigs($settings_array);

if (isset($_POST['action_item'])){
	$item->action($_POST['action_item']);
	if($_POST['action_item']=="delete_item"){
		$gs= "/admin/maillist.php";
		//Удаление ГРУППЫ удаляет все адреса 	
		$del_sql="DELETE FROM ".DB_PREFIX."_sustribe WHERE sgroup=".$_POST['id_group'];	
		mysql_query($del_sql)or die(mysql_error());
	}else{
		$gs= "/admin/maillist.php?sustribers=group_".$item->my_item['key'];
	}
	
	if($item->error==""){
		header("Location:".SERVER_HOST.$gs);
		exit;
	}// $item->error;
}elseif (isset($_POST['list_s'])&&$_POST['list_s']=='delete_all'){
	//print_r($_POST);
	$del_arr=array();	
	foreach($_POST as $k => $v){
		$del_sql="DELETE FROM ".DB_PREFIX."_sustribe WHERE id IN(";									   
		if (substr($k,0,3)=="ckb"){
			$del_arr[]=strval($v);	
		}	
	}
	$del_sql.=implode(",",$del_arr).") ";
	if(count($del_arr)>0){
		//print_r($del_sql);
		mysql_query($del_sql)or die(mysql_error());
//			//ПЕРЕСЧИТЫВАЕМ количество адресов
//		$query_all = sprintf("SELECT COUNT(*) FROM %s_mailadres WHERE id_maillist=%d  ", DB_PREFIX,$_GET['list']);
//		$all = mysql_query($query_all)or die(mysql_error());
//		$allrows = mysql_fetch_row($all);
//		//print_r($query_finish);
//		$allrows=$allrows[0];
//		$update_sql=sprintf("UPDATE %s_maillist SET adr_full=%d WHERE  id=%d ",DB_PREFIX,$allrows,$_GET['list']);
//		//print($update_sql);
//		mysql_free_result($all);
//		$update = mysql_query($update_sql)or die(mysql_error());
	}
	header("Location:".SERVER_HOST. "/admin/maillist.php?sustribers=".$_GET['sustribers']);
	exit;
}

//
//if (isset($_POST['action_item'])){
//	$is_arhive='none';
//	if($_POST['action_item']=="delete_item"){
//		$gs= "/admin/maillist.php";
//		//Удаление рассылки удаляет все адреса
//		$del_sql=sprintf("DELETE FROM %s_mailadres WHERE  id_maillist=%d",DB_PREFIX,$_POST['id']);
//		mysql_query($del_sql)or die(mysql_error());
//		//
//	}elseif($_POST['action_item']=="edit"){
//		
//		//Проверяем архивность
//		$is_arhive=$item->get_value('stat',$_POST['id']);
//		//die($is_arhive);
//		$gs= "/admin/maillist.php?id=".$_POST['id'];
//		if($_POST['stat']=="arhive"){//архив удаляет все адреса
//			$del_sql=sprintf("DELETE FROM %s_mailadres WHERE  id_maillist=%d",DB_PREFIX,$_POST['id']);
//			mysql_query($del_sql)or die(mysql_error());
//		}
//	}
//	
//	if(!(($is_arhive=="arhive")&&($_POST['action_item']!="delete_item"))){//запрещаем редактирование архивов
//		$item->action($_POST['action_item']);	
//		if($_POST['action_item']=="add"){
//			$gs= "/admin/maillist.php?id=".$item->my_item['key'];
//		}
//	}
// 	
//	if($item->error==""){
//		header("Location:".SERVER_HOST.$gs);
//		exit;
//	}// $item->error;
//	
//}elseif (isset($_GET['send'])){
//	$maillist_id=$_GET['id'];
//	$sort=' a.email';
//	include("maillist/maillist_query.php");
//	header("Location:".SERVER_HOST. "/admin/maillist.php?id=".$maillist_id);
//	exit;
//}

?>