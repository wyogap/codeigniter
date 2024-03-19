<script type="text/javascript">

    $(function () {
        $('[data-toggle="popover"]').popover()

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
        let data = dt.rows('.selected').data();

        let pos1 = $('.wf-state:has(#wf-pos1)');
        let pos2 = $('.wf-state:has(#wf-pos2)');
        let pos3 = $('.wf-state:has(#wf-pos3)');
        let pos4 = $('.wf-state:has(#wf-pos4)');
        let pos5 = $('.wf-state:has(#wf-pos5)');

        if (data.length == 0 || data.length > 1) {
            update_wf_images(null);
            pos1.popover('disable');
            pos2.popover('disable');
            pos3.popover('disable');
            pos4.popover('disable');
            pos5.popover('disable');
            pos1.removeClass('wf-enable');
            pos2.removeClass('wf-enable');
            pos3.removeClass('wf-enable');
            pos4.removeClass('wf-enable');
            pos5.removeClass('wf-enable');
            pos1.find("img").tooltip('disable');
            pos2.find("img").tooltip('disable');
            pos3.find("img").tooltip('disable');
            pos4.find("img").tooltip('disable');
            pos5.find("img").tooltip('disable');

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

        let status = data[0]['status'];
        update_wf_images(status);

        let val = data[0]['approveddate'];
        if (val===undefined || val===null){
            val="";
        } 
        else if (val.length>10) {
            val=val.substring(0,10);
        }

        let html = '<td class="child"><ul class="dtr-details">'
                    +'<li><span class="dtr-title">' +data[0]['ponum']+ '</span> <span class="dtr-data">' +data[0]['status']+ '</span></li>'
                    +'<li><span class="dtr-title">Tanggal Persetujuan</span> <span class="dtr-data">' 
                        +val+ '</span></li>'
                    +'<li><span class="dtr-title">HPS</span> <span class="dtr-data">' 
                        +$.fn.dataTable.render.number('{$currency_thousand_separator}'
                                                    , '{$currency_decimal_separator}'
                                                    , {$currency_decimal_precision}
                                                    , '{$currency_prefix}').display(data[0]['estprice'])
                        + '</span></li>'
                        +'</ul></td>';
        pos1.attr('data-content',html);
        pos1.popover('hide');
        pos1.addClass('wf-enable');
        pos1.find("img").tooltip('enable');

        if (data[0]['tenderid'] !== undefined && data[0]['tenderid'] !== null && data[0]['tenderid'] != "" && data[0]['tenderid'] != 0) {
            val = data[0]['enddate'];
            if (val===undefined || val===null){
                val="";
            } 
            else if (val.length>10) {
                val=valdt.substring(1,10);
            }

            html = '<td class="child"><ul class="dtr-details">'
                        +'<li><span class="dtr-title">' +data[0]['tenderid_label']+ ' <a target="_blank" href="{$site_url}/{$controller}/tender/detail/' +data[0]['tenderid']+ '"><i class="fa fas fa-external-link-alt"></i></a></span></li>'
                        +'<li><span class="dtr-title">Tanggal Mulai</span> <span class="dtr-data">' +data[0]['startdate']+ '</span></li>'
                        +'<li><span class="dtr-title">Tanggal Selesai</span> <span class="dtr-data">' +val+ '</span></li>'
                            +'</ul></td>';
            pos2.attr('data-content',html);
            pos2.popover('hide');
            pos2.addClass('wf-enable');
            pos2.find("img").tooltip('enable');
        }

        if (data[0]['contractid'] !== undefined && data[0]['contractid'] !== null && data[0]['contractid'] != "" && data[0]['contractid'] != 0) {
            val = data[0]['contractdate'];
            if (val===undefined || val===null){
                val="";
            } 
            else if (val.length>10) {
                val=val.substring(1,10);
            }

            html = '<td class="child"><ul class="dtr-details">'
                        +'<li><span class="dtr-title">' +data[0]['contractid_label']+ ' <a target="_blank" href="{$site_url}/{$controller}/kontrak/detail/' +data[0]['contractid']+ '"><i class="fa fas fa-external-link-alt"></i></a></span> <span class="dtr-data">' +data[0]['contractid_status']+ '</span></li>'
                        +'<li><span class="dtr-title">Tanggal Kontrak</span> <span class="dtr-data">' +val+ '</span></li>'
                        +'<li><span class="dtr-title">Nilai Kontrak</span> <span class="dtr-data">' 
                            +$.fn.dataTable.render.number('{$currency_thousand_separator}'
                                                    , '{$currency_decimal_separator}'
                                                    , {$currency_decimal_precision}
                                                    , '{$currency_prefix}').display(data[0]['contractvalue'])
                            + '</span></li>'
                            +'</ul></td>';
            pos3.attr('data-content',html);
            pos3.popover('hide');
            pos3.addClass('wf-enable');
            pos3.find("img").tooltip('enable');
        }

        if (data[0]['doid'] !== undefined && data[0]['doid'] !== null && data[0]['doid'] != "" && data[0]['doid'] != 0) {
            val = JSON.parse('[' +data[0]['doid_label']+ ']');

            html = '';
            if (val!==undefined || val!==null){
                html += '<td class="child"><ul class="dtr-details">';
                for(i=0; i<val.length; i++) {
                    html += '<li><span class="dtr-title">' +val[i]['donum']+ ' <a target="_blank" href="{$site_url}/{$controller}/perintahterima/detail/' +val[i]['doid']+ '"><i class="fa fas fa-external-link-alt"></i></a></span> <span class="dtr-data">' +val[i]['status']+ '</span></li>';
                    html += '<li><span class="dtr-title">Tanggal</span> <span class="dtr-data">' +val[i]['dodate']+ '</span></li>';
            }
                html += '</ul></td>';
            } 
            pos4.attr('data-content',html);
            pos4.popover('hide');
            pos4.addClass('wf-enable');
            pos4.find("img").tooltip('enable');
            
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
        if (data['status'] == 'APPR' || data['status'] == 'TENDER') {
            return 1;
        }

        return 0;
    }

    function conditional_perintahterima(data, row, meta) {
        if (data['status'] == 'CONTRACT' || data['status'] == 'DELIVR') {
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
        let url = "{$site_url}disbekal/pengadaan/close";

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
                    toastr.error("Tidak berhasil mengarsipkan Pengadaan " +label+ ". Error: " +msg, 'Error');
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
                toastr.error(errorMessage, 'Error');
            }
        });
        {/literal}

    }

    function onclick_approve(rowIdx, dt, id) {
        let data = dt.row(rowIdx).data();
        let label = data['ponum'];
        let url = "{$site_url}disbekal/pengadaan/approve";

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
                    toastr.error("Tidak berhasil menyetujui Pengadaan " +label+ ". Error: " +msg, 'Error');
                    return;
                }

                //successful - reload
                //dt.ajak.reload();
                let row = dt.row(rowIdx);
                let data = row.data();
                data['status'] = 'APPR';
                row.data(data);
                toastr.success("Berhasil menyetujui Pengadaan " +label+ ".");
            },
            error: function(jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        });
        {/literal}

    }

    function onclick_tender(rowIdx, dt, id) {
        let data = dt.row(rowIdx).data();
        let label = data['ponum'];
        let url = "{$site_url}disbekal/pengadaan/buattender";

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
                    toastr.error("Tidak berhasil memasukkan data Tender LPSE untuk Pengadaan " +label+ ". Error: " +msg, 'Error');
                    return;
                }

                //successful - reload
                //dt.ajak.reload();
                let row = dt.row(rowIdx);
                let data = row.data();
                data['status'] = 'APPR';
                row.data(data);
                toastr.success("Berhasil memasukkan data Tender LPSE untuk Pengadaan " +label+ ".");
            },
            error: function(jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        });
        {/literal}

    }

    function onclick_contract(rowIdx, dt, id) {
        let data = dt.row(rowIdx).data();
        let label = data['ponum'];
        let url = "{$site_url}disbekal/pengadaan/buatkontrak";

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
                    toastr.error("Tidak berhasil membuat DRAFT Kontrak untuk Pengadaan " +label+ ". Error: " +msg, 'Error');
                    return;
                }

                //successful - reload
                //dt.ajak.reload();
                let row = dt.row(rowIdx);
                let data = row.data();
                data['status'] = 'APPR';
                row.data(data);
                toastr.success("Berhasil membuat DRAFT Kontrak untuk Pengadaan " +label+ ".");
            },
            error: function(jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        });
        {/literal}

    }

    function onclick_perintahterima(rowIdx, dt, id) {
        let data = dt.row(rowIdx).data();
        let label = data['ponum'];
        let url = "{$site_url}disbekal/pengadaan/buatperintahterima";

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
                    toastr.error("Tidak berhasil membuat DRAFT Perintah Terima untuk Pengadaan " +label+ ". Error: " +msg, 'Error');
                    return;
                }

                //successful - reload
                //dt.ajak.reload();
                let row = dt.row(rowIdx);
                let data = row.data();
                data['status'] = 'APPR';
                row.data(data);
                toastr.success("Berhasil membuat DRAFT Perintah Terima untuk Pengadaan " +label+ ".");
            },
            error: function(jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        });
        {/literal}

    }

    function onclick_buatdarikebutuhan(e, dt, node, conf) {
        let demandid = 0;
        let tahun = 0;
        let label = '';     //label demandid
        let url = "{$site_url}disbekal/kebutuhan/buatpengadaan";

        {literal}
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: demandid, year: tahun},
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
                    toastr.error("Tidak berhasil membuat DRAFT Perintah Terima untuk Pengadaan " +label+ ". Error: " +msg, 'Error');
                    return;
                }

                //successful - reload
                dt.ajak.reload();
                toastr.success("Berhasil membuat DRAFT Pengadaan dari Rencana Kebutuhan " +label+ ".");
            },
            error: function(jqXhr, textStatus, errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        });
        {/literal}

    }

    function update_wf_images(status) {

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
            $('#wf-pos1').attr("src","{$site_url}images/wf/pos1a.png");
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
            $('#wf-pos2').attr("src","{$site_url}images/wf/pos2.png");
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
            $('#wf-pos2').attr("src","{$site_url}images/wf/pos2.png");
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
            $('#wf-pos2').attr("src","{$site_url}images/wf/pos2.png");
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
            $('#wf-pos2').attr("src","{$site_url}images/wf/pos2.png");
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
</script>

{include file="crud/_js-crud-table.tpl" tbl=$crud}

{include file="crud/_js-crud-subtables.tpl" tbl=$crud}

{include file='crud/_js.tpl'}