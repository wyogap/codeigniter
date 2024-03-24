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

    <link href="http://localhost/pusbekal/assets/datatables/tcg/dt-editor-select2.bootstrap4.css" rel="stylesheet"/>
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
                                            <a class="btn btn-primary" onclick="onclick_editor1();">Editor 1</a></br>
                                            <a class="btn btn-secondary" onclick="onclick_editor2();">Editor 2</a>
                                        </div> <!-- end card body-->
                                    </div> <!-- end card -->
                                </div><!-- end col-->
                            </div>
                        </div>
                    </div>
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

    <script src="http://localhost/pusbekal/assets/datatables/tcg/dt-editor-select2.js" defer></script>

</body>

<script type="text/javascript">
    var editor1, editor2;

    $(document).ready(function() {
        editor1 = new $.fn.dataTable.Editor({
            ajax: "http://localhost/pusbekal/disbekal/wfpengadaan/completetender",
            //idSrc: "poid",
            fields: [
            {
                name: "tenderid",
                type: "hidden"
            }, {
                label: "Vendor (Editor1)",
                compulsory: true,
                //TODO: if 2 select2 field has the same value, one of them will fail to init.
                name: "vendorid",
                type: 'tcg_select2',
                ajax: "http://localhost/pusbekal/crud/mitra/lookup",
                className: 'editor1',
                editorId: 'editor1'
            }, ],
            formOptions: {
                main: {
                    submit: 'changed'
                }
            },
            i18n: {
                create: {
                    button: "Baru",
                    title: "Pemenang Tender LPSE",
                    submit: "Simpan"
                },
                error: {
                    system: "System error. Hubungi system administrator."
                },
            }
        });
        editor2 = new $.fn.dataTable.Editor({
            ajax: "http://localhost/pusbekal/disbekal/wfpengadaan/completetender",
            //idSrc: "poid",
            fields: [
            {
                name: "contractid",
                type: "hidden"
            }, {
                label: "Vendor (Editor2)",
                compulsory: true,
                //TODO: if 2 select2 field has the same value, one of them will fail to init.
                name: "vendorid",
                type: 'tcg_select2',
                ajax: "http://localhost/pusbekal/crud/mitra/lookup",
                className: 'editor2',
                editorId: 'editor2'
            }, ],
            formOptions: {
                main: {
                    submit: 'changed'
                }
            },
            i18n: {
                create: {
                    button: "Baru",
                    title: "Pemenang Tender LPSE",
                    submit: "Simpan"
                },
                error: {
                    system: "System error. Hubungi system administrator."
                },
            }
        });
    });

    function onclick_editor1() {
        editor1
        .buttons({
            label: 'Simpan',
            className: "btn-primary",
            fn: function () {
                this.submit();
            }
        })
        .edit(1)
        .title('Editor1');

        return;
    }


    function onclick_editor2() {
        editor2
        .buttons({
            label: 'Simpan',
            className: "btn-primary",
            fn: function () {
                this.submit();
            }
        })
        .edit(1)
        .title('Editor2');

        return;
    }

</script>

</html>