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

    body {
        overflow: hidden !important;
    }

    .content-header {
        padding: 0.5rem 0.5rem;
    }

    .peta {
        width: 100%; height: calc(100vh - 65px);
    }

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

        .peta {
            width: 100%; height: calc(100vh - 120px);
        }

        .geo-search-container {
            width: 80%;
        }

        .nav-justified .nav-item {
            -ms-flex-preferred-size: 0;
            flex-basis: 100%;
        }

        .content-header {
            padding: .5rem .5rem;
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

{include file="crud/_css.tpl"}
{include file="disbekal/css.tpl"}

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
        /* scrollbar */
        max-height: 300px;
        overflow-y: auto;
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
                <div id="peta" class="peta"></div><br>
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
                <!--
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
                -->
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

            zoomToBoundsOnClick:false,

            //create clustermarker icon
            iconCreateFunction: function(cluster) {
                var children = cluster.getAllChildMarkers();
                var n = 0, n0 = 0, n1 = 0, n2 = 0, n3 = 0;
                for (var i = 0; i < children.length; i++) {
                    n += children[i].total;
                    // n0 += parseInt(children[i].total0);
                    // n1 += parseInt(children[i].total1);
                    // n2 += parseInt(children[i].total2);
                    // n3 += parseInt(children[i].total3);
                }

                //use different size icon depending the value of n (=> the length of the numer)
                let marker = null;
                if (n<1000) {
                    marker = L.divIcon({
                        html: '<div><span>' + n + '</span></div>',
                        className: "marker-cluster marker-cluster-large",
                        iconSize: [40, 40],
                        iconAnchor: [20, 20]
                    });
                }
                else if (n<10000) {
                    marker = L.divIcon({
                        html: '<div><span>' + accounting.formatNumber(n,0,'.') + '</span></div>',
                        className: "marker-cluster-1000 marker-cluster-large",
                        iconSize: [50, 50],
                        iconAnchor: [25, 25]
                    });
                }
                else if (n<100000) {
                    marker = L.divIcon({
                        html: '<div><span>' + accounting.formatNumber(n,0,'.') + '</span></div>',
                        className: "marker-cluster-10000 marker-cluster-large",
                        iconSize: [60, 60],
                        iconAnchor: [30, 30]
                    });
                }
                else {
                    marker = L.divIcon({
                        html: '<div><span>' + accounting.formatNumber(n,0,'.') + '</span></div>',
                        className: "marker-cluster-100000 marker-cluster-large",
                        iconSize: [70, 70],
                        iconAnchor: [35, 35]
                    });
                }

                marker.total = n;
                // var msg = render_template('#popover-search', {
                //                     "total0"    : accounting.formatNumber(n0,0,'.'),
                //                     "total1"    : accounting.formatNumber(n1,0,'.'),
                //                     "total2"    : accounting.formatNumber(n2,0,'.'),
                //                     "total3"    : accounting.formatNumber(n3,0,'.'),
                //                 });
                // marker.desc = msg;
                //alert(msg);

                return marker;
            },

            //Disable all of the defaults:
            //maxClusterRadius: 120,
            //spiderfyOnMaxZoom: false, showCoverageOnHover: false, zoomToBoundsOnClick: false

            //use Search pane which is above the other marker pane
            clusterPane: 'search'
        });

        //when a single marker is clicked
        markers.on('click', function(marker) {
            //console.log('marker ' + marker.layer.storeid);
            popup.setContent(marker.layer.desc);
            popup.setLatLng(marker.latlng);
            map.openPopup(popup);
        });

        // markers.on('mouseover', function(c) {
        //     var popup = L.popup()
        //         .setLatLng(c.layer.getLatLng())
        //         .setContent(c.layer.desc)
        //         .openOn(map);
        // }).on('mouseout',function(c){
        //         map.closePopup();
        // });
        
        markers.on('clusterclick',function(c){
            c.layer = calculate_aggregate(c.layer);
        
            var popup = L.popup()
                .setLatLng(c.layer.getLatLng())
                .setContent(c.layer.desc)
                .openOn(map);
        });

        // markers.on('clustermouseover', function(c) {
        //     if (c.layer.desc === undefined) {
        //         var children = c.layer._markers;
        //         var n = 0, n0 = 0, n1 = 0, n2 = 0, n3 = 0;
        //         for (var i = 0; i < children.length; i++) {
        //             n += children[i].total;
        //             n0 += children[i].total0;
        //             n1 += children[i].total1;
        //             n2 += children[i].total2;
        //             n3 += children[i].total3;
        //         }   
        //         children = c.layer._childClusters;
        //         for (var i = 0; i < children.length; i++) {
        //             n += children[i].total;
        //             n0 += children[i].total0;
        //             n1 += children[i].total1;
        //             n2 += children[i].total2;
        //             n3 += children[i].total3;
        //         }   

        //         var msg = render_template('#popover-search', {
        //                             "total0"    : accounting.formatNumber(n0,0,'.'),
        //                             "total1"    : accounting.formatNumber(n1,0,'.'),
        //                             "total2"    : accounting.formatNumber(n2,0,'.'),
        //                             "total3"    : accounting.formatNumber(n3,0,'.'),
        //                         });

        //         c.layer.total = n;
        //         c.layer.total0 = n0;
        //         c.layer.total1 = n1;
        //         c.layer.total2 = n2;
        //         c.layer.total3 = n3;

        //         c.layer.desc = msg;
        //     }
        
        //     var popup = L.popup()
        //         .setLatLng(c.layer.getLatLng())
        //         .setContent(c.layer.desc)
        //         .openOn(map);
        // }).on('clustermouseout',function(c){
        //     map.closePopup();
        // });

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

    function calculate_aggregate(layer) {
        if (layer.desc !== undefined && !isNaN(layer.total) && layer.total>0) {
            return layer;
        }

        var children = layer._markers;
        var n = 0, n0 = 0, n1 = 0, n2 = 0, n3 = 0;
        var storeid = '';
        for (var i = 0; i < children.length; i++) {
            n += children[i].total;
            n0 += children[i].total0;
            n1 += children[i].total1;
            n2 += children[i].total2;
            n3 += children[i].total3;
            storeid += "," +children[i].storeid;
        }   
        children = layer._childClusters;
        for (var i = 0; i < children.length; i++) {
            if (children[i].total == 0 || isNaN(children[i].total)) {
                children[i] = calculate_aggregate(children[i]);
            }
            n += (children[i].total === undefined) ? 0 : children[i].total;
            n0 += (children[i].total0 === undefined) ? 0 : children[i].total0;
            n1 += (children[i].total1 === undefined) ? 0 : children[i].total1;
            n2 += (children[i].total2 === undefined) ? 0 : children[i].total2;
            n3 += (children[i].total3 === undefined) ? 0 : children[i].total3;
            storeid += "," +children[i].storeid;
        }   

        storeid = storeid.substring(1);
        var msg = render_template('#popover-search', {
                            "total0"    : accounting.formatNumber(n0,0,'.'),
                            "total1"    : accounting.formatNumber(n1,0,'.'),
                            "total2"    : accounting.formatNumber(n2,0,'.'),
                            "total3"    : accounting.formatNumber(n3,0,'.'),
                            "link0"     : n0>0 ? true : false,
                            "link1"     : n1>0 ? true : false,
                            "link2"     : n2>0 ? true : false,
                            "link3"     : n3>0 ? true : false,
                            "storeid"   : storeid,
                });

        layer.total = n;
        layer.total0 = n0;
        layer.total1 = n1;
        layer.total2 = n2;
        layer.total3 = n3;
        layer.storeid = storeid;

        layer.desc = msg;

        return layer;
    }

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
                                    "nilairusak"    : indo_money(parseInt(value.rusak) + parseInt(value.kadaluarsa)),
                                    "kadaluarsa"    : indo_money(value.kadaluarsa),
                                    "fastmoving"    : indo_money(value.fastmoving),
                                    "lowstock"      : parseInt(value.lowstock),
                                    "perwiragudang" : 'Perwira Gudang',
                                    "userimage"     : '{$base_url}assets/image/user.png'
                                });
                        marker.desc = msg;
                        marker.storeid = value.storeid;

                        //add to oms (and automatically to map)
                        oms.addMarker(marker);

                        //store marker under Store overlays
                        marker.addTo(overlays["Gudang"]);
                    }
                });
            },
            "json");
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
                        m.total0 = parseInt(value.total0);
                        m.total1 = parseInt(value.total1);
                        m.total2 = parseInt(value.total2);
                        m.total3 = parseInt(value.total3);
                        m.storeid = value.storeid;

                        var msg = render_template('#popover-search', {
                                    "total0"    : accounting.formatNumber(value.total0,0,'.'),
                                    "total1"    : accounting.formatNumber(value.total1,0,'.'),
                                    "total2"    : accounting.formatNumber(value.total2,0,'.'),
                                    "total3"    : accounting.formatNumber(value.total3,0,'.'),
                                    "link0"     : value.total0>0 ? true : false,
                                    "link1"     : value.total1>0 ? true : false,
                                    "link2"     : value.total2>0 ? true : false,
                                    "link3"     : value.total3>0 ? true : false,
                                    "storeid"   : value.storeid,
                                });
                        m.desc = msg;

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
                toastr.error(textStatus);
            }
        });
        
    }

</script>

<script type="text/javascript">

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
                toastr.error(textStatus);
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
                toastr.error(textStatus);
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
                toastr.error(textStatus);
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
            <a href="#" onclick="switch_store({{storeid}}, '{{storename}}')"><i class="fa fas fa-external-link-alt"></i></a>
        </div>
        <div style="margin-left: -2px; margin-right: -2px; margin-bottom: 6px;">
            <div class="bg-gray" style="display: block; margin-left: 2px; margin-right: 2px; text-align: center; padding: 8px; border-radius: 0.25rem; width: 100%">
                <span style="display: block; padding: 4px; font-size: small">
                Nilai Stok : {{nilaitotal}}
                </span>    
            </div>
        </div>
        <div style="margin-left: -2px; margin-right: -2px; margin-bottom: 6px;">
            <div class="bg-blue" style="display: inline-block; margin-left: 2px; margin-right: 2px; text-align: center; padding: 8px; border-radius: 0.25rem">
                <span style="display: block; padding-bottom: 4px; border-bottom: solid 1px; font-size: small; padding-left: 4px; padding-right: 4px">
                {{fastmoving}}
                </span>    
                <span style="display: block; padding-top: 6px; font-size: smaller;">
                Fast Moving
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
            <div class="bg-red" style="display: inline-block; margin-left: 2px; margin-right: 2px; text-align: center; padding: 8px; border-radius: 0.25rem">
                <span style="display: block; padding-bottom: 4px; border-bottom: solid 1px; font-size: small; padding-left: 4px; padding-right: 4px">
                {{lowstock}}
                </span>    
                <span style="display: block; padding-top: 6px; font-size: smaller;">
                Low Stock
                </span>    
            </div>
        </div>
        <div>
            <img src="{{userimage}}" class="user-image img-circle elevation-2" alt="User Image" style="width: 2.1rem; height: 2.1rem;">
            <span style="margin-left: 6px; font-size: small;">{{perwiragudang}}</span>
        </div>
    </div>
</script>

<style>
    .popover-search th {
        text-align: center;
        padding: 2px 4px;
        border-top: 1px solid #dee2e6;
        border-bottom: 2px solid #dee2e6;
        text-wrap: nowrap;
    }

    .popover-search td {
        text-align: center;
        padding: 2px 4px;
    }

    .dt-col-80 {
        max-width: 80px;
    }

    .dt-col-60 {
        max-width: 60px;
    }

    .dt-col-40 {
        max-width: 40px;
    }

    .accordion .card-header .btn-link {
        color: black;
    }

    .modal .modal-header .modal-title { 
        width: 100%;
        margin: auto;
        text-align: center;
    }

    .modal .modal-header button {
        position: absolute;
        right: 16px;
    }

    .accordion .card-header:has(+ .collapse.show) {
        background-color: var(--theme-color);
        color: black;
    }
</style>

<script id="popover-search" type="text/template">
    <table class="popover-search">
        <thead><tr>
            <th>0 Tahun</th>
            <th>1-2 Tahun</th>
            <th>3-4 Tahun</th>
            <th>>5 Tahun</th>
        </tr></thead>
        <tbody><tr>
            <td>{{total0}}{{#link0}} <a href="#" onclick="detail_search(0, '{{storeid}}')"><i class="fa fas fa-external-link-alt"></i></a>{{/link0}}</td>
            <td>{{total1}}{{#link1}} <a href="#" onclick="detail_search(1, '{{storeid}}')"><i class="fa fas fa-external-link-alt"></i></a>{{/link1}}</td>
            <td>{{total2}}{{#link2}} <a href="#" onclick="detail_search(2, '{{storeid}}')"><i class="fa fas fa-external-link-alt"></i></a>{{/link2}}</td>
            <td>{{total3}}{{#link3}} <a href="#" onclick="detail_search(3, '{{storeid}}')"><i class="fa fas fa-external-link-alt"></i></a>{{/link3}}</td>
        </tr></tbody>
    </table>
</script>
{/literal}

<div id='modal-bekal' class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document"><div class="modal-content" style="max-width: 700px; margin: auto;">
    <div class="modal-header">
        <h5 class="modal-title">Daftar Bekal</h5>
        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" style="padding-top: 0px; margin-top: -8px;">
        <div class="table-responsive-sm">
            <table id="tinventory" class="table table-striped dt-responsive nowrap" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" data-priority="100" tcg-column-filter=1>Gudang</th>
                        <th class="text-center" data-priority="100" tcg-column-filter=1 style="max-width: 80px;">No Kartu</th>
                        <th class="text-center" data-priority="1" tcg-column-filter=1 style="min-width: 120px;">Nama Bekal</th>
                        <th class="text-center" data-priority="3" tcg-column-filter=1 style="max-width: 40px;">Tahun</th>
                        <th class="text-center" data-priority="2">Stok</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    </div></div>
    </div>			
</div>
<div id='modal-store' class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document"><div class="modal-content" style="max-width: 700px; margin: auto;">
    <div class="modal-header">
        <h5 class="modal-title" id="store-title">Daftar Bekal</h5>
        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" style="padding: 0px !important; margin-top: -2px;">

        <div class="accordion" id="store-detail">
            <div class="card" style="margin-bottom: 0px;">
                <div class="card-header" id="fastmoving">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#fastmoving-content" aria-expanded="true" aria-controls="fastmoving-content">
                    FAST MOVING
                    </button>
                </h2>
                </div>

                <div id="fastmoving-content" class="collapse show" aria-labelledby="fastmoving" data-parent="#store-detail">
                <div class="card-body" style="padding-top: 0px;">
                    <div class="table-responsive-sm">
                        <table id="tfastmoving" class="table table-striped dt-responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" data-priority="1" tcg-column-filter=1 style="min-width: 120px;">Nama Bekal</th>
                                    <th class="text-center" data-priority="100" tcg-column-filter=1 style="max-width: 80px;">No Kartu</th>
                                    <th class="text-center" data-priority="3" tcg-column-filter=1 style="max-width: 80px;">Tanggal Terima</th>
                                    <th class="text-center" data-priority="2" style="max-width: 40px;">Stok</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                </div>
            </div>
            <div class="card" style="margin-bottom: 0px;">
                <div class="card-header" id="rusakkadaluarsa">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#rusakkadaluarsa-content" aria-expanded="false" aria-controls="rusakkadaluarsa-content">
                    RUSAK / KADALUARSA
                    </button>
                </h2>
                </div>
                <div id="rusakkadaluarsa-content" class="collapse" aria-labelledby="rusakkadaluarsa" data-parent="#store-detail">
                <div class="card-body" style="padding-top: 0px;">
                    <div class="table-responsive-sm">
                        <table id="trusak" class="table table-striped dt-responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" data-priority="1" tcg-column-filter=1 style="min-width: 120px;">Nama Bekal</th>
                                    <th class="text-center" data-priority="100" tcg-column-filter=1 style="max-width: 80px;">No Kartu</th>
                                    <th class="text-center" data-priority="2" tcg-column-filter=1 style="max-width: 60px;">Status</th>
                                    <th class="text-center" data-priority="3" tcg-column-filter=1 style="max-width: 80px;">Tanggal Status</th>
                                    <th class="text-center" data-priority="2" style="max-width: 40px;">Stok</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                </div>
            </div>
            <div class="card" style="margin-bottom: 0px;">
                <div class="card-header" id="lowstock">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#lowstock-content" aria-expanded="false" aria-controls="lowstock-content">
                    HABIS / HAMPIR HABIS
                    </button>
                </h2>
                </div>
                <div id="lowstock-content" class="collapse" aria-labelledby="lowstock" data-parent="#store-detail">
                <div class="card-body" style="padding-top: 0px;">
                    <div class="table-responsive-sm">
                        <table id="tlowstock" class="table table-striped dt-responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" data-priority="1" tcg-column-filter=1 style="min-width: 120px;">Nama Bekal</th>
                                    <th class="text-center" data-priority="100" tcg-column-filter=1 style="max-width: 80px;">Tipe Bekal</th>
                                    <th class="text-center" data-priority="100" tcg-column-filter=1 style="max-width: 40px;">Fast Moving</th>
                                    <th class="text-center" data-priority="2" style="max-width: 40px;">Stok</th>
                                    <th class="text-center" data-priority="3" style="max-width: 40px;">Stok <span class="text-nowrap">Minimal</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <!-- <div class="accordion" id="store-detail" style="border-top-right-radius: 0px; border-top-left-radius: 0px;">
            <div class="accordion-item">
                <h2 class="accordion-header" id="fastmoving">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#fastmoving-content" aria-expanded="true" aria-controls="fastmoving-content"
                style="border-top-right-radius: 0px; border-top-left-radius: 0px;">
                    FAST MOVING
                </button>
                </h2>
                <div id="fastmoving-content" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#store-detail">
                <div class="accordion-body">
                </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="rusakkadaluarsa">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rusakkadaluarsa-content" aria-expanded="false" aria-controls="rusakkadaluarsa-content">
                    RUSAK / KADALUARSA
                </button>
                </h2>
                <div id="rusakkadaluarsa-content" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#store-detail">
                <div class="accordion-body">
                </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="lowstock">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#lowstock-content" aria-expanded="false" aria-controls="lowstock-content">
                    LOW STOCK
                </button>
                </h2>
                <div id="lowstock-content" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#store-detail">
                <div class="accordion-body">
                </div>
                </div>
            </div>
        </div> -->

    </div>
    </div></div>
    </div>			
</div>

<script type="text/javascript">
    var dt_tinventory = null;

    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'throw';
        $.extend($.fn.dataTable.defaults, {
            responsive: true,
        });

        var tinventory_refresh = debounce(function(api) {
            //recalc responsive columns
            api.columns.adjust().responsive.recalc();
        }, 500);

        // Setup - add a text input to each footer cell
        $('#tinventory thead tr').clone(true).addClass('filters').addClass('d-none').appendTo('#tinventory thead');

        //easy access
        api_tinventory = null;

        dt_tinventory = $('#tinventory').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": false,
            "scrollX": false,
            orderCellsTop: true,
            fixedHeader: true,
            "pageLength": 10,
            "lengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100, 200, "All"]],
            "paging": true,
            "pagingType": "numbers",
            dom: "t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
            select: 'single',
            buttons: {
                buttons: [],
            },
            "language": {
                "sProcessing": "Processing",
                "sLengthMenu": "Menampilkan _MENU_ baris",
                "sZeroRecords": "No data",
                "sInfo": "Menampilan _START_ - _END_ dari _TOTAL_ baris",
                "sInfoEmpty": "Menampilan 0 dari 0 baris",
                "sInfoFiltered": "Difilter dari _MAX_ total baris",
                "sInfoPostFix": "",
                "sSearch": "Mencari",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelum",
                    "sNext": "Setelah",
                    "sLast": "Terakhir"
                }
            },
            rowId: 'itemid',
            "ajax": null,
            "columns": [
            {
                data: "storeid_label",
                className: "col_tcg_text",
                orderable: true,
            },
            {
                data: "inventorycode",
                className: "col_tcg_text",
                orderable: true,
            },
            {
                data: "itemid_label",
                className: "col_tcg_text",
                orderable: true,
            },
            {
                data: "year",
                className: "col_tcg_number text-center",
                orderable: true,
            },
            {
                data: "availableamount",
                className: "col_tcg_number text-right",
                orderable: true,
            }, ],
            order: [[3, 'asc'],[2, 'asc']],
            initComplete: function() {
                var api = this.api();

                // For each column
                api.columns().eq(0).each(function(colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('#tinventory .filters th').eq($(api.column(colIdx).header()).index());

                    var title = $(cell).text().trim();
                    var col_filter = cell.attr('tcg-column-filter');
                    if ($(api.column(colIdx).header()).index() >= 0 && col_filter == 1) {
                        $(cell).html('<input type="text" placeholder="' + title + '" style="width: 100%;"/>');
                    } else {
                        $(cell).html('');
                    }

                    // On every keypress in this input
                    $('input', cell).off('keyup change').on('change', function(e) {
                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({ search })';

                        var cursorPosition = this.selectionStart;
                        // Search the column for that value
                        api.column(colIdx).search(this.value != '' ? regexr.replace('{ search }', '(((' + this.value + ')))') : '', this.value != '', this.value == '').draw();
                    }).on('keyup', function(e) {
                        e.stopPropagation();

                        var cursorPosition = this.selectionStart;

                        $(this).trigger('change');
                        $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                    });

                    //show/hide cell based on col's responsive status
                    var col = api.column(colIdx);
                    if (col.responsiveHidden()) {
                        cell.show();
                    } else {
                        cell.hide();
                    }

                });

                //show the filter row
                $('#tinventory thead tr').removeClass("d-none");

                api_tinventory = this.api();
                dt_tinventory_initialized = true;
            },
            "footerCallback": function(row, data, start, end, display) {
                tinventory_refresh(this.api());
             },
        });

        dt_tinventory.on('select.dt deselect.dt', function(e, settings) {
            let that = dt_tinventory;
            let api = new $.fn.dataTable.Api(settings);
            tinventory_refresh(api);
        });

        dt_tinventory.on('responsive-resize', function(e, api, columns) {
            api.columns().eq(0).each(function(colIdx) {
                var cell = $('#tinventory .filters th').eq($(api.column(colIdx).header()).index());

                var col = api.column(colIdx);
                if (col.responsiveHidden()) {
                    cell.show();
                } else {
                    cell.hide();
                }
            });
        });

        dt_tinventory.on('page.dt', function(e, settings) {
            var api = new $.fn.dataTable.Api(settings);
            tinventory_refresh(api);
        });

        dt_tinventory.on('order.dt search.dt', function(e, settings) {
            //refresh responsive table
            var api = new $.fn.dataTable.Api(settings);
            tinventory_refresh(api);
        }).draw();

        // dt_tinventory.on("user-select.dt", function(e, dt, type, cell, originalEvent) {
        //     let that = dt_tinventory;
        //     // let api = new $.fn.dataTable.Api(dt);
        //     tinventory_refresh(dt);
        // });

        var tfastmoving_refresh = debounce(function(api) {
            //recalc responsive columns
            api.columns.adjust().responsive.recalc();
        }, 500);

        // Setup - add a text input to each footer cell
        $('#tfastmoving thead tr').clone(true).addClass('filters').addClass('d-none').appendTo('#tfastmoving thead');

        //easy access
        api_tfastmoving = null;

        dt_tfastmoving = $('#tfastmoving').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": false,
            "scrollX": false,
            orderCellsTop: true,
            fixedHeader: true,
            "pageLength": 5,
            "lengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100, 200, "All"]],
            "paging": true,
            "pagingType": "numbers",
            dom: "t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
            select: 'single',
            buttons: {
                buttons: [],
            },
            "language": {
                "sProcessing": "Processing",
                "sLengthMenu": "Menampilkan _MENU_ baris",
                "sZeroRecords": "No data",
                "sInfo": "Menampilan _START_ - _END_ dari _TOTAL_ baris",
                "sInfoEmpty": "Menampilan 0 dari 0 baris",
                "sInfoFiltered": "Difilter dari _MAX_ total baris",
                "sInfoPostFix": "",
                "sSearch": "Mencari",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelum",
                    "sNext": "Setelah",
                    "sLast": "Terakhir"
                }
            },
            rowId: 'itemid',
            "ajax": null,
            "columns": [
            {
                data: "itemid_label",
                className: "col_tcg_text",
                orderable: true,
            },
            {
                data: "inventorycode",
                className: "col_tcg_text",
                orderable: true,
            },
            {
                data: "receiveddate",
                className: "col_tcg_datetime text-center text-nowrap",
                orderable: true,
            },
            {
                data: "availableamount",
                className: "col_tcg_number text-center",
                orderable: true,
            }, ],
            order: [[2, 'asc'],[1, 'asc']],
            initComplete: function() {
                var api = this.api();

                // For each column
                api.columns().eq(0).each(function(colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('#tfastmoving .filters th').eq($(api.column(colIdx).header()).index());

                    var title = $(cell).text().trim();
                    var col_filter = cell.attr('tcg-column-filter');
                    if ($(api.column(colIdx).header()).index() >= 0 && col_filter == 1) {
                        $(cell).html('<input type="text" placeholder="' + title + '" style="width: 100%;"/>');
                    } else {
                        $(cell).html('');
                    }

                    // On every keypress in this input
                    $('input', cell).off('keyup change').on('change', function(e) {
                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({ search })';

                        var cursorPosition = this.selectionStart;
                        // Search the column for that value
                        api.column(colIdx).search(this.value != '' ? regexr.replace('{ search }', '(((' + this.value + ')))') : '', this.value != '', this.value == '').draw();
                    }).on('keyup', function(e) {
                        e.stopPropagation();

                        var cursorPosition = this.selectionStart;

                        $(this).trigger('change');
                        $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                    });

                    //show/hide cell based on col's responsive status
                    var col = api.column(colIdx);
                    if (col.responsiveHidden()) {
                        cell.show();
                    } else {
                        cell.hide();
                    }

                });

                //show the filter row
                $('#tfastmoving thead tr').removeClass("d-none");

                api_tfastmoving = this.api();
                dt_tfastmoving_initialized = true;
            },
            "footerCallback": function(row, data, start, end, display) {
                tfastmoving_refresh(this.api());
             },
        });

        dt_tfastmoving.on('select.dt deselect.dt', function(e, settings) {
            let that = dt_tinventory;
            let api = new $.fn.dataTable.Api(settings);
            tfastmoving_refresh(api);
        });

        dt_tfastmoving.on('responsive-resize', function(e, api, columns) {
            api.columns().eq(0).each(function(colIdx) {
                var cell = $('#tfastmoving .filters th').eq($(api.column(colIdx).header()).index());

                var col = api.column(colIdx);
                if (col.responsiveHidden()) {
                    cell.show();
                } else {
                    cell.hide();
                }
            });
        });

        dt_tfastmoving.on('page.dt', function(e, settings) {
            var api = new $.fn.dataTable.Api(settings);
            tfastmoving_refresh(api);
        });

        dt_tfastmoving.on('order.dt search.dt', function(e, settings) {
            //refresh responsive table
            var api = new $.fn.dataTable.Api(settings);
            tfastmoving_refresh(api);
        }).draw();

        // dt_tfastmoving.on("user-select.dt", function(e, dt, type, cell, originalEvent) {
        //     let that = dt_tfastmoving;
        //     // let api = new $.fn.dataTable.Api(dt);
        //     tfastmoving_refresh(dt);
        // });      
        
        var trusak_refresh = debounce(function(api) {
            //recalc responsive columns
            api.columns.adjust().responsive.recalc();
        }, 500);

        // Setup - add a text input to each footer cell
        $('#trusak thead tr').clone(true).addClass('filters').addClass('d-none').appendTo('#trusak thead');

        //easy access
        api_trusak = null;

        dt_trusak = $('#trusak').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": false,
            "scrollX": false,
            orderCellsTop: true,
            fixedHeader: true,
            "pageLength": 5,
            "lengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100, 200, "All"]],
            "paging": true,
            "pagingType": "numbers",
            dom: "t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
            select: 'single',
            buttons: {
                buttons: [],
            },
            "language": {
                "sProcessing": "Processing",
                "sLengthMenu": "Menampilkan _MENU_ baris",
                "sZeroRecords": "No data",
                "sInfo": "Menampilan _START_ - _END_ dari _TOTAL_ baris",
                "sInfoEmpty": "Menampilan 0 dari 0 baris",
                "sInfoFiltered": "Difilter dari _MAX_ total baris",
                "sInfoPostFix": "",
                "sSearch": "Mencari",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelum",
                    "sNext": "Setelah",
                    "sLast": "Terakhir"
                }
            },
            rowId: 'itemid',
            "ajax": null,
            "columns": [
            {
                data: "itemid_label",
                className: "col_tcg_text",
                orderable: true,
            },
            {
                data: "inventorycode",
                className: "col_tcg_text dt-col-80",
                orderable: true,
            },
            {
                data: "status_label",
                className: "col_tcg_text text-center dt-col-80",
                orderable: true,
            },
            {
                data: "statusupdatedate",
                className: "col_tcg_datetime text-nowrap text-center dt-col-80",
                orderable: true,
            },
            {
                data: "amount",
                className: "col_tcg_number text-center dt-col-40",
                orderable: true,
            }, ],
            order: [[2, 'asc'],[1, 'asc']],
            initComplete: function() {
                var api = this.api();

                // For each column
                api.columns().eq(0).each(function(colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('#trusak .filters th').eq($(api.column(colIdx).header()).index());

                    var title = $(cell).text().trim();
                    var col_filter = cell.attr('tcg-column-filter');
                    if ($(api.column(colIdx).header()).index() >= 0 && col_filter == 1) {
                        $(cell).html('<input type="text" placeholder="' + title + '" style="width: 100%;"/>');
                    } else {
                        $(cell).html('');
                    }

                    // On every keypress in this input
                    $('input', cell).off('keyup change').on('change', function(e) {
                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({ search })';

                        var cursorPosition = this.selectionStart;
                        // Search the column for that value
                        api.column(colIdx).search(this.value != '' ? regexr.replace('{ search }', '(((' + this.value + ')))') : '', this.value != '', this.value == '').draw();
                    }).on('keyup', function(e) {
                        e.stopPropagation();

                        var cursorPosition = this.selectionStart;

                        $(this).trigger('change');
                        $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                    });

                    //show/hide cell based on col's responsive status
                    var col = api.column(colIdx);
                    if (col.responsiveHidden()) {
                        cell.show();
                    } else {
                        cell.hide();
                    }

                });

                //show the filter row
                $('#trusak thead tr').removeClass("d-none");

                api_trusak = this.api();
                dt_trusak_initialized = true;
            },
            "footerCallback": function(row, data, start, end, display) {
                trusak_refresh(this.api());
             },
        });

        dt_trusak.on('select.dt deselect.dt', function(e, settings) {
            let that = dt_tinventory;
            let api = new $.fn.dataTable.Api(settings);
            trusak_refresh(api);
        });

        dt_trusak.on('responsive-resize', function(e, api, columns) {
            api.columns().eq(0).each(function(colIdx) {
                var cell = $('#trusak .filters th').eq($(api.column(colIdx).header()).index());

                var col = api.column(colIdx);
                if (col.responsiveHidden()) {
                    cell.show();
                } else {
                    cell.hide();
                }
            });
        });

        dt_trusak.on('page.dt', function(e, settings) {
            var api = new $.fn.dataTable.Api(settings);
            trusak_refresh(api);
        });

        dt_trusak.on('order.dt search.dt', function(e, settings) {
            //refresh responsive table
            var api = new $.fn.dataTable.Api(settings);
            trusak_refresh(api);
        }).draw();

        // dt_trusak.on("user-select.dt", function(e, dt, type, cell, originalEvent) {
        //     let that = dt_trusak;
        //     // let api = new $.fn.dataTable.Api(dt);
        //     trusak_refresh(dt);
        // });   

        var tlowstock_refresh = debounce(function(api) {
            //recalc responsive columns
            api.columns.adjust().responsive.recalc();
        }, 500);

        // Setup - add a text input to each footer cell
        $('#tlowstock thead tr').clone(true).addClass('filters').addClass('d-none').appendTo('#tlowstock thead');

        //easy access
        api_tlowstock = null;

        dt_tlowstock = $('#tlowstock').DataTable({
            "processing": true,
            "responsive": true,
            "serverSide": false,
            "scrollX": false,
            orderCellsTop: true,
            fixedHeader: true,
            "pageLength": 5,
            "lengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100, 200, "All"]],
            "paging": true,
            "pagingType": "numbers",
            dom: "t<'row'<'col-sm-12 col-md-8'i><'col-sm-12 col-md-4'p>>",
            select: 'single',
            buttons: {
                buttons: [],
            },
            "language": {
                "sProcessing": "Processing",
                "sLengthMenu": "Menampilkan _MENU_ baris",
                "sZeroRecords": "No data",
                "sInfo": "Menampilan _START_ - _END_ dari _TOTAL_ baris",
                "sInfoEmpty": "Menampilan 0 dari 0 baris",
                "sInfoFiltered": "Difilter dari _MAX_ total baris",
                "sInfoPostFix": "",
                "sSearch": "Mencari",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "Pertama",
                    "sPrevious": "Sebelum",
                    "sNext": "Setelah",
                    "sLast": "Terakhir"
                }
            },
            rowId: 'itemid',
            "ajax": null,
            "columns": [
            {
                data: "itemid_label",
                className: "col_tcg_text",
                orderable: true,
            },
            {
                data: "itemtypeid_label",
                className: "col_tcg_text text-center",
                orderable: true,
            },
            {
                data: "fastmoving",
                className: "col_tcg_text text-center",
                orderable: true,
                render: function ( data, type, row ) {
                    if (typeof data === 'undefined' || data === null || data == "" ) {
                        data = "";
                    }
                    else if (data == '1') {
                        data = '{__("Ya")}';
                    }
                    else {
                        data = '{__("Tdk")}';
                    }
                    return data;
                },
            },
            {
                data: "availableamount",
                className: "col_tcg_number text-center",
                orderable: true,
            }, 
            {
                data: "minstock",
                className: "col_tcg_number text-center",
                orderable: true,
            },
            // {
            //     data: "gapamount",
            //     className: "col_tcg_number text-right",
            //     orderable: true,
            // }, 
            ],
            order: [[1, 'asc']],
            initComplete: function() {
                var api = this.api();

                // For each column
                api.columns().eq(0).each(function(colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('#tlowstock .filters th').eq($(api.column(colIdx).header()).index());

                    var title = $(cell).text().trim();
                    var col_filter = cell.attr('tcg-column-filter');
                    if ($(api.column(colIdx).header()).index() >= 0 && col_filter == 1) {
                        $(cell).html('<input type="text" placeholder="' + title + '" style="width: 100%;"/>');
                    } else {
                        $(cell).html('');
                    }

                    // On every keypress in this input
                    $('input', cell).off('keyup change').on('change', function(e) {
                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({ search })';

                        var cursorPosition = this.selectionStart;
                        // Search the column for that value
                        api.column(colIdx).search(this.value != '' ? regexr.replace('{ search }', '(((' + this.value + ')))') : '', this.value != '', this.value == '').draw();
                    }).on('keyup', function(e) {
                        e.stopPropagation();

                        var cursorPosition = this.selectionStart;

                        $(this).trigger('change');
                        $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                    });

                    //show/hide cell based on col's responsive status
                    var col = api.column(colIdx);
                    if (col.responsiveHidden()) {
                        cell.show();
                    } else {
                        cell.hide();
                    }

                });

                //show the filter row
                $('#tlowstock thead tr').removeClass("d-none");

                api_tlowstock = this.api();
                dt_tlowstock_initialized = true;
            },
            "footerCallback": function(row, data, start, end, display) {
                tlowstock_refresh(this.api());
             },
        });

        dt_tlowstock.on('select.dt deselect.dt', function(e, settings) {
            let that = dt_tinventory;
            let api = new $.fn.dataTable.Api(settings);
            tlowstock_refresh(api);
        });

        dt_tlowstock.on('responsive-resize', function(e, api, columns) {
            api.columns().eq(0).each(function(colIdx) {
                var cell = $('#tlowstock .filters th').eq($(api.column(colIdx).header()).index());

                var col = api.column(colIdx);
                if (col.responsiveHidden()) {
                    cell.show();
                } else {
                    cell.hide();
                }
            });
        });

        dt_tlowstock.on('page.dt', function(e, settings) {
            var api = new $.fn.dataTable.Api(settings);
            tlowstock_refresh(api);
        });

        dt_tlowstock.on('order.dt search.dt', function(e, settings) {
            //refresh responsive table
            var api = new $.fn.dataTable.Api(settings);
            tlowstock_refresh(api);
        }).draw();

        // dt_tlowstock.on("user-select.dt", function(e, dt, type, cell, originalEvent) {
        //     let that = dt_tlowstock;
        //     // let api = new $.fn.dataTable.Api(dt);
        //     tlowstock_refresh(dt);
        // });   

        $("#store-detail .card-header button").on("click", function(e) {
            // api_tfastmoving.columns.adjust().responsive.recalc();
            // api_tlowstock.columns.adjust().responsive.recalc();
            // api_trusak.columns.adjust().responsive.recalc();
            tfastmoving_refresh(api_tfastmoving);
            tlowstock_refresh(api_tlowstock);
            trusak_refresh(api_trusak);
        })
    })

    function detail_search(age, storeids) {

        dt_tinventory.clear().draw();

        //get the data from ajax
        let str = $("#search").val().trim();

        //retrieve list from json
        let url = "{$site_url}disbekal/dashboard/gisdetail"
                        + "?f_siteid=" +(v_siteid==null?"":v_siteid)
                        + "&f_itemtypeid=" +(v_itemtypeid==null?"":v_itemtypeid)
                        + "&f_age=" +(age==null?"":age)
                        + "&f_storeids=" +(storeids==null?"":storeids)
                        + "&q=" +(str==null?"":str);

        dt_tinventory.ajax.url(url);
        dt_tinventory.ajax.reload(null, true);

        $('#modal-bekal').modal({
            backdrop: "static",
            focus: true,
            show: true,
        })

        // const modal = new bootstrap.Modal('#modal-bekal', {
        //     backdrop: "static",
        //     focus: true,
        // });
        // modal.show();
    }

    
    function switch_store(storeid, storename) {
        $("#modal-store #store-title").html(storename);

        let url = "{$site_url}disbekal/dashboard/fastmoving"
                        + "?f_storeid=" +(storeid==null?"":storeid);

        dt_tfastmoving.ajax.url(url);
        dt_tfastmoving.ajax.reload(function(json) {
            if (json.error !== undefined && json.error != null) {
                //error. send some toastr
                return;
            }

            let totalItem = 0;
            if (json.data !== undefined && json.data != null) {
                for(i=0; i<json.data.length; i++) {
                    totalItem += parseInt(json.data[i]['availableamount']);
                }
            }

            //update value
            $("#store-detail #fastmoving button").html("FAST MOVING (" +accounting.formatNumber(totalItem,0,'.')+ " BEKAL)");
        }, false);

        url = "{$site_url}disbekal/dashboard/rusakkadaluarsa"
                        + "?f_storeid=" +(storeid==null?"":storeid);

        dt_trusak.ajax.url(url);
        dt_trusak.ajax.reload(function(json) {
            if (json.error !== undefined && json.error != null) {
                //error. send some toastr
                return;
            }

            let totalItem = 0;
            if (json.data !== undefined && json.data != null) {
                for(i=0; i<json.data.length; i++) {
                    totalItem += json.data[i]['amount'];
                }
            }

            //update value
            $("#store-detail #rusakkadaluarsa button").html("RUSAK / KADALUARSA (" +accounting.formatNumber(totalItem,0,'.')+ " BEKAL)");
        }, true);

        url = "{$site_url}disbekal/dashboard/lowstock"
                        + "?f_storeid=" +(storeid==null?"":storeid);

        dt_tlowstock.ajax.url(url);
        dt_tlowstock.ajax.reload(function(json){
            if (json.error !== undefined && json.error != null) {
                //error. send some toastr
                return;
            }

            let totalItem = json.data.length;

            //update value
            $("#store-detail #lowstock button").html("HABIS / HAMPIR HABIS (" +accounting.formatNumber(totalItem,0,'.')+ " BEKAL)");

        }, true);

        $('#modal-store').modal({
            backdrop: "static",
            focus: true,
            show: true,
        })

        let el = null;

        el = $('.accordion .card-header:has(+ .collapse.show)');

        // const modal = new bootstrap.Modal('#modal-store', {
        //     backdrop: "static",
        //     focus: true,
        // });
        // modal.show();
    }

    function throttle(func, wait, options) {
        var timeout, context, args, result;
        var previous = 0;
        if (!options) options = {};

        var later = function() {
            previous = options.leading === false ? 0 : now();
            timeout = null;
            result = func.apply(context, args);
            if (!timeout) context = args = null;
        };

        var throttled = function() {
            var _now = now();
            if (!previous && options.leading === false) previous = _now;
            var remaining = wait - (_now - previous);
            context = this;
            args = arguments;
            if (remaining <= 0 || remaining > wait) {
            if (timeout) {
                clearTimeout(timeout);
                timeout = null;
            }
            previous = _now;
            result = func.apply(context, args);
            if (!timeout) context = args = null;
            } else if (!timeout && options.trailing !== false) {
            timeout = setTimeout(later, remaining);
            }
            return result;
        };

        throttled.cancel = function() {
            clearTimeout(timeout);
            previous = 0;
            timeout = context = args = null;
        };

        return throttled;
    }

    function restArguments(func, startIndex) {
        startIndex = startIndex == null ? func.length - 1 : +startIndex;

        return function() {
            var length = Math.max(arguments.length - startIndex, 0),
                rest = Array(length),
                index = 0;
            for (; index < length; index++) {
                rest[index] = arguments[index + startIndex];
            }
            switch (startIndex) {
                case 0: return func.call(this, rest);
                case 1: return func.call(this, arguments[0], rest);
                case 2: return func.call(this, arguments[0], arguments[1], rest);
            }
            var args = Array(startIndex + 1);
                for (index = 0; index < startIndex; index++) {
                args[index] = arguments[index];
            }
            args[startIndex] = rest;
            return func.apply(this, args);
        };
    };

    function now() {
        return new Date().getTime();
    };

    function debounce(func, wait, immediate) {
        var timeout, previous, args, result, context;

        var later = function() {
            var passed = now() - previous;
            if (wait > passed) {
                // new call while the existing call is executing -> schedule for latter
                timeout = setTimeout(later, wait - passed);
            } else {
                timeout = null;
                if (!immediate) result = func.apply(context, args);
                if (!timeout) args = context = null;
            }
        };

        var debounced = restArguments(function(_args) {
            context = this;
            args = _args;
            previous = now();
            if (!timeout) {
                timeout = setTimeout(later, wait);
                if (immediate) result = func.apply(context, args);
            }
            return result;
        });

        debounced.cancel = function() {
            clearTimeout(timeout);
            timeout = args = context = null;
        };

        return debounced;
    }
</script>