
<!-- bootstrap. bundle includes popper.js -->
<script src="{$base_url}assets/bootstrap/js/bootstrap.bundle.min.js" defer></script>

<!-- datatables -->
<script src="{$base_url}assets/datatables/DataTables-1.10.20/js/jquery.dataTables.min.js" defer></script>
<script src="{$base_url}assets/datatables/DataTables-1.10.20/js/dataTables.bootstrap4.min.js" defer></script>
<script src="{$base_url}assets/datatables/Responsive-2.2.3/js/dataTables.responsive.min.js" defer></script>
<script src="{$base_url}assets/datatables/Responsive-2.2.3/js/responsive.bootstrap4.min.js" defer></script>
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/dataTables.buttons.min.js" defer></script>
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.bootstrap4.min.js" defer></script>
<!-- <script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.html5.min.js" defer></script>
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.flash.min.js" defer></script>
<script src="{$base_url}assets/datatables/Buttons-1.6.1/js/buttons.print.min.js" defer></script> -->
<script src="{$base_url}assets/datatables/Select-1.3.1/js/dataTables.select.min.js" defer></script>
<script src="{$base_url}assets/datatables/Select-1.3.1/js/select.bootstrap4.min.js" defer></script>

<!-- datatables : spreadsheet like key -->
<script src="{$base_url}assets/datatables/KeyTable-2.5.1/js/dataTables.keyTable.min.js" defer></script>
<script src="{$base_url}assets/datatables/KeyTable-2.5.1/js/keyTable.bootstrap4.min.js" defer></script>

<script src="{$base_url}assets/datatables/Editor-1.9.2/js/dataTables.editor.min.js" defer></script>
<script src="{$base_url}assets/datatables/Editor-1.9.2/js/editor.bootstrap4.min.js" defer></script>

<script src="{$base_url}assets/select2/js/select2.min.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-select2.js" defer></script>
<script src="{$base_url}assets/jquery-mask/jquery.mask.min.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-mask.js" defer></script>
<script src="{$base_url}assets/datatables/tcg/dt-editor-toggle.js" defer></script>

<!-- WYSIWYG editor -->
<script src="{$base_url}assets/ckeditor/ckeditor.js" defer></script>
<!-- <script src="{$base_url}assets/backend/js/vendor/summernote-bs4.min.js"></script>
<script src="{$base_url}assets/backend/js/pages/demo.summernote.js"></script> -->

<!-- full calendar -->
<script src="{$base_url}assets/fullcalendar/core/main.min.js" defer></script>

<!-- dropzone file upload -->
<script src="{$base_url}assets/dropzone/dropzone.min.js" defer></script>

<!-- dragula drag-n-drop component -->
<script src="{$base_url}assets/dragula/dragula.min.js" defer></script>

<!-- mustache templating -->
<script src="{$base_url}assets/mustache/mustache.min.js" defer></script>

<!-- toastr toast popup -->
<script src="{$base_url}assets/toastr/toastr.min.js"></script>

<!-- fontawesome -->
<script src="{$base_url}assets/fontawesome/js/fontawesome.min.js" defer charset="utf-8"></script>
<!-- <script src="{$base_url}assets/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js" defer charset="utf-8"></script> -->

<!-- jquery plugins -->
<script src="{$base_url}assets/jquery-jvectormap/jquery-jvectormap.min.js" defer></script>
<!-- <script src="{$base_url}assets/backend/js/vendor/jquery-jvectormap-world-mill-en.js"></script> -->
<script src="{$base_url}assets/bootstrap-tagsinput/bootstrap-tagsinput.min.js" defer charset="utf-8"></script>

<!--- moment -->
<script src="{$base_url}assets/moment/moment-with-locales.min.js" defer></script>

<!-- third party js -->
<!-- <script src="{$base_url}assets/backend/js/vendor/Chart.bundle.min.js"></script>
<script src="{$base_url}assets/backend/js/pages/demo.dashboard.js"></script>
<script src="{$base_url}assets/backend/js/pages/datatable-initializer.js"></script>
<script src="{$base_url}assets/backend/js/pages/demo.form-wizard.js"></script> -->

<!-- app -->
<script src="{$base_url}{$theme_prefix}/js/app.min.js" defer></script>
<script src="{$base_url}{$theme_prefix}/js/ui/component.fileupload.js" defer charset="utf-8"></script>
<script src="{$base_url}{$theme_prefix}/js/ui/component.dragula.js" defer></script>

<script src="{$base_url}{$theme_prefix}/js/custom.js" defer></script>

<!-- <script type="text/javascript">
  $(document).ready(function() {
    $(function() {
       $('.icon-picker').iconpicker();
     });
  });
</script> -->

<!-- Toastr and alert notifications scripts -->
<script type="text/javascript" defer>

  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  //Dropzone.autoDiscover = false;

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

  function notify(message) {
    toastr.info(message, "{__('Heads Up')}!");
  }

  function success_notify(message) {
    toastr.success(message, "{__('Congratulations')}!");
  }

  function error_notify(message) {
    toastr.error(message, "{__('Oh Snap')}!");
  }

  function error_required_field() {
    toastr.error("{__('Please fill all the required fields')}", "{__('Oh Snap')}!");
  }

  // render template
  function render_template(selector, options) {
      var template = $(selector).html();
      Mustache.parse(template);
      var rendered_template = Mustache.render(template, options);
      return rendered_template;
  }

    function button_status(element, handle) {
        if(handle == "loading") {
            /* loading */
            element.data('text', element.html());
            element.prop('disabled', true);
            element.html('<span class="spinner-grow spinner-grow-sm mr10"></span>'+'{__("Loading")}');
        } else {
            /* reset */
            element.prop('disabled', false);
            element.html(element.data('text'));
        }
    }


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

  // confirm
  function confirm(title, message, callback) {
      modal('#modal-confirm', { 'title': title, 'message': message });
      $("#modal-confirm-ok").click( function() {
          button_status($(this), "loading");
          if(callback) callback();
          $('#modal').modal('hide');
      });
  }

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
      } else  {
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

</script>

<script type="text/javascript">
  $(document).ready(function() {
    {if isset($flashdata) && !empty($flashdata['info_message'])}
    toastr.info("{__('Success')}!", '{$flashdata["info_message"]}');
    {/if}

    {if isset($flashdata) && !empty($flashdata['error_message'])}
      toastr.error("{__('Oh Snap')}!", '{$flashdata["error_message"]}');
    {/if}

    {if isset($flashdata) && !empty($flashdata['flash_message'])}
      toastr.success("{__('Congratulations')}!", '{$flashdata["flash_message"]}');
    {/if}    
  });
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
        <a class="btn btn-primary" href="{$base_url}login">{__("Login")}</a>
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


