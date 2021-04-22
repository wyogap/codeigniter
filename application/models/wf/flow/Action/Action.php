<?php 

declare(strict_types=1);

namespace Tcg\Workflow\Flow\Action;

use Tcg\Workflow\Flow;

class Action extends CI_Model
{
    public const ACTION_NOTSTARTED = 0;
    public const ACTION_ACTIVE = 1;
    public const ACTION_COMPLETED = 99;

    private $step;
    private $instance;

    public function __construct(Step $step, array $instance) {
        $this->step = $step;
        $this->instance = $instance;
    }

    public function execute(): bool
    {
        $step_name = $this->step->getName();
        $payload = $this->step->getPayload();
        $state = $this->step->getState();
        $action_name = $this->instance['name'];

        $this->auditAction($step_name, $action_name, $payload, $state);

        return true;
    }

    protected function parseMessage($template, $payload, $state) {
        //TODO
        return $template;
    }

    private function auditAction($step_name, $action, $payload, $state) {

        $keys = array(
            'workflow_id', 'name', 'label', 'entity', 'entity_id', 'step', 'action', 'payload', 'state'
        );

        $values = array(
            $this->workflow_id, $this->name, $this->label, $this->entity, $this->entity_id, $step_name, $action, json_encode($payload, JSON_INVALID_UTF8_IGNORE),json_encode($state, JSON_INVALID_UTF8_IGNORE),
        );

        audittrail_trail('workflow', $this->instance_id, 'ACTION', 'Workflow action: [' .$step_name. "]->" .$action, $keys, $values);
    }


}

?>