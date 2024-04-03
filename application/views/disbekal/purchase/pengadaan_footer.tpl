{* This footer template is still included even in form-edit/form-detail mode. So we have to exclude part of it if necessary! *}

{* This display-formatting function is used in both form-mode and table-mode *}
<script type="text/javascript">
    function display_pengadaan_doid(value, tipe, data) {
        if(value === undefined || value === null || value == '' || value == 0)  return '';

        let json = JSON.parse("[" +data['doid_label']+ "]");
        if (json.length==0) {
            return "";
        }

        let str = "";
        for (i=0; i<json.length; i++) {
            if (str.length > 0)     str += ", ";
            str += json[i]["donum"] + " <a target='_blank' href='{$site_url}{$controller}/perintahterima/detail/" +json[i]["doid"]+ "'><i class='fa fas fa-external-link-alt'></i></a>";
        }

        return str;
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

</script>

{if empty($form_mode)}
{* The utility functions below are only used in table-mode *}

{literal}
<script id="popover-po" type="text/template">
    <td class="child"><ul class="dtr-details">
        <li><span class="dtr-title">{{ponum}}</span> <span class="dtr-data">{{postatus}}</span></li>
        <li><span class="dtr-title">Tanggal Persetujuan</span> <span class="dtr-data">{{podate}}</span></li>
        <li><span class="dtr-title">HPS</span> <span class="dtr-data">{{estprice}}</span></li>
        {{#postatus_draft}}
        <li><a class="btn btn-primary" onclick="event.stopPropagation(); onclick_approve(null, dt_tdata_150, {{poid}})">Setujui Perintah Pengadaan</a></li>
        {{/postatus_draft}}
        {{#postatus_appr}}
        <li><a class="btn btn-primary" onclick="event.stopPropagation(); onclick_tender(null, dt_tdata_150, {{poid}})">Data Tender LPSE</a></li>
        <li><a class="btn btn-primary" onclick="event.stopPropagation(); onclick_contract(null, dt_tdata_150, {{poid}})">Buat Kontrak</a></li>
        {{/postatus_appr}}
        {{#postatus_reset}}
        <li><a class="btn btn-danger" onclick="event.stopPropagation(); onclick_reset(null, dt_tdata_150, {{poid}}, 'DRAFT')">Reset Status</a></li>
        {{/postatus_reset}}
    </ul></td>
</script>

<script id="popover-tender" type="text/template">
    <td class="child"><ul class="dtr-details">
        <li><span class="dtr-title">{{tendernum}} <a target="_blank" href="{{url}}"><i class="fa fas fa-external-link-alt"></i></a></span> 
                <span class="dtr-data">{{tenderstatus}}</span></li>
        <li><span class="dtr-title">Tanggal Mulai</span> <span class="dtr-data">{{startdate}}</span></li>
        <li><span class="dtr-title">Tanggal Selesai</span> <span class="dtr-data">{{enddate}}</span></li>
        {{#postatus_tender_inprog}}
        <li><a class="btn btn-primary" onclick="event.stopPropagation(); onclick_completetender(null, dt_tdata_150, {{poid}})">Data Pemenang Tender</a></li>
        {{/postatus_tender_inprog}}
        {{#postatus_tender_comp}}
        <li><a class="btn btn-primary" onclick="event.stopPropagation(); onclick_contract(null, dt_tdata_150, {{poid}})">Buat Kontrak</a></li>
        {{/postatus_tender_comp}}
        {{#postatus_reset}}
        <li><a class="btn btn-danger" onclick="event.stopPropagation(); onclick_reset(null, dt_tdata_150, {{poid}}, 'TENDER')">Reset Status</a></li>'
        {{/postatus_reset}}
    </ul></td>
</script>

<script id="popover-contract" type="text/template">
    <td class="child"><ul class="dtr-details">
        <li><span class="dtr-title">{{contractnum}} <a target="_blank" href="{{url}}"><i class="fa fas fa-external-link-alt"></i></a></span> 
                <span class="dtr-data">{{contractstatus}}</span></li>
        <li><span class="dtr-title">Tanggal Kontrak</span> <span class="dtr-data">{{contractdate}}</span></li>
        <li><span class="dtr-title">Nilai Kontrak</span> <span class="dtr-data">{{contractvalue}}</span></li>
        {{#postatus_contract_draft}}
        <li><a class="btn btn-primary" onclick="event.stopPropagation(); onclick_approvecontract(null, dt_tdata_150, {{poid}})">Setujui Kontrak</a></li>
        {{/postatus_contract_draft}}
        {{#postatus_contract_appr}}
        <li><a class="btn btn-primary" onclick="event.stopPropagation(); onclick_perintahterima(null, dt_tdata_150, {{poid}})">Buat Perintah Terima</a></li>
        {{/postatus_contract_appr}}
        {{#postatus_reset}}
        <li><a class="btn btn-danger" onclick="event.stopPropagation(); onclick_reset(null, dt_tdata_150, {{poid}}, 'CONTRACT')">Reset Status</a></li>
        {{/postatus_reset}}  
    </ul></td>
</script>

<script id="popover-do" type="text/template">
    <td class="child"><ul class="dtr-details">
        {{#dos}}
        <li><span class="dtr-title">{{donum}} <a target="_blank" href="{{url}}"><i class="fa fas fa-external-link-alt"></i></a> 
                </span> <span class="dtr-data">{{status}}</span></li>
        <li><span class="dtr-title">Tanggal</span> <span class="dtr-data">{{dodate}}</span></li>
        <li><span class="dtr-title">Gudang</span> <span class="dtr-data">{{store}}</span></li>
        {{#dostatus_draft}}
        <li><a class="btn btn-primary" onclick="event.stopPropagation(); onclick_approveperintahterima(null, dt_tdata_150, {{poid}},{{doid}},'{{donum}}')">Setujui Perintah Terima</a></li>
        {{/dostatus_draft}}
        {{/dos}}
        {{#postatus_delivr}}
        <li><a class="btn btn-primary" onclick="event.stopPropagation(); onclick_perintahterima(null, dt_tdata_150, {{poid}})">Perintah Terima Baru</a></li>
        {{/postatus_delivr}}
        {{#postatus_reset}}
        <li><a class="btn btn-danger" onclick="event.stopPropagation(); onclick_reset(null, dt_tdata_150, {{poid}}, 'DELIVR')">Reset Status</a></li>
        {{/postatus_reset}}
    </ul></td>
</script>
{/literal}

<script type="text/javascript">

    var v_dt = null;
    var v_dtIdx = 0;
    var v_id = null;
    var v_tenderid = null;
    var v_contractid = null;
    var v_siteid = null;

    $(function () {
        $('[data-toggle="popover"]').popover( {
            sanitize: false, // <-- ADD HERE
            html: true 
        })

        let pos1 = $('.wf-state:has(#wf-pos1)');
        let pos2 = $('.wf-state:has(#wf-pos2)');
        let pos3 = $('.wf-state:has(#wf-pos3)');
        let pos4 = $('.wf-state:has(#wf-pos4)');
        let pos5 = $('.wf-state:has(#wf-pos5)');

        pos1.popover('disable');
        pos2.popover('disable');
        pos3.popover('disable');
        pos4.popover('disable');
        pos5.popover('disable');

        pos1.find("img").tooltip('disable');
        pos2.find("img").tooltip('disable');
        pos3.find("img").tooltip('disable');
        pos4.find("img").tooltip('disable');
        pos5.find("img").tooltip('disable');

    })

    function onselect_pengadaan(dt, api, table_name) {
        let data = dt.rows({
                selected: true
            }).data();

        let states = [];
        states.push($('.wf-state:has(#wf-pos1)')); 
        states.push($('.wf-state:has(#wf-pos2)')); 
        states.push($('.wf-state:has(#wf-pos3)')); 
        states.push($('.wf-state:has(#wf-pos4)')); 
        states.push($('.wf-state:has(#wf-pos5)')); 

        for(i=0; i<states.length; i++) {
            states[i].popover('hide');
            //add function to enable
            states[i].enable = function(html = null) {
                if (html != null) this.attr('data-content',html);
                this.popover('enable');
                this.addClass('wf-enable');
                this.find("img").tooltip('enable');
            };
            //add function to disable
            states[i].disable = function() {
                this.attr('data-content','');
                this.popover('disable');
                this.removeClass('wf-enable');
                this.find("img").tooltip('disable');
            };
        }

        if (data.length == 0 || data.length > 1) {
            update_wf_images(null);

            states.forEach(pos => {
                pos.disable();
            });

            // $("#detail").hide();

            $("#tdata_93_wrapper .dt-action-buttons .dt-buttons").hide();

            $(".tabbable a[href$='pane_165']").removeClass('active');
            $(".tab-content #pane_165").removeClass('active');
            $(".tabbable .nav-item:has(a[href$='pane_165'])").hide();
            $(".tab-content #pane_165").hide();
            
            $(".tabbable a[href$='pane_93']").addClass('active');
            $(".tab-content #pane_93").addClass('active');
            $(".tabbable .nav-item:has(a[href$='pane_93'])").show();
            $(".tab-content #pane_93").show();

            return;
        }

        // //show detail
        // $("#detail").show();

        let po = data[0];

        let status = po['status'];
        let tenderid = parseInt(po['tenderid']);
        update_wf_images(status, !(isNaN(tenderid) || tenderid==0));

        let podate = moment.utc(po['approveddate']).local();
        let html = render_template('#popover-po', {
                "poid"      : po['poid'],
                "ponum"     : po['ponum'],
                "postatus"  : po['status'],
                "podate"    : podate.isValid() ? podate.format('YYYY-MM-DD') : '',
                "estprice"  : accounting.formatMoney(po['estprice'], "{$currency_prefix}"
                                                        , {$currency_decimal_precision}
                                                        , "{$currency_thousand_separator}"
                                                        , "{$currency_decimal_separator}"),
                "postatus_draft"    : (po['status'] == 'DRAFT'),
                "postatus_appr"     : (po['status'] == 'APPR'),
                "postatus_reset"    : (po['status'] != 'DRAFT' && po['status'] != 'APPR' && po['status'] != 'CLOSED'),
            });
        states[0].enable(html);

        if (po['tenderid'] !== undefined && po['tenderid'] !== null && po['tenderid'] != "" && po['tenderid'] != 0) {
            let startdate = moment.utc(po['tenderid_startdate']).local();
            let enddate = moment.utc(po['tenderid_enddate']).local();
            html = render_template('#popover-tender', {
                    "poid"          : po['poid'],
                    "tendernum"     : po['tenderid_label'],
                    "tenderstatus"  : po['tenderid_status'],
                    "startdate"     : startdate.isValid() ? podate.format('YYYY-MM-DD') : '',
                    "startdate"     : enddate.isValid() ? podate.format('YYYY-MM-DD') : '',
                    "url"           : "{$site_url}{$controller}/tender/detail/" +po['tenderid'],
                    "postatus_tender_inprog"    : (po['status'] == 'TENDER' && po['tenderid_status'] == 'INPROG'),
                    "postatus_tender_comp"      : (po['status'] == 'TENDER' && po['tenderid_status'] == 'COMP'),
                    "postatus_reset"            : (po['status'] != 'TENDER' && po['status'] != 'DRAFT' && po['status'] != 'APPR' && po['status'] != 'CLOSED'),
                });
            states[1].enable(html);
        }
        else {
            states[1].disable();
        }

        if (po['contractid'] !== undefined && po['contractid'] !== null && po['contractid'] != "" && po['contractid'] != 0) {
            let contractdate = moment.utc(po['contractdate']).local();
            html = render_template('#popover-contract', {
                    "poid"      : po['poid'],
                    "contractnum"     : po['contractid_label'],
                    "contractstatus"  : po['contractid_status'],
                    "contractdate"    : contractdate.isValid() ? contractdate.format('YYYY-MM-DD') : '',
                    "contractvalue"   : accounting.formatMoney(po['contractvalue'], "{$currency_prefix}"
                                                            , {$currency_decimal_precision}
                                                            , "{$currency_thousand_separator}"
                                                            , "{$currency_decimal_separator}"),
                    "postatus_contract_draft"    : (po['status'] == 'CONTRACT' && po['contractid_status'] == 'DRAFT'),
                    "postatus_contract_appr"     : (po['status'] == 'CONTRACT' && po['contractid_status'] == 'APPR'),
                    "postatus_reset"   : (po['status'] == 'DELIVR' || po['status'] == 'EVAL' || po['status'] == 'COMP'),
                }); 
            states[2].enable(html);           
        }
        else {
            states[2].disable();           
        }

        if (po['doid'] !== undefined && po['doid'] !== null && po['doid'] != "" && po['doid'] != 0) {
            let dos = JSON.parse('[' +po['doid_label']+ ']');
            let dodate = null;

            html = '';
            if (dos!==undefined || dos!==null){
                for(i=0; i<dos.length; i++) {
                    dos[i]['poid'] = po['poid'];
                    dos[i]['url'] = "{$site_url}{$controller}/perintahterima/detail/" +dos[i]['doid'];
                    dos[i]['dostatus_draft'] = (dos[i]['status'] == 'DRAFT');
                    dodate = moment.utc(dos[i]['dodate']).local();
                    dos[i]['dodate'] = dodate.isValid() ? dodate.format('YYYY-MM-DD') : '';
                }
                html = render_template('#popover-do', {
                        "poid"  : po['poid'],
                        "dos"   : dos,
                        "postatus_delivr"   : (po['status'] == 'DELIVR'),
                        "postatus_reset"    : (po['status'] == 'EVAL' || po['status'] == 'COMP'),
                    });
            }
            states[3].enable(html);           
        }
        else {
            states[3].disable(); 
        }

        $("#tdata_93_wrapper .dt-action-buttons .dt-buttons").show();

        status = dt.rows('.selected').data().pluck('status')[0];
        if (status == 'DRAFT') {
            $("#tdata_93_wrapper .dt-action-buttons .buttons-create").show();
            $("#tdata_93_wrapper .dt-action-buttons .buttons-edit").show();
            $("#tdata_93_wrapper .dt-action-buttons .buttons-remove").show();
            $("#tdata_93_wrapper .dt-action-buttons .btn-import").show();

            $(".tabbable a[href$='pane_165']").removeClass('active');
            $(".tab-content #pane_165").removeClass('active');
            $(".tabbable .nav-item:has(a[href$='pane_165'])").hide();
            $(".tab-content #pane_165").hide();
            // $(".tabbable a[href$='pane_93']").removeClass('active');
            $(".tabbable a[href$='pane_93']").addClass('active');
            $(".tab-content #pane_93").addClass('active');
            $(".tabbable .nav-item:has(a[href$='pane_93'])").show();
            $(".tab-content #pane_93").show();
        }
        else {

            $(".tabbable a[href$='pane_165']").addClass('active');
            $(".tab-content #pane_165").addClass('active');
            $(".tabbable .nav-item:has(a[href$='pane_165'])").show();
            $(".tab-content #pane_165").show();
            // $(".tabbable a[href$='pane_93']").removeClass('active');
            $(".tabbable a[href$='pane_93']").removeClass('active');
            $(".tab-content #pane_93").removeClass('active');
            $(".tabbable .nav-item:has(a[href$='pane_93'])").hide();
            $(".tab-content #pane_93").hide();
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
        if (data['status'] == 'APPR' || data['status'] == 'TENDER') {
            return 1;
        }

        return 0;
    }

    function conditional_perintahterima(data, row, meta) {
        if ((data['status'] == 'CONTRACT' && data['contractid_status'] != 'DRAFT') || data['status'] == 'DELIVR') {
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
        if (data['status'] == 'EVAL' || data['status'] == 'COMP') {
            return 1;
        }

        return 0;
    }

    function onclick_close(rowIdx, dt, id) {
        let data = dt.row(rowIdx).data();
        let label = data['ponum'];
        let url = "{$site_url}disbekal/pengadaan/closepo";

        //clear selection
        dt.rows().deselect();
        dt.row(rowIdx).select();

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
                    toastr.error("Tidak berhasil mengarsipkan Pengadaan " +label)
                    toastr.error(msg)
                    return;
                }

                //successful -> reload
                let row = dt.row(rowIdx);
                let data = row.data();
                data['status'] = 'CLOSED';
                row.data(data);
                toastr.success("Berhasil menutup dan mengarsipkan Pengadaan " +label+ ".");
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
        let row = dt.row("#"+id);
        let data = row.data();
        let label = data['ponum'];

        //clear selection
        //dt.rows().deselect();
        row.select();

        $.confirm({
                title: 'Konfirmasi',
                content: 'Setujui Perintah Pengadaan ' +label+ '?',
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
                                url: "{$site_url}disbekal/wfpengadaan/approvepo",
                                type: 'POST',
                                data: { id: id },
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
                                        toastr.error("Tidak berhasil menyetujui Pengadaan " +label)
                                        toastr.error(msg)
                                        return;
                                    }

                                    //successful - reload
                                    if (response.data !== undefined && response.data !== null) {
                                        row.data(response.data);
                                    }
                                    
                                    toastr.success("Berhasil menyetujui Pengadaan " +label+ ".");

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

    function onclick_reset(rowIdx, dt, id, state) {
        let row = dt.row("#"+id);
        let data = row.data();
        let label = data['ponum'];

        //clear selection
        //dt.rows().deselect();
        row.select();

        $.confirm({
                title: 'Konfirmasi',
                content: 'Reset Pengadaan ' +label+ ' ke Status ' +state+ '? Semua data setelah status ini akan dihapus.',
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
                                url: "{$site_url}disbekal/wfpengadaan/resetpo",
                                type: 'POST',
                                data: { id: id, state: state },
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
                                        toastr.error("Tidak berhasil mereset Pengadaan " +label)
                                        toastr.error(msg)
                                        return;
                                    }

                                    //successful - reload
                                    if (response.data !== undefined && response.data !== null) {
                                        row.data(response.data);
                                    }
                                    
                                    toastr.success("Berhasil mereset Pengadaan " +label+ " ke status=" +state+ ".");

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

    function onclick_createpofromdemand(e, dt, node, conf) {

        let itemtypeid = $("#f_itemtypeid").val();
        let siteid = $("#f_siteid").val();
        let url = "{$site_url}{$controller}/kebutuhan/lookup?itemtypeid=" +(itemtypeid==null?'':itemtypeid)+ "&siteid=" +(siteid==null?'':siteid);
        editor_pofromdemand.field('demandid').ajax(url);
        editor_pofromdemand.field('demandid').reload();

        editor_pofromdemand
        .buttons({
            label: 'Simpan',
            className: "btn-primary",
            fn: function () {
                this.submit();
            }
        })
        .create()
        .title('Buat Perintah Pengadaan');

        return;
    }


    // function onclick_buatdarikebutuhan(e, dt, node, conf) {
    //     let demandid = 0;
    //     let tahun = 0;
    //     let label = '';     //label demandid
    //     let url = "{$site_url}disbekal/kebutuhan/buatpengadaan";

    //     {literal}
    //     $.ajax({
    //         url: url,
    //         type: 'POST',
    //         data: {id: demandid, year: tahun},
    //         dataType: 'json',
    //         beforeSend: function(request) {
    //             request.setRequestHeader("Content-Type", "application/json");
    //         },
    //         success: function(response) {
    //             if (response.status === null || response.status == 0 || (response.error !== undefined && response.error !== null && response.error !== '')) {
    //                 //error
    //                 let msg = "Tidak spesifik";
    //                 if (response.error !== undefined && response.error !== null && response.error !== '') {
    //                     msg = response.error;
    //                 }
    //                 toastr.error("Tidak berhasil membuat DRAFT Perintah Terima untuk Pengadaan " +label)
    //                 toastr.error(msg)
    //                 return;
    //             }

    //             //successful - reload
    //             dt.ajak.reload();
    //             toastr.success("Berhasil membuat DRAFT Pengadaan dari Rencana Kebutuhan " +label+ ".");
    //         },
    //         error: function(jqXhr, textStatus, errorMessage) {
    //             if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
    //                 (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
    //                     && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
    //                 ) {
    //                 //login ulang
    //                 window.location.href = "{$site_url}" +'auth';
    //             }
    //             //send toastr message
    //             toastr.error(textStatus);
    //         }
    //     });
    //     {/literal}

    // }

    function update_wf_images(status, istender=1) {

        if (status==null || status=='') {
            $('#wf-arrow1').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos1').attr("src","{$site_url}images/wf/pos1b.png");
            $('#wf-arrow2').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos2').attr("src","{$site_url}images/wf/pos2b.png");
            $('#wf-arrow3').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos3').attr("src","{$site_url}images/wf/pos3b.png");
            $('#wf-arrow4').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos4').attr("src","{$site_url}images/wf/pos4b.png");
            $('#wf-arrow5').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos5').attr("src","{$site_url}images/wf/pos5b.png");
            $('#wf-arrow-end').attr("src","{$site_url}images/wf/arrow0.png");
        }
        else if (status=='DRAFT') {
            $('#wf-arrow1').attr("src","{$site_url}images/wf/arrow1.png");
            $('#wf-pos1').attr("src","{$site_url}images/wf/pos1.png");
            $('#wf-arrow2').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos2').attr("src","{$site_url}images/wf/pos2b.png");
            $('#wf-arrow3').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos3').attr("src","{$site_url}images/wf/pos3b.png");
            $('#wf-arrow4').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos4').attr("src","{$site_url}images/wf/pos4b.png");
            $('#wf-arrow5').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos5').attr("src","{$site_url}images/wf/pos5b.png");
            $('#wf-arrow-end').attr("src","{$site_url}images/wf/arrow0.png");

            let val = $('.wf-state:has(#wf-pos1)');
            $('.wf-state:has(#wf-pos1)').popover('enable');
        } else if (status=='APPR') {
            $('#wf-arrow1').attr("src","{$site_url}images/wf/arrow1.png");
            $('#wf-pos1').attr("src","{$site_url}images/wf/pos1.png");
            $('#wf-arrow2').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos2').attr("src","{$site_url}images/wf/pos2b.png");
            $('#wf-arrow3').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos3').attr("src","{$site_url}images/wf/pos3b.png");
            $('#wf-arrow4').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos4').attr("src","{$site_url}images/wf/pos4b.png");
            $('#wf-arrow5').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos5').attr("src","{$site_url}images/wf/pos5b.png");
            $('#wf-arrow-end').attr("src","{$site_url}images/wf/arrow0.png");

            $('.wf-state:has(#wf-pos1)').popover('enable');
        } else if (status=='TENDER') {
            $('#wf-arrow1').attr("src","{$site_url}images/wf/arrow1.png");
            $('#wf-pos1').attr("src","{$site_url}images/wf/pos1.png");
            $('#wf-arrow2').attr("src","{$site_url}images/wf/arrow2.png");
            $('#wf-pos2').attr("src","{$site_url}images/wf/pos2.png");
            $('#wf-arrow3').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos3').attr("src","{$site_url}images/wf/pos3b.png");
            $('#wf-arrow4').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos4').attr("src","{$site_url}images/wf/pos4b.png");
            $('#wf-arrow5').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos5').attr("src","{$site_url}images/wf/pos5b.png");
            $('#wf-arrow-end').attr("src","{$site_url}images/wf/arrow0.png");

            $('.wf-state:has(#wf-pos1)').popover('enable');
            $('.wf-state:has(#wf-pos2)').popover('enable');
        } else if (status=='CONTRACT') {
            $('#wf-arrow1').attr("src","{$site_url}images/wf/arrow1.png");
            $('#wf-pos1').attr("src","{$site_url}images/wf/pos1.png");
            $('#wf-arrow2').attr("src","{$site_url}images/wf/arrow2.png");
            if (istender) {
                $('#wf-pos2').attr("src","{$site_url}images/wf/pos2.png");
            }
            else {
                $('#wf-pos2').attr("src","{$site_url}images/wf/pos2a.png");
            }
            $('#wf-arrow3').attr("src","{$site_url}images/wf/arrow3.png");
            $('#wf-pos3').attr("src","{$site_url}images/wf/pos3.png");
            $('#wf-arrow4').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos4').attr("src","{$site_url}images/wf/pos4b.png");
            $('#wf-arrow5').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos5').attr("src","{$site_url}images/wf/pos5b.png");
            $('#wf-arrow-end').attr("src","{$site_url}images/wf/arrow0.png");

            $('.wf-state:has(#wf-pos1)').popover('enable');
            $('.wf-state:has(#wf-pos2)').popover('enable');
            $('.wf-state:has(#wf-pos3)').popover('enable');
        } else if (status=='DELIVR') {
            $('#wf-arrow1').attr("src","{$site_url}images/wf/arrow1.png");
            $('#wf-pos1').attr("src","{$site_url}images/wf/pos1.png");
            $('#wf-arrow2').attr("src","{$site_url}images/wf/arrow2.png");
            if (istender) {
                $('#wf-pos2').attr("src","{$site_url}images/wf/pos2.png");
            }
            else {
                $('#wf-pos2').attr("src","{$site_url}images/wf/pos2a.png");
            }
            $('#wf-arrow3').attr("src","{$site_url}images/wf/arrow3.png");
            $('#wf-pos3').attr("src","{$site_url}images/wf/pos3.png");
            $('#wf-arrow4').attr("src","{$site_url}images/wf/arrow4.png");
            $('#wf-pos4').attr("src","{$site_url}images/wf/pos4.png");
            $('#wf-arrow5').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos5').attr("src","{$site_url}images/wf/pos5b.png");
            $('#wf-arrow-end').attr("src","{$site_url}images/wf/arrow0.png");

            $('.wf-state:has(#wf-pos1)').popover('enable');
            $('.wf-state:has(#wf-pos2)').popover('enable');
            $('.wf-state:has(#wf-pos3)').popover('enable');
            $('.wf-state:has(#wf-pos4)').popover('enable');
        } else if (status=='EVAL') {
            $('#wf-arrow1').attr("src","{$site_url}images/wf/arrow1.png");
            $('#wf-pos1').attr("src","{$site_url}images/wf/pos1.png");
            $('#wf-arrow2').attr("src","{$site_url}images/wf/arrow2.png");
            if (istender) {
                $('#wf-pos2').attr("src","{$site_url}images/wf/pos2.png");
            }
            else {
                $('#wf-pos2').attr("src","{$site_url}images/wf/pos2a.png");
            }
            $('#wf-arrow3').attr("src","{$site_url}images/wf/arrow3.png");
            $('#wf-pos3').attr("src","{$site_url}images/wf/pos3.png");
            $('#wf-arrow4').attr("src","{$site_url}images/wf/arrow4.png");
            $('#wf-pos4').attr("src","{$site_url}images/wf/pos4.png");
            $('#wf-arrow5').attr("src","{$site_url}images/wf/arrow5.png");
            $('#wf-pos5').attr("src","{$site_url}images/wf/pos5.png");
            $('#wf-arrow-end').attr("src","{$site_url}images/wf/arrow0.png");

            $('.wf-state:has(#wf-pos1)').popover('enable');
            $('.wf-state:has(#wf-pos2)').popover('enable');
            $('.wf-state:has(#wf-pos3)').popover('enable');
            $('.wf-state:has(#wf-pos4)').popover('enable');
            $('.wf-state:has(#wf-pos5)').popover('enable');
        } else if (status=='COMP' || status=='CLOSED') {
            $('#wf-arrow1').attr("src","{$site_url}images/wf/arrow1.png");
            $('#wf-pos1').attr("src","{$site_url}images/wf/pos1.png");
            $('#wf-arrow2').attr("src","{$site_url}images/wf/arrow2.png");
            if (istender) {
                $('#wf-pos2').attr("src","{$site_url}images/wf/pos2.png");
            }
            else {
                $('#wf-pos2').attr("src","{$site_url}images/wf/pos2a.png");
            }
            $('#wf-arrow3').attr("src","{$site_url}images/wf/arrow3.png");
            $('#wf-pos3').attr("src","{$site_url}images/wf/pos3.png");
            $('#wf-arrow4').attr("src","{$site_url}images/wf/arrow4.png");
            $('#wf-pos4').attr("src","{$site_url}images/wf/pos4.png");
            $('#wf-arrow5').attr("src","{$site_url}images/wf/arrow5.png");
            $('#wf-pos5').attr("src","{$site_url}images/wf/pos5.png");
            $('#wf-arrow-end').attr("src","{$site_url}images/wf/arrow6.png");

            $('.wf-state:has(#wf-pos1)').popover('enable');
            $('.wf-state:has(#wf-pos2)').popover('enable');
            $('.wf-state:has(#wf-pos3)').popover('enable');
            $('.wf-state:has(#wf-pos4)').popover('enable');
            $('.wf-state:has(#wf-pos5)').popover('enable');
        } else {
            $('#wf-arrow1').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos1').attr("src","{$site_url}images/wf/pos1b.png");
            $('#wf-arrow2').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos2').attr("src","{$site_url}images/wf/pos2b.png");
            $('#wf-arrow3').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos3').attr("src","{$site_url}images/wf/pos3b.png");
            $('#wf-arrow4').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos4').attr("src","{$site_url}images/wf/pos4b.png");
            $('#wf-arrow5').attr("src","{$site_url}images/wf/arrow0.png");
            $('#wf-pos5').attr("src","{$site_url}images/wf/pos5b.png");
            $('#wf-arrow-end').attr("src","{$site_url}images/wf/arrow0.png");
        }
    }

</script>

<script type="text/javascript">

    $(document).ready(function() {

        $(".tabbable a[href$='pane_165']").removeClass('active');
        $(".tab-content #pane_165").removeClass('active');
        $(".tabbable .nav-item:has(a[href$='pane_165'])").hide();
        $(".tab-content #pane_165").hide();
        // $(".tabbable a[href$='pane_93']").removeClass('active');
        $(".tabbable a[href$='pane_93']").addClass('active');
        $(".tab-content #pane_93").addClass('active');
        $(".tabbable .nav-item:has(a[href$='pane_93'])").show();
        $(".tab-content #pane_93").show();

    });

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
    $(document).ready(function() {
        editor_tdata_150.on( 'initEdit', function (e, node, data, items, type) {
            //disable field itemtypeid
            editor_tdata_150.field('itemtypeid').disable();
        });

        editor_tdata_150.on( 'initCreate', function (e, node, data, items, type) {
            //enable field itemtypeid
            editor_tdata_150.field('itemtypeid').enable();

            if (v_itemtypeid != null && v_itemtypeid != '') {
                editor_tdata_150.field('itemtypeid').set(v_itemtypeid);
                editor_tdata_150.field('itemtypeid').disable();
            }
        });
    });

</script>

<script>
    var editor_tenderfrompo;
    var editor_completetender;
    var editor_contractfrompo;
    //var editor_contractfromtender;
    var editor_dofrompo;
    var editor_pofromdemand;

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

        // editor_contractfromtender = new $.fn.dataTable.Editor({
        //     ajax: "{$site_url}disbekal/wfpengadaan/createcontractfromtender",
        //     //idSrc: "poid",
        //     fields: [{
        //         label: "No Kontrak <span class='text-danger font-weight-bold'>*</span>",
        //         compulsory: true,
        //         name: "contractnum",
        //         type: 'tcg_text',
        //     }, {
        //         label: "Tanggal Kontrak <span class='text-danger font-weight-bold'>*</span>",
        //         compulsory: true,
        //         name: "contractdate",
        //         type: 'tcg_date',
        //     }, ],
        //     formOptions: {
        //         main: {
        //             submit: 'all'
        //         }
        //     },
        //     i18n: {
        //         create: {
        //             button: "Baru",
        //             title: "Data Kontrak",
        //             submit: "Simpan"
        //         },
        //         error: {
        //             system: "System error. Hubungi system administrator."
        //         },
        //         datetime: {
        //             previous: "Sebelum",
        //             next: "Setelah",
        //             months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "Desember"],
        //             weekdays: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
        //             hour: "Jam",
        //             minute: "Menit"
        //         }
        //     }
        // });

        // editor_contractfromtender.on('preSubmit', function(e, o, action) {
        //     if (action === 'create' || action === 'edit') {
        //         let field = null;
        //         let hasError = false;

        //         field = this.field('contractnum');
        //         if (!field.isMultiValue()) {
        //             hasError = false;
        //             if (!field.val() || field.val() == 0) {
        //                 hasError = true;
        //                 field.error('Harus diisi');
        //             }
        //         }
        //         field = this.field('contractdate');
        //         if (!field.isMultiValue()) {
        //             hasError = false;
        //             if (!field.val() || field.val() == 0) {
        //                 hasError = true;
        //                 field.error('Harus diisi');
        //             }
        //         }
        //         /* If any error was reported, cancel the submission so it can be corrected */
        //         if (this.inError()) {
        //             this.error('Data wajib belum diisi atau tidak berhasil divalidasi');
        //             return false;
        //         }
        //     }

        // });

        // editor_contractfromtender.on('postSubmit', function(e, json, data, action, xhr) {

        //     let row = v_dt.row("#" +v_id);
        //     let value = row.data();
        //     let label = value['ponum'];

        //     if (json.status === null || json.status == 0 || (json.error !== undefined && json.error !== null && json.error !== '')) {
        //         //error
        //         let msg = "Tidak spesifik";
        //         if (json.error !== undefined && json.error !== null && json.error !== '') {
        //             msg = json.error;
        //         }
        //         toastr.error("Tidak berhasil membuat Kontrak untuk Pengadaan " +label)
        //         toastr.error(msg)
        //         return;
        //     }

        //     //successful - reload
        //     if (json.data !== undefined && json.data !== null) {
        //         row.data(json.data[0]);
        //     }
            
        //     toastr.success("Berhasil membuat Kontrak untuk Pengadaan " +label+ ".");

        //     onselect_pengadaan(v_dt, null, null);

        // });

        // editor_contractfromtender.on( 'open' , function ( e, type ) {
        //     editor_contractfromtender.field('tenderid').set(v_tenderid);
        // });

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

        editor_pofromdemand = new $.fn.dataTable.Editor({
            ajax: "{$site_url}disbekal/wfpengadaan/createpofromdemand",
            //idSrc: "poid",
            fields: [
            {
                label: "Rencana Kebutuhan <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "demandid",
                type: 'tcg_select2',
                //ajax: "{$site_url}{$controller}/kebutuhan/lookup",
            }, {
                label: "Tahun Anggaran <span class='text-danger font-weight-bold'>*</span>",
                compulsory: true,
                name: "year",
                type: 'tcg_number',
                attr: {
                    min: 2000
                }
            }, {
                label: "Nomer Perintah Pengadaan",
                compulsory: true,
                name: "ponum",
                type: 'tcg_text',
           }, ],
            formOptions: {
                main: {
                    submit: 'all'
                }
            },
            i18n: {
                create: {
                    button: "Baru",
                    title: "Perintah Pengadaan",
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

        editor_pofromdemand.on('preSubmit', function(e, o, action) {
            if (action === 'create' || action === 'edit') {
                let field = null;
                let hasError = false;

                field = this.field('demandid');
                if (!field.isMultiValue()) {
                    hasError = false;
                    if (!field.val() || field.val() == 0) {
                        hasError = true;
                        field.error('Harus diisi');
                    }
                }
                field = this.field('year');
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

        editor_pofromdemand.on('postSubmit', function(e, json, data, action, xhr) {

            //let row = v_dt.row("#" +v_id);
            // let value = row.data();
            // let label = value['ponum'];

            if (json === null || json.status === null || json.status == 0 || (json.error !== undefined && json.error !== null && json.error !== '')) {
                //error
                let msg = "Tidak spesifik";
                if (json === null) {
                    msg = "Internal system error";
                }
                else if (json.error !== undefined && json.error !== null && json.error !== '') {
                    msg = json.error;
                }
                toastr.error("Tidak berhasil membuat Perintah Pengadaan baru")
                toastr.error(msg)
                return;
            }

            //successful - reload
            if (json.data !== undefined && json.data !== null) {
                dt_tdata_150.row.add(json.data[0]);
            }
            
            toastr.success("Berhasil membuat Perintah Pengadaan baru");

            onselect_pengadaan(dt_tdata_150, null, null);

        });

        editor_pofromdemand.on( 'open' , function ( e, type ) {
            let year = $("#f_year").val();
            if (year == null || year == 0) {
                year = new Date().getFullYear();
            }
            editor_pofromdemand.field('year').set(year);
            editor_pofromdemand.field('year').disable();
        });

    });
        
</script>

{include file='crud/_js.tpl'}

{/if}