<?php
namespace Nodes\Validation\Rules;

/**
 * Class Color
 *
 * @trait
 * @package Nodes\Validation\Rules
 */
trait Color
{
    /**
     * Validate that attribute is a valid hex color
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access protected
     * @param  string $attribute
     * @param  string $value
     * @param  array  $paramters
     * @return boolean
     */
    protected function validateHexcolor($attribute, $value, $paramters)
    {
        return (bool) preg_match('/^#?[a-fA-F0-9]{3,6}$/', $value);
    }
}