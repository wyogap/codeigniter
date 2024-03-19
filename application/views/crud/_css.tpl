<style>
    {if $tbl.custom_css}
    {$tbl.custom_css}
    {/if}
</style>

<style>

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
}

</style>

