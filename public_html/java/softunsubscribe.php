<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 06.03.2016
 * Time: 20:11
 *
 *
 * ссылка типа http://www.petsgroomer.ru/java/softunsubscribe.php?list=*|LIST:UID|*&sender=*|EMAIL|*
 * http://www.petsgroomer.ru/java/softunsubscribe.php?list=b797dcf1fd&sender=center@center-vityaz.com
 *
 */
require_once("../class/common.php");

if(isset($_GET['list']) && isset($_GET['sender'])){
    $m4p=new MailchimpUse('11b24623a085eb18ed372b596f2694c6-us9');
//    $user_info=$m4p->get_user_info($_GET['list'],$_GET['sender']);
//    print_r($user_info);
    $uns=$m4p->unsubscribe($_GET['list'],$_GET['sender']);
    //print_r($uns);
    //вне зависимости от результата, перегружаемся на страницу ОТПИСАНО
    header("Location:".SERVER_HOST.'/subscribe/accountdeleted/');
    exit;
}else{
    header("Location:".SERVER_HOST);
    exit;
}

//востановить
//$rest=$m4p->restoreuser('e82121b7b2','deiww@mail.ru');
//e82121b7b2 вебинар
//b797dcf1fd Рассылка Академии Боншери caf5efb8cecbdc11c8db28829e8d79cf

