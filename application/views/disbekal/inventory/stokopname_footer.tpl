<script type="text/javascript">

    function onclick_approve(row, dt, id) {
        $.confirm({
            title: 'Konfirmasi',
            content: 'Setujui 1 Stok Opnam?',
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
                        formData.append("id", id);

                        this.buttons.cancel.disable();
                        this.buttons.formSubmit.disable();

                        $.ajax({
                            type: "POST",
                            url: site_url +"disbekal/stockcheck/approve",
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

                                if (json.status==1) {
                                    dt.rows().deselect();
                                    //dt_tdata_86.clear().draw();
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

    function onclick_daftarbarang(row, dt, id) {
        $.confirm({
            title: 'Konfirmasi',
            content: 'Buat daftar barang untuk Stok Opnam? Ini akan menimpa data detail stok opnam yang sudah ada.',
            columnClass: 'medium',
            buttons: {
                cancel: function () {
                    //nothing
                },
                formSubmit: {
                    text: 'Buat',
                    btnClass: 'btn-primary',
                    action: function () {
                        let that = this;

                        // add assoc key values, this will be posts values
                        var formData = new FormData();
                        formData.append("id", id);

                        this.buttons.cancel.disable();
                        this.buttons.formSubmit.disable();

                        $.ajax({
                            type: "POST",
                            url: site_url +"disbekal/stockcheck/generate",
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
                                dt.row("#" +id).select();

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