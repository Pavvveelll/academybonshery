<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 19.02.2018
 * Time: 22:22
 */

namespace ShortCode;


class VypusknikiBlock implements iShortCode
{
    private $maxrows = 10;
    private $pagenum = 0;
    //private $page;


    //private $patch_url;
    public function setPagenum($pagenum){
        $this->pagenum=$pagenum;
    }



    public function render($param){

        if (isset(Detector::$page)){
            Detector::$page->ajax= true;
        }



        //$pagenum = 0;
        $db = \DB::getInstance();
        $res="";
        $mode= " look='yes' ";
        $order=" ORDER BY rank DESC";
        $startrow= intval($this->pagenum) * $this->maxrows;
        $limit =' LIMIT '.$startrow.', '.$this->maxrows;
        $query="SELECT COUNT(*) FROM " .DB_PREFIX. "_vypusknik WHERE " . $mode;
        $query_limit =  "SELECT * FROM " .DB_PREFIX. "_vypusknik WHERE ". $mode. $order. $limit;
        // print $query_limit;

        $l=[];
        $r=[];
        try {
            //throw new PDOException('Проверка ошибки');
            $totalrows = $db->query($query)->fetchColumn();
            $totalpages = ceil($totalrows/$this->maxrows)-1;
            //$subpage_list.=$totalpages;
            //$subpage_list.=$query_limit;
            $counter=0;
            foreach ($db->query($query_limit) as $row) {
                $counter++;
                $block='';
                //$cur_patch_url=$this->patch_url.$row['nik']."/";
                $block.='<div class="vypusknik';
                $block.='">';
                if($row['vimg']!=NULL){
                    $block.= '<img class="vypusknik_img" src="/picture/vypysk/vimg'.$row['id'].'.'.$row['vimg'].'" alt="'.$row['name'].'" />';
                }
                $block.='<div class="vypusknik_in">';
                $block.= '<p class="vypusknik__zag">'.$row['name'].'</p>';
                if ($row['sity']!=''){
                    $block.= '<p class="vypusknik__sity">'.$row['sity'].'</p>';
                }
                $block.= $row['vtext'];
                $block.='</div><br class="clearfloat">';
                $block.='</div>';
                $res.=$block;
            }

            $pagenew = $this->pagenum+1;
            if($pagenew <= $totalpages){
                $res.='<div class="spadd">';
                $res.= '<button class="spbut" type="button"
                data-pagenum="'.$pagenew.'" data-mode="vypusknik" 
                onclick="vievsubpagelist(this);"
                title="Показать еще...">Показать ещё...</button>';
                $res.='<div></div>';
                $res.='</div>';
            }


        } catch (\PDOException $e) {
            //$this->error="Ошибка". $e->getMessage();
            error_log($e->getMessage());
        }

        return $res;
    }

}