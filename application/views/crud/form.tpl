<div class="content-header">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="float-right">
                            {if empty($form_mode) || $form_mode=='add'}
                            {if $crud.table_actions.add}
                            <button class="btn btn-primary crud-form-submit" data-table-id="{$crud.table_id}">{__('Tambah')}</button>
                            {/if}
                            {elseif $form_mode=='edit'}
                            {if $detail && $crud.table_actions.edit}
                            <button class="btn btn-primary crud-form-add" data-table-id="{$crud.table_id}">{__('Baru')}</button>
                            <button class="btn btn-primary crud-form-submit" data-table-id="{$crud.table_id}">{__('Simpan')}</button>
                            {/if}
                            {elseif $form_mode=='detail'}
                            {if $crud.table_actions.add}
                            <a class="btn btn-primary crud-form-add" data-table-id="{$crud.table_id}" href="{$site_url}{$controller}/{$page_name}/add">{__('Baru')}</a>
                            {/if}
                            {if $detail && $crud.table_actions.edit}
                            <a class="btn btn-primary crud-form-edit" data-table-id="{$crud.table_id}" href="{$site_url}{$controller}/{$page_name}/edit/{$detail[$crud.key_column]}">{__('Edit')}</a>
                            {/if}
                            {/if}
                            {if !empty($show_table_link)}
                            <button class="btn btn-primary crud-form-table" data-table-id="{$crud.table_id}"><i class="fa fa-table fas"></i></button>
                            {/if}
                        </div>
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
        <div class="row"><div class="col-12">
                        {include file='crud/crud-form.tpl' tbl=$crud}
        </div></div>

    </div>
</div>

            
{if !empty($subtables) && count($subtables)}
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                        {include file='crud/crud-form-subtables.tpl'}
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