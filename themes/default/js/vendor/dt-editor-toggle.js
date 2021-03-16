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
  
            if (typeof conf.attr.label === 'undefined' || conf.attr.label == null) {
                conf.attr.label = conf.label;
            }

            if (typeof conf.attr.value === 'undefined' || conf.attr.value == null) {
                conf.attr.value = 1;
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
  
            let _chkbox = conf._input.find('input');
            if (typeof conf.attr.readonly !== 'undefined' && conf.attr.readonly == true) {
              $(_chkbox).attr('readonly', true);
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
      
    })(jQuery, jQuery.fn.dataTable);
  