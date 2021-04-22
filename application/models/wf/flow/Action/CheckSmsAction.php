<?php 

declare(strict_types=1);

namespace Tcg\Workflow\Flow\Action;

class CheckSmsAction extends Action
{
    public function execute(): bool
    {
        //TODO: do the actual thing
        return false;
        
        //mark unread sms for reading
        $filter = array(
            "is_read" => 0,
            "is_deleted" => 0
        );

        $valuepair = array(
            "is_read" => -1
        );

        $query = $this->db->update($valuepair, $filter);

        //get the entry
        
        return parent::execute();
    }


}

?>