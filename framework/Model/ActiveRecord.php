<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 07.03.2016
 * Time: 21:37
 */

namespace Framework\Model;


use Framework\DI\Service;
use PDO;

abstract class ActiveRecord
{
    //Default primary key
    public $id;

    /**
     * @return name of table
     */
    public abstract static function getTable();

    /**
     * Finds records by mode, where mode can be 'all' or some record id
     * @param $mode
     * @return array of table objects or one table object
     */
    public static function find($mode = 'all'){
        if($mode === "all")
            return self::findAll();
        elseif(is_numeric($mode))
            return self::findByParams(array("id" => $mode))[0];
    }

    /**
     * Returns records by some parameters
     * @param array $params
     * @return array of table objects
     */
    protected static function findByParams(array $params){
        $pdo = Service::get('dbConnection');
        $result = array();

        $query = "SELECT * FROM " . static::getTable() . " WHERE ";

        foreach(array_keys($params) as $name)
            $query .= "$name = ? AND ";

        $query = substr($query, 0, -5) . ";";

        $stmt = $pdo->prepare($query);
        $stmt->execute(array_values($params));

        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            $result[] = self::std2class($row);

        return $result;
    }

    /**
     * Returns all records from DB
     * @return array of Post objects
     */
    protected static function findAll(){
        $pdo = Service::get('dbConnection');
        $result = array();

        $query = "SELECT * FROM " . static::getTable() . ";";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            $result[] = self::std2class($row);

        return $result;
    }

    /**
     * @return array of properties
     */
    protected function getFields(){
        return get_object_vars($this);
    }

    /**
     * @return array of record id
     */
    public static function getIds(){
        $pdo = Service::get('dbConnection');
        $tblName = static::getTable();
        //Gets all ids from table
        $query = "SELECT id FROM " . $tblName . ";";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);;
    }

    /**
     * Insert or update db table
     */
    public function save(){
        $pdo = Service::get('dbConnection');
        $fields = $this->getFields();
        $tblName = static::getTable();

        //Checks if such id already exists in the table
        if(in_array($this->id, self::getIds()))
        {
            $query = "Update " . $tblName . " SET ";

            foreach (array_keys($fields) as $col_name)
                $query .= "$col_name = ?, ";

            $query = substr($query, 0, -2);
            $query .= " WHERE id = " . $this->id . ";";
            echo $query;
        }
        else {
            $query = "INSERT INTO " . $tblName;
            $query .= " (" . implode(", ", array_keys($fields));
            $query .= ") VALUES (";
            $query .= substr(str_repeat("?, ", count($fields)), 0, -2);
            $query .= ");";
        }
        $stmt = $pdo->prepare($query);
        $stmt->execute(array_values($fields));
    }

    /**
     * Deletes record from db table
     */
    public function delete(){
        $tblName = static::getTable();
        $pdo = Service::get('dbConnection');

        $query = "DELETE FROM " . $tblName;
        $query .= " WHERE id = " . $this->id . ";";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }

    /**
     * Transforms array of object properties into the appropriate object
     * @param $row array of object properties
     * @return table object
     */
    protected static function std2class($row){
        //Gets model name
        $tblName = static::class;
        $obj = new $tblName();

        foreach($row as $prop => $val)
           $obj->$prop = $val;

        return $obj;
    }
}