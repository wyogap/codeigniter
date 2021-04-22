<?php 

declare(strict_types=1);

namespace Tcg\Workflow\Flow;

class Base extends CI_Model
{
    private $workflow_id;
    private $instance_id;

    public function getWorkflowId() {
        return $this->workflow_id;
    }

    public function getInstanceId() {
        return $this->instance_id;
    }

}

?>