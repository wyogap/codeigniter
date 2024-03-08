
<script id="crud-form-row" type="text/template">
    <div class="form-group row DTE_Field DTE_Field_Type_{literal}{{type}}{/literal} DTE_Field_Name_{literal}{{name}}{/literal}" id="DTE_Field_{literal}{{name}}{/literal}" data-id="{literal}{{name}}{/literal}">
        <label data-dte-e="label" class="col-md-3 col-form-label form-label" for="DTE_Field_{literal}{{name}}{/literal}"></label>
        <div data-dte-e="input" class="col-md-9 form-input">
            <div data-dte-e="input-control" id="{literal}{{name}}{/literal}_input_control" class="DTE_Field_InputControl form-input-control" style="display: block;">
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
        <label data-dte-e="label" class="col-md-3 col-form-label form-label" for="DTE_Field_{literal}{{name}}{/literal}"></label>
        <div data-dte-e="input" class="col-md-9 col-form-label">
            <div data-dte-e="input-control" id="{literal}{{name}}{/literal}_input_control" class="DTE_Field_InputControl form-input-control" style="display: block;">
                <!-- actual input field here -->
            </div>   
            <div data-dte-e="info" id="{literal}{{name}}{/literal}_input_info" class="DTE_Field_Info form-input-info d-none">
            {literal}{{fieldInfo}}{/literal}
            </div>    
        </div>
    </div>
</script>

<script type="text/javascript" defer>

    var base_url = "{$base_url}";
    var site_url = "{$site_url}";
    var ajax_url = "{$tbl.ajax}";
    var tbl_title = "{$tbl.title}";

    var field_list = [];

    var edit_list = null;
    var detail = null;

    $(document).ready(function() {
        let _options = [];
        let _attr = {};

        // let conf = null;
        // let field = null;
        // let dom = null;
        // let edit_type = null;
        // let input_container = null;
        let form_container = $('#{$tbl.table_id}_form');
        // let val = null;

        {if !empty($detail)}
        detail = {$detail|@json_encode nofilter};
        //set key and value for subtable

        {foreach $subtables as $subtbl}
        selected_key_{$subtbl.crud.table_id} = detail['{$tbl.key_column}'];
        selected_label_{$subtbl.crud.table_id} = detail['{$tbl.lookup_column}'];
        {/foreach}

        {/if}

        {if $detail && !empty($readonly)} 
        let fieldInfo = '';
        let colName = "";
        let colLabel = "";
        let className = "";
        let dom = null;
        let label_container = null;
        let input_container = null;
        let field_container = null;
        let colValue = null;
        let data = null;
        let colType = '';
        let filename = '';
        let path = '';

        {foreach $tbl.columns as $col}
        {if (!$col.visible)} {continue} {/if}

        colName = '{$col.name}';
        colLabel = '{$col.label}';
        colType = '{$col.type}';
        className = "col_{$col.type} {$col.css} {if isset($col.type) && $col.type=='tcg_toggle'}text-center{/if}";

        dom = $(render_template('#crud-detail-row', {
            'name'      : colName,
            'type'      : colType,
            'fieldInfo' : fieldInfo,
            'className' : className
        }));

        {if $col.foreign_key && $col.type=="tcg_select2"}
        data = detail["{$col.name}_label"];
        {else}
        data = detail['{$col.name}'];
        {/if}

        colValue = data;
        {if isset($col.type) && $col.type=="tcg_upload"}
            filename = row['{$col.name}_filename'];
            path = row['{$col.name}_path'];
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
            {if $col.display_format_js}
            colValue = {$col.display_format_js}(data, colType, detail);
            {else}
            colValue = data;
            {/if}
        {else if isset($col.type) && $col.type=="tcg_date"}
            if (typeof data === 'undefined' || data == null || data.substring(0,10) == "0000-00-00") {
                colValue = "";
            }

            if (data != "") {
                data = moment.utc(data).local().format('YYYY-MM-DD');
                {if $col.display_format_js}
                colValue = {$col.display_format_js}(data, colType, detail);
                {else}
                colValue = data;
                {/if}
            }
        {else if isset($col.type) && $col.type=="tcg_datetime"}
            if (typeof data === 'undefined' || data == null || data == "0000-00-00 00:00:00") {
                data = "";
            }

            if (data != "") {
                data = moment.utc(data).local().format('YYYY-MM-DD HH:mm:ss');
                {if $col.display_format_js}
                colValue = {$col.display_format_js}(data, colType, detail);
                {else}
                colValue = data;
                {/if}
            }
        {else if isset($col.type) && $col.type=="tcg_currency"}
            if (typeof data === 'undefined' || data === null || data == "") {
                data = 0;
            }
            data = $.fn.dataTable.render.number('{$currency_thousand_separator}', '{$currency_decimal_separator}', {$currency_decimal_precision}, '{$currency_prefix}').display(data);
            {if $col.display_format_js}
            colValue = {$col.display_format_js}(data, colType, detail);
            {else}
            colValue = data;
            {/if}
        {else if isset($col.type) && $col.type=="tcg_toggle"}
            if (typeof data === 'undefined' || data === null || data == "" ) {
                colValue = "";
            }
            else if (data == '1') {
                colValue = '{__("Ya")}';
            }
            else {
                colValue = '{__("Tdk")}';
            }
        {else if $col.display_format_js}
            if (typeof data === 'undefined' || data === null) {
                data = "";
            }
            colValue = {$col.display_format_js}(data, colYype, detail);
        {/if}
  
        label_container = dom.find("label[data-dte-e=label]");
        label_container.html(colLabel);

        input_container = dom.find("#" +colName+ "_input_control");
        input_container.prepend(colValue);

        field_container = $("[data-editor-template='" +colName+ "']", form_container);
        field_container.append(dom);

        {/foreach}

        {/if}

        {if ($detail && (!isset($readonly) || $readonly==0))}
        edit_list = [
            {if !empty($level1_column)}
            {
                name: "{$level1_column}",
                type: "hidden"
            },
            {/if}
            {foreach $tbl.editor_columns as $col}
            {       
                "label": "{$col.edit_label} {if $col.edit_label && $col.edit_compulsory}<span class='text-danger font-weight-bold'>*</span>{/if}",

                {if $col.edit_field|@count==1}
                "name": "{$col.edit_field[0]}",
                {else if $col.edit_field|@count>1}
                "name": "{$col.name}",
                {/if}

                {if $col.edit_type == 'js'}
                "type": 'hidden',
                {else if $col.edit_type == 'tcg_currency'}
                "type": 'tcg_mask',
                "mask": "#{$currency_thousand_separator}##0",
                {else if $col.edit_field|@count>1}
                "type": "tcg_readonly",
                {else}
                "type": '{$col.edit_type}',
                {/if}

                'info'      : "{$col.edit_info}",
                'className' : "{$col.edit_css}",
                'labelInfo' : "",
                'message'   : "",

                {if isset($col.edit_options)}
                "options": [
                    {foreach from=$col.edit_options key=k item=v}
                    {if is_array($v)}
                        { "label": "{$v.label}", "value": "{$v.value}" },
                    {else}
                        { "label": "{$v}", "value": "{$k}" },
                        {/if}
                    {/foreach}
                ],
                {/if}

                {if !empty($col.edit_attr)}
                "attr": {$col.edit_attr|@json_encode nofilter},
                {/if}

                {if !empty($col.options_data_url) && $col.edit_type=='tcg_select2'}
                {if $col.options_data_url_params|@count==0}
                "ajax": "{$site_url}{$col.options_data_url}",
                {/if}
                {/if}

                {if $col.edit_type=='tcg_upload'}
                ajax: "{$tbl.ajax}",
                {/if}

                {if !empty($col.edit_info)}
                "fieldInfo":  "{$col.edit_info}",
                {/if}

                {if isset($col.edit_def_value) && $col.edit_def_value != null}
                "def":  "{$col.edit_def_value}",
                {/if}

                {if $col.edit_type=='upload' || $col.edit_type=='image'}
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

        edit_list.forEach(function(conf, index, arr) {
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
                input_field = $("<span>" +conf.type+ "</span>");
            }
            else {
                input_field = edit_type.create(conf);
            }

            {if !empty($readonly)}
            input_field.find(".form-control").attr("disabled", true);
            input_field.find("select").attr("disabled", true);
            {/if}

            let label_container = dom.find("label[data-dte-e=label]");
            label_container.html(conf.label);

            let input_container = dom.find("#" +conf.name+ "_input_control");
            input_container.prepend(input_field);

            let field_container = $("[data-editor-template='" +conf.name+ "']", form_container);
            field_container.append(dom);

            //set the value if necessary
            if (detail != null) {
                edit_type.set( conf, detail[conf.name] );
            }

            //update the stored value
            arr[index] = conf;
        });

        $(".crud-form-submit[data-table-id='{$tbl.table_id}']").on("click", function(e) {
            let form = $(".crud-form[data-table-id='{$tbl.table_id}']");
            let id = form.data('id');
            if (typeof id === "undefined" || id === null) {
                id = 0;
            }

            let data = {};
            let item = {};
            let error = false;

            edit_list.forEach(function(conf, index, arr) {
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
                if (conf.compulsory && (typeof val === 'undefined' || val === null || val == "") && edit_type == 'tcg_toggle') {
                    field.html("{__('Harus diisi')}");
                    field.removeClass("d-none");
                    error = true;
                    return;
                }

                //TODO: other validation
            })

            //check for error
            if (error)  return;

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
                            if (typeof item !== "undefined" && item != null) {
                                let id = item["{$tbl.key_column}"];
                                form.data('id', id);
                            }

                            //raise event
                            form.trigger('crud.created', response, form_data);
                            toastr.success("{__('Data berhasil disimpan')}!");
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
        {/if}

        $(".crud-form-table[data-table-id='{$tbl.table_id}']").on("click", function(e) {

            //TODO: get confirmation!

            window.location.href = "{$tbl.crud_url}";

        });

    });
</script>
