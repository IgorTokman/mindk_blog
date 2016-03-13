<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 12.03.2016
 * Time: 14:01
 */

namespace Framework\Security\Model;


interface UserInterface
{
    public static function findByEmail($email);
}