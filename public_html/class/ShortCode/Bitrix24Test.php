<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 16.07.2018
 * Time: 22:13
 */

namespace ShortCode;


class Bitrix24Test implements iShortCode
{


    public function render($param)
    {
        $err='';
        if (isset($_POST['paymentTest'])){
/*            $post_fields1=[
                'TITLE'=>'Проверка создания лида №1.7',
                'LOGIN'=>'deiwww@yandex.ru',
                'PASSWORD'=>'sdfE35738!',
                'EMAIL_WORK'=> "muwkagammy@gmail.com",
                'PHONE_WORK'=>'89261145566',
                'NAME'=>'Петров Сергей Иванович',
                'UF_CRM_1530448220340'=>'12.09.2018',//Дата начала обучения - дд.мм.гггг
                'UF_CRM_1530448231287'=>'19.09.2018',//Дата окончания обучения - дд.мм.гггг
                'UF_CRM_1530448256512'=>'Стандарт',//Категория -  - тип поля список принимает значения: Стандарт Экстра
                'UF_CRM_1530448275959'=>'Не включено',//Проживание -  - тип поля список - принимает значения: Включено Не включено
                'UF_CRM_1531837476163'=>'Курс',//Оплата за - Тип поля список - значения списка: Курс Мастеркласс Другое
                'COMMENTS'=>'ТЕСТ Оплата 12.09.2018 стандарт без проживания muwkagammy@gmail.com',
            //'summa'=>'39900',
            ];
            $post_fields2=[
                'TITLE'=>'Проверка создания лида №2',
                'LOGIN'=>'deiwww@yandex.ru',
                'PASSWORD'=>'sdfE35738!',
                'EMAIL_WORK'=> "test2@mail.ru",
                'PHONE_WORK'=>'89031442255',
                'NAME'=>'Марфина Светлана',
                'UF_CRM_1530448220340'=>'12.09.2018',//Дата начала обучения - дд.мм.гггг
                'UF_CRM_1530448231287'=>'19.09.2018',//Дата окончания обучения - дд.мм.гггг
                'UF_CRM_1530448256512'=>'Экстра',//Категория -  - тип поля список принимает значения: Стандарт Экстра
                'UF_CRM_1530448275959'=>'Не включено',//Проживание -  - тип поля список - принимает значения: Включено Не включено
                'UF_CRM_1531837476163'=>'Курс',//Оплата за - Тип поля список - значения списка: Курс Мастеркласс Другое
                'COMMENTS'=>'ТЕСТ Оплата 12.09.2018 экстра без проживания Больше 7 дней',
            ];
            $post_fields3=[
                'TITLE'=>'Проверка создания лида №3',
                'LOGIN'=>'deiwww@yandex.ru',
                'PASSWORD'=>'sdfE35738!',
                'EMAIL_WORK'=> "test3@mail.ru",
                'PHONE_WORK'=>'89057775511',
                'NAME'=>'Зимина Ольга',
                'UF_CRM_1530448220340'=>'05.10.2018',//Дата начала обучения - дд.мм.гггг
                'UF_CRM_1530448231287'=>'12.10.2018',//Дата окончания обучения - дд.мм.гггг
                'UF_CRM_1530448256512'=>'Стандарт',//Категория -  - тип поля список принимает значения: Стандарт Экстра
                'UF_CRM_1530448275959'=>'Включено',//Проживание -  - тип поля список - принимает значения: Включено Не включено
                'UF_CRM_1531837476163'=>'Курс',//Оплата за - Тип поля список - значения списка: Курс Мастеркласс Другое
                'COMMENTS'=>'ТЕСТ Оплата 05.10.2018 стандарт c проживанием'
            ];
            $post_fields4=[
                'TITLE'=>'Проверка создания лида №2',
                'LOGIN'=>'deiwww@yandex.ru',
                'PASSWORD'=>'sdfE35738!',
                'EMAIL_WORK'=> "test4@mail.ru",
                'PHONE_WORK'=>'89031000000',
                'NAME'=>'Светлана Сидорова',
                'UF_CRM_1530448220340'=>'25.07.2018',//Дата начала обучения - дд.мм.гггг
                'UF_CRM_1530448231287'=>'01.08.2018',//Дата окончания обучения - дд.мм.гггг
                'UF_CRM_1530448256512'=>'Экстра',//Категория -  - тип поля список принимает значения: Стандарт Экстра
                'UF_CRM_1530448275959'=>'Не включено',//Проживание -  - тип поля список - принимает значения: Включено Не включено
                'UF_CRM_1531837476163'=>'Курс',//Оплата за - Тип поля список - значения списка: Курс Мастеркласс Другое
                'COMMENTS'=>'ТЕСТ Оплата 25.07.2018 экстра без проживания Меньше 7 дней',
            ];*/

/*            switch ($_POST['paymentTest']) {
                case 1:
                    $post_fields=$post_fields1;
                    break;
                case 2:
                    $post_fields=$post_fields2;
                    break;
                case 3:
                    $post_fields=$post_fields3;
                    break;
                case 4:
                    $post_fields=$post_fields4;
                    break;
                default:


            }*/
            $post_fields=[
                'TITLE'=>$_POST['TITLE'],
                'LOGIN'=>'deiwww@yandex.ru',
                'PASSWORD'=>'sdfE35738!',
                'EMAIL_WORK'=>$_POST['EMAIL_BLYA'],
                'PHONE_WORK'=>$_POST['PHONE_BLYA'],
                'NAME'=>$_POST['NAME'],//
                'UF_CRM_1530448220340'=>$_POST['UF_CRM_1530448220340'],//'25.07.2018',//Дата начала обучения - дд.мм.гггг
                'UF_CRM_1530448231287'=>$_POST['UF_CRM_1530448231287'],//'01.08.2018',//Дата окончания обучения - дд.мм.гггг
                'UF_CRM_1530448256512'=>$_POST['UF_CRM_1530448256512'],//'Экстра',//Категория -  - тип поля список принимает значения: Стандарт Экстра
                'UF_CRM_1530448275959'=>$_POST['UF_CRM_1530448275959'],//'Не включено',//Проживание -  - тип поля список - принимает значения: Включено Не включено
                'UF_CRM_1531837476163'=>$_POST['UF_CRM_1531837476163'],//'Курс',//Оплата за - Тип поля список - значения списка: Курс Мастеркласс Другое
                'OPPORTUNITY'=>$_POST['OPPORTUNITY'],
                'COMMENTS'=>$_POST['COMMENTS'],//'ТЕСТ Оплата 25.07.2018 экстра без проживания Меньше 7 дней',

            ];


            $z=curl_init();
            //die($v['url'].'admin/sinhron/robot.php?type=find_this&code='. urlencode($code).'&id='.$id.'&host='.urlencode($_SERVER['HTTP_HOST']));
            curl_setopt($z, CURLOPT_URL,'https://bonsheryacademy.bitrix24.ru/crm/configs/import/lead.php');
            curl_setopt($z, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($z, CURLOPT_USERAGENT,'Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.16');
            curl_setopt($z, CURLOPT_CONNECTTIMEOUT, 30);//ожидание в секундах
            curl_setopt($z, CURLOPT_TIMEOUT, 30);//ожидание в секундах
            //curl_setopt($z, CURLOPT_HEADER, 0);
            //curl_setopt($z, CURLOPT_USERPWD, "deiwww@yandex.ru:sdfE35738!");
//			curl_setopt($z, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($z, CURLOPT_UNRESTRICTED_AUTH, 1);
            curl_setopt($z, CURLOPT_POST, 1);
            curl_setopt($z, CURLOPT_POSTFIELDS, $post_fields);

            //curl_setopt($z, CURLOPT_SSL_VERIFYPEER, false);
           // curl_setopt($z, CURLOPT_SSL_VERIFYHOST, 0);

            $ress=curl_exec($z);
            if($ress === false)
            {
                $err.= curl_error($z);
            }else{
                $err.= $ress;
            }
            // Закрываем дескриптор
            curl_close($z);

        }
        $oplata_form='<div class="oplata">';
        $oplata_form.= $err;
        $oplata_form.='<br><br><form name=TestForm method="POST" action="" >';
        $oplata_form.='<table class="oplata__table">';
        $oplata_form.='<tr>';
        $oplata_form.='<td>Название лида&nbsp;</td>';
        $oplata_form.='<td><input class="oplata__input" name="TITLE" value="Проверка создания лида №" type="text"></td>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>Емайл&nbsp;</td>';
        $oplata_form.='<td><input class="oplata__input" name="EMAIL_BLYA" value="test5@mail.ru" type="email"></td>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>Телефон&nbsp;</td>';
        $oplata_form.='<td><input class="oplata__input" name="PHONE_BLYA" value="89031000000" type="text"></td>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>ФИО&nbsp;</td>';
        $oplata_form.='<td><input class="oplata__input" name="NAME" value="Светлана Гаприндашвили" type="text"></td>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>Начало&nbsp;</td>';
        $oplata_form.='<td><input class="oplata__input" name="UF_CRM_1530448220340" value="25.07.2018" type="text"></td>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>Окончание&nbsp;</td>';
        $oplata_form.='<td><input class="oplata__input" name="UF_CRM_1530448231287" value="01.08.2018" type="text"></td>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>Категория&nbsp;</td>';
        $oplata_form.='<td>
<input name="UF_CRM_1530448256512" value="Стандарт" type="radio" checked>Стандарт<br>
<input name="UF_CRM_1530448256512" value="Экстра" type="radio">Экстра<br>
</td>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>Проживание&nbsp;</td>';
        $oplata_form.='<td>
<input name="UF_CRM_1530448275959" value="Не включено" type="radio" checked>Не включено<br>
<input name="UF_CRM_1530448275959" value="Включено" type="radio">Включено<br>
</td>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>Оплата за&nbsp;</td>';
        $oplata_form.='<td>
<input name="UF_CRM_1531837476163" value="Курс" type="radio" checked>Курс<br>
<input name="UF_CRM_1531837476163" value="Мастеркласс" type="radio">Мастеркласс<br>
<input name="UF_CRM_1531837476163" value="Другое" type="radio">Включено<br>
</td>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>Сумма&nbsp;</td>';
        $oplata_form.='<td><input class="oplata__input" name="OPPORTUNITY" value="30000" type="text"></td>';
        $oplata_form.='</tr>';
        $oplata_form.='</tr>';
        $oplata_form.='<tr>';
        $oplata_form.='<td>Комментарий&nbsp;</td>';
        $oplata_form.='<td><textarea  class="oplata__input" name="COMMENTS">Тестовый комментарий</textarea></td>';
        $oplata_form.='</tr>';
        $oplata_form.='</tr>';
        $oplata_form.='<input type="hidden" name="paymentTest" value="test"/>';

        $oplata_form.='</table>';

        //'TITLE'=>'Проверка создания лида №2'
        //$oplata_form.='<input type="hidden" name="ShopID" value="23440"/>';
        //$oplata_form.='<input type="hidden" name="scid" value="13436"/>';
        //$oplata_form.='<input type="hidden" name="nik" value="'.$opvalues['nik'].'"/>';
//        $oplata_form.='<input name="paymentTest" value="1" type="radio">Оплата 12.09.2018 стандарт muwkagammy<br>';
//        $oplata_form.='<input name="paymentTest" value="2" type="radio">Оплата 12.09.2018 экстра Больше 7 дней<br>';
//        $oplata_form.='<input name="paymentTest" value="3" type="radio">Оплата 05.10.2018 стандарт с проживанием<br>';
//        $oplata_form.='<input name="paymentTest" value="4" type="radio">Оплата 25.07.2018 экстра  Меньше 7 дней<br>';
           // $oplata_form.='<input name="paymentTest" value="4" type="radio">Оплата 4<br>';
            //$oplata_form.='<input name="paymentTest" value="5" type="radio">Оплата 5<br>';

        $oplata_form.='<input class="button" type=submit  value="Тестировать">';

        $oplata_form.='</form>';
        $oplata_form.='</div>';
        return $oplata_form;
    }

}