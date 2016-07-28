<?php

namespace Nodes\Validation\Rules;

/**
 * Class Color.
 *
 * @trait
 */
trait Color
{
    /**
     * Validate that attribute is a valid hex color.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string $attribute
     * @param  string $value
     * @param  array  $paramters
     * @return bool
     */
    protected function validateHexcolor($attribute, $value, $paramters)
    {
        return (bool) preg_match('/^#?[a-fA-F0-9]{3,6}$/', $value);
    }
}
