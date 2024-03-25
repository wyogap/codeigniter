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

function onselect_tender(dt, api, table_name) {
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
    if (data['status'] == 'APPR') {
        return 1;
    }

    return 0;
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

<script type="text/javascript">

    var editor_tenderfrompo;

    function onclick_tender(rowIdx, dt, id) {
        let row = dt.row("#"+id);
        let data = row.data();
        let label = data['ponum'];

        //clear selection
        //dt.rows().deselect();
        row.select();

        //easy access
        v_dt = dt; v_dtIdx = rowIdx; v_id = id;

        editor_tenderfrompo
        .buttons({
            label: 'Simpan',
            className: "btn-primary",
            fn: function () {
                this.submit();
            }
        })
        .edit(id)
        .title('Masukan Data Tender LPSE');

        return;
    }

    function onclick_completetender(rowIdx, dt, id) {
        let row = dt.row("#"+id);
        let data = row.data();
        let label = data['ponum'];

        //clear selection
        //dt.rows().deselect();
        row.select();

        //easy access
        v_dt = dt; v_dtIdx = rowIdx; v_id = id; v_tenderid = data['tenderid'];

        editor_completetender
        .buttons({
            label: 'Simpan',
            className: "btn-primary",
            fn: function () {
                this.submit();
            }
        })
        .edit(v_id)
        .title('Data Pemenang Tender');

        return;
    }

    $(document).ready(function() {
        editor_tenderfrompo = new $.fn.dataTable.Editor({
            ajax: "{$site_url}disbekal/wfpengadaan/createtender",
            //idSrc: "poid",
            fields: [
            {
                name: "poid",
                type: "hidden"
            },
            {
                label: "LPSE Tender Number <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "tendernum",
                type: 'tcg_text',
            }, {
                label: "Tanggal Mulai Tender <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "startdate",
                type: 'tcg_date',
            }, {
                label: "Tanggal Selesai Tender",
                compulsory: false,
                name: "enddate",
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
                    title: "Data Tender LPSE",
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

        editor_tenderfrompo.on('preSubmit', function(e, o, action) {
            if (action === 'create' || action === 'edit') {
                let field = null;
                let hasError = false;

                field = this.field('tendernum');
                if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == 0) {
                        hasError = true;
                        field.error('Harus diisi');
                    }
                }
                field = this.field('startdate');
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
        
        editor_tenderfrompo.on('postSubmit', function(e, json, data, action, xhr) {
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
            
            toastr.success("Berhasil memasukkan Tender untuk Pengadaan " +label+ ".");

            onselect_pengadaan(v_dt, null, null);

        });

        editor_tenderfrompo.on( 'open' , function ( e, type ) {
            editor_tenderfrompo.field('startdate').set(moment.utc().local().format('YYYY-MM-DD'));
        });

        editor_completetender = new $.fn.dataTable.Editor({
            ajax: "{$site_url}disbekal/wfpengadaan/completetender",
            //idSrc: "poid",
            fields: [
            {
                name: "tenderid",
                type: "hidden"
            }, {
                label: "Mitra Yang Ditunjuk <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                //TODO: if 2 select2 field has the same value, one of them will fail to init.
                name: "vendorid",
                type: 'tcg_select2',
                ajax: "{$site_url}{$controller}/mitra/lookup",
                editorId: "tender",
            }, {
                label: "Nilai Penawaran <span class='text-danger font-weight-bold'>*</span>",
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
                    title: "Pemenang Tender LPSE",
                    submit: "Simpan"
                },
                error: {
                    system: "System error. Hubungi system administrator."
                },
            }
        });

        editor_completetender.on('preSubmit', function(e, o, action) {
            if (action === 'create' || action === 'edit') {
                let field = null;
                let hasError = false;

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

        editor_completetender.on('postSubmit', function(e, json, data, action, xhr) {

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
            
            toastr.success("Berhasil memasukkan pemenang Tender untuk Pengadaan " +label+ ".");

            onselect_pengadaan(v_dt, null, null);


        });

        editor_completetender.on( 'open' , function ( e, type ) {
            editor_completetender.field('tenderid').set(v_tenderid);
        });

     });

</script>

{include file='crud/_js.tpl'}