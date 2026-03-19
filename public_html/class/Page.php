<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 14.04.2018
 * Time: 17:28
 */

class Page
{

    public $title='';
    public $keywords='';
    public $description='';
    public $canonical='';

    public $tudasuda='';
    public $viev_pform=false;

    public $ajax=false;
    public $script='';
    public $item_viev;
    private $adminviev=false;//показывать ли?
    public $patch_url;

    public $viev='page';

    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->item_viev= new catalog();
    }


    public function routing($defnik='glavnaya'){
        if(!isset($_GET['prs']) ||sizeof($_GET['prs'])==0) {//если без аргументов то это главная страница
            $last_piece = $defnik;
            $this->viev = $defnik;
        }else{
            $pieces = explode("/", $_GET['prs']);
            $last_piece=end($pieces);
            if((isset($last_piece))&&($last_piece=="")){
                array_pop($pieces);
            }
            $last_piece=end($pieces);
            if($last_piece=="glavnaya"){//перегружаем на главную
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: ".SERVER_HOST.'/');
                exit;
            }
        }
        $modesql="look";
        if(isset($_COOKIE["siteadmin"])) {
            $modesql="";
            $this->adminviev=true;
        }

        $this->item_viev->table=DB_PREFIX."_page";
        if($this->item_viev->get_item_seo($last_piece,$modesql)==false){
            return false;
        }

        $this->patch_url="/";
        //формируем ссылку
        if($last_piece!="glavnaya"){
            $patch_array=$this->item_viev->get_path_seo_array();
            //print_r($patch_array);
            foreach($patch_array as $pat){
                $this->patch_url.=sprintf("%s/",$pat['nik']);
            }
            $this->patch_url.=$this->item_viev->my_item['nik']."/";
        }

        //проверяем соответствие
        if(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) != $this->patch_url){
            $querystr=parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY);
            //перегружаем на правильный адрес
            $newurl=$this->patch_url.($querystr!='' ? '?'.$querystr :'');
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".SERVER_HOST.$newurl);
            exit;
        }

        //приход по ссылке из рассылки
        //rst - тип рассылки, добавляется к ссылкам в письмах /nabor/?rst=kurs
        //kurs - курсы
        //master - мастер-классы
        //biznes
        //pomosh
        if(isset($_GET['rst'])){
            setcookie($_GET['rst'], 'yes', time() + 604800*20,"/", COOKIES);//20 недель//ставим куку ID подписчика
        }

        /* ВСПЛЫВАЮЩАЯ ФОРМА*/
        if(!isset($_GET['rst'])){//если приход не из рассылки
            //скрываем на служебных страницах
            if(!in_array($this->item_viev->my_item['nik'],LocalConfig::$servise_pages)){
                if (!isset($_COOKIE['master']) && !isset($_COOKIE['kurs'])) {
                    if(!isset($_SESSION['pform']) || $_SESSION['pform']=='1') {
                        if(isset($_SESSION['pform']) && $_SESSION['pform']=='1') {
                            $_SESSION['pform'] = '2';//больше не показываем
                            $this->viev_pform = true;
                            $this->script .= 'var timepform=5000; ';
                        }else{
                            $_SESSION['pform'] = '1';
                        }
                    }
                }
            }
        }

        return true;
    }

    public function makePage(){
        $this->title=htmlspecialchars($this->item_viev->my_item['title'],ENT_QUOTES|ENT_XHTML,'UTF-8');
        if($this->item_viev->my_item['keywords']!=''){
            $this->keywords = '<meta name="keywords" content="'.$this->item_viev->my_item['keywords'].'" />';
        }
        if($this->item_viev->my_item['anons']!=''){
            $this->description='<meta name="description" content="'.
                htmlspecialchars($this->item_viev->my_item['anons'],ENT_QUOTES|ENT_XHTML,'UTF-8').'" />';
        }
        $this->canonical='<link rel="canonical" href="'.SERVER_HOST.$this->patch_url.'"/>';

        //ТУДА СЮДА
        if($this->item_viev->my_item['parent']!=0) {//только для третьего уровня и дальше
            $parent_item = new catalog();
            $parent_item->table=$this->item_viev->table;
            $parent_item->load($this->item_viev->my_item['parent'],  $this->item_viev->table);
            $pos_listalka = strpos($parent_item->my_item['article'], '%SUBPAGE_LIST%');//если в Паренте демонстрируется туда сюда стрелки.
            if ($pos_listalka !== false) {//список есть
                //формируем путь к родительской категории
                $ppatch_array=$parent_item->get_path_seo_array();
                $ppatch_url="/";
                foreach($ppatch_array as $pat){
                    $ppatch_url.=sprintf("%s/",$pat['nik']);
                }
                $ppatch_url.=$parent_item->my_item['nik']."/";

                //ССЫЛЬ на раздел
                //print_r($parent_item);
                $this->tudasuda=sprintf('<div class="to_list"><a href="%s">%s</a></div>',$ppatch_url,$parent_item->my_item['title']);

                //вставляем туда-сюда
                $this->tudasuda.= '<div class="tudasuda" id="tudasuda"></div>';
                //TODO - если не нужно скрывать ссылки а нужна перелинковка по кольцу, выводить через PHP

                $this->script .= 'var listparent='.$this->item_viev->my_item['parent'].'; ';
                $this->script.= 'var listid='.$this->item_viev->my_item['id'].'; ';
                $this->ajax = true;
            }
        }

    }

    public function render(){
        $res='';
        if($this->viev=='glavnaya'){
            $res.=$this->renderSplash();
        }
        $res.=$this->renderArticle();
        return $res;
    }

    private function renderSplash(){
        $res='';
        $res.='<div class="splash">';
        //проверяем существование/data/splash.jpg
        $sfile=ROOT_PATH."data/splash.jpg";
        $ftime= @filemtime($sfile);
        $src="/picture/splash_def.jpg";
        if($ftime){
            $src="/data/splash.jpg?v=".$ftime;
        }
        //$res.=  print_r();
        $res.='<a href="/nabor/"><img src="'.$src.'" alt="Основной курс груминга" width="710" height="287" /></a>';
        $res.='</div>';
        return $res;
    }

    private function renderArticle(){
        $res='<div class="article">';
        ShortCode\Detector::$page = $this;
        $res.=ShortCode\Detector::replace($this->item_viev->my_item['article']);
        //$res.=$this->item_viev->my_item['article'];
        $res.= '</div>';

        return $res;
    }

    public function renderAdminBlock(){
        $res='';
        if($this->adminviev) {// только для админов
            if(!isset($_GET['hideadmin'])){//FIXME на сессию
                $res.= '<div class="admin__cont">';

                if($this->item_viev->my_item['look']=='yes'){
                    $res.= '<div class="admin__blok">';
                    $res.= '<span>Страница доступна</span>';
                }else{
                    $res.= '<div class="admin__blok admin__blok_hide">';
                    $res.= '<span>Страница скрыта</span>';
                }
                $res.= '<a class="admin__link" href="/admin/?id='.$this->item_viev->my_item['id'].'" target="_blank">редактировать...</a>
                </div>';
                $res.= '</div>';
            }
        }
        return $res;
    }

    public function podpBaner(){
        $res= <<<PB
<div id="grayoverlay" style="display:none">
<div class="pform__overlay"> </div>
<div id="pform" class="pform" style="width: 300px; display:none">
    <div class="pform__close" onclick="close_pform()" title="Закрыть">X</div>
    <div id="quest" class="pform__block">
        <div class="pform__txt">Ответьте на один вопрос</div>
        <div class="pform__head">Вы работаете грумером?</div>
        <div class="pform__buttcont">
        <div class="pform__butt pform__butt_yes" onclick="grumer()">Да</div>

        <div class="pform__butt pform__butt_no" onclick="nogrumer()">пока нет</div>
        </div>
    </div>
    <div id="nogrumer" class="pform__block" style="display: none">
        <div class="pform__text">Для Вас рекомендовано:</div>
        <div class="pform__text">Почтовая рассылка</div>
        <div class="pform__head">&laquo;7 шагов в груминг&raquo;</div>
        <a href="/subscribe/" class="pform__butt pform__butt_yes">узнать подробнее...</a>
        <div class="pform__butt pform__butt_gray" onclick="close_pform()">Потом</div>
    </div>
    <div id="grumer" class="pform__block" style="display: none">
        <div class="pform__text">Для Вас рекомендовано:</div>
        <div class="pform__text">Почтовая рассылка</div>
        <div class="pform__head">&laquo;Вершины груминга&raquo;</div>
        <a  href="/subscribe_masterklass/" class="pform__butt pform__butt_yes">узнать подробнее...</a>
        <div class="pform__butt pform__butt_gray" onclick="close_pform()">Потом</div>
    </div>
</div>
</div>
PB;

     return $res;
    }

}