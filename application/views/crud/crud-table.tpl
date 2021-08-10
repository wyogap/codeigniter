
{if empty($fsubtable)}
{assign var=fsubtable value=0}
{/if}

{if empty($fkey)}
{assign var=fkey value=0}
{/if}

<style>
    {if $tbl.custom_css}
    {$tbl.custom_css}
    {/if}

    {if $tbl.row_reorder}
    td.reorder {
        position: relative;
    }

    td.reorder:after {  
        top: 16px;
        right: 8px;
        height: 14px;
        width: 14px;
        display: block;
        position: absolute;
        text-align: center;
        text-indent: 0 !important;
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        line-height: 14px;
        content: '\f0b2';
    }

    td.inline-actions {
        padding-right: 0px !important;
    }
    {/if}
</style>

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
                    {if (!empty($fkey) && $fkey == $col.name)}
                        {continue}
                    {/if}
                    {if $col.visible == 1}
                    <th class="{if $col.data_priority < 0}none {else if $col.css}{$col.css} {/if}text-center" data-priority="{$col.data_priority}" style="word-break: normal!important;">
                    {if isset($col.edit_bubble) && $col.edit_bubble}<i class="dripicons-document-edit"></i> {/if}{$col.label}
                    </th>
                    {/if}
                {/foreach}
                {if count($tbl.row_actions) > 0}
                <th class="text-center" data-priority="1"></th>
                {/if}
                {if $tbl.row_reorder}
                <th class="text-center" data-priority="1"></th>
                {/if}
            </tr>
        </thead>
    </table>
</div>




