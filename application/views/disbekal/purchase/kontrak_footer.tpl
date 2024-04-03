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
        //TODO
        return 0;

        if (data['status'] == 'DRAFT') {
            return 1;
        }

        return 0;
    }

    function conditional_close(data, row, meta) {
        //TODO
        return 0;

        if (data['status'] == 'COMP') {
            return 1;
        }

        return 0;
    }

    function conditional_perintahterima(data, row, meta) {
        //TODO
        return 0;

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
                    toastr.error("Tidak berhasil mengarsipkan Kontrak " +label)
                    toastr.error(msg)
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
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(textStatus);
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
                    toastr.error("Tidak berhasil menyetujui Kontrak " +label)
                    toastr.error(msg)
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
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(textStatus);
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
                    toastr.error("Tidak berhasil membuat DRAFT Perintah Terima untuk Kontrak " +label)
                    toastr.error(msg)
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
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(textStatus);
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
                    toastr.error("Tidak berhasil membuat DRAFT Kontrak untuk Tender " +label)
                    toastr.error(msg)
                    return;
                }

                //successful - reload
                dt.ajak.reload();
                toastr.success("Berhasil membuat DRAFT Kontrak dari Tender " +label+ ".");
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
                    toastr.error("Tidak berhasil membuat DRAFT Kontrak untuk Pengadaan " +label)
                    toastr.error(msg)
                    return;
                }

                //successful - reload
                dt.ajak.reload();
                toastr.success("Berhasil membuat DRAFT Kontrak dari Pengadaan " +label+ ".");
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

    if (v_itemtypeid!='' && v_itemtypeid!=0) {
        $("#f_itemtypeid").attr("disabled", true);
    }
</script>

{include file="crud/_js-crud-table.tpl" tbl=$crud}

{include file="crud/_js-crud-subtables.tpl" tbl=$crud}

<script>
    var editor_contractfrompo;
    var editor_contractfromtender;

    function onclick_contract(rowIdx, dt, id) {
        let row = dt.row("#"+id);
        let data = row.data();
        let label = data['ponum'];

        //clear selection
        //dt.rows().deselect();
        row.select();

        //easy access
        v_dt = dt; v_dtIdx = rowIdx; v_id = id;

        editor_contractfrompo
        .buttons({
            label: 'Simpan',
            className: "btn-primary",
            fn: function () {
                this.submit();
            }
        })
        .edit(id)
        .title('Masukan Data Kontrak');

        return;
    }

    function onclick_approvecontract(rowIdx, dt, id) {
        let row = dt.row("#"+id);
        let data = row.data();
        let label = data['contractid_label'];
        let contractid = data['contractid'];

        //clear selection
        //dt.rows().deselect();
        row.select();

        $.confirm({
                title: 'Konfirmasi',
                content: 'Setujui Kontrak ' +label+ '?',
                closeIcon: true,
                columnClass: 'medium',
                buttons: {
                    cancel: {
                        text: 'Batal',
                        keys: ['enter', 'shift'],
                        action: function(){
                            //do nothing
                        }
                    },
                    confirm: {
                        text: 'OK',
                        btnClass: 'btn-info',
                        action: function(){
                            $.ajax({
                                url: "{$site_url}disbekal/wfpengadaan/approvecontract",
                                type: 'POST',
                                data: { id: id, contractid: contractid },
                                dataType: 'json',
                                // IMPORTANT: DO NOT set content-type to JSON. It is not supported well by codeigniter
                                // beforeSend: function(request) {
                                //     request.setRequestHeader("Content-Type", "application/json");
                                // },
                                success: function(response) {
                                    if (response.status === null || response.status == 0 || (response.error !== undefined && response.error !== null && response.error !== '')) {
                                        //error
                                        let msg = "Tidak spesifik";
                                        if (response.error !== undefined && response.error !== null && response.error !== '') {
                                            msg = response.error;
                                        }
                                        toastr.error("Tidak berhasil menyetujui Kontrak " +label)
                                        toastr.error(msg)
                                        return;
                                    }

                                    //successful - reload
                                    if (response.data !== undefined && response.data !== null) {
                                        row.data(response.data);
                                    }
                                    
                                    toastr.success("Berhasil menyetujui Kontrak " +label+ ".");

                                    onselect_pengadaan(dt, null, null);
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
                                }
                            });
                        }
                    },
                }
            });           

    }

    // function onclick_contractfromtender(rowIdx, dt, id) {
    //     let row = dt.row("#"+id);
    //     let data = row.data();
    //     let label = data['ponum'];

    //     //clear selection
    //     //dt.rows().deselect();
    //     row.select();

    //     //easy access
    //     v_dt = dt; v_dtIdx = rowIdx; v_id = id; v_tenderid = data['tenderid'];

    //     editor_contractfromtender.field('poid').val(id);
    //     editor_contractfromtender
    //     .buttons({
    //         label: 'Simpan',
    //         className: "btn-primary",
    //         fn: function () {
    //             this.submit();
    //         }
    //     })
    //     .edit()
    //     .title('Masukan Data Kontrak');

    //     return;
    // }

    $(document).ready(function() {
        editor_contractfrompo = new $.fn.dataTable.Editor({
            ajax: "{$site_url}disbekal/wfpengadaan/createcontract",
            //idSrc: "poid",
            fields: [
            {
                name: "tenderid",
                type: "hidden"
            },
            {
                label: "No Kontrak <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "contractnum",
                type: 'tcg_text',
            }, {
                label: "Tanggal Kontrak <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "contractdate",
                type: 'tcg_date',
            }, {
                label: "Mitra Yang Ditunjuk <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "vendorid",
                type: 'tcg_select2',
                ajax: "{$site_url}{$controller}/mitra/lookup",
                editorId: "contract"
            }, {
                label: "Nilai Kontrak <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "quotationvalue",
                type: 'tcg_mask',
                mask: "#.##0",
            }, ],
            formOptions: {
                main: {
                    submit: 'all'
                }
            },
            i18n: {
                create: {
                    button: "Baru",
                    title: "Data Kontrak",
                    submit: "Simpan"
                },
                error: {
                    system: "System error. Hubungi system administrator."
                },
                datetime: {
                    previous: "Sebelum",
                    next: "Setelah",
                    months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "Desember"],
                    weekdays: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                    hour: "Jam",
                    minute: "Menit"
                }
            }
        });

        editor_contractfrompo.on('preSubmit', function(e, o, action) {
            if (action === 'create' || action === 'edit') {
                let field = null;
                let hasError = false;

                field = this.field('contractnum');
                if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == 0) {
                        hasError = true;
                        field.error('Harus diisi');
                    }
                }
                field = this.field('contractdate');
                if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == 0) {
                        hasError = true;
                        field.error('Harus diisi');
                    }
                }
                field = this.field('vendorid');
                if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == 0) {
                        hasError = true;
                        field.error('Harus diisi');
                    }
                }
                field = this.field('quotationvalue');
                if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == 0) {
                        hasError = true;
                        field.error('Harus diisi');
                    }
                }
                /* If any error was reported, cancel the submission so it can be corrected */
                if (this.inError()) {
                    this.error('Data wajib belum diisi atau tidak berhasil divalidasi');
                    return false;
                }
            }

        });

        editor_contractfrompo.on('postSubmit', function(e, json, data, action, xhr) {

            let row = v_dt.row("#" +v_id);
            let value = row.data();
            let label = value['ponum'];

            if (json === null || json.status === null || json.status == 0 || (json.error !== undefined && json.error !== null && json.error !== '')) {
                //error
                let msg = "Tidak spesifik";
                if (json === null) {
                    msg = "Internal system error";
                }
                else if (json.error !== undefined && json.error !== null && json.error !== '') {
                    msg = json.error;
                }
                toastr.error("Tidak berhasil membuat Kontrak untuk Pengadaan " +label)
                toastr.error(msg)
                return;
            }

            //successful - reload
            if (json.data !== undefined && json.data !== null) {
                row.data(json.data[0]);
            }
            
            toastr.success("Berhasil membuat Kontrak untuk Pengadaan " +label+ ".");

            onselect_pengadaan(v_dt, null, null);

        });

        editor_contractfrompo.on( 'open' , function ( e, type ) {
            let row = v_dt.row("#" +v_id);
            let value = row.data();

            let vendorid = value['tenderid_vendorid'];
            if (vendorid == null || vendorid == '' || vendorid == 0) {
                editor_contractfrompo.field('vendorid').enable();
            }
            else {
                editor_contractfrompo.field('vendorid').set(vendorid);
                editor_contractfrompo.field('vendorid').disable();
            }
            let quotationvalue = parseFloat(value['quotationvalue']);
            if (isNaN(quotationvalue) || quotationvalue === null || quotationvalue == '' || quotationvalue == 0) {
                editor_contractfrompo.field('quotationvalue').enable();
            }
            else {
                editor_contractfrompo.field('quotationvalue').set(quotationvalue);
                editor_contractfrompo.field('quotationvalue').disable();
            }
            let tenderid = value['tenderid'];
            if (vendorid == null || vendorid == '' || vendorid == 0 || isNaN(quotationvalue)) {
                //force create from po
                tenderid = 0;
            }
            editor_contractfrompo.field('tenderid').set(tenderid);
            editor_contractfrompo.field('contractdate').set(moment.utc().local().format('YYYY-MM-DD'));
        });
    });
    
</script>

{include file='crud/_js.tpl'}
