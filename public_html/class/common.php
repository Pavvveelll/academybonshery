<?php
require_once("setting.php");
// ВЕРСИЯ СБОРКИ
define('VERSION','2.1.10');

function __autoload($name) {
    $name =  str_replace('\\', '/', $name);
	require_once CLASS_PATH.$name.'.php';
}
mysql_connect(BD_SERVER, BD_USER, BD_PWD);
mysql_select_db(BD_NAME);
 mysql_query('set names utf8');
// Функция экранирования переменных
function gvs($value,$theType="")
{

   // если magic_quotes_gpc включена - используем stripslashes
   if (get_magic_quotes_gpc()) {
       $value = stripslashes($value);
   }
  // print gettype($value);
   // Если переменная - число, то экранировать её не нужно
   // если нет - то окружем её кавычками, и экранируем
   if (!is_numeric($value)) {
		$value = "'" . mysql_real_escape_string($value) . "'";
   }elseif($theType=="text"){
		$value = "'" . mysql_real_escape_string($value) . "'";
   }
   return $value;
}
function reload_after_event($relative_url=""){
			//header("Location: http://".$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'].$relative_url);
			header("Location:".SERVER_HOST. $_SERVER['PHP_SELF'].$relative_url);
			exit;
}
/// НАСТРОЙКИ ВСЯКИЕ ////

?>
