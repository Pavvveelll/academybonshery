<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 06.02.2018
 * Time: 15:09
 */

class LocalTinyConfig
{
    static function tinyConf($tiny_conf){
        //importcss_append: false, ";
        //image_advtab: true,";
        //$sitename = SITENAME;
        $version = VERSION;
        if($tiny_conf['mode'] == 'vypusknik'){
            $res = <<<CONF
menubar: false,
plugins: [
    'nonbreaking,code,link,paste,lists,anchor,help,importcss'
],
toolbar: 'undo redo |  bold italic | link bullist numlist | removeformat code | help',
content_css: ['/css/tiny.css?v={$version}'],  
rel_list: [
    {title: 'стандарт', value: ''},
    {title: 'чужая ссылка', value: 'nofollow'}
],
CONF;
        }
        elseif($tiny_conf['mode'] == 'short'){
            $res = <<<CONF
menubar: false,
plugins: [
    'nonbreaking,code,table,image,link,media,paste,lists,anchor,help,importcss'
],
toolbar: 'undo redo |  bold italic | link bullist numlist | removeformat code | help',
content_css: ['/css/tiny.css?v={$version}'],  
rel_list: [
    {title: 'стандарт', value: ''},
    {title: 'чужая ссылка', value: 'nofollow'}
],
CONF;


        }else{//FULL
            $res = <<<CONF
plugins: ['nonbreaking,code,table,image,link,media,paste,lists,anchor,help,importcss'], 
block_formats: 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;DIV=div',
removed_menuitems:'newdocument', 
content_css: ['/css/tiny.css?v={$version}'],
rel_list: [
    {title: 'стандарт', value: ''},
    {title: 'чужая ссылка', value: 'nofollow'}
],
image_class_list: [
    {title: 'нет', value: ''},
    {title: 'иконка слева', value: 'logoimg'},
    {title: 'с промежутками', value: 'addspace'},
    {title: 'по ширине', value: 'fluid_img'},
    {title: 'в тексте слева', value: 'intxt_left'},
    {title: 'в тексте справа', value: 'intxt_right'}
],
formats: {
    alignleft: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'article_left'},
    aligncenter: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'article_center'},
    alignright: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'article_right'},
    alignjustify: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'article_justify'}
},
setup: function(ed) {
    ed.addMenuItem('example', {
        text: 'Insert DIV over',
        context: 'insert',
        onclick: function() {
                ed.selection.setContent( "<div >"+ed.selection.getContent()+"</div>");
        }
    });  
},
table_class_list: [
    {title: 'None', value: ''},
    {title: 'с рамкой', value: 'bordertable'},
    {title: 'скользкая', value: 'fluid_table'},
],
 

CONF;
            if(isset($tiny_conf['img_basename'])){
                $res.= <<<CONF
file_browser_callback: function(field_name, url, type, win) {
    win.document.getElementById(field_name).value = '';
    var connector = '/file_manager/file_manager.php';
    my_field = field_name;
    my_win = win;
    switch (type) {
        case 'image':
        connector += '?type=img';
        break;
        case 'media':
        connector += '?type=media';
        break;
        case 'file':
        connector += '?type=files';
        break;
    }
    connector+= '&img_basename=$tiny_conf[img_basename]_$tiny_conf[id]_';
    window.open(connector, 'file_manager', 'modal,width=950,height=600,scrollbars=1');
},
CONF;

            }
        }

        return $res;
    }
}