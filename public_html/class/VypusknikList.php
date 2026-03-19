<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 18.02.2018
 * Time: 22:46
 */

class VypusknikList
{
    private $list;
    private $pagelist=[];
    private $path;
    private $errors=[];
    private $get;

    public function __construct()
    {
        $this->list = new ItemsList();//TODO может сюда и загружать из БД?
        $this->list->setSettings(VypusknikConfig::getConfig());
        $this->path = '/admin/page/';
    }
    public function routing($get){
        $this->get=$get;
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //Передвигаем итемы по rank
            if((isset($_POST['moveid']))&&(intval($_POST['moveid'])>0)&&(isset($_POST['moveshift']))&&(is_numeric($_POST['moveshift']))){
                $move = new MoveItemInList($this->list);
                if($move->move($get, intval($_POST['moveid']), intval($_POST['moveshift']))){
                    //TODO сбрасывать форму и выводить ошибку
                    header("Location:".SERVER_HOST.$_SERVER['REQUEST_URI']);//TODO с учетом страницы
                    exit;
                }else{
                    $this->errors += $move->errors;
                }
            }
        }
        return true;
    }

    public function render()
    {

        //сортируется по умолчанию
        $this->list->loadList([]);//загружаем полный список обрабатывать будем здесь TODO загрузку с LEFT JOIN
        $this->errors+=$this->list->errors;//FIXME сделать по нормальному

        //предварительная поготовка данных
        $counter=1;
        foreach ($this->list->items as &$item){
            $item['counter']=$counter++;
            $class= 'vypitem__link';
            if($item['look']=='no'){
                $class.=' vypitem__link_dis';
            }
            $item['name']='<a class="'.$class.'" href="?id='.$item['id'].'">'.$item['name'].'</a>';
//            if ($item['active']=='yes'){
//                if ($item['adm']=='yes'){
//                    $item['active'] ='только админ';
//                    $item['active_mdfclass']='red';
//                }else{
//                    $item['active'] ='да';
//                }
//            }else{
//                $item['active'] ='нет';
//                $item['active_mdfclass']='red';
//            }
//            $item['rank']='<img class="tvrs__arrow" src="/admin/images/down_16.gif" title="вниз" onclick="moveit(\'down\','.$item['id'].')" />
//				        <input class="tvrs__text tvrs__text_center" name="s'.$item['id'].'" id="s'.$item['id'].'" type="text" value="1" size="1"/>
//				        <img  class="tvrs__arrow" src="/admin/images/up_16.gif"   title="вверх" onclick="moveit(\'up\','.$item['id'].')"/>';

        }
        unset($item);

        $res='';
        $res .= '<h1><a href="'.$this->path.'" >Выпускники</a></h1>';

        $this->errors += $this->list->errors;
        if (count($this->errors)>0){
            $res .= '<div class="propertylist__errors">'.implode('<br />', $this->errors).'</div>';
        }



        $table = new HtmlTable($this->list);
        $res .= $table->render();

//        $counter=1;
//        foreach ($this->list->items as $item){
//            $item['counter']=$counter++;
//            //array_push($item['niks'],$item['nik']);
//            $res.= $this->renderItem($item);
//        }


        //форма для перемещения FIXME пока одна на странице
        $res .= '<form action="" method="post" name="moveform" id="moveform">';
        $res .= '<input name="moveid" id="moveid" type="hidden" value="0" />';
        $res .= '<input name="moveshift" id="moveshift" type="hidden" value="0" />';
        $res .= '</form>';

        $res .= '<div class="btns">
        <div class="btns__btn  btns__btn_green"><a href="?id=0">Добавить...</a></div>
        </div>';
        return $res;
    }

    private function renderItem($item){

        $res='';
/*        $res.= '<div class="vypitem">';
        $res.= '<div class="pagesitem__nav">';
        //$res.= '<a href="/'.implode('/',$item['niks']) .'/" target="_blank">';
        $res.= '<img width="16" height="16" border="0"'.(($item['look']!="yes")?(' title="не опубликовано" src="/img/previev_none.gif" '):(' title="смотреть на сайте" src="/img/previev.gif" ')).'>';
        $res.= '</a> ';
        $res.= '<a href="?id='. $item['id'].'"><img width="16" height="16" border="0" title="редактировать" src="/img/edit_16.gif"></a> ';

        $res.= '<a href="?parent='. $item['id'].'"><img width="16" height="16" border="0" title="вложенные страницы" src="/img/';
        if(isset($this->pagelist[$item['id']])){
            $res.= 'kateg.gif';
        }else{
            $res.= 'kateg_none.gif';
        }
        $res.= '"></a>';

        //блок для перемещения TODO сделать одинаково для всех списков
        $res.= self::renderMoveItem($item['id']);
        $res.= '</div >';

        $res.= '<div class="pagesitem__box">';
        $res.= '<h2><a href="?id='. $item['id'].'"';

        $res.= '>'. $item['counter'].'.&nbsp;'.$item['name'].'</a></h2>';

        $res.= '</div>';
        $res.= '</div>';*/




        return $res;
    }
    static function renderMoveItem($id){
        $res='';
        $res.=<<<"MOVE"
        <div class="movelist">
        <img class="movelist__arrow" src="/admin/images/down_16.gif" title="вниз" onclick="moveit('down','$id')" />
		<input class="movelist__text" name="s$id" id="s$id" type="text" value="1" size="1" />
	    <img  class="movelist__arrow" src="/admin/images/up_16.gif" title="вверх" onclick="moveit('up','$id')"/>
	    </div>
MOVE;
        return $res;

    }

}