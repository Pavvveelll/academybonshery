
<?php

if($cat>0){
	echo '<div id="path">';
 	$path_array=$item->get_path_array($cat);
 // print_r($path_array);
	echo '<a href="',$phpself,'" >Страницы</a> ';
	foreach($path_array as $value){
		printf(" - <a href=\"%s?cat=%s\" >%s</a> ",$phpself,$value['id'],$value['name']);
	}
	echo '</div>';
}

// формируем путь к просмотру
$parr=$item->get_path_array($item->my_item['id']);
$pp="";
foreach($parr as $v){
	$pp.=$v['nik']."/";
}
$pp.=$item->my_item['nik']."/";

echo '<h1>';
echo '<a href="/',$pp ,'" target="_blank">';
echo '<img width="16" height="16" border="0"'.(($item->my_item['look']!="yes")?(' title="не опубликовано" src="/admin/images/previev_none.gif" '):(' title="смотреть на сайте" src="/admin/images/previev.gif" ')).'> ';
echo '</a>';
echo '<a href="',$phpself ,'?id=', $item->my_item['id'],'"><img width="16" height="16" border="0" title="редактировать" src="/admin/images/edit_16.gif"> ';
echo $item->my_item['name'],'</a></h1>';
if($item->my_item['anons']!="") print('<p>'.$item->my_item['anons'].'</p>'); 
?>
 
<p style="margin-top:15px; border-top:solid #CCC 1px; color:#999; width:940px">Вложенные страницы:</p>
