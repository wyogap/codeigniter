<?php 

declare(strict_types=1);

namespace Tcg\Workflow\Flow;

class Step extends CI_Model
{
    protected const STEP_NOTREACHED = 0;
    protected const STEP_REACHED = 1;
    protected const STEP_STARTED = 2;
    protected const STEP_TERMINATED = 9;
    protected const STEP_COMPLETED = 99;

    private $name;
    private $label;

    private $workflow_id;
    private $instance_id;

    private $entity;
    private $entity_id;

    protected $user_id;
    protected $role_id;

    private $status = Step::STEP_NOTREACHED;

    /**
     * Parent workflow
     *
     * @var object
     */
    private $workflow;

    /**
     * Active step's state.
     *
     * @var array
     */
    protected $state = array();

    /**
     * Active step's payload.
     *
     * @var array
     */
    protected $payload = array();

    /**
     * Parameters values.
     *
     * @var array
     */
    protected $params = array();

    /**
     * Configuration values.
     *
     * @var array
     */
    protected $config = array();

    /**
     * Instance values.
     *
     * @var array
     */
    protected $instance = array();

    protected $preconditions = array();

    protected $actions = array();

    /**
     * Construct.
     *
     * @param string $name   Name of the element.
     * @param string $label  Label of the element.
     * @param array  $config Configuration values.
     */
    public function __construct(Workflow $workflow, string $name, array $instance = null)
    {
        $this->workflow   = $workflow;
        $this->workflow_id  = $workflow->getWorkflowId();
        $this->instance_id  = $workflow->getInstanceId();
        $this->params       = $workflow->getParams();
        $this->entity       = $workflow->getEntity();
        $this->entityId     = $workflow->getEntityId();
        
        $this->name         = $name;

        // //get db instance
        // $ci &= get_instance();
        // $this->db     = $ci->db; 

        //get current user id
        $this->user_id  = $this->session->userdata('user_id');
        $this->role_id  = $this->session->userdata('role_id');

        do {
            //load config
            $this->config = $this->_getStepConfig($this->name);
            if ($this->config == null) {
                break;
            }

            $this->label = $this->config['label'];

            if ($instance == null) {
                $instance = $this->_getStepInstance($this->instance_id, $this->name);
            }

            if ($instance == null) {
                break;
            }

            $this->payload = json_decode($instance['payload']);
            $this->state = json_decode($instance['state']);

            $this->instance = $instance;
            $this->status = $instance['status'];

            if (!empty($this->config['prerequisites'])) {
                $condition = new ConditionCollection();
                $condition->loadPrerequisites($this, $this->config['prerequisites']);

                $this->preconditions[] = $condition;
            }
    
        } while(false);

        //DO NOT automatically execute wf
    }

    public function getName() {
        return $this->name;
    }

    public function getWorkflow() {
        return $this->workflow;
    }

    public function getState() {
        return $this->state;
    }

    public function updateState($key, $val) {
        return $this->state[$key] = $val;
    }

    public function getPayload() {
        return $this->payload;
    }

    public function updatePayload($key, $val) {
        return $this->payload[$key] = $val;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getWorkflowId() {
        return $this->workflow_id;
    }

    public function getInstanceId() {
        return $this->instance_id;
    }

    protected function transitIn($payload=null, $step_from=null, $transition_from=null) {
        if ($this->workflow == null || $this->config == null) {
            return false;
        }

        //init step if necessary
        if ($this->instance == null) {
            $this->$instance = $this->_initializeStep($this->config, $payload, $step_from, $transition_from);
            
            //initial state
            $this->payload = $payload;
            $this->state = json_decode($instance['state']);;
            $this->status = $instance['status'];
        }
        else {
            //if step is already completed, ignore
            if ($this->status == Step::STEP_COMPLETED || $this->status == Step::STEP_TERMINATED ) {
                return true;
            }

            //merge payload
            $this->payload = array_merge($this->payload, $payload);
        }

        //check step pre-conditions
        if (!$this->checkStepPreconditions()) {
            return false;
        }

        //execute actions
        $this->executeStepActions();

        //if it is final step, end the wf
        $is_final = !empty($this->config['is_final']);
        if($is_final) {
            //updateStep current state
            $this->status = Step::STEP_COMPLETED;
            $this->_persistStep();

            //terminate workflow
            $this->workflow->terminate($step_name);
            return true;
        }

        //updateStep current state
        $this->status = Step::STEP_STARTED;
        $this->_persistStep();
        
        //see if we can move to next step automatically
        $transit = $this->transitOut();

        return true;
    }

    protected function transitOut() {
        //iterate through all transitions
        $transitions = $this->_getStepTransitions();
        if ($transitions == null)       return false;

        $completed = false;
        foreach($transitions as $key => $transition) {
            if ($this->workflow->getStatus() == Workflow::WF_FINISHED) {
                $completed = true;
                break;
            }
                
            $transit = $transition->execute();

            if ($transit) {
                $completed = true;
            }
        }

        if ($completed) {
            $this->_finishStep();
        }

        return $completed;
    }

    protected function checkStepPreconditions() {
        if (empty($this->preconditions))    return true;

        foreach($this->preconditions as $condition) {
            if (!$condition->match())     return false;
        }

        return true;
    }

    protected function executeStepActions() {
        $actions = $this->_getStepActions();
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
        else if ($instance['action'] == "edit-payload") {
            $action = new EditPayloadAction($step, $instance);
        }
        else if ($instance['action'] == "check-sms") {
            $action = new CheckSmsAction($step, $instance);
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
        else if ($instance['action'] == "timeout") {
            $action = new Timeout($step, $instance);
        }

        return $action;
    }

    private function _getStepConfig($workflow_id, $step_name) {
        $filter = array(
            "name"          => $step_name, 
            "workflow_id"   => $workflow_id, 
            "is_deleted"    => 0
        );

        $step = $this->db->get_where("dbo_wf_steps", $filter)->row_array();

        return $step;
    }

    private function _getStepInstance($instance_id, $step_name) {
        $filter = array (
            'instance_id'   => $instance_id,
            'step'          => $step_name,
            // 'status!='      => Step::STEP_TERMINATED,
            // 'status!='      => Step::STEP_COMPLETED,
            'is_deleted'    => 0
        );

        $instance = $this->db->get_where("dbo_wf_instances_steps", $filter)->row_array();

        return $instance;
    }

    private function _initializeStep($config, $payload, $step_from, $transition_from) {
        $state = array(
            'status'    => $self::STEP_REACHED
        );

        $valuepair = array (
            'workflow_id'   => $this->workflow_id,
            'instance_id'   => $this->instance_id,
            'step'          => $config['name'],
            'state'         => json_encode($state, JSON_INVALID_UTF8_IGNORE),
            'payload'       => json_encode($payload, JSON_INVALID_UTF8_IGNORE),
            'params'        => json_encode($this->params, JSON_INVALID_UTF8_IGNORE),
            'is_final'      => $config['is_final'],
            'status'        => Step::STEP_REACHED,
            'step_from'     => $step_from,
            'transition_from'   => $transition_from,
            'created_on'    => date('Y/m/d H:i:s'),
            'created_by'    => $this->user_id
        );

        $query = $this->db->insert("dbo_wf_instances_steps", $valuepair);
        if ($query) {
            $id = $this->db->insert_id();

            //insert action list
            $this->_initializeStepActions($config['name'], $payload);

            // //audit trail
            // $this->auditStep($step['name'], $self::STEP_REACHED);

            return $this->_getStepInstance($this->instance_id, $config['name']);
        }

        return null;
    }

    private function initializeStepActions($step_name, $payload) {
        $this->db->order_by("order_no asc");

        $steps = $this->db->get_where("dbo_wf_steps_actions", array("step"=>$step_name, "workflow_id"=>$this->workflow_id, "is_deleted"=>0))->row_array();

        foreach($steps as $key => $val) {
            $name = $val['name'];

            $valuepair = array(
                'workflow_id'   => $this->workflow_id,
                'instance_id'   => $this->instance_id,
                'step'          => $step_name,
                'name'          => $name,
                'action'        => $val['action'],
                'type'          => $val['type'],
                'interval'      => $val['interval'],
                'repeat'        => $val['repeat'],
                'state_condition'        => $val['state_condition'],
                'payload_condition'      => $val['payload_condition'],
                'transform_payload'      => $val['transform_payload'],
                'transform_state'        => $val['transform_state'],
                'recipients'    => $val['recipients'],
                'user_messages' => $val['user_messages'],
                'user_action'   => $val['user_action'],
                'editable_payload'       => $val['editable_payload']
            );

            if ($val['user_action']) {
                //generate token
                $valuepair['token'] = generate_token(10);
            }

            if (!empty($val['recipients'])) {
                //TODO: parse recipients:s
                //[[xx]]    -> user groups
                //{{xx}}    -> payload field
                $valuepair['recipients'] = $val['recipients'];
             }

            $valuepair['status'] = Step::ACTION_NOTSTARTED;

            //insert
            $this->db->insert('dbo_wf_instances_actions', $valuepair);
        }
    }

    private function _persistStep() {
        //update
        $valuepair = array(
            'status'    => $this->status,
            'payload'   => json_encode($this->payload, JSON_INVALID_UTF8_IGNORE),
            'state'     => json_encode($this->state, JSON_INVALID_UTF8_IGNORE),
            'updated_by'    => $this->user_id,
            'updated_on'    => date('Y/m/d H:i:s')
        );

        //filter
        $filter = array(
            'is_deleted'    => 0,
            'instance_id'   => $this->instance_id,
            'step'          => $this->name
        );

        $this->db->update('dbo_wf_instances_steps', $valuepair, $filter);

        return true;
    }

    private function _finishStep() {
        $status = Step::STEP_COMPLETED;
        $state = $this->state;
        $state['status'] = $status;

        //update
        $valuepair = array(
            'status'    => $status,
            'state'     => json_encode($state, JSON_INVALID_UTF8_IGNORE),
            'updated_by'    => $this->user_id,
            'updated_on'    => date('Y/m/d H:i:s')
        );

        //filter
        $filter = array(
            'is_deleted'    => 0,
            'instance_id'   => $this->instance_id,
            'step'          => $this->name
        );

        $this->db->update('dbo_wf_instances_steps', $valuepair, $filter);

        $this->status = $status;
        $this->state = $state;

        return true;
    }


    private function _getStepTransitions() {
        $this->db->order_by("order_no asc");

        $configs = $this->db->get_where("dbo_wf_transitions", array("step_from"=>$this->name, "workflow_id"=>$this->workflow_id, "is_deleted"=>0))->result_array();
        if ($configs == null)     return array();

        //get payload
        $payload = $this->payload;
        $state = $this->state;

        $transitions = array();
        foreach($configs as $key => $val) {
            $transition = new Transition($this, $val);
            $transitions[] = $transition;
        }

        return $transitions;
    }
    
    private function _getStepActions() {
        $this->db->order_by("order_no asc");

        $configs = $this->db->get_where("dbo_wf_instances_actions", array("step"=>$this->name, "workflow_id"=>$this->workflow_id, "is_deleted"=>0))->result_array();
        if ($configs == null)     return array();

        $actions = array();
        foreach($configs as $val) {
            if ($val['type'] == 'once' && $val['status'] == Action::ACTION_COMPLETED) {
                continue;
            }

            if ($val['type'] == 'interval' && $val['counter'] >= $val['repeat']) {
                continue;
            }

            $action = $this->createAction($this, $val);
            if ($action != null) {
                $actions[] = $action;
            }
        }

        return $actions;
    }
}

?>