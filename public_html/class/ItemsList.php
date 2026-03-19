<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 31.05.2015
 * Time: 14:41
 *
 * работает со списками
 *
 *
 * НЕ НРАВИТСЯ
 * дважды работаем с setting ами
 *
 *
 */

class ItemsList{

    /**
     * @var ItemNew
     */
    public $item;//protected
    public $errors=[];//общие ошибки
    public $error_rows=[];
    public $list_settings;//структура для вывода таблицей
    public $list_fields;//поля для показа
    public $entity;

    public $load_fields;//что будем загружать

    public $items=[];//строки данных

    public $limit;
    public $totalpages;
    public $totalrows;
    public $pagenum;
    public $sort_base;//по какому полю идет сортировка
    public $sort_dop;//в какую сторону сортируем

    public $qs_array=[];//для WHERE
    public $paths;//пути инициализируем снаружи TODO сделать универсальней и не здесь

    public function setSettings(array $settings){
        $this->item = new ItemNew();
        $this->item->setSettings($settings);
        $this->makeListFields();//TODO может вызывать извне
        $this->list_settings = $this->item->getSetting('list');
        $this->entity = $this->item->getSetting('entity');
        if(!$this->entity){
            die('Неверная конфигурация entity');
        }
    }

    public function makeListFields(){
        if(count($this->list_fields)>0){
            return true;
        }
        //var_dump($this->item->settings);
        if (isset($this->item->settings['fields']) && is_array($this->item->settings['fields'])){
            //всё хорошо записываем
            foreach ($this->item->settings['fields'] as $field){
                if(isset($field['viev_list'])){
                    if (isset($field['name'])){// обязательное поле
                        $this->list_fields[$field['name']] = $field;
                    }else{
                        $this->error('Имя поля не задано. Работа невозможна.');
                        return false;
                    }
                }
            }
        }else{
            $this->error('Конфигурация для показа списка не задана');//TODO использовать и номер ошибки
            return false;
        }
        return true;
    }

    public function deleteItems(array $get){
        //массовое удаление выбранных итемов по параметрам
        if (!$this->item->makeTableFields()){
            return false;
        }
        $where=$this->makeWhere($get);
        if($where!=''){
            $where=" WHERE ".$where;
        }
        $count=0;
        $sql="DELETE t FROM ". $this->item->table. ' t '.$where;
        $db = DB::getInstance();
        try {
            //throw new PDOException('Проверка ошибки');
            $count = $db->exec($sql);

        } catch (PDOException $e) {
            //$this->error="Ошибка". $e->getMessage();
        	error_log($e->getMessage());
        	return false;
        }

        //$sql= 'UPDATE '.$this->item->getSetting('table')." SET ".implode(', ',$set).

        //$sql="SELECT ".implode(', ', $select)." FROM ". $this->item->table. ' t '.$where.$sort.$limit;
        return $count;
    }


    //обновляются не все поля, а только которые есть в list_fields и table_fields
    // и причем из значимого списка. Оч.сложно, но проще пока не придумал.
    public function updateList(array $data){
        $upd_fields=[];
        if (!$this->item->makeTableFields()){
            return false;
        }
        //вычислаем пересечения table_fields и list_fields
        //$newarr= array_intersect(array_keys ($this->item->table_fields), array_keys ($this->list_fields));
        //определяем, что будем обновлять

        foreach ($this->list_fields as $field){
            if(isset($this->item->table_fields[$field['name']])){//есть в таблице
                if(isset($field['format'])){//только что запиывается
                    if (isset($field['name'])){// обязательное поле
                        //определяем по viev_list что записывать TODO хотя стратегически это не правильно
                        if (in_array($field['viev_list'],array('text', 'checkbox','select','radio','textarea','hidden','txt_hidden','key' ))){
                            $upd_fields[$field['name']] = $field;
                        }
                    }else{
                        $this->error('Имя поля не задано. Работа невозможна.');
                        return false;
                    }
                }
            }
        }

        $this->item->table_fields=$upd_fields;

        //ЭТО ПРОВЕРКА  только статика
        if(is_array($data)){
            $this->items=[];//на случай если это не пустое //TODO а зачем заливать в items? все равно будем перезагружаться ИЛИ нет?
            foreach ($data as $k=>$v) {//TODO какая роль  $k ???
                $this->item->clearData();
                if(!$this->item->checkAndFillData($v,$this->item)){
                    $this->errors=$this->item->errors;
                    return false;//серьеная ошибка
                }
                if (count($this->item->error_fields)>0){//сохраняем ошибки
                    $this->error_rows[$k]=$this->item->error_fields;
                }
                $this->items[$k]=$this->item->my_item;
            }
        }

        //оставляем только статику



        $db=DB::getInstance();
        $key_name=$this->item->getKeyName();
        unset($upd_fields[$key_name]);//удаляем ИД


        if(count($upd_fields)>0){
            //  именованные метки
            $set=[];
            foreach ($upd_fields as $kf=> $field){
                $set[]=$kf." = :".$kf;
            }
            $sql= 'UPDATE '.$this->item->getSetting('table')." SET ".implode(', ',$set).
                ' WHERE '.$key_name.'= :'.$key_name;
//            $sql= 'UPDATE '.$this->item->getSetting('table')." SET ".implode('=?, ',array_keys($upd_fields)).
//                '=? WHERE '.$this->item->getKeyName().'=?';
            try {
                $sth = $db->prepare($sql);
                //throw new PDOException('Проверка ошибки');
                foreach ($this->items as $k=>$idata) {
                    if(!isset($this->error_rows[$k])){//если ошибка в строке её не сохраняем
                        $sth->execute($idata);
                    }
                }
            } catch (PDOException $e) {
                $this->error("Ошибка обновления данных ". $e->getMessage());
                error_log($e->getMessage());
            }
        }

        return true;
    }



    public function loadList($get){
        if($this->item->makeTableFields()){
            //$sql="SELECT ".implode(', ', array_keys($this->table_fields))." FROM ".$this->table . " WHERE ".$this->key_name."=".$db->quote($id). " LIMIT 1";
            $db=DB::getInstance();

            //TODO здесь сделать построитель пути
            //unset($get['prs']);
            $this->qs_array=$get;//оставшиеся параметры TODO может не здесь

            if (!empty($_SERVER['QUERY_STRING'])) {//TODO может не так?
                foreach ($this->qs_array as $k=>$v){
                    if($v=='0'){//обнуляем пустые свойства
                        unset($this->qs_array[$k]);
                    }
                }
            }

            //формируем where
            //$where='';
            $where=$this->makeWhere($get);
            if($where!=''){
                $where=" WHERE ".$where;
            }

            $sort='';
            if(isset($get['sort'])){
                $sort = $this->makeSort($get['sort']);
                unset($get['sort']);
            }else{//сортировка по умолчению
                if (isset($this->list_settings['sort'])){
                    $sort = $this->makeSort($this->list_settings['sort']);
                }
            }

            //$startrow=0;
            //номер страницы
            if (isset($get['pn'])) {
                $this->pagenum = intval($get['pn'])-1;
                //проверяем правильная ли страница будет показываться
//                if ($this->pagenum > $this->totalpages){
//                    //если нет, показываем первую страницу
//                    $this->pagenum=0;
//                }
            }

            //TODО может запрашивать не всегда, а например когда нужен листинг
            if (isset($get['tr'])) {
                $this->totalrows = intval($get['tr']);
            } else {
                $query_total="SELECT COUNT(*) FROM ". $this->item->table . " t " .$where;
                try {
                    $this->totalrows = $db->query($query_total)->fetchColumn();
                    $this->qs_array['tr']=$this->totalrows;
                } catch (PDOException $e) {
                    $this->totalrows=0;
                    error_log($e->getMessage());
                }
            }

            //лимиты

            $this->makeLimit($get);

/*            $limit='';
            if(isset($get['limit'])){
                //проверим не выходит ли за максимальный лимит если установлен
                if(isset($this->list_settings['maxlimit'])){
                    if (intval($get['limit']) > intval($this->list_settings['maxlimit'])){
                        $limit=intval($this->list_settings['maxlimit']);//ограничения пол лимиту
                    }else{
                        $limit=intval($get['limit']);
                    }
                }
            }elseif(isset($this->list_settings['limit'])){
                $limit= intval($this->list_settings['limit']);
            }*/

            $limit='';
            if($this->limit){
                $this->totalpages = ceil($this->totalrows/$this->limit)-1;
                $startrow= $this->pagenum * $this->limit;
                $limit =' LIMIT '.$startrow.', '.$this->limit;
            }

            $select=[];
            // формировать список запрашиваемых полей
            foreach ($this->item->table_fields as $field){
                if(isset($this->list_fields[$field['name']])){
                    $select[]="t." .$field['name'];
                }
            }

           // $select = array_keys($this->list_fields);
            array_unshift($select, 't.'.$this->item->getKeyName());//ключ запрашиваем всегда
            $select = array_unique($select);
            //$where должно быть экранировано ранее
            $sql="SELECT ".implode(', ', $select)." FROM ". $this->item->table. ' t '.$where.$sort.$limit;
//               print($sql);
            try {
                //throw new PDOException('Проверка ошибки');
                foreach ($db->query($sql, PDO::FETCH_ASSOC) as $row) {
                    $this->items[]= $row;
                }
            } catch (PDOException $e) {
                $this->error('Ошибка загрузки данных. ' . $e->getMessage()) ;
                return false;
            }
        }else{
            return false;
        }
        return true;
    }

    public function cutFields(array $setmy_item, array $list_fields){//TODO вынести в Item, здесь наследовать
        $new_fields=[];
        foreach ($setmy_item as $k=>$v){//положением в итеме определяется показ столбцов
            if(isset($list_fields[$k]) && $v!='none'){//none для радио
                $new_fields[$k]=$list_fields[$k];
            }
        }
        return $new_fields;
    }

    protected function makeLimit($get){
        if(isset($get['limit'])){
            if($get['limit']=='unlimit'){
                $this->limit=false;
            }else{
                //проверим не выходит ли за максимальный лимит если установлен
                if(isset($this->list_settings['maxlimit'])){
                    if (intval($get['limit']) > intval($this->list_settings['maxlimit'])){
                        $this->limit=intval($this->list_settings['maxlimit']);//ограничения пол лимиту
                    }else{
                        $this->limit=intval($get['limit']);
                    }
                }
            }
        }elseif(isset($this->list_settings['limit'])){
            $this->limit= intval($this->list_settings['limit']);
        }
    }

    protected function makeSort($sort_string){
        $sort='';
        if($sort_string=='rand'){
            $sort=' ORDER BY RAND() ';
        }else{
            $this->sort_base=substr($sort_string,0,-1);
            if(isset($this->item->table_fields[$this->sort_base])){
                $this->sort_dop=substr($sort_string,-1);
                if($this->sort_dop=='d'){
                    $sort=  $this->sort_base." DESC ";
                }elseif($this->sort_dop=='a'){
                    $sort = $this->sort_base." ";
                }
                $sort=' ORDER BY t.'.	$sort;
            }
        }
        return $sort;
    }

    protected function makeWhere($get){
        $where=[];
        $where_all=[];
        $where_str='';
        if(isset($get['or']) && is_array($get['or'])){
            foreach ($get['or'] as $k=>$plainfragment){
                $where[]=$this->makePlainWhere($plainfragment);
            }
            $where_all[] = '('.implode(') OR (', $where).')';
            unset($get['or']);
        }
        if(count($get)>0){
            $result=$this->makePlainWhere($get);
            if($result){
                $where_all[]=$result;
            }
        }
        if (count($where_all)>0){
            $where_str ='('.implode(') AND (', $where_all).')';
        }

        return $where_str;
    }

    protected function makePlainWhere($get){
        $db=DB::getInstance();
        $where=[];
        $compar_array=['equal'=>'=','noequal'=>'<>','over'=>'>','low'=>'<','overequal'=>'=>','lowequal'=>'<='];
        $result='';
        foreach($this->item->table_fields as $field) {
            //TODO вообще не понятно почему viev
            if (isset($field['viev'])) {
                if (isset($get[$field['name']])) {
//                    if(is_array($get[$field['name']])){// IN
//                        $where[] = "t." . $field['name'] . " IN (" . implode(',', DB::quote_array($get[$field['name']])).') ';
//                    }else{
                        if($field['viev'] == 'checkbox'){
                            if ($get[$field['name']] != '0') {//как строка
                                $where[] = "t." . $field['name'] . "=" . $db->quote($get[$field['name']]);
                            }
                        }
                        //FIXME непонятно почему здесь такой список
                        //elseif ($field['viev'] == 'text' || $field['viev'] == 'textskr' || $field['viev'] == 'hidden'|| $field['viev'] == 'select'){
                        else{
                            //if($field['format'] == 'int' || $field['format'] == 'float'){
                                $ev=$get[$field['name']];
                                if(is_array($ev)){
                                    //TODO стандартизировать формат
                                    //compar - знак
                                    //equal равно
                                    //noequal неравно
                                    //over - больше
                                    //overequal - больше и равно
                                    //low - меньше
                                    //lowequal - меньше  и равно

                                    if (isset($ev['compar'])){
                                        if($ev['compar']=='like'){//специально для LIKE
                                            if ($ev['value'] != '') {//как строка
                                                $where[]=$field['name'].' LIKE '.$db->quote('%'.$ev['value'].'%');
                                            }
                                        }else{
                                            $value='';
                                            $compar=$compar_array[$ev['compar']];
                                            if (isset($ev['convert'])){
                                                //случай когда в аргументе поле типа price2<price
                                                //проверяем на наличие такго поля
                                                //TODO внедрить в точное соответствие
                                                foreach($this->item->table_fields as $convert_field) {
                                                    if (isset($convert_field['viev']) && $convert_field['name']==$ev['value']) {
                                                        //такое поле есть
                                                        $value = "t." .$ev['value'];
                                                    }
                                                }

                                            }else{
                                                $value = $db->quote($ev['value']);
                                            }
                                            if($value!=''){
                                                $where[]="t." .$field['name'].$compar.$value;
                                            }




                                        }

                                    }else{//это IN
                                        $where[] = "t." . $field['name'] . " IN (" . implode(',', DB::quote_array($get[$field['name']])).') ';
                                    }
                                }else{//точное соответствие
                                    $where[]="t." .$field['name'].'='.$db->quote($get[$field['name']]);
                                }

//                            }else{
//                                if ($get[$field['name']] != '') {//как строка
//                                    $where[]=$field['name'].' LIKE '.$db->quote('%'.$get[$field['name']].'%');
//                                }
//                            }
                        }
//                    }


                }
            }
        }
        if(count($where)>0){
            $result=implode(' AND ', $where);
        }

        return $result;

    }


    /* ЗЕРКАЛА ИТЕМА */
    public function getKeyName(){
        //TODO проверка на ошибку
        return $this->item->getKeyName();
    }
    public function getSetting($name){
        return $this->item->getSetting($name);
    }
    protected function error($message){
        $this->errors[]=$message;
    }

    /**
     * Сортируем итемы по одному из полей
     * @param $fieldname
     * @return Closure
     */
    public function sortItems($fieldname){
        return function ($a, $b) use ($fieldname) {
            //return strnatcmp($a[$key], $b[$key]);
            if ($a[$fieldname] == $b[$fieldname]) {
                return 0;
            }
            return ($a[$fieldname] < $b[$fieldname]) ? -1 : 1;
        };
    }




}
