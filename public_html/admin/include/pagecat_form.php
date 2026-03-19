<div  id="additemdiv">
<?php
if($catalog->error!=""){
?>
<div id="error"><?=$catalog->error ?></div><br />
<?php 
}
//print_r($catalog);
print ($catalog->my_item['action_item']=="edit")?("<h1>Редактирование категории.</h1>"):("<h1>Добавление категории.</h1>");
include_once(CLASS_PATH."function/html.function.php"); 
//
$add_sourse=array();
if($cat_edit==1){//ГОЛОВНАЯ КАТЕГОРИЯ
	$catalog->fields['parent']['viev']="hidden";
	$catalog->my_item['parent']=0;
	print "<h2>Это корневая категория</h2>";
/*	print_r($catalog->fields['parent']);
	print_r($catalog->my_item['parent']);
	
    [name] => parent
    [text] => <strong>Категория</strong>

    [format] => int
    [viev] => label
    [default] => 0*/
}else{
	$get_full_tree=$catalog->get_full_tree(0,0,999,$cat_edit);
	//исключить из списка себя и дочерние категории
	foreach($get_full_tree as $v){
		$add_sourse[$v['id']]=str_repeat("&nbsp;&nbsp;&nbsp;",intval($v['l'])-1).$v['name'];
	}
	unset($get_full_tree);
}
$select_sourse[]=$add_sourse;
//print_r( $select_sourse);
print html_form($catalog->fields,$catalog->my_item,$select_sourse);


?>
</div>