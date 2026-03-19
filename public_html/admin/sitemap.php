<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 18.02.2018
 * Time: 18:47
 */
function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}
$time_start = getmicrotime();
define("ANMIN_PAGE","yes_admin");
require_once("../class/common.php");
$page="sitemap";

$left='';
if(isset($_GET['id'])){
    $pages = new Vypusknik();
}else{
    $pages = new VypusknikList();
}

if ($pages->routing($_GET)){
    $left=$pages->render();
}else{
    $title = 'Ошибка страницы';
    //$left=$tovar->renderErrorsBlock();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>SITEMAP</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <script type="text/javascript" src="js/admin.js"></script>
    <script type="text/javascript" src="/js/tw-sack.min.js" ></script>
    <script type="text/javascript">
        ajax = new sack();
        function makeSitemap() {
            ajax.requestFile = "/admin/sitemap_generator.php";
            ajax.onCompletion = mapComplete;
            var btn = getObject('btn');
            btn.style.display='none';
            var preinfo = getObject('preinfo');
            preinfo.style.display='none';
            var wait = getObject('wait');
            wait.style.display='block';
            ajax.runAJAX();
            return false;
        }
        function mapComplete() {
            var err;
            var wait;
            var rez_arr=parse_str(ajax.response);
            if(rez_arr['res']==='timeout'){
                wait = getObject('wait');
                wait.innerHTML += ' + ';
                makeSitemap();
            }else if(rez_arr['res']==='error'){
                wait = getObject('wait');
                wait.style.display='none';
                err = getObject('err');
                err.style.display='block';
                err.innerHTML= rez_arr['errors'];
            }else if(rez_arr['res']==='ok'){
                wait = getObject('wait');
                wait.style.display='none';
                var fin = getObject('fin');
                fin.style.display='block';
                fin.innerHTML +=rez_arr['info'];
            }else {
                err = getObject('err');
                err.style.display='block';
            }
        }
    </script>
</head>
<body>
<div id="content">
    <?php
    include("include/adminmenu.php");//adminmenu ?>
    <div class="left">
        <h1>SITEMAP</h1>
        <div class="sitemap">
        <?php

        $sitemap = new Sitemap();
        if(isset(LocalConfig::$site_map)){
            $sitemap->init(LocalConfig::$site_map);
        }
        $res = $sitemap->loadFile();
        //echo $sitemap->getDataOld();
        $text='Сформировать файл';
        echo '<div id="preinfo">';
           // Настройки:';
        //echo nl2br($sitemap->getInfo()).'<br>';

        if(isset($res['errors'])){

            foreach ($res['errors'] as $error){
                echo '<p>'.$error.'</p>';
            }

        }else{
            echo 'В файле '.$res['count']. ' URL.'. ' Сформирован: '.$res['date'];
            $text ='Обновить файл';
        }
        echo '</div>';
        echo '<div id="btn" class="btns"><div class="btns__btn btns__btn_left btns__btn_green" onclick="makeSitemap();">'.$text.'</div></div>';

        echo '<div id="wait" class="sitemap__wait">Файл формируется. Подождите сообщения об окончании.<br>Не перезагружайте страницу!</div>';

        echo '<div id="fin" class="sitemap__fin">Файл sitemap.xml сформирован.</div>';

        echo '<div id="err" class="sitemap__err">Ошибка формирования файла</div>';
        ?>
    </div>
    </div>
    <div class="footer">
        <?php 	include("include/adminfooter.php"); ?>
    </div>
</div>
</body>
</html>
