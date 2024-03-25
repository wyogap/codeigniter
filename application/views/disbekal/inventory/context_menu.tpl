<style>


.bottom-right-menu {
    bottom: 1.25rem;
    position: fixed;
    right: 1.25rem;
    z-index: 1032;
}

.bottom-right-menu > .menu-list {
    text-align: right;
    display: none;
    padding-inline-start: 0px;
    margin-bottom: 44px;
}

.bottom-right-menu > ul > li {
    list-style: none;
    margin-top: .25rem;
}

.bottom-right-menu > ul > li > a > .nav-icon {
    /* margin-left: .2rem; */
    /* font-size: 1.2rem; */
    /* margin-right: .05rem; */
    text-align: right;
    width: 1.6rem;
}

.bottom-right-menu > .menu {
    display: block;
    float: right;
}   

/* .bottom-right-menu > .menu-list > li  {

} */

.context-menu-popup {
    position: fixed;
    right: 1.25rem;
    bottom: 3.5rem;
    width: 13.25rem;
    z-index: 1000;
}

.setting-menu {
    position: fixed;
    right: 4.5rem;
    bottom: 1.25rem;
}

</style>

        <div class="bottom-right-menu d-print-none" >
            <div id="context-menu" href="#" class="btn btn-primary menu" aria-label="Quick context menu" onclick="toggle_context_menu(); return false;" >
                <i class="fas fa-cog"></i>
            </div>
            <div class="btn btn-primary menu disabled"><span class="store-name">Semua Gudang</span>, <span class="label-periode">Tahun Berjalan</span></div>
        </div>

        <div id="context-menu-popup" class="card context-menu-popup d-print-none" style="width: 13.25rem; display: none;">
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 10px;" id="ctx-store">
                    <label for="edit-store" class="small">Gudang</label>
                    <select id="edit-store" name="store" class="form-control-sm" placeholder="Gudang" style="width: 100%;">
                        <option value='0'>-- Semua --</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 10px;" id="ctx-periode">
                    <label for="edit-periode" class="small">Periode</label>
                    <select id="edit-periode" name="periode" class="form-control-sm" placeholder="Periode" style="width: 100%;">
                        <option value='ytd' selected>Tahun Berjalan</option>
                        <option value='mtd'>Bulan Berjalan</option>
                        <option value='30'>30 Hari Yang Lalu</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 10px;" id="ctx-offset">
                    <label for="edit-periode" class="small">Offset</label>
                    <input type="number" id="edit-offset" name="offset" class="form-control-sm" placeholder="0" style="width: 100%;" min="0" />
                </div>
            </div>
            <div class="card-footer text-right">
                <button id="context-menu-reload" class="small" onclick="context_menu_reload_click(); return false;" >Reload</button>
            </div>
        </div>

<script type="text/javascript">

    var storeid = "0";
    var storename = "";
    var periode = "ytd";
    var offset = "0";
    var labelperiode = "";

    $(document).ready(function() {
        $("#context-menu-popup").hide();

        $(".content-wrapper").on("click", function() {
            $("#context-menu-popup").hide();
            $("#context-menu").html("<i class='fas fa-cog'/>");
        });

        let _options = [];

        let _attr = {
            multiple: false,
            minimumResultsForSearch: 25,
        };

        $('#edit-offset').val(1);

        //retrieve list from json
        $.ajax({
            url: "{$base_url}disbekal/select/store",
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.data === null) {} else if (typeof response.error !==
                    'undefined' && response.error !== null && response
                    .error != "") {} else {
                    _options = response.data;
                }
                select_build($('#edit-store'), '-- Semua --', '0', storeid, _options, _attr);

                // select_build($('#edit-korwil'), _options, _attr);
                // $('#edit-korwil').val(korwil);
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
                toastr.error(errorMessage);
                //select_build($('#edit-korwil'), '-- Semua --', '', korwil, null, _attr);
                // select_build($('#edit-korwil'), _options, _attr);
            }
        });

    });

    function toggle_context_menu() {
        //toggle menu-list visibility
        $("#context-menu-popup").toggle();

        //change menu icon
        if ($("#context-menu-popup").is(':visible')) {
            $("#context-menu").html("<i class='fas fa-chevron-down'/>");
        }
        else {
            $("#context-menu").html("<i class='fas fa-cog'/>");
        }
    }

    function context_menu_reload_click() {
        $("#context-menu-popup").hide(); 
        $("#context-menu").html("<i class='fas fa-cog'/>");

        let _storeid = $("#edit-store").val();
        let _periode = $("#edit-periode").val();
        let _offset = $("#edit-offset").val();

        let changed = false;

        if (_storeid != storeid) {
            if (_storeid == 0) {
                storename = "SEMUA GUDANG";
            }
            else {
                storename = $('#edit-store option:selected').text();
            }

            $(".store-name").html(storename);
            if (_storeid == 0) {
                $(".store-name-2").html("");
                $("#btn-reset-store").hide();
            }
            else {
                $(".store-name-2").html("(" +storename+ ")");
                $("#btn-reset-store").show();
            }

            storeid = _storeid;
            changed = true;
        }

        if (_periode != periode || _offset != offset) {
            if (_periode == 'ytd') {
                if (_offset == 0) {
                    labelperiode = 'Tahun Berjalan';
                }
                else {
                    const d = new Date();
                    let year = d.getFullYear() - _offset;
                    labelperiode = "Tahun " + year;
                }
            }
            else if (_periode == 'mtd') {
                if (_offset == 0) {
                    labelperiode = 'Bulan Berjalan';
                }
                else {
                    var d = new Date(); 
                    d.setFullYear(d.getFullYear());
                    d.setMonth(d.getMonth() - _offset);
                    d.setDate(d.getDate());

                    let year = d.getFullYear();
                    let month = nama_bulan(d.getMonth()+1);
                    labelperiode = month + " " + year;
                }
            }
            else {
                var d = new Date(); 
                d.setFullYear(d.getFullYear());
                d.setMonth(d.getMonth());
                d.setDate(d.getDate() - _offset);

                let month = nama_pendek_bulan(d.getMonth()+1);
                labelperiode = d.getDate() + "-" + month + "-" + d.getFullYear() + " (30 Hari)";        
            }

            $(".label-periode").html(labelperiode);
        
            periode = _periode;
            offset = _offset;
            changed = true;
        }

        if (changed) {
            reload();
        }
    }

    function nama_bulan(bulan) {
        switch(parseInt(bulan)) {
            case 1: return 'Januari';
            case 2: return 'Februari';
            case 3: return 'Maret';
            case 4: return 'April';
            case 5: return 'Mei';
            case 6: return 'Juni';
            case 7: return 'Juli';
            case 8: return 'Agustus';
            case 9: return 'September';
            case 10: return 'Oktober';
            case 11: return 'November';
            case 12: return 'Desember';
            default: return bulan;
        }
    }

    function nama_pendek_bulan(bulan) {
        switch(parseInt(bulan)) {
            case 1: return 'Jan';
            case 2: return 'Feb';
            case 3: return 'Mar';
            case 4: return 'Apr';
            case 5: return 'Mei';
            case 6: return 'Jun';
            case 7: return 'Jul';
            case 8: return 'Agu';
            case 9: return 'Sep';
            case 10: return 'Okt';
            case 11: return 'Nov';
            case 12: return 'Des';
            default: return bulan;
        }
    }
</script>