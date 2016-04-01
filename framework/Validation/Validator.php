<?php
/**
 * Created by PhpStorm.
 * User: igortokman
 * Date: 19.03.16
 * Time: 11:47
 */

namespace Framework\Validation;


use Framework\Model\ActiveRecord;

class Validator
{
    //Container that stores error messages
    public $errors = array();
    //Table record object
    public $tblObj;

    /**
     * Validator constructor.
     * @param ActiveRecord $tblObj
     */
    public function __construct(ActiveRecord $tblObj)
    {
        $this->tblObj = $tblObj;
    }

    /**
     * Verifies if the table record meets the requirement
     * @return bool
     */
    public function isValid(){
        $fields = $this->tblObj->getFields();

        //Checks the value according to certain rules
        if(!empty($rulArr = $this->tblObj->getRules()))
        {
            foreach($rulArr as $fieldName => $rules)
                foreach($rules as $rule)
                    if(!($rule->isValid($fields[$fieldName])))
                        $this->errors[$fieldName] = "Invalid " . $fieldName . " value for saving in the DB. " . $rule->getMessage();
        }

        return empty($this->errors);
    }

    /**
     * Gets the container of error messages
     * @return array
     */
    public function getErrors(){
        return $this->errors;
    }
}