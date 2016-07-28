<?php

namespace Nodes\Validation;

use Illuminate\Validation\Validator as IlluminateValidator;
use Nodes\Validation\Rules\Auth;
use Nodes\Validation\Rules\Color;
use Nodes\Validation\Rules\International;

/**
 * Class Validator.
 */
class Validator extends IlluminateValidator
{
    use Auth, Color, International;
}
