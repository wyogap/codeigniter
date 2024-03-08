
<style>

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
						<th class="text-center" data-priority="1">Bekal</th>
						<th class="text-center" data-priority="1">Stok Awal</th>
						<th class="text-center" data-priority="1">Bekal Masuk</th>
						<th class="text-center" data-priority="1">Dalam Pengadaan</th>
						<th class="text-center" data-priority="1">Bekal Keluar</th>
						<th class="text-center" data-priority="1">Transfer</th>
						<th class="text-center" data-priority="1">Penghapusan</th>
						<th class="text-center" data-priority="1">Kadaluarsa</th>
						<th class="text-center" data-priority="1">Stok Akhir</th>
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

<div class="row ">
	<div class="col-xl-12" style="margin-top: -8px;">
		<div class="info-box bg-white" style="min-height: 0px;">
			<div class="info-box-content">
			<div class="page-title" style="display: flex;"><h4 class="store-name" style="margin: auto; margin-bottom: 0px;">NAMA BEKAL</h4>
			</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12 col-sm-12 col-md-6 col-xl-4">
		<div class="card widget-inline">
			<div class="card-header">Stok Awal/Gudang</div>
			<div class="card-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive-sm">
						<table id="renbut" class="table table-striped dt-responsive nowrap" width="100%">
							<thead>
								<tr>
									<th class="text-center" data-priority="1">Gudang</th>
									<th class="text-center" data-priority="1">Stok</th>
								</tr>
							</thead>
						</table>
					</div>			
				</div>
			</div>
			</div>
			<div class="card-footer">Total: </div>
		</div> <!-- end card-box-->
	</div> <!-- end col-->
	<div class="col-12 col-sm-12 col-md-6 col-xl-4">
		<div class="card widget-inline">
			<div class="card-header">Bekal Masuk (Termasuk Dalam Pengadaan)</div>
			<div class="card-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive-sm">
						<table id="renbut" class="table table-striped dt-responsive nowrap" width="100%">
							<thead>
								<tr>
									<th class="text-center" data-priority="1">Tanggal</th>
									<th class="text-center" data-priority="1">Jumlah</th>
									<th class="text-center" data-priority="1">Perintah Terima</th>
									<th class="text-center" data-priority="1">No. Kontrak</th>
								</tr>
							</thead>
						</table>
					</div>			
				</div>
			</div>
			</div>
			<div class="card-footer">Total: </div>
		</div> <!-- end card-box-->
	</div> <!-- end col-->
	<div class="col-12 col-sm-12 col-md-6 col-xl-4">
		<div class="card widget-inline">
			<div class="card-header">Bekal Keluar</div>
			<div class="card-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive-sm">
						<table id="renbut" class="table table-striped dt-responsive nowrap" width="100%">
							<thead>
								<tr>
									<th class="text-center" data-priority="1">Tanggal</th>
									<th class="text-center" data-priority="1">Jumlah</th>
									<th class="text-center" data-priority="1">Perintah Distribusi</th>
								</tr>
							</thead>
						</table>
					</div>			
				</div>
			</div>
			</div>
			<div class="card-footer">Total: </div>
		</div> <!-- end card-box-->
	</div> <!-- end col-->
	<div class="col-12 col-sm-12 col-md-6 col-xl-4">
		<div class="card widget-inline">
			<div class="card-header">Transfer Bekal</div>
			<div class="card-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive-sm">
						<table id="renbut" class="table table-striped dt-responsive nowrap" width="100%">
							<thead>
								<tr>
									<th class="text-center" data-priority="1">Tanggal</th>
									<th class="text-center" data-priority="1">Jumlah</th>
									<th class="text-center" data-priority="1">Perintah Transfer</th>
									<th class="text-center" data-priority="1">Dari/Ke Gudang</th>
								</tr>
							</thead>
						</table>
					</div>			
				</div>
			</div>
			</div>
			<div class="card-footer">Total: </div>
		</div> <!-- end card-box-->
	</div> <!-- end col-->
	<div class="col-12 col-sm-12 col-md-6 col-xl-4">
		<div class="card widget-inline">
			<div class="card-header">Penghapusan Bekal (Termasuk Kadaluarsa)</div>
			<div class="card-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive-sm">
						<table id="renbut" class="table table-striped dt-responsive nowrap" width="100%">
							<thead>
								<tr>
									<th class="text-center" data-priority="1">Tanggal</th>
									<th class="text-center" data-priority="1">Jumlah</th>
									<th class="text-center" data-priority="1">Status</th>
								</tr>
							</thead>
						</table>
					</div>			
				</div>
			</div>
			</div>
			<div class="card-footer">Total: </div>
		</div> <!-- end card-box-->
	</div> <!-- end col-->
	<div class="col-12 col-sm-12 col-md-6 col-xl-4">
		<div class="card widget-inline">
			<div class="card-header">Estimasi Stok Akhir/Gudang</div>
			<div class="card-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive-sm">
						<table id="renbut" class="table table-striped dt-responsive nowrap" width="100%">
							<thead>
								<tr>
									<th class="text-center" data-priority="1">Gudang</th>
									<th class="text-center" data-priority="1">Stok</th>
								</tr>
							</thead>
						</table>
					</div>			
				</div>
			</div>
			</div>
			<div class="card-footer">Total: </div>
		</div> <!-- end card-box-->
	</div> <!-- end col-->
	<div class="col-12 col-sm-12">	
		<div class="card">
			<div class="card-header with-border text-center">
				<span class="box-title"><b>Profil Stok</b></span>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="demandprofile-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	</div>
</div>

</div>

	</div>
</section>

<script type="text/javascript">


</script>
