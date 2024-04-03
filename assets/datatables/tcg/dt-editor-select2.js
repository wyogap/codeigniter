(function ($, DataTable) {

	if (!DataTable.ext.editorFields) {
		DataTable.ext.editorFields = {};
	}

	var Editor = DataTable.Editor;
	var _fieldTypes = DataTable.ext.editorFields;

	_fieldTypes.tcg_select2 = {

		create: function (conf) {
			var that = this;

			conf._enabled = true;

			conf._safeId = Editor.safeId(conf.id);

			//default attributes
			conf.attr = $.extend(true, {}, tcg_select2.defaults, conf.attr);

			//legacy setting
			if (typeof conf.ajax !== 'undefined' && conf.ajax != null && conf.ajax != "") {
				conf.attr.ajax = conf.ajax;
			}

			//force read-only (if any)
			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
				conf.attr.readonly = conf.readonly;
			}

			//force compulsory (if any)
			if (typeof conf.compulsory !== 'undefined' && conf.compulsory != null && conf.compulsory != "") {
				conf.attr.compulsory = conf.compulsory;
			}

			//force editorid
			if (conf.editorId !== undefined && conf.editorId != null && conf.editorId != "") {
				conf.attr.editorId = conf.editorId;
			}

			//some time, just the field name is not safe enough!
			if (conf.attr.editorId != "") {
				conf._safeId = conf.attr.editorId + "_" + conf._safeId;
			};

            //default value
            conf.val = null;

			// Create the elements to use for the input
			if (conf.attr.additionalInfo == 'inline') {
				conf._input = $(
					'<div id="' + conf._safeId + '" class="tcg-select2-container tcg-select2-info-inline ' + conf.attr.className + '">'
					+ '<select id="'+conf._safeId+'-select" class="tcg-select2-select"></select>'
					+ '<input id="'+conf._safeId+'-info" type="text" class="tcg-select2-info"/>'
					+ '</div>');

				conf._select = $('select', conf._input);
				conf._info = $('.tcg-select2-info', conf._input);

				conf._info.attr('readonly', true);
				if (conf.attr.infoMask != "") {
					conf._info.mask(conf.attr.infoMask, {reverse: true});
				}
			}
			else if (conf.attr.additionalInfo == 'newline') {
				conf._input = $(
					'<div id="' + conf._safeId + '" class="tcg-select2-container tcg-select2-info-newline ' + conf.attr.className + '">'
					+ '<select id="'+conf._safeId+'-select" class="tcg-select2-select"></select>'
					+ '<input id="'+conf._safeId+'-info" type="text" class="tcg-select2-info"/>'
					+ '</div>');

				conf._select = $('select', conf._input);
				conf._info = $('.tcg-select2-info', conf._input);

				conf._info.attr('readonly', true);
				if (conf.attr.infoMask != "") {
					conf._info.mask(conf.attr.infoMask, {reverse: true});
				}
			}
			else {
				conf._input = $(
					'<div id="' + conf._safeId + '" class="tcg-select2-container ' + conf.attr.className + '">'
					+ '<select id="'+conf._safeId+'-select" class="tcg-select2-select"></select>'
					+ '</div>');

				conf._select = $('select', conf._input);
				conf._info = null;		
			}

			if (conf.attr.multiple) {
				conf._select.attr('multiple', 'multiple');
			}

			//readonly?
			if (conf.attr.readonly == true) {
				conf._select.attr('readonly', true);
			}

			let _options = null;
			if (typeof conf.options !== 'undefined' && conf.options != null && Array.isArray(conf.options) && conf.options.length > 0) {
				_options = conf.options;
				tcg_select2.helpers._select2_build(conf._input, _options, conf.attr, conf.val);
			} 
			else if (typeof conf.attr.ajax !== 'undefined' && conf.attr.ajax != null && conf.attr.ajax != "") {
				//retrieve list from json
				$.ajax({
					url: conf.attr.ajax,
					type: 'GET',
					dataType: 'json',
					beforeSend: function (request) {
						request.setRequestHeader("Content-Type", "application/json");
					},
					success: function (response) {
						if (response.data === null) {
							conf._error = tcg_select2.messages.error.general;
						} else if (typeof response.error !== 'undefined' && response.error !== null && response.error != "") {
							conf.error = response.error;
						} else {
							_options = response.data;
						}
                        //default value is conf.val, the last value set for this control
						tcg_select2.helpers._select2_build(conf._input, _options, conf.attr, conf.val);
					},
					error: function (jqXhr, textStatus, errorMessage) {
						conf.error = errorMessage;
					}
				});
			} 
			else {
				tcg_select2.helpers._select2_build(conf._input, null, conf.attr, conf.val);
			}
			
			//store old value 
			conf._select.on('change', function() {
                //conf.val = conf._select.val();;
            });
			
			return conf._input;
		},

		get: function (conf) {
			let val = conf._select.val();
			return val;
		},

		set: function (conf, val) {
			if ((typeof val === 'undefined' || val === null) && conf.attr.compulsory) {
				//set default value
				val = conf.def;
			}

			if (conf.val != val) {
                //when loading from ajax, select2 might be initialized after the value is set.
                //so we need to save the val somewhere else!
                conf.val = val;
			}

            //set the value
            let old_val = conf._select.val();
            if (old_val != val) {
                conf._select.val(val).trigger('change');
            }
		},

		enable: function (conf) {
			conf._enabled = true;
			conf._select.removeClass('disabled').prop("disabled", false);
		},

		disable: function (conf) {
			conf._enabled = false;
			conf._select.addClass('disabled').prop("disabled", true);
		},

		update: function (conf, options) {
			if (typeof options === 'undefined' || options == null || !Array.isArray(options)) {
				return;
			}

			if (typeof conf.attr === 'undefined' || conf.attr == null) {
				conf.attr = {};
			}

			tcg_select2.helpers._select2_rebuild(conf._input, options, conf.attr);
		},

		info: function(conf, val = null) {
			if (val == null) {
				//getter;
				if (conf._info == null) {
					return "";
				}

				if (conf.attr.infoMask != "") {
					let val = conf._info.unmask().val();
					conf._info.mask(conf.attr.infoMask, {reverse: true});

					return val;
				}
				  
				return conf._info.val();
			}
			else {
				//setter
				if (conf._info == null) {
					return;
				}
				conf._info.val(val).trigger('input');
			}
		},

		showInfo: function(conf) {
			if (conf._info == null) {
				return;
			}
			conf._info.removeClass("d-none");
		},

		hideInfo: function(conf) {
			if (conf._info == null) {
				return;
			}
			conf._info.addClass("d-none");
		},

		tag1: function(conf) {
			let val = conf._select.val();
			let item = conf._select.find("option[value='" +val+ "']");
			return item.attr("data-tag-1");
		},

		tag2: function(conf) {
			let val = conf._select.val();
			let item = conf._select.find("option[value='" +val+ "']");
			return item.attr("data-tag-2");
		},
		
		ajax: function(conf, value=null) {
			if (typeof value === 'undefined' || value === null || value === '')		return conf.attr.ajax;
			
			//set ajax value
			conf.attr.ajax = value;
		},
		
		reload: function(conf) {
			tcg_select2.helpers._reload(conf);
		}
	};

	var tcg_select2 = {};

	tcg_select2.defaults = {
		//whether to use select2 or the generic select
		select2: true,

		//whether it is editable or not
		readonly: false,

		//if using select2, minimum entries when the search will show
		minimumResultsForSearch: 10,

		//whether multiple select
		multiple: false,

		//whether to use additional info: 
		//- none: no additional info
		//- inline: in the same line as the input 
		//- newline: new line below the input 
		additionalInfo: "none",

		//mask for additional info
		infoMask: "",

		//in case more than 1 editor has the same field name, this editor id can be used to distinguish it
		editorId: "",

		//custom css class name
		className: "",

		//autocomplete ajax api
		autocomplete: null,

		//minimal search string length for autocomplete
		minimumInputLength: 0,
		
		//string for minimal search string
		minimumInputLengthText: null,

		//placeholder for search string
		placeholder: "",

		//ajax url to retrieve the options
		ajax: null,
		
		//mapping to fields
		columns: [{
			data: 'value',
			title: 'label'
		}],

		//whether value is compulsory
		compulsory: false,
	};

	tcg_select2.helpers = {
		
		_reload: function(_conf) {
			let _options = null;
			
			//retrieve list from json
			$.ajax({
				url: _conf.attr.ajax,
				type: 'GET',
				dataType: 'json',
				beforeSend: function (request) {
					request.setRequestHeader("Content-Type", "application/json");
				},
				success: function (response) {
					if (response.data === null) {
						_conf._error = tcg_select2.messages.error.general;
					} else if (typeof response.error !== 'undefined' && response.error !== null && response.error != "") {
						_conf.error = response.error;
					} else {
						_options = response.data;
					}
					tcg_select2.helpers._select2_rebuild(_conf._input, _options, _conf.attr);
				},
				error: function (jqXhr, textStatus, errorMessage) {
					_conf.error = errorMessage;
				}
			});	
			
			return _options;
		},
		
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * Private methods
		 */
		_select2_build: function (_input, _options, _attr, _defvalue = null) {
			let _select = _input.find('select');

			//add class for easy identification
			_select.addClass('dt-editor-select2');

			//store current value
			let _value = _select.val();

			//default value
			if ((typeof _value === 'undefined' || _value === null) && typeof _defvalue !== 'undefined' && _defvalue !== null) {
				_value = _defvalue;
			}

			//console.log(_options);
			
			//rebuild the option list
			_select.empty();
			if (!_attr.compulsory) {
				let _option = $("<option>").val('').text('-- NA --');
				_option.addClass("select-option-level-1");
				
				//selected
				if (typeof _value === 'undefined' || _value == null || _value == '') {
					_option.attr("selected", true);
				}

				_select.append(_option);
				
				if (typeof _attr.placeholder === 'undefined' || _attr.placeholder == null || _attr.placeholder == '') {
					_attr.placeholder = '-- NA --';
				}
			}
			
			if (_options != null && Array.isArray(_options)) {
				//add options one by one
				for (item of _options) {
					if (typeof item === "undefined" || item == null ||
						typeof item.value === "undefined" || item.value == null ||
						typeof item.label === "undefined" || item.label == null) {
						return;
					}

					let _option = $("<option>").val(item.value).text(item.label);

					if (typeof item.level === "undefined" || item.level == null) {
						_option.addClass("select-option-level-1");
					} else if (item.level == 2) {
						_option.addClass("select-option-level-2");
					} else if (item.level == 3) {
						_option.addClass("select-option-level-3");
					} else if (item.level == 4) {
						_option.addClass("select-option-level-4");
					} else if (item.level == 5) {
						_option.addClass("select-option-level-5");
					} else {
						_option.addClass("select-option-level-1");
					}

					if (typeof item.optgroup !== "undefined" && item.optgroup != null && item.optgroup == 1) {
						_option.addClass("select-option-group");
						_option.prop("disabled", true);
					}

					//tag1
					if (typeof item.tag1!=="undefined" && item.tag1!=null) {
						_option.attr("data-tag-1", item.tag1)
					}

					//tag2
					if (typeof item.tag2!=="undefined" && item.tag2!=null) {
						_option.attr("data-tag-2", item.tag2)
					}

					//selected
					if (item.value == _value) {
						_option.attr("selected", true);
					}
					
					_select.append(_option);

                    //console.log(_option.prop('outerHTML'));
				};
			}
            
			//re-set the value
            //console.log(_value);
			_select.val(_value);

			//multiple select?
			if (typeof _attr.multiple !== 'undefined' && _attr.multiple) {
				_select.attr('multiple', 'multiple');
			}

			//dropdown parent
			let _body = $(document.body);
			let _dte = _body.find('.DTE');
			if (_dte.length == 0) {
				//since field is created before the DT Editor is created, we just create a dummy editor
				//this allows for customization and styling without affecting global/generic style
				//Important: add d-none class to hide it on load, especially on mobile!
				_body.append("<div class='DTE DTE_Select2 d-none'></div>");
				_dte = _body.find('.DTE');
			}

			//autocomplete
			let _ajax = null;
			if (typeof _attr.autocomplete !== "undefined" && _attr.autocomplete != null && _attr.autocomplete != "") {
				_ajax = {
					url: _attr.autocomplete,
					dataType: 'json',
					delay: 250,
					data: function (data) {
						return {
							keyword: data.term // search term
						};
					},
					processResults: function (response) {
						//empty response
						if (typeof response.data === "undefined" || response.data == null) {
							return {
								results: []
							};
						}

						//mapping fields
						var data = $.map(response.data, function (obj) {
							obj.id = obj.id || obj.value; 
							obj.text = obj.text || obj.label; 
						  
							return obj;
						});

						return {
							results: data
						};
					},
					cache: true
				};
			}

			//if (_ajax == null) {
			//	_ajax = _attr.ajax;
			//}
			
			//build select2
			if (typeof _attr.select2 === "undefined" || _attr.select2 == true) {
				_select.select2({
					minimumResultsForSearch: _attr.minimumResultsForSearch,
					//dropdownParent: $(_input).parent().parent().parent().parent(),
					dropdownParent: _body,
					templateResult: function (data) {
						// We only really care if there is an element to pull classes from
						if (!data.element) {
							return data.text;
						}

						var $element = $(data.element);

						var $wrapper = $('<div></div>');
						$wrapper.addClass($element[0].className);

						$wrapper.text(data.text);

						return $wrapper;
					},
					ajax: _ajax,
					allowClear: !_attr.compulsory,
					//placeholder: {
					//  id: "",
					//  text:_attr.placeholder,
					//  selected:'selected'
					//},
					placeholder: _attr.placeholder,
					minimumInputLength: _attr.minimumInputLength,
					language: {
						inputTooShort: function(args) {
						  // args.minimum is the minimum required length
						  // args.input is the user-typed text
						  return "Ketik " +args.minimum+ " huruf atau lebih" ;
						},
						inputTooLong: function(args) {
						  // args.maximum is the maximum allowed length
						  // args.input is the user-typed text
						  return "Ketik maksimal " +args.maximum+ " huruf";
						},
						errorLoading: function() {
						  return "Gagal mendapatkan data";
						},
						loadingMore: function() {
						  return "Mendapatkan data tambahan";
						},
						noResults: function() {
						  return "Tidak ada data yang sesuai";
						},
						searching: function() {
						  return "Mencari...";
						},
						maximumSelected: function(args) {
						  // args.maximum is the maximum number of items the user may select
						  return "Hasil pencarian terlalu banyak";
						}
					},
					//allowClear: true
				});

				// //read-only?
				// if (typeof _attr.readonly !== 'undefined' && _attr.readonly == true) {
				// 	_select.select2("readonly", true);
				// }
			}
			else {
				console.log("not select2");
			}

			_select.on('select2:opening', function (e) {
				e.stopPropagation();

				let overlay = $(".DTE_Select2");
				overlay.removeClass("d-none");

				//since DTED_Lightbox_Mobile hides/moves other dom element under DTED_Lightbox_Shown, we need to move back the select2 overlay background.
				$("body").append(overlay);

				// //throttle
				// _attr.flag = 1;
				// setTimeout(function(){ _attr.flag=0; }, 500);
			});

			_select.on('select2:closing', function (e) {
				e.stopPropagation();
				
				// //throttle
				// if (_attr.flag == 1) {
				// 	return;
				// }

				let overlay = $(".DTE_Select2");
				overlay.addClass("d-none");
			});
			
			_select.on('select2:closing', function (e) {
				e.stopPropagation();
				
				//store value

			});

			return _input;
		},

		_select2_rebuild: function (_input, _options, _attr) {
			let _select = _input.find('select');

			//store current value
			let _value = _select.val();

			//rebuild the option list
			_select.empty();
			if (!_attr.compulsory) {
				let _option = $("<option>").val('').text('-- NA --');
				_option.addClass("select-option-level-1");
				
				//selected
				if (typeof _value === 'undefined' || _value == null || _value == '') {
					_option.attr("selected", true);
				}

				_select.append(_option);
				
				if (typeof _attr.placeholder === 'undefined' || _attr.placeholder == null || _attr.placeholder == '') {
					_attr.placeholder = '-- NA --';
				}
			}

			if (_options != null && Array.isArray(_options)) {
				//add options one by one
				for (item of _options) {
					if (typeof item === "undefined" || item == null ||
						typeof item.value === "undefined" || item.value == null ||
						typeof item.label === "undefined" || item.label == null) {
						return;
					}

					let _option = $("<option>").val(item.value).text(item.label);

					if (typeof item.level === "undefined" || item.level == null) {
						_option.addClass("select-option-level-1");
					} else if (item.level == 2) {
						_option.addClass("select-option-level-2");
					} else if (item.level == 3) {
						_option.addClass("select-option-level-3");
					} else if (item.level == 4) {
						_option.addClass("select-option-level-4");
					} else if (item.level == 5) {
						_option.addClass("select-option-level-5");
					} else {
						_option.addClass("select-option-level-1");
					}

					if (typeof item.optgroup !== "undefined" && item.optgroup != null && item.optgroup == 1) {
						_option.addClass("select-option-group");
						_option.prop("disabled", true);
					}

					//tag1
					if (typeof item.tag1!=="undefined" && item.tag1!=null) {
						_option.attr("data-tag-1", item.tag1)
					}

					//tag2
					if (typeof item.tag2!=="undefined" && item.tag2!=null) {
						_option.attr("data-tag-2", item.tag2)
					}

					_select.append(_option);

				};
			}

			//re-set the value
			_select.val(_value);

			if (_select.val() != _value) {
				_select.val(0);
			}

			//multiple select?
			if (typeof _attr.multiple !== 'undefined' && _attr.multiple) {
				_select.attr('multiple', 'multiple');
			}

			return _input;
		}

	};

	//language translation
	tcg_select2.messages = {
		error: {
			//fail to retrieve data from server
			general: "Gagal mendapatkan data dari server"
		}
	};

	//$.extend(true, tcg_select2.messages.error, this.i18n.error);

})(jQuery, jQuery.fn.dataTable);

$(document).ready(function () {


});

