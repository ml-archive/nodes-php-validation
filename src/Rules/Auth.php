<?php

namespace Nodes\Validation\Rules;

/**
 * Class Auth.
 *
 * @trait
 */
trait Auth
{
    /**
     * Validate that attribute is a valid username.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $attribute
     * @param  string $value
     * @param  array  $parameters
     * @return bool
     */
    protected function validateUsername($attribute, $value, $parameters)
    {
        // Use provided regex or use fallback
        $pattern = ! empty($parameters[0]) ? $parameters[0] : '/^([a-zA-Z0-9._-])+$/i';

        return (bool) preg_match($pattern, $value);
    }
}
