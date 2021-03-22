<?php
// @codeCoverageIgnoreStart
defined('BASEPATH') || exit('No direct script access allowed');
// @codeCoverageIgnoreEnd

/**
 * CodeIgniter Dummy Gettext Helpers
 *
 */

if (!function_exists('__')) {
    /**
     * @param string $expression
     * @return string
     */
    function __($expression)
    {
        return $expression;
    }
}

if (!function_exists('_e')) {
    /**
     * @param string $expression
     */
    function _e($expression)
    {
        echo $expression;
    }
}

if (!function_exists('_n')) {
    /**
     * @param $expression_singular
     * @param $expression_plural
     * @param $number
     * @return string
     */
    function _n($expression_singular, $expression_plural, $number)
    {
        return ($number == 1) ? $expression_singular : $expression_plural;
    }
}