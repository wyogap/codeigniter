
<script type="text/javascript" defer> 

var base_url = "{$base_url}";
var site_url = "{$site_url}";
var ajax_url = "{$tbl.ajax}";

var editor_{$tbl.table_id} = null;
var dt_{$tbl.table_id} = null;

{if empty($fsubtable)}
{assign var=fsubtable value=0}
{/if}

{if empty($fkey)}
{assign var=fkey value=0}
{/if}

$(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'throw';
    $.extend($.fn.dataTable.defaults, {
        responsive: true
    });

    {if $tbl.editor}
        editor_{$tbl.table_id} = new $.fn.dataTable.Editor({
            ajax: "{$tbl.ajax}",
            table: "#{$tbl.table_id}",
            idSrc: "{$tbl.key_column}",
            fields: [
                {if !empty($level1_column)}
                {
                    name: "{$level1_column}",
                    type: "hidden"
                },
                {/if}
                {foreach $tbl.editor_columns as $col}
                {
                    label: "{$col.edit_label} {if $col.edit_label && $col.edit_compulsory}<span class='text-danger font-weight-bold'>*</span>{/if}",

                    {if $col.edit_field|@count==1}
                    name: "{$col.edit_field[0]}",
                    {else if $col.edit_field|@count>1}
                    name: "{$col.name}",
                    {/if}

                    {if $col.edit_type == 'js'}
                    type: 'hidden',
                    {else if $col.edit_type == 'tcg_currency'}
                    type: 'tcg_mask',
                    mask: "#{$currency_thousand_separator}##0",
                    {else if $col.edit_field|@count>1}
                    type: "tcg_readonly",
                    {else}
                    type: '{$col.edit_type}',
                    {/if}

                    {if isset($col.edit_options)}
                    options: [
                        {foreach from=$col.edit_options key=k item=v}
                        {if is_array($v)}
                            { label: "{$v.label}", value: "{$v.value}" },
                        {else}
                            { label: "{$v}", value: "{$k}" },
                            {/if}
                        {/foreach}
                    ],
                    {/if}

                    {if !empty($col.edit_attr)}
                    attr: {$col.edit_attr|@json_encode nofilter},
                    {/if}

                    {if !empty($col.options_data_url) && $col.edit_type=='tcg_select2'}
                    {if $col.options_data_url_params|@count==0}
                    ajax: "{$site_url}{$col.options_data_url}",
                    {/if}
                    {/if}

                    {if !empty($col.edit_info)}
                    fieldInfo:  "{$col.edit_info}",
                    {/if}

                    {if $fsubtable == 1 && $col.edit_field[0] == $fkey}
                    readonly: 1,
                    {/if}

                    {if isset($col.edit_def_value) && $col.edit_def_value != null}
                    def:  "{$col.edit_def_value}",
                    {/if}

                    {if $col.edit_type=='upload' || $col.edit_type=='image'}
                    display: function ( file_id ) {
                        if (!Number.isInteger(file_id)) {
                            return '<img src="'+file_id+'"/>';
                        }
                        
                        let files = null;
                        let file = null;
                        try {
                            files = editor.files('files');
                            file = files[file_id];
                        }
                        catch(err) {
                            //ignore
                        }

                        if (file !== null) {
                            return '<img src="'+editor.file( 'files', file_id ).thumbnail+'"/>';
                        }
                        else if (file_id != ""){
                            return '<img src="'+file_id+'"/>';
                        }
                        else {
                            return "";
                        }
                    },
                    clearText: "{__('Hapus')}",
                    noImageText: "{__('No image')}",
                    uploadText: "{__('Pilih fail...')}",
                    noFileText: "{__('No file')}",
                    processingText: "{__('Mengunggah')}",
                    fileReadText: "{__('Membaca fail')}",
                    dragDropText: "{__('Tarik dan taruh fail di sini untuk mengunggah')}"
                    {/if}
                },
                {/foreach}
            ],
            formOptions: {
                main: {
                    submit: 'changed'
                }
            },
            i18n: {
                create: {
                    button: "{__('Baru')}",
                    title: "{__('Buat')} {$tbl.name}",
                    submit: "{__('Simpan')}"
                },
                edit: {
                    button: "{__('Ubah')}",
                    title: "{__('Ubah')} {$tbl.name}",
                    submit: "{__('Simpan')}"
                },
                remove: {
                    button: "{__('Hapus')}",
                    title: "{__('Hapus')} {$tbl.name}",
                    submit: "{__('Hapus')}",
                    confirm: {
                        _: "{__('Konfirmasi menghapus')} %d {$tbl.name}?",
                        1: "{__('Konfirmasi menghapus')} 1 {$tbl.name}?"
                    }
                },
                error: {
                    system: "{__('System error. Hubungi system administrator.')}"
                },
                datetime: {
                    previous: "{__('Sebelum')}",
                    next: "{__('Setelah')}",
                    months: [
                        "{__('Januari')}", 
                        "{__('Februari')}", 
                        "{__('Maret')}", 
                        "{__('April')}", 
                        "{__('Mei')}", 
                        "{__('Juni')}", 
                        "{__('Juli')}", 
                        "{__('Augustus')}",
                        "{__('September')}", 
                        "{__('Oktober')}", 
                        "{__('November')}", 
                        "{__('Desember')}"
                    ],
                    weekdays: [
                        "{__('Min')}", 
                        "{__('Sen')}", 
                        "{__('Sel')}", 
                        "{__('Rab')}", 
                        "{__('Kam')}", 
                        "{__('Jum')}", 
                        "{__('Sab')}"
                    ],
                    hour: "{__('Jam')}",
                    minute: "{__('Menit')}"
                }
            }
        });

        editor_{$tbl.table_id}.on( 'open' , function ( e, type ) {
            let data = this.s.editData;
            let url = '';
            let col = '';
            let val = '';
            {foreach $tbl.editor_columns as $col}
                {if $fsubtable == 1 && $col.edit_field[0] == $fkey}
                editor_{$tbl.table_id}.field("{$col.edit_field[0]}").set(selected_key_{$tbl.table_id});
                {else if $col.edit_type == 'js' }
                editor_{$tbl.table_id}.field("{$col.edit_field[0]}").set(v_{$col.name});
                {/if}

                {if !empty($col.options_data_url) && $col.edit_type=='tcg_select2'}
                {if $col.options_data_url_params|@count>0}
                url = "{$site_url}{$col.options_data_url}";

                {foreach $col.options_data_url_params as $param}
                col = "{$param}";
                val = data[col];
                if (typeof val !== 'undefined' && val !== null) {
                    //just get the first value (in case multiple value)
                    let idx = Object.keys(val)[0];
                    val = val[ idx ];
                    col = "{literal}{{{/literal}" + col + "{literal}}}{/literal}";
                    url = url.replace(col, val);
                }
                {/foreach}

                if (url.length > 0) {
                    this.field("{$col.edit_field[0]}").ajax(url);
                    this.field("{$col.edit_field[0]}").reload();
                }
                {/if}
                {/if}
            {/foreach}
        });

        editor_{$tbl.table_id}.on('preSubmit', function(e, o, action) {
            if (action === 'create' || action === 'edit') {
                let field = null;

                {foreach $tbl.editor_columns as $col}
                    {if isset($col.edit_compulsory) && $col.edit_compulsory == true && $col.edit_type != 'tcg_toggle' && $col.edit_type != 'tcg_readonly'}
                    field = this.field('{$col.edit_field[0]}');
                    if (!field.isMultiValue()) {
                        if (!field.val() || field.val() == 0) {
                            field.error('{__("Harus diisi")}');
                        }
                    }
                    {/if}
                {/foreach}

                /* If any error was reported, cancel the submission so it can be corrected */
                if (this.inError()) {
                    this.error('{__("Data wajib belum diisi")}');
                    return false;
                }
            }

            /* dont sent all data for remove */
            if (action === 'remove') {
                $.each(o.data, function (key, val) {
                    o.data[key] = {};
                    o.data[key].{$tbl.key_column} = key;
                });
            }

            /* set the hidden js field */
            {foreach $tbl.editor_columns as $col}
                {if $col["edit_type"] == "js"}
                $.each(o.data, function (key, val) {
                    o.data[key].{$col.edit_field[0]} = v_{$col.name};
                });
                {/if}
            {/foreach}

            /* level1 hidden field */
            {if !empty($level1_column)}
                $.each(o.data, function (key, val) {
                    o.data[key].{$level1_column} = {$level1_id};
                });
            {/if}
        });        

        editor_{$tbl.table_id}.on('postSubmit', function(e, json, data, action, xhr) {
            if (action=="upload") {

            }

        });

        {foreach $tbl.columns as $col}
            {if isset($col.edit_event_js)}
            $(editor_{$tbl.table_id}.field('{$col.edit_field[0]}').node()).on('change', function() {
                let val = editor.field('{$col.edit_field[0]}').val();
                {$col['edit_event_js']} (val, editor_{$tbl.table_id});
            });
            {/if}
        {/foreach}

        /* Activate the bubble editor on click of a table cell */
        $('#{$tbl.table_id}').on( 'click', 'tbody td.editable', function (e) {
            editor_{$tbl.table_id}.bubble( this, {
                buttons: [
                    {
                        text: "{__('Batal')}",
                        className: 'btn-sm btn-secondary mr-md-1',
                        action: function () {
                            this.close();
                        }
                    },
                    {
                        text: "{__('Simpan')}",
                        className: 'btn-sm btn-primary',
                        action: function () {
                            this.submit();
                        }
                    },
                ]   
            });
        } );

        /* Inline editing in responsive cell */
        $('#{$tbl.table_id}').on( 'click', 'tbody ul.dtr-details li', function (e) {
            /* Ignore the Responsive control and checkbox columns */
            if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
                return;
            }
    
            /* ignore read-only column */
            var editable = false;
            var colnum = $(this).attr( 'data-dt-column' );
            {assign var=i value=0}
            {foreach from=$tbl.columns key=k item=v}
            {if $v.visible == 1}
            {if !empty($v.edit_bubble)}
            //{$v.name}
            if ( colnum == {$i} ) {
                editable = true;
            }            
            {/if}
            {assign var=i value=$i+1}
            {/if}
            {/foreach}

            /* Edit the value, but this method allows clicking on label as well */
            if (editable) {
                editor_{$tbl.table_id}.bubble( $('span.dtr-data', this), {
                    buttons: [
                        {
                            text: "{__('Batal')}",
                            className: 'btn-sm btn-secondary mr-md-1',
                            action: function () {
                                this.close();
                            }
                        },
                        {
                            text: "{__('Simpan')}",
                            className: 'btn-sm btn-primary',
                            action: function () {
                                this.submit();
                            }
                        },
                    ]
                });
            }
        });

        //hack: somehow the footer is nested inside the body.
        //TODO: find the real reason why it happens (note: in most cases, it does not happen)
        //NOTE: in localhost/sngine, which uses older version of this code, this does not happen!
        editor_{$tbl.table_id}.on('open', function () {
            $('div.DTE_Body').after( $('div.DTE_Footer') );
        });

    {/if}

    dt_{$tbl.table_id} = $('#{$tbl.table_id}').DataTable({
        "processing": true,
        "responsive": true,
        "serverSide": false,
        {if !empty($tbl.page_size)}
        "pageLength": {$tbl.page_size},
        {else}
        "pageLength": 25,
        {/if}
        "lengthMenu": [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        "paging": true,
        "pagingType": "numbers",
        dom: "<'row'<'col-sm-12 col-md-7 dt-action-buttons'B><'col-sm-12 col-md-5'fr>>t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
        select: true,
        buttons: {
            buttons: 
            [
                {if $tbl.editor}
                {if isset($tbl.table_actions) && $tbl.table_actions.add}
                {
                    extend: "create",
                    editor: editor_{$tbl.table_id},
                    className: 'btn-sm'
                },
                {/if}
                {if isset($tbl.table_actions) && $tbl.table_actions.edit}
                {
                    extend: "edit",
                    editor: editor_{$tbl.table_id},
                    className: 'btn-sm btn-info'
                },
                {/if}
                {if isset($tbl.table_actions) && $tbl.table_actions.delete}
                {
                    extend: "remove",
                    editor: editor_{$tbl.table_id},
                    className: 'btn-sm btn-danger'
                },
                {/if}
                {/if}
            ],
        },
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
        {if !$tbl.initial_load}
        "ajax": function(
            data, callback, settings) {
            dt_{$tbl.table_id}_ajax_load(data).then(function(_data) {
                callback(_data);
            });
        },
        {else if !empty($detail)}
        "ajax": "{$tbl.ajax}",
        {else}
        "ajax": {
            "url": "{$tbl.ajax}",
            "dataType": "json",
            "type": "POST",
            "data": function(d) {
                {foreach $tbl.filter_columns as $f}
                d.f_{$f.name} = v_{$f.name};
                {/foreach}
                return d;
            }
        },
        {/if} 
        "columns": [
            {if $tbl.row_select_column}
            {
                data: null,
                className: "text-right",
                orderable: 'false',
                defaultContent: ''
            },
            {/if}
            {if $tbl.row_id_column}
            {
                data: null,
                className: "text-right",
                orderable: 'false',
                defaultContent: ''
            },
            {/if}
            {foreach $tbl.columns as $x}
                {if $x.visible == 1}
                { 
                    {if $x.foreign_key && $x.type=="tcg_select2"}
                        data: "{$x.name}_label", 
                        editField: "{$x.name}", 
                    {else}
                        data: "{$x.name}", 
                        {if !empty($x.edit_field)}
                        {if $x.edit_field|@count == 1}
                        editField: "{$x.edit_field[0]}",
                        {else if $x.edit_field|count > 1}
                        editField: [ {foreach $x.edit_field as $field}"{$field}",{/foreach} ],
                        {/if}
                        {/if}
                    {/if}
                    className: "col_{$x.type} {$x.css} {if !empty($x.edit_bubble)}editable{/if}",
                    {if isset($x.type) && $x.type=="tcg_select2"}
                    render: function ( data, type, row ) {
                        // if (type == "export") {
                        //     //export raw data?
                        // }
                        return data;
                    }
                    {else if isset($x.type) && $x.type=="tcg_upload"}
                    render: function ( data, type, row ) {
                        if (type == "export") {
                            if (typeof data === 'undefined' || data == null) {
                                return '';
                            }
                            //put extra space after comma so that it is not treated as thousand separator
                            return data.replace(/,/g, ', ');
                        }

                        if (type == "display") {
                            let filename = row['{$x.name}_filename'];
                            let path = row['{$x.name}_path'];
                            if (typeof data !== 'undefined' && data !== null && data != "" && data != 0
                                    && typeof filename !== 'undefined' && filename !== null && filename != ""
                                    && typeof path !== 'undefined' && path !== null && path != ""
                                    ) {
                                let filenames = filename.split(";");
                                let paths = path.split(";");
                                let arr = data.split(",");
                                for(let i=0; i<arr.length, i<filenames.length, i<paths.length; i++) {
                                    arr[i] = "<a href='{$base_url}" +paths[i]+ "' target='_blank'>" +filenames[i]+ "</a>";
                                }

                                if (arr.length <= 1) {
                                    data = arr.join(",");
                                }
                                else {
                                    data = "<ul><li>";
                                    data += arr.join("</li><li>");
                                    data += "</li></ul>";
                                }
                            }
                            else {
                                data = "";
                            }
                            {if $x.display_format_js}
                            return {$x.display_format_js}(data, type, row);
                            {else}
                            return data;
                            {/if}
                        }
                        return data;
                    },
                    {else if isset($x.type) && $x.type=="tcg_date"}
                    render: function ( data, type, row ) {
                        // if (type == "export") {
                        //     //export raw data?
                        //     return data;
                        // }

                        if (type == "display") {
                            if (typeof data !== 'undefined' && data !== null && data != "") {
                                data = moment(data).format('YYYY-MM-DD');
                            }
                            {if $x.display_format_js}
                            return {$x.display_format_js}(data, type, row);
                            {else}
                            return data;
                            {/if}
                        }
                        return data;
                    },
                    {else if isset($x.type) && $x.type=="tcg_currency"}
                    render: function ( data, type, row ) {
                        // if (type == "export") {
                        //     //export raw data?
                        //     return data;
                        // }

                        if (type == "display") {
                            if (typeof data === 'undefined' || data === null || data == "") {
                                data = 0;
                            }
                            data = $.fn.dataTable.render.number('{$currency_thousand_separator}', '{$currency_decimal_separator}', {$currency_decimal_precision}, '{$currency_prefix}').display(data);
                            {if $x.display_format_js}
                            return {$x.display_format_js}(data, type, row);
                            {else}
                            return data;
                            {/if}
                        }
                        return data;
                    },
                    {else if $x.display_format_js}
                    render: function ( data, type, row ) {
                        // if (type == "export") {
                        //     //export raw data?
                        //     return data;
                        // }

                        if (type == "display") {
                            if (typeof data === 'undefined' || data === null) {
                                data = "";
                            }
                            return {$x.display_format_js}(data, type, row);
                        }
                        return data;
                    },
                    {/if}
                },
                {/if}
            {/foreach}
            {if count($tbl.row_actions) > 0}
            {
                data: null,
                className: 'text-right inline-flex text-nowrap',
                "orderable": false,
                render: function(data, type, row, meta) {
                    if(type != 'display') {
                        return "";
                    }

                    {if count($tbl.row_actions) == 1 && $tbl.row_actions[0].icon_only == false}
                        return "<button href='#' onclick='event.stopPropagation(); {$tbl.row_actions[0].onclick_js}(" +meta.row+ ", dt_{$tbl.table_id});' data-tag='" +meta.row+ "' class='btn btn-sm {$tbl.row_actions[0].css}'><i class='{$tbl.row_actions[0].icon}'></i> {$tbl.row_actions[0].label}</button>";
                    {else}
                        let str = '';

                        {assign var=num_of_dropdown value=0 }
                        {foreach $tbl.row_actions as $x} 
                            {if !$x.icon_only}
                                {assign var=num_of_dropdown value=$num_of_dropdown+1 }
                            {else}
                                {if !empty($x.conditional_js)}
                                if ({$x.conditional_js}(meta.row)) {
                                {/if}
                                str += "<button href='#' onclick='event.stopPropagation(); {$x.onclick_js}(" +meta.row+ ", dt_{$tbl.table_id});' data-tag='" +meta.row+ "' class='btn btn-icon-circle {$x.css}'><i class='{$x.icon}'></i></button>"                           
                                {if !empty($x.conditional_js)}
                                }
                                {/if}
                            {/if}
                        {/foreach}
                        
                        {if $num_of_dropdown > 0}
                            str += '<div class="dropright dt-row-actions" data-tag="' +meta.row+ '" style="display: inline-block;">'
                                + '<button type="button" class="btn btn-icon-circle btn-outline-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                    + '<i class="fa fa-ellipsis-v fas"></i>'
                                + '</button>'
                                + '<ul class="dropdown-menu" x-placement="right-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(5px, 248px, 0px);" x-out-of-boundaries="">';

                            {foreach $tbl.row_actions as $x}
                                {if !$x.icon_only}
                                    {if $x.conditional_js}
                                    if ({$x.conditional_js}(meta.row)) {
                                    {/if}
                                    str += "<span style='cursor: pointer;' onclick='event.stopPropagation(); {$x.onclick_js}(" +meta.row+ ", dt_{$tbl.table_id});' data-tag='" +meta.row+ "' class='dropdown-item'><i class='{$x.icon}'></i> {$x.label}</span>";
                                    {if $x.conditional_js}
                                    }
                                    {/if}
                                {/if}
                            {/foreach}

                            str += '</ul></div>';
                        {/if}      

                        return str;  
                    {/if}
                }
            },
            {/if}
        ],
        "columnDefs": [
            // {
            //     target: [
            //         {foreach from=$tbl.columns key=k item=v}
            //         {if $v.visible == 0}
            //             {$k+1},
            //         {/if}
            //         {/foreach}
            //     ],
            //     visible: false
            // },
            {
                targets: [0],
                orderable: false
            }
        ],
        initComplete: function() {
            dt_{$tbl.table_id}_initialized = true;
        },
        "createdRow": function ( row, data, index ) {
            if ( $('ul.dropdown-menu span', row).length == 0 ) {
                $('.btn-dropdown', row).addClass('d-none')
            }
        }
    });

    dt_{$tbl.table_id}.buttons( 0, null ).container().addClass("mr-md-2 mb-1");

    let buttons = new $.fn.dataTable.Buttons( dt_{$tbl.table_id}, {
        buttons: [
            {if isset($tbl.table_actions) && $tbl.table_actions.export}
            {
                extend: 'excelHtml5',
                text: '{__("Ekspor")}',
                className: 'btn-sm btn-primary',
                exportOptions: {
                    orthogonal: "export",
                    modifier: {
                        //selected: true
                    },
                    // format: {
                    //     body: function (data, row, column, node) { 
                    //         return data;
                    //     }
                    // }
                },
                {if 1==0}
                //formatting to text does not seem to have much value!
                //the cell format is still set to GENERAL
                customize: function ( xlsx ) {
                    let sheet = xlsx.xl.worksheets['sheet1.xml'];
                    let col = '';

                    //lazy way of formatting. set all cell to text
                    $('row c', sheet).attr( 's', '50' );

                    {assign var=x value=0}
                    {foreach from=$tbl.columns key=k item=v}
                    {if $v.visible == 1}
                        {assign var=x value=$x+1}
                        {if isset($v.type) && $v.type=="tcg_text"}
                            col = toColumnName({$x});
                            $('row c[r^="' +col+ '"]', sheet).attr( 's', '50' );
                        {else if isset($v.type) && $v.type=="tcg_upload"}
                            col = toColumnName({$x});
                            $('row c[r^="' +col+ '"]', sheet).attr( 's', '50' ); 
                        {/if}
                    {/if}
                    {/foreach}
                },
                {/if}
            },
            {/if}
            {if isset($tbl.table_actions) && $tbl.table_actions.import}
            {
                text: '{__("Impor")}',
                className: 'btn-sm btn-danger',
                action: function ( e, dt, node, conf ) {
                    dt_{$tbl.table_id}_import(e, dt, node, conf);
                },
            },
            {/if}
        ]
    } );
 
    let cnt = buttons.c.buttons.length;
    if (cnt == 0) {
        buttons.container().addClass('d-none dt-export-buttons');
    }
    else {
        buttons.container().addClass('mr-md-2 mb-1 dt-export-buttons');
    }

    dt_{$tbl.table_id}.buttons( 0, null ).container().after(
        dt_{$tbl.table_id}.buttons( 1, null ).container()
    );

    {if count($tbl.custom_actions) > 0}
    buttons = new $.fn.dataTable.Buttons( dt_{$tbl.table_id}, {
        buttons: [
            {foreach $tbl.custom_actions as $x}
            {
                {if $x.selected==1}
                extend: 'selectedSingle',
                {else if $x.selected>1}
                extend: 'selected',
                {/if}
                text: '{__($x.label)}',
                action: function ( e, dt, node, conf ) {
                    {$x.onclick_js}(e, dt, node, conf);
                },
                className: 'btn-sm {$x.css}'
            },
            {/foreach}
        ]
    } );
 
    buttons.container().addClass('mr-md-2 mb-1 dt-custom-buttons');

    dt_{$tbl.table_id}.buttons( 1, null ).container().after(
        dt_{$tbl.table_id}.buttons( 2, null ).container()
    );
    {/if}

    {if $tbl.row_id_column}
    dt_{$tbl.table_id}.on('order.dt search.dt', function() {
        dt_{$tbl.table_id}.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
    {/if}

    dt_{$tbl.table_id}.on("user-select.dt", function (e, dt, type, cell, originalEvent) {
        var $elem = $(originalEvent.target); // get element clicked on
        var tag = $elem[0].nodeName.toLowerCase(); // get element's tag name

        if (!$elem.closest("div.dt-row-actions").length) {
            return; // ignore any element not in the dropdown
        }

        if (tag === "i" || tag === "a" || tag === "button") {
            return false; // cancel the select event for the row
        }
    });

    // dt_{$tbl.table_id}.on( 'column-sizing.dt', function ( e, settings ) {
    //     //dt_{$tbl.table_id}.columns.adjust().responsive.recalc();
    //     console.log( 'Column width recalculated in table' );
    // });

});

var dt_{$tbl.table_id}_initialized = false;

function dt_{$tbl.table_id}_ajax_load(data) {
    return new Promise(function(resolve, reject) {
        {if !$tbl.initial_load}
        if (!dt_{$tbl.table_id}_initialized) {
            resolve({
                data: [],
            });
            return;
        }
        {/if}

        $.ajax({
            "url": "{$tbl.ajax}",
            "dataType": "json",
            "type": "POST",
            "data": {
                {foreach $tbl.filter_columns as $f}
                    {if $f.filter_type == 'js'}
                        f_{$f.name}: v_{$f.name},
                    {else}
                        f_{$f.name}: $("#f_{$f.name}").val(),
                    {/if}
                {/foreach}
            },
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type",
                    "application/x-www-form-urlencoded; charset=UTF-8");
            },
            success: function(response) {
                let data = [];
                if (response.data === null) {
                    alert("{__('Gagal mengambil data via ajax')}");
                    data = [];
                } else if (typeof response.error !== 'undefined' && response.error !== null &&
                    response
                    .error != "") {
                    alert(response.error);
                    data = [];
                } else {
                    data = response.data;
                }

                resolve({
                    data: data,
                });
            },
            error: function(jqXhr, textStatus, errorMessage) {
                alert("{__('Gagal mengambil data via ajax')}");
                resolve({
                    data: [],
                });
            }
        });
    });
}

function dt_{$tbl.table_id}_edit_row(row_id, dt) {
    let row = dt.row(row_id);

    editor_{$tbl.table_id}
            .title("{__('Ubah')} {$tbl.name}")
            .buttons([
                { label: "{__('Simpan')}", className: "btn-primary", fn: function () { this.submit(); } },
            ])
            .edit(row.index(), {
                submit: 'changed'
            });

    // row.edit( {
    //     editor: editor_{$tbl.table_id},
    //     buttons: [
    //         { label: "{__('Save')}", className: "btn-primary", fn: function () { this.submit(); } },
    //     ]
    // }, false );

    return;
}

function dt_{$tbl.table_id}_delete_row(row_id, dt) {
    let row = dt.row(row_id);

    editor_{$tbl.table_id}
            .title("{__('Hapus')} {$tbl.name}")
            .buttons([
                { label: "{__('Hapus')}", className: "btn-danger", fn: function () { this.submit(); } },
            ])
            .message( "{__('Konfirmasi menghapus')} 1 {$tbl.name}?" )
            .remove(row.index(), true);

    // row.delete( {
    //     buttons: [
    //         { label: "{__('Delete')}", className: "btn-danger", fn: function () { this.submit(); } },
    //     ]
    // } );
}

function dt_{$tbl.table_id}_import(e, dt, node, conf){
    $.confirm({
        columnClass: 'medium',
        title: '{__("Impor")} {$tbl.name}',
        content: '' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<input id="upload" type="file" name="import" accept=".xlsx, .xls, .csv" style="width: 100%;" />' +
        '<div id="error" class="d-none text-danger mt-2"></div>' +
        '<div class="d-none text-center justify-content-center" id="loading" style="position: absolute; width: 100%; top: 0px;">' +
        '  <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>' +
        '</div>' +
        '</div>' +
        '</form>',
        buttons: {
            cancel: function () {
                //close
            },
            formSubmit: {
                text: '{__("Impor")}',
                btnClass: 'btn-primary',
                action: function () {
                    let that = this;

                    //upload the file
                    let upload = that.$content.find('#upload');
                    if (upload[0].files.length == 0) {
                        let message = that.$content.find('#error');
                        message.html("Belum memilih file");
                        message.removeClass("d-none");
                        return false;
                    }
                    let file = upload[0].files[0];

                    let spinner = that.$content.find('#spinner');

                    // add assoc key values, this will be posts values
                    var formData = new FormData();
                    formData.append("upload", file, file.name);
                    formData.append("action", "import");

                    spinner.removeClass('d-none');
                    $.ajax({
                        type: "POST",
                        url: "{$tbl.ajax}",
                        async: true,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        timeout: 60000,
                        dataType: 'json',
                        success: function(json) {
                            if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
                                let message = that.$content.find('#error');
                                message.html(json.error);
                                message.removeClass("d-none");
                                //hide spinner
                                spinner.addClass('d-none');
                                return;
                            }

                            //hide spinner
                            spinner.addClass('d-none');

                            dt.ajax.reload();
                            that.close();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            let message = that.$content.find('#error');
                            message.html("Gagal mengimpor file: " + textStatus);
                            message.removeClass("d-none");
                            //hide spinner
                            spinner.addClass('d-none');
                            return;
                        }
                    });

                    //wait for completion of ajax
                    return false;
                }
            },
        },
        onContentReady: function () {
            // bind to events
            var that = this;
            this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                that.$$formSubmit.trigger('click'); // reference the button and click it
            });
            this.$content.find('#upload').on('change', function (e) {
                let message = that.$content.find('#error');
                message.html("");
                message.addClass("d-none");
            });
        }
    });
}

function toColumnName(num) {
    for (var ret = '', a = 1, b = 26; (num -= a) >= 0; a = b, b *= 26) {
        ret = String.fromCharCode(parseInt((num % b) / a) + 65) + ret;
    }
    return ret;
}

</script>

{if $tbl.custom_js}
<script type="text/javascript" defer>
    {$tbl.custom_js}
</script>
{/if}