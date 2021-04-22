<?php

declare(strict_types=1);

namespace Tcg\Workflow\Flow\Condition;

use Assert\Assertion;

class ConditionCollection implements Condition
{
    /**
     * Child conditions of the collection.
     *
     * @var Condition[]|iterable
     */
    protected $conditions = array();

    /**
     * Construct.
     *
     * @param Condition[]|iterable $conditions Conditions.
     */
    public function __construct(iterable $conditions = array())
    {
        $this->addConditions($conditions);
    }

    public function match() {
        foreach($conditions as $condition) {
            if (!$condition->match())     return false;
        }

        return true;
    }

    public function loadPrerequisites($step, $val) {
        $arr = explode(",", $val);
        foreach($arr as $val) {
            $condition = new PreRequisiteCondition($step, trim($val));
            $this->conditions[] = $condition;
        }
    }

    public function loadPayloadCondition($step, $val) {
        $json = json_decode($val);
        foreach($json as $key => $val) {
            $condition = new PayloadCondition($step, $key, $val);
            $this->conditions[] = $condition;
        }
    }

    public function loadStateCondition($step, $val) {
        $json = json_decode($val);
        foreach($json as $key => $val) {
            $condition = new StateCondition($step, $key, $val);
            $this->conditions[] = $condition;
        }
    }

    /**
     * Add new child condition.
     *
     * @param Condition $condition Child condition.
     *
     * @return $this
     */
    public function addCondition(Condition $condition): self
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * Add multiple conditions.
     *
     * @param Condition[]|iterable $conditions Array of conditions.
     *
     * @return $this
     */
    public function addConditions(iterable $conditions): self
    {
        Assertion::allIsInstanceOf($conditions, Condition::class);

        foreach ($conditions as $condition) {
            $this->addCondition($condition);
        }

        return $this;
    }

    /**
     * Get all conditions.
     *
     * @return Condition[]|iterable
     */
    public function getConditions(): iterable
    {
        return $this->conditions;
    }

    /**
     * Remove condition from collection.
     *
     * @param Condition $condition Condition to remove.
     *
     * @return $this
     */
    public function removeCondition(Condition $condition): self
    {
        foreach ($this->conditions as $index => $value) {
            if ($value === $condition) {
                unset($this->conditions[$index]);
            }
        }

        return $this;
    }
}