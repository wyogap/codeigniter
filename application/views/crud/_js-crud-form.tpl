
<script id="crud-form-row" type="text/template">
    <div class="form-group row DTE_Field DTE_Field_Type_{literal}{{type}}{/literal} DTE_Field_Name_{literal}{{name}}{/literal}" id="DTE_Field_{literal}{{name}}{/literal}" data-id="{literal}{{name}}{/literal}">
        <label data-dte-e="label" for="DTE_Field_{literal}{{name}}{/literal}" class="col-md-3 col-form-label form-label"></label>
        <div data-dte-e="input-group" class="col-md-9 form-input">
            <div data-dte-e="input" id="{literal}{{name}}{/literal}_input_control" class="DTE_Field_InputControl form-input-control" style="display: block;">
                <!-- actual input field here -->
                <div data-dte-e="msg-error" class="form-text text-danger small d-none"></div>
                <div data-dte-e="msg-message" class="form-text text-secondary small d-none"></div>
                <div data-dte-e="msg-info" class="form-text text-secondary small d-none"></div>
            </div>   
            <div data-dte-e="info" id="{literal}{{name}}{/literal}_input_info" class="DTE_Field_Info form-input-info d-none">
            {literal}{{fieldInfo}}{/literal}
            </div>    
        </div>
    </div>
</script>

<script id="crud-detail-row" type="text/template">
    <div class="form-group row DTE_Field DTE_Field_Type_{literal}{{type}}{/literal} DTE_Field_Name_{literal}{{name}}{/literal}" id="DTE_Field_{literal}{{name}}{/literal}" data-id="{literal}{{name}}{/literal}">
        <label data-dte-e="label" data-dte-col="{literal}{{name}}{/literal}" class="col-md-3 col-form-label form-label"></label>
        <div data-dte-e="value-group" class="col-md-9 col-form-label">
            <div data-dte-e="value" data-dte-col="{literal}{{name}}{/literal}"
                id="{literal}{{name}}{/literal}_value" class="DTE_Field_InputControl form-input-control" style="display: block;">
                <!-- actual input field here -->
            </div>   
            <div data-dte-e="info" data-dte-col="{literal}{{name}}{/literal}"
                id="{literal}{{name}}{/literal}_input_info" class="DTE_Field_Info form-input-info d-none">
            {literal}{{fieldInfo}}{/literal}
            </div>    
        </div>
    </div>
</script>

{if !isset($form_mode)}{$form_mode=''}{/if}

<script type="text/javascript" defer>

    // var base_url = "{$base_url}";
    // var site_url = "{$site_url}";
    // var ajax_url = "{$tbl.ajax}";
    // var tbl_title = "{$tbl.title}";

    var field_list = [];

    var edit_list = null;
    var detail = null;
    var id = null;

    $(document).ready(function() {
        let _options = [];
        let _attr = {};
        let status = false;

        // let conf = null;
        // let field = null;
        // let dom = null;
        // let edit_type = null;
        // let input_container = null;
        let form_container = $('#{$tbl.table_id}_form');
        // let val = null;

        {if !empty($detail)}
        detail = {$detail|@json_encode nofilter};
        id = "{$detail[$tbl.key_column]}";

        //set key and value for subtable
        {foreach $subtables as $subtbl}
        fkey_value_{$subtbl.crud.table_id} = detail['{$tbl.key_column}'];
        fkey_label_{$subtbl.crud.table_id} = detail['{$tbl.lookup_column}'];
        {/foreach}

        {if !empty($tbl.edit_conditional_js) && $form_mode=='edit'}
        status = {$tbl.edit_conditional_js}(detail, 0, null);
        if (!status) {
            //redirect to readonly
            window.location.href = "{$site_url}{$controller}/{$page_name}/detail/" +id;
        }
        {/if}
        {/if}

        {if empty($form_mode) || $form_mode=='detail'}
        //form-mode == 'detail'
        detail_list = [
            {if !empty($level1_column)}
            {
                name: "{$level1_column}",
                type: "hidden"
            },
            {/if}
            {foreach $tbl.columns as $col}
            {if !$col.visible} {continue} {/if}

            {if empty($col.editor)}
            {
                "label"     : "{$col.label}",
                "name"      : "{$col.name}",
                "type"      : "tcg_readonly",
                "info"      : "",
                "className" : "",
                "labelInfo" : "",
                "message"   : "",
                "readonly"  : true,
                "colType"   : "{$col.type}",
            },
            {continue}
            {/if}

            {$editor = $col.editor}
            {       
                "label": "{$col.label} {if !empty($editor.edit_compulsory)}<span class='text-danger font-weight-bold'>*</span>{/if}",
                "name" : "{$col.name}",

                {if $editor.edit_type == 'js'}
                "type"      : 'hidden',
                {else if $editor.edit_type == 'tcg_currency'}
                "type"      : 'tcg_readonly',
                "colType"   : "{$col.type}",
                {else if $editor.edit_field|@count>1}
                "type"      : "tcg_readonly",
                "colType"   : "{$col.type}",
                {else}
                "type"      : '{$editor.edit_type}',
                {/if}

                'info'      : "{$editor.edit_info}",
                'className' : "{$editor.edit_css}",
                'labelInfo' : "",
                'message'   : "",
                "readonly"  : true,

                {if !empty($editor.edit_attr)}
                "attr": {$editor.edit_attr|@json_encode nofilter},
                {/if}

                {if !empty($editor.options_data_url) && $editor.edit_type=='tcg_select2'}
                {if $editor.options_data_url_params|@count==0}
                "ajax": "{$site_url}{$editor.options_data_url}",
                {/if}
                {/if}

                {if $editor.edit_type=='tcg_upload'}
                ajax: "{$tbl.ajax}",
                {/if}

                {if !empty($editor.edit_info)}
                "fieldInfo":  "{$col.edit_info}",
                {/if}

                {if isset($editor.edit_options)}
                "options": [
                    {foreach from=$editor.edit_options key=k item=v}
                    {if is_array($v)}
                        { label: "{htmlspecialchars($v.label)}", value: "{htmlspecialchars($v.value)}" },
                    {else}
                        { label: "{htmlspecialchars($v)}", value: "{htmlspecialchars($k)}" },
                    {/if}
                    {/foreach}
                ],
                {/if}

                {if $editor.edit_type=='upload' || $editor.edit_type=='image'}
                "display": function ( file_id ) {
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
                "clearText": "{__('Hapus')}",
                "noImageText": "{__('No image')}",
                "uploadText": "{__('Pilih fail...')}",
                "noFileText": "{__('No file')}",
                "processingText": "{__('Mengunggah')}",
                "fileReadText": "{__('Membaca fail')}",
                "dragDropText": "{__('Tarik dan taruh fail di sini untuk mengunggah')}"
                {/if}
            },
            {/foreach}
        ];
        {elseif $form_mode=='add'}
        //form-mode == 'add'
        detail_list = [
            {if !empty($level1_column)}
            {
                name: "{$level1_column}",
                type: "hidden"
            },
            {/if}

            {foreach $tbl.columns as $col}
            {if empty($col.editor) || empty($col.allow_insert)} {continue} {/if}
            {$editor = $col.editor}

            {       
                "label": "{if !empty($editor.edit_label)}{$editor.edit_label}{else}{$col.label}{/if} {if !empty($editor.edit_compulsory)}<span class='text-danger font-weight-bold'>*</span>{/if}",

                {if $editor.edit_field|@count==1}
                "name": "{$editor.edit_field[0]}",
                {else if $editor.edit_field|@count>1}
                "name": "{$editor.name}",
                {/if}

                {if $editor.edit_type == 'js'}
                "type": 'hidden',
                {else if $editor.edit_type == 'tcg_currency'}
                "type": 'tcg_mask',
                "mask": "#{$currency_thousand_separator}##0",
                {else if $editor.edit_field|@count>1}
                "type": "tcg_readonly",
                {else}
                "type": '{$editor.edit_type}',
                {/if}

                'info'      : "{$editor.edit_info}",
                'className' : "{$editor.edit_css}",
                'labelInfo' : "",
                'message'   : "",

                {if !empty($editor.edit_attr)}
                "attr": {$editor.edit_attr|@json_encode nofilter},
                {/if}

                {if !empty($editor.options_data_url) && $editor.edit_type=='tcg_select2'}
                {if $editor.options_data_url_params|@count==0}
                "ajax": "{$site_url}{$editor.options_data_url}",
                {/if}
                {/if}

                {if $editor.edit_type=='tcg_upload'}
                ajax: "{$tbl.ajax}",
                {/if}

                {if !empty($editor.edit_info)}
                "fieldInfo":  "{$col.edit_info}",
                {/if}

                {if isset($editor.edit_def_value) && $editor.edit_def_value != null}
                "def":  "{$editor.edit_def_value}",
                {/if}

                {if $editor.edit_type=='tcg_readonly' || (!empty($editor.edit_attr) && !empty($editor.edit_attr.readonly))}
                "readonly": true,
                {else}
                "readonly": false,
                {/if}

                {if !empty($editor.edit_compulsory)}
                "compulsory": true,
                {else}
                "compulsory": false,
                {/if}
                
                {if isset($editor.edit_options)}
                "options": [
                    {foreach from=$editor.edit_options key=k item=v}
                    {if is_array($v)}
                        { label: "{htmlspecialchars($v.label)}", value: "{htmlspecialchars($v.value)}" },
                    {else}
                        { label: "{htmlspecialchars($v)}", value: "{htmlspecialchars($k)}" },
                        {/if}
                    {/foreach}
                ],
                {/if}

                {if $editor.edit_type=='upload' || $editor.edit_type=='image'}
                "display": function ( file_id ) {
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
                "clearText": "{__('Hapus')}",
                "noImageText": "{__('No image')}",
                "uploadText": "{__('Pilih fail...')}",
                "noFileText": "{__('No file')}",
                "processingText": "{__('Mengunggah')}",
                "fileReadText": "{__('Membaca fail')}",
                "dragDropText": "{__('Tarik dan taruh fail di sini untuk mengunggah')}"
                {/if}
            },

            {/foreach}
        ];
        {elseif $form_mode=='edit'}
        //form-mode == 'edit'
        detail_list = [
            {if !empty($level1_column)}
            {
                name: "{$level1_column}",
                type: "hidden"
            },
            {/if}

            {foreach $tbl.columns as $col}
            {if empty($col.editor) || empty($col.allow_edit)} {continue} {/if}
            {$editor = $col.editor}

            {       
                "label": "{if !empty($editor.edit_label)}{$editor.edit_label}{else}{$col.label}{/if} {if !empty($editor.edit_compulsory)}<span class='text-danger font-weight-bold'>*</span>{/if}",

                {if $editor.edit_field|@count==1}
                "name": "{$editor.edit_field[0]}",
                {else if $editor.edit_field|@count>1}
                "name": "{$editor.name}",
                {/if}

                {if $editor.edit_type == 'js'}
                "type": 'hidden',
                {elseif $editor.edit_type == 'tcg_currency'}
                "type": 'tcg_mask',
                "mask": "#{$currency_thousand_separator}##0",
                {elseif $editor.edit_field|@count>1}
                "type": "tcg_readonly",
                {else}
                "type": '{$editor.edit_type}',
                {/if}

                'info'      : "{$editor.edit_info}",
                'className' : "{$editor.edit_css}",
                'labelInfo' : "",
                'message'   : "",

                {if !empty($editor.edit_attr)}
                "attr": {$editor.edit_attr|@json_encode nofilter},
                {/if}

                {if !empty($editor.options_data_url) && $editor.edit_type=='tcg_select2'}
                {if $editor.options_data_url_params|@count==0}
                "ajax": "{$site_url}{$editor.options_data_url}",
                {/if}
                {/if}

                {if $editor.edit_type=='tcg_upload'}
                ajax: "{$tbl.ajax}",
                {/if}

                {if !empty($editor.edit_info)}
                "fieldInfo":  "{$col.edit_info}",
                {/if}

                {if isset($editor.edit_def_value) && $editor.edit_def_value != null}
                "def":  "{$editor.edit_def_value}",
                {/if}

                {if $editor.edit_type=='tcg_readonly' || (!empty($editor.edit_attr) && !empty($editor.edit_attr.readonly))}
                "readonly": true,
                {else}
                "readonly": false,
                {/if}

                {if !empty($editor.edit_compulsory)}
                "compulsory": true,
                {else}
                "compulsory": false,
                {/if}
                
                {if isset($editor.edit_options)}
                "options": [
                    {foreach from=$editor.edit_options key=k item=v}
                    {if is_array($v)}
                        { label: "{htmlspecialchars($v.label)}", value: "{htmlspecialchars($v.value)}" },
                    {else}
                        { label: "{htmlspecialchars($v)}", value: "{htmlspecialchars($k)}" },
                        {/if}
                    {/foreach}
                ],
                {/if}

                {if $editor.edit_type=='upload' || $editor.edit_type=='image'}
                "display": function ( file_id ) {
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
                "clearText": "{__('Hapus')}",
                "noImageText": "{__('No image')}",
                "uploadText": "{__('Pilih fail...')}",
                "noFileText": "{__('No file')}",
                "processingText": "{__('Mengunggah')}",
                "fileReadText": "{__('Membaca fail')}",
                "dragDropText": "{__('Tarik dan taruh fail di sini untuk mengunggah')}"
                {/if}
            },

            {/foreach}
        ];
        {else}
        //no data??
        {/if}

        detail_list.forEach(function(conf, index, arr) {
            conf['id'] = "DTE_Field_" +conf['name'];

            let dom = $(render_template('#crud-form-row', {
                'name'      : conf.name,
                'type'      : conf.type,
                'fieldInfo' : conf.info,
                'className' : conf.className
            }));

            //show info if necessary
            if (typeof conf.info !== 'undefined' && conf.info !== null && conf.info.length > 0) {
                dom.find('#' +conf.name+ '_input_info').show();
            }

            //the input field
            let input_field = null;

            //get the edit-type
            let edit_type = jQuery.fn.dataTable.ext.editorFields[ conf.type ];
            if (typeof edit_type === 'undefined') {
                edit_type = jQuery.fn.dataTable.ext.editorFields[ 'tcg_readonly' ];
            }
            input_field = edit_type.create(conf);

            if(conf.type=='tcg_readonly' || conf.readonly) {
                input_field.find(".form-control").attr("disabled", true);
                input_field.find("select").attr("disabled", true);
                input_field.find("input").attr("disabled", true);
            }

            let label_container = dom.find("label[data-dte-e=label]");
            label_container.html(conf.label);

            let input_container = dom.find("#" +conf.name+ "_input_control");
            input_container.prepend(input_field);

            let field_container = $("[data-editor-template='" +conf.name+ "']", form_container);
            field_container.append(dom);

            //set the value if necessary
            if (detail != null) {
                if (conf.type == 'tcg_readonly') {
                    if (conf.colType == 'tcg_currency') {
                        data = $.fn.dataTable.render.number('{$currency_thousand_separator}', '{$currency_decimal_separator}', {$currency_decimal_precision}, '').display(detail[conf.name]);
                        edit_type.set( conf, data );
                    }
                    else if (conf.colType == "tcg_select2") {
                        data = detail[conf.name];
                        if (detail[conf.name +"_label"] !== undefined && detail[conf.name +"_label"] !== null) {
                            data = detail[conf.name +"_label"];
                        }
                        edit_type.set( conf, data );
                    }
                    else {
                        data = detail[conf.name];
                    }
                    edit_type.set( conf, data );
                } else {
                    edit_type.set( conf, detail[conf.name] );
                }
            }

            //update the stored value
            arr[index] = conf;
        });

        {if $form_mode=='edit' || $form_mode=='add'}
        $(".crud-form-submit[data-table-id='{$tbl.table_id}']").on("click", function(e) {
            let form = $(".crud-form[data-table-id='{$tbl.table_id}']");
            let id = form.data('id');
            if (typeof id === "undefined" || id === null) {
                id = 0;
            }

            let data = {};
            let item = {};
            let error = false;

            detail_list.forEach(function(conf, index, arr) {
                let edit_type = jQuery.fn.dataTable.ext.editorFields[ conf.type ];
                if (typeof edit_type === 'undefined') {
                    return;
                }

                let val = edit_type.get(conf);
                item[ conf.name ] = val;

                //reset any error first
                let field = $("#" +conf.id, form_container).find('[data-dte-e="msg-error"]');
                field.addClass("d-none");

                //check for compulsory field
                if (conf.compulsory && (val === undefined || val === null || val == "")) {
                    field.html("{__('Harus diisi')}");
                    field.removeClass("d-none");
                    error = true;
                    return;
                }

                //TODO: other validation
            })

            //check for error
            if (error) {
                let msg = '{__("Data wajib belum diisi atau tidak berhasil divalidasi")}';
                form.trigger('crud.error', "validation-error", msg, null);
                toastr.error(msg, "validation-error");
                return;
            } 

            data[id] = item;

            //build form-data
            let form_data = {};
            if (id == 0) {
                form_data['action'] = 'create';
            }
            else {
                form_data['action'] = 'edit';
            }
            form_data['data'] = data;

            $.ajax({
                url: "{$tbl.ajax}",
                type: 'POST',
                dataType: 'json',
                data: form_data,
                // beforeSend: function(request) {
                //     request.setRequestHeader("Content-Type", "application/json");
                // },
                success: function(response) {

                    //raise event
                    if (id == 0) {
                        //get new id
                        if (typeof response.error != "undefined") {
                            form.trigger('crud.error', "ajax-error", response.error, form_data);
                            toastr.error(response.error, "ajax-error");
                        } 
                        else if (typeof response.data !== "undefined" && response.data.length > 0) {
                            let item = response.data[0];
                            if (item === "undefined" || item === null) {
                                form.trigger('crud.error', "ajax-error", "invalid-response", form_data);
                                toastr.error("{__('Response tidak valid.')}!", "ajax-error");
                                return;
                            }

                            //raise event
                            form.trigger('crud.created', response, form_data);
                            toastr.success("{__('Data berhasil disimpan')}!");

                            let id = item["{$tbl.key_column}"];
                            form.data('id', id);

                            if (form_data['action'] == 'create') {
                                let url = "{$site_url}{$controller}/{$page_name}/edit/" +id;
                                window.location.replace(url);
                            }
                        }
                        else {
                            form.trigger('crud.error', "ajax-error", "invalid-response", form_data);
                            toastr.error("{__('Response tidak valid.')}!", "ajax-error");
                        }
                    }
                    else {
                        //TODO: update fields
                        if (typeof response.error != "undefined") {
                            form.trigger('crud.error', "ajax-error", response.error, form_data);
                            toastr.error(response.error, "ajax-error");
                        } else {
                            //raise event
                            form.trigger('crud.updated', response, form_data);
                            toastr.success("{__('Data berhasil disimpan')}!");
                        }
                    }

                },
                error: function(jqXhr, textStatus, errorMessage) {

                    form.trigger('crud.error', textStatus, errorMessage, form_data);
                    toastr.error(errorMessage, "ajax-error");
                }    
            }); 

        });

        $(".crud-form-add[data-table-id='{$tbl.table_id}']").on("click", function(e) {

            $.confirm({
                title: 'Buat {$tbl.title} Baru?',
                content: 'Semua perubahan yang belum disimpan akan hilang.',
                closeIcon: true,
                buttons: {
                    // confirm: function () {
                    //     $.alert('Confirmed!');
                    // },
                    cancel: {
                        text: 'Batal',
                        keys: ['enter', 'shift'],
                        action: function(){
                            //do nothing
                        }
                    },
                    confirm: {
                        text: 'OK',
                        btnClass: 'btn-danger',
                        action: function(){
                            window.location.href = "{$site_url}{$controller}/{$page_name}/add";
                        }
                    },
                }
            });           
        
        });

        {/if}

        $(".crud-form-table[data-table-id='{$tbl.table_id}']").on("click", function(e) {

            $.confirm({
                title: 'Buka Daftar {$tbl.title}?',
                content: 'Semua perubahan yang belum disimpan akan hilang.',
                closeIcon: true,
                buttons: {
                    // confirm: function () {
                    //     $.alert('Confirmed!');
                    // },
                    cancel: {
                        text: 'Batal',
                        keys: ['enter', 'shift'],
                        action: function(){
                            //do nothing
                        }
                    },
                    confirm: {
                        text: 'OK',
                        btnClass: 'btn-danger',
                        action: function(){
                            window.location.href = "{$tbl.crud_url}";
                        }
                    },
                }
            });           

        });

        {if !empty($detail)}
        let key = "{$detail[$tbl.key_column]}";
        let val = "{$detail[$tbl.lookup_column]}";
        $(".page-title").html("{$page_title} - " +val);
        {elseif $form_mode=='add'}
        $(".page-title").html("Buat {$tbl.title} Baru");
        {else}
        $(".page-title").html("{$page_title}");
        {/if}

        {if !empty($detail) && !empty($tbl.edit_conditional_js)}
        status = {$tbl.edit_conditional_js}(detail, 0, null);
        if (!status) {
            $(".crud-form-edit").addClass("d-none");
        }
        {/if}
    });
</script>
