<link rel="shortcut icon" href="{$base_url}{$app_icon}">

<!-- <link rel="shortcut icon" href="http://localhost/academy/uploads/system/favicon.png"> -->

<!-- bootstrap -->
<link href="{$base_url}assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<!-- datatables -->
<link href="{$base_url}assets/datatables/DataTables-1.10.20/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/datatables/Responsive-2.2.3/css/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/datatables/Buttons-1.6.1/css/buttons.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/datatables/Select-1.3.1/css/select.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/datatables/KeyTable-2.5.1/css/keyTable.bootstrap4.css" rel="stylesheet" type="text/css" >
<link href="{$base_url}assets/datatables/Editor-1.9.2/css/editor.bootstrap4.css" rel="stylesheet" type="text/css" >

<link href="{$base_url}assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-select2.bootstrap4.css" rel="stylesheet" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-mask.css" rel="stylesheet" />
<link href="{$base_url}assets/datatables/tcg/dt-editor-toggle.bootstrap4.css" rel="stylesheet" />

<!-- WYSIWYG editor -->
<!-- <link href="{$base_url}assets/backend/css/vendor/summernote-bs4.css" rel="stylesheet" type="text/css" /> -->

<!-- full calendar -->
<link href="{$base_url}assets/fullcalendar/core/main.min.css" rel="stylesheet" type="text/css" />

<!-- dropzone file upload -->
<link href="{$base_url}assets/dropzone/dropzone.min.css" rel="stylesheet" type="text/css" />

<!-- dragula drag-n-drop component -->
<link href="{$base_url}assets/dragula/dragula.min.css" rel="stylesheet" type="text/css" />

<!-- toastr toast popup -->
<link href="{$base_url}assets/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

<!-- icons -->
<link href="{$base_url}assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />
<!-- <link href="{$base_url}assets/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css" /> -->
<link href="{$base_url}assets/dripicons/icons.min.css" rel="stylesheet" type="text/css" />

<!-- many of the 3rd-party library (including datatables) require older version of materialdesign icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.4.93/css/materialdesignicons.min.css" integrity="sha512-9hrcuHFRJBsyfJiotGL1U+zraOkuI5fzlo0X0C8s7gkkgV1wLkmiP1JbUjVAws4Wo8FcSK82Goj64vT8ERocgg==" crossorigin="anonymous" />

<!-- materialdesignicon v5 -->
<!-- <link href="{$base_url}assets/materialdesignicons/css/icons.min.css" rel="stylesheet" type="text/css" /> -->

<!-- utilities -->
<link href="{$base_url}assets/utilities.css" rel="stylesheet" type="text/css" />

<!-- jquery plugins -->
<link href="{$base_url}assets/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}assets/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

<!-- jquery js. must be loaded first before other js -->
<script src="{$base_url}assets/jquery/jquery-3.6.0.min.js"></script>

<!-- App css -->
<link href="{$base_url}{$theme_prefix}/css/app.min.css" rel="stylesheet" type="text/css" />
<link href="{$base_url}{$theme_prefix}/css/main.css" rel="stylesheet" type="text/css" />

<script src="{$base_url}{$theme_prefix}/js/onDomChange.js"></script>

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
@media (min-width: 1200px) { 

}

@media only screen and (max-width: 768px) {

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
}

</style>