<?php

namespace Nodes\Validation\Exceptions;

use Nodes\Exceptions\Exception as NodesException;

/**
 * Class GroupRulesNotFoundException.
 */
class GroupRulesNotFoundException extends NodesException
{
    /**
     * GroupRulesNotFoundException constructor.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  string   $message
     * @param  int  $code
     * @param  array    $headers
     * @param  bool  $report
     * @param  string   $severity
     */
    public function __construct($message = 'Validation group not found', $code = 500, array $headers = [], $report = false, $severity = 'error')
    {
        parent::__construct($message, $code, $headers, $report, $severity);
    }
}
