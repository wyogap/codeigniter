<div class="content-header">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        {if $detail && $crud.table_actions.edit}
                        <div class="float-right">
                            <button class="btn btn-primary crud-form-submit" data-table-id="{$crud.table_id}">{__('Simpan')}</button>
                            {if !empty($show_table_link)}
                            <button class="btn btn-primary crud-form-table" data-table-id="{$crud.table_id}"><i class="fa fa-table fas"></i></button>
                            {/if}
                        </div>
                        {else if $crud.table_actions.add}
                        <div class="float-right">
                            <button class="btn btn-primary crud-form-submit"
                                data-table-id="{$crud.table_id}">{__('Tambah')}</button>
                            {if !empty($show_table_link)}
                            <button class="btn btn-primary crud-form-table" data-table-id="{$crud.table_id}"><i class="fa fa-table fas"></i></button>
                            {/if}
                        </div>
                        {/if}
                        <h4 class="page-title"> <i class="mdi {$page_icon} title_icon"></i>
                            {$page_title}
                        </h4>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
</div>

{if !$crud}
<div class="content">
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
</div>
{else}

{include file="crud/_css.tpl"}

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- <form class="crud-form" data-url="{$crud.ajax}" action="{$crud.ajax}" role="form" enctype="multipart/form-data" method="post" id="{$crud.table_id}_form" data-table-id="{$crud.table_id}" {if $detail}data-id="{$detail[$crud.key_column]}"{/if}> -->
                <div class="card widget-inline">
                    <div class="card-body">
                        {include file='crud/crud-form.tpl' tbl=$crud}
                        <div class=" my-3"></div>
                    </div>
                </div>
                <!-- </form> -->
            </div> <!-- end col -->
        </div>

    </div>
</div>

{if !empty($subtables) && count($subtables)}
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- <form class="crud-form" data-url="{$crud.ajax}" action="{$crud.ajax}" role="form" enctype="multipart/form-data" method="post" id="{$crud.table_id}_form" data-table-id="{$crud.table_id}" {if $detail}data-id="{$detail[$crud.key_column]}"{/if}> -->
                <div class="card widget-inline">
                    <div class="card-body">
                        {include file='crud/crud-subtables.tpl'}
                    </div>
                </div>
                <!-- </form> -->
            </div> <!-- end col -->
        </div>

    </div>
</div>
{/if}

{include file="crud/_js-crud-form.tpl" tbl=$crud}

{if !empty($subtables) && count($subtables)}
{include file="crud/_js-crud-subtables.tpl" tbl=$crud}
{/if}

{/if}