<?php

namespace Nodes\Validation;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Validation\Validator as IlluminateValidator;
use Nodes\Validation\Exceptions\InvalidValidatorException;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Register publish groups
        $this->publishGroups();

        // Override Laravel's validator with the one from our config file
        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages) {
            // Retrieve namespace of validator
            $customValidator = config('nodes.validation.validator');

            // Instantiate validator
            $validator = new $customValidator($translator, $data, $rules, $messages);

            // Validate validator parent
            if (! $validator instanceof IlluminateValidator) {
                throw new InvalidValidatorException(sprintf('Validator [%s] is not extending Laravel\'s validator [%s]', get_class($validator), 'Illuminate\Validation\Validator'));
            }

            return $validator;
        });
    }

    /**
     * Register service provider.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    public function register()
    {
        /* ... */
    }

    /**
     * Register publish groups.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    protected function publishGroups()
    {
        // Config files
        $this->publishes([
            __DIR__.'/../config/validation.php' => config_path('nodes/validation.php'),
        ], 'config');
    }
}
