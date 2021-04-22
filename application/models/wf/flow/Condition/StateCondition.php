<?php 

declare(strict_types=1);

namespace Tcg\Workflow\Flow\Condition;

class StateCondition implements Condition
{
    private $step;
 
    private $workflow_id;
    private $instance_id;

    private $key;
    private $value;
    private $operator;

    public function __construct(Step $step, string $key, string $value)
    {
        $this->step = $step;

        $this->workflow_id = $step->getWorkflowId();
        $this->instance_id = $step->getInstanceId();

        $this->operator = Comparison::parseOperator($key);
        $this->key = str_replace($this->operator, '', $key);
        $this->value = $value;
    }

    public function match() {
        $state = $this->step->getState();
        if (!isset($state[$this->key]))   return false;

        //get the current value
        $cur_value = $state[$this->key];

        return Comparison::compare($cur_value, $this->value, $this->operator);
    }
}

?>