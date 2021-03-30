
<div class="table-responsive-sm">
    <table id="{$tbl.table_id}" class="table table-striped dt-responsive nowrap" width="100%">
        <thead>
            <tr>
                {if $tbl.row_select_column}
                <th class="text-center" data-priority="1">#</th>
                {/if}
                {if $tbl.row_id_column}
                <th class="text-center" data-priority="1">#</th>
                {/if}
                {foreach from=$tbl.columns key=i item=col}
                    {if $col.visible == 1}
                    <th class="{if $col.data_priority < 0} none {else} {$col.css} {/if} text-center" data-priority="{$col.data_priority}" style="word-break: normal!important;">
                    {if isset($col.edit_bubble) && $col.edit_bubble}<i class="dripicons-document-edit"></i> {/if}
                    {$col.label}
                    {/if}
                {/foreach}
                {if count($tbl.row_actions) > 0}
                <th class="text-center" data-priority="1"></th>
                {/if}
            </tr>
        </thead>
    </table>
</div>




