{if $crud.filter || $crud.search}
{include file='crud/_js-crud-filter.tpl'}
{/if}

<script type="text/javascript">
    //override default filter value. must be after include js-crud-filter.tpl
    v_itemtypeid = '{if !empty($userdata["itemtypeid"])}{$userdata["itemtypeid"]}{/if}';
    v_siteid = '{if !empty($userdata["siteid"])}{$userdata["siteid"]}{/if}';

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
    
    function onclick_approvebulk(e, dt, node, conf) {
        let data = dt.rows('.selected').data();
        let ids = [];

        for(i=0; i<data.length; i++) {
            val = data[i];
            if (val['status'] == 'DRAFT') {
                ids.push(val['invusageid']);
            }
        }

        if(ids.length == 0)     return;

        let str = ids.join(',');
        $.confirm({
            title: 'Konfirmasi',
            content: 'Setujui ' +ids.length+ ' Bekal Keluar?',
            buttons: {
                cancel: function () {
                    //nothing
                },
                formSubmit: {
                    text: 'Bekal Keluar',
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
                            url: site_url +"disbekal/wfinventory/approvestockout",
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
            content: 'Setujui 1 Bekal Keluar?',
            buttons: {
                cancel: function () {
                    //nothing
                },
                formSubmit: {
                    text: 'Bekal Keluar',
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
                            url: site_url +"disbekal/wfinventory/approvestockout",
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

    //TODO: cascading edit for storeid => locationid/inventoryid

    //TODO: cascading edit for locationid => inventoryid

    //TODO: cascading edit for inventoryid => inventoryid_label/itemid_label/manufacturerid_label

    function onchange_gudang(field, oldvalue, newvalue, editor, dt) {
        let storeid = newvalue;
        
        editor.field('locationid').ajax("{$site_url}crud/lokasigudang/lookup?f_storeid=" +(storeid==null?'':storeid));
        editor.field('locationid').reload();

        let locationid = editor.field('locationid').val();

        editor.field('inventoryid').ajax("{$site_url}crud/stock/lookup?f_storeid=" +(storeid==null?'':storeid)+ "&f_locationid=" +(locationid==null?'':locationid));
        editor.field('inventoryid').reload();

        return newvalue;
    }

    function onchange_lokasi(field, oldvalue, newvalue, editor, dt) {
        let storeid = editor.field('storeid').val();;
        let locationid = newvalue;

        editor.field('inventoryid').ajax("{$site_url}crud/stock/lookup?f_storeid=" +(storeid==null?'':storeid)+ "&f_locationid=" +(locationid==null?'':locationid));
        editor.field('inventoryid').reload();
        
        return newvalue;
    }

</script>

{include file='crud/_js.tpl'}