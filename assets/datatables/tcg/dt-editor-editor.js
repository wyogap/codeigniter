(function ($, DataTable) {

	if (!DataTable.ext.editorFields) {
		DataTable.ext.editorFields = {};
	}

	var Editor = DataTable.Editor;
	var _fieldTypes = DataTable.ext.editorFields;

	_fieldTypes.tcg_editor = {
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

			// Create the elements to use for the input
			conf._input = $(
				'<div id="' + conf._safeId + '"></div>');

			conf._input_control = $('<textarea id="' + conf._safeId + '_editor" class="tcg-editor-input form-control"></textarea>');

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

            //capture resize when the field is attached
            const resizeObserver = new ResizeObserver(() => {
				if (conf.attr.ckeditor) {
					conf._input_control.ckeditor({
						toolbarGroups: conf.attr.toolbar
					});
					conf._editor = conf._input_control.ckeditor().editor;
				}
            });
            resizeObserver.observe(conf._input_control[0]);

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
			return conf._input_control.val();
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
			
			if (typeof conf._editor !== 'undefined') {
				//hack: destroy any existing editor before setting the value. this way we can use the editor event 'instanceReady'
				conf._editor.destroy();
			}
			
			//create the editor
			conf._input_control.ckeditor({
				toolbarGroups: conf.attr.toolbar
			});

			//set the value only when editor is loaded and visible
			conf._editor = conf._input_control.ckeditor().editor;
			if (conf._editor.status !== "ready") {
				conf._editor.on("instanceReady",
					event => {
						event.editor.setData(val);
					});
			} else {
				conf._editor.setData(val);
			}
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
		ckeditor: false,

		//basic ckeditor toolbar
		toolbar: [
			{ name: 'clipboard', groups: [ 'undo' ] },
			{ name: 'basicstyles', groups: [ 'basicstyles' ] },
			{ name: 'paragraph', groups: [ 'list', 'indent' ] },
			{ name: 'tools', groups: [ 'tools' ] }
		],

	};

})(jQuery, jQuery.fn.dataTable);
