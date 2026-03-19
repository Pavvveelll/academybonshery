<?php 
$filename='toco.txt';
if (file_exists($filename)) {
    $co = file_get_contents($filename);   
    $co++;
    file_put_contents ( $filename ,$co);
}else{
    file_put_contents ( $filename ,'1' );
}

