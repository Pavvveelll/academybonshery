<?php
session_start();
//  print_r($_POST);
require_once("../../class/common.php");
$page = new Page();
if(!$page->routing('english')){
    header("HTTP/1.0 404 Not Found");
    include_once("../../site_stop.php");
    exit;
}
$page->makePage();//Заголовки
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
<title><?=$page->title?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$page->keywords?>
    <?=$page->description?>
    <?=$page->canonical?>
<link href="/css/page.css?v=<?=VERSION?>" rel="stylesheet" type="text/css" />
    <link href="/css/bonsherygroom.css?v=<?=VERSION?>" rel="stylesheet" type="text/css" />
    <?php
    //GTM
    include "../../include/gtm_top.php";
    ?>
</head>
<body>
<?php
echo $page->renderAdminBlock();
?>
<div id="container">
    <div class="header">


    </div>
<div class="mainContent">
<?php
echo $page->render();
?>
<!--noindex-->
<div class="share"><p>Расскажите об этой странице друзьям</p>
    <script src="//yastatic.net/share2/share.js" async="async"></script>
    <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,telegram"></div>
</div>
<!--/noindex-->

    <div id="scroller"></div>
</div>

</div>
<?php
    include_once(INCLUDE_PATH."footer.php");
if($page->viev_pform){
    echo $page->podpBaner();
}
if ($page->ajax){
    echo '<script type="text/javascript" src="/js/tw-sack'.(BD_SERVER!='127.0.0.1'?'.min':'').'.js" ></script>';
    $page->script.= 'var ajax = new sack();';
}
if ($page->script!=''){
    echo '<script type="text/javascript"> ';
    echo $page->script;
    echo ' </script>';
}
?>
<script src="/js/page02.min.js?v=<?=VERSION?>" ></script>
</body>
</html>
