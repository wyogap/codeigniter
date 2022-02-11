(function ($, DataTable) {

	if (!DataTable.ext.editorFields) {
		DataTable.ext.editorFields = {};
	}

	var Editor = DataTable.Editor;
	var _fieldTypes = DataTable.ext.editorFields;

	_fieldTypes.tcg_upload = {
		create: function (conf) {
			var that = this;

			conf._enabled = true;

			conf._safeId = Editor.safeId(conf.id);

			//default attributes
			conf.attr = $.extend(true, {}, tcg_upload.defaults, conf.attr);

			//default messages
			conf.messages = $.extend(true, {}, tcg_upload.messages, conf.messages);

			//some time, just the field name is not safe enough!
			if (conf.attr.editorId != "") {
				conf._safeId = conf.attr.editorId + "_" + conf._safeId;
			};

			//force read-only (if any)
			if (typeof conf.ajax !== 'undefined' && conf.ajax != null && conf.ajax != "") {
				conf.attr.ajax = conf.ajax;
			}

			//force ajax (if any)
			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
				conf.attr.readonly = conf.readonly;
			}
			
			// Create the elements to use for the input
			conf._input = $(
				'<div id="' + conf._safeId + '"></div>');

			conf._input_control = $('<div type="' + conf._safeId + '_dz" class="dropzone tcg-upload-dropzone"><div id="' + conf._safeId + '_upload" class="fallback"><input name="' +conf.name+ '" type="file" /></div></div>');

			conf._input.append(conf._input_control);

			//default value
			if (typeof conf.def !== 'undefined' && conf.def != null) {
				conf._input_control.val(conf.def);
			}

			if (conf.attr.readonly == true) {
				conf._input_control.attr('readonly', true);
			}

			conf._input_control.dropzone({
				url: conf.attr.ajax,
				previewTemplate: conf.attr.template.html(),
				createImageThumbnails: true,
				thumbnailWidth: 80,
				thumbnailHeight: 80,
				thumbnailMethod: "crop",
				maxFiles: conf.attr.maxFiles,
				maxFilesize: conf.attr.maxFileSize,
				//capture: "camera",
				dictDefaultMessage: conf.messages.defaultMessage,
				dictMaxFilesExceeded: conf.messages.maxFilesExceeded,
				dictRemoveFileConfirmation: conf.messages.removeFileConfirmation,
				dictFileTooBig: conf.messages.fileTooBig,
				// dictRemoveFile: 'Remove File',
				// dictCancelUpload: 'Cancel Upload',
				params: { "action": "uploadFile", "field": conf.name },
				success: function (file, response) {
					let json = [];
					try {
						json = JSON.parse(response);
					}
					catch(err) {
						//ignore
						this.defaultOptions.error(file, conf.messages.uploadFail);  
						return;
					}

					if (typeof json.error !== 'undefined' && json.error != null && json.error != "") {
						this.defaultOptions.error(file, json.error); 
						return;
					}

					if (typeof json.files === 'undefined' || !Array.isArray(json.files) || json.files.length == 0) {
						this.defaultOptions.error(file, conf.messages.uploadFail);  
						return;
					}

					let upload = json.files[0];
					if (upload.id > 0) {
						$(file.previewTemplate).find(".dz-preview").attr("id", upload.id);
						file['id'] = upload.id;
						file['url'] = upload.web_path;

						//check for maxfile
						if (this.options.maxFiles && this.files.length >= this.options.maxFiles) {
							this.disable();
						}

						//user server side thumbnail						
						this.emit("thumbnail", file, upload.thumbnail_path);
					}
				},
				init: function () {
					let that = this;

					this.on("removedfile", function (file) {
						that.enable();
					});

					this.on("maxfilesexceeded", function (file) {
						that.removeFile(file);
					});

					conf._dz = that;
					
					if (!conf._enabled) {
						conf._dz.disable();
					}
				},
				// accept : function(file, done) {
				//     //if ()
				//     done();
				// },
				// complete: function(file) {
				//
				// },
				removedfile: function(file) {
					let that = this;

					if (file.id > 0) {
						//server file
						$.ajax({
							type: 'POST',
							url: conf.attr.ajax,
							data: {action: "removeFile", files: file.id},
							success: function(response){
								let json = "";
								try {
									json = JSON.parse(response);
								}
								catch(err) {
									//fail
									alert(conf.messages.serverDeleteFail);
									return;
								}
	
								if (typeof json.error !== 'undefined' && json.error != null && json.error != "") {
									alert(json.error); 
									return;
								}
			
								let idx = that.files.findIndex(f => f.id == file.id);
								that.files.splice(idx, 1); 
								file.previewElement.parentNode.removeChild(file.previewElement);

								if (that.files.length == 0) {
									conf._input_control.removeClass("dz-started");
								}
								
							}
						});
	
						//add back. wait for the server delete
						this.files.push(file);
						return false;
					}

					//local file. not uploaded yet
					file.previewElement.parentNode.removeChild(file.previewElement);

					if (that.files.length == 0) {
						conf._input_control.removeClass("dz-started");
					}

					return true;				
				},
				complete: function(file) {
					if (typeof file.url !== 'undefined') {
						// Download link
						var url = $("<div class='dz-download text-center'><a target='_blank' href='" +file.url+ "'>Download</a></div>");
						$(file.previewTemplate).append(url);
					}
					
					//this.emit("complete", file);
					file.previewElement.classList.add("dz-complete")
					
					return true;
				}
			});

			conf._input_control.on("click", ".dz-error-mark", function(e) {
				e.preventDefault();
				e.stopPropagation();
			});

			conf._input_control.on("click", ".dz-container", function(e) {
				e.preventDefault();
				e.stopPropagation();
			});

			conf._input_control.on("click", "[data-dz-remove]", function(e) {
				e.preventDefault();
				e.stopPropagation();
			});

			// conf._input_control.on("touchend", "*", function(e) { $(this).trigger("focus"); });

			// conf._input_control.on("click", ".dz-error-mark", function(e) {
			// 	e.preventDefault();
			// 	e.stopPropagation();

			// 	let container = $(this).closest(".dz-container");
			// 	if (container.hasClass("dz-focus")) {
			// 		container.removeClass("dz-focus");
			// 	}
			// 	else {
			// 		container.addClass("dz-focus");
			// 	}
			// });

			// conf._input_control.on("click", ".dz-container", function(e) {
			// 	e.preventDefault();
			// 	e.stopPropagation();

			// 	let container = $(this);
			// 	if (container.hasClass("dz-focus")) {
			// 		container.removeClass("dz-focus");
			// 	}
			// 	else {
			// 		container.addClass("dz-focus");
			// 	}
			// });

			// conf._input_control.on("click", ".dz-error-message", function(e) {
			// 	e.preventDefault();
			// 	e.stopPropagation();

			// 	let container = $(this).closest(".dz-container");
			// 	container.removeClass("dz-focus");
			// 	//container.trigger("focusout");
			// });

			// conf._input_control.on("click", ".dz-filename", function(e) {
			// 	e.preventDefault();

			// 	let container = $(this);
			// 	if (container.hasClass("dz-focus")) {
			// 		container.removeClass("dz-focus");
			// 	}
			// 	else {
			// 		container.addClass("dz-focus");
			// 	}
			// });

			return conf._input;
		},

		get: function (conf) {
			let dz = conf._dz;
			const ids = dz.files.filter(f => f.id > 0);
			if (ids.length == 0) {
				return "";
			}
			let arr = ids.map(o => o["id"]);
			return arr.join(",");
		},

		set: function (conf, val) {
			if (typeof val === 'undefined' || val == null) val = [];

			let strVal = "";
			if (Array.isArray(val)) {
				strVal = val.join(",");
			}
			else {
				strVal = val;
				val = val.split(',')
			}

			let dz = conf._dz;

			//reset
			if(typeof dz.files !== "undefined" && dz.files.length != 0){
				for(i=0; i<dz.files.length; i++){
					dz.files[i].previewElement.remove();
				}
				dz.files.length = 0;
			}
	
			conf._input_control.removeClass("dz-started");

			if (val.length == 0)	return;

			//get file info
			if (strVal !== null && strVal.length > 0) {
				$.ajax({
					type: 'POST',
					url: conf.attr.ajax,
					data: {action: "listFile", files: strVal},
					success: function(response){
						let json = "";
						try {
							json = JSON.parse(response);
						}
						catch(err) {
							//ignore
							return;
						}
	
						if (typeof json.files === 'undefined' || !Array.isArray(json.files) || json.files.length == 0) {
							//ignore
							return;
						}
	
						if (typeof json.error !== 'undefined' && json.error != null && json.error != "") {
							alert(json.error); 
							return;
						}
	
						let upload = null;
						let url = null;
						for(i=0; i<json.files.length; i++){
							upload = json.files[i];
							//url = "<a href='" +upload.web_path+ "'>" +upload.filename+ "</a>"
							let mockFile = { id: upload.id, name: upload.filename, size: upload.filesize, url: upload.web_path };
							dz.displayExistingFile(mockFile, upload.thumbnail_path);
	
							dz.files.push(mockFile); 
						}					
						
						//check for maxfile
						if (dz.options.maxFiles && dz.files.length >= dz.options.maxFiles) {
							dz.disable();
						}						
	
					}
				});	
			}

		},

		enable: function (conf) {
			conf._enabled = true;
			conf._input_control.removeClass('disabled').prop("disabled", false);
			if (typeof conf._dz !== 'undefined' && conf._dz != null) {
				conf._dz.enable();
			}
		},

		disable: function (conf) {
			conf._enabled = false;
			conf._input_control.addClass('disabled').prop("disabled", true);
			if (typeof conf._dz !== 'undefined' && conf._dz != null) {
				conf._dz.disable();
			}
		},

		isEnabled: function (conf) {
			return conf._enabled;
		}
	};

	var tcg_upload = {};

	tcg_upload.defaults = {
		//whether it is editable or not
		readonly: false,

		//in case more than 1 editor has the same field name, this editor id can be used to distinguish it
		editorId: "",

		ajax: "",

		maxFiles: 10,

		//max file size in MB
		maxFileSize: 10,

		//comma separated list of mime types or file extensions
		acceptedFiles: '*',

		template: $(
		'<script id="dropzone-detail" type="text/template">'+
			'<div class="dz-preview dz-image-preview dz-file-preview">'+ 
				'<div class="dz-container">'+
					'<div class="dz-image">'+
						'<img data-dz-thumbnail="" alt="">'+
					'</div>'+  
					'<div class="dz-details">'+    
						'<div class="dz-size"><span data-dz-size=""></div>'+
						'<div class="dz-remove"><strong><i class="fa fa-times-circle" data-dz-remove></strong></i></div>'+  
					'</div>'+  
					'<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span></div>'+  
					'<div class="dz-success-mark">'+    
						'<svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">'+      
							'<title>Check</title>'+      
							'<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">'+        
								'<path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF"></path>'+      
							'</g>'+    
						'</svg>'+  
					'</div>'+  
					'<div class="dz-error-mark">'+    
						'<svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">'+      
							'<title>Error</title>'+      
							'<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">'+        
								'<g stroke="#747474" stroke-opacity="0.198794158" fill="red" fill-opacity="0.816519475">'+          
									'<path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z"></path>'+        
								'</g>'+      
							'</g>'+    
						'</svg>'+  
					'</div>'+
					'<div class="dz-error-message"><span data-dz-errormessage="" style="color: #ffffff"></span></div>'+
				'</div>'+
				'<div class="dz-filename text-center"><span data-dz-name=""></span></div>'+  
			'</div>'+
		'</script>'
		),

	};

	tcg_upload.messages = {
		defaultMessage: "Klik di sini untuk mengunggah",
		maxFilesExceeded: "Anda tidak bisa menggunggah fail lagi.",
		removeFileConfirmation: "Apakah anda ingin menghapus fail ini?",
		fileTooBig: 'Batasan maksimal ukuran fail adalah {{maxFilesize}}',
		uploadFail: 'Gagal mengunggah fail',
		serverDeleteFail: 'Gagal menghapus fail dari server',
	};

})(jQuery, jQuery.fn.dataTable);
