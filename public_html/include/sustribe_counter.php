<?php
//управляем видимостью форм
/*
rst - тип рассылки, добавляется к ссылкам в письмах /nabor/?rst=kurs
kurs - курсы
master - мастер-классы
*/
//проверяем есть ли rst
//ставим куку                   35765be68c-_4_14_2016_delimport
//if(isset($_GET['utm_campaign']) && $_GET['utm_campaign']='35765be68c-_4_14_2016_delimport'){
//    setcookie('kurs', 'yes', 0,"/", COOKIES);
//
////echo '1';
//}else{

    if(isset($_GET['rst']) && ($_GET['rst']=='kurs' || $_GET['rst']=='master')){
        setcookie($_GET['rst'], 'yes', time() + 604800*20,"/", COOKIES);//20 недель//ставим куку ID подписчика
    }
//}
