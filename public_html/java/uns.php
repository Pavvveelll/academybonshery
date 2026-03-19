<?php 
//error_reporting(-1);

require_once("../class/common.php");
require_once("../class/Mailchimp.php");
//
$mch=new Mailchimp('11b24623a085eb18ed372b596f2694c6-us9');

/// unsubscribe($id, $email, $delete_member=false, $send_goodbye=true, $send_notify=true) {

try {
	$mch->lists->unsubscribe('84de7c7eec',array('euid'=>$_GET['e']),true, false,false);
} catch (Exception $e) {
    //echo 'Caught exception: ',  $e->getMessage(), "\n";
}

echo 'Вы отписаны от рассылки.';

//echo '<pre>';
//print_r(   );
//echo '</pre>';

////$mchlist=new Mailchimp_Lists($mch);
//$u=array(array('euid'=>'821782367bz'));//  *|EMAIL_UID|*
////echo '<pre>';
//$res=$mch->lists->memberInfo('84de7c7eec',$u);
//if(isset($res['data'][0]['merges']['GROUPINGS'][0]['groups'])){
//	foreach($res['data'][0]['merges']['GROUPINGS'][0]['groups'] as $v){
//		if(stristr($v['name'], 'курс') !== FALSE && $v['interested']=='true') {//
//			echo $v['name'];
//		}
//		if(stristr($v['name'], 'класс') !== FALSE && $v['interested']=='true') {//
//			echo $v['name'];
//		}	 
//		
//	}
//}
//var_dump($res['data'][0]['merges']['GROUPINGS'][0]['groups']  );//821782367b 
//echo '</pre>';
// phpinfo()


?>