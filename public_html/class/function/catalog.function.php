<?php 
/*	function calc_path($cat=1){
	//table
	//обновляем все пути
	//обновляем свой патч
	$parent=$this->get_parent($cat);
	$path=$this->get_path($parent);
	if($parent!=0){
		$path.=($path!=false)?(",".strval($parent)):(strval($parent));
	}
	$updatesql=sprintf("UPDATE %s SET path=%s WHERE id=%d ",  $this->table,$this->db->gvs($path, "text"),$cat);
	//print $updatesql;
	$this->db->query($updatesql) or die(mysql_error());
	//ищем у кого парент как кат
	$sql=sprintf("SELECT id FROM %s WHERE parent=%d ", $this->table,  $cat);
	$this->db->query($sql) or die(mysql_error());
	$cur_cats=array();
	while(($row=$this->db->get_results())!=false){
	///все в массив чтобы освободить db
		$cur_cats[]=$row['id'];
	}
	$this->db->db_free();
	foreach($cur_cats as $v){/// рекурсивно перебираем каждую ветвь
		$this->refresh_path($v);
	}
}*/


function calc_cat($entity,$target,$items,$cat=1){//cat,id_cat,st_items
	if($cat!=1){//корень не учитываем
		$sql=sprintf("SELECT COUNT(*) as co FROM %s WHERE %s=%d AND look='yes' ",  $items, $target, $cat);
		// print $sql. "<br />";
		$res=mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_assoc($res);
		$cur_co=$row['co'];
		mysql_free_result($res);	
	}else{
		$cur_co=0;//корень не учитываем
	}
	//print $cur_co. "<br />";
	//дочки
	$sql=sprintf("SELECT id FROM %s_%s WHERE parent=%d ", DB_PREFIX,$entity, $cat);
	
	$res=mysql_query($sql) or die(mysql_error());
	$cur_cats=array();
	while(($row=mysql_fetch_assoc($res))!=false){
	///все в массив чтобы освободить db
		$cur_cats[]=$row['id'];
	}
	mysql_free_result($res);	
	//print_r($cur_cats);
	foreach($cur_cats as $v){///перебираем каждую ветвь
		$cur_co+=calc_cat($entity,$target,$items,$v);
	}
	$updatesql=sprintf("UPDATE %s_%s SET items=%d WHERE id=%d ", DB_PREFIX,$entity,$cur_co,$cat);
	 //print($updatesql. "<br />");
	mysql_query($updatesql) or die(mysql_error());
	return $cur_co;
}
	
function calc_cat2($entity, $cat=1,$action='all'){
print $action;
//print_r( func_get_args());
	//в 2.0 считаем и детские итемы
		switch ($action) {
		case 'plus':
			if($cat!=1){//корень не пересчитываем
				//обновляем всех предков
				$sql=sprintf("SELECT id, path FROM %s_%s WHERE id=%d LIMIT 1", DB_PREFIX,$entity,$cat);
				$res=mysql_query($sql) or die(mysql_error());
				while(($row=mysql_fetch_assoc($res))!=false){
					$updatecats=$row['path'] . "," . $row['id'];
				}
				mysql_free_result($res);	
				$updatesql=sprintf("UPDATE %s_%s SET items=items+1 WHERE id IN (%s)", DB_PREFIX,$entity, $updatecats);
				//print $updatesql. "<br />";
				mysql_query($updatesql) or die(mysql_error());
			}
			break;
		case 'minus':
			if($cat!=1){//корень не пересчитываем
				$sql=sprintf("SELECT id, path FROM %s_%s WHERE id=%d LIMIT 1",DB_PREFIX,$entity,$cat);
				print ($sql). "<br />";
				$res=mysql_query($sql) or die(mysql_error());
				while(($row=mysql_fetch_assoc($res))!=false){
					$updatecats=$row['path'] . "," . $row['id'];
				}
				mysql_free_result($res);
				$updatesql=sprintf("UPDATE %s_%s SET items=items-1 WHERE id IN (%s)", DB_PREFIX,$entity, $updatecats);
				print $updatesql. "<br />";
				mysql_query($updatesql) or die(mysql_error());
			}
			break;
		default://вызов функции без аргументов пересчитывает все
		//просчет ресурсов в категории включая детей
		//перебираем все значения
		//на первом ищем собственные итемы
		//потом ищем всех потомков
			if($cat!=1){//корень не учитываем
				$sql=sprintf("SELECT COUNT(*) as co FROM %s_%s_items WHERE id_cat=%d AND look='yes' ", DB_PREFIX, $entity, $cat);
				//print $sql. "<br />";
				$res=mysql_query($sql) or die(mysql_error());
				$row=mysql_fetch_assoc($res);
				$cur_co=$row['co'];
				mysql_free_result($res);	
			}else{
				$cur_co=0;//корень не учитываем
			}
			$sql=sprintf("SELECT id FROM %s_%s WHERE parent=%d ", DB_PREFIX,$entity, $cat);
			$res=mysql_query($sql) or die(mysql_error());
			$cur_cats=array();
			while(($row=mysql_fetch_assoc($res))!=false){
			///все в массив чтобы освободить db
				$cur_cats[]=$row['id'];
			}
			mysql_free_result($res);	
			//print_r($cur_cats);
			foreach($cur_cats as $v){///перебираем каждую ветвь
				$cur_co+=calc_cat($entity,$v);
			}
			$updatesql=sprintf("UPDATE %s_%s SET items=%d WHERE id=%d ", DB_PREFIX,$entity,$cur_co,$cat);
			//print($updatesql);
			mysql_query($updatesql) or die(mysql_error());
			return $cur_co;
			break;
		}
}
	

?>