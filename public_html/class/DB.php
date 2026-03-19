<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 08.04.2015
 * Time: 12:46
 *
 * заботится о том, чтобы небыло нескольких соединений с базой
 * при необходимости отдает объект PDO для работы
 *
 * принцип заимствован
 * * PHP5 PDO Singleton Class v.1
 * @author Evren Yalcin
 * @link http://www.evrenyalcin.com
 */
final class DB
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            /*            $db_info = array(
                            "db_host" => "localhost",
                            "db_port" => "3306",
                            "db_user" => "root",
                            "db_pass" => "",
                            "db_name" => "DB",
                            "db_charset" => "UTF-8"
                        );*/
            try {
                self::$instance = new PDO('mysql:dbname=' .
                    BD_NAME . ';host='.BD_SERVER,
                    BD_USER,
                    BD_PWD);
                //устанавливаем режим исключений
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance-> exec("set names utf8");
            } catch (PDOException $e) {
                error_log(__FILE__." str:".__LINE__." ".$e->getMessage());
//                echo $e->getMessage();
            }
        }
        return self::$instance;
    }

    private function __clone()
    {
    }

    public static function quote_array(array $arr){
        $db=self::getInstance();
        foreach($arr as &$str){
            $str=$db->quote($str);
        }
        unset($str);
        return $arr;
    }
}
