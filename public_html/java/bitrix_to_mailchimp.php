<?php

$user_email = $_GET['email'];                 // получить e-mail
$FirstName = $_GET['FirstName'];              // получить Имя
$LastName = $_GET['LastName'];                // получить Фамилию
$user_ID = $_GET['user_ID'];                  // получить Идентификатор контакта

// значения для настройки

$list_id_1 = 'b797dcf1fd';                            // будущих грумеров
$list_id_2 = '84de7c7eec';                            // для грумеров
$api_key = '11b24623a085eb18ed372b596f2694c6-us9';   // указать ключ API
$queryUrl = 'https://bonsheryacademy.bitrix24.ru/rest/16/e1zgmgcvjxef2sjg/crm.contact.update.json'; // имя входящего вебхука в Битрикс24
$CRM_user_field_1 = 'UF_CRM_1533279325';
$CRM_user_field_2 = 'UF_CRM_1533279337';
$debug_1 = false;

$data_center = substr($api_key,strpos($api_key,'-')+1);// получить имя сервера mailchimp

if ($debug_1){
  echo "========= start ========= <br />";
  echo "ID = $user_ID <br />";
  echo "Имя = $FirstName <br />";
  echo "Фамилия = $LastName <br />";
  echo "EMail = $user_email <br />";
}
// функция проверки наличия пользователя в списке рассылки

function is_subscribed($email, $debug, $apikey, $listid, $server) {
   
    if ($debug) {
      echo '<br />FUNCTION is_subscribed <br />';
      echo "for user = $email and list = $listid <br />";
    }
  
    $userid = md5($email);
    $auth = base64_encode( 'user:'. $apikey );
    $data = array(
        'apikey'        => $apikey,
        'email_address' => $email
        );
    $json_data = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://'.$server.'.api.mailchimp.com/3.0/lists/'.$listid.'/members/' . $userid);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
        'Authorization: Basic '. $auth));
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    $result = curl_exec($ch);

    if ($debug) {
   		var_dump($result);
    }
   
    $json = json_decode($result);

    if ($debug) { 
      echo "<br />";
      echo $json->{'status'};
      echo "<br />";
    }

    if($json->{"status"} == "404") return false; // пользователя НЕТ в списке
    return true;                                 // пользователь ЕСТЬ в списке
}

// функция добавления пользователя в список рассылки

function mc_subscribe($email, $fname, $lname, $apikey, $listid, $server, $debug) {
  $auth = base64_encode( 'user:'.$apikey );
	$data = array(
		'apikey'        => $apikey,
		'email_address' => $email,
		'status'        => 'subscribed',
		'merge_fields'  => array(
			'FNAME' => $fname,
      'LNAME' => $lname
			)
		);
  if ($debug) {
      echo '<br />FUNCTION mc_subscribe<br />';
      echo "<br />EMail $email , FirstName $fname, LastName $lname <br />";
      echo "List ID: $listid <br />";
  }

	$json_data = json_encode($data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://'.$server.'.api.mailchimp.com/3.0/lists/'.$listid.'/members/');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		'Authorization: Basic '.$auth));
	curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
	$result = curl_exec($ch);

	if ($debug) {
		var_dump($result);
	}

  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if (($status_code == "200") or ($status_code == "400")) return true;  // пользователь успешно добавлен в список или он там уже есть
  return false;                                                         // ошибка при добавлении пользователя в список
}

function mc_delete($email, $apikey, $listid, $server, $debug) {
  // функция удаляет пользователя из списка рассылки

  $url = 'https://'. $server .'.api.mailchimp.com/3.0/lists/'. $listid .'/members/'. md5(strtolower($email));

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apikey);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);

  if ($debug) {
    var_dump($result);
  }

}

function mc_unsubscribe($email, $apikey, $listid, $server, $debug) {
  // функция отписывает пользователя из списка рассылки

    $userid = md5($email);
  	$auth = base64_encode( 'user:'. $apikey );
  	$data = array(
        'apikey'        => $apikey,
        'email_address' => $email,
        'status' => "unsubscribed"
  		);

    if ($debug) {
        echo '<br />Mailchimp executed mc_unsubscribe<br />';
        echo "<br />EMail $email <br />";
        echo "List ID: $listid <br />";
        echo 'https://'. $server .'.api.mailchimp.com/3.0/lists/'. $listid .'/members/' . $userid;
        echo "<br />";
    }

    $json_data = json_encode($data);

  	$ch = curl_init();
  	curl_setopt($ch, CURLOPT_URL, 'https://'. $server .'.api.mailchimp.com/3.0/lists/'. $listid .'/members/' . $userid);
  	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
  		'Authorization: Basic '. $auth));
  	curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  	curl_setopt($ch, CURLOPT_POST, true);
  	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
  	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

    $result = curl_exec($ch);

    if ($debug) {
      var_dump($result);
    }
}

// основная логика скрипта

// сначала добавляем пользователя в список 2
if   (mc_subscribe ($user_email, $FirstName, $LastName, $api_key, $list_id_2, $data_center, $debug_1))
{
  // Пользователя успешно добавили в список 2
  // присваиваем полю UF_CRM_1533279337 значение Y
  $list_2_subscribed = "Y";
}
else
{
  // присваиваем полю UF_CRM_1533279337 значение N
  $list_2_subscribed = "N";
}

// Теперь проверяем есть ли пользователь в списке 1

if (is_subscribed($user_email, $debug_1, $api_key, $list_id_1, $data_center))
{
  // пользователь есть в $list_id_1
  // УДАЛЯТЬ ЕГО ИЗ ПЕРВОГО СПИСКА? УДАЛЯЕМ его из list_id_1
  mc_delete ($user_email, $api_key, $list_id_1, $data_center, $debug_1);
  $list_1_subscribed = "Y";
}
else 
{ 
  $list_1_subscribed = "N";
}
// вызываем входящий вебхук, обновляем поля Контакт.UF_CRM_1533279325, UF_CRM_1533279337


 $queryData = http_build_query(array(
  'fields' => array(
    "$CRM_user_field_1" => $list_1_subscribed,
    "$CRM_user_field_2" => $list_2_subscribed,
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


