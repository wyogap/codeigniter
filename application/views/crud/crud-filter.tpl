<style>
.adv-search-box {
    padding: 12px 10px 0px 10px;
    display: flex;
    flex-wrap: wrap;
}

.btn .caret {
    margin-left: 0;
}
.caret {
    display: inline-block;
    width: 0;
    height: 0;
    margin-left: 2px;
    vertical-align: middle;
    border-top: 4px dashed;
    border-top: 4px solid\9;
    border-right: 4px solid transparent;
    border-left: 4px solid transparent;
}

.cust-input-grp .btn-group .adv-search-btn {
    box-shadow: none;
    border: 1px solid #ccc;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}

</style>

{assign var=num_visible_filter value=0 }
{foreach $crud.filter_columns as $f} 
    {if $f.filter_type != "js"}
        {assign var=num_visible_filter value=$num_visible_filter+1 }
    {/if}
{/foreach}

{if $num_visible_filter || $crud.search}
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                        {if $crud.search}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group" id="adv-search"><input type="text" name="search" id="search"
                                        class="form-control" placeholder="{__('Pencarian')}">
                                    <div class="input-group-btn cust-input-grp">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-primary btn-search"><span
                                                    class="glyphicon glyphicon-search"><i class="fas fa-search"></i></span></button>
                                            {if $num_visible_filter}
                                            <a class="btn btn-default adv-search-btn"
                                                href="#"><span class='d-none d-md-inline'>{__('Filter')} </span><span class="caret"></span></a>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {if $num_visible_filter}
                        <div class="adv-search-box" style="display: none;">
                        {foreach $crud.filter_columns as $f} 
                            {if $f.filter_type != 'js'}
                            <div class="form-group col-4 mb-0 mt-1 {$f.filter_css}">
                                {if $f.filter_type == 'select' || $f.filter_type == 'tcg_select2'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control filter_select" placeholder="{$f.filter_label}">
                                        <option value=''>-- {$f.filter_label} --</option>
                                        {if $f.filter_invalid_value}
                                        <option value='null'>-- {__("Kosong/Data Tidak Valid")} --</option>
                                        {/if}
                                        {if isset($f.filter_options)}
                                            {foreach from=$f.filter_options key=k item=v}
                                            {if is_array($v)}
                                            {$v.label|trim}
                                            <option value="{$v.value}">{if empty($v.label)}-- {__("Kosong")} --{else}{$v.label}{/if}</option>
                                            {else}
                                            {$v|trim}
                                            <option value="{$k}">{if empty($v)}-- {__("Kosong")} --{else}{$v}{/if}</option>
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else if $f.filter_type == 'distinct'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control filter_distinct" placeholder="{$f.filter_label}">
                                        <option value=''>-- {$f.filter_label} --</option>
                                        {if isset($f.filter_options)}
                                            {foreach from=$f.filter_options key=k item=v}
                                            {if is_array($v)}
                                                {$v.value|trim}
                                                {if empty($v.value)}
                                                <option value="null">-- {__("Kosong")} --</option>
                                                {else}
                                                <option value="{$v.value}">{$v.label}</option>
                                                {/if}
                                            {else}
                                                {$k|trim}
                                                {if empty($k)}
                                                <option value="null">-- {__("Kosong")} --</option>
                                                {else}
                                                <option value="{$k}">{$v}</option>
                                                {/if}
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else if $f.filter_type == 'date'}
                                    <input type="text" class="form-control filter_date" id="f_{$f.name}" name="{$f.name}" placeholder="{$f.filter_label}">
                                {else if $f.filter_type == 'daterange'}
                                    <div class="input-daterange input-group filter_daterange" id="f_{$f.name}">
                                        <input type="text" class="input-sm form-control" name="{$f.name}_start" placeholder="{$f.filter_label}"/>
                                        <span class="input-group-addon filter_daterange_separator">s/d</span>
                                        <input type="text" class="input-sm form-control" name="{$f.name}_end" />
                                    </div>                               
                                {else}
                                    <input class="form-control filter_{$f.filter_type}" type="text" id="f_{$f.name}" name="{$f.name}" placeholder="{$f.filter_label}"/>
                                {/if}
                            </div>
                            {/if}
                        {/foreach}
                        </div>
                        {/if}
                        {else if $crud.filter}
                        <div class="row">
                        {foreach $crud.filter_columns as $f} 
                            {if $f.filter_type != 'js'}
                            <div class="form-group col-4 mb-0 mt-1 {$f.filter_css}">
                                <label>{$f.filter_label}</label>
                                {if $f.filter_type == 'select' || $f.filter_type == 'tcg_select2'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control filter_select" placeholder="{$f.filter_label}">
                                        <option value=''>-- {$f.filter_label} --</option>
                                        {if $f.filter_invalid_value}
                                        <option value='null'>-- {__("Kosong/Data Tidak Valid")} --</option>
                                        {/if}
                                        {if isset($f.filter_options)}
                                            {foreach from=$f.filter_options key=k item=v}
                                            {if is_array($v)}
                                            {$v.label|trim}
                                            <option value="{$v.value}">{if empty($v.label)}-- {__("Kosong")} --{else}{$v.label}{/if}</option>
                                            {else}
                                            {$v|trim}
                                            <option value="{$k}">{if empty($v)}-- {__("Kosong")} --{else}{$v}{/if}</option>
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else if $f.filter_type == 'distinct'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control filter_distinct" placeholder="{$f.filter_label}">
                                        <option value=''>-- {$f.filter_label} --</option>
                                        {if isset($f.filter_options)}
                                            {foreach from=$f.filter_options key=k item=v}
                                            {if is_array($v)}
                                                {$v.value|trim}
                                                {if empty($v.value)}
                                                <option value="null">-- {__("Kosong")} --</option>
                                                {else}
                                                <option value="{$v.value}">{$v.label}</option>
                                                {/if}
                                            {else}
                                                {$k|trim}
                                                {if empty($k)}
                                                <option value="null">-- {__("Kosong")} --</option>
                                                {else}
                                                <option value="{$k}">{$v}</option>
                                                {/if}
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else if $f.filter_type == 'date'}
                                    <input type="text" class="form-control filter_date" id="f_{$f.name}" name="{$f.name}" placeholder="{$f.filter_label}">
                                {else if $f.filter_type == 'daterange'}
                                    <div class="input-daterange input-group filter_daterange" id="f_{$f.name}">
                                        <input type="text" class="input-sm form-control" name="{$f.name}_start" placeholder="{$f.filter_label} Awal"/>
                                        <span class="input-group-addon filter_datarange_separator" style="margin-top: 8px;">-</span>
                                        <input type="text" class="input-sm form-control" name="{$f.name}_end" placeholder="{$f.filter_label} Akhir" />
                                    </div>                               
                                {else}
                                    <input class="form-control filter_{$f.filter_type}" type="text" id="f_{$f.name}" name="{$f.name}" placeholder="{$f.filter_label}"/>
                                {/if}
                            </div>
                            {/if}
                        {/foreach}
                        </div>
                        {/if}
                    </div>
                    {if $crud.filter && !$crud.search && $num_visible_filter>1}
                    <div class="card-footer">
                        <div class="row">
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary btn-block" id='btn_crud_filter'
                                    name="button">{__('Tampilkan')}</button>
                        </div>
                        </div>
                    </div>
                    {/if}
                </div> <!-- end card-box-->
            </div> <!-- end col-->
        </div>
    </div>
</section>

<script type="text/javascript">

    {foreach $crud.filter_columns as $f} 
        {if $f.filter_type == 'js'}{continue}{/if}
        {if isset($userdata['f_$f.name'])}
        var v_{$f.name} = "{$userdata['f_$f.name']}";
        {else}
        var v_{$f.name} = "";
        {/if}
    {/foreach}

    $('.adv-search-btn').click(function(e) {
        $('.adv-search-box').toggle();
    });

    $('.btn-search').click(function(e) {
        e.stopPropagation();
        dt_{$crud.table_id}.ajax.reload();
    });

    $("#search").keyup(function (e) {
        if (e.which == 13) {
            $('.btn-search').trigger('click');
        }
    });

    $(document).ready(function() {
        $('input.filter_date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });

        $('.filter_daterange').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });

        let _options = [];
        let _attr = {};

        let _multiple = false;
        let _minimumResult = 10;

        {foreach $crud.filter_columns as $f} 
            {if ($f.filter_type == 'select' || $f.filter_type == 'tcg_select2')}
                //default value
                _multiple = false;
                _minimumResult = 10;

                {if isset($f.filter_attr)}
                _multiple = {if empty($f.filter_attr.multiple)}_multiple{else}true{/if};
                _minimumResult = {if empty($f.filter_attr.minimumResultsForSearch)}_minimumResult{else}{$f.filter_attr.minimumResultsForSearch} {/if}
                {/if}

                _attr = {
                    multiple: _multiple,
                    minimumResultsForSearch: _minimumResult,
                };

                {if (isset($f.filter_attr) && !empty($f.filter_attr.ajax))}
                //retrieve list from json
                $.ajax({
                    url: "{$site_url}{$f.filter_attr.ajax}",
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

                        {if $f.filter_type == 'tcg_select2'}
                        select2_build($('#f_{$f.name}'), "-- {$f.filter_label} --", "", v_{$f.name}, _options, _attr, null);
                        {else}
                        select_build($('#f_{$f.name}'), "-- {$f.filter_label} --", "", v_{$f.name}, _options, _attr);
                        {/if}
                    },
                    error: function(jqXhr, textStatus, errorMessage) {
                        console.log(jqXhr.responseText);
                        {if $f.filter_type == 'tcg_select2'}
                        select2_build($('#f_{$f.name}'), "-- {$f.filter_label} --", "", v_{$f.name}, _options, _attr, null);
                        {else}
                        select_build($('#f_{$f.name}'), "-- {$f.filter_label} --", "", v_{$f.name}, _options, _attr);
                        {/if}
                    }
                });
                {else if ($f.filter_type == 'tcg_select2')}
                //rebuild as select2
                select2_rebuild($('#f_{$f.name}'), _attr, null);
                {/if}

            {/if}
        {/foreach}

        {foreach $crud.filter_columns as $f} 
            {if $f.filter_type == 'js'}{continue}{/if}
            $("#f_{$f.name}").val(v_{$f.name});
            $('#f_{$f.name}').on('change', function() {
                v_{$f.name} = $("#f_{$f.name}").val();
    
                {if $num_visible_filter == 1}
                do_filter();
                {/if}
            });        
        {/foreach}

        $('#btn_crud_filter').click(function(e) {
            e.stopPropagation();
            do_filter();
        });

        {foreach $crud.filter_columns as $f} 
        {if $f.filter_type == 'distinct'}
            $("#f_{$f.name}").select2({
                minimumResultsForSearch: 10,
                minimumInputLength: 0,
                //theme: "bootstrap",
            });    
        {/if}
        {/foreach}
    });

    function do_filter() {

        let flag = true;
        {foreach $crud.filter_columns as $f} 
            {if $f.filter_type == 'js' || !$f.is_required} {continue} {/if}

            if (v_{$f.name} == '' || v_{$f.name} == 0 || v_{$f.name} == null) {
                $("#f_{$f.name}").addClass('need-attention');
                flag = false;

                {if $f.filter_type == 'tcg_select2'}
                $("#f_{$f.name}").select2();
                {/if}
            }
            else {
                $("#f_{$f.name}").removeClass('need-attention');
            }
        {/foreach}

        if (flag) {
            dt_{$crud.table_id}.ajax.reload();
        }
        else {
            error_notify('Filter wajib belum diisi');
        }

    }
</script>

{/if}