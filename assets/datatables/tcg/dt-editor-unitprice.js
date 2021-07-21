(function ($, DataTable) {
  
    if ( ! DataTable.ext.editorFields ) {
        DataTable.ext.editorFields = {};
    }
      
    var Editor = DataTable.Editor;
    var _fieldTypes = DataTable.ext.editorFields;
      
    _fieldTypes.tcg_unitprice = {
        create: function ( conf ) {
            var that = this;
      
            conf._enabled = true;
            conf._safeId = Editor.safeId( conf.id );
  
            //default attributes
            conf.attr = $.extend(true, {}, tcg_unitprice.defaults, conf.attr);

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
                '<div id="'+conf._safeId+'" class="tcg-unitprice-container">'
                + '<input id="'+conf._safeId+'-quantity" type="number" min="0" class="tcg-unitprice-quantity form-control"/>' 
                + "<span class='tcg-unitprice-text'>&nbsp;&nbsp;x&nbsp;&nbsp;</span>"
                + '<input id="'+conf._safeId+'-unitprice" type="number" min="0" class="tcg-unitprice-unitprice form-control" tabindex="-1"/>'
                + "<span class='tcg-unitprice-text'>&nbsp;&nbsp;=&nbsp;&nbsp;</span>" 
                + '<input id="'+conf._safeId+'-total" type="text" class="tcg-unitprice-total form-control" tabindex="-1"/>'
                + '</div>');
      
            conf._quantity = $('.tcg-unitprice-quantity', conf._input);
            conf._unitprice = $('.tcg-unitprice-unitprice', conf._input);
            conf._total = $('.tcg-unitprice-total', conf._input);

            // // Use the fact that we are called in the Editor instance's scope to call
            // // the API method for setting the value when needed
            // $(conf._input).click( function () {
            //     if ( conf._enabled ) {
            //         that.set( conf.name, $(this).attr('value') );
            //     }
      
            //     return false;
            // } );

			//default value
			if (!isNaN(conf.def)) {
				conf._total.val(conf.def).trigger("input");
				conf._quantity.val(1);
				conf._unitprice.val(conf.def).trigger("input");
			}
			
            conf._quantity.change( function () {
              let qty = conf._quantity.val();
              if (isNaN(qty)) {
                qty = 0;  
              }

              let unitprice = conf._unitprice.unmask().val();
              conf._unitprice.mask(conf.attr.mask, conf.attr);
              if (isNaN(unitprice)) {
                unitprice = 0;  
              }

              let total = qty * unitprice;
              conf._total.val(total).trigger("input");
            });

            conf._unitprice.change( function () {
              let qty = conf._quantity.val();
              if (isNaN(qty)) {
                qty = 0;  
              }

              let unitprice = conf._unitprice.unmask().val();
              conf._unitprice.mask(conf.attr.mask, conf.attr);
              if (isNaN(unitprice)) {
                unitprice = 0;  
              }

              let total = qty * unitprice;
              conf._total.val(total).trigger("input");
            });
              
            conf._total.attr("type",conf.attr.type).mask(conf.attr.mask, conf.attr);
            conf._total.attr('readonly', true);
  
            if (conf.attr.editableQuantity == false) {
              conf._quantity.attr('readonly', true);
            }

            conf._unitprice.attr("type",conf.attr.type).mask(conf.attr.mask, conf.attr);
            if (conf.attr.editableUnitPrice == false) {
              conf._unitprice.attr('readonly', true);
            }

            if (conf.attr.readonly == true) {
              conf._quantity.attr('readonly', true);
              conf._unitprice.attr('readonly', true);
            }

            return conf._input;
        },
      
        get: function ( conf ) {
            value = conf._total.unmask().val();
            conf._total.mask(conf.attr.mask, conf.attr);
            return value;
        },
      
        set: function ( conf, val ) {
          if (isNaN(val)) {
            val = 0;  
          }

          let curtotal = conf._total.unmask().val();
          conf._total.mask(conf.attr.mask, conf.attr);

          if (val == curtotal) {
            return;
          }

          conf._total.val(val).trigger("input");

          //update quantity
          do {
            if(val == 0) {
              conf._quantity.val("").trigger("input");
              //conf._unitprice.val("").trigger("input");
              continue;
            }
  
            let qty = conf._quantity.val();
            if (isNaN(qty)) {
              qty = 0;  
            }
  
            let unitprice = conf._unitprice.unmask().val();
            conf._unitprice.mask(conf.attr.mask, conf.attr);
            if (isNaN(unitprice)) {
              unitprice = 0;  
            }
  
            if (unitprice > 0) {
              qty = val/unitprice;
              conf._quantity.val(qty).trigger("input");
            }
            else if (qty > 0) {
              unitprice = val/qty;
              conf._unitprice.val(unitprice).trigger("input");
            }
            else {
              conf._quantity.val(1).trigger("input");
              conf._unitprice.val(val).trigger("input");
            }
          } while(false);

          //trigger change event
          conf._input.trigger("change");

        },
      
        enable: function ( conf ) {
            conf._enabled = true;
            conf._quantity.removeClass( 'disabled' ).prop("disabled", false);
        },
      
        disable: function ( conf ) {
            conf._enabled = false;
            conf._quantity.addClass( 'disabled' ).prop("disabled", true);
        },

        isEnabled: function (conf) {
          return conf._enabled;
        },

        quantity: function ( conf, val = null) {
          let jumlah = conf._quantity.val();

          if (val == null) {
            //get data
            return jumlah;
          }

          if (isNaN(val)) {
            val = 0;  
          }

          if (val == jumlah) {
            return;
          }
          
          conf._quantity.val(val).trigger("input");

          let unitprice = conf._unitprice.unmask().val();
          conf._unitprice.mask(conf.attr.mask, conf.attr);
          if (isNaN(unitprice)) {
            unitprice = 0;  
          }

          let total = val * unitprice;

          let curtotal = conf._total.unmask().val();
          conf._total.mask(conf.attr.mask, conf.attr);

          if (total == curtotal) {
            return;
          }

          conf._total.val(total).trigger("input");

          //trigger change event
          conf._input.trigger("change");
        },

        unitPrice: function ( conf, val = null) {
          let unitprice = conf._unitprice.unmask().val();
          conf._unitprice.mask(conf.attr.mask, conf.attr);

          if (val == null) {
            //get value
            return unitprice;
          }

          //set value
          if (isNaN(val)) {
            val = 0;  
          }

          if (val == unitprice) {
            return;
          }

          conf._unitprice.val(val).trigger("input");;

          let qty = conf._quantity.val();
          if (isNaN(qty)) {
            qty = 0;  
          }

          let total = qty * val;

          let curtotal = conf._total.unmask().val();
          conf._total.mask(conf.attr.mask, conf.attr);

          if (total == curtotal) {
            return;
          }

          conf._total.val(total).trigger("input");

          //trigger change event
          conf._input.trigger("change");
        },

    };
      
    var tcg_unitprice = {};

    tcg_unitprice.defaults = {
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

      //whether quantity is editable
      editableQuantity: true,

      //whether unit-price is editable
      editableUnitPrice: false,
    };
    

})(jQuery, jQuery.fn.dataTable);
  