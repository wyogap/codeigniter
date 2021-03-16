
<div class="table-responsive-sm">
    <table id="{$crud.table_id}" class="table table-striped dt-responsive nowrap" width="100%">
        <thead>
            <tr>
                {if $crud.row_select_column}
                <th class="text-center" data-priority="1">#</th>
                {/if}
                {if $crud.row_id_column}
                <th class="text-center" data-priority="1">#</th>
                {/if}
                {foreach from=$crud.columns key=i item=col}
                    {if $col.visible == 1}
                    <th class="{if $col.data_priority < 0} none {else} {$col.css} {/if} text-center" data-priority="{$col.data_priority}">
                    {if isset($col.edit_bubble) && $col.edit_bubble}<i class="dripicons-document-edit"></i> {/if}
                    {$col.label}
                    {/if}
                {/foreach}
                {if count($crud.row_actions) > 0}
                <th class="text-center" data-priority="2"></th>
                {/if}
            </tr>
        </thead>
    </table>
</div>

{if count($crud.custom_actions) > 0}
<div id='dt-custom-actions' class="dt-buttons btn-group dt-custom-actions" data-table-id="{$crud.table_id}" style='display: inline-grid; width: fit-content;'> 
    {foreach $crud.custom_actions as $x}
    <button class="btn btn-sm {$x.css}" tabindex="0" aria-controls="tdata" type="button" onclick="{$x.js}();"><span>{__($x.label)}</span></button>
    {/foreach}
</div>
{/if}



