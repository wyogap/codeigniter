<!DOCTYPE html>
<html>

<head>
    <title>Pengguna | Sistem Informasi Kendaraan Dinas Kab. Kebumen</title>
    <!-- all the meta tags -->
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- all the css files -->
    <link rel="shortcut icon" href="http://localhost/codeigniter/assets/image/icon-kebumen.ico">

    <!-- <link rel="shortcut icon" href="http://localhost/academy/uploads/system/favicon.png"> -->

    <!-- bootstrap -->
    <link href="http://localhost/codeigniter/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- select2 -->
    <link href="http://localhost/codeigniter/assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/assets/select2/css/select2-bootstrap.min.css" rel="stylesheet"
        type="text/css" />

    <!-- datatables -->
    <link href="http://localhost/codeigniter/assets/datatables/DataTables-1.10.20/css/dataTables.bootstrap4.css"
        rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/assets/datatables/Responsive-2.2.3/css/responsive.bootstrap4.css"
        rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/assets/datatables/Buttons-1.6.1/css/buttons.bootstrap4.css"
        rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/assets/datatables/Select-1.3.1/css/select.bootstrap4.css" rel="stylesheet"
        type="text/css" />
    <link href="http://localhost/codeigniter/assets/datatables/KeyTable-2.5.1/css/keyTable.bootstrap4.css"
        rel="stylesheet" type="text/css">
    <link href="http://localhost/codeigniter/assets/datatables/Editor-1.9.2/css/editor.bootstrap4.css" rel="stylesheet"
        type="text/css">

    <!-- many of the 3rd-party library (including datatables) require older version of materialdesign icon -->
    <!-- <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.4.93/css/materialdesignicons.min.css"
        integrity="sha512-9hrcuHFRJBsyfJiotGL1U+zraOkuI5fzlo0X0C8s7gkkgV1wLkmiP1JbUjVAws4Wo8FcSK82Goj64vT8ERocgg=="
        crossorigin="anonymous" /> -->

    <!-- materialdesignicon v5 -->
    <!-- <link href="http://localhost/codeigniter/assets/materialdesignicons/css/icons.min.css" rel="stylesheet" type="text/css" /> -->

    <!-- utilities -->
    <!-- <link href="http://localhost/codeigniter/assets/utilities.css" rel="stylesheet" type="text/css" /> -->

    <!-- jquery plugins -->
    <!-- <link href="http://localhost/codeigniter/assets/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet"
        type="text/css" />
    <link href="http://localhost/codeigniter/assets/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet"> -->

    <!-- jquery js. must be loaded first before other js -->
    <!-- <script src="http://localhost/codeigniter/assets/jquery/jquery-3.6.0.min.js"></script> -->

    
    <script src="http://localhost/raps/assets/adminlte/plugins/jquery/jquery.min.js"></script>  

    <!-- App css -->
    <!-- <link href="http://localhost/codeigniter/themes/default/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/themes/default/css/main.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/themes/default/app.css" rel="stylesheet" type="text/css" />

    <script src="http://localhost/codeigniter/themes/default/js/onDomChange.js"></script>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"> -->

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

    <div class="navbar-custom topnav-navbar topnav-navbar-dark">
        <div class="container-fluid header">

            <ul class="list-unstyled topbar-right-menu float-right mb-0">

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown"
                        id="topbar-userdrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="account-user-avatar">
                            <img src="http://localhost/codeigniter/assets/image/user.png" alt="user-image"
                                class="rounded-circle">
                        </span>
                        <span style="color: #fff;">
                            <span class="account-user-name">Yoga</span>
                            <span class="account-position">System Administrator</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
                        aria-labelledby="topbar-userdrop">
                        <!-- item-->
                        <div class=" dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>

                        <!-- Logout-->
                        <a href="http://localhost/codeigniter/index.php/crud/profile" class="dropdown-item notify-item">
                            <i class="mdi mdi-account mr-1"></i>
                            <span>Profil</span>
                        </a>

                        <!-- Logout-->
                        <a href="http://localhost/codeigniter/index.php/auth/logout" class="dropdown-item notify-item">
                            <i class="mdi mdi-logout mr-1"></i>
                            <span>Logout</span>
                        </a>

                    </div>
                </li>

            </ul>

            <a class="button-menu-mobile disable-btn" style="width: 50px;">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>

            <!-- LOGO -->
            <a href="http://localhost/codeigniter/index.php/sistem/home" class="topnav-logo"
                style="min-width: unset; float: unset; line-height: unset; display: flex;">
                <div class="topnav-logo-lg" style="margin-top: 19px; ">
                    <img src="http://localhost/codeigniter/assets/image/logo-kebumen.png" alt="" height="32">
                </div>

                <div class="d-none d-md-flex"
                    style="font-size: 14px; font-weight: 600; line-height: 32px; color: #fff; opacity: 1; text-transform: uppercase; margin-top: 19px; margin-left: 8px">
                    Sistem Informasi Kendaraan Dinas Kab. Kebumen</div>

                <div class="d-flex d-md-none"
                    style="font-size: 14px; font-weight: 600; line-height: 32px; color: #fff; opacity: 1; text-transform: uppercase; margin-top: 19px; margin-left: 8px">
                    SI KENDI</div>
            </a>

        </div>
    </div>
    <!-- end Topbar -->
    <div class="container-fluid">
        <div class="wrapper">
            <!-- BEGIN CONTENT -->
            <!-- SIDEBAR -->

            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu left-side-menu-detached">
                <div class="leftbar-user">
                    <a href="javascript: void(0);">
                        <img src="http://localhost/codeigniter/assets/image/user.png" alt="user-image" height="42"
                            class="rounded-circle shadow-sm">
                        <span class="leftbar-user-name">Yoga</span>
                    </a>
                </div>

                <!--- Sidemenu -->
                <ul class="metismenu side-nav side-nav-light">

                    <li class="side-nav-item ">
                        <a href="http://localhost/codeigniter/index.php/crud/kendaraan_dinas" class="side-nav-link">
                            <i class="dripicons-disc"></i>
                            <span>Kendaraan Dinas</span>
                        </a>
                    </li>
                    <li class="side-nav-item ">
                        <a href="http://localhost/codeigniter/index.php/crud/kendaraan_dinas_verifikasi"
                            class="side-nav-link">
                            <i class="dripicons-help"></i>
                            <span>Kendaraan Dinas (Perlu Verifikasi)</span>
                        </a>
                    </li>
                    <li class="side-nav-item ">
                        <a href="http://localhost/codeigniter/index.php/crud/kendaraan_dinas_dihapus"
                            class="side-nav-link">
                            <i class=""></i>
                            <span>Kendaraan Dinas (Dihapus)</span>
                        </a>
                    </li>

                    <li class="side-nav-item ">
                        <a href="javascript: void(0);" class="side-nav-link">
                            <i class="dripicons-network-2"></i>
                            <span>Workflow</span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul class="side-nav-second-level collapse " aria-expanded="false">
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/workflow">Konfigurasi</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/workflow_instance">Workflow</a>
                            </li>
                        </ul>
                    </li>

                    <li class="side-nav-item ">
                        <a href="javascript: void(0);" class="side-nav-link">
                            <i class="dripicons-device-mobile"></i>
                            <span>SMS</span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul class="side-nav-second-level collapse " aria-expanded="false">
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/sms_inbox">Inbox</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/sms_outbox">Outbox</a>
                            </li>
                        </ul>
                    </li>
                    <li class="side-nav-title side-nav-item">Administrasi</li>
                    <li class="side-nav-item active">
                        <a href="http://localhost/codeigniter/index.php/crud/users" class="side-nav-link">
                            <i class="dripicons-user-group"></i>
                            <span>Pengguna</span>
                        </a>
                    </li>

                    <li class="side-nav-item ">
                        <a href="javascript: void(0);" class="side-nav-link">
                            <i class="dripicons-stack"></i>
                            <span>CRUD</span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul class="side-nav-second-level collapse " aria-expanded="false">
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/pages">Halaman</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/permissions">Hak Akses</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/navigations">Navigasi</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/tables">Tabel</a>
                            </li>
                        </ul>
                    </li>

                    <li class="side-nav-item ">
                        <a href="javascript: void(0);" class="side-nav-link">
                            <i class="dripicons-web"></i>
                            <span>REST API</span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul class="side-nav-second-level collapse " aria-expanded="false">
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/api_keys">API Keys</a>
                            </li>
                        </ul>
                    </li>

                    <li class="side-nav-item ">
                        <a href="javascript: void(0);" class="side-nav-link">
                            <i class="dripicons-view-thumb"></i>
                            <span>Master Data</span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul class="side-nav-second-level collapse " aria-expanded="false">
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/opd">OPD</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/upb">UPB</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/merek">Merek</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/model">Model</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/kodebarang">Jenis Kendaraan</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/kondisi">Kondisi Kendaraan</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/asalusul">Asal Usul Kendaraan</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/tipepenyusutan">Tipe Penyusutan</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/tipemasalah">Tipe Masalah</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/statusperbaikan">Status
                                    Perbaikan</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/peruntukan">Peruntukan</a>
                            </li>
                        </ul>
                    </li>

                    <li class="side-nav-item ">
                        <a href="javascript: void(0);" class="side-nav-link">
                            <i class="dripicons-gear"></i>
                            <span>Sistem</span>
                            <span class="menu-arrow"></span>
                        </a>

                        <ul class="side-nav-second-level collapse " aria-expanded="false">
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/roles">Peran</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/lookups">Lookup</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/settings">Konfigurasi</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/audittrails">Audit Trail</a>
                            </li>
                            <li class="">
                                <a href="http://localhost/codeigniter/index.php/crud/wilayah">Kode Wilayah</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- PAGE CONTAINER-->
            <div class="content-page">
                <div class="content">
                    <!-- BEGIN PlACE PAGE CONTENT HERE -->
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row ">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="page-title"> <i class="mdi  title_icon"></i>
                                                Pengguna
                                            </h4>
                                        </div> <!-- end card body-->
                                    </div> <!-- end card -->
                                </div><!-- end col-->
                            </div>
                        </div>
                    </div>


                    <style>
                    .btn-icon-circle {
                        display: inline-block;
                        width: 30px;
                        height: 30px;
                        border: 1px solid #909090;
                        border-radius: 50%;
                        margin: 0px 2px 2px;
                        /*space between*/
                        padding: 0px;
                        cursor: pointer;
                        box-shadow: 0px 0px 2px #dee2e6;
                        text-align: center;
                        position: relative;
                    }

                    .btn-icon-circle.small {
                        width: 25px;
                        height: 25px;
                    }

                    .btn-icon-circle .fa {
                        font-size: .75em !important;
                    }

                    .btn-icon-circle.active {
                        color: #fff;
                        background-color: var(--primary);
                    }

                    .btn-icon-circle i {
                        margin: auto;
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                    }

                    .btn-dropdown {
                        display: inline-grid;
                        height: 36px;
                        width: 36px;
                        margin: 0px 2px;
                    }

                    .btn-dropdown button {
                        display: inline-block;
                        border-width: 1px;
                    }

                    .btn-dropdown i {
                        padding: 0px;
                    }

                    /* .DTE_Body.modal-body .row.form-group {
    margin-bottom: 1rem;
}

.DTE_Body.modal-body .row.form-group:last-child {
    margin-bottom: 0.5rem;
} */

                    /* .DTE_Footer.modal-footer {
    padding-bottom: 0px;
} */

                    /* .DTE.modal-header .row.form-group:last-child {
    margin-bottom: 0.5rem;
} */

                    .DTE.modal-header .ans-close-wrp {
                        display: grid;
                    }

                    .ans-buttons {
                        margin-left: 5px;
                        float: right !important;
                    }

                    .dt-action-buttons {
                        margin-bottom: 0.5rem;
                    }

                    @media (max-width: 768px) {
                        div.dt-custom-actions {
                            margin-left: auto;
                            margin-right: auto;
                        }

                        .nav-tabs>li {
                            width: 100%;
                        }

                        .nav-tabs .nav-link.active {
                            color: #fff;
                            background-color: var(--primary);
                            border-color: var(--primary);
                            border-radius: .25rem;
                            margin-left: -1px;
                            margin-right: -1px;
                        }

                        .nav-tabs .nav-link {
                            border: 0px solid;
                            /* border-radius: .25rem;
        border-color: #dee2e6; */
                        }

                        .nav-tabs {
                            border: 1px solid #dee2e6;
                            border-radius: .25rem;
                        }

                        .card {
                            margin-bottom: 1rem;
                        }

                        .card-body {
                            padding: 1rem;
                        }

                        /* li.paginate_button {
        width: fit-content;
        display: inline-flex !important;
        justify-content: center;
        align-items: center;
        align-content: center;
    } */
                    }
                    </style>

                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card widget-inline">
                                        <div class="card-body">
                                            <div class="row" style="display: flex;">

                                                <div class="form-group col-md-4 mb-0 ">
                                                    <label>Peran</label>
                                                    <select id="f_role_id" name="role_id" class="form-control"
                                                        placeholder="Peran">
                                                        <option value=''>-- Peran --</option>
                                                        <option value="1">System Administrator</option>
                                                        <option value="2">Administrator</option>
                                                        <option value="3">Penatausahaan Barang OPD/UPB</option>
                                                        <option value="4">Pengurus Barang OPD/UPB</option>
                                                        <option value="99">Koordinator BPKAD</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4 mb-0 ">
                                                    <label>OPD</label>
                                                    <select id="f_opd" name="opd" class="form-control"
                                                        placeholder="OPD">
                                                        <option value=''>-- OPD --</option>
                                                        <option value="perkimlh">Dinas Perumahan dan Kawasan Permukiman
                                                            dan Lingkungan Hidup</option>
                                                        <option value="dinkes">Dinas Kesehatan</option>
                                                        <option value="disperindag">Dinas Perindustrian dan Perdagangan
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <button type="submit" class="btn btn-primary btn-block"
                                                        id='btn_crud_filter' name="button">Tampilkan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end card-box-->
                                </div> <!-- end col-->
                            </div>
                        </div>
                    </section>

                    <script type="text/javascript">
                    var v_role_id = "";

                    var v_opd = "";

                    $(document).ready(function() {
                        let _options = [];
                        let _attr = {};





                        $("#f_role_id").val(v_role_id);
                        $('#f_role_id').on('change', function() {
                            v_role_id = $("#f_role_id").val();
                        });

                        $("#f_opd").val(v_opd);
                        $('#f_opd').on('change', function() {
                            v_opd = $("#f_opd").val();
                        });

                        $('#btn_crud_filter').click(function(e) {
                            e.stopPropagation();
                            dt_tdata_20.ajax.reload();
                        });


                        $("#f_role_id").select2({
                            minimumResultsForSearch: 10,
                            minimumInputLength: 0,
                            //theme: "bootstrap",
                        });

                        $("#f_opd").select2({
                            minimumResultsForSearch: 10,
                            minimumInputLength: 0,
                            //theme: "bootstrap",
                        });
                    });
                    </script>


                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="table-responsive-sm">
                                                <table id="tdata_20" class="table table-striped dt-responsive nowrap"
                                                    width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" data-priority="1"
                                                                style="word-break: normal!important;">
                                                                Id
                                                            <th class="text-center" data-priority="10"
                                                                style="word-break: normal!important;">
                                                                Username
                                                            <th class="text-center" data-priority="10"
                                                                style="word-break: normal!important;">
                                                                <i class="dripicons-document-edit"></i> Peran
                                                            <th class="text-center" data-priority="11"
                                                                style="word-break: normal!important;">
                                                                Email
                                                            <th class="text-center" data-priority="12"
                                                                style="word-break: normal!important;">
                                                                <i class="dripicons-document-edit"></i> Nama
                                                            <th class="text-center" data-priority="13"
                                                                style="word-break: normal!important;">
                                                                Handphone
                                                            <th class="none text-center" data-priority="-1"
                                                                style="word-break: normal!important;">
                                                                OPD
                                                            <th class="none text-center" data-priority="-1"
                                                                style="word-break: normal!important;">
                                                                <i class="dripicons-document-edit"></i> Alamat
                                                            <th class="text-center" data-priority="1"></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>




                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <script type="text/javascript" defer>
                    var base_url = "http://localhost/codeigniter/";
                    var site_url = "http://localhost/codeigniter/index.php/";
                    var ajax_url = "http://localhost/codeigniter/index.php/crud/users/json";

                    var editor_tdata_20 = null;
                    var dt_tdata_20 = null;

                    $(document).ready(function() {
                        $.fn.dataTable.ext.errMode = 'throw';
                        $.extend($.fn.dataTable.defaults, {
                            responsive: true
                        });

                        editor_tdata_20 = new $.fn.dataTable.Editor({
                            ajax: "http://localhost/codeigniter/index.php/crud/users/json",
                            table: "#tdata_20",
                            idSrc: "user_id",
                            fields: [{
                                    label: "Username <span class='text-danger font-weight-bold'>*</span>",
                                    name: "user_name",
                                    type: 'text',
                                    options: [],
                                },
                                {
                                    label: "Peran <span class='text-danger font-weight-bold'>*</span>",
                                    name: "role_id",
                                    type: 'select',
                                    options: [{
                                            label: "System Administrator",
                                            value: "1"
                                        },
                                        {
                                            label: "Administrator",
                                            value: "2"
                                        },
                                        {
                                            label: "Penatausahaan Barang OPD/UPB",
                                            value: "3"
                                        },
                                        {
                                            label: "Pengurus Barang OPD/UPB",
                                            value: "4"
                                        },
                                        {
                                            label: "Koordinator BPKAD",
                                            value: "99"
                                        },
                                    ],
                                },
                                {
                                    label: "Email <span class='text-danger font-weight-bold'>*</span>",
                                    name: "email",
                                    type: 'text',
                                    options: [],
                                },
                                {
                                    label: "Nama <span class='text-danger font-weight-bold'>*</span>",
                                    name: "nama",
                                    type: 'text',
                                    options: [],
                                },
                                {
                                    label: "Handphone ",
                                    name: "handphone",
                                    type: 'text',
                                    options: [],
                                },
                                {
                                    label: "OPD ",
                                    name: "opd",
                                    type: 'select',
                                    options: [{
                                            label: "Dinas Perumahan dan Kawasan Permukiman dan Lingkungan Hidup",
                                            value: "perkimlh"
                                        },
                                        {
                                            label: "Dinas Kesehatan",
                                            value: "dinkes"
                                        },
                                        {
                                            label: "Dinas Perindustrian dan Perdagangan",
                                            value: "disperindag"
                                        },
                                    ],
                                },
                                {
                                    label: "Alamat ",
                                    name: "address",
                                    type: 'text',
                                    options: [],
                                },
                            ],
                            i18n: {
                                create: {
                                    button: "Baru",
                                    title: "Buat Users",
                                    submit: "Simpan"
                                },
                                edit: {
                                    button: "Ubah",
                                    title: "Ubah Users",
                                    submit: "Simpan"
                                },
                                remove: {
                                    button: "Hapus",
                                    title: "Hapus Users",
                                    submit: "Hapus",
                                    confirm: {
                                        _: "Konfirmasi menghapus %d Users?",
                                        1: "Konfirmasi menghapus 1 Users?"
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

                        editor_tdata_20.on('preSubmit', function(e, o, action) {
                            if (action === 'create' || action === 'edit') {
                                let field = null;

                                field = this.field('user_name');
                                if (!field.isMultiValue()) {
                                    if (!field.val() || field.val() == 0) {
                                        field.error('Harus diisi');
                                    }
                                }
                                field = this.field('role_id');
                                if (!field.isMultiValue()) {
                                    if (!field.val() || field.val() == 0) {
                                        field.error('Harus diisi');
                                    }
                                }
                                field = this.field('email');
                                if (!field.isMultiValue()) {
                                    if (!field.val() || field.val() == 0) {
                                        field.error('Harus diisi');
                                    }
                                }
                                field = this.field('nama');
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
                                    o.data[key].user_id = key;
                                });
                            }

                            /* set the hidden js field */

                        });

                        editor_tdata_20.on('postSubmit', function(e, json, data, action, xhr) {
                            if (action == "upload") {

                            }

                        });


                        /* Activate the bubble editor on click of a table cell */
                        $('#tdata_20').on('click', 'tbody td.editable', function(e) {
                            editor_tdata_20.bubble(this);
                        });

                        // /* Inline editing in responsive cell */
                        // $('#tdata_20').on( 'click', 'tbody ul.dtr-details li', function (e) {
                        //     /* Ignore the Responsive control and checkbox columns */
                        //     if ( $(this).hasClass( 'control' ) || $(this).hasClass('select-checkbox') ) {
                        //         return;
                        //     }

                        //     /* ignore read-only column */
                        //     var colnum = $(this).attr( 'data-dt-column' );
                        //     if ( colnum == 1 ) {
                        //         return;
                        //     }

                        //     /* Edit the value, but this method allows clicking on label as well */
                        //     editor_tdata_20.bubble( $('span.dtr-data', this) );
                        // });

                        dt_tdata_20 = $('#tdata_20').DataTable({
                            "processing": true,
                            "responsive": true,
                            "serverSide": false,
                            "pageLength": 25,
                            "lengthMenu": [
                                [25, 50, 100, 200, -1],
                                [25, 50, 100, 200, "All"]
                            ],
                            "paging": true,
                            "pagingType": "numbers",
                            dom: "<'row'<'col-sm-12 col-md-7 dt-action-buttons'B><'col-sm-12 col-md-5'fr>>t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
                            buttons: {
                            },
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
                            "ajax": {
                                "url": "http://localhost/codeigniter/index.php/crud/users/json",
                                "dataType": "json",
                                "type": "POST",
                                "data": function(d) {
                                    d.f_role_id = v_role_id;
                                    d.f_opd = v_opd;
                                    return d;
                                }
                            },

                            "columns": [{
                                    data: "user_id",
                                    editField: "user_id",
                                    className: "col_tcg_readonly  ",
                                },
                                {
                                    data: "user_name",
                                    editField: "user_name",
                                    className: "col_tcg_text  ",
                                },
                                {
                                    data: "role_id_label",
                                    editField: "role_id",
                                    className: "col_tcg_select2  editable",
                                    render: function(data, type, row) {
                                        // if (type == "export") {
                                        //     //export raw data?
                                        // }
                                        return data;
                                    }
                                },
                                {
                                    data: "email",
                                    editField: "email",
                                    className: "col_tcg_text  ",
                                },
                                {
                                    data: "nama",
                                    editField: "nama",
                                    className: "col_tcg_text  editable",
                                },
                                {
                                    data: "handphone",
                                    editField: "handphone",
                                    className: "col_tcg_text  ",
                                },
                                {
                                    data: "opd_label",
                                    editField: "opd",
                                    className: "col_tcg_select2  ",
                                    render: function(data, type, row) {
                                        // if (type == "export") {
                                        //     //export raw data?
                                        // }
                                        return data;
                                    }
                                },
                                {
                                    data: "address",
                                    editField: "address",
                                    className: "col_tcg_textarea  editable",
                                },
                                {
                                    data: null,
                                    className: 'text-right inline-flex text-nowrap',
                                    "orderable": false,
                                    render: function(data, type, row, meta) {
                                        if (type != 'display') {
                                            return "";
                                        }

                                        let str = '';


                                        str +=
                                            "<button href='#' onclick='event.stopPropagation(); dt_tdata_20_edit_row(" +
                                            meta.row + ", dt_tdata_20);' data-tag='" + meta
                                            .row +
                                            "' class='btn btn-icon-circle btn-info'><i class='fa fa-edit fas'></i></button>"

                                        str +=
                                            "<button href='#' onclick='event.stopPropagation(); dt_tdata_20_delete_row(" +
                                            meta.row + ", dt_tdata_20);' data-tag='" + meta
                                            .row +
                                            "' class='btn btn-icon-circle btn-danger'><i class='fa fa-trash fas'></i></button>"



                                        return str;
                                    }
                                },
                            ],
                            "columnDefs": [
                                {
                                    targets: [0],
                                    orderable: false
                                }
                            ],
                           "createdRow": function(row, data, index) {
                                if ($('ul.dropdown-menu span', row).length == 0) {
                                    $('.btn-dropdown', row).addClass('d-none')
                                }
                            }
                        });

                        dt_tdata_20.on("user-select.dt", function(e, dt, type, cell, originalEvent) {
                            var $elem = $(originalEvent.target); // get element clicked on
                            var tag = $elem[0].nodeName.toLowerCase(); // get element's tag name

                            if (!$elem.closest("div.dt-row-actions").length) {
                                return; // ignore any element not in the dropdown
                            }

                            if (tag === "i" || tag === "a" || tag === "button") {
                                return false; // cancel the select event for the row
                            }
                        });

                        // dt_tdata_20.on( 'column-sizing.dt', function ( e, settings ) {
                        //     //dt_tdata_20.columns.adjust().responsive.recalc();
                        //     console.log( 'Column width recalculated in table' );
                        // });

                    });

                    var dt_tdata_20_initialized = false;

                    function dt_tdata_20_ajax_load(data) {
                        return new Promise(function(resolve, reject) {

                            $.ajax({
                                "url": "http://localhost/codeigniter/index.php/crud/users/json",
                                "dataType": "json",
                                "type": "POST",
                                "data": {
                                    f_role_id: $("#f_role_id").val(),
                                    f_opd: $("#f_opd").val(),
                                },
                                beforeSend: function(request) {
                                    request.setRequestHeader("Content-Type",
                                        "application/x-www-form-urlencoded; charset=UTF-8");
                                },
                                success: function(response) {
                                    let data = [];
                                    if (response.data === null) {
                                        alert("Gagal mengambil data via ajax");
                                        data = [];
                                    } else if (typeof response.error !== 'undefined' && response
                                        .error !== null &&
                                        response
                                        .error != "") {
                                        alert(response.error);
                                        data = [];
                                    } else {
                                        data = response.data;
                                    }

                                    resolve({
                                        data: data,
                                    });
                                },
                                error: function(jqXhr, textStatus, errorMessage) {
                                    alert("Gagal mengambil data via ajax");
                                    resolve({
                                        data: [],
                                    });
                                }
                            });
                        });
                    }

                    function dt_tdata_20_edit_row(row_id, dt) {
                        let row = dt.row(row_id);

                        editor_tdata_20
                            .title("Ubah Users")
                            .buttons([{
                                label: "Simpan",
                                className: "btn-primary",
                                fn: function() {
                                    this.submit();
                                }
                            }, ])
                            .edit(row.index(), {
                                submit: 'changed'
                            });

                        // row.edit( {
                        //     editor: editor_tdata_20,
                        //     buttons: [
                        //         { label: "Save", className: "btn-primary", fn: function () { this.submit(); } },
                        //     ]
                        // }, false );

                        return;
                    }

                    function dt_tdata_20_delete_row(row_id, dt) {
                        let row = dt.row(row_id);

                        editor_tdata_20
                            .title("Hapus Users")
                            .buttons([{
                                label: "Hapus",
                                className: "btn-danger",
                                fn: function() {
                                    this.submit();
                                }
                            }, ])
                            .message("Konfirmasi menghapus 1 Users?")
                            .remove(row.index(), true);

                        // row.delete( {
                        //     buttons: [
                        //         { label: "Delete", className: "btn-danger", fn: function () { this.submit(); } },
                        //     ]
                        // } );
                    }

                    function dt_tdata_20_import(e, dt, node, conf) {
                        $.confirm({
                            columnClass: 'medium',
                            title: 'Impor Users',
                            content: '' +
                                '<form action="" class="formName">' +
                                '<div class="form-group">' +
                                '<input id="upload" type="file" name="import" accept=".xlsx, .xls, .csv" style="width: 100%;" />' +
                                '<div id="error" class="d-none text-danger mt-2"></div>' +
                                '<div class="d-none text-center justify-content-center" id="loading" style="position: absolute; width: 100%; top: 0px;">' +
                                '  <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>' +
                                '</div>' +
                                '</div>' +
                                '</form>',
                            buttons: {
                                cancel: function() {
                                    //close
                                },
                                formSubmit: {
                                    text: 'Impor',
                                    btnClass: 'btn-primary',
                                    action: function() {
                                        let that = this;

                                        //upload the file
                                        let upload = that.$content.find('#upload');
                                        if (upload[0].files.length == 0) {
                                            let message = that.$content.find('#error');
                                            message.html("Belum memilih file");
                                            message.removeClass("d-none");
                                            return false;
                                        }
                                        let file = upload[0].files[0];

                                        let spinner = that.$content.find('#spinner');

                                        // add assoc key values, this will be posts values
                                        var formData = new FormData();
                                        formData.append("upload", file, file.name);
                                        formData.append("action", "import");

                                        spinner.removeClass('d-none');
                                        $.ajax({
                                            type: "POST",
                                            url: "http://localhost/codeigniter/index.php/crud/users/json",
                                            async: true,
                                            data: formData,
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            timeout: 60000,
                                            dataType: 'json',
                                            success: function(json) {
                                                if (typeof json.error !== 'undefined' && json
                                                    .error != "" && json.error != null) {
                                                    let message = that.$content.find('#error');
                                                    message.html(json.error);
                                                    message.removeClass("d-none");
                                                    //hide spinner
                                                    spinner.addClass('d-none');
                                                    return;
                                                }

                                                //hide spinner
                                                spinner.addClass('d-none');

                                                dt.ajax.reload();
                                                that.close();
                                            },
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                let message = that.$content.find('#error');
                                                message.html("Gagal mengimpor file: " +
                                                    textStatus);
                                                message.removeClass("d-none");
                                                //hide spinner
                                                spinner.addClass('d-none');
                                                return;
                                            }
                                        });

                                        //wait for completion of ajax
                                        return false;
                                    }
                                },
                            },
                            onContentReady: function() {
                                // bind to events
                                var that = this;
                                this.$content.find('form').on('submit', function(e) {
                                    // if the user submits the form by pressing enter in the field.
                                    e.preventDefault();
                                    that.$$formSubmit.trigger(
                                    'click'); // reference the button and click it
                                });
                                this.$content.find('#upload').on('change', function(e) {
                                    let message = that.$content.find('#error');
                                    message.html("");
                                    message.addClass("d-none");
                                });
                            }
                        });
                    }

                    function toColumnName(num) {
                        for (var ret = '', a = 1, b = 26;
                            (num -= a) >= 0; a = b, b *= 26) {
                            ret = String.fromCharCode(parseInt((num % b) / a) + 65) + ret;
                        }
                        return ret;
                    }
                    </script>



                    <!-- END PLACE PAGE CONTENT HERE -->
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
    </div>

    <!-- all the js files -->
    <!-- bootstrap. bundle includes popper.js -->
    <script src="http://localhost/codeigniter/assets/bootstrap/js/bootstrap.min.js" defer></script>

    <script src="http://localhost/codeigniter/assets/select2/js/select2.min.js"></script>

    <!-- datatables -->
    <script src="http://localhost/codeigniter/assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js" defer>
    </script>
    <script src="http://localhost/codeigniter/assets/datatables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js"
        defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js" defer>
    </script>
    <script src="http://localhost/codeigniter/assets/datatables/Responsive-2.2.3/js/responsive.bootstrap4.min.js" defer>
    </script>

    <script src="http://localhost/codeigniter/assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js" defer>
    </script>
    <script src="http://localhost/codeigniter/assets/datatables/Editor-1.9.2/js/editor.bootstrap4.min.js" defer>
    </script>

</body>

</html>