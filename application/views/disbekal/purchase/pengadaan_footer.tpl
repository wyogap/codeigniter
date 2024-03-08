<script type="text/javascript">

// function onchange_demandtype(field, oldvalue, newvalue, editor) {
//     if (newvalue == "0") {
//         editor.field('frequency').hide();
//         editor.field('frequencyunit').hide();
//         editor.field('enddate').hide();
//     } else {
//         editor.field('frequency').show();
//         editor.field('frequencyunit').show();
//         editor.field('enddate').show();
//     }
// }

function onselect_pengadaan(dt, api, table_name) {
    let cnt = dt.rows('.selected').data().length;

    if (cnt == 0) {
        $("#tdata_93_wrapper .dt-action-buttons .dt-buttons").hide();

    }
    else if (cnt == 1){
        $("#tdata_93_wrapper .dt-action-buttons .dt-buttons").show();
        let status = dt.rows('.selected').data().pluck('status')[0];
        if (status == 'DRAFT') {
            $("#tdata_93_wrapper .dt-action-buttons .buttons-create").show();
            $("#tdata_93_wrapper .dt-action-buttons .buttons-edit").show();
            $("#tdata_93_wrapper .dt-action-buttons .buttons-remove").show();
            $("#tdata_93_wrapper .dt-action-buttons .btn-import").show();

        }
        else {
            $("#tdata_93_wrapper .dt-action-buttons .buttons-create").hide();
            $("#tdata_93_wrapper .dt-action-buttons .buttons-edit").hide();
            $("#tdata_93_wrapper .dt-action-buttons .buttons-remove").hide();
            $("#tdata_93_wrapper .dt-action-buttons .btn-import").hide();

        }
    }
    else {
        $("#tdata_93_wrapper .dt-action-buttons .dt-buttons").hide();

    }
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

function conditional_tender(data, row, meta) {
    if (data['status'] == 'APPR') {
        return 1;
    }

    return 0;
}

function conditional_contract(data, row, meta) {
    if (data['status'] == 'TENDER') {
        return 1;
    }

    return 0;
}

function conditional_delivery(data, row, meta) {
    if (data['status'] == 'CONTRACT') {
        return 1;
    }

    return 0;
}

function conditional_evaluation(data, row, meta) {
    if (data['status'] == 'DELIVR') {
        return 1;
    }

    return 0;
}

function conditional_close(data, row, meta) {
    if (data['status'] == 'EVAL') {
        return 1;
    }

    return 0;
}
</script>