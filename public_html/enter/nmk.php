<?php 
// print_r($_GET);
require_once("../class/common.php");
 
if(isset($_GET['id'])){
	include_once("sustribe.item.php");
	$u_id=$item->get_id_from_hash($_GET['id']);
	if($u_id!=false){
		$item->load($u_id);
		$item->set_value("master",$u_id,"no");
		// print_r($item);
//		switch ($_GET['act']){
//			case "act":
//				$item->set_value("sstatus",$u_id,"yes");// set_value($value_type, $id, $new_value, $table=""){
//				//
//				$viev="act";
//			break;
//			case "edit":
//			case "uns":
//			default:	
//				$item->my_item['action_item']='edit';		
//				$viev="edit";
//				break;
//			break;
//		}
	}else{//не найден выводим ошибку
			header("Location: ".SERVER_HOST );
			exit;
	}
}else{
			header("Location: ".SERVER_HOST );
			exit;
}

 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Нет мастерклассам! - Школа груминга Боншери</title>
 
<link href="/css/page.css" rel="stylesheet" type="text/css" />

</head>
<body class="twoColFixLt" >
<div id="container">
<div id="header"><div id="logoa" onclick="window.location.href='<?=SERVER_HOST?>'"></div>
<!-- end #header --></div>
<div id="mainContent">
	<h1 align="center">Спасибо <?=$item->my_item['name']?>!</h1>
  <p align="center"><strong>С этого момента Вы будете получать только нужную информацию</strong></p>
 
  <p>По всей видимости Вы еще только задумываетесь о профессии &quot;грумер&quot;, может быть Вам будет полезно вступить в открытую группу школы Боншери во ВКонтакте.</p>
  <script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>

    <!-- VK Widget -->
    <div id="vk_groups"></div>
    <script type="text/javascript">
    VK.Widgets.Group("vk_groups", {mode: 0, width: "670", height: "270", color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 18623149);
    </script>
</div>
  <div id="sidebar1">
  <div  class="page" id="gmenu">
	<?php include(INCLUDE_PATH."menu.php");?>
  </div>
  <br class="clearfloat" />
<?php //include_once(INCLUDE_PATH."left_col.php"); ?>
<br class="clearfloat" />
  <!-- end #sidebar1 --></div>
<br class="clearfloat" />
<!-- end #container --></div>
<?php include_once(INCLUDE_PATH."footer.php"); ?>
</body>
</html>