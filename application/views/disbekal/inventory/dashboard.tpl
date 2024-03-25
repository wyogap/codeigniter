<!--
<link rel="stylesheet" href="{$base_url}assets/ionicons/css/ionicons.min.css">
<link rel="stylesheet" href="{$base_url}assets/css/ppdb.css">
<script src="{$base_url}assets/highcharts/code/highcharts.js"></script>
<script src="{$base_url}assets/highcharts/code/highcharts-more.js"></script>
<script src="{$base_url}assets/highcharts/code/themes/grid-light.js"></script>
-->

<link href="{$base_url}assets/highcharts/css/highcharts.css" rel="stylesheet" />

<script src="{$base_url}assets/highcharts/highcharts.js"></script>
<script src="{$base_url}assets/highcharts/highcharts-more.js"></script>
<script src="{$base_url}assets/highcharts/themes/grid-light.js"></script>

<style>

.myDivIcon {
  text-align: center;
  /* Horizontally center the text (icon) */
  line-height: 20px;
  /* Vertically center the text (icon) */
}

a > .info-box.bg-purple:hover {
	background-color: #8e54f9!important;
}

a > .info-box.bg-red:hover {
	background-color: #f33c4d!important;
}

a > .info-box.bg-blue:hover {
	background-color: #3293fb!important;
}

.highcharts-container {
	margin: auto;
}

.info-box {
    text-align: center;
}

.info-box .info-box-number {
    font-size: xx-large;
}

.nav-tabs > li.active > a, 
.nav-tabs > li.active > a:focus, 
.nav-tabs > li.active > a:hover {
  /* background-color: #ccc !important; */
  border-left-color: #3c8dbc !important;
  border-right-color: #3c8dbc !important;
}

.nav-tabs-custom > .nav-tabs {
    border-bottom-color: #3c8dbc;
}

@media screen and (max-width: 480px) {

	.nav-justified .nav-item {
		-ms-flex-preferred-size: 0;
		flex-basis: 100%;
	}
}

@media screen and (max-width: 767px) {


.navbar-toggle {
	z-index: 999 !important;
}

div.dataTables_paginate {
	display: inline-block;
	float: left !important;
	padding-top: 0.5em !important;
}

div.dataTables_info {
	display: inline-block;
	clear: left !important;
	float: left !important;
	padding-top: 0.835em !important;
	margin-left: 0px;
}

div.dataTables_length {
	display: inline-block;
	padding-top: 0.750em !important;
	clear: right !important;
	float: right !important;
}

.nav-tabs > li.active > a, 
.nav-tabs > li.active > a:focus, 
.nav-tabs > li.active > a:hover {
background-color: #f4f4f4 !important; 
border-bottom-color: #3c8dbc !important;
border-left-color: #3c8dbc !important;
border-right-color: #3c8dbc !important;
}

.tahun-selection {
	position: relative;
	margin-top: 5px;
	top: 0;
	right: 0;
	float: none;
	padding-left: 0px;
	margin-left: -12px;
}

.navbar-collapse.pull-left + .navbar-custom-menu {
	display: block;
	position: absolute;
	top: 0;
	right: 60px !important;
}

.dropdown-menu > li > a {
	color: #fff;
}
}

</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row ">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div id="peta" style="width: 100%; height: 500px;"></div><br>
			</div>
			<div class="col-xl-12" style="margin-top: -8px;">
				<div class="info-box bg-white" style="min-height: 0px;">
					<div class="info-box-content">
					<div class="page-title" style="display: flex;"><h4 class="store-name" style="margin: auto; margin-bottom: 0px;">SEMUA GUDANG</h4>
					<span id="btn-reset-store" style="cursor: pointer; display: none;" onclick="switch_store(0);"><i class="fas fa-arrow-up"></i></span></div>
					</div>
				</div>
			</div>
			<!--
			<div class="col-xl-12">
				<div class="info-box bg-gray">
					<div class="info-box-content" style="margin-left: 0px;">
                <div class="card">
                    <div class="card-body">
                        <h4 class="page-title"> <i class="mdi {$page_icon} title_icon"></i>
                            {$page_title} <span class="store-name-2"></span>
                        </h4>
                    </div> 
                </div>
            </div>
			-->
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box bg-gray">
							<!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
							<div class="info-box-content" style="margin-left: 0px;">
								<span class="info-box-number" id="nilaitotal-val">0</span>
								<span class="info-box-text">Rupiah</span>
								<div class="progress">
									<div class="progress-bar" style="width: 100%"></div>
								</div>
								<span class="progress-description">Nilai Total</span>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<a href="{$site_url}{$controller}/stock_status">
						<div class="info-box bg-purple">
							<!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
							<div class="info-box-content" style="margin-left: 0px;">
								<span class="info-box-number" id="rusak-val">0</span>
								<span class="info-box-text">Rupiah</span>
								<div class="progress">
									<div class="progress-bar" style="width: 100%"></div>
								</div>
								<span class="progress-description">Rusak Dan Kadaluarsa</span>
							</div>
						</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<a href="{$site_url}{$controller}/expired_stock">
						<div class="info-box bg-red">
							<!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
							<div class="info-box-content" style="margin-left: 0px;">
								<span class="info-box-number" id="hampirkadaluarsa-val">0</span>
								<span class="info-box-text">Rupiah</span>
								<div class="progress">
									<div class="progress-bar" style="width: 100%"></div>
								</div>
								<span class="progress-description">Kadaluarsa Dalam 6 Bulan</span>
							</div>
						</div>
						</a>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<a href="{$site_url}{$controller}/low_stock">
						<div class="info-box bg-blue">
							<!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
							<div class="info-box-content" style="margin-left: 0px;">
								<span class="info-box-number" id="hampirhabis-val">0</span>
								<span class="info-box-text">Jenis Barang</span>
								<div class="progress">
									<div class="progress-bar" style="width: 100%"></div>
								</div>
								<span class="progress-description">Habis Dalam 3 Bulan</span>
							</div>
						</div>
						</a>
					</div>

 	    </div>

		<div class="nav-tabs-custom" id="tabs">
			<ul class="nav nav-pills nav-justified" id="tabNames">
				<li class="nav-item"><a class="nav-link active" href="#stokpergudang" data-toggle="tab" role="tab" aria-controls="vert-tabs-home" aria-selected="true" id='label-belum'>Stok/Gudang</a></li>
				<li class="nav-item"><a class="nav-link" href="#stokperkategori" data-toggle="tab" role="tab" aria-controls="vert-tabs-home" aria-selected="true" id='label-sedang'>Stok/Klasifikasi</a></li>
				{if 1==0}
				<li class="nav-item"><a class="nav-link" href="#kadaluarsapergudang" data-toggle="tab" aria-controls="vert-tabs-home" aria-selected="true" role="tab" id="label-sudah">Kadaluarsa/Gudang</a></li>
				<li class="nav-item"><a class="nav-link" href="#kadaluarsaperkategori" data-toggle="tab" aria-controls="vert-tabs-home" aria-selected="true" role="tab" id="label-berkas">Kadaluarsa/Klasifikasi</a></li>
				<li class="nav-item"><a class="nav-link" href="#rusakpergudang" data-toggle="tab" role="tab" aria-controls="vert-tabs-home" aria-selected="true" id="label-sudah">Rusak/Gudang</a></li>
				<li class="nav-item"><a class="nav-link" href="#rusakperkategori" data-toggle="tab" role="tab" aria-controls="vert-tabs-home" aria-selected="true" id="label-berkas">Rusak/Klasifikasi</a></li>
				{/if}
				<li class="nav-item"><a class="nav-link" href="#perkiraankadaluarsa" data-toggle="tab" role="tab" aria-controls="vert-tabs-home" aria-selected="true" id="label-berkas">Perkiraan Kadaluarsa</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade active show" role="tabpanel" id="stokpergudang">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Stok Per Gudang (<span class="store-name">Semua Gudang</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="stokpergudang-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" role="tabpanel" id="stokperkategori">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Stok Per Klasifikasi (<span class="store-name">Semua Gudang</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="stokperkategori-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				{if 1==0}
				<div class="tab-pane fade" role="tabpanel" id="kadaluarsapergudang">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Stok Kadaluarsa Per Gudang (<span class="store-name">Semua Gudang</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="kadaluarsapergudang-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" role="tabpanel" id="kadaluarsaperkategori">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Stok Kadaluarsa Per Klasifikasi (<span class="store-name">Semua Gudang</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="kadaluarsaperkategori-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" role="tabpanel" id="rusakpergudang">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Stok Rusak Per Gudang (<span class="store-name">Semua Gudang</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="rusakpergudang-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" role="tabpanel" id="rusakperkategori">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Stok Rusak Per Klasifikasi (<span class="store-name">Semua Gudang</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="rusakperkategori-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				{/if}
				<div class="tab-pane fade" role="tabpanel" id="perkiraankadaluarsa">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Stok Per Perkiraan Kadaluarsa (<span class="store-name">Semua Gudang</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="perkiraankadaluarsa-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box bg-gray">
							<!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
							<div class="info-box-content" style="margin-left: 0px;">
								<span class="info-box-number" id="pergerakan-val">0</span>
								<span class="info-box-text">Rupiah</span>
								<div class="progress">
									<div class="progress-bar" style="width: 100%"></div>
								</div>
								<span class="progress-description">Net Transaksi (<span class="label-periode">Tahun Berjalan</span>)</span>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box bg-purple">
							<!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
							<div class="info-box-content" style="margin-left: 0px;">
								<span class="info-box-number" id="penerimaan-val">0</span>
								<span class="info-box-text">Rupiah</span>
								<div class="progress">
									<div class="progress-bar" style="width: 100%"></div>
								</div>
								<span class="progress-description">Barang Masuk (<span class="label-periode">Tahun Berjalan</span>)</span>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box bg-blue">
							<!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
							<div class="info-box-content" style="margin-left: 0px;">
								<span class="info-box-number" id="penggunaan-val">0</span>
								<span class="info-box-text">Rupiah</span>
								<div class="progress">
									<div class="progress-bar" style="width: 100%"></div>
								</div>
								<span class="progress-description">Barang Keluar (<span class="label-periode">Tahun Berjalan</span>)</span>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box bg-red">
							<!-- <span class="info-box-icon"><i class="glyphicon glyphicon-user"></i></span> -->
							<div class="info-box-content" style="margin-left: 0px;">
								<span class="info-box-number" id="hapusbuku-val">0</span>
								<span class="info-box-text">Rupiah</span>
								<div class="progress">
									<div class="progress-bar" style="width: 100%"></div>
								</div>
								<span class="progress-description">Hapus Buku (<span class="label-periode">Tahun Berjalan</span>)</span>
							</div>
						</div>
					</div>
		</div>

		<div class="nav-tabs-custom" id="tabs">
			<ul class="nav nav-pills nav-justified" id="tabNames">
				<li class="nav-item"><a class="nav-link active" href="#barangmasukperwaktu" data-toggle="tab" role="tab" aria-controls="vert-tabs-home" aria-selected="true" id='label-belum'>Barang Masuk</a></li>
				<li class="nav-item"><a class="nav-link" href="#barangmasukpergudang" data-toggle="tab" role="tab" aria-controls="vert-tabs-home" aria-selected="true" id='label-sedang'>Barang Masuk/Gudang</a></li>
				<li class="nav-item"><a class="nav-link" href="#barangmasukperkategori" data-toggle="tab" aria-controls="vert-tabs-home" aria-selected="true" role="tab" id="label-sudah">Barang Masuk/Klasifikasi</a></li>
				<li class="nav-item"><a class="nav-link" href="#barangkeluarperwaktu" data-toggle="tab" aria-controls="vert-tabs-home" aria-selected="true" role="tab" id="label-berkas">Barang Keluar</a></li>
				<li class="nav-item"><a class="nav-link" href="#barangkeluarpergudang" data-toggle="tab" role="tab" aria-controls="vert-tabs-home" aria-selected="true" id="label-sudah">Barang Keluar/Gudang</a></li>
				<li class="nav-item"><a class="nav-link" href="#barangkeluarperkategori" data-toggle="tab" role="tab" aria-controls="vert-tabs-home" aria-selected="true" id="label-berkas">Barang Keluar/Klasifikasi</a></li>
				<li class="nav-item"><a class="nav-link" href="#hapusbuku" data-toggle="tab" role="tab" aria-controls="vert-tabs-home" aria-selected="true" id="label-berkas">Hapus Buku</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade active show" role="tabpanel" id="barangmasukperwaktu">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Barang Masuk (<span class="label-periode">Tahun Berjalan</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="barangmasukperwaktu-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" role="tabpanel" id="barangmasukpergudang">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Barang Masuk Per Gudang (<span class="label-periode">Tahun Berjalan</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="barangmasukpergudang-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" role="tabpanel" id="barangmasukperkategori">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Barang Masuk Per Klasifikasi (<span class="label-periode">Tahun Berjalan</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="barangmasukperkategori-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" role="tabpanel" id="barangkeluarperwaktu">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Barang Keluar (<span class="label-periode">Tahun Berjalan</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="barangkeluarperwaktu-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" role="tabpanel" id="barangkeluarpergudang">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Barang Keluar Per Gudang (<span class="label-periode">Tahun Berjalan</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="barangkeluarpergudang-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" role="tabpanel" id="barangkeluarperkategori">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Barang Keluar Per Klasifikasi (<span class="label-periode">Tahun Berjalan</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="barangkeluarperkategori-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" role="tabpanel" id="hapusbuku">
					<div class="card">
						<div class="card-header with-border text-center">
							<i class="glyphicon glyphicon-dashboard"></i>
							<span class="box-title"><b>Hapus Buku (<span class="label-periode">Tahun Berjalan</span>)</b></span>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div id="hapusbuku-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
		</div>
	</div>
</div>

	</div>
</div>

{include "disbekal/inventory/context_menu.tpl"}

<script type="text/javascript">

	function reload() {

		$.ajax({
            url: "{$base_url}disbekal/dashboard/nilaistok?s=" +storeid,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1) {
					return;
                }

				$('#nilaitotal-val').html(indo_money(response.nilaistok));
				$('#rusak-val').html(indo_money(response.rusak));
				$('#kadaluarsa-val').html(indo_money(response.kadaluarsa));
				$('#hampirkadaluarsa-val').html(indo_money(response.hampirkadaluarsa));

            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
			}
        });

		$.ajax({
            url: "{$base_url}disbekal/dashboard/itemstok?s=" +storeid,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1) {
					return;
                }

				$('#hampirhabis-val').html(indo_money(response.hampirhabis));
				$('#itemrusak-val').html(indo_money(response.rusak));
				$('#itemkadaluarsa-val').html(indo_money(response.kadaluarsa));
				$('#itemhampirkadaluarsa-val').html(indo_money(response.hampirkadaluarsa));

            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
			}
        });

		$.ajax({
            url: "{$base_url}disbekal/dashboard/pergerakanbarang?s=" +storeid+ "&p=" +periode+ "&o=" +offset,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				$('#pergerakan-val').html(indo_money(response.data.nilai_total));
				$('#penerimaan-val').html(indo_money(response.data.penerimaan));
				$('#penggunaan-val').html(indo_money(response.data.penggunaan));
				let hapus_buku = parseFloat(response.data.hapusbuku_rusak) + parseFloat(response.data.hapusbuku_kadaluarsa);
				$('#hapusbuku-val').html(indo_money(hapus_buku));

            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
			}
        });

		load_stokpergudang();
		load_barangmasukperwaktu();

		load_stokperkategori();
		// load_kadaluarsapergudang();
		// load_kadaluarsaperkategori();
		// load_rusakpergudang();
		// load_rusakperkategori();
		load_perkiraankadaluarsa();

		load_barangmasukpergudang();
		load_barangmasukperkategori();
		load_barangkeluarperwaktu();
		load_barangkeluarpergudang();
		load_barangkeluarperkategori();
		load_hapusbuku();
		
	}

	function indo_money(number) {
		let label = "";
		let absnumber = Math.abs(number);

		if (absnumber > 1000000000) {
			number = Math.round(number/1000000000);
			label = number + " Mil";
		}
		else if (absnumber > 1000000) {
			number = Math.round(number/1000000);
			label = number + " Juta";
		}
		else if (absnumber > 1000) {
			number = Math.round(number/1000);
			label = number + " Ribu";
		}
		else {
			number = Math.round(number);
			label = number;			
		}

		return label;
	}

	{literal}
	theme = 'grid-light';

	var hstokpergudang = hstokperkategori = hkadaluarsapergudang = hkadaluarsaperkategori = hrusakpergudang = hrusakperkategori = hperkiraankadaluarsa = null;

	var hbarangmasukperwaktu = hbarangmasukpergudang = hbarangmasukperkategori = hbarangkeluarperwaktu = hbarangkeluarpergudang = hbarangkeluarperkategori = hhapusbuku = null;

	$(document).ready(function() {
	//Pie Chart
	hstokpergudang = Highcharts.chart('stokpergudang-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}<br>Rusak: {point.rusak}<br>Kadaluarsa: {point.kadaluarsa}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:100,label:'NA',rusak:'NA',kadaluarsa:'NA'},
			]
		}]
	});

	//Pie Chart
	hstokperkategori = Highcharts.chart('stokperkategori-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:100,label:'NA'},
			]
		}]
	});

	/*
	//Pie Chart
	hkadaluarsapergudang = Highcharts.chart('kadaluarsapergudang-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:100,label:'NA'},
			]
		}]
	});

	//Pie Chart
	hkadaluarsaperkategori = Highcharts.chart('kadaluarsaperkategori-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:100,label:'NA'},
			]
		}]
	});

	//Pie Chart
	hrusakpergudang = Highcharts.chart('rusakpergudang-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:100,label:'NA'},
			]
		}]
	});

	//Pie Chart
	hrusakperkategori = Highcharts.chart('rusakperkategori-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:100,label:'NA'},
			]
		}]
	});
	*/
	
	//Pie Chart
	hperkiraankadaluarsa = Highcharts.chart('perkiraankadaluarsa-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:200,label:'NA'},
			]
		}]
	});

	//Bar Chart
	hbarangmasukperwaktu = Highcharts.chart('barangmasukperwaktu-chart', {
		chart: {
			type: 'column'
		},
		title: {
			text: false
		},
		xAxis: {
			categories: ['Tanggal'],
			title: {
				text: null
			}
		},
		yAxis: {
			min: 0,
			title: false,
			labels: {
				overflow: 'justify'
			}
		},
		tooltip: {
			pointFormat: 'Nilai: {point.label}'
		},
		plotOptions: {
	        bar: {
	            dataLabels: {
	                enabled: true
	            },
	            enableMouseTracking: false
	        }
	    },
		legend: {
			enabled: true
		},
		credits: {
			enabled: true
		},
		series: [{
			name: 'Barang Masuk',
			color: '#7cb5ec',
			data: [
				{name:'1970-01-01',y:200,label:'NA'},
			]
		}]
	});

	//Pie Chart
	hbarangmasukpergudang = Highcharts.chart('barangmasukpergudang-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:200,label:'NA'},
			]
		}]
	});

	//Pie Chart
	hbarangmasukperkategori = Highcharts.chart('barangmasukperkategori-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:200,label:'NA'},
			]
		}]
	});

	//Bar Chart
	hbarangkeluarperwaktu = Highcharts.chart('barangkeluarperwaktu-chart', {
		chart: {
			type: 'column'
		},
		title: {
			text: false
		},
		xAxis: {
			categories: ['Tanggal'],
			title: {
				text: null
			}
		},
		yAxis: {
			min: 0,
			title: false,
			labels: {
				overflow: 'justify'
			}
		},
		tooltip: {
			pointFormat: 'Nilai: {point.label}'
		},
		plotOptions: {
	        bar: {
	            dataLabels: {
	                enabled: true
	            },
	            enableMouseTracking: false
	        }
	    },
		legend: {
			enabled: true
		},
		credits: {
			enabled: true
		},
		series: [{
			name: 'Barang Masuk',
			color: '#7cb5ec',
			data: [
				{name:'1970-01-01',y:200,label:'NA'},
			]
		}]
	});

	//Pie Chart
	hbarangkeluarpergudang = Highcharts.chart('barangkeluarpergudang-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:200,label:'NA'},
			]
		}]
	});

	//Pie Chart
	hbarangkeluarperkategori = Highcharts.chart('barangkeluarperkategori-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:200,label:'NA'},
			]
		}]
	});

	//Pie Chart
	hhapusbuku = Highcharts.chart('hapusbuku-chart', {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: false
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.label}'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
		series: [{
			name: 'Persentase',
			colorByPoint: true,
			data: [
				{name:'No Data',y:200,label:'NA'},
			]
		}]
	});

	context_menu_reload_click();

	});
	{/literal}

	//var hstokpergudang = hstokperkategori = hkadaluarsapergudang = hkadaluarsaperkategori = hrusakpergudang = hrusakperkategori = hperkiraankadaluarsa = null;

	function load_stokpergudang() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/stokpergudang?s=" +storeid,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].description;
					let nilai = parseFloat(response.data[i].nilai_total);
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					nilai = parseFloat(response.data[i].rusak);
					item['rusak'] = indo_money(nilai);
					nilai = parseFloat(response.data[i].kadaluarsa);
					item['kadaluarsa'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				// hstokpergudang.series[0].update({
				// 	name: 'Gudang',
				// 	colorByPoint: true,
				// 	data: data
				// }, false);

				hstokpergudang.series[0].setData(data);
				hstokpergudang.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	function load_stokperkategori() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/stokperkategori?s=" +storeid,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].description;
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				// hstokpergudang.series[0].update({
				// 	name: 'Gudang',
				// 	colorByPoint: true,
				// 	data: data
				// }, false);

				hstokperkategori.series[0].setData(data);
				hstokperkategori.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	/*
	function load_kadaluarsapergudang() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/kadaluarsapergudang?s=" +storeid,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].description;
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				hkadaluarsapergudang.series[0].setData(data);
				hkadaluarsapergudang.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	function load_kadaluarsaperkategori() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/kadaluarsaperkategori?s=" +storeid,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].description;
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				hkadaluarsaperkategori.series[0].setData(data);
				hkadaluarsaperkategori.redraw();

			},
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	function load_rusakpergudang() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/rusakpergudang?s=" +storeid,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].description;
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				hrusakpergudang.series[0].setData(data);
				hrusakpergudang.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	function load_rusakperkategori() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/rusakperkategori?s=" +storeid,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].description;
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				hrusakperkategori.series[0].setData(data);
				hrusakperkategori.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}
	*/

	function load_perkiraankadaluarsa() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/perkiraankadaluarsa?s=" +storeid,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].label;
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				hperkiraankadaluarsa.series[0].setData(data);
				hperkiraankadaluarsa.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	function load_barangmasukperwaktu() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/barangmasukperwaktu?s=" +storeid+ "&p=" +periode+ "&o=" +offset,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				let cat = []; 
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					if (periode=='ytd') {
						item['name'] = response.data[i].nama_pendek + "-" + response.data[i].tahun;
					}
					else {
						item['name'] = response.data[i].tanggal;
					}
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
					cat.push(item['name']);
				}
				{/literal}

				hbarangmasukperwaktu.xAxis[0].categories=cat;
				hbarangmasukperwaktu.series[0].setData(data);
				hbarangmasukperwaktu.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	function load_barangmasukpergudang() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/barangmasukpergudang?s=" +storeid+ "&p=" +periode+ "&o=" +offset,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].description;
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				hbarangmasukpergudang.series[0].setData(data);
				hbarangmasukpergudang.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	function load_barangmasukperkategori() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/barangmasukperkategori?s=" +storeid+ "&p=" +periode+ "&o=" +offset,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].description;
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				hbarangmasukperkategori.series[0].setData(data);
				hbarangmasukperkategori.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	function load_barangkeluarperwaktu() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/barangkeluarperwaktu?s=" +storeid+ "&p=" +periode+ "&o=" +offset,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				let cat = []; 
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					if (periode=='ytd') {
						item['name'] = response.data[i].nama_pendek + "-" + response.data[i].tahun;
					}
					else {
						item['name'] = response.data[i].tanggal;
					}
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
					cat.push(item['name']);
				}
				{/literal}

				hbarangkeluarperwaktu.xAxis[0].categories=cat;
				hbarangkeluarperwaktu.series[0].setData(data);
				hbarangkeluarperwaktu.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	function load_barangkeluarpergudang() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/barangkeluarpergudang?s=" +storeid+ "&p=" +periode+ "&o=" +offset,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].description;
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				hbarangkeluarpergudang.series[0].setData(data);
				hbarangkeluarpergudang.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	function load_barangkeluarperkategori() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/barangkeluarperkategori?s=" +storeid+ "&p=" +periode+ "&o=" +offset,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].description;
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				hbarangkeluarperkategori.series[0].setData(data);
				hbarangkeluarperkategori.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}

	function load_hapusbuku() {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/dashboard/hapusbuku?s=" +storeid+ "&p=" +periode+ "&o=" +offset,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				{literal}
				let data = [];
				for(let i=0; i<response.data.length; i++) {
					let item = {};
					item['name'] = response.data[i].description;
					let nilai = parseFloat(response.data[i].nilai_total)
					item['y'] = nilai;
					item['label'] = indo_money(nilai);
					data.push(item);
				}
				{/literal}

				hhapusbuku.series[0].setData(data);
				hhapusbuku.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });

	}


</script>

<script type="text/javascript">
	var map;
	var overlays;
	var icon;

	$(document).ready(function() {

		{literal}
		//Peta
		map = L.map('peta',{zoomControl:false}).setView([-2.189275, 119.7852448],5);
		L.tileLayer(
			'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom: 15,attribution: 'DISBEKAL TNI-AL',id: 'mapbox.streets'}
		).addTo(map);

		var streetmap   = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {id: 'mapbox.light', attribution: ''}),
			satelitemap  = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {id: 'mapbox.streets',   attribution: ''});
		var baseLayers = {
			"Satelite": satelitemap,
			"Streets": streetmap
		};

		//Layer Group
		var jGudang = new L.LayerGroup();

		var marker = L.circle([-7.664174,109.617572], 60, {
			color: '#1f618d',
			fillColor: '#1f618d',
			fillOpacity: 0.8
		});
		marker.addTo(jGudang);

		//Adding Layer Group
		jGudang.addTo(map);
		overlays = {
			"Gudang":jGudang,
		};
		L.control.layers(baseLayers,overlays).addTo(map);

		new L.control.fullscreen({position:'bottomleft'}).addTo(map);
		new L.Control.Zoom({position:'bottomright'}).addTo(map);

		{/literal}

		icon = L.icon({
			iconUrl: '{$base_url}images/disbekal-xsm.png',
			//shadowUrl: 'leaf-shadow.png',

			iconSize:     [30, 31], // size of the icon
			shadowSize:   [0, 0], // size of the shadow
			iconAnchor:   [15, 15], // point of the icon which will correspond to marker's location
			shadowAnchor: [0, 0],  // the same for the shadow
			popupAnchor:  [0, -15] // point from which the popup should open relative to the iconAnchor
		});		

		// icon = L.divIcon({
		// 	// iconUrl: '{$base_url}images/disbekal-xsm.png',
		// 	html: '<i class="fas fa-landmark fa-2x"></i>',
		// 	iconSize: [20, 20],
		// 	className: 'myDivIcon'
		// });

		map_pendaftaran();
	});

	function map_pendaftaran() {
		$.post("{$site_url}disbekal/dashboard/stokpergudang",
		{},
		function(data, status){
			data.data.forEach(function(value, index, array) {
				if (value.latitude != "" && value.latitude != null 
						& value.longitude != "" && value.longitude != null) {
					if (value.orgcode=='DISBEKAL') {
						var msg = "<a href='#' onclick='switch_store(\"" +value.storeid+ "\")'>" +value.description+"</a><br>" 
							+ "Nilai Stok: <b>" +indo_money(value.nilai_total)+ "</b>, Rusak: <b>" +indo_money(value.rusak)+ "</b>, Kadaluarsa: <b>" +indo_money(value.kadaluarsa)+ "</b>"
							+ "<br>Perwira Gudang: ";
						var marker = L.marker([parseFloat(value.latitude), parseFloat(value.longitude)]).addTo(map)
							.bindPopup(msg);
						marker.setIcon(icon);
						marker.addTo(overlays["Gudang"]);
					}
					else {
						var msg = "<a href='#' onclick='switch_store(\"" +value.storeid+ "\")'>" +value.description+"</a><br>" 
							+ "Nilai Stok: <b>" +indo_money(value.nilai_total)+ "</b>, Rusak: <b>" +indo_money(value.rusak)+ "</b>, Kadaluarsa: <b>" +indo_money(value.kadaluarsa)+ "</b>"
							+ "<br>Perwira Gudang: ";
						var marker = L.marker([parseFloat(value.latitude), parseFloat(value.longitude)]).addTo(map)
							.bindPopup(msg);

						// var marker = L.circle([parseFloat(value.latitude), parseFloat(value.longitude)], 60, {
						// 	color: '#1f618d',
						// 	fillColor: '#1f618d',
						// 	fillOpacity: 0.8
						// });

						marker.addTo(overlays["Gudang"]);
					}
				}
			});
		},
		"json");
	}

	function switch_store(storeid) {
		$("#edit-store").val(storeid);
		context_menu_reload_click();
	}

</script>
