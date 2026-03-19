<?php
/*
для админа - расширенный
*/
$init_array['setup']= "function(ed) {
ed.addButton('divwrapper', {
                  title : 'Wrap Selected Content with a div',
                  image : '/tiny_mce/divwrapper.gif',
                  onclick : function()
                  {ed.selection.setContent( '<div >'+ed.selection.getContent()+'</div>'); }
               });
}";
$init_array['mode']= "exact";
$init_array['elements']=  substr($tiny_elements, 0, -1);
$init_array['theme']= "advanced";
$init_array['plugins']= "table,paste,advimage,style";
$init_array['language']= "ru";
$init_array['content_css']= "/css/tiny_mce01.css";
$init_array['theme_advanced_font_sizes']= "12px,14px,15px,16px";
$init_array['theme_advanced_disable']= "strikethrough,underline";
$init_array['theme_advanced_buttons1_add']= "fontsizeselect";
$init_array['theme_advanced_buttons2_add_before']= "pastetext,pasteword,separator,divwrapper";
$init_array['theme_advanced_buttons2_add']= "styleprops,removeformat,code,help";
$init_array['theme_advanced_resize_horizontal']= "false";
$init_array['theme_advanced_resizing']= "true";
///$init_array['theme_advanced_blockformats']= "p,div,h1,h2,h3,h4,h5,h6,blockquote,dt,dd,code,samp";
$init_array['theme_advanced_buttons3_add_before']= "tablecontrols,separator";
//$init_array['theme_advanced_buttons3']= "";
$init_array['theme_advanced_toolbar_location']= "top";
$init_array['theme_advanced_toolbar_align']= "left";
$init_array['theme_advanced_path_location']= "bottom";
$init_array['relative_urls']= "false";
$init_array['convert_urls']= "false";
//$init_array['invalid_elements']= "h1";
$init_array['extended_valid_elements']= "a[name|class|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],iframe[src|width|height|name|align|title|frameborder|allowfullscreen],script[src|type|charset|async]";
if ($id>0){
	//TODO для каждого раздела использующего свои ID создавать собственное img_basename
	$init_array['external_image_list_url']="/file_manager/get_image_list.php?type=img&img_basename=page_".$id;
	$init_array['file_browser_callback']= "fileBrowserCallBack";
	global $sufix;
	$fileBrowserCallBack=" function fileBrowserCallBack(field_name, url, type, win) {
var connector = \"/file_manager/file_manager.php\";
my_field = field_name;
my_win = win;
switch (type) {
	case \"image\":
		connector += \"?type=img\";
		break;
	case \"media\":
		connector += \"?type=media\";
		break;
	case \"flash\": //for older versions of tinymce
		connector += \"?type=media\";
		break;
	case \"file\":
		connector += \"?type=files\";
		break;
}
connector+=\"&img_basename=page_". $id."\";

window.open(connector, \"file_manager\", \"modal,width=950,height=600,scrollbars=1\");}";


}
//	alert('/file_manager/get_image_list.php?type=img&img_basename=page_$id');
?>
