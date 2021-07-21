(function ($, DataTable) {
  
    if ( ! DataTable.ext.editorFields ) {
        DataTable.ext.editorFields = {};
    }
      
    var Editor = DataTable.Editor;
    var _fieldTypes = DataTable.ext.editorFields;
      
    _fieldTypes.tcg_toggle = {
        create: function ( conf ) {
            var that = this;
      
            conf._enabled = true;
      
            conf._safeId = Editor.safeId( conf.id );
  
			//default attributes
			conf.attr = $.extend(true, {}, tcg_toggle.defaults, conf.attr);

			//some time, just the field name is not safe enough!
			if (conf.attr.editorId != "") {
				conf._safeId = conf.attr.editorId + "_" + conf._safeId;
			};

			//force read-only (if any)
			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
				conf.attr.readonly = conf.readonly;
			}
			
            //default value
            if (typeof conf.attr.value === 'undefined' || conf.attr.value == null) {
                conf.attr.value = 0;
            }

            if (typeof conf.def !== 'undefined') {
                if (conf.def)       conf.attr.value = 1;
                else                conf.attr.value = 0;
            }

            // Create the elements to use for the input
            conf._input = $(
                '<label class="switch" id="'+conf._safeId+'" for="'+conf.name+'"> <input type="checkbox" name="'+conf.name+'" id="'+conf._safeId+'_checkbox"> <span class="slider round" id="'+conf._safeId+'_slider"></span></label>'
                );
      
            // // Use the fact that we are called in the Editor instance's scope to call
            // // the API method for setting the value when needed
            // $(conf._input).click( function () {
            //     if ( conf._enabled ) {
            //         that.set( conf.name, $(this).attr('value') );
            //     }
      
            //     return false;
            // } );
  
            let _chkbox = conf._input.find('input');
            if (typeof conf.attr.readonly !== 'undefined' && conf.attr.readonly == true) {
              $(_chkbox).attr('readonly', true);
            }

            //default value
            if (conf.attr.value) {
                _chkbox[0].checked = true;
                _chkbox.trigger("change");
            }
    
            //register event handler
            conf._input.on('click', function() {
                if (conf.attr.readonly)     return;

                let _chkbox = $(this).find('input');

                if(_chkbox[0].checked)     _chkbox[0].checked = false;
                else                       _chkbox[0].checked = true;

                _chkbox.trigger("change");
            });

            //easy access
            conf._checkbox = conf._input.find("input");

            if (conf.attr.readonly) {
                conf._checkbox.attr('readonly', true);
                conf._input.addClass('readonly');
            }

            return conf._input;
        },
      
        get: function ( conf ) {
            let _chkbox = conf._input.find('input');
            let checked = _chkbox[0].checked;
            return checked ? 1 : 0;
        },
      
        set: function ( conf, val ) {
			if (typeof val === 'undefined' || val == null)		val = "";
            let checked = (val == 1) ? true : false;

            let _chkbox = conf._input.find('input');
            if (_chkbox[0].checked != checked) {
                _chkbox[0].checked = checked;
                _chkbox.trigger("change");
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

	var tcg_toggle = {};

	tcg_toggle.defaults = {
        //whether it is editable or not
        readonly: false,
    
		//in case more than 1 editor has the same field name, this editor id can be used to distinguish it
		editorId: "",

		//default value
		value: false,
    };

    
})(jQuery, jQuery.fn.dataTable);
  