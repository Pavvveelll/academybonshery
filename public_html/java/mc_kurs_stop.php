<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 15.08.2018
 * Time: 16:02
 *
 * Принимает $_GET
 * debug_1 - отладка
 * email - обязательный
 * user_ID - обязательный
 * FirstName - не фатальная ошибка
 * отписывает от БУДУЩИХ
 * подписывает в ГРУМЕРЫ
 * возвращает в Битрикс УДАЛОСЬ ли подписать
 * ошибки user_error
 * TODO передавать проживание
 * FIXME обрабатывать ошибки user_error в Битриксе
 */

include ("ms_kurs_set.php");//настройки списков

error_log($_SERVER['QUERY_STRING']);

$queryUrl = 'https://bonsheryacademy.bitrix24.ru/rest/10/q3o0p84119tz0axe/crm.contact.update.json'; // имя входящего вебхука в Битрикс24
$CRM_user_field_1 = 'UF_CRM_1533279337';              // поле в Битрикс в которое заносится статус подписки на Список 1
$CRM_user_field_2 = 'UF_CRM_1533279325';              // поле в Битрикс в которое заносится статус подписки на Список 2
$debug_1 = false;                                     // установить true если нужна отладочная информация на экран
$user_error = '';

if (isset($_GET['debug_1'])){//режим отладки через GET
    $debug_1 = true;
}

//проверки входщих значений
if (isset($_GET['email'])){
    $user_email = $_GET['email'];
}else{
    exit("Error. Parameter email are missing <br />");
}

if (isset($_GET['user_ID'])){
    $user_ID = $_GET['user_ID'];
}else{
    exit("Error. Parameter user_ID are missing <br />");
}

$fname='';
if (isset($_GET['FirstName']) && trim($_GET['FirstName'])!=''){
    $fname = $_GET['FirstName'];
}else{
    //TODO если без имени, или пустое вызывать ошибку в битриксе, ставит задачу
    exit("Error. Parameter FirstName are missing <br />");
    //$user_error.='ИМЯ не указано <br />';
}

$m4p = new MailchimpUse($api_key);

//TODO из поля битрикса с проживанием или нет, устанавливать поле Далеко из Москвы в MailChimp
$merge_fields=[
        'FNAME' => $fname,
        //'LNAME' => $lname,
        'MMERGE5' => 'yes',//выпускник
        'MMERGE6' => 'yes',//ветеринар
];
//проживание
//progiv Включено MMERGE2 Далеко от Боншери Москва и Подмосковье
if (isset($_GET['progiv'])){
    if($_GET['progiv']=='Включено'){
        $merge_fields['MMERGE2']='Далеко от Боншери';
    }else{
        $merge_fields['MMERGE2']='Москва и Подмосковье';
    }
}
error_log(print_r($merge_fields,true));


$result = $m4p->subscribe($list_id_2, $user_email, $merge_fields);// "title" Member Exists

// Пользователя успешно добавили в список 2
// присваиваем полю UF_CRM_1533279325 значение Y
$list_2_subscribed = "Y";
if($result['status']!='subscribed' && !(isset($result['title']) && $result['title']=='Member Exists')){
    //Произошла ошибка, готовим текст для Битрикса
    $user_error .= 'Ошибка'. $m4p->getLastError();
    $list_2_subscribed = "N";
}

//Отписываем от списка БУДУЩИХ
$m4p->unsubscribe($list_id_1, $user_email);

//БИТРИКС
// вызываем входящий вебхук, обновляем поля Контакт.UF_CRM_1533279337, UF_CRM_1533279325

$queryData = http_build_query(array(
    'fields' => array(
        //   "$CRM_user_field_1" => $list_1_subscribed,
        "$CRM_user_field_2" => $list_2_subscribed,
        "user_error" => $user_error,//FIXME обработать в битриксе //ПЕРЕДАЕМ ошибку, если есть

    ),
    'params' => array("REGISTER_SONET_EVENT" => "Y"),
    'ID' => $user_ID,
));
//print_r($m4p->getLastError());
//error_log();

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
