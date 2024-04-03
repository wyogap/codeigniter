<style>
    {if !isset($tbl) && isset($crud)}{assign var=tbl value=$crud}{/if}

    {if isset($tbl) && isset($tbl.custom_css)}
    {$tbl.custom_css}
    {/if}
</style>

<style>
    
    .dataTable tbody tr:hover {
        background-color: #48a4f3 !important;
        color: white;
    }

    .dataTable tbody tr:hover > .sorting_1 {
        background-color: #48a4f3 !important;
        color: white;
    }

    .dataTable tbody tr.selected:hover {
        background-color: #0275d8 !important;
        color: white;
    }

    .dataTable tbody tr.selected:hover > .sorting_1 {
        background-color: #0275d8 !important;
        color: white;
    }
    
    .dataTable > tbody > tr.child:hover {
        color: black !important;
    }
   
    .dt-col-select {
        padding-left: 0px;
        max-width: 20px;
    }

    td.reorder {
        position: relative;
    }

    td.reorder:after {  
        top: 16px;
        right: 8px;
        height: 14px;
        width: 14px;
        display: block;
        position: absolute;
        text-align: center;
        text-indent: 0 !important;
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        line-height: 14px;
        content: '\f0b2';
    }

    td.inline-actions {
        padding-right: 4px !important;
    }

    .editor-layout div.tab-pane.active {
        display: flex;
        flex-wrap: wrap;
    }

    .editor-layout div.tab-pane > .form-group {
        flex: 0 0 100%;
    }

    thead input {
        width: 100%;
    }

    /* Bootstrap datepicker setting is overridden by dropdown menu setting in adminlte. Need to override back. */
    .datepicker.dropdown-menu {
        padding: 0.5rem 1rem !important;
    }

    /* so that any custom css will still align properly */
    .DTE .form-field {
        padding-right: 0px;
        padding-left: 0px;
        margin-top: 0px;
        width: 100%;
    }

    /*  
     * .dt-horizontal-2x : full row field, but align in 2 field per row layout
    **/
    .dt-horizontal-2x {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        width: 100%;    
    }

    .crud-form .form-field.dt-horizontal-2x .form-label {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
    }

    .crud-form .form-field.dt-horizontal-2x .form-input {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
    }

    .DTE_Form_Content .dt-horizontal-2x label[data-dte-e="label"] {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
    }

    .DTE_Form_Content .dt-horizontal-2x div[data-dte-e="input"] {
        -ms-flex: 0 0 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;    
        /* margin-right: -7.5px;
        margin-left: -7.5px;         */
    }

    /*  
     * .dt-horizontal-6 : half row field, but empty space in the next space
    **/
    .dt-horizontal-6 {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        width: 100%;
    }

    .crud-form .dt-horizontal-6 .form-field .form-label {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
    } 

    .crud-form .dt-horizontal-6 .form-field .form-input {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
    }

    /* .DTE_Form_Content .dt-horizontal-6 .DTE_Field.form-group {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
    } */

    .DTE_Form_Content .dt-horizontal-6 label[data-dte-e="label"] {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
        /* margin-right: 18px; */
    } 

    .DTE_Form_Content .dt-horizontal-6 div[data-dte-e="input"] {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
        /* padding-right: 24px; */
    }

    .crud-form .dt-horizontal-6 .DTE_Field_Type_tcg_toggle .form-label {
        -ms-flex: 0 0 66.666667% !important;
        flex: 0 0 66.666667% !important;
        max-width: 66.666667% !important;
    } 

    .crud-form .dt-horizontal-6 .DTE_Field_Type_tcg_toggle .form-input {
        -ms-flex: 0 0 33.333333% !important;
        flex: 0 0 33.333333% !important;
        max-width: 33.333333% !important;
        text-align: right !important;
    }

    .DTE_Form_Content .dt-horizontal-6 .DTE_Field_Type_tcg_toggle label[data-dte-e="label"] {
        -ms-flex: 0 0 66.666667% !important;
        flex: 0 0 66.666667% !important;
        max-width: 66.666667% !important;
    } 

    .DTE_Form_Content .dt-horizontal-6 .DTE_Field_Type_tcg_toggle div[data-dte-e="input"] {
        -ms-flex: 0 0 33.333333% !important;
        flex: 0 0 33.333333% !important;
        max-width: 33.333333% !important;
        text-align: right !important;
        padding-right: 7.5px;
    }

    .DTE_Field_Type_tcg_toggle {
        margin-top: 8px;
    }

    @media (min-width: 767px) {

        .DTE .form-field {
            margin-top: -8px;
        }

        .crud-form .dt-horizontal-6 .DTE_Field_Type_tcg_toggle .form-label {
            -ms-flex: 0 0 33.333333% !important;
            flex: 0 0 33.333333% !important;
            max-width: 33.333333% !important;
        } 

        .crud-form .dt-horizontal-6 .DTE_Field_Type_tcg_toggle .form-input {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
            padding-right: 24px;
            text-align: right !important;
        }

        .DTE_Form_Content .dt-horizontal-6 .DTE_Field.form-group {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
            padding-right: 16px !important;
        }

        .DTE_Form_Content .dt-horizontal-6 .DTE_Field_Type_tcg_toggle label[data-dte-e="label"] {
            -ms-flex: 0 0 66.666667%;
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        } 

        .DTE_Form_Content .dt-horizontal-6 .DTE_Field_Type_tcg_toggle div[data-dte-e="input"] {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            text-align: right !important;
            padding-right: 7.5px
        }

        .DTE_Field_Type_tcg_toggle {
            margin-top: 8px !important;
        }
    }

    @media (min-width: 992px) {

        .DTE .form-field {
            margin-top: 0px;
        }

        .crud-form .form-field.dt-horizontal-2x .form-label {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }

        .crud-form .form-field.dt-horizontal-2x .form-input {
            -ms-flex: 0 0 83.333333%;
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }

        .DTE_Form_Content .dt-horizontal-2x label[data-dte-e="label"] {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }

        .DTE_Form_Content .dt-horizontal-2x div[data-dte-e="input"] {
            -ms-flex: 0 0 83.333333% !important;
            flex: 0 0 83.333333% !important;
            max-width: 83.333333% !important;    
            margin-right: -7.5px;
            margin-left: -7.5px;        
        }

        /* .dt-horizontal-6 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        } */

        .crud-form .dt-horizontal-6 .form-field .form-label {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        } 

        .crud-form .dt-horizontal-6 .form-field .form-input {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        /* .DTE_Form_Content .dt-horizontal-6 .DTE_Field.form-group {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
            padding-right: 16px !important;
        } */

        .DTE_Form_Content .dt-horizontal-6 label[data-dte-e="label"] {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        } 

        .DTE_Form_Content .dt-horizontal-6 div[data-dte-e="input"] {
            -ms-flex: 0 0 66.666667%;
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
            padding-right: 7.5px
         }

        /*  
         * .dt-vertical : full horizontal field, input in the next row
        **/
        .DTE .form-field.dt-vertical label {  
            flex: 0 0 100%;
            max-width: 100%;
        }

        .DTE .form-field.dt-vertical div[data-dte-e="input"] {  
            flex: 0 0 100%;
            max-width: 100%;
        }

        /* Hack: since the input field makes most of the top padding, when the input field is under the label the top margin needs to be compensated. */
        .dt-vertical {
            margin-top: -1em;
        }

        .DTE_Field_Type_tcg_toggle {
            margin-top: 0px !important;
        }
        
    }

</style>

<style>

    .DTE_Header_Content {
        font-size: 22px;
        line-height: 20px;
    }

    .dtr-data .btn {
        margin-left: 4px;
        margin-right: 4px;
    }

    .inline-actions .btn {
        margin-left: 8px;
    }

    .inline-actions .btn:first-child {
        margin-left: 0px;
    }

    .inline-actions .btn:not(:first-child) {
        margin-left: 8px;
    }


    .bd-title {
        --bs-heading-color: var(--bs-emphasis-color);
        /* font-size: calc(1.425rem + 2.1vw); */
    }

    .bd-lead {
        /* font-size: calc(1.275rem + .3vw);  */
        font-weight: 300;
    }

    .btn-bd-light {
        --btn-custom-color: #9461fb;
        --bs-border-color: #dee2e6;
        --bd-violet-rgb: 112.520718,44.062154,249.437846;
        --bs-btn-color: var(--bs-gray-600);
        --bs-btn-border-color: var(--bs-border-color);
        --bs-btn-hover-color: var(--btn-custom-color);
        --bs-btn-hover-border-color: var(--btn-custom-color);
        --bs-btn-active-color: var(--btn-custom-color);
        --bs-btn-active-bg: var(--bs-white);
        --bs-btn-active-border-color: var(--btn-custom-color);
        --bs-btn-focus-border-color: var(--btn-custom-color);
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
    }

    .btn-bd-light:hover {
        color: #fff;
        background-color: #4e5bf2;
        border-color: #4250f2;
    }

    .py-1 {
        padding-top: 0.25rem!important;
        padding-bottom: 0.25rem!important;
    }
    .px-2 {
        padding-right: 0.5rem!important;
        padding-left: 0.5rem!important;
    }

    .me-2 {
        margin-right: 0.5rem!important;
        margin-bottom: 0.5rem!important;
    }

    :last-child > .me-2 {
        margin-right: 0;
        margin-bottom: 0.5rem!important;
    }

    .btn-icon-circle{
        display: inline-block;
        width: 30px;
        height: 30px;
        border: 1px solid #909090;
        border-radius: 50%;
        margin: 0px 2px 2px; /*space between*/
        padding: 0px;
        cursor: pointer;
        box-shadow: 0px 0px 2px #dee2e6;
        text-align: center;
        position: relative;
    } 

    .btn-icon-circle.small{
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

    .btn-icon-circle i{
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

    .btn-tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -60px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .btn-tooltip .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    .btn-tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
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

    /* Allow control for responsive table in any column */
    table.dataTable.dtr-column > tbody > tr > td.control:before, 
    table.dataTable.dtr-column > tbody > tr > th.control:before {
        top: 12px;
        left: 4px;
        height: 14px;
        width: 14px;
        margin-top: 0px;
        margin-left: 0px;
    }

    table.dataTable.dtr-column > tbody > tr > td.control, 
    table.dataTable.dtr-column > tbody > tr > th.control {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
    }    

    .DTE.modal-header
    .ans-close-wrp {
        display: grid;
    }

    .ans-buttons {
        margin-left: 5px;
        float: right !important;
    }

    .dt-action-buttons {
        margin-bottom: 0.5rem;
    }

    .dt-mr2 {
        margin-right: 2px !important;
    }


    div.dataTables_wrapper div.dataTables_info {
        padding-top: 0.85em;
        white-space: nowrap;
    }

    div.dataTables_wrapper div.dataTables_length {
        padding-top: 0.85em;
        white-space: nowrap;
        float: right;
        margin-left: 16px;
    }   

    div.dataTables_wrapper div.dataTables_length .custom-select-sm {
        font-size: 100% !important;
    } 

    div.dataTables_wrapper div.dataTables_paginate {
        padding-top: 0.5em;
        white-space: nowrap;
        float: right;
    }   

    div.DTE_Bubble .DTE_Field_Type_tcg_toggle label[data-dte-e="label"] {
        -ms-flex: 0 0 66.666667% !important;
        flex: 0 0 66.666667% !important;
        max-width: 66.666667% !important;
    }

    div.DTE_Bubble .DTE_Field_Type_tcg_toggle div[data-dte-e="input"] {
        -ms-flex: 0 0 33.333333% !important;
        flex: 0 0 33.333333% !important;
        max-width: 33.333333% !important;
        text-align: right !important;
    }

    @media (max-width: 768px) {
        div.dt-custom-actions {
            margin-left: auto;
            margin-right: auto;
        }

        .nav-tabs > li {
            width:100%;
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

        /* switch spacing from horizontal-right to vertical-bottom */
        .dt-mr2 {
            margin-right: 0px !important;
        }

        /* breakdown button group into vertical list of buttons */
        .btn-group .btn {
            border-radius: 0.2rem !important;
        }

        /* li.paginate_button {
            width: fit-content;
            display: inline-flex !important;
            justify-content: center;
            align-items: center;
            align-content: center;
        } */

        div.dataTables_wrapper div.dataTables_length {
            padding-top: 0.85em;
            white-space: nowrap;
            float: unset;
            margin-left: 0px;
        }   

        div.dataTables_wrapper div.dataTables_length .custom-select-sm {
            font-size: 100% !important;
        } 

        div.dataTables_wrapper div.dataTables_paginate {
            margin-top: -0.5em;
            padding-top: 0px;
            white-space: nowrap;
            float: unset;
        }   

    }


</style>

<style>
    /** OPTION DROPDOWN **/
    .select2-container .select-option-level-1 {
        padding-left: 0px !important;
    }

    .select2-container .select-option-level-2 {
        padding-left: 12px !important;
    }

    .select2-container .select-option-level-3 {
        padding-left: 24px !important;
    }

    .select2-container .select-option-level-4 {
        padding-left: 36px !important;
    }

    .select2-container .select-option-level-5 {
        padding-left: 48px !important;
    }

    .select2-container .select-option-group {
        font-weight: bold !important;
        color: black;
    }
    /** END OPTION-DROPDOWN **/
</style>

{include file="disbekal/css.tpl"}