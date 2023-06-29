<script type="text/javascript">
    // modal
    function modal() {
        if(arguments[0] == "#modal-login" || arguments[0] == "#chat-calling" || arguments[0] == "#chat-ringing") {
            /* disable the backdrop (don't close modal when click outside) */
            if($('#modal').data('bs.modal')) {
                $('#modal').data('bs.modal').options = { backdrop: 'static', keyboard: false };
            } else {
                $('#modal').modal({ backdrop: 'static', keyboard: false });
            }
        }
        /* check if the modal not visible, show it */
        if(!$('#modal').is(":visible")) $('#modal').modal('show');
        /* prepare modal size */
        $('.modal-dialog', '#modal').removeClass('modal-sm');
        $('.modal-dialog', '#modal').removeClass('modal-lg');
        $('.modal-dialog', '#modal').removeClass('modal-xlg');
        switch(arguments[2]) {
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
        if(typeof initialize_modal === "function") {
            initialize_modal();
        }
        //   console.log($('#modal'));
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
        <h6 class="modal-title">{__("Not Logged In")}</h6>
    </div>
    <div class="modal-body">
        <p>{__("Please log in to continue")}</p>
    </div>
    <div class="modal-footer">
        <a class="btn btn-primary" href="{$site_url}login">{__("Login")}</a>
    </div>
</script>

<script id="modal-message" type="text/template">
    <div class="modal-header">
        <h6 class="modal-title">{literal}{{title}}{/literal}</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <p>{literal}{{message}}{/literal}</p>
    </div>
</script>

<script id="modal-success" type="text/template">
    <div class="modal-body text-center">
        <div class="big-icon success">
            <i class="fa fa-thumbs-up fa-3x"></i>
        </div>
        <h4>{literal}{{title}}{/literal}</h4>
        <p class="mt20">{literal}{{message}}{/literal}</p>
    </div>
</script>

<script id="modal-error" type="text/template">
    <div class="modal-body text-center">
        <div class="big-icon error">
            <i class="fa fa-times fa-3x"></i>
        </div>
        <h4>{literal}{{title}}{/literal}</h4>
        <p class="mt20">{literal}{{message}}{/literal}</p>
    </div>
</script>

<script id="modal-confirm" type="text/template">
    <div class="modal-header">
        <h6 class="modal-title">{literal}{{title}}{/literal}</h6>
    </div>
    <div class="modal-body">
        <p>{literal}{{message}}{/literal}</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">{__("Cancel")}</button>
        <button type="button" class="btn btn-primary" id="modal-confirm-ok">{__("Confirm")}</button>
    </div>
</script>

<script id="modal-loading" type="text/template">
    <div class="modal-body text-center">
        <div class="spinner-border text-primary"></div>
    </div>
</script>