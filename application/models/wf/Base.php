<?php

declare(strict_types=1);

namespace Tcg\Workflow\Flow;

abstract class Base
{
    protected const WF_NOTSTARTED = 0;
    protected const WF_STARTED = 1;
    protected const WF_FINISHED = 99;

    protected const STEP_NOTREACHED = 0;
    protected const STEP_REACHED = 1;
    protected const STEP_STARTED = 2;
    protected const STEP_TERMINATED = 9;
    protected const STEP_COMPLETED = 99;

    protected const ACTION_NOTSTARTED = 0;
    protected const ACTION_ACTIVE = 1;
    protected const ACTION_COMPLETED = 99;

    private $name;
    private $label;

    private $workflow_id;
    private $instance_id;

    private $entity;
    private $entity_id;

    private $status = 0;

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

    /**
     * Active steps.
     *
     * @var string
     */
    private $steps = array();

    /**
     * Active step's state.
     *
     * @var array
     */
    protected $states = array();

    /**
     * Active step's payload.
     *
     * @var array
     */
    protected $payloads = array();

    /**
     * Database.
     *
     * @var database object
     */
    protected $db;

    protected $user_id;
    protected $role_id;

    // /**
    //  * Construct.
    //  *
    //  * @param string $name   Name of the element.
    //  * @param string $label  Label of the element.
    //  * @param array  $config Configuration values.
    //  */
    // public function __construct(string $name, string $label = '', array $config = array())
    // {
    //     $this->name   = $name;
    //     $this->label  = $label ?: $name;
    //     $this->config = $config;

    //     //get db instance
    //     $ci &= get_instance();
    //     $this->db     = $ci->db; 

    //     //load config
    //     $this->config();

    //     //DO NOT automatically start wf
    // }

    /**
     * Construct.
     *
     * @param string $name   Name of the element.
     * @param string $label  Label of the element.
     * @param array  $config Configuration values.
     */
    public function __construct(string $name, array $params, int $instance_id = 0)
    {
        $this->name   = $name;
        $this->params = $params;

        //get db instance
        $ci &= get_instance();
        $this->db     = $ci->db; 

        //get current user id
        $this->user_id  = $ci->session->userdata('user_id');
        $this->role_id  = $ci->session->userdata('role_id');

        //load config
        $this->config();

        //load current state
        if ($instance_id > 0) {   
            if (!$this->load($instance_id)) {
                //fail to load. reset instance id
                $instance_id = 0;
            }
        }

        //DO NOT automatically execute wf
        $this->instance_id  = $instance_id;
    }

    public function getInstanceId() {
        return $this->instance_id ;
    }

    public function getPayload($step_name = null) {
        if (!empty($step_name)) {
            return $this->payloads[$step_name];
        }

        $payload = array();
        foreach($this->payloads as $key => $val) {
            $payload = array_merge($payload, $val);
        }

        return $payload;
    }

    public function updatePayload($payload, $step_name = null) {
        if (empty($payload)) return;

        foreach($this->payloads as $key => $val) {
            if (!empty($step_name) && $key != $step_name)   continue;

            //merge the array
            $this->payloads[$key] = array_merge($this->payloads[$key], $payload);

            //update the state
            $this->states[$key]['update_payload'] = 1;

            //persist
            $status = $this->steps[$key]['status'];
            $payload = $this->payloads[$key];
            $state = $this->states[$key];
            $this->updateStep($key, $status, $payload, $state);
        }

    }

    public function getState($step_name) {
        return $this->states[$step_name];
    }

    public function getStep($step_name) {
        return $this->steps[$step_name];
    }

    // public function execute() {
    //     if ($this->instance_id == 0) {
    //         //not started
    //         if (!$this->start()) {
    //             return false;
    //         }
    //     }

    //     $steps = $this->getActiveSteps();
    //     if ($steps == null)     return false;

    //     foreach($steps as $key => $val) {
    //         $step_name = $val['name'];
            
    //         //simulate a transit-in
    //         $this->transitIn($step_name);
    //     }
    // }

    public function execute(array $params = array()) {

        //check for existing instance
        if ($this->instance_id == 0) {
            //store params
            $this->params = array_merge($this->params, $param);

            //check for existing wf for this entity and entity-id
            $this->entity = isset($this->params['entity']) ? $this->params['entity'] : '';
            $this->entity_id = isset($this->params['entity_id']) ? $this->params['entity_id'] : 0;

            if (empty($this->entity) || empty($this->entity_id)) {
                return false;
            }

            //get existing wf if any
            $this->instance_id = $this->getWorkflowInstance($this->workflow_id, $this->entity, $this->entity_id);

            //no existing, init one
            if ($this->instance_id == 0) {
                $this->instance_id = $this->initializeInstance($this->workflow_id, $this->entity, $this->entity_id);
            }

            if ($this->instance_id == 0) {
                return false;
            }

            //load instance
            $this->load($this->instance_id);
        }

        $steps = $this->getActiveSteps();
        if (count($steps)) {
            //already started
            foreach($steps as $key => $val) {
                $step_name = $val['name'];
                
                //simulate a transit-in
                $this->transitIn($step_name);
            }

            return true;
        }

        //audit trail
        $this->auditStartup();

        //get payload
        $payload = $this->initializePayload($this->entity, $this->entity_id);

        //execute start transitions
        $transitions = $this->getStartTransitionsConfig();

        $flag = false;
        foreach($transitions as $key => $val) {
            //check transition pre-conditions
            if (!$this->checkTransitionConditions($val)) {
                continue;
            }

            //execute actions
            $this->executeTransitionActions($val);

            //check transition conditions
            $transit = $this->checkTransitionConditions($val);

            if ($transit) {
                $step_to = $val['step_to'];
                $transition = $val['name'];

                //try to transit
                $transit = $this->transitIn($step_to, $payload, null, $transition);

                //audit
                if ($transit) {
                    $this->auditTransition($step_name, $transition, $step_to, $payload);
                    $flag = true;
                }
            }
        }

        //set status: active
        $this->status = self::WF_STARTED;

        return $flag;

    }

    protected function terminate($final_step_name) {
        $this->terminateActiveSteps($step_name);

        $this->status = self::WF_FINISHED;
        $this->updateInstance($this->status);

        //audit trail
        $this->auditCompletion($final_step_name);
    }

    protected function transitIn($step_name, $payload=null, $step_from=null, $transition_from=null) {
        //get step configuration
        $step = $this->getStepConfig($step_name);

        //init step if necessary
        $instance = $this->getStepInstance($step_name);
        if ($instance == null) {
            $instance = $this->initializeStep($step, $payload, $step_from, $transition_from);
            
            //initial state
            $state = array();
        }
        else {
            //if step is already completed, ignore
            if ($instance['status'] == self::STEP_COMPLETED || $instance['status'] == self::STEP_TERMINATED ) {
                return true;
            }

            //merge payload
            if (!empty($instance['payload'])) {
                $payload = array_merge(json_decode($instance['payload']), $payload);
            }

            //get state
            $state = json_decode($instance['state']);

            //save current state
            $this->updateStep($step_name, $instance['status'], $payload, $state);
        }

        //store for easy access
        $this->steps[$step_name] = $instance;
        $this->payloads[$step_name] = $payload;
        $this->states[$step_name] = $state;

        //check step pre-conditions
        if (!$this->checkStepPreconditions($step)) {
            return false;
        }

        //execute actions
        $this->executeStepActions($step);

        //if it is final step, end the wf
        $is_final = !empty($step['is_final']);
        if($is_final) {
            //updateStep current state
            $this->steps[$step_name]['status'] = self::STEP_COMPLETED;
            $this->updateStep($step_name);

            //terminate workflow
            $this->terminate($step_name);
            return true;
        }

        //updateStep current state
        $this->steps[$step_name]['status'] = self::STEP_STARTED;
        $this->updateStep($step_name);
        
        //see if we can move to next step automatically
        $transit = $this->transitOut($step_name);

        return true;
    }

    protected function transitOut($step_name) {
        //iterate through all transitions
        $transitions = $this->getStepTransitionsConfig($step_name);
        if ($transitions == null)       return false;

        $flag = false;
        foreach($transitions as $key => $val) {
            //if wf is terminated or completed in the middle of loop, quit
            if ($this->status != 1) {
                break;
            }

            //check transition pre-conditions
            if (!$this->checkTransitionConditions($step_name, $val)) {
                continue;
            }

            //execute actions
            $this->executeTransitionActions($step_name, $val);

            //check transition conditions
            $transit = $this->checkTransitionConditions($step_name, $val);

            if ($transit) {
                $step_to = $val['step_to'];
                $transition = $val['name'];
                $payload = $this->payload['$step_name'];

                //try to transit
                $transit = $this->transitIn($step_to, $payload, $step_name, $transition);

                //audit
                if ($transit) {
                    $this->auditTransition($step_name, $transition, $step_to, $payload);
                }

                //finish the step
                if ($transit && !$flag) {
                    $this->finishStep($step_to, $transition);
                    $flag = true;
                }
            }
        }

        return $flag;
    }

    protected function checkStepPreconditions($step) {
        //TODO
        return false;
    }

    protected function executeStepActions($step) {
        //TODO
    }

    protected function checkTransitionPreconditions($transition) {
        //TODO
        return false;
    }

    protected function executeTransitionActions($transition) {
        //TODO
    }

    protected function checkTransitionConditions($transition) {
        //TODO
        return false;
    }

    private function config() {
        //get workflow config
        $config = $this->db->get_where("dbo_wf_workflows", array("name"=>$this->name, "is_deleted"=>0))->row_array();

        if ($config != null) {
            $this->config = $config;
            $this->label = $config['label'];
            $this->workflow_id = $config['id'];
            return true;
        }

        return false;
    }

    private function load($instance_id) {
        //get active steps
        $instance = $this->db->get_where("dbo_wf_instances", array("id"=>$instance_id, "is_deleted"=>0))->row_array();

        if ($instance != null) {
            $this->instance = $instance;
            return true;
        }

        return false;
    }

    private function getWorkflowInstance($workflow_id, $entity, $entity_id) {
        $filter = array (
            'workflow_id'   => $workflow_id,
            'entity'        => $entity,
            'entity_id'     => $entity_id,
            'is_deleted'    => 0
        );

        $instance = $this->db->get_where("dbo_wf_instances", $filter)->row_array();
        if($instance == null)   return 0;

        return $instance['id'];
    }

    private function initializeInstance($workflow_id, $entity, $entity_id) {
        $valuepair = array (
            'workflow_id'   => $workflow_id,
            'entity'        => $entity,
            'entity_id'     => $entity_id,
            'status'        => $self::WF_NOTSTARTED,
            'created_on'    => date('Y/m/d H:i:s'),
            'created_by'    => $this->user_id
        );

        $query = $this->db->insert("dbo_wf_instances", $valuepair);
        if ($query) {
            $id = $this->db->insert_id();

            //audit trail
            $this->auditInitialize();

            return $id;
        }

        return 0;
    }

    private function initializePayload($entity, $entity_id) {
        $filter = array(
            'id'    => $entity_id,
            'is_deleted'    => 0
        );

        return $this->db->get_where($entity, $filter)->row_array();
    }

    private function updateInstance($status) {
        $filter = array(
            'id'            => $this->instance_id,
            'is_deleted'    => 0
        );

        $valuepair = array(
            'status'     => $status,
            'updated_on' => date('Y/m/d H:i:s'),
            'updated_by' => $this->user_id

        );

        $this->db->update("dbo_wf_instances", $valuepair, $filter);   
    }

    private function getStepInstance($step_name) {
        $filter = array (
            'instance_id'   => $this->instance_id,
            'step'          => $step_name,
            'is_deleted'    => 0
        );

        $instance = $this->db->get_where("dbo_wf_instances_steps", $filter)->row_array();

        return $instance;
    }

    private function initializeStep($step, $payload, $step_from, $transition_from) {
        $state = array(
            'status'    => $self::STEP_REACHED
        );

        $valuepair = array (
            'workflow_id'   => $this->workflow_id,
            'instance_id'   => $this->instance_id,
            'step'          => $step['name'],
            'state'         => json_encode($state, JSON_INVALID_UTF8_IGNORE),
            'payload'       => json_encode($payload, JSON_INVALID_UTF8_IGNORE),
            'params'        => json_encode($this->params, JSON_INVALID_UTF8_IGNORE),
            'is_final'      => $step['is_final'],
            'status'        => $self::STEP_REACHED,
            'step_from'     => $step_from,
            'transition_from'   => $transition_from,
            'created_on'    => date('Y/m/d H:i:s'),
            'created_by'    => $this->user_id
        );

        $query = $this->db->insert("dbo_wf_instances_steps", $valuepair);
        if ($query) {
            $id = $this->db->insert_id();

            //insert action list
            $this->initializeStepActions($step_name, $payload);

            //audit trail
            $this->auditStep($step['name'], $self::STEP_REACHED);

            return $id;
        }

        return 0;
    }

    private function updateStep($step_name, $status = null, $payload = null, $state = null) {
        if ($status == null) {
            $step = $this->steps[$step_name];
            if (empty($step)) {
                $status = 0;
            }
            else {
                $status = $step['status'];
            }
        }

        if ($payload == null) {
            $payload = $this->payloads[$step_name];
            if (empty($payload)) {
                $payload = array();
            }
        }

        if ($state == null) {
            $state = $this->states[$step_name];
            if (empty($state)) {
                $state = array();
            }
        }
        
        //update
        $valuepair = array(
            'status'    => $status,
            'payload'   => json_encode($payload, JSON_INVALID_UTF8_IGNORE),
            'state'     => json_encode($state, JSON_INVALID_UTF8_IGNORE),
            'updated_by'    => $this->user_id,
            'updated_on'    => date('Y/m/d H:i:s')
        );

        //filter
        $filter = array(
            'is_deleted'    => 0,
            'instance_id'   => $this->instance_id,
            'step'          => $step_name
        );

        $this->db->update('dbo_wf_instances_steps', $valuepair, $filter);

        return true;
    }

    private function finishStep($step_name, $step_to, $transition) {
        $state = $this->states[$step_name];
        $state['status'] = $self::STEP_COMPLETED;

        //update
        $valuepair = array(
            'status'    => $self::STEP_COMPLETED,
            'state'     => json_encode($state, JSON_INVALID_UTF8_IGNORE),
            'updated_by'    => $this->user_id,
            'updated_on'    => date('Y/m/d H:i:s')
        );

        //filter
        $filter = array(
            'is_deleted'    => 0,
            'instance_id'   => $this->instance_id,
            'step'          => $step_name
        );

        $this->db->update('dbo_wf_instances_steps', $valuepair, $filter);

        $this->states[$step_name] = $state;

        return true;
    }

    private function getStepStatus($step_name) {
        //filter
        $filter = array(
            'is_deleted'    => 0,
            'instance_id'   => $this->instance_id,
            'step'          => $step_name
        );

        $instance = $this->db->get_where('dbo_wf_instances_steps', $filter)->row_array();
        if ($instance == null)      return self::STEP_NOTREACHED;

        return $instance['status'];
    }

    private function initializeStepActions($step_name, $payload) {
        $steps = $this->getStepActionsConfig($step_name);

        foreach($steps as $key => $val) {
            $valuepair = array(
                'workflow_id'   => $this->workflow_id,
                'instance_id'   => $this->instance_id,
                'step'          => $step_name,
                'action'        => $val['action'],
                'action_type'   => $val['action_type'],
                'interval'      => $val['interval'],
                'repeat'        => $val['repeat'],
                'user_action'   => $val['user_action'],
                'user_messages' => $val['user_messages'],
                'user_token'    => $val['user_token'],
                'send_sms'      => $val['send_sms'],
                'send_email'    => $val['send_email'],
                'recipients'    => $val['recipients'],
                'editable_payload'   => $val['sendeditable_payload_email']
            );

            if ($val['user_token']) {
                //generate token
                $valuepair['token'] = generate_token(10);
            }

            if (!empty($val['user_messages'])) {
                //TODO: parse user messages
                //[[xx]]    -> user field (runtime replacement)
                //{{xx}}    -> payload field (init replacement)
                $valuepair['user_messages'] = $val['user_messages'];
            }

            if (!empty($val['recipients'])) {
                //TODO: parse recipients:
                //[[xx]]    -> user groups
                //{{xx}}    -> payload field
                $valuepair['recipients'] = $val['recipients'];
             }

            $valuepair['status'] = $self::ACTION_NOTSTARTED;

            //insert
            $this->db->insert('dbo_wf_instances_steps', $valuepair);
        }
    }

    private function getActiveActions($step_name) {
        $filter = array(
            'is_deleted'    => 0,
            'instance_id'   => $this->instance_id,
            'step'          => $step_name
        );

        $instances = $this->db->get_where("dbo_wf_instances_actions", $filter)->result_array();

        return $instance;
    }

    private function getActiveStates() {
        $this->db->where('is_deleted', 0);
        $this->db->where('instance_id', $this->instance_id);
        $this->db->group_start();
        $this->db->where('status', $self::STEP_REACHED);
        $this->db->or_where('status', $self::STEP_STARTED);
        $this->db->group_end();

        $instances = $this->db->get("dbo_wf_instances_steps")->result_array();

        return $instances;
    }

    private function terminateActiveStates($final_step_name) {
        //update
        $valuepair = array(
            'status'    => $self::STEP_TERMINATED,
            'updated_by'    => $this->user_id,
            'updated_on'    => date('Y/m/d H:i:s')
        );

        //filter
        $filter = array(
            'is_deleted'    => 0,
            'instance_id'   => $this->instance_id,
            'step!='        => $final_step_name
        );

        $this->db->group_start();
        $this->db->where('status', $self::STEP_REACHED);
        $this->db->or_where('status', $self::STEP_STARTED);
        $this->db->group_end();

        $this->db->update('dbo_wf_instances_steps', $valuepair, $filter);
    }

    private function getStepConfig($step_name) {
        $step = $this->db->get_where("dbo_wf_steps", array("name"=>$this->step_name, "workflow_id"=>$this->workflow_id, "is_deleted"=>0))->row_array();

        return $step;
    }

    private function getStepTransitionsConfig($step_name) {
        $transitions = $this->db->get_where("dbo_wf_transitions", array("step_from"=>$step_name, "workflow_id"=>$this->workflow_id, "is_deleted"=>0))->row_array();

        return $transitions;
    }

    private function getStepActionsConfig($step_name) {
        $steps = $this->db->get_where("dbo_wf_steps_actions", array("step"=>$step_name, "workflow_id"=>$this->workflow_id, "is_deleted"=>0))->row_array();

        return $steps;
    }

    private function getStartTransitionsConfig() {
        $transitions = $this->db->get_where("dbo_wf_transitions", array("step_from"=>null, "workflow_id"=>$this->workflow_id, "is_deleted"=>0))->row_array();

        return $transitions;
    }

    private function auditInitialize() {
        //TODO
    }

    private function auditStartup() {
        //TODO
    }

    private function auditTransition($step_name, $transition, $step_to, $payload) {
        //TODO
    }

    private function auditCompletion($final_step_name) {
        //TODO
    }

    private function auditStep($step_name, $status) {
        //TODO
    }

}

?>