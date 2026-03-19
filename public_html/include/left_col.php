<?php
if(file_exists(ROOT_PATH."data/left.txt")){
    include(ROOT_PATH."data/left.txt");
}
//не показываем подписанным не курсы и сказавшим что они грумеры или подписаным на МК
if(!( (isset($_COOKIE["kurs"]) && $_COOKIE["kurs"]=='yes' ) || isset($_COOKIE["master"]) )){//проверяем куку
    if(!isset($_GET['rst'])){//или пришел по ссылку из письма
        ?>
        <div class="braunpodp">
            <a href="/subscribe/"><img src="/img/7shogov.png" alt="рассылка для будущих грумеров" width="265" height="154" /></a>
        </div>
        <?php
    }
}
// для ГРУМЕРОВ
if(isset($_COOKIE["master"])){
    echo '<div class="braunpodp">';
    echo '<a href="/master_klass/udalennoe_obuchenie/" ><img src="/img/veterinar2mini.png" alt="ветеринария для грумеров" width="265" height="85"   border="0" /></a>';
    echo '</div>';
}
/*    <ul>
        <li><a href="/vacancy_rabota_grumerom/">Вакансии</a></li>
        <li><a rel="nofollow" href="https://www.facebook.com/BonsheryAcademy/">Мы в Фейсбук</a></li>
        <li><a rel="nofollow" href="https://vk.com/club_bonshery">Мы ВКонтакте</a></li>
        <li><a rel="nofollow" href="https://www.instagram.com/bonshery_groom/">Наш Инстаграм</a></li>
        <li><a rel="nofollow" href="https://ok.ru/group/54986486186225/">Одноклассники</a></li>
        <li>Принимаем к оплате</li>
    </ul>*/
?>
<div class="braun">
    <div class="braun_center">Дополнительно</div>
    <p class="braun__p"><a href="/vacancy_rabota_grumerom/">Вакансии</a></p>
    <p class="braun__p">Мы в социальных сетях</p>
    <div class="tbl__icons tbl__icons_lb">
        <a rel="nofollow" class="teleblock__it teleblock__it_icon teleblock__it_tonefb" href="https://www.facebook.com/BonsheryAcademy/" title="Мы в Фейсбук"></a>
        <a rel="nofollow" class="teleblock__it teleblock__it_icon teleblock__it_tonevk" href="https://vk.com/club_bonshery" title="Мы ВКонтакте"></a>
        <a rel="nofollow" class="teleblock__it teleblock__it_icon teleblock__it_toneok" href="https://ok.ru/group/54986486186225/" title="Одноклассники"></a>
        <a rel="nofollow" class="teleblock__it teleblock__it_icon teleblock__it_toneinst" href="https://www.instagram.com/bonshery_groom/" title="Наш Инстаграм"></a>

    </div>
    <p class="braun__p">Принимаем к оплате</p>
    <p class="braun__p braun_center"><img src="/img/logopay.png" alt="оплата VISA mastercard МИР" width="182" height="33"></p>

</div>
<div class="braun braun_center">
<a href="/sveden/">Сведения об образовательной организации</a>
</div>

<?php



if( DEBUG!=true){ ?>
<div style="text-align:center">
<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
var _tmr = window._tmr || (window._tmr = []);
_tmr.push({id: "1883127", type: "pageView", start: (new Date()).getTime()});
(function (d, w, id) {
    if (d.getElementById(id)) return;
    var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
    ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
    var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
    if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window, "topmailru-code");
</script><noscript><div>
<img src="//top-fwz1.mail.ru/counter?id=1883127;js=na" style="border:0;position:absolute;left:-9999px;" alt="" />
</div></noscript>
    <!-- //Rating@Mail.ru counter -->
    <!-- Rating@Mail.ru logo -->
<a href="https://top.mail.ru/jump?from=1883127">
<img src="//top-fwz1.mail.ru/counter?id=1883127;t=310;l=1"
     style="border:0;" height="15" width="88" alt="Рейтинг@Mail.ru" /></a>
    <!-- //Rating@Mail.ru logo -->
</div>
<?php

/* <!-- Rating@Mail.ru counter -->
<script type="text/javascript">//<![CDATA[
(function(w,n,d,r,s){d.write('<p><a href="http://top.mail.ru/jump?from=1883127">'+
'<img src="http://db.cb.bc.a1.top.mail.ru/counter?id=1883127;t=104;js=13'+
((r=d.referrer)?';r='+escape(r):'')+((s=w.screen)?';s='+s.width+'*'+s.height:'')+';_='+Math.random()+
'" style="border:0;" height="18" width="88" alt="Рейтинг@Mail.ru" /><\/a><\/p>');})(window,navigator,document);//]]>
</script>
<!-- //Rating@Mail.ru counter --> */

} ?>

