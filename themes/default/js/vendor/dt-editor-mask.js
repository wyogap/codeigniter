(function ($, DataTable) {
  
    if ( ! DataTable.ext.editorFields ) {
        DataTable.ext.editorFields = {};
    }
      
    var Editor = DataTable.Editor;
    var _fieldTypes = DataTable.ext.editorFields;
      
    _fieldTypes.tcg_mask = {
        create: function ( conf ) {
            var that = this;
      
            conf._enabled = true;
      
            conf._safeId = Editor.safeId( conf.id );
  
            //default attributes
            conf.attr = $.extend(true, {}, tcg_unit_price.defaults, conf.attr);

            //legacy setting
            if (typeof conf.attr.mask !== 'undefined' && conf.attr.mask != null && conf.attr.mask != "") {
              conf.attr.mask = conf.attr.mask;
            }

            //some time, just the field name is not safe enough!
            if (conf.attr.editorId != "") {
              conf._safeId = conf.attr.editorId + "_" + conf._safeId;
            };

            // Create the elements to use for the input
            conf._input = $(
                '<div id="'+conf._safeId+'"></div>');

            conf._masked_input = $('<input type="text" class="tcg-mask-placeholder"/>');

            conf._input.append(conf._masked_input);
      
            // // Use the fact that we are called in the Editor instance's scope to call
            // // the API method for setting the value when needed
            // $(conf._input).click( function () {
            //     if ( conf._enabled ) {
            //         that.set( conf.name, $(this).attr('value') );
            //     }
      
            //     return false;
            // } );
  
            if (conf.attr.mask != "") {
              conf._masked_input.attr("type",conf.attr.type).mask(conf.attr.mask, conf.attr);
            }

            return conf._input;
        },
      
        get: function ( conf ) {
          if (conf.attr.mask == "") {
            return conf._masked_input.val();
          }

          value = conf._masked_input.unmask().val();
          conf._masked_input.mask(conf.attr.mask, conf.attr);
          return value;
        },
      
        set: function ( conf, val ) {
          let curtotal = conf._masked_input.unmask().val();
          conf._masked_input.mask(conf.attr.mask, conf.attr);

          if (val == curtotal) {
            return;
          }

          conf._masked_input.val(val).trigger("input");

          //trigger change event
          conf._input.trigger("change");
        },
      
        enable: function ( conf ) {
            conf._enabled = true;
            conf._masked_input.removeClass( 'disabled' ).prop("disabled", false);
        },
      
        disable: function ( conf ) {
            conf._enabled = false;
            conf._masked_input.addClass( 'disabled' ).prop("disabled", true);
        },

        isEnabled: function (conf) {
          return conf._enabled;
        }
    };

    var tcg_mask = {};

    tcg_mask.defaults = {
      //whether it is editable or not
      readonly: false,
  
      //field type
      type: 'text',

      //field mask for unit price
      mask: "#,##0",
  
      //whether mask is applied in reverse
      reverse: true,

      //in case more than 1 editor has the same field name, this editor id can be used to distinguish it
      editorId: "",

    };
      
})(jQuery, jQuery.fn.dataTable);
  