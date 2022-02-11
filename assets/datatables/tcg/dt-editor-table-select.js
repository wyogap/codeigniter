  function debounce(func, wait, immediate) {
  	var that = this;
  	var timeout;
  	return function () {
  		var context = that,
  			args = arguments;
  		var later = function () {
  			timeout = null;
  			if (!immediate) func.apply(context, args);
  		};
  		var callNow = immediate && !timeout;
  		clearTimeout(timeout);
  		timeout = setTimeout(later, wait);
  		if (callNow) func.apply(context, args);
  	};
  };

  jQuery.fn.dataTable.Api.register('sum()', function () {
  	return this.flatten().reduce(function (a, b) {
  		if (typeof a === 'string') {
  			a = a.replace(/[^\d.-]/g, '') * 1;
  		}
  		if (typeof b === 'string') {
  			b = b.replace(/[^\d.-]/g, '') * 1;
  		}
  		return a + b;
  	}, 0);
  });

  var DtInternalApi = $.fn.dataTable.ext.oApi;
  function dataGet(src) {
    return DtInternalApi._fnGetObjectDataFn(src);
  }

  (function ($, DataTable) {

  	if (!DataTable.ext.editorFields) {
  		DataTable.ext.editorFields = {};
  	}

    $.extend($.fn.dataTable.defaults, {
      responsive: true,
    });

  	var Editor = DataTable.Editor;
  	var _fieldTypes = DataTable.ext.editorFields;

  	var _editField = {
  		create: function (conf) {
  			var that = this;

  			conf.optionsPair = $.extend({
  				label: 'label',
  				value: 'value'
  			}, conf.optionsPair);

        $.extend( DataTable.defaults, {
            responsive: true
        } );

  			conf._enabled = true;

  			conf._safeId = Editor.safeId(conf.id);

  			//default attributes
  			conf.attr = $.extend(true, {}, tcg_table_select.defaults, conf.attr);

  			//some time, just the field name is not safe enough!
  			if (conf.attr.editorId != "") {
  				conf._safeId = conf.attr.editorId + "_" + conf._safeId;
  			};

  			//force read-only (if any)
  			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
  				conf.attr.readonly = conf.readonly;
  			}

  			if (typeof conf.tableClass !== 'undefined' && conf.tableClass != null && conf.tableClass != "") {
  				conf.attr.tableClass = conf.tableClass;
  			}

  			if (typeof conf.emptyMessage !== 'undefined' && conf.emptyMessage != null && conf.emptyMessage != "") {
  				conf.attr.emptyMessage = conf.emptyMessage;
  			}

        // Create the elements to use for the input
  			conf._input = $('<div id="' + conf._safeId + '" class="input-group"></div>');

  			conf._input_text = $('<input type="text" class="tcg-text-input form-control" id="' + conf._safeId + '-value"/>');
  			conf._input_text.attr('readonly', true);
  			conf._input.append(conf._input_text);
  			conf._input_select_btn = $('<button type="submit" class="btn btn-primary btn-select"><i class="fas fa-chevron-down"></i></button>');
  			conf._input.append(conf._input_select_btn);

  			// //default value
  			// if (typeof conf.def !== 'undefined' && conf.def != null) {
  			//   conf._input_control.val(conf.def);
  			// }

  			conf._table_container = $('<div class="table-responsive-sm dt-editor-table" id="' + conf._safeId + '-container" style="width:100%"></div>');

  			// Create html table
  			conf._table = $('<table class="table table-striped dt-responsive nowrap" id="' + conf._safeId + '-tbl" style="width:100%">');

        // add footer (if any)
        if (conf.footer) {
          $('<tfoot>')
            .append(Array.isArray(conf.footer)
              ? $('<tr>').append($.map(conf.footer, function (str) { return $('<th>').html(str); }))
              : conf.footer)
            .appendTo(conf._table);
        }

  			conf._table_container.append(conf._table);
        //conf._table_container.hide();          

  			conf._input.append(conf._table_container);

        conf._initialized = false;
        conf._reloading = false;
        conf._values = '';
        conf._additionalOptions = [];

        conf.responsive = null;
        conf.select = null;
        conf.layout = false;

        //must initialize datatable after page is fully loaded to make sure all dependency is loaded
        $(window).on('load', function() {
          tcg_table_select.dt_config_defaults.select.style = conf.multiple ? 'os' : 'single';
          tcg_table_select.dt_config_defaults.columns[0].data = conf.optionsPair.label;

          if (typeof conf.attr.emptyMessage !== 'undefined' && conf.attr.emptyMessage != "" && conf.attr.emptyMessage != null) {
            tcg_table_select.dt_config_defaults.language.emptyTable = conf.attr.emptyMessage;
          }
  
          var dt_config = $.extend(tcg_table_select.dt_config_defaults, conf.config);

          //initcompleted
          dt_config.initComplete = function( settings, json ) {
            if (!conf._initialized) return;
            _editField._reloadAjax(conf);
          };

          var dt = conf._table
            .addClass(conf.attr.tableClass)
            .width('100%')
            .DataTable(dt_config);

          dt.on('init.dt', function (e, settings) {
            var api = new DataTable.Api(settings);

            conf.dt = api;

            // Select init
            if (conf.select == null) {
              DataTable.select.init(api);

              conf.select = DataTable.select;
            }

            // // rearrange component (if necessary)
            // var containerNode = $(api.table(undefined).container());

            if (!conf.layout) {
              var field = conf._input.closest(".DTE_Field");
              field.append(conf._table_container);
  
              //enable/disable
              if (conf._enabled) {              
                _editField.enable(conf);
              }
              else {
                _editField.disable(conf);
              }

              conf.layout = true;
            }
 
            // add options directly
            if (typeof conf.options !== 'undefined' && conf.options !== null && conf.options.length > 0) {
              _editField._addOptions(conf, conf.options);
            }

            // initialize responsive
            if (conf.responsive == null) {
              conf.responsive = new $.fn.DataTable.Responsive( settings, {} );
            }
  
            conf._initialized = true;

            //in case values are set while initializing
            if (conf._values != '') {
              _editField.set(conf._values);
            }

            //   //must resize responsive table after the popup is completely opened
            //   //otherwise we can not deduce the windth of the popup
            //   conf.dt.columns.adjust();
            //   conf.dt.responsive.recalc();
          });

          // Change event for when the user does a select - `set` will do its own
          // triggering of the change for the api
          conf._userselect = false;
          dt.on('user-select', function () {
            // setTimeout(function () {
            //   conf._values = _editField.get(conf);
            //   _editField._triggerChange(conf, $(conf.dt.table().container()));
            // }, 0);

            //trigger change value
            conf._userselect = true;
          });

          dt.on( 'select', function ( e, dt, type, indexes ) {
            if ( type === 'row' && conf._userselect) {
                //trigger change value. only when selection is done by user (via ui)
                //if selection is done via api (usually in set()), the necessary action is already done there
                conf._userselect = false;

                let old_value = conf._values;
                let rows = dt.rows( indexes ).data().pluck(conf.optionsPair.value)
                .toArray();
      
                if (typeof rows === 'undefined' || rows === null)  rows='';
      
                conf._values = conf.separator || !conf.multiple
                  ? rows.join(conf.separator || ',')
                  : rows;
      
                if (old_value != conf._values) {
                  _editField._triggerChange(conf);
                }
            }
          });

          conf.dt = dt;
        })

  			conf._input_select_btn.click(function () {
  				if (conf.attr.readonly) {
  					return;
  				}

  				//toggle menu-list visibility
  				conf._table_container.toggle();

  				//change menu icon
  				if (!conf._table_container.is(':visible')) {
  					conf._input_select_btn.html("<i class='fas fa-chevron-down'/>");
  				} else {
  					conf._input_select_btn.html("<i class='fas fa-chevron-left'/>");
  				}
  			});

  			this.on('open', function () {
  				var that = this;

  				if (conf.dt.search()) {
  					conf.dt.search('').draw();
  				}
  			});

  			this.on('opened', function () {
  				var that = this;

          //must resize responsive table after the popup is completely opened
          //otherwise we can not deduce the windth of the popup
  				conf.dt.columns.adjust();
  				//conf.dt.responsive.recalc();

          //only hide it after it opened
          conf._table_container.hide();
          conf._input_select_btn.html("<i class='fas fa-chevron-down'/>");
  			});

        // allow edit options directly
        if (conf.editor) {
          conf.editor.table(dt);
          conf.editor.on('submitComplete', function (e, json, data, action) {
            if (action === 'create') {
              var _loop_1 = function (dp) {
                dt
                  .rows(function (idx, d) { return d === dp; })
                  .select();
              };
              // Automatically select the new data
              for (var _i = 0, _a = json.data; _i < _a.length; _i++) {
                var dp = _a[_i];
                _loop_1(dp);
              }
            }
            else if (action === 'edit' || action === 'remove') {
              _this._dataSource('refresh');
            }
            _editField._jumpToFirst(conf);
          });
        }
  
        conf._enabled = true;
        
        return conf._input;
  		},

  		get: function (conf) {
        if (!conf._initialized || conf._reloading) {
          return conf._values;
        }

        var rows = conf.dt
          .rows({ selected: true })
          .data()
          .pluck(conf.optionsPair.value)
          .toArray();

        if (typeof rows === 'undefined' || rows === null)  rows='';

        return conf.separator || !conf.multiple
          ? rows.join(conf.separator || ',')
          : rows;
  		},

      getRows: function(conf) {
        if (!conf._initialized || conf._reloading) {
          return '';
        }

        var rows = conf.dt
          .rows({ selected: true })
          .data()
          .toArray();

        if (typeof rows === 'undefined' || rows === null)  rows=[];

        return rows;
      },

      getLabel: function (conf) {
        if (!conf._initialized || conf._reloading) {
          return '';
        }

        var rows = conf.dt
          .rows({ selected: true })
          .data()
          .pluck(conf.optionsPair.label)
          .toArray();

        if (typeof rows === 'undefined' || rows === null)  rows='';

        return conf.separator || !conf.multiple
          ? rows.join(conf.separator || ',')
          : rows;
  		},

  		set: function (conf, val, localUpdate) {
        var that = this;

        conf._values = val;

        // if we are initializing, store the value temporarily
        if (!conf._initialized || conf._reloading) {
          return;
        }

        // Convert to an array of values - works for both single and multiple
        if (conf.multiple && conf.separator && !Array.isArray(val)) {
          val = typeof val === 'string' ?
            val.split(conf.separator) :
            [];
        }
        else if (!Array.isArray(val)) {
          val = [val];
        }

        // var data = conf.dt.rows().data();
        // console.log(data);

        // if ( ! localUpdate ) {
        // 	conf._lastSet = val;
        // }
        var valueFn = dataGet(conf.optionsPair.value);
        conf.dt.rows({ selected: true }).deselect();
        conf.dt.rows(function (idx, data, node) { return val.indexOf(valueFn(data)) !== -1; }).select();

        // Jump to the first page with a selected row (if there are any)
        _editField._jumpToFirst(conf);

        // Update will call change itself, otherwise multiple might be called
        if (!localUpdate) {
          _editField._triggerChange(conf);
        }
  		},

      enable: function (conf) {
  			conf._enabled = true;

        conf._input_select_btn.prop('disabled', false);;

        if (!conf._initialized) {
          return;
        }

        conf.dt.select.style(conf.multiple ? 'os' : 'single');
        conf.dt.buttons().container().css('display', 'block');
  		},

  		disable: function (conf) {
  			conf._enabled = false;

        conf._input_select_btn.prop('disabled', true);

        if (!conf._initialized) {
          return;
        }

        conf.dt.select.style('api');
        conf.dt.buttons().container().css('display', 'none');
  		},

  		dataTable: function (conf) {
        if (!conf._initialized) {
          return null;
        }

        return conf.dt;
  		},

  		isEnabled: function (conf) {
  			return conf._enabled;
  		},

      update: function (conf, options, append) {
        if (typeof options === "undefined" || options === null || options.length == 0) return;

        _editField._addOptions(conf, options, append);

        //no need to store additionalOptions. keep it simple. 
        //when using options, then ajax data is not used. and vice versa
        // //store any append
        // if(append) {
        //   conf._additionalOptions.concat(options);
        // } else {
        //   conf._additionalOptions = [];
        // }

        // Attempt to set the last selected value (set by the API or the end user, they get equal priority)
        if (typeof conf._values !== "undefined" && conf._values != "" && conf._values !== null) {
          _editField.set(conf, conf._values, true);
        }
        _editField._triggerChange(conf);
      },

      reload: function (conf, url) {
        if (typeof url === 'undefined' || url == "" || url === null) return;

        let old_url = conf.dt.ajax.url();
        if (old_url == url) return;

        let old_value = conf._values;
        conf._reloading = true;

        conf.dt.ajax.url(url);
        conf.dt.ajax.reload(function(json) {
          conf._reloading = false;

          if ((typeof conf._values !== 'undefined' && conf._values.length>0 && conf._values !== null)
                || (conf._values.length == 0 && old_value != conf._values)) {
            _editField.set(conf, conf._values, true);
          }

          //only trigger change when value changed since reload start
          if (old_value != conf._values) {
            _editField._triggerChange(conf);
          }
        }, true);
      },

      canReturnSubmit: function (conf, node) {
        return true;
      },

      // add options directly
      _addOptions: function (conf, options, append) {
        if (typeof options === 'undefined' || options.length==0 || options === null) return;

        if (append === void 0) { append = false; }

        var dt = conf.dt;
        if (!append) {
          dt.clear();
        }
        dt.rows.add(options).draw();
      },

      _jumpToFirst: function (conf) {
        if (!conf._initialized) {
          return;
        }

        // Find which page in the table the first selected row is
        var idx = conf.dt.row({ order: 'applied', selected: true }).index();
        var page = 0;
        if (typeof idx === 'number') {
          var pageLen = conf.dt.page.info().length;
          var pos = conf.dt.rows({ order: 'applied' }).indexes().indexOf(idx);
          page = pageLen > 0
            ? Math.floor(pos / pageLen)
            : 0;
        }
        conf.dt.page(page).draw(false);
      },

      _triggerChange: function (conf) {
        var that = this;
        //var input = $(conf.dt.table().container());

        setTimeout(function () {
          //get value
          let label = _editField.getLabel(conf);

          //update label
          conf._input_text.val(label);

          //trigger event
          conf._input.trigger('change', { editor: true, editorSet: true }); // editorSet legacy
        }, 0);
      }

  	};

  	var tcg_table_select = {};

  	tcg_table_select.defaults = {
  		//whether it is editable or not
  		readonly: false,

  		//in case more than 1 editor has the same field name, this editor id can be used to distinguish it
  		editorId: "",

      //custom css class for table
      tableClass: "",

      //message for when there is no options available
      emptyMessage: "",
  	};

  	tcg_table_select.dt_config_defaults = {
  		buttons: [],
  		columns: [{
  			data: 'label',
  			title: 'Label'
  		}],
  		// deferRender: true,
  		// responsive: true,
  		responsive: true,
  		scrollX: false,
  		//dom: 'fiBtp',
  		dom: "<'row'<'col-sm-12'fr>>t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
  		//dom: 'ftip',
  		pageLength: 5,
  		paging: true,
      serverSide: false,
      processing: true,
  		language: {
  			"processing": "Sedang proses...",
  			"lengthMenu": "Tampilan _MENU_ entri",
  			"zeroRecords": "Tidak ditemukan data yang sesuai",
  			"info": "Tampilan _START_ - _END_ dari _TOTAL_ entri",
  			"infoEmpty": "Tampilan 0 hingga 0 dari 0 entri",
  			"infoFiltered": "",
  			//"infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
  			"infoPostFix": "",
  			"loadingRecords": "Loading...",
  			"emptyTable": "Tidak ditemukan data yang sesuai",
  			"search": "",
  			searchPlaceholder: 'Cari',
  			"url": "",
  			"paginate": {
  				"first": "<<",
  				"previous": "<",
  				"next": ">",
  				"last": ">>"
  			},
  			aria: {
  				sortAscending: ": klik untuk mengurutkan dari bawah ke atas",
  				sortDescending: ": klik untuk mengurutkan dari atas ke bawah"
  			},
  			select: {
  				rows: ""
  			}
  		},
  		lengthChange: false,
  		select: {
  			style: 'single'
  		},
  	};

    _fieldTypes.tcg_table_select = _editField;

  })(jQuery, jQuery.fn.dataTable);
