<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 02.07.2016
 * Time: 21:54
 */

class History {
    //'newerror','error','ok','newok','alert','dangers'


    const ERROR     = 'error';// только в сессию
    const ERROR_NEW     = 'newerror';// в сессию и если доступно в БД
    const DANGERS     = 'dangers';// только в БД

    const OK     = 'ok';   // только в сессию
    const OK_NEW = 'newok'; // в сессию и если доступно в БД
    const INFO = 'info'; // только в БД


//    //не записываются в БД, только показываются
//    const ALERT     = 'alert';

    private $history=[];
    private $table;
    private $entity;
    private $key_name;

    //TODO все оповещения проводить через этот класс, использовать сессии в том числе

/*
    const EMERGENCY = 'emergency'; Авария, система неработоспособна.
    const ALERT     = 'alert'; Тревога, меры должны быть предприняты незамедлительно.
    * Примеры: весь веб-сайт недоступен, БД недоступна и т.д. Вплоть до
    * отправки SMS-сообщения ответственному лицу.
    *
    const CRITICAL  = 'critical'; Критическая ошибка, критическая ситуация.
     * Пример: недоступен компонент приложения, неожиданное исключение.
     *
    const ERROR     = 'error';     * Ошибка на стадии выполнения, не требующая неотложного вмешательства,
    * но требующая протоколирования и дальнейшего изучения.
    *
    const WARNING   = 'warning';     * Предупреждение, нештатная ситуация, не являющаяся ошибкой.
     * Пример: использование устаревшего API, неверное использование API,
     * нежелательные эффекты и ситуации, которые, тем не менее,
     * не обязательно являются ошибочными.
     *
    const NOTICE    = 'notice'; Замечание, важное событие.
    const INFO      = 'info'; Информация, полезные для понимания происходящего события.
     * Пример: авторизация пользователя, протокол взаимодействия с БД.
    const DEBUG     = 'debug'; Детальная отладочная информация.

*/

    function __construct($entity, $key_name, $table = null)
    {
        //$key_name - потом убрать
        $this->entity = $entity;
        $this->table = $table;
        $this->key_name = $key_name;
    }


    /**
     * отдает только с сессии
     * @param int $id
     * @return array
     */
    public function getHistory($id = 0){
        $history =[];
        if(isset($_SESSION['history'][$this->entity][$id])){
            $history = $_SESSION['history'][$this->entity][$id];
            $this->unsetHistory($id);
        }
        return $history;
    }

    public function getHistoryDb($id=0){
        $history =[];
        if($this->table && $id>0){
            $db = DB::getInstance();
            $sql = "SELECT * FROM ".$this->table." WHERE id_order=".$db->quote($id)." ORDER BY timeadd";
            try {
                $history=$db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
            } catch (PDOException $e) {
                //$this->error="Ошибка". $e->getMessage();
                die($e->getMessage());
            }
        }
        return $history;
    }

    public function setHistory($level, $message, $id = 0 , $user = ''){
        //$history[]=$message;

        //определяем пользователя
        if($user == ''){//если не определено кто
            if(isset($_SERVER['REMOTE_USER'])){
                $user= $_SERVER['REMOTE_USER'];
            }
        }
        //В зависимости от LEVEL
        //МОЖЕТ записываться в сессию - для вывода после сохранения или изменения
        //в базу данных для хранения
        //FIXME пока только в сессию
        if(in_array($level, [self::OK, self::OK_NEW])){
            //записываем только self::OK упрощаем
            $_SESSION['history'][$this->entity][$id][self::OK][]=$message;

        }elseif(in_array($level, [self::ERROR, self::ERROR_NEW])){
            $_SESSION['history'][$this->entity][$id][self::ERROR][]=$message;
        }
        //БАЗА
        //'newerror','error','ok','newok','newalert','alert','newdangers','dangers'
        if($id != 0) {//без номера не записываем
            $dblevel = '';
            if (in_array($level, [self::INFO, self::OK_NEW])) {
                $dblevel = 'ok';
            }
            if (in_array($level, [self::DANGERS, self::ERROR_NEW])) {
                $dblevel = 'error';
            }
            if ($dblevel != '') {
                //записываем массив, чтобы потом в БД бд
                //`id_order` `timeadd` `user` `status` `history` `level`
                $this->history[]=['id_order'=>$id, 'user'=>$user,  'history'=>$message,  'level'=>$dblevel];
            }
        }else{
            error_log('Popytka zapisi v istoriiu bez nomera');
        }

    }

    public function saveDb(){
        if(count($this->history)>0){
            $db = DB::getInstance();
            try {
                //throw new PDOException('Проверка ошибки');
                $sql = "INSERT INTO ".$this->table." (id_order,user,history,level) VALUES (?, ?, ?, ?)";
                $stm = $db->prepare($sql);
                foreach ($this->history as $hdata){
                    //$hd=DB::quote_array($hdata);
                    $stm->execute(array_values($hdata));
                }
            } catch (PDOException $e) {
                //$this->error="Ошибка". $e->getMessage();
                error_log($e->getMessage());
            }
        }
        $this->history=[];//обнуляем
    }

    private function unsetHistory($id){
        unset($_SESSION['history'][$this->entity][$id]);
    }

    function __destruct() {
        //записываем все события
//        if(count($this->history)>0){
//            //print_r($this->history);
//            $this->saveDb();
//            error_log('НЕ была записана история для '. $this->entity);
//        }

         //TODO проверить работу с сессиями
         //
/*        $sql=sprintf("INSERT INTO %s (id_order,timeadd,user,status,history,level) VALUES(%d,NOW(),'%s',%d,'%s','%s') ",
        $this->table_history,$id,$cueser,
            ((isset($this->my_item['status']))?($this->my_item['status']):(0)),
            mysql_real_escape_string($arg),$level);
        mysql_query($sql) or die(mysql_error());*/
    }


}
