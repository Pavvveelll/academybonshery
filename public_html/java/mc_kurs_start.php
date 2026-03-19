<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 15.08.2018
 * Time: 15:07
 * Принимает $_GET
 * debug_1 - отладка
 * email - обязательный
 * user_ID - обязательный
 *
 * Отписываем из списка БУДУЩИХ Грумеров
 * Возвращаем в Битрикс Был ли он вообще подписан
 *
 */
error_log($_SERVER['QUERY_STRING']);


include ("ms_kurs_set.php");//настройки списков

$queryUrl = 'https://bonsheryacademy.bitrix24.ru/rest/10/q3o0p84119tz0axe/crm.contact.update.json'; // имя входящего вебхука в Битрикс24
$CRM_user_field_1 = 'UF_CRM_1533279337';              // поле в Битрикс в которое заносится статус подписки на Список 1
$CRM_user_field_2 = 'UF_CRM_1533279325';              // поле в Битрикс в которое заносится статус подписки на Список 2
$debug_1 = false;                                     // установить true если нужна отладочная информация на экран

if (isset($_GET['debug_1'])){//режим отладки через GET
    $debug_1 = true;
}
//проверки входщих значений
if (isset($_GET['email'])){
    $user_email = $_GET['email'];
}else{
    exit("Error. Parameter email are missing <br />");
}
error_log($user_email);

if (isset($_GET['user_ID'])){
    $user_ID = $_GET['user_ID'];
}else{
    exit("Error. Parameter user_ID are missing <br />");
}

$m4p = new MailchimpUse($api_key);

//$status = $m4p->get_user_info($list_id_1, $user_email); //TODO передавать в битрикс дату подписки
$status = $m4p->get_user_status($list_id_1, $user_email);

$list_1_subscribed = "N";//для битрикса
if ($status=='subscribed'){
    $list_1_subscribed = "Y";
    //отписываем
    $m4p->unsubscribe($list_id_1, $user_email);
}
//print_r($status);

//БИТРИКС
// вызываем входящий вебхук, обновляем поля Контакт.UF_CRM_1533279337

$queryData = http_build_query(array(
    'fields' => array(
        "$CRM_user_field_1" => $list_1_subscribed,
    ),
    'params' => array("REGISTER_SONET_EVENT" => "Y"),
    'ID' => $user_ID,
));

if ($debug_1) {
    echo "<br />Start import webhook $queryUrl <br />";
    var_dump($queryData);
}

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl,
    CURLOPT_POSTFIELDS => $queryData,
));

$result = curl_exec($curl);

if ($debug_1) {
    echo "<br />Results of import webhook $queryUrl <br />";
    var_dump($result);
}

curl_close($curl);
