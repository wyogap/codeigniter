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
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <i class="glyphicon glyphicon-dashboard"></i>
                        <h4 class="box-title"><b>Kendaraan Dinas Per OPD/UPB</b></h4>
                    </div>
                    <div class="card-body" style="padding-top: 0px;">
                        <div class="table-responsive-sm">
                            <table id="per_opd" class="table table-striped dt-responsive nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" data-priority="1">OPD</th>
                                        <th class="text-center" data-priority="2">Total</th>
                                        <th class="text-center" data-priority="3">Terverifikasi</th>
                                        <th class="text-center" data-priority="4">Perlu Verifikasi</th>
                                        <th class="text-center">Roda Dua</th>
                                        <th class="text-center">Roda Tiga</th>
                                        <th class="text-center">Mobil</th>
                                        <th class="text-center">Perorangan</th>
                                        <th class="text-center">Jabatan</th>
                                        <th class="text-center">Operasional</th>
                                        <th class="text-center">Operasional Khusus</th>
                                        <th class="text-center">Pinjam Pakai</th>
                                    </tr>
                                </thead>
                            </table>
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
    //         colors: ["#205493", "#BE5873", "#81CACF", "#E98841", "#E3D830", "#A6C46F",
    //    "#894C7B", "#BA9765", "#7F7F7F", "#C3C3C3"],
			data: [
                {foreach $per_peruntukan as $jenis}
                {literal}{{/literal}name: '{$jenis.label} ({$jenis.jumlah})',y:{$jenis.jumlah}{literal}}{/literal},
                {/foreach}
			]
		}]
	});

    dt_per_opd = $('#per_opd').DataTable({
        "processing": true,
        "responsive": true,
        "serverSide": false,
        "pageLength": 10,
        "lengthMenu": [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        "paging": true,
        "pagingType": "numbers",
        dom: "Bfrtip",
        "buttons": [
            // {
            //     extend: 'excelHtml5',
            //     text: 'Ekspor ke XLS',
            //     className: 'btn-sm btn-primary',
            //     exportOptions: {
            //         modifier: {
            //             //selected: true
            //         }
            //     },
            // },
        ],
        select: true,
        "language": {
            "sProcessing": "{__('Processing')}",
            "sLengthMenu": "{__('Menampilkan')} _MENU_ {__('baris')}",
            "sZeroRecords": "{__('No data')}",
            "sInfo": "{__('Menampilan')} _START_ - _END_ {__('dari')} _TOTAL_ {__('baris')}",
            "sInfoEmpty": "{__('Menampilan')} 0 {__('dari')} 0 {__('baris')}",
            "sInfoFiltered": "{__('Difilter dari')} _MAX_ {__('total baris')}",
            "sInfoPostFix": "",
            "sSearch": "{__('Mencari')}",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "{__('Pertama')}",
                "sPrevious": "{__('Sebelum')}",
                "sNext": "{__('Setelah')}",
                "sLast": "{__('Terakhir')}"
            }
        },
        "ajax": "{$site_url}admin/home/per_opd",
        "columns": [
            {
                data: "label",
                width: "30%",
                className: "text-left",
                orderable: 'true',
            },
            {
                data: "total",
                className: "text-center",
                orderable: 'true',
            },
            {
                data: "terverifikasi",
                className: "text-center",
                orderable: 'true',
            },
            {
                data: "perlu_verifikasi",
                className: "text-center",
                orderable: 'true',
            },
            {
                data: "jenis_roda_dua",
                className: "text-center",
                orderable: 'true',
            },
            {
                data: "jenis_roda_tiga",
                className: "text-center",
                orderable: 'true',
            },
            {
                data: "jenis_mobil",
                className: "text-center",
                orderable: 'true',
            },
            // {
            //     data: "jenis_lainnya",
            //     className: "text-center",
            //     orderable: 'true',
            // },
            {
                data: "peruntukan_perorangan",
                className: "text-center",
                orderable: 'true',
            },
            {
                data: "peruntukan_jabatan",
                className: "text-center",
                orderable: 'true',
            },
            {
                data: "peruntukan_operasional",
                className: "text-center",
                orderable: 'true',
            },
            {
                data: "peruntukan_khusus",
                className: "text-center",
                orderable: 'true',
            },
            {
                data: "peruntukan_pinjam_pakai",
                className: "text-center",
                orderable: 'true',
            },
            // {
            //     data: "peruntukan_lainnya",
            //     className: "text-center",
            //     orderable: 'true',
            // },
        ]
    });
});

</script>