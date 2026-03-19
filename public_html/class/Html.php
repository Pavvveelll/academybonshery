<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 06.06.2015
 * Time: 22:39
 */

class Html {

//    static function key($data,$field){//TODO убрать
//        return '';
//    }


    static function img($data,$field){
        //в  $data передается путь к картинке,
        //всё остальное в freedata
        //field['class'] формируется во вне и включает в себя все модификаторы
        $res='';
        if($data){
            $class='';
            if(isset($field['class'])){
                $class=' class="'.$field['class'].'"';
            }

            $res.='<img'.
                $class.
                ((isset($field['freedata']))?(' '.$field['freedata'].' '):(''));
            $res.=' src="'.$data.'" ';
            $res.='>';
        }
        return $res;
    }

    static function imgload($value,$field){
        //field['class'] формируется во вне и включает в себя все модификаторы
        $class='';
        if(isset($field['class'])){
            $class=' class="'.$field['class'].'"';
        }
        $res='';
        if ($value==""){
            if(isset($field['max_size']) && !isset($field['multiple'])){
                $res .= '<input name="MAX_FILE_SIZE" value="'.$field['max_size'].'" type="hidden">';
            }
            $res.='<input '.$class.' name="'.$field['input_name'].
                ((isset($field['multiple']))?('[]'):('')).//добавляем брекеты
                '" id="'.$field['input_id'].'" type="file"'.
                ((isset($field['freedata']))?(' '.$field['freedata'].' '):('')).
                ((isset($field['multiple']))?(' multiple="multiple" '):('')).
                '>';
        }else{
           // $res.='<div class="r" style="float: left;"><a href="javascript:confirmDeleteAction(\'foto\',\'action_item\',\'Удалить?\');">Удалить</a></div>';
            //Что будет показываться нужно позаботится заранее
            $res.=self::label($value,$field);
        }

        return $res;
    }

    static function checkbox($value,$field){
        $class='';
        if(isset($field['class'])){
            $class.=$field['class'];
            if(isset($field['mdfclass'])){
                $class.=' '.$field['class'].'_'.$field['mdfclass'];//модификатор ячейки
            }
        }
        if($class!=''){
            $class=' class="'.$class.'"';
        }

        //tovars[2908][kolvo]
        //input_id и input_name формируются синтетически на основании key и name
/*        $res = '<input '.$class.' type="checkbox" name="'.$field['input_name'].'" id="'.$field['input_id'].'" value="yes" '.
            ((isset($field['freedata']))?(' '.$field['freedata'].' '):('')).
            (($value=="yes")?(' checked="checked" '):('')).
            '/>';*/
        $res = '<span '.$class.'><input  type="checkbox" name="'.$field['input_name'].'" id="'.$field['input_id'].'" value="yes" '.
            ((isset($field['freedata']))?(' '.$field['freedata'].' '):('')).
            (($value=="yes")?(' checked="checked" '):('')).
            '/></span>';

        return $res;
    }

    static $colspan;
    static function blank(){
        self::$colspan++;
        return '&nbsp;';
    }
    static function txt($value){
        //просто данные, если нужна обертка используйте label
        return $value;
    }



    static function label($value,$field){
        //field['class'] формируется во вне и включает в себя все модификаторы
        $class='';
        if(isset($field['class'])){
            $class=' class="'.$field['class'].'"';
        }
        $res='<span id="'.$field['input_id'].'"'.
            $class.
            ((isset($field['freedata']))?(' '.$field['freedata'].' '):('')).
            '>';
        $res.=$value;
        $res.=((isset($field['posfix']))?($field['posfix']):(''));
        $res.='</span>';
        return $res;
    }

    static function header(){
        //выводится только текст из поля
        return '';
    }

    static function groppe($data,$field){
        return self::select($data,$field);
    }
    static function select($data,$field){
        $class='';
        if(isset($field['class'])){
            $class.=$field['class'];
            if(isset($field['mdfclass'])){
                $class.=' '.$field['class'].'_'.$field['mdfclass'];//модификатор ячейки
            }
        }
        if($class!=''){
            $class=' class="'.$class.'"';
        }
        //если не определено используем name
        if(!(isset($field['input_name']) && isset($field['input_id']))){
            $field['input_name']=$field['name'];
            $field['input_id']=$field['name'];
        }
        if((isset($field['sourse']))&&(is_array($field['sourse']))){
            $select_array=$field['sourse'];
        }else{
            $select_array=array();
        }
        $res='<select'.
            $class.
            ((isset($field['freedata']))?(' '.$field['freedata'].' '):('')).
            ' name="'.$field['input_name'].'" id="'.$field['input_id'].'">';
        foreach($select_array as $key=>$value){
            $res.='<option value="'.$key.'" '.(($key==$data)?(' selected="selected"'):('')).' >'.$value.'</option>';
        }//foreach
        $res.='</select>';
        return $res;
    }

    static function radio($data,$field,$class='',$id=''){
        $class='';
        if(isset($field['class'])){
            $class.=$field['class'];
            if(isset($field['mdfclass'])){
                $class.=' '.$field['class'].'_'.$field['mdfclass'];//модификатор ячейки
            }
        }
        if($class!=''){
            $class=' class="'.$class.'"';
        }
        if((isset($field['sourse']))&&(is_array($field['sourse']))){
            $select_array=$field['sourse'];
        }else{
            $select_array=array();
        }
        //если не определено используем name
        if(!(isset($field['input_name']) && isset($field['input_id']))){
            $field['input_name']=$field['name'];
            $field['input_id']=$field['name'];
        }

        $res='';
        foreach($select_array as $key=>$value){
            $res.='<label '.$class.'>';
            $res.='<input name="'.$field['input_name'].'" id="'.$field['input_id'].'" type="radio" value="'.$key.'" ';
            $res.= (($key==$data)?(' checked="checked"'):(''));
            $res.= '/>'.$value.'</label>';
        }//foreach




/*        foreach($radio_array as $key=>$value){
            $radio_add.="\n<label style='white-space: nowrap'>";
            $radio_add.="<input name=\"".$field['name']."\" type=\"radio\" value=\"".$key."\" ";//print("key=".$key." "."value=".$value);]
            //print($item[$field['name']]);
            if(isset($item[$field['name']])){
                if($item[$field['name']]==$key){
                    $radio_add.=" checked=\"checked\" ";
                }
            }
            //$radio_add.=(($key==$item[$field['name']])?(" checked=\"checked\" "):(""));
            $radio_add.= " />".$value."</label><br />";
        }*/
        return $res;
    }

    static function multicheck($data,$field){
        //TODO куда классы и freedata?
        // по старому сюда приходил массив, если строка то преобразовываем в массив
        if (!is_array($data)){
            $data=explode(',',$data);
        }
        $class='';
        if(isset($field['class'])){
            $class.=$field['class'];
            if(isset($field['mdfclass'])){
                $class.=' '.$field['class'].'_'.$field['mdfclass'];//модификатор ячейки
            }
        }
        if($class!=''){
            $class=' class="'.$class.'"';
        }
        //если не определено используем name
        if(!(isset($field['input_name']) && isset($field['input_id']))){
            $field['input_name']=$field['name'];
            $field['input_id']=$field['name'];
        }
        if((isset($field['sourse']))&&(is_array($field['sourse']))){
            $select_array=$field['sourse'];
        }else{
            $select_array=array();
        }

        $cbx_fields=[];
        //$cbx_fields['class']= 'multicheckbox';// если надо изменить, изменяем как связку
        $res='';
        foreach($select_array as $key=>$value){
            if(in_array($key,$data)){
                $cur_data='yes';
            }else{
                $cur_data='no';
            }
            $res.='<label'.$class.'>';
            $cbx_fields['input_name']=$field['input_name'].'['.$key.']';
            $cbx_fields['input_id']=$field['input_id'].'_'.$key;
            $res.= self::checkbox($cur_data, $cbx_fields);
            $res.=$value.'</label>';
        }//foreach

        return $res;
        ////////////////
/*        $add_select="";

        foreach($select_array as $key=>$value){
            $add_select.=sprintf("<label><input name='%s_%s' type='checkbox' id='%s_%s' value='435' %s />
      %s</label><br />",$field['name'],$key,$field['name'],$key,
                ((!isset($item[$field['name']]))?(""):(
                ((is_array($item[$field['name']]))?(  (in_array($key,$item[$field['name']]))?("checked='checked' "):("")  )
                    :(  ($key==$item[$field['name']])?("checked='checked' "):(""))))),$value,$field['name']);
        }//foreach*/
    }


    static function text($value, $field){
        $res='';
        //field['class'] формируется во вне и включает в себя все модификаторы
        $class='';
        if(isset($field['class'])){
            $class=' class="'.$field['class'].'"';
        }
        $res.=((isset($field['prefix']))?($field['prefix']):(''));
        //tovars[2908][kolvo]
        //input_id и input_name формируются синтетически на основании key и name
        $res.= '<input '.$class.' type="text" name="'.$field['input_name'].'" id="'.$field['input_id'].'" value="'.
            htmlspecialchars($value,ENT_COMPAT|ENT_HTML401,'UTF-8') .
            '" '.
            ((isset($field['freedata']))?(' '.$field['freedata'].' '):('')).
            ((isset($field['max_length']))?(' maxlength="'.$field['max_length'].'" '):('')).
            '/>';
        $res.=((isset($field['posfix']))?($field['posfix']):(''));
        return $res;
    }

    static function password($value, $field){
        unset($value);//лишь бы не плакало
        $res='';
        //field['class'] формируется во вне и включает в себя все модификаторы
        $class='';
        if(isset($field['class'])){
            $class=' class="'.$field['class'].'"';
        }
        //input_id и input_name формируются синтетически на основании key и name
        //value у пароля всегда пустое
        $res.= '<input '.$class.' type="password" name="'.$field['input_name'].'" id="'.$field['input_id'].'" value="" '.
            ((isset($field['freedata']))?(' '.$field['freedata'].' '):('')).
            ((isset($field['max_length']))?(' maxlength="'.$field['max_length'].'" '):('')).
            '/>';
        return $res;
    }



    static function textskr($value,$field){
        $class__name ='textskr__name';
        $class__click='textskr__click';
        $class__el='textskr__text';
        if(isset($field['class'])){
            $class__name = $field['class'].' '.$class__name;
            $class__click= $field['class'].' '.$class__click;
            $class__el   = $field['class'].' '.$class__el;
        }
        $value_textskr='неопределено';
        if($value!=""){
            $value_textskr=$value;
        }
        //TODO предупреждать всплывающим окном об опасности редактирования
        $res = '<span style="display: block" id="skr_'.$field['input_id'].'">
                <span class="'.$class__name.'">'.$value_textskr.'</span> - <span class="'.$class__click.'" 
                onclick="toggleElement(\''.$field['input_id'].'\');toggleElement(\'skr_'.$field['input_id'].'\');"
                title="эту информацию можно редактировать лишь в крайних случаях">редактировать</span>
                </span>';

        if(isset($field['freedata'])){
            $field['freedata'].= '  style="display:none" ';
        }else{
            $field['freedata'] = '  style="display:none" ';
        }
        $field['class']=$class__el;
        $res .=self::text($value,$field);
        return $res;
    }

    static function textarea($data,$field){
        $class='';
        if(isset($field['class'])){
            $class.=$field['class'];
            if(isset($field['mdfclass'])){
                $class.=' '.$field['class'].'_'.$field['mdfclass'];//модификатор ячейки
            }
        }
        if($class!=''){
            $class=' class="'.$class.'"';
        }

        //tovars[2908][kolvo]
        //input_id и input_name формируются синтетически на основании key и name
        $res = '<textarea '.$class.' name="'.$field['input_name'].'" id="'.$field['input_id'].'" '.
            ((isset($field['freedata']))?(' '.$field['freedata'].' '):('')).
            ((isset($field['textarea']))?(' rows="'.$field['textarea'].'"'):('')).
            '>'.
            $data.
            '</textarea>';
        return $res;

/*        $textarea_add="<textarea class=\"fullwidth".(( in_array($field['name'],$item['error_fields']))?(' error_field'):(''))."\"  name=\"".$field['name']."\"  id=\"".$field['name']."\"";
        if(isset($field['textarea'])){
            $textarea_add.=" rows=\"".$field['textarea']."\"";
        }

        $contar="";
        $contar2="";
        if(isset($field['max_length'])){
            $contar_name="ost".$field['name'];
            $contar="<br />Осталось <span id=\"".$contar_name."\">".$field['max_length']."</span> знаков.";
            $textarea_add.="  onkeyup=\"javascript:Contar('".$field['name']."','$contar_name',".$field['max_length'].")\" onkeypress=\"javascript:Contar('".$field['name']."','$contar_name',".$field['max_length'].")\" onchange=\"javascript:Contar('".$field['name']."','$contar_name',".$field['max_length'].")\"";
            $contar2="<script language=\"JavaScript\" type=\"text/javascript\" >Contar('".$field['name']."','$contar_name',".$field['max_length'].")</script>";
        }
        $textarea_add.=">".((isset($item[$field['name']]))?($item[$field['name']]):(''))."</textarea>";*/
    }

    static function datetime($data,$field){
        //tovars[2908][kolvo]
        $class='';
        if(isset($field['class'])){
            $class.=$field['class'];
            if(isset($field['mdfclass'])){
                $class.=' '.$field['class'].'_'.$field['mdfclass'];//модификатор ячейки
            }
        }
        if($class!=''){
            $class=' class="'.$class.'"';
        }

        $res='<span '.
            $class.
            ((isset($field['freedata']))?(' '.$field['freedata'].' '):('')).
            '>';
        $res.=date("d-m-Y H:i:s",strtotime($data));
        $res.='</span>';
        return $res;
    }

    static function datetimeeditable($value,$field,$addattach=''){//$addattach изврат конечно
//        $class='';
//        if(isset($field['class'])){
//            $class=' class="'.$field['class'].'"';
//        }
        //ДАТА YYYY-MM-DD HH:MM:SS
        $str_value=strtotime($value);
        $res = self::hidden($value,$field);
        $res.= '<span class="'.$field['class'].'">';
        $lvl_field=$field;
        $lvl_field['input_id'] .= '_show';
        $lvl_field['class'] = 'dteshow';
        if(intval($str_value)>1){
            $dt_value=date("d-m-Y",$str_value);
           // $lvl_field['class'] .= '_show';
           // $img_class=$field['class'] . '_img';
        }else{
           // $lvl_field['class'] .= '_error';
            //$img_class=$field['class'] . '_imgerror';
            $dt_value='не установлена';
        }
        $res.= self::label($dt_value,$lvl_field);

//        $img_class=$field['class'] . '_img';
        $img_id = $field['input_id']. '_img';
        $res.= '<img class="dteimg" src="/js/calendar.gif" id="'.$img_id.'" title="Изменить">';
        $res.= '</span>';
        $res.= '<script type="text/javascript">
        window.addEventListener("load", function(){
			var '.$field['input_id'].'Calendar = new dhtmlXCalendarObject({  button: "'.$img_id.'", dateFormat:"%Y-%m-%d"});
			'.$field['input_id'].'Calendar.setDate("'.date("Y-m-d", $str_value) .'");
			'.$field['input_id'].'Calendar.hideTime();
            '.$field['input_id'].'Calendar.showToday();
            '.$field['input_id'].'Calendar.attachEvent("onClick", function(date, state){
                var lbl=document.getElementById("'.$lvl_field['input_id'].'");
                lbl.innerHTML='.$field['input_id'].'Calendar.getFormatedDate("%d-%m-%Y");
                var inp=document.getElementById("'.$field['input_id'].'");
                inp.value='.$field['input_id'].'Calendar.getFormatedDate("%Y-%m-%d");
            });
            '.$addattach.'
         });
		</script>';
        return $res;
    }

    static function datetime_ago($value,$field){
        $class='';
        if(isset($field['class'])){
            $class.=$field['class'];
            if(isset($field['mdfclass'])){
                $class.=' '.$field['class'].'_'.$field['mdfclass'];//модификатор ячейки
            }
        }
        if($class!=''){
            $class=' class="'.$class.'"';
        }
        $res='<span '.
            $class.
            ((isset($field['freedata']))?(' '.$field['freedata'].' '):(''));
        if($value=='0000-00-00 00:00:00'){
            $ago = 'никогда';
        }else{
            $startTime = new Datetime($value);
            //$res .= $startTime->format('d-m-Y H:i:s ').''.Html::ago($startTime).'';
            $res .= ' title="'.$startTime->format('d-m-Y H:i:s ').'" ';
            $ago =  Html::ago($startTime);
        }
        $res .='>'.$ago.'</span>';
        return $res;
    }


    static function rank($value, $field){
        $res='';
        //field['class'] формируется во вне и включает в себя все модификаторы
        $class='rank';
        if(isset($field['class'])){
            $class= $field['class'];
        }
        $res.='<img class="'.$class.'_arrow" src="/admin/images/down_16.gif" title="вниз" onclick="moveit(\'down\','.$value.')" />
			<input class="'.$class.'_text" name="s'.$value.'" id="s'.$value.'" type="text" value="1" size="1"/>
			<img  class="'.$class.'_arrow" src="/admin/images/up_16.gif" title="вверх" onclick="moveit(\'up\','.$value.')"/>';
        return $res;
    }

//    static function key($data,$field){
//        return self::hidden($data,$field);
//    }
    static function hidden($value,$field){
        //tovars[2908][kolvo]

        $res = '<input type="hidden" name="'.$field['input_name'].'" id="'.$field['input_id'].'" value="'.$value.'"/>';
        return $res;
    }

    static function txt_hidden($value,$field){
        //и текст и скрытое поле
        return $value . self::hidden($value,$field);
    }
    static function recaptha($value,$field){
        return '<div class="g-recaptcha" data-sitekey="'.LocalConfig::RECAPTCHA_PUBLIC_KEY.'"></div>';
    }



    /**
     * Переводит первый символ в верхний регистр, для UTF
     *
     * Вообще, наверное другая библиотека, ну пока пусть побудет здесь
     *
     * @param string $str
     * @return string
     */
    static function mbUcfirst($str) {
        $fc = mb_strtoupper(mb_substr($str, 0, 1, "UTF-8"), "UTF-8");
        return $fc.mb_substr($str, 1, null, "UTF-8");
    }

    static function ago( $datetime ) {
        $interval = date_create('now')->diff( $datetime );
        $suffix = ( $interval->invert ? ' назад' : '' );
        if ( $v = $interval->y >= 1 ) return $interval->y.' '.self::declOfNum($interval->y, array('год', 'года', 'лет'))   . $suffix;
        if ( $v = $interval->m >= 1 ) return $interval->m.' '.self::declOfNum($interval->m, array('месяц', 'месяца', 'месяцев'))   . $suffix;
        if ( $v = $interval->d >= 1 ) return $interval->d.' '.self::declOfNum($interval->d, array('день', 'дня', 'дней'))   . $suffix;
        if ( $v = $interval->h >= 1 ) return $interval->h.' '.self::declOfNum($interval->h, array('час', 'часа', 'часов'))   . $suffix;
        if ( $v = $interval->i >= 1 ) return  $interval->i.' '.self::declOfNum($interval->i, array('минуту', 'минуты', 'минут')) . $suffix;
        return $interval->s.' '.self::declOfNum($interval->s, array('секунду', 'секунды', 'секунд')) . $suffix;
    }

    static function declOfNum($number, $titles)
    {
        $cases = array (2, 0, 1, 1, 1, 2);
        return  $titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
    }

    static function translit($arg_string){
        // echo setlocale (LC_CTYPE,"0");
        $arg_string=mb_strtolower($arg_string, "UTF-8");
        $arg_string=preg_replace("/[^\w ]/iu", "", $arg_string);

        $res_string=str_replace(LocalTranslit::$translit_table['ru'], LocalTranslit::$translit_table['trans'] , $arg_string);
        $res_string = preg_replace('/_+/', '_', $res_string);
        return $res_string;
    }


}
