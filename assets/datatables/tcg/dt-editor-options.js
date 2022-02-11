(function ($, DataTable) {
  
    if ( ! DataTable.ext.editorFields ) {
        DataTable.ext.editorFields = {};
    }
      
    var Editor = DataTable.Editor;
    var _fieldTypes = DataTable.ext.editorFields;
      
    _fieldTypes.tcg_option_radio = {
        create: function ( conf ) {
            var that = this;
      
            conf._enabled = true;
      
            conf._safeId = Editor.safeId( conf.id );
  
            //default attributes
            conf.attr = $.extend(true, {}, tcg_options.defaults, conf.attr);

            //some time, just the field name is not safe enough!
            if (conf.attr.editorId != "") {
              conf._safeId = conf.attr.editorId + "_" + conf._safeId;
            };

			//force read-only (if any)
			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
				conf.attr.readonly = conf.readonly;
			}

			if (typeof conf.options !== 'undefined' && conf.options != null) {
				conf.attr.options = conf.options;
			}
			
            // Create the elements to use for the input
            conf._input = $(
                '<div id="'+conf._safeId+'" class="tcg-options"></div>');

            conf._input_control = $('<ul/>');
			conf.attr.options.forEach(function(data, idx, array) {
				let dom = $('' 
				+ '<li>'
                + '    <label class="option block">'
                + '        <input class="input_option" type="radio" name="' +conf._safeId+ '_input" id="' +conf._safeId+ '_input" value="' +data['value']+ '">'
                + '        <span class="radio"></span>'
                + '        <div class="option-text" style="display: inline-block">' +data['text']+ '</div>'
                + '    </label>'
                + '</li>');
				conf._input_control.append(dom);
			});

            conf._input.append(conf._input_control);
      
            if (conf.attr.readonly == true) {
              conf._input_control.find('input').addClass( 'disabled' ).prop('disabled',true);
			  conf._input.addClass( 'disabled' );
            }
    
            return conf._input;
        },
      
        get: function ( conf ) {
			let val = conf._input_control.find('#'+conf._safeId+ '_input:checked').val();
			if (typeof val === 'undefined' || val == null)	return '';
			return val;
        },
      
        set: function ( conf, val ) {
			let input = conf._input_control.find('#'+conf._safeId+ '_input');

			//reset the value
			input.removeAttr('checked');

			if (typeof val === 'undefined' || val == null || val == '') {
				return;
			}
			
			let selected = conf._input_control.find('#'+conf._safeId+ '_input[value=' +val+ ']');
			selected.prop('checked', true);

			//trigger change event
			conf._input.trigger("change");
        },
      
        enable: function ( conf ) {
            conf._enabled = true;
            conf._input_control.find('input').removeClass( 'disabled' ).prop("disabled", false);
			conf._input.removeClass( 'disabled' );
        },
      
        disable: function ( conf ) {
            conf._enabled = false;
            conf._input_control.find('input').addClass( 'disabled' ).prop('disabled',true);
			conf._input.addClass( 'disabled' );
        },

        isEnabled: function (conf) {
          return conf._enabled;
        }
    };

    _fieldTypes.tcg_option_checkbox = {
        create: function ( conf ) {
            var that = this;
      
            conf._enabled = true;
      
            conf._safeId = Editor.safeId( conf.id );
  
            //default attributes
            conf.attr = $.extend(true, {}, tcg_options.defaults, conf.attr);

            //some time, just the field name is not safe enough!
            if (conf.attr.editorId != "") {
              conf._safeId = conf.attr.editorId + "_" + conf._safeId;
            };

			//force read-only (if any)
			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
				conf.attr.readonly = conf.readonly;
			}

			if (typeof conf.options !== 'undefined' && conf.options != null) {
				conf.attr.options = conf.options;
			}
			
            // Create the elements to use for the input
            conf._input = $(
                '<div id="'+conf._safeId+'" class="tcg-options"></div>');

            conf._input_control = $('<ul/>');
			conf.attr.options.forEach(function(data, idx, array) {
				let dom = $(''
				+ '<li>'
                + '    <label class="option block">'
                + '        <input class="input_option" type="checkbox" name="' +conf._safeId+ '_input" id="' +conf._safeId+ '_input" value="' +data['value']+ '">'
                + '        <span class="checkbox"></span>'
                + '        <div class="option-text" style="display: inline-block">' +data['text']+ '</div>'
                + '    </label>'
                + '</li>');
				conf._input_control.append(dom);
			});

            conf._input.append(conf._input_control);
      
            if (conf.attr.readonly == true) {
              conf._input_control.find('input').addClass( 'disabled' ).prop('disabled',true);
			  conf._input.addClass( 'disabled' );
            }
    
            return conf._input;
        },
      
        get: function ( conf ) {
            let values = [];
            conf._input_control.find('#'+conf._safeId+ '_input:checked').each(function(){
                values.push($(this).val());
            });
			return values.join(',');
        },
      
        set: function ( conf, val ) {
			let input = conf._input_control.find('#'+conf._safeId+ '_input');

			//reset the value
			input.removeAttr('checked');

			if (typeof val === 'undefined' || val == null || val == '') {
				return;
			}
			
			let values = val.split(',');
			values.forEach(function(data, idx, arr) {
				let selected = conf._input_control.find('#'+conf._safeId+ '_input[value=' +data+ ']');
				selected.prop('checked', true);
			});

			//trigger change event
			conf._input.trigger("change");
        },
      
        enable: function ( conf ) {
            conf._enabled = true;
            conf._input_control.find('input').removeClass( 'disabled' ).prop("disabled", false);
			conf._input.removeClass( 'disabled' );
        },
      
        disable: function ( conf ) {
            conf._enabled = false;
            conf._input_control.find('input').addClass( 'disabled' ).prop('disabled',true);
			conf._input.addClass( 'disabled' );
        },

        isEnabled: function (conf) {
          return conf._enabled;
        }
    };

    var tcg_options = {};

    tcg_options.defaults = {
      //whether it is editable or not
      readonly: false,
  
      //in case more than 1 editor has the same field name, this editor id can be used to distinguish it
      editorId: "",

		options: [],
    };
      
})(jQuery, jQuery.fn.dataTable);
  