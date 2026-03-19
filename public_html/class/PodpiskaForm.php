<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 07.08.2015
 * Time: 13:10
 */

class PodpiskaForm {
    private $action_url;
    private $secret_field;
    public $form_class='podpform';

    //private $param;

    function __construct($action_url, $secret_field){
        // //petsgroomer.us9.list-manage.com/subscribe/post?u=87b717f9681cc98b9d6fcba82&amp;id=84de7c7eec
        $this->action_url=$action_url;
        // b_87b717f9681cc98b9d6fcba82_84de7c7eec
        $this->secret_field=$secret_field;
    }

    function show($param, $k) {
        $type=$param['type'];

        if(isset($param['b_text'])){
            //<button class="spbut" type="button" data-page_id="6" data-patch_url="/master_klass/"
            // data-pagenum="1" onclick="vievsubpagelist(this);" title="Показать еще...">Показать ещё...</button>
            $s_button='<button class="podpbut" type="button" onclick="podpiska(\''.$type.$k.'\')" >'.
                $param['b_text'].'</button>';
            $sogl_txt=$param['b_text'];
        }else{//по старому
            $s_button='<span onClick="podpiska(\''.$type.$k.'\')" style="cursor:pointer">
            <img id="'.$type.$k.'_but" src="/img/sustribe.png" width="126" height="19" alt="Оформить"></span>';
            $sogl_txt='Оформить';
        }
        if(isset($param['optin'])){
            $optin= ' data-optin="no" ';
        }else{//с оптином
            $optin= ' data-optin="yes" ';
        }

        $podpiska_form= '<form id="'.$type.$k.'_form" class="'.$this->form_class.'" action="'.$this->action_url.'" method="post" target="_blank" '.$optin.' novalidate>';
        $podpiska_form.= '<div id="'.$type.$k.'_error_span" class="podpform__error"></div>';
        $podpiska_form.= '<table class="podpform__table">';
        $podpiska_form.= '<tr>';
        $podpiska_form.= '<td class="podpform__td_l"><strong>Ваше имя</strong>:*</td>';
        $podpiska_form.= '<td class="podpform__td_r"><input name="FNAME" id="'.$type.$k.'_name" class="fullwidth" maxlength="64" value="" type="text"></td>';
        $podpiska_form.= '</tr>';
        $podpiska_form.= '<tr>';
        $podpiska_form.= '<td class="podpform__td_l"><strong>E-mail</strong>:*</td>';
        $podpiska_form.= '<td class="podpform__td_r"><input name="EMAIL" id="'.$type.$k.'_login" class="fullwidth" maxlength="64" value="" type="text"></td>';
        $podpiska_form.= '</tr>';
        $podpiska_form.= '<tr>';
        $podpiska_form.= '<td class="podpform__td_l">&nbsp;</td>';
        $podpiska_form.= '<td class="podpform__td_r">'.$s_button.'</td>';
        $podpiska_form.= '</tr>';
        $podpiska_form.= '<tr>';
        $podpiska_form.= '<td colspan="2">Нажимая "'.$sogl_txt.'" я даю согласие на обработку персональных данных 
в соответствии с <a href="/akademiya_gruminga_bonsheri/personalnye_dannye/">Политикой обработки персональных данных</a></td>';
        $podpiska_form.= '</tr>';
        $podpiska_form.= '</table>';
//        if($type=='k'){//для майлчимпа
//            $podpiska_form.= '<input type="hidden" value="1" name="group[16537][1]" >';//курсы
//        }elseif($type=='m'){
//            $podpiska_form.= '<input type="hidden" value="2" name="group[16537][2]">';//мастерклассы
//        }
        $podpiska_form.= '<div style="position: absolute; left: -5000px;"><input type="text" name="'.$this->secret_field.'" tabindex="-1" value=""></div>';
        $podpiska_form.= '<input type="hidden" value="Subscribe" name="subscribe">';
        $podpiska_form.= '</form>';
        return $podpiska_form;
    }
}
