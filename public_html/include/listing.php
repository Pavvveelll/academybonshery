
<?php //  при нажатии на стрелочку в самое начало или в самый конец 
//$querystring="&".substr($querystring,1);
 
print	 "<div class=\"listing\"> ";
	$vievpages=15;//максимум страниц 
	$vievpages2=floor($vievpages/2);
	$listing="";
	$endlisting="";
	if ($totalpages<$vievpages){//страниц меньше максимума
		$startpage=0;
		$finishpage=$totalpages;
	}else{
		//формируем последнюю стрелочку
		$endlisting .=sprintf("&nbsp;<span style=\"font-family:'ARIAL'; font-size: 14px;font-weight:bold\"><a href=\"%s?pn=%s%s\">&#8594;</a></span>",$phpself,$totalpages+1, (($querystring!="")?("&amp;".$querystring):("")));
		if ($vievpages2>$pagenum){
			//если текущая страница не дальше половины максимума от начала
			$startpage=0;
			$finishpage=$vievpages-1;
		}else{
			//текущая страница больше половины максимума стартуем не с нуля
			$startpage=$pagenum-$vievpages2 +1;
			$finishpage=$pagenum+$vievpages2;
			//формируем начальную стрелочку
			$listing.=sprintf("<span style=\"font-family:'ARIAL';font-size: 14px; font-weight:bold\"><a href=\"%s%s\">&#8592;</a></span>&nbsp;",$phpself, (($querystring!="")?("?".$querystring):("")));
			if($finishpage>=$totalpages){
				//если приблизились к концу
				$finishpage=$totalpages;
				$startpage=$totalpages-$vievpages+1;
				$endlisting="";//последняя стрелочка не нужна
			}
		}
	}
	 for($i=$startpage;$i<=$finishpage; $i++){
	 	if($i==$pagenum){//выделяем текущую
			$listing.="&nbsp;<div class=\"listing_active\">".($i+1)."</div>&nbsp;";
		}else{
			if($i==0){
				$listing.=sprintf("&nbsp;<a href=\"%s%s\">%s</a>&nbsp;",$phpself,(($querystring!="")?("?".$querystring):("")),$i+1);
			}else{
				$listing.=sprintf("&nbsp;<a href=\"%s?pn=%s%s\">%s</a>&nbsp;",$phpself,$i+1, (($querystring!="")?("&amp;".$querystring):("")),$i+1);
			}
		}
	 }
	 print substr($listing,0,-1) . $endlisting;
 print	 "</div>";
?>