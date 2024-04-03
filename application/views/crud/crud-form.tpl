<style>
    .editor-layout div.tab-pane.active {
        display: flex;
        flex-wrap: wrap;
    }

    .editor-layout div.tab-pane > .form-group {
        flex: 0 0 100%;
    }
</style>

{if !isset($form_mode)}
    {assign var='form_mode' value='detail'}
{/if}

<div class="crud-form" id="{$tbl.table_id}_form" data-table-id="{$tbl.table_id}" {if $detail}data-id="{$detail[$tbl.key_column]}"{/if}>

{if $form_mode=='detail' && !empty($tbl.detail_template)}
<div id="{$tbl.table_id}-detail-layout">
    {$tbl.detail_template}
</div>
{elseif $form_mode=='edit' && !empty($tbl.edit_template)}
<div id="{$tbl.table_id}-editor-layout">
    {$tbl.editor_template}
</div>
{elseif count($tbl.column_groupings) > 1}
<div id="{if $form_mode=='edit'}{$tbl.table_id}-editor-layout{else}{$tbl.table_id}-detail-layout{/if}" class="editor-layout">
    <ul class="nav nav-pills nav-justified" id="{$tbl.table_id}-editor-tabs">
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
            <div class="row" style="flex-grow: 1;"><div class="col-12">
            <div class="card widget-inline">
                <div class="card-body row">
            {foreach from=$grp.editors key=j item=col}
            <div class="form-field col-12 {$col.edit_css}" data-editor-template="{$col.name}"></div>
            {/foreach}
                </div>
            </div>
            </div></div>
        </div>
        {/foreach}
    </div>
</div>
{else}
<div id="{if $form_mode=='edit'}{$tbl.table_id}-editor-layout{else}{$tbl.table_id}-detail-layout{/if}" class="row" style="flex-grow: 1;"><div class="col-12">
<div class="card widget-inline">
    <div class="card-body row">
    {if !empty($level1_column)}
        <div class="form-field d-none" data-editor-template="{$level1_column}"></div>
    {/if}
    {foreach $tbl.columns as $col}
        {if empty($col.editor)}{continue}{/if}
        {$col=$col.editor}
        <div class="form-field col-12 {$col.edit_css}" data-editor-template="{$col.name}"></div>
    {/foreach}
    </div>
</div>
</div></div>
{/if}

</div>
