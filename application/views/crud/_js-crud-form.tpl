
<script type="text/javascript">
    $(document).ready(function() {
        let _options = [];
        let _attr = {};

        {foreach $tbl.editor_columns as $col} 
            {if ($col.edit_type == 'select' || $col.edit_type == 'select2') && !isset($col.edit_options) && isset($col.edit_attr)}
            //default attr
            _attr = {
                multiple: {if empty($col.edit_attr.multiple)}false{else}true{/if},
                minimumResultsForSearch: {if empty($col.edit_attr.minimumResultsForSearch)}25{else}{$col.edit_attr.minimumResultsForSearch}{/if},
            };

            //retrieve list from json
            $.ajax({
                url: "{$col.edit_attr.ajax}",
                type: 'GET',
                dataType: 'json',
                beforeSend: function(request) {
                    request.setRequestHeader("Content-Type", "application/json");
                },
                success: function(response) {
                    if (response.data === null) {
                        //error("Gagal mendapatkan daftar kas.");
                        _options = null;
                    } else if (typeof response.error !== 'undefined' && response.error !==
                        null && response
                        .error != "") {
                        //error(response.error);
                        _options = null;
                    } else {
                        _options = response.data;
                    }

                    {if $col.edit_type == 'select2'}
                    select2_build($('#f_{$col.edit_field}'), "-- {__($col.label)} --", "{if $detail}{$detail[$col.edit_field]}{/if}", v_{$col.edit_field}, _options, _attr, null); 
                    {else}
                    select_build($('#f_{$col.edit_field}'), "-- {__($col.label)} --", "{if $detail}{$detail[$col.edit_field]}{/if}", v_{$coledit_field}, _options, _attr); 
                    {/if}
                },
                error: function(jqXhr, textStatus, errorMessage) {
                    {if $col.edit_type == 'select2'}
                    select2_build($('#f_{$col.edit_field}'), "-- {__($col.label)} --", "{if $detail}{$detail[$col.edit_field]}{/if}", v_{$col.edit_field}, _options, _attr, null); 
                    {else}
                    select_build($('#f_{$col.edit_field}'), "-- {__($col.label)} --", "{if $detail}{$detail[$col.edit_field]}{/if}", v_{$col.edit_field}, _options, _attr); 
                    {/if}
                }    
            }); 
            {/if} 
        {/foreach}

        $(".crud-form-submit[data-table-id='{$tbl.table_id}']").on("click", function(e) {
            let form = $(".crud-form[data-table-id='{$tbl.table_id}']");
            let id = form.data('id');
            if (typeof id === "undefined" || id === null) {
                id = 0;
            }

            let field = null;
            let data = {};

            let item = {};
            {foreach $tbl.editor_columns as $col}
            {if $col.edit_type == 'readonly'}
                //ignore
            {else if $col.edit_type == 'upload'}
                //TODO
            {else}
                field = form.find(".form-control[name='{$col.edit_field}']");
                item['{$col.edit_field}'] = field.val();
            {/if}
            {/foreach}

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

        // $(".crud-form[data-table-id='{$tbl.table_id}']").on("crud.updated", function(e, json, form_data) {
        //     alert(JSON.stringify(json));
        // }) ;

    });
</script>