<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 16.12.2017
 * Time: 17:23
 */

class SubPages
{
    private $page_id;
    private $maxrows = 25;
    private $patch_url;

    /**
     * SubPages constructor.
     * @param $page_id
     * @param $patch_url
     */
    public function __construct($page_id,$patch_url)
    {
        $this->page_id = $page_id;
        $this->patch_url = $patch_url;
    }


    public function render($pagenum = 0){
        $db = DB::getInstance();
        $subpage_list="";
        $mode= " parent=".$db->quote($this->page_id)." AND look='yes' AND tlist<>'' ";
        $order=" ORDER BY rank DESC";
        $startrow= intval($pagenum) * $this->maxrows;
        $limit =' LIMIT '.$startrow.', '.$this->maxrows;
        $query="SELECT COUNT(*) FROM " .DB_PREFIX. "_page WHERE " . $mode;
        $query_limit =  "SELECT * FROM " .DB_PREFIX. "_page WHERE ". $mode. $order. $limit;
        // print $query_limit;


        try {
            //throw new PDOException('Проверка ошибки');
            $totalrows = $db->query($query)->fetchColumn();
            $totalpages = ceil($totalrows/$this->maxrows)-1;
            //$subpage_list.=$totalpages;
            //$subpage_list.=$query_limit;
            foreach ($db->query($query_limit) as $row) {
                $cur_patch_url=$this->patch_url.$row['nik']."/";
                $subpage_list.='<div class="subpageblock">';
                $cur_photo = '';
                if($row['foto']!=NULL){
                    $cur_photo = '<img src="/picture/foto'.$row['id'].'.'.$row['foto'].'" alt="'.$row['name'].'" width="130" height="90" />';
                }

                $subpage_list.= '<h3><a href="'.$cur_patch_url.'">'.$cur_photo;
                if($row['tlist']!=""){
                    $subpage_list.=$row['tlist'];
                }else{
                    $subpage_list.=$row['title'];
                }
                $subpage_list.='</a></h3><p>'.$row['anons'].'</p>';
                $subpage_list.='</div>';
            }

            $pagenew = $pagenum+1;
            if($pagenew <= $totalpages){
                $subpage_list.='<div class="spadd">';
                $subpage_list.= '<button class="spbut" type="button"
                data-page_id="'.$this->page_id.'" data-patch_url="'.$this->patch_url.'" data-pagenum="'.$pagenew.'" 
                onclick="vievsubpagelist(this);" 
                title="Показать еще...">Показать ещё...</button>';
                $subpage_list.='<div></div>';
                $subpage_list.='</div>';
            }



$subpage_list.='';
$subpage_list.='';

        } catch (PDOException $e) {
            //$this->error="Ошибка". $e->getMessage();
            error_log($e->getMessage());
        }

/*        $all = mysql_query($query_limit) or die(mysql_error());
        $allrows = mysql_num_rows($all);
        $total = mysql_query($query);
        $totalrows = mysql_num_rows($total);
        mysql_free_result($total);
        $totalpages = ceil($totalrows/MAXROWS)-1;
        if($allrows!=0){
            while(($row = mysql_fetch_assoc($all))!=false){
                //print($patch_url );
                $cur_patch_url=$patch_url.$row['nik']."/";
                $subpage_list.='<div class="subpageblock">';
                if($row['foto']!=NULL){
                }
                $subpage_list.= '<h3><a href="'.$cur_patch_url.'">'.
                    '<img src="/picture/foto'.$row['id'].'.'.$row['foto'].'" alt="'.$row['name'].'" width="130" height="90" />'.
                    (($row['tlist']!="")?($row['tlist']):($row['title'])).'</a></h3><p>'.$row['anons'].'</p>';
                $subpage_list.='</div>';
            }
            $item_viev->my_item['article']=str_replace("%SUBPAGE_LIST%",$subpage_list, $item_viev->my_item['article']);


        }*/
        return $subpage_list;
    }

}