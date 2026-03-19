<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 28.08.2015
 * Time: 10:29
 */

//print_r($_GET);
require_once("../class/common.php");
require_once("../class/Mailchimp.php");
//
$mch=new Mailchimp('11b24623a085eb18ed372b596f2694c6-us9');
$mchlist=new Mailchimp_Lists($mch);
$u=array(array('euid'=>$_GET['e']));
try {

    $res=$mch->lists->memberInfo('84de7c7eec',$u);
    if($res['success_count']==0){
        throw new Exception('Podpischik ne naiden');
    }
    if(isset($_GET['gr']) && $_GET['gr']=='yes'){//грумер - подписываем на мастерклассы
        $merge_vars = array(
            'GROUPINGS'=> array(
                array(
                    'id' => 16537,
                    'groups' => array('- о мастер-классах (для грумеров)')
                )
            )
        );
    }else{
        $merge_vars = array(
            'GROUPINGS'=> array(
                array(
                    'id' => 16537,
                    'groups' => array('- о курсах (для новичков)')
                )
            )
        );
    }
    $mch->lists->updateMember('84de7c7eec',array('euid'=>$_GET['e']), $merge_vars);
    $mess1="Спасибо";
    $mess="Информация обновлена";
} catch (Exception $e) {
    print('Error groomer: '.$e->getMessage());//,1,"deiww@mail.ru"
    $mess1="Ошибка";
    $mess="Не удалось обновить информацию.";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Спасибо</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Description" content="Спасибо" />
    <meta name="Keywords" content="Спасибо" />
    <link href="/css/page.css" rel="stylesheet" type="text/css" />

</head>
<body>
<h1><?=$mess1?></h1>
<p><?=$mess?></p>
<p>перейти <a href="/" style="color:#d6a46e">на главную страницу...</a></p>
</body>
</html>
