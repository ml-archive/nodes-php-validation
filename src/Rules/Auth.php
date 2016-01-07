<?php
namespace Nodes\Validation\Rules;

/**
 * Class Auth
 *
 * @trait
 * @package Nodes\Validation\Rules
 */
trait Auth
{
    /**
     * Validate that attribute is a valid username
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access protected
     * @param  string $attribute
     * @param  string $value
     * @param  array  $parameters
     * @return boolean
     */
    protected function validateUsername($attribute, $value, $parameters)
    {
        // Use provided regex or use fallback
        $pattern = !empty($parameters[0]) ? $parameters[0] : '/^([a-zA-Z0-9._-])+$/i';

        return (bool) preg_match($pattern, $value);
    }
}