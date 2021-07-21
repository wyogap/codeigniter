(function ($, DataTable) {
  
  if ( ! DataTable.ext.editorFields ) {
      DataTable.ext.editorFields = {};
  }
    
  var Editor = DataTable.Editor;
  var _fieldTypes = DataTable.ext.editorFields;
    
  _fieldTypes.tcg_date = {
      create: function ( conf ) {
          var that = this;
    
          conf._enabled = true;
    
          conf._safeId = Editor.safeId( conf.id );

          //default attributes
          conf.attr = $.extend(true, {}, tcg_date.defaults, conf.attr);

          //some time, just the field name is not safe enough!
          if (conf.attr.editorId != "") {
            conf._safeId = conf.attr.editorId + "_" + conf._safeId;
          };

			//force read-only (if any)
			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
				conf.attr.readonly = conf.readonly;
			}
			
          // Create the elements to use for the input
          conf._input = $(
              '<div id="'+conf._safeId+'"></div>');

          conf._input_control = $('<input type="date" class="tcg-text-input form-control"/>');

          conf._input.append(conf._input_control);
    
		  //TODO: default value
		  if (typeof conf.def !== 'undefined' && conf.def !== null && conf.def != "") {
			let val = moment(conf.def).format("YYYY-MM-DD");
			conf._input_control.val(val);
		  }
		  else {
			let val = moment().format("YYYY-MM-DD");
			conf._input_control.val(val);
		  }
		  
          if (conf.attr.readonly == true) {
            conf._input_control.attr('readonly', true);
          }
  
          return conf._input;
      },
    
      get: function ( conf ) {
        return conf._input_control.val();
      },
    
      set: function ( conf, val ) {
		  //TODO: set value not working yet!
			if (typeof val === 'undefined' || val == null)		val = "";
			
			val = moment(val).format("YYYY-MM-DD");
        conf._input_control.val(val).trigger("input");

        //trigger change event
        conf._input.trigger("change");
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

  var tcg_date = {};

  tcg_date.defaults = {
    //whether it is editable or not
    readonly: false,

    //in case more than 1 editor has the same field name, this editor id can be used to distinguish it
    editorId: "",

  };
    
})(jQuery, jQuery.fn.dataTable);
