<div class="tabbable">

    <ul class="nav nav-pills nav-justified">
        {assign var=is_active value=true}
        {var_dump($subtables)}
        {foreach $subtables as $subtbl}
        <li class="nav-item{if !empty($subtbl.css)} {$subtbl.css}{/if}">
            <a class="nav-link {if $is_active}active{/if}" href="#pane_{$subtbl.subtable_id}" data-toggle="tab">{$subtbl.label}</a>
        </li>
        {assign var=is_active value=false}
        {/foreach}
    </ul>
    <div class="tab-content" style="margin-top: 16px;">
        {assign var=is_active value=true}
        {foreach $subtables as $subtbl}
        <div id="pane_{$subtbl.subtable_id}" class="tab-pane{if $is_active} active{/if}{if !empty($subtbl.css)} {$subtbl.css}{/if}">
            <div class="row" style="flex-grow: 1;"><div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
            {include file='crud/crud-table.tpl' tbl=$subtbl.crud fsubtable='1' fkey=$subtbl.subtable_fkey_column flabel=$subtbl.label}
                    </div>
                </div>
            </div></div>
        </div>
        {assign var=is_active value=false}
        {/foreach}
    </div>
    <!-- /.tab-content -->
</div>
<!-- /.tabbable -->