<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 06.12.2015
 * Time: 14:42
 */


	if($item->my_item['id']>0){
        echo '<h1>',$item->my_item['name'],'</h1>';
    }else{
        echo '<h1>Новая оплата</h1>';
    }

if($item->error!=""){
    ?>
    <div id="error"><?php echo $item->error ?></div>
<?php
}
    include_once(CLASS_PATH."function/html.function.php");
    $add_sourse=array();


    //print_r($add_sourse);
    $select_sourse[]=$add_sourse;
    /*$select_sourse[]=$info_cat_array;*/
    //$select_sourse[]=$reference_array;//список списков для формирования списков :)

    //
    print html_form($item->fields,$item->my_item,$select_sourse);

    //все хорошо выводим код ссылки
    if($item->my_item['id']>0 && $item->error==""){
        echo '<p>Код ссылки</p>';
        echo '<textarea class="fullwidth">'.SERVER_HOST.'/oplata/?oplata='.$item->my_item['nik'].'</textarea>';
    }



 //if($item->error!=""){

