<style>

    html {
        font-size: 14px;
    }

    :root {
        --theme-color: #aad3df;
        --theme-primary: #58bcd9;
        --theme-primary-active: #009fcd;
        --theme-info: #007bff;
        --theme-info-active: #005fc5;
    }    

    .main-sidebar {
        background-color: var(--theme-color);
    }

    .nav-sidebar>.nav-item>.nav-link.active, .nav-treeview>.nav-item>.nav-link.active {
        background-color: var(--theme-primary) !important;
        color: #212529 !important;
        /* color: #fff !important; */
    }    

    .nav-sidebar>.nav-item>.nav-link:hover {
        background-color: var(--theme-primary) !important;
        color: #212529 !important;
    }

    .nav-treeview>.nav-item>.nav-link:hover {
        background-color: var(--theme-primary) !important;
        color: #212529 !important;
    }
   
    .nav-sidebar>.nav-item.menu-open>.nav-link:not(.active) {
        background-color: var(--theme-primary) !important;
    }

    .nav-treeview>.nav-item>.nav-link {
        color: #212529 !important;
    }

    .btn-primary {
        color: #fff;
        background-color: var(--theme-primary);
        border-color: var(--theme-primary);
        box-shadow: none;
    }

    .btn-primary:hover {
        color: #fff;
        background-color: var(--theme-primary-active);
        border-color: var(--theme-primary-active);
    }

    .btn-primary.disabled, .btn-primary:disabled {
        color: #fff;
        background-color: var(--theme-primary);
        border-color: var(--theme-primary);
    }

    .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle {
        color: #fff;
        background-color: var(--theme-primary-active);
        border-color: var(--theme-primary-active);
    }

    .btn-info {
        color: #fff;
        background-color: var(--theme-info);
        border-color: var(--theme-info);
        box-shadow: none;
    }    

    .btn-info:hover {
        color: #fff;
        background-color: var(--theme-info-active);
        border-color: var(--theme-info-active);
    }

    .btn-info.disabled, .btn-info:disabled {
        color: #fff;
        background-color: var(--theme-info);
        border-color: var(--theme-info);
    }    

    .btn-info:not(:disabled):not(.disabled).active, .btn-info:not(:disabled):not(.disabled):active, .show>.btn-info.dropdown-toggle {
        color: #fff;
        background-color: var(--theme-info-active);
        border-color: var(--theme-info-active);
    }

    .dataTable tbody tr:hover {
        background-color: var(--theme-primary) !important;
        color: black;
    }

    .dataTable tbody tr:hover > td {
        background-color: var(--theme-primary) !important;
        color: black;
    }

    .dataTable tbody tr:hover > .sorting_1 {
        background-color: var(--theme-primary) !important;
        color: black;
    }

    .dataTable tbody tr.selected {
        background-color: var(--theme-color) !important;
        color: black !important;
    }

    .dataTable tbody tr.selected td {
        background-color: var(--theme-color) !important;
        color: black !important;
    }

    .dataTable tbody tr.selected:hover {
        background-color: var(--theme-primary) !important;
        color: black !important;
    }

    .dataTable tbody tr.selected:hover > td {
        background-color: var(--theme-primary) !important;
        color: black !important;
    }

    .dataTable tbody tr.selected:hover > .sorting_1 {
        background-color: var(--theme-primary) !important;
        color: black !important;
    }
    
    .dataTable > tbody > tr.child:hover {
        color: black !important;
    }

    table.dataTable tbody tr.selected a, table.dataTable tbody th.selected a, table.dataTable tbody td.selected a {
        color: var(--theme-primary);
    }

    .page-item.active .page-link {
        background-color: var(--theme-primary);
        border-color: var(--theme-primary);
    }

    .page-link {
        color: var(--theme-primary);
        background-color: #fff;
    }

    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        color: #fff;
        background-color: var(--theme-primary);
    }

    .nav-pills .nav-link:not(.active):hover {
        color: var(--theme-primary);
    }

    .bg-theme {
        background-color: var(--theme-color);
    }

    table.dataTable tbody tr.selected a, table.dataTable tbody th.selected a, table.dataTable tbody td.selected a {
        color: var(--theme-info-active);
    }

    @media (min-width: 768px) {
        .col-md-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

</style>

<script type="text/javascript" defer>
    $(document).ready(function() {
        $(".content-header .card-body").addClass("bg-theme");
    });

</script>