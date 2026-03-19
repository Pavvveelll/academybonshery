<?php
$pagenum = 0;
if (isset($_GET['pn'])) {
	$pagenum = intval($_GET['pn'])-1;
}
//print phpinfo();
$startrow= $pagenum * MAXROWS;//
$query="SELECT COUNT(*) FROM " . DB_PREFIX . "_".$items." p  WHERE " . $mode;
//print $query;
$sortarray= array("rank","item","popul");
if((isset($_GET['sort']))&&(in_array($_GET['sort'],$sortarray))){
	$sort ="p.". trim($_GET['sort']);
}else{
	$sort= " p.rank DESC";
}
$order =" ORDER BY $sort ";

$query_limit = sprintf("SELECT p.id, p.name, p.nik, p.look,  p.anons, COUNT( p2.id) as cid FROM %s_%s p LEFT JOIN %s_%s p2 ON p.id=p2.parent WHERE %s GROUP BY p.id %s LIMIT %d, %d"
 						, DB_PREFIX ,$items, DB_PREFIX,$items,$mode, $order, $startrow, MAXROWS);
//print $query_limit;

$all = mysql_query($query_limit) or die(mysql_error());

if (isset($_GET['tr'])) {
	$totalrows = $_GET['tr'];
} else {
	$total = mysql_query($query);
	$ttr = mysql_fetch_row($total);
	$totalrows=$ttr[0];
}
$totalpages = ceil($totalrows/MAXROWS)-1;

$nParams=$_GET;
unset($nParams['pn']);
$querystring=http_build_query($nParams);

//print $querystring;

//print $mode_text;
if($totalrows!=0){
?>
<div id="cat_list" >

<?php
$counter=	$pagenum*MAXROWS;
if(!isset($pp)) $pp='';//инициируем path
while(($rowe = mysql_fetch_assoc($all))!=false){
	$counter++;


	echo '<div style="float:left; margin:12px 5px 0 0; padding:3px; border:solid 1px #CCC " >';
	echo '<a href="/',$pp,$rowe['nik'],'/" target="_blank">';
	echo '<img width="16" height="16" border="0"'.(($rowe['look']!="yes")?(' title="не опубликовано" src="/admin/images/previev_none.gif" '):(' title="смотреть на сайте" src="/admin/images/previev.gif" ')).'>';
	echo '</a> ';
	echo '<a href="',$phpself ,'?id=', $rowe['id'],'"><img width="16" height="16" border="0" title="редактировать" src="/admin/images/edit_16.gif"></a> ';

	echo '<a href="',$phpself ,'?cat=', $rowe['id'],'"><img width="16" height="16" border="0" title="вложенные страницы" src="/admin/images/';
	if($rowe['cid']>0){
		echo 'kateg.gif';
	}else{
		echo 'kateg_none.gif';
	}
	echo '"></a>';
	$movem="<br /><img src='/admin/images/down_16.gif' width='16' height='16'  border='0' title='вниз'    style='cursor:pointer;'  onclick='moveit(\"down\",\"".$rowe['id']."\")' />
			<input name='s".$rowe['id']."' id='s".$rowe['id']."' type='text' value='1' size='1' style='text-align:center; width:20px;height:14px' />
			<img src='/admin/images/up_16.gif' width='16' height='16'  border='0'    title='вверх'    style='cursor:pointer' onclick='moveit(\"up\",\"".$rowe['id']."\")'/>";
	echo $movem;
	echo '</div >';
	//$servise_pages=array('glavnaya', 'rasprodaga', 'catalog', 'novinki', 'lider' );//служебные страницы

 	echo '<h2><a href="',$phpself ,'?cat=', $rowe['id'],'"';
	if(in_array($rowe['nik'], $servise_pages)){
		echo ' style="color:#999"';
	}
	echo '>', $counter.'.&nbsp;',$rowe['name'],'</a></h2>';
	if($rowe['anons']!="") print('<p>'.$rowe['anons'].'</p>');
 echo '<div class="clear"></div>';

}//while
?>
<form action="" method="post" name="moveform" id="moveform">
<input name="moveid" id="moveid" type="hidden" value="0" />
<input name="moveshift" id="moveshift" type="hidden" value="0" />
</form>
</div><br />
<?php
	if($totalpages>0){
		include(INCLUDE_PATH."listing.php");
	}

}//if($totalrows!=0){
?>
<div class="buttons">
<div class="g"><a href="<?php printf("%s?cat=%s&id=0",$phpself, $cat) ?>" >Добавить...</a></div>
</div>



