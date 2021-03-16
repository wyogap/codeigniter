(function ($, DataTable) {

	if (!DataTable.ext.editorFields) {
		DataTable.ext.editorFields = {};
	}

	var Editor = DataTable.Editor;
	var _fieldTypes = DataTable.ext.editorFields;

	_fieldTypes.tcg_cascade = {

		create: function (conf) {
			var that = this;

			conf._enabled = true;

			conf._safeId = Editor.safeId(conf.id);

			//default attributes
			conf.attr = $.extend(true, {}, tcg_cascade.defaults, conf.attr);

			//some time, just the field name is not safe enough!
			if (conf.attr.editorId != "") {
				conf._safeId = conf.attr.editorId + "_" + conf._safeId;
			};

			//make sure it is a number
			conf.attr.level = parseInt(conf.attr.level);
			if ( isNaN(conf.attr.level) ) {
				conf.attr.level = 3;
			}

			conf.attr.minLevel = parseInt(conf.attr.minLevel);
			if ( isNaN(conf.attr.minLevel) ) {
				conf.attr.minLevel = 1;
			}
			
			conf.attr.maxLevel = parseInt(conf.attr.maxLevel);
			if ( isNaN(conf.attr.maxLevel) ) {
				conf.attr.maxLevel = conf.attr.level;
			}
			
			conf._input = $('<div class="tcg-cascade" id="'+conf._safeId+'"></div>');

			let fields = [];
			let row = $('<div class="row"></div>');

			let select = null;
			for (var i=conf.attr.minLevel; i<=conf.attr.maxLevel; i++) {
				let levelName = 'Level ' +i;
				if (typeof conf.attr.fnLevelName === 'function') {
					levelName = conf.attr.fnLevelName(i);
				}

				let html = $(
					'<div class="col-md-4 mb-2 ' + conf.attr.className + '">'
					+ '<select id="'+conf._safeId+'-select-'+i+'" class="tcg-cascade-select" data-level="'+i+'" style="width: 100%;">'
					+   '<option value="">-- '+levelName+' --</option>'
					+ '</select>'
					+ '</div>');
				row.append(html);

				select = html.find("select");
				if (conf.attr.readonly == true) {
					select.attr('readonly', true);
				}

				fields.push(select[0]);

			}

			conf._input.append(row);

			//initial value
			if (conf.attr.ajax == null || conf.attr.ajax == '') {
				//do nothing. for now
			}
			else {
				for (var i=conf.attr.minLevel; i<=conf.attr.maxLevel; i++) {
					let idx = i-conf.attr.minLevel;
					let field = fields[idx];
	
					var data = {value:null, level:conf.attr.maxLevel, target:i};
					$.ajax({
						type: "GET",
						url : conf.attr.ajax,
						data: data,
						success: function(msg){
							$(field).html(msg);
						}
					});
				}
			}

			//change event
			for (var i=conf.attr.minLevel; i<conf.attr.maxLevel; i++) {
				let idx = i-conf.attr.minLevel;
				let srcField = fields[idx];
				let targetField = fields[idx+1];

				$(srcField).on('change', function() {
					if (conf.attr.ajax == null || conf.attr.ajax == '') {
						return;
					}

					//current value of target field
					let field = $(targetField);
					let val = field.val();

					var data = {value:$(this).val(), level:i, target:i+1};
					$.ajax({
						type: "GET",
						url : conf.attr.ajax,
						data: data,
						success: function(msg){
							field.html(msg);
							field.val(val);
						}
					});


				});
			}

			//convert to select2
			if (conf.attr.select2) {
				//dropdown parent
				let _body = $(document.body);
				let _dte = _body.find('.DTE');
				if (_dte.length == 0) {
					//since field is created before the DT Editor is created, we just create a dummy editor
					//this allows for customization and styling without affecting global/generic style
					_body.append("<div class='DTE DTE_Select2 d-none'></div>");
					_dte = _body.find('.DTE');
				}

				$(".tcg-cascade-select", conf._input).select2({
					minimumResultsForSearch: conf.attr.minimumResultsForSearch,
					dropdownParent: _dte,
					minimumInputLength: conf.attr.minimumInputLength,
				});

				// //read-only?
				// if (conf.attr.readonly == true) {
				// 	$(".tcg-cascade-select", conf._input).select2("readonly", true);
				// }
			}
			
			//easy access
			conf._fields = fields;
			conf._fieldValue = select;
			
			return conf._input;
		},

		get: function (conf) {
			let val = conf._fieldValue.val();
			return val;
		},

		set: function (conf, val) {
			if (conf.attr.ajax == null || conf.attr.ajax == '') {
				return;
			}
			
			if (val == null || val == '') {
				//only initialize top level
				let field = conf._fields[0];

				var data = {value:"", level:conf.attr.maxLevel, target:conf.attr.minLevel};
				$.ajax({
					type: "GET",
					url : conf.attr.ajax,
					data: data,
					success: function(msg){
						$(conf._fields[0]).html(msg);
					}
				});

				//reset lower level
				for (var i=conf.attr.minLevel+1; i<=conf.attr.maxLevel; i++) {
					let idx = i-conf.attr.minLevel;
					field = conf._fields[idx];
					$(field).val('');
				}
			}
			else {
				//initialize all levels
				for (var i=conf.attr.minLevel; i<=conf.attr.maxLevel; i++) {
					let idx = i-conf.attr.minLevel;
					let field = conf._fields[idx];
					
					var data = {value:val, level:conf.attr.maxLevel, target:i};
					$.ajax({
						type: "GET",
						url : conf.attr.ajax,
						data: data,
						success: function(msg){
							$(field).html(msg);
						}
					});
				}
			}

		},

		enable: function (conf) {
			conf._enabled = true;
			$(".tcg-cascade-select", conf._input).removeClass('disabled').prop("disabled", false);
		},

		disable: function (conf) {
			conf._enabled = false;
			$(".tcg-cascade-select", conf._input).addClass('disabled').prop("disabled", true);
		},

	};

	var tcg_cascade = {};

	tcg_cascade.defaults = {
		//whether it is editable or not
		readonly: false,
  
		//number of level
		level: 3,

		//min level. override level
		minLevel: null,

		//max level. override level
		maxLevel: null,

		//css class for the select
		className: '',

		//ajax for dynamic entry
		ajax: '',

		//in case more than 1 editor has the same field name, this editor id can be used to distinguish it
		editorId: "",

		//whether to convert to select2
		select2: false,

		//if using select2, minimum entries when the search will show
		minimumResultsForSearch: 10,

		//autocomplete ajax api
		autocomplete: null,

		//minimal search string length for autocomplete
		minimumInputLength: 0,
		
		//string for minimal search string
		minimumInputLengthText: null,

		//function to get level name/label
		fnLevelName: null,
	};

	tcg_cascade.messages = {
		error: {
			//fail to retrieve data from server
			general: "Gagal mendapatkan data dari server"
		}
	};

	//$.extend(true, tcg_select2.messages.error, this.i18n.error);

})(jQuery, jQuery.fn.dataTable);

$(document).ready(function () {


});
