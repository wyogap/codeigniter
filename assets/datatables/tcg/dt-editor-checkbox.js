(function ($, DataTable) {
  
    if ( ! DataTable.ext.editorFields ) {
        DataTable.ext.editorFields = {};
    }
      
    var Editor = DataTable.Editor;
    var _fieldTypes = DataTable.ext.editorFields;
      
    _fieldTypes.tcg_checkbox = {
        create: function ( conf ) {
            var that = this;
      
            conf._enabled = true;
      
            conf._safeId = Editor.safeId( conf.id );
  
			//default attributes
			conf.attr = $.extend(true, {}, tcg_checkbox.defaults, conf.attr);

			//some time, just the field name is not safe enough!
			if (conf.attr.editorId != "") {
				conf._safeId = conf.attr.editorId + "_" + conf._safeId;
			};

            //default label -> use the field label
            if (conf.attr.label == null) {
                conf.attr.label = conf.label;
            }

			//force read-only (if any)
			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
				conf.attr.readonly = conf.readonly;
			}
			
            // Create the elements to use for the input
            conf._input = $(
                '<div id="'+conf._safeId+'"><input id="'+conf._safeId+'_input" type="checkbox" value="' +conf.attr.value+ '"/><label for="'+conf._safeId+'_input">' +conf.attr.label+ '</label></div>');
      
            // // Use the fact that we are called in the Editor instance's scope to call
            // // the API method for setting the value when needed
            // $(conf._input).click( function () {
            //     if ( conf._enabled ) {
            //         that.set( conf.name, $(this).attr('value') );
            //     }
      
            //     return false;
            // } );
  
            //readonly checkbox
            let _chkbox = conf._input.find('input');
            if (conf.attr.readonly == true) {
                $(_chkbox).attr('readonly', true);
                _chkbox.on("click", function(e) {
                    return false;
                })
            }
			
            //default value
            let checked = (conf.def == conf.attr.value) ? true : false;
            if (_chkbox.prop("checked") != checked) {
                _chkbox.prop("checked", checked).trigger("change");
            }
    
            return conf._input;
        },
      
        get: function ( conf ) {
            let checked = conf._input.find('input').prop("checked");
            return checked ? conf.attr.value : '';
        },
      
        set: function ( conf, val ) {
			if (typeof val === 'undefined' || val == null)		val = "";
          let checked = (val == conf.attr.value) ? true : false;

          let chkbox = conf._input.find('input');
          if (chkbox.prop("checked") != checked) {
            chkbox.prop("checked", checked).trigger("change");
          }
        },
      
        enable: function ( conf ) {
            conf._enabled = true;
            $(conf._input).find('input').removeClass( 'disabled' ).prop("disabled", false);
        },
      
        disable: function ( conf ) {
            conf._enabled = false;
            $(conf._input).find('input').addClass( 'disabled' ).prop("disabled", true);
        }
    };

    var tcg_checkbox = {};

	tcg_checkbox.defaults = {
        //whether it is editable or not
        readonly: false,
    
		//in case more than 1 editor has the same field name, this editor id can be used to distinguish it
		editorId: "",

        //label
        label: null,

		//default value
		value: 1,
    };

})(jQuery, jQuery.fn.dataTable);
  