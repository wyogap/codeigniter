<?php 

declare(strict_types=1);

namespace Tcg\Workflow\Flow;

class Workflow extends CI_Model
{
    protected const WF_NOTSTARTED = 0;
    protected const WF_STARTED = 1;
    protected const WF_FINISHED = 99;

    private $name;
    private $label;

    private $workflow_id;
    private $instance_id;

    private $entity;
    private $entity_id;

    private $status = Workflow::WF_NOTSTARTED;
    
    protected $user_id;
    protected $role_id;

    /**
     * Parameters values.
     *
     * @var array
     */
    protected $params = array();

    /**
     * Active steps.
     *
     * @var string
     */
    private $steps = array();

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
        $this->instance_id = 0;

        // //get db instance
        // $ci &= get_instance();
        // $this->db     = $ci->db; 

        //get current user id
        $this->user_id  = $this->session->userdata('user_id');
        $this->role_id  = $this->session->userdata('role_id');

        do {
            //load config
            $this->config = $this->_getWorkflowConfig($this->name);
            if ($this->config == null) {
                break;
            }

            $this->label = $this->config['label'];
            $this->workflow_id = $this->config['id'];

            //load current state
            if ($instance_id > 0) {   
                $this->instance = $this->_getWorkflowInstance($instance_id);

                if ($this->instance != null) {
                    $this->instance_id  = $instance_id;
                    //override from db value
                    $this->entity = $this->instance['entity'];
                    $this->entity_id = $this->instance['entity_id'];
                }
            }

            if ($this->instance == null) {
                $this->entity = $this->params['entity'] ?? '';
                $this->entity_id = $this->params['entity_id'] ?? 0;
    
                if (empty($this->entity) || empty($this->entity_id)) {
                    break;
                }  
    
                $this->instance = $this->_getWorkflowInstanceByEntity($this->workflow_id, $this->entity, $this->entity_id);
            }

            if ($this->instance == null) {
                break;
            }

            $this->instance_id = $this->instance['id'];

            $this->entity = $this->instance['entity'];
            $this->entity_id = $this->instance['entity_id'];

            if ($this->instance['states'] != null) {
                $this->states = json_decode($this->instance['states']);
            }
            else {
                $this->states = array();
            }
            
            if ($this->instance['payload'] != null) {
                $this->payload = json_decode($this->instance['payload']);
            }
            else {
                $this->payload = array();
            }

        } while(false);

        //DO NOT automatically execute wf

    }

    public function execute(array $params = array()) {

        if ($this->config == null) {
            return false;
        }

        //check for existing instance
        if ($this->instance == null) {
            //store params
            $this->params = array_merge($this->params, $param);

            //check for existing wf for this entity and entity-id
            $this->entity = isset($this->params['entity']) ? $this->params['entity'] : '';
            $this->entity_id = isset($this->params['entity_id']) ? $this->params['entity_id'] : 0;

            if (empty($this->entity) || empty($this->entity_id)) {
                return false;
            }

            //get existing wf if any
            $this->instance = $this->_getWorkflowInstance($this->workflow_id, $this->entity, $this->entity_id);

            //no existing, init one
            if ($this->instance == null) {
                $this->instance = $this->_initializeInstance($this->workflow_id, $this->entity, $this->entity_id, $this->params);
            }

            if ($this->instance == null) {
                return false;
            }

            $this->instance_id = $this->instance['id'];
            $this->status = $this->instance['status'];

            if ($this->instance['states'] != null) {
                $this->states = json_decode($this->instance['states']);
            }
            else {
                $this->states = array();
            }
            
            if ($this->instance['payload'] != null) {
                $this->payload = json_decode($this->instance['payload']);
            }
            else {
                $this->payload = array();
            }
        }

        if ($this->status == Workflow::WF_NOTSTARTED) {
            //not started. start it up
            $this->_updateInstance(Workflow::WF_STARTED);

            //audit trail
            $this->auditStartup();

            //execute start transitions
            $transitions = $this->_getStartTransitions();

            $started = false;
            foreach($transitions as $key => $transition) {
                if ($this->status == Workflow::WF_FINISHED) {
                    $started = false;
                    break;
                }
                    
                $transit = $transition->execute();

                if ($transit) {
                    $started = true;
                }
            }

            if ($started == false) {
                return false;
            }
        }

        $this->steps = $this->_getActiveSteps();
        foreach($this->steps as $key => $step) {
            if ($this->status == Workflow::WF_FINISHED) {
                break;
            }

            //simulate a transit-in
            $step->transitIn();
        }

        return true;
    }

    public function updatePayload(array $payload, $token) {
        if ($payload == null || count($payload) == 0) {
            return false;
        }

        //make sure there is edit-payload action in currently active steps
        $action = $this->_getEditPayloadAction($token);
        if ($action == null) {
            return false;
        }

        $this->paylod = $payload;

        //TODO: update step level payload

        return true;
    }

    public function isValidInstance() {
        return ($this->instance_id > 0);
    }

    public function getWorkflowId() {
        return $this->workflow_id;
    }

    public function getInstanceId() {
        return $this->instance_id;
    }

    public function getParams() {
        return $this->params;
    }

    public function getEntity() {
        return $this->entity;
    }

    public function getEntityId() {
        return $this->entity_id;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getPayload($token) {
        //make sure there is edit-payload action in currently active steps
        $action = $this->_getEditPayloadAction($token);
        if ($action == null) {
            return false;
        }

        //TODO: get payload in step level in case there are branching
        return $this->payload;
    }

    public function getStates() {
        return $this->states;
    }

    public function terminate($final_step_name) {
        $this->_terminateActiveSteps($final_step_name);

        $this->_updateInstance(Workflow::WF_FINISHED);

        //audit trail
        $this->auditCompletion($final_step_name);
    }
    
    private function _getWorkflowConfig($name) {
        //get workflow config
        return $this->db->get_where("dbo_wf_workflows", array("name"=>name, "is_deleted"=>0))->row_array();
    }

    private function _getWorkflowInstance($instance_id) {
        $filter = array (
            'instance_id'   => $instance_id,
            'status!='      => $Workflow::WF_FINISHED,
            'is_deleted'    => 0
        );

        return $this->db->get_where("dbo_wf_instances", $filter)->row_array();
    }

    private function _getWorkflowInstanceByEntity($workflow_id, $entity, $entity_id) {
        $filter = array (
            'workflow_id'   => $workflow_id,
            'entity'        => $entity,
            'entity_id'     => $entity_id,
            'status!='      => Workflow::WF_FINISHED,
            'is_deleted'    => 0
        );

        return $this->db->get_where("dbo_wf_instances", $filter)->row_array();
    }

    private function _initializeInstance($workflow_id, $entity, $entity_id, $params) {
        $payload = null;

        //initial states from params
        $states = $params;

        $valuepair = array (
            'workflow_id'   => $workflow_id,
            'entity'        => $entity,
            'entity_id'     => $entity_id,
            'status'        => Workflow::WF_NOTSTARTED,
            'created_on'    => date('Y/m/d H:i:s'),
            'created_by'    => $this->user_id
        );

        if ($params != null && count($params)>0) {
            $valuepair['params'] = json_encode($params, JSON_INVALID_UTF8_IGNORE);
        }

        if ($states != null && count($states)>0) {
            $valuepair['states'] = json_encode($states, JSON_INVALID_UTF8_IGNORE);
        }

        $query = $this->db->insert("dbo_wf_instances", $valuepair);
        if ($query) {
            $id = $this->db->insert_id();

            //copy wf steps and wf step actions
            $this->_initializeInstanceSteps($workflow_id, $id);
            $this->_initializeInstanceActions($workflow_id, $id);

            return $this->_getWorkflowInstance($id);
        }

        return null;
    }

    private function _updateInstance($status) {
        $filter = array(
            'id'            => $this->instance_id,
            'is_deleted'    => 0
        );

        $valuepair = array(
            'status'     => $status,
            'updated_on' => date('Y/m/d H:i:s'),
            'updated_by' => $this->user_id

        );

        if ($status == Workflow::WF_STARTED) {
            //get payload
            $this->payload = $this->_getEntity($this->entity, $this->entity_id);
            $valuepair['payload'] = json_encode($this->payload, JSON_INVALID_UTF8_IGNORE);
        }

        $this->db->update("dbo_wf_instances", $valuepair, $filter);   

        $this->status = $status;
    }

    private function _getActiveSteps() {
        $this->db->where('is_deleted', 0);
        $this->db->where('instance_id', $this->instance_id);
        $this->db->group_start();
        $this->db->where('status', Step::STEP_REACHED);
        $this->db->or_where('status', Step::STEP_STARTED);
        $this->db->group_end();

        $instances = $this->db->get("dbo_wf_instances_steps")->result_array();
        if ($instances == null)     return array();

        $steps = array();
        foreach($instances as $key => $val) {
            $step = new Step($this, $val);
            $steps[] = $step;
        }

        return $steps;
    }

    private function _getStartTransitions() {
        $configs = $this->db->get_where("dbo_wf_transitions", array("step_from"=>null, "workflow_id"=>$this->workflow_id, "is_deleted"=>0))->row_array();
        if ($configs == null)     return array();

        $transitions = array();
        foreach($configs as $key => $val) {
            $transition = new Transition($this, $val, $this->payload, $null);
            $transitions[] = $transition;
        }

        return $transitions;
    }

    private function _getEntity($entity, $entity_id) {
        $filter = array(
            'id'    => $entity_id,
            'is_deleted'    => 0
        );

        return $this->db->get_where($entity, $filter)->row_array();
    }

    private function _terminateActiveSteps($final_step_name) {
        //update
        $valuepair = array(
            'status'    => $self::STEP_TERMINATED,
            'updated_by'    => $this->user_id,
            'updated_on'    => date('Y/m/d H:i:s')
        );

        //filter
        $filter = array(
            'is_deleted'    => 0,
            'instance_id'   => $this->instance_id
        );

        if (!empty($final_step_name)) {
            $this->db->where('step!=', $final_step_name);
        }

        $this->db->group_start();
        $this->db->where('status', Step::STEP_REACHED);
        $this->db->or_where('status', Step::STEP_STARTED);
        $this->db->group_end();

        $this->db->update('dbo_wf_instances_steps', $valuepair, $filter);
    }

    private function auditStartup() {
        $keys = array(
            'workflow_id', 'name', 'label', 'entity', 'entity_id', 'states', 'payload'
        );

        $states = json_encode($this->states, JSON_INVALID_UTF8_IGNORE);
        $payload = json_encode($this->payload, JSON_INVALID_UTF8_IGNORE);

        $values = array(
            $this->workflow_id, $this->name, $this->label, $this->entity, $this->entity_id, $states, $payload
        );

        audittrail_trail('workflow', $this->instance_id, 'START', 'Workflow start-up', $keys, $values);
    }

    private function auditCompletion($final_step_name) {
        $keys = array(
            'workflow_id', 'name', 'label', 'entity', 'entity_id', 'step', 'states', 'payload'
        );

        $states = json_encode($this->states, JSON_INVALID_UTF8_IGNORE);
        $payload = json_encode($this->payload, JSON_INVALID_UTF8_IGNORE);

        $values = array(
            $this->workflow_id, $this->name, $this->label, $this->entity, $this->entity_id, $final_step_name, $states, $payload
        );

        audittrail_trail('workflow', $this->instance_id, 'FINISH', 'Workflow completion', $keys, $values);
    }

    private function _initializeInstanceSteps($workflow_id, $instance_id) {
        $sql = "
            INSERT INTO `dbo_wf_instances_steps`
            (
                `workflow_id`,
                `step_id`,
                `instance_id`,
                `step`,
                `is_final`,
                `status`,
                `role_id`,
                `user_group`,
                `created_on`,
                `created_by`
            )
            select 
                workflow_id,
                id as step_id,
                ? as instance_id,
                `name` as step,
                is_final,
                0 as status,
                role_id,
                user_group,
                now() as created_on,
                ? as created_by
            from dbo_wf_steps
            where   
                workflow_id = ? and is_deleted=0
            order by id
        ";

        $this->db->query($sql, array($instance_id, $this->user_id, $workflow_id));

        return true;
    }

    private function _initializeInstanceActions($workflow_id, $instance_id) {
        $sql = "
            INSERT INTO dbo_wf_instances_actions
            (
                `workflow_id`,
                `step_id`,
                `action_id`,
                `instance_id`,
                `step`,
                `name`,
                `action`,
                `type`,
                `interval`,
                `interval_type`,
                `repeat`,
                `state_condition`,
                `payload_condition`,
                `transform_payload`,
                `transform_state`,
                `recipients`,
                `user_messages`,
                `user_action`,
                `readable_payload`,
                `editable_payload`,
                `order_no`,
                `created_on`,
                `created_by`
            )
            select 
                a.workflow_id,
                a.id as step_id,
                b.id as action_id,
                ? as instance_id,
                b.`step`,
                b.`name`,
                b.`action`,
                b.`type`,
                b.`interval`,
                b.`interval_type`,
                b.`repeat`,
                b.`state_condition`,
                b.`payload_condition`,
                b.`transform_payload`,
                b.`transform_state`,
                b.`recipients`,
                b.`user_messages`,
                b.`user_action`,
                b.`readable_payload`,
                b.`editable_payload`,
                b.`order_no`,
                now() as created_on,
                ? as created_by
            from dbo_wf_steps a
            join dbo_wf_steps_actions b on b.workflow_id=a.workflow_id and b.step=a.name and b.is_deleted=0
            where   
                a.workflow_id = ? and a.is_deleted=0
            order by a.id, b.id        
        ";

        $this->db->query($sql, array($instance_id, $this->user_id, $workflow_id));

        return true;
    }

    private function _finishInstance() {
        //TODO: write payload value back and update any status
    }

    private function _getEditPayloadAction($token=null) {
        //TODO: use token to identify the correct action (in case there are multiple instance of the same actions)
        $sql = "
            select 
                a.workflow_id, 
                a.id as instance_step_id, 
                b.id as instance_action_id, 
                a.step, 
                b.`name` as `action` 
            from dbo_wf_instances_steps a
            join dbo_wf_instances_actions b 
                on b.instance_id=a.instance_id and b.step=a.step and b.is_deleted=0
            where a.is_deleted=0 
                and a.workflow_id=? and a.instance_id=? 
                and (a.status=1 or a.status=2) and a.is_deleted=0
                and b.action='edit-payload';
        ";

        return $this->db->query($sql, array($this->workflow_id, $this->instance_id))->row_array();

    }
}

?>