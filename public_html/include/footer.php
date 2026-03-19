<?php if( DEBUG!=true){ ?>
<!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter8132020 = new Ya.Metrika({id:8132020, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true, ut:"noindex"}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/8132020?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MXGC94J"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

<?php }


//<script>
//(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
//    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
//            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
//        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
//
//ga('create', 'UA-73885556-1', 'auto');
//ga('send', 'pageview');
//
//</script>
//ОТКЛЮЧЕН!!! 1==2
if( 1==2 && DEBUG!=true && !in_array($page->item_viev->my_item['nik'],['bonsherygroom','english'])) {
    $curtime = intval(date("G"));
    if ($curtime >= 10 && $curtime <= 18) {
        //  https://zadarma.com/ru/
        //  petsgroomer@yandex.ru
        // sf56l4TyW
        ?>
        <script type="text/javascript">
            var ZCallbackWidgetLinkId = 'f82007429fa1eaf6e6275d5b1551902e';
            var ZCallbackWidgetDomain = 'my.zadarma.com';
            (function () {
                var lt = document.createElement('script');
                lt.type = 'text/javascript';
                lt.charset = 'utf-8';
                lt.async = true;
                lt.src = 'https://' + ZCallbackWidgetDomain + '/callbackWidget/js/main.min.js?unq=' + Math.floor(Math.random(0, 1000) * 1000);
                var sc = document.getElementsByTagName('script')[0];
                if (sc) sc.parentNode.insertBefore(lt, sc);
                else document.documentElement.firstChild.appendChild(lt);
            })();
        </script>
        <?php
    }
}