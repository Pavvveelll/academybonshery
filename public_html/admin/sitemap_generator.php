<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 29.08.2017
 * Time: 20:14
 */
require_once("../class/common.php");
$sitemap = new Sitemap();
if(isset(LocalConfig::$site_map)){
    $sitemap->init(LocalConfig::$site_map);
}
if($sitemap->make()){
    if(count($sitemap->errors)==0){
        //сформирован, ошибок нет
        if($sitemap->save()){
            print 'res=ok&info='. nl2br($sitemap->info);
        }else{
            print 'res=error&errors='.implode('|',$sitemap->errors);
        }
    }else{
        print 'res=error&errors='.implode('|',$sitemap->errors);
    }
}else{
    if ($sitemap->status=='timeout'){
        print 'res=timeout';
    }else{
        print 'res=error&errors='.implode('<br>',$sitemap->errors);
    }
}
