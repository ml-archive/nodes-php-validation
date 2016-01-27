<?php
namespace Nodes\Validation\Exceptions;

use Illuminate\Validation\Validator as IlluminateValidator;
use Nodes\Exceptions\Exception as NodesException;

/**
 * Class ValidationException
 *
 * @package Nodes\Validation\Exceptions
 */
class ValidationException extends NodesException
{
    /**
     * ValidationException constructor
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  \Illuminate\Validation\Validator $validator
     * @param  array                            $errorCodes
     * @param  array                            $headers
     * @param  boolean                          $report
     * @param  string                           $severity
     */
    public function __construct(IlluminateValidator $validator, array $errorCodes, array $headers = [], $report = false, $severity = 'error')
    {
        // Parse failed rules
        $failedRules = $this->parseFailedRules($validator->failed());

        // Set message of exception
        $errorMessages = $validator->errors();
        if ($errorMessages->count() > 1) {
            $message = 'Multiple validation rules failed. See "errors" for more details.';
        } else {
            $message = $errorMessages->first();
        }

        // Custom error codes container
        $customErrorCodes = [];

        // Custom error codes takes priority, so let's see
        // if one of our failed rules has one
        $failedRulesCustomErrorCodes = array_intersect(array_keys($errorCodes), $failedRules);
        if (!empty($failedRulesCustomErrorCodes)) {
            foreach ($failedRulesCustomErrorCodes as $failedRule) {
                $customErrorCodes[$errorCodes[$failedRule]] = $errorCodes[$failedRule];
            }
        }

        // Determine exception and status code
        $exceptionCode = !empty($customErrorCodes) ? $customErrorCodes : 412;
        $statusCode = !empty($customErrorCodes) ? array_shift($customErrorCodes) : 412;

        // Construct exception
        parent::__construct($message, $exceptionCode, $headers, $report, $severity);

        // Fill exception's error bag with validation errors
        $this->setErrors($errorMessages);

        // Set status code
        $this->setStatusCode($statusCode, $errorMessages->first());
    }

    /**
     * Parse failed rules
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access protected
     * @param  $failedRules
     * @return array
     */
    protected function parseFailedRules($failedRules)
    {
        // Parsed failed rules container
        $parsedFailedRules = [];

        foreach ($failedRules as $field => $ruleAttributes) {
            foreach ($ruleAttributes as $rule => $attributes) {
                $parsedFailedRules[$field] = strtolower($field . '.' . $rule);
            }
        }

        return $parsedFailedRules;
    }
}