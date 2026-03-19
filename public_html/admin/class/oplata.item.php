<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 06.12.2015
 * Time: 14:36
 */
$item= new item();
$settings_array=array(
    'fields'=>array(
        array(
            'name'=>"id",
            'format'=>"key",
            'viev'=>"key",
            'default'=>0
        ),

        array(
            'name'=>"name",
            'format'=>"text",
            'text'=>"Наименование платежа",
            'viev'=>"text",
            'max_length'=>64,
//            'unique'=>' - это название уже используется.',
            'required'=>"Наименование платежа"
        ),
        array(
            'name'=>"summa",
            'format'=>"int",
            'text'=>"Сумма платежа",
            'viev'=>"text",
            'default'=>0,
            'required'=>"Сумма платежа"
        ),
//        array(//заголовок
//            'name'=>"title",
//            'format'=>"text",
//            'text'=>"Title",
//            'viev'=>"text",
//            'max_length'=>255,
//            'required'=>"Title"
//        ),
//        array(//анонс h2 он же описание без спецсимволов
//            'name'=>"anons",
//            'format'=>"text",
//            'text'=>"Descr:",
//            'viev'=>"textarea",
//            'max_length'=>250,
//            'required'=>"Description"
//        ),
//        array(//заголовок
//            'name'=>"tlist",
//            'format'=>"text",
//            'text'=>"Заголовок в списке<br /><span class=\"prim\" >(если отстутствует, в списке не показывается)</span>",
//            'viev'=>"text",
//            'max_length'=>255
//        ),
        array(//Псевдоним латиницей формируется автоматически
            'name'=>"nik",
            'format'=>"text",
            'text'=>"Ник (латиница):",
            'viev'=>"hidden"
        ),
        array(
            'name'=>"timeadd",
            'text'=>"Добавлено:",
            'format'=>"datetime",
            'viev'=>"none",
            'default'=>"now"
        ),
        array(
            'name'=>"look",
            'format'=>"checkbox",
            'text'=>"<strong>Ограничить по времени?</strong>",
            'viev'=>"checkbox",
            'default'=>"yes"
        ),
        array(
            'name'=>"datefinal",
            'text'=>"Действует до даты: </br>(включительно)",
            'format'=>"datetime",
            'viev'=>"datetimeeditable",
            'default'=>"now"
        ),//////
        array(
            'name'=>"timefinal",
            'text'=>"Действует до времени: </br>(включительно)",
            'format'=>"int",
            'sourse'=>array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'),
            'viev'=>"select",
            'default'=>'23'
        ),//////
        array(
            'name'=>"d_start",
            'text'=>"Дата начала обучения",
            'format'=>"datetime",
            'viev'=>"datetimeeditable",
            'default'=>"now"
        ),
        array(
            'name'=>"d_stop",
            'text'=>"Дата окончания обучения",
            'format'=>"datetime",
            'viev'=>"datetimeeditable",
            'default'=>"now"
        ),
        array(
            'name'=>"action_item",
            'format'=>"hidden",
            'viev'=>"hidden",
            'default'=>"add"
        ),
        array(
            'name'=>"jilie",
            'format'=>"checkbox",
            'text'=>"Проживание",
            'viev'=>"checkbox",
            'default'=>"no"
        ),
        array(
            'name'=>"kurs",
            'format'=>"text",
            'text'=>"Тип обучения",
            'viev'=>"select",
            'sourse'=>array('kurs'=>"Основной курс",'mk'=>"Мастеркласс",'other'=>"Другое"),
            'default'=>"yes"
        ),
        array(
            'name'=>"extra",
            'format'=>"checkbox",
            'text'=>"Экстра?",
            'viev'=>"checkbox",
            'default'=>"no"
        ),
    ),
    'table'=>"oplata"
);
//ALTER TABLE `pet_oplata` ADD `jilie` ENUM('yes','no') NOT NULL DEFAULT 'no' AFTER `look`, ADD `kurs` ENUM('yes','no') NOT NULL DEFAULT 'no' AFTER `jilie`, ADD `extra` ENUM('yes','no') NOT NULL DEFAULT 'no' AFTER `kurs`;
//ALTER TABLE `pet_oplata` ADD `d_start` DATE NOT NULL AFTER `extra`;
//ALTER TABLE `pet_oplata` ADD `d_stop` DATE NOT NULL AFTER `d_start`;
$item->set_settigs($settings_array);

if (isset($_POST['action_item'])){
//    die(substr(md5(time()),0,6));

    //формируем псевдоним
    if($_POST['nik']==""){
        do {
            $new_nik=substr(md5(time()),0,6);
        }while (is_numeric($new_nik) || $item->check_unique("nik", $new_nik)==false);
        $_POST['nik']=$new_nik;
    }


    $item->action($_POST['action_item']);
    if($item->error==""){
        if(strstr($_POST['action_item'],"delete_item")){
            header("Location:".SERVER_HOST.$phpself);
        }else{
            header("Location:".SERVER_HOST.$phpself.'?id='.$item->my_item['key']);//TODO с учетом страницы
        }
        exit;
    }
}
