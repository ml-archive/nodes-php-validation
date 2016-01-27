<?php
namespace Nodes\Validation;

use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Validation\Factory as IlluminateValidator;
use Nodes\Validation\Exceptions\GroupRulesNotFoundException;
use Nodes\Validation\Exceptions\ValidationException;

/**
 * Class AbstractValidator
 *
 * @abstract
 * @package Nodes\Validation
 */
abstract class AbstractValidator
{
    /**
     * Validator Factory instance
     *
     * @var \Illuminate\Validation\Factory
     */
    protected $validatorFactory;

    /**
     * Resolved validator instance
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * Data to be validated
     *
     * @var array
     */
    protected $data = [];

    /**
     * Model to validate
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = null;

    /**
     * Group name
     *
     * @var string|null
     */
    protected $group = 'create';

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Validation custom messages
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Validation custom attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Validation custom error codes
     *
     * @var array
     */
    protected $errorCodes = [];

    /**
     * Validation variables
     *
     * @var array
     */
    protected $variables = [];

    /**
     * Errors bag
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors = null;

    /**
     * Constructor
     *
     * @access public
     * @param  \Illuminate\Validation\Factory $validatorFactory
     */
    public function __construct(IlluminateValidator $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * Validate data
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return boolean
     * @throws \Nodes\Validation\Exceptions\ValidationException
     */
    public function validate()
    {
        // Generate validator instance
        $this->validator = $this->validatorFactory->make($this->getData(), $this->getRules(), $this->getMessages(), $this->getAttributes());

        // Validation was a success!
        if (!$this->validator->fails()) {
            return true;
        }

        // Collect validation errors
        $this->errors = $this->validator->messages();

        return false;
    }

    /**
     * Validate data and throw an exception on failure
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return boolean
     * @throws \Nodes\Validation\Exceptions\ValidationException
     */
    public function validateOrFail()
    {
        // Validate data
        $passed = $this->validate();

        // If validation failed,
        // we'll throw an exception
        if (!$passed) {
            throw new ValidationException($this->validator, $this->getErrorCodes());
        }

        return true;
    }

    /**
     * Set data to validate
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array $data
     * @return $this
     */
    public function with(array $data)
    {
        // Set data for later validation
        $this->data = $data;

        // Set validation variables based on data
        $this->setValidationVariables($data);

        return $this;
    }

    /**
     * Set model to validate
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return $this
     */
    public function withModel(IlluminateModel $model)
    {
        // Set model for later validation
        $this->model = $model;

        // Set validation variables based on data
        $this->setValidationVariables($model->toArray());

        return $this;
    }

    /**
     * Set group name
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  string $name
     * @return $this
     */
    public function group($name)
    {
        $this->group = $name;
        return $this;
    }

    /**
     * Retrieve errors
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return array
     */
    public function errors()
    {
        return $this->errorsBag()->all();
    }

    /**
     * Retrieve errors message bag
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return \Illuminate\Support\MessageBag
     */
    public function errorsBag()
    {
        return $this->errors;
    }

    /**
     * Retrieve data to validate
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return array
     */
    public function getData()
    {
        // If a model has been set, it'll take priority
        if (!empty($this->model)) {
            return $this->model->toArray();
        }

        return $this->data;
    }

    /**
     * Retrieve validation rules
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return array
     * @throws \Nodes\Validation\Exceptions\GroupRulesNotFoundException
     */
    public function getRules()
    {
        // Retrieve group name
        $group = $this->group;

        // Make sure a group has been set
        if (empty($group)) {
            throw new GroupRulesNotFoundException('No group has been provided');
        }

        // Make sure group exists in rules array
        if (!array_key_exists($group, $this->rules)) {
            throw new GroupRulesNotFoundException(sprintf('Group [%s] not found in rules array', $group));
        }

        return $this->prepareRules($this->rules[$group]);
    }

    /**
     * Set validation rules
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array $rules
     * @return $this
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Retrieve custom error messages
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set custom error messages
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array $messages
     * @return $this
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Retrieve custom attributes
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set custom attributes
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Set custom error codes
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array $errorCodes
     * @return $this
     */
    public function setErrorCodes(array $errorCodes)
    {
        $this->errorCodes = $errorCodes;
        return $this;
    }

    /**
     * Retrieve custom error codes
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return array
     */
    public function getErrorCodes()
    {
        return $this->errorCodes;
    }

    /**
     * Set validation variables
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array  $data
     * @param  string $nestedKey
     * @return \Nodes\Validation\AbstractValidator
     */
    protected function setValidationVariables(array $data, $nestedKey = null)
    {
        foreach ($data as $key => $value) {
            // Add support for nested arrays
            $key = !empty($nestedKey) ? $nestedKey . '.' . $key : $key;

            // If value is an array, we need to created a nested key
            // until we reach the final value.
            if (is_array($value)) {
                $this->setValidationVariables($value, $key);
            } else {
                $this->variables['{:' . $key . '}'] = $value;
            }
        }

        return $this;
    }

    /**
     * Prepare rules and each rule's tests
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  array $rules
     * @return array
     */
    protected function prepareRules(array $rules)
    {
        // Prepared rules container
        $preparedRules = [];

        foreach ($rules as $key => $tests) {
            // Support arrays and pipes-splitted cases
            $tests = !is_array($tests) ? explode('|', $tests) : $tests;

            // Prepared tests container
            $preparedTests = [];

            foreach ($tests as $test) {
                // If validation variable exists in
                // our variables array we'll replace it.
                $test = str_replace(array_keys($this->variables), array_values($this->variables), $test);

                // Sometimes we expect a validation variable
                // but it's not present in our variables array
                // so we need to replace it with "null"
                $test = preg_replace('|{:.*}|is', 'null', $test);

                // Add test to container
                $preparedTests[] = $test;
            }

            // Add rule to container
            $preparedRules[$key] = $preparedTests;
        }

        return $preparedRules;
    }
}