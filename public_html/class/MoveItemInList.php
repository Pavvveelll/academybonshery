<?php

/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 24.01.2017
 * Time: 18:13
 */
class MoveItemInList
{
    private $list;
    public $errors=[];

    /**
     * MoveItemInList constructor.
     * @param $list
     */
    public function __construct(ItemsList $list)
    {
        $this->list = $list;
    }


    /**
     * @param array $get
     * @param int $moveid
     * @param int $moveshift
     * @return bool
     */
    public function move(array $get, $moveid, $moveshift){
        //TODO разобраться что за дела
//        if(isset($_POST['movetable'])){
//            $movetable=trim($_POST['movetable']);
//        }else{
//            $movetable= $this->list->getSetting('table');
//        }
        $movetable= $this->list->getSetting('table');

        //TODO разобраться что за дела
//        if(isset($_POST['movewhere'])){
//            $movewhere=$_POST['movewhere'];
//        }else{
//            $movewhere="";
//        }

        //foreach ($this->list->it)
        $get['limit']='unlimit';

        $this->list->list_fields=$this->list->cutFields(['id'=>'yes','rank'=>'yes'],$this->list->list_fields);
        $this->list->loadList($get);
        //TODO проверку добавить
        $shiftarray=$this->list->items;

        $key_name=$this->list->getKeyName();

        while($curitem = current($shiftarray)){
            if($curitem[$key_name] ==$moveid){
                break;
            }
            next($shiftarray);
        }

        $currank=$curitem['rank'];

        $db=DB::getInstance();
        $updsql= "UPDATE ".$movetable." SET rank=? WHERE id=?";
        try {
            // throw new PDOException('Проверка ошибки');
            $sth = $db->prepare($updsql);
            do{
                if($moveshift>0){
                    $curitem=prev($shiftarray);
                    $moveshift--;
                }else{
                    $curitem=next($shiftarray);
                    $moveshift++;
                }
                if($curitem==false)
                    break;

                $sth->execute(array($currank, $curitem[$key_name]));
                $currank=$curitem['rank'];
            }while($moveshift!=0);
            //завершаем
            $sth->execute(array($currank, $moveid));
        } catch (PDOException $e) {
            $this->errors[] = "Ошибка перемещения. ". $e->getMessage();
            error_log($e->getMessage());
        }

        if (count($this->errors)>0){
            return false;
        }else{
            return true;
        }
    }


}