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

{if $num_visible_filter}
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
                                        <div class="btn-group"><button type="submit" class="btn btn-primary btn-search"><span
                                                    class="glyphicon glyphicon-search"><i class="fas fa-search"></i></span></button><a class="btn btn-default adv-search-btn"
                                                href="#"><span class='d-none d-md-inline'>{__('Filter')} </span><span class="caret"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="adv-search-box" style="display: none;">
                        {foreach $crud.filter_columns as $f} 
                            {if $f.filter_type != 'js'}
                            <div class="form-group col-md-6 mb-0 mt-1 {$f.filter_css}">
                                {if $f.filter_type == 'select' || $f.filter_type == 'tcg_select2'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control" placeholder="{__($f.filter_label)}">
                                        <option value=''>-- {__($f.filter_label)} --</option>
                                        {if $f.filter_invalid_value}
                                        <option value='null'>-- {__("Kosong/Data Tidak Valid")} --</option>
                                        {/if}
                                        {if isset($f.filter_options)}
                                            {foreach from=$f.filter_options key=k item=v}
                                            {if is_array($v)}
                                            <option value="{$v.value}">{$v.label}</option>
                                            {else}
                                            <option value="{$k}">{$v}</option>
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else if $f.filter_type == 'distinct'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control" placeholder="{__($f.filter_label)}">
                                        <option value=''>-- {__($f.filter_label)} --</option>
                                        {if isset($f.filter_options)}
                                            {foreach from=$f.filter_options key=k item=v}
                                            {if is_array($v)}
                                            <option value="{$v.value}">{$v.label}</option>
                                            {else}
                                            <option value="{$k}">{$v}</option>
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else}
                                    <input class="form-control" type="text" id="f_{$f.name}" name="{$f.name}" placeholder="{__($f.filter_label)}"/>
                                {/if}
                            </div>
                            {/if}
                        {/foreach}
                        </div>
                        {else if $crud.filter}
                        <div class="row">
                        {foreach $crud.filter_columns as $f} 
                            {if $f.filter_type != 'js'}
                            <div class="form-group col-md-6 mb-0 mt-1 {$f.filter_css}">
                                <label>{__($f.filter_label)}</label>
                                {if $f.filter_type == 'select' || $f.filter_type == 'tcg_select2'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control" placeholder="{__($f.filter_label)}">
                                        <option value=''>-- {__($f.filter_label)} --</option>
                                        {if $f.filter_invalid_value}
                                        <option value='null'>-- {__("Kosong/Data Tidak Valid")} --</option>
                                        {/if}
                                        {if isset($f.filter_options)}
                                            {foreach from=$f.filter_options key=k item=v}
                                            {if is_array($v)}
                                            <option value="{$v.value}">{$v.label}</option>
                                            {else}
                                            <option value="{$k}">{$v}</option>
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else if $f.filter_type == 'distinct'}
                                    <select id="f_{$f.name}" name="{$f.name}" class="form-control" placeholder="{__($f.filter_label)}">
                                        <option value=''>-- {__($f.filter_label)} --</option>
                                        {if isset($f.filter_options)}
                                            {foreach from=$f.filter_options key=k item=v}
                                            {if is_array($v)}
                                            <option value="{$v.value}">{$v.label}</option>
                                            {else}
                                            <option value="{$k}">{$v}</option>
                                            {/if}
                                            {/foreach}
                                        {/if}
                                    </select>
                                {else}
                                    <input class="form-control" type="text" id="f_{$f.name}" name="{$f.name}" placeholder="{__($f.filter_label)}"/>
                                {/if}
                            </div>
                            {/if}
                        {/foreach}
                        </div>
                        {/if}
                    </div>
                    {if $crud.filter && !$crud.search}
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
        {if $f.filter_type != 'js'}
            {if isset($userdata['f_$f.name'])}
            var v_{$f.name} = "{$userdata['f_$f.name']}";
            {else}
            var v_{$f.name} = "";
            {/if}
        {/if}
    {/foreach}

    $('.adv-search-btn').click(function(e) {
        $('.adv-search-box').toggle();
    });

    $('.btn-search').click(function(e) {
        e.stopPropagation();
        dt_{$crud.table_id}.ajax.reload();
    });

    $(document).ready(function() {
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
                        select2_build($('#f_{$f.name}'), "-- {__($f.filter_label)} --", "", v_{$f.name}, _options, _attr, null);
                        {else}
                        select_build($('#f_{$f.name}'), "-- {__($f.filter_label)} --", "", v_{$f.name}, _options, _attr);
                        {/if}
                    },
                    error: function(jqXhr, textStatus, errorMessage) {
                        console.log(jqXhr.responseText);
                        {if $f.filter_type == 'tcg_select2'}
                        select2_build($('#f_{$f.name}'), "-- {__($f.filter_label)} --", "", v_{$f.name}, _options, _attr, null);
                        {else}
                        select_build($('#f_{$f.name}'), "-- {__($f.filter_label)} --", "", v_{$f.name}, _options, _attr);
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
            {if $f.filter_type != 'js'}
                $("#f_{$f.name}").val(v_{$f.name});
                $('#f_{$f.name}').on('change', function() {
                    v_{$f.name} = $("#f_{$f.name}").val();
                });        
            {/if}
        {/foreach}

        $('#btn_crud_filter').click(function(e) {
            e.stopPropagation();
            dt_{$crud.table_id}.ajax.reload();
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
</script>

{/if}