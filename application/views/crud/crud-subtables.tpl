<div class="tabbable">

    <ul class="nav nav-tabs mb-3">
        {assign var=is_active value=true}
        {foreach $subtables as $subtbl}
        <li class="nav-item">
            <a class="nav-link {if $is_active}active{/if}" href="#pane_{$subtbl.subtable_id}" data-toggle="tab">{$subtbl.label}</a>
        </li>
        {assign var=is_active value=false}
        {/foreach}
    </ul>
    <div class="tab-content">
        {assign var=is_active value=true}
        {foreach $subtables as $subtbl}
        <div id="pane_{$subtbl.subtable_id}" class="tab-pane {if $is_active}active{/if}">
            {include file='crud/crud-table.tpl' tbl=$subtbl.crud fsubtable='1' fkey=$subtbl.subtable_fkey_column}
        </div>
        {assign var=is_active value=false}
        {/foreach}
    </div>
    <!-- /.tab-content -->
</div>
<!-- /.tabbable -->