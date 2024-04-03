{include file="crud/_css.tpl"}

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

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">

<div class="tabbable">
    <ul class="nav nav-pills nav-justified">
        {assign var=is_active value=true}
        {foreach $crud as $c}
        <li class="nav-item{if !empty($c.css)} {$c.css}{/if}">
            <a class="nav-link {if $is_active}active{/if}" href="#pane_{$c.table_id}" data-toggle="tab">{$c.title}</a>
        </li>
        {assign var=is_active value=false}
        {/foreach}
    </ul>
    <div class="tab-content" style="margin-top: 16px;">
        {assign var=is_active value=true}
        {foreach $crud as $c}
        <div id="pane_{$c.table_id}" class="tab-pane{if $is_active} active{/if}{if !empty($c.css)} {$c.css}{/if}">
            <div class="row" style="flex-grow: 1;"><div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                    {include file='crud/crud-table.tpl' tbl=$c}
                    </div>
                </div>
            </div></div>
        </div>
        {assign var=is_active value=false}
        {/foreach}
    </div>
    <!-- /.tab-content -->
</div>

            </div>
        </div>
    </div>
</section>

{foreach $crud as $c}
{include file="crud/_js-crud-table.tpl" tbl=$c}
{/foreach}

{include file='crud/_js.tpl'}