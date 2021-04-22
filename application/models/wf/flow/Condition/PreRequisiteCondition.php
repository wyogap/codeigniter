<?php 

declare(strict_types=1);

namespace Tcg\Workflow\Flow\Condition;

class PreRequisiteCondition implements Condition
{
    private $step;
 
    private $workflow_id;
    private $instance_id;

    private $prereq;

    public function __construct(Step $step, $prereq)
    {
        $this->step = $step;
        $this->prereq = $prereq;

        $this->workflow_id = $step->getWorkflowId();
        $this->instance_id = $step->getInstanceId();

    }

    public function match() {
        $step_name = $this->step->getName();

        $filter = array (
            'instance_id'   => $this->instance_id,
            'step'          => $step_name,
            'is_deleted'    => 0
        );

        $instance = $this->db->get_where("dbo_wf_instances_steps", $filter)->row_array();

        return ($instance['status'] == Step::STEP_COMPLETED);
    }

}

?>