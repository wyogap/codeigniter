
{if empty($fsubtable)}
{assign var=fsubtable value=0}
{/if}

{if empty($fkey)}
{assign var=fkey value=0}
{/if}

<style>
    .dataTable tbody tr:hover {
        background-color: #48a4f3 !important;
        color: white;
    }

    .dataTable tbody tr:hover > .sorting_1 {
        background-color: #48a4f3 !important;
        color: white;
    }

    .dataTable tbody tr.selected:hover {
        background-color: #0275d8 !important;
        color: white;
    }

    .dataTable tbody tr.selected:hover > .sorting_1 {
        background-color: #0275d8 !important;
        color: white;
    }
    
    .dataTable > tbody > tr.child:hover {
        color: black !important;
    }

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
        padding-right: 4px !important;
    }
    {/if}

    .editor-layout div.tab-pane.active {
        display: flex;
        flex-wrap: wrap;
    }

    .editor-layout div.tab-pane > .form-group {
        flex: 0 0 100%;
    }

    thead input {
        width: 100%;
    }

    /* so that any custom css will still align properly */
    .DTE .form-group {
        padding-left: 0px;
        padding-left: 0px;
    }

    @media (min-width: 992px) {
        /*  
         * .dt-horizontal-2x : full horizontal field, label and field in one row, adjusted for 2 field per row
        **/
        .dt-horizontal-2x label {
            -ms-flex: 0 0 16.666667% !important;
            flex: 0 0 16.666667% !important;
            max-width: 16.666667% !important;        
        }

        .dt-horizontal-2x div[data-dte-e="input"] {
            -ms-flex: 0 0 83.333333% !important;
            flex: 0 0 83.333333% !important;
            max-width: 83.333333% !important;    
            margin-right: -7.5px;
            margin-left: -7.5px;    
        }

        /*  
         * .dt-vertical : full horizontal field, input in the next row
        **/
        .DTE .form-group.dt-vertical label {  
            flex: 0 0 100%;
            max-width: 100%;
        }

        .DTE .form-group.dt-vertical div[data-dte-e="input"] {  
            flex: 0 0 100%;
            max-width: 100%;
        }

        /* Hack: since the input field makes most of the top padding, when the input field is under the label the top margin needs to be compensated. */
        .dt-vertical {
            margin-top: -1em;
        }

        /*  
         * .dt-horizontal-6 : half row field, but empty space in the next space
        **/
        .dt-horizontal-6 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }

        .dt-horizontal-6 .form-group {
            max-width: 51%;
        }

    }

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
                    {* Hide virtual column *}
                    {if $col.type=="virtual" || $col.type=="tcg_table"}
                        {continue}
                    {/if}
                    {* Hide reference column when displaying as subtable *}
                    {if (!empty($fkey) && $fkey == $col.name) || $col.visible != 1}
                        {$col.data_priority = -1}
                    {/if}
                    <th {if !empty($col.column_filter)}tcg-column-filter=1{/if} class="{if $col.data_priority < 0}none {else if $col.css}{$col.css} {/if}text-center" data-priority="{$col.data_priority}" style="word-break: normal!important;">
                    {if isset($col.edit_bubble) && $col.edit_bubble}<i class="dripicons-document-edit"></i> {/if}{$col.label}
                    </th>
                {/foreach}
                {if count($tbl.row_actions) > 0}
                <th class="text-center" data-priority="1"></th>
                {/if}
                {if $tbl.row_reorder}
                <th class="text-center" data-priority="1"></th>
                {/if}
            </tr>
        </thead>
        {if $tbl.footer_row}
        <tfoot>
            <tr>
                {if $tbl.row_select_column}
                <th class="text-center" data-priority="1">#</th>
                {/if}
                {if $tbl.row_id_column}
                <th class="text-center" data-priority="1">#</th>
                {/if}
                {foreach from=$tbl.columns key=i item=col}
                    {* Hide virtual column *}
                    {if $col.type=="virtual" || $col.type=="tcg_table"}
                        {continue}
                    {/if}
                    {* Hide reference column when displaying as subtable *}
                    {if (!empty($fkey) && $fkey == $col.name) || $col.visible != 1}
                        {$col.data_priority = -1}
                    {/if}
                    <th class="{$col.css} {if $col.name == $tbl.lookup_column}text-left{/if}" style="word-break: normal!important;">
                    {if $col.name == $tbl.lookup_column}Total
                    {elseif $col.total_row}-
                    {/if}
                    </th>
                {/foreach}
                {if count($tbl.row_actions) > 0}
                <th class="text-center" data-priority="1"></th>
                {/if}
                {if $tbl.row_reorder}
                <th class="text-center" data-priority="1"></th>
                {/if}
            </tr>
        </tfoot>
        {/if}
    </table>
</div>

{if !empty($tbl.editor)}
{if count($tbl.column_groupings) > 1}
<div id="{$tbl.table_id}-editor-layout" class="editor-layout">
    <ul class="nav nav-pills nav-justified" id="{$tbl.table_id}-editor-tabs">
        {foreach from=$tbl.column_groupings key=i item=grp}
        {if empty($grp.editors)} {continue} {/if}
        <li class="nav-item">
            <a class="nav-link {if $i==0}active{/if}" href="#{$tbl.table_id}-{$grp.id}" data-toggle="tab">
            {if !empty($grp.icon)}<i class="{$grp.icon}"></i>{/if}
            {if !$grp.icon_only}{$grp.label}{/if}
            </a>
        </li>
        {/foreach}
    </ul>
    <div class="tab-content" style="margin-top: 16px;">
        {foreach from=$tbl.column_groupings key=i item=grp}
        {if empty($grp.editors)} {continue} {/if}
        <div class="tab-pane {if $i==0}active{/if}" id="{$tbl.table_id}-{$grp.id}">
            {foreach from=$grp.editors key=j item=col}
            <div class="form-group {$col.edit_css}" data-editor-template="{$col.name}"></div>
            {/foreach}
        </div>
        {/foreach}
    </div>
</div>
{else}
<div id="{$tbl.table_id}-editor-layout" class="editor-layout">
    <div class="tab-pane active" id="{$tbl.table_id}-1">
        {foreach from=$tbl.columns key=j item=col}
        {if !empty($col.editor)}
        <div class="form-group {$col.editor.edit_css}" data-editor-template="{$col.editor.name}"></div>
        {/if}
        {/foreach}
    </div>
</div>
{/if}
{/if}
