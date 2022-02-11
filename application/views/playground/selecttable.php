<!DOCTYPE html>
<html>
<head>
    <title>Hasil Penilaian | TCG Framework</title>
    <!-- all the meta tags -->
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- all the css files -->
    <link rel="shortcut icon" href="http://localhost/codeigniter/assets/image/icon.ico">
    <!-- <link rel="shortcut icon" href="http://localhost/academy/uploads/system/favicon.png"> -->
    <!-- bootstrap -->
    <link href="http://localhost/codeigniter/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- leaflet -->
    <link href="http://localhost/codeigniter/assets/leaflet/leaflet/leaflet.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/leaflet/esri/esri-leaflet-geocoder.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/leaflet/fullscreen/leaflet.fullscreen.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/leaflet/easybutton/easy-button.css" rel="stylesheet" />
    <!-- select2 -->
    <link href="http://localhost/codeigniter/assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/assets/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- datatables -->
    <link href="http://localhost/codeigniter/assets/datatables/DataTables-1.10.20/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/assets/datatables/Responsive-2.2.3/css/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/assets/datatables/Buttons-1.6.1/css/buttons.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/assets/datatables/Select-1.3.1/css/select.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/assets/datatables/KeyTable-2.5.1/css/keyTable.bootstrap4.css" rel="stylesheet" type="text/css">

    <link href="http://localhost/codeigniter/assets/datatables/Editor-2.0.6/css/editor.bootstrap4.css" rel="stylesheet" type="text/css">

    <link href="http://localhost/codeigniter/assets/datatables/RowReorder-1.2.6/css/rowReorder.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="http://localhost/codeigniter/assets/datatables/SearchBuilder-1.3.0/css/searchBuilder.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="http://localhost/codeigniter/assets/datatables/SearchPanes-1.4.0/css/searchPanes.bootstrap4.css" rel="stylesheet" type="text/css">
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-select2.bootstrap4.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-mask.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-toggle.bootstrap4.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-checkbox.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-cascade.bootstrap4.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-geolocation.bootstrap4.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-unitprice.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-table.bootstrap4.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-plugin-rowgroup.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-text.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-number.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-readonly.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-date.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-textarea.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-editor.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/dropzone/dropzone.min.css" rel="stylesheet" />
    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-upload.bootstrap4.css" rel="stylesheet" />

    <link href="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-table-select.css" rel="stylesheet" />

    <!-- WYSIWYG editor -->
    <!-- <link href="http://localhost/codeigniter/assets/backend/css/vendor/summernote-bs4.css" rel="stylesheet" type="text/css" /> -->
    <!-- full calendar -->
    <link href="http://localhost/codeigniter/assets/fullcalendar/core/main.min.css" rel="stylesheet" type="text/css" />
    <!-- dropzone file upload -->
    <link href="http://localhost/codeigniter/assets/dropzone/dropzone.min.css" rel="stylesheet" type="text/css" />
    <!-- dragula drag-n-drop component -->
    <link href="http://localhost/codeigniter/assets/dragula/dragula.min.css" rel="stylesheet" type="text/css" />
    <!-- toastr toast popup -->
    <link href="http://localhost/codeigniter/assets/jquery-confirm/jquery-confirm.min.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/assets/toastr/toastr.min.css" rel="stylesheet" type="text/css" />
    <!-- icons -->
    <link href="http://localhost/codeigniter/assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />
    <!-- <link href="http://localhost/codeigniter/assets/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css" /> -->
    <link href="http://localhost/codeigniter/assets/dripicons/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- many of the 3rd-party library (including datatables) require older version of materialdesign icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.4.93/css/materialdesignicons.min.css" integrity="sha512-9hrcuHFRJBsyfJiotGL1U+zraOkuI5fzlo0X0C8s7gkkgV1wLkmiP1JbUjVAws4Wo8FcSK82Goj64vT8ERocgg==" crossorigin="anonymous" />
    <!-- materialdesignicon v5 -->
    <!-- <link href="http://localhost/codeigniter/assets/materialdesignicons/css/icons.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- utilities -->
    <link href="http://localhost/codeigniter/assets/utilities.css" rel="stylesheet" type="text/css" />
    <!-- jquery plugins -->
    <link href="http://localhost/codeigniter/assets/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/assets/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
    <!-- jquery js. must be loaded first before other js -->
    <script src="http://localhost/codeigniter/assets/jquery-3.4.1/jquery.js"></script>
    <!-- using jquery-3.6.0 screw up bubble editor layout! -->
    <!-- <script src="http://localhost/codeigniter/assets/jquery/jquery-3.6.0.min.js"></script> -->
    <!-- App css -->
    <link href="http://localhost/codeigniter/themes/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/themes/adminlte/dist/css/adminlte.min.css" rel="stylesheet" type="text/css" />
    <link href="http://localhost/codeigniter/themes/adminlte/app.css" rel="stylesheet" type="text/css" />
    <!-- <link href="http://localhost/codeigniter/themes/adminlte/css/main.css" rel="stylesheet" type="text/css" /> -->
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
    <!-- page header -->
</head>
<body class="sidebar-mini layout-fixed control-sidebar-open" style="height: auto;">
    <div class="wrapper">
        <!-- HEADER -->
        <!-- Topbar Start -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" id="tcg-navbar-toggler"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <div class="nav-link page-title d-md-none"><b>TCG</b></div>
                    <div class="nav-link page-title d-none d-md-block"><b>TCG Framework</b></div>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown user user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <img src="http://localhost/codeigniter/assets/image/user.png" class="user-image img-circle elevation-2" alt="User Image" style="width: "></a>
                    <ul class="dropdown-menu dropdown-menu dropdown-menu-right" style="width:250px; z-index:1035">
                        <li class="user-header bg-primary">
                            <img src="http://localhost/codeigniter/assets/image/user.png" class="img-circle elevation-2" alt="User Image">
                            <p>
                                Super Administrator <small>System Administrator</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left" style="display: inline-block;">
                                <a href="http://localhost/codeigniter/index.php/crud/profile" class="btn btn-default btn-flat">Profil</a>
                            </div>
                            <div class="pull-right" style="display: inline-block; float: right;">
                                <a href="http://localhost/codeigniter/index.php/auth/logout" class="btn btn-default btn-flat">Logout</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- end Topbar -->
        <aside class="main-sidebar elevation-4 sidebar-light-info">
            <a href="#!" class="brand-link">
                <img src="http://localhost/codeigniter/assets/image/logo.jpg" alt="TCG Framework" class="brand-image elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">TCG</span>
            </a>
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="http://localhost/codeigniter/assets/image/user.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Super Administrator</a>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class  with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="http://localhost/codeigniter/index.php/user/penilaian" class="nav-link active">
                                <i class="dripicons-disc"></i>
                                <p>Hasil Penilaian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="http://localhost/codeigniter/index.php/user/ringkasan_penilaian" class="nav-link ">
                                <i class="dripicons-disc"></i>
                                <p>Ringkasan Penilaian</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview ">
                            <a href="javascript: void(0);" class="nav-link ">
                                <i class="dripicons-network-2"></i>
                                <p>Workflow
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/workflow" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Konfigurasi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/workflow_instance" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Workflow</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview ">
                            <a href="javascript: void(0);" class="nav-link ">
                                <i class="dripicons-device-mobile"></i>
                                <p>SMS
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/sms_inbox" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Inbox</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/sms_outbox" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Outbox</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">Administrasi</li>
                        <li class="nav-item">
                            <a href="http://localhost/codeigniter/index.php/user/users" class="nav-link ">
                                <i class="dripicons-user-group"></i>
                                <p>Pengguna</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview ">
                            <a href="javascript: void(0);" class="nav-link ">
                                <i class="dripicons-stack"></i>
                                <p>CRUD
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/pages" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Halaman</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/permissions" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Hak Akses</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/navigations" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Navigasi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/tables" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Tabel</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview ">
                            <a href="javascript: void(0);" class="nav-link ">
                                <i class="dripicons-web"></i>
                                <p>REST API
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/api_keys" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>API Keys</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview ">
                            <a href="javascript: void(0);" class="nav-link ">
                                <i class="dripicons-view-thumb"></i>
                                <p>Master Data
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>OPD</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>UPD</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Merek</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Model</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Kode Barang</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Kondisi Kendaraan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Asal Usul Kendaraan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Tipe Penyusutan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Tipe Masalah</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Status Perbaikan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Peruntukan</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview ">
                            <a href="javascript: void(0);" class="nav-link ">
                                <i class="dripicons-gear"></i>
                                <p>Sistem
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/roles" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Peran</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/lookups" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Lookup</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/settings" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Konfigurasi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/audittrails" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Audit Trail</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="http://localhost/codeigniter/index.php/user/wilayah" class="nav-link "><i class="far fa-circle nav-icon"></i>
                                        <p>Kode Wilayah</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
        </aside>
        <div class="content-wrapper" style="min-height: 644px;">
            <!-- BEGIN PlACE PAGE CONTENT HERE -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row ">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="page-title"> <i class="mdi  title_icon"></i>
                                        Hasil Penilaian
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
            </style>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card widget-inline">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6 mb-0 mt-1 ">
                                            <label>Wilayah</label>
                                            <select id="f_wilayah" name="wilayah" class="form-control" placeholder="Wilayah">
                                                <option value=''>-- Wilayah --</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 mb-0 mt-1 ">
                                            <label>Kategori</label>
                                            <select id="f_kategori" name="kategori" class="form-control" placeholder="Kategori">
                                                <option value=''>-- Kategori --</option>
                                                <option value="1">Proaktif</option>
                                                <option value="2">Reguler</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 mb-0 mt-1 ">
                                            <label>Suku</label>
                                            <select id="f_suku" name="suku" class="form-control" placeholder="Suku">
                                                <option value=''>-- Suku --</option>
                                                <option value="1">Jawa</option>
                                                <option value="2">Sunda</option>
                                                <option value="3">Ambon</option>
                                                <option value="4">Bali</option>
                                                <option value="5">Banjar</option>
                                                <option value="6">Bugis</option>
                                                <option value="7">Batak</option>
                                                <option value="8">Dayak</option>
                                                <option value="9">Minang</option>
                                                <option value="10">Papua</option>
                                                <option value="11">Tionghoa</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 mb-0 mt-1 ">
                                            <label>Khusus</label>
                                            <select id="f_khusus" name="khusus" class="form-control" placeholder="Khusus">
                                                <option value=''>-- Khusus --</option>
                                                <option value="1">PRESTASI</option>
                                                <option value="2">ATLET</option>
                                                <option value="3">PEJ</option>
                                                <option value="4">KB</option>
                                                <option value="5">PAPUA</option>
                                                <option value="6">NA</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-primary btn-block" id='btn_crud_filter' name="button">Tampilkan</button>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-box-->
                        </div> <!-- end col-->
                    </div>
                </div>
            </section>
            <script type="text/javascript">
                var v_wilayah = "";
                var v_kategori = "";
                var v_suku = "";
                var v_khusus = "";
                $('.adv-search-btn').click(function(e) {
                    $('.adv-search-box').toggle();
                });
                $('.btn-search').click(function(e) {
                    e.stopPropagation();
                    dt_tdata_70.ajax.reload();
                });
                $(document).ready(function() {
                    let _options = [];
                    let _attr = {};
                    let _multiple = false;
                    let _minimumResult = 10;
                    //default value
                    _multiple = false;
                    _minimumResult = 10;
                    _multiple = _multiple;
                    _minimumResult = _minimumResult
                    _attr = {
                        multiple: _multiple,
                        minimumResultsForSearch: _minimumResult,
                    };
                    //rebuild as select2
                    select2_rebuild($('#f_wilayah'), _attr, null);
                    //default value
                    _multiple = false;
                    _minimumResult = 10;
                    _multiple = _multiple;
                    _minimumResult = _minimumResult
                    _attr = {
                        multiple: _multiple,
                        minimumResultsForSearch: _minimumResult,
                    };
                    //rebuild as select2
                    select2_rebuild($('#f_kategori'), _attr, null);
                    //default value
                    _multiple = false;
                    _minimumResult = 10;
                    _multiple = _multiple;
                    _minimumResult = _minimumResult
                    _attr = {
                        multiple: _multiple,
                        minimumResultsForSearch: _minimumResult,
                    };
                    //rebuild as select2
                    select2_rebuild($('#f_suku'), _attr, null);
                    //default value
                    _multiple = false;
                    _minimumResult = 10;
                    _multiple = _multiple;
                    _minimumResult = _minimumResult
                    _attr = {
                        multiple: _multiple,
                        minimumResultsForSearch: _minimumResult,
                    };
                    //rebuild as select2
                    select2_rebuild($('#f_khusus'), _attr, null);
                    $("#f_wilayah").val(v_wilayah);
                    $('#f_wilayah').on('change', function() {
                        v_wilayah = $("#f_wilayah").val();
                    });
                    $("#f_kategori").val(v_kategori);
                    $('#f_kategori').on('change', function() {
                        v_kategori = $("#f_kategori").val();
                    });
                    $("#f_suku").val(v_suku);
                    $('#f_suku').on('change', function() {
                        v_suku = $("#f_suku").val();
                    });
                    $("#f_khusus").val(v_khusus);
                    $('#f_khusus').on('change', function() {
                        v_khusus = $("#f_khusus").val();
                    });
                    $('#btn_crud_filter').click(function(e) {
                        e.stopPropagation();
                        dt_tdata_70.ajax.reload();
                    });
                });
            </script>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <style>
                                    </style>
                                    <div class="table-responsive-sm">
                                        <table id="tdata_70" class="table table-striped dt-responsive nowrap" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Id
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Nik
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Nama
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        No Peserta
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Nilai SKD
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Nilai Kes
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Nilai Psi
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Nilai Jas
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Nilai MI
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Nilai Akhir
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Kemampuan
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Wilayah
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Provinsi
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Kabupaten Kota
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Kategori
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Umur
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Agama
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Suku
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Khusus
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Photo
                                                    </th>
                                                    <th class="text-center" data-priority="100" style="word-break: normal!important;">
                                                        Lulus
                                                    </th>
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
                var ajax_url = "http://localhost/codeigniter/index.php/user/penilaian/json";
                var level1_name = "";
                var level1_id = "";
                var editor_tdata_70 = null;
                var dt_tdata_70 = null;
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

                    editor_tdata_70 = new $.fn.dataTable.Editor({
                        ajax: "http://localhost/codeigniter/index.php/user/penilaian/json",
                        table: "#tdata_70",
                        idSrc: "_key_",
                        fields: [{
                                label: "Id ",
                                name: "_key_",
                                type: 'tcg_readonly',
                                options: [],
                                readonly: 1,
                            },
                            {
                                label: "Nik ",
                                name: "nik",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "Nama ",
                                name: "nama",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "No Peserta ",
                                name: "no_peserta",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "Nilai SKD ",
                                name: "nilai_skd",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "Nilai Kes ",
                                name: "nilai_kes",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "Nilai Psi ",
                                name: "nilai_psi",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "Nilai Jas ",
                                name: "nilai_jas",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "Nilai MI ",
                                name: "nilai_mi",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "Nilai Akhir ",
                                name: "nilai_akhir",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "Kemampuan ",
                                name: "kemampuan",
                                type: 'tcg_select2',
                                options: [{
                                        label: "Akademik",
                                        value: "1"
                                    },
                                    {
                                        label: "Bahasa Asing",
                                        value: "2"
                                    },
                                    {
                                        label: "Bela Diri",
                                        value: "3"
                                    },
                                    {
                                        label: "Komputer",
                                        value: "4"
                                    },
                                    {
                                        label: "Olah Raga",
                                        value: "5"
                                    },
                                    {
                                        label: "Seni",
                                        value: "6"
                                    },
                                ],
                            },
                            {
                                label: "Wilayah ",
                                name: "wilayah",
                                type: 'tcg_select2',
                                options: [],
                            },
                            {
                                label: "Provinsi ",
                                name: "provinsi",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "Kabupaten Kota ",
                                name: "kabupaten_kota",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "Kategori ",
                                name: "kategori",
                                type: 'tcg_select2',
                                options: [{
                                        label: "Proaktif",
                                        value: "1"
                                    },
                                    {
                                        label: "Reguler",
                                        value: "2"
                                    },
                                ],
                            },
                            {
                                label: "Umur ",
                                name: "umur",
                                type: 'tcg_text',
                                options: [],
                            },
                            // {
                            //     label: "Site:",
                            //     name: "agama",
                            //     type: "datatable",
                            //     optionsPair: {
                            //         value: 'id',
                            //         label: 'agama'
                            //     },
                            //     config: {
                            //         ajax: "http://localhost/codeigniter/index.php/sistem/lookup_agama/json",
                            //         responsive: true,
                            //         columns: [
                            //             {
                            //                 title: 'ID',
                            //                 data: 'id'
                            //             },
                            //             {
                            //                 title: 'Value',
                            //                 data: 'agama'
                            //             },
                            //             {
                            //                 title: 'ID',
                            //                 data: 'id'
                            //             },
                            //             {
                            //                 title: 'Value',
                            //                 data: 'agama'
                            //             },
                            //             {
                            //                 title: 'ID',
                            //                 data: 'id'
                            //             },
                            //             {
                            //                 title: 'Value',
                            //                 data: 'agama'
                            //             },
                            //             {
                            //                 title: 'ID',
                            //                 data: 'id'
                            //             },
                            //             {
                            //                 title: 'Value',
                            //                 data: 'agama'
                            //             },
                            //              {
                            //                 title: 'ID',
                            //                 data: 'id'
                            //             },
                            //             {
                            //                 title: 'Value',
                            //                 data: 'agama'
                            //             }
                            //         ]
                            //     }
                            // },
                            {
                                label: "Agama ",
                                name: "agama",
                                type: 'tcg_select2',
                                options: [{
                                        label: "Islam",
                                        value: "1"
                                    },
                                    {
                                        label: "Kristen",
                                        value: "2"
                                    },
                                    {
                                        label: "Katholik",
                                        value: "3"
                                    },
                                    {
                                        label: "Hindu",
                                        value: "4"
                                    },
                                    {
                                        label: "Bunda",
                                        value: "5"
                                    },
                                    {
                                        label: "Lainnya",
                                        value: "6"
                                    },
                                ],
                            },
                            {
                                label: "Suku ",
                                name: "suku",
                                type: 'tcg_table_select',
                                optionsPair: {
                                    value: 'id',
                                    label: 'suku'
                                },
                                config: {
                                    ajax: "http://localhost/codeigniter/index.php/sistem/lookup_suku/json",
                                    responsive: true,
                                    columns: [
                                        {
                                            title: 'ID',
                                            data: 'id'
                                        },
                                        {
                                            title: 'Value',
                                            data: 'suku'
                                        },
                                        {
                                            title: 'ID',
                                            data: 'id'
                                        },
                                        {
                                            title: 'Value',
                                            data: 'suku'
                                        },
                                        {
                                            title: 'ID',
                                            data: 'id'
                                        },
                                        {
                                            title: 'Value',
                                            data: 'suku'
                                        },
                                        {
                                            title: 'ID',
                                            data: 'id'
                                        },
                                        {
                                            title: 'Value',
                                            data: 'suku'
                                        },
                                         {
                                            title: 'ID',
                                            data: 'id'
                                        },
                                        {
                                            title: 'Value',
                                            data: 'suku'
                                        }
                                    ]
                                }
                            // },
                            // {
                            //     label: "Suku ",
                            //     name: "suku",
                            //     type: 'tcg_select2',
                            //     options: [{
                            //             label: "Jawa",
                            //             value: "1"
                            //         },
                            //         {
                            //             label: "Sunda",
                            //             value: "2"
                            //         },
                            //         {
                            //             label: "Ambon",
                            //             value: "3"
                            //         },
                            //         {
                            //             label: "Bali",
                            //             value: "4"
                            //         },
                            //         {
                            //             label: "Banjar",
                            //             value: "5"
                            //         },
                            //         {
                            //             label: "Bugis",
                            //             value: "6"
                            //         },
                            //         {
                            //             label: "Batak",
                            //             value: "7"
                            //         },
                            //         {
                            //             label: "Dayak",
                            //             value: "8"
                            //         },
                            //         {
                            //             label: "Minang",
                            //             value: "9"
                            //         },
                            //         {
                            //             label: "Papua",
                            //             value: "10"
                            //         },
                            //         {
                            //             label: "Tionghoa",
                            //             value: "11"
                            //         },
                            //     ],
                            },
                            {
                                label: "Khusus ",
                                name: "khusus",
                                type: 'tcg_select2',
                                options: [{
                                        label: "PRESTASI",
                                        value: "1"
                                    },
                                    {
                                        label: "ATLET",
                                        value: "2"
                                    },
                                    {
                                        label: "PEJ",
                                        value: "3"
                                    },
                                    {
                                        label: "KB",
                                        value: "4"
                                    },
                                    {
                                        label: "PAPUA",
                                        value: "5"
                                    },
                                    {
                                        label: "NA",
                                        value: "6"
                                    },
                                ],
                            },
                            {
                                label: "Photo ",
                                name: "photo",
                                type: 'tcg_text',
                                options: [],
                            },
                            {
                                label: "Lulus ",
                                name: "lulus",
                                type: 'tcg_toggle',
                                options: [],
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
                                title: "Buat Penilaian",
                                submit: "Simpan"
                            },
                            edit: {
                                button: "Ubah",
                                title: "Ubah Penilaian",
                                submit: "Simpan"
                            },
                            remove: {
                                button: "Hapus",
                                title: "Hapus Penilaian",
                                submit: "Hapus",
                                confirm: {
                                    _: "Konfirmasi menghapus %d Penilaian?",
                                    1: "Konfirmasi menghapus 1 Penilaian?"
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
                    editor_tdata_70.on('open', function(e, type) {
                        let data = this.s.editData;
                        let url = '';
                        let col = '';
                        let val = '';
                    });
                    editor_tdata_70.on('preSubmit', function(e, o, action) {
                        if (action === 'create' || action === 'edit') {
                            let field = null;
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
                                o.data[key]._key_ = key;
                            });
                        }
                        /* set the hidden js field */
                        /* level1 hidden field */
                    });
                    editor_tdata_70.on('postSubmit', function(e, json, data, action, xhr) {
                    });

                    $(editor_tdata_70.field('agama').node()).on('change', function() {
                        let val = editor_tdata_70.field('agama').val();
                        editor_tdata_70.field('suku').reload("http://localhost/codeigniter/index.php/sistem/lookup_suku/json?" + val);
                    });

                    /* Activate the bubble editor on click of a table cell */
                    $('#tdata_70').on('click', 'tbody td.editable', function(e) {
                        editor_tdata_70.bubble(this, {
                            buttons: [{
                                    text: "Batal",
                                    className: 'btn-sm btn-secondary mr-1',
                                    action: function() {
                                        this.close();
                                    }
                                },
                                {
                                    text: "Simpan",
                                    className: 'btn-sm btn-primary',
                                    action: function() {
                                        this.submit();
                                    }
                                },
                            ]
                        });
                    });
                    /* Inline editing in responsive cell */
                    $('#tdata_70').on('click', 'tbody ul.dtr-details li', function(e) {
                        /* Ignore the Responsive control and checkbox columns */
                        if ($(this).hasClass('control') || $(this).hasClass('select-checkbox')) {
                            return;
                        }
                        /* ignore read-only column */
                        var editable = false;
                        var colnum = $(this).attr('data-dt-column');
                        /* Edit the value, but this method allows clicking on label as well */
                        if (editable) {
                            editor_tdata_70.bubble($('span.dtr-data', this), {
                                buttons: [{
                                        text: "Batal",
                                        className: 'btn-sm btn-secondary mr-1',
                                        action: function() {
                                            this.close();
                                        }
                                    },
                                    {
                                        text: "Simpan",
                                        className: 'btn-sm btn-primary',
                                        action: function() {
                                            this.submit();
                                        }
                                    },
                                ]
                            });
                        }
                    });
                    //hack: somehow the footer is nested inside the body.
                    //TODO: find the real reason why it happens (note: in most cases, it does not happen)
                    //NOTE: in localhost/sngine, which uses older version of this code, this does not happen!
                    editor_tdata_70.on('open', function() {
                        $('div.DTE_Body').after($('div.DTE_Footer'));
                    });
                    dt_tdata_70 = $('#tdata_70').DataTable({
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
                        dom: "<'row'<'col-sm-12 col-md-7 dt-action-buttons'B><'col-sm-12 col-md-5'fr>>t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
                        //dom: 'Bfrtip',
                        select: true,
                        buttons: {
                            buttons: [{
                                    extend: "create",
                                    editor: editor_tdata_70,
                                    className: 'btn-sm'
                                },
                                {
                                    extend: "edit",
                                    editor: editor_tdata_70,
                                    className: 'btn-sm btn-info'
                                },
                                {
                                    extend: "remove",
                                    editor: editor_tdata_70,
                                    className: 'btn-sm btn-danger'
                                },
                                // {
                                //     //text: 'Filter',
                                //     className: 'btn-sm btn-primary',
                                //     extend: 'searchPanes',
                                //     config: {
                                //         cascadePanes: true
                                //     }
                                // },
                                {
                                    //text: 'Kuery',
                                    className: 'btn-sm btn-primary',
                                    extend: 'searchBuilder',
                                    config: {
                                        depthLimit: 2
                                    }
                                },
                            ],
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
                        rowId: '_key_',
                        "ajax": {
                            "url": "http://localhost/codeigniter/index.php/user/penilaian/json",
                            "dataType": "json",
                            "type": "POST",
                            "data": function(d) {
                                d.f_wilayah = v_wilayah;
                                d.f_kategori = v_kategori;
                                d.f_suku = v_suku;
                                d.f_khusus = v_khusus;
                                return d;
                            }
                        },
                        "columns": [
                            {
                                data: "_key_",
                                editField: "_key_",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "nik",
                                editField: "nik",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "nama",
                                editField: "nama",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "no_peserta",
                                editField: "no_peserta",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "nilai_skd",
                                editField: "nilai_skd",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "nilai_kes",
                                editField: "nilai_kes",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "nilai_psi",
                                editField: "nilai_psi",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "nilai_jas",
                                editField: "nilai_jas",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "nilai_mi",
                                editField: "nilai_mi",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "nilai_akhir",
                                editField: "nilai_akhir",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "kemampuan_label",
                                editField: "kemampuan",
                                className: "col_tcg_select2  ",
                                render: function(data, type, row) {
                                    // if (type == "export") {
                                    //     //export raw data?
                                    // }
                                    return data;
                                }
                            },
                            {
                                data: "wilayah_label",
                                editField: "wilayah",
                                className: "col_tcg_select2  ",
                                render: function(data, type, row) {
                                    // if (type == "export") {
                                    //     //export raw data?
                                    // }
                                    return data;
                                }
                            },
                            {
                                data: "provinsi",
                                editField: "provinsi",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "kabupaten_kota",
                                editField: "kabupaten_kota",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "kategori_label",
                                editField: "kategori",
                                className: "col_tcg_select2  ",
                                render: function(data, type, row) {
                                    // if (type == "export") {
                                    //     //export raw data?
                                    // }
                                    return data;
                                }
                            },
                            {
                                data: "umur",
                                editField: "umur",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "agama_label",
                                editField: "agama",
                                className: "col_tcg_select2  ",
                                render: function(data, type, row) {
                                    // if (type == "export") {
                                    //     //export raw data?
                                    // }
                                    return data;
                                }
                            },
                            {
                                data: "suku_label",
                                editField: "suku",
                                className: "col_tcg_select2  ",
                                render: function(data, type, row) {
                                    // if (type == "export") {
                                    //     //export raw data?
                                    // }
                                    return data;
                                }
                            },
                            {
                                data: "khusus_label",
                                editField: "khusus",
                                className: "col_tcg_select2  ",
                                render: function(data, type, row) {
                                    // if (type == "export") {
                                    //     //export raw data?
                                    // }
                                    return data;
                                }
                            },
                            {
                                data: "photo",
                                editField: "photo",
                                className: "col_tcg_text  ",
                            },
                            {
                                data: "lulus",
                                editField: "lulus",
                                className: "col_tcg_toggle  ",
                            },
                        ],
                        "columnDefs": [
                            {
                                targets: [0],
                                orderable: false
                            }
                        ],
                        initComplete: function() {
                            dt_tdata_70_initialized = true;
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

                    $('#tdata_70').on('init.dt', function (e, settings) {
                //         var api = new DataTable.Api(settings);
                //         var containerNode = $(api.table(undefined).container());
                //         // Select init
                //         DataTable.select.init(api);
                // //DataTable.responsive.init(api);
                //         // // Append side button controls
                //         // side
                //         //   .append(containerNode.find('div.dataTables_filter'))
                //         //   .append(containerNode.find('div.dt-buttons'))
                //         //   .append(containerNode.find('div.dataTables_info'));
                        //$.fn.dataTable.responsive.init(api);
                    })

                    dt_tdata_70.buttons(0, null).container().addClass("mr-md-2 mb-1");
                    let buttons = new $.fn.dataTable.Buttons(dt_tdata_70, {
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                text: 'Ekspor',
                                className: 'btn-sm btn-primary',
                                exportOptions: {
                                    orthogonal: "export",
                                    modifier: {
                                        //selected: true
                                    },
                                },
                            },
                            {
                                text: 'Impor',
                                className: 'btn-sm btn-danger',
                                action: function(e, dt, node, conf) {
                                    dt_tdata_70_import(e, dt, node, conf);
                                },
                            },
                        ]
                    });
                    let cnt = buttons.c.buttons.length;
                    if (cnt == 0) {
                        buttons.container().addClass('d-none dt-export-buttons');
                    } else {
                        buttons.container().addClass('mr-md-2 mb-1 dt-export-buttons');
                    }
                    dt_tdata_70.buttons(0, null).container().after(
                        dt_tdata_70.buttons(1, null).container()
                    );
                    buttons = new $.fn.dataTable.Buttons(dt_tdata_70, {
                        buttons: [
                            // {
                            //     //text: 'Filter',
                            //     className: 'btn-sm btn-primary',
                            //     extend: 'searchPanes',
                            //     config: {
                            //         cascadePanes: true
                            //     }
                            // },
                            // {
                            //     //text: 'Kuery',
                            //     className: 'btn-sm btn-primary',
                            //     extend: 'searchBuilder',
                            //     config: {
                            //         depthLimit: 2
                            //     }
                            // },
                        ]
                    });
                    cnt = buttons.c.buttons.length;
                    if (cnt == 0) {
                        buttons.container().addClass('d-none dt-filter-buttons');
                    } else {
                        buttons.container().addClass('mr-md-2 mb-1 dt-filter-buttons');
                    }
                    dt_tdata_70.buttons(1, null).container().after(
                        dt_tdata_70.buttons(2, null).container()
                    );
                    dt_tdata_70.on("user-select.dt", function(e, dt, type, cell, originalEvent) {
                        var $elem = $(originalEvent.target); // get element clicked on
                        var tag = $elem[0].nodeName.toLowerCase(); // get element's tag name

                        if (!$elem.closest("div.dt-row-actions").length) {
                            return; // ignore any element not in the dropdown
                        }
                        if (tag === "i" || tag === "a" || tag === "button") {
                            return false; // cancel the select event for the row
                        }
                    });
                    // dt_tdata_70.on( 'column-sizing.dt', function ( e, settings ) {
                    //     //dt_tdata_70.columns.adjust().responsive.recalc();
                    //     console.log( 'Column width recalculated in table' );
                    // });
                });
                var dt_tdata_70_initialized = false;
                function dt_tdata_70_ajax_load(data) {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            "url": "http://localhost/codeigniter/index.php/user/penilaian/json",
                            "dataType": "json",
                            "type": "POST",
                            "data": {
                                f_wilayah: $("#f_wilayah").val(),
                                f_kategori: $("#f_kategori").val(),
                                f_suku: $("#f_suku").val(),
                                f_khusus: $("#f_khusus").val(),
                                search: $("#search").val(),
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
                                } else if (typeof response.error !== 'undefined' && response.error !== null &&
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
                function dt_tdata_70_edit_row(row_id, dt, key) {
                    let row = dt.row('#' + key);
                    editor_tdata_70
                        .title("Ubah hasil_penilaian")
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
                    //     editor: editor_tdata_70,
                    //     buttons: [
                    //         { label: "Save", className: "btn-primary", fn: function () { this.submit(); } },
                    //     ]
                    // }, false );
                    return;
                }
                function dt_tdata_70_delete_row(row_id, dt, key) {
                    let row = dt.row('#' + key);
                    editor_tdata_70
                        .title("Hapus hasil_penilaian")
                        .buttons([{
                            label: "Hapus",
                            className: "btn-danger",
                            fn: function() {
                                this.submit();
                            }
                        }, ])
                        .message("Konfirmasi menghapus 1 hasil_penilaian?")
                        .remove(row.index(), true);
                    // row.delete( {
                    //     buttons: [
                    //         { label: "Delete", className: "btn-danger", fn: function () { this.submit(); } },
                    //     ]
                    // } );
                }
                function dt_tdata_70_import(e, dt, node, conf) {
                    $.confirm({
                        columnClass: 'medium',
                        title: 'Impor Penilaian',
                        content: '' +
                            '<form action="" class="formName">' +
                            '<div class="form-group">' +
                            '<input id="upload" type="file" name="import" accept=".xlsx, .xls, .csv" style="width: 100%;" />' +
                            '<div id="error" class="d-none text-danger mt-2"></div>' +
                            '<div class="d-none text-center justify-content-center" id="spinner" style="position: absolute; width: 100%; top: 0px;">' +
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
                                    upload.attr('disabled', 'disabled');
                                    this.buttons.cancel.disable();
                                    this.buttons.formSubmit.disable();
                                    $.ajax({
                                        type: "POST",
                                        url: "http://localhost/codeigniter/index.php/user/penilaian/json",
                                        async: true,
                                        data: formData,
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        timeout: 60000,
                                        dataType: 'json',
                                        success: function(json) {
                                            if (typeof json.error !== 'undefined' && json.error != "" && json.error != null) {
                                                let message = that.$content.find('#error');
                                                message.html(json.error);
                                                message.removeClass("d-none");
                                                //hide spinner
                                                spinner.addClass('d-none');
                                                upload.removeAttr('disabled');
                                                that.buttons.cancel.enable();
                                                that.buttons.formSubmit.enable();
                                                return;
                                            }
                                            //hide spinner
                                            spinner.addClass('d-none');
                                            dt.ajax.reload();
                                            that.close();
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            let message = that.$content.find('#error');
                                            message.html("Gagal mengimpor file: " + textStatus);
                                            message.removeClass("d-none");
                                            //hide spinner
                                            spinner.addClass('d-none');
                                            upload.removeAttr('disabled');
                                            that.buttons.cancel.enable();
                                            that.buttons.formSubmit.enable();
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
                                that.$$formSubmit.trigger('click'); // reference the button and click it
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
    <!-- all the js files -->
    <!-- bootstrap. bundle includes popper.js -->
    <script src="http://localhost/codeigniter/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- leaflet -->
    <script src="http://localhost/codeigniter/assets/leaflet/leaflet/leaflet.js" defer></script>
    <script src="http://localhost/codeigniter/assets/leaflet/esri/esri-leaflet.js" defer></script>
    <script src="http://localhost/codeigniter/assets/leaflet/esri/esri-leaflet-geocoder.js" defer></script>
    <script src="http://localhost/codeigniter/assets/leaflet/fullscreen/Leaflet.fullscreen.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/leaflet/easybutton/easy-button.js" defer></script>
    <!-- select2 -->
    <script src="http://localhost/codeigniter/assets/select2/js/select2.min.js"></script>
    <script src="http://localhost/codeigniter/assets/ckeditor5/ckeditor.js"></script>
    <!-- datatables -->
    <script src="http://localhost/codeigniter/assets/datatables/DataTables-1.10.20/js/jquery.dataTables.js"></script>
    <script src="http://localhost/codeigniter/assets/datatables/DataTables-1.10.20/js/dataTables.bootstrap4.js"></script>
    <script src="http://localhost/codeigniter/assets/datatables/Responsive-2.2.3/js/dataTables.responsive.js"></script>
    <script src="http://localhost/codeigniter/assets/datatables/Responsive-2.2.3/js/responsive.bootstrap4.js"></script>
    <script src="http://localhost/codeigniter/assets/datatables/Select-1.3.1/js/dataTables.select.min.js"></script>
    <script src="http://localhost/codeigniter/assets/datatables/Select-1.3.1/js/select.bootstrap4.min.js"></script>
    <script src="http://localhost/codeigniter/assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/Buttons-1.6.1/js/buttons.bootstrap4.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/JSZip-2.5.0/jszip.min.js" defer></script>
    <!--
<script src="http://localhost/codeigniter/assets/datatables/Buttons-1.6.1/js/buttons.flash.min.js" defer></script>
<script src="http://localhost/codeigniter/assets/datatables/Buttons-1.6.1/js/buttons.print.min.js" defer></script> -->
    <!-- datatables : spreadsheet like key -->
    <script src="http://localhost/codeigniter/assets/datatables/Editor-2.0.6/js/dataTables.editor.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/Editor-2.0.6/js/editor.bootstrap4.min.js" defer></script>

    <script src="http://localhost/codeigniter/assets/datatables/KeyTable-2.5.1/js/dataTables.keyTable.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/KeyTable-2.5.1/js/keyTable.bootstrap4.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/RowReorder-1.2.6/js/dataTables.rowReorder.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/RowReorder-1.2.6/js/rowReorder.bootstrap4.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/SearchBuilder-1.3.0/js/dataTables.searchBuilder.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/SearchBuilder-1.3.0/js/searchBuilder.bootstrap4.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/SearchPanes-1.4.0/js/dataTables.searchPanes.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/SearchPanes-1.4.0/js/searchPanes.bootstrap4.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-select2.js" defer></script>
    <script src="http://localhost/codeigniter/assets/jquery-mask/jquery.mask.min.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-mask.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-toggle.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-checkbox.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-cascade.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-geolocation.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-unitprice.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-table.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-plugin-rowgroup.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-text.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-number.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-readonly.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-date.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-textarea.js" defer></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-editor.js" defer></script>

    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-table-select.js" defer></script>

    <script src="http://localhost/codeigniter/assets/dropzone/dropzone.min.js"></script>
    <script src="http://localhost/codeigniter/assets/datatables/tcg/dt-editor-upload.js" defer></script>
    <!-- WYSIWYG editor -->
    <!-- <script src="http://localhost/codeigniter/assets/ckeditor/ckeditor.js" defer></script>
<script src="http://localhost/codeigniter/assets/ckeditor/adapters/jquery.js" defer></script> -->
    <!-- <script src="http://localhost/codeigniter/assets/backend/js/vendor/summernote-bs4.min.js"></script>
<script src="http://localhost/codeigniter/assets/backend/js/pages/demo.summernote.js"></script> -->
    <!-- full calendar -->
    <script src="http://localhost/codeigniter/assets/fullcalendar/core/main.min.js" defer></script>
    <!-- dropzone file upload -->
    <script src="http://localhost/codeigniter/assets/dropzone/dropzone.min.js" defer></script>
    <!-- dragula drag-n-drop component -->
    <script src="http://localhost/codeigniter/assets/dragula/dragula.min.js" defer></script>
    <!-- mustache templating -->
    <script src="http://localhost/codeigniter/assets/mustache/mustache.min.js" defer></script>
    <!-- toastr toast popup -->
    <script src="http://localhost/codeigniter/assets/jquery-confirm/jquery-confirm.min.js"></script>
    <script src="http://localhost/codeigniter/assets/toastr/toastr.min.js"></script>
    <!-- fontawesome -->
    <script src="http://localhost/codeigniter/assets/fontawesome/js/fontawesome.min.js" defer charset="utf-8"></script>
    <!-- <script src="http://localhost/codeigniter/assets/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js" defer charset="utf-8"></script> -->
    <!-- jquery plugins -->
    <script src="http://localhost/codeigniter/assets/jquery-jvectormap/jquery-jvectormap.min.js" defer></script>
    <!-- <script src="http://localhost/codeigniter/assets/backend/js/vendor/jquery-jvectormap-world-mill-en.js"></script> -->
    <script src="http://localhost/codeigniter/assets/bootstrap-tagsinput/bootstrap-tagsinput.min.js" defer charset="utf-8"></script>
    <!--- moment -->
    <script src="http://localhost/codeigniter/assets/moment/moment-with-locales.min.js" defer></script>
    <!-- third party js -->
    <!-- <script src="http://localhost/codeigniter/assets/backend/js/vendor/Chart.bundle.min.js"></script>
<script src="http://localhost/codeigniter/assets/backend/js/pages/demo.dashboard.js"></script>
<script src="http://localhost/codeigniter/assets/backend/js/pages/datatable-initializer.js"></script>
<script src="http://localhost/codeigniter/assets/backend/js/pages/demo.form-wizard.js"></script> -->
    <!-- app -->
    <script src="http://localhost/codeigniter/themes/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js" defer></script>
    <script src="http://localhost/codeigniter/themes/adminlte/dist/js/adminlte.min.js" defer></script>
    <script src="http://localhost/codeigniter/themes/adminlte/app.js" defer></script>
    <!-- <script src="http://localhost/codeigniter/themes/adminlte/js/custom.js" defer></script> -->
    <!-- Toastr and alert notifications scripts -->
    <script type="text/javascript">
        //select2 default theme
        $.fn.select2.defaults.set("theme", "bootstrap");
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
        function showAjaxModal(url, header) {
            // SHOWING AJAX PRELOADER IMAGE
            jQuery('#scrollable-modal .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="http://localhost/codeigniter/assets/global/bg-pattern-light.svg" /></div>');
            jQuery('#scrollable-modal .modal-title').html('...');
            // LOADING THE AJAX MODAL
            jQuery('#scrollable-modal').modal('show', {
                backdrop: 'true'
            });
            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: url,
                success: function(response) {
                    jQuery('#scrollable-modal .modal-body').html(response);
                    jQuery('#scrollable-modal .modal-title').html(header);
                }
            });
        }
        function showLargeModal(url, header) {
            // SHOWING AJAX PRELOADER IMAGE
            jQuery('#large-modal .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="http://localhost/codeigniter/assets/global/bg-pattern-light.svg" height = "50px" /></div>');
            jQuery('#large-modal .modal-title').html('...');
            // LOADING THE AJAX MODAL
            jQuery('#large-modal').modal('show', {
                backdrop: 'true'
            });
            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax({
                url: url,
                success: function(response) {
                    jQuery('#large-modal .modal-body').html(response);
                    jQuery('#large-modal .modal-title').html(header);
                }
            });
        }
    </script>
    <!-- (Large Modal)-->
    <div class="modal fade" id="large-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- Scrollable modal -->
    <div class="modal fade" id="scrollable-modal" tabindex="-1" role="dialog" aria-labelledby="scrollableModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollableModalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ml-2 mr-2">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal"><?php echo __("close"); ?></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
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
        // modal
        function modal() {
            if (arguments[0] == "#modal-login" || arguments[0] == "#chat-calling" || arguments[0] == "#chat-ringing") {
                /* disable the backdrop (don't close modal when click outside) */
                if ($('#modal').data('bs.modal')) {
                    $('#modal').data('bs.modal').options = {
                        backdrop: 'static',
                        keyboard: false
                    };
                } else {
                    $('#modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            }
            /* check if the modal not visible, show it */
            if (!$('#modal').is(":visible")) $('#modal').modal('show');
            /* prepare modal size */
            $('.modal-dialog', '#modal').removeClass('modal-sm');
            $('.modal-dialog', '#modal').removeClass('modal-lg');
            $('.modal-dialog', '#modal').removeClass('modal-xlg');
            switch (arguments[2]) {
                case 'small':
                    $('.modal-dialog', '#modal').addClass('modal-sm');
                    break;
                case 'large':
                    $('.modal-dialog', '#modal').addClass('modal-lg');
                    break;
                case 'extra-large':
                    $('.modal-dialog', '#modal').addClass('modal-xl');
                    break;
            }
            /* update the modal-content with the rendered template */
            let content = render_template(arguments[0], arguments[1]);
            let container = $('.modal-content:last', '#modal');
            container.html(content);
            //$('.modal-content:last', '#modal').html( render_template(arguments[0], arguments[1]) );
            /* initialize modal if the function defined (user logged in) */
            if (typeof initialize_modal === "function") {
                initialize_modal();
            }
            //   console.log($('#modal'));
        }
        // confirm
        // function confirm(message, callback) {
        //     $.confirm({
        //         title: 'Konfirmasi',
        //         content: message,
        //         buttons: {
        //             confirm: {
        //                 text: 'Yes', // With spaces and symbols
        //                 action: function () {
        //                     console.log('clicked yes');
        //                     if(callback) callback();
        //                 }
        //             },
        //             cancel: {
        //                 text: 'No', // With spaces and symbols
        //                 action: function () {
        //                     //nothing
        //                 }
        //             },
        //         }
        //     });
        // }
        // function confirm(message, callback) {
        //     toastr.info("<br /><br /><button type='button' id='confirmationRevertYes' class='btn clear'>Yes</button>",'delete item?',
        //     {
        //         closeButton: false,
        //         allowHtml: true,
        //         onShown: function (toast) {
        //             $("#confirmationRevertYes").click(function(){
        //                 if(callback) callback();
        //             });
        //             }
        //     });
        // }
        // function confirm(title, message, callback) {
        //     toastr.success("<br /><br /><button type='button' id='confirmationRevertYes' class='btn clear'>Yes</button>",'delete item?',
        //     {
        //         closeButton: false,
        //         allowHtml: true,
        //         onShown: function (toast) {
        //             $("#confirmationRevertYes").click(function(){
        //                 console.log('clicked yes');
        //             });
        //             }
        //     });
        //     modal('#modal-confirm', { 'title': title, 'message': message });
        //     $("#modal-confirm-ok").click( function() {
        //         button_status($(this), "loading");
        //         if(callback) callback();
        //         $('#modal').modal('hide');
        //     });
        // }
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
        function select_build(select, deflabel, defvalue, value, options, attr) {
            //store current value
            let _prevvalue = select.val();
            //rebuild the option list
            select.empty();
            //default option
            if (typeof deflabel !== "undefined" && deflabel != null && typeof defvalue !== "undefined" && defvalue != null) {
                let _def = $("<option>").val(defvalue).text(deflabel);
                _def.addClass("select-option-level-1");
                select.append(_def);
            }
            //list of options
            if (options != null && Array.isArray(options)) {
                //add options one by one
                options.forEach(function(item, index, arr) {
                    if (typeof item === "undefined" || item == null ||
                        typeof item.value === "undefined" || item.value == null ||
                        typeof item.label === "undefined" || item.label == null) {
                        return;
                    }
                    if (item.value == defvalue) {
                        return;
                    }
                    let _option = $("<option>").val(item.value).text(item.label);
                    if (typeof item.level === "undefined" || item.level == null) {
                        _option.addClass("select-option-level-1");
                    } else if (item.level == 2) {
                        _option.addClass("select-option-level-2");
                    } else if (item.level == 3) {
                        _option.addClass("select-option-level-3");
                    } else if (item.level == 4) {
                        _option.addClass("select-option-level-4");
                    } else if (item.level == 5) {
                        _option.addClass("select-option-level-5");
                    } else {
                        _option.addClass("select-option-level-1");
                    }
                    if (typeof item.optgroup !== "undefined" && item.optgroup != null && item.optgroup == 1) {
                        _option.addClass("select-option-group");
                        _option.prop("disabled", true);
                    }
                    select.append(_option);
                });
            }
            //re-set the value
            if (typeof value !== 'undefined' && value != null) {
                select.val(value);
            } else {
                select.val(_prevvalue);
            }
            // if (typeof value === 'undefined' || value == null) {
            //     if (typeof defvalue === 'undefined' || defvalue == null || defvalue == '') {
            //         select.val('0').trigger('change');
            //     } else {
            //         select.val(defvalue).trigger('change');
            //     }
            // } else {
            //     select.val(value);
            // }
            //multiple select?
            if (typeof attr.multiple !== 'undefined' && attr.multiple) {
                select.attr('multiple', 'multiple');
            }
            //read-only?
            if (typeof attr.readonly !== 'undefined' && attr.readonly == true) {
                select.attr("readonly", true);
            }
            return select;
        }
        function select2_build(select, deflabel, defvalue, value, options, attr, parent = null) {
            //build the select
            select_build(select, deflabel, defvalue, value, options, attr);
            //convert to select2
            select.select2({
                minimumResultsForSearch: attr.minimumResultsForSearch,
                //dropdownCssClass: attr.cssClass,
                dropdownParent: parent,
                templateResult: function(data) {
                    // We only really care if there is an element to pull classes from
                    if (!data.element) {
                        return data.text;
                    }
                    var $element = $(data.element);
                    var $wrapper = $('<div></div>');
                    $wrapper.addClass($element[0].className);
                    $wrapper.text(data.text);
                    return $wrapper;
                }
            });
            //read-only?
            if (typeof attr.readonly !== 'undefined' && attr.readonly == true) {
                select.select2("readonly", true);
            }
            return select;
        }
        function select2_rebuild(select, attr, parent = null) {
            //convert to select2
            select.select2({
                minimumResultsForSearch: attr.minimumResultsForSearch,
                //dropdownCssClass: attr.cssClass,
                dropdownParent: parent,
                templateResult: function(data) {
                    // We only really care if there is an element to pull classes from
                    if (!data.element) {
                        return data.text;
                    }
                    var $element = $(data.element);
                    var $wrapper = $('<div></div>');
                    $wrapper.addClass($element[0].className);
                    $wrapper.text(data.text);
                    return $wrapper;
                }
            });
            //read-only?
            if (typeof attr.readonly !== 'undefined' && attr.readonly == true) {
                select.select2("readonly", true);
            }
            return select;
        }
    </script>
    <!-- Modals -->
    <div id="modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader pt10 pb10"></div>
                </div>
            </div>
        </div>
    </div>
    <script id="modal-login" type="text/template">
        <div class="modal-header">
        <h6 class="modal-title">Not Logged In</h6>
    </div>
    <div class="modal-body">
        <p>Please log in to continue</p>
    </div>
    <div class="modal-footer">
        <a class="btn btn-primary" href="http://localhost/codeigniter/index.php/login">Login</a>
    </div>
</script>
    <script id="modal-message" type="text/template">
        <div class="modal-header">
        <h6 class="modal-title">{{title}}</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <p>{{message}}</p>
    </div>
</script>
    <script id="modal-success" type="text/template">
        <div class="modal-body text-center">
        <div class="big-icon success">
            <i class="fa fa-thumbs-up fa-3x"></i>
        </div>
        <h4>{{title}}</h4>
        <p class="mt20">{{message}}</p>
    </div>
</script>
    <script id="modal-error" type="text/template">
        <div class="modal-body text-center">
        <div class="big-icon error">
            <i class="fa fa-times fa-3x"></i>
        </div>
        <h4>{{title}}</h4>
        <p class="mt20">{{message}}</p>
    </div>
</script>
    <script id="modal-confirm" type="text/template">
        <div class="modal-header">
        <h6 class="modal-title">{{title}}</h6>
    </div>
    <div class="modal-body">
        <p>{{message}}</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="modal-confirm-ok">Confirm</button>
    </div>
</script>
    <script id="modal-loading" type="text/template">
        <div class="modal-body text-center">
        <div class="spinner-border text-primary"></div>
    </div>
</script>
    <!-- page footer -->
</body>
</html>