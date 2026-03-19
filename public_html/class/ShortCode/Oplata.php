<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 15.04.2018
 * Time: 16:02
 */

namespace ShortCode;


class Oplata implements iShortCode
{
    public $replace_mode='place';

    public function render($param){
        //Анализируем GET
        $opvalues=array('name'=>'','summa'=>0);
        if(isset($_GET['oplata']) ){
            if($_GET['oplata']!='' && !is_numeric($_GET['oplata'])) {//цифры запрещены
                $op=new \item();//
                $op->table=DB_PREFIX.'_oplata';
                if($op->get_item_seo($_GET['oplata'])){
                    if($op->my_item['look']=='yes'){//проверяем, не истекло ли
                        $currt=new \DateTime();
                        $final=new \DateTime($op->my_item['datefinal'].' '.$op->my_item['timefinal'].':59:59.9');
                        if($currt>$final){
                            //echo 'Просрочено';
                            return $this->getOutTimePage();
                        }else{
                            $opvalues['name']=$op->my_item['name'];
                            $opvalues['summa']=$op->my_item['summa'];
                        }
                    }else{
                        $opvalues['name']=$op->my_item['name'];
                        $opvalues['summa']=$op->my_item['summa'];
                    }
                }else{
                    return $this->getErrorPage();
                }
            }else{
                //неизвестная ссылка, загружаем страницу ошибки оплаты
                return $this->getErrorPage();
            }
        }
        return $this->renderForm($opvalues);
    }

    private function getErrorPage(){
        //неизвестная ссылка, загружаем страницу ошибки оплаты
        $this->replace_mode='full';
        if (isset(Detector::$page)){
            if(Detector::$page->item_viev->get_item_seo('oplataerror',"look")==false){
                return 'Ошибка страницы оплаты.';
            }
            return Detector::$page->item_viev->my_item['article'];
        }
        return 'Ошибка страницы оплаты.';
    }
    private function getOutTimePage(){
        //неизвестная ссылка, загружаем страницу ошибки оплаты
        $this->replace_mode='full';
        if (isset(Detector::$page)){
            if(Detector::$page->item_viev->get_item_seo('prosrocheno',"look")==false){
                return 'Время для оплаты прошло.';
            }
            return Detector::$page->item_viev->my_item['article'];
        }
        return 'Время для оплаты прошло.';
    }

    private function renderForm(array $opvalues){
        Detector::$page->ajax=true;
        //$oplata_form='<script type="text/javascript" src="/js/tw-sack.min.js" ></script>';
        $oplata_form='<div class="oplata">';
        $oplata_form.='<p  class="zag">';
        //Оплата+обучения+грумингу+(основной+курс)+19-26+января+2015+года
        if($opvalues['name']!=''){
            $oplata_form.=$opvalues['name'];
        }else{
            $oplata_form.='Оплата обучения';
        }
        $oplata_form.='</p>';
        $oplata_form.='<div class="oplata__info" id="infoblock">';
        $oplata_form.='Напишите пожалуйста свои имя, фамилию, контактный адрес электронной почты и телефон, чтобы администратор смог подтвердить оплату.';
        $oplata_form.='</div>';
        $oplata_form.='<form name=ShopForm method="POST" 
	            action="https://money.yandex.ru/eshop.xml?rndm='.rand(1,3000).
            '" >';
        $oplata_form.='<input type="hidden" name="ShopID" value="23440"/>';
        $oplata_form.='<input type="hidden" name="scid" value="13436"/>';

        $oplata_form.='<input type="hidden" name="CustomerNumber" value="2018_07">';//Идентификатор клиента/Номер заказа:
        $oplata_form.='<table class="oplata__table">';

        $oplata_form.='<tr>';
        $oplata_form.='<td>*Ф.И.О.:&nbsp;</td>';
        $oplata_form.='<td><input class="oplata__input" type=text name="CustName" size="12"></td>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>*E-mail:&nbsp;</td>';
        $oplata_form.='<td><input class="oplata__input" type=text name="CustEmail" size="12"></td>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>*Телефон:&nbsp;</td>';
        $oplata_form.='<td><input class="oplata__input" type=text name="custAddr" size="12"></td>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td class="oplata__td_top">*Способ оплаты:&nbsp;</td>';
        $oplata_form.='<td>';
        $oplata_form.='<input name="paymentType" value="AC" type="radio" checked="checked">Оплата банковской картой<br>';
        if($opvalues['summa']<15001){
            $oplata_form.='<input name="paymentType" value="AB" type="radio">Оплата через Альфа-Клик<br>';
            $oplata_form.='<input name="paymentType" value="PC" type="radio">Оплата со счета в Яндекс.Деньгах<br>';
            $oplata_form.='<input name="paymentType" value="WM" type="radio">Оплата cо счета WebMoney<br>';
            $oplata_form.='<input name="paymentType" value="GP" type="radio">Оплата по коду через терминал<br>';
        }
        $oplata_form.='</td>';
        $oplata_form.='</tr>';


        $oplata_form.='<tr>';
        $oplata_form.='<td>*Сумма:&nbsp;</td><td>';
        if($opvalues['summa']>0 ){
            $oplata_form.='<input type="hidden" name="Sum" value="'.$opvalues['summa'].'"/>';
            $oplata_form.=$opvalues['summa'].' руб.';
        }else{
            $oplata_form.='<input class="oplata__input" type="text" name="Sum" value="" size="12"/>';
        }
        $oplata_form.='</td></tr>';


        $addform='';
        if($opvalues['name']!=''){
            $addform.='<input type="hidden" maxlength="64" name="orderDetails" value="'.
                htmlspecialchars($opvalues['name'],ENT_QUOTES|ENT_XHTML,'UTF-8'). '">';
        }else{
            $oplata_form.='<tr>';
            $oplata_form.='<td>*Назначение:&nbsp;</td>';
            $oplata_form.='<td><input class="oplata__input" type="text" name="orderDetails" value="" size="12"/></td>';
            $oplata_form.='</tr>';
        }

        //$oplata_form.='<input name="cps_phone" value="+79123456543" type="hidden"/>';
        //$oplata_form.='<input name="cps_email" type="hidden" value=""/>';
        //ДЛЯ ЧЕКА

        $oplata_form.="<input name='ym_merchant_receipt' type='hidden' value=''/>";
        /*
{
    "customerContact": "+79001231212",
    "taxSystem": 1,
    "items":[
        {
            "quantity": 1.154,
            "price": {"amount": 300.23},
            "tax": 3,
            "text": "Зеленый чай \"Юн Ву\", кг"
        },
        {
            "quantity": 2,
            "price": {"amount": 200.00},
            "tax": 3,
            "text": "Кружка для чая, шт., скидка 10%"
        }
    ]
}
         * */


        $oplata_form.='<tr>';
        $oplata_form.='<td>&nbsp;</td>';
        $oplata_form.='<td><input class="button" type=button  onclick="chekoplata(this.form);"  value="Оплатить и зарезервировать"></td>';
        $oplata_form.='</tr>';
        $oplata_form.='</table>';
        $oplata_form.= $addform;
        $oplata_form.='</form>';
        $oplata_form.='<p>Все поля формы должны быть заполнены. После нажатия кнопки Вы будете перенаправлены на страницу платежного сервиса. Выполните все инструкции на ней.</p>';
        $oplata_form.='<p>Нажимая "Оплатить" я даю согласие на обработку персональных данных 
в соответствии с <a href="/akademiya_gruminga_bonsheri/personalnye_dannye/">Политикой обработки персональных данных</a></p>';
        $oplata_form.='</div>';

        return $oplata_form;
    }

}