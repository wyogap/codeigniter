
{if empty($fsubtable)}
{assign var=fsubtable value=0}
{/if}

{if empty($fkey)}
{assign var=fkey value=0}
{/if}



<div class="table-responsive-sm">
    <table id="{$tbl.table_id}" class="table table-striped dt-responsive nowrap" width="100%">
        <thead>
            <tr>
                {if $tbl.row_select_column || $tbl.inline_edit}
                <th class="text-center dt-col-select" data-priority="1">
                <input class="dt-select-checkbox" type="checkbox" aria-label="Select all rows">
                </th>
                {/if}
                {if $tbl.row_id_column}
                <th class="text-center" data-priority="1">#
                </th>
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
                {if $tbl.row_select_column || $tbl.inline_edit}
                <th class="text-center" data-priority="1"></th>
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
            <div class="form-field {$col.edit_css}" data-editor-template="{$col.name}"></div>
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
        <div class="form-field {$col.editor.edit_css}" data-editor-template="{$col.editor.name}"></div>
        {/if}
        {/foreach}
    </div>
</div>
{/if}
{/if}
