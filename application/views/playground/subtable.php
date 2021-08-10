<!DOCTYPE html>
<html>

<head>
    <title>Bank Soal | TCG Framework</title>
    <!-- all the meta tags -->
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- all the css files -->
    <link rel="shortcut icon" href="http://localhost/kebumen/backend/assets/image/icon.ico">

    <!-- <link rel="shortcut icon" href="http://localhost/academy/uploads/system/favicon.png"> -->

    <!-- bootstrap -->
    <link href="http://localhost/kebumen/backend/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />

    <!-- leaflet -->
    <link href="http://localhost/kebumen/backend/assets/leaflet/leaflet/leaflet.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/leaflet/esri/esri-leaflet-geocoder.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/leaflet/fullscreen/leaflet.fullscreen.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/leaflet/easybutton/easy-button.css" rel="stylesheet" />

    <!-- select2 -->
    <link href="http://localhost/kebumen/backend/assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/kebumen/backend/assets/select2/css/select2-bootstrap.min.css" rel="stylesheet"
        type="text/css" />

    <!-- datatables -->
    <link href="http://localhost/kebumen/backend/assets/datatables/DataTables-1.10.20/css/dataTables.bootstrap4.css"
        rel="stylesheet" type="text/css" />
    <link href="http://localhost/kebumen/backend/assets/datatables/Responsive-2.2.3/css/responsive.bootstrap4.css"
        rel="stylesheet" type="text/css" />
    <link href="http://localhost/kebumen/backend/assets/datatables/Buttons-1.6.1/css/buttons.bootstrap4.css"
        rel="stylesheet" type="text/css" />
    <link href="http://localhost/kebumen/backend/assets/datatables/Select-1.3.1/css/select.bootstrap4.css"
        rel="stylesheet" type="text/css" />
    <link href="http://localhost/kebumen/backend/assets/datatables/KeyTable-2.5.1/css/keyTable.bootstrap4.css"
        rel="stylesheet" type="text/css">
    <link href="http://localhost/kebumen/backend/assets/datatables/Editor-1.9.2/css/editor.bootstrap4.css"
        rel="stylesheet" type="text/css">
    <link href="http://localhost/kebumen/backend/assets/datatables/RowReorder-1.2.6/css/rowReorder.bootstrap4.min.css"
        rel="stylesheet" type="text/css">

    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-select2.bootstrap4.css"
        rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-mask.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-toggle.bootstrap4.css"
        rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-checkbox.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-cascade.bootstrap4.css"
        rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-geolocation.bootstrap4.css"
        rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-unitprice.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-table.bootstrap4.css"
        rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-plugin-rowgroup.css" rel="stylesheet" />

    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-text.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-number.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-readonly.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-date.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-textarea.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-editor.css" rel="stylesheet" />

    <link href="http://localhost/kebumen/backend/assets/dropzone/dropzone.min.css" rel="stylesheet" />
    <link href="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-upload.bootstrap4.css"
        rel="stylesheet" />

    <!-- WYSIWYG editor -->
    <!-- <link href="http://localhost/kebumen/backend/assets/backend/css/vendor/summernote-bs4.css" rel="stylesheet" type="text/css" /> -->

    <!-- full calendar -->
    <link href="http://localhost/kebumen/backend/assets/fullcalendar/core/main.min.css" rel="stylesheet"
        type="text/css" />

    <!-- dropzone file upload -->
    <link href="http://localhost/kebumen/backend/assets/dropzone/dropzone.min.css" rel="stylesheet" type="text/css" />

    <!-- dragula drag-n-drop component -->
    <link href="http://localhost/kebumen/backend/assets/dragula/dragula.min.css" rel="stylesheet" type="text/css" />

    <!-- toastr toast popup -->
    <link href="http://localhost/kebumen/backend/assets/jquery-confirm/jquery-confirm.min.css" rel="stylesheet"
        type="text/css" />
    <link href="http://localhost/kebumen/backend/assets/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

    <!-- icons -->
    <link href="http://localhost/kebumen/backend/assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="http://localhost/kebumen/backend/assets/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css" /> -->
    <link href="http://localhost/kebumen/backend/assets/dripicons/icons.min.css" rel="stylesheet" type="text/css" />

    <!-- many of the 3rd-party library (including datatables) require older version of materialdesign icon -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.4.93/css/materialdesignicons.min.css"
        integrity="sha512-9hrcuHFRJBsyfJiotGL1U+zraOkuI5fzlo0X0C8s7gkkgV1wLkmiP1JbUjVAws4Wo8FcSK82Goj64vT8ERocgg=="
        crossorigin="anonymous" />

    <!-- materialdesignicon v5 -->
    <!-- <link href="http://localhost/kebumen/backend/assets/materialdesignicons/css/icons.min.css" rel="stylesheet" type="text/css" /> -->

    <!-- utilities -->
    <link href="http://localhost/kebumen/backend/assets/utilities.css" rel="stylesheet" type="text/css" />

    <!-- jquery plugins -->
    <link href="http://localhost/kebumen/backend/assets/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet"
        type="text/css" />
    <link href="http://localhost/kebumen/backend/assets/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- jquery js. must be loaded first before other js -->
    <script src="http://localhost/kebumen/backend/assets/jquery-3.4.1/jquery.min.js"></script>

    <!-- using jquery-3.6.0 screw up bubble editor layout! -->
    <!-- <script src="http://localhost/kebumen/backend/assets/jquery/jquery-3.6.0.min.js"></script> -->

    <!-- App css -->
    <link href="http://localhost/kebumen/backend/themes/default/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/kebumen/backend/themes/default/css/main.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/kebumen/backend/themes/default/app.css" rel="stylesheet" type="text/css" />

    <script src="http://localhost/kebumen/backend/themes/default/js/onDomChange.js"></script>

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

    .form-group label .required {
        color: var(--danger);
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
    <!-- page header -->

</head>

<body data-layout="detached">
    <!-- HEADER -->
    <!-- Topbar Start -->

    <!-- end Topbar -->
    <div class="container-fluid">
        <div class="wrapper">
            <!-- BEGIN CONTENT -->
            <!-- SIDEBAR -->

            <!-- PAGE CONTAINER-->
            <div class="content-page">
                    <!-- BEGIN PlACE PAGE CONTENT HERE -->
                    <!-- <div class="content-header">
                        <div class="container-fluid">
                            <div class="row ">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="page-title"> <i class="mdi  title_icon"></i>
                                                Bank Soal </h4>
                                        </div> 
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <style>
                    .adv-search-box {
                        padding: 12px 10px 0px 10px;
                        display: flex;
                        flex-wrap: wrap;
                    }

                    .btn .caret {
                        margin-left: 0;
                    }

                    .caret {
                        display: inline-block;
                        width: 0;
                        height: 0;
                        margin-left: 2px;
                        vertical-align: middle;
                        border-top: 4px dashed;
                        border-top: 4px solid\9;
                        border-right: 4px solid transparent;
                        border-left: 4px solid transparent;
                    }

                    .cust-input-grp .btn-group .adv-search-btn {
                        box-shadow: none;
                        border: 1px solid #ccc;
                        border-top-left-radius: 0;
                        border-bottom-left-radius: 0;
                        border-top-right-radius: 4px;
                        border-bottom-right-radius: 4px;
                    }

                    .form-control2 {
                        display: block;
                        width: 100%;
                        height: calc(2.2125rem + 2px);
                        padding: .45rem .9rem;
                        font-size: .875rem;
                        font-weight: 400;
                        line-height: 1.5;
                        color: #6c757d;
                        background-color: #fff;
                        background-clip: padding-box;
                        border: 1px solid #dee2e6;
                        border-radius: .25rem;
                        -webkit-transition: border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
                        transition: border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
                        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
                        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
                    }

                    .form-control3 {
                        display: block;
                        width: 100%;
                        height: calc(2.2125rem + 2px);
                        padding: .45rem .9rem;
                        font-size: .875rem;
                        font-weight: 400;
                        line-height: 1.5; 
                        color: #6c757d;
                        background-color: #fff;
                        background-clip: padding-box;
                        border: 1px solid #dee2e6;
                        border-radius: .25rem;
                    }
                    </style>


                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control">

                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end card-box-->
                                </div> <!-- end col-->
                            </div>
                        </div>
                    </section>

                     
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive-sm">
                                                <table id="tdata_120" class="table table-striped dt-responsive nowrap"
                                                    width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" data-priority="1"
                                                                style="word-break: normal!important;">
                                                                Id
                                                                </th>
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Mata Pelajaran
                                                                </th>
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Pertanyaan / Tugas
                                                                </th>
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Tipe
                                                                </th>
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Nilai Maksimal
                                                                </th>
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Url Video
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                            <div class=" my-3"></div>
                                            <div class="table-responsive-sm">
                                                <table id="tdata_119"
                                                    class="table table-striped dt-responsive nowrap"
                                                    width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Id
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Urutan
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Label
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Text
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Jawaban Benar
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Nilai
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Gambar
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Video Url
                                                            <th class="text-center" data-priority="100"
                                                                style="word-break: normal!important;">
                                                                Video Type
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <!-- /.tabbable -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <script type="text/javascript" defer>
                    var base_url = "http://localhost/kebumen/backend/";
                    var site_url = "http://localhost/kebumen/backend/index.php/";
                    var ajax_url = "http://localhost/kebumen/backend/index.php/user/teacher_question_banks/json";

                    var editor_tdata_120 = null;
                    var dt_tdata_120 = null;

                    $(document).ready(function() {
                        $.fn.dataTable.ext.errMode = 'throw';
                        $.extend($.fn.dataTable.defaults, {
                            responsive: true,
                        });
                        $.extend(true, $.fn.dataTable.Editor.defaults, {
                            formOptions: {
                                main: {
                                    onBackground: 'none'
                                },
                                bubble: {
                                    onBackground: 'none'
                                }
                            }
                        });

                        editor_tdata_120 = new $.fn.dataTable.Editor({
                            ajax: "http://localhost/kebumen/backend/index.php/user/teacher_question_banks/json",
                            table: "#tdata_120",
                            idSrc: "question_id",
                            fields: [{
                                    label: "Id ",
                                    name: "question_id",
                                    type: 'hidden',
                                },
                                {
                                    label: "Mata Pelajaran <span class='text-danger font-weight-bold'>*</span>",
                                    name: "course_id",
                                    type: 'select2',
                                    options: [{'value': '5', 'label': 'Ilmu Pengetahuan Alam'}],
                                },
                            ],
                            formOptions: {
                                main: {
                                    submit: 'changed'
                                }
                            },
                            i18n: {
                                create: {
                                    button: "Baru",
                                    title: "Buat Bank Soal",
                                    submit: "Simpan"
                                },
                                edit: {
                                    button: "Ubah",
                                    title: "Ubah Bank Soal",
                                    submit: "Simpan"
                                },
                                remove: {
                                    button: "Hapus",
                                    title: "Hapus Bank Soal",
                                    submit: "Hapus",
                                    confirm: {
                                        _: "Konfirmasi menghapus %d Bank Soal?",
                                        1: "Konfirmasi menghapus 1 Bank Soal?"
                                    }
                                },
                                error: {
                                    system: "System error. Hubungi system administrator."
                                },
                                datetime: {
                                    previous: "Sebelum",
                                    next: "Setelah",
                                    months: [
                                        "Januari",
                                        "Februari",
                                        "Maret",
                                        "April",
                                        "Mei",
                                        "Juni",
                                        "Juli",
                                        "Augustus",
                                        "September",
                                        "Oktober",
                                        "November",
                                        "Desember"
                                    ],
                                    weekdays: [
                                        "Min",
                                        "Sen",
                                        "Sel",
                                        "Rab",
                                        "Kam",
                                        "Jum",
                                        "Sab"
                                    ],
                                    hour: "Jam",
                                    minute: "Menit"
                                }
                            }
                        });

                        editor_tdata_120.on('preSubmit', function(e, o, action) {
                            if (action === 'create' || action === 'edit') {
                                let field = null;

                                field = this.field('course_id');
                                if (!field.isMultiValue()) {
                                    if (!field.val() || field.val() == 0) {
                                        field.error('Harus diisi');
                                    }
                                }

                                /* If any error was reported, cancel the submission so it can be corrected */
                                if (this.inError()) {
                                    this.error('Data wajib belum diisi');
                                    return false;
                                }
                            }

                            /* dont sent all data for remove */
                            if (action === 'remove') {
                                $.each(o.data, function(key, val) {
                                    o.data[key] = {};
                                    o.data[key].question_id = key;
                                });
                            }

                            /* set the hidden js field */

                            /* level1 hidden field */
                        });

                        dt_tdata_120 = $('#tdata_120').DataTable({
                            "processing": true,
                            "responsive": true,
                            "serverSide": false,
                            paging: false,
                            dom: "Bt",
                            select: true,
                            buttons: {
                                buttons: [{
                                        extend: "create",
                                        editor: editor_tdata_120,
                                        className: 'btn-sm'
                                    },
                                    {
                                        extend: "edit",
                                        editor: editor_tdata_120,
                                        className: 'btn-sm btn-info'
                                    },
                                    {
                                        extend: "remove",
                                        editor: editor_tdata_120,
                                        className: 'btn-sm btn-danger'
                                    },
                                ],
                            },
                            rowId: 'question_id',
                            "ajax": "http://localhost/kebumen/backend/index.php/user/teacher_question_banks/json",
                            "columns": [
                                {
                                    data: "question_id",
                                    editField: "question_id",
                                    className: "col_tcg_text  ",
                                },

                                {
                                    data: "course_id_label",
                                    editField: "course_id",
                                    className: "col_tcg_select2  ",
                                    render: function(data, type, row) {
                                        // if (type == "export") {
                                        //     //export raw data?
                                        // }
                                        return data;
                                    }
                                },

                                {
                                    data: "question",
                                    editField: "question",
                                    className: "col_tcg_textarea  ",
                                },

                                {
                                    data: "type_label",
                                    editField: "type",
                                    className: "col_tcg_select2  ",
                                    render: function(data, type, row) {
                                        // if (type == "export") {
                                        //     //export raw data?
                                        // }
                                        return data;
                                    }
                                },

                                {
                                    data: "point",
                                    editField: "point",
                                    className: "col_tcg_text  ",
                                },
 
                                {
                                    data: "video_url",
                                    editField: "video_url",
                                    className: "col_tcg_text  ",
                                },
                            ],
                       });

                    });

                    </script>


                    <script type="text/javascript" defer>
                    var selected_key_tdata_119 = '';
                    var data_tdata_119 = null;

                    $(document).ready(function() {

                        //use user-select event instead of select/deselect to avoid being triggerred because of API
                        dt_tdata_120.on('select.dt deselect.dt', function() {
                            let data = dt_tdata_120.rows({
                                selected: true
                            }).data();

                            if (data.length == 0) {
                                //on deselect all, clear subtables
                                dt_tdata_119.clear().draw();
                                selected_key_tdata_119 = '';
                                data_tdata_119 = null;
                            } else {
                                //on select, reload subtables
                                //master value
                                selected_key_tdata_119 = data[0]['question_id'];
                                data_tdata_119 = data[0];
                                dt_tdata_119.ajax.url(
                                    "http://localhost/kebumen/backend/index.php/user/teacher_question_banks/subtable/119/" +
                                    selected_key_tdata_119);
                                // editor_tdata_119.s.ajax =
                                //     "http://localhost/kebumen/backend/index.php/user/teacher_question_banks/subtable/119/" +
                                //     selected_key_tdata_119;
                                dt_tdata_119.ajax.reload();
                            }

                        });
                    });
                    </script>




                    <script type="text/javascript" defer>
                    var base_url = "http://localhost/kebumen/backend/";
                    var site_url = "http://localhost/kebumen/backend/index.php/";
                    var ajax_url =
                    "http://localhost/kebumen/backend/index.php/user/teacher_question_banks/subtable/119";

                    var editor_tdata_119 = null;
                    var dt_tdata_119 = null;

                    $(document).ready(function() {



                        dt_tdata_119 = $('#tdata_119').DataTable({
                            "processing": true,
                            "responsive": true,
                            "serverSide": false,
                            "scrollX": false,
                            "pageLength": 25,
                            "lengthMenu": [
                                [25, 50, 100, 200, -1],
                                [25, 50, 100, 200, "All"]
                            ],
                            "paging": true,
                            "pagingType": "numbers",
                            dom: "t",
                            select: true,
                            // buttons: {
                            //     buttons: [{
                            //             extend: "create",
                            //             editor: editor_tdata_119,
                            //             className: 'btn-sm'
                            //         },
                            //         {
                            //             extend: "edit",
                            //             editor: editor_tdata_119,
                            //             className: 'btn-sm btn-info'
                            //         },
                            //         {
                            //             extend: "remove",
                            //             editor: editor_tdata_119,
                            //             className: 'btn-sm btn-danger'
                            //         },
                            //     ],
                            // },
                            "language": {
                                "sProcessing": "Processing",
                                "sLengthMenu": "Menampilkan _MENU_ baris",
                                "sZeroRecords": "No data",
                                "sInfo": "Menampilan _START_ - _END_ dari _TOTAL_ baris",
                                "sInfoEmpty": "Menampilan 0 dari 0 baris",
                                "sInfoFiltered": "Difilter dari _MAX_ total baris",
                                "sInfoPostFix": "",
                                "sSearch": "Mencari",
                                "sUrl": "",
                                "oPaginate": {
                                    "sFirst": "Pertama",
                                    "sPrevious": "Sebelum",
                                    "sNext": "Setelah",
                                    "sLast": "Terakhir"
                                }
                            },
                            rowId: 'option_id',
                            "ajax": "",
                            "columns": [

                                {
                                    data: "option_id",
                                    editField: "option_id",
                                    className: "col_tcg_text  ",
                                },

                                {
                                    data: "order_no",
                                    editField: "order_no",
                                    className: "col_tcg_text  ",
                                },

                                {
                                    data: "label",
                                    editField: "label",
                                    className: "col_tcg_text  ",
                                },

                                {
                                    data: "text",
                                    editField: "text",
                                    className: "col_tcg_textarea  ",
                                },

                                {
                                    data: "is_answer",
                                    editField: "is_answer",
                                    className: "col_tcg_toggle  ",
                                },

                                {
                                    data: "point",
                                    editField: "point",
                                    className: "col_tcg_text  ",
                                },

                                {
                                    data: "picture",
                                    editField: "picture",
                                    className: "col_tcg_text  ",
                                },

                                {
                                    data: "video_url",
                                    editField: "video_url",
                                    className: "col_tcg_text  ",
                                },

                                {
                                    data: "video_type",
                                    editField: "video_type",
                                    className: "col_tcg_text  ",
                                },
                            ],
                            "columnDefs": [
                                // {
                                //     target: [
                                //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //                     //     ],
                                //     visible: false
                                // },
                                {
                                    targets: [0],
                                    orderable: false
                                }
                            ],
                            initComplete: function() {
                                dt_tdata_119_initialized = true;
                            },
                            "createdRow": function(row, data, index) {
                                if ($('ul.dropdown-menu span', row).length == 0) {
                                    $('.btn-dropdown', row).addClass('d-none')
                                }
                            },
                            // "drawCallback": function( settings ) {
                            //     let that = this;
                            //     var api = this.api();
                            //     api.columns.adjust().responsive.recalc();
                            // }
                        });


                    });

                    </script>


                    <!-- END PLACE PAGE CONTENT HERE -->
            </div>
            <!-- END CONTENT -->
        </div>
    </div>

    <!-- all the js files -->
    <!-- bootstrap. bundle includes popper.js -->
    <script src="http://localhost/kebumen/backend/assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>

    <!-- leaflet -->
    <script src="http://localhost/kebumen/backend/assets/leaflet/leaflet/leaflet.js"></script>
    <script src="http://localhost/kebumen/backend/assets/leaflet/esri/esri-leaflet.js"></script>
    <script src="http://localhost/kebumen/backend/assets/leaflet/esri/esri-leaflet-geocoder.js"></script>
    <script src="http://localhost/kebumen/backend/assets/leaflet/fullscreen/Leaflet.fullscreen.min.js"></script>
    <script src="http://localhost/kebumen/backend/assets/leaflet/easybutton/easy-button.js"></script>

    <script src="http://localhost/kebumen/backend/assets/select2/js/select2.min.js"></script>
    <script src="http://localhost/kebumen/backend/assets/jquery-mask/jquery.mask.min.js"></script>

    <!-- datatables -->
    <script src="http://localhost/kebumen/backend/assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js">
    </script>
    <script src="http://localhost/kebumen/backend/assets/datatables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js"
        defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"
        defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/Responsive-2.2.3/js/responsive.bootstrap4.min.js"
        defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/Select-1.3.1/js/dataTables.select.min.js" defer>
    </script>
    <script src="http://localhost/kebumen/backend/assets/datatables/Select-1.3.1/js/select.bootstrap4.min.js" defer>
    </script>

    <script src="http://localhost/kebumen/backend/assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js" defer>
    </script>
    <script src="http://localhost/kebumen/backend/assets/datatables/Buttons-1.6.1/js/buttons.bootstrap4.min.js" defer>
    </script>
    <script src="http://localhost/kebumen/backend/assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js" defer>
    </script>
    <script src="http://localhost/kebumen/backend/assets/datatables/JSZip-2.5.0/jszip.min.js" defer></script>
    <!--
<script src="http://localhost/kebumen/backend/assets/datatables/Buttons-1.6.1/js/buttons.flash.min.js" defer></script>
<script src="http://localhost/kebumen/backend/assets/datatables/Buttons-1.6.1/js/buttons.print.min.js" defer></script> -->

    <!-- datatables : spreadsheet like key -->
    <script src="http://localhost/kebumen/backend/assets/datatables/KeyTable-2.5.1/js/dataTables.keyTable.min.js" defer>
    </script>
    <script src="http://localhost/kebumen/backend/assets/datatables/KeyTable-2.5.1/js/keyTable.bootstrap4.min.js" defer>
    </script>

    <script src="http://localhost/kebumen/backend/assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js"></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/Editor-1.9.2/js/editor.bootstrap4.min.js" defer>
    </script>

    <script src="http://localhost/kebumen/backend/assets/datatables/RowReorder-1.2.6/js/dataTables.rowReorder.min.js"
        defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/RowReorder-1.2.6/js/rowReorder.bootstrap4.min.js"
        defer></script>

    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-select2.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-mask.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-toggle.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-checkbox.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-cascade.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-geolocation.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-unitprice.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-table.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-plugin-rowgroup.js" defer></script>

    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-text.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-number.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-readonly.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-date.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-textarea.js" defer></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-editor.js" defer></script>

    <script src="http://localhost/kebumen/backend/assets/dropzone/dropzone.min.js"></script>
    <script src="http://localhost/kebumen/backend/assets/datatables/tcg/dt-editor-upload.js" defer></script>

    <!-- WYSIWYG editor -->
    <script src="http://localhost/kebumen/backend/assets/ckeditor/ckeditor.js"></script>
    <script src="http://localhost/kebumen/backend/assets/ckeditor/adapters/jquery.js"></script>
    <!-- <script src="http://localhost/kebumen/backend/assets/backend/js/vendor/summernote-bs4.min.js"></script>
<script src="http://localhost/kebumen/backend/assets/backend/js/pages/demo.summernote.js"></script> -->

    <!-- full calendar -->
    <script src="http://localhost/kebumen/backend/assets/fullcalendar/core/main.min.js" defer></script>

    <!-- dropzone file upload -->
    <script src="http://localhost/kebumen/backend/assets/dropzone/dropzone.min.js" defer></script>

    <!-- dragula drag-n-drop component -->
    <script src="http://localhost/kebumen/backend/assets/dragula/dragula.min.js" defer></script>

    <!-- mustache templating -->
    <script src="http://localhost/kebumen/backend/assets/mustache/mustache.min.js" defer></script>

    <!-- toastr toast popup -->
    <script src="http://localhost/kebumen/backend/assets/jquery-confirm/jquery-confirm.min.js"></script>
    <script src="http://localhost/kebumen/backend/assets/toastr/toastr.min.js"></script>

    <!-- fontawesome -->
    <script src="http://localhost/kebumen/backend/assets/fontawesome/js/fontawesome.min.js" defer charset="utf-8">
    </script>
    <!-- <script src="http://localhost/kebumen/backend/assets/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js" defer charset="utf-8"></script> -->

    <!-- jquery plugins -->
    <script src="http://localhost/kebumen/backend/assets/jquery-jvectormap/jquery-jvectormap.min.js" defer></script>
    <!-- <script src="http://localhost/kebumen/backend/assets/backend/js/vendor/jquery-jvectormap-world-mill-en.js"></script> -->
    <script src="http://localhost/kebumen/backend/assets/bootstrap-tagsinput/bootstrap-tagsinput.min.js" defer
        charset="utf-8"></script>

    <!--- moment -->
    <script src="http://localhost/kebumen/backend/assets/moment/moment-with-locales.min.js" defer></script>

    <!-- third party js -->
    <!-- <script src="http://localhost/kebumen/backend/assets/backend/js/vendor/Chart.bundle.min.js"></script>
<script src="http://localhost/kebumen/backend/assets/backend/js/pages/demo.dashboard.js"></script>
<script src="http://localhost/kebumen/backend/assets/backend/js/pages/datatable-initializer.js"></script>
<script src="http://localhost/kebumen/backend/assets/backend/js/pages/demo.form-wizard.js"></script> -->

    <!-- app -->
    <script src="http://localhost/kebumen/backend/themes/default/js/app.min.js" defer></script>
    <script src="http://localhost/kebumen/backend/themes/default/js/ui/component.fileupload.js" defer charset="utf-8">
    </script>
    <script src="http://localhost/kebumen/backend/themes/default/js/ui/component.dragula.js" defer></script>

    <script src="http://localhost/kebumen/backend/themes/default/js/custom.js" defer></script>
    <script src="http://localhost/kebumen/backend/themes/default/app.js" defer></script>

</body>

</html>