<?php 
// print_r($_GET);
require_once("../class/common.php");
 

//считаем приходы с рассылки
if(isset($_GET['rs'])&&intval($_GET['rs'])>0){
	$sql=sprintf("UPDATE %s_maillist SET adr_rez=adr_rez +1 WHERE id=%d ", DB_PREFIX, intval($_GET['rs']));
	mysql_query($sql) or die(mysql_error());
	//error_log("id=".$_GET['id']);
	if(isset($_GET['id'])&&($_GET['id']!="")){
		$sql=sprintf("UPDATE %s_mailadres SET prihodtime=NOW() WHERE prihodtime='0000-00-00 00:00:00' AND id_maillist=%d AND shash=%s", DB_PREFIX, intval($_GET['rs']),gvs($_GET['id'],"text"));
	//	error_log($sql);
		mysql_query($sql) or die(mysql_error());
		if(mysql_affected_rows()>0){
			// считать только первый визит по рассылке
			$sql=sprintf("UPDATE %s_sustribe SET prihod=prihod+1 WHERE  shash=%s", DB_PREFIX, gvs($_GET['id'],"text"));
			mysql_query($sql) or die(mysql_error());
			$sql=sprintf("UPDATE %s_maillist SET prihod=prihod +1 WHERE id=%d ", DB_PREFIX, intval($_GET['rs']));
			mysql_query($sql) or die(mysql_error());
		}
	}
}
		
//редирект
header("HTTP/1.1 302 Found");
header("Location: http://vk.com/club42832803");
exit;	
?>
