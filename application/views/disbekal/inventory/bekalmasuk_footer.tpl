<script type="text/javascript">

</script>

{if $crud.filter || $crud.search}
{include file='crud/_js-crud-filter.tpl'}
{/if}

<script type="text/javascript">
    //override default filter value. must be after include js-crud-filter.tpl
    v_itemtypeid = '{if !empty($userdata["itemtypeid"])}{$userdata["itemtypeid"]}{/if}';
    v_siteid = '{if !empty($userdata["siteid"])}{$userdata["siteid"]}{/if}';
    //v_year = new Date().getFullYear();

    if (v_itemtypeid!='' && v_itemtypeid!=0) {
        $("#f_itemtypeid").attr("disabled", true);
    }
</script>

{include file="crud/_js-crud-table.tpl" tbl=$crud}

<script type="text/javascript">

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

    // function onclick_approve(rowIdx, dt, id) {
    //     toastr.warning("Belum diimplementasikan");
    // }

    // function onclick_approvebulk(e, dt, node, conf) {
    //     toastr.warning("Belum diimplementasikan");
    // }

    function onclick_deleteall(e, dt, node, conf) {
        $.confirm({
            title: 'Konfirmasi',
            content: 'Hapus SEMUA Bekal Masuk?',
            columnClass: 'medium',
            buttons: {
                cancel: function () {
                    //nothing
                },
                formSubmit: {
                    text: 'Hapus SEMUA',
                    btnClass: 'btn-danger',
                    action: function () {
                        let that = this;

                        this.buttons.cancel.disable();
                        this.buttons.formSubmit.disable();

                        $.ajax({
                            type: "POST",
                            url: site_url +"disbekal/wfinventory/deletestockinall",
                            async: true,
                            cache: false,
                            contentType: false,
                            processData: false,
                            timeout: 60000,
                            dataType: 'json',
                            success: function(json) {
                                if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
                                    that.buttons.cancel.enable();
                                    that.buttons.formSubmit.enable();
                                    
                                    alert(message);
                                    return;
                                }

                                toastr.success("Berhasil menghapus SEMUA Bekal Masuk");
                                
                                dt.ajax.reload();
                                that.close();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                let message = that.$content.find('#error');
                                that.buttons.cancel.enable();
                                that.buttons.formSubmit.enable();

                                alert(message);
                                return;
                            }
                        });

                        //wait for completion of ajax
                        return false;
                    }
                },
            }
        });
    }

    function onclick_approveall(e, dt, node, conf) {
        $.confirm({
            title: 'Konfirmasi',
            content: 'Setujui SEMUA Bekal Masuk?',
            columnClass: 'medium',
            buttons: {
                cancel: function () {
                    //nothing
                },
                formSubmit: {
                    text: 'Setujui SEMUA',
                    btnClass: 'btn-danger',
                    action: function () {
                        let that = this;

                        this.buttons.cancel.disable();
                        this.buttons.formSubmit.disable();

                        $.ajax({
                            type: "POST",
                            url: site_url +"disbekal/wfinventory/approvestockinall",
                            async: true,
                            cache: false,
                            contentType: false,
                            processData: false,
                            timeout: 60000,
                            dataType: 'json',
                            success: function(json) {
                                if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
                                    that.buttons.cancel.enable();
                                    that.buttons.formSubmit.enable();
                                    
                                    alert(message);
                                    return;
                                }

                                toastr.success("Berhasil menyetujui SEMUA Bekal Masuk");

                                dt.ajax.reload();
                                that.close();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                let message = that.$content.find('#error');
                                that.buttons.cancel.enable();
                                that.buttons.formSubmit.enable();

                                alert(message);
                                return;
                            }
                        });

                        //wait for completion of ajax
                        return false;
                    }
                },
            }
        });
    }


    function onclick_approvebulk(e, dt, node, conf) {
        let data = dt.rows('.selected').data();
        let ids = [];

        for(i=0; i<data.length; i++) {
            val = data[i];
            if (val['status'] == 'DRAFT') {
                ids.push(val['invreceiveid']);
            }
        }

        if(ids.length == 0)     return;

        let str = ids.join(',');
        $.confirm({
            title: 'Konfirmasi',
            content: 'Setujui ' +ids.length+ ' Bekal Masuk?',
            columnClass: 'medium',
            buttons: {
                cancel: function () {
                    //nothing
                },
                formSubmit: {
                    text: 'Setujui',
                    btnClass: 'btn-primary',
                    action: function () {
                        let that = this;

                        // add assoc key values, this will be posts values
                        var formData = new FormData();
                        formData.append("ids", str);

                        this.buttons.cancel.disable();
                        this.buttons.formSubmit.disable();

                        $.ajax({
                            type: "POST",
                            url: site_url +"disbekal/wfinventory/approvestockin",
                            async: true,
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            timeout: 60000,
                            dataType: 'json',
                            success: function(json) {
                                if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
                                    that.buttons.cancel.enable();
                                    that.buttons.formSubmit.enable();
                                    
                                    alert(message);
                                    return;
                                }

                                dt.ajax.reload();
                                that.close();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                let message = that.$content.find('#error');
                                that.buttons.cancel.enable();
                                that.buttons.formSubmit.enable();

                                alert(message);
                                return;
                            }
                        });

                        //wait for completion of ajax
                        return false;
                    }
                },
            }
        });
    }

    function onclick_approve(row, dt, id) {
        $.confirm({
            title: 'Konfirmasi',
            content: 'Setujui 1 Bekal Masuk?',
            buttons: {
                cancel: function () {
                    //nothing
                },
                formSubmit: {
                    text: 'Bekal Masuk',
                    btnClass: 'btn-primary',
                    action: function () {
                        let that = this;

                        // add assoc key values, this will be posts values
                        var formData = new FormData();
                        formData.append("ids", id);

                        this.buttons.cancel.disable();
                        this.buttons.formSubmit.disable();

                        $.ajax({
                            type: "POST",
                            url: site_url +"disbekal/wfinventory/approvestockin",
                            async: true,
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            timeout: 60000,
                            dataType: 'json',
                            success: function(json) {
                                if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
                                    that.buttons.cancel.enable();
                                    that.buttons.formSubmit.enable();
                                    
                                    alert(message);
                                    return;
                                }

                                dt.ajax.reload();
                                that.close();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                let message = that.$content.find('#error');
                                that.buttons.cancel.enable();
                                that.buttons.formSubmit.enable();

                                alert(message);
                                return;
                            }
                        });

                        //wait for completion of ajax
                        return false;
                    }
                },
            }
        });
    }

    function onsubmit_stockin(e, o, action, editor, dt) {

        if (action === 'create' || action === 'edit') {
            let field = null;

            let dikirim = parseInt(editor.field('receivedamount').val());
            if (dikirim == null || isNaN(dikirim))    dikirim = 0;
            let diterima = parseInt(editor.field('acceptedamount').val());
            if (diterima == null || isNaN(diterima))   diterima = 0;
            let ditolak = parseInt(editor.field('rejectedamount').val());
            if (ditolak == null || isNaN(ditolak))    ditolak = 0;

            if (dikirim != diterima + ditolak) {
                editor.field('acceptedamount').error('Total diterima + ditolak tidak sama dengan total dikirim');
                editor.field('rejectedamount').error('Total diterima + ditolak tidak sama dengan total dikirim');
                return 0;
            }
        }

        return 1;
    }

</script>

{include file='crud/_js.tpl'}