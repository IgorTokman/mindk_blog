<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 20.02.2016
 * Time: 15:37
 */

namespace Framework\Response;


use Framework\DI\Service;

class Response
{
    //array of headers for the response
    protected $headers = array();
    //HTTP response body
    protected $content = '';
    //content-type mime
    protected $content_type;
    //response code for the raw header
    protected $code;

    //HTTP response codes
    private static $statusCodes = array(
        //Success
        200 => 'OK',
        //Redirection
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        //Client Error
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        //Server Error
        500 => 'Internal Server Error'
    );

    /**
     * Response constructor.
     * @param string $content HTTP response body
     * @param int $code Response code for the raw header, default 200
     * @param string $content_type Content-type mime, default 'text/html'
     */
    public function __construct($content = '', $code = 200, $content_type = 'text/html')
    {
        $this->content = $content;
        $this->content_type = $content_type;
        $this->code = $code;
    }

    /**
     * Sets the response content-type mime
     * @param $contentType
     */
    public function setContentType($contentType){
        $this->content_type = $contentType;
    }

    //Returns the content-type mime
    public function getContentType(){
        return $this->content_type;
    }

    /**
     * Sets the headers for the response
     * @param $name
     * @param $value
     */
    public function setHeaders($name, $value){
        $this->headers[$name] = $value;
    }

    /**
     * Returns the headers
     * @return array of headers set by the user
     */
    public function getHeaders(){
        return $this->headers;
    }

    /**
     * Sets HTTP response body
     * @param $content
     */
    public function setContent($content){
        $this->content = $content;
    }

    //Gets the HTTP response body
    public function getContent(){
        return $this->content;
    }
    /**
     * Sets response code for the raw header
     * @param $code
     */
    public function setResponseCode($code){
        $this->code = $code;
    }

    //Returns the HTTP response code
    public function getResponseCode(){
        return $this->code;
    }

    //Prints out HTTP response to the client
    public function send(){
        $this->sendHeaders();
        $this->sendBody();
        //Launches the appropriate event
        Service::get('eventManager')->trigger('sendAction', "Prints out HTTP response to the client");
    }

    //Sends headers to the client
    public function sendHeaders(){
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $this->code . ' ' . self::$statusCodes[$this->code]);
        header('Content-Type:' . $this->content_type);

        foreach($this->headers as $key => $value)
            header(sprintf("%s: %s", $key, $value));
    }

    //Sends HTTP response body
    public function sendBody(){
        echo $this->content;
    }

}