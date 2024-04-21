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

    /* datatable2.x */
    table.dataTable.table > tbody > tr.selected > * {
        /* box-shadow: inset 0 0 0 9999px rgb(2, 117, 216); */
        box-shadow: inset 0 0 0 9999px rgb(var(--theme-color));
    }

    table.dataTable.table.table-striped
	> tbody
	> tr:nth-of-type(2n + 1).selected
	> * {
        /* box-shadow: inset 0 0 0 9999px rgb(2, 117, 216); */
        box-shadow: inset 0 0 0 9999px rgb(var(--theme-color));
    }
    table.dataTable.table.table-hover > tbody > tr.selected:hover > * {
        /* box-shadow: inset 0 0 0 9999px rgb(2, 117, 216); */
        box-shadow: inset 0 0 0 9999px rgb(var(--theme-color));
    }

    table.dataTable > tbody > tr > .selected {
        background-color: var(--theme-color) !important;
        color: black !important;
    }

    table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control, table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
    }

    table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before, 
    table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
        top: 12px;
        left: 8px;
        height: 14px;
        width: 14px;
        display: block;
        position: absolute;
        color: white;
        box-shadow: 0 0 3px #444;
        box-sizing: content-box;
        text-align: center;
        text-indent: 0 !important;
        font-family: 'Courier New', Courier, monospace;
        line-height: 14px;
        border: 2px solid white;
        border-radius: 14px;
        content: '+';
        background-color: #0275d8;
    }            

    table.dataTable.dtr-inline.collapsed > tbody > tr.dtr-expanded > td.dtr-control:before, 
    table.dataTable.dtr-inline.collapsed > tbody > tr.dtr-expanded > th.dtr-control:before {        
        /* top: 12px;
        left: 4px;
        height: 14px;
        width: 14px;
        display: block;
        position: absolute;
        color: white;
        box-shadow: 0 0 3px #444;
        box-sizing: content-box;
        text-align: center;
        text-indent: 0 !important;
        font-family: 'Courier New', Courier, monospace;
        line-height: 14px; */
        border: 2px solid white;
        border-radius: 14px;
        content: '-';
        background-color: #d33333;
    }   

    div.dt-container div.dt-paging {
        padding-top: 0.5em;
        white-space: nowrap;
        float: right;
    }    

    /* end of datatable2.x */

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