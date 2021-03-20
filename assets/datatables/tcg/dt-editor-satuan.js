(function ($, DataTable) {
  
    if ( ! DataTable.ext.editorFields ) {
        DataTable.ext.editorFields = {};
    }
      
    var Editor = DataTable.Editor;
    var _fieldTypes = DataTable.ext.editorFields;
      
    _fieldTypes.tcg_satuan = {
        create: function ( conf ) {
            var that = this;
      
            conf._enabled = true;
            conf._satuan = 0;
            conf._safeId = Editor.safeId( conf.id );
  
            // Create the elements to use for the input
            conf._input = $(
                '<div id="'+conf._safeId+'" class="tcg-satuan-container">'
                + '<input id="'+conf._safeId+'-jumlah" type="number" min="0" class="tcg-satuan-jumlah"/>' 
                + "<span class='tcg-satuan-text'>&nbsp;&nbsp;x&nbsp;&nbsp;</span>"
                + '<input id="'+conf._safeId+'-nilai-satuan" type="number" min="0" class="tcg-satuan-nilai" tabindex="-1"/>'
                + "<span class='tcg-satuan-text'>&nbsp;&nbsp;=&nbsp;&nbsp;</span>" 
                + '<input id="'+conf._safeId+'-total" type="text" class="tcg-satuan-total" tabindex="-1"/>'
                + '</div>');
      
            conf._jumlah = $('.tcg-satuan-jumlah', conf._input);
            conf._satuan = $('.tcg-satuan-nilai', conf._input);
            conf._total = $('.tcg-satuan-total', conf._input);

            // // Use the fact that we are called in the Editor instance's scope to call
            // // the API method for setting the value when needed
            // $(conf._input).click( function () {
            //     if ( conf._enabled ) {
            //         that.set( conf.name, $(this).attr('value') );
            //     }
      
            //     return false;
            // } );

            conf._jumlah.change( function () {
              let jml = conf._jumlah.val();
              if (isNaN(jml)) {
                jml = 0;  
              }

              let satuan = conf._satuan.unmask().val();
              conf._satuan.mask(conf.mask, conf.attr);
              if (isNaN(satuan)) {
                satuan = 0;  
              }

              let total = jml * satuan;
              conf._total.val(total).trigger("input");
            });

            conf._satuan.change( function () {
              let jml = conf._jumlah.val();
              if (isNaN(jml)) {
                jml = 0;  
              }

              let satuan = conf._satuan.unmask().val();
              conf._satuan.mask(conf.mask, conf.attr);
              if (isNaN(satuan)) {
                satuan = 0;  
              }

              let total = jml * satuan;
              conf._total.val(total).trigger("input");
            });
            
            if (typeof conf.mask === 'undefined' || conf.mask == null) {
              conf.mask = "#,##0";
            }
  
            if (typeof conf.attr === 'undefined' || conf.attr == null) {
              conf.attr = {};
            }
      
            if (typeof conf.attr.reverse === 'undefined' || conf.attr.reverse == null) {
              conf.attr.reverse = true;
            }
  
            if (typeof conf.attr.type === 'undefined' || conf.attr.type == null) {
              conf.attr.type = "text";
            }
  
            conf._total.attr("type",conf.attr.type).mask(conf.mask, conf.attr);
            conf._total.attr('readonly', true);
  
            if (typeof conf.attr.editableQuantity !== 'undefined' && conf.attr.editableQuantity == false) {
              conf._jumlah.attr('readonly', true);
            }

            conf._satuan.attr("type",conf.attr.type).mask(conf.mask, conf.attr);
            if (typeof conf.attr.editableUnitPrice !== 'undefined' && conf.attr.editableUnitPrice == false) {
              conf._satuan.attr('readonly', true);
            }

            if (typeof conf.attr.readonly !== 'undefined' && conf.attr.readonly == true) {
              conf._jumlah.attr('readonly', true);
              conf._satuan.attr('readonly', true);
            }

            return conf._input;
        },
      
        get: function ( conf ) {
            value = conf._total.unmask().val();
            conf._total.mask(conf.mask, conf.attr);
            return value;
        },
      
        set: function ( conf, val ) {
          if (isNaN(val)) {
            val = 0;  
          }

          let curtotal = conf._total.unmask().val();
          conf._total.mask(conf.mask, conf.attr);

          if (val == curtotal) {
            return;
          }

          conf._total.val(val).trigger("input");

          //update quantity
          do {
            if(val == 0) {
              conf._jumlah.val("").trigger("input");
              //conf._satuan.val("").trigger("input");
              continue;
            }
  
            let jml = conf._jumlah.val();
            if (isNaN(jml)) {
              jml = 0;  
            }
  
            let satuan = conf._satuan.unmask().val();
            conf._satuan.mask(conf.mask, conf.attr);
            if (isNaN(satuan)) {
              satuan = 0;  
            }
  
            if (satuan > 0) {
              jml = val/satuan;
              conf._jumlah.val(jml).trigger("input");
            }
            else if (jml > 0) {
              satuan = val/jml;
              conf._satuan.val(satuan).trigger("input");
            }
            else {
              conf._jumlah.val(1).trigger("input");
              conf._satuan.val(val).trigger("input");
            }
          } while(false);

          //trigger change event
          conf._input.trigger("change");

        },
      
        enable: function ( conf ) {
            conf._enabled = true;
            conf._jumlah.removeClass( 'disabled' ).prop("disabled", false);
        },
      
        disable: function ( conf ) {
            conf._enabled = false;
            conf._jumlah.addClass( 'disabled' ).prop("disabled", true);
        },

        isEnabled: function (conf) {
          return conf._enabled;
        },

        quantity: function ( conf, val = null) {
          let jumlah = conf._jumlah.val();

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
          
          conf._jumlah.val(val).trigger("input");

          let satuan = conf._satuan.unmask().val();
          conf._satuan.mask(conf.mask, conf.attr);
          if (isNaN(satuan)) {
            satuan = 0;  
          }

          let total = val * satuan;

          let curtotal = conf._total.unmask().val();
          conf._total.mask(conf.mask, conf.attr);

          if (total == curtotal) {
            return;
          }

          conf._total.val(total).trigger("input");

          //trigger change event
          conf._input.trigger("change");
        },

        unitPrice: function ( conf, val = null) {
          let satuan = conf._satuan.unmask().val();
          conf._satuan.mask(conf.mask, conf.attr);

          if (val == null) {
            //get value
            return satuan;
          }

          //set value
          if (isNaN(val)) {
            val = 0;  
          }

          if (val == satuan) {
            return;
          }

          conf._satuan.val(val).trigger("input");;

          let jml = conf._jumlah.val();
          if (isNaN(jml)) {
            jml = 0;  
          }

          let total = jml * val;

          let curtotal = conf._total.unmask().val();
          conf._total.mask(conf.mask, conf.attr);

          if (total == curtotal) {
            return;
          }

          conf._total.val(total).trigger("input");

          //trigger change event
          conf._input.trigger("change");
        },

        setQuantityAndUnitPrice: function ( conf, qty, unitprice) {
          if (isNaN(qty)) {
            qty = 0;  
          }

          conf._jumlah.val(qty).trigger("input");

          if (isNaN(unitprice)) {
            unitprice = 0;  
          }

          conf._satuan.val(unitprice).trigger("input");;

          let total = qty * unitprice;

          let curtotal = conf._total.unmask().val();
          conf._total.mask(conf.mask, conf.attr);

          if (total == curtotal) {
            return;
          }

          conf._total.val(total).trigger("input");

          //trigger change event
          conf._input.trigger("change");

        },
    };
      
    })(jQuery, jQuery.fn.dataTable);
  