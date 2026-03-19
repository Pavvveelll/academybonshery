<div id="path">
<?php
/*	$path_array=$item->get_path_array($cat);
	//print_r($path_array);
	foreach($path_array as $value){
		printf("<a href=\"%s?cat=%s\" >%s</a> - ",$phpself,$value['id'],$value['name']);
	}*/
	printf("<a href=\"%s?cat=%s\" >%s</a>", $phpself,$cat,$item->my_item['name']);
?>
</div>
<div class="zag" >
	<div class="cat_box_photo">
	<?
	if($cat_full->my_item['icon']!=false)
		print "<p><img src=\"/picture/icon" . $cat . "." . $cat_full->my_item['icon'] . "\" alt=\"".$cat_full->my_item['name']."\" /></p>";
	?>
	</div><!--cat_box_photo-->
    <h1><a href="<?php printf("%s?id=cat%s",$phpself,$cat_full->my_item['id']) ?>" >
      <?=$cat_full->my_item['name'] ?>
    </a>(<?=$cat_full->my_item['items'] ?>)</h1>
</div><!--zag-->
<div>
<p class="description"> 
<?php 
  $subs_array=$item->get_sub_array($id);
  if (sizeof($subs_array)!=0) {
  	$subs="";
  	foreach($subs_array as $key=>$value){
		$subs .=sprintf("<a href=\"%s?cat=%s\">%s (%s)</a>, ",$phpself, $value['id'],$value['name'],$value['items']);
	}
	print(substr($subs,0,-2));
  }else {
  	print("подкатегории не определены");
  }
  ?><br /><br />
<a href="<?php printf("%s?cat=%s&id=cat0",$phpself, $cat) ?>" >Добавить подкатегорию...</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?vievfull=yes">Все категории...</a> </p>
</div>