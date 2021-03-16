<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi {$page_icon} title_icon"></i>
                    {$page_title}
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

{if !$crud}

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
            <div> No CRUD definition </div>
            </div>
        </div>
    </div>
</div>

{else}

{include file="crud/_css.tpl"}

<div class="row">
    <div class="col-12">
        <!-- <form class="crud-form" data-url="{$crud.ajax}" action="{$crud.ajax}" role="form" enctype="multipart/form-data" method="post" id="{$crud.table_id}_form" data-table-id="{$crud.table_id}" {if $detail}data-id="{$detail[$crud.key_column]}"{/if}> -->
        <div class="card widget-inline">
            <div class="card-body">
                {include file='crud/crud-form.tpl'}
            </div>
            {if $detail && $crud.table_actions.edit}
            <div class="card-footer text-right"> 
                <!-- <button type="submit" class="btn btn-primary crud-form-submit" data-table-id="{$crud.table_id}">{__('Save Changes')}</button>  -->
                <button class="btn btn-primary crud-form-submit" data-table-id="{$crud.table_id}">{__('Save Changes')}</button> 
            </div>
            {else if $crud.table_actions.add}
            <div class="card-footer text-right"> 
                <!-- <button type="submit" class="btn btn-primary crud-form-submit" data-table-id="{$crud.table_id}">{__('Save Changes')}</button>  -->
                <button class="btn btn-primary crud-form-submit" data-table-id="{$crud.table_id}">{__('Create')}</button> 
            </div>
            {/if}
        </div>
        <!-- </form> -->
    </div> <!-- end col -->
</div>

{include file="crud/_js-crud-form.tpl"}

{/if}