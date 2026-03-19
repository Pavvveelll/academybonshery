<?php
require_once("../class/common.php");
if(isset($_POST['arg']) && isset($_POST['sender'])){
    //устанавливаем в майлчимпе поле
    $m4p=new MailchimpUse('11b24623a085eb18ed372b596f2694c6-us9');
    $list_id = 'b797dcf1fd';//курсы
    $m4p->setMergeField($list_id,$_POST['sender'],'GOTOV',trim($_POST['arg']));//$list,$login,$merge_id,$value
}