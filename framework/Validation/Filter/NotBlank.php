<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 12.03.2016
 * Time: 14:21
 */

namespace Framework\Validation\Filter;


class NotBlank implements ValidationFilterInterface
{
    /**
     * Checks if the value meets the requirement
     * @param $value
     * @param $value
     * @return bool
     */
    public function isValid($value)
    {
        return !empty($value);
    }

    /**
     * Gets the error message by checking the value
     * @return string
     */
    public function getMessage()
    {
        return "The value must be not a blank";
    }
}