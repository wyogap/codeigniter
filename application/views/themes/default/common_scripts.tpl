<script type="text/javascript">
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

    // // confirm
    // function confirm(title, message, callback) {
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