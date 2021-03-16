{assign var=num_visible_filter value=0 }
{foreach $crud.filters as $f} 
    {if $f.type != "js"}
        {assign var=num_visible_filter value=$num_visible_filter+1 }
    {/if}
{/foreach}

{if $num_visible_filter}
<div class="row">
    <div class="col-12">
        <div class="card widget-inline">
            <div class="card-body">
                <div class="row"  style="display: flex;">
                {foreach $crud.filters as $f} 
                    {if $f.filter_type != 'js'}
                    <div class="form-group {$f.filter_css}">
                        {if $f.filter_type == 'select' || $f.filter_type == 'select2'}
                            <select id="f_{$f.edit_field}" name="{$f.edit_field}" class="form-control" placeholder="{__($f.label)}">
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
                            <input class="form-control" type="text" id="f_{$f.edit_field}" name="{$f.edit_field}" placeholder="{__($f.label)}"/>
                        {/if}
                    </div>
                    {/if}
                {/foreach}
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                <div class="col-sm-3">
                       <button type="submit" class="btn btn-primary btn-block" id='btn_crud_filter'
                            name="button">{__('View')}</button>
                </div>
                </div>
            </div>
        </div> <!-- end card-box-->
    </div> <!-- end col-->
</div>


<script type="text/javascript">

    {foreach $crud.filters as $f} 
        {if $f.filter_type != 'js'}
            {if isset($userdata['f_$f.edit_field'])}
            var v_{$f.edit_field} = "{$userdata['f_$f.edit_field']}";
            {else}
            var v_{$f.edit_field} = "";
            {/if}
        {/if}
    {/foreach}

    $(document).ready(function() {
        let _options = [];
        let _attr = {};

        {foreach $crud.filters as $f} 
            {if ($f.filter_type == 'select' || $f.filter_type == 'select2') && !isset($f.filter_options) && isset($f.filter_attr)}
                _attr = {
                    multiple: {if empty($f.filter_attr.multiple)}false{else}true{/if},
                    minimumResultsForSearch: {if empty($f.filter_attr.minimumResultsForSearch)} 25 {else} {$f.filter_attr.minimumResultsForSearch} {/if},
                };

                //retrieve list from json
                $.ajax({
                    url: "{$f.filter_attr.ajax}",
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

                        {if $f.type == 'select2'}
                        select2_build($('#f_{$f.edit_field}'), "-- {__($f.label)} --", "", v_{$f.edit_field}, _options, _attr, null);
                        {else}
                        select_build($('#f_{$f.edit_field}'), "-- {__($f.label)} --", "", v_{$f.edit_field}, _options, _attr);
                        {/if}
                    },
                    error: function(jqXhr, textStatus, errorMessage) {
                        {if $f.type == 'select2'}
                        select2_build($('#f_{$f.edit_field}'), "-- {__($f.label)} --", "", v_{$f.edit_field}, _options, _attr, null);
                        {else}
                        select_build($('#f_{$f.edit_field}'), "-- {__($f.label)} --", "", v_{$f.edit_field}, _options, _attr);
                        {/if}
                    }
                });
            {/if}
        {/foreach}

        {foreach $crud.filters as $f} 
            {if $f.filter_type != 'js'}
                $("#f_{$f.edit_field}").val(v_{$f.edit_field});
                $('#f_{$f.edit_field}').on('change', function() {
                    v_{$f.edit_field} = $("#f_{$f.edit_field}").val();
                });        
            {/if}
        {/foreach}

        $('#btn_crud_filter').click(function(e) {
            e.stopPropagation();
            dt.ajax.reload();
        });
    });

</script>

{/if}