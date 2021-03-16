/*! RowGroup 1.1.1
 * ©2017-2019 SpryMedia Ltd - datatables.net/license
 */

/**
 * @summary     RowGroup
 * @description RowGrouping for DataTables
 * @version     1.1.1
 * @file        dataTables.rowGroup.js
 * @author      SpryMedia Ltd (www.sprymedia.co.uk)
 * @contact     datatables.net
 * @copyright   Copyright 2017-2019 SpryMedia Ltd.
 *
 * This source file is free software, available under the following license:
 *   MIT license - http://datatables.net/license/mit
 *
 * This source file is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.
 *
 * For details please refer to: http://www.datatables.net
 */

(function( factory ){
	if ( typeof define === 'function' && define.amd ) {
		// AMD
		define( ['jquery', 'datatables.net'], function ( $ ) {
			return factory( $, window, document );
		} );
	}
	else if ( typeof exports === 'object' ) {
		// CommonJS
		module.exports = function (root, $) {
			if ( ! root ) {
				root = window;
			}

			if ( ! $ || ! $.fn.dataTable ) {
				$ = require('datatables.net')(root, $).$;
			}

			return factory( $, root, root.document );
		};
	}
	else {
		// Browser
		factory( jQuery, window, document );
	}
}(function( $, window, document, undefined ) {
'use strict';
var DataTable = $.fn.dataTable;


var RowGroup = function ( dt, opts ) {
	// Sanity check that we are using DataTables 1.10 or newer
	if ( ! DataTable.versionCheck || ! DataTable.versionCheck( '1.10.8' ) ) {
		throw 'RowGroup requires DataTables 1.10.8 or newer';
	}

	// User and defaults configuration object
	this.c = $.extend( true, {},
		DataTable.defaults.rowGroup,
		RowGroup.defaults,
		opts
	);

	// Internal settings
	this.s = {
		dt: new DataTable.Api( dt ),
		groups: [],
		filter: '',
		filteredRows: null,
		collapsedRows: [],
		isDrawing: false
	};

	//internationalization
	let strSearch = this.s.dt.i18n('search', 'Search');

	let settings = this.s.dt.settings()[0];
	let instance = settings.sInstance;
	
	// DOM items
	this.dom = {
		filterContainer: $('<div/>')
			.addClass( "dataTables_filter" ).addClass( "dt-rowgroup-filter" ).attr("id", instance+"_filter")
			.append( $( '<label>' )
				.html( strSearch )
				.append( $( '<input>' )
					.attr('type', 'search')
					.attr("id", instance+"_filter_input")
					) 
				)
	};

	// Check if row grouping has already been initialised on this table
	var existing = settings.rowGroup;
	if ( existing ) {
		return existing;
	}

	settings.rowGroup = this;
	this._constructor();
};


$.extend( RowGroup.prototype, {
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * API methods for DataTables API interface
	 */

	/**
	 * Get/set the grouping data source - need to call draw after this is
	 * executed as a setter
	 * @returns string~RowGroup
	 */
	dataSrc: function ( val )
	{
		if ( val === undefined ) {
			return this.c.dataSrc;
		}

		var dt = this.s.dt;

		this.c.dataSrc = val;

		$(dt.table().node()).triggerHandler( 'rowgroup-datasrc.dt', [ dt, val ] );

		return this;
	},

	/**
	 * Disable - need to call draw after this is executed
	 * @returns RowGroup
	 */
	disable: function ()
	{
		this.c.enable = false;
		return this;
	},

	/**
	 * Enable - need to call draw after this is executed
	 * @returns RowGroup
	 */
	enable: function ( flag )
	{
		if ( flag === false ) {
			return this.disable();
		}

		this.c.enable = true;
		return this;
	},

	/**
	 * Get the container node for the filter text
	 * @return {jQuery} filter input node
	 */
	filterContainer: function ()
	{
		return this.dom.filterContainer;
	},


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Constructor
	 */
	_constructor: function ()
	{
		var that = this;
		var dt = this.s.dt;
		var dtPrivateSettings = dt.settings()[0];

		this.s.groups = [];
		this.s.filter = null;
		
		this._updateFilterAndCollapsibleThrottled = this._debounce(this._updateFilterAndCollapsible, 500);

		dt.on( 'column-visibility.dt.dtrg responsive-resize.dt.dtrg', function () {
			that._adjustColspan();
		} );

		dt.on( 'destroy', function () {
			dt.off( '.dtrg' );
		} );

		dt.on('responsive-resize.dt', function () {
			that._adjustColspan();
		})
		
		// Click handler to show / hide the details rows when they are available
		var body = dt.table().body();
		$( dt.table().body() )
			.on( 'click.dtr', this.c.selector, function (e) {

			// if ( that.c.enable ) {
				// that._draw();
			// }
			
			var tr = $(this).closest('tr');
			var row = dt.row( tr );

			if (!tr.hasClass("dtrg-expandable")) {
				return;
			}

			// if (!$(this).hasClass("dtrg-expandable")) {
				// return;
			// }
			
			var groupName = tr.attr("dtrg-group-name");
			var group = that._getGroup(that.s.groups, groupName);
			if (group != null) {
				if ($(this).hasClass("dtrg-expanded")) {
					group.collapsed = 1;
					$(this).removeClass("dtrg-expanded");
				}
				else {
					group.collapsed = 0;
					$(this).addClass("dtrg-expanded");
				}
				
				//apply filters
				var collapsible = that._collapsedGroups(that.s.groups);
				that.s.collapsedRows = collapsible;		
				
				that.s.isDrawing = true;
				dt.draw();

			}
			
			//console.log("Group clicked: " + groupName);
		});


		// DataTables doesn't currently trigger an event when a row is added, so
		// we need to hook into its private API to enforce the hidden rows when
		// new data is added
		dtPrivateSettings.oApi._fnCallbackReg( dtPrivateSettings, 'aoRowCreatedCallback', function (tr, data, idx) {
			if (typeof dtPrivateSettings.rowgroup === "undefined" || dtPrivateSettings.rowgroup == null) {
				//if init not completed, ignore
				return;
			}
			
			that._insertRow(tr, data, idx, that.s.groups, 0);
			
			//apply filters
			that._updateFilterAndCollapsibleThrottled();
		});
				
		// Datatable initialization
		dt.on( 'init.dt', function () {
			var dt = that.s.dt;
			
			//var rows = dt.rows( { page: 'current' } );
			var rows = dt.rows();
			var groups = that._group( 0, rows.indexes() );
			
			for ( var i=0, ien=groups.length ; i<ien ; i++ ) {
				that._displayHeader(groups[i]);
			}
			
			that.s.groups = groups;
			
			//apply filters
			$.fn.dataTable.ext.search = [];

			var filters = that._filter(true);
			that.s.filteredRows = filters;
			$.fn.dataTable.ext.search.push(
				function(settings, data, dataIndex, row) {
					//return that.s.filteredRows == null || that.s.filteredRows.includes(dataIndex);
					if (typeof settings.rowGroup === 'undefined' || settings.rowGroup == null) {
						return true;
					}
					return settings.rowGroup.s.filteredRows == null || settings.rowGroup.s.filteredRows.includes(row[settings.rowId]);
				}
			);

			var collapsible = that._collapsedGroups(groups);
			that.s.collapsedRows = collapsible;			
			$.fn.dataTable.ext.search.push(
				function(settings, data, dataIndex, row) {
					//return !that.s.collapsedRows.includes(dataIndex);
					if (typeof settings.rowGroup === 'undefined' || settings.rowGroup == null) {
						return true;
					}
					return !settings.rowGroup.s.collapsedRows.includes(row[settings.rowId]);
				}
			);
				
			that.s.isDrawing = true;
			dt.draw();

			console.log( 'Table initialisation complete: '+new Date().getTime() );
			dtPrivateSettings.rowgroup = that;
		} )
	
		// Redraw the details box on each draw which will happen if the data
		// has changed. This is used until DataTables implements a native
		// `updated` event for rows
		dt.on( 'draw.dtr', function () {
			if (that.s.isDrawing) {
				//is drawing triggered manually
				that.s.isDrawing = false;
				return;
			}
			
			var dt = that.s.dt;
			var rows = dt.rows( { page: 'current' } );
			//var rows = dt.rows();
			var groups = that._group( 0, rows.indexes() );
			
			//merge with global group list
			var group = null;
			for (var i=0; i<groups.length; i++) {
				group = that._getGroup(that.s.groups, groups[i].dataPoint);
				if (group == null) {
					that.s.groups.push(groups[i]);
				}
				else {
					that._mergeGroup(groups[i], group);
				}
			}
			
			for ( var i=0, ien=groups.length ; i<ien ; i++ ) {
				that._displayHeader(groups[i]);
			}
			
			//apply filters
			that._updateFilterAndCollapsibleThrottled();

		} );
					
		//Keyup when filter input changed
		let settings = this.s.dt.settings()[0];
		let filterSelector = "#" + settings.sInstance + "_filter_input";

		$(document).on("keyup", filterSelector, that._debounce(function() {
			
			let filters = that._filter(false);			
			if (typeof filters !== 'undefined') {			
				that.s.filteredRows = filters;
				
				that.s.isDrawing = true;
				dt.draw();
			}
			
		}, 500));

	},

	_debounce: function(func, wait, immediate) {
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
	},

	_mergeGroup: function(target, source) {
		//carry the settings
		target.headerRow = source.headerRow;
		target.footerRow = source.footerRow;
		target.collapsed = source.collapsed;		
		
		target.rows = source.rows;
		target.children = source.children;

		// //merge the contents -> cheaper to merge the other way around since most of the times the target is empty array
		// this._appendUnique(source.rows, target.rows);
		// target.rows = source.rows;
		
		
		// if (typeof target.children !== "undefined" && target.children != null && Array.isArray(target.children)) {
			// var group = null;
			// for (var i=0; i<target.children.length; i++) {
				// group = this._getGroup(source.children, target.children[i].dataPoint);
				// if (group == null) {
					// source.children.push(target.children[i]);
				// }
				// else {
					// this._mergeGroup(target.children[i], group);
				// }
			// }
		// }
	},
	
	_updateFilterAndCollapsible: function() {
			
		//apply filters
		let filters = this._filter(false);
		if (typeof filters !== 'undefined') {			
			this.s.filteredRows = filters;
		}

		var collapsible = this._collapsedGroups(this.s.groups);
		this.s.collapsedRows = collapsible;			

		this.s.isDrawing = true;
		this.s.dt.draw();
	
	},
	
	_insertRow: function ( tr, data, idx, groups, level ) {
		let fns = $.isArray( this.c.dataSrc ) ? this.c.dataSrc : [ this.c.dataSrc ];
		let fn = DataTable.ext.oApi._fnGetObjectDataFn( fns[ level ] );

		let groupName = fn(data);
		let row = this.s.dt.row(idx);
		let rowId = row.id();

		let group = this._getGroupById(groups, rowId);
		if (group != null) {
			//already exist in map
			return;
		}

		group = this._getGroup(groups, groupName);
		if (group == null) {
			group = {
						dataPoint: groupName,
						level: level,
						dataSrc: fns[ level ],
						collapsed: 0,
						rows: [],
						children: []
					};
						
			var headerRowId = null;	
			if ( this.c.headerRow ) {
				var headerRow = this.c.headerRow.call(level, groupName);
				if (headerRow != null) {
					headerRowId = headerRow.id();
				}
			}
			
			var footerRowId = null;
			if ( this.c.footerRow ) {
				var footerRow = this.c.footerRow.call(level, groupName);
				if (footerRow != null) {
					footerRowId = footerRow.id();
				}
			}
			
			group.headerRow = headerRowId;
			group.footerRow = footerRowId;

			if (this.s.defaultCollapsedAll) {
				group.collapsed = 1;
			}
					
			groups.push(group);
		}

		if (group.headerRow == null) {
			group.headerRow = rowId;
		} 
		
		if (rowId != group.headerRow && rowId != group.footerRow && group.rows.indexOf(rowId) == -1) {
			group.rows.push( rowId );
			if ( fns[ level+1 ] !== undefined ) {
				this._insertRow(tr, data, idx, group.children, level+1);
			}
		}

		// let cell = $(this.c.selector, tr);
		// cell.addClass( 'dtrg-selector-level-' + (level) );

	},

	_getGroupById: function(groups, rowId) {
		let group = null;
		for ( let i=0; i<groups.length; i++ ) {
			
			if (!groups[i].rows.includes(rowId)) 
				continue;
			
			//search in children, if any
			if (groups[i].children && groups[i].children.length > 0) {
				group = this._getGroupById(groups[i].children, rowId);
				if (group != null) {
					return group;
				}
			}
			
			return groups[i];
		}
		
		return null;
	},

	_filter: function (forced) {
		var that = this;

		let settings = this.s.dt.settings()[0];
		let filterSelector = "#" + settings.sInstance + "_filter_input";
		let filter = $(filterSelector).val();
		if (typeof filter === "undefined") {
			filter = null;
		}
		else if (filter != null) {
			filter = filter.trim(); 
		}
		
		if (this.s.filter == filter && forced != true) {
			return;
		}
		
		this.s.filter = filter;
		//console.log("Filter: " + filter);
		
		if (filter == null || filter == "") {
			return null;
		}
		
		//console.log(this.s);
		var cols = $.isArray( this.c.search ) ? this.c.search : [ this.c.search ];
		if (cols.length == 0) {
			cols = this.s.dt.columns()[0];
		}
		
		var filteredRows = [];
		var row = null;
		var rowIndex = -1;
		var rowData = [];
		var filteredData = [];
		var rows = [];
		var subFilteredRows = [];
		
		var filteredData = this.s.dt
			.columns( cols )
			.rows()
			.data().filter( function ( obj, index ) {				
				for (var i = 0, keys = Object.keys(obj), ii = keys.length; i < ii; i++) {
					let value = obj[keys[i]];
					if (typeof value === 'undefined' || value == null) continue;
					
					let status = value.toString().toLowerCase().includes(filter.toLowerCase());
					if (status) {
						//console.log('key : ' + keys[i] + ' val : ' + value + "; index: " + index);
						rows.push(index);
						return true;
					}
				}
				
				return false;
			} );
	
		for ( var i=0, ien=rows.length ; i<ien ; i++ ) {
			rowIndex = rows[i];
			row = this.s.dt.row(rowIndex);
			
			rowData = row.data();
						
			subFilteredRows = this._filteredRow(this.s.groups, row, 0);
			filteredRows = this._appendUnique(filteredRows, subFilteredRows);
		}

		return filteredRows;
	},
	
	_appendUnique: function (dest, arr) {
		if (typeof dest === 'undefined' || dest == null) {
			dest = [];
		}
	
		if (typeof arr === 'undefined' || arr == null || !Array.isArray(arr) || arr.length <= 0) {
			return dest;
		}
		
		var val;
		for(var i=0, j=arr.length; i<j; i++) {
			val = arr[i];
			if (dest.indexOf(val) == -1) {
				dest.push(val);
			}
		}
		
		return dest;
	},
	
	_filteredRow: function(groups, row, level) {
		let fns = $.isArray( this.c.dataSrc ) ? this.c.dataSrc : [ this.c.dataSrc ];
		let fn = DataTable.ext.oApi._fnGetObjectDataFn( fns[ level ] );
		
		let rowIndex = row.index();;
		let rowId = row.id();;
		let rowData = row.data();
		let groupName = fn( rowData );
		
		let filters = [];
		let group = this._getGroup(groups, groupName);
		
		if (group.headerRow != null) {
			filters.push(group.headerRow);
		}
		
		if (group.footerRow != null) {
			filters.push(group.footerRow);
		}
		
		//filters.push(rowIndex);
		filters.push(rowId);
		
		if (typeof group.children !== 'undefined' && group.children != null && group.children.length > 0) {
			let subfilters = this._filteredRow(group.children, row, level+1);
			filters = this._appendUnique(filters, subfilters);
		}
		
		return filters;
	},
	
	_displayHeader: function ( group ) {
		let dt = this.s.dt;
		let display;
		let groupName = group.dataPoint;
		let level = group.level;
	
		let row = null;
		let node = null;
		let cell = null;
		
		if (group.headerRow != null) {
			row = dt.row('#'+group.headerRow);
			node = $(row.node());
			node.addClass( this.c.className )
				.addClass( this.c.startClassName )
				.addClass( 'dtrg-level-'+level )
				.attr('dtrg-auto-header', false)
				.attr('dtrg-group-name', groupName);
			
			cell = $(this.c.selector, row.node());
			cell.addClass( 'dtrg-selector' );
			cell.addClass( 'dtrg-selector-level-' + group.level );
			if (group.rows.length == 0) {
				node.addClass( 'dtrg-not-expandable' )
				//cell.addClass( 'dtrg-not-expandable' )
			}
			else {
				node.addClass( 'dtrg-expandable' );
				//cell.addClass( 'dtrg-expandable' );
				if (group.collapsed == 0) {
					cell.addClass( 'dtrg-expanded' )
				}
			}
		}
		else {
			node = null;
			if ( this.c.startRender ) {
				display = this.c.startRender.call( this, dt.rows(rows), groupName, level );
				node = this._rowWrap( display, this.c.startClassName, level );
			}
			else {
				node = this._rowWrap( groupName, this.c.startClassName, level );
			}

			if ( node ) {
				node.insertBefore( dt.row( rows[0] ).node() );
				node.attr('dtrg-group-name', groupName)
				cell = $(this.c.selector, node);
				cell.addClass( 'dtrg-selector' );
				cell.addClass( 'dtrg-selector-level-' + group.level );
				if (group.rows.length == 0) {
					node.addClass( 'dtrg-not-expandable' )
					//cell.addClass( 'dtrg-not-expandable' )
				}
				else {
					node.addClass( 'dtrg-expandable' );
					//cell.addClass( 'dtrg-expandable' );
					if (group.collapsed == 0) {
						cell.addClass( 'dtrg-expanded' )
					}
				}
			}
		}
		
		if (group.footerRow != null) {
			row = dt.row('#'+group.footerRow);
			$(row.node())
				.addClass( this.c.className )
				.addClass( this.c.endClassName )
				.addClass( 'dtrg-level-'+level );
		}
		
		if ( group.children && group.children.length>0 ) {
			let ien=group.children.length;
			for ( let i=0; i<ien ; i++ ) {
				let child = group.children[i];
				if (typeof child === 'undefined' || child == null) continue;
				this._displayHeader(child);
			}
		}
	
	},

	_collapsedGroups: function(groups) {
		let dt = this.s.dt;
		let filters = [];
		let that = this;
		
		groups.forEach(function(item, idx) {
			if (item.collapsed == 1) {
				filters = filters.concat(item.rows);
			}	
			
			if (typeof item.children !== 'undefined' && item.children != null && item.children.length > 0) {
				let subfilters = that._collapsedGroups(item.children);
				filters = filters.concat(subfilters);
			}
		});
		
		return filters;
	},
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Private methods
	 */

	/**
	 * Adjust column span when column visibility changes
	 * @private
	 */
	_adjustColspan: function ()
	{
		var rows = $( 'tr.'+this.c.className, this.s.dt.table().body() );
		var that = this;
		rows.each( function (i) {
			var autoheader = $(this).attr('dtrg-auto-header');
			if (typeof autoheader !== "undefined" && autoheader === 'true') {
				$(this).find('td').attr( 'colspan', that._colspan() );
			}
		});
		
		// if (this.c.adjustColSpan) {
			// $( 'tr.'+this.c.className, this.s.dt.table().body() ).find('td')
				// .attr( 'colspan', this._colspan() );
		// }
	},

	/**
	 * Get the number of columns that a grouping row should span
	 * @private
	 */
	_colspan: function ()
	{
		return this.s.dt.columns().visible().reduce( function (a, b) {
			return a + b;
		}, 0 );
	},

	/**
	 * Get the grouping information from a data set (index) of rows
	 * @param {number} level Nesting level
	 * @param {DataTables.Api} rows API of the rows to consider for this group
	 * @returns {object[]} Nested grouping information - it is structured like this:
	 *	{
	 *		dataPoint: 'Edinburgh',
	 *		headerRow: 0,
	 *		footerRow: -1,
	 *		level: 0,
	 *		dataSrc: '',
	 *		collapsed: 0,
	 *		rows: [ 1,2,3,4,5,6,7 ],
	 *		children: [ {
	 *			dataPoint: 'developer'
	 *			rows: [ 1, 2, 3 ]
	 *		},
	 *		{
	 *			dataPoint: 'support',
	 *			rows: [ 4, 5, 6, 7 ]
	 *		} ]
	 *	}
	 * @private
	 */
	_group: function ( level, rows ) {
		let fns = $.isArray( this.c.dataSrc ) ? this.c.dataSrc : [ this.c.dataSrc ];
		let fn = DataTable.ext.oApi._fnGetObjectDataFn( fns[ level ] );
		let dt = this.s.dt;
		let groupName, last;
		let data = [];
		let that = this;
		let group = null;

		let ien=rows.length;
		for ( let i=0; i<ien ; i++ ) {
			let rowIndex = rows[i];
			let row = dt.row( rowIndex );
			let rowData = row.data();
			let groupName = fn( rowData );
			let rowId = row.id();

			if ( groupName === null || groupName === undefined ) {
				groupName = that.c.emptyDataGroup;
			}
			
			if ( last === undefined || groupName !== last ) {
				group = this._getGroup(data, groupName);
				
				if (group == null) {
					group = {
									dataPoint: groupName,
									level: level,
									dataSrc: fns[ level ],
									collapsed: 0,
									rows: [],
									children: []
								};
								
					var rowIndexHeader = null;	
					if ( this.c.headerRow ) {
						var headerRow = this.c.headerRow.call(level, groupName);
						if (headerRow != null) {
							//rowIndexHeader = headerRow.index();
							rowIndexHeader = headerRow.id();
						}
					}
					
					var rowIndexFooter = null;
					if ( this.c.footerRow ) {
						var footerRow = this.c.footerRow.call(level, groupName);
						if (footerRow != null) {
							//rowIndexFooter = footerRow.index();
							rowIndexFooter = footerRow.id();
						}
					}
					
					group.headerRow = rowIndexHeader;
					group.footerRow = rowIndexFooter;

					if (this.c.defaultCollapsedAll) {
						group.collapsed = 1;
					}
					
					data.push(group);
				}
		
				last = groupName;
			}

			if (group.headerRow == null) {
				//group.headerRow = rowIndex;
				group.headerRow = rowId;
			} 
			
			// if (rowIndex != group.headerRow && rowIndex != group.footerRow) {
				// group.rows.push( rowIndex );
			// }
		
			if (rowId != group.headerRow && rowId != group.footerRow && group.rows.indexOf(rowId) == -1) {
				group.rows.push( rowId );
			}

			let cell = $(this.c.selector, row.node());
			cell.addClass( 'dtrg-selector' );
			cell.addClass( 'dtrg-selector-level-' + (level) );
		}

		let settings = dt.settings()[0];
		if ( fns[ level+1 ] !== undefined && data.length>0) {
			let ien=data.length;
			for ( let i=0; i<ien ; i++ ) {
				if (data[i].rows.length == 0) continue;
				data[i].children = this._group( level+1, that._getRowIndexes(dt,data[i].rows) );
			}
		}

		// if (level==0) {
			// console.log(data);
		// }
		
		return data;
	},

	_getRowIndexes: function (dt, rowIds) {
		let selector = [];
		for(let i=0, len=rowIds.length; i<len; i++) {
			selector.push('#'+rowIds[i]);
		}	
		
		let rows = dt.rows(selector);
		return rows.indexes();
	},
	
	_getGroup: function(groups, groupName) {
		let group = null;
		for ( let i=0; i<groups.length; i++ ) {
			if (groupName == groups[i].dataPoint) {
				return groups[i];
			}
			//search in children, if any
			if (groups[i].children && groups[i].children.length > 0) {
				group = this._getGroup(groups[i].children, groupName);
				if (group != null) {
					return group;
				}
			}
		}
		
		return null;
	},
	
	/**
	 * Take a rendered value from an end user and make it suitable for display
	 * as a row, by wrapping it in a row, or detecting that it is a row.
	 * @param {node|jQuery|string} display Display value
	 * @param {string} className Class to add to the row
	 * @param {array} group
	 * @param {number} group level
	 * @private
	 */
	_rowWrap: function ( display, className, level )
	{
		var row;
		
		if ( display === null || display === '' ) {
			display = this.c.emptyDataGroup;
		}

		if ( display === undefined || display === null ) {
			return null;
		}
		
		if ( typeof display === 'object' && display.nodeName && display.nodeName.toLowerCase() === 'tr') {
			row = $(display).attr('dtrg-auto-header', false);
		}
		else if (display instanceof $ && display.length && display[0].nodeName.toLowerCase() === 'tr') {
			row = display.attr('dtrg-auto-header', false);
		}
		else {
			row = $('<tr/>')
				.append(
					$('<td/>')
						.attr( 'colspan', this._colspan() )
						.append( display  )
				)
				.attr('dtrg-auto-header', true);
		}

		return row
			.addClass( this.c.className )
			.addClass( className )
			.addClass( 'dtrg-level-'+level );
	}
} );


/**
 * RowGroup default settings for initialisation
 *
 * @namespace
 * @name RowGroup.defaults
 * @static
 */
RowGroup.defaults = {
	/**
	 * Class to apply to grouping rows - applied to both the start and
	 * end grouping rows.
	 * @type string
	 */
	className: 'dtrg-group',

	/**
	 * Data property from which to read the grouping information
	 * @type string|integer|array
	 */
	dataSrc: 0,

	/**
	 * Text to show if no data is found for a group
	 * @type string
	 */
	emptyDataGroup: 'No group',

	/**
	 * Initial enablement state
	 * @boolean
	 */
	enable: true,

	/**
	 * Class name to give to the end grouping row
	 * @type string
	 */
	endClassName: 'dtrg-end',

	/**
	 * End grouping label function
	 * @function
	 */
	endRender: null,

	/**
	 * Class name to give to the start grouping row
	 * @type string
	 */
	startClassName: 'dtrg-start',

	/**
	 * Start grouping label function
	 * @function
	 */
	startRender: function ( rows, group ) {
		return group;
	},
		
	/**
	 * Colapsable selector. This defines the element that when clicked will expand/colapse the group.
	 *
	 * @type {String}
	 */
	selector: 'td:nth-child(2)',
	
	/**
	 * Header Row
	 */
	headerRow: function(level, groupName) {
		return null;
	},
	
	/**
	 * Footer Row
	 */
	footerRow: null,
	
	/**
	 * Whether to collapse all on init
	 */
	defaultCollapsedAll: true,
	
	search: []
};


RowGroup.version = "1.1.1";


$.fn.dataTable.RowGroup = RowGroup;
$.fn.DataTable.RowGroup = RowGroup;


DataTable.Api.register( 'rowGroup()', function () {
	return this;
} );

DataTable.Api.register( 'rowGroup().disable()', function () {
	return this.iterator( 'table', function (ctx) {
		if ( ctx.rowGroup ) {
			ctx.rowGroup.enable( false );
		}
	} );
} );

DataTable.Api.register( 'rowGroup().enable()', function ( opts ) {
	return this.iterator( 'table', function (ctx) {
		if ( ctx.rowGroup ) {
			ctx.rowGroup.enable( opts === undefined ? true : opts );
		}
	} );
} );

DataTable.Api.register( 'rowGroup().dataSrc()', function ( val ) {
	if ( val === undefined ) {
		return this.context[0].rowGroup.dataSrc();
	}

	return this.iterator( 'table', function (ctx) {
		if ( ctx.rowGroup ) {
			ctx.rowGroup.dataSrc( val );
		}
	} );
} );


// Attach a listener to the document which listens for DataTables initialisation
// events so we can automatically initialise
$(document).on( 'preInit.dt.dtrg', function (e, settings, json) {
	if ( e.namespace !== 'dt' ) {
		return;
	}

	var init = settings.oInit.rowGroup;
	var defaults = DataTable.defaults.rowGroup;

	if ( init || defaults ) {
		var opts = $.extend( {}, defaults, init );

		if ( init !== false ) {
			new RowGroup( settings, opts  );
		}
	}
} );

function _initFilter ( settings ) {
	var api = new DataTable.Api( settings );
	var opts = api.init().rowGroup || DataTable.defaults.rowGroup;

	return new RowGroup( api, opts ).filterContainer();
}

// DataTables `dom` feature option
DataTable.ext.feature.push( {
	fnInit: _initFilter,
	cFeature: "F"
} );

// DataTables 2 layout feature
if ( DataTable.ext.features ) {
	DataTable.ext.features.register( 'rowGroup', _initFilter );
}

return RowGroup;

}));
