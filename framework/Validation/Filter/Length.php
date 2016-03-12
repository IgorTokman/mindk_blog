<?php
/**
 * Created by PhpStorm.
 * User: IgorTokman
 * Date: 12.03.2016
 * Time: 14:21
 */

namespace Framework\Validation\Filter;


class Length implements ValidationFilterInterface
{
    private $min_length;
    private $max_length;

    /**
     * Length constructor.
     * Initialization for established limits
     * @param $min
     * @param $max
     */
    public function __construct($min, $max)
    {
        $this->min_length = $min;
        $this->max_length = $max;
    }

    /**
     * Checks if the value meets the requirement
     * @param $value
     * @return bool
     */
    public function isValid($value)
    {
        return ((strlen($value) >= $this->min_length) && (strlen($value) <= $this->max_length));
    }

    /**
     * Gets the error message by checking the value
     * @return string
     */
    public function getMessage()
    {
        return "Error length. The value must have a length in the range (" . $this->min_length . ".." . $this->max_length . ")";
    }
}