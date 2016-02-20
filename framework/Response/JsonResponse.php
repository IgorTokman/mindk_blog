<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 20.02.2016
 * Time: 17:13
 */

namespace Framework\Response;


class JsonResponse extends Response
{
    /**
     * Sets HTTP response body, the parameter $array is automatically converted to JSON, content-type is "application/json"
     * JsonResponse constructor.
     * @param array $array
     */
    public function __construct($array = array())
    {
        parent::__construct(json_encode($array), 200, "application/json");
    }
}