<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 27.05.2015
 * Time: 16:26
 *
 * TODO помечать обязательные поля
 *
 *
 */

class HtmlOld {
    static function form(Item $item,$fields){
        $data=$item->my_item;
        $class_block=$fields['form']['class-block'];
//TODO multipart
        $res='<form method="post" class="'.$class_block.'__form"'.
            ((isset($fields['form']['name']))?(' name="'.$fields['form']['name'].'"'):('')).
            ' action="'.((isset($fields['form']['action']))?($fields['form']['action']):('')).'"'.
            '>';
        $res.='<table class="'.$class_block.'__table">';

        $hidden_fields='';
        foreach($fields as $field){
            if(isset($field['viev'])){
                //готовим данные
                if(isset($data[$field['name']])){
                    $value=$data[$field['name']];
                }elseif(isset($field['default'])){
                    $value= $field['default'];
                }else{
                    $value='';//TODO а если 0
                }
                //вывод
                if($field['viev']=='hidden'){
                    $hidden_fields.=self::hidden($value,$field);
                }else {
                    $res .= '<tr class="'.$class_block.'__row">';
                    $res .= '<td class="'.$class_block.'__cell '.$class_block.'__cell_left">';//левая ячейка
                    $res .= '<div class="'.$class_block.'__label">';
                    $res .= $field['text'];
                    if(isset($field['required'])) $res .= '<div class="'.$class_block.'__asteriks">*</div>';
                    $res .= '</div>';
                    $res .= '</td>';
                    $res .= '<td class="'.$class_block.'__cell '.$class_block.'__cell_right">';//правая ячейка
                    $field['class'] = $class_block.'__'.$field['viev'];
                    //определяем ошибки
                    if(isset($data['error_fields'][$field['name']]))
                        $field['class'].=' '.$class_block.'__'.$field['viev'].'_error';
                    $res .= self::$field['viev']($value, $field);//вызов соотв. функции
                    $res .= '</td>';
                    $res .= '</tr>';
                }
            }
        }
        $res.='</table>';
        $res.=$hidden_fields;
        // КНОПКИ
        if(isset($fields['form']['buttons'])){
            $class_buttons=$fields['form']['class-buttons'];
            $res.='<div class="'.$class_buttons.'">';
            foreach($fields['form']['buttons'] as $button){
                //класс кнопки с модификаторами
                $button['class'] = $class_buttons.'__button '.$class_buttons.'__button_'.
                    implode(' '.$class_buttons.'__button_', explode(' ',$button['class']));
                $res.=self::div($button['text'],$button);
            }
            $res.='</div>';
        }
        $res.='</form>';
        return $res;
    }

    static function tr($data, $class_block, $class_mod=''){
//        if($class_mod){
//            $dd=1
//        }
        $res='<tr class="'.$class_block.'__row'.
         (($class_mod!='') ? (' '.$class_block.'__row_'.$class_mod) : ('')) .
        '">';
        foreach($data as $d) {
//            if(isset($d[$field['name']."_mdfclass"])){
//                $field['class'].=' '.$class_block.'__'.$field['viev_list'].'_'.$d[$field['name']."_mdfclass"];
//            }
            $res .= '<td class="' . $class_block . '__cell">';
            $res .= $d;
            $res .= '</td>';
        }
        $res.='</tr>';
        return $res;
    }

    static function formTable(ItemsList $list){//$data,$fields
        $class_block = $list->list_settings['class-block'];
        //TODO multipart
        $hidden_fields='';
        $res='<form method="post" class="'.$class_block.'__form"'.
            ((isset($list->list_settings['name']))?(' name="'.$list->list_settings['name'].'"'):('')).
            ' action="'.((isset($list->list_settings['action']))?($list->list_settings['action']):('')).'"'.
            '>';

        $res.='<table class="'.$class_block.'__table">';
        //первая строка с заголовками
        $th_txt=[];
        foreach($list->load_fields as $field){
            if(isset($field['viev_list'])) {
                if($field['viev_list']=='hidden'){
                    //$hidden_fields.=self::hidden($value,$field);
                }else {
                    $sort_znak='';
                    if(isset($field['sort'])){
                        $qs_array=$list->qs_array;
                        $qs_array['sort']=$field['name'].($field['sort']==''?'d':$field['sort']);//по умолчанию
                        if($list->sort_base==$field['name']){//меняем на другое
                            $qs_array['sort']=$field['name'].($list->sort_dop=='d'?'a':'d');
                            $sort_znak='<div class="' . $class_block . '__sort '.$class_block.
                                ($list->sort_dop=='d'? '__sort_d ':'__sort_a').'"></div>';
                        }
                        $field['text']='<a href="'.$list->paths.'?'.http_build_query($qs_array).'">'.$field['text'].'</a>';//? по любому чтото есть в массиве
                    }
                    $th_txt[] = '<div class="' . $class_block . '__txt ' . $class_block . '__txt_head">'.
                        $field['text'].
                        $sort_znak.
                        '</div>';
                }
            }
        }
        $res.=self::tr($th_txt,$class_block,'head');

        foreach($list->items as $d){ //строки данных
            $td_txt=[];
                foreach($list->load_fields as $field){//столбцы по полям
                    if(isset($field['viev_list'])) {
                        $value=$d[$field['name']];


                        if ($field['viev_list'] == 'hidden') {
                            //модифицируем name
                            $field['name']=$field['name']."[".$d['id']."]";//TODO не id  а имя ключевого поля
                            $hidden_fields.=self::hidden($value,$field);
                        } else {


                            $field['class'] = $class_block.'__'.$field['viev_list'];
                            //определяем ошибки
                            if(isset($d['error_fields']) && isset($d['error_fields'][$field['name']])){
                                 //TODO если 2 ошибки?
                                $field['freedata']=' title="'.implode('<br />',$d['error_fields'][$field['name']]).'" ';
                                $field['class'].=' '.$class_block.'__'.$field['viev_list'].'_error';
                            }
                            //модифицируем name
                            $field['name']=$field['name']."[".$d['id']."]";//TODO не id  а имя ключевого поля
                            $td_txt[] = self::$field['viev_list']($value, $field);//вызов соотв. функции
                        }
                    }
                }
            $res.=self::tr($td_txt,$class_block);
        }
        $res.='</table>';
        $res.=$hidden_fields;
        // КНОПКИ
        if($list->list_settings['buttons']){
            $class_buttons=$list->list_settings['class-buttons'];
            $res.='<div class="'.$class_buttons.'">';
            foreach($list->list_settings['buttons'] as $button){
                //класс кнопки с модификатором
                $button['class'] = $class_buttons.'__button '.$class_buttons.'__button_'.
                    implode(' '.$class_buttons.'__button_', explode(' ',$button['class']));
                $res.=self::div($button['text'],$button);
            }
            $res.='</div>';
        }
        $res.='</form>';
        return $res;
    }

    static function simleTable(ItemsList $list){//$data,$fields
        print_r($list->items);
        $class_block = $list->list_settings['class-block'];


        $res='<table class="'.$class_block.'__table">';
        if(isset($list->caption)){
            $res.='<caption class="'.$class_block.'__caption">';
            $res .= '<div class="'.$class_block.'__headtxt">';
            $res .=  $list->caption;
            $res .= '</div>';
            $res.='</caption>';
        }


        //первая строка с заголовками
        $th_txt=[];
        foreach($list->load_fields as $field){
            if(isset($field['viev_list'])) {
                if($field['viev_list']=='hidden'){
                    //$hidden_fields.=self::hidden($value,$field);
                }else {
                    $sort_znak='';
                    if(isset($field['sort'])){
                        $qs_array=$list->qs_array;
                        $qs_array['sort']=$field['name'].($field['sort']==''?'d':$field['sort']);//по умолчанию
                        if($list->sort_base==$field['name']){//меняем на другое
                            $qs_array['sort']=$field['name'].($list->sort_dop=='d'?'a':'d');
                            $sort_znak='<div class="' . $class_block . '__sort '.$class_block.
                                ($list->sort_dop=='d'? '__sort_d ':'__sort_a').'"></div>';
                        }
                        $field['text']='<a href="'.$list->paths.'?'.http_build_query($qs_array).'">'.$field['text'].'</a>';//? по любому чтото есть в массиве
                    }
                    $th_txt[] = '<div class="' . $class_block . '__txt ' . $class_block . '__txt_head">'.
                        $field['text'].
                        $sort_znak.
                        '</div>';
                }
            }
        }
        $res.=self::tr($th_txt,$class_block,'head');

        foreach($list->items as $d){ //строки данных
            $td_txt=[];
            foreach($list->load_fields as $field){//столбцы по полям
                if(isset($field['viev_list'])) {
                    $value=$d[$field['name']];


                    if ($field['viev_list'] == 'hidden') {
                        //модифицируем name
                        $field['name']=$field['name']."[".$d['id']."]";//TODO не id  а имя ключевого поля
                        //$hidden_fields.=self::hidden($value,$field);
                    } else {


/*                        $field['class'] = $class_block.'__'.$field['viev_list'];
                        //определяем ошибки
                        if(isset($d['error_fields']) && isset($d['error_fields'][$field['name']])){
                            //TODO если 2 ошибки?
                            $field['freedata']=' title="'.implode('<br />',$d['error_fields'][$field['name']]).'" ';
                            $field['class'].=' '.$class_block.'__'.$field['viev_list'].'_error';
                        }
//                        if(isset($d[$field['name']."_mdfclass"])){
//                            $field['class'].=' '.$class_block.'__'.$field['viev_list'].'_'.$d[$field['name']."_mdfclass"];
//                        }
                        //модифицируем name
                        $field['name']=$field['name']."[".$d['id']."]";//TODO не id  а имя ключевого поля
                        $td_txt[] = self::$field['viev_list']($value, $field);//вызов соотв. функции*/

                        $td_txt[] = self::td($value,$field);

                    }
                }
            }

            $res.=self::tr($td_txt,$class_block,((isset($d["mdfclass"]))?$d["mdfclass"]:''));
        }
        $res.='</table>';
        return $res;
    }

    static function td($data, $field){
        $res='<td class="">';

        $res.='</td>';
        return $res;
    }

    static function txt($data,$field){
        $res='<span'.
            ((isset($field['class']))?(' class="'.$field['class'].'" '):('')).
            ((isset($field['freedata']))?(' '.$field['freedata'].' '):('')).
            '>';
        $res.=$data;
        $res.='</span>';
        return $res;
    }

    static function div($data,$field){
        $res='<div'.
            ((isset($field['class']))?(' class="'.$field['class'].'" '):('')).
            ((isset($field['freedata']))?(' '.$field['freedata'].' '):('')).
            '>';
        $res.=$data;
        $res.='</div>';
        return $res;
    }

    static function checkbox($data,$field){
        //tovars[2908][kolvo]
        $res = '<input type="checkbox" name="'.$field['name'].'" id="'.$field['name'].'" value="yes" '.
            (($data==='yes')?('  checked="checked" '):('')).
            ((isset($field['class']))?(' class="'.$field['class'].'"'):('')).
            ((isset($field['freedata']))?($field['freedata']):('')).
            '/>';
        return $res;
    }


    static function text($data,$field){
        //tovars[2908][kolvo]
        $res = '<input type="text" name="'.$field['name'].'" id="'.$field['name'].'" value="'.
            htmlspecialchars($data,ENT_COMPAT|ENT_HTML401,'UTF-8') .
            '" '.
            ((isset($field['class']))?(' class="'.$field['class'].'"'):('')).
            ((isset($field['freedata']))?($field['freedata']):('')).
            '/>';
        return $res;
    }
    static function textarea($data,$field){
        //tovars[2908][kolvo]
        $res = '<textarea name="'.$field['name'].'" id="'.$field['name'].'" '.
            ((isset($field['class']))?(' class="'.$field['class'].'"'):('')).
            ((isset($field['freedata']))?($field['freedata']):('')).
            ' rows="'.((isset($field['rows']))?($field['rows']):('')).'"'.
            ' cols="'.((isset($field['cols']))?($field['cols']):('')).'"'.
            '>';
        $res .=$data;
        $res .= '</textarea>';
        return $res;
    }

    static function select($data,$field){
        if((isset($field['sourse']))&&(is_array($field['sourse']))){
            $select_array=$field['sourse'];
        }else{
            $select_array=array();
        }
        $res='<select name="'.$field['name'].'" id="'.$field['name'].'" '.
            ((isset($field['class']))?(' class="'.$field['class'].'"'):('')).
            ((isset($field['freedata']))?($field['freedata']):('')).
            ' >';
        foreach($select_array as $key=>$value){
            $res.=sprintf('<option value="%s" %s >%s</option>',$key,
                ($key==$data)?(' selected="selected"'):(''),$value);
        }
        $res.='</select>';
        return $res;
    }


    static function hidden($data,$field){
        //tovars[2908][kolvo]

        $res = '<input type="hidden" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$data.'"/>';
        return $res;
    }
    static public function ul(Array $array)
    {
        $res = "<ul>";
        foreach ($array as $li) {
            $res .= "<li>$li</li>";
        }
        $res .= "</ul>";
        return $res;
    }

    /**
     * выводим только данные без обрамления
     * @param $data
     * @param $field
     * @return mixed
     */
    static function blank($data,$field){
        return $data;
    }

/*    static public function errorBlock($txt){
        return '<div class="form__error"><span class="form__errortext">'.$txt.'</span></div>';
    }
    static public function okBlock($txt){
        return '<div class="form__ok"><span class="form__oktext">'.$txt.'</span></div>';
    }*/

}
