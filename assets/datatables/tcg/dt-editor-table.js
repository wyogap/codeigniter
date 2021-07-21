  function debounce(func, wait, immediate) {
    var that = this;
    var timeout;
    return function() {
      var context = that, args = arguments;
      var later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  };

  jQuery.fn.dataTable.Api.register( 'sum()', function() {
    return this.flatten().reduce( function ( a, b ) {
        if ( typeof a === 'string' ) {
            a = a.replace(/[^\d.-]/g, '') * 1;
        }
        if ( typeof b === 'string' ) {
            b = b.replace(/[^\d.-]/g, '') * 1;
        }
        return a + b;
    }, 0 );
  });

  (function ($, DataTable) {
  
  if ( ! DataTable.ext.editorFields ) {
      DataTable.ext.editorFields = {};
  }
    
  var Editor = DataTable.Editor;
  var _fieldTypes = DataTable.ext.editorFields;
    
  _fieldTypes.tcg_table = {
      create: function ( conf ) {
          var that = this;
    
          conf._enabled = true;
    
          conf._safeId = Editor.safeId( conf.id );

          //default attributes
          conf.attr = $.extend(true, {}, tcg_table.defaults, conf.attr);

          //some time, just the field name is not safe enough!
          if (conf.attr.editorId != "") {
            conf._safeId = conf.attr.editorId + "_" + conf._safeId;
          };

			//force read-only (if any)
			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
				conf.attr.readonly = conf.readonly;
			}
			
          conf._input = $('<div class="table-responsive dt-editor-table" id="' +conf._safeId+ '"></div>');

          // Create html table
          conf._table = $(
              '<table class="display" id="' +conf._safeId+ '-tbl" style="width:100%">');

          var tr = $('<tr></tr>');

          //row id
          var th = $('<th class="text-center dt-col-action"><a href="" class="btn btn-primary btn-sm dt-editor-create" type="button" id="' +conf._safeId+ '-create"><i class="fas fa-plus"></i></a></th>');
          tr.append(th);

          th = $('<th class="text-center">RowID</th>');
          tr.append(th);

          //columns
          conf.columns.forEach(function(col) {
            th = $('<th class="text-center">' +col.title+ '</th>');
            tr.append(th);
          });
    
          //header
          conf._table.append(
              $('<thead></thead>').append(tr)
            );

          conf.responsive = false;

          conf._input.append(conf._table);

          //rowidx
          conf._idx = 1;

          //build column list and templates (start with internal _rowid_)
          conf._template = {};
          conf._template['_rowid_'] = 0;

          let columns = [];
          let column = {
            data: null,
            //defaultContent: "",
            defaultContent: '<a href="" class="btn btn-danger btn-sm dt-editor-remove"><i class="fas fa-trash"></i></a>',
            className: 'dt-body-center dt-col-action',
            sortable: false
          };
          columns.push(column);

          column = {
            data: "_rowid_",
            className: 'dt-body-right readonly-column'
          };
          columns.push(column);

          let fields = [];
          let field = {
              "label": "Id:",
              "name": "_rowid_",
              "type": "readonly"
          };
          fields.push(field);
          
          //build column list and templates (from configuration)
          conf.columns.forEach(function(col) {
            conf._template[col.data] = col.defaultContent;
            //column
            columns.push(col);
            //editor field
            field = {
              label: col.title + ":",
              name: col.data,
              type: col.editorType,
              attr: col.editorAttr
            };
            fields.push(field);
          });

          //empty data
          conf._data = [];

          //build the data table
          conf._dt = conf._table.DataTable( {
            data: conf._data,
            responsive: false,
            paging: false,
            dom: 't',
            select: true,
            rowId: "_rowid_",
            columns: columns,
            order: [1, 'desc'],
            columnDefs: [
                {
                  "targets": [1],
                  "visible": false,
                  "searchable": false
                }, 
            ],
            language: {
                "processing": "Sedang proses...",
                "zeroRecords": "Tidak ada data",
                "loadingRecords": "Loading...",
                "emptyTable": "Tidak ada data",
                aria: {
                    sortAscending: ": Klik untuk mengurutkan dari bawah ke atas",
                    sortDescending: ": Klik untuk mengurutkan dari atas ke bawah"
                }
            },
          });
    
          //build the editor
          conf._editor = new $.fn.dataTable.Editor( {
              table: "#" +conf._safeId+ "-tbl",
              idSrc: "_rowid_",
              fields: fields
          });
    
          //various event handler
          conf._editor.on('postSubmit', function(e, json, data, action, xhr) {
              if (action == "create") {
                conf._idx++;
              }

              //trigger change event
              conf._input.trigger("change");
          });
    
          conf._input.on('click', 'a.dt-editor-create', function (e) {
              e.preventDefault();
      
              //clone the template
              let row = JSON.parse(JSON.stringify(conf._template));
              row._rowid_ = conf._idx++;
      
              conf._dt.row.add(row);
              conf._dt.draw();
          } );
      
          conf._input.on('click', 'a.dt-editor-export', function (e) {
              e.preventDefault();
      
              //get the data
              let data = conf._dt.rows().data().toArray();
      
              //sort asc first so that data is ordered based on time of entry.
              data.sort(function(a, b){return a._rowid_ - b._rowid_});
      
              //export to json
              let json = JSON.stringify( data );
      
              alert(json);
          } );
      
          // Delete a record
          conf._table.on('click', 'a.dt-editor-remove', function (e) {
              e.preventDefault();
      
              conf._editor.remove($(this).closest('tr'), false).submit();    
          } );
      
          conf._table.on( 'click', 'tbody td:not(:first-child)', function (e) {
              if (!conf._enabled || conf.attr.readonly)   return;

              conf._editor
              .bubble( this, {
                  buttons: [
                    'Simpan',
                    { text: 'Batal', action: function () { this.close(); } }
                  ]
              });

          });
      
          let filter = conf._input.find("#" +conf._safeId+ "-filter");
          let filter_input = conf._input.find("#" +conf._safeId+ "-filter-input");
          filter.keyup(debounce(function() {
              let value = filter_input.val();
              conf._dt.search( value ).draw();
          }, 500, false));
    
          if (conf.attr.readonly == true) {
            // conf._table.find('a.dt-editor-create').addClass('disabled');
            // conf._table.find('a.dt-editor-export').addClass('disabled');
            // conf._table.find('a.dt-editor-remove').addClass('disabled');

            //hide action column
            let action_column = conf._dt.column( 0 );
            action_column.visible(false);
          }

          return conf._input;
      },
    
      get: function ( conf ) {
        let data = conf._dt.rows().data().toArray();

        //sort asc first so that data is ordered based on time of entry.
        data.sort(function(a, b){return a._rowid_ - b._rowid_});

        return JSON.stringify(data);
      },
    
      set: function ( conf, val ) {
        if (typeof conf._dt === 'undefined' || conf._dt == null) {
          initialize(conf);
        }

        let json = [];
        try {
            json = JSON.parse(val);
        }
        catch(err) {
            return;
        }

        if (json == null) {
          conf._dt.clear();
          conf._dt.draw();
          return;
        }

        if (!Array.isArray(json)) {
          return;
        }

        conf._dt.clear();
        conf._dt.rows.add( json ).draw();
      },
    
      enable: function ( conf ) {
          conf._enabled = true;

          let action_column = conf._dt.column( 0 );
          action_column.visible(true);

      },
    
      disable: function ( conf ) {
          conf._enabled = false;

          let action_column = conf._dt.column( 0 );
          action_column.visible(false);
      },

      table: function(conf) {
        return conf._table;
      },

      dataTable: function(conf) {
        return conf._dt;
      },

      editor: function(conf) {
        return conf._editor;
      },
      
      isEnabled: function(conf) {
        return conf._enabled;
      },

      sum: function (conf, col_idx) {
        return conf._dt.api.column( col_idx ).data().sum();
      },

      count: function (conf) {
        return conf._dt.rows().count();
      }
  };
   
	var tcg_table = {};

	tcg_table.defaults = {
		//whether it is editable or not
		readonly: false,

		//in case more than 1 editor has the same field name, this editor id can be used to distinguish it
		editorId: "",
	};

  tcg_table.column_defaults = {
    //column title
    title: "",

    //column title css
    titleClassName: "text-center",

    //field name
    data: "",

    //column css
    className: "",

    //column width
    width: null,

    //column orderable
    orderable: false,

    //column default content
    defaultContent: "",

    //column type
    editorType: "text",

    //additional attributes based on column type
    editorAttr: null,

    //rendering
    render: null,
  };
    
    
  })(jQuery, jQuery.fn.dataTable);
