<!DOCTYPE html>
<html>

<head>
    <title>Check-In Silaturahmi 2023 | Induk Warga Asal Kabupaten Kebumen</title>
    <!-- all the meta tags -->
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <meta name="title" property="og:title" content="Check-In Silaturahmi 2023 | Induk Warga Asal Kabupaten Kebumen" />
    <meta name="type" property="og:type" content="website" />
    <meta name="image" property="og:image" content="<?php echo base_url(); ?>/images/iwakk.png" />

    <!-- all the css files -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/image/icon.ico">

    <!-- <link rel="shortcut icon" href="http://localhost/academy/uploads/system/favicon.png"> -->

    <!-- bootstrap -->
    <link href="<?php echo base_url(); ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- select2 -->
    <link href="<?php echo base_url(); ?>/assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- datatables -->
    <link href="<?php echo base_url(); ?>/assets/datatables/DataTables-1.10.20/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/datatables/Responsive-2.2.3/css/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/datatables/Buttons-1.6.1/css/buttons.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/datatables/Select-1.3.1/css/select.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/datatables/KeyTable-2.5.1/css/keyTable.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>/assets/datatables/Editor-1.9.2/css/editor.bootstrap4.css" rel="stylesheet" type="text/css">

    <link href="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-text.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-number.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-readonly.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-date.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-textarea.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-editor.css" rel="stylesheet" />

    <link href="<?php echo base_url(); ?>/assets/dropzone/dropzone.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-upload.bootstrap4.css" rel="stylesheet" />

    <!-- WYSIWYG editor -->
    <!-- <link href="<?php echo base_url(); ?>/assets/backend/css/vendor/summernote-bs4.css" rel="stylesheet" type="text/css" /> -->

    <!-- toastr toast popup -->
    <link href="<?php echo base_url(); ?>/assets/jquery-confirm/jquery-confirm.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/assets/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

    <!-- utilities -->
    <link href="<?php echo base_url(); ?>/assets/utilities.css" rel="stylesheet" type="text/css" />

    <!-- jquery js. must be loaded first before other js -->
    <script src="<?php echo base_url(); ?>/assets/jquery-3.4.1/jquery.min.js"></script>

    <!-- using jquery-3.6.0 screw up bubble editor layout! -->
    <!-- <script src="<?php echo base_url(); ?>/assets/jquery/jquery-3.6.0.min.js"></script> -->

    <!-- App css -->
    <link href="<?php echo base_url(); ?>/themes/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/themes/adminlte/dist/css/adminlte.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>/themes/adminlte/app.css" rel="stylesheet" type="text/css" />
    <!-- <link href="<?php echo base_url(); ?>/themes/adminlte/css/main.css" rel="stylesheet" type="text/css" /> -->

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet">

    <style>
        .modal-content.DTE_Action_Remove {
            margin-left: auto;
            margin-right: auto;
        }

        #toast-container>div {
            opacity: 1;
        }

        .toast {
            background-color: var(--primary) !important;
        }

        .toast-success {
            background-color: var(--success) !important;
        }

        .toast-error {
            background-color: var(--danger) !important;
        }

        .toast-info {
            background-color: var(--info) !important;
        }

        .toast-warning {
            background-color: var(--warning) !important;
        }

        .form-group label .required {
            color: var(--danger);
        }

        /* hack */
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #ddd;
            color: #313a46;
        }

        .nav-tabs .nav-item {
            min-width: 100px;
            text-align: center;
            margin-bottom: -1px;
        }

        table.dataTable.nowrap th,
        table.dataTable.nowrap td {
            white-space: normal;
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 500px;
                margin: 1.75rem auto;
            }

            .modal-content.DTE_Action_Remove {
                max-width: 500px;
            }
        }

        @media (min-width: 768px) {
            .modal-dialog {
                max-width: 700px;
                margin: 1.75rem auto;
            }
        }

        @media (min-width: 992px) {
            .modal-dialog {
                max-width: 900px;
                margin: 1.75rem auto;
            }
        }

        // Extra large devices (large desktops, 1200px and up)
        @media (min-width: 1200px) {}

        @media only screen and (max-width: 767px) {

            .container-fluid {
                padding-right: 0px;
                padding-left: 0px;
            }

            .container-fluid.header {
                padding-right: 8px;
                padding-left: 8px;
            }

            .nav-tabs .nav-item {
                width: 100%;
                text-align: center;
                margin-bottom: -1px;
            }

            .nav-tabs .nav-item .nav-link {
                border-color: #dee2e6;
                border-radius: .25rem;
            }

            .btn-icon-circle {
                width: 28px !important;
                height: 28px !important;
                margin: 0px 2px 2px !important;
            }

            .btn-icon-circle .fa {
                font-size: .75em !important;
            }

            .btn-dropdown {
                width: 28px !important;
                height: 28px !important;
            }

            .btn-dropdown .fa {
                font-size: .75em !important;
            }

            div.dataTables_wrapper div.dataTables_info {
                white-space: normal;
            }
        }
    </style>

    <style>
        .os-theme-light>.os-scrollbar>.os-scrollbar-track>.os-scrollbar-handle {
            background-color: rgba(0, 0, 0, 0.15) !important;
        }
    </style>
    <!-- page header -->

</head>

<body class="layout-fixed" style="height: auto;">
    <div class="wrapper">
        <!-- HEADER -->
        <!-- Topbar Start -->


        <div class="" style="min-height: 644px;">
            <!-- BEGIN PlACE PAGE CONTENT HERE -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row ">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <image src="<?php echo base_url(); ?>/images/iwakk.png"></image>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="nav-link page-title d-md-none text-center"><b>IWAKK Walet Mas</b></div>
                                            <div class="nav-link page-title d-none d-md-block text-center"><b>Induk Warga Asal Kabupaten Kebumen</b></div>
                                        </div>
                                    </div>
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                    <div class="row ">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="page-title"> <i class="mdi title_icon"></i>
                                        <div id="page-title">Check-In Silaturahmi 2023</div>
                                    </h4>
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                </div>
            </div>

            <div id="checkin" class="content" style="padding: 0px 0.5rem;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card widget-inline">
                                <div class="card-body">
                                    <div class="crud-form" id="tdata_74_form" data-table-id="tdata_74">
                                    </div>
                                </div>
                            </div>
                            <!-- </form> -->
                        </div> <!-- end col -->
                    </div>
                    <div class="row" id="geofencing" style="d-none">
                        <div class="col-12">
                            <div class="card widget-inline">
                                <div class="card-body">
                                    <div class="row">
                                    <div class="col-12">
                                        Tidak berhasil mendapatkan data lokasi GPS. Apabila anda menghadapi kendala dengan fitur GPS di hp, silahkan masukkan kode check-in dari panitia.
                                    </div>
                                    </div>
                                    <div class="row" style="margin-top: 16px;">
                                    <div class="col-md-3 col-form-label">
                                        <label>Kode Check-In</label>
                                    </div>
                                    <div class="col-md-9 form-input">
                                        <input id="kode-checkin" type="text" class="tcg-text-input form-control">
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <!-- </form> -->
                        </div> <!-- end col -->
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-primary crud-form-submit" data-table-id="tdata_74" style="width: 100%;">CHECK-IN</button>
                        </div>
                        <!-- </form> -->
                    </div> <!-- end col -->
                </div>
            </div>

            <div id="success" class="content" style="padding: 0px 0.5rem; display: none">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- <form class="crud-form" data-url="<?php echo base_url(); ?>/index.php/sistem/checkin/json" action="<?php echo base_url(); ?>/index.php/sistem/checkin/json" role="form" enctype="multipart/form-data" method="post" id="tdata_74_form" data-table-id="tdata_74" > -->
                            <div class="card widget-inline">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6 col-lg-4">No. Registrasi:</div>
                                        <div class="col-6 col-lg-8" id="success-noregistrasi">xxx</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 col-lg-4">Nama Lengkap:</div>
                                        <div class="col-6 col-lg-8" id="success-namalengkap">xxx</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 col-lg-4">No. KTP:</div>
                                        <div class="col-6 col-lg-8" id="success-noktp">xxx</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 col-lg-4">No. HP:</div>
                                        <div class="col-6 col-lg-8" id="success-nohp">xxx</div>
                                    </div>
                                </div>
                            </div>
                            <!-- </form> -->
                        </div> <!-- end col -->
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <!-- <form class="crud-form" data-url="<?php echo base_url(); ?>/index.php/sistem/checkin/json" action="<?php echo base_url(); ?>/index.php/sistem/checkin/json" role="form" enctype="multipart/form-data" method="post" id="tdata_74_form" data-table-id="tdata_74" > -->
                            <div class="card widget-inline">
                                <div class="card-body">
                                    Jika data di atas bukan milik anda, silahkan check-in lagi menggunakan nomer registrasi anda.
                                </div>
                            </div>
                            <!-- </form> -->
                        </div> <!-- end col -->
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-primary crud-form-checkin" style="width: 100%;">CHECK-IN LAGI</button>
                        </div>
                        <!-- </form> -->
                    </div> <!-- end col -->

                </div>
            </div>

            <div id="update" class="content" style="padding: 0px 0.5rem; display: none">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card widget-inline">
                                <div class="card-body">
                                    <div class="crud-form" id="tdata_75_form" data-table-id="tdata_75">
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-primary crud-form-update" data-table-id="tdata_75" style="width: 100%;">CHECK-IN</button>
                        </div>
                    </div> <!-- end col -->

                </div>
            </div>

        </div>


        <script type="text/javascript" defer>
            var field_list = [];

            var edit_list = null;
            var detail = null;

            //set the value if necessary
            var no_registrasi = "";
            <?php if (isset($no_registrasi)) { ?>
                no_registrasi = "<?php echo $no_registrasi; ?>";
            <?php }; ?>

            var geofencing_check = 1;
            <?php if (isset($geo_fencing) && !$geo_fencing) { ?>
                geofencing_check = 0;
            <?php }; ?>

            <?php if (!isset($geo_fencing) || $geo_fencing) { ?>
            var latitude = 0;
            var longitude = 0;

            var ref_latitude = 0;
            <?php if (isset($ref_latitude)) { ?>
                ref_latitude = <?php echo $ref_latitude; ?>;
            <?php }; ?>

            var ref_longitude = 0;
            <?php if (isset($ref_longitude)) { ?>
                ref_longitude = <?php echo $ref_longitude; ?>;
            <?php }; ?>

            var rad_latitude = 0;
            <?php if (isset($rad_latitude)) { ?>
                rad_latitude = <?php echo $rad_latitude; ?>;
            <?php }; ?>

            var rad_longitude = 0;
            <?php if (isset($rad_longitude)) { ?>
                rad_longitude = <?php echo $rad_longitude; ?>;
            <?php }; ?>

            var kode_checkin = null;
            <?php if (isset($kode_checkin)) { ?>
                kode_checkin = "<?php echo $kode_checkin; ?>";
            <?php }; ?>

            <?php }; ?>

            $(document).ready(function() {

                const successCallback = (position) => {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    $("#geofencing").hide();
                };

                const errorCallback = (error) => {
                    toastr.error("Tidak berhasil mendapatkan data lokasi");

                    $("#geofencing").show();
                };

                if (!geofencing_check) {
                    $("#geofencing").hide();
                }
                else {
                    navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
                }

                let _options = [];
                let _attr = {};

                let form_checkin = $('#tdata_74_form');

                edit_list = [{
                    "label": "No Registrasi ",
                    "name": "no_registrasi",
                    "type": 'tcg_text',
                    'info': "",
                    'className': "",
                    'labelInfo': "",
                    'message': "",
                    "options": [],
                    'idx': "1",
                    'compulsory': '1'
                }, ];

                edit_list.forEach(function(conf, index, arr) {
                    conf['id'] = "DTE_Field_" + conf['name'];

                    let dom = $(render_template('#crud-form-row', {
                        'name': conf.name,
                        'type': conf.type,
                        'fieldInfo': conf.info,
                        'className': conf.className
                    }));

                    //show info if necessary
                    if (typeof conf.info !== 'undefined' && conf.info !== null && conf.info.length > 0) {
                        dom.find('#' + conf.name + '_input_info').show();
                    }

                    //the input field
                    let input_field = null;

                    //get the edit-type
                    let edit_type = jQuery.fn.dataTable.ext.editorFields[conf.type];
                    if (typeof edit_type === 'undefined') {
                        input_field = $("<span>" + conf.type + "</span>");
                    } else {
                        input_field = edit_type.create(conf);
                    }

                    let label_container = dom.find("label[data-dte-e=label]");
                    label_container.html(conf.label);

                    let input_container = dom.find("#" + conf.name + "_input_control");
                    input_container.prepend(input_field);

                    form_checkin.append(dom);

                    if (conf.name == 'no_registrasi' && no_registrasi != "") {
                        edit_type.set(conf, no_registrasi);
                    }

                    //update the stored value
                    arr[index] = conf;
                });

                $(".crud-form-checkin").on("click", function(e) {
                    $('#page-title').text("Check-in Silaturahmi 2023");
                    //toggle
                    $('#success').hide();
                    $('#checkin').show();
                    //reset data
                    $('#success-noregistrasi').text("");
                    $('#success-namalengkap').text("");
                    $('#success-noktp').text("");
                    $('#success-nohp').text("");
                });

                $(".crud-form-submit[data-table-id='tdata_74']").on("click", function(e) {
                    let form = $(".crud-form[data-table-id='tdata_74']");
                    let id = form.data('id');
                    if (typeof id === "undefined" || id === null) {
                        id = 0;
                    }

                    let data = {};
                    let item = {};
                    let error = false;

                    edit_list.forEach(function(conf, index, arr) {
                        let edit_type = jQuery.fn.dataTable.ext.editorFields[conf.type];
                        if (typeof edit_type === 'undefined') {
                            return;
                        }

                        let val = edit_type.get(conf);
                        item[conf.name] = val;

                        //reset any error first
                        let field = $("#" + conf.id, form_checkin).find('[data-dte-e="msg-error"]');
                        field.addClass("d-none");

                        //check for compulsory field
                        if (conf.compulsory && (typeof val === 'undefined' || val === null || val == "")) {
                            field.html("Harus diisi");
                            field.removeClass("d-none");
                            error = true;
                            return;
                        }

                        //TODO: other validation
                        field.html("");
                        field.addClass("d-none");
                    })

                    //check for error
                    if (error) return;

                    if (geofencing_check) {
                        let geofencing_flag = 1;

                        //kode check-in
                        let val = $("#kode-checkin").val();
                        if (val == kode_checkin) {
                            geofencing_flag = 0;
                        }

                        //geo-fencing
                        if (geofencing_flag) {
                            if (latitude < (ref_latitude - rad_latitude) || latitude > (ref_latitude + rad_latitude)
                            || longitude < (ref_longitude - rad_longitude) || longitude > (ref_longitude + rad_longitude)) 
                            {
                                toastr.error("Sedang tidak di lokasi acara");
                                $("#geofencing").show();
                                return;
                            }
                        }
                    }

                    //build form-data
                    let form_data = {};
                    form_data['action'] = 'checkin';
                    form_data['data'] = item;

                    $(".crud-form-submit[data-table-id='tdata_74']").addClass("disabled");
                    $.ajax({
                        url: "<?php echo $ajax; ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: form_data,
                        // beforeSend: function(request) {
                        //     request.setRequestHeader("Content-Type", "application/json");
                        // },
                        success: function(response) {

                            if (typeof response.error === undefined) {
                                form.trigger('crud.error', "ajax-error", "invalid-response", form_data);
                                toastr.error("Response tidak valid!");
                            } else if (response.error == '1') {
                                form.trigger('crud.error', "ajax-error", response.error, form_data);
                                toastr.error(response.message);

                                let field = $("#no_registrasi_input_control", form_checkin).find('[data-dte-e="msg-error"]');
                                field.html(response.message);
                                field.removeClass("d-none");
                            } else if (response.error == '2') {
                                //set data
                                // let conf = update_fields['id'];
                                // conf.editor.set(conf, response.id);
                                // conf = update_fields['no_registrasi'];
                                // conf.editor.set(conf, response.no_registrasi);
                                id_registrasi = response.id;
                                let conf = update_fields['nama_lengkap'];
                                conf.editor.set(conf, response.nama_lengkap);
                                conf = update_fields['no_ktp'];
                                conf.editor.set(conf, response.no_ktp);
                                if (response.is_ktp_valid == '0') {
                                    let field = $("#" + conf.id, form_update).find('[data-dte-e="msg-error"]');
                                    field.html("No KTP tidak valid");
                                    field.removeClass("d-none");
                                    conf['error'] = 1;
                                    conf['oldvalue'] = response.no_ktp;
                                } else {
                                    conf['error'] = 0;
                                    conf['oldvalue'] = null;
                                }
                                conf = update_fields['no_hp'];
                                conf.editor.set(conf, response.no_hp);
                                if (response.is_hp_valid == '0') {
                                    let field = $("#" + conf.id, form_update).find('[data-dte-e="msg-error"]');
                                    field.html("No HP tidak valid");
                                    field.removeClass("d-none");
                                    conf['error'] = 1;
                                    conf['oldvalue'] = response.no_hp;
                                } else {
                                    conf['error'] = 0;
                                    conf['oldvalue'] = null;
                                }
                                $('#page-title').text("Konfirmasi Data");

                                //toggle
                                $('#checkin').hide();
                                $('#update').show();
                            } else {
                                //show data
                                $('#success-noregistrasi').text(response['no_registrasi']);
                                $('#success-namalengkap').text(response['nama_lengkap']);
                                $('#success-noktp').text(response['no_ktp']);
                                $('#success-nohp').text(response['no_hp']);
                                if (response.message !== undefined) {
                                    $('#page-title').text(response['message']);
                                } else {
                                    $('#page-title').text("Check-in Berhasil");
                                }
                                //toggle
                                $('#checkin').hide();
                                $('#update').hide();
                                $('#success').show();

                                //raise event
                                form.trigger('crud.created', response, form_data);
                                toastr.success("Berhasil!");
                            }

                            $(".crud-form-submit[data-table-id='tdata_74']").removeClass("disabled");

                        },
                        error: function(jqXhr, textStatus, errorMessage) {

                            form.trigger('crud.error', textStatus, errorMessage, form_data);
                            toastr.error(errorMessage.message, "ajax-error");

                            $(".crud-form-submit[data-table-id='tdata_74']").removeClass("disabled");
                        }
                    });

                });


                let form_update = $('#tdata_75_form');

                update_list = [{
                        "label": "No Registrasi ",
                        "name": "no_registrasi",
                        "type": 'hidden',
                        'idx': "1"
                    },
                    {
                        "label": "ID",
                        "name": "id",
                        "type": 'hidden',
                        'idx': "1"
                    },
                    {
                        "label": "Nama Lengkap <span class='text-danger font-weight-bold'>*</span>",
                        "name": "nama_lengkap",
                        "type": 'tcg_text',
                        'info': "",
                        'className': "",
                        'labelInfo': "",
                        'message': "",
                        "options": [],
                        'idx': "2",
                        'readonly': 1
                    },
                    {
                        "label": "No KTP <span class='text-danger font-weight-bold'>*</span>",
                        "name": "no_ktp",
                        "type": 'tcg_text',
                        'info': "",
                        'className': "",
                        'labelInfo': "",
                        'message': "",
                        "options": [],
                        'idx': "2"
                    },
                    {
                        "label": "No HP <span class='text-danger font-weight-bold'>*</span>",
                        "name": "no_hp",
                        "type": 'tcg_text',
                        'info': "",
                        'className': "",
                        'labelInfo': "",
                        'message': "",
                        "options": [],
                        'idx': "2"
                    },
                ];

                var id = 0;
                var update_fields = {};
                update_list.forEach(function(conf, index, arr) {
                    if (conf.type == 'hidden') return;

                    conf['id'] = "DTE_Field_" + conf['name'];

                    let dom = $(render_template('#crud-form-row', {
                        'name': conf.name,
                        'type': conf.type,
                        'fieldInfo': conf.info,
                        'className': conf.className
                    }));

                    //show info if necessary
                    if (typeof conf.info !== 'undefined' && conf.info !== null && conf.info.length > 0) {
                        dom.find('#' + conf.name + '_input_info').show();
                    }

                    //the input field
                    let input_field = null;

                    //get the edit-type
                    let edit_type = jQuery.fn.dataTable.ext.editorFields[conf.type];
                    if (typeof edit_type === 'undefined') {
                        input_field = $("<span>" + conf.type + "</span>");
                    } else {
                        input_field = edit_type.create(conf);
                    }

                    let label_container = dom.find("label[data-dte-e=label]");
                    label_container.html(conf.label);

                    let input_container = dom.find("#" + conf.name + "_input_control");
                    input_container.prepend(input_field);

                    if (conf.type == 'hidden') {
                        input_container.addClass("d-none");
                    };

                    form_update.append(dom);

                    //set the value if necessary
                    if (detail != null) {
                        edit_type.set(conf, detail[conf.name]);
                    }

                    //update the stored value
                    conf['editor'] = edit_type;
                    update_fields[conf['name']] = conf;
                });

                $(".crud-form-update[data-table-id='tdata_75']").on("click", function(e) {
                    let form = $(".crud-form[data-table-id='tdata_75']");

                    let data = {};
                    let item = {};
                    let error = false;

                    update_list.forEach(function(conf, index, arr) {
                        let edit_type = jQuery.fn.dataTable.ext.editorFields[conf.type];
                        if (typeof edit_type === 'undefined') {
                            return;
                        }

                        let val = edit_type.get(conf);
                        item[conf.name] = val;

                        if (conf.error == '1' && val == conf.oldvalue) {
                            error = true;
                            return;
                        }

                        //reset any error first
                        let field = $("#" + conf.id, form_update).find('[data-dte-e="msg-error"]');
                        //field.addClass("d-none");

                        if (conf.name == 'no_ktp') {
                            if (val.length != 16 || isNaN(val)) {
                                field.html("No KTP tidak valid");
                                field.removeClass("d-none");
                                conf['error'] = 1;
                                conf['oldvalue'] = val;
                                error = true;
                                return;
                            }
                        }

                        if (conf.name == 'no_hp') {
                            if (val.length <= 7 || val.length >= 16 || isNaN(val)) {
                                field.html("No HP tidak valid");
                                field.removeClass("d-none");
                                conf['error'] = 1;
                                conf['oldvalue'] = val;
                                error = true;
                                return;
                            }
                        }

                        //check for compulsory field
                        if (conf.compulsory && (typeof val === 'undefined' || val === null || val == "")) {
                            field.html("Harus diisi");
                            field.removeClass("d-none");
                            conf['error'] = 1;
                            conf['oldvalue'] = val;
                            error = true;
                            return;
                        }

                        //TODO: other validation
                        field.html("");
                        field.addClass("d-none");
                        conf['error'] = 0;
                        conf['oldvalue'] = '';
                    })

                    //check for error
                    if (error) {
                        toastr.error("Silahkan perbaiki data yang salah");
                        return;
                    };

                    item['id'] = id_registrasi;

                    //build form-data
                    let form_data = {};
                    form_data['action'] = 'update';
                    form_data['data'] = item;

                    $.ajax({
                        url: "<?php echo $ajax; ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: form_data,
                        // beforeSend: function(request) {
                        //     request.setRequestHeader("Content-Type", "application/json");
                        // },
                        success: function(response) {

                            if (typeof response.error === undefined) {
                                form.trigger('crud.error', "ajax-error", "invalid-response", form_data);
                                toastr.error("Response tidak valid!", "ajax-error");
                            } else if (response.error != '0') {
                                form.trigger('crud.error', "ajax-error", response.error, form_data);
                                toastr.error(response.error, "ajax-error");
                            } else {
                                //show data
                                $('#success-noregistrasi').text(response['no_registrasi']);
                                $('#success-namalengkap').text(response['nama_lengkap']);
                                $('#success-noktp').text(response['no_ktp']);
                                $('#success-nohp').text(response['no_hp']);
                                if (response.message !== undefined) {
                                    $('#page-title').text(response['message']);
                                } else {
                                    $('#page-title').text("Check-in Berhasil");
                                }
                                //toggle
                                $('#checkin').hide();
                                $('#update').hide();
                                $('#success').show();

                                //raise event
                                form.trigger('crud.created', response, form_data);
                                toastr.success("Berhasil!");
                            }

                        },
                        error: function(jqXhr, textStatus, errorMessage) {

                            form.trigger('crud.error', textStatus, errorMessage, form_data);
                            toastr.error(errorMessage, "ajax-error");
                        }
                    });

                });

            });
        </script>

        <script id="crud-form-row" type="text/template">
            <div class="form-group row DTE_Field DTE_Field_Type_{{type}} DTE_Field_Name_{{name}}" id="DTE_Field_{{name}}" data-id="{{name}}">
                <label data-dte-e="label" class="col-md-3 col-form-label form-label" for="DTE_Field_{{name}}"></label>
                <div data-dte-e="input" class="col-md-9 form-input">
                    <div data-dte-e="input-control" id="{{name}}_input_control" class="DTE_Field_InputControl form-input-control" style="display: block;">
                        <!-- actual input field here -->
                        <div data-dte-e="msg-error" class="form-text text-danger small d-none"></div>
                        <div data-dte-e="msg-message" class="form-text text-secondary small d-none"></div>
                        <div data-dte-e="msg-info" class="form-text text-secondary small d-none"></div>
                    </div>   
                    <div data-dte-e="info" id="{{name}}_input_info" class="DTE_Field_Info form-input-info d-none">
                    {{fieldInfo}}
                    </div>    
                </div>
            </div>
        </script>

        <!-- END PLACE PAGE CONTENT HERE -->
    </div>
    </div>

    <!-- all the js files -->
    <!-- bootstrap. bundle includes popper.js -->
    <script src="<?php echo base_url(); ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="<?php echo base_url(); ?>/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js" defer></script>

    <!-- datatables -->
    <script src="<?php echo base_url(); ?>/assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js" defer></script>
    <script src="<?php echo base_url(); ?>/assets/datatables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js" defer></script>
    <script src="<?php echo base_url(); ?>/assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js" defer></script>
    <script src="<?php echo base_url(); ?>/assets/datatables/Responsive-2.2.3/js/responsive.bootstrap4.min.js" defer></script>
    <script src="<?php echo base_url(); ?>/assets/datatables/Select-1.3.1/js/dataTables.select.min.js" defer></script>
    <script src="<?php echo base_url(); ?>/assets/datatables/Select-1.3.1/js/select.bootstrap4.min.js" defer></script>

    <script src="<?php echo base_url(); ?>/assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js" defer></script>
    <script src="<?php echo base_url(); ?>/assets/datatables/Editor-1.9.2/js/editor.bootstrap4.min.js" defer></script>

    <script src="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-text.js" defer></script>
    <script src="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-number.js" defer></script>
    <script src="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-readonly.js" defer></script>
    <script src="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-date.js" defer></script>
    <script src="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-textarea.js" defer></script>
    <script src="<?php echo base_url(); ?>/assets/datatables/tcg/dt-editor-editor.js" defer></script>

    <!-- mustache templating -->
    <script src="<?php echo base_url(); ?>/assets/mustache/mustache.min.js" defer></script>

    <!-- toastr toast popup -->
    <script src="<?php echo base_url(); ?>/assets/toastr/toastr.min.js"></script>

    <!-- app -->
    <script src="<?php echo base_url(); ?>/themes/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js" defer></script>
    <script src="<?php echo base_url(); ?>/themes/adminlte/dist/js/adminlte.min.js" defer></script>
    <script src="<?php echo base_url(); ?>/themes/adminlte/app.js" defer></script>

    <!-- <script src="<?php echo base_url(); ?>/themes/adminlte/js/custom.js" defer></script> -->

    <!-- Toastr and alert notifications scripts -->
    <script type="text/javascript">
        //select2 default theme
        //$.fn.select2.defaults.set("theme", "bootstrap");

        $(function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $.fn.dataTable.tables({
                    visible: true,
                    api: true
                }).columns.adjust().responsive.recalc();
            });

            $('[data-toggle="tooltip"]').tooltip();
        });

        //Dropzone.autoDiscover = false;

        $(document).ready(function() {

            //default toastr options
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

        });

        function notify(message) {
            toastr.info(message, "Heads Up!");
        }

        function success_notify(message) {
            toastr.success(message, "Congratulations!");
        }

        function error_notify(message) {
            toastr.error(message, "Oh Snap!");
        }

        function error_required_field() {
            toastr.error("Please fill all the required fields", "Oh Snap!");
        }
    </script>

    <script type="text/javascript">
        function confirm_modal(delete_url) {
            jQuery('#alert-modal').modal('show', {
                backdrop: 'static'
            });
            document.getElementById('update_link').setAttribute('href', delete_url);
        }
    </script>

    <!-- Info Alert Modal -->
    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-information h1 text-info"></i>
                        <h4 class="mt-2"><?php echo __("Heads Up"); ?>!</h4>
                        <p class="mt-3"><?php echo __("Are you sure"); ?>?</p>
                        <button type="button" class="btn btn-info my-2" data-dismiss="modal"><?php echo __("cancel"); ?></button>
                        <a href="#" id="update_link" class="btn btn-danger my-2"><?php echo __("continue"); ?></a>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script type="text/javascript">
        // render template
        function render_template(selector, options) {
            var template = $(selector).html();
            Mustache.parse(template);
            var rendered_template = Mustache.render(template, options);
            return rendered_template;
        }

        function button_status(element, handle) {
            if (handle == "loading") {
                /* loading */
                element.data('text', element.html());
                element.prop('disabled', true);
                element.html('<span class="spinner-grow spinner-grow-sm mr10"></span>' + 'Loading');
            } else {
                /* reset */
                element.prop('disabled', false);
                element.html(element.data('text'));
            }
        }

        // guid
        function guid() {
            function s4() {
                return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
            }
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
        }

        // is empty
        function is_empty(value) {
            if (typeof value === 'undefined') {
                return true;
            }

            if (value.match(/\S/) == null) {
                return true;
            } else {
                return false;
            }
        }

        // get parameter by name
        function get_parameter_by_name(name) {
            var url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }
    </script>

    <!-- page footer -->

</body>

</html>