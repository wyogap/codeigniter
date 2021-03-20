(function ($, DataTable) {
  
  if ( ! DataTable.ext.editorFields ) {
      DataTable.ext.editorFields = {};
  }
    
  var Editor = DataTable.Editor;
  var _fieldTypes = DataTable.ext.editorFields;
    
  _fieldTypes.tcg_editor = {
      create: function ( conf ) {
          var that = this;
    
          conf._enabled = true;
    
          conf._safeId = Editor.safeId( conf.id );

          //default attributes
          conf.attr = $.extend(true, {}, tcg_editor.defaults, conf.attr);

          //some time, just the field name is not safe enough!
          if (conf.attr.editorId != "") {
            conf._safeId = conf.attr.editorId + "_" + conf._safeId;
          };

          // Create the elements to use for the input
          conf._input = $(
              '<div id="'+conf._safeId+'"></div>');

          conf._input_control = $('<textarea class="tcg-textarea-input form-control"></textarea>');

          conf._input.append(conf._input_control);
    
          if (conf.attr.readonly == true) {
            conf._input_control.attr('readonly', true);
          }

          if (conf.attr.rows !== null && conf.attr.rows > 0) {
            conf._input_control.attr('rows', conf.attr.rows);
          }
          
          return conf._input;
      },
    
      get: function ( conf ) {
        return conf._input_control.val();
      },
    
      set: function ( conf, val ) {
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

  var tcg_editor = {};

  tcg_editor.defaults = {
    //whether it is editable or not
    readonly: false,

    //number of rows
    rows: null,

    //in case more than 1 editor has the same field name, this editor id can be used to distinguish it
    editorId: "",

    //whether to convert to ckeditor WYSWYG
    ckeditor: false,

  };
    
})(jQuery, jQuery.fn.dataTable);
