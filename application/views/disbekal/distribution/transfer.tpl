{include file="crud/_css.tpl"}

<style>
/** OPTION DROPDOWN **/
.select2-container .select-option-level-1 {
	padding-left: 0px !important;
}

.select2-container .select-option-level-2 {
	padding-left: 12px !important;
}

.select2-container .select-option-level-3 {
	padding-left: 24px !important;
}

.select2-container .select-option-level-4 {
	padding-left: 36px !important;
}

.select2-container .select-option-level-5 {
	padding-left: 48px !important;
}

.select2-container .select-option-group {
	font-weight: bold !important;
	color: black;
}
/** END OPTION-DROPDOWN **/
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        
                        {if !empty($page_navigations) && count($page_navigations)}
                        <div class="d-md-flex flex-md-row-reverse align-items-center justify-content-between">
                            <div class="mb-3 mb-md-0 d-flex flex-wrap text-nowrap">
                                {foreach from=$page_navigations key=i item=subitem}
                                {if $page_name == $subitem.page_name} {continue} {/if}
                                {if $subitem.action_type == 'page'}
                                <a class="btn btn-sm btn-bd-light rounded-2 border me-2" 
                                    href="{$site_url}{$controller}/{$subitem.page_name}{if !empty($nav.page_param)}{$nav.page_param}{/if}">
                                        <i class="{$subitem.icon}"></i>{__($subitem.label)}
                                </a>
                                {else if $subitem.action_type == 'url'}
                                <a class="btn btn-sm btn-bd-light rounded-2 border me-2" 
                                    href="{$subitem.url}">
                                        <i class="{$subitem.icon}"></i>{__($subitem.label)}
                                </a>
                                {else if $subitem.action_type == 'param_url'}
                                <a class="btn btn-sm btn-bd-light rounded-2 border me-2" 
                                    href="{$site_url}{$subitem.url}">
                                        <i class="{$subitem.icon}"></i>{__($subitem.label)}
                                </a>
                                {/if}
                                {/foreach}
                            </div>
                            <h3 class="page-title"> <i class="mdi {$page_icon} title_icon"></i>
                                {$page_title}
                            </h3>
                        </div>
                        {else}
                        <h3 class="page-title"> <i class="mdi {$page_icon} title_icon"></i>
                                {$page_title}
                        </h3>                            
                        {/if}

                        {if !empty($page_description)}
                        <p>{$page_description}</p>
                        {/if}

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
</div>

{if !$crud}
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div> No CRUD definition </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{else}

<!-- //filtering -->
{include file='crud/crud-filter.tpl'}

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        {include file='crud/crud-table.tpl' tbl=$crud}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{if !empty($subtables) && count($subtables)}
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <div class="tabbable">

                <ul class="nav nav-pills nav-justified">
                    {assign var=is_active value=true}
                    {foreach $subtables as $subtbl}
                    <li class="nav-item" {if $subtbl.crud.table_id=='tdata_164'}style="display: none"{/if}>
                        <a class="nav-link {if $is_active}active{/if}" href="#pane_{$subtbl.subtable_id}" data-toggle="tab">{$subtbl.label}</a>
                    </li>
                    {assign var=is_active value=false}
                    {/foreach}
                </ul>
                <div class="tab-content" style="margin-top: 16px;">
                    {assign var=is_active value=true}
                    {foreach $subtables as $subtbl}
                    <div id="pane_{$subtbl.subtable_id}" class="tab-pane {if $is_active}active{/if}" {if $subtbl.crud.table_id=='tdata_164'}style="display: none"{/if}>
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
            </div> <!-- end col -->
        </div>

    </div>
</div>
{/if}

{/if}