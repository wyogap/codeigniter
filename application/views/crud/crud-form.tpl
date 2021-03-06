<style>
    .editor-layout div.tab-pane.active {
        display: flex;
        flex-wrap: wrap;
    }

    .editor-layout div.tab-pane > .form-group {
        flex: 0 0 100%;
    }
</style>

<div class="crud-form" id="{$tbl.table_id}_form" data-table-id="{$tbl.table_id}" {if $detail}data-id="{$detail[$tbl.key_column]}"{/if}>

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

</div>
