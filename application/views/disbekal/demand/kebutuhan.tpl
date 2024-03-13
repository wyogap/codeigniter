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
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
						<div class="row">
							<div class="col-12 col-md-9"> <div class="row">
								<div class="form-group col-4 mb-0 mt-1 col-12 col-md-6 col-lg-4">
									<select id="f_itemtypeid" name="itemtypeid" class="form-control filter_select" placeholder="Tipe Bekal">
										<option value="" data-select2-id="2">-- Tipe Bekal --</option>
									</select>
								</div>
								<div class="form-group col-4 mb-0 mt-1 col-12 col-md-6 col-lg-4">
									<select id="f_siteid" name="siteid" class="form-control filter_select" placeholder="Satuan Kerja">
										<option value="" data-select2-id="2">-- Satuan Kerja --</option>
									</select>
								</div>
								<div class="form-group col-4 mb-0 mt-1 col-12 col-md-6 col-lg-4">
									<select id="f_year" name="year" class="form-control filter_select" placeholder="Tahun Anggaran">
										<option value="" data-select2-id="2">-- Tahun Anggaran --</option>
                                        {for $year = date('Y')-5; $year <= date('Y')+5; $year++}
                                        <option value="{$year}" data-select2-id="{$year}" {if $year == date('Y')}selected{/if}>TA {$year}</option>
                                        {/for}
									</select>
								</div>
							</div></div>
							<div class="col-12 col-md-3">
							<div class="row">
								<div class="col-12" style="margin-top: 4px;">
								<button type="submit" class="btn btn-primary btn-block" id='btn_crud_filter'
										name="button">{__('Tampilkan')}</button>
								</div>
							</div>
							</div>
						</div>
					</div>
				</div> <!-- end card-box-->
            </div> <!-- end col-->
        </div>
    </div>
</section>


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

{include file="crud/_js-crud-table.tpl" tbl=$crud}

<script type="text/javascript" defer> 

{foreach $subtables as $subtbl}
var selected_key_{$subtbl.crud.table_id} = '';
var selected_label_{$subtbl.crud.table_id} = '';
var data_{$subtbl.crud.table_id} = null;
{/foreach}

$(document).ready(function() {

    {if empty($detail)}
        //use user-select event instead of select/deselect to avoid being triggerred because of API
        dt_{$crud.table_id}.on('select.dt deselect.dt', function() {
            let data = dt_{$crud.table_id}.rows({
                selected: true
            }).data();

            if (data.length == 0) {
                //on deselect all, clear subtables
                {foreach $subtables as $subtbl}
                dt_{$subtbl.crud.table_id}.clear().draw();
                selected_key_{$subtbl.crud.table_id} = '';
                data_{$subtbl.crud.table_id} = null;
                {/foreach}
            } else {
                let f_tahun= $("#f_tahun").val();

                {foreach $subtables as $subtbl}
                //master value
                selected_key_{$subtbl.crud.table_id} = data[0]['{$subtbl.table_key_column}'];
                selected_label_{$subtbl.crud.table_id} = data[0]['{$subtbl.table_label_column}'];
                data_{$subtbl.crud.table_id} = data[0];

                dt_{$subtbl.crud.table_id}.ajax.url("{$subtbl.crud.ajax}/" +selected_key_{$subtbl.crud.table_id} +"?f_tahun="+f_tahun);
                {if $subtbl.crud.editor}
                editor_{$subtbl.crud.table_id}.s.ajax = "{$subtbl.crud.ajax}/" +selected_key_{$subtbl.crud.table_id};
                {/if}
                dt_{$subtbl.crud.table_id}.ajax.reload();
                {/foreach}
            }

        });
    {/if}
});

</script>


{foreach $subtables as $subtbl}
    {include file="crud/_js-crud-table.tpl" tbl=$subtbl.crud fsubtable='1' fkey=$subtbl.subtable_fkey_column flabel=$subtbl.label}
{/foreach}

{/if}