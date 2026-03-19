<?php
$mode=" ";
?>
<div id="path"><a href="maillist.php">Рассылки</a> - 
<?php 
printf("<a href='/admin/maillist.php?sustribers=%d'>Группа: %s</a></div>",$item->my_item['id_group'],$item->my_item['gname']) ;

if($error!=""){ ?>
	<div id="error"><?php echo $error ?></div>
<?php
}
//print($sustriber->my_item['id']);
?>
<h1  class="greentext"><?=$item->my_item['gname']?></h1>
<p><?=nl2br($item->my_item['gdes'])?></p>
<?php 
$query_limit = 	sprintf("SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(s.login, '@', -1),'.',1) AS e, 
						COUNT(SUBSTRING_INDEX(SUBSTRING_INDEX(s.login, '@', -1),'.',1)) AS c 
						FROM %s_sustribe s WHERE s.sgroup=%d  GROUP BY  e  ORDER BY c DESC",
						DB_PREFIX,$item->my_item['id_group']);//,$mode,$order, $startrow, $maxrowlistWHERE s.sgroup=%d %s %s LIMIT %d, %d
//  print $query_limit;

$post_array=array('mail'=>1,'list'=>1,'bk'=>1,'inbox'=>1, 'yandex'=>2, 'ya'=>2,'gmail'=>3 ,'rambler'=>4	);
$txt_array=array(1=>'Mail.ru',2=>'Яндекс-почта',3=>'Gmail',4=>'Rambler', 5=>'остальные');
$res_array=array();
$all = mysql_query($query_limit) or die(mysql_error());	
while(($row = mysql_fetch_assoc($all))!=false){
	if(isset($post_array[$row ['e']])){
		$res_array[$post_array[$row ['e']]]+=$row ['c'];
	}else{
		$res_array[5]+=$row ['c'];
	}	
}
// print_r($txt_array);
//  print_r($res_array);
if(count($res_array)>0){
	 $rez =array();
	 foreach($res_array as $k=>$v){
		$rez[$txt_array[$k]] =$v;
	 }
	//ДИАГРАММА 
?>
<div id="piechart" style="width: 620px; height: 450px;"></div>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
         
		 var data = google.visualization.arrayToDataTable([
          ['Сервер', 'Подписчики']
		<?php
			foreach($rez as $k=>$v){
				 printf( ",['%s',%d]", $k,$v);
			}  
		 ?>
        ]);     

        var options = {
          title: 'Почтовые сервера', is3D: true 

        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>
<?php 
 	 
}else{//if($totalrows!=0){
	?><p><br /><br />Данных нет.</p><?php
}
?>