<?php
 if(isset($_GET['mode'])){
	 $get_mode=$_GET['mode'];
 }else{
	 $get_mode="";
 }
switch ($get_mode) {
	case "active":
	   $mode_text=" - к рассылке</h1>";
	   $mode="AND a.stat='active'";
		break;
	case "finish":
	   $mode_text=" - разосланные";
	   $mode="AND a.stat='finish'";
		break;		
	default:
		$mode_text="";
		$mode=" ";
}
 
?>
<div id="path"><a href="maillist.php">Рассылки</a> - <?php 
	printf("<a href='/admin/maillist.php?id=%d'>%s</a>",$item->my_item['id'],$item->my_item['subject']) 
?> - <?php 
	printf("<a href='/admin/maillist.php?list=%d'>Адреса</a>%s",$item->my_item['id'],$mode_text ) 
?></div>Статус рассылки:
<?php
	print $stat_arr[$item->my_item['stat']]
?>                                        
<?php if($error!=""){ ?>
	<div id="error"><?php echo $error ?></div>
    
<?php
}
?> 
<h1 class="greentext"  >Статистика</h1>
<?php 
$query_limit = 	sprintf("SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(s.email, '@', -1),'.',1) AS e, 
						COUNT(SUBSTRING_INDEX(SUBSTRING_INDEX(s.email, '@', -1),'.',1)) AS c 
						FROM %s_mailadres s WHERE s.id_maillist=%d  GROUP BY  e  ORDER BY c DESC",
						DB_PREFIX,$item->my_item['id']);//,$mode,$order, $startrow, $maxrowlistWHERE s.sgroup=%d %s %s LIMIT %d, %d
//print $query_limit;

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
?>
   <div>информации нет</div>    
<?php
}
?>



