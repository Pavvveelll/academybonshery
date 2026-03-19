<?php 
function getmicrotime(){ 
	list($usec, $sec) = explode(" ",microtime()); 
	return ((float)$usec + (float)$sec); 
}
$time_start = getmicrotime();
session_start();
 
// print_r($_POST);
 
 
define("ANMIN_PAGE","yes_admin");
///////////////////////////
///////*SETTINGS*//////////
  define('MAX_MESSAGE_COUNT', 50);
  define('MAX_MESSAGE_TIME', 8);
 // $test_group=array("'deiww@mail.ru'","'deiwww@yandex.ru'");///,"'center@center-vityaz.com'","'sierra2000@mail.ru'"
//////////////////////////
$page="maillist";
$error="";
require_once("../class/common.php");
$phpself=$_SERVER['PHP_SELF'];
$prefix="maillist";
$viev="sustribe_list";

/* ПОИСК */
if(isset($_POST['slogin'])){
	$query = sprintf("SELECT id,sgroup FROM %s_sustribe WHERE login='%s' LIMIT 1 ", DB_PREFIX, trim($_POST['slogin']));
	$searh = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($searh)>0){
		$row = mysql_fetch_assoc($searh);
		header("Location:".SERVER_HOST. "/admin/maillist.php?sustribers=".$row['sgroup']."&sstrb=".$row['id']);
		exit;		
	}else{
		$error='Не удалось найти подписчкика '.$_POST['slogin'];
	}
	mysql_free_result($searh);

}

if(isset($_GET['id'])){
	include_once("maillist/".$prefix.".item.php");
	if(intval($_GET['id'])>0){//загружаю
		if($item->load(intval($_GET['id']))==false){
			  $item->create_blank();
			  $item->error.="Не удалось загрузить данные!";
			  $item->my_item['action_item']="add";
			  //$item->my_item['id_cat']=$cat;
		 }else{//загружено
			 // $cat=($item->my_item['id_cat']>1)?($item->my_item['id_cat']):(1);
			  $item->my_item['action_item']="edit";
		 }
	 }else{
		  if (!isset($_POST['action_item'])){
			  $item->create_blank();
			  $item->my_item['action_item']="add";
			 // $item->my_item['id_cat']=$cat;
		  }
	 }
	$viev="form";
}elseif(isset($_GET['list'])){//список адресов рассылки
	include_once("maillist/adresa.item.php");
	// $item->create_blank();
	 $item->load(intval($_GET['list']));
	 //запрещаем редактирование адресов архивных рассылок
	 if($item->my_item['stat']=='arhive'){
		header("Location:".SERVER_HOST. "/admin/maillist.php?id=".$_GET['list']);
		exit;
	 }
	 //$item->my_item['id']=intval($_GET['list']);
	//TODO убрать после апдейта  , когда присваивается  по дефолту
	 $item->my_item['action_item']='podbor';
	 $item->my_item['alluser']='no';
	 $item->my_item['allhosyain']='no';
	 $item->my_item['podpis4iki']='no';
	 $item->my_item['testir']='yes';
		if(isset($_GET['stat'])){
			$viev="sustribe_stat";	
		}else{
			$viev="sustribe_adr";
		}
}elseif(isset($_GET['sustribers'])){//список подписчиков
	include_once("maillist/group.item.php");
	if(is_numeric($_GET['sustribers'])){//список
		$id_group=intval($_GET['sustribers']);
		
		if($item->load(intval($id_group),'','','id_group')==false){
			
				header("Location:".SERVER_HOST. "/admin/maillist.php");
				exit;
		}
		 
		if(isset($_GET['stat'])){
			$viev="group_stat";	
		}else{
			$viev="group_list";	
			//ПОДПИСЧИК
			include_once("maillist/sustriber.item.php");
			$display_sstb="none";
			if($sustriber->error==""){
				if(isset($_GET['sstrb'])&&intval($_GET['sstrb'])>0){
					if($sustriber->load(intval($_GET['sstrb']))==false){
						$sustriber->create_blank();
					}else{
						$sustriber->my_item['action_sus']="edit";
						$display_sstb="block";
					}
				}else{
					$sustriber->create_blank();
					$sustriber->my_item['sgroup']=$id_group;
				}
			}else{
				$display_sstb="block";
			}
		}
	}else{//форма редактирования
		$id_group=substr($_GET['sustribers'],6);
		if(is_numeric($id_group)){
			
			if(intval($id_group)>0){
				if($item->load(intval($id_group))==false){
					  $item->create_blank();
					  $item->error.="Не удалось загрузить данные!";
					  $item->my_item['action_item']="add";
				 }else{//загружено
					  $item->my_item['action_item']="edit";
				 }
				 
				$viev="group_edit";	
			}elseif(intval($id_group)==0){//новая
				$viev="group_edit";
				//print("ddddddddddd");
				if (!isset($_POST['action_item'])){
				// 
				  $item->create_blank();
				 //  print_r( $item);
				 // $item->my_item['action_item']="add";
			  	}		
			}
		}
		

	}
}


$stat_arr=array('new'=>' <span class="alert">новая</span> (рассылка не происходит)', 'active'=>'  <span class="greentext">активная</span> (происходит рассылка)', 'finish'=>'  <strong>завершена</strong> (рассылка завершена)' , 'arhive'=>'  <span class="alert">Архив</span>' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />
	<title><?=DB_PREFIX?>.Рассылка</title>
	<script language="JavaScript" type="text/javascript" src="/js/forms.js"></script>
</head>
<body >
<div id="content">
  <?php include("include/adminmenu.php"); ?>
  <div class="left">
          <?php
if(isset($_SESSION['message_error'])){
	echo '<div class="error">'.$_SESSION['message_error'].'</div>';
	unset($_SESSION['message_error']);
}
///РЕЗУЛЬТАТ РАССЫЛКИ
if(isset($_SESSION['message_result'])){
	$message_txt="<div id='send'>";
	if(isset($_SESSION['message_result']['counter'])){
		$message_txt.="Разослано <strong>";
		$message_txt.= $_SESSION['message_result']['counter'];
		$message_txt.= "</strong> сообщений за ";
		$message_txt.= $_SESSION['message_result']['timer'];
		$message_txt.= "</strong> сек.";
	}
	if(isset($_SESSION['message_result']['finall'])){
		$message_txt.= $_SESSION['message_result']['finall'];
		unset($_GET['auto']);
	}
	if(isset($_SESSION['message_result']['save'])){
		$message_txt.=$_SESSION['message_result']['save'];
	}
	$message_txt.="</div>";
	unset($_SESSION['message_result']);
	print($message_txt);
}
 
if(isset($_GET['auto']) && $item->my_item['stat']=='active'){/// ПОЛУАВТОМАТ
	$message_txt="<div id='timer'>";
	$message_txt.='Полуавтоматическая рассылка  через <span id="reload_timer" style="font-weight:bold; font-size:16px;"></span> &nbsp;&nbsp;&nbsp; 
					<a href="'.SERVER_HOST. '/admin/maillist.php?id='.$_GET['id'].'">остановить...</a>';
	$message_txt.="</div>";
	print($message_txt);
	?>
    <script type="text/javascript">
	var intervalID;
	var interr = <?php echo rand(180, 240) ?>; //интервал обновления страницы в секундах
	var intert = 1; //интервал обновления таймера в секундах
	var rt=getObject('reload_timer');
	var timer_text = function(sec) {
		sec = interr-sec;
		 if(sec<=0){
			 clearInterval(intervalID);
			 rt.innerHTML = '.......';
			 window.location.href="<?php echo SERVER_HOST. "/admin/maillist.php?id=".$_GET['id']."&send=auto"; ?>";
		 }else{
			var h = Math.floor(sec / 3600);
			var m = (Math.floor(sec / 60) - (Math.floor(sec / 3600) * 60));
	
			var s = sec % 60;
			var text = '';
			
			//if(h > 0) text += h + ": ";
			if(m > 0){
				text += m+ ":";
			}else{
				text += "0:";
			}
			if(s > 9){
				text += s;
			}else if(s > 0){
				text += "0"+s;
			}else{
				text += "00";
			}
			rt.innerHTML = text;
		 }
	}
	timer_start_date = new Date().getTime();	
	timer_text(0);
	intervalID=setInterval(function() { //авто обновление таймера
		timer_text(Math.ceil((new Date().getTime() - timer_start_date) / 1000));
	}, intert*1000); //Интервал таймера в миллисекундах, т.е 1000 это 1 секунда
	</script>
    <?php
}
// выбор показа
switch($viev){
	case "form":
		include("maillist/maillist_form.php");
		break;
	case "sustribe_list":
		include("maillist/maillist_list.php");
		break;	
	case "sustribe_adr":
		include("maillist/maillist_list_adr.php");
		break;	
	case "sustribe_stat":
		include("maillist/maillist_list_stat.php");
		break;			
	case "group_edit":
		include("maillist/group_form.php");
		break;
	case "group_list":
		include("maillist/group_list.php");
		break;
	case "group_stat":
		include("maillist/group_stat.php");
		break;	
	
}
//if(isset($_GET['id'])){///показываем сообщения определенного пользователя
//	//include("include/message_user.php");
//	include("maillist/maillist_form.php");
//}else{//показываем список пользователей написавших сообщения
//	//include("include/message_list.php");
//	//print_r("dfgdf");
//	if(isset($_GET['list'])){
//		include("maillist/maillist_list_adr.php");
//	}else{
//		include("maillist/maillist_list.php");
//	}
//	
//} 
?>
<br />
</div>
<div id="right">
<div class="boxtop razdel" >Рассылки</div>
<div class="box">
<p><a href="/admin/maillist.php">Текущие</a></p>
<p><a href="/admin/maillist.php?mode=all">Все рассылки</a></p>
<p><a href="/admin/maillist.php?mode=new" >Новые</a></p>
<p><a href="/admin/maillist.php?mode=active" >Активные</a></p>
<p><a href="/admin/maillist.php?mode=finish" >Завершенные</a></p>
<p><a href="/admin/maillist.php?mode=arhive" >Архив</a></p>
</div>
 
<div class="boxtop razdel" >Группы подписчиков</div>
<div class="box">
<?php
//Группы подписчиков
//$query = sprintf("SELECT * FROM %s_sustribe_group ", DB_PREFIX);
$query = sprintf("SELECT count(s.id) as co,g.* FROM %s_sustribe s, %s_sustribe_group g WHERE s.sstatus='yes' AND g.id_group=s.sgroup GROUP BY s.sgroup", DB_PREFIX, DB_PREFIX);
// print($query);	
$pod = mysql_query($query) or die(mysql_error());
while(($rowe = mysql_fetch_assoc($pod))!=false){
	printf('<p><a href="/admin/maillist.php?sustribers=group_%d" ><img src="images/edit_16.gif" alt="ред" width="16" height="16" border="0" /></a> 
				<a href="/admin/maillist.php?sustribers=%d" >%s</a> - <strong>%s</strong></p>',$rowe['id_group'],$rowe['id_group'],$rowe['gname'],$rowe['co']);
}
mysql_free_result($pod);
?>    
    <p><br /><a href="/admin/maillist.php?sustribers=group_0" >Создать группу...</a></p>
</div>
    <div class="boxtop razdel" >Подписчики</div>
    <div class="box">
<?php
//Подписчики
$query = sprintf("SELECT COUNT(*) as co,sstatus FROM %s_sustribe   GROUP BY sstatus ORDER BY sstatus DESC ", DB_PREFIX);	
$pod = mysql_query($query) or die(mysql_error());
$sstatus_array=array();

while(($rowe = mysql_fetch_assoc($pod))!=false){
	if($rowe['sstatus']=='yes'){
		$sstatus_array['yes']=$rowe['co'];
	}elseif($rowe['sstatus']=='new'){
		$sstatus_array['new']=$rowe['co'];
	}elseif($rowe['sstatus']=='uns'){
		$sstatus_array['uns']=$rowe['co'];
	}
}
//print_r($sstatus_array);
if(count($sstatus_array)>0){
	print(  '<p><strong>По статусу</strong></p>');
	print("<ul>");
	if($sstatus_array['yes']>0){
		print(  '<li>Активные - <strong>'.$sstatus_array['yes'].'</strong></li>');
	}
	if($sstatus_array['new']>0){
		print(  '<li>не активированные - <strong>'.$sstatus_array['new'].'</strong></li>');
	}
	if($sstatus_array['uns']>0){
		print(  '<li>отписались - <strong>'.$sstatus_array['uns'].'</strong></li>');
	}
	
	print("</ul>");	
}

	
mysql_free_result($pod);

print(  '<p><strong>По содержанию</strong></p>');
print("<ul>");
	$query = sprintf("SELECT COUNT(*)  FROM %s_sustribe WHERE sstatus='yes' AND master='yes' ", DB_PREFIX);	
	$query_viev = mysql_query($query) or die(mysql_error());
	$rown = mysql_fetch_row($query_viev);
	$rez=$rown[0];
	mysql_free_result($query_viev);
	if($rez>0){
		print(  '<li>Мастерклассы - <strong>'.$rez.'</strong></li>');
	} 
	$query = sprintf("SELECT COUNT(*)  FROM %s_sustribe WHERE sstatus='yes' AND kurs='yes' ", DB_PREFIX);	
	$query_viev = mysql_query($query) or die(mysql_error());
	$rown = mysql_fetch_row($query_viev);
	$rez=$rown[0];
	mysql_free_result($query_viev);
	if($rez>0){
		print(  '<li>Курсы - <strong>'.$rez.'</strong></li>');
	} 
 
 print("</ul>");

?>
</div>

<div class="boxtop"></div>
<div class="box">
<p class="razdel">Рекомендации</p>
<p>  Рассылку создавать не чаще одного раза в сутки. Если поводов для рассылки много, то включать их в одну рассылку, разделяя абзацами.</p>
<p>Рассылка будет производится автоматически при условиях:<br />
  - статус рассылки &quot;активная&quot;;<br />
  - дата начала рассылки актуальна.<br />
  - список адресов содержит не отправленные.</p>
<p>сначала сформировать список рассылки для тестовой группы, разослать, проверить получение, оформление. При необходимости отредактировать сообщение.</p>
<p>Рассылка начинается после дого как сформирован список адресов.<br />
  Остановит рассылку можно принудительно установив статус &quot;завершена&quot;.</p>
<p>При &quot;очистке списка&quot; все адреса удаляются кроме тех на которые уже были отправлены сообщения. Это сделано для исключения повторных сообщений.<br />
  Примечание: адреса  группы &quot;Тестовая&quot; удаляются всегда.</p>
</div>
</div>
<div class="footer">
  <?php 	include("include/adminfooter.php");?>
</div>
</div>
</body>
</html>