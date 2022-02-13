
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
                    {if $col.visible != 1}
                        {continue}
                    {/if}
                    {* Hide reference column when displaying as subtable *}
                    {if (!empty($fkey) && $fkey == $col.name)}
                        {continue}
                    {/if}
                    {* Hide virtual column *}
                    {if $col.type=="virtual" || $col.type=="tcg_table"}
                        {continue}
                    {/if}
                    <th class="{if $col.data_priority < 0}none {else if $col.css}{$col.css} {/if}text-center" data-priority="{$col.data_priority}" style="word-break: normal!important;">
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
    </table>
</div>

{if count($tbl.column_groupings) > 1}
<div id="{$tbl.table_id}-editor-layout" class="editor-layout">
    <ul class="nav nav-tabs nav-justified" id="{$tbl.table_id}-editor-tabs">
        {foreach from=$tbl.column_groupings key=i item=grp}
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
        <div class="tab-pane {if $i==0}active{/if}" id="{$tbl.table_id}-{$grp.id}">
            {foreach from=$grp.columns key=j item=col}
            <div class="form-group {$col.edit_css}" data-editor-template="{$col.name}"></div>
            {/foreach}
        </div>
        {/foreach}
    </div>
</div>
{/if}
