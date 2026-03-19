<?php

/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 16.12.2016
 * Time: 23:21
 */
class HtmlFormNew
{
    private $item;
    private $class_block;
    private $form_name = false;
    private $form_action = "";
    private $action_field;
    private $method = "POST";
    private $enctype = "";
    private $key_name;
    private $btns;

    private $add_before;
    private $add_after;

    static $tinyscript;
    /**
     * HtmlFormNew constructor.
     * @param $item
     */
    public function __construct(ItemNew $item)
    {
        $this->item = $item;
        $this->class_block = 'stdform';//по умолчанию

        $form_setting = $this->item->getSetting('form');
        if($form_setting){
            if(isset($form_setting['class'])){
                $this->class_block = $form_setting['class'];
            }
            if(isset($form_setting['name'])){
                $this->form_name = $form_setting['name'];
                //с остальными работаем если задано имя формы
                if(isset($form_setting['action'])){
                    $this->form_action = $form_setting['action'];
                }
                if(isset($form_setting['action_field'])){
                    $this->action_field = $form_setting['action_field'];
                }else{
                    $this->action_field =  $this->form_name.'_action';
                }
                if(isset($form_setting['method']) && $form_setting['method']=='GET'){//другой возможный вариант GET
                    $this->method = 'GET';
                }
                //кнопки
                if(isset($form_setting['btns'])){
                    $this->btns = $form_setting['btns'];
                }



            }
        }
    }

    public function addBefore($string){
        $this->add_before=$string;
    }
    public function addAfter($string){
        $this->add_after=$string;
    }

    public function renderForm(){

    }

    public function render($action_field_value = 'add'){
        $res='';
        $hidden_fields="";
        $tinymceinit="";
        $tiny_conf = [];
        $calendar_init=false;
        $error_fields = $this->item->error_fields;

        foreach($this->item->viev_fields as $field){
            if(isset($field['required'])){
                $field['text'].= '*';
            }
            if($field['viev']== 'imgload'){
                $this->enctype = ' enctype="multipart/form-data" ';//если загружаем картинку, меняем TODO проверить
            }
            if(isset($this->item->my_item[$field['name']])){
                $value = $this->item->my_item[$field['name']];//данные ячейки
            }else{
                $value = '';
            }
            $field['input_name']=$field['name'];
            $field['input_id']=$field['name'];

            //скрытые поля
            if($field['viev']=='key'){
                $this->key_name = $field['name'];
                $hidden_fields .= Html::hidden($value, $field);
                continue;
            }
            elseif($field['viev']=='hidden'){
                $hidden_fields .= Html::hidden($value, $field);
                continue;
            }
            elseif($field['viev']=='datetimeeditable'){
                if($calendar_init ==false){
                    global $gpage;
                    $gpage['js']['calendar']='<script type="text/javascript" src="/js/dhtmlxcalendar51.js"></script>';
                    $gpage['css']['calendar']='<style type="text/css">@import url(/js/dhtmlxcalendar51.css);</style>';
                    $calendar_init=true;
                }
                // проверяем правильность даты
                $str_value=strtotime($value);
                if(intval($str_value)<1){
                    $error_fields[$field['name']]='не установлена';
                }
            }
            elseif($field['viev']=='textskr'){//textskr - поле с затрудненным редактированием
                //если ошибка показываем как текстовый блок
                if (isset($error_fields[$field['name']])){
                    $field['viev'] ='text';
                }

            }
            elseif($field['viev']== 'textarea'){//текстовые поля
                if(isset($field['max_length'])){
                    $contar_name="ost".$field['name'];
                    $ostalos=0;
                    if(isset($this->item->my_item[$field['name']])){
                        $ostalos = mb_strlen($this->item->my_item[$field['name']], 'UTF-8');
                    }
                    $ostalos = $field['max_length']- $ostalos;

                    $field['text'].= ' <div class="'.$this->class_block.'_ost">Осталось <span id="'.$contar_name.'">'.$ostalos.'</span> знаков.</div>';
                    if(!isset($field['freedata'])) $field['freedata']="";
                    $field['freedata'] .= ' onkeyup="Contar(\''.$field['name'].'\',\''.$contar_name.'\',\''.$field['max_length'].'\');" ';
                    $field['freedata'] .= ' onkeypress="Contar(\''.$field['name'].'\',\''.$contar_name.'\',\''.$field['max_length'].'\');" ';
                    $field['freedata'] .= ' onchange="Contar(\''.$field['name'].'\',\''.$contar_name.'\',\''.$field['max_length'].'\');" ';
                }
                elseif(isset($field['tiny'])){//TODO подумать как это можно объединить
                    //подключаем скрипт только один раз
                    if(self::$tinyscript==""){ //TODO проверить как работают когда два поля в одной форме и две формы с такими полями
                        $tinymceinit.='<script language="JavaScript" type="text/javascript" src="/tiny_mce/tinymce.min.js"></script>';
                        self::$tinyscript="defined";
                    }
                    //собираем информацию для инициализации tinymce, включая несколько полей
                    $tiny_conf['id']= $this->item->getKeyValue();
                    $tiny_conf['selectors'][]= 'textarea#'.$field['name'];

                    //img_basename для новых оставляем пустым, далее не привязываем функцию
                    if($action_field_value=='edit'){
                        $tiny_conf['img_basename']=$this->item->getSetting('entity');
                    }


                    if (isset($field['body_class'])) {
                        $tiny_conf['body_class']=$field['body_class'];
                    }
                    if (isset($field['textarea'])) {
                        $tiny_conf['textarea']=$field['textarea'];
                    }

                    if($field['tiny'] == 'short') {
                        $tiny_conf['mode'] = 'short';
                    }else {
                        $tiny_conf['mode'] = 'full';
                    }

                    //инициализация




                   // $tinymceinit.=self::tinyInit($tiny_conf);

//                    $init_array = tinyMceConfig::getConfig($field['tiny']);
//                    $init_array['mode']= "specific_textareas";
//                    $init_array['editor_selector']= $this->class_block."__".$field['viev']."_TMCE".$field['name'];//stdform__textarea_TMCEanons
//                    $this->item->my_item[$field['name'].'_mdfclass']="TMCE".$field['name'];
//                    if (isset($field['body_class'])) {
//                        $init_array['body_class']=$field['body_class'];
//                    }
//
//                    $fileBrowserCallBack = '';
//
//                    if(isset($init_array['plugins']) && strpos($init_array['plugins'],'advimage')){
//                        //определена возможность загрузки картинок
//                        //проверяем сохранен ли итем, чтобы сформировать правильное имя файла
////                            print_r($item);
//                        $id = $this->item->getKeyValue();
//                        if($id>0){
//                            //TODO для каждого раздела использующего свои ID создавать собственное img_basename
//                            $init_array['external_image_list_url']="/file_manager/get_image_list.php?type=img&img_basename=".$img_basename."_".$id;
//                            $init_array['file_browser_callback']= "fileBrowserCallBack";
//
//                            $fileBrowserCallBack=" function fileBrowserCallBack(field_name, url, type, win) {
//                                var connector = \"/file_manager/file_manager.php\";
//                                my_field = field_name;
//                                my_win = win;
//                                switch (type) {
//                                    case \"image\":
//                                        connector += \"?type=img\";
//                                        break;
//                                    case \"media\":
//                                        connector += \"?type=media\";
//                                        break;
//                                    case \"flash\": //for older versions of tinymce
//                                        connector += \"?type=media\";
//                                        break;
//                                    case \"file\":
//                                        connector += \"?type=files\";
//                                        break;
//                                }
//                                connector+=\"&img_basename=".$img_basename."_". $id."\";
//                                window.open(connector, \"file_manager\", \"modal,width=950,height=600,scrollbars=1\");}";
//                        }
//
//                    }
//                    $tinymceinit.=self::tiny_init($init_array,$fileBrowserCallBack);
                }
            }


            $class_tr=$this->class_block.'__tr';
            $class_td = $this->class_block.'__td';//по умолчанию
            $class_el = $this->class_block.'__'.$field['viev'];//по умолчанию для вложенного элемента
            if(isset($field['class'])){
                $class_tr.=' '.$this->class_block.'__tr_'.$field['class'];//модификатор строки
                $class_td.=' '.$this->class_block.'__td_'.$field['class'];//модификатор всего столбца
                $class_el.=' '.$this->class_block.'__'.$field['viev'].'_'.$field['class'];
            }



            //ищем в данных модификатор  и переопределяем и строку, и ячейки и вложенные элементы
            if(isset($this->item->my_item[$field['name']."_mdfclass"])){
                $class_tr.=' '.$this->class_block.'__tr_'.$this->item->my_item[$field['name']."_mdfclass"];//модификатор строки
                $class_td.=' '.$this->class_block.'__td_'.$this->item->my_item[$field['name']."_mdfclass"];//модификатор ячейки
                $class_el.=' '.$this->class_block.'__'.$field['viev'].'_'.$this->item->my_item[$field['name']."_mdfclass"];
            }
            //дополнительные данные только для ячейки
            if(isset($this->item->my_item[$field['name'].'_freedata'])){
                $field['freedata'] = (isset($field['freedata'])?($field['freedata'].' '):'') . $this->item->my_item[$field['name'].'_freedata'];
            }
            //подсвечиваем ошибки
            if (isset($error_fields[$field['name']])){
                $class_el.=' '.$this->class_block.'__'.$field['viev'].'_error';
            }

            //добавляем модификаторы для левого и правого столбца
            $class_tdl = $class_td.' '.$this->class_block.'__td_l';
            $class_tdr = $class_td.' '.$this->class_block.'__td_r';

            $res .= '<tr class="'.$class_tr.'">';
            //название
            $data1='';
            if($field['viev']== 'imgload'){
                if($value!=""){
                    //первью для картинки
                    $field['class'] = $this->class_block.'__imgpreviev';
                    $data1 .= Html::img($value, $field);
                    //готовим к выводу кнопку удаления
                    $value = "Удалить";
                    $class_el=$this->class_block.'__imgdelbutton';
                    $field['freedata']=' onclick="confirmDeleteAction(\''.$field['name'].'\',\''.$this->action_field.'\',\'Удалить фото?\');" ';
                }
            }
            $data1 .= $field['text'];//.((isset($field['required']))?("*"):(""));
            //помощь
            if (isset($field['help'])){
                $data1 .= '<div class="'.$this->class_block.'__help"><div>'.$field['help'].'</div></div>';
            }
            //значение
            $data2='';
            $field['class']=$class_el;
            $data2 .= Html::$field['viev']($value, $field);

            if(isset($field['template'])){
                $res .= '<td class="'.$class_td.'" colspan=2 >';
                $res .= $data1;
                if($data2){
                    $res .= '<br />';
                    $res .= $data2;
                }
                $res .= '</td>';
            }else{
                $res .= '<td class="'.$class_tdl.'">';
                $res .= $data1;
                $res .= '</td>';

                $res .= '<td class="'.$class_tdr.'">';
                $res .= $data2;
                $res .= '</td>';
            }



            $res .= '</tr>';//конец строчного блока

//                "<input name=\"".$field['name']."\" id=\"".$field['name']."\"  class=\"fullwidth".
//                (( in_array($field['name'],$item['error_fields']))?(' error_field'):(''))
//                ."\" type=\"text\" ".((isset($field['max_length']))?("maxlength=\"".$field['max_length']."\""):("")).
//                "value=\"".((isset($item[$field['name']]))?(htmlspecialchars($item[$field['name']],ENT_COMPAT|ENT_HTML401,'cp1251')):(""))."\" />"
        }

        //замыкаем в таблицу
        $res = '<table class="'.$this->class_block.'__table">'.$res.'</table>';

        //добавляем скрытые поля
        $res .= $hidden_fields;

        if($this->form_name){
            //добавляем поле action //используется для переопределения соранения
            $res .= '<input type="hidden" name="'.$this->action_field.'" id="'.$this->action_field.'" value="'.$action_field_value.'"/>';
            //оборачиваем в форму
//            'class'=>'skidka',
//                'name'=>'skidka',
//                'action',
//                'enctype',
//                'method'

            //ошибки
            $error_block = $this->renderError();



            $res = $error_block.'
                    <form class="'.$this->class_block.'__form" 
                        action="'.$this->form_action.'"  
                        name="'.$this->form_name.'"
                        id="'.$this->form_name.'" 
                        method="'.$this->method.'" '.$this->enctype.' 
                        >'.
                $this->add_before.
                $res.
                $this->add_after. '</form>';



            //кнопки
            if ($this->btns !== null){
                $res.= $this->renderButtons($this->btns);
            }


        }
        $res = $res .$tinymceinit.self::tinyInit($tiny_conf);//.$calendar_init.
        return $res;
    }

    public function getErrors(){
        //ошибки
        $all_errors= $this->item->errors;
        foreach ($this->item->error_fields as $error_field){
            $all_errors = array_merge($all_errors,$error_field);
        }
        return $all_errors;
    }
    public function renderError(){
        $error_block='';
        $all_errors = $this->getErrors();
        if(count($all_errors)>0){
            //оборачиваем
            $error_block='<div class="'.$this->class_block.'__errors">'.implode('<br />', $all_errors).'</div>';
        }
        return $error_block;
    }



    private function tinyInit($tiny_conf){
        if(count($tiny_conf)==0){
            return '';
        }
        $tiny_string ="<script>";
        $tiny_string.="tinymce.init({";
        //$tiny_string.="language: 'ru',";
        if(!isset($tiny_conf['selectors']) || !is_array($tiny_conf['selectors']) || count($tiny_conf['selectors'])==0){
            $tiny_string.= "selector: 'textarea',";//всё
        }else{
            $tiny_string.= "selector: '".implode(',',$tiny_conf['selectors'])."', ";
        }
        $tiny_string.="element_format : 'xhtml', ";
        //$tiny_string.="schema: 'html5-strict',";
        if (isset($tiny_conf['body_class'])){
            $tiny_string.= "body_class: '".$tiny_conf['body_class']."', ";
        }
        if (isset($tiny_conf['textarea'])){
            $tiny_string.= "height: ".($tiny_conf['textarea']*15).", ";
        }
        $tiny_string.="relative_urls: false, ";

        $tiny_string.= LocalTinyConfig::tinyConf($tiny_conf);

        $tiny_string.="";
        $tiny_string.="});";
        $tiny_string.="</script>";

        return $tiny_string;
    }

    private function renderButtons($btns){
        $class = 'btns';
        if(isset($btns['class'])){
            $class = $btns['class'];
            unset($btns['class']);
        }
        $res = '<div class="'.$class.'">';

        //сначала смотрим и выводим кнопки по умолчанию
        if (!isset($btns['submit'])){
            $btns['submit']=[];
            //$res .= '<div class="'.$class.'__btn '.$class.'__btn_green" onclick="document.forms[\''.$this->form_name.'\'].submit()">Сохранить</div>';
        }
        //дополняем умолчаниями
        if(!isset($btns['submit']['text'])){
            $btns['submit']['text']='Сохранить';
        }
        if(!isset($btns['submit']['class'])){
            $btns['submit']['class']='green';
        }
        if(!isset($btns['submit']['freedata'])){
            $btns['submit']['freedata']=' onclick="document.forms[\''.$this->form_name.'\'].submit()" ';
        }

        if (!isset($btns['reset'])){
            $btns['reset']=[];
            //$res .= '<div class="'.$class.'__btn '.$class.'__btn_green" onclick="document.forms[\''.$this->form_name.'\'].submit()">Сохранить</div>';
        }
        //дополняем умолчаниями
        if(!isset($btns['reset']['text'])){
            $btns['reset']['text']='Сброс';
        }
        if(!isset($btns['reset']['freedata'])){
            $btns['reset']['freedata']=' onclick="document.forms[\''.$this->form_name.'\'].reset()" ';
        }


//        if (!isset($btns['reset'])){
//            $res .= '<div class="'.$class.'__btn" onclick="document.forms[\''.$this->form_name.'\'].reset()">Сброс</div>';
//        }

        if($this->key_name && isset($this->item->my_item[$this->key_name]) && intval($this->item->my_item[$this->key_name])>0){
            if (!isset($btns['delete'])){
                $btns['delete']=[];
                //$res .= '<div class="'.$class.'__btn '.$class.'__btn_green" onclick="document.forms[\''.$this->form_name.'\'].submit()">Сохранить</div>';
            }
            //дополняем умолчаниями
            if(!isset($btns['delete']['text'])){
                $btns['delete']['text']='Удалить';
            }
            if(!isset($btns['delete']['class'])){
                $btns['delete']['class']='red,left';
            }
            if(!isset($btns['delete']['freedata'])){
                if(!isset($btns['delete']['confirm'])){
                    $btns['delete']['confirm']= 'Удалить?';
                }
                $btns['delete']['freedata']='  onclick="confirmDeleteAction(\'item\',\''.$this->action_field.'\',\''.$btns['delete']['confirm'].'\')" ';
            }
//        if (!isset($btns['delete'])){
//            $res .= '<div class="'.$class.'__btn '.$class.'__btn_red '.$class.'__btn_left" ';
//            $res .= ' onclick="confirmDeleteAction(\'item\',\''.$this->action_field.'\',\'Удалить?\')">Удалить</div>';
//        }
        }else{//это новое, удалить нельзя
            $btns['delete']=['hide'=>'hide'];
        }



        //теперь всй остальное
        foreach ($btns as $btn){
            if (isset($btn['hide'])){
                continue;
            }
            $type='div';
            if (isset($btn['type'])){
                $type = $btn['type'];
            }
            $res .= '<'.$type.' class="'.$class.'__btn ';
            if(isset($btn['class'])){
                //если модификаторов несколько разбиваем запятой
                $classmdf=explode(',',$btn['class']);
                foreach ($classmdf as $mdf){
                    $res.=' '.$class.'__btn_'.trim($mdf);
                }
            }
            $res .= '"';//закрываем классы
            if(isset($btn['freedata'])){
                $res .=$btn['freedata'];
            }
            $res .= '>'.$btn['text'].'</'.$type.'>';
        }


/*        $res = '<div class="'.$class.'">
            <div class="btns__btn" onclick="document.forms[\''.$this->form_name.'\'].reset()">Сброс</div>
            <div class="btns__btn btns__btn_green" onclick="document.forms[\''.$this->form_name.'\'].submit()">';
        if(isset($this->btns['submit'])){
            $res .=$this->btns['submit'];
        }else{
            $res .= 'Сохранить';
        }
        $res .= '</div>';
        //кнопка удаления
        if($this->key_name && isset($this->item->my_item[$this->key_name]) && intval($this->item->my_item[$this->key_name])>0){
            if(!(isset($this->btns['delete']) && $this->btns['delete']=='hide' )){
                $res .= '<div class="btns__btn btns__btn_red btns__btn_left" ';
                $confirm  = 'Удалить';
                if(isset($this->btns['delete_confirm'])){
                    $confirm  = $this->btns['delete_confirm'];
                }
                $res .= ' onclick="confirmDeleteAction(\'item\',\''.$this->action_field.'\',\''.$confirm.'\')"
                    >';

                if(isset($this->btns['delete_text'])){
                    $res .= $this->btns['delete_text'];
                }else{
                    $res .= 'Удалить';
                }
                $res .= '</div>';
            }
        }*/

        $res .= '</div>';

        return $res;
    }
}