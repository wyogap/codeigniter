<script type="text/javascript">

function onchange_demandtype(field, oldvalue, newvalue, editor) {
    if (newvalue == "0") {
        editor.field('frequency').hide();
        editor.field('frequencyunit').hide();
        editor.field('enddate').hide();
    } else {
        editor.field('frequency').show();
        editor.field('frequencyunit').show();
        editor.field('enddate').show();
    }
}

function onselect_renbut(dt, api, table_name) {
    let cnt = dt.rows('.selected').data().length;

    if (cnt == 0) {
        $("#tdata_89_wrapper .dt-action-buttons .dt-buttons").hide();

        $(".tabbable a[href$='pane_164']").removeClass('active');
        $(".tab-content #pane_164").removeClass('active');
        $(".tabbable .nav-item:has(a[href$='pane_164'])").hide();
        $(".tab-content #pane_164").hide();
        // $(".tabbable a[href$='pane_89']").removeClass('active');
        $(".tabbable a[href$='pane_89']").addClass('active');
        $(".tab-content #pane_89").addClass('active');
        $(".tabbable .nav-item:has(a[href$='pane_89'])").show();
        $(".tab-content #pane_89").show();
    }
    else if (cnt == 1){
        $("#tdata_89_wrapper .dt-action-buttons .dt-buttons").show();
        let status = dt.rows('.selected').data().pluck('status')[0];
        if (status == 'DRAFT') {
            $("#tdata_89_wrapper .dt-action-buttons .buttons-create").show();
            $("#tdata_89_wrapper .dt-action-buttons .buttons-edit").show();
            $("#tdata_89_wrapper .dt-action-buttons .buttons-remove").show();
            $("#tdata_89_wrapper .dt-action-buttons .btn-import").show();

            $(".tabbable a[href$='pane_164']").removeClass('active');
            $(".tab-content #pane_164").removeClass('active');
            $(".tabbable .nav-item:has(a[href$='pane_164'])").hide();
            $(".tab-content #pane_164").hide();
            // $(".tabbable a[href$='pane_89']").removeClass('active');
            $(".tabbable a[href$='pane_89']").addClass('active');
            $(".tab-content #pane_89").addClass('active');
            $(".tabbable .nav-item:has(a[href$='pane_89'])").show();
            $(".tab-content #pane_89").show();
        }
        else {

            $(".tabbable a[href$='pane_164']").addClass('active');
            $(".tab-content #pane_164").addClass('active');
            $(".tabbable .nav-item:has(a[href$='pane_164'])").show();
            $(".tab-content #pane_164").show();
            // $(".tabbable a[href$='pane_89']").removeClass('active');
            $(".tabbable a[href$='pane_89']").removeClass('active');
            $(".tab-content #pane_89").removeClass('active');
            $(".tabbable .nav-item:has(a[href$='pane_89'])").hide();
            $(".tab-content #pane_89").hide();
        }
    }
    // else {
    //     $("#tdata_89_wrapper .dt-action-buttons .dt-buttons").hide();

    //     $(".tabbable a[href$='pane_158']").removeClass('active');
    //     $(".tab-content #pane_158").removeClass('active');
    //     $(".tabbable .nav-item:has(a[href$='pane_158'])").hide();
    //     $(".tab-content #pane_158").hide();
    //     // $(".tabbable a[href$='pane_89']").removeClass('active');
    //     $(".tabbable a[href$='pane_89']").addClass('active');
    //     // $(".tab-content #pane_89").removeClass('active');
    //     $(".tab-content #pane_89").addClass('active');
    // }
}

function onadd_pengadaan() {
    alert("OnAdd");
}

function onedit_pengadaan() {
    alert("OnEdit");
}

function ondelete_pengadaan() {
    alert("OnDelete");
}

function conditional_edit(data, row, meta) {
    if (data['status'] == 'DRAFT') {
        return 1;
    }

    return 0;
}

function conditional_delete(data, row, meta) {
    if (data['status'] == 'DRAFT') {
        return 1;
    }

    return 0;
}

function conditional_approve(data, row, meta) {
    if (data['status'] == 'DRAFT') {
        return 1;
    }

    return 0;
}

function conditional_close(data, row, meta) {
    if (data['status'] == 'APPR') {
        return 1;
    }

    return 0;
}

function conditional_buatpengadaan(data, row, meta) {
    if (data['status'] == 'APPR') {
        return 1;
    }

    return 0;
}

function onclick_pengadaan(row, dt, id) {
    let data = dt.rows(row).data()[0];
    let status = data['status'];

    let url = '';
    if (status == 'DRAFT') {
        url = "{$site_url}/{$controller}/pengadaan/edit/" + id;
    }
    else {
        url = "{$site_url}{$controller}/pengadaan/detail/" + id;
    }

    window.open(url, "_blank");
}

function display_item_contractinfo(value, tipe, data) {
    if (value == null || value == '') return '';

    let kontrak = JSON.parse("[" +value+ "]");
    if (kontrak.length==0) {
        return "";
    }

    let str = "";
    for (i=0; i<kontrak.length; i++) {
        if (str.length > 0)     str += ", ";
        str += kontrak[i]["contractnum"] + " <a target='_blank' href='{$site_url}{$controller}/kontrak/detail/" +kontrak[i]["contractid"]+ "'><i class='fa fas fa-external-link-alt'></i></a>";
    }

    return str;
}

function display_item_poinfo (value, tipe, data) {
    if (value == null || value == '') return '';

    let pengadaan = JSON.parse("[" +value+ "]");
    if (pengadaan.length==0) {
        return "";
    }

    let str = "";
    for (i=0; i<pengadaan.length; i++) {
        if (str.length > 0)     str += ", ";
        str += pengadaan[i]["ponum"] + " <a target='_blank' href='{$site_url}{$controller}/pengadaan/detail/" +pengadaan[i]["poid"]+ "'><i class='fa fas fa-external-link-alt'></i></a>";
    }

    return str;
}

function display_item_doinfo(value, tipe, data) {
    if (value == null || value == '') return '';

    let perintahterima = JSON.parse("[" +value+ "]");
    if (perintahterima.length==0) {
        return "";
    }

    let str = "";
    for (i=0; i<perintahterima.length; i++) {
        if (str.length > 0)     str += ", ";
        str += perintahterima[i]["donum"] + " <a target='_blank' href='{$site_url}{$controller}/perintahterima/detail/" +perintahterima[i]["doid"]+ "'><i class='fa fas fa-external-link-alt'></i></a>";
    }

    return str;
}

</script>

<div class="form-group" id="form-tahun">
    <select id="f_tahun" name="tahun" class="form-control filter_select" placeholder="Tahun" style="width: 130px; height: 2rem; padding: 0rem 0.75rem;">
        {for $year = date('Y')-5; $year <= date('Y')+5; $year++}
        <option value="{$year}" data-select2-id="{$year}" {if $year == date('Y')}selected{/if}>TA {$year}</option>
        {/for}
    </select>
</div>


<script type="text/javascript">

    var v_itemtypeid = '{if !empty($userdata["itemtypeid"])}{$userdata["itemtypeid"]}{/if}';
    var v_siteid = '{if !empty($userdata["siteid"])}{$userdata["siteid"]}{/if}';
    var v_tahunanggaran = new Date().getFullYear();

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

        let _options = [];
        let _attr = {};

        let _multiple = false;
        let _minimumResult = 10;
        let _url = '';

        {foreach $crud.filters as $f} 
            {if ($f.type == 'select' || $f.type == 'tcg_select2')}
                //default value
                _multiple = false;
                _minimumResult = 10;

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
                {if (!empty($f.controller_params))}
                url += "?{$f.controller_params}";
                {/if}
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

                        {if $f.type == 'tcg_select2'}
                        select2_build($('#f_{$f.name}'), "-- {$f.label} --", "", v_{$f.name}, _options, _attr, null);
                        {else}
                        select_build($('#f_{$f.name}'), "-- {$f.label} --", "", v_{$f.name}, _options, _attr);
                        {/if}
                    },
                    error: function(jqXhr, textStatus, errorMessage) {
                        {if $f.type == 'tcg_select2'}
                        select2_build($('#f_{$f.name}'), "-- {$f.label} --", "", v_{$f.name}, _options, _attr, null);
                        {else}
                        select_build($('#f_{$f.name}'), "-- {$f.label} --", "", v_{$f.name}, _options, _attr);
                        {/if}
                    }
                });
                {else if ($f.type == 'tcg_select2')}
                //rebuild as select2
                //select2_rebuild($('#f_{$f.name}'), _attr, null);
                {/if}

            {/if}
        {/foreach}

        {foreach $crud.filters as $f} 
            {if $f.type == 'js'}{continue}{/if}
            $("#f_{$f.name}").val(v_{$f.name});
            $('#f_{$f.name}').on('change', function() {
                v_{$f.name} = $("#f_{$f.name}").val();   
                do_filter();
            });        
        {/foreach}

        $('#btn_crud_filter').click(function(e) {
            e.stopPropagation();
            do_filter();
        });

        {foreach $crud.filters as $f} 
        {if $f.type == 'distinct'}
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
        {foreach $crud.filters as $f} 
            {if $f.type == 'js' || !$f.is_required} {continue} {/if}

            if (v_{$f.name} == '' || v_{$f.name} == 0 || v_{$f.name} === null) {
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
            dt_{$crud.table_id}.ajax.reload();
        }
        else {
            error_notify('Filter wajib belum diisi');
        }

    }
</script>

{include file="crud/_js-crud-table.tpl" tbl=$crud}

<!-- Subtable Scripts -->
<script type="text/javascript" defer> 

    {foreach $subtables as $subtbl}
    var fkey_value_{$subtbl.crud.table_id} = '';
    var fkey_label_{$subtbl.crud.table_id} = '';
    var fdata_{$subtbl.crud.table_id} = null;
    var fkey_column_{$subtbl.crud.table_id} = null;
    {/foreach}

    $(document).ready(function() {

        {if empty($detail)}
            //use user-select event instead of select/deselect to avoid being triggerred because of API
            dt_{$crud.table_id}.on('select.dt deselect.dt', function() {
                let data = dt_{$crud.table_id}.rows({
                    selected: true
                }).data();

                if (data.length == 0) {
                    //on deselect all, clear subtables
                    {foreach $subtables as $subtbl}
                    dt_{$subtbl.crud.table_id}.clear().draw();
                    fkey_value_{$subtbl.crud.table_id} = '';
                    fdata_{$subtbl.crud.table_id} = null;
                    fkey_column_{$subtbl.crud.table_id} = "";
                    {/foreach}
                } else {
                    let f_tahun= $("#f_tahun").val();

                    {foreach $subtables as $subtbl}
                    //master value
                    fkey_value_{$subtbl.crud.table_id} = data[0]['{$subtbl.table_key_column}'];
                    fkey_label_{$subtbl.crud.table_id} = data[0]['{$subtbl.table_label_column}'];
                    fdata_{$subtbl.crud.table_id} = data[0];
                    fkey_column_{$subtbl.crud.table_id} = "{$subtbl.subtable_fkey_column}";

                    dt_{$subtbl.crud.table_id}.ajax.url("{$subtbl.crud.ajax}/" +fkey_value_{$subtbl.crud.table_id} +"?f_tahun="+f_tahun);
                    {if $subtbl.crud.editor}
                    editor_{$subtbl.crud.table_id}.s.ajax = "{$subtbl.crud.ajax}/" +fkey_value_{$subtbl.crud.table_id};
                    {/if}
                    dt_{$subtbl.crud.table_id}.ajax.reload();
                    {/foreach}
                }

            });
        {/if}
    });

</script>

{foreach $subtables as $subtbl}
    {include file="crud/_js-crud-table.tpl" tbl=$subtbl.crud fsubtable='1' fkey=$subtbl.subtable_fkey_column flabel=$subtbl.label}
{/foreach}

<script type="text/javascript">

    $(document).ready(function() {
        $(".tabbable a[href$='pane_164']").removeClass('active');
        $(".tab-content #pane_164").removeClass('active');
        $(".tabbable .nav-item:has(a[href$='pane_164'])").hide();
        $(".tab-content #pane_164").hide();
        // $(".tabbable a[href$='pane_89']").removeClass('active');
        $(".tabbable a[href$='pane_89']").addClass('active');
        $(".tab-content #pane_89").addClass('active');
        $(".tabbable .nav-item:has(a[href$='pane_89'])").show();
        $(".tab-content #pane_89").show();

        $("#tdata_89_wrapper .dt-action-buttons .dt-buttons").hide();

        let x_tahun = $("#form-tahun");
        let par3 = $("#tdata_164_wrapper .row .dt-action-buttons");
        par3.html("").append(x_tahun);

        // $("#f_tahun").on('change', function() {
        //     let f_tahun= $("#f_tahun").val();

        //     {foreach $subtables as $subtbl}
        //     {if $subtbl.crud.table_id!='tdata_164'}{continue}{/if}
        //     //master value
        //     dt_{$subtbl.crud.table_id}.ajax.url("{$subtbl.crud.ajax}/" +fkey_value_{$subtbl.crud.table_id} +"?f_tahun="+f_tahun);
        //     dt_{$subtbl.crud.table_id}.ajax.reload();
        //     {/foreach}
        // });

        // let _attr = {
        //         multiple: false,
        //         minimumResultsForSearch: 25,
        //     };

        // $.ajax({
        //     url: "{$site_url}{$controller}/satuankerja/lookup",
        //     type: 'GET',
        //     dataType: 'json',
        //     beforeSend: function(request) {
        //         request.setRequestHeader("Content-Type", "application/json");
        //     },
        //     success: function(response) {
        //         if (response.data === null) {} else if (typeof response.error !==
        //             'undefined' && response.error !== null && response
        //             .error != "") {} else {
        //             _options = response.data;
        //         }
        //         select2_build($('#f_siteid'), '-- Satuan Kerja --', '', v_siteid, _options, _attr);

        //         // select_build($('#edit-korwil'), _options, _attr);
        //         // $('#edit-korwil').val(korwil);
        //     },
        //     error: function(jqXhr, textStatus, errorMessage) {
        //         select2_build($('#f_siteid'), '-- Satuan Kerja --', '', v_siteid, null, _attr);
        //         // select_build($('#edit-korwil'), _options, _attr);
        //     }
        // });

        // $.ajax({
        //     url: "{$site_url}disbekal/select/tipebekal",
        //     type: 'GET',
        //     dataType: 'json',
        //     beforeSend: function(request) {
        //         request.setRequestHeader("Content-Type", "application/json");
        //     },
        //     success: function(response) {
        //         if (response !== null && response.length > 0) {
        //             select2_build($('#f_itemtypeid'), '-- Tipe Bekal --', '', v_typeid, response, _attr);
        //         }

        //         // select_build($('#edit-korwil'), _options, _attr);
        //         // $('#edit-korwil').val(korwil);
        //     },
        //     error: function(jqXhr, textStatus, errorMessage) {
        //         select2_build($('#f_itemtypeid'), '-- Tipe Bekal --', '', v_typeid, null, _attr);
        //         // select_build($('#edit-korwil'), _options, _attr);
        //     }
        // });

    });

</script>

