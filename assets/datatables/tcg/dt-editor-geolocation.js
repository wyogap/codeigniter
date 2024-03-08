(function ($, DataTable) {
  
    if ( ! DataTable.ext.editorFields ) {
        DataTable.ext.editorFields = {};
    }
      
    var Editor = DataTable.Editor;
    var _fieldTypes = DataTable.ext.editorFields;
      
    _fieldTypes.tcg_geolocation = {
        create: function ( conf ) {
            var that = this;
      
            conf._enabled = true;
      
            conf._safeId = Editor.safeId( conf.id );
  
			//default attributes
			conf.attr = $.extend(true, {}, tcg_geolocation.defaults, conf.attr);

			//some time, just the field name is not safe enough!
			if (conf.attr.editorId != "") {
				conf._safeId = conf.attr.editorId + "_" + conf._safeId;
			};

			//default language
			conf.language = $.extend(true, {}, tcg_geolocation.messages, conf.language);

			//force read-only (if any)
			if (typeof conf.readonly !== 'undefined' && conf.readonly != null && conf.readonly != "") {
				conf.attr.readonly = conf.readonly;
			}
			
            // Create the elements to use for the input
            conf._input = $('<div class="tcg-geo-location" id="'+conf._safeId+'"></div>');

            let html = '<div class="row"><div class="col-12"><div class="map" id="'+conf._safeId+'_peta" style="width: 100%; height: '+conf.attr.mapHeight+';"></div></div></div>';
            conf._input.append(html);

            html = '<div class="row" style="margin-top: 10px; margin-bottom: 10px;"><div class="col-12 info">'+conf.language.info+'</div></div>';
            conf._input.append(html);

            html = '<div class="row">' 
                    +'<div class="col-md-6"><div class="form-group has-feedback mb-2"><label for="lintang">'+conf.language.latitude+'</label><input type="text" class="lintang form-control" id="'+conf._safeId+'_lintang" name="lintang" placeholder="'+conf.language.latitude+'" value=""></div></div>'
                    +'<div class="col-md-6"><div class="form-group has-feedback mb-2"><label for="bujur">'+conf.language.longitude+'</label><input type="text" class="bujur form-control" id="'+conf._safeId+'_bujur" name="bujur" placeholder="'+conf.language.longitude+'" value=""></div></div>'
                    +'</div>';
            conf._input.append(html);

            conf._map = conf._input.find('.map');
            conf._lat = conf._input.find('.lintang');
            conf._long = conf._input.find('.bujur');

            let mapCenter = JSON.parse(conf.attr.mapCenter);
			
			//use default value as map center
			if (typeof conf.def !== 'undefined' && conf.def !== null && conf.def != '') {
				try {
					mapCenter = JSON.parse(conf.def);
				} 
				catch(err) {
					//ignore
				}
			}

            var map = L.map(conf._map[0],{zoomControl:false}).setView(mapCenter,conf.attr.mapZoom);
	
            // L.tileLayer(
            //     conf.attr.streetLayer,{maxZoom: 18,attribution: conf.attr.attribution,id: 'mapbox.street'}
            // ).addTo(map);

            //map layers
            var streetmap    = L.tileLayer(conf.attr.streetLayer, {maxZoom: 18, id: 'mapbox.street', attribution: conf.attr.attribution});
            var satelitemap  = L.tileLayer(conf.attr.satelliteLayer, {maxZoom: 18, id: 'mapbox.satellite',   attribution: conf.attr.attribution});
            var baseLayers = {
                "Streets": streetmap,
                "Satellite": satelitemap
            };
            var overlays = {};
            L.control.layers(baseLayers,overlays).addTo(map);

            //set active layer to street
            streetmap.addTo(map);

            //layer groups
            var layerGroup = L.layerGroup().addTo(map);

            //click on the map to set coordinate
            map.on('click', function(e) {
                if (!conf._enabled || conf.attr.readonly)   return;
                
                layerGroup.clearLayers();
                var lintang = e.latlng.lat;
                var bujur = e.latlng.lng;
                new L.marker(e.latlng).addTo(layerGroup).bindPopup(conf.language.popupMessage+"<br>"+lintang+", "+bujur);
                conf._lat.val(lintang);
                conf._long.val(bujur);              
            });

            //search location
            var searchControl = L.esri.Geocoding.geosearch().addTo(map);
            searchControl.on('layerGroup', function(data){
                layerGroup.clearLayers();
            });

            //zoom and fullscreen control
            new L.control.fullscreen({position:'bottomleft'}).addTo(map);
            new L.Control.Zoom({position:'bottomright'}).addTo(map);

            //reset view and zoom level
            new L.Control.EasyButton( '<span class="map-button">&curren;</span>', 
                function(){
                        map.setView(mapCenter,conf.attr.mapZoom);;
                }, 
                {position: 'bottomleft'}
            ).addTo(map);

            //default marker
            //L.marker(mapCenter).addTo(layerGroup);
    
            //manually set the coordinate
            function onChangeCoordinate() {
                layerGroup.clearLayers();
                var lintang = conf._lat.val();
                var bujur = conf._long.val();
                new L.marker([lintang,bujur]).addTo(layerGroup).bindPopup(conf.language.popupMessage+"<br>"+lintang+", "+bujur);
                //recenter to marker
                conf._mapObj.setView([lintang,bujur], conf.attr.mapZoom);
            }

            conf._lat.on('change', onChangeCoordinate);
            conf._long.on('change', onChangeCoordinate);

            //capture resize when the modal is opened
            const resizeObserver = new ResizeObserver(() => {
                map.invalidateSize();
            });
            resizeObserver.observe(conf._map[0]);

            //store for easy access
            conf._mapObj = map;
            conf._mapLayerGroup = layerGroup;

            if (conf.attr.readonly == true || !conf.attr.allowManualInput) {
                conf._lat.attr('readonly', true);
                conf._long.attr('readonly', true);
            }
  
            return conf._input;
        },
      
        get: function ( conf ) {
            //format: [lat, long]
            let lat = conf._lat.val();
            let lng = conf._long.val();

            if (lat == '' && lng == '')     return null;

			//add space after comma so that it wraps nicely on mobile
			return '["'+lat+'", "'+lng+'"]';
            
			//let val = [lat, lng];
            //return JSON.stringify(val);
        },
      
        set: function ( conf, val ) {
            //format: [lat, long]
            let json = [];
            try {
                json = JSON.parse(val);
            }
            catch(err) {
                return;
            }

            if (json !== null && (!Array.isArray(json) || json.length < 2)) {
                return;
            }

            if (json == null || (json[0] == "" && json[1] == "")) {
                conf._lat.val('');
                conf._long.val('');
                //clear marker
                conf._mapLayerGroup.clearLayers();
                //recenter the map
                let mapCenter = JSON.parse(conf.attr.mapCenter);
                conf._mapObj.setView(mapCenter, conf.attr.mapZoom);
                return;
            }

            //update the marker
            conf._lat.val(json[0]);
            conf._long.val(json[1]);
            conf._lat.trigger('change');

            //recenter the map
            conf._mapObj.setView(json,conf.attr.mapZoom);
        },
      
        enable: function ( conf ) {
            conf._enabled = true;
            conf._long.removeClass( 'disabled' ).prop("disabled", false);
            conf._lat.removeClass( 'disabled' ).prop("disabled", false);
        },
      
        disable: function ( conf ) {
            conf._enabled = false;
            conf._long.addClass( 'disabled' ).prop("disabled", true);
            conf._lat.addClass( 'disabled' ).prop("disabled", true);
        },

        center: function( conf, val, zoom=0 ) {
            //format: [lat, long]
            let json = [];
            try {
                json = JSON.parse(val);
            }
            catch(err) {
                return;
            }

            if (json == null || !Array.isArray(json) || json.length < 2) {
                return;
            }

            //recenter the map
            if (zoom == 0) {
                let zoom = conf._mapObj.getZoom();
            }
            conf._mapObj.setView(json,zoom);
        },

        zoom: function ( conf, val ) {
            let mapCenter = conf._mapObj.getCenter();
            conf._mapObj.setView(mapCenter,val);
        }
    };

	var tcg_geolocation = {};

	tcg_geolocation.defaults = {
        //whether it is editable or not
        readonly: false,
  
        //map height
        mapHeight: '200px',

        //map center
        mapCenter: '[-7.740462033789701,109.62647438049316]',

        //map zoom
        mapZoom: 14,

		//attribution in right-bottom corner of the map
		attribution: '',

		//street layer
		streetLayer: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

		//street layer
		satelliteLayer: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',

		//in case more than 1 editor has the same field name, this editor id can be used to distinguish it
		editorId: "",
		
		//allow to enter long-lat manually
		allowManualInput: false,
	};

	tcg_geolocation.messages = {
        info: 'Silahkan klik di peta <b>(<i class="fa fa-map-marker-alt fas"></i>)</b> untuk perubahan data koordinat.',
        popupMessage: "Lokasi: ",
        latitude: "Lintang",
        longitude: "Bujur",
	};
    
    })(jQuery, jQuery.fn.dataTable);
  