<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 18.02.2018
 * Time: 18:55
 */

class StdViev
{
    public $item;//protected
    public  $errors=[];
    public $action_field_value = 'add';//protected
    protected $path;//путь без qs
    protected $qs_array=[];//набор аргументов для qs
    public $history;

    public function __construct(ItemNew $item, array $config){
        $this->item = $item;
        $this->item->setSettings($config);
        //определяем сущность для истории, например история пишется для заказов
        $entity = $this->item->getSetting('entity');
        if(!$entity){
            die('Неверная конфигурация entity');
        }
        $hist_table= $this->item->getSetting('history');
        if($hist_table){
            $this->history= new History($entity, $this->item->getKeyName() , DB_PREFIX . '_' . $hist_table);
        }else{
            $this->history= new History($entity,$this->item->getKeyName());
        }
        $this->path = $_SERVER['PHP_SELF'];
    }

    public function loadPost($post){
        //это только статика
        return $this->item->checkAndFillData($post, $this->item);
    }

    public function routing($get){
        $result=true;
        // $this->path = $_SERVER['PHP_SELF'];//а может определятся и по другому
        $this->qs_array = $_GET;

        if($_SERVER['REQUEST_METHOD']=='POST'){
            //выявляем поле action
            $form_setting = $this->item->getSetting('form');
            if(isset($form_setting['name'])){
                if(isset($form_setting['action_field'])){
                    $action_field = $form_setting['action_field'];
                }else{
                    $action_field =  $form_setting['name'].'_action';
                }

                //проверяем эта форма или нет
                if(isset($_POST[$action_field])){
                    switch ($_POST[$action_field]){
                        case 'edit':
                            //TODO предварительые проверки
                            if($this->loadPost($_POST)){//критических ошибок нет
                                if($this->update([])){
                                    if (count($this->item->errors)==0 && count($this->item->error_fields)==0 ){
                                        //$this->history->setHistory(History::OK,'Сохранено');//TODO историю перенести в потомков
                                        //всё хорошо, перегружаем на ту же самую страницу
                                        header("Location:".$this->getLocation());//.'#'.$form_setting['name']
                                        exit;
                                    }
                                }
                            }
                            $this->item->errors[]= "Ошибка обновления данных";
                            $this->action_field_value = 'edit';
                            break;
                        case 'add':
                            //сохраняем
//                            print_r($_POST);
                            if($this->loadPost($_POST)) {//критических ошибок нет
                                if ($this->save([])) {
                                    //$this->qs_array[$this->item->getKeyName()]=$this->item->my_item['id'];
                                    //$this->history->setHistory(History::OK, '!!!Добавлено', $this->item->my_item['id']);//TODO делать в дочерних классах
                                    header("Location:" . $this->getLocation());
                                    exit;
                                } else {//ошибка при сохранении
                                    //$this->item->errors[] = "Ошибка сохранения данных";
                                }
                            }
                            $this->item->errors[]= "Ошибка сохранения данных";
                            break;
                        case 'delete_item':
                            if($this->delete(intval($get['id']))){
                                header("Location:".$this->getLocation());
                                exit;
                            }else{//ошибка
                                $this->history->setHistory(History::ERROR,'Ошибка удаления '. __CLASS__,$_POST['id']);
                                header("Location:".$this->getLocation());
                                exit;
                            }
                            break;
                        default:
                            //  удаление картинки
                            if(strstr($_POST[$action_field],"delete_")){
                                $act=substr($_POST[$action_field],7);
                                if($this->item->deleteImg($act, intval($get['id']))){
                                    $this->history->setHistory(History::OK,'Картинка удалена успешно',$_POST['id']);
                                }else{
                                    $this->history->setHistory(History::ERROR,'Ошибка удаления картинки',$_POST['id']);
                                }
                                header("Location:".$this->getLocation());
                                exit;
                            }
                            break;
                    }
                }
            }
        }

        if (count($this->item->errors)==0){ //при сохранении или обновлении была ошибка, ничего не загружаем в данные
            //если ошибок не было пытаемся загрузить данные
            if(isset($get['id'])){//ожидвается загрузка данных
                if($get['id']==='0'){//создание нового
                    if ($this->createBlank()){
                        $this->action_field_value = 'add';
                    }else{
                        $result= false;
                    }
                }else{
                    if ($this->load($get['id'])){//может быть и строкой
                        $this->action_field_value = 'edit';
                    }else{//данные не загрузились
                        $this->action_field_value = 'errorload';
                        $result= false;
                    }
                }
            }
        }
        return $result;
    }

    protected function createBlank(){
        return $this->item->createBlank();
    }


    /**
     * @param $id int
     * @return bool
     */
    public function load($id){
        return $this->item->loadItem($id);
    }

    /**
     * мы можем переопределить обработку в наследниках класса
     * @param $post
     * @return bool
     */
    public function update($post){
        //все данные в my_item
        return $this->item->updateItem([]);
    }

    public function save($post){
        //все данные в my_item
        return $this->item->saveItem([]);
    }

    public function delete($id){
        return $this->item->deleteItem($id);// возвращает количество строк, которые были удалены . Если таких строк нет, вернет 0 или false в случае ошибки
    }





    public function getLocation($qs_array=NULL){
        $location=$this->path;
        if(!$qs_array){
            $qs_array=$this->qs_array;
        }
        if(count($qs_array)>0){
            $location .= '?'.http_build_query($qs_array);
        }
        return $location;
    }

    protected function makeNik($id, $name, $nik){
        //проверяем и заполняем ник
        if(($nik == "")&&($name != "")){
            $translit_nik =Html::translit(trim($name));
            $add_nik="";
            do {
                $new_nik=$translit_nik.strval($add_nik);
                $nik = $new_nik;
                if($add_nik==""){
                    $add_nik=2;
                }else{
                    $add_nik=intval($add_nik)+1;
                }
            }while ($this->item->checkUniques("nik", $new_nik, $id)!==false);
        }
        return $nik;
    }

    protected function makeRank(){
        $row = $this->getMaxRank($this->item->getSetting('table'));
        if($row!==false){
            $row = intval($row)+1;
            return $row;
        }
        return false;
    }

    public function getMaxRank($table){
        $sql = "SELECT MAX(rank) FROM ".$table;
        //print $sql;
        $db = DB::getInstance();
        try {
            return $db->query($sql, PDO::FETCH_COLUMN, 0)->fetch();
        } catch (PDOException $e) {
            $this->errors[]="Ошибка определения ранга. ". $e->getMessage();
            error_log("Ошибка определения ранга. ".$e->getMessage());
            return false;
        }
    }
}