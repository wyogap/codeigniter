<script type="text/javascript">

    {foreach $crud.filter_columns as $f} 
        {if $f.type == 'js'}{continue}{/if}
        {if isset($userdata['f_$f.name'])}
        var v_{$f.name} = "{$userdata['f_$f.name']}";
        {else}
        var v_{$f.name} = "";
        {/if}
    {/foreach}

    //flag when filter is changing, to protect when we use cascading filter
    var _onfilterchanging = false;

    $('.adv-search-btn').click(function(e) {
        $('.adv-search-box').toggle();
    });

    $('.btn-search').click(function(e) {
        e.stopPropagation();
        //reload, reset paging
        dt_{$crud.table_id}.ajax.reload();
    });

    $("#search").keyup(function (e) {
        if (e.which == 13) {
            $('.btn-search').trigger('click');
        }
    });

    $(document).ready(function() {
        $('input.date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });

        $('.daterange').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });

        {foreach $crud.filter_columns as $f} 
            {if $f.type == 'js'}{continue}{/if}

            {if ($f.type == 'select' || $f.type == 'tcg_select2')}
                {if (!empty($f.options_data_url))}
                    //populate the list
                    populate_filter_{$f.name}();
                {else}
                    //rebuild as select2
                    select2_rebuild($('#f_{$f.name}'), 
                        {
                            multiple: {if empty($f.attr) || empty($f.attr.multiple)}false{else}true{/if},
                            minimumResultsForSearch: {if empty($f.attr) || empty($f.attr.minimumResultsForSearch)}10{else}{$f.attr.minimumResultsForSearch}{/if},
                        }, 
                        null);
                {/if}
            {else if $f.type == 'distinct'}
                $("#f_{$f.name}").select2({
                    minimumResultsForSearch: 10,
                    minimumInputLength: 0,
                    //theme: "bootstrap",
                });    
            {/if}

            //reset the value
            $("#f_{$f.name}").val(v_{$f.name}).trigger('change');

            //on-change event
            $('#f_{$f.name}').on('change', function() {
                v_{$f.name} = $("#f_{$f.name}").val();   

                if (_onfilterchanging)  return;
                _onfilterchanging = true;

                {* reset cascading filter if necessary *}
                {foreach $crud.filter_columns as $cascading}
                    {* check if it is cascading filter*}
                    {$flag = 0}
                    {foreach $cascading.cascading_filters as $param}
                        {if $param != "f_`$f.name`"} {continue} {/if}
                        //reset the value
                        $("#f_{$cascading.name}").val("").trigger('change');
                        {break}
                    {/foreach}
                {/foreach} 

                do_filter();

                {foreach $crud.filter_columns as $cascading}
                    {* check if it is cascading filter*}
                    {$flag = 0}
                    {foreach $cascading.cascading_filters as $param}
                        {if $param != "f_`$f.name`"} {continue} {/if}
                        //repopulate the list
                        populate_filter_{$cascading.name}();
                        {break}
                    {/foreach}
                {/foreach} 

                _onfilterchanging = false;
            });        
        {/foreach}

        $('#btn_crud_filter').click(function(e) {
            e.stopPropagation();
            do_filter();
        });

        {foreach $crud.filter_columns as $f} 

        {/foreach}
    });

    {foreach $crud.filter_columns as $f} 
    {if ($f.type == 'select' || $f.type == 'tcg_select2')}
    function populate_filter_{$f.name}() {
        let _options = [];
        let _attr = {};

        //default value
        let _multiple = false;
        let _minimumResult = 10;
        let _url = '';
 
        {if isset($f.attr)}
        _multiple = {if empty($f.attr.multiple)}_multiple{else}true{/if};
        _minimumResult = {if empty($f.attr.minimumResultsForSearch)}_minimumResult{else}{$f.attr.minimumResultsForSearch} {/if}
        {/if}

        _attr = {
            multiple: _multiple,
            minimumResultsForSearch: _minimumResult,
        };

        {if (!empty($f.options_data_url))}
            //retrieve list from json
            url = "{$site_url}{$f.options_data_url}";

            {foreach $f.cascading_filters as $cascading}
                {* build the parameters *}
                val = $("#{$cascading}").val();
                param = "{literal}{{{/literal}{$cascading}{literal}}}{/literal}";
                url = url.replace(param, val);
            {/foreach}

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                beforeSend: function(request) {
                    request.setRequestHeader("Content-Type", "application/json");
                },
                success: function(response) {
                    if (response.data === null) {
                        //error("Gagal mendapatkan daftar kas.");
                        _options = null;
                    } else if (typeof response.error !== 'undefined' && response.error !== null && response
                        .error != "") {
                        //error(response.error);
                        _options = null;
                    } else {
                        _options = response.data;
                    }

                    if(_options.length == 0) {
                        v_{$f.name} = null;
                        $('#f_{$f.name}').attr("disabled", true);
                    }
                    else if(_options.length == 1) {
                        v_{$f.name} = _options[0]['value'];
                        $('#f_{$f.name}').attr("disabled", true);
                    }
                    else {
                        $('#f_{$f.name}').attr("disabled", false);
                    }

                    {if $f.type == 'tcg_select2'}
                    select2_build($('#f_{$f.name}'), "-- {$f.label} --", "", v_{$f.name}, _options, _attr, null);
                    {else}
                    select_build($('#f_{$f.name}'), "-- {$f.label} --", "", v_{$f.name}, _options, _attr);
                    {/if}
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
                    toastr.error(textStatus);
                    //rebuild select2 with default options
                    {if $f.type == 'tcg_select2'}
                    select2_build($('#f_{$f.name}'), "-- {$f.label} --", "", v_{$f.name}, _options, _attr, null);
                    {else}
                    select_build($('#f_{$f.name}'), "-- {$f.label} --", "", v_{$f.name}, _options, _attr);
                    {/if}
                }
            });
        {else if ($f.type == 'tcg_select2')}
            //rebuild as select2
            select2_rebuild($('#f_{$f.name}'), _attr, null);
        {/if}
    }
    {/if}
    {/foreach}


    function do_filter() {

        let flag = true;
        {foreach $crud.filter_columns as $f} 
            {if $f.type == 'js' || !$f.is_required} {continue} {/if}

            if (v_{$f.name} == '' || v_{$f.name} == 0 || v_{$f.name} == null) {
                $("#f_{$f.name}").addClass('need-attention');
                flag = false;

                {if $f.type == 'tcg_select2'}
                $("#f_{$f.name}").select2();
                {/if}
            }
            else {
                $("#f_{$f.name}").removeClass('need-attention');
            }
        {/foreach}

        if (flag) {
            //reload, reset paging
            dt_{$crud.table_id}.ajax.reload(dt_{$crud.table_id}_post_load);
        }
        else {
            error_notify('Filter wajib belum diisi');
        }

    }
</script>
