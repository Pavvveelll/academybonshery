<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 16.12.2017
 * Time: 18:32
 */

$res='no';
if(isset($_POST['mode']) && $_POST['mode']=='vypusknik'){
    if(isset($_POST['pagenum'])){
        require_once("../class/common.php");
        $subpg = new \ShortCode\VypusknikiBlock();
        $subpg->setPagenum(intval($_POST['pagenum']));
        $res = $subpg->render([]);
    }
}else{
    if(isset($_POST['page_id']) && isset($_POST['patch_url'])&& isset($_POST['pagenum'])){
        //sleep(5);
        require_once("../class/common.php");
        $subpg = new SubPages(intval($_POST['page_id']),$_POST['patch_url']);
        $res = $subpg->render(intval($_POST['pagenum']));
    }
}


echo $res;