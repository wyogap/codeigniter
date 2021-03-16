
<script type="text/javascript" defer> 

var editor_{$crud.table_id} = null;
var dt_{$crud.table_id} = null;

$(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'throw';
    $.extend($.fn.dataTable.defaults, {
        responsive: true
    });

    {if $crud.editor}
        editor_{$crud.table_id} = new $.fn.dataTable.Editor({
            ajax: "{$crud.ajax}",
            table: "#{$crud.table_id}",
            idSrc: "{$crud.key_column}",
            fields: [
                {foreach $crud.editor_columns as $col}
                {
                    label: "{$col.edit_label} {if $col.edit_label && $col.edit_compulsory}<span class='text-danger font-weight-bold'>*</span>{/if}",
                    name: "{$col.edit_field}",
                    {if $col.edit_type == 'js'}
                    type: 'hidden',
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
                    {if isset($col.edit_attr)}
                    attr: {
                        {foreach from=$col.edit_attr key=k item=v}
                        {$k} : "{$v}",
                        {/foreach}
                    },
                    {/if}
                    {if isset($col.edit_info)}
                    fieldInfo:  "{$col.edit_info}",
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
                    clearText: "{__('Delete')}",
                    noImageText: "{__('No image')}",
                    uploadText: "{__('Choose file...')}",
                    noFileText: "{__('No file')}",
                    processingText: "{__('Uploading')}",
                    fileReadText: "{__('Reading file')}",
                    dragDropText: "{__('Drag and drop a file here to upload')}"
                    {/if}
                },
                {/foreach}
            ],
            i18n: {
                create: {
                    button: "{__('Add')}",
                    title: "{__('Add')} {$page_title}",
                    submit: "{__('Save')}"
                },
                edit: {
                    button: "{__('Edit')}",
                    title: "{__('Edit')} {$page_title}",
                    submit: "{__('Save')}"
                },
                remove: {
                    button: "{__('Delete')}",
                    title: "{__('Delete')} {$page_title}",
                    submit: "{__('Delete')}",
                    confirm: {
                        _: "{__('Do you want to delete')} %d {$page_title}?",
                        1: "{__('Do you want to delete')} 1 {$page_title}?"
                    }
                },
                error: {
                    system: "{__('System error. Please contact system administrator.')}"
                },
                datetime: {
                    previous: "{__('Previous')}",
                    next: "{__('Next')}",
                    months: [
                        "{__('January')}", 
                        "{__('February')}", 
                        "{__('March')}", 
                        "{__('April')}", 
                        "{__('May')}", 
                        "{__('June')}", 
                        "{__('July')}", 
                        "{__('Augustus')}",
                        "{__('September')}", 
                        "{__('October')}", 
                        "{__('November')}", 
                        "{__('December')}"
                    ],
                    weekdays: [
                        "{__('Mon')}", 
                        "{__('Tue')}", 
                        "{__('Wed')}", 
                        "{__('Thu')}", 
                        "{__('Wed')}", 
                        "{__('Fri')}", 
                        "{__('Sat')}"
                    ],
                    hour: "{__('Hour')}",
                    minute: "{__('Minute')}"
                }
            }
        });

        editor_{$crud.table_id}.on( 'open' , function ( e, type ) {
            {foreach $crud.editor_columns as $col}
                {if $col.edit_type == 'js' }
                editor_{$crud.table_id}.field("{$col.edit_field}").set(v_{$col.name});
                {/if}
            {/foreach}
        });

        editor_{$crud.table_id}.on('preSubmit', function(e, o, action) {
            if (action === 'create' || action === 'edit') {
                let field = null;

                {foreach $crud.editor_columns as $col}
                    {if isset($col.edit_compulsory) && $col.edit_compulsory == true}
                    field = this.field('{$col.edit_field}');
                    if (!field.isMultiValue()) {
                        if (!field.val() || field.val() == 0) {
                            field.error('{__("Compulsory field")}');
                        }
                    }
                    {/if}
                {/foreach}

                /* If any error was reported, cancel the submission so it can be corrected */
                if (this.inError()) {
                    this.error('{__("Compulsory fields is not set")}');
                    return false;
                }
            }

            /* dont sent all data for remove */
            if (action === 'remove') {
                $.each(o.data, function (key, val) {
                    o.data[key] = {};
                    o.data[key].{$crud.key_column} = key;
                });
            }

            /* set the hidden js field */
            {foreach $crud.editor_columns as $col}
                {if $col["edit_type"] == "js"}
                $.each(o.data, function (key, val) {
                    o.data[key].{$col.edit_field} = v_{$col.name};
                });
                {/if}
            {/foreach}
            
        });        

        editor_{$crud.table_id}.on('postSubmit', function(e, json, data, action, xhr) {
            if (action=="upload") {

            }

        });

        {foreach $crud.columns as $col}
            {if isset($col.edit_event_js)}
            $(editor_{$crud.table_id}.field('{$col.edit_field}').node()).on('change', function() {
                let val = editor.field('{$col.edit_field}').val();
                {$col['edit_event_js']} (val, editor_{$crud.table_id});
            });
            {/if}
        {/foreach}

        /* Activate the bubble editor on click of a table cell */
        $('#{$crud.table_id}').on( 'click', 'tbody td.editable', function (e) {
            editor_{$crud.table_id}.bubble( this );
        } );

        /* Inline editing in responsive cell */
        $('#{$crud.table_id}').on( 'click', 'tbody ul.dtr-details li', function (e) {
            /* Ignore the Responsive control and checkbox columns */
            if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
                return;
            }
    
            /* ignore read-only column */
            var colnum = $(this).attr( 'data-dt-column' );
            if ( colnum == 1 ) {
                return;
            }
        
            /* Edit the value, but this method allows clicking on label as well */
            editor_{$crud.table_id}.bubble( $('span.dtr-data', this) );
        });
    {/if}

    dt_{$crud.table_id} = $('#{$crud.table_id}').DataTable({
        "processing": true,
        "responsive": true,
        "serverSide": false,
        "pageLength": 25,
        "lengthMenu": [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        "paging": true,
        "pagingType": "numbers",
        dom: "<'row'<'col-sm-12 col-md-7 dt-action-buttons'B><'col-sm-12 col-md-5'fr>>t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
        select: true,
        buttons: [
            {if isset($crud.table_actions) && $crud.table_actions.add}
            {
                extend: "create",
                editor: editor_{$crud.table_id},
                className: 'btn-sm'
            },
            {/if}
            {if isset($crud.table_actions) && $crud.table_actions.edit}
            {
                extend: "edit",
                editor: editor_{$crud.table_id},
                className: 'btn-sm btn-info'
            },
            {/if}
            {if isset($crud.table_actions) && $crud.table_actions.delete}
            {
                extend: "remove",
                editor: editor_{$crud.table_id},
                className: 'btn-sm btn-danger'
            },
            {/if}
        ],
        "language": {
            "sProcessing": "{__('Processing')}",
            "sLengthMenu": "{__('Showing')} _MENU_ {__('entries')}",
            "sZeroRecords": "{__('No data')}",
            "sInfo": "{__('showing')} _START_ - _END_ {__('from')} _TOTAL_ {__('entries')}",
            "sInfoEmpty": "{__('showing')} 0 {__('from')} 0 {__('entries')}",
            "sInfoFiltered": "{__('Filtered_from')} _MAX_ {__('total_entries')}",
            "sInfoPostFix": "",
            "sSearch": "{__('Search')}",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "{__('First')}",
                "sPrevious": "{__('Previous')}",
                "sNext": "{__('Next')}",
                "sLast": "{__('Last')}"
            }
        },
        {if !$crud.initial_load}
        "ajax": function(
            data, callback, settings) {
            dt_{$crud.table_id}_ajax_load(data).then(function(_data) {
                callback(_data);
            });
        },
        {else}
        "ajax": {
            "url": "{$crud.ajax}",
            "dataType": "json",
            "type": "POST",
            "data": function(d) {
                {foreach $crud.filter_columns as $f}
                d.f_{$f.name} = v_{$f.name};
                {/foreach}
                return d;
            }
        },
        {/if} 
        "columns": [
            {if $crud.row_select_column}
            {
                data: null,
                className: "text-right",
                orderable: 'false',
                defaultContent: ''
            },
            {/if}
            {if $crud.row_id_column}
            {
                data: null,
                className: "text-right",
                orderable: 'false',
                defaultContent: ''
            },
            {/if}
            {foreach $crud.columns as $x}
                {if $x.visible == 1}
                { 
                    data: "{$x.name}", 
                    className: "{$x.css} {if !empty($x.edit_bubble)}editable{/if}",
                    {if !empty($x.edit_field)}
                    editField: "{$x.edit_field}",
                    {/if}
                    {if !empty($x.type) && $x.type=="upload"}
                    render: function ( data, type, row ) {
                        if (type == "display") {
                            if (data !== null && data != "") {
                                return "<a href='" +row.{$x.edit_field}+ "'><img src='" +data+ "' style='max-height:100px;'></img></a>";
                            }
                            return "";
                        }
                        return data;
                    },
                    {/if}
                    {if !empty($x.type) && $x.type=="date"}
                    render: function ( data, type, row ) {
                        if (type == "display") {
                            return moment(data).format('YYYY-MM-DD');
                        }
                        return data;
                    },
                    {/if}
                },
                {/if}
            {/foreach}
            {if count($crud.row_actions) > 0}
            {
                data: null,
                className: 'text-right inline-flex text-nowrap',
                "orderable": false,
                render: function(data, type, row, meta) {
                    if(type != 'display') {
                        return "";
                    }

                    {if count($crud.row_actions) == 1 && $crud.row_actions[0].icon_only == false}
                        return "<a href='#' onclick='event.stopPropagation(); {$crud.row_actions[0].onclick_js}(" +meta.row+ ", dt_{$crud.table_id});' data-tag='" +meta.row+ "' class='btn btn-sm {$crud.row_actions[0].css}'><i class='{$crud.row_actions[0].icon}'></i> {$crud.row_actions[0].label}</a>";
                    {else}
                        let str = '';

                        {assign var=num_of_dropdown value=0 }
                        {foreach $crud.row_actions as $x} 
                            {if !$x.icon_only}
                                {assign var=num_of_dropdown value=$num_of_dropdown+1 }
                            {else}
                                {if !empty($x.conditional_js)}
                                if ({$x.conditional_js}(meta.row)) {
                                {/if}
                                str += "<a href='#' onclick='event.stopPropagation(); {$x.onclick_js}(" +meta.row+ ", dt_{$crud.table_id});' data-tag='" +meta.row+ "' class='btn btn-icon-circle {$x.css}'><i class='{$x.icon}'></i></a>"                           
                                {if !empty($x.conditional_js)}
                                }
                                {/if}
                            {/if}
                        {/foreach}
                        
                        {if $num_of_dropdown > 0}
                            str += '<div class="dropright dropright btn-dropdown dt-row-actions" data-tag="' +meta.row+ '">'
                                + '<button type="button" class="btn btn-sm btn-outline-success btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                    + '<i class="fa fa-ellipsis-v fas"></i>'
                                + '</button>'
                                + '<ul class="dropdown-menu" x-placement="right-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(5px, 248px, 0px);" x-out-of-boundaries="">';

                            {foreach $crud.row_actions as $x}
                                {if !$x.icon_only}
                                    {if $x.conditional_js}
                                    if ({$x.conditional_js}(meta.row)) {
                                    {/if}
                                    str += "<span style='cursor: pointer;' onclick='event.stopPropagation(); {$x.onclick_js}(" +meta.row+ ", dt_{$crud.table_id});' data-tag='" +meta.row+ "' class='dropdown-item'><i class='{$x.icon}'></i> {$x.label}</span>";
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
            {
                target: [
                    {foreach from=$crud.columns key=k item=v}
                    {if $v.visible == 0}
                        {$k+1},
                    {/if}
                    {/foreach}
                ],
                visible: false
            },
            {
                targets: [0],
                orderable: false
            }
        ],
        initComplete: function() {
            dt_{$crud.table_id}_initialized = true;
        },
        "createdRow": function ( row, data, index ) {
            if ( $('ul.dropdown-menu span', row).length == 0 ) {
                $('.btn-dropdown', row).addClass('d-none')
            }
        }
    });

    {if $crud.row_id_column}
    dt_{$crud.table_id}.on('order.dt search.dt', function() {
        dt_{$crud.table_id}.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
    {/if}

    $('.dt-action-buttons', '#{$crud.table_id}_wrapper').append($('.dt-custom-actions[data-table-id="{$crud.table_id}"]'));

    dt_{$crud.table_id}.on("user-select.dt", function (e, dt, type, cell, originalEvent) {
        var $elem = $(originalEvent.target); // get element clicked on
        var tag = $elem[0].nodeName.toLowerCase(); // get element's tag name

        if (!$elem.closest("div.dt-row-actions").length) {
        return; // ignore any element not in the dropdown
        }

        if (tag === "i" || tag === "a" || tag === "button") {
            return false; // cancel the select event for the row
        }
    });
});

var dt_{$crud.table_id}_initialized = false;

function dt_{$crud.table_id}_ajax_load(data) {
    return new Promise(function(resolve, reject) {
        {if !$crud.initial_load}
        if (!dt_{$crud.table_id}_initialized) {
            resolve({
                data: [],
            });
            return;
        }
        {/if}

        $.ajax({
            "url": "{$crud.ajax}",
            "dataType": "json",
            "type": "POST",
            "data": {
                {foreach $crud.filter_columns as $f}
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
                    alert("{__('Fail to load data via ajax')}");
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
                alert("{__('Fail to load data via ajax')}");
                resolve({
                    data: [],
                });
            }
        });
    });
}
</script>