<?php
//25 06 2008 	добавлено уборка слешей при возврате данных в форму
//25 06 2008 	предварительная обработка вынесена в отдельную функцию check_and_fill_data
//19 07 2008 	исправлен баг в проверке мыла позволяющий вводить два емайла
//				улучшена проверка URL
//				добавлено LOWER в проверку уникальности TODO настроить локаль
//				добавлено strtolower в проверку изменения уникальноко поля
//24 07 2008	добавлено strip_tags в функцию check_and_fill_data
//05 12 2008    добавлена проверка пустого html текста в функцию check_and_fill_data
//				добавлена проверка img_strict  в функцию check_and_fill_data
//11 12 2008 	в функции translit добавлено удаление повторяющихся подчеркиваний
//				добавлен способ обработки нескольких уменьшенных картинок
//17 12 2008    пофиксен баг в SQL-запросе функции deleteimg
//30 12 2008    отменен особыый случай checkbox для look
//29 01 2009	clear_kav - добавлена для очищения от кавычек
//10 05 2009	get_value добавлено значение id по умолчанию
//09 11 2009 	добавлена обработка динамических полей
//09 12 2009	изменена обработка динамических полей
//				добавлен multicheck
//17 10 2010    min_length для text  required required
//05 04 2011	исправлена ошибка добавления значений динамических полей
//20 07 2011	расширена функция load, теперь можно загружать по любому уникальному полю
//25 01 2012	добавлено поле $key_name - имя первичного ключа
//01 03 2012    min_length теперь не только для required
//22 04 2012	учет key_name - при удалении
//07 06 2012	в функции translit теперь используется mb_strtolower
//07 06 2012	в функции delete отключен calculate_all //TODO вернуть
//14 06 2012	в функции  get_value использован self::
//17 06 2012	откорректирован unique в check_and_fill_data с учетом неоднозначности имени ключа
//29 07 2012	запоминаем имена ошибочных полей в $this->my_item['error_fields']
//29 09 2012	в check_and_fill_data добавлено заполнение полей типа multicheck
//29 09 2012	check_email вынесен в отдельную функцию
//31 10 2012	в функции translit теперь НЕ используется mb_strtolower, а просто strtolower
//07 11 2012	в функции set_settigs исправлен баг инициализации пустым массивом
//25 11 2012	добавлена обработка _tovar_txt - текстовых полей товара
//02 12 2012	set_value возвращает mysql_affected_rows
//				таблица транслитерации вынесена в translit_table.php
//01 06 2013	пустые значения на уникальность НЕ проверяем
//15 07 2013 	добавлена обработка watermark
//29 07 2013	свойству dynamic присваиваем id
//18 08 2013	сначала обрабатываются динамические поля
//18 08 2013	добавлен динамический формат float
//29 11 2013	add_visit_simple - перенесен в tovar
//03 11 2014	action add для int если значения нет в data берем из fild->default


class item{
	public $settings_array;
	public $my_item=array('error_fields'=>array());
	public $error="";
	public $fields;
	public $table;
	public $picture_path;
	public $previev_path;
	public $owner=false;
	public $entity;
	public $target;
	private $target_array=array();
	private $picmove;
	public $raiting_array=array();
	public $dynamic_table="";
	public $dynamic_properties=array();//массив динамических свойств hik->id
	private $dynamic_properties2=array();//массив динамических свойств id->hik
	private $dynamic_query=array();
	public $key_name="";//имя ключа
	//public $error_fields=array();

	function item($id=0, $table=""){
		if($table!=""){
			$this->table= $table;
		}
		if($id!=0){
			$this->load($id);

		}
		//setlocale (LC_CTYPE,"ru_RU.CP1251");
		//echo setlocale (LC_CTYPE,"0");
	}


	function get_item(){
		return $this->my_item;
	}

	function get_item_seo($pointer,$modesql=""){
		if($modesql!=""){
			$modesql= " AND look='yes' ";
		}else{
			$modesql="";
		}
		if(is_numeric($pointer)){
			//$this->load(intval($pointer));
			//print_r($this->my_item);
			if($this->load(intval($pointer))){
				if(isset($this->my_item['look'])){
					if($this->my_item['look']=="yes"){
						return true;
					}else{
						return false;
					}
				}else{
					return true;
				}
			}else{
				return false;
			}
		}else{
			$sql=sprintf("SELECT * FROM %s WHERE nik=%s %s LIMIT 1",$this->table, gvs($pointer, "text"), $modesql);
		 //print $sql;
			$res=mysql_query($sql) or die(mysql_error());
			if (mysql_num_rows($res) > 0){
				$row=mysql_fetch_assoc($res);
				$this->load($row['id']);
				//$this->my_item=mysql_fetch_assoc($res);
				/*if(isset($this->my_item['remained'])){
					$this->my_item['remained']=(strtotime ($this->my_item['remained'])-strtotime (date("Y-m-d")))/60/60/24;
				}*/
				//print_r($this->my_item);
				 return true;
			}else{
				$this->error="Данные не найдены.";
				return false;
			}
		}
		return $this->my_item;
	}


	function set_settigs($settings_array){
		//print_r($settings_array);
		if(isset($settings_array['table']))
			$this->table=DB_PREFIX."_".$settings_array['table'];
		if(isset($settings_array['picture_path']))
			$this->picture_path=$settings_array['picture_path'];
		if(isset($settings_array['previev_path']))
			$this->previev_path=$settings_array['previev_path'];
		if(isset($settings_array['items_table']))
			$this->items_table=$settings_array['items_table'];
		if(isset($settings_array['items_key']))
			$this->items_key=$settings_array['items_key'];
		if(isset($settings_array['entity']))
			$this->entity=$settings_array['entity'];
		//динамические поля
		if($this->dynamic_table!=""){
			$query="SELECT * FROM " . DB_PREFIX .$this->dynamic_table. " ORDER BY rank";//WHERE active='yes'
			//print $query;
			$prop = mysql_query($query) or error_log(mysql_error());
			while(($row = mysql_fetch_assoc($prop))!=false){
				$add_row=array('name'=>$row['nik'],'text'=>$row['name'] ,'active'=>$row['active'],'menu'=>$row['menu'],'podbor'=>$row['podbor'],'dynamic'=>$row['id']);
//!!!!			'text'=>"текст",'float'=>"число",'select'=>"список",'multicheck'=>"отбор",'groppe'=>"группа"
				if($row['ptype']=='text'){
					$add_row['format']="text";
					$add_row['viev']="text";
				}elseif($row['ptype']=='float'){
					$add_row['format']="float";
					$add_row['viev']="text";
				}else{//select
					$add_row['format']="int";
					$add_row['viev']=$row['ptype'];
				}

				if(($row['ptype']=="select")||($row['ptype']=="multicheck")||($row['ptype']=="groppe")){//формируем список значений
					$add_row['sourse']=array();
					if($row['ptype']!="multicheck"){
						$add_row['sourse'][0]="не определено";
					}
					$query="SELECT * FROM " . DB_PREFIX .$this->dynamic_table. "_values WHERE pid=".$row['id']. " ORDER BY rank";
					$pval = mysql_query($query) or error_log(mysql_error());
					while(($rov = mysql_fetch_assoc($pval))!=false){
						$add_row['sourse'][$rov['id']]=$rov['pvalue'];
					}
					mysql_free_result($pval);
					//print_r($add_row);
				}
				$settings_array['fields'][$row['nik']]=$add_row;
			}// print_r($settings_array['fields']);
			mysql_free_result($prop);
		}

		if(isset($settings_array['fields'])){
 			$this->fields=$settings_array['fields'];
			//формируем массив для пересчета итемов в категориях
			foreach($this->fields as $field){
				//error_log(var_export($field));
				if(isset($field['target'])){
					$this->target_array[]=$field;
				}
				if(isset($field['format']) && $field['format']=="key"){//запоминаем имя ключевого поля
					$this->key_name=$field['name'];
				}
			}
		}else{
			$this->fields=array();
		}
	}

	function get_owner($id){
		$sql=sprintf("SELECT id_user FROM %s WHERE id=%s LIMIT 1",$this->table, gvs($id, "int"));
		$res=mysql_query($sql) or die(mysql_error());
		if (mysql_num_rows($res) > 0){
			$row=mysql_fetch_assoc($res);
			mysql_free_result($res);
			return $row['id_user'];
		}else{
			mysql_free_result($res);
			return 0;
		}

	}
	function get_id_from_hash($shash, $hash_field='shash'){
		$sql=sprintf("SELECT id FROM %s WHERE $hash_field=%s LIMIT 1",$this->table, gvs($shash));
		$res=mysql_query($sql) or die(mysql_error());
		if (mysql_num_rows($res) > 0){
			$row=mysql_fetch_assoc($res);
			mysql_free_result($res);
			return $row['id'];
		}else{
			mysql_free_result($res);
			return false;
		}

	}
	function get_seo_link($add=""){
		//print_r($this->my_item['nik'] );
		if((isset($this->my_item['nik']))&&($this->my_item['nik']!="")){
			$get_seo_link=$this->my_item['nik'];
		}else{
			$get_seo_link= $add.$this->my_item['id'];
		}
		return $get_seo_link;
	}


	function get_value($value_type, $id=-999, $table="",$kn=""){
		$mode=false;
		//получаем значение по полю и ид
		//табле для использования функции без создания класса
		if($table=="")
			$table=$this->table;
		if($id==-999)
			$id=$this->my_item['id'];

		if($kn==""){
			if(!isset(self::$key_name) || self::$key_name==''){
				$kn='id';
			}else{
				$kn=self::$key_name;
			}
		}


		$sql=sprintf("SELECT %s FROM %s WHERE %s=%d LIMIT 1",
			$value_type,
			$table,$kn,
			gvs($id, "int"));
//			print_r($this);
// 			 print $sql;
//			 die('++');
		$res=mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_assoc($res);
		$mode=$row[$value_type];
		mysql_free_result($res);

		return $mode;
	}
	function get_dynamic_properties(){//получаем массив динамических свойств hik->id
		if($this->dynamic_table==""){
			//error_log("ERROR Class Item: No find dynamic_table");
			return false;
		}else{
			$query="SELECT id, nik FROM " . DB_PREFIX . $this->dynamic_table ." ORDER BY rank";// "_property" WHERE active='yes'
			// print $query;
			$all = mysql_query($query) or die("get_dynamic_properties: ".mysql_error());
			while(($row = mysql_fetch_assoc($all))!=false){
				$this->dynamic_properties[$row['nik']]=$row['id'];
				$this->dynamic_properties2[$row['id']]=$row['nik'];
			}
			mysql_free_result($all);
			//print_r($this->dynamic_properties);
			return true;
		}
	}
	function set_value($value_type, $id, $new_value, $table=""){
		//станавливаем значение по полю и ид
		//табле для использования функции без создания класса
		if($table=="")
			$table=$this->table;
			//print gettype($new_value);
		$sql=sprintf("UPDATE %s SET %s=%s WHERE id=%d LIMIT 1",
		 	$table,
			$value_type,
			gvs($new_value),///&???????????????????
			gvs($id, "int"));
			 //print $sql;
		mysql_query($sql) or die(mysql_error());
		return mysql_affected_rows();
	}

	function check_owner($auth_id,$item_id ){
		if(defined("ANMIN_PAGE")){
			$this->owner=true;
		}else{
			if($this->get_owner($item_id)===$auth_id){
				$this->owner=true;
			}else{
				$this->owner=false;
			}
		}
		return $this->owner;
	}

	function check_unique($value_type, $value_data, $table=""){
			if($table=="")
			$table=$this->table;
			//проверяем уникальность поля
			$sql=sprintf("SELECT %s FROM %s WHERE LOWER(%s)=LOWER(%s) LIMIT 1",
					$value_type,
					$table,
					$value_type,
					gvs($value_data));
					// print $sql;
			$res=mysql_query($sql) or die(mysql_error());
			if(mysql_num_rows($res)>0){
				$return=false;//не уникальна
			}else{
				$return=true;//уникальна
			}
			mysql_free_result($res);
			return $return;
	}

	function check_email($value_data){
		$temp="/^[_a-zA-Z0-9-](\.{0,1}[_a-zA-Z0-9-])*@([a-zA-Z0-9-]{2,}\.){0,}[a-zA-Z0-9-]{2,}(\.[a-zA-Z]{2,4}){1,2}$/";
		if (!preg_match($temp, $value_data)){
		//if (!preg_match('/\\A[A-Z0-9._%-]+@[A-Z0-9._%-]+\\.[A-Z]{2,4}\\z/i', $value_data)){
			return false;
		}else{
			return true;
		}
	}

	function check_and_fill_data($data=""){
		//print("check_and_fill_data");
		if($data=="")
			$data=$_POST;
	/// Проверка полей ///
  ///print_r($data);

		foreach($this->fields as $field){

			if((isset($field['name']))&&(isset($data[$field['name']]))){//обрезаем данные
				if(!isset($field['html']))
					$data[$field['name']]=strip_tags($data[$field['name']]);
				$data[$field['name']]=trim($data[$field['name']]);
			}
			if(isset($field['required'])){
				if((!isset($data[$field['name']]))||($data[$field['name']]=="")){
					$this->my_item['error_fields'][]=$field['name'];
					$this->error.="Поле <strong>".$field['required']."</strong> должно быть заполнено<br>";
				}elseif((isset($field['html']))&&(trim(str_replace("&nbsp;","",strip_tags($data[$field['name']])))=="")){//особый случай для html
					$this->my_item['error_fields'][]=$field['name'];
					$this->error.="Поле <strong>".$field['required']."</strong> должно быть заполнено<br>";
				}
			}


			if(isset($field['unique'])){
				if(trim($data[$field['name']])!=''){	//пустые значения на уникальность НЕ проверяем
					if($this->key_name==''){
						$kn='id';
					}else{
						$kn=$this->key_name;
					}
					//die($this->get_value($field['name'], intval($data[$kn]),$this->table,$kn));
					//проверяем есть ли уже эта запись
					$chk_value="";
					if($this->my_item['key']>0){//получаем текущее значение
						$chk_value=mb_convert_case( $this->get_value($field['name'], intval($data[$kn]),$this->table,$kn) , MB_CASE_LOWER, "UTF-8");
					}

					if((mb_convert_case($data[$field['name']], MB_CASE_LOWER, "UTF-8"))!=$chk_value){
						//проверяем уникальность поля
						//если поле изменено или новое
						if($this->check_unique($field['name'], $data[$field['name']])==false){
							$this->error.=$data[$field['name']].$field['unique']."<br>";
						}
					}
				}
			}// unique


			switch($field['format']){
				case "key":
					if((!isset($data[$field['name']]))||(!is_numeric($data[$field['name']]))){
						$this->error.=	"Произошла ошибка определения идентификатора!<br />";
					}else{//ключ для обновления (втч картинок)
						$this->my_item['key']=$data[$field['name']];
					}
				break;
				case "url":
					if((isset($data[$field['name']]))&&($data[$field['name']]!="")){
						if (!preg_match('/^http:\/\/[-a-zA-Z0-9.]+\.[a-z]{2,4}\/\z/i',$data[$field['name']])){
							$this->my_item['error_fields'][]=$field['name'];
							$this->error.=	"Поле Адрес (URL) должно иметь формат http://sait.ru/<br />";
						}
					}
				break;
				case "email":
					if((isset($data[$field['name']]))&&($data[$field['name']]!="")){
						if ( !$this->check_email($data[$field['name']]) ){
							$this->my_item['error_fields'][]=$field['name'];
							$this->error.=	"Поле E-mail должно иметь формат name@sait.ru<br />";
						}
					}
				break;
				case "password":
//				ПАроль
//				если добавление то обязательно
//				если нет то нет

					if((isset($data[$field['name']]))&&(trim($data[$field['name']])!="")){
						if (!preg_match('/\\b[A-Za-zА-Яа-я0-9]+\\b/', $data[$field['name']])){
							$this->my_item['error_fields'][]=$field['name'];
							$this->error.=	"Поле пароля должно содержать буквы и цифры<br />";
							$data[$field['name']]="";
						}
						if ((isset($field['min_length']))&&(strlen(trim($data[$field['name']]))<$field['min_length'])){
							$this->my_item['error_fields'][]=$field['name'];
							$this->error.=	"Поле <strong>Пароль</strong> должно содержать не менее ". $field['min_length']."  знаков.<br />";
							$data[$field['name']]="";
						}
					}
				break;
				case "img":
					$this->my_item[$field['name']]=false;
					if((isset($_FILES[$field['name']]))&&($_FILES[$field['name']]['name'])){
						include_once(CLASS_PATH.'uploadfile.php');
						$this->picmove = new FileManadger();
						$this->picmove->filefield = $_FILES[$field['name']];
						//$this->picmove->destpath = IMAGE_PATH . "logotype/";//
						if(!($this->picmove->checkfiletypesize())){
							$this->error.=$this->picmove->errorlog;
						}
						//var_dump($this->picmove->finfo);
						//int(120) [1]=>  int(90)
						if(isset($field['img_strict'])){
							//проверяем точные размеры
							switch($field['img_strict']){
							case "proportion":
								if($this->picmove->finfo['0']!=$this->picmove->finfo['1']){
									$this->error.="Ошибка: ".$field['img_strict_error'] ;
								}
							break;
							}

						}
						$this->my_item[$field['name']."type"]=$this->picmove->filetyp;
					}else{
						$this->my_item[$field['name']."type"]=NULL;
					}
				break;
				case "checkbox":
				//if((isset($data[$field['name']]))&&(trim($data[$field['name']])!="")){
				if(!isset($data[$field['name']])){
					$this->my_item[$field['name']]="no";
				}
				break;
				case "text":

				$len_data=strlen(trim($data[$field['name']]));
				if ((isset($field['min_length']))&&($len_data>0)&&($len_data<$field['min_length'])){
					//TODO вырезать из жирного текста
					$this->my_item['error_fields'][]=$field['name'];
					$this->error.=	"Поле <strong>".$field['text']."</strong> должно содержать не менее ". $field['min_length']."  знаков.<br />";
				}

				break;
			}
		}

		//заполняем итем
		foreach($this->fields as $field){
			if((isset($field['name']))&&(isset($data[$field['name']]))){
				if(($this->error!="")&&(get_magic_quotes_gpc())){//При ошибке во всех полях убираем слеши
					$this->my_item[$field['name']] = stripslashes($data[$field['name']]);
				}else{
					$this->my_item[$field['name']]=$data[$field['name']];
				}
			}elseif($field['viev']=='multicheck'){//для нескольких значений сhandelier_82

				foreach($data as $kd=>$vd){
						$pos1 = stripos($kd, $field['name']);
						if(($pos1!==false)&&($pos1==0)){//найдено похожее, получаем хвостик
							$pieces = explode("_", $kd);
							$last_piece=end($pieces);
							if((isset($last_piece))&&($last_piece!="")){
								if(isset($this->my_item[$field['name']]) ){
									if( is_array($this->my_item[$field['name']])){
										$this->my_item[$field['name']][]=$last_piece;
									}else{
										$this->my_item[$field['name']]=array($this->my_item[$field['name']],$last_piece);
									}

								}else{
									$this->my_item[$field['name']]=$last_piece;
								}
							}
						}
				}
			}
		}//foreach
		//print_r($this);
		//die('==========');
	}

function action($action, $data=""){
	//print_r($action);
	//print "asdasda";

	if($data=="") $data=$_POST;
	//// Удаление ///
	//var_dump($_POST);
	//var_dump($_POST);
	if(strstr($action,"delete_")){
	//print_r($_POST);
		//if($this->owner){
			$act=substr($action,7);
			$del_id=intval($data['id']);
			if($act=="item"){//удаление итема
				///print($del_id);
				$this->delete($del_id);
			}else{//удаление картинок
				$this->deleteimg($act,$del_id);
				$this->my_item['key']=$del_id;
			}
		//}
	}else{

		$this->check_and_fill_data($data);

//
///*************************///
///                         ///
///      РЕДАКТИРОВАНИЕ     ///
///                         ///
///*************************///
/// после успешной проверки
// обрабатываем и формируем SQL
// print_r($this->fields);
	// $this->error;
	if($this->error==""){

		$dynamic_sql="";
		switch($action){
			case "previev"://previev
				//print_r($this->my_item);
			break;
			case "edit":
			 //print_r($data);
				$sql="";

				$rating_to_be=false;//по умолчанию рейтинги не считаем, в дальнейшем, если хоть у одного элемента будет рейтинг, то будем его считать
				foreach($this->fields as $field){
					if(isset($field['rating'])) $rating_to_be=true;

					//СНАЧАЛА ИЩЕМ ДИНАМИЧЕСКИЕ ПОЛЯ 'text'=>"текст",'float'=>"число",'select'=>"список",'multicheck'=>"отбор",'groppe'=>"группа"
					if((isset($field['dynamic']))){
						if(count($this->dynamic_properties)<1)//формируем массив динамических свойств hik->id
								$this->get_dynamic_properties();
						if(isset($field['viev'])){
							switch($field['viev']){
							case "multicheck":
								foreach($data as $kd=>$vd){
									$pos1 = stripos($kd, $field['name']);
									if(($pos1!==false)&&($pos1==0)){//найдено похожее, получаем хвостик
										$pieces = explode("_", $kd);
										$last_piece=end($pieces);
										if((isset($last_piece))&&($last_piece!="")){
											$dynamic_sql.=sprintf("(CYRRKEY,%d,%s),", $this->dynamic_properties[$field['name']], gvs($last_piece,"text") );
										}
									}
								}
							break;
							case "select":
							case "groppe":
								if((isset($data[$field['name']]))&&(trim($data[$field['name']])!="")&&(intval($data[$field['name']])!=0)){
									$dynamic_sql.=sprintf("(CYRRKEY,%d,%s),", $this->dynamic_properties[$field['name']], gvs(trim($data[$field['name']]),"text") );
								}
							break;
							default://text  float
								//динамический текст сохраняем в отдельную таблицу
								if((isset($data[$field['name']]))&&(trim($data[$field['name']])!="")){
									if($field['format']=='float'){
										$dynamic_txt_sql.=sprintf("(CYRRKEY,%d,%s),", $this->dynamic_properties[$field['name']], gvs(floatval(trim($data[$field['name']])),"text") );
									}else{
										//формируем шаблоны запросов для динамических полей ID свойства по псевдониму
										$dynamic_txt_sql.=sprintf("(CYRRKEY,%d,%s),", $this->dynamic_properties[$field['name']], gvs(trim($data[$field['name']]),"text") );
									}
								}
							break;
							}
						}//if((isset($field['viev'])
					}else{//if((isset($field['dynamic']))){
						//теперь статические
						switch($field['format']){
							case "int":
								if(isset($data[$field['name']])){
									$sql.=$field['name']."=".intval($data[$field['name']]). ",";
								}
							break;
							case "float":
								if(isset($data[$field['name']])){
									$sql.=$field['name']."=".floatval($data[$field['name']]). ",";
								}
							break;
							case "password":
								if((isset($data[$field['name']]))&&($data[$field['name']]!="")){
									$temp=trim($data[$field['name']]);
									if(isset($field['max_length'])){
										$temp=substr($temp,0,$field['max_length']);
									}
									$sql.=$field['name']."=";
									$sql.=gvs(md5($temp)) . ",";
								}
							break;
							case "text":
							case "url":
							case "email":
								if(isset($data[$field['name']])){
									$sql.=$field['name']."=";
									$temp=trim($data[$field['name']]);
									if(!isset($field['html']))
										$temp=strip_tags($temp);
									if(isset($field['max_length'])){
										$temp=mb_substr($temp,0,$field['max_length'],'utf-8');
									}
									$sql.=gvs($temp,"text") . ",";
								}elseif(isset($field['default'])){
									$sql.=$field['name']."=";
									$sql.=gvs($field['default']) . ",";
								}
							break;
							case "datetime"://время добавления
							case "datetimeeditable":
								//ДАТА YYYY-MM-DD HH:MM:SS
								if ((isset($data[$field['name']]))&&(($timestamp = strtotime($data[$field['name']])) !== -1)) {
									$sql.=$field['name']."=";
									$sql.=gvs($data[$field['name']]) . ",";
								}
							break;
							case "listtext":
								if(isset($data[$field['name']])){
									$sql.=$field['name']."=";
									$temp="";
									if($data[$field['name']]!="0"){
										$temp=strip_tags(trim($data[$field['name']]));
										if(isset($field['max_length'])){
											$temp=substr($temp,0,$field['max_length']);
										}
									}else{
										if(isset($field['alternativa'])){
											$temp=strip_tags(trim($data[$field['alternativa']]));
											if(isset($temp['max_length'])){
												$temp=substr($temp,0,$field['max_length']);
											}
										}
									}
									$sql.=gvs($temp) . ",";
								}
							break;
							case "remainedday"://сталось дней
								if(isset($data[$field['name']])){
									$sql.=$field['name']."=";
									$sql.="ADDDATE(NOW(),INTERVAL ".intval($data[$field['name']])." DAY),";
								}
							break;
							case "img":
								if(isset($this->my_item[$field['name']."type"])){
									$sql.=$field['name']."=";
									$sql.=gvs($this->my_item[$field['name']."type"]). ",";
									$this->picmove->filefield = $_FILES[$field['name']];
									$this->picmove->destpath = IMAGE_PATH .$this->picture_path ;//
									$this->picmove->filename=$field['name'].$this->my_item['key'];
									$this->picmove->create_img((isset($field['img_width']))?($field['img_width']):(0),(isset($field['img_height']))?($field['img_height']):(0),"","",
																(isset($field['watermark']))?(ROOT_PATH.$field['watermark']):(""));
									if(isset($field['previev'])){
										if(!is_array($field['previev'])){
											//отрабатываем обратную совместимость синтаксиса 'previev'=>80
											if($this->picmove->create_img($field['previev'],$field['previev'],"previev/")==false){
												$this->error.=$this->picmove->errorlog;
											}
										}else{
											//новый синтаксис двойной массив
											//'nameplus'=>'mal','width'=>80,'height'=>80
											foreach($field['previev'] as $p){
												if(isset($p['obrez'])){
													//create_obrez($newwidth,$newheight,$p_path, $name_add=""){
													if($this->picmove->create_obrez($p['width'],$p['height'],"",$p['nameplus'])==false){
														$this->error.=$p['nameplus'].": ".$this->picmove->errorlog;
													}
												}else{
													if($this->picmove->create_img($p['width'],$p['height'],"",$p['nameplus'],((isset($p['watermark']))?(ROOT_PATH.$p['watermark']):("")))==false){
														$this->error.=$p['nameplus'].": ".$this->picmove->errorlog;
													}
												}

											}
										}
									}
								}
							break;
							case "checkbox":
								$sql.=$field['name']."=";
								if(isset($data[$field['name']])){
									$sql.="'yes',";
								}else{
									$sql.="'no',";
								}
							break;
							case "key":
								$where=$field['name']."=".intval($data[$field['name']]);
								$cur_look_id=intval($data[$field['name']]);
							break;
						}//switch
					}//if((isset($field['dynamic']))){
				}//foreach
				if($rating_to_be)//считаем постоянный рейтинг $cur_look_id
						$sql.=" rating=".$this->calc_rating().",";
				//print $sql;
				$sql=substr($sql,0,-1);
				$sql=sprintf("UPDATE %s SET  %s  WHERE %s", $this->table,$sql,$where);
				//  die( $sql);
				$res=mysql_query($sql) or die(mysql_error());

				if(isset($dynamic_sql) && $dynamic_sql!=""){	//обновляем динамические поля
					mysql_query(sprintf("DELETE FROM %s_value WHERE id_tovar=%d ", $this->table, $cur_look_id)) or error_log("DELETE_dynamic_field: ".mysql_error());
					$dynamic_sql=str_replace ( "CYRRKEY", $cur_look_id, $dynamic_sql );
					 //print($dynamic_sql);
					mysql_query(sprintf("INSERT INTO  %s_value (id_tovar,pid,pvalue) VALUES %s ", $this->table, substr($dynamic_sql, 0, -1))) or error_log("Update_dynamic_field: ".mysql_error());
				}
				if(isset($dynamic_txt_sql) && $dynamic_txt_sql!=""){	//обновляем динамические ТЕКСТОВЫЕ поля	//TODO избавится от DELETE
					mysql_query(sprintf("DELETE FROM %s_txt WHERE id_tovar=%d ", $this->table, $cur_look_id)) or error_log("DELETE_dynamic_txt_field: ".mysql_error());
					$dynamic_txt_sql=str_replace ( "CYRRKEY", $cur_look_id, $dynamic_txt_sql );
					//print($dynamic_sql);
					mysql_query(sprintf("INSERT INTO  %s_txt (id_tovar,pid,pvalue) VALUES %s ", $this->table, substr($dynamic_txt_sql, 0, -1))) or error_log("Update_dynamic_txt_field: ".mysql_error());
				}
			break;
///*************************///
///                         ///
///      ДОБАВЛЕНИЕ     ///
///                         ///
///*************************///
			case "add":

				$ins1="";
				$ins2="";
				$rating_to_be=false;//по умолчанию рейтинги не считаем, в дальнейшем, если хоть у одного элемента будет рейтинг, то будем его считать
				foreach($this->fields as $field){
					if(isset($field['rating'])) $rating_to_be=true;
					//СНАЧАЛА ИЩЕМ ДИНАМИЧЕСКИЕ ПОЛЯ 'text'=>"текст",'float'=>"число",'select'=>"список",'multicheck'=>"отбор",'groppe'=>"группа"
					if((isset($field['dynamic']))){
						if(count($this->dynamic_properties)<1)//формируем массив динамических свойств hik->id
							$this->get_dynamic_properties();
									//////////////////////
							if(isset($field['viev'])){
								switch($field['viev']){
								case "multicheck":
									foreach($data as $kd=>$vd){
										$pos1 = stripos($kd, $field['name']);
										if(($pos1!==false)&&($pos1==0)){//найдено похожее, получаем хвостик
											$pieces = explode("_", $kd);
											$last_piece=end($pieces);
											if((isset($last_piece))&&($last_piece!="")){
												$dynamic_sql.=sprintf("(CYRRKEY,%d,%s),", $this->dynamic_properties[$field['name']], gvs($last_piece,"text") );
											}
										}
									}
									break;
								case "select":
								case "groppe":
									if((isset($data[$field['name']]))&&(trim($data[$field['name']])!="") &&(intval($data[$field['name']])!=0) ){
										//формируем шаблоны вставки для динамических полей ID свойства по псевдониму
										$dynamic_sql.=sprintf("(CYRRKEY,%d,%s),", $this->dynamic_properties[$field['name']], gvs(trim($data[$field['name']]),"text") );
									}
									break;
								case "text"://text
									//динамический текст сохраняем в отдельную таблицу
									if((isset($data[$field['name']]))&&(trim($data[$field['name']])!="")){
										if($field['format']=='float'){
											$dynamic_txt_sql.=sprintf("(CYRRKEY,%d,%s),", $this->dynamic_properties[$field['name']], gvs(floatval(trim($data[$field['name']])),"text") );
										}else{
											//формируем шаблоны запросов для динамических полей ID свойства по псевдониму
											$dynamic_txt_sql.=sprintf("(CYRRKEY,%d,%s),", $this->dynamic_properties[$field['name']], gvs(trim($data[$field['name']]),"text") );
										}
									}
									break;
								}
							}//if((isset($field['viev'])
					}else{//теперь статические
						switch($field['format']){
							case "int":
								if(isset($data[$field['name']])){
									$ins1.=$field['name'] . ",";
									$ins2.=intval($data[$field['name']]). ",";
								}elseif(isset($field['default'])){//значение по умолчанию
									$ins1.=$field['name'] . ",";
									$ins2.=intval($field['default']) . ",";
								}
							break;
							case "float":
									$ins1.=$field['name'] . ",";
									$ins2.=floatval($data[$field['name']]). ",";
							break;
							case "text":
							case "url":
							case "email":
									if(isset($data[$field['name']])){
										$ins1.=$field['name'] . ",";
										$instemp=trim($data[$field['name']]);
										if(!isset($field['html']))
											$instemp=strip_tags($instemp);
										if(isset($field['max_length'])){
											$instemp=mb_substr($instemp,0,$field['max_length'],'utf-8');
										}
										$ins2.=gvs($instemp,"text") . ",";
									}elseif(isset($field['default'])){
										$ins1.=$field['name'] . ",";
										$ins2.=gvs($field['default']) . ",";
									}
							break;
							case "password":
								if((isset($data[$field['name']]))&&($data[$field['name']]!="")){
									$temp=trim($data[$field['name']]);
									if(isset($field['max_length'])){
										$temp=substr($temp,0,$field['max_length']);
									}
									$ins1.=$field['name'] . ",";
									$ins2.=gvs(md5($temp)) . ",";
									do{//формируем уникальный кеш
										$hash_id=md5("estal" . $data[$field['name']] . strval(microtime()));
										$sql=sprintf("SELECT hash_id FROM %s WHERE hash_id=%s LIMIT 1",$this->table,gvs($hash_id, "text"));
										//print $sql;
										$res=mysql_query($sql) or die(mysql_error());
										if (mysql_num_rows($res) > 0){
											$check_hash=false;
										}else{
											$check_hash=true;
										}
										mysql_free_result($res);
									} while ($check_hash==false);
									$ins1.="hash_id,";
									$ins2.= "'".$hash_id ."'". ",";
								}else{
									$this->my_item['error_fields'][]=$field['name'];
									$this->error.="Поле <strong>Пароль</strong> должно быть заполнено<br>";
									break 3;
								}
							break;
							case "datetime"://время добавления
							case "datetimeeditable":
								//ДАТА YYYY-MM-DD HH:MM:SS
								if ((isset($data[$field['name']]))&&(($timestamp = strtotime($data[$field['name']])) !== -1)) {
									$ins1.=$field['name'] . ",";
									$ins2.=gvs($data[$field['name']]) . ",";
								}else{
									$ins1.=$field['name'] . ",";
									$ins2.="NOW(),";
								}
							break;
							case "listtext":
								if(isset($data[$field['name']])){
									$ins1.=$field['name'] . ",";
									$instemp="";
									if($data[$field['name']]!="0"){
										$instemp=strip_tags(trim($data[$field['name']]));
										if(isset($field['max_length'])){
											$instemp=substr($instemp,0,$field['max_length']);
										}
									}else{
										if(isset($field['alternativa'])){
											$instemp=strip_tags(trim($data[$field['alternativa']]));
											if(isset($field['max_length'])){
												$instemp=substr($instemp,0,$field['max_length']);
											}
										}
									}
									$ins2.=gvs($instemp) . ",";
								}
							break;
							case "remainedday"://сталось дней
								if(isset($data[$field['name']])){
									$ins1.=$field['name'] . ",";
									$ins2.="ADDDATE(NOW(),INTERVAL ".intval($data[$field['name']])." DAY),";
								}
							break;
							case "img":
								if(isset($this->my_item[$field['name']."type"])){
									$this->my_item[$field['name']]=$this->my_item[$field['name']."type"];
									$ins1.=$field['name'] . ",";
									$ins2.=gvs($this->my_item[$field['name']."type"]). ",";
								}
							break;
							case "checkbox":
								$ins1.=$field['name'] . ",";
								if(isset($data[$field['name']])){
									$ins2.="'yes',";
	/*								if($field['name']=='look'){//особый случай для look
										$new_look="yes";
									}*/
								}else{
									$ins2.="'no',";
								}
							break;
						}
					}
				}//foreach
				if($rating_to_be){//считаем постоянный рейтинг $cur_look_id
					$ins1.=" rating,";
					$ins2.=$this->calc_rating().",";
					//$sql.=" rating=".$this->calc_rating($cur_look_id).",";
				}
				$ins1=substr($ins1,0,-1);
				$ins2=substr($ins2,0,-1);
				$sql=sprintf("INSERT INTO %s (%s) VALUES (%s)", $this->table,$ins1,$ins2);
				// die($sql);
				$res=mysql_query($sql) or die(mysql_error());
				$lastid=mysql_insert_id();


				if($dynamic_sql!=""){	//обновляем динамические поля
					//mysql_query(sprintf("DELETE FROM %s_tovar_value WHERE id_tovar=%d ",  DB_PREFIX, $cur_look_id)) or error_log("DELETE_dynamic_field: ".mysql_error());
					$dynamic_sql=str_replace ( "CYRRKEY", $lastid, $dynamic_sql );
					mysql_query(sprintf("INSERT INTO  %s_value (id_tovar,pid,pvalue) VALUES %s ", $this->table, substr($dynamic_sql, 0, -1))) or error_log("Update_dynamic_field: ".mysql_error());
				}
				if($dynamic_txt_sql!=""){	//обновляем динамические поля ТЕКСТ
					//mysql_query(sprintf("DELETE FROM %s_tovar_value WHERE id_tovar=%d ",  DB_PREFIX, $cur_look_id)) or error_log("DELETE_dynamic_field: ".mysql_error());
					$dynamic_txt_sql=str_replace ( "CYRRKEY", $lastid, $dynamic_txt_sql );
					mysql_query(sprintf("INSERT INTO  %s_txt (id_tovar,pid,pvalue) VALUES %s ", $this->table, substr($dynamic_txt_sql, 0, -1))) or error_log("Update_dynamic_txt_field: ".mysql_error());
				}

				//догружаем картинки
				foreach($this->fields as $field){
					if($field['format']=="img"){
						if(isset($this->my_item[$field['name']."type"])){
							//include_once(CLASS_PATH.'uploadfile.php');
							$this->picmove->filefield = $_FILES[$field['name']];
							$this->picmove->destpath = IMAGE_PATH .$this->picture_path ;//
							$this->picmove->filename=$field['name'].$lastid;
							$this->picmove->create_img((isset($field['img_width']))?($field['img_width']):(0),(isset($field['img_height']))?($field['img_height']):(0),"","",
															(isset($field['watermark']))?(ROOT_PATH.$field['watermark']):(""));
							if(isset($field['previev'])){
								if(!is_array($field['previev'])){
									//отрабатываем обратную совместимость синтаксиса 'previev'=>80
									if($this->picmove->create_img($field['previev'],$field['previev'],"previev/")==false){
										$this->error.=$this->picmove->errorlog;
									}
								}else{
									//новый синтаксис двойной массив
									//'nameplus'=>'mal','width'=>80,'height'=>80
										foreach($field['previev'] as $p){
											if(isset($p['obrez'])){
												//create_obrez($newwidth,$newheight,$p_path, $name_add=""){
												if($this->picmove->create_obrez($p['width'],$p['height'],"",$p['nameplus'])==false){
													$this->error.=$p['nameplus'].": ".$this->picmove->errorlog;
												}
											}else{
												if($this->picmove->create_img($p['width'],$p['height'],"",$p['nameplus'],((isset($p['watermark']))?(ROOT_PATH.$p['watermark']):("")))==false){
													$this->error.=$p['nameplus'].": ".$this->picmove->errorlog;
												}
											}

										}
								}
							}

						}
					}
				}

				//print_r($this->my_item['key']=$lastid);
				$this->my_item['key']=$lastid;
			break;
			default:
				global $q_string;
				reload_after_event($q_string);
			break;
		}

	}//if($this->error==""){
	}//if(strstr($action,"delete_")){

}

	function load($id, $table="", $mode="",$field="id",$field_format="int"){
		if($mode!=""){
			$modesql= " AND look='yes' ";
		}else{
			$modesql="";
		}
		if($table=="")
			$table=$this->table;

		$sql=sprintf("SELECT * FROM %s WHERE %s=%s %s LIMIT 1",$table,$field, gvs($id, $field_format), $modesql);
		//  print "sql=".$sql;

		$res=mysql_query($sql) or die(mysql_error());
		if (mysql_num_rows($res) > 0){

			$this->my_item=array_merge($this->my_item,mysql_fetch_assoc($res));

			if(isset($this->my_item['remained'])){
				$this->my_item['remained']=(strtotime ($this->my_item['remained'])-strtotime (date("Y-m-d")))/60/60/24;
			}
			//проверяем есть ли таблица динамических полей
			if($this->get_dynamic_properties()){//TODO устанавливать таблицу для значений
				//загружаем значения из двух таблиц
				$sql=sprintf("SELECT * FROM %s_value WHERE id_tovar=%d UNION ALL SELECT * FROM %s_txt WHERE id_tovar=%d",$this->table,$id,$this->table,$id);
				// print_r($this->dynamic_properties2);
				$resd=mysql_query($sql) or error_log("Get dynamic values: ".mysql_error());
//				if(count($this->dynamic_properties)<1){
//					$this->get_dynamic_properties();
//				}
				while(($rowd = mysql_fetch_assoc($resd))!=false){
					if(isset($this->my_item[$this->dynamic_properties2[$rowd['pid']]])){//ключ уже существует, преобразуем в массив
						if(!is_array($this->my_item[$this->dynamic_properties2[$rowd['pid']]])){
							$tmp=$this->my_item[$this->dynamic_properties2[$rowd['pid']]];
							$this->my_item[$this->dynamic_properties2[$rowd['pid']]]=array($tmp);
						}
						$this->my_item[$this->dynamic_properties2[$rowd['pid']]][]=$rowd['pvalue'];
					}else{
						$this->my_item[$this->dynamic_properties2[$rowd['pid']]]=$rowd['pvalue'];
					}
				}
				mysql_free_result($resd);
			}
			//print_r($this->my_item);
			return true;
		}else{
			$this->error="Данные не найдены.";
			return false;
		}
		mysql_free_result($res);
		//return $newitem;
	}


	function delete($id){
	//print("delete");

	//var_dump($this->fields);
		if($this->fields){
			foreach($this->fields as $field){
				if($field['format']=="img"){//удаляем все картинки
					$this->deleteimg($field['name'],$id);
				}
//				if((isset($field['name']))&&($field['name']=="look")){
//					$this->calculate_all();
//				}
			}
		}

		if($this->dynamic_table!=""){//при удалении  удаляем все динамические свойства
			$delsql=sprintf("DELETE FROM %s_value WHERE id_tovar=%d", $this->table, $_POST['id']);
			//print $delsql;
			mysql_query($delsql) or die("DELETE _value: ".mysql_error());
		}
		if($this->key_name==''){
			$kn='id';
		}else{
			$kn=$this->key_name;
		}
		$sql=sprintf("DELETE FROM %s WHERE %s=%d", $this->table,$kn, gvs($id));
		mysql_query($sql) or die(mysql_error());
	}

	function deleteimg($field, $id){
		$sql=sprintf("SELECT %s FROM %s WHERE id=%d",$field,$this->table,$id);
		$res=mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($res)>0){
			$row=mysql_fetch_assoc($res);
			$img_type=$row[$field];
			if($img_type!=NULL){
				$delfile_path= IMAGE_PATH .$this->picture_path.$field.$id . "." . $img_type;
				if(file_exists($delfile_path)){
						unlink($delfile_path);
				}
				//удаляем превьюшки
				if(isset($this->fields[$field]['previev'])){
					$img_field=$this->fields[$field]['previev'];
						if(!is_array($img_field)){
							//отрабатываем обратную совместимость синтаксиса 'previev'=>80
							$delfile_path= IMAGE_PATH .$this->picture_path."previev/".$field.$id . "." . $img_type;
							if(file_exists($delfile_path)){
									unlink($delfile_path);
							}
						}else{//новый синтаксис двойной массив		//'nameplus'=>'mal','width'=>80,'height'=>80
							foreach($img_field as $p){
								$delfile_path= IMAGE_PATH .$this->picture_path.$field.$id .$p['nameplus']. "." . $img_type;
								if(file_exists($delfile_path)){
									unlink($delfile_path);
								}
							}
						}
				}
				$sql=sprintf("UPDATE %s SET %s=NULL WHERE id=%d LIMIT 1",
							$this->table,
							$field,
							gvs($id));
				mysql_query($sql) or die(mysql_error());
			}

		}//mysql_num_rows
		mysql_free_result($res);
		return;
	}

	function create_blank(){

		foreach($this->fields as $field){
			if(isset($field['default'])){
				$this->my_item[$field['name']]=$field['default'];
			}else{
				if($field['format']=="int"){
					$this->my_item[$field['name']]=0;
				}elseif($field['format']=="datetimeeditable"){
					$this->my_item[$field['name']]=date("Y-m-d H:i:s");
				}else{
					if(isset($field['name']))
						$this->my_item[$field['name']]="";
				}
			}
		}
		if(defined("ANMIN_PAGE"))
			$this->my_item['id_user']=0;


	}

	function add_visit($entity=""){//cчитаем визиты
		if($entity=="")
			$entity=$this->entity;
		$sql=sprintf("UPDATE %s SET visit=visit +1 WHERE id=%d AND
		(SELECT COUNT(item) FROM st_visit_ip WHERE entity=%s AND item=%s AND l_ip=%d)=0",
				$this->table,
				$this->my_item['id'],
				gvs($entity),
				$this->my_item['id'],
				ip2long($_SERVER["REMOTE_ADDR"]));
		mysql_query($sql) or die(mysql_error());
		//die( $sql);
		if(mysql_affected_rows()>0){
			//записываем посещение с конкретного IP
			$sql=sprintf("INSERT DELAYED IGNORE INTO st_visit_ip (entity,item,l_ip) VALUES (%s,%d,%d)",gvs($entity), $this->my_item['id'],ip2long($_SERVER["REMOTE_ADDR"]));
			//die($sql);
			mysql_query($sql) or die(mysql_error());
			$this->my_item['visit']++;
		}

	}



	function calculate_all(){
		//print("calculate_all");
		include_once(CLASS_PATH."/function/catalog.function.php");
		 //print_r($this->target_array);
		foreach($this->target_array as $target){
		//$entity,$target,$items
		//cat,id_cat,items
			//calc_cat($this->entity,$target['target'],$target['name']);$this->entity,
			calc_cat($target['target'],$target['name'],$this->table);
		}
	}

	function calc_cat_id($id,$action){
		include_once(CLASS_PATH."/function/catalog.function.php");
		//обновляем категорию по номеру ресурса
		//print_r($this->target_array);
		foreach($this->target_array as $target){
			$cur_cat=$this->get_value($target['name'], $id);
			//calc_cat($entity,$target,$target_field, $cat=1,$action='all')
			calc_cat($this->entity,$target['target'],$target['name'],$cur_cat,$action);
			//$this->calc_cat($cur_cat,$action);
		}
		//$cur_cat=$this->get_value("id_cat", $id);
	}

	function calc_cat($cat=1,$action='all'){
		include_once(CLASS_PATH."/function/catalog.function.php");
		//print($this->target);
		//print "$this->entity ".$this->entity;
		//print "$this->target ".$this->target;
		//calc_cat($this->entity,$this->target,$cat,$action);
	}

	function move_to_cat($from, $to){
		//используется при массовом перемещении например при удалении подкатегории
		include_once(CLASS_PATH."/function/catalog.function.php");
		move_to_cat($this->entity,$from, $to);
	}

	function translit($arg_string){
//		if(BD_SERVER!="localhost"){
//			setlocale (LC_CTYPE,"ru_RU.CP1251");
//		}else{
//			setlocale (LC_ALL, '');
//		}
		// echo setlocale (LC_CTYPE,"0");
		//$arg_string=strtolower($arg_string);//mb_strtolower
		//print($arg_string);
		$arg_string=mb_convert_case($arg_string, MB_CASE_LOWER, "UTF-8");

		$arg_string=preg_replace("/[^\w ]/u", "", $arg_string);
		include('translit_table.php');
		// print($arg_string);
		$res_string=str_replace($ru, $trans, $arg_string);
		$res_string = preg_replace('/_+/', '_', $res_string);
		return $res_string;
	}

	public static function clear_kav($arg_string){
		return str_replace(array('"',"'"), "",$arg_string);
	}

	/*
	Просчет рейтинга
	рейтинг состоит из переменного по количествам визитов
	и
	Постоянного который считаем здесь
	$item - id ресурса

	ЗАВИСИТ
	- хозяин
	- заполненность полей
	- по 10 адрес
	- 0 - факс
	-  10 Полное описание,услуги


	*/
	function calc_rating(){//$item
		$rating=0;
		//$stop_users=array(0,21,22,23,25);//в дольнейшем заменить на модераторов
		$stop_users=array(0);//в дольнейшем заменить на модераторов
		foreach($this->fields as $field){
			//print $field['name']."<br />";
			if((isset($field['name']))&&($field['name']=="id_user")&&(in_array(intval($this->my_item[$field['name']]),$stop_users))){
				//пропускаем занесенное администраторами
				continue;
			}
			if((isset($field['rating']))&&($this->my_item[$field['name']]!=NULL)){
				$rating+=$field['rating'];
			}

		}
		return $rating;
	}
	function get_rating(){
	//print_r($this->my_item);
		$rating=0;
		if(count($this->raiting_array)<1){
			return 0;
		}else{
			foreach($this->raiting_array as $k=>$v){
				if(!isset($this->my_item[$k])){
					$this->my_item[$k]=0;
				}else{
					$rating+=doubleval($this->my_item[$k])*$v;
				}
			}
			return $rating;
		}
	}
}
?>
