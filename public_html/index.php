<?php
session_start();
//  print_r($_POST);
require_once("class/common.php");

//проверяем платеж ли это?
if($_SERVER['REQUEST_METHOD']=='POST') {
    if (isset($_POST['oplataaction']) && $_POST['oplataaction'] == 'send') {
        //проверяем сессию
        if(isset($_SESSION['oplatasess']) && $_SESSION['oplatasess']=='yes'){
            unset($_SESSION['oplatasess']);
            //пока всё правильно
            if (isset($_POST['nik']) && trim($_POST['nik'])!=''){
                //загружаем данные по курсу
                $op=new \item();//
                $op->table=DB_PREFIX.'_oplata';
                $paynumber=trim($_POST['nik']);
                if($op->get_item_seo($paynumber)){
                    $oplata_name=$op->my_item['name'];
                    $oplata_name=html_entity_decode($oplata_name);
                    $oplata_name=str_replace(['«','»',"'"],'"',$oplata_name);
                    $email_work=trim($_POST['CustEmail']);
                    //$oplata_name=str_replace(['"'],'\"',$oplata_name);
                    //формируем запрос в ЯНДЕКС КАССУ
                    $ykklient = new \YandexCheckout\Client;
                    $ykklient->setAuth('519884', 'live_x30Iw2_aHcGDlOwlYDgvfZh6BvJC0FPyqMMxf8nDiCg');
                    $uniqid = uniqid('', true);
                    try {
                        $summa=number_format($op->my_item['summa'], 2, '.', '');
                        $data_arr =array(
                            'amount' => array(
                                'value' => $summa,
                                'currency' => 'RUB'
                            ),
                            'description' =>$oplata_name,
                            'confirmation' => array(
                                'type' => 'redirect',
                                'return_url' =>  'https://www.petsgroomer.ru/oplata/good/',
                            ),
                            "receipt" => array(
                                "email" => $email_work,
                                "items" => array(
                                    array(
                                        "quantity" => "1",
                                        "description" => $oplata_name,
                                        "amount" => array(
                                            'value' => $summa,
                                            'currency' => 'RUB'
                                        ),
                                        "vat_code" => "1"
                                    )
                                )
                            ),
                            'payment_method_data' => array(
                                'type' => $_POST['paymentType'],
                            ),
                            'capture'=>true,
                            'metadata'=>[
                                    'paynumber'=>$paynumber,
                                'email_work'=>$email_work,
                                'phone_work'=> trim($_POST['custAddr']),
                                'user_name'=> trim($_POST['CustName']),
                                'd_start'=>date("d.m.Y", strtotime($op->my_item['d_start'])),//25.07.2018
                                'd_stop'=>date("d.m.Y", strtotime($op->my_item['d_stop'])),
                                'extra'=>$op->my_item['extra'],
                                'jilie'=>$op->my_item['jilie'],
                                'kurs'=>$op->my_item['kurs'],
//                                'kurs_extra'=>'yes',
//                                'tele'=>$_POST['custAddr'],
//                                'name'=>$_POST['CustName'],
//                                'datastart'=>'2018-09-12'
                            ]
                        );
                        $payment = $ykklient->createPayment(
                            $data_arr,
                            $uniqid
                        );
                    } catch (Exception $e) {
                        error_log($e->getMessage());
                    }
                    //file_put_contents (ROOT_PATH.'log1.txt', print_r($data_arr, true));
                    //file_put_contents (ROOT_PATH.'log.txt', print_r($payment, true));

                    //$opvalues =$op->my_item['name'];
                }


                if($payment->status=='pending'){
                    $kassaurl = $payment->confirmation->confirmationUrl;
                    header('Location: '.$kassaurl);
                }else{
                    $_POST['erroroplata']='error';
                }



            }

        }
    }
}


$page = new Page();
if(!$page->routing()){
    header("HTTP/1.0 404 Not Found");
    include_once("site_stop.php");
    exit;
}
$page->makePage();//Заголовки
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
<title><?=$page->title?> - Школа груминга Боншери</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$page->keywords?>
    <?=$page->description?>
    <?=$page->canonical?>
<link href="/css/page.css?v=<?=VERSION?>" rel="stylesheet" type="text/css" />
    <?php
    //GTM
    include "include/gtm_top.php";
    ?>
</head>
<body>
<?php
echo $page->renderAdminBlock();
?>
<div id="container">
<div class="header">
    <?php
    include "include/header.php";
    ?>
</div>
<div class="mainContent">
<?php
echo $page->render();
?>
<!--noindex-->
<div class="share"><p>Расскажите об этой странице друзьям</p>
    <script src="//yastatic.net/share2/share.js" async="async"></script>
    <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,telegram"></div>
</div>
<!--/noindex-->
<?php
echo $page->tudasuda;
?>
    <div id="scroller"></div>
</div>
  <div id="sdb" class="sidebar1">

  <div class="gmenu">
	<?php include(INCLUDE_PATH."menu.php");?>
  </div>
  <br class="clearfloat" />
<?php include_once(INCLUDE_PATH."left_col.php"); ?>
</div>
<br class="clearfloat" />
</div>
<?php
    include_once(INCLUDE_PATH."footer.php");
if($page->viev_pform){
    echo $page->podpBaner();
}
if ($page->ajax){
    echo '<script type="text/javascript" src="/js/tw-sack'.(BD_SERVER!='127.0.0.1'?'.min':'').'.js" ></script>';
    $page->script.= 'var ajax = new sack();';
}
if ($page->script!=''){
    echo '<script type="text/javascript"> ';
    echo $page->script;
    echo ' </script>';
}
?>
<script src="/js/page02.min.js?v=<?=VERSION?>" ></script>
</body>
</html>
