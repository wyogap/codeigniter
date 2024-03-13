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

    let x_tahun = $("#form-tahun");
    let par3 = $("#tdata_164_wrapper .row .dt-action-buttons");
    par3.html("").append(x_tahun);

    $("#f_tahun").on('change', function() {
        let f_tahun= $("#f_tahun").val();

        {foreach $subtables as $subtbl}
        {if $subtbl.crud.table_id!='tdata_164'}{continue}{/if}
        //master value
        dt_{$subtbl.crud.table_id}.ajax.url("{$subtbl.crud.ajax}/" +selected_key_{$subtbl.crud.table_id} +"?f_tahun="+f_tahun);
        dt_{$subtbl.crud.table_id}.ajax.reload();
        {/foreach}
    });

    let _attr = {
            multiple: false,
            minimumResultsForSearch: 25,
        };

    $.ajax({
        url: "{$site_url}{$controller}/satuankerja/lookup",
        type: 'GET',
        dataType: 'json',
        beforeSend: function(request) {
            request.setRequestHeader("Content-Type", "application/json");
        },
        success: function(response) {
            if (response.data === null) {} else if (typeof response.error !==
                'undefined' && response.error !== null && response
                .error != "") {} else {
                _options = response.data;
            }
            select2_build($('#f_siteid'), '-- Satuan Kerja --', '', '', _options, _attr);

            // select_build($('#edit-korwil'), _options, _attr);
            // $('#edit-korwil').val(korwil);
        },
        error: function(jqXhr, textStatus, errorMessage) {
            select2_build($('#f_siteid'), '-- Satuan Kerja --', '', '', null, _attr);
            // select_build($('#edit-korwil'), _options, _attr);
        }
    });

    $.ajax({
        url: "{$site_url}disbekal/select/tipebekal",
        type: 'GET',
        dataType: 'json',
        beforeSend: function(request) {
            request.setRequestHeader("Content-Type", "application/json");
        },
        success: function(response) {
            if (response !== null && response.length > 0) {
                select2_build($('#f_itemtypeid'), '-- Tipe Bekal --', '', '', response, _attr);
            }

            // select_build($('#edit-korwil'), _options, _attr);
            // $('#edit-korwil').val(korwil);
        },
        error: function(jqXhr, textStatus, errorMessage) {
            select2_build($('#f_itemtypeid'), '-- Tipe Bekal --', '', '', null, _attr);
            // select_build($('#edit-korwil'), _options, _attr);
        }
    });

});

</script>