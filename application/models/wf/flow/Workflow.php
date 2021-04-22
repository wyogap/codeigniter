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

            $this->entity = isset($this->params['entity']) ? $this->params['entity'] : '';
            $this->entity_id = isset($this->params['entity_id']) ? $this->params['entity_id'] : 0;

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

            if (empty($this->entity) || empty($this->entity_id)) {
                break;
            }  

            if ($this->instance == null) {
                $this->instance = $this->_getWorkflowInstanceByEntity($this->workflow_id, $this->entity, $this->entity_id);
            }

            if ($this->instance == null) {
                break;
            }

            $this->instance_id = $this->instance['id'];
    
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
                $this->instance = $this->_initializeInstance($this->workflow_id, $this->entity, $this->entity_id);
            }

            if ($this->instance == null) {
                return false;
            }

            $this->instance_id = $this->instance['id'];
        }

        $this->steps = $this->_getActiveSteps();
        if (count($this->steps)) {
            //already started
            foreach($this->steps as $key => $step) {
                if ($this->status == Workflow::WF_FINISHED) {
                    break;
                }

                //simulate a transit-in
                $step->transitIn();
            }

            return true;
        }

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

        return $started;
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

    private function _initializeInstance($workflow_id, $entity, $entity_id) {
        $valuepair = array (
            'workflow_id'   => $workflow_id,
            'entity'        => $entity,
            'entity_id'     => $entity_id,
            'status'        => Workflow::WF_NOTSTARTED,
            'created_on'    => date('Y/m/d H:i:s'),
            'created_by'    => $this->user_id
        );

        $query = $this->db->insert("dbo_wf_instances", $valuepair);
        if ($query) {
            $id = $this->db->insert_id();

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

        //get payload
        $payload = $this->_getEntity($this->entity, $this->entity_id);

        $transitions = array();
        foreach($configs as $key => $val) {
            $transition = new Transition($this, $val, $payload, $null);
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
            'workflow_id', 'name', 'label', 'entity', 'entity_id'
        );

        $values = array(
            $this->workflow_id, $this->name, $this->label, $this->entity, $this->entity_id
        );

        audittrail_trail('workflow', $this->instance_id, 'START', 'Workflow start-up', $keys, $values);
    }

    private function auditCompletion($final_step_name) {
        $keys = array(
            'workflow_id', 'name', 'label', 'entity', 'entity_id', 'step'
        );

        $values = array(
            $this->workflow_id, $this->name, $this->label, $this->entity, $this->entity_id, $final_step_name
        );

        audittrail_trail('workflow', $this->instance_id, 'FINISH', 'Workflow completion', $keys, $values);
    }

}

?>