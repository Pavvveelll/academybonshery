<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 16.07.2018
 * Time: 14:31
 *
 * сюда приходит ответ от Янжекс-кассы waiting_for_capture при успешном платеже
 * и отсюда мы отправляем данные в Битрикс-24
 *
 *
 */

require_once("../class/common.php");
/*
 * слушаем
 * проверяем содержимое
 * вытаскиваем статус
 * если PAYMENT_SUCCEEDED
 * формируем данные для битрикс
 * отправляем битриксу
 * отправляем письмо школе об оплате
 * */


$source = file_get_contents('php://input');
//file_put_contents (ROOT_PATH.'logF.txt', print_r($source, true));
//error_log(print_r($source, true), 1,'deiww@mail.ru');


if (empty($source)) {
    header('HTTP/1.1 400 Empty request body');
    exit();
}
$json = json_decode($source, true);
if (empty($json)) {
    if (json_last_error() === JSON_ERROR_NONE) {
        $message = 'empty object in body';
    } else {
        $message = 'invalid object in body: ' . $source;
    }
    error_log('Invalid parameters in capture notification controller - ' . $message);
    header('HTTP/1.1 400 Invalid json object in body');
    exit();
}
file_put_contents (ROOT_PATH.'logA'.date('His').'.txt', print_r($json, true));

if($json['event']=='payment.succeeded'){
    $res=$json['object'];
    $kurs_arr=[
        'kurs'=>"Курс",
        'mk'=>"Мастеркласс",
        'other'=>"Другое"
    ];

    $post_fields=[
        'TITLE'=>$res['description'],
        'LOGIN'=>'deiwww@yandex.ru',
        'PASSWORD'=>'sdfE35738!',
        'EMAIL_WORK'=>$res['metadata']['email_work'],
        'PHONE_WORK'=>$res['metadata']['phone_work'],
        'NAME'=>$res['metadata']['user_name'],//
        'UF_CRM_1530448220340'=>$res['metadata']['d_start'],//'25.07.2018',//Дата начала обучения - дд.мм.гггг
        'UF_CRM_1530448231287'=>$res['metadata']['d_stop'],//'01.08.2018',//Дата окончания обучения - дд.мм.гггг
        'UF_CRM_1530448256512'=>(($res['metadata']['extra']=='yes')?('Экстра'):('Стандарт')),//'Экстра',//Категория -  - тип поля список принимает значения: Стандарт Экстра
        'UF_CRM_1530448275959'=>(($res['metadata']['jilie']=='yes')?('Включено'):('Не включено')),//'Не включено',//Проживание -  - тип поля список - принимает значения: Включено Не включено
        'UF_CRM_1531837476163'=>$kurs_arr[$res['metadata']['kurs']],//'Курс',//Оплата за - Тип поля список - значения списка: Курс Мастеркласс Другое
        'OPPORTUNITY'=>intval($res['amount']['value']),
        'COMMENTS'=>'тест',//'ТЕСТ Оплата 25.07.2018 экстра без проживания Меньше 7 дней',
    ];
    file_put_contents (ROOT_PATH.'logB'.date('His').'.txt', print_r($post_fields, true));

    $z=curl_init();
    //die($v['url'].'admin/sinhron/robot.php?type=find_this&code='. urlencode($code).'&id='.$id.'&host='.urlencode($_SERVER['HTTP_HOST']));
    curl_setopt($z, CURLOPT_URL,'https://bonsheryacademy.bitrix24.ru/crm/configs/import/lead.php');
    curl_setopt($z, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($z, CURLOPT_USERAGENT,'Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.16');
    curl_setopt($z, CURLOPT_CONNECTTIMEOUT, 30);//ожидание в секундах
    curl_setopt($z, CURLOPT_TIMEOUT, 30);//ожидание в секундах
    curl_setopt($z, CURLOPT_UNRESTRICTED_AUTH, 1);
    curl_setopt($z, CURLOPT_POST, 1);
    curl_setopt($z, CURLOPT_POSTFIELDS, $post_fields);

    //curl_setopt($z, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($z, CURLOPT_SSL_VERIFYHOST, 0);

    $ress=curl_exec($z);
    if($ress === false)
    {
        $err.= curl_error($z);
    }else{
        $err.= $ress;
    }
    // Закрываем дескриптор
    curl_close($z);

    //отправляем письмо
    $email = EmailSMTP::instance();
    $email->to(ADMIN_MAIL);
    $email->from(ADMIN_MAIL, 'Платежи');
    $email->subject("Платеж Я-Деньги ".$res['metadata']['user_name']);
    $bodymail = '<p>Поступил платеж '.$res['captured_at'].'</p>';
    $bodymail.= '<p>Номер транзакции: '.$res['authorization_details']['rrn'].'<br></p>';
    $bodymail.= '<p>Заказ: '.$res['description'].'</p>';
    $bodymail.= '<p>Начало обучения: '.$res['metadata']['d_start'].'</p>';
    $bodymail.= '<p>Окончание обучения: '.$res['metadata']['d_stop'].'</p>';
    $bodymail.= '<p>От: '.$res['metadata']['user_name'].'</p>';
    $bodymail.= '<p>email: '.$res['metadata']['email_work'].'</p>';
    $bodymail.= '<p>Телефон: '.$res['metadata']['phone_work'].'</p>';
    $bodymail.= '<p>Оплачено: '.$res['amount']['value'].'</p>';
    $email->body($bodymail);

    $err = $email->send();
    if ($err!=1){
        error_log('Payment mail send error', 1,'deiww@mail.ru');
    }
}

