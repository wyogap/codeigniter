(function ($, DataTable) {
  
    if ( ! DataTable.ext.editorFields ) {
        DataTable.ext.editorFields = {};
    }
      
    var Editor = DataTable.Editor;
    var _fieldTypes = DataTable.ext.editorFields;
      
    _fieldTypes.tcg_readonly = {
        create: function ( conf ) {
            var that = this;
      
            conf._enabled = true;
      
            conf._safeId = Editor.safeId( conf.id );
  
            //default attributes
            conf.attr = $.extend(true, {}, tcg_readonly.defaults, conf.attr);

            //some time, just the field name is not safe enough!
            if (conf.attr.editorId != "") {
              conf._safeId = conf.attr.editorId + "_" + conf._safeId;
            };

            // Create the elements to use for the input
            conf._input = $(
                '<div id="'+conf._safeId+'"></div>');

            conf._input_control = $('<input type="text" class="tcg-readonly-input form-control"/>');

            conf._input.append(conf._input_control);
      
			//default value
			if (typeof conf.def !== 'undefined' && conf.def != null) {
				conf._input_control.val(conf.def);
			}

            //always read-only
            conf._input_control.attr('readonly', true);
    
            return conf._input;
        },
      
        get: function ( conf ) {
          return conf._input_control.val();
        },
      
        set: function ( conf, val ) {
			if (typeof val === 'undefined' || val == null)		val = "";
			conf._input_control.val(val).trigger("input");
			//readonly. dont trigger change event
			//conf._input.trigger("change");
        },
      
        enable: function ( conf ) {
            conf._enabled = true;
            conf._input_control.removeClass( 'disabled' ).prop("disabled", false);
        },
      
        disable: function ( conf ) {
            conf._enabled = false;
            conf._input_control.addClass( 'disabled' ).prop("disabled", true);
        },

        isEnabled: function (conf) {
          return conf._enabled;
        }
    };

    var tcg_readonly = {};

    tcg_readonly.defaults = {
      // //whether it is editable or not
      // readonly: false,
  
      //in case more than 1 editor has the same field name, this editor id can be used to distinguish it
      editorId: "",

    };
      
})(jQuery, jQuery.fn.dataTable);
  