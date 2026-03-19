<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 15.04.2018
 * Time: 15:58
 */

namespace ShortCode;


class VKontakte implements iShortCode
{
    public function render($param){
        $vk_block='';
        //$vk_block.='<br class="clearfloat" />';
        $vk_block.='<script type="text/javascript" src="https://vk.com/js/api/openapi.js?150"></script>';
        $vk_block.='<div id="vk_groups" style="margin:10px 10px 0 0;"></div>';

        $vk_block.='<script type="text/javascript">';
        $vk_block.='VK.Widgets.Group("vk_groups", {mode: 3, width: "auto", no_cover: 1, color1: "FFFFFF", color2: "2B587A", color3: "5B7FA6"}, 18623149);';
        $vk_block.='</script>';
        $vk_block.='<br class="clearfloat" />';
        return $vk_block;
    }
}