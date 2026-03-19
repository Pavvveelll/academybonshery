

<?php 
 
	if($item->error!=""){
	?>
<div id="error" style="background-color:#FF0">ВНИМАНИЕ!!!! <?php echo $item->error ?></div><br />
	<?php 
	}
	include_once(CLASS_PATH."function/html.function.php");
//	if((isset($auth)&&($auth->is_loged==true)&&($auth_user[3]!='temp'))){
//		$item->my_item['sender_name']=": ".$auth_user[1];
//		$item->my_item['login']=" ".$auth_user[2];
//	}
	///print_r($item->my_item);
	$select_sourse[]=array('submit'=>'Позвоните мне','saveimg'=>'enter.gif');
	
	$template="<tr ><td align=\"right\" valign=\"top\"  >%s</td><td valign=\"top\" width=\"180px\">%s</td></tr>";
	print html_form($item->fields,$item->my_item,$select_sourse,$template,$form_name );
// function html_form($fields,$item,$select_sourse=array(),$template="",$form_name='form_item'){
?> 


