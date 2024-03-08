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

    return;
    
    if (cnt == 0) {
        $("#tdata_89_wrapper .dt-action-buttons .dt-buttons").hide();

        $(".tabbable a[href$='pane_158']").removeClass('active');
        $(".tab-content #pane_158").removeClass('active');
        $(".tabbable .nav-item:has(a[href$='pane_158'])").hide();
        $(".tab-content #pane_158").hide();
        // $(".tabbable a[href$='pane_89']").removeClass('active');
        $(".tabbable a[href$='pane_89']").addClass('active');
        // $(".tab-content #pane_89").removeClass('active');
        $(".tab-content #pane_89").addClass('active');
    }
    else if (cnt == 1){
        $("#tdata_89_wrapper .dt-action-buttons .dt-buttons").show();
        let status = dt.rows('.selected').data().pluck('status')[0];
        if (status == 'DRAFT') {
            $("#tdata_89_wrapper .dt-action-buttons .buttons-create").show();
            $("#tdata_89_wrapper .dt-action-buttons .buttons-edit").show();
            $("#tdata_89_wrapper .dt-action-buttons .buttons-remove").show();
            $("#tdata_89_wrapper .dt-action-buttons .btn-import").show();

            $(".tabbable a[href$='pane_158']").removeClass('active');
            $(".tab-content #pane_158").removeClass('active');
            $(".tabbable .nav-item:has(a[href$='pane_158'])").hide();
            $(".tab-content #pane_158").hide();
            // $(".tabbable a[href$='pane_89']").removeClass('active');
            $(".tabbable a[href$='pane_89']").addClass('active');
            // $(".tab-content #pane_89").removeClass('active');
            $(".tab-content #pane_89").addClass('active');
        }
        else {
            $("#tdata_89_wrapper .dt-action-buttons .buttons-create").hide();
            $("#tdata_89_wrapper .dt-action-buttons .buttons-edit").hide();
            $("#tdata_89_wrapper .dt-action-buttons .buttons-remove").hide();
            $("#tdata_89_wrapper .dt-action-buttons .btn-import").hide();

            $(".tabbable .nav-item:has(a[href$='pane_158'])").show();
            $(".tab-content #pane_158").show();
        }
    }
    else {
        $("#tdata_89_wrapper .dt-action-buttons .dt-buttons").hide();

        $(".tabbable a[href$='pane_158']").removeClass('active');
        $(".tab-content #pane_158").removeClass('active');
        $(".tabbable .nav-item:has(a[href$='pane_158'])").hide();
        $(".tab-content #pane_158").hide();
        // $(".tabbable a[href$='pane_89']").removeClass('active');
        $(".tabbable a[href$='pane_89']").addClass('active');
        // $(".tab-content #pane_89").removeClass('active');
        $(".tab-content #pane_89").addClass('active');
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

function conditional_close(data, row, meta) {
    if (data['status'] == 'APPR') {
        return 1;
    }

    return 0;
}

</script>