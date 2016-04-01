<?php
/**
 * Created by PhpStorm.
 * User: igortokman
 * Date: 30.03.16
 * Time: 15:41
 */

namespace Framework\Logger;


class Logger
{
    /**
     * Provides logging services for applications
     * @param $message
     * @param null $additional
     *
     * Subscribes to the corresponding event
     * @Event applicationInit
     * @Event parseRoute
     * @Event dispatchAction
     * @Event controllerRender
     * @Event renderAction
     * @Event controllerGenerateRoute
     * @Event controllerRedirect
     * @Event sendAction
     */
    public function log($message, $additional = null){

        $data  = 'Event "' . $message . '"';
        $data .= is_null($additional)? '' : "\t" . $additional;
        $data .= "\n";

        file_put_contents(__DIR__ . '/../../app/logs/' . date( "Y_m_d" ) . '.log', $data, FILE_APPEND);
    }

}