<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 08.12.2015
 * Time: 12:23
 */

define("BD_SERVER","localhost" );
define("BD_NAME","a0413150_pets" );//lustras_db
define("BD_USER","a0413150_pets" );//us_lustras
define("BD_PWD","TsxrdREU" );//sv1til0ik
define("DB_PREFIX","pet" );

define("ROOT_PATH","/home/a0413150/domains/petsgroomer.ru/public_html/");
define("IMAGE_PATH",ROOT_PATH."picture/");
define("INCLUDE_PATH", ROOT_PATH . "include/");
define("CLASS_PATH",ROOT_PATH . "class/" );

define("SERVER_HOST","https://www.petsgroomer.ru" );
define("COOKIES", "petsgroomer.ru");

//сейчас используется только для  подтверждения рассылки
define("ADMIN_MAIL", "school@petsgroomer.ru" );
define("SMTP_LOGIN", "school@petsgroomer.ru");
define("SMTP_PASS", "vg7744AGA");
define("SMTP_SERVER", "smtp.yandex.ru");
define("SMTP_PROTOCOL", "ssl");
define("SMTP_PORT", "465");

define("SITE_NAME", "Школа груминга Боншери");

define("MAXROWS", 10);
define("MAXROWS_MESSAGES", 10);

define("DEBUG", false);
$servise_pages=array('glavnaya','contact' ,'subscribe' ,'subscribe_masterklass','oplata','prosrocheno','oplataerror');//служебные страницы
