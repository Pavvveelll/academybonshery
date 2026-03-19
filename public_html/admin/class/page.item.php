<?php 

//print_r($_POST);
 $item  = new catalog();
$settings_array=array(
	'fields'=>array(
		array(
			'name'=>"id",
			'format'=>"key",
			'viev'=>"key",
			'default'=>0
		),
		array(
			'name'=>"parent",
			'format'=>"int",
			'text'=>"Категория",
			'viev'=>"select",
			'default'=>1,
			'target'=>"page"
		),
		array(
			'name'=>"name",
			'format'=>"text",
			'text'=>"Пункт меню",
			'viev'=>"text",
			'max_length'=>32,
			'unique'=>' - это название уже используется.',
			'required'=>"Пункт меню"
		),

		array(//заголовок  
			'name'=>"title",
			'format'=>"text",
			'text'=>"Title",
			'viev'=>"text",
			'max_length'=>255,
			'required'=>"Title"
		),
		array(//анонс h2 он же описание без спецсимволов
			'name'=>"anons",
			'format'=>"text",
			'text'=>"Descr:",
			'viev'=>"textarea",
			'max_length'=>250,
			'required'=>"Description"
		),
		array(//заголовок  
			'name'=>"tlist",
			'format'=>"text",
			'text'=>"Заголовок в списке<br /><span class=\"prim\" >(если отстутствует, в списке не показывается)</span>",
			'viev'=>"text",
			'max_length'=>255
		),
		array(//Псевдоним латиницей формируется автоматически
			'name'=>"nik",
			'format'=>"text",
			'text'=>"Ник (латиница):",
			'viev'=>"text",
			'unique'=>' - этот псевдоним уже используется. Измените его.',
			'max_length'=>128
		),
		'foto'=>array(//для картинок обязательно такой синтаксис
			'name'=>"foto",
			'format'=>"img",
			'text'=>"<strong>Иконка:</strong> (jpeg, gif)<br />Строго 130х90",
			'viev'=>"img_full",
			'img_width'=>130,
			'img_height'=>90,
//			'img_strict'=>"proportion",///пропорции должны быть соблюдены
//			'img_strict_error'=>"Загружаемая картинка для иконки должна быть в пропорции 1х1, т.е. квадратная."
		),
		array(
			'name'=>"article",
			'format'=>"text",
			'text'=>"<strong>Текст:</strong>",
			'viev'=>"textarea",
			'textarea'=>8,
			'html'=>1
		),
		array(
			'name'=>"keywords",
			'format'=>"text",
			'text'=>"<strong>Ключевые слова:</strong><br />
                       <span class=\"prim\" >(через пробел)</span>",
			'viev'=>"text",
			'max_length'=>128
		),	
		array(
			'name'=>"timeadd",
			'text'=>"Добавлено:",
			'format'=>"datetime",
			'viev'=>"datetime",
			'default'=>"now"
		),//////
		array(
			'name'=>"look",
			'format'=>"checkbox",
			'text'=>"<strong>Показывать?</strong>",
			'viev'=>"checkbox",
			'default'=>"no"
		),
        array(
            'name'=>"vievps",
            'format'=>"checkbox",
            'text'=>"Разрешить индексирование поисковыми системами",
            'viev'=>"checkbox",
            'default'=>"yes"
        ),
		array(
			'name'=>"action_item",
			'format'=>"hidden",
			'viev'=>"hidden",
			'default'=>"add"
		)
	),
	'entity'=>"page",
	'table'=>"page",
	'items_table'=>"page",
	'items_key'=>"parent",
	'picture_path'=>""
);


$item->set_settigs($settings_array);

if (isset($_POST['action_item'])){

	//формируем псевдоним	
	if(($_POST['nik']=="")&&($_POST['name']!="")){
		//print($item->translit(trim($_POST['name'])));
		
		//if((stripcslashes(trim($_POST['name']))!=$item->get_value("name", $_POST['id']))||($_POST['id']==0)){
			$translit_nik=$item->translit(trim($_POST['name']));
			$add_nik="";
			do {
				$new_nik=$translit_nik.strval($add_nik);
				$_POST['nik']=$new_nik;
				if($add_nik==""){
					$add_nik=2;
				}else{
					$add_nik=$add_nik+1;
				}
			}while ($item->check_unique("nik", $new_nik)==false);
		//}
	}
	
	//die('==');

	
	$item->action($_POST['action_item']);
	
	//устанавливаем rank
	if((intval($_POST['id'])==0)&&($item->my_item['key']!=0)){
		$item->set_value('rank', $item->my_item['key'], $item->my_item['key']);;
	}

 
	if($item->error==""){
		if(strstr($_POST['action_item'],"delete_item")){
			header("Location:".SERVER_HOST.$phpself.'?cat='.$_POST['parent']);
		}else{
			header("Location:".SERVER_HOST.$phpself.'?id='.$item->my_item['key']);//TODO с учетом страницы
		}
		exit;
	}
}
?>