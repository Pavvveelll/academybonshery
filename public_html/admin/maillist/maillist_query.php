<?php  //ручная рассылка

//получаем адреса для рассылки  
//$selectSQL=sprintf("SELECT a.id,a.email,u.name ,u.shash 
//					FROM %s_mailadres a, %s_sustribe u, %s_maillist m 
//					WHERE m.id=%d AND  m.stat='active' AND  DATE(m.timeadd)<=CURDATE()
//					AND a.stat='active' AND a.id_maillist=%d AND u.login=a.email
//					ORDER BY %s LIMIT %d",DB_PREFIX,DB_PREFIX,DB_PREFIX,$maillist_id,$maillist_id,$sort, MAX_MESSAGE_COUNT);
 //получаем адреса для рассылки  
$selectSQL=sprintf("SELECT a.id,a.email,a.name ,a.shash 
					FROM %s_mailadres a , %s_maillist m 
					WHERE m.id=%d AND  m.stat='active' AND  DATE(m.timeadd)<=CURDATE()
					AND a.stat='active' AND a.id_maillist=%d  
					ORDER BY %s LIMIT %d",DB_PREFIX,DB_PREFIX,$maillist_id,$maillist_id,$sort, MAX_MESSAGE_COUNT);
 
 
//  die(htmlentities($selectSQL));
$res = mysql_query($selectSQL) or die(mysql_error());
	//die("llllllll");
if(mysql_num_rows($res)>0){//есть что рассылать
	
	require_once(CLASS_PATH."maillib.php");
	//форируем текст письма
	$email = new maillib();
	
	//загружаем данные из бд рассылки
	$selSQL=sprintf("SELECT *  FROM %s_maillist WHERE id=%d ",DB_PREFIX,$maillist_id);
	$resm = mysql_query($selSQL) or die(mysql_error());
	$rowm = mysql_fetch_assoc($resm);
	$email->from((($rowm['otemail']!='')?($rowm['otemail']):(ADMIN_MAIL)),(($rowm['otkogo']!='')?($rowm['otkogo']):(SITE_NAME)));//set a valid E-mail
	$email->return_path(($rowm['otemail']!='')?($rowm['otemail']):(ADMIN_MAIL));
	$message_post=$rowm['message'];
	$email->subject($rowm['subject']);
	$email->contentType="text/html";
	mysql_free_result($resm);
	
	$updateSustriber="";
	$updateSustriberBase="";//будем записывать колво рассылок пользователю
	$counter=0;
	//формируем шаблон
	$message=file_get_contents(ROOT_PATH."admin/maillist/msg_list_html.tpl");
	//проставляем доп параметры к ссылкам в письме//
	//$message_post = preg_replace('/href="([^"]+)"/', 'href="$1?rs=%%RS%%&amp;id=%%SHASH%%"', $message_post);
	
	//ищем ссылки в тексте
 	preg_match_all('/href="([^"]+)"/',$message_post, $links);
	//print_r($links);
	$links=array_unique($links[1]);//удаляем дубли
	//print_r($links);
	$links_new=array();
	foreach($links as $k=>$v){
		$purl=parse_url(trim($v));
		$query="";
		$query_arr=array();
		if($purl['query']!=""){
			$query_arr = explode('&amp;', $purl['query']);
		}
		//добавляем новый аргумент
		$query_arr[]="rs=%%RS%%";
		$query_arr[]="id=%%SHASH%%";
		$query=htmlentities(implode("&", $query_arr));

		
		//формируем ссылку
		$new_link="";
		if(isset($purl['host'])){
			$new_link.="http://".$purl['host'];
			//TODO для других сайтов добавляем партнерский аргумент (или редирект через наш сайт)
		}else{
			$new_link.=SERVER_HOST;
		}
		$new_link.=$purl['path'];
//		if(substr($new_link, -1)!="/"){
//			$new_link.="/";
//		}
		if($query!=""){
			$new_link.="?".$query;
		}
		//print("<br />");
		//print($new_link);		
		$links_new[$k]='"'.$new_link.'"'; 
		$links[$k]='"'.$v.'"';
	}
//	print_r($links);
// print_r($links_new);
	//меняем ссылки в тексте
//die($message_post);	
	$message_post=str_replace($links, $links_new, $message_post);
// die($message_post); 

	$message=str_replace('%%MESSAGE%%', $message_post, $message);
	$search  = array('%%SHASH%%', '%%NAME%%','%%HOST%%','%%RS%%');

	//в цикле отправляем.
	while ((($row = mysql_fetch_assoc($res))!=false)&&((getmicrotime()-$time_start)<MAX_MESSAGE_TIME)){
		$email->to($row['email']); // брать из базы
		$replace = array($row['shash'], $row['name'],SERVER_HOST,$maillist_id);
		$messageS=str_replace($search, $replace, $message);
		///die($message);
		$email->message($messageS);
		$email->send();
		//собираем апдейт
		$updateSustriber.=$row['id'].",";
		$updateSustriberBase.="'".$row['email']."',";
		$counter++;
	}
	///помечаем разосланных
	if($updateSustriber!=""){
		$updateSustriber="UPDATE ".DB_PREFIX."_mailadres SET stat='finish',sendtime=NOW() WHERE id IN (".substr($updateSustriber,0,-1).")";
		$resu = mysql_query($updateSustriber) or die(mysql_error());
		
		$updateSustriberBase="UPDATE ".DB_PREFIX."_sustribe SET rs=rs+1 WHERE login IN (".substr($updateSustriberBase,0,-1).")";
		//die($updateSustriberBase);
		$resu = mysql_query($updateSustriberBase) or die(mysql_error());
		
	}
	//ПЕРЕСЧИТЫВАЕМ количество адресов
	//$maillist_id=$maillist_id;
	//include_once("include/update_maillist.php");
	//ПЕРЕСЧИТЫВАЕМ количество адресов//отправленные
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
	$update_sql=sprintf("UPDATE %s_maillist SET stat ='finish',finaltime =NOW() WHERE stat<>'arhive' AND adr_full=adr_fin AND id=%d",DB_PREFIX ,$maillist_id);		
	$update = mysql_query($update_sql)or die(mysql_error());
	
	if(!defined('CRON')){
	
		$_SESSION['message_result']['counter']=$counter;
		$_SESSION['message_result']['timer']=number_format((getmicrotime()-$time_start),3);
		//print ($item->get_value("adr_full",$maillist_id));
		if($finishrows==$item->get_value("adr_full",$maillist_id)){
			$_SESSION['message_result']['finall']=" <strong>Рассылка завершена.</strong>";
		}
	}else{
		printf("%s || Rassylka #%s || %d - pisem za %s cekund", date("d m Y, H:i:s"), $maillist_id, $counter,number_format((getmicrotime()-$time_start),3));
	}
	
}else{//if(mysql_num_rows($res)>0){
	//рассылка завершена
	$_SESSION['message_result']['finall']="Нет адресов для рассылки или рассылка неактивна.";
}//if(mysql_num_rows($res)>0){
	////
	mysql_free_result($res);?>