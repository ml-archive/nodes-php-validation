<?php
namespace Nodes\Validation\Exceptions;

use Nodes\Exceptions\Exception as NodesException;

/**
 * Class InvalidValidatorException
 *
 * @package Nodes\Validation\Exceptions
 */
class InvalidValidatorException extends NodesException
{
    /**
     * GroupRulesNotFoundException constructor
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  string   $message
     * @param  integer  $code
     * @param  array    $headers
     * @param  boolean  $report
     * @param  string   $severity
     */
    public function __construct($message = 'Validator not extending Laravel\'s validator', $code = 500, array $headers = [], $report = false, $severity = 'error')
    {
        parent::__construct($message, $code, $headers, $report, $severity);
    }
}