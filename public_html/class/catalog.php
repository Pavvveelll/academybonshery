<?php 
class catalog extends item{
	function action($action, $data=""){
		if($action=="delete_item"){//при удалении категории переносим итемы в верхний уровень
			$del_id=intval($_POST['id']);///print($del_id);
			///print_r($this);
			$parent=$this->get_value('parent', $del_id);
			//print($parent); 
			$this->move_to_cat($del_id,$parent);//переносим итемы
			$this->move_cat_to_cat($del_id,$parent);//переносим подкатегории
			$this->refresh_path();//обновляем все пути
			//TODO Это не работает
			//$this->calculate_all();
			//calc_cat($entity,$target,$items,$cat=1)
			//cat,id_cat,st_items

				//$entity - корень таблиы итемов нр product
	//$target - корень таблиы категории назначения
	//$target_field - поле таблиы категории назначения в которое записывается результат
		}
		//$old_parent=$this->get_value('parent', intval($_POST['id']));
	 	parent::action($action);
		//print_r($this->my_item);
		if(!strstr($action,"delete_")){
			//дополнительно сохраняем пути
			$parent=$this->my_item['parent'];
			$path=$this->get_path($parent);
			$path.=($path!=null)?(",".strval($parent)):(strval($parent));
			$sql=sprintf("UPDATE %s SET path=%s WHERE id=%s", 
					$this->table,
					gvs($path, "text"),
					$this->my_item['key']);
				//	print $sql; 
			mysql_query($sql) or die(mysql_error());
			
		}
		include_once(CLASS_PATH."/function/catalog.function.php");
		//print("<br />entity=".$this->entity);
		//print("<br />items_key=".$this->items_key);
		//print("<br />items_table=".$this->items_table);
		 calc_cat($this->entity,$this->items_key,DB_PREFIX."_".$this->items_table);
	}
	
	function move_to_cat($from, $to){
		//используется при массовом перемещении например при удалении подкатегории
		$sql=sprintf("UPDATE %s_%s SET %s=%d WHERE %s=%d", 
		DB_PREFIX,$this->items_table,
		$this->items_key,
		gvs($to, "int"), 
		$this->items_key,
		gvs($from, "int"));
		mysql_query($sql) or die(mysql_error());
		//print $this->entity;
		include_once(CLASS_PATH."/function/catalog.function.php");
		calc_cat($this->entity,$this->items_key,DB_PREFIX."_".$this->items_table);
		
	}
	
	function move_cat_to_cat($from, $to){
		//используется при перемещении например при удалении подкатегории
		//выбираем и пересчитываем пути
/*		$sql=sprintf("SELECT id FROM %s WHERE 'parent'=%d",
				$this->table,  
	    		$from);
		$res=mysql_query($sql) or die(mysql_error());
		$new_path_array=array();
		while(($row=mysql_fetch_assoc($res))!=false){
			$new_path_array[]=$row;
		}
		mysql_free_result($res);*/
		////////////////////
		$sql=sprintf("UPDATE %s SET parent=%d WHERE parent=%d", 
			$this->table,
			gvs($to, "int"), 
			gvs($from, "int"));
			//print $sql;
		mysql_query($sql) or die(mysql_error());
		////////////
		include_once(CLASS_PATH."/function/catalog.function.php");
		calc_cat($this->entity,$this->items_key,DB_PREFIX."_".$this->items_table);
	}
	
	function refresh_path($cat=1){//обновляем все пути
		//обновляем свой патч
		$parent=$this->get_value('parent', $cat);
		$path=$this->get_path($parent);
		if($parent!=0){
			$path.=($path!=false)?(",".strval($parent)):(strval($parent));
		}
		$updatesql=sprintf("UPDATE %s SET path=%s WHERE id=%d ",  
			$this->table,
			gvs($path, "text"),
			$cat);
		//print $updatesql;
		mysql_query($updatesql) or die(mysql_error());
		//ищем у кого парент как кат
		$sql=sprintf("SELECT id FROM %s WHERE parent=%d ", $this->table,  $cat);
		$res=mysql_query($sql) or die(mysql_error());
		$cur_cats=array();
		while(($row=mysql_fetch_assoc($res))!=false){
			//все в массив чтобы освободить db
			$cur_cats[]=$row['id'];
		}
		mysql_free_result($res);
		foreach($cur_cats as $v){/// рекурсивно перебираем каждую ветвь
			$this->refresh_path($v);
		}
	}

	function get_path($id){
		$sql=sprintf("SELECT path FROM %s WHERE id=%d LIMIT 1",
				$this->table,  
	    		gvs($id, "int"));
				
	    		$res=mysql_query($sql) or die(mysql_error());
	    		if(mysql_num_rows($res)>0){					
					$row=mysql_fetch_assoc($res);
	    			$path= $row['path'];
	    		}else {
	    			$path= false;
	    		}
	    		mysql_free_result($res);
	    		return $path;
	}
	function get_path_array($id=0){
		if($id==0){//по умолчанию текущий
			$id=$this->my_item['id'];
		}
		$path=$this->get_path($id);
		//print $id;
		$path_array=array();
		if ($path!=false) {
			$sql=sprintf("SELECT id, nik, name FROM %s WHERE id IN (%s) ORDER BY path",$this->table, $path);
			$res=mysql_query($sql) or die(mysql_error());
			while(($row=mysql_fetch_assoc($res))!=false){
				$path_array[]=$row;
			}
			mysql_free_result($res);
		}
		return $path_array;
	}
	function get_path_seo_array($id=0){
		if($id==0){//по умолчанию текущий
			$id=$this->my_item['id'];
		}
		$path=$this->get_path($id);
		
		// print $path;
		$path_array=array();
		if ($path!=false) {
			$sql=sprintf("SELECT id,nik,name FROM %s WHERE id IN (%s) ORDER BY path",$this->table, $path);
			//print($sql);
			$res=mysql_query($sql) or die(mysql_error());
			while(($row=mysql_fetch_assoc($res))!=false){
				//пропускаем заглавный каталог
/*				if($row['id']>1){
					if($row['nik']!="")
						$row['id']=$row['nik'];
					//	print_r($row);
					
				}*/
				$path_array[]=$row;
			}
			mysql_free_result($res);
		}
		return $path_array;
	}
	
	
	function get_level_name_from_path($id, $level){
		$path_array=$this->get_path_array($id);
		$path_array[]=array("id"=>$id, "name"=>$this->get_value("name",$id));
		if(isset($path_array[$level]['name'])){
			return $path_array[$level]['name'];
		}else{
			return "";
		}
	}
	
	function get_sub_array($id=0){
		if($id==0){//по умолчанию текущий
			$id=$this->my_item['id'];
		}
		//возвращает массив подкатегорий
		$sub_cats=array();
		$sql="SELECT id, name,nik, items FROM " . $this->table . " WHERE parent=$id ORDER BY name";
		$res=mysql_query($sql) or die(mysql_error());
		while(($row=mysql_fetch_assoc($res))!=false){
			$sub_cats[]=$row;
		}
		mysql_free_result($res);
		return $sub_cats;
	}
	
	function get_children_array($id){
		//возвращает массив вида 2,4,66,5
		//который содерщит все дочерние категории этой категории
		$sql="SELECT id FROM " . $this->table . " WHERE parent=$id";
		 //print($sql);
		$res=mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($res)>0){
			while(($row=mysql_fetch_assoc($res))!=false){
				$cur_array[]=$row['id'];
			}
			mysql_free_result($res);
			foreach($cur_array as $v){
				//print $this->get_current_children($v);
				$childs=$this->get_children_array($v);
				if($childs){
					$cur_array=array_merge($cur_array,$childs);
				}
			}
			return $cur_array;
		}else{
			mysql_free_result($res);
			return false;
		}
	}
	
	function get_children($id=0){
		if($id==0){//по умолчанию текущий
			$id=$this->my_item['id'];
		}
		//возвращает строку вида 2,4,66,5
		//который содерщит все дочерние категории этой категории
		//print_r($this->get_children_array($id));
		$res=$this->get_children_array($id);
		if($res){
			return implode(",", $this->get_children_array($id));
		}else{
			return false;
		}
		
	}
	
	function get_full_tree($start=0,$stop=0, $maxlevel=99999, $nochield=false,$sort='rank'){
	//	if($id==0){//по умолчанию текущий
//			$id=$this->my_item['id'];
//		}
	//параметр $nochield убирает из результатов указанную категорию и все дочерние
		$sql="SELECT id,name,nik, parent,rank FROM " . $this->table . " ORDER BY ".$sort;
		//  print($sql);
		$res=mysql_query($sql) or die(mysql_error());
		$tree=array();
		$finish=array();
		while(($row=mysql_fetch_assoc($res))!=false){
				if($nochield!=$row['id']){
					array_push($tree,$row);
				}
		}
		mysql_free_result($res);
		$fin=$this->crelist($tree,$finish,$start,0,$maxlevel,$stop);
		return $fin;
	}
	
	function crelist(&$na ,&$fin, $par, $l, $maxlevel,$stop){
	//$par - отвечает за корень дерева, если 1 до корень учитываться не будет
	//$stop 
		$l++;
		for ($i = 0; $i < count($na); $i++) {
			$tval=$na[$i];
			if(($l<$maxlevel)&&($tval["parent"]==$par)&&(($tval["id"]!=$stop))){
				$tval['l']=$l;
				array_push($fin,$tval);
				//if($tval["id"]!=$stop){
					$this->crelist($na,$fin,$tval["id"],$l,$maxlevel,$stop);
				//}
			}
		}
		return $fin;
	}



	function get_two_level_tree($startlevel, $sort='rank'){
			$sql="SELECT " . $this->table . ".id, " . $this->table . ".name, " .     $this->table . "_1.id, " . $this->table . "_1.name, " . $this->table . "_1.nik, ".$this->table . ".nik  FROM " . $this->table . " LEFT JOIN " . $this->table . " AS " . $this->table . "_1 ON " . $this->table . ".id=" . $this->table . "_1.parent
	WHERE " . $this->table . ".parent=" . $startlevel . " ORDER BY " . $this->table . ".".$sort.", " . $this->table . "_1.".$sort;
	// print $sql;
			//$res= $this->db->query($sql);
			$res=mysql_query($sql) or die(mysql_error());
			$tree=array();//создаем двухуровневый массив
			$i=-1;
			$cur_id=0;
			while(($row=mysql_fetch_array($res, MYSQL_NUM))!=false){
			// print_r($row);
				if($row[0]==$cur_id){//это листья
				//print("<br />это листья<br />");
				//print_r($row);
					array_push($tree[$i][2],array($row[2],$row[3]));//,$row[5]
				}else{//это ветви
				//print("<br />это ветви<br />");
				//print_r($row);
					$i++;
					$tree[$i]=array($row[0],$row[1],(($row[2]!=NULL)?(array(array($row[2],$row[3]))):(NULL) ));//,$row[7]
					$cur_id=$row[0];
				}
			}
			return $tree;
	}
/*	function get_children($id){
		//возвращает строку вида 2,4,66,5
		//который содерщит все дочерние категории этой категории
		$sql="SELECT id FROM " . $this->table . " WHERE parent IN($id)";
		$res=mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($res)>0){
			$cur_id="";
			while(($row=mysql_fetch_assoc($res))!=false){
					$cur_id.=$row['id'].",";
			}
			mysql_free_result($res);
			$cur_id=substr($cur_id,0,-1);
			$childs=$this->get_children($cur_id);
			if($childs){
			$cur_id.=$childs;
			}
			return ",".$cur_id;
		}else{
			mysql_free_result($res);
			return false;
		}
	}*/
}

?>