<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 22.07.2017
 * Time: 14:46
 */

class Sitemap
{
    /**
     * @var DomDocument
     */
    public $xml;
    private $siteroot;
    private $db;

    //private $groppe;
    public $niks=[];//url для категорий

    public $errors=[];
    public $status;
    public $info;

    private $max_level=3;//глубина парсинга категорий товаров

    private $filepatch;//имя и путь sitemap

    private $time_start;//начало выполнения
    private $time_out = 30; //максимальное время выполнения
    private $completed=[];//категорие уже проверенные
    private $cacheComplit;//имя файла для проверенных категорий
    private $cacheKatList;//имя файла для списка готовых категорий
    private $ignore_groppe=false;//true - считать группу как обычную категорию TODO разобраться с группами
    /**
     * Sitemap constructor.
     */

    public function __construct()
    {
        $this->time_start = microtime(true);
        $this->db = DB::getInstance();
        $this->siteroot = SERVER_HOST;
    }

    //загружаем настройки
    public function init($config){//LocalConfig::$site_map
        if(isset($config['cacheComplit'])){
            $this->cacheComplit =  TMP_PATH.$config['cacheComplit'];
        }else{//по умолчанию
            $this->cacheComplit =  TMP_PATH.'mapcomplit';
        }
        if(isset($config['cacheKatList'])){
            $this->cacheKatList =  TMP_PATH.$config['cacheKatList'];
        }else {//по умолчанию
            $this->cacheKatList =  TMP_PATH.'maplist';
        }
        if(isset($config['filepatch'])){
            $this->filepatch = ROOT_PATH.$config['filepatch'];
        }else{//по умолчанию
            $this->filepatch = ROOT_PATH."sitemap.xml";
        }

        if(isset($config['ignore_groppe'])){
            $this->ignore_groppe = $config['ignore_groppe'];
        }
        if(isset($config['max_level'])){
            $this->max_level = $config['max_level'];
        }
        if(isset($config['time_out'])){
            $this->time_out = $config['time_out'];
        }
    }

    public function loadFile(){
        $res=[];
        if (file_exists($this->filepatch)) {
            $loadxml = simplexml_load_file($this->filepatch);
            if($loadxml===false){
                $res['errors'][]='Ошибка загрузки файла '.$this->filepatch;
            }else{
                $res['count']=$loadxml->count();
                $res['date']=date ("d-m-Y H:i:s", filemtime($this->filepatch));
            }
        } else {
            $res['errors'][]='Файл не существует.';
        }
        return $res;
    }

    public function getData(){
        if (file_exists($this->filepatch)) {
            return filemtime($this->filepatch);
        }
        return false;
    }

    public function make(){//TODO привести в порядок
        //сначала пытаемся сформировать список категорий
        //если это удастся, будем делать всё остальное
        //формируем категории
        $this->xml = new DomDocument("1.0","utf-8");
        $this->xml->formatOutput = true;
        $root = $this->xml->createElement('urlset');
        $root->setAttribute("xmlns","http://www.sitemaps.org/schemas/sitemap/0.9");
        $root = $this->xml->appendChild($root);
        //главная страница
        $root->appendChild($this->addUrl('/'));
        $itogo=1;

        if(isset(LocalConfig::$site_map['makekats'])){
            $res = $this->getKats();
            //добавляем к содержимому записанного файла niks из категорий
            foreach ($this->niks as $item){
                $root->appendChild($this->addUrl('/'.$item.'/'));
            }
            $this->info.=$this->getInfo();

            $this->info.= PHP_EOL.'Категорий: '.count($this->niks);
            $itogo+=count($this->niks);
            //получаем товары которые есть в наличии
            //TODO настроить кроме отсутствующих снятых с производства
            if(count($this->errors)==0) {
                $items = $this->getTovars();
                foreach ($items as $item) {
                    $root->appendChild($this->addUrl('/' . $item['nik'] . '/'));
                }
                $this->info .= PHP_EOL . 'Товаров: ' . count($items);
                $itogo += count($items);
            }
        }

        //страницы сайта учитывая служебные
        $items = $this->getPages();
        foreach ($items as $item){
            $root->appendChild($this->addUrl('/'.$item['nik'].'/'));
        }
        $this->info.= PHP_EOL.'Страниц: '.count($items);
        $itogo+=count($items);
        $this->info.= PHP_EOL.'Итого: '.$itogo.PHP_EOL;
        return true;//сформирован

    }

    public function getPropTLink(){
        //формируем список свойств для проверки ссылок в товарах
        return $this->getKats();
    }

    public function getInfo(){
        $res= PHP_EOL.'Вложенность категорий: '.$this->max_level;
        if ($this->ignore_groppe == true){
            $res.= PHP_EOL.'Группы категорий - игнорировать.';
        }else{
            $res.= PHP_EOL.'Группы категорий - учтены.';
        }
        $res.= PHP_EOL.'Товары: в наличии или под заказ.';
        return $res;
    }

    private function getKats(){
        //$plainkats=[];
        //$groppekats=[];
        $firstkats=[];
        //берем все уровни
        $sql= "SELECT p.ptype, pv.id, pv.pid, pv.nik  FROM ".DB_PREFIX."_property p, ".DB_PREFIX."_property_values pv 
                WHERE 
                EXISTS (
                    SELECT * FROM ".DB_PREFIX."_tovar_value tv, ".DB_PREFIX."_tovar t
                    WHERE tv.pvalue = pv.id AND tv.id_tovar=t.id AND ((t.look='yes' AND t.sklad='yes') OR t.skladkol>0) AND t.foto IS NOT NULL
                ) AND 
                pv.pid=p.id AND p.active = 'yes' AND (p.menu = 'yes' OR p.podbor = 'yes' ) 
                AND p.sitemap = 'yes' AND pv.nik<>'' AND pv.toindx = 'yes' ORDER BY p.rank, pv.rank";  //AND pv.mvalue<>''
        // или другой вариант то что в подборе, выделять отдельно, формировать другой список всего два уровня
        //TODO отбирать свойства по типу
        // print $sql;
        try {
            foreach ($this->db->query($sql, PDO::FETCH_ASSOC) as $row) {
                $firstkats[$row['pid']][]=$row;
            }
        } catch (PDOException $e) {
            $this->errors[] = "Ошибка выгрузки группы категорий ". $e->getMessage();
        	error_log($e->getMessage());
        	return false;
        }
        //$plainkats = array_values($plainkats);
        //print_r($plainkats);
/*
        $groppekatsT= [
            [
                'nik'=>'A',
                'id'=>'A'
            ],
            [
                'nik'=>'B',
                'id'=>'B'
            ],
        ];
        $firstkatsT=[
            [
                [
                    'nik'=>'1',
                    'id'=>'1'
                ],
                [
                    'nik'=>'2',
                    'id'=>'2'
                ],

            ],
            [
                [
                    'nik'=>'3',
                    'id'=>'3'
                ],
                [
                    'nik'=>'4',
                    'id'=>'4'
                ],
            ],
            [
                [
                    'nik'=>'5',
                    'id'=>'5'
                ],
                [
                    'nik'=>'6',
                    'id'=>'6'
                ],
            ],
            [
                [
                    'nik'=>'7',
                    'id'=>'7'
                ],
                [
                    'nik'=>'8',
                    'id'=>'8'
                ],
            ],
        ];

        $plainkats3 =[
            [
                [1],
                [2]
            ],
            [
                [3],
                [4]
            ],
            [
                [5],
                [6]
            ],
            [
                [7],
                [8]
            ],
            [
                [9],
                [0]
            ],

        ];
        $plainkats2=[
            [
                ['A']
            ],
            [
                ['B']
            ],
            [
                ['C']
            ],
            [
                ['D']
            ],
            [
                ['E']
            ],
        ];

        //print_r($this->umn($plainkats[0],$plainkats[1]));
//        $a=[[1],
//            [2]];
//        $b=[[3],
//            [4]];
//        print_r($a);
//        print_r($this->umn($a,$b));

*/

        //если уже начинали формировать категории, подгружаем данные пройденных категорий из временного файла
        $this->loadTmpFile();
        //ловим исключение при выходе по таймауту
        try {
            $this->makeKatsSimple($firstkats,[],[], 0);
//            if(count($groppekats)>0){
//                foreach ($groppekats as $groppe) {
//                    $this->groppe = $groppe;
//                    $this->makeKats($firstkats, 0);
//                }
//            }else{
//                // print_r($firstkats);
//                //$this->makeKats($firstkats, 0);
//                //$this->makeKatsSimple($firstkats,[],[], 0);
//                //$this->makeKatsPlain($plainkats,$plainkats);
//                $this->makeKatsSimple($firstkats,[],[], 0);
//            }
        } catch (Exception $e) {
            $this->status='timeout';
            return false;
        }

        $lines=[];
        if (file_exists($this->cacheKatList)) {
            $content = file_get_contents($this->cacheKatList);
            $lines=explode(PHP_EOL,$content);
            $lines = array_diff($lines, array(''));//удаляем пустые строки, почемуто в конце файла остаются FIXME
        }
        //добвляем из файла
        $this->niks = array_merge($lines,$this->niks);
        //уникализируем TODO гдето двоится, исправить
        $this->niks = array_unique($this->niks);
        //если дошли сюда, файл сформирован
        //удаляем кеш
        if (file_exists($this->cacheComplit)) {
            unlink($this->cacheComplit);
        }
        if (file_exists($this->cacheKatList)) {
            unlink($this->cacheKatList);
        }

//        print ' масс.ошибок:'.count($this->faileds).' ';
//        print 'запросошибок:'. ($this->chkfail).' ';
//        print ' найдено в ошибках:'.$this->infailed.' ';
//         print ' проверок в БД'.$this->prov.' ';
//        print ' отриц ответ БД:'.$this->er.' ';
//        print ' итого:'.count($this->niks).' ';
//           print_r($this->niks);
//        print_r($this->niks);
        //print( "<p>Время: ". number_format(($this->getmicrotime()- $this->time_start),4). " сек.</p>");
        //return $this->niks;
        return true;
    }

    private function saveAndExit(){
        //сохраняем список пройденных категорий в файл
        $fileHandler = fopen($this->cacheComplit, "w");
        fwrite($fileHandler, serialize($this->completed));
        fclose($fileHandler);

        //дописываем сгенерированные URL
        $fileHandler = fopen($this->cacheKatList, "a");
        foreach ($this->niks as $nik){
            fwrite($fileHandler, $nik.PHP_EOL);
        }
        fclose($fileHandler);
        //print ' Перерыв Время: '. number_format(( microtime(true)- $this->time_start),4). ' сек.' ;

        //exit;//прекращаем выполнение
    }

    private function loadTmpFile(){
        if (file_exists($this->cacheComplit)) {
            $content=file_get_contents($this->cacheComplit);
            if ($content !== false) {
                $this->completed = unserialize($content);
            }
        }
    }

    private function makeKatsSimple($ost, $path, $niks, $level){
        $level++;
        if(count($ost)>0 && $level<=$this->max_level) {
            $first = array_shift($ost);
            foreach ($first as $f) {
                $newpath = $path;
                $newpath[] = $f['id'];
                //ПРОВЕРКА в проверенных категориях
                $checked=false;
                $hs_path='-'.implode('-',$path).'-';
                $hs_newpath='-'.implode('-',$newpath).'-';
                if(isset($this->completed[$hs_path])){
                    if(in_array($hs_newpath,$this->completed[$hs_path])){
                        $checked=true;
                    }
                }
                if($checked==false){
                    //проверяем в базе данных
                    $itog = $this->checkDbSimple($newpath);
                    if ($itog) {
                        $newniks=$niks;
                        $newniks[]=$f['nik'];
                        //FIXME двоит на обрыве
                        $this->niks[] =  implode('/',$newniks);//.'/'.$level
                        //проверяем ветку дальше TODO проверить уровень здесь
                        $this->makeKatsSimple($ost, $newpath, $newniks, $level);
                    }
                    //помечаем как проверенные
                    if($level<$this->max_level){
                        //удаляем потомков этого узла
                        if(isset($this->completed[$hs_newpath])){
                            unset($this->completed[$hs_newpath]);
                        }
                        $this->completed[$hs_path][]=$hs_newpath;
                        //рвем по таймауту
                        //$mkk=microtime(true);
                        if(microtime(true)- $this->time_start > $this->time_out ) {
                            $this->saveAndExit();
                            throw new Exception('generated timeout');
                        }
                    }
                }
            }
            //обходим рекурсивно хвост
            if($this->ignore_groppe == true || (isset($first[0]) && $first[0]['ptype']!='groppe')){
                $this->makeKatsSimple($ost, $path, $niks, --$level);
            }

        }
    }


    /**
     * проверяем существование пересечения своиств и наличия к ним товаров
     * проверка свойств группами  - в некоторых случаях замедляет
     * использование подготовленных запросов выигрыша не дало
     * @param $ids array список pvalue для проверки
     * @return bool
     */
    private function checkDbSimple($ids){
        //$this->prov++;
        $where_arr=[];
        $join_SQL=" ".DB_PREFIX."_tovar_value tv0 ";
        for ($c = 1; $c < count($ids); $c++) {
            $join_SQL.="
                        LEFT JOIN ".DB_PREFIX."_tovar_value tv".$c." USING(id_tovar)";
            $where_arr[]= " tv".$c.".pvalue='".$ids[$c]."'";
        }
        $where_arr[]= " tv0.pvalue='".$ids[0]."'";
        $sql="
        SELECT tv0.pvalue FROM ".$join_SQL. "   
        LEFT JOIN ".DB_PREFIX."_tovar t ON t.id=tv0.id_tovar 
        WHERE ".implode(" AND ",$where_arr)." AND (t.skladkol>0 OR t.look='yes') AND t.foto IS NOT NULL LIMIT 1
        ";
        // print $sql;
        try {
            $itogs = $this->db->query($sql, PDO::FETCH_COLUMN,0)->fetchAll();
            if(count($itogs)>0){
                return true;
            }
        } catch (PDOException $e) {
            $this->errors[] = "Ошибка выгрузки товаров ". $e->getMessage();
            error_log($e->getMessage());
        }
        return false;
    }




    /**
     * Список активных страниц
     * только те которые переопределены
     * служебные не переопределенные страницы не выводятся
     * @return array
     */
    private function getPages(){
        $pages=[];
        $sql= "SELECT id, parent, nik, vievps FROM ".DB_PREFIX."_page 
            WHERE look='yes' AND nik <>'glavnaya' ORDER BY rank";
        try {
            foreach ($this->db->query($sql, PDO::FETCH_ASSOC) as $row) {
                $pages[$row['id']]=$row;
            }
        } catch (PDOException $e) {
            $this->errors[] = "Ошибка выгрузки страниц ". $e->getMessage();
            error_log($e->getMessage());
        }
        do{
            $change=false;
            foreach ($pages as &$page){
                if($page['parent']!=0){
                    $page['nik'] = $pages[$page['parent']]['nik'].'/'.$page['nik'];
                    $page['parent']=$pages[$page['parent']]['parent'];//меняем родителя
                    $change=true;
                }
            }
            unset($page);
        }while($change==true);

        //чистим от неиндексируемых //TODO чтото поумнее
        foreach ($pages as $kp=>$page){
            if($page['vievps']!='yes'){
                unset($pages[$kp]);
            }
        }

        return $pages;
    }

    private function getTovars(){
        $tovars=[];
        $sql= "SELECT nik FROM ".DB_PREFIX."_tovar WHERE skladkol>0  OR look='yes' ";// snyato='no' OR
        $db = DB::getInstance();
        try {
            $tovars=$db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
        } catch (PDOException $e) {
            $this->errors[] = "Ошибка выгрузки товаров ". $e->getMessage();
            error_log($e->getMessage());
        }
        return $tovars;
    }

    private function addUrl($url){
        $el = $this->xml->createElement("url");
        $loc = $this->xml->createElement("loc", $this->siteroot.$url);
        $el->appendChild($loc);
        return $el;
    }

    public function save(){
        if(count($this->errors)==0){
            $this->xml->save($this->filepatch);
            return true;
            //return count($this->niks). ' OK Время: '. number_format(($this->getmicrotime()- $this->time_start),4). ' сек.' ;
        }
        return false;
        //return print_r($this->errors, true);
    }

    public function show(){
//        if(count($this->errors)==0){
//            $this->xml->save($this->filepatch);
//            //return count($this->niks). ' OK Время: '. number_format(($this->getmicrotime()- $this->time_start),4). ' сек.' ;
//        }else{
//            return print_r($this->errors, true);
//        }
    }

    //DRAFT
/*
    private function makeKatsPlain($bases,$res){

        while (count($bases)>4){
            array_shift($bases);
//            foreach ($res as $k1=>$re){
//                foreach ($re as $k2=>$r) {
//                     $pr= implode('_', $r);
//                    print $pr. '
//';
////                     if($pr=='2_4'){
////                         unset($res[$k1][$k2]);
////                     }else{
////
////                     }
//                }
//                print '
//';
//            }
            $newres=[];
            $counter=1;
            foreach ($bases as $base){
                $new=[];
                for ($i = 0; $i < $counter; $i++) {
                    $new =array_merge($new,$this->umn($res[$i],$base));
                }
                $counter++;
                $newres[]=$new;
            }
            $res=$newres;
        }
    }

     private function umn($as, $bs){
        $res=[];
        foreach ($as as $a){
            foreach ($bs as $b){
                $nw=array_merge($a,$b);
                //ПРОВЕРКА
                //$this->prov++;
                $itog=true;
                $infailed=false;
                if(count($nw)>2){
                    if($this->checkInFaileds($nw)){
                        //найдено в ошибочных
                            $itog=false;
                            $this->infailed++;
                            $infailed=true;
                    }
                }

                if($itog){
                    $itog = $this->checkDbSimple($nw);
                }

                if($itog){
                    $res[] =$nw;
                    print  implode('_', $nw).'
';
                }elseif($infailed==false){
                    if(count($nw)<3){
                         $this->faileds[$nw[0]][$nw[1]]=true;
                    }
                }
            }
        }
        return $res;
    }

    private function checkInFaileds($nw){
        $this->chkfail++;
        $failarr=[];
        foreach ($nw as $n){
            if(count($failarr)>0){
                //один найден, ищем другой
                if(isset($failarr[$n])){
                    return true;
                }
            }else{
                //сначала надо найти хотя бы один
                if(isset( $this->faileds[$n])){
                    $failarr=$this->faileds[$n];
                }
            }
        }
        return false;
    }

    private function checkDbFull($pid, $ids){
        $where_arr=[];
        $count_join=2;
        $join_SQL=" ".DB_PREFIX."_tovar_value tv1 ";
        if(isset($this->groppe['id'])){
            $ids[]=$this->groppe['id'];
        }
        foreach ($ids as $id){
            if($count_join>1){
                $join_SQL.="
                            LEFT JOIN ".DB_PREFIX."_tovar_value tv".$count_join." USING(id_tovar)";
            }
            $where_arr[]= " tv".$count_join++.".pvalue='".$id."'";
        }
        $where_arr[]= " tv1.pid='".$pid."'";
        $sql="SELECT ss.pvalue FROM (
        SELECT tv1.id_tovar, tv1.pvalue FROM ".$join_SQL. " WHERE ".implode(" AND ",$where_arr)."
        ) ss
        LEFT JOIN ".DB_PREFIX."_tovar t ON t.id=ss.id_tovar
        WHERE (t.skladkol>0 OR t.look='yes') AND t.foto IS NOT NULL  GROUP BY ss.pvalue
        ";
//        print $sql;

        $itogs=[];
        try {
            $itogs = $this->db->query($sql, PDO::FETCH_COLUMN,0)->fetchAll();
        } catch (PDOException $e) {
            $this->errors[] = "Ошибка выгрузки товаров ". $e->getMessage();
            error_log($e->getMessage());
        }
        return $itogs;
    }


*/
}