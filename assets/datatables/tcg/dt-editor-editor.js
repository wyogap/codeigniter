(function ($, DataTable) {

	if (!DataTable.ext.editorFields) {
		DataTable.ext.editorFields = {};
	}

	var Editor = DataTable.Editor;
	var _fieldTypes = DataTable.ext.editorFields;

	_fieldTypes.tcg_editor4 = {
		create: function (conf) {
			var that = this;

			conf._enabled = true;

			conf._safeId = Editor.safeId(conf.id);

			//default attributes
			conf.attr = $.extend(true, {}, tcg_editor.defaults, conf.attr);

			//some time, just the field name is not safe enough!
			if (conf.attr.editorId != "") {
				conf._safeId = conf.attr.editorId + "_" + conf._safeId;
			};

			//force read-only (if any)
			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
				conf.attr.readonly = conf.readonly;
			}
			
			// Create the elements to use for the input
			let editorId = conf._safeId + '_editor';

			conf._input = $(
				'<div id="' + conf._safeId + '"></div>');

			conf._input_control = $('<textarea id="' + editorId + '" class="tcg-editor-input form-control"></textarea>');

			conf._input.append(conf._input_control);

			//default value
			if (typeof conf.def !== 'undefined' && conf.def != null) {
				conf._input_control.val(conf.def);
			}

			if (conf.attr.readonly == true) {
				conf._input_control.attr('readonly', true);
			}

			if (conf.attr.rows !== null && conf.attr.rows > 0) {
				conf._input_control.attr('rows', conf.attr.rows);
			}

            // //capture resize when the field is attached
            // const resizeObserver = new ResizeObserver(() => {
			// 	if (conf.attr.ckeditor) {
			// 		conf._editor = conf._input_control.ckeditor({
			// 			toolbarGroups: conf.attr.toolbar
			// 		}).editor;
			// 	}
            // });
            // resizeObserver.observe(conf._input_control[0]);

			// CKEditor needs to be initialised on each open call
			this.on( 'open.ckEditInit-'+editorId, function () {
				if ( $('#'+editorId).length ) {
					conf._editor = CKEDITOR.replace( editorId, conf.attr);

					// conf._editor = conf._input_control.ckeditor({
					// 	toolbarGroups: conf.attr.toolbar
					// });

					conf._editor.on( 'instanceReady', function () {
						// If shown in a bubble, there is a good chance we'll need to
						// move it once CKEditor is shown, since it is large!
						if ( conf._input.parents('div.DTE_Bubble').length ) {
							//that.bubblePosition();
						}
					} );
	
					if ( conf._initSetVal ) {
						conf._editor.setData( conf._initSetVal );
						conf._initSetVal = null;
					}
					else {
						conf._editor.setData( '' );
					}
				}
			} );
	
			// And destroyed on each close, so it can be re-initialised on reopen
			this.on( 'preClose.ckEditInit-'+editorId, function () {
				if ( conf._editor ) {
					conf._editor.destroy();
					conf._editor = null;
				}
			} );

			////convert to ckeditor
			//if (conf.attr.ckeditor) {
			//	conf._input_control.ckeditor({
			//		toolbarGroups: conf.attr.toolbar
			//	});
			//	conf._editor = conf._input_control.ckeditor().editor;
			//}
			
			return conf._input;
		},

		get: function (conf) {
			if (!conf.attr.ckeditor) {
				return conf._input_control.val();
			}

			if ( !conf._editor ) {
				return conf._initSetVal;
			}
	 
			return conf._editor.getData();
		},

		set: function (conf, val) {
			if (typeof val === 'undefined' || val == null) val = "";
			
			if (!conf.attr.ckeditor) {
				//not ckeditor. just set the input val
				conf._input_control.val(val);
				//trigger change event
				conf._input.trigger("change");
				return;
			}

			// If not ready, then store the value to use when the onOpen event fires
			if ( ! conf._editor || ! conf._input_control.length ) {
				conf._initSetVal = val;
				return;
			}
			conf._editor.setData( val );
				
			// if (typeof conf._editor !== 'undefined') {
			// 	//hack: destroy any existing editor before setting the value. this way we can use the editor event 'instanceReady'
			// 	//conf._editor.destroy();
			// }
			
			// //create the editor
			// conf._editor = conf._input_control.ckeditor({
			// 	toolbarGroups: conf.attr.toolbar
			// }).editor;

			// //set the value only when editor is loaded and visible
			// conf._editor = conf._input_control.ckeditor().editor;
			// if (conf._editor.status !== "ready") {
			// 	conf._editor.on("instanceReady",
			// 		event => {
			// 			event.editor.setData(val);
			// 		});
			// } else {
			// 	conf._editor.setData(val);
			// }
		},

		enable: function (conf) {
			conf._enabled = true;
			conf._input_control.removeClass('disabled').prop("disabled", false);
		},

		disable: function (conf) {
			conf._enabled = false;
			conf._input_control.addClass('disabled').prop("disabled", true);
		},

		isEnabled: function (conf) {
			return conf._enabled;
		},
 
		destroy: function ( conf ) {
			let editorId = conf._safeId + '_editor';
	 
			this.off( 'open.ckEditInit-'+editorId );
			this.off( 'preClose.ckEditInit-'+editorId );
		}
	};

	var tcg_editor = {};

	tcg_editor.defaults = {
		//whether it is editable or not
		readonly: false,

		//number of rows
		rows: 5,

		//in case more than 1 editor has the same field name, this editor id can be used to distinguish it
		editorId: "",

		//whether to convert to ckeditor WYSWYG
		ckeditor: true,

		height: "140px",
		
		//basic ckeditor toolbar
		toolbarGroups: [
			{ name: 'clipboard', groups: [ 'undo' ] },
			{ name: 'basicstyles', groups: [ 'basicstyles' ] },
			{ name: 'paragraph', groups: [ 'list', 'indent' ] },
			{ name: 'links', groups: [ 'links' ] },
			{ name: 'insert', groups: [ 'insert' ] },
			{ name: 'tools', groups: [ 'tools' ] },
		],

	};

    _fieldTypes.tcg_editor = {
        create: function ( conf ) {
            var that = this;

			conf._enabled = true;

			conf._safeId = Editor.safeId(conf.id);

			//default attributes
			conf.attr = $.extend(true, {}, tcg_editor.defaults, conf.attr);

			//some time, just the field name is not safe enough!
			if (conf.attr.editorId != "") {
				conf._safeId = conf.attr.editorId + "_" + conf._safeId;
			};

			//force read-only (if any)
			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
				conf.attr.readonly = conf.readonly;
			}
			
            conf._input = $('<div id="'+conf._safeId+'" class="tcg-editor"></div>');
			conf._readonly = $('<div id="'+conf._safeId+'-readonly" class="tcg-editor-readonly" style="height:' +conf.attr.height+ ';"></div>');
            conf._ck = $('<div id="'+conf._safeId+'-ck" class="tcg-editor-ck"></div>');
			
			conf._input.append(conf._readonly);
			conf._input.append(conf._ck);
			
            window[ 'ClassicEditor' ].create( conf._ck[0], conf.attr )
                .then( function (editor) {
                    conf._ckeditor = editor;
					if (typeof conf._initSetVal !== 'undefined' && conf._initSetVal != null) {
						conf._ckeditor.setData( conf._initSetVal );
						conf._initSetVal = null;
					}
                });
 
            return conf._input;
        },
 
        get: function ( conf ) {
			if (typeof conf._initSetVal !== 'undefined' && conf._initSetVal != null) {
				return conf._initSetVal;
			}
            return conf._ckeditor.getData();
        },
 
        set: function ( conf, val ) {
			if (typeof val === 'undefined' || val == null) val = "";
			
			if (typeof conf._ckeditor === 'undefined' || conf._ckeditor == null) {
				conf._initSetVal = val;
			}
			else {
				conf._initSetVal = null;
				conf._ckeditor.setData( val );
			}
			
			conf._readonly.html(val);
			
			//trigger change event
			conf._input.trigger("change");
        },
 
        enable: function ( conf ) {
            conf._enabled = true;
            conf._input.removeClass( 'disabled' ).prop("disabled", false);
        },
      
        disable: function ( conf ) {
            conf._enabled = false;
            conf._input.addClass( 'disabled' ).prop('disabled',true);
        },

        isEnabled: function (conf) {
			return conf._enabled;
        },
 
        destroy: function ( conf ) {
            conf._ckeditor.destroy();
			conf._ckeditor = null;
        },
 
        inst: function ( conf ) {
            return conf._ckeditor;
        }
    };
})(jQuery, jQuery.fn.dataTable);
