<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 15.04.2018
 * Time: 12:50
 */

namespace ShortCode;


class SubPagesList implements iShortCode
{
    public function render($param){
        if (isset(Detector::$page)){
            Detector::$page->ajax= true;
        }
        //$res="";
        //подчиненные страницы
        $subpg = new \SubPages(Detector::$page->item_viev->my_item['id'],Detector::$page->patch_url);
        return $subpg->render();
    }
}