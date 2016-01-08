<?php
if (!function_exists('validation_key_failed')) {
    /**
     * Check if a specific key has failed validation
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  string $key
     * @return boolean
     */
    function validation_key_failed($key)
    {
        // Only continue if we actually
        // have a validation that has failed
        if (\Session::has('error')) {
            return false;
        }

        // Retrieve failed validations error bag
        $errorBag = \Session::get('error');

        // If $key is present in either in the error bag (or array)
        // then $key has failed validation
        if (($errorBag instanceof \Illuminate\Support\MessageBag && $errorBag->has($key)) ||
            (is_array($errorBag) && array_key_exists($key, $errorBag))) {
            return true;
        }

        return false;
    }
}