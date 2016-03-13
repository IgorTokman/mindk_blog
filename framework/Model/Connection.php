<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 07.03.2016
 * Time: 22:57
 */

namespace Framework\Model;


use PDO;

class Connection
{
    private static $db = null;

    private function __construct() {

    }

    private function __clone() {

    }

    /**
     * Creates new db connection if it does not exist
     * @param $pdo config array
     * @return PDO object
     */
    public static function get($pdo) {
        if (is_null(self::$db)) {
            try {
                self::$db = new PDO($pdo['dns'], $pdo['user'], $pdo['password']);
            }catch (\PDOException $e){
                echo $e->getMessage();
                //die("Connection error " . $e->getMessage());
            }
        }
        return self::$db;
    }

    //Connection closing
    public function close(){
        self::$db = null;
    }
}