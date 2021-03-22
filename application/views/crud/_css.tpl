<style>
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

}

</style>

