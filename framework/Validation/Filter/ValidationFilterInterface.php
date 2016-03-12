<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 12.03.2016
 * Time: 14:23
 */

namespace Framework\Validation\Filter;

/**
 * Basic interface for filter
 * Interface ValidationFilterInterface
 * @package Framework\Validation
 */
interface ValidationFilterInterface
{
    public function isValid($value);

    public function getMessage();
}