<div class="content-header">
    <div class="container-fluid">
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
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="box-title text-center">Jumlah Total: {$total}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="box-title text-center">Masih Perlu Verifikas: {$perlu_verifikasi} (<a href="{$site_url}crud/kendaraan_dinas_verifikasi">Detail</a>)</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <i class="glyphicon glyphicon-dashboard"></i>
                        <h4 class="box-title"><b>Kendaraan Dinas Per Jenis Kendaraan</b></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div id="jenis_kendaraan" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <i class="glyphicon glyphicon-dashboard"></i>
                        <h4 class="box-title"><b>Kendaraan Dinas Per Peruntukan</b></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div id="peruntukan" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<link href="{$base_url}assets/highcharts/css/highcharts.css" rel="stylesheet" />
<script src="{$base_url}assets/highcharts/highcharts.js"></script>
<script src="{$base_url}assets/highcharts/highcharts-more.js"></script>
<script src="{$base_url}assets/highcharts/themes/grid-light.js"></script>

<script type="text/javascript">

var dt_per_opd = null;

$(document).ready(function() {
    //Pie Chart
	Highcharts.chart('jenis_kendaraan', {
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
            {literal}
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Jumlah {point.y}'
            {/literal}
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
			name: 'Jenis Kendaraan',
			colorByPoint: true,
			data: [
                {foreach $per_jenis_kendaraan as $jenis}
                {literal}{{/literal}name: '{$jenis.label} ({$jenis.jumlah})',y:{$jenis.jumlah}{literal}}{/literal},
                {/foreach}
			]
		}]
	});

	Highcharts.chart('peruntukan', {
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
            {literal}
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Jumlah {point.y}'
            {/literal}
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
			name: 'Peruntukan',
			colorByPoint: true,
			data: [
                {foreach $per_peruntukan as $jenis}
                {literal}{{/literal}name: '{$jenis.label} ({$jenis.jumlah})',y:{$jenis.jumlah}{literal}}{/literal},
                {/foreach}
			]
		}]
	});

});

</script>