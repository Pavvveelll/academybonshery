<div  id="additemdiv">
<?php 
	echo '<div id="path">';
 	$path_array=$item->get_path_array($cat);
	echo '<a href="',$phpself,'" >Страницы</a> ';
	foreach($path_array as $value){
		printf(" - <a href=\"%s?cat=%s\" >%s</a> ",$phpself,$value['id'],$value['name']);
	}
	echo '</div>';
 
	//формируем ссылку на сайт	
	$ss_array=$item->get_path_seo_array($item->my_item['id']);
	$ss="/";
	foreach($ss_array as $v){
		$ss.=$v['nik']."/";
	}
	$ss.=$item->my_item['nik']."/";

    // превью
    $hclass = 'art__h1';
    if($item->my_item['look']=='no'){
        //$ss.='?modeviev=previev';
        $hclass .= 'art__h1_noviev';
    }

	if($item->my_item['id']>0){
		echo '<h1 class="'.$hclass.'"><a href="',$ss ,'" title="просмотр" target="_blank">',$item->my_item['name'],'</a></h1>';
	}else{
		echo '<h1 class="art__h1">Новая страница</h1>';
	}

if($item->error!=""){
?>
	<div id="error"><?php echo $item->error ?></div>
<?php 
}else{
	include_once(CLASS_PATH."function/html.function.php"); 
	$add_sourse=array();
	$add_sourse[0]="Материалы сайта";
	$get_full_tree=$item->get_full_tree(0,0,999,$item->my_item['id']);//($start=0,$stop=0, $maxlevel=99999, $nochield=false,$sort='name'
	//исключить из списка себя и дочерние категории
	foreach($get_full_tree as $v){
		$add_sourse[$v['id']]=str_repeat("&nbsp;&nbsp;&nbsp;",intval($v['l'])-1).$v['name'];
	}
	unset($get_full_tree);
	//print_r($add_sourse);
	$select_sourse[]=$add_sourse;
	/*$select_sourse[]=$info_cat_array;*/
	//$select_sourse[]=$reference_array;//список списков для формирования списков :)
	
	//
	print html_form($item->fields,$item->my_item,$select_sourse);
}//if($item->error!=""){
?> 
<p class="description">&nbsp;</p>
</div>