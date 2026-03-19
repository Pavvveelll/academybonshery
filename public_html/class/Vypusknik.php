<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 18.02.2018
 * Time: 18:55
 */

class Vypusknik extends StdViev
{

    public function __construct()
    {
        parent::__construct(new ItemNew(),VypusknikConfig::getConfig());
        $this->path = '/admin/vypuskniki.php';//FIXME
    }
    public function save($post)
    {
        $this->item->my_item['rank']= $this->makeRank();
        if($this->item->my_item['rank']){
            if(parent::save([])){
                $this->qs_array['id']=$this->item->my_item['id'];
                unset($this->qs_array['parent']);
                return true;
            }
        }else{
            $this->errors[]="Ошибка присвоения rank";
        }
        return false;
    }
    public function render(){
        $this->item->makeVievFields();
        //формируем имя путь картинки
        if(isset($this->item->my_item['vimg']) && $this->item->my_item['vimg']!="") {
            $this->item->my_item['vimg']=$this->makeImgPathAndName($this->item->viev_fields['vimg'],$this->item->my_item );
        }
        $res='';
        $paths=[];
        $paths[]='<a href="'.$this->path.'" >Выпускники</a>';
        $action = 'Новый выпускник';
        if($this->item->my_item['id'] && $this->item->my_item['name']){
            $action= $this->item->my_item['name'];
            $paths[] = '<a href="?id='.$this->item->my_item['id'].'">'.$this->item->my_item['name'].'</a>';
        }
        if(count($paths)>0){
            $res .= '<div class="path">';
            $res .= implode(' - ', $paths);
            $res .= '</div>';
        }
        $res .= '<h1>'.$action.'</h1>';

        $form= new HtmlFormNew($this->item);
        $res .= $form->render($this->action_field_value);
        return $res;
    }
    private function makeImgPathAndName(array $field,array $item){
        $pathname='';
        if(isset($field['previev'])){
            $previev=current($field['previev']);
            $nameplus=$previev['nameplus'];
        }else{//превью нет показываем картинку
            $nameplus='';
        }
        //путь к картинке
        //$this->item->my_item[$field['name']]
        $pathname .= '/'.$field['picture_path'].
            $field['name'] . $item['id'] .$nameplus . '.' . $item[$field['name']];
        return $pathname;

    }

}