<?php 

//bug_fix
//05 01 2007  prozessupload  line 60 => if(($this->destpath == "") || (!is_dir($this->destpath))){
//11 12 2008 добавлен параметр name_add в функцию create_img для изменения имени файла добавлением


class FileManadger{
		var $filename;
		var $filefield;
		var $filetyp;
		var $destpath;
		var $errorlog;
		//если портрет ширина не должна превышать img_min
		//если ландшафт ширина не должна превышать img_мах
		//если при ланшафте высота превышает img_min то высоту регулируем по img_min
		//если при портрете высота превышает img_max то высоту регулируем по img_max
		public $img_min=250;//
		public $img_max=600;//		
		public $img_width=300;
		public $img_height=300;
		private $orientation;
		var $finfo;
/*[0] = The width of the image. It is integer data type.
[1] = The height of the image. It is an integer data type.
[2] = Image Type Flag. It is an integer data type.
[3] = String for <img> tag (width="xxx" height="xxx"). It is a string data type.
[bits] = Number of bits. It is an integer data type.
[channels] = Number of channels. It is an integer data type.
[mime] = MIME type. It is a string data type.*/
		// konstruktor
		function FileManadger(){
			//$this->maxfilesize = 100000;
			$this->seed = 1;
			@set_time_limit (0);
		}
		
		function checkfiletypesize($f_size=100000, $f_type="picture"){
			//$res=$this->ReparePhpBug();
			//if ($res){
			$res=1;
			if($this->filefield['error']==0){
				if ($this->filefield['name']!= "") {//checks if file exists
					if($this->filefield['size']< $f_size){
					//print $this->filefield['type'];
							if($this->filefield['type']=="image/pjpeg"){
								$this->filetyp="jpeg";		
							}elseif($this->filefield['type']=="image/jpeg"){
								$this->filetyp="jpeg";		
							}elseif($this->filefield['type'] =="image/gif"){
								$this->filetyp="gif";
							}else{
								$res=0;
								$this->errorlog.="<br>Формат файла должен быть jpeg или gif! ";		
								$this->filetyp=NULL;
							}
							$this->finfo = getimagesize($this->filefield["tmp_name"]);
							//var_dump($this->finfo);
					}else{
						$res=0;
						$this->errorlog.="<br>Размер файла превышает допустимый.";
						$this->filetyp=NULL;
					}
				}else{
					$res=0;
					$this->errorlog.="<br>Файл не определен.";
					$this->filetyp=NULL;
				}
			}else{
					$res=0;
					$this->errorlog.="<br>Ошибка загрузки файла. Вероятно недопустимый формат или превышен размер.";
					$this->filetyp=NULL;
			}
			return $res;
		}
		
		function prozessupload(){
			$res=1;
			if(($this->destpath == "") || (!is_dir($this->destpath))){
				$this->errorlog.="Директория назначения не определена или не существует.<br>";
				$res=0;
			}else{
				// copy the file
				$itogfile=$this->destpath . $this->filename . "." . $this->filetyp;
				if(file_exists($itogfile)){
					$this->errorlog .= "Файл обновлен.<br>";
				}
				if (move_uploaded_file($this->filefield['tmp_name'], $itogfile)) {//is_uploaded_file
					$res=1;
					if(chmod($itogfile,0644)==false){
						error_log("Не удалось изменить chmod файла.".$itogfile);
					}
///определяем ориентацию
if($this->finfo[0]>$this->finfo[1]){
	$orientation="landscape";
}else{
	$orientation="portrait";
}

//проверяем нужно ли преобразовывать размер
if(($this->img_width!=0)||($this->img_height!=0)){
	//проверяем размер изображения для доступной памяти 4МБ
	$size_memory=($this->finfo[0]*$this->finfo[1]*$this->finfo['bits']*$this->finfo['channels'])/(1024*1024*8);
	//print $size_memory;
	if($size_memory<4){
		$this->create_previev($this->img_width,$this->img_height,"");
	}else{
		$this->errorlog .= "Размер файла превышает допустимый, в графической программе измените ширину и высоту файла и попробуйте загрузить снова.";
		unlink($itogfile);
	}	
}
				} else {
					$this->errorlog .= "Possible file upload attack! <br>";
					$res=0;
				}
			}
			return $res;
		}
		
function create_obrez($newwidth,$newheight,$p_path, $name_add=""){
//если задано обрезание, пока только квадрат
				//обрезание надо задавать каждый раз
				//рамки устанавливаются жестко
				//определяем размер по минимальному
				//$minsize=min($this->finfo[0],$this->finfo[1]);
//						imagecopyresampled  ($dst_image,$src_image,
//						int $dst_x ,int $dst_y ,int $src_x , int $src_y  , 
//						int $dst_w  , int $dst_h  , int $src_w  , int $src_h  )
		$res=true;
		if(is_array($this->finfo)){
				if($this->finfo[0]>$this->finfo[1]){//ширина больше высоты
					$dst_x=0;
					$dst_y=0;
					$src_x=($this->finfo[0]-$this->finfo[1])/2;
					$src_y=0;
					$dst_w=$newwidth;
					$dst_h=$newheight;
					$src_w=$this->finfo[1];
					$src_h=$this->finfo[1];
				}elseif($this->finfo[0]<$this->finfo[1]){
					$dst_x=0;
					$dst_y=0;
					$src_x=0;
					$src_y=($this->finfo[1]-$this->finfo[0])/2;
					$dst_w=$newwidth;
					$dst_h=$newheight;
					$src_w=$this->finfo[0];
					$src_h=$this->finfo[0];
				}else{//равны
					$dst_x=0;
					$dst_y=0;
					$src_x=0;
					$src_y=0;
					$dst_w=$newwidth;
					$dst_h=$newheight;
					$src_w=$this->finfo[0];
					$src_h=$this->finfo[1];
				}
/*				print("dst_x=".$dst_x."<br />");
				print("dst_y=".$dst_y."<br />");
				print("dst_w=".$dst_w."<br />");
				print("dst_h=".$dst_h."<br />");
				print("src_w=".$src_w."<br />");
				print("src_h=".$src_h."<br />");*/
				$previevpath=$this->destpath.$p_path;	
				$itogfile=$previevpath . $this->filename .$name_add. "." . $this->filetyp;
				//преобразовываем 
				if($this->filetyp=="jpeg"){
					$OldImg=imagecreatefromjpeg($this->filefield['tmp_name']);
					if(($dst_h==$this->finfo[1])&&($dst_w==$this->finfo[0])){//пропускаем преобразование
						//просто копируем файл в директорию
						if (!copy($this->filefield['tmp_name'], $itogfile)) {
							error_log( " It was not possible to copy a file не удалось скопировать файл");
						}
					}else{
						$NewImg=imagecreatetruecolor($dst_w,$dst_h);
						//imagecopyresampled  ($dst_image,$src_image,
//						int $dst_x ,int $dst_y ,int $src_x , int $src_y  , 
//						int $dst_w  , int $dst_h  , int $src_w  , int $src_h  )
						imagecopyresampled ($NewImg,$OldImg,$dst_x,$dst_y,$src_x,$src_y,$dst_w,$dst_h,$src_w,$src_h);
						imagejpeg($NewImg, $itogfile, 80);
					}
				}else{
				
				}
		}else{
			$res=false;
			$this->errorlog.="<br />Неудалось получить информацию о файле. Обратитесь к разработчикам.";
		}		
				
				
				
		if($res!=false){
			if(chmod($itogfile,0644)==false){
						error_log("It was not possible to change chmod.".$itogfile);
			}	
		}
		return 	$res;
}
function create_img($maxwidth,$maxheight, $p_path, $name_add=""){
		$res=true;
		$size_memory=($this->finfo[0]*$this->finfo[1]*$this->finfo['bits']*$this->finfo['channels'])/(1024*1024*8);
		//print $size_memory;
		if($size_memory>4){
			$this->errorlog .= "Размер файла превышает допустимый, в графической программе измените ширину и высоту файла и попробуйте загрузить снова.";
			return false;
		}
		if(is_array($this->finfo)){
				if($maxwidth==0 && $maxheight==0){
					$maxwidth=$this->finfo[0];
					$maxheight=$this->finfo[1];
				}
				if($maxwidth==0) {
					if($maxheight>=$this->finfo[1]){//пропускаем
						$new_width=$this->finfo[0];
						$new_height=$this->finfo[1];
					}else{
						//определяем размер по высоте
						$new_height=$maxheight;
						$new_width=ceil(($this->finfo[0]*$new_height)/$this->finfo[1]);
					}
				}elseif($maxheight==0){
					if($maxwidth>=$this->finfo[0]){//пропускаем
						$new_width=$this->finfo[0];
						$new_height=$this->finfo[1];
					}else{
						//определяем размер по ширине
						$new_width=$maxwidth;
						$new_height=ceil(($this->finfo[1]*$new_width)/$this->finfo[0]);
					}
				}else{
					if(($maxheight>=$this->finfo[1])&&($maxwidth>=$this->finfo[0])){//пропускаем
						$new_width=$this->finfo[0];
						$new_height=$this->finfo[1];
					}else{
						if($this->finfo[0]/$maxwidth <= $this->finfo[1]/$maxheight){
							//определяем размер по высоте
							$new_height=$maxheight;
							$new_width=ceil(($this->finfo[0]*$new_height)/$this->finfo[1]);
						}else if($this->finfo[0]/$maxwidth > $this->finfo[1]/$maxheight){
							//определяем размер по ширине
							$new_width=$maxwidth;
							$new_height=ceil(($this->finfo[1]*$new_width)/$this->finfo[0]);
						}else{
							//косяк
							error_log("kosyak (2) ");
							$new_width=$maxwidth;
							$new_height=$maxheight;
						}
					}
				}
			$previevpath=$this->destpath.$p_path;
			//$previevpath=$this->destpath."previev/";
				//print $previevpath;
				
			$itogfile=$previevpath . $this->filename .$name_add. "." . $this->filetyp;
			if(($this->destpath != "")&&(is_dir($previevpath))){
				//преобразовываем 
				if($this->filetyp=="jpeg"){
					$OldImg=imagecreatefromjpeg($this->filefield['tmp_name']);
					if(($new_height==$this->finfo[1])&&($new_width==$this->finfo[0])){//пропускаем преобразование
						//просто копируем файл в директорию
						if (!copy($this->filefield['tmp_name'], $itogfile)) {
							error_log( " It was not possible to copy a file не удалось скопировать файл");
						}
					}else{
						$NewImg=imagecreatetruecolor($new_width,$new_height);

						imagecopyresampled ($NewImg,$OldImg,0,0,0,0,$new_width,$new_height,$this->finfo[0],$this->finfo[1]);
						imagejpeg($NewImg, $itogfile, 80);
					}
				}else{
					if(($new_height==$this->finfo[1])&&($new_width==$this->finfo[0])){//пропускаем преобразование
						//просто копируем файл в директорию
						if (!copy($this->filefield['tmp_name'], $itogfile)) {
							error_log ("It was not possible to copy a file previev (3)");
						}
					}else{
						//TODO проверить есть ли прозрачность другим способом
						$OldImg=imagecreatefromgif($this->filefield['tmp_name']);
						$NewImg=imagecreatetruecolor($new_width,$new_height);					
						// the following part gets the transparency color for a gif file
						// this code is from the PHP manual and is written by
						// fred at webblake dot net and webmaster at webnetwizard dotco dotuk, thanks!
						$fp = fopen($this->filefield['tmp_name'], "rb");
						$result = fread($fp, 13);
						$colorFlag = ord(substr($result,10,1)) >> 7;
						$background = ord(substr($result,11));
						if ($colorFlag) {
							$tableSizeNeeded = ($background + 1) * 3;
							$result = fread($fp, $tableSizeNeeded);
							$transparentColorRed = ord(substr($result, $background * 3, 1));
							$transparentColorGreen = ord(substr($result, $background * 3 + 1, 1));
							$transparentColorBlue = ord(substr($result, $background * 3 + 2, 1));
						}
						fclose($fp);
						if (isset($transparentColorRed) && isset($transparentColorGreen) && isset($transparentColorBlue)) {
							$transparent = imagecolorallocate($NewImg, $transparentColorRed, $transparentColorGreen, $transparentColorBlue);
							imagefilledrectangle($NewImg, 0, 0, $new_width, $new_height, $transparent);
							imagecolortransparent($NewImg, $transparent);
						}
						//imagecopymerge($NewImg,$OldImg,0,0,0,0,$this->finfo[0],$this->finfo[1],100);
						imagecopyresampled($NewImg,$OldImg,0,0,0,0,$new_width,$new_height,$this->finfo[0],$this->finfo[1]);
						imagegif($NewImg, $itogfile);
					}//if(($new_height==$this->finfo[1])&&($new_width==$this->finfo[0]))
				}
			}else{
				error_log("The directory of purpose is not certain or does not exist (3).");
				$this->errorlog.="<br>Директория назначения не определена или не существует.";
				$res=false;
			}
		}else{
			$res=false;
			$this->errorlog.="<br />Неудалось получить информацию о файле. Обратитесь к разработчикам.";
		}	
		if($res!=false){
			if(chmod($itogfile,0644)==false){
						error_log("It was not possible to change chmod.".$itogfile);
			}	
		}
		return 	$res;	
	}
	
}
?>
