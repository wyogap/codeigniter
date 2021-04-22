<?php 

declare(strict_types=1);

namespace Tcg\Workflow\Flow;

class Transition extends CI_Model
{
    private $name;
    private $label;

    private $workflow_id;
    private $instance_id;

    private $step;
    private $config;

    private $conditions = array();

    public function __construct(Step $step, array $config) {
        $this->step = $step;
        $this->config = $config;

        $this->name = $config['name'];
        $this->label = $config['label'];
        $this->workflow_id = $step->getWorkflowId();
        $this->instance_id = $step->getInstanceId();

        if (!empty($this->config['payload_condition'])) {
            $condition = new ConditionCollection();
            $condition->loadPayloadCondition($this->step, $this->config['payload_condition']);

            $this->conditions[] = $condition;
        }

        if (!empty($this->config['state_condition'])) {
            $condition = new ConditionCollection();
            $condition->loadStateCondition($this->step, $this->config['state_condition']);

            $this->conditions[] = $condition;
        }
    }

    public function execute() {
        //check transition pre-conditions
        if (!$this->checkTransitionConditions()) {
            return false;
        }

        $workflow = $this->step->getWorkflow();
        $step_to = $this->config['step_to'];
        $next_step = new Step($workflow, $step_to);

        $payload = $this->step->getPayload();
        $step_name = $this->step->getName();
        $transition = $this->name;

        //try to transit
        $transit = $next_step->transitIn($step_to, $payload, $step_name, $transition);
        if (!$transit) {
            return false;
        }

        //audit
        $this->auditTransition($step_name, $transition, $step_to, $payload);

        //execute actions
        $this->executeTransitionActions($next_step);

    }

    public function getName() {
        return $this->name;
    }

    public function getStepTo() {
        return $this->config['step_to'];
    }

    protected function checkTransitionConditions() {
        if (empty($this->conditions))    return true;

        foreach($this->conditions as $condition) {
            if (!$condition->match())     return false;
        }

        return true;
    }

    protected function executeStepActions() {
        $actions = $this->_getTransitionActions();
        if (empty($actions))       return true;

        foreach($actions as $action) {
            $action->execute();
        }

        return true;
    }

    protected function createAction($step, $instance) {
        $action = null;
        if ($instance['action'] == "update-entity") {
            $action = new UpdateEntityAction($step, $instance);
        }
        else if ($instance['action'] == "send-sms") {
            $action = new SendSmsAction($step, $instance);
        }
        else if ($instance['action'] == "send-email") {
            $action = new SendEmailAction($step, $instance);
        }
        else if ($instance['action'] == "transition") {
            $action = new TransitionAction($step, $instance);
        }

        return $action;
    }

    private function _getTransitionActions(Step $step) {
        $step_name = $step->getName();

        $filter = array(
            "transition"=>$step_name, 
            "workflow_id"=>$this->workflow_id, 
            "is_deleted"=>0
        );

        $this->db->order_by("order_no asc");

        $configs = $this->db->get_where("dbo_wf_transitions_actions", $filter)->result_array();
        if ($configs == null)     return array();

        $actions = array();
        foreach($configs as $val) {
            $action = $this->createAction($step, $val);
            if ($action != null) {
                $actions[] = $action;
            }
        }

        return $actions;
    }

    private function auditTransition($step_name, $transition, $step_to, $payload, $state) {

        $keys = array(
            'workflow_id', 'name', 'label', 'entity', 'entity_id', 'step_from', 'transition', 'step_to', 'payload', 'state'
        );

        $values = array(
            $this->workflow_id, $this->name, $this->label, $this->entity, $this->entity_id, $step_name, $transition, $step_to, json_encode($payload, JSON_INVALID_UTF8_IGNORE),json_encode($state, JSON_INVALID_UTF8_IGNORE),
        );

        audittrail_trail('workflow', $this->instance_id, 'TRANSITION', 'Workflow transition: [' .$step_name. "]=>" .$transition. "=>[" .$step_to. "]", $keys, $values);
    }

}

?>