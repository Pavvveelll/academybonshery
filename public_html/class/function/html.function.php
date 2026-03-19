<?php 
/*
** функции переработки в HTML форму **
входные параметры
массив полей
и массив списков $select_sourse
*/ 
//19 07 2008 	исправлен баг Checkbox
//				добавлен атрибут deletefieldname  к кнопкам теперь опрределено поле для передачи удаления TODO Картинки!!!
//				добавлен атрибут deleteconfirm вывода предупреждений перед удалением
//				добавлен атрибут  deletetext текст кнопки удаления
//11 12 2008	добавлен способ отображения превью в случае нескольких преобразований картинок
//16 02 2009 	добавлен атрибут max_size в поле img, формирует скрытое поле ограничивающее размер файла
//07 05 2009 	добавлен параметр form_name, для определения имени формы
//09 12 2009	добавлен multicheck		- отбор нескольких чекбоксов
//10 12 2009    для текста, селекта и multicheck добавлена проверка существования значения TODO может устанавливать в дефолт?
//12 01 2010    if((isset($id))&&($id!=0)){
//17 01 2009	c выходом TinyMCE v3 изменена обработка $tiny_string

//TODO - кэширование формы без данных
function html_form($fields,$item,$select_sourse=array(),$template="",$form_name='form_item'){
	//print_r($select_sourse);
	$tinymceinit="";
	$tiny_elements="";
	$calendar_init="";
	//формируем имя формы 
	//$form_name='form_item';
	//print crc32( $fields);
	//$checksum = crc32("The quick brown fox jumped over the lazy dog.");
	//$form_name= sprintf("%u\n", crc32(implode("",$fields)));

	if($template=="")			
		$template="<tr><td width=\"200\" valign=\"top\">%s
	</td><td width=\"5\">&nbsp;</td><td width=\"505\" valign=\"top\">%s</td></tr>";
	$template2="<tr><td colspan=\"3\" valign=\"top\">%s%s</td></tr>";
	
	$form="<script language=\"JavaScript\" type=\"text/javascript\" src=\"/js/forms.js\"></script>";
	$form.="<form action=\"".((isset($fields['action_form']))?($fields['action_form']):(""))."\" method=\"post\" enctype=\"multipart/form-data\" name=\"$form_name\" id=\"$form_name\"><table width=\"98%\" border=\"0\">";//результат
	$hidden_fields="";
	foreach($fields as $field){
		//print_r($field);
		if(!isset($field['viev']))
			continue;
		switch ($field['viev']){/////////////
		case "key":
			$id=$item[$field['name']];
		case "hidden":
		case "hiddenaction":
			$hidden_fields.=sprintf("<input name=\"%s\"  id=\"%s\" type=\"hidden\" value=\"%s\" />",
							$field['name'], $field['name'],$item[$field['name']]);
			break;
		case "text"://для текстовых полей
		case "email":
		case "url":
			$form.=sprintf($template,$field['text'].((isset($field['required']))?("*"):("")),"<input name=\"".$field['name']."\" id=\"".$field['name']."\"  class=\"fullwidth\" type=\"text\" ".((isset($field['max_length']))?("maxlength=\"".$field['max_length']."\""):(""))."value=\"".((isset($item[$field['name']]))?(htmlspecialchars($item[$field['name']])):(""))."\" />");
		break;
		case "password":	
			$form.=sprintf($template,$field['text'].((isset($field['required']))?("*"):("")),"<input name=\"".$field['name']."\" id=\"".$field['name']."\"  class=\"fullwidth\" type=\"password\" ".((isset($field['max_length']))?("maxlength=\"".$field['max_length']."\""):(""))."value=\"\" />");
			
			break;
		case "select":	
		case "groppe":	
			if((isset($field['sourse']))&&(is_array($field['sourse']))){
				$select_array=$field['sourse'];
			}else{
				$select_array=current($select_sourse);
				next($select_sourse);
			}
			$add_select="<select name=\"".$field['name']."\" id=\"".$field['name']."\" class=\"fullwidth\">";
			foreach($select_array as $key=>$value){
				$add_select.=sprintf("<option value=\"%s\" %s >%s</option>",$key,
				(  (isset($item[$field['name']]))&&($key==$item[$field['name']])    )?("selected=\"selected\""):(""),$value);
			}//foreach
			$add_select.="</select>";
			$form.=sprintf($template,$field['text'].((isset($field['required']))?("*"):("")),$add_select);
			break;
		case "radio":
			$radio_add="";
			if(is_array($field['sourse'])){
				$radio_array=$field['sourse'];
			}else{
				$radio_array=current($select_sourse);
				next($select_sourse);
			}
			foreach($radio_array as $key=>$value){
				$radio_add.="\n<label>";
				$radio_add.="<input name=\"".$field['name']."\" type=\"radio\" value=\"".$key."\" ";//print("key=".$key." "."value=".$value);]
				//print($item[$field['name']]);
				if($item[$field['name']]==$key){
					$radio_add.=" checked=\"checked\" ";
				}
				//$radio_add.=(($key==$item[$field['name']])?(" checked=\"checked\" "):(""));
				$radio_add.= " />".$value."</label><br />";
			}
			$form.=sprintf($template,$field['text'].((isset($field['required']))?("*"):("")),$radio_add);
			break;
		case "textarea":
			if(isset($field['html'])){
				if($tinymceinit==""){
				$tinymceinit="<script language=\"JavaScript\" type=\"text/javascript\" src=\"/tiny_mce/tinymce.min.js\"></script>";
				}
				$tiny_elements.=$field['name'].",";
				$textarea_add="<textarea class=\"fullwidth\"  name=\"".$field['name']."\"  id=\"".$field['name']."\"";
				if(isset($field['textarea'])){
					$textarea_add.=" rows=\"".$field['textarea']."\"";
				}
				$textarea_add.=">".$item[$field['name']]."</textarea>";
				$form.=sprintf($template2,$field['text'].((isset($field['required']))?("*"):("")),$textarea_add);
			
			}else{
				$textarea_add="<textarea class=\"fullwidth\"  name=\"".$field['name']."\"  id=\"".$field['name']."\"";
				if(isset($field['textarea'])){
					$textarea_add.=" rows=\"".$field['textarea']."\"";
				}
				
				$contar="";
				$contar2="";
				if(isset($field['max_length'])){
					$contar_name="ost".$field['name'];
					$contar="<br />Осталось <span id=\"".$contar_name."\">".$field['max_length']."</span> знаков.";
					$textarea_add.="  onkeyup=\"javascript:Contar('".$field['name']."','$contar_name',".$field['max_length'].")\" onkeypress=\"javascript:Contar('".$field['name']."','$contar_name',".$field['max_length'].")\" onchange=\"javascript:Contar('".$field['name']."','$contar_name',".$field['max_length'].")\"";
					$contar2="<script language=\"JavaScript\" type=\"text/javascript\" >Contar('".$field['name']."','$contar_name',".$field['max_length'].")</script>";
				}
				$textarea_add.=">".$item[$field['name']]."</textarea>";
				$form.=sprintf($template,$field['text'].((isset($field['required']))?("*"):("")).$contar,$textarea_add . $contar2);
			}
			break;
		case "checkbox":
			$checkbox_add="<input name=\"".$field['name']."\" type=\"checkbox\" value=\"yes\" ";
			$checkbox_add.=(($item[$field['name']]=="yes")?(" checked=\"checked\" "):(" "))."/>";
			$form.=sprintf($template,$field['text'].((isset($field['required']))?("*"):("")),$checkbox_add);
			break;
		case "multicheck":
			$add_select="";
			if((isset($field['sourse']))&&(is_array($field['sourse']))){
				$select_array=$field['sourse'];
			}else{
				$select_array=current($select_sourse);
				next($select_sourse);
			}
			foreach($select_array as $key=>$value){
				$add_select.=sprintf("<label><input name='%s_%s' type='checkbox' id='%s_%s' value='435' %s />
      %s</label><br />",$field['name'],$key,$field['name'],$key,
	  		((!isset($item[$field['name']]))?(""):(
			((is_array($item[$field['name']]))?(  (in_array($key,$item[$field['name']]))?("checked='checked' "):("")  )
			 :(  ($key==$item[$field['name']])?("checked='checked' "):(""))))),$value,$field['name']);
			}//foreach
			$form.=sprintf($template,$field['text'].((isset($field['required']))?("*"):("")),$add_select);
			break;
		case "datetime":
			///print "datetime"	.$item[$field['name']];
			$form.=sprintf($template,$field['text'],(((isset($item[$field['name']]))&&($item[$field['name']]!=""))?(
			date("d-m-Y H:i:s",strtotime($item[$field['name']]))
			//$item[$field['name']]
			):("")));
			break;
		case "datetimeeditable":
            //ДАТА YYYY-MM-DD HH:MM:SS
            if($calendar_init==""){
                $calendar_init="\n<style type=\"text/css\">@import url(/js/calendar-system.css);</style>
								<script type=\"text/javascript\" src=\"/js/calendar.js\"></script>
								<script type=\"text/javascript\" src=\"/js/calendar-rus.js\"></script>
								<script type=\"text/javascript\" src=\"/js/calendar-setup.js\"></script>";
            }
            $date_adds='<input type="hidden" name="'.$field['name'].'" id="'.$field['name'].'f_date_d" value="'.
                (date("Y-m-d H:i:s",strtotime($item[$field['name']]))).'" />';
            $date_adds.='<span id="'.$field['name'].'show_e">'.
                ( (isset($item[$field['name']])&&(intval(strtotime($item[$field['name']]))>1) )?(date("d-m-Y",strtotime($item[$field['name']]))):(
                '<span style="color:red">не установлена</span>')).'</span>&nbsp;&nbsp;<img src="/js/calendar.gif" id="'.$field['name'].'f_trigger_e"
			style="border: 1px solid red; cursor: pointer;" title="Изменить"
			onmouseover="this.style.background=\'red\';" onmouseout="this.style.background=\'\'">
			<script type="text/javascript">
				Calendar.setup({
					inputField     :    "'.$field['name'].'f_date_d",
					ifFormat       :    "%Y-%m-%d %H:%M:%S",
					displayArea    :    "'.$field['name'].'show_e",
					button         :    "'.$field['name'].'f_trigger_e",
					daFormat       :    "%d-%m-%Y"
				});
			</script>';
            $form.=sprintf($template,$field['text'],$date_adds);
            //
            break;
		case "img":
		case "img_full":
			$img_add="";
			if($item[$field['name']]!=""){
				if($field['viev']=="img_full"){//показываем саму картинку, иначе превью
					$img_add.=sprintf("<img src=\"/picture/%s%s.%s\" hspace=\"5\"  align=\"right\" >",
						$field['name'],$id,$item[$field['name']]);
				}else{
					if(!is_array($field['previev'])){//для обратной совместимости
						$img_add.=sprintf("<img src=\"/picture/previev/%s%s.%s\" hspace=\"5\"  align=\"right\" >",
							$field['name'],$id,$item[$field['name']]);
					}else{//берем первое попавшееся
						/*$img_add.=sprintf("<img src=\"/picture/%s%s%s.%s\" hspace=\"5\"  align=\"right\" >",
							$field['name'],$id,$field['previev'][0]['nameplus'],$item[$field['name']]);*/
						$img_add.=sprintf("<img src=\"/picture/%s%s%s.%s\" hspace=\"5\"  align=\"right\" >",
							$field['name'],$id,$field['previev'][0]['nameplus'],$item[$field['name']]);	
							
					}
				}
				
				
			}
			$img_add2="";
			if($item[$field['name']]!=""){
			$img_add2.="<div class=\"r\" style=\"float: left;\">";
			$img_add2.="<a href=\"javascript:confirmDeleteAction('".$field['name']."','action_item','Удалить?');\">";
			$img_add2.="Удалить</a></div>";
			}else{
				if(isset($field['max_size'])){
					$img_add2.="<input name=\"MAX_FILE_SIZE\" value=\"".$field['max_size']."\" type=\"hidden\">";
				}
			$img_add2.="<input name=\"".$field['name']."\" class=\"fullwidth\" id=\"".$field['name']."\" type=\"file\">";
			}
			$form.=sprintf($template,$img_add.$field['text'].((isset($field['required']))?("*"):("")),$img_add2);
			break;
		case "header":
			$form.=sprintf($template2,$field['text'],"");
		break;
		case "label":
			$form.=sprintf($template,$field['text'],((isset($item[$field['name']]))?($item[$field['name']]):("&nbsp;")));
		break;
		}//switch
	}//foreach 

	
	
	$button_array=current($select_sourse);
	//print_r($button_array);
	//print_r("hjkhjk=".$select_array);
	next($select_sourse);
	if(!defined('ANMIN_PAGE')){
		if((isset($id))&&($id!=0)){
	$form.=sprintf($template,(isset($button_array['delete']) && $button_array['delete']=='hide')?('&nbsp;'):("<a href=\"javascript:confirmDeleteAction('item','".
			((isset($button_array['deletefieldname']))?($button_array['deletefieldname']):('action_item'))."', '".
			((isset($button_array['deleteconfirm']))?($button_array['deleteconfirm']):('Удалить?')).
			"');\"><img src='/img/delete.gif' alt='Удалить' width='126' height='19' border='0' /></a>")
			,"<a href=\"javascript:document.forms['$form_name'].submit();\"><img src='/img/save.gif' alt='Сохранить' width='126' height='19' border='0' /></a>");
		}else{
	$form.=sprintf($template,"&nbsp;","<a href=\"javascript:document.forms['$form_name'].submit();\"><img src='/img/".((isset($button_array['saveimg']))?($button_array['saveimg']):('save.gif'))."' alt='".$button_array['submit']."'   border='0' /></a>");
		}
	}
	$form.="</table>";
	
	if(defined('ANMIN_PAGE')){
	$form.="<div class=\"buttons\">";	
	$form.="<div class=\"b\"><a href=\"javascript:document.forms['$form_name'].reset();\">Сброс</a></div>";
	$form.="<div class=\"g\"><a href=\"javascript:document.forms['$form_name'].submit();\">";
	$form.=((isset($button_array['submit']))?($button_array['submit']):("Cохранить"));
	$form.="</a></div>";
	
	if($id!=0){//key
		if(!isset($button_array['delete'])||($button_array['delete']!='hide')){
		$form.="<div class=\"r\" style=\"float:left\"><a href=\"javascript:confirmDeleteAction('item','".((isset($button_array['deletefieldname']))?($button_array['deletefieldname']):('action_item'))."', '".((isset($button_array['deleteconfirm']))?($button_array['deleteconfirm']):('Удалить?'))."');\">".((isset($button_array['deletetext']))?($button_array['deletetext']):('Удалить'))."</a></div>";
		}
	}
	$form.="</div>";
	}
	$form.=$hidden_fields;
	$form.="</form>";
	if($tinymceinit!=""){
		$fileBrowserCallBack="";
		$init_array=array();
		if(defined('ANMIN_PAGE')){
			include("tiny_init5.php");
		}else{//for user
			include("tiny_init_1.php");
		}
		$tinymceinit.=tiny_init($id,$init_array,$fileBrowserCallBack);
	}	
	$form=$tinymceinit.$calendar_init.$form;
	return $form;
}

function tiny_init($id,$init_array,$add){
    $tiny_string =  <<<TINY
<script>
tinymce.init({
//  language: 'ru',
  selector: 'textarea#article',
  element_format : 'html',
  schema: 'html5-strict',
  body_class: 'article',
  height: 500,
  relative_urls: false,
  plugins: [
    'nonbreaking,code,table,image,link,media,paste,lists,anchor,help'
  ],
  setup: function(ed) {
      ed.addMenuItem('example', {
         text: 'Insert DIV over',
         context: 'insert',
         onclick: function() {
            ed.selection.setContent( '<div >'+ed.selection.getContent()+'</div>');
         }
      });
   },
  block_formats: 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;DIV=div',
  removed_menuitems:'newdocument',
   //toolbar: "media",
  // extended_valid_elements : 'p[class|style]',
  //extended_valid_elements : 'table[class|style]',
    //table_appearance_options: false,
    invalid_elements : 'span',
   extended_valid_elements: 'script[language|type|src|charset|async],table[id|class|style],' +
    'a[class|id|onclick|target<_blank?_self?_top?_parent|href|type|rel<alternate?archives?author?bookmark?external?feed?first?help?index?last?license?next?nofollow?noreferrer?prev?search?sidebar?tag?up]',
    
  table_class_list: [
    {title: 'None', value: ''},
    {title: 'kortable', value: 'kortable'}
  ],
  table_cell_class_list: [
    {title: 'нет', value: ''},
    {title: 'отзывы', value: 'comm'},
    {title: 'светлее', value: 'td_sel'}
  ],
   table_cell_advtab: false,
   rel_list: [
    {title: 'стандарт', value: ''},
    {title: 'чужая ссылка', value: 'nofollow'}
  ],
//  formats: {
//    alignleft: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'left'},
//    aligncenter: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'center'},
//    alignright: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'right'},
//    alignjustify: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : ''} 
////    bold: {inline : 'span', 'classes' : 'bold'},
////    italic: {inline : 'span', 'classes' : 'italic'},
////    underline: {inline : 'span', 'classes' : 'underline', exact : true},
////    strikethrough: {inline : 'del'},
////    forecolor: {inline : 'span', classes : 'forecolor', styles : {color : '%value'}},
////    hilitecolor: {inline : 'span', classes : 'hilitecolor', styles : {backgroundColor : '%value'}},
////    custom_format: {block : 'h1', attributes : {title : 'Header'}, styles : {color : 'red'}}
//  },
  content_css: ['/css/tiny.css'],
  //importcss_append: false, 
    
//  importcss_groups: [
//    {title: 'Списки', selector: 'ul', filter: 'list-'}
//  ],
  
    style_formats: [
    {title: 'Списки', items: [
      {title: 'галки', selector: 'ul', classes: 'list-chek'},
      {title: 'звезды', selector: 'ul', classes: 'list-star'},
       {title: 'стрелки', selector: 'ul', classes: 'list-arrow'},
      {title: 'плюсы', selector: 'ul', classes: 'list-plus'},
      {title: 'плюсы малые', selector: 'ul', classes: 'list-plus_min'}     
    ]},
    {title: 'светлый блок', selector: 'div', classes: 'block'},
    {title: 'новая строка', selector: 'div,p,h1,h2,h3', classes: 'clearfix'},
    {title: 'подписка', selector: 'div', classes: 'podpblock'},
    {title: 'увеличенный текст', selector: 'p,div,td', classes: 'bigtxt'}
    ],
    
//    formats: {
//    alignleft: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'left'},
//    aligncenter: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'text_c'},
//    alignright: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'right'},
//    alignjustify: {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'full'},
//    bold: {inline : 'span', 'classes' : 'bold'},
//    italic: {inline : 'span', 'classes' : 'italic'},
//    underline: {inline : 'span', 'classes' : 'underline', exact : true},
//    strikethrough: {inline : 'del'},
//    forecolor: {inline : 'span', classes : 'forecolor', styles : {color : '%value'}},
//    hilitecolor: {inline : 'span', classes : 'hilitecolor', styles : {backgroundColor : '%value'}},
//    custom_format: {block : 'h1', attributes : {title : 'Header'}, styles : {color : 'red'}}
//  },
    

   //image_list: '/file_manager/get_image_list.php?type=img&img_basename=page_$id'
   image_class_list: [
    {title: 'нет', value: ''},
    {title: 'иконка слева', value: 'intext_icon'},
    {title: 'в тексте слева', value: 'intext_l'},
    {title: 'в тексте справа', value: 'intext_r'},
    {title: 'по центру', value: 'intext_c'},
    {title: 'с промежутками', value: 'addspace'}
  ],
  //image_dimensions: false
  image_advtab: true,
TINY;

    if($id>0){
        $tiny_string.=  <<<TINY
file_browser_callback: function(field_name, url, type, win) {
//win.document.getElementById(field_name).value = 'my browser value';
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
connector+= '&img_basename=page_{$id}_';
window.open(connector, 'file_manager', 'modal,width=950,height=600,scrollbars=1');
},
TINY;



    }
    $tiny_string.= "
        });
</script>
        ";

    return $tiny_string;

/*	$tiny_string="<script language=\"JavaScript\" type=\"text/JavaScript\">
	<!--
	tinyMCE.init({";
	//$tiny_string.=implode(",", $init_array);
	foreach($init_array as $k=>$v){
		if(($v=="false")||($v=="true")||($k=='setup')){
			$tiny_string.=$k.":".$v.",";	
		}else{
			$tiny_string.=$k.":\"".$v."\",";
		}
	}
	$tiny_string=substr($tiny_string, 0, -1);
	$tiny_string.="});$add//-->	
	</script>";
	return $tiny_string;*/
}