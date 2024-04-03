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

    function conditional_hapusbuku(data, row, meta) {
        if (data['status'] == 'DRAFT') {
            return 1;
        }

        return 0;
    }


    function onclick_hapusbukubulk(e, dt, node, conf) {
        let data = dt.rows('.selected').data();

        //hanya yang belum hapus buku yang ditampilkan
        let ids = data.pluck('invstatusid');;

        // let ids = [];
        // for(i=0; i<data.length; i++) {
        //     val = data[i];
        //     if (val['writeoff'] == 0) {
        //         ids.push(val['invstatusid']);
        //     }
        // }

        if(ids.length == 0)     return;

        let str = ids.join(',');
        $.confirm({
            title: 'Konfirmasi',
            content: 'Hapus buku ' +ids.length+ ' Stok?',
            buttons: {
                cancel: function () {
                    //nothing
                },
                formSubmit: {
                    text: 'Hapus Buku',
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
                            url: site_url +"disbekal/wfinventory/writeoff",
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

    function onclick_hapusbuku(row, dt, id) {
        $.confirm({
            title: 'Konfirmasi',
            content: 'Hapus buku 1 Stok?',
            buttons: {
                cancel: function () {
                    //nothing
                },
                formSubmit: {
                    text: 'Hapus Buku',
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
                            url: site_url +"disbekal/wfinventory/writeoff",
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

</script>

{include file='crud/_js.tpl'}