<?php
$res='no';
if(isset($_POST['parent']) && isset($_POST['id'])){

	$parent=intval($_POST['parent']);
	$id=intval($_POST['id']);
	
	require_once("../class/common.php");
	$mode=" look='yes'  AND tlist<>'' ";
	$order=" ORDER BY rank DESC";
	$table=DB_PREFIX."_page";

	//загружаем данные на парент
	$parent_item=new catalog();	
	$parent_item->table=$table;
	$parent_item->load($parent, $table);
	
	$ppatch_array=$parent_item->get_path_seo_array();
	$ppatch_url="/";
	foreach($ppatch_array as $pat){
		$ppatch_url.=sprintf("%s/",$pat['nik']);
	}
	$ppatch_url.=$parent_item->my_item['nik']."/";
		
	$sqly = sprintf("SELECT id,title,tlist,nik,name,foto FROM %s WHERE parent=%d AND %s %s ", $table,$parent,$mode,$order) ;
	//  print($sqly);
	$ally = mysql_query($sqly) or error_log(mysql_error());
	if(mysql_num_rows($ally)>1){		
			$yar=array();
			$allt=array();
			while(($y = mysql_fetch_assoc($ally))!=false){
				$yar[]=$y['id'];
				if($y['tlist']=='') $y['tlist']=$y['title'];
				$allt[]=$y;
			}
			
			$yar_k=array_search($id,$yar);
			$n_img="";
			if (array_key_exists($yar_k+1, $yar)) {
				$n_link=sprintf("%s%s/", $ppatch_url, $allt[$yar_k+1]['nik']);
				if($allt[$yar_k+1]['foto']){
					$n_img=sprintf('<img src="/picture/foto%d.%s" alt="%s" border="0" />',$allt[$yar_k+1]['id'],$allt[$yar_k+1]['foto'], $allt[$yar_k+1]['name']);
				}
				$n_title=$allt[$yar_k+1]['tlist'];
			}else{
				$n_link=$ppatch_url;
				if($parent_item->my_item['foto']){
					$n_img=sprintf('<img src="/picture/foto%d.%s" alt="%s" border="0" />',$parent_item->my_item['id'],$parent_item->my_item['foto'],$parent_item->my_item['name']);
				}				
				$n_title=(($parent_item->my_item['tlist']!="")?($parent_item->my_item['tlist']):($parent_item->my_item['title']));
			}
			$p_img="";
			if (array_key_exists($yar_k-1, $yar)) {
				$p_link=sprintf("%s%s/", $ppatch_url, $allt[$yar_k-1]['nik']);
				if($allt[$yar_k-1]['foto']){
					$p_img=sprintf('<img src="/picture/foto%d.%s" alt="%s" border="0" />',$allt[$yar_k-1]['id'],$allt[$yar_k-1]['foto'], $allt[$yar_k-1]['name']);
				}
				$p_title=$allt[$yar_k-1]['tlist'];
			}else{
				$p_link=$ppatch_url;
				if($parent_item->my_item['foto']){
					$p_img=sprintf('<img src="/picture/foto%d.%s" alt="%s" border="0" />',$parent_item->my_item['id'],$parent_item->my_item['foto'],$parent_item->my_item['name']);
				}				
				$p_title=(($parent_item->my_item['tlist']!="")?($parent_item->my_item['tlist']):($parent_item->my_item['title']));
			}
			mysql_free_result($ally);
			
			$res='<div id="tdsd">';
        	$res.='<div class="tdl"><a href="'.$n_link.'">';
			if($n_img){
				$res.= $n_img;
			}
        	$res.= $n_title.'</a></div>';

        	$res.='<div class="tdr"><a href="'.$p_link.'">';
			if($p_img){
				$res.= $p_img;
			}
        	$res.= $p_title.'</a></div>';

			$res.= '</div>';

//        $res='<table cellspacing="5" id="tdsd"><tr>';
//        if($n_img){
//            $res.=sprintf('<td width="130" class="tdl"><a href="%s">%s</a></td>',$n_link,$n_img);
//        }else{
//            $res.='<td width="1" class="tdl">&nbsp;</td>';
//        }
//        $res.=sprintf('<td align="left"><p><a href="%s">%s</a></p></td>',$n_link,$n_title);
//
//        $res.=sprintf('<td align="right"><p><a href="%s">%s</a></p></td>',$p_link,$p_title);
//        if($p_img){
//            $res.=sprintf('<td width="130" class="tdr"><a href="%s">%s</a></td>',$p_link,$p_img);
//        }else{
//            $res.='<td width="1" class="tdr">&nbsp;</td>';
//        }
//
//        $res.= '</tr></table>';


	}//if(mysql_num_rows($ally)>1){
	
	
			
}
echo  $res ;//отправляем для ajax
?>
