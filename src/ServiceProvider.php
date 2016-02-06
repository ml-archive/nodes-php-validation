<?php
namespace Nodes\Validation;

use Illuminate\Validation\Validator as IlluminateValidator;
use Nodes\AbstractServiceProvider;
use Nodes\Validation\Exceptions\InvalidValidatorException;

/**
 * Class ServiceProvider
 *
 * @package Nodes\Validation
 */
class ServiceProvider extends AbstractServiceProvider
{
    /**
     * Package name
     *
     * @var string
     */
    protected $package = 'validation';

    /**
     * Array of configs to copy
     *
     * @var array
     */
    protected $configs = [
        'config/validation.php' => 'config/nodes/validation.php'
    ];

    /**
     * Boot the service provider
     * Used to resolve our custom validator
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return void
     */
    public function boot()
    {
        // Override Laravel's validator with the one from our config file
        $this->app['validator']->resolver(function($translator, $data, $rules, $messages) {
            // Retrieve namespace of validator
            $customValidator = config('nodes.validation.validator');

            // Instantiate validator
            $validator = new $customValidator($translator, $data, $rules, $messages);

            // Validate validator parent
            if (!$validator instanceof IlluminateValidator) {
                throw new InvalidValidatorException(sprintf('Validator [%s] is not extending Laravel\'s validator [%s]', get_class($validator), 'Illuminate\Validation\Validator'));
            }

            return $validator;
        });
    }
}