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

    {if !empty($use_select2)}
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
    {/if}
    
</script>
