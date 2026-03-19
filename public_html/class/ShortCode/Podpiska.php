<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 15.04.2018
 * Time: 13:47
 */

namespace ShortCode;


class Podpiska implements iShortCode
{
    public $replace_mode='block';
    static $counter = 0;

    public function render($param){
        $pviev = true;
        if(isset($_GET['rst']) && $_GET['rst']==$param['type']){
            // чел пришел по ссылке из письма,
            // кука может еще не стоять, но все равно форму этого типа не показываем
            $pviev = false;
        }else{
            //проверяем куку
            if(isset($_COOKIE[$param['type']]) && $_COOKIE[$param['type']]=='yes'){
                $pviev = false;//подписан, не показываем
            }

        }

        $page_nik='';
        if (isset(Detector::$page)){
            $page_nik = Detector::$page->item_viev->my_item['nik'];
        }

        if($pviev==true){
            $p_form=new \PodpiskaForm($param['action_url'], $param['secret_field']);
            $this->replace_mode='place';
            return $p_form->show($param, self::$counter++);
        }else{
            if(isset($param['nik']) && $page_nik == $param['nik']){
                //на основной странице показываем сообщение о подписанности
                $this->replace_mode='place';
                return '<div class="podpform" style="text-align:center;">Вы уже подписаны на рассылку</div>';
            }else{
                $this->replace_mode='block';
                return '';//убираем окружающий блок
            }
        }
    }
}