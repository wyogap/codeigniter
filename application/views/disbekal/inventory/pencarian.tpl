<!--
<link rel="stylesheet" href="{$base_url}assets/ionicons/css/ionicons.min.css">
<link rel="stylesheet" href="{$base_url}assets/css/ppdb.css">
<script src="{$base_url}assets/highcharts/code/highcharts.js"></script>
<script src="{$base_url}assets/highcharts/code/highcharts-more.js"></script>
<script src="{$base_url}assets/highcharts/code/themes/grid-light.js"></script>
-->

<link href="{$base_url}assets/highcharts/css/highcharts.css" rel="stylesheet" />
<link href="{$base_url}assets/leaflet/markercluster/MarkerCluster.css" rel="stylesheet" />
<link href="{$base_url}assets/leaflet/markercluster/MarkerCluster.Default.css" rel="stylesheet" />

<script src="{$base_url}assets/highcharts/highcharts.js"></script>
<script src="{$base_url}assets/highcharts/highcharts-more.js"></script>
<script src="{$base_url}assets/highcharts/themes/grid-light.js"></script>

<style>
    a>.info-box.bg-purple:hover {
        background-color: #8e54f9 !important;
    }

    a>.info-box.bg-red:hover {
        background-color: #f33c4d !important;
    }

    a>.info-box.bg-blue:hover {
        background-color: #3293fb !important;
    }

    .highcharts-container {
        margin: auto;
    }

    .info-box {
        text-align: center;
    }

    .info-box .info-box-number {
        font-size: xx-large;
    }

    .nav-tabs>li.active>a,
    .nav-tabs>li.active>a:focus,
    .nav-tabs>li.active>a:hover {
        /* background-color: #ccc !important; */
        border-left-color: #3c8dbc !important;
        border-right-color: #3c8dbc !important;
    }

    .nav-tabs-custom>.nav-tabs {
        border-bottom-color: #3c8dbc;
    }

    .geo-search-container {
        width: 500px;
    }

    @media screen and (max-width: 480px) {

        .geo-search-container {
            width: 100%;
        }

        .nav-justified .nav-item {
            -ms-flex-preferred-size: 0;
            flex-basis: 100%;
        }
    }

    @media screen and (max-width: 767px) {


        .navbar-toggle {
            z-index: 999 !important;
        }

        div.dataTables_paginate {
            display: inline-block;
            float: left !important;
            padding-top: 0.5em !important;
        }

        div.dataTables_info {
            display: inline-block;
            clear: left !important;
            float: left !important;
            padding-top: 0.835em !important;
            margin-left: 0px;
        }

        div.dataTables_length {
            display: inline-block;
            padding-top: 0.750em !important;
            clear: right !important;
            float: right !important;
        }

        .nav-tabs>li.active>a,
        .nav-tabs>li.active>a:focus,
        .nav-tabs>li.active>a:hover {
            background-color: #f4f4f4 !important;
            border-bottom-color: #3c8dbc !important;
            border-left-color: #3c8dbc !important;
            border-right-color: #3c8dbc !important;
        }

        .tahun-selection {
            position: relative;
            margin-top: 5px;
            top: 0;
            right: 0;
            float: none;
            padding-left: 0px;
            margin-left: -12px;
        }

        .navbar-collapse.pull-left+.navbar-custom-menu {
            display: block;
            position: absolute;
            top: 0;
            right: 60px !important;
        }

        .dropdown-menu>li>a {
            color: #fff;
        }
    }
</style>

<style>
    .autocomplete {
    /*the container must be positioned relative:*/
    position: relative;
    }
    .autocomplete-items {
    position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    /*position the autocomplete items to be the same width as the container:*/
    top: 100%;
    left: 0;
    right: 0;
    font-size: medium;
    }
    .autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    background-color: #fff;
    border-bottom: 1px solid #d4d4d4;
    }
    .autocomplete-items div:hover {
    /*when hovering an item:*/
    background-color: #e9e9e9;
    }
    .autocomplete-active {
    /*when navigating through the items using the arrow keys:*/
    background-color: DodgerBlue !important;
    color: #ffffff;
    }

    .btn .caret {
        margin-left: 0;
    }
    .caret {
        display: inline-block;
        width: 0;
        height: 0;
        margin-left: 2px;
        vertical-align: middle;
        border-top: 4px dashed;
        border-top: 4px solid\9;
        border-right: 4px solid transparent;
        border-left: 4px solid transparent;
    }

    .marker-cluster {
        background-clip: padding-box;
        border-radius: 20px;
    }

    .marker-cluster-1000 {
        background-clip: padding-box;
        border-radius: 25px;
    }

    .marker-cluster-1000 div {
        width: 40px;
        height: 40px;
        margin-left: 5px;
        margin-top: 5px;
        text-align: center;
        border-radius: 20px;
        font: 12px "Helvetica Neue", Arial, Helvetica, sans-serif;
    }

    .marker-cluster-1000 span {
        line-height: 40px;
    }

    .marker-cluster-10000 {
        background-clip: padding-box;
        border-radius: 30px;
    }

    .marker-cluster-10000 div { 
        width: 50px;
        height: 50px;
        margin-left: 5px;
        margin-top: 5px;
        text-align: center;
        border-radius: 25px;
        font: 12px "Helvetica Neue", Arial, Helvetica, sans-serif;
    }

    .marker-cluster-10000 span {
        line-height: 50px;
    }

    .marker-cluster-100000 {
        background-clip: padding-box;
        border-radius: 40px;
    }

    .marker-cluster-100000 div {
        width: 60px;
        height: 60px;
        margin-left: 5px;
        margin-top: 5px;
        text-align: center;
        border-radius: 30px;
        font: 12px "Helvetica Neue", Arial, Helvetica, sans-serif;
    }

    .marker-cluster-100000 span {
        line-height: 60px;
    }

</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div id="peta" style="width: 100%; height: calc(100vh - 100px);"></div><br>
            </div>
        </div>
    </div>
</div>

<script id="geo-search" type="text/template">
    <div class="geo-search-container">
        <div class="row">
                <div class="col-md-12">
                <div class="input-group autocomplete" id="adv-search">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Pencarian" fdprocessedid="cwui6">
                        <div class="input-group-btn cust-input-grp">
                        <div class="btn-group">
                                <button type="submit" class="btn btn-primary btn-search" fdprocessedid="5vqcq">
                                <span class="glyphicon glyphicon-search">
                                        <i class="fas fa-search"></i>
                                </span>
                                </button>
                                <a class="btn btn-default adv-search-btn" href="#">
                                <span class="d-none d-md-inline">Filter </span>
                                <span class="caret"></span>
                                </a>
                        </div>
                        </div>
                </div>
                </div>
        </div>
        <div class="row adv-search-box" style="display: none;">
                <div class="form-group mb-0 mt-2 col-12 col-sm-6">
                <select id="f_itemtypeid" name="itemtypeid" class="form-control filter_select" placeholder="Tipe Bekal" fdprocessedid="384oob">
                        <option value="">-- Tipe Bekal --</option>
                </select>
                </div>
                <div class="form-group mb-0 mt-2 col-12 col-sm-6">
                <select id="f_siteid" name="siteid" class="form-control filter_select" placeholder="Satuan Kerja" fdprocessedid="lhqr5n">
                        <option value="">-- Satuan Kerja --</option>
                </select>
                </div>
                <div class="form-group mb-0 mt-2 col-12 col-sm-6">
                <select id="f_age" name="age" class="form-control filter_select" placeholder="Usia Stok" fdprocessedid="lhqr5n">
                        <option value="">-- Usia Stok --</option>
                        <option value="1"> <= 1 Tahun </option>
                        <option value="2"> 1 - 2 Tahun </option>
                        <option value="3"> 2 - 3 Tahun </option>
                        <option value="4"> 3 - 4 Tahun </option>
                        <option value="5"> 4 - 5 Tahun </option>
                        <option value="6"> > 5 Tahun </option>
                </select>
                </div>
        </div>
    </div>
</script>

<script type="text/javascript">
    var map;
    var overlays;
    var icon;
    var circleIcon;
    var markers;
    var oms;

    function indo_money(number) {
        let label = "";
        let absnumber = Math.abs(number);

        if (absnumber > 1000000000) {
            number = Math.round(number / 1000000000);
            label = number + " Mil";
        } else if (absnumber > 1000000) {
            number = Math.round(number / 1000000);
            label = number + " Juta";
        } else if (absnumber > 1000) {
            number = Math.round(number / 1000);
            label = number + " Ribu";
        } else {
            number = Math.round(number);
            label = number;
        }

        return label;
    }

    $(document).ready(function() {

        {literal}
        //Peta
        map = L.map('peta', {
            zoomControl: false
        }).setView([-2.189275, 119.7852448], 5);
        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 15,
                attribution: 'DISBEKAL TNI-AL',
                id: 'mapbox.streets'
            }
        ).addTo(map);

        var streetmap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                id: 'mapbox.light',
                attribution: ''
            }),
            satelitemap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                id: 'mapbox.streets',
                attribution: ''
            });
        var baseLayers = {
            "Satelite": satelitemap,
            "Streets": streetmap
        };

        //Layer Group
        var jGudang = new L.LayerGroup();
        var jSearch = new L.LayerGroup();

        //Adding Layer Group
        jSearch.addTo(map);
        jGudang.addTo(map);

        //add overlays menu
        overlays = {
            "Gudang": jGudang,
            "Pencarian": jSearch,
        };
        L.control.layers(baseLayers, overlays).addTo(map);

        new L.control.fullscreen({
            position: 'bottomleft'
        }).addTo(map);
        new L.Control.Zoom({
            position: 'bottomright'
        }).addTo(map);

        {/literal}

        //create search pane above the marker pane
        map.createPane('search').style.zIndex = 610;

        //create cluster marker for search
        markers = L.markerClusterGroup({
            //use cluster marker icon even for single marker
            singleMarkerMode: true,

            //create clustermarker icon
            iconCreateFunction: function(cluster) {
                var children = cluster.getAllChildMarkers();
                var n = 0;
                for (var i = 0; i < children.length; i++) {
                    n += children[i].total;
                }

                //TODO: use different size icon depending the value of n (=> the length of the numer)
                if (n<1000) {
                    return L.divIcon({
                        html: '<div><span>' + n + '</span></div>',
                        className: "marker-cluster marker-cluster-large",
                        iconSize: [40, 40],
                        iconAnchor: [20, 20]
                    });
                }
                else if (n<10000) {
                    return L.divIcon({
                        html: '<div><span>' + accounting.formatNumber(n,0,'.') + '</span></div>',
                        className: "marker-cluster-1000 marker-cluster-large",
                        iconSize: [50, 50],
                        iconAnchor: [25, 25]
                    });
                }
                else if (n<100000) {
                    return L.divIcon({
                        html: '<div><span>' + accounting.formatNumber(n,0,'.') + '</span></div>',
                        className: "marker-cluster-10000 marker-cluster-large",
                        iconSize: [60, 60],
                        iconAnchor: [30, 30]
                    });
                }
                else {
                    return L.divIcon({
                        html: '<div><span>' + accounting.formatNumber(n,0,'.') + '</span></div>',
                        className: "marker-cluster-100000 marker-cluster-large",
                        iconSize: [70, 70],
                        iconAnchor: [35, 35]
                    });
                }
            },

            //Disable all of the defaults:
            //maxClusterRadius: 120,
            //spiderfyOnMaxZoom: false, showCoverageOnHover: false, zoomToBoundsOnClick: false

            //use Search pane which is above the other marker pane
            clusterPane: 'search'
        });

        //when a single marker is clicked
        markers.on('click', function(marker) {
            console.log('marker ' + marker.layer.storeid);
        });

        //cluster marker is under search overlays
        markers.addTo(overlays["Pencarian"]);

        //create 
        oms = new OverlappingMarkerSpiderfier(map, {
            markersWontMove: true,
            markersWontHide: true,
            basicFormatEvents: true,
            keepSpiderfied: true
        });

        //delegate the onclick popup to oms
        var popup = new L.Popup({
            closeButton: false,
            offset: new L.Point(0.5, -24)
        });
        oms.addListener('click', function(marker) {
            popup.setContent(marker.desc);
            popup.setLatLng(marker.getLatLng());
            map.openPopup(popup);
        });

        //build store maps
        build_store_maps();

        //create search control
        L.Control.TcgSearch = L.Control.extend({
            includes: L.Mixin.Events,
            options: {
                position: 'topleft',
            },
            initialize: function(options) {
                L.setOptions(this, options);

                var el = L.DomUtil.create('div', this.options.className);;

                //build the control
                let dom = $(render_template('#geo-search', {
                    // 'name': conf.name,
                    // 'type': conf.type,
                    // 'fieldInfo': conf.info,
                    // 'className': conf.className
                }));
                el.appendChild(dom[0]);

                //position the control
                L.DomUtil.addClass(el, this.options.position);

                //store for easy access
                this._container = el;

                return this;
            },
            isToggled: function() {
                return L.DomUtil.hasClass(this._container, this.options.toggleButton);
            },
            _fireClick: function(e) {
                this.fire('click');

                if (this.options.toggleButton) {
                    var btn = this._container;
                    if (this.isToggled()) {
                        L.DomUtil.removeClass(this._container, this.options.toggleButton);
                    } else {
                        L.DomUtil.addClass(this._container, this.options.toggleButton);
                    }
                }
            },
            onAdd: function(map) {
                if (this._container) {
                    L.DomEvent.on(this._container, 'click', this._fireClick, this);
                    var stop = L.DomEvent.stopPropagation;
                    L.DomEvent.on(this._container, 'mousedown', stop)
                        .on(this._container, 'touchstart', stop)
                        .on(this._container, 'dblclick', stop)
                        .on(this._container, 'mousewheel', stop)
                        .on(this._container, 'MozMozMousePixelScroll', stop)
                    this.fire('load');

                    this._map = map;
                }

                return this._container;
            },
            onRemove: function(map) {
                if (this._container && this._map) {
                    L.DomEvent.off(this._container, 'click', this._fireClick, this);
                    L.DomEvent.off(this._container, 'mousedown', stop)
                        .off(this._container, 'touchstart', stop)
                        .off(this._container, 'dblclick', stop)
                        .off(this._container, 'mousewheel', stop)
                        .off(this._container, 'MozMozMousePixelScroll', stop)

                    this.fire('unload');
                    this._map = null;
                }

                return this;
            }
        });

        L.control.tcgSearch = function(options) {
            return new L.Control.TcgSearch(options);
        };

        //add to map
        var search = new L.Control.TcgSearch();
        search.addTo(map);

        //autocomplete search
        autocomplete(document.getElementById("search"));

        //toggle adv search
        $('.adv-search-btn').click(function(e) {
            $('.adv-search-box').toggle();
        });

        //do search when button click
        $('.btn-search').click(function(e) {
            e.stopPropagation();
            //close search suggestion
            closeAllLists();
            //reload, reset paging
            do_search();
        });

        //do search when enter
        $("#search").keyup(function(e) {
            if (e.which == 13) {
                $('.btn-search').trigger('click');
            }
        });

        //populate the list
        populate_filter_itemtypeid();

        //reset the value
        $("#f_itemtypeid").val(v_itemtypeid).trigger('change');

        if (v_itemtypeid!='' && v_itemtypeid!=0) {
            $("#f_itemtypeid").attr("disabled", true);
        }

        //on-change event
        $('#f_itemtypeid').on('change', function() {
            v_itemtypeid = $("#f_itemtypeid").val();

            if (_onfilterchanging)
                return;
            _onfilterchanging = true;

            do_search();

            _onfilterchanging = false;
        });

        //populate the list
        populate_filter_siteid();

        //reset the value
        $("#f_siteid").val(v_siteid).trigger('change');

        //on-change event
        $('#f_siteid').on('change', function() {
            v_siteid = $("#f_siteid").val();

            if (_onfilterchanging)
                return;
            _onfilterchanging = true;

            do_search();

            _onfilterchanging = false;
        });
   
        //master list of items
        populate_filter_itemid();

        //stock age
        populate_filter_age();

        //reset the value
        $("#f_age").val(v_age).trigger('change');

        //on-change event
        $('#f_age').on('change', function() {
            v_age = $("#f_age").val();

            if (_onfilterchanging)
                return;
            _onfilterchanging = true;

            do_search();

            _onfilterchanging = false;
        });
   
    });

    function build_store_maps() {
        $.post("{$site_url}disbekal/dashboard/stokpergudang", {},
            function(response, status) {
                response.data.forEach(function(value, index, array) {
                    if (value.latitude != "" && value.latitude != null && value.longitude != "" && value.longitude != null) {

                        var marker = L.marker([parseFloat(value.latitude), parseFloat(value.longitude)]);

                        //TODO: use moustache template to provide visually more attractive popup
                        // var msg = "<div style='border-bottom: 1px solid gray; margin-bottom: 6px; font-size: small;'>" +value.description+ " <a href='#' onclick='switch_store(\"" + value.storeid + "\")'><i class='fa fas fa-external-link-alt'></i></a></div>" +
                        //     "Nilai Stok: <b>" + indo_money(value.nilai_total) + "</b>, Rusak: <b>" + indo_money(value.rusak) + "</b>, Kadaluarsa: <b>" + indo_money(value.kadaluarsa) + "</b>" +
                        //     "<br>Perwira Gudang: ";
                        var msg = render_template('#popover-store', {
                                    "storename"     : value.description,
                                    "storeid"       : value.storeid,
                                    "nilaitotal"    : indo_money(value.nilai_total),
                                    "nilairusak"    : indo_money(value.rusak),
                                    "kadaluarsa"    : indo_money(value.kadaluarsa),
                                    "fastmoving"    : 0,
                                    "perwiragudang" : 'Perwira Gudang',
                                });
                        marker.desc = msg;
                        marker.storeid = value.storeid;

                        //add to oms (and automatically to map)
                        oms.addMarker(marker);

                        //store marker under Store overlays
                        marker.addTo(overlays["Gudang"]);

                        // //dummy search result
                        // var m = L.marker([parseFloat(value.latitude), parseFloat(value.longitude)], {
                        //     title: value.description
                        // });
                        // m.total = 100;
                        // m.storeid = value.storeid;

                        // //add to cluster marker (and automatically to map and to Search overlay)
                        // markers.addLayer(m);

                    }
                });
            },
            "json");
    }

    function switch_store(storeid) {
        $("#edit-store").val(storeid);
        toastr.info("Belum diimplementasikan");
    }

    function do_search() {
        let str = $("#search").val().trim();

        //retrieve list from json
        let url = "{$site_url}disbekal/dashboard/gissearch"
                        + "?f_siteid=" +(v_siteid==null?"":v_siteid)
                        + "&f_itemtypeid=" +(v_itemtypeid==null?"":v_itemtypeid)
                        + "&f_age=" +(v_age==null?"":v_age)
                        + "&q=" +(str==null?"":str);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                markers.clearLayers();

                if (response.error !== undefined && response.error !== null && response.error != "") {
                    if (response.error=="not-login") {
                        //login ulang
                        window.location.href = "{$site_url}" +'auth';
                    }
                    toastr.error("Tidak berhasil melakukan pencarian");
                    toastr.error(msg)
                    return
                }
                else if (response.data === null || response.data == undefined) {
                    toastr.warning("Tidak ada data hasil pencarian");
                    return;
                }

                //clear existing result
                // let children = markers.getAllChildMarkers()
                // markers.removeLayers(children);

                response.data.forEach(function(value, index, array) {
                    if (value.latitude != "" && value.latitude != null && value.longitude != "" && value.longitude != null) {

                        //search result
                        var m = L.marker([parseFloat(value.latitude), parseFloat(value.longitude)], {
                            title: value.description
                        });
                        m.total = parseInt(value.total);
                        m.storeid = value.storeid;

                        //add to cluster marker (and automatically to map and to Search overlay)
                        markers.addLayer(m);
                    }
                });
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                        (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                         && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });
        
    }

</script>

<script>

    var v_itemtypeid = '{if !empty($userdata["itemtypeid"])}{$userdata["itemtypeid"]}{/if}';
    var v_siteid = '{if !empty($userdata["siteid"])}{$userdata["siteid"]}{/if}';
    var items = [];
    var v_age = "";

    var _onfilterchanging = false;

    function autocomplete(inp) {
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) { return false;}
            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            let str = val.toUpperCase();
            for (i = 0; i < items.length; i++) {
                let item = items[i];
                //ignore if not the itemtype selected
                if (v_itemtypeid != null && v_itemtypeid != '' && v_itemtypeid != item['itemtypeid']) {
                    continue;
                }
                /*check if the item starts with the same letters as the text field value:*/
                let label = item['label'];
                if (val.length<3) {
                    //search start-with
                    if (label.substr(0, val.length).toUpperCase() == str) {
                        /*create a DIV element for each matching element:*/
                        b = document.createElement("DIV");
                        /*make the matching letters bold:*/
                        b.innerHTML = "<strong>" + label.substr(0, val.length) + "</strong>";
                        b.innerHTML += label.substr(val.length);
                        /*insert a input field that will hold the current array item's value:*/
                        b.innerHTML += "<input type='hidden' value='" + label + "'>";
                        /*execute a function when someone clicks on the item value (DIV element):*/
                        b.addEventListener("click", function(e) {
                            /*insert the value for the autocomplete text field:*/
                            inp.value = this.getElementsByTagName("input")[0].value;
                            /*close the list of autocompleted values,
                            (or any other open lists of autocompleted values:*/
                            closeAllLists();
                            //do search
                            do_search();
                        });
                        a.appendChild(b);
                    }
                }
                else {
                    //do regex search
                    let idx = label.toUpperCase().search(str);
                    if (idx != -1) {
                        /*create a DIV element for each matching element:*/
                        b = document.createElement("DIV");
                        /*make the matching letters bold:*/
                        b.innerHTML = '';
                        if (idx>0) {
                            b.innerHTML += label.substr(0, idx);
                        }
                        b.innerHTML += "<strong>" + label.substr(idx, val.length) + "</strong>";
                        b.innerHTML += label.substr(val.length+idx);
                        /*insert a input field that will hold the current array item's value:*/
                        b.innerHTML += "<input type='hidden' value='" + label + "'>";
                        /*execute a function when someone clicks on the item value (DIV element):*/
                        b.addEventListener("click", function(e) {
                            /*insert the value for the autocomplete text field:*/
                            inp.value = this.getElementsByTagName("input")[0].value;
                            /*close the list of autocompleted values,
                            (or any other open lists of autocompleted values:*/
                            closeAllLists();
                            //do search
                            do_search();
                        });
                        a.appendChild(b);
                    }
                }
            }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
                else {
                    //submit search with wild card
                    $('.btn-search').trigger('click');
                }
            }
        });
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        let inp = document.getElementById("search");
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }

    function populate_filter_itemtypeid() {
        let _options = [];
        let _attr = {};

        //default value
        let _multiple = false;
        let _minimumResult = 10;
        let _url = '';

        _attr = {
            multiple: _multiple,
            minimumResultsForSearch: _minimumResult,
        };

        //retrieve list from json
        url = "{$site_url}crud/tipebekal/lookup";

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.data === null) {
                    //error("Gagal mendapatkan daftar kas.");
                    _options = null;
                } else if (typeof response.error !== 'undefined' && response.error !== null && response.error != "") {
                    //error(response.error);
                    _options = null;
                } else {
                    _options = response.data;
                }

                select2_build($('#f_itemtypeid'), "-- Tipe Bekal --", "", v_itemtypeid, _options, _attr, null);
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
                //build select2 with default options
                select2_build($('#f_itemtypeid'), "-- Tipe Bekal --", "", v_itemtypeid, _options, _attr, null);
            }
        });
    }

    function populate_filter_siteid() {
        let _options = [];
        let _attr = {};

        //default value
        let _multiple = false;
        let _minimumResult = 10;
        let _url = '';

        _attr = {
            multiple: _multiple,
            minimumResultsForSearch: _minimumResult,
        };

        //retrieve list from json
        url = "{$site_url}crud/satuankerja/lookup";

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.data === null) {
                    //error("Gagal mendapatkan daftar kas.");
                    _options = null;
                } else if (typeof response.error !== 'undefined' && response.error !== null && response.error != "") {
                    //error(response.error);
                    _options = null;
                } else {
                    _options = response.data;
                }

                select2_build($('#f_siteid'), "-- Satuan Kerja --", "", v_siteid, _options, _attr, null);
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
                //build select2 with default options
                select2_build($('#f_siteid'), "-- Satuan Kerja --", "", v_siteid, _options, _attr, null);
            }
        });
    }

    function populate_filter_itemid() {
        let _options = [];
        let _attr = {};

        //default value
        let _multiple = false;
        let _minimumResult = 10;
        let _url = '';

        _attr = {
            multiple: _multiple,
            minimumResultsForSearch: _minimumResult,
        };

        //retrieve list from json
        if (v_itemtypeid !== null && v_itemtypeid != '') {
            url = "{$site_url}crud/bekal/lookup?f_itemtypeid=" +v_itemtypeid;
        }
        else {
            url = "{$site_url}crud/bekal/lookup";
        }
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            beforeSend: function(request) {
                request.setRequestHeader("Content-Type", "application/json");
            },
            success: function(response) {
                if (response.data === null) {
                    //error("Gagal mendapatkan daftar kas.");
                    _options = null;
                } else if (typeof response.error !== 'undefined' && response.error !== null && response.error != "") {
                    //error(response.error);
                    _options = null;
                } else {
                    _options = response.data;
                }

                items = response.data;
            },
            error: function(jqXhr, textStatus, errorMessage) {
                if (jqXhr.status == 403 || errorMessage == 'Forbidden' || 
                    (jqXhr.responseJSON !== undefined && jqXhr.responseJSON != null 
                        && jqXhr.responseJSON.error != undefined && jqXhr.responseJSON.error == 'not-login')
                    ) {
                    //login ulang
                    window.location.href = "{$site_url}" +'auth';
                }
                //send toastr message
                toastr.error(errorMessage);
            }
        });
    }

    function populate_filter_age() {
        let _options = [];
        let _attr = {};

        //default value
        let _multiple = false;
        let _minimumResult = 10;
        let _url = '';

        _attr = {
            multiple: _multiple,
            minimumResultsForSearch: _minimumResult,
        };

        //rebuild as select2
        select2_rebuild($('#f_age'), _attr, null);
    }


</script>

{literal}
<script id="popover-store" type="text/template">
    <div class="gis-popover">
        <div style="border-bottom: 1px solid gray; margin-bottom: 6px; font-size: small; padding-bottom: 3px;">{{storename}}
            <a href="#" onclick="switch_store({{storeid}})"><i class="fa fas fa-external-link-alt"></i></a>
        </div>
        <div style="margin-left: -2px; margin-right: -2px; margin-bottom: 6px;">
            <div class="bg-gray" style="display: inline-block; margin-left: 2px; margin-right: 2px; text-align: center; padding: 8px; border-radius: 0.25rem">
                <span style="display: block; padding-bottom: 4px; border-bottom: solid 1px; font-size: small; padding-left: 4px; padding-right: 4px">
                {{nilaitotal}}
                </span>    
                <span style="display: block; padding-top: 6px; font-size: smaller;">
                Nilai Stok
                </span>    
            </div>
            <div class="bg-purple" style="display: inline-block; margin-left: 2px; margin-right: 2px; text-align: center; padding: 8px; border-radius: 0.25rem">
                <span style="display: block; padding-bottom: 4px; border-bottom: solid 1px; font-size: small; padding-left: 4px; padding-right: 4px">
                {{nilairusak}}
                </span>    
                <span style="display: block; padding-top: 6px; font-size: smaller;">
                Rusak/Kadaluarsa
                </span>    
            </div>
            <div class="bg-blue" style="display: inline-block; margin-left: 2px; margin-right: 2px; text-align: center; padding: 8px; border-radius: 0.25rem">
                <span style="display: block; padding-bottom: 4px; border-bottom: solid 1px; font-size: small; padding-left: 4px; padding-right: 4px">
                {{kadaluarsa}}
                </span>    
                <span style="display: block; padding-top: 6px; font-size: smaller;">
                Fast Moving
                </span>    
            </div>
        </div>
        <div>
            <img src="http://localhost/pusbekal/assets/image/user.png" class="user-image img-circle elevation-2" alt="User Image" style="width: 2.1rem; height: 2.1rem;">
            <span style="margin-left: 6px; font-size: small;">{{perwiragudang}}</span>
        </div>
    </div>
</script>
{/literal}

<script>

</script>