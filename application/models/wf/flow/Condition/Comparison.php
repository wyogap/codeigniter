<?php

declare(strict_types=1);

namespace Tcg\Workflow\Flow\Condition;

final class Comparison
{
    const EQUALS                 = '==';
    const IDENTICAL              = '===';
    const NOT_EQUALS             = '!=';
    const NOT_IDENTICAL          = '!==';
    const GREATER_THAN           = '>';
    const LESSER_THAN            = '<';
    const LESSER_THAN_OR_EQUALS  = '<=';
    const GREATER_THAN_OR_EQUALS = '>=';

    /**
     * Operation method mapping cache.
     *
     * @var array
     */
    private static $operators;

    /**
     * Compare two values.
     *
     * @param mixed  $valueA   Value a.
     * @param mixed  $valueB   Value b.
     * @param string $operator The operator for the comparison.
     *
     * @return bool
     */
    public static function compare($valueA, $valueB, string $operator): bool
    {
        if ($valueB == null && $operator == Comparisan::EQUALS) {
            return self::isEmpty($valueA);
        }

        if ($valueB == null && $operator == Comparisan::NOT_EQUALS) {
            return self::notEmpty($valueA);
        }

        $method = self::getOperatorMethod($operator);

        if ($method) {
            return call_user_func([get_called_class(), $method], $valueA, $valueB);
        }

        return false;
    }

    public static function parseOperator($key) {
        $reflector = new \ReflectionClass(get_called_class());
        $constants = $reflector->getConstants();

        foreach ($constants as $name => $operator) {
            $pos = strrpos($key, $operator);
            if ($pos >= 0) {
                return $operator;
            }
        }

        //default operator is equal
        return Comparison::EQUALS;
    }

    /**
     * Check for empty variable
     *
     * @param mixed $valueA Value a.
     *
     * @return bool
     */
    public static function isEmpty($valueA): bool 
    {
        return empty($valueA);
    }

    /**
     * Check for non empty variable
     *
     * @param mixed $valueA Value a.
     *
     * @return bool
     */
    public static function notEmpty($valueA): bool 
    {
        return !empty($valueA);
    }

    /**
     * Consider if two values equals.
     *
     * @param mixed $valueA Value a.
     * @param mixed $valueB Value b.
     *
     * @return bool
     */
    public static function equals($valueA, $valueB): bool
    {
        return $valueA == $valueB;
    }

    /**
     * Consider if both values are identical.
     *
     * It uses the === operator of php.
     *
     * @param mixed $valueA Value a.
     * @param mixed $valueB Value b.
     *
     * @return bool
     */
    public static function identical($valueA, $valueB): bool
    {
        return $valueA === $valueB;
    }

    /**
     * Consider if two values not equals.
     *
     * @param mixed $valueA Value a.
     * @param mixed $valueB Value b.
     *
     * @return bool
     */
    public static function notEquals($valueA, $valueB): bool
    {
        return !static::equals($valueA, $valueB);
    }

    /**
     * Consider if two values are not identical.
     *
     * @param mixed $valueA Value a.
     * @param mixed $valueB Value b.
     *
     * @return bool
     */
    public static function notIdentical($valueA, $valueB): bool
    {
        return !static::identical($valueA, $valueB);
    }

    /**
     * Consider if value a is greater than value b.
     *
     * @param mixed $valueA Value a.
     * @param mixed $valueB Value b.
     *
     * @return bool
     */
    public static function greaterThan($valueA, $valueB): bool
    {
        return $valueA > $valueB;
    }

    /**
     * Consider if value a is greater than or equals value b.
     *
     * @param mixed $valueA Value a.
     * @param mixed $valueB Value b.
     *
     * @return bool
     */
    public static function greaterThanOrEquals($valueA, $valueB): bool
    {
        return $valueA >= $valueB;
    }

    /**
     * Consider if value a is lesser than value b.
     *
     * @param mixed $valueA Value a.
     * @param mixed $valueB Value b.
     *
     * @return bool
     */
    public static function lesserThan($valueA, $valueB): bool
    {
        return $valueA < $valueB;
    }

    /**
     * Consider if value a is lesser than or equals value b.
     *
     * @param mixed $valueA Value a.
     * @param mixed $valueB Value b.
     *
     * @return bool
     */
    public static function lesserThanOrEquals($valueA, $valueB): bool
    {
        return $valueA <= $valueB;
    }

    /**
     * Get operator method. Returns false if metod not set.
     *
     * @param string $operator The current operator.
     *
     * @return string|bool
     */
    private static function getOperatorMethod(string $operator)
    {
        $operators = self::getOperators();

        if (isset($operators[$operator])) {
            return $operators[$operator];
        }

        return false;
    }

    /**
     * Get operator method mapping.
     *
     * @return array
     */
    private static function getOperators(): array
    {
        if (!is_array(self::$operators)) {
            $reflector = new \ReflectionClass(get_called_class());
            $constants = $reflector->getConstants();
            $operators = array();

            foreach ($constants as $name => $operator) {
                $parts = explode('_', $name);
                $parts = array_map(
                    function ($item) {
                        $item = strtolower($item);
                        $item = ucfirst($item);

                        return $item;
                    },
                    $parts
                );

                $operators[$operator] = lcfirst(implode('', $parts));
            }

            self::$operators = $operators;
        }

        return self::$operators;
    }
}