<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 15.04.2018
 * Time: 15:58
 */

namespace ShortCode;


class Facebook implements iShortCode
{
    public function render($param){
        $block='';

        $block.='<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FBonsheryAcademy%2F&tabs&width=670&height=154&small_header=true&adapt_container_width=true&hide_cover=true&show_facepile=true&appId=2010005232647885" width="670" height="154" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>';

/*        $block.='<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = \'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.0&appId=2010005232647885&autoLogAppEvents=1\';
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>';

        $block.='<div class="fb-page" data-href="https://www.facebook.com/BonsheryAcademy/" data-width="670"
data-small-header="true" data-adapt-container-width="true" data-hide-cover="true"
data-show-facepile="true"><blockquote cite="https://www.facebook.com/BonsheryAcademy/"
class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/BonsheryAcademy/">Академия Груминга Боншери</a></blockquote></div>';
        */

        $block.='<br class="clearfloat" />';
        return $block;
    }
}