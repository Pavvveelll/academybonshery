<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 18.02.2018
 * Time: 19:06
 */

class VypusknikConfig
{
    public static function getConfig($mode = ''){
        $settings_array=array(
            'fields'=>array(
                array(
                    'name'=>"id",
                    'format'=>"key",
                    'viev'=>"key",
                    'default'=>0
                ),
                array(
                    'name'=>"counter",
                    //'format'=>"text",
                    //'text'=>"Имя Фамилия",
                    //'viev'=>"text",
                    //'max_length'=>255,
                    //'required'=>"Имя Фамилия",
                    'viev_list'=>'txt',
                ),
                array(
                    'name'=>"name",
                    'format'=>"text",
                    'text'=>"Имя Фамилия",
                    'viev'=>"text",
                    'max_length'=>255,
                    'required'=>"Имя Фамилия",
                    'viev_list'=>'txt',
                ),
                array(
                    'name'=>"sity",
                    'format'=>"text",
                    'text'=>"Город",
                    'viev'=>"text",
                    'max_length'=>64,
                    //'required'=>"Имя Фамилия",
                    'viev_list'=>'txt',
                ),
//                array(//Псевдоним латиницей формируется автоматически
//                    'name'=>"nik",
//                    'format'=>"text",
//                    'text'=>"Ник (латиница):",
//                    'viev'=>"text",
//                    'unique'=>' - этот псевдоним уже используется. Измените его.',
//                    'max_length'=>128,
//                    'viev_list'=>'txt',
//                ),
//                array(
//                    'name'=>"parent",
//                    'format'=>"int",
//                    'text'=>"Категория",
//                    'viev'=>"select",
//                    'default'=>1,
//                    'target'=>"page",
//                    'viev_list'=>'txt'
//                ),
//                array(//заголовок в том числе надпись на 1-й странице
//                    'name'=>"title",
//                    'format'=>"text",
//                    'text'=>"<strong>Title</strong>",
//                    'viev'=>"text",
//                    'max_length'=>255,
//                    'required'=>"Title"
//                ),
//                array(
//                    'name'=>"descr",
//                    'format'=>"text",
//                    'text'=>"<strong>Текст</strong>",//Анонс
//                    'viev'=>"textarea",
//                    //'max_length'=>250,
//                    'required'=>"Текст",
//                    'viev_list'=>'txt'
//                ),
                'pageimg'=>array(//для картинок обязательно такой синтаксис
                    'name'=>"vimg",
                    'format'=>"img",
                    'text'=>"Фото:<br>(jpeg) Ширина 300px",
                    'th'=>'',
                    'viev'=>"imgload",
                    'previev'=>array(
                        'small'=>array('nameplus'=>'small', 'width'=>50,  'text'=>'50x50'),
                    ),
                    'img_width'=>300,
//                    'img_height'=>600,
                    'max_size'=>200000,
//                    'img_strict'=>"proportion",///пропорции должны быть соблюдены
//                    'img_strict_error'=>"Загружаемая картинка должна быть в пропорции 1х1, т.е. квадратная.",
                    'picture_path'=>'picture/vypysk/',
                    //'viev_list'=>"img",
                ),
//                array(
//                    'name'=>"anons",
//                    'format'=>"text",
//                    'text'=>"<strong>Анонс</strong><br>в списке",//Анонс
//                    'viev'=>"textarea",
//                    'max_length'=>250,
//                    'viev_list'=>'txt'
//                ),
                array(
                    'name'=>"vtext",
                    'format'=>"htmltext",
                    'text'=>"Текст",
                    'viev'=>"textarea",
                    'textarea'=>30,
                    'tiny'=>'vypusknik',
                    //'class'=>'big',
                    'template'=>true,//TODO разобраться
                    //'html'=>1,
                    'body_class'=>'article body_vypusknik'
                    //'required'=>"Текст"
                ),

//                array(//анонс
//                    'name'=>"anons",
//                    'text'=>"Описание",
//                    'format'=>"htmltext",
//                    'viev'=>"textarea",
//                    'textarea'=>5,
//                    'tiny'=>'full',
//                    'class'=>'big',
//                    'body_class'=>'tovar',
//                    'viev_list'=>"textarea",
//                ),
//
//
//                array(
//                    'name'=>"keywords",
//                    'format'=>"text",
//                    'text'=>"<strong>Ключевые слова:</strong><br />
//                       <span class=\"prim\" >(через пробел)</span>",
//                    'viev'=>"text",
//                    'max_length'=>128
//                ),
//		array(
//			'name'=>"timeadd",
//			'text'=>"Добавлено:",
//			'format'=>"datetime",
//			'viev'=>"datetimeeditable",
//			'default'=>"now"
//		),//////
                array(
                    'name'=>"look",
                    'format'=>"checkbox",
                    'text'=>"<strong>Показывать?</strong>",
                    'viev'=>"checkbox",
                    'default'=>"yes",
                    'viev_list'=>'hidden',
                ),
                array(//перемещение по списку
                    'name'=>"rank",
                    'format'=>"int",
                    'text'=>"Перемещение",
                    'viev_list'=>"rank",
                ),
//                array(
//                    'name'=>"action_item",
//                    'format'=>"hidden",
//                    'viev'=>"hidden",
//                    'default'=>"add"
//                )
            ),
            'form'=>array(
                //'class'=>'skidka',//класс блока, если неопределен используется stdform
                'name'=>'vypusknik',
                //'action',//необязательно
                // 'method' // POST | GET по умолчанию POST
                'btns'=>array( //reset submit delete apply
                    //'class',
//                    'delete'=>[
//                        //'hide'=>'hide',
//                        'text'=>'Удалить',
//                        'confirm'=>'Удалить?',
//                    ]

                    //'delete_text'=>'Удалить скидку',
                    //'delete_confirm'=>'Удалить скидку? \nНе забудьте проверить остальные!',
                )
            ),
            'list'=>array(//как выводить в списке (таблице)
                'class-block'=>'vyp',//общий класс для всей таблицы
                //'caption-mdfclass'=>'',
                'th'=>'noth',
                'sort'=>'rankd'
            ),
            'entity'=>"vypusknik",
            'table'=>DB_PREFIX. '_' ."vypusknik",
            'picture_path'=>""
        );
        return $settings_array;
    }
}