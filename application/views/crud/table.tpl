<div class="content-header">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="page-title"> <i class="mdi {$page_icon} title_icon"></i>
                            {$page_title} {if !empty($level1_title)}- {$level1_title}{/if}
                        </h4>
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

{include file="crud/_css.tpl"}

{if $crud.filter || $crud.search}
{include file='crud/crud-filter.tpl'}
{/if}

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        {include file='crud/crud-table.tpl' tbl=$crud}
                        {if !empty($subtables) && count($subtables)}
                        <div class=" my-3"></div>
                        {include file='crud/crud-subtables.tpl'}
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{include file="crud/_js-crud-table.tpl" tbl=$crud}

{if !empty($subtables) && count($subtables)}
{include file="crud/_js-crud-subtables.tpl" tbl=$crud}
{/if}

{/if}