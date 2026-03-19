<?php
//загружает EXE!!!!!!!!!!!!!!
//ДОБАВИТЬ
//проверку идентификации пользователя и админа
//убрать создание директорий
//REGUEST
//лимиты на размеры и вес картинок
//использовать класс загрузок
//предусмотреть создание списка изображений без вызова этого окна
//упорядочить и формализовать для дальнейшего использования
//русский язык
//обработка ошибок
//TODO it woudn't be bad to check for admin rights here ;)
//require_once("../class/common.php");
/*include(CLASS_PATH."auth.start.php");//для входа
if($auth->is_loged!=true)
	exit;*/

/*set_time_limit(0);
ini_set("max_input_time", "600");
ini_set("max_execution_time", "600");
ini_set("memory_limit", "104857600");
ini_set("upload_max_filesize", "104857600");
ini_set("post_max_size", "104857600");*/
//print_r($_GET);
require("file_manager_config.php");
///require("file_manager/utils.php");
if (isSet($_REQUEST["type"])) {
	$type = $_REQUEST["type"];
}
else {
	$type = -2;
}
$default_dir = ".";
$ext = array("*");
$url_dir = "";
if ($type != -2) {
	if (isSet($settings[$type])) {
		$default_dir = $settings[$type]["dir"];
		$url_dir = $settings[$type]["url_dir"];
		$ext = $settings[$type]["ext"];
	}
}

if (!isSet($_GET["dir"]) || strlen($_GET["dir"]) == 0) {
	$dir = $default_dir;
	$requested_dir = "";
}
else {
	$requested_dir = $_GET["dir"] . "/";
	$dir = $default_dir . "/" . $requested_dir;
}
if (strpos($dir, "..") > 0) //'..' in our path is a big no-no
	$dir = $default_dir;

/*if (strlen($requested_dir) > 0)
{
	$requested_dirs = explode("/", $requested_dir);
	$tmp_dirs = "";
	foreach ($requested_dirs AS $tmp_dir)
	{
		if ($tmp_dir != "")
		{
			if ($tmp_dirs == "")
				echo "<a class='back' href='?type=" . $type . "'>/</a>";
			else
				echo "/";
				
			$tmp_dirs .= $tmp_dir . "/";
			if ($requested_dir == $tmp_dirs)
				echo $tmp_dir;
			else
				echo "<a class='back' href='?type=" . $type . "&dir=" . $tmp_dirs . "'>" . $tmp_dir . "</a>";
		}
	}
}
else
	echo "&nbsp;";

if (strlen($requested_dir) > 0) {
	$last_pos = strrpos(substr($requested_dir, 0, strlen($requested_dir)-1), "/");
	$prev_dir = "";
	if ($last_pos !== FALSE && $last_pos > 0)
		$prev_dir = substr($requested_dir, 0, $last_pos);
	?>
	<a class="back" href="?type=<?php echo $type; ?>&amp;dir=<?php echo $prev_dir; ?>">&lt;&lt; <?php echo $strings["back"]; ?></a>
	<?php
}*/

//print($dir);
$dh  = opendir($dir);
$files_num=array();
$files=array();
while (false !== ($filename = readdir($dh))) {
 
	if ($filename != "." && $filename != "..") {
		if (is_dir($dir . "/" . $filename)) {
			$dirs[] = $filename;
		}else {
		//print_r($ext);
			if (sizeof($ext) > 0) {
			//print  strlen($_GET['img_basename']);
				for ($i=0;$i<sizeof($ext);$i++) {
					if ($ext[$i] == "*" || (strtolower($ext[$i]) == strtolower(substr($filename, -strlen($ext[$i]))))) {
						if((isset($_GET['img_basename']))&&(strncasecmp($filename,$_GET['img_basename'],strlen($_GET['img_basename']))==0)){
						
						$files[] = $filename;
						$fa=explode("_",$filename);
						$fa=explode(".",$fa[2]);
						//print_r($fa[0]);
						$files_num[]=$fa[0];
						break;
						}
					}
				}
			}
			else {
				$files[] = $filename;
			}
		}
	}
}
$tmp_str = "[";
foreach($files as $v){
	$tmp_str.="{title: '$url_dir$v', value: '$url_dir$v'},";
}
$tmp_str=substr($tmp_str,0,-1);
$tmp_str .="]";
print 	$tmp_str;	

//print "var tinyMCEImageList = new Array(";
//$tmp_str="var tinyMCEImageList = new Array( ";
//foreach($files as $v){
//	$tmp_str.="
//	[ \"$v\",\"".$url_dir.$v."\"],";
//}
//$tmp_str=substr($tmp_str,0,-1);
///*print "var tinyMCEImageList = new Array(
//		[\"Logo 3\", \"logo.jpg\"],
//		[\"Logo 2 Over\", \"logo_over.jpg\"]);";*/
//$tmp_str.=");";	
//	print 	$tmp_str;	
///*if (sizeof($ext) > 0) {
//	print_r($files);
//}*/
//include($types[$settings[$type]["type"]]);
?>
