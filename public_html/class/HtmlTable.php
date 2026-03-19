<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 16.08.2015
 * Time: 18:02
 *
 * Таблица формируется на основании настроек
 *
 * Второй режим - РУЧНОЙ
 * снаружи можно формировать в произвольном порядке строки, потом замыкать таблицей
 * или так или так
 *
 */

class HtmlTable {
    public $class_block;
    private $list;
    private $hiddenfields;//здесь будут накапливаться скрытые поля в текстовом виде
    private $add_rows=[];
  //  private $form;//определяем в форме выводится таблица или нет
    //TODO дать возможность выводить таблицу без th
    private $caption;//инициализируется при небоходимости вывода заголовка таблицы
    public $key_name;
    public $entity;
    private $result;//позволяет сначала формировать строки, а только потом выводить таблицу
    private $colspan=0;

    function __construct(ItemsList $list = null){
        $this->class_block = 'std';
        if($list){
            if(isset($list->list_settings['class-block'])){
                $this->class_block= $list->list_settings['class-block'];
            }
            $this->list= $list;

            $this->key_name=$this->list->getKeyName();
            $this->entity=$this->list->entity;
        }
    }



    public function setCaption($caption){
        $this->caption = $caption;
    }

    //можно добавлять несколько дополнительных строк
    public function addRow(array $data,array $fields){
        $this->add_rows[]=array('data'=>$data, 'fields'=>$fields);
    }

    function render($form=''){
        //сама форма определяется вне этого класса. Это влияет пока на то - выводить скрытые поля или нет...

        if(!$this->result){//строим сразу всю таблицу
            $this->makeHeader();
            $this->body();
            $this->makeAddRows();
        }

        $res = '<table class="' . $this->class_block . '__table">';
        $res .=$this->result;
        $res.='</table>';
        if($form != '') {
            $res .= $this->hiddenfields;
        }
        return $res;
    }

    public function body(array $items=null, $fields=null){
        if(is_null($items)){
            $items = $this->list->items;
        }
        if(is_null($fields)){
            $fields = $this->list->list_fields;
        }
        foreach($items as $d) { //строки данных
            $this->tr($d, $fields);
        }
    }


    function makeAddRows(){
        //ДОПОЛНИТЕЛЬНЫЕ СТРОКИ
        if(count($this->add_rows)){
            foreach($this->add_rows as  $row) {
                if(count($row['fields'])>0){
                    //передавались поля
                     $this->tr($row['data'], $row['fields']);
                }else{//используем поля по умолчанию
                     $this->tr($row['data']);
                }
            }
        }
    }

    function renderPlainEdit($curid=0, $form=''){
        $this->form=$form;//сама форма определяется вне этого класса. Это влияет пока на то - выводить скрытые поля или нет...
        //перебираем данный и в зависимости от $curid
        //выводим или текстом или как форма
        //Текстовая версия
        $plain_fields=[];
        foreach($this->list->list_fields as $field) {
            if(isset($field['viev_list']) &&  $field['viev_list']!='hidden'){
                $field['viev_list']='txt';
                $plain_fields[$field['name']]=$field;
            }
        }
        $this->makeHeader();

        //$res = '<table class="' . $this->class_block . '__table">';
        //$res .= $this->makeHeader();
        foreach($this->list->items as $data) {
            if($data[$this->key_name]==$curid){
                 $this->tr($data);
            }else{
                //меняем значения
                foreach ($data as $kd=>$vd){
                    if(isset($this->list->list_fields[$kd]) && isset($this->list->list_fields[$kd]['viev_list'])){
                        if($this->list->list_fields[$kd]['viev_list']=='select'){//TODO доделать не только select
                            $data[$kd]=$this->list->list_fields[$kd]['sourse'][$vd];
                        }
                    }
                }
                 $this->tr($data,$plain_fields);
            }
        }
        $this->makeAddRows();
        $res = '<table class="' . $this->class_block . '__table">';
        $res .=$this->result;
        $res.='</table>';
        $res.= $this->hiddenfields;
        return $res;
    }

    private function makeHeader(){
        if ($this->caption) {
            $res = '<caption class="' . $this->class_block . '__caption';
            if(isset($this->list->list_settings['caption-mdfclass'])){
                $res .= ' ' . $this->class_block . '__caption_'.$this->list->list_settings['caption-mdfclass'];
            }
            $res .= '">';
            $res .= $this->caption;
            $res .= '</caption>';
            $this->result .= $res;
        }

        if (!isset($this->list->list_settings['th']) || $this->list->list_settings['th']!='noth') {
            //заголовок таблицы TODO как то коряво
            $this->th();
        }
    }

/*    private function makeHidden(){
        //добавляем скрытые поля
        $hidden_fields='';
        foreach($this->list->items as $d) { //строки данных
            foreach($this->table_hidden_fields as $field){
                if(isset($d[$field['name']])){
                    $value = $d[$field['name']];//данные
                }else{
                    $value = '';
                }
                //если индекс определен добавляем к имени поля индекс, для вывода в полях
                if($this->key_name){
                    $field['input_name']=$this->list->entity .'['.$d[$this->key_name]."][".$field['name']."]";
                    $field['input_id']=$field['name']."_".$d[$this->key_name];
                }else{//нет строк
                    $field['input_name']=$field['name'];
                    $field['input_id']=$field['name'];
                }
                $hidden_fields.=Html::hidden($value,$field);
            }
        }
        return $hidden_fields;
    }*/

    function th($fields=null, $mdfclass=''){//модификатор класса нужен только при прямом вызове
        if(!$fields){
            $fields = $this->list->list_fields;
        }
        $class_tr=$this->class_block.'__row';
        if($mdfclass!=''){//модификатор строки
            $class_tr.=' '.$this->class_block.'__row_'.$mdfclass;
        }
        $res='<tr class="'.$class_tr.'">';

        foreach($fields as $field) {//столбцы по полям
            if(!isset($field['viev_list']) || $field['viev_list']=='hidden'){
                continue;
            }
            $class_th=$this->class_block.'__th';
            if(isset($field['class'])){
                $class_th.=' '.$this->class_block.'__th_'.$field['class'];//модификатор всего столбца
            }
            $res.='<td class="'.$class_th.'">';

            //если определен заголовок, используем его
            if(isset($field['th'])){
                $field['text'] = $field['th'];
            }

            if(isset($field['sort'])){
                $qs_array=$this->list->qs_array;
                $qs_array['sort']=$field['name'].($field['sort']==''?'d':$field['sort']);//по умолчанию
                $sort_class='';
                if($this->list->sort_base==$field['name']){//меняем на другое
                    $qs_array['sort']=$field['name'].($this->list->sort_dop=='d'?'a':'d');
                    $sort_class=' '.$this->class_block . '__sort_'.$this->list->sort_dop;
                }
                $field['text']='<a class="' . $this->class_block . '__link'.$sort_class.'" href="'.$this->list->paths.'?'.http_build_query($qs_array).'">'.$field['text'].'</a>';//? по любому чтото есть в массиве
            }
            if(isset($field['text'])){
                $res.=$field['text'];
            }else{
                $res.='&nbsp;';
            }
            $res.='</td>';
        }
        $res.='</tr>';
        $this->result .= $res;
    }

    function td($data, $field){
        if($field['viev_list']=='blank'){
            $this->colspan++;
            return '';
        }



        //$data - строка данных - массив
        if($field['viev_list']=='rank'){
            //для  передаем ID
            $value = $data[$this->key_name];
        }else{
            if(isset($data[$field['name']])){
                $value = $data[$field['name']];//данные ячейки
            }else{
                $value = '';
            }
        }

        $res='';

        $class_td = $this->class_block.'__td';//по умолчанию
        $class_el = $this->class_block.'__'.$field['viev_list'];//по умолчанию для вложенного элемента
        if(isset($field['class'])){
            //если модификаторов несколько разбиваем запятой
            $classmdf=explode(',',$field['class']);
            foreach ($classmdf as $class){
                $class_td.=' '.$this->class_block.'__td_'.trim($class);//модификатор всего столбца
                $class_el.=' '.$this->class_block.'__'.$field['viev_list'].'_'.trim($class);
            }
//            $class_td.=' '.$this->class_block.'__td_'.$field['class'];//модификатор всего столбца
//            $class_el.=' '.$this->class_block.'__'.$field['viev_list'].'_'.$field['class'];
        }
        //ищем в данных модификатор для ячейки и переопределяем
        if(isset($data[$field['name']."_mdfclass"])){
            //$field['mdfclass'] =$data[$field['name']."_mdfclass"];
            $class_td.=' '.$this->class_block.'__td_'.$data[$field['name']."_mdfclass"];//модификатор ячейки
            $class_el.=' '.$this->class_block.'__'.$field['viev_list'].'_'.$data[$field['name']."_mdfclass"];
        }
        //TODO дополнительный моификатор для ошибочных полей error_fields
        if(isset($field['mdfclass'])){//TODO объединить?
            //$class_td.=' '.$this->class_block.'__td_'.$field['mdfclass'];//модификатор ячейки
            //$class_el.=' '.$this->class_block.'__'.$field['viev_list'].'_'.$field['mdfclass'];
        }
        //подсвечиваем ошибки
        if ($this->key_name && isset($this->list->error_rows[$data[$this->key_name]][$field['name']])){
            $class_el.=' '.$this->class_block.'__'.$field['viev_list'].'_error';
        }
        $colspan='';
        if($this->colspan > 0){
            $colspan= ' colspan="'.($this->colspan + 1).'" ';
        }
        $res .='<td class="'.$class_td.'" '.$colspan.'>';


        if($field['viev_list']== 'imgload'){
            if($value!=""){
                //первью для картинки
                $field['class'] = $this->class_block.'__imgpreviev';
                $res .= Html::img($value, $field);
                //готовим к выводу кнопку удаления
                $value = "Удалить фото";
                $class_el=$this->class_block.'__imgdelbutton';
                $field['freedata']=' onclick="confirmDeleteAction(\''.$field['input_id'].'\',\''.$this->entity.'_action'.'\',\'Удалить фото?\');" ';//$this->action_field
            }

        }


        //$res.=$data;
        $field['class']=$class_el;//переопределяем класс вложенного элемента

        if(isset($data[$field['name'].'_freedata'])){//дополнительные данные только для ячейки
            $field['freedata'] = (isset($field['freedata'])?($field['freedata'].' '):'') . $data[$field['name'].'_freedata'];
        }

        $func=$field['viev_list'];

        $res.=Html::$func($value, $field);//вызов соотв. функции

        $res.='</td>';
        $this->colspan =  0;
        return $res;
    }

    function tr($data,$fields=null){

        if(!$fields){
            $fields=$this->list->list_fields;
        }
        $res='';
        foreach($fields as $field) {//столбцы по полям
            if (isset($field['viev_list'])) {// && ($field['viev_list']!='blank' || $field['viev_list']!='txt' )
                //если индекс определен добавляем к имени поля индекс, для вывода в полях
                if($this->key_name){
                    $field['input_name']=$this->entity .'['.$data[$this->key_name]."][".$field['name']."]";
                    $field['input_id']=$this->entity."_".$field['name']."_".$data[$this->key_name];
                }else{//нет строк
                    $field['input_name']=$field['name'];
                    $field['input_id']=$field['name'];
                }
                if($field['viev_list']=='hidden'){
                    //if($this->form != ''){ по любому формируем
                        if(!isset($data[$field['name']])){
                            $data[$field['name']]='';//TODO может пустое и не вывоить?
                        }
                        $this->hiddenfields .= Html::hidden($data[$field['name']],$field);
                    //}
                }else{
                    $res.=$this->td($data,$field);
                }
            }
        }
        $class_tr=$this->class_block.'__tr';
        if(isset($data['mdfclass'])){//модификатор строки
            $class_tr.=' '.$this->class_block.'__tr_'.$data['mdfclass'];
        }
        if ($this->key_name && isset($this->list->error_rows[$data[$this->key_name]])){
            $class_tr.=' '.$this->class_block.'__tr_error';
        }
        $res='<tr class="'.$class_tr.'">'.$res;
        $res.='</tr>';
        $this->result .= $res;
    }


}
