<!-- kontrak_footer.tpl -->

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

    function onselect_kontrak(dt, api, table_name) {
        let cnt = dt.rows('.selected').data().length;

        if (cnt == 0) {
            $("#tdata_148_wrapper .dt-action-buttons .dt-buttons").hide();

        }
        else if (cnt == 1){
            $("#tdata_148_wrapper .dt-action-buttons .dt-buttons").show();
            let status = dt.rows('.selected').data().pluck('status')[0];
            if (status == 'DRAFT') {
                $("#tdata_148_wrapper .dt-action-buttons .buttons-create").show();
                $("#tdata_148_wrapper .dt-action-buttons .buttons-edit").show();
                $("#tdata_148_wrapper .dt-action-buttons .buttons-remove").show();
                $("#tdata_148_wrapper .dt-action-buttons .btn-import").show();

            }
            else {
                $("#tdata_148_wrapper .dt-action-buttons .buttons-create").hide();
                $("#tdata_148_wrapper .dt-action-buttons .buttons-edit").hide();
                $("#tdata_148_wrapper .dt-action-buttons .buttons-remove").hide();
                $("#tdata_148_wrapper .dt-action-buttons .btn-import").hide();

            }
        }
        else {
            $("#tdata_148_wrapper .dt-action-buttons .dt-buttons").hide();

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
        if (data['status'] == 'COMP') {
            return 1;
        }

        return 0;
    }

    function conditional_perintahterima(data, row, meta) {
        if (data['status'] == 'APPR') {
            return 1;
        }

        return 0;
    }

    function onclick_close(rowIdx, dt, id) {
        let data = dt.row(rowIdx).data();
        let label = data['contractnum'];
        let url = "{$site_url}disbekal/kontrak/close";

        {literal}
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status === null || response.status == 0 || (response.error !== undefined && response.error !== null && response.error !== '')) {
                    //error
                    let msg = "Tidak spesifik";
                    if (response.error !== undefined && response.error !== null && response.error !== '') {
                        msg = response.error;
                    }
                    toastr.error("Tidak berhasil mengarsipkan Kontrak " +label+ ". Error: " +msg, 'Error');
                    return;
                }

                //successful -> reload
                let row = dt.row(rowIdx);
                let data = row.data();
                data['status'] = 'CLOSED';
                row.data(data);
                toastr.success("Berhasil menutup dan mengarsipkan Kontrak " +label+ ".");
            },
            error: function(jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        });
        {/literal}

    }

    function onclick_approve(rowIdx, dt, id) {
        let data = dt.row(rowIdx).data();
        let label = data['contractnum'];
        let url = "{$site_url}disbekal/kontrak/approve";

        {literal}
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status === null || response.status == 0 || (response.error !== undefined && response.error !== null && response.error !== '')) {
                    //error
                    let msg = "Tidak spesifik";
                    if (response.error !== undefined && response.error !== null && response.error !== '') {
                        msg = response.error;
                    }
                    toastr.error("Tidak berhasil menyetujui Kontrak " +label+ ". Error: " +msg, 'Error');
                    return;
                }

                //successful - reload
                //dt.ajak.reload();
                let row = dt.row(rowIdx);
                let data = row.data();
                data['status'] = 'APPR';
                row.data(data);
                toastr.success("Berhasil menyetujui Kontrak " +label+ ".");
            },
            error: function(jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        });
        {/literal}

    }

    function onclick_perintahterima(rowIdx, dt, id) {
        let data = dt.row(rowIdx).data();
        let label = data['contractnum'];
        let url = "{$site_url}disbekal/kontrak/buatperintahterima";

        {literal}
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: id},
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status === null || response.status == 0 || (response.error !== undefined && response.error !== null && response.error !== '')) {
                    //error
                    let msg = "Tidak spesifik";
                    if (response.error !== undefined && response.error !== null && response.error !== '') {
                        msg = response.error;
                    }
                    toastr.error("Tidak berhasil membuat DRAFT Perintah Terima untuk Kontrak " +label+ ". Error: " +msg, 'Error');
                    return;
                }

                //successful - reload
                //dt.ajak.reload();
                let row = dt.row(rowIdx);
                let data = row.data();
                data['status'] = 'APPR';
                row.data(data);
                toastr.success("Berhasil membuat DRAFT Perintah Terima untuk Kontrak " +label+ ".");
            },
            error: function(jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        });
        {/literal}

    }

    function onclick_buatdaritender(e, dt, node, conf) {
        let tenderid = 0;
        let tahun = 0;
        let label = '';     //label tenderid
        let url = "{$site_url}disbekal/tender/buatkontrak";

        {literal}
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: tenderid},
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status === null || response.status == 0 || (response.error !== undefined && response.error !== null && response.error !== '')) {
                    //error
                    let msg = "Tidak spesifik";
                    if (response.error !== undefined && response.error !== null && response.error !== '') {
                        msg = response.error;
                    }
                    toastr.error("Tidak berhasil membuat DRAFT Kontrak untuk Tender " +label+ ". Error: " +msg, 'Error');
                    return;
                }

                //successful - reload
                dt.ajak.reload();
                toastr.success("Berhasil membuat DRAFT Kontrak dari Tender " +label+ ".");
            },
            error: function(jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        });
        {/literal}

    }

    function onclick_buatdaripengadaan(e, dt, node, conf) {
        let poid = 0;
        let tahun = 0;
        let label = '';     //label tenderid
        let url = "{$site_url}disbekal/pengadaan/buatkontrak";

        {literal}
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: tenderid},
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.status === null || response.status == 0 || (response.error !== undefined && response.error !== null && response.error !== '')) {
                    //error
                    let msg = "Tidak spesifik";
                    if (response.error !== undefined && response.error !== null && response.error !== '') {
                        msg = response.error;
                    }
                    toastr.error("Tidak berhasil membuat DRAFT Kontrak untuk Pengadaan " +label+ ". Error: " +msg, 'Error');
                    return;
                }

                //successful - reload
                dt.ajak.reload();
                toastr.success("Berhasil membuat DRAFT Kontrak dari Pengadaan " +label+ ".");
            },
            error: function(jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        });
        {/literal}

    }
</script>

{if $crud.filter || $crud.search}
{include file='crud/_js-crud-filter.tpl'}
{/if}

<script type="text/javascript">
    //override default filter value. must be after include js-crud-filter.tpl
    v_itemtypeid = '{if !empty($userdata["itemtypeid"])}{$userdata["itemtypeid"]}{/if}';
    v_siteid = '{if !empty($userdata["siteid"])}{$userdata["siteid"]}{/if}';
    v_year = new Date().getFullYear();
</script>

{include file="crud/_js-crud-table.tpl" tbl=$crud}

{include file="crud/_js-crud-subtables.tpl" tbl=$crud}

{include file='crud/_js.tpl'}
