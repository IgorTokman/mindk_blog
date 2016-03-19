<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 12.03.2016
 * Time: 12:30
 */

namespace Framework\Request;


class Request
{
    /**
     * @return HTTP request method
     */
    private function getMethod(){
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Checks if the current HTTP method is POST
     * @return bool
     */
    public function isPost(){
        return $this->getMethod() === "POST";
    }

    /**
     * Checks if the current HTTP method is GET
     * @return bool
     */
    public function isGet(){
        return $this->getMethod() === "GET";
    }

    /**
     * Checks if the type of request is Ajax
     * @return bool
     */
    public function isAjax(){
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
            return true;
        else
            return false;
    }

    /**
     * Fetches required header
     * @param $headerName
     * @return appropriate HTTP request header or null if it does not exist
     */
    public function getHeader($headerName = null){

        $headers = $this->getAllHeaders();

        if(!is_null($headerName))
           return array_key_exists($headerName, $headers)? $headers[$headerName] : null;
    }

    /**
     * Fetches all HTTP request headers from the current request
     * @return An associative array of all the HTTP headers in the current request, or FALSE on failure
     */
    public function getAllHeaders(){
        return apache_request_headers();
    }

    /**
     * Fetches and checks the variable from POST array
     * @param $varName
     * @param string $filterName
     * @return Variable or false if it is incorrect
     */
    public function post($varName, $filterName = 'string'){
        $var = filter_input(INPUT_POST, $varName, $this->getFilter($filterName));
        return ($varName === 'password')? md5($var) : $var;
    }

    /**
     * Fetches and checks the variable from GET array
     * @param $varName
     * @param string $filterName
     * @return Variable or false if it is incorrect
     */
    public function get($varName, $filterName = 'string'){
        return filter_input(INPUT_GET, $varName, $this->getFilter($filterName));
    }

    /**
     * Checks the filterName and gets some filter
     * @param $filterName
     * @return Some type of filter Validate | Sanitize
     */
    public function getFilter($filterName){
        switch (strtoupper($filterName)){
            case 'STRING':
                return FILTER_SANITIZE_STRING;
            case 'BOOLEAN':
                return FILTER_VALIDATE_BOOLEAN;
            case 'EMAIL':
                return FILTER_VALIDATE_EMAIL;
            case 'URL':
                return FILTER_VALIDATE_URL;
            case 'IP':
                return FILTER_VALIDATE_IP;
            case 'INT':
                return FILTER_VALIDATE_INT;
            case 'FLOAT':
                return FILTER_VALIDATE_FLOAT;
        }
    }
}