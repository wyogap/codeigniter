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

    function onselect_perintahterima(dt, api, table_name) {
        let cnt = dt.rows('.selected').data().length;

        if (cnt == 0) {
            $("#tdata_97_wrapper .dt-action-buttons .dt-buttons").hide();

        }
        else if (cnt == 1){
            $("#tdata_97_wrapper .dt-action-buttons .dt-buttons").show();
            let status = dt.rows('.selected').data().pluck('status')[0];
            if (status == 'DRAFT') {
                $("#tdata_97_wrapper .dt-action-buttons .buttons-create").show();
                $("#tdata_97_wrapper .dt-action-buttons .buttons-edit").show();
                $("#tdata_97_wrapper .dt-action-buttons .buttons-remove").show();
                $("#tdata_97_wrapper .dt-action-buttons .btn-import").show();

            }
            else {
                $("#tdata_97_wrapper .dt-action-buttons .buttons-create").hide();
                $("#tdata_97_wrapper .dt-action-buttons .buttons-edit").hide();
                $("#tdata_97_wrapper .dt-action-buttons .buttons-remove").hide();
                $("#tdata_97_wrapper .dt-action-buttons .btn-import").hide();

            }
        }
        else {
            $("#tdata_97_wrapper .dt-action-buttons .dt-buttons").hide();

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
        if (data['status'] == 'RETURN') {
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

<script type="text/javascript">

    var editor_dofrompo;

    function onclick_perintahterima(rowIdx, dt, id) {
        let row = dt.row("#"+id);
        let data = row.data();
        let label = data['ponum'];

        //clear selection
        //dt.rows().deselect();
        row.select();

        //easy access
        v_dt = dt; v_dtIdx = rowIdx; v_id = id

        if (v_siteid != data['siteid']) {
            let siteid = data['siteid'];
            if (siteid == null || siteid == "") {
                editor_dofrompo.field('storeid').ajax("{$site_url}{$controller}/gudang/lookup");
            }
            else {
                editor_dofrompo.field('storeid').ajax("{$site_url}{$controller}/gudang/lookup?f_siteid=" +siteid);
            }
            editor_dofrompo.field('storeid').reload();
            v_siteid = siteid;
        }

        editor_dofrompo
        .buttons({
            label: 'Simpan',
            className: "btn-primary",
            fn: function () {
                this.submit();
            }
        })
        .edit(id)
        .title('Buat Perintah Terima');

        return;
    }

    function onclick_approveperintahterima(rowIdx, dt, id, doid, donum) {
        let row = dt.row("#"+id);
        let data = row.data();
        let label = donum;

        //clear selection
        //dt.rows().deselect();
        row.select();

        $.confirm({
                title: 'Konfirmasi',
                content: 'Setujui Perintah Terima ' +label+ '?',
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
                                url: "{$site_url}disbekal/wfpengadaan/approvedo",
                                type: 'POST',
                                data: { id: id, doid: doid },
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
                                        toastr.error("Tidak berhasil menyetujui Perintah Terima " +label)
                                        toastr.error(msg)
                                        return;
                                    }

                                    //successful - reload
                                    if (response.data !== undefined && response.data !== null) {
                                        row.data(response.data);
                                    }
                                    
                                    toastr.success("Berhasil menyetujui Perintah Terima " +label+ ".");

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

    $(document).ready(function() {

        editor_dofrompo = new $.fn.dataTable.Editor({
            ajax: "{$site_url}disbekal/wfpengadaan/createdo",
            //idSrc: "poid",
            fields: [
            {
                name: "contractid",
                type: "hidden"
            },
            {
                label: "No Perintah Terima <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "donum",
                type: 'tcg_text',
            }, {
                label: "Tanggal Perintah <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "dodate",
                type: 'tcg_date',
            }, {
                label: "Gudang <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "storeid",
                type: 'tcg_select2',
                ajax: "{$site_url}{$controller}/gudang/lookup",
            }, {
                label: "Tanggal Pengiriman <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "targetdeliverydate",
                type: 'tcg_date',
            }, ],
            formOptions: {
                main: {
                    submit: 'all'
                }
            },
            i18n: {
                create: {
                    button: "Baru",
                    title: "Data Perintah Terima",
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

        editor_dofrompo.on('preSubmit', function(e, o, action) {
            if (action === 'create' || action === 'edit') {
                let field = null;
                let hasError = false;

                field = this.field('donum');
                if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == 0) {
                        hasError = true;
                        field.error('Harus diisi');
                    }
                }
                field = this.field('dodate');
                if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == 0) {
                        hasError = true;
                        field.error('Harus diisi');
                    }
                }
                field = this.field('storeid');
                if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == 0) {
                        hasError = true;
                        field.error('Harus diisi');
                    }
                }
                field = this.field('targetdeliverydate');
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

        editor_dofrompo.on('postSubmit', function(e, json, data, action, xhr) {

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
            
            toastr.success("Berhasil membuat Perintah Terima untuk Pengadaan " +label+ ".");

            onselect_pengadaan(v_dt, null, null);

        });

        editor_dofrompo.on( 'open' , function ( e, type ) {
            let row = v_dt.row("#" +v_id);
            let value = row.data();

            let contractid = value['contractid'];
            editor_dofrompo.field('contractid').set(contractid);

            editor_dofrompo.field('dodate').set(moment.utc().local().format('YYYY-MM-DD'));
        });
    });


</script>
{include file='crud/_js.tpl'}
