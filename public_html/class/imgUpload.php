<?php

/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 29.12.2016
 * Time: 16:50
 */
class imgUpload
{
    private $field;//передается поле целиком

    public $filefield;
    public $finfo;
    public $filetyp;
    public $filename;//базовое имя файла, без расширения
    public $destpath;//путь к хранилищу картинок

    public $errors = [];//  ошибки

    /**
     * imgUpload constructor.
     * @param array $field
     */
    public function __construct(array $field)
    {
        $this->field = $field;
    }

    public function finishUpload($id){
        $this->destpath = ROOT_PATH .$this->field['picture_path'];
        $this->filename=$this->field['name'].$id;

        if($this->createImg(
            (isset($this->field['img_width'])?($this->field['img_width']):(0)),
            (isset($this->field['img_height'])?($this->field['img_height']):(0)),
            "","",
            (isset($this->field['watermark'])?(ROOT_PATH.$this->field['watermark']):("")))
        ){//основное загрузилось
            $previev_error=false;
            if(isset($this->field['previev'])){
                if(!is_array($this->field['previev'])){
                    //отрабатываем обратную совместимость синтаксиса 'previev'=>80
                    if($this->createImg($this->field['previev'],$this->field['previev'],"previev/")==false){
                        $previev_error=true;
                    }
                    error_log('TODO исправьте синтаксис img previev');
                }else{
                    //новый синтаксис двойной массив
                    //'nameplus'=>'mal','width'=>80,'height'=>80
                    foreach($this->field['previev'] as $p){
                        if(isset($p['obrez'])){
                            if($this->createObrez($p['width'],$p['height'],"",$p['nameplus'])==false){
                                $previev_error=true;
                            }
                        }else{
                            if($this->createImg($p['width'],$p['height'],"",$p['nameplus'],((isset($p['watermark']))?(ROOT_PATH.$p['watermark']):("")))==false){
                                $previev_error=true;
                            }
                        }
                    }
                }
            }
            if($previev_error != false){//ошибки
                return false;
            }
        }else{
            return false;
        }
        return true;
    }


    /**
     * @param int $f_size
     * @return bool
     */
    public function checkFileTypeSize($f_size = 1512000)
    {//максимальный размер файла, определяется выделенной памятью
        $result = true;
        if ($this->filefield['error'] == 0) {
            if ($this->filefield['name'] != "") {//checks if file exists
                if ($this->filefield['size'] < $f_size) {
                    //print $this->filefield['type'];
                    if ($this->filefield['type'] == "image/pjpeg") {
                        $this->filetyp = "jpeg";
                    } elseif ($this->filefield['type'] == "image/jpeg") {
                        $this->filetyp = "jpeg";
                    } elseif ($this->filefield['type'] == "image/gif") {
                        $this->filetyp = "gif";
                    } else {
                        $result = false;
                        $this->errors[] = "Формат файла должен быть jpeg или gif!";//TODO png?
                        $this->filetyp = NULL;
                    }
                    $this->finfo = getimagesize($this->filefield["tmp_name"]);
                    //var_dump($this->finfo);
                } else {
                    $result = false;
                    $this->errors[] = "Размер файла превышает допустимый.";
                    $this->filetyp = NULL;
                }
            } else {
                $result = false;
                $this->errors[] = "Файл не определен.";
                $this->filetyp = NULL;
            }
        } else {
            $result = false;
            $this->errors[] = "Ошибка загрузки файла. Вероятно недопустимый формат или превышен размер.";
            $this->filetyp = NULL;
        }
        return $result;
    }

    public function createImg($maxwidth, $maxheight, $p_path, $name_add = "", $watermark = "")
    {
        //TODO поручить этой функции и обработку превьювок, чтобы не создавать img объекты по нескольку раз
        $size_memory = ($this->finfo[0] * $this->finfo[1] * $this->finfo['bits'] * $this->finfo['channels']) / (1024 * 1024 * 8);
        //print $size_memory;
        if ($size_memory > 4) {
            $this->errors[] = "Размер файла превышает допустимый, в графической программе измените ширину и высоту файла и попробуйте загрузить снова.";
            return false;
        }
        $result = true;
        $itogfile = '';
        if (is_array($this->finfo)) {
            if ($maxwidth == 0 && $maxheight == 0) {
                $maxwidth = $this->finfo[0];
                $maxheight = $this->finfo[1];
            }
            if ($maxwidth == 0) {
                if ($maxheight >= $this->finfo[1]) {//пропускаем
                    $new_width = $this->finfo[0];
                    $new_height = $this->finfo[1];
                } else {
                    //определяем размер по высоте
                    $new_height = $maxheight;
                    $new_width = ceil(($this->finfo[0] * $new_height) / $this->finfo[1]);
                }
            } elseif ($maxheight == 0) {
                if ($maxwidth >= $this->finfo[0]) {//пропускаем
                    $new_width = $this->finfo[0];
                    $new_height = $this->finfo[1];
                } else {
                    //определяем размер по ширине
                    $new_width = $maxwidth;
                    $new_height = ceil(($this->finfo[1] * $new_width) / $this->finfo[0]);
                }
            } else {
                if (($maxheight >= $this->finfo[1]) && ($maxwidth >= $this->finfo[0])) {//пропускаем
                    $new_width = $this->finfo[0];
                    $new_height = $this->finfo[1];
                } else {
                    if ($this->finfo[0] / $maxwidth <= $this->finfo[1] / $maxheight) {
                        //определяем размер по высоте
                        $new_height = $maxheight;
                        $new_width = ceil(($this->finfo[0] * $new_height) / $this->finfo[1]);
                    } else if ($this->finfo[0] / $maxwidth > $this->finfo[1] / $maxheight) {
                        //определяем размер по ширине
                        $new_width = $maxwidth;
                        $new_height = ceil(($this->finfo[1] * $new_width) / $this->finfo[0]);
                    } else {
                        //косяк
                        error_log("kosyak (2) ");//TODO что это?
                        $new_width = $maxwidth;
                        $new_height = $maxheight;
                    }
                }
            }

            $previevpath = $this->destpath . $p_path;
            //$previevpath=$this->destpath."previev/";
            //print $previevpath;

            $itogfile = '';
            if (($this->destpath != "") && (is_dir($previevpath))) {
                $itogfile = $previevpath . $this->filename . $name_add . "." . $this->filetyp;
                //преобразовываем
                if ($this->filetyp == "jpeg") {

                    if (($new_height == $this->finfo[1]) && ($new_width == $this->finfo[0])) {//пропускаем преобразование
                        //просто копируем файл в директорию
                        if (!copy($this->filefield['tmp_name'], $itogfile)) {
                            $this->errors[] = "Не удалось скопировать файл";
                            $result = false;
                        } else {
                            if ($watermark != "") {
                                $img = imagecreatefromjpeg($itogfile);
                                if($this->setWatermark($img, $watermark)){
                                    imagejpeg($img, $itogfile, 90);
                                }else{
                                    $result = false;//не удалось нанести водяной знак, значит не удалось ничего
                                }
                            }
                        }
                    } else {
                        $OldImg = imagecreatefromjpeg($this->filefield['tmp_name']);
                        $NewImg = imagecreatetruecolor($new_width, $new_height);
                        imagecopyresampled($NewImg, $OldImg, 0, 0, 0, 0, $new_width, $new_height, $this->finfo[0], $this->finfo[1]);
                        if ($watermark != "") {
                            if ($this->setWatermark($NewImg, $watermark)){
                                imagejpeg($NewImg, $itogfile, 90);
                            }else{
                                $result = false;//не удалось нанести водяной знак, значит не удалось ничего
                            }
                        }else{// без знака
                            imagejpeg($NewImg, $itogfile, 90);
                        }
                    }
                } else {//gif
                    if (($new_height == $this->finfo[1]) && ($new_width == $this->finfo[0])) {//пропускаем преобразование
                        //просто копируем файл в директорию
                        if (!copy($this->filefield['tmp_name'], $itogfile)) {
                            $this->errors[] = "Не удалось скопировать файл gif";
                            $result = false;
                        }
                    } else {
                        //TODO проверить есть ли прозрачность другим способом
                        //TODO отловить ошибки
                        $OldImg = imagecreatefromgif($this->filefield['tmp_name']);
                        $NewImg = imagecreatetruecolor($new_width, $new_height);
                        // the following part gets the transparency color for a gif file
                        // this code is from the PHP manual and is written by
                        // fred at webblake dot net and webmaster at webnetwizard dotco dotuk, thanks!
                        $fp = fopen($this->filefield['tmp_name'], "rb");
                        $result = fread($fp, 13);
                        $colorFlag = ord(substr($result, 10, 1)) >> 7;
                        $background = ord(substr($result, 11));
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
                        imagecopyresampled($NewImg, $OldImg, 0, 0, 0, 0, $new_width, $new_height, $this->finfo[0], $this->finfo[1]);
                        imagegif($NewImg, $itogfile);
                    }//if(($new_height==$this->finfo[1])&&($new_width==$this->finfo[0]))
                }
            } else {
                error_log("The directory of purpose is not certain or does not exist (3).");
                $this->errors[] = "Директория назначения не определена или не существует.";
                $result = false;
            }
        } else {
            $result = false;
            $this->errors[] = "Неудалось получить информацию о файле. Обратитесь к разработчикам.";
        }
        if ($result != false && $itogfile !='') {
            if (chmod($itogfile, 0644) == false) {//TODO надо ли?
                error_log("It was not possible to change chmod." . $itogfile);
            }
        }
        return $result;
    }

    public function createObrez($newwidth,$newheight,$p_path, $name_add=""){
        //если задано обрезание, пока только квадрат
        //обрезание надо задавать каждый раз
        //рамки устанавливаются жестко
        //определяем размер по минимальному
        //$minsize=min($this->finfo[0],$this->finfo[1]);
        //  imagecopyresampled  ($dst_image,$src_image,
        //  int $dst_x ,int $dst_y ,int $src_x , int $src_y  ,
        //  int $dst_w  , int $dst_h  , int $src_w  , int $src_h  )
        $result=true;
        $itogfile='';
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

            $previevpath = $this->destpath . $p_path;
            $itogfile=$previevpath . $this->filename .$name_add. "." . $this->filetyp;
            //преобразовываем
            if($this->filetyp=="jpeg"){
                $OldImg=imagecreatefromjpeg($this->filefield['tmp_name']);
                if(($dst_h==$this->finfo[1])&&($dst_w==$this->finfo[0])){//пропускаем преобразование
                    //просто копируем файл в директорию
                    if (!copy($this->filefield['tmp_name'], $itogfile)) {
                        $this->errors[] = "Не удалось скопировать файл";
                        $result = false;
                    }
                }else{
                    $NewImg=imagecreatetruecolor($dst_w,$dst_h);
                    imagecopyresampled ($NewImg,$OldImg,$dst_x,$dst_y,$src_x,$src_y,$dst_w,$dst_h,$src_w,$src_h);
                    imagejpeg($NewImg, $itogfile, 90);
                }
            }else{

            }
        }else{
            $this->errors[] = "Неудалось получить информацию о файле. Обратитесь к разработчикам";
            $result = false;
        }


        if ($result != false && $itogfile !='') {
            if (chmod($itogfile, 0644) == false) {//TODO надо ли?
                error_log("It was not possible to change chmod." . $itogfile);
            }
        }
        return 	$result;
    }


    /**
     * @param resource $image
     * @param $watermark string полный путь к водяному знаку в формате png
     * @return bool
     */
    private function setWatermark($image, $watermark){
        try {
            $wmData = getimagesize($watermark);
            if($wmData!==false){
                $wmWidth = $wmData[0];
                $wmHeight = $wmData[1];
                $wmImg = imagecreatefrompng($watermark);
                if($wmData!==false){
                    if (imagesavealpha($wmImg, true)){
                        if(imagecopy($image, $wmImg, 0, 0, 0, 0, $wmWidth, $wmHeight)){
                            imagedestroy($wmImg);
                        }else{
                            $this->errors[]="Ошибка нанесения водяного знака";
                            return false;
                        }
                    }else{
                        $this->errors[]="Ошибка создания водяного знака";
                        return false;
                    }
                }else{
                    $this->errors[]="Ошибка файла водяного знака";
                    return false;
                }
            }else{
                $this->errors[]="Ошибка загрузки файла водяного знака";
                return false;
            }
        } catch (Exception $e) {
            $this->errors[]="Ошибка обработки файла водяного знака";
            return false;
        }
        return true;
    }


}