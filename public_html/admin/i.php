<?php 
//error_reporting(-1);
//require_once("../class/Mailchimp.php");
//
//$mch=new Mailchimp('11b24623a085eb18ed372b596f2694c6-us9');
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
var_dump( date('Y m d H'));
 phpinfo()


?>