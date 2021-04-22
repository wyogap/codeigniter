<?php 

declare(strict_types=1);

namespace Tcg\Workflow\Flow\Condition;

interface Condition
{
    /**
     * Consider if workflow matches to the entity.
     *
     * @param Workflow $workflow The current workflow.
     * @param EntityId $entityId The entity id.
     * @param mixed    $entity   The entity.
     *
     * @return bool
     */
    public function match(): bool;
}

?>