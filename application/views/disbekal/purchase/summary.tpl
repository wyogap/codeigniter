
<style>

.wf {
	display: flex;
    /* margin: auto; */
    align-items: flex-start;
    justify-content: center;
	margin: 16px 16px;
}

.wf-block {
	display: flex;
	align-items: flex-start;
    justify-content: center;
	width: 160px;
}

.wf-start {
	display: flex;
	align-items: flex-start;
    justify-content: center;
}

.wf-end {
	display: flex;
	align-items: flex-start;
    justify-content: center;
	width: 120px;
}

.wf-arrow {

}

.wf-arrow img {
	width: 64px;
    margin: 26px 4px 0px 4px;
}

.wf-state {
	text-align: center;
}

.wf-state img {
    margin: 0px 4px 8px 4px;
    width: 64px;
    height: 64px;
}

.wf-endpoint img {
    margin: 12px 4px 0px 4px;
    height: 40px;
    width: 40px;
}

@media screen and (max-width: 767px) {

	/* .wf {
		display: block;
	}

	.wf-block {
		margin: auto;
    	padding: 12px 0px 12px 0px;
	} */

	.wf {
		display: flex;
		-ms-flex-wrap: wrap;
		flex-wrap: wrap;
		padding: 8px;
		margin: 0px;
	}

	.wf-block {
		margin-right: auto;
		margin-left: auto;
		-ms-flex: 0 0 16.666667%;
		flex: 0 0 16.666667%;
		max-width: 16.666667%;
	}

	.wf-start {
		display: none;
	}

	.wf-end {
		display: none;
	}		

	.wf-arrow {
		display: none;
	}

	.wf-state {
		text-align: center;
		display: block;
		flex: 0 0 100%;
		max-width: 100%;
		font-size: small;
	}

	.wf-state img {
    	margin: 0px 0px 8px 0px;
	}
}

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
									<select id="f_itemtypeid" name="itemtypeid" class="form-control filter_select" placeholder="Tipe Bekal">
										<option value="" data-select2-id="2">-- Satuan Kerja --</option>
									</select>
								</div>
								<div class="form-group col-4 mb-0 mt-1 col-12 col-md-6 col-lg-4">
									<select id="f_itemtypeid" name="itemtypeid" class="form-control filter_select" placeholder="Tipe Bekal">
										<option value="" data-select2-id="2">-- Tahun --</option>
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
			<div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
						<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive-sm">
			<table id="renbut" class="table table-striped dt-responsive nowrap" width="100%">
				<thead>
					<tr>
						<th class="text-center" data-priority="1">Perintah Pengadaan</th>
						<th class="text-center" data-priority="1">Tanggal</th>
						<th class="text-center" data-priority="1">Status</th>
						<th class="text-center" data-priority="1">No. Kontrak</th>
						<th class="text-center" data-priority="1">Perintah Terima</th>
					</tr>
				</thead>
			</table>
		</div>			
	</div>
						</div>			
					</div>
				</div>			
			</div>

</div>	

<div id="detail" style="display: block;">

<div class="row"> <div class="col-12"> <div class="wf"> 
	<div class="wf-start"><div class="wf-endpoint"><img src="{$site_url}images/wf/pos0.png"></div></div>
	<div class="wf-block">
		<div class="wf-arrow"><img src="{$site_url}images/wf/arrow1.png"></div>
		<div class="wf-state"><img src="{$site_url}images/wf/pos1.png">Perintah Pengadaan</div>
	</div>	
	<div class="wf-block">
		<div class="wf-arrow"><img src="{$site_url}images/wf/arrow0.png"></div>
		<div class="wf-state"><img src="{$site_url}images/wf/pos2b.png">Tender</div>
	</div>	
	<div class="wf-block">
		<div class="wf-arrow"><img src="{$site_url}images/wf/arrow0.png"></div>
		<div class="wf-state"><img src="{$site_url}images/wf/pos3b.png">Kontrak</div>
	</div>	
	<div class="wf-block">
		<div class="wf-arrow"><img src="{$site_url}images/wf/arrow0.png"></div>
		<div class="wf-state"><img src="{$site_url}images/wf/pos4b.png">Perintah Terima</div>
	</div>	
	<div class="wf-block">
		<div class="wf-arrow"><img src="{$site_url}images/wf/arrow0.png"></div>
		<div class="wf-state"><img src="{$site_url}images/wf/pos5b.png">Evaluasi Pengadaan</div>
	</div>	
	<div class="wf-end">
		<div class="wf-arrow"><img src="{$site_url}images/wf/arrow0.png"></div>
		<div class="wf-endpoint"><img src="{$site_url}images/wf/pos0.png"></div>
	</div>	
</div></div></div>


</div>

	</div>
</section>

<script type="text/javascript">


</script>
