<link href="{$base_url}assets/highcharts/css/highcharts.css" rel="stylesheet" />

<script src="{$base_url}assets/highcharts/highcharts.js"></script>
<script src="{$base_url}assets/highcharts/highcharts-more.js"></script>
<script src="{$base_url}assets/highcharts/themes/grid-light.js"></script>

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
                                        {for $year = date('Y'); $year <= date('Y')+5; $year++}
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

<section id="detail" class="content">
    <div class="container-fluid">

<div class="row ">
	<div class="col-xl-12">
		<div class="info-box bg-white" style="min-height: 0px;">
			<div class="info-box-content">
			<div class="page-title" style="display: flex;"><h4 class="item-name" style="margin: auto; margin-bottom: 0px;">PILIH BEKAL</h4>
			</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12 col-sm-12">	
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="demandprofile-chart" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-sm-12 col-md-6 col-xl-4">
		<div class="card widget-inline">
			<!--  <div class="card-header">Kebutuhan</div> -->
			<div class="card-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive-sm">
						<table id="tdemand" class="table table-striped dt-responsive nowrap" width="100%">
							<thead>
								<tr>
									<th class="text-center" data-priority="1">Kebutuhan</th>
									<th class="text-center" data-priority="1">Jumlah</th>
									<th class="text-center" data-priority="1">Tanggal</th>
								</tr>
							</thead>
						</table>
					</div>			
				</div>
			</div>
			</div>
			<div class="card-footer">Total: <span id="total_demand"></span></div>
		</div> <!-- end card-box-->
	</div> <!-- end col-->
	<div class="col-12 col-sm-12 col-md-6 col-xl-4">
		<div class="card widget-inline">
			<!-- <div class="card-header">Pengadaan</div> -->
			<div class="card-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive-sm">
						<table id="tpo" class="table table-striped dt-responsive nowrap" width="100%">
							<thead>
								<tr>
									<th class="text-center" data-priority="1">Pengadaan</th>
									<th class="text-center" data-priority="1">Jumlah</th>
									<th class="text-center" data-priority="1">Pengiriman</th>
									<th class="text-center" data-priority="1">Status</th>
								</tr>
							</thead>
						</table>
					</div>			
				</div>
			</div>
			</div>
			<div class="card-footer">Total: <span id="total_po"></span></div>
		</div> <!-- end card-box-->
	</div> <!-- end col-->
	<div class="col-12 col-sm-12 col-md-6 col-xl-4">
		<div class="card widget-inline">
			<!-- <div class="card-header">Stok Awal/Gudang</div> -->
			<div class="card-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="table-responsive-sm">
						<table id="tstock" class="table table-striped dt-responsive nowrap" width="100%">
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
			<div class="card-footer">Total: <span id="total_stock"></span></div>
		</div> <!-- end card-box-->
	</div> <!-- end col-->

	</div>
</div>

	</div>
</section>

<script type="text/javascript">


</script>

{if $crud.filter || $crud.search}
{include file='crud/_js-crud-filter.tpl'}
{/if}

<script type="text/javascript">
    //override default filter value. must be after include js-crud-filter.tpl
    v_itemtypeid = '{if !empty($userdata["itemtypeid"])}{$userdata["itemtypeid"]}{/if}';
    v_siteid = '{if !empty($userdata["siteid"])}{$userdata["siteid"]}{/if}';
    v_year = new Date().getFullYear();

    if (v_itemtypeid!='' && v_itemtypeid!=0) {
        $("#f_itemtypeid").attr("disabled", true);
    }
</script>

<script type="text/javascript" defer>

    var dt_tdata_171 = null;
	var dt_tdemand = null;
	var dt_tpo = null;
	var dt_tstock = null;
	var v_itemid = null;
	var v_itemlabel = null;

    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'throw';
        $.extend($.fn.dataTable.defaults, {
            responsive: true,
        });
        $.extend(true, $.fn.dataTable.Editor.defaults, {
            formOptions: {
                main: {
                    onBackground: 'none'
                },
                bubble: {
                    onBackground: 'none'
                }
            }
        });

        var tdata_171_refresh = debounce(function(api) {
            //recalc responsive columns
            api.columns.adjust().responsive.recalc();

			//custom select/deselect routine
			let data = api.rows('.selected').data();

			if (data.length == 1) {
				//$("#detail").show();
				show_item_detail(data[0]);
			}
			else {
				//$("#detail").hide();
				show_item_detail(null);
			}
        }, 500);

        // Setup - add a text input to each footer cell
        $('#tdata_171 thead tr').clone(true).addClass('filters').addClass('d-none').appendTo('#tdata_171 thead');

        //easy access
        api_tdata_171 = null;

        dt_tdata_171 = $('#tdata_171').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": false,
            "scrollX": false,
            orderCellsTop: true,
            fixedHeader: true,
            "pageLength": 5,
            "lengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100, 200, "All"]],
            "paging": true,
            "pagingType": "numbers",
            dom: "t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
            select: 'single',
            buttons: {
                buttons: [],
            },
            "language": {
                "sProcessing": "Processing",
                "sLengthMenu": "Menampilkan _MENU_ baris",
                "sZeroRecords": "No data",
                "sInfo": "Menampilan _START_ - _END_ dari _TOTAL_ baris",
                "sInfoEmpty": "Menampilan 0 dari 0 baris",
                "sInfoFiltered": "Difilter dari _MAX_ total baris",
                "sInfoPostFix": "",
                "sSearch": "Mencari",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelum",
                    "sNext": "Setelah",
                    "sLast": "Terakhir"
                }
            },
            rowId: 'itemid',
            "ajax": {
                "url": "{$site_url}{$controller}/analisakebutuhan/json",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
					d.f_itemtypeid = v_itemtypeid;
					d.f_siteid = v_siteid;
					d.f_year = v_year;
                    return d;
                }
            },
            "columns": [
            {
                visible: false,
                data: "year",
                editField: "year",
                className: "col_tcg_text   no-export " + "" + "",
                orderable: true,
            },
            {
                data: "itemid_label",
                editField: "itemid",
                className: "col_tcg_select2   no-export " + "" + "",
                orderable: true,
                render: function(data, type, row) {
                    if (data == null) {
                        return data;
                    }

                    return data;
                }
            },
            {
                visible: false,
                data: "categoryid",
                editField: "categoryid",
                className: "col_tcg_select2   no-export " + "" + "",
                orderable: true,
                render: function(data, type, row) {
                    if (data == null) {
                        return data;
                    }

                    return data;
                }
            },
            {
                visible: false,
                data: "itemtypeid",
                editField: "itemtypeid",
                className: "col_tcg_select2 no-export " + "" + "",
                orderable: true,
                render: function(data, type, row) {
                    if (data == null) {
                        return data;
                    }

                    return data;
                }
            },
            {
                data: "demand",
                editField: "demand",
                className: "col_tcg_text text-center  no-export " + "" + "",
                orderable: true,
            },
            {
                data: "stock",
                editField: "stock",
                className: "col_tcg_text text-center  no-export " + "" + "",
                orderable: true,
            },
            {
                data: "po",
                editField: "po",
                className: "col_tcg_text text-center  no-export " + "" + "",
                orderable: true,
            },
            {
                data: "remaining",
                editField: "remaining",
                className: "col_tcg_text text-center  no-export " + "" + "",
                orderable: true,
            }, ],
            order: [[1, 'asc'], ],
            initComplete: function() {
                var api = this.api();

                // For each column
                api.columns().eq(0).each(function(colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('#tdata_171 .filters th').eq($(api.column(colIdx).header()).index());

                    var title = $(cell).text().trim();
                    var col_filter = cell.attr('tcg-column-filter');
                    if ($(api.column(colIdx).header()).index() >= 0 && col_filter == 1) {
                        $(cell).html('<input type="text" placeholder="' + title + '"/>');
                    } else {
                        $(cell).html('');
                    }

                    // On every keypress in this input
                    $('input', cell).off('keyup change').on('change', function(e) {
                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({ search })';

                        var cursorPosition = this.selectionStart;
                        // Search the column for that value
                        api.column(colIdx).search(this.value != '' ? regexr.replace('{ search }', '(((' + this.value + ')))') : '', this.value != '', this.value == '').draw();
                    }).on('keyup', function(e) {
                        e.stopPropagation();

                        var cursorPosition = this.selectionStart;

                        $(this).trigger('change');
                        $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                    });

                    //show/hide cell based on col's responsive status
                    var col = api.column(colIdx);
                    if (col.responsiveHidden()) {
                        cell.show();
                    } else {
                        cell.hide();
                    }

                });

                //show the filter row
                $('#tdata_171 thead tr').removeClass("d-none");

                api_tdata_171 = this.api();
                dt_tdata_171_initialized = true;
            },
            "createdRow": function(row, data, index) {
                if ($('ul.dropdown-menu span', row).length == 0) {
                    $('.btn-dropdown', row).addClass('d-none')
                }
            },
            "footerCallback": function(row, data, start, end, display) {
                tdata_171_refresh(this.api());
             },
        });

        dt_tdata_171.on('select.dt deselect.dt', function(e, settings) {
            let that = dt_tdata_171;
            let api = new $.fn.dataTable.Api(settings);
            tdata_171_refresh(api);
        });

        dt_tdata_171.on('responsive-resize', function(e, api, columns) {
            api.columns().eq(0).each(function(colIdx) {
                var cell = $('#tdata_171 .filters th').eq($(api.column(colIdx).header()).index());

                var col = api.column(colIdx);
                if (col.responsiveHidden()) {
                    cell.show();
                } else {
                    cell.hide();
                }
            });
        });

        dt_tdata_171.on('page.dt', function(e, settings) {
            var api = new $.fn.dataTable.Api(settings);
            tdata_171_refresh(api);
        });

        dt_tdata_171.on('order.dt search.dt', function(e, settings) {
            //refresh responsive table
            var api = new $.fn.dataTable.Api(settings);
            tdata_171_refresh(api);
        }).draw();

        dt_tdata_171.on("user-select.dt", function(e, dt, type, cell, originalEvent) {
            var $elem = $(originalEvent.target);
            // get element clicked on
            var tag = $elem[0].nodeName.toLowerCase();
            // get element's tag name

            if (!$elem.closest("div.dt-row-actions").length) {
                return;
                // ignore any element not in the dropdown
            }

            if (tag === "i" || tag === "a" || tag === "button") {
                return false;
                // cancel the select event for the row
            }
        });
		
        var tdemand_refresh = debounce(function(api) {
            //recalc responsive columns
            api.columns.adjust().responsive.recalc();

			// //calculate total count
			// let total = api
			// 	.column( '1:visIdx' )
			// 	.data()
			// 	.reduce( function (a, b) {
			// 		return parseInt(a) + parseInt(b);
			// 	}, 0 );

			// $("#total_demand").html(total);

        }, 1000);

        dt_tdemand = $('#tdemand').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": false,
            "scrollX": false,
            orderCellsTop: true,
            fixedHeader: true,
            "pageLength": 5,
            "paging": true,
            "pagingType": "numbers",
            dom: "t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
            select: 'single',
            "language": {
                "sProcessing": "Processing",
                "sLengthMenu": "Menampilkan _MENU_ baris",
                "sZeroRecords": "No data",
                "sInfo": "Menampilan _START_ - _END_ dari _TOTAL_ baris",
                "sInfoEmpty": "Menampilan 0 dari 0 baris",
                "sInfoFiltered": "Difilter dari _MAX_ total baris",
                "sInfoPostFix": "",
                "sSearch": "Mencari",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelum",
                    "sNext": "Setelah",
                    "sLast": "Terakhir"
                }
            },
            "columns": [
            {
                data: "demandnum",
                className: "col_tcg_select2   no-export ",
                orderable: true,
                render: function(data, type, row) {
                    if (data == null) {
                        return data;
                    }

                    let id = row['demandid'];
                    data += ' <a target="_blank" href="{$site_url}{$controller}/kebutuhan/detail/' +id+ '" data-toggle="tooltip" data-placement="top" title="Buka Detail">'
                            + '<i class="fa fas fa-external-link-alt"></i></a>';

					return data;
                }
            },
            {
                data: "count",
                className: "col_tcg_text text-center  no-export ",
                orderable: true,
            },
            {
                data: "demanddate",
                className: "col_tcg_select2 no-export ",
                orderable: true,
                render: function(data, type, row) {
                    if (data == null) {
                        return data;
                    }

                    return moment.utc(data).local().format('YYYY-MM-DD');;
                }
            }, ],
            order: [[1, 'asc'], ],
            "footerCallback": function(row, data, start, end, display) {
                tdemand_refresh(this.api());
             },
        });

        dt_tdemand.on('page.dt', function(e, settings) {
            var api = new $.fn.dataTable.Api(settings);
            tdemand_refresh(api);
        });

        dt_tdemand.on('order.dt search.dt', function(e, settings) {
            //refresh responsive table
            var api = new $.fn.dataTable.Api(settings);
            tdemand_refresh(api);
        }).draw();

        var tpo_refresh = debounce(function(api) {
            //recalc responsive columns
            api.columns.adjust().responsive.recalc();

			// //calculate total count
			// let total = api
			// 	.column( '1:visIdx' )
			// 	.data()
			// 	.reduce( function (a, b) {
			// 		return parseInt(a) + parseInt(b);
			// 	}, 0 );

			// $("#total_po").html(total);

        }, 1000);

        dt_tpo = $('#tpo').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": false,
            "scrollX": false,
            orderCellsTop: true,
            fixedHeader: true,
            "pageLength": 5,
            "paging": true,
            "pagingType": "numbers",
            dom: "t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
            select: 'single',
            "language": {
                "sProcessing": "Processing",
                "sLengthMenu": "Menampilkan _MENU_ baris",
                "sZeroRecords": "No data",
                "sInfo": "Menampilan _START_ - _END_ dari _TOTAL_ baris",
                "sInfoEmpty": "Menampilan 0 dari 0 baris",
                "sInfoFiltered": "Difilter dari _MAX_ total baris",
                "sInfoPostFix": "",
                "sSearch": "Mencari",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelum",
                    "sNext": "Setelah",
                    "sLast": "Terakhir"
                }
            },
            "columns": [
            {
                data: "ponum",
                className: "col_tcg_select2   no-export ",
                orderable: true,
                render: function(data, type, row) {
                    if (data == null) {
                        return data;
                    }

                    let id = row['poid'];
                    data += ' <a target="_blank" href="{$site_url}{$controller}/pengadaan/detail/' +id+ '" data-toggle="tooltip" data-placement="top" title="Buka Detail">'
                            + '<i class="fa fas fa-external-link-alt"></i></a>';

					return data;
                }
            },
            {
                data: "count",
                className: "col_tcg_text text-center  no-export ",
                orderable: true,
            },
            {
                data: "deliverydate",
                className: "col_tcg_select2 no-export ",
                orderable: true,
                render: function(data, type, row) {
                    if (data == null || data == '') {
                        data = row['targetdeliverydate'];
                    }

					if (data == null || data == '')		return data;
					
                    data = moment.utc(data).local().format('YYYY-MM-DD');

					status = row['status']
					if (status == 'APPR' || status == 'TENDER' || status == 'CONTRACT') {
						data += "*";
					}

					return data;
                }
            }, 
            {
                data: "status",
                className: "col_tcg_text text-center  no-export ",
                orderable: true,
            },
			],
            order: [[1, 'asc'], ],
            "footerCallback": function(row, data, start, end, display) {
                tpo_refresh(this.api());
             },
        });

        dt_tpo.on('page.dt', function(e, settings) {
            var api = new $.fn.dataTable.Api(settings);
            tpo_refresh(api);
        });

        dt_tpo.on('order.dt search.dt', function(e, settings) {
            //refresh responsive table
            var api = new $.fn.dataTable.Api(settings);
            tpo_refresh(api);
        }).draw();

        var tstock_refresh = debounce(function(api) {
            //recalc responsive columns
            api.columns.adjust().responsive.recalc();

			// //calculate total count
			// let total = api
			// 	.column( '1:visIdx' )
			// 	.data()
			// 	.reduce( function (a, b) {
			// 		return parseInt(a) + parseInt(b);
			// 	}, 0 );

			// $("#total_stock").html(total);

        }, 1000);

        dt_tstock = $('#tstock').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": false,
            "scrollX": false,
            orderCellsTop: true,
            fixedHeader: true,
            "pageLength": 5,
            "paging": true,
            "pagingType": "numbers",
            dom: "t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
            select: 'single',
            "language": {
                "sProcessing": "Processing",
                "sLengthMenu": "Menampilkan _MENU_ baris",
                "sZeroRecords": "No data",
                "sInfo": "Menampilan _START_ - _END_ dari _TOTAL_ baris",
                "sInfoEmpty": "Menampilan 0 dari 0 baris",
                "sInfoFiltered": "Difilter dari _MAX_ total baris",
                "sInfoPostFix": "",
                "sSearch": "Mencari",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelum",
                    "sNext": "Setelah",
                    "sLast": "Terakhir"
                }
            },
            "columns": [
            {
                data: "storecode",
                className: "col_tcg_select2   no-export ",
                orderable: true,
                render: function(data, type, row) {
                    if (data == null) {
                        return data;
                    }

                    return data;
                }
            },
            {
                data: "count",
                className: "col_tcg_text text-center  no-export ",
                orderable: true,
            }, 
			],
            order: [[1, 'asc'], ],
            "footerCallback": function(row, data, start, end, display) {
                tstock_refresh(this.api());
             },
        });

        dt_tstock.on('page.dt', function(e, settings) {
            var api = new $.fn.dataTable.Api(settings);
            tstock_refresh(api);
        });

        dt_tstock.on('order.dt search.dt', function(e, settings) {
            //refresh responsive table
            var api = new $.fn.dataTable.Api(settings);
            tstock_refresh(api);
        }).draw();
    });



</script>

<script type="text/javascript">
	function show_item_detail(data) {
		if (data == null) {
			$('.item-name').html("PILIH BEKAL");

			hdemandprofile.setTitle({ text: "Profil Kebutuhan" });
			hdemandprofile.setTitle(null, { text: "" });
			//hdemandprofile.yAxis[0].setExtremes(null, max);
			hdemandprofile.series[0].setData( [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0] );
			hdemandprofile.series[1].setData( [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0] );
			hdemandprofile.series[2].setData( [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0] );
			hdemandprofile.redraw();

			dt_tdemand.clear().draw();
			dt_tpo.clear().draw();
			dt_tstock.clear().draw();
		}
		else {
			v_itemlabel = data['itemid_label'];
			v_itemid = data['itemid'];
			v_year = $("#f_year").val();
			v_siteid = $("#f_siteid").val();

			$('.item-name').html(v_itemlabel.toUpperCase()); 
			load_demandprofile(v_itemid, v_itemlabel, v_year, v_siteid);

			dt_tdemand.ajax.url("{$base_url}disbekal/analisakebutuhan/demand?id=" +v_itemid+ "&year=" +v_year+ "&siteid=" +v_siteid);
			dt_tdemand.ajax.reload(tdemand_post_load, false);
			dt_tpo.ajax.url("{$base_url}disbekal/analisakebutuhan/po?id=" +v_itemid+ "&year=" +v_year+ "&siteid=" +v_siteid);
			dt_tpo.ajax.reload(tpo_post_load, false);
			dt_tstock.ajax.url("{$base_url}disbekal/analisakebutuhan/stock?id=" +v_itemid+ "&year=" +v_year+ "&siteid=" +v_siteid);
			dt_tstock.ajax.reload(tstock_post_load, false);
		}
	}

	var hdemandprofile = null;

	{literal}
	hdemandprofile = Highcharts.chart('demandprofile-chart', {
		chart: {
			zoomType: 'xy'
		},
		title: {
			text: 'Profil Kebutuhan, 2024',
			align: 'left'
		},
		subtitle: {
			text: '<Nama-Bekal>',
			align: 'left'
		},
		xAxis: [{
			categories: [
				'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
				'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
			],
			crosshair: true
		}],
		yAxis: [{ // Primary yAxis
			labels: {
				format: '{value}',
				style: {
					color: Highcharts.getOptions().colors[0]
				}
			},
			title: {
				text: 'Jumlah',
				style: {
					color: Highcharts.getOptions().colors[0]
				}
			}
		}],
		tooltip: {
			shared: true
		},
		legend: {
			align: 'left',
			x: 80,
			verticalAlign: 'top',
			y: 60,
			floating: true,
			backgroundColor:
				Highcharts.defaultOptions.legend.backgroundColor || // theme
				'rgba(255,255,255,0.25)'
		},
		series: [
		{
			name: 'Kebutuhan',
			type: 'column',
			// yAxis: 1,
			data: [
				0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
 			],
		}, 
		{
			name: 'Pengadaan',
			type: 'column',
			// yAxis: 1,
			data: [
				0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
			],
		}, 
		{
			name: 'Perkiraan Stok',
			type: 'spline',
			data: [
				0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
			],
		}]
	});
	{/literal}

	function load_demandprofile(id, label, year, siteid) {
       	//retrieve list from json
	   	$.ajax({
            url: "{$base_url}disbekal/analisakebutuhan/timeseries?id=" +id+ "&year=" +year+ "&siteid=" +siteid,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status != 1 || response.data === null) {
					return;
                }

				//get maximum value
				let max = 0;
				for(let i=0; i<12; i++) {
					val = parseFloat(response.data['demand'][i]);
					if (max < val)	max=val;
					val = parseFloat(response.data['po'][i]);
					if (max < val)	max=val;
					val = parseFloat(response.data['totalstock'][i]);
					if (max < val)	max=val;
				}

				max = max + (max*40/100);

				hdemandprofile.setTitle({ text: "Profil Kebutuhan, " +year });
				hdemandprofile.setTitle(null, { text: label });
				hdemandprofile.yAxis[0].setExtremes(null, max);
				hdemandprofile.series[0].setData(response.data['demand']);
				hdemandprofile.series[1].setData(response.data['po']);
				hdemandprofile.series[2].setData(response.data['totalstock']);
				hdemandprofile.redraw();
            },
            error: function(jqXhr, textStatus, errorMessage) {
				//TODO
            }
        });

	}

	function dt_tdata_171_post_load(json) {
		//select first row
		dt_tdata_171.row(0).select();
	}

	function tdemand_post_load(json) {
		$("#total_demand").html(json.total);
	}

	function tpo_post_load(json) {
		$("#total_po").html(json.total);
	}

	function tstock_post_load(json) {
		$("#total_stock").html(json.total);
	}

</script>

{include file='crud/_js.tpl'}