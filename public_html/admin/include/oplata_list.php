<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 07.12.2015
 * Time: 23:05
 */

$currt=new DateTime();

$mode = 'active';
if(isset($_GET['mode'])){
    $mode= $_GET['mode'];
}
$where='';
switch ($mode) {
    case 'active':
        $where = " WHERE look='no' OR  datefinal>='".$currt->format('Y-m-d')."'";// Полные тексты//datefinal timefinal
        break;
    case "prosro":
        $where = " WHERE look='yes' AND  datefinal<'".$currt->format('Y-m-d')."'";
        break;
    default:
        break;
}

?>
<div class="opfiltr">
    <a href="?mode=active" class="opfiltr__punkt<?=($mode=='active'?' opfiltr__punkt_active':'')?>">Активные</a>
    <a href="?mode=prosro" class="opfiltr__punkt<?=($mode=='prosro'?' opfiltr__punkt_active':'')?>">Просроченные</a>
    <a href="?mode=all" class="opfiltr__punkt<?=($mode=='all'?' opfiltr__punkt_active':'')?>">Все</a>
</div>


<div class="buttons">
    <div class="g"><a href="/admin/oplata.php?id=0">Добавить оплату...</a></div>
</div>
<?php



///%s LIMIT %d, %d,$items, DB_PREFIX,$items,$mode, $order, $startrow, MAXROWS
$sql = "SELECT * FROM ".DB_PREFIX."_oplata $where ORDER BY timeadd DESC";
//  print $query_limit;
$db = DB::getInstance();
try {
    $rowes = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
} catch (PDOException $e) {
    //$this->error="Ошибка". $e->getMessage();
	error_log($e->getMessage());
}

foreach ($rowes as $rowe){
    echo '<div class="opitem">';
    echo '<a class="opitem__link" href="/admin/oplata.php?id='.$rowe['id'].'">'.$rowe['name'].'</a>';
    echo '<div class="opitem__p">Сумма: '.$rowe['summa'].'</div>';
    if($rowe['look']=='no'){
        echo '<div class="opitem__p">бессрочно</div>';
    }else{
        echo '<div class="opitem__p">Действует до: '.$rowe['datefinal'].' '.$rowe['timefinal'].':59 включительно.</div>';
        //просрочено

        $final=new DateTime($rowe['datefinal'].' '.$rowe['timefinal'].':59:59.9');
//                        print_r($currt);
//                        print_r($final);
        if($currt>$final){
            echo '<div class="opitem__p opitem__p_alert">просрочено</div>';
        }else{
            $interval = $final->diff($currt);
//            var_dump($interval);
            //echo $interval->format('%R%a дней');
            echo '<div class="opitem__p opitem__p_good">Осталось: '.$interval->format('%a д. %h ч. %i м.').'</div>';
        }
    }
    echo '</div>';
}
?>
<div class="buttons">
<div class="g"><a href="/admin/oplata.php?id=0">Добавить оплату...</a></div>
</div>
