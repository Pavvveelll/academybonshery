<?php

/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 27.12.2016
 * Time: 18:44
 *
 * загрузка, сохранение, обновление данных содержащихся в одной таблице
 *
 *
 * структура error_fields
 * error_fields[fieldname]=>['error1','error2']
 */
class ItemNew
{
    public $settings=[];//TODO сделать только чтение
    public $errors=[];//общие ошибки
    public $error_fields=[];//поля с ошибками для выделения

    //private $history;//объект истории

    public $my_item=[];

    public $viev_fields;
    public $table_fields;//для сохранения в БД
    public $table;
    protected $key_name;

    //public $check_fields;//список полей по которым идет работа в checkAndFillData

    private $pictures = [] ;// массив классов работающий с картинками

    public function setSettings(array $settings){
        $this->settings = $settings;//будем хранить всё и обращаться по мере необходимости
        // для проистого итема обязательны поля fields и table
        $this->viev_fields =[];//обнуляем
        $this->table_fields =[];//обнуляем
        $this->table = null;
        //$this->table = $this->settings['table'];

    }

    public function getSetting($name){//возвращает часть массива $settings
        if (isset($this->settings[$name])){
            return $this->settings[$name];
        }else{
            return false;
        }
    }

    public function makeVievFields(){
        if(count($this->viev_fields)>0){
            return true;
        }
        if (isset($this->settings['fields']) && is_array($this->settings['fields'])){
            //всё хорошо записываем
            foreach ($this->settings['fields'] as $field){
                if(isset($field['viev'])){
                    if (isset($field['name'])){// обязательное поле
                        $this->viev_fields[$field['name']] = $field;
                    }else{
                        $this->error('Имя поля не задано. Работа невозможна.');
                        return false;
                    }
                }
            }
        }else{
            $this->error('Конфигурация для показа не задана');//TODO использовать и номер ошибки
            return false;
        }
        return true;
    }

    public function getKeyName(){
        //FIXME вызовы из потомков очень длинные, сократить
        if(!$this->key_name){
            foreach ($this->settings['fields'] as $field){
                if(isset($field['format']) && $field['format']=="key"){//имя первичного ключа
                    $this->key_name=$field['name'];
                    break;
                }
            }
        }
        return $this->key_name;
    }

    public function getKeyValue(){
        if(isset($this->my_item[$this->key_name])){
            return $this->my_item[$this->key_name];
        }
        return 0;
    }

    public function makeTableFields(){//отбираем поля и таблицу для сохранения в БД
        if(count($this->table_fields)>0){
            return true;
        }
        if (isset($this->settings['fields']) && is_array($this->settings['fields'])){
            if (isset($this->settings['table'])){
                //всё хорошо записываем
                foreach ($this->settings['fields'] as $field){
                    if(isset($field['format'])){
                        if (isset($field['name'])){// обязательное поле
                            $this->table_fields[$field['name']] = $field;
                            if($field['format']=="key"){//имя первичного ключа
                                $this->key_name=$field['name'];
                            }
                        }else{
                            $this->error('Имя поля не задано. Работа невозможна.');
                            return false;
                        }
                    }
                }
                if(!$this->key_name){//TODO проверить!
                    $this->error('Ключевое поле не задано. Работа невозможна.');
                    return false;
                }
                $this->table = $this->settings['table'];//TODO далее обходится без DB_PREFIX
            }else{
                $this->error('Таблица для сохранения в базе данных не задана');
                return false;
            }
        }else{
            $this->error('Конфигурация объекта для сохранения в базе данных не задана');//TODO использовать и номер ошибки
            return false;
        }
        return true;
    }

    /**
     * проверяем данные на соответствие форматов
     * манипуляции с данными будут производится только при сохранении
     *      исключение - checkbox присваивается yes или no
     *                  - неправильный пароль обнуляется
     * могут накапливаться ошибки в полях, в них оставляются ошибочные значения, а правильные поля можем сохранить
     * @param array $data
     * @param ItemNew $item
     * @return bool возвращаем false в случае критических ошибок, не сохраняем
     *
     *
     * TODO сделать публичным? для предварительной проверки ошибок
     *
     * v2
     * задача
     * получаем одномерный ассиативный массив данных и объект по ссылке
     *
     * массив полей по которым идет проверка и формирование данных для записи
     * при наличии ошибок они фиксируются
     * использовать или не использовать ошибочные поля решается вне этой функции
     * возвращается да или нет
     */
    public function checkAndFillData(array $data, ItemNew $item){
        $item->my_item=[];//всегда обнуляем
        $result=true;
        if(!$this->makeTableFields()){
            return false;
        }
        foreach ($item->table_fields as $field){
            //предварительная обработка
            if(isset($data[$field['name']])){//обрезаем данные
                if($field['format']!='htmltext'){
                    $data[$field['name']]=strip_tags($data[$field['name']]);
                }
                $data[$field['name']]=trim($data[$field['name']]);
            }
            //обязательные поля
            if(isset($field['required'])){
                if((!isset($data[$field['name']]))||($data[$field['name']]=="")){
                    $item->error_fields[$field['name']][] = "Поле <strong>".$field['required']."</strong> должно быть заполнено";
                }elseif($field['format']=='htmltext' && (trim(str_replace("&nbsp;","",strip_tags($data[$field['name']])))=="")){//особый случай для html
                    $item->error_fields[$field['name']][] = "Поле <strong>".$field['required']."</strong> должно быть заполнено";
                }
            }
            if(isset($field['unique'])){
                if(isset($data[$field['name']]) && $data[$field['name']]!=''){	//пустые значения на уникальность НЕ проверяем
                    //проверяем есть ли уже эта запись
                    //в других таблицах и в текущей таблице
                    $unique_errors = $item->checkUniques($field['name'],$data[$field['name']],$data[$this->key_name]);
                    if($unique_errors !== false){
                        foreach ($unique_errors as $err){
                            $item->error_fields[$field['name']][]= $err;
                        }
                    }
                }
            }
            //минимальная длина данных, проверяется только если определено, если пустое не проверяется
            if (isset($field['min_length'])){
                $strlendata= mb_strlen($data[$field['name']], "UTF-8");
                if($strlendata>0 && $strlendata < $field['min_length']){
                    $item->error_fields[$field['name']][] = "Поле <strong>".$field['messname']."</strong> должно содержать не менее ". $field['min_length']."  знаков.";
                }
            }

            //максимальная
            if(isset($field['max_length'])){
                $data[$field['name']]=mb_substr($data[$field['name']],0,$field['max_length'], "UTF-8");
            }

            //проверяем по формату сразу заполняя итем
            //здесь перечислены все возможные форматы, динамика в другом классе.
            if ($field['format']=='img'){
                //для картинки свой функционал
                //предзагрузка файла
                if((isset($_FILES[$field['name']]))&&($_FILES[$field['name']]['name'])){
                    $pict =  new imgUpload($field);//инициализируем

                    $pict->filefield = $_FILES[$field['name']];
                    //$picmove->destpath = IMAGE_PATH . "logotype/";//
                    if(!$pict->checkFileTypeSize()){
                        //TODO преверить, как слипаются ерроры
                        $item->error_fields[$field['name']]=array_merge((isset($item->error_fields[$field['name']]))?($item->error_fields[$field['name']]):([]),$pict->errors);
                       // $item->error_fields[$field['name']] += $pict->errors;
                    }else{// пока все правильно
                        if(isset($field['img_strict'])){
                            //проверяем точные размеры
                            switch($field['img_strict']){
                                case "proportion":
                                    if($pict->finfo['0']!=$pict->finfo['1']){
                                        $item->error_fields[$field['name']][] = "Ошибка: файл ".$pict->filefield['name'].' '.$field['img_strict_error'];
                                    }else{
                                        $item->my_item[$field['name']] = $pict->filetyp;//TODO както сделать по нормальному, чтобы два раза одно и тоже не писать
                                    }
                                    break;
                            }
                        }else{
                            $item->my_item[$field['name']] = $pict->filetyp;
                        }
                    }
                    $item->pictures[$field['name']] = $pict;
                }
            }
            elseif($field['format']=='key'){
                //ключ, это обязательное поле TODO ключ должен быть числовой?
                if((!isset($data[$field['name']]))||(!is_numeric($data[$field['name']]))){
                    //общая ошибка
                    $this->error("Произошла ошибка определения идентификатора!");
                    $result=false;
                }else{//ключ для обновления (втч картинок)
                    //$item->my_item['key']=$data[$field['name']];//TODO ? нужны ли эти данные
                    $item->my_item[$field['name']] = $data[$field['name']];
                }
            }elseif($field['format']=='checkbox'){
                if(isset($data[$field['name']])){
                    $item->my_item[$field['name']]="yes";
                }else{
                    $item->my_item[$field['name']]="no";
                }
            }elseif($field['format']=='datetime' || $field['format']=='date'){
                if(isset($data[$field['name']])){//TODO может это не формат?
                    if(!strtotime($data[$field['name']])) {//правильная дата
                        $item->error_fields[$field['name']][] = $field['text']." - ошибка в дате";
                        //ставим начало  эпохи

                    }else{//дата правильная, в итем
                        $item->my_item[$field['name']] = $data[$field['name']];
                    }
                }
//                elseif (isset($field['default']) ){//TODO пока без вариантов
//                    //дата по умолчанию
//                    $item->my_item[$field['name']]=date("Y-m-d H:i:s");
//                }
            }
            else{
                if((isset($data[$field['name']]))&&($data[$field['name']]!="")) {//только значимые поля
                    //специальные форматы полей, которые нужно проверять
                    switch($field['format']){
                        case "url":
                            //TODO https
                            if (!preg_match('/^http:\/\/[-a-zA-Z0-9.]+\.[a-z]{2,4}\/\z/i',$data[$field['name']])){
                                $item->error_fields[$field['name']][] = "Поле Адрес (URL) должно иметь формат http://sait.ru/";
                            }
                            break;
                        case "email":
                            if ( !$this->checkEmail($data[$field['name']]) ){
                                $item->error_fields[$field['name']][] = "Поле E-mail должно иметь формат name@sait.ru";
                            }
                            break;
                        case "password":
                            //				ПАроль
                            //				если добавление то обязательно
                            //				если нет то нет
                            if (!preg_match('/\\b[A-Za-zА-Яа-я0-9]+\\b/', $data[$field['name']])){
                                $item->error_fields[$field['name']][] = "Пароль должен содержать буквы и цифры";
                                $item->my_item[$field['name']] = "";//сбрасываем TODO выбрасывать ошибку?
                            }
                            break;
                        case "text":
                        //case "htmltext":
                            break;//ничего
                        case "int":
                            //print_r(gettype($data[$field['name']]));
                            if(!is_numeric($data[$field['name']])){
                                $item->error_fields[$field['name']][] = $field['text']." - должно быть числом";
                            }
                            break;
                        case "float":
                            //устраняем распространенную ошибку
                            $data[$field['name']]=str_replace(',','.',$data[$field['name']]);

                            if(!is_numeric($data[$field['name']])){
                                $item->error_fields[$field['name']][] = $field['text']." - должно быть числом";
                            }
                            break;

                    }
                }
                //FIXME stripslashes проверить необходимость при multipart
                //TODO пустые кавычки это значимое поле?
                if(isset($data[$field['name']])){//заполняем только значимые поля
                    $this->my_item[$field['name']] = $data[$field['name']];
                }
            }
        }
        return $result;
    }


    public function checkAndFillDataOld(array $data){
        $result=true;
        foreach ($this->table_fields as $field){
            //предварительная обработка
            if(isset($data[$field['name']])){//обрезаем данные
                if($field['format']!='htmltext'){
                    $data[$field['name']]=strip_tags($data[$field['name']]);
                }
                $data[$field['name']]=trim($data[$field['name']]);
            }
            //обязательные поля
            if(isset($field['required'])){
                if((!isset($data[$field['name']]))||($data[$field['name']]=="")){
                    $this->error_fields[$field['name']][] = "Поле <strong>".$field['required']."</strong> должно быть заполнено";
                }elseif($field['format']=='htmltext' && (trim(str_replace("&nbsp;","",strip_tags($data[$field['name']])))=="")){//особый случай для html
                    $this->error_fields[$field['name']][] = "Поле <strong>".$field['required']."</strong> должно быть заполнено";
                }
            }
            //уникальные поля
            if(isset($field['unique'])){
                if(isset($data[$field['name']]) && $data[$field['name']]!=''){	//пустые значения на уникальность НЕ проверяем

                    //проверяем есть ли уже эта запись
                     //в других таблицах и в текущей таблице
                    $unique_errors = $this->checkUniques($field['name'],$data[$field['name']],$data[$this->key_name]);
                    if($unique_errors !== false){
                        foreach ($unique_errors as $err){
                            $this->error_fields[$field['name']][]= $err;
                        }
                    }
                }
            }
            //минимальная длина данных
            if ((isset($field['min_length'])) && (mb_strlen($data[$field['name']], "UTF-8") < $field['min_length'])){
                $this->error_fields[$field['name']][] = "Поле <strong>".$field['name']."</strong> должно содержать не менее ". $field['min_length']."  знаков.";
            }
            //максимальная?



            //проверяем по формату сразу заполняя итем
            //здесь перечислены все возможные форматы, динамика в другом классе.
            if ($field['format']=='img'){
                //для картинки свой функционал
                //предзагрузка файла
                if((isset($_FILES[$field['name']]))&&($_FILES[$field['name']]['name'])){
                    $pict =  new imgUpload($field);//инициализируем

                    $pict->filefield = $_FILES[$field['name']];
                    //$picmove->destpath = IMAGE_PATH . "logotype/";//
                    if(!$pict->checkFileTypeSize()){
                        //TODO преверить, как слипаются ерроры
                        $this->error_fields[$field['name']] += $pict->errors;
                    }else{// пока все правильно
                        if(isset($field['img_strict'])){
                            //проверяем точные размеры
                            switch($field['img_strict']){
                                case "proportion":
                                    if($pict->finfo['0']!=$pict->finfo['1']){
                                        $this->error_fields[$field['name']][] = "Ошибка: ".$field['img_strict_error'];
                                    }else{
                                        $this->my_item[$field['name']] = $pict->filetyp;//TODO както сделать по нормальному, чтобы два раза одно и тоже не писать
                                    }
                                    break;
                            }
                        }else{
                            $this->my_item[$field['name']] = $pict->filetyp;
                        }
                    }
                    $this->pictures[$field['name']] = $pict;
                }
            }
            elseif($field['format']=='key'){
                //ключ, это обязательное поле TODO ключ должен быть числовой?
                if((!isset($data[$field['name']]))||(!is_numeric($data[$field['name']]))){
                    //общая ошибка
                    $this->error("Произошла ошибка определения идентификатора!");
                    $result=false;
                }else{//ключ для обновления (втч картинок)
                    $this->my_item['key']=$data[$field['name']];
                    $this->my_item[$field['name']] = $data[$field['name']];
                }
            }
            elseif($field['format']=='checkbox'){
                if(isset($data[$field['name']])){
                    $this->my_item[$field['name']]="yes";
                }else{
                    $this->my_item[$field['name']]="no";
                }
            }
            else{
                if((isset($data[$field['name']]))&&($data[$field['name']]!="")){//только значимые поля
                    //специальные форматы полей, которые нужно проверять
                    switch($field['format']){
                        case "url":
                            //TODO https
                            if (!preg_match('/^http:\/\/[-a-zA-Z0-9.]+\.[a-z]{2,4}\/\z/i',$data[$field['name']])){
                                $this->error_fields[$field['name']][] = "Поле Адрес (URL) должно иметь формат http://sait.ru/";
                            }
                            break;
                        case "email":
                            if ( !$this->checkEmail($data[$field['name']]) ){
                                $this->error_fields[$field['name']][] = "Поле E-mail должно иметь формат name@sait.ru";
                            }
                            break;
                        case "password":
                            //				ПАроль
                            //				если добавление то обязательно
                            //				если нет то нет
                            if (!preg_match('/\\b[A-Za-zА-Яа-я0-9]+\\b/', $data[$field['name']])){
                                $this->my_item['error_fields'][]=$field['name'];
                                $this->error_fields[$field['name']][] = "Пароль должен содержать буквы и цифры";
                                $data[$field['name']] = "";
                            }
                            break;
                        case "text":
                            break;
                        case "int":
                        case "float":
                            //print_r(gettype($data[$field['name']]));
                            if(!is_numeric($data[$field['name']])){
                                $this->my_item['error_fields'][]=$field['name'];
                                $this->error_fields[$field['name']][] = $field['text']." - должно быть числом";
                            }
                            break;
                        case "datetime"://время добавления
                        case "datetimeeditable"://TODO может это не формат?
                            //ДАТА YYYY-MM-DD HH:MM:SS
                            if (isset($this->my_item[$field['name']])){
                                if(!strtotime($this->my_item[$field['name']])) {//правильная дата
                                    $this->my_item['error_fields'][]=$field['name'];
                                    $this->error_fields[$field['name']][] = $field['text']." - ошибка в дате";
                                }
                            }
                            break;
                    }
                }
                //FIXME stripslashes проверить необходимость при multipart
                if(isset($data[$field['name']])){//заполняем только значимые поля
                    $this->my_item[$field['name']] = $data[$field['name']];
                }

            }
        }
        return $result;
    }


    /**
     * @param $table string таблица в которой проверяется уникальность
     * @param $key_name string имя ключевого которое возвращается как ключ массива
     * @param $field_name string имя поля в котором происходит поиск
     * @param $value string значение которое проверяется на присутствие
     * @return int 0 если уникально
     */
    public function checkUnique($table, $key_name, $field_name, $value){ //$value_type, $value_data, $table=""
        $db=DB::getInstance();
        //проверяем уникальность поля
        $sql= "SELECT ".$key_name." FROM ".$table." WHERE LOWER(".$field_name.")=LOWER(".$db->quote($value).") LIMIT 1";
        //error_log($sql);
        $db = DB::getInstance();
        try {
            $row=$db->query($sql, PDO::FETCH_COLUMN, 0)->fetch();
            if($row!==false){
                return $row;
            }
        } catch (PDOException $e) {
            $this->error('Ошибка базы данных. ' . $e->getMessage()) ;
        }
        return 0;
    }

    public function checkUniques($field_name, $value, $curid = 0){
        $this->makeTableFields();
        $field=$this->table_fields[$field_name];
        $errors = false;
        //проверяем есть ли уже эта запись
        //в других таблицах
        if(isset($field['unique_tables'])) {
            foreach ($field['unique_tables'] as $table => $values) {
                if ($this->checkUnique( $table, $values['key_name'], $values['field_name'], $value) != 0){
                    $errors[] = $value. $values['error_message'];
                }
            }
        }
        //в текущей таблице
        $fid = $this->checkUnique($this->table, $this->key_name, $field['name'], $value);
        if($fid != 0 && $fid != $curid){
            $errors[] = $value.$field['unique'];
        }
        return $errors;
    }

    protected function checkEmail($email){
        //TODO проверить длинные домены
        $temp="/^[_a-zA-Z0-9-](\.{0,1}[_a-zA-Z0-9-])*@([a-zA-Z0-9-]{2,}\.){0,}[a-zA-Z0-9-]{2,}(\.[a-zA-Z]{2,4}){1,2}$/";
        if (!preg_match($temp, $email)){
            //if (!preg_match('/\\A[A-Z0-9._%-]+@[A-Z0-9._%-]+\\.[A-Z]{2,4}\\z/i', $value_data)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @param $field_name
     * @return imgUpload | bool
     */
    private function getPicture($field_name){
        if(isset($this->pictures[$field_name])){
            return $this->pictures[$field_name];
        }
        return false;
    }


    public function createBlank(){
        if($this->makeVievFields()){
            foreach($this->viev_fields as $field){
                if(isset($field['default'])){
                    $this->my_item[$field['name']]=$field['default'];
                }else{
                    if(isset($field['format']) && ($field['format']=="int" || $field['format']=="key" )){
                        $this->my_item[$field['name']]=0;
                    }elseif(isset($field['format']) && $field['format']=="datetimeeditable"){
                        $this->my_item[$field['name']]=date("Y-m-d H:i:s");
                    }else{
                        if(isset($field['name']))
                            $this->my_item[$field['name']]="";
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function clearData(){
        $this->my_item=[];
        $this->error_fields=[];
    }

    /**
     * @param array $data
     * @return bool
     */
    public function updateItem(array $data){
        //TODO должен работать только с готовым my_item, тоесть предварительно checkAndFillData
        // сохранять правильные поля при ошибках в других
        if($this->makeTableFields()){

            if(count($this->my_item)==0){
                //если до этого не заливали my_item то заливаем
                if ($this->checkAndFillData($data, $this)==false){
                    return false;
                }
            }

            //формируем SQL исключая ошибочные поля
            //здесь используем не data а значения из my_item, так как они уже обработаны
            $db =DB::getInstance();
            $sql='';
            $where='';
            foreach ($this->table_fields as $field){
                if(!isset($this->error_fields[$field['name']])){
                    //Если данных в этом поле нет, в БД остается то что было
                    switch($field['format']){
                        case "int":
                            if(isset($this->my_item[$field['name']])){
                                $sql.=$field['name']."=".intval($this->my_item[$field['name']]). ",";
                            }
                            break;
                        case "float":
                            if(isset($this->my_item[$field['name']])){
                                $sql.=$field['name']."=".floatval($this->my_item[$field['name']]). ",";
                            }
                            break;
                        case "password":
                            //сохраняется как хеш
                            //пустые пароли не сохраняем
                            if(isset($this->my_item[$field['name']]) && $this->my_item[$field['name']]!=''){
                                $sql.=$field['name']."=";
                                $sql.=$db->quote($this->generateHach($this->my_item[$field['name']])) . ",";
                            }
                            break;
                        case "text":
                        case "htmltext":
                        case "url":
                        case "email":
                        case "checkbox":// yes или no присвоено ранее
                            if(isset($this->my_item[$field['name']])) {
                                $sql .= $field['name'] . "=" . $db->quote($this->my_item[$field['name']]). ",";
                            }
                            break;
                        case "datetime"://время добавления
                        case "datetimeeditable"://TODO может эо не формат?
                            //ДАТА YYYY-MM-DD HH:MM:SS
                            if (isset($this->my_item[$field['name']])){//TODO проверку вынести в checkAndFillData
                                if(strtotime($this->my_item[$field['name']])) {//правильная дата
                                    $sql .= $field['name'] . "=" . $db->quote($this->my_item[$field['name']]). ",";
                                }
                            }
                            break;
                        case "img":
                            if(isset($this->my_item[$field['name']])){
                                //догружаем картинку
                                $pic = $this->getPicture($field['name']);
                                if ($pic != false) {
                                    //if (isset($this->pictures[$field['name']]) && $this->pictures[$field['name']] instanceof imgUpload) {
                                    if($pic->finishUpload($this->my_item[$this->key_name])!=false){
                                        $sql.=$field['name']."=";
                                        $sql.=$db->quote($this->my_item[$field['name']]). ",";
                                    }else{
                                        if(isset($this->error_fields[$field['name']])){
                                            $this->error_fields[$field['name']] += $pic->errors;
                                        }else{
                                            $this->error_fields[$field['name']] = $pic->errors;
                                        }
                                        unset($this->my_item[$field['name']]);//ошибка загрузки очищаем
                                    }
                                }
                            }
                            break;
                        case "key":
                            $where = $field['name']."=".intval($this->my_item[$field['name']]);
                            //$cur_look_id=intval($this->my_item[$field['name']]);
                            break;
                    }//switch


                }
            }
            if($sql!=''){
                $sql = substr($sql,0,-1);
                $sql = "UPDATE ".$this->table." SET  ".$sql." WHERE ".$where ;
                //die($sql);

                try {
                    //throw new PDOException('Проверка ошибки');
                    $db->exec($sql);
                } catch (PDOException $e) {
                    //$this->error="Ошибка". $e->getMessage();
                    $this->error("Ошибка записи в базу данных. Обратитесь к разработчикам");
                    error_log($e->getMessage());
                    //TODO удалять загруженные картинки?
                }
            }
        }else{
            return false;
        }
        return true;
    }


    /**
     * @param array $data
     * @return bool
     */
    public function saveItem(array $data){
        if($this->makeTableFields()) {
            if(count($this->my_item)==0){
                //если до этого не заливали my_item то заливаем
                if ($this->checkAndFillData($data, $this)==false){
                    return false;
                }
            }
            //сохраняем только в случае полного отсутствия ошибок
            if(count($this->error_fields)==0){
                $db =DB::getInstance();
                $ins1=[];
                $ins2=[];
                foreach ($this->table_fields as $field) {
                    // сохраняеи только значимые поля
                    if(isset($this->my_item[$field['name']])){
                        switch($field['format']){
                            case "int":
                                $ins1[]=$field['name'];
                                if(isset($this->my_item[$field['name']])){
                                    $ins2[]=intval($this->my_item[$field['name']]);
                                }elseif(isset($field['default'])){
                                    $ins2[]=intval($field['default']);
                                }else{
                                    $ins2[]=0;
                                }
                                break;
                            case "float":
                                $ins1[]=$field['name'];
                                if(isset($this->my_item[$field['name']])){
                                    $ins2[]=floatval($this->my_item[$field['name']]);
                                }elseif(isset($field['default'])){
                                    $ins2[]=floatval($field['default']);
                                }else{
                                    $ins2[]=0;
                                }
                                break;
                            case "password":
                                //если не задан, не сохраняется????
                                if(isset($this->my_item[$field['name']])){
                                    $ins1[]=$field['name'];
                                    //сохраняется как хеш
                                    $ins2[]=$db->quote($this->generateHach($this->my_item[$field['name']]));

                                }
                                break;
                            case "text":
                            case "htmltext":
                            case "url":
                            case "email":
                            case "checkbox":// yes или no присвоено ранее
                                $ins1[]=$field['name'];
                                $ins2[]=$db->quote($this->my_item[$field['name']]);
                                break;
                            case "datetime"://время добавления
                            case "datetimeeditable"://TODO может эо не формат?
                                //ДАТА YYYY-MM-DD HH:MM:SS
                                $ins1[]=$field['name'];
                                //Дату проверили раньше
                                $ins2[]=$db->quote($this->my_item[$field['name']]);
                                break;
                            case "img":
                                if(isset($this->my_item[$field['name']])){
                                    //догружать будем после сохранения
                                    $ins1[]=$field['name'];
                                    $ins2[]=$db->quote($this->my_item[$field['name']]);
                                }
                                break;
                            case "key":
                                //key будет формироваться автоматом?
                                //$where = $field['name']."=".intval($this->my_item[$field['name']]);
                                break;
                        }//switch
                    }
                }

                if (count($ins1)>0){
                    $sql = "INSERT INTO ".$this->table." (".implode(',',$ins1).") VALUES (".implode(',',$ins2).")";
                    //die($sql);
                    try {
                        //throw new PDOException('Проверка ошибки');
                        $db->exec($sql);
                        $this->my_item[$this->key_name]=$db->lastInsertId();
                        //$this->setHistory(History::OK, 'Сохранено');// историю ведем вне этого класса

                        //догружаем картинки
                        foreach (array_keys($this->pictures) as $pk ){
                            $pic = $this->getPicture($pk);
                            if($pic->finishUpload($this->my_item[$this->key_name])==false){
                                //TODO проверить как работает с ошибками
                                $this->error_fields[$pk] += $pic->errors;
                                //не загрузилось - удаляем данные о картинке
                                $this->setValue($pk,'NULL',$this->my_item[$this->key_name]);
                            }
                        }
                        return true;
                    } catch (PDOException $e) {
                        //$this->error="Ошибка". $e->getMessage();
                        $this->error("Ошибка записи в базу данных. Обратитесь к разработчикам");
                        error_log($e->getMessage());
                        return false;
                    }
                }else{
                    return false;
                }
            }
        }
        return false;
    }


/*    private function findItem($name, $value){
        //быстрый поиск ключа по параметру
        //если не найден или ошибка возвращает 0
        $id = 0;
        if(trim($name)!='' && trim($value)!=''){
            if($this->makeTableFields()){
                if(isset($this->table_fields[$name])){
                    $db = DB::getInstance();
                    // LIKE?
                    $sql="SELECT ".$this->key_name." FROM ".$this->table. " WHERE ".$name."=".$db->quote($value). " LIMIT 1";
                    try {
                        //throw new PDOException('Проверка ошибки');
                        $res = $db->query($sql, PDO::FETCH_ASSOC)->fetchColumn();
                        if($res){
                            $id =$res;
                        }else{
                            $this->error("Данные не найдены");
                        }
                    } catch (PDOException $e) {
                        //$this->error("Ошибка загрузки данных". $e->getMessage());
                        error_log("Ошибка поиска по полю ".$name." | ".$e->getMessage());
                    }
                }
            }
        }
        return $id;
    }*/

    /**
     * @param $id string|int может быть и строкой или целым
     * @return bool
     */
    public function loadItem($id){
        if($this->makeTableFields()){
            $db = DB::getInstance();
            $sql="SELECT ".implode(', ', array_keys($this->table_fields))." FROM ".$this->table . " WHERE ".$this->key_name."=".$db->quote($id). " LIMIT 1";//TODO может vievfields?

            try {
                //throw new PDOException('Проверка ошибки');
                $res=$db->query($sql, PDO::FETCH_ASSOC)->fetch();
                if($res){
                    $this->my_item=$res;
                }else{
                    $this->error("Данные не найдены");
                    return false;
                }
                // проверяем есть данные или нет?
            } catch (PDOException $e) {
                //$this->error="Ошибка". $e->getMessage();
                $this->error("Ошибка загрузки данных". $e->getMessage());
                //error_log($e->getMessage());
                return false;
            }
        }else{
            return false;
        }
        return true;
    }

    public function loadValue($name, $id){
        if($this->makeTableFields()){
            $db = DB::getInstance();
            $sql = "SELECT `". $name ."` FROM ". $this->table ." WHERE ".$this->key_name."=".$db->quote($id);
            try {
                $row=$db->query($sql, PDO::FETCH_COLUMN, 0)->fetch();
                if($row){
                    return $row;
                }
            } catch (PDOException $e) {
                $this->error('Ошибка загрузки значения. ' . $e->getMessage()) ;
            }
        }

        return false;
    }

    public function setValue($name, $value, $id){
        if($this->makeTableFields()){
            $db = DB::getInstance();
            //TODO а проверку??
            $sql = "UPDATE ".$this->table." SET ".$name.'='.$db->quote($value)." WHERE ".$this->key_name .'='. $db->quote($id);
            try {
                //throw new PDOException('Проверка ошибки');
                $db->exec($sql);
                return true;
            } catch (PDOException $e) {
                //$this->error="Ошибка". $e->getMessage();
                $this->error("Ошибка обновления значения $name. Обратитесь к разработчикам");
                error_log($e->getMessage());
            }
        }
        return false;
    }

    public function getValue($field_name, $id, $keyname, $table){
        $res=false;

        //$this->key_name=$this->getKeyName();
        $db = DB::getInstance();
        //ТОЛЬКО ПРИ ИНИЦИАЛИЗИРОВАННОМ КЛАССЕ, предусмотреть запрос без инициализации
        $sql= "SELECT ".$field_name." FROM ".$table." WHERE ".$keyname."=".$db->quote($id)." LIMIT 1";
        try {
            //TODO prepare placeholder ?
            //throw new PDOException('Проверка ошибки');
            $res = $db->query($sql, PDO::FETCH_ASSOC)->fetchColumn();
        } catch (PDOException $e) {
            //$this->error="Ошибка". $e->getMessage();
            error_log($e->getMessage());
        }
        //Не следует использовать PDOStatement::fetchColumn() для получения булевых полей,
        // так как невозможно отличить значение FALSE от отсутствия оставшихся строк результата.


        return $res;
    }

    public function deleteItem($id){
        //print("delete");
        if($this->makeTableFields()) {
            foreach ($this->table_fields as $field) {
                if ($field['format'] == "img") {//удаляем все картинки
                    $this->deleteImg($field['name'], $id);
                }
            }
            $db = DB::getInstance();
            $sql = "DELETE FROM ".$this->table." WHERE ". $this->key_name ."=".$db->quote($id);
            try {
                //throw new PDOException('Проверка ошибки');
                return $db->exec($sql);// возвращает количество строк, которые были удалены . Если таких строк нет, вернет 0.
            } catch (PDOException $e) {
                $this->error("Ошибка удаления из базы данных. Обратитесь к разработчикам");
                error_log($e->getMessage());
            }
        }
        return false;
    }

    public function deleteImg($field_name, $id){
        if($this->makeTableFields()) {
            $img_type = $this->loadValue($field_name, $id);
            if ($img_type!=false   ) {
                $field=$this->table_fields[$field_name];

                $destpath = ROOT_PATH . $this->table_fields[$field_name]['picture_path'];
                $filename = $field_name . $id;

                //удаляем превьюшки
                if(isset($field['previev'])){
                    foreach($field['previev']as $p){
                        $delfile_path= $destpath .$filename. $p['nameplus']. "." . $img_type;
                        if(file_exists($delfile_path)){
                            if(!unlink($delfile_path)){
                                error_log('Ошибка удаления превью '.$delfile_path);
                            }
                        }
                    }
                }
                //удаляем оригинал если есть
                if(isset($field['origin_path'])){
                    $delfile_path= ROOT_PATH .$field['origin_path'] .$filename. "." . $img_type;
                    if(file_exists($delfile_path)){
                        if(!unlink($delfile_path)){
                            error_log('Ошибка удаления оригинала '.$delfile_path);
                        }
                    }
                }

                //удаляем основной файл
                $delfile_path= $destpath .$filename. "." . $img_type;
                if(file_exists($delfile_path)){
                    if(!unlink($delfile_path)){
                        error_log('Ошибка удаления фото '.$delfile_path);
                    }
                }
                return $this->setValue($field_name,'NULL', $id);
            }
        }
        return false;
    }

    private function generateHach($word){
        //TODO переделать на более безопасное
        return md5($word);
    }

    protected function error($message){
        $this->errors[]=$message;
    }

//    public function getErrors(){
//
//    }

    /*
     * история
     * сохранение информации о действиях
     * сохраняем и ошибки и благодарности
     * для
     * прямого вывода
     * сохранения в сессиях
     * сохранения в базе данных
     * по умоляанию хранятся в памяти
     * после показа удаляются
     * если класс уничтожается деструктором
     * оставшиеся ошибки записываются в сессию или в базу данных
     * куда именно определяется конфигурацией history=>'имя таблицы базы данных"
     * если не определено то в сессию
     *
     */
//    private function setHistory($level, $message){
//        //разобраться
//        if(!$this->history){//инициируем
//            //определяем сущность для истории, например история пишется для заказов
//            $entity = $this->getSetting('entity');
//            if(!$entity){
//                die('Неверная конфигурация entity');
//            }
//            $hist_table= $this->getSetting('history');
//            if($hist_table){
//                $this->history= new History($entity, DB_PREFIX. '_' . $hist_table);
//            }else{
//                $this->history= new History($entity);
//            }
//
//        }
//        $this->history->setHistory($level, $message);
//    }

}