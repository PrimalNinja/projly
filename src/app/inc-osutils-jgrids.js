/*jsl:option explicit*/
/*jsl:import inc-osutils.js*/

// ====================================================================================
// AWAFOS Utils v20241106 =============================================================
// copyright(C) 2012-2024 Mitsukibo, Julian Cassin & Francis Weston. All rights reserved.

// note: rownum is a special field id for row numbers
//objGridOptions_a has the following available: autoHeight, containerClass, editable, enableAddRow, extraParameters, fixedFilter, gridName, multiSelect, rowHeight, showHeaderRow, columns, returnFields, cbOnSelection, cbOnAddNew, cbOnCalculate, cbOnFetchData
function jGrid(objOS_a, objOptions_a)
{
	var os = objOS_a;
	var m_objThis = this;

	// default options
	var m_objOptions =
	{
		autoEdit : true,
		autoHeight : true,
		editable : false,
		enableAddRow : false,
		enableColumnReorder : false,
		explicitInitialization : true,
		extraParameters : [],
		fixedFilter : [],
		xforceFitColumns : true,
		autosizeColsMode : true,
		fullWidthRows : true,
		gridName : '',
		headerRowHeight : 25,
		multiColumnSort : true,
		multiSelect : false,
		showHeaderRow : false,
		enableCellNavigation: true,
		createPreHeaderPanel: false
	};

	var m_objGridDataView = null;
	var m_objGrid = null;
	var m_objColumnPicker = null;

	var m_objActiveRow = null;
	var m_blnFetch = true;

	var m_strContainerClass = '';
	var m_strFormID = '';
	var m_strGridElementID = '';
	var m_strGridElementClass = '';
	var m_objGridData = [];
	var m_objGridColumnFilters = {};
	var m_objVariableFilter = [];

	var m_blnAjaxGrid = false;
	if (objOptions_a.webServiceFunction.length > 0)
	{
		m_blnAjaxGrid = true;
	}

	var m_arrReturnFields = objOptions_a.returnFields;
	var m_objGridColumns = objOptions_a.columns;
	var m_cbOnActiveRowChange = objOptions_a.cbOnActiveRowChange;
	var m_cbOnAddNew = objOptions_a.cbOnAddNew;
	var m_cbOnCalculate = objOptions_a.cbOnCalculate;
	var m_cbOnCellChange = objOptions_a.cbOnCellChange;
	var m_cbOnClickEvent = objOptions_a.cbOnClickEvent;
	var m_cbOnDeleteRow = objOptions_a.cbOnDeleteRow;
	var m_cbOnFetchData = objOptions_a.cbOnFetchData;
	var m_cbOnSelection = objOptions_a.cbOnSelection;
	var m_cbOnSelectionChange = objOptions_a.cbOnSelectionChange;	
	
	var m_strWebServiceFunction = objOptions_a.webServiceFunction;
	
	var m_objSortFields = null;

	// allowable overridden options
	if (objOptions_a.autoHeight !== undefined)
	{
		m_objOptions.autoHeight = objOptions_a.autoHeight;
	}
	if (objOptions_a.containerClass !== undefined)
	{
		m_strContainerClass = objOptions_a.containerClass;
	}
	if (objOptions_a.editable !== undefined)
	{
		m_objOptions.editable = objOptions_a.editable;
	}
	if (objOptions_a.enableAddRow !== undefined)
	{
		m_objOptions.enableAddRow = objOptions_a.enableAddRow;
	}
	if (objOptions_a.extraParameters !== undefined)
	{
		m_objOptions.extraParameters = objOptions_a.extraParameters;
	}
	if (objOptions_a.fixedFilter !== undefined)
	{
		m_objOptions.fixedFilter = objOptions_a.fixedFilter;
	}
	// if (objOptions_a.forceFitColumns !== undefined)
	// {
		// m_objOptions.forceFitColumns = objOptions_a.forceFitColumns;
	// }
	if (objOptions_a.autosizeColsMode !== undefined)
	{
		m_objOptions.autosizeColsMode = objOptions_a.autosizeColsMode;
	}
	if (objOptions_a.gridName !== undefined)
	{
		m_objOptions.gridName = objOptions_a.gridName;
	}
	if (objOptions_a.multiSelect !== undefined)
	{
		m_objOptions.multiSelect = objOptions_a.multiSelect;
	}
	if (objOptions_a.rowHeight !== undefined)
	{
		m_objOptions.rowHeight = objOptions_a.rowHeight;
	}
	if (objOptions_a.showHeaderRow !== undefined)
	{
		m_objOptions.showHeaderRow = objOptions_a.showHeaderRow;
	}

	// ====================================================================================
	// HELPERS ============================================================================

	function clearVariableFilter()
	{
		m_objVariableFilter = [];
	}

	function getReturnFields(objItem_a, arrReturnFields_a)
	{
		var objResultFields = null;
		var strAddResultField = '';
		var strResultFields = '';
		var arrReturnFields = arrReturnFields_a;

		if (arrReturnFields === undefined)
		{
			arrReturnFields = m_arrReturnFields;
		}

		for (var intI = 0; intI < arrReturnFields.length; intI++)
		{
			var objValue = objItem_a[arrReturnFields[intI]];
			strAddResultField = '"' + arrReturnFields[intI] + '": "' + objValue + '"';

			if (intI > 0)
			{
				strResultFields += ',';
			}

			strResultFields += strAddResultField;
		}

		strResultFields = '{' + strResultFields + '}';
		objResultFields = JSON.parse(strResultFields);

		return objResultFields;
	}

	function onClearFilter()
	{
		if (m_blnAjaxGrid)
		{
			if (m_blnFetch)
			{
				// instead of changing the underlying data and notifying the dataview, since filters are only available for ajax grids (not editable ones), we just blank the data and reinitiale the grid
				m_objGridData = [];
				m_objGridColumnFilters = {};
			}
		}

		m_objThis.render(m_strFormID, m_strGridElementClass);

		// selection change
		if ($.isFunction(m_cbOnSelectionChange))
		{
			var objResultFields = [];
			m_cbOnSelectionChange(objResultFields);
		}
	}

	function populateGrid()
	{
		m_objGrid.invalidate();
		m_objGrid.render();
		if (m_objOptions.editable)
		{
			m_objGrid.commitEditAndSetFocus();
		}
	}

	function renumberRows(strRowNumField_a)
	{
		if (strRowNumField_a.length > 0)
		{
			processArray(m_objGridData, function(objRow_a, intRowNum_a)
			{
				objRow_a[strRowNumField_a] = intRowNum_a;
			});
		}
	}
	
	function wildcardMatch(str_a, strPattern_a)
	{
		var objRegEx = strPattern_a.toRegExp();
		return objRegEx.test(str_a);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindDataView()
	{
		m_objGridDataView.onRowCountChanged.subscribe(function(objEvent_a, objArgs_a)
		{
			m_objGrid.updateRowCount();
			m_objGrid.render();
		});

		m_objGridDataView.onRowsChanged.subscribe(function(objEvent_a, objArgs_a)
		{
			m_objGrid.invalidateRows(objArgs_a.rows);
			m_objGrid.render();
		});
	}

	function bindGrid()
	{
		// bind only if used
		if ($.isFunction(m_cbOnActiveRowChange))
		{
			m_objGrid.onActiveCellChanged.subscribe(function(objEvent_a, objArgs_a)
			{
				var objColumn = m_objGrid.getColumns()[objArgs_a.cell];

				if (m_objGridData[objArgs_a.row] !== m_objActiveRow)
				{
					m_cbOnActiveRowChange(m_objGridData[objArgs_a.row], objColumn.field);
					m_objActiveRow = objArgs_a.row;
				}
			});
		}

		// bind only if used
		if ($.isFunction(m_cbOnAddNew))
		{
			m_objGrid.onAddNewRow.subscribe(function(objEvent_a, objArgs_a)
			{
				var objItem = null;

				objItem = m_cbOnAddNew();

				$.extend(objItem, objArgs_a.item);
				m_objGridDataView.addItem(objItem);
			});
		}
		
		// bind only if used
		if ($.isFunction(m_cbOnCalculate))
		{
			m_objGrid.onCellChange.subscribe(function(objEvent_a, objArgs_a)
			{
				var objColumn = m_objGrid.getColumns()[objArgs_a.cell];
				m_cbOnCalculate(m_objGridData[objArgs_a.row], objColumn.field);
				m_objGrid.invalidateRow(objArgs_a.row);
				m_objGrid.render();
			});
			
			m_objGrid.onActiveCellChanged.subscribe(function(objEvent_a, objArgs_a)
			{
				m_cbOnCalculate(null, null);
			});
		}

		if ($.isFunction(m_cbOnCellChange))
		{
			m_objGrid.onCellChange.subscribe(function(objEvent_a, objArgs_a)
			{
				m_cbOnCellChange();
			});

			// m_objGrid.onCellCssStylesChanged.subscribe(function(objEvent_a, objArgs_a)
			// {
				// if ($.isFunction(m_cbOnCellChange))
				// {
					// m_cbOnCellChange();
				// }
			// }
			// );
		}

		// bind only if used
		if ($.isFunction(m_cbOnDeleteRow))
		{
			m_objGrid.onDeleteRow.subscribe(function(objEvent_a, objArgs_a)
			{
				var objRow = m_objGridDataView.getItem(objArgs_a.row);
				var intRowID = objRow.id;

				try
				{
					m_objGridDataView.deleteItem(intRowID);
				}
				catch (err)
				{
					doNothing();
				}

				m_cbOnDeleteRow(objRow);

				//m_objGrid.navigateRowStart();
				//m_objGrid.navigateUp();
				m_objThis.refreshRow(objRow);
				//m_objGrid.navigateNext();
				//m_objGrid.navigateUp();
				//m_objGrid.navigateRowStart();
				m_objGrid.focus();
			});
		}

		// bind only if used
		if ($.isFunction(m_cbOnSelectionChange))
		{
			m_objGrid.onClick.subscribe(function(objEvent_a, objArgs_a)
			{
				objEvent_a.stopPropagation();

				// selection change
				var objCell = m_objGrid.getCellFromEvent(objEvent_a);
				var objRow = objCell.row;
				var objItem = m_objGridDataView.getItem(objRow);
				var objResultFields = [];
				objResultFields[0] = getReturnFields(objItem);
				m_cbOnSelectionChange(objResultFields);
			});
		}

		// bind only if used
		if ($.isFunction(m_cbOnSelection))
		{
			m_objGrid.onDblClick.subscribe(function(objEvent_a, objArgs_a)
			{
				objEvent_a.stopPropagation();

				var objCell = m_objGrid.getCellFromEvent(objEvent_a);
				var objRow = objCell.row;
				var objItem = m_objGridDataView.getItem(objRow);
				var objResultFields = [];
				objResultFields[0] = getReturnFields(objItem);
				m_cbOnSelection(objResultFields);
			});
		}

		m_objGrid.onHeaderRowCellRendered.subscribe(function(objEvent_a, objArgs_a)
		{
			$(objArgs_a.node).empty();
			if (objArgs_a.column.id === 'rownum')
			{
				$("<img id='" + m_strGridElementID + "-clearfilter' class='gb-button' src='" + DYNAMIC_APP_DIR_URL + "images/themes/default/framework/icon_clearfilter.png'>").data("columnId", objArgs_a.column.id).val(m_objGridColumnFilters[objArgs_a.column.id]).appendTo(objArgs_a.node);
				$('#' + m_strGridElementID + '-clearfilter').bind('click', onClearFilter);
			}
			else
			{
				$("<input type='text' class='gs-grid-headcell-xxx'>").data("columnId", objArgs_a.column.id).val(m_objGridColumnFilters[objArgs_a.column.id]).appendTo(objArgs_a.node);
			}
		});

		m_objGrid.onSort.subscribe(function(objEvent_a, objArgs_a)
		{
			var objColumns = objArgs_a.sortCols;
			m_objSortFields = new Array();

			processArray(objColumns, function(objField_a, intRowNum_a)
			{
				m_objSortFields[intRowNum_a - 1] =
				{
					"field" : objField_a.sortCol.field,
					"ascending" : objField_a.sortAsc
				};
			});

			fetchGridData();
		});

		// note: keyboard deletion is not fully implemented because slickgrid doesn't yet support keyboard control of non-edtible fields (such as the row selector)
		// m_objGrid.onKeyDown.subscribe(function(objEvent_a)
		// {
		// if (objEvent_a.which === 46)
		// {
		// var objRows = m_objGrid.getSelectedRows();
		// for (var intI = 0, intRows = objRows.length; intI < intRows; intI++)
		// {
		// var objRow = m_objGridDataView.getItem(objRows[intI]);
		// var intRowID = objRow.id;
		// alert(intRowID);
		// m_objThis.remove({ "rownm": intRowID }, 'rownum', 'rownum');
		// m_objGridDataView.deleteItem(intRowID);
		// }
		// }
		// });
	}

	function bindHeaderFields()
	{
		$(m_objGrid.getHeaderRow()).delegate(':input', 'change keyup', function(objEvent_a)
		{
			var strColumnID = $(this).data('columnId');
			if (strColumnID !== 'rownum')
			{
				if (strColumnID !== null)
				{
					m_objGridColumnFilters[strColumnID] = $.trim($(this).val());

					if (objEvent_a.which === 13)
					{
						fetchGridData();
					}
				}
			}
		});
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function gridDataFetched(objResponse_a)
	{
		$('body').css('cursor', 'auto');
		m_objGridData = objResponse_a;

		// notify the dataview that we are manually changing the underlying data
		m_objGridDataView.beginUpdate();
		m_objGridDataView.setItems(m_objGridData);
		m_objGridDataView.endUpdate();

		populateGrid();

		if ($.isFunction(m_cbOnFetchData))
		{
			m_cbOnFetchData(m_objGridData);
		}
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function fetchGridData()
	{
		if (m_blnAjaxGrid)
		{
			if (m_blnFetch)
			{
				var objJSON = os.ajaxRequestCreate(m_strWebServiceFunction,
						$.merge([
								{
									"name" : "filter",
									"value" : m_objThis.getFilter()
								},
								{
									"name" : "order",
									"value" : m_objSortFields
								}
							],
							m_objOptions.extraParameters));
				os.ajaxCall(URL_WEBSERVICE, objJSON, gridDataFetched, os.ajaxError);
			}
			else
			{
				gridDataFetched(m_objGridData);
			}

			// selection change
			if ($.isFunction(m_cbOnSelectionChange))
			{
				var objResultFields = [];
				m_cbOnSelectionChange(objResultFields);
			}
		}
	}

	// ====================================================================================
	// PUBLICS ============================================================================

	this.append = function(arrRowData_a, strRowNumField_a)
	{
		if (strRowNumField_a === undefined)
		{
			strRowNumField_a = '';
		}

		// make arrRowData a copy of arrRowData_a instead of a reference
		var arrRowData = os.copyArray(arrRowData_a);

		// append arrRowsData to m_objGridData
		var intIndex = m_objGridData.length;
		processArray(arrRowData, function(objRow_a)
		{
			m_objGridData[intIndex] = objRow_a;
			intIndex++;
		});

		// renumber rows
		renumberRows(strRowNumField_a);

		// notify the dataview that we are manually changing the underlying data
		m_objGridDataView.beginUpdate();
		m_objGridDataView.setItems(m_objGridData);
		m_objGridDataView.endUpdate();

		populateGrid();
	};

	this.commit = function()
	{
		m_objGrid.commitEditAndSetFocus();
	};

	this.getData = function()
	{
		return m_objGridData;
	};

	this.getDataView = function()
	{
		return m_objGridDataView;
	};

	// grid filter callback
	this.getFilter = function()
	{
		var objFilter = new Array();

		var intI = 0;
		for (var strColumnID in m_objGridColumnFilters)
		{
			if (strColumnID !== undefined && m_objGridColumnFilters[strColumnID] !== '')
			{
				var objColumn = m_objGrid.getColumns()[m_objGrid.getColumnIndex(strColumnID)];

				objFilter[intI] =
				{
					"field" : objColumn.field,
					"value" : m_objGridColumnFilters[strColumnID]
				};
				intI++;
			}
		}

		// add the fixed filter
		try
		{
			processArray(m_objOptions.fixedFilter, function(objFixedField_a)
			{
				objFilter[intI] =
				{
					"field" : objFixedField_a.field,
					"value" : objFixedField_a.value,
					"wildcards" : false
				};
				intI++;
			});
		}
		catch (err)
		{
			doNothing();
		}

		// add the variable filter
		try
		{
			processArray(m_objVariableFilter, function(objVariableField_a)
			{
				objFilter[intI] =
				{
					"field" : objVariableField_a.field,
					"value" : objVariableField_a.value,
					"wildcards" : false
				};
				intI++;
			});
		}
		catch (err)
		{
			doNothing();
		}

		return objFilter;
	};

	this.getSelection = function(arrReturnFields_a)
	{
		var objResultFields = [];
		var objSelectedRows = m_objGrid.getSelectedRows();

		// sort the selecction as slick grid returns random order
		objSelectedRows.sort(function(a, b)
		{
			return a > b;
		});

		processArray(objSelectedRows, function(objRow_a, intRowNum_a)
		{
			var objItem = m_objGridDataView.getItem(objRow_a);
			objResultFields[intRowNum_a - 1] = getReturnFields(objItem, arrReturnFields_a);
		});

		return objResultFields;
	};

	this.moveTo = function(objTargetGrid_a, arrRowData_a, strIDField_a, strRowNumField_a)
	{
		try
		{
			objTargetGrid_a.append(arrRowData_a, strRowNumField_a);
			m_objThis.remove(arrRowData_a, strIDField_a, strRowNumField_a);
		}
		catch (err)
		{
			doNothing();
		}
	};

	this.setFocus = function()
	{
		m_objGrid.commitEditAndSetFocus();
		m_objGrid.navigateTop();
		m_objGrid.navigateRowStart();
	};

	this.refresh = function(blnFetch_a)
	{
		m_blnFetch = blnFetch_a;
		if (m_blnFetch === undefined)
		{
			m_blnFetch = true;
		}

		try
		{
			if (m_blnAjaxGrid)
			{
				onClearFilter();
			}
			else
			{
				m_objGridDataView.beginUpdate();
				m_objGridDataView.setItems(m_objGridData);
				m_objGridDataView.endUpdate();
				populateGrid();
			}
		}
		catch (err)
		{
			doNothing();
		}

		m_blnFetch = true;
	};

	this.refreshRow = function(objRow_a)
	{
		m_objGrid.invalidateRow(objRow_a);
		m_objGrid.render();
	};

	this.remove = function(arrRowData_a, strIDField_a, strRowNumField_a)
	{
		if (strRowNumField_a === undefined)
		{
			strRowNumField_a = '';
		}

		// make arrRowData a copy of arrRowData_a instead of a reference
		var arrRowData = os.copyArray(arrRowData_a);

		// delete arrRowData from m_objGridData
		processArray(arrRowData, function(objRowToDelete_a)
		{
			var strIDToDelete = objRowToDelete_a[strIDField_a];

			processArray(m_objGridData, function(objRow_a, intRowNum_a, intIndex_a)
			{
				var blnDeleteRow = (objRow_a[strIDField_a] == strIDToDelete);

				if (blnDeleteRow)
				{
					// remove item from array
					m_objGridData.splice(intIndex_a, 1);
				}
			});
		});

		// renumber rows
		renumberRows(strRowNumField_a);

		// notify the dataview that we are manually changing the underlying data
		m_objGridDataView.beginUpdate();
		m_objGridDataView.setItems(m_objGridData);
		m_objGridDataView.endUpdate();

		populateGrid();
	};

	this.render = function(strFormID_a, strGridElementClass_a)
	{
		// dynamically set a unique grid id for the provided class div
		m_strFormID = strFormID_a;
		m_strGridElementClass = strGridElementClass_a;
		m_strGridElementID = 'grid-' + getGUID();
		$(m_strGridElementClass, strFormID_a).attr('id', m_strGridElementID);

		if ((m_strContainerClass.length > 0) && (m_objOptions.autoHeight === false))
		{
			$(m_strGridElementClass, strFormID_a).addClass(m_strContainerClass);
		}

		m_objGridDataView = new Slick.Data.DataView();
		m_objGrid = new Slick.Grid('#' + m_strGridElementID, m_objGridDataView, m_objGridColumns, m_objOptions);
		m_objGrid.setSelectionModel(new Slick.RowSelectionModel());

		bindDataView();
		bindGrid();
		bindHeaderFields();
		fetchGridData();

		m_objGrid.init();
		// notify the dataview that we are manually changing the underlying data
		m_objGridDataView.beginUpdate();
		m_objGridDataView.setItems(m_objGridData);
		m_objGridDataView.endUpdate();

		//      <ul id="contextMenu" style="display:none;position:absolute">
		//          <b>Set priority:</b>
		//          <li data="Low">Low</li>
		//          <li data="Medium">Medium</li>
		//          <li data="High">High</li>
		//      </ul>

		m_objGrid.onContextMenu.subscribe(function(e)
		{
			e.preventDefault();
			//          var cell = m_objGrid.getCellFromEvent(e);
			//          $("#contextMenu").data("row", cell.row).css("top", e.pageY).css("left", e.pageX).show();
			//
			//          $("body").one("click", function()
			//          {
			//              $("#contextMenu").hide();
			//          });
		});

		//      $("#contextMenu").click(function(e)
		//      {
		//          if (!$(e.target).is("li"))
		//          {
		//              return;
		//          }
		//          var row = m_objGridData("row");
		//          m_objGridData[row].priority = $(e.target).attr("data");
		//          m_objGrid.updateRow(row);
		//      });


		m_objColumnPicker = new Slick.Controls.ColumnPicker(m_objGridColumns, m_objGrid, m_objOptions);
		populateGrid();
	};

	this.setData = function(objGridData_a)
	{
		m_objGridData = objGridData_a;
	};

	this.setVariableFilter = function(objFilter_a)
	{
		m_objVariableFilter = objFilter_a;
	};
}

// --- datatables --- //

/**
 * Wrapper class for Datatables grid
 *
 */
function jDatatableRenderer(objOS_a, objOptions_a)
{
    var os = objOS_a;

    var m_objThis = this;

    var m_strFormID = ''; //strFormID_a;

    var m_strGridClass = ''; //strGridClass_a;

    var m_blnMultiSelect = false;
    var m_blnClearSelectionEnabled = false;

    var m_arrReturnFields = objOptions_a.returnFields;
    var m_arrGridColumns = objOptions_a.columns;
    var m_arrWebServiceParameters = objOptions_a.parameters;
    var m_cbDataFormatter = objOptions_a.cbDataFormatter;
    var m_cbPopulateAfter = objOptions_a.cbPopulateAfter;
    var m_cbOnActiveRowChange = objOptions_a.cbOnActiveRowChange;
    var m_cbOnAddNew = objOptions_a.cbOnAddNew;
    var m_cbOnDoubleClick = objOptions_a.cbOnDoubleClick;
    var m_cbOnCalculate = objOptions_a.cbOnCalculate;
	var m_cbOnCellChange = objOptions_a.cbOnCellChange;
	var m_cbOnClickEvent = objOptions_a.cbOnClickEvent;
    var m_cbOnFetchData = objOptions_a.cbOnFetchData;
    var m_cbOnSelection = objOptions_a.cbOnSelection;
    var m_cbOnSelectionChange = objOptions_a.cbOnSelectionChange;
	var m_cbOnRowReorder = objOptions_a.cbOnRowReorder;
	var m_cbOnPreRowReorder = objOptions_a.cbOnPreRowReorder;
    var m_strWebServiceFunction = objOptions_a.webServiceFunction;
    var m_cbClearSelection = objOptions_a.cbClearSelection;
    var m_cbRowFormatter = objOptions_a.cbRowFormatter;

    var m_objTimeout;
    var m_intDelay = 250;  // Delay in milliseconds
    var m_blnDoubleClicked = false;

    /**
     *
     * @type Stringfilter type values: all, array, plain
     */
    var m_strFilterType = 'all';

    if (objOptions_a.filterType !== undefined) 
	{
       m_strFilterType = objOptions_a.filterType;
    }

    if (objOptions_a.multiSelect !== undefined) 
	{
       m_blnMultiSelect = objOptions_a.multiSelect;
    }

    if (objOptions_a.clearSelectionEnabled !== undefined) 
	{
       m_blnClearSelectionEnabled = objOptions_a.clearSelectionEnabled;
    }
	
	var m_blnRowReorder = false;
	
	if (objOptions_a.rowReorder !== undefined) 
	{
		m_blnRowReorder = objOptions_a.rowReorder;
	}
	
	var m_strRowReorderSortorderfield = 'id';  //'sortorder';
	
	if (objOptions_a.rowReorderSortorderfield !== undefined)
	{
		m_strRowReorderSortorderfield = objOptions_a.rowReorderSortorderfield ;
	}

    var m_strTableGUID = 'ge-DT-' + getGUID();
	var m_strRefreshGUID = '';

    var m_objDataTable = '';

    var m_arrRowSelection = [];

    var m_strFilter = '';

	function formatRowID(strRowID_a)
	{
		var strResult = m_strTableGUID + "-" + strRowID_a;
		var strInvalidChars = ' +:._<>=&;"()/' + "'";

		for (var intI = 0, intLength = strInvalidChars.length; intI < intLength; intI++)
		{
			strResult = str_replace(strResult, strInvalidChars[intI], "-");
		}

		return strResult;
	}

    function _render(strFormID_a, strGridClass_a) 
	{
        m_strFormID    = strFormID_a;
        m_strGridClass = strGridClass_a;

        _renderGrid();
        _populateTable();
        _initCallback();
    }

    function _renderGrid()
    {
        m_strRefreshGUID = 'ge-DT-' + getGUID();

        var strGrid = '';

        strGrid = '<table  class="table table-hover display nowrap ' + m_strRefreshGUID + '" cellspacing="0" width="100%">';

        strGrid += '<thead>';
        strGrid += '<tr>';

        processArray(m_arrGridColumns, function(objColumn_a)
        {
            strGrid += '<th>';
            strGrid += objColumn_a.name;
            strGrid += '</th>';
        });

        strGrid += '</tr>';
        strGrid += '</thead>';

        strGrid += '<tbody>';

        strGrid += '</tbody>';
        strGrid += '</table>';

        os.element(m_strFormID, m_strGridClass).html(strGrid);
    }

    function _populateTable()
    {

        var arrColumns = [];
        var intFirstColumnIndexOfOrderableField = 0;

        processArray(m_arrGridColumns, function(objColumn_a, intRowNum_a, intI_a)
        {
            var blnOrderable = false;

            if (os.toBoolean(objColumn_a.sortable))
            {
                blnOrderable = true;

                if (intFirstColumnIndexOfOrderableField === 0)
                {
                    intFirstColumnIndexOfOrderableField = intI_a;
                }
            }

            arrColumns.push(
            {
                "data" : objColumn_a.id,
                "orderable" : blnOrderable,
                "defaultContent" : ""
            });
        });

		var strHasRecords = "Showing _START_ to _END_ of _TOTAL_ records";
		var strNoRecords = "No Records";
		
		if (os.hasCapability("mobile"))
		{
			strHasRecords = "";
			strNoRecords = "";
		}

        var objOptions = {

                ajax : function(objData_a, fnCallback_a, objSettings_a)
                {
                    var objAPI = this.api();
                    var arrParams = m_arrWebServiceParameters;
					var strFilter = '';
                    
                    if (arrParams !== undefined && arrParams.length > 0) 
					{
                        //look for the offset
                        processArray(arrParams, function(objParam_a, intRowNum_a, intI_a)
                        {
                            if (objParam_a.name === 'offset')
                            {
                                arrParams[intI_a].value = objData_a.start;
                                return true;
                            }
                        });

                        //look for the filter
                        processArray(arrParams, function(objParam_a, intRowNum_a, intI_a)
                        {
                            if (objParam_a.name === 'filter')
                            {
                                if (m_strFilterType !== 'normal') 
								{ 
                                    strFilter = _getFilter(objAPI);
                                    arrParams[intI_a].value = strFilter;
                                } 
                                return true;
                            }
                        });

                        //look for the order
                        processArray(arrParams, function(objParam_a, intRowNum_a, intI_a)
                        {
                            if (objParam_a.name === 'order')
                            {
                                arrParams[intI_a].value = _getOrder(objAPI);
                                return true;
                            }
                        });

                        processArray(arrParams, function(objParam_a, intRowNum_a, intI_a)
                        {
                            if (objParam_a.name === 'limit')
                            {
                                arrParams[intI_a].value = _getResponsivePagesize();
                                return true;
                            }
                        });
                    }
                    else 
					{
                        arrParams = [
                            {
                                "name" : 'order',
                                "value" : ""
                            },
                            {
                                "name" : 'filter',
                                "value" : ""
                            },
                            {
                                "name" : 'offset',
                                "value" : 0
                            },
                            {
                                "name" : 'limit',
                                "value" : _getResponsivePagesize()
                            }
                        ];

                        m_arrWebServiceParameters = arrParams;
                    }
                    
                    var objJSON = os.ajaxRequestCreate(m_strWebServiceFunction, arrParams);

                    os.ajaxCall(URL_WEBSERVICE, objJSON, function(arrResponse_a)
                    {

                        var intRecordCount = 0;
						var intFiltered = 0;	// JC doesn't work
                        var arrData = [];

						//var strInfo = 'Showing _START_ to _END_ of _TOTAL_ records';
						//var strInfoEmpty = 'Showing 0 to 0 of 0 records';
						// var strFiltered = '';	// JC doesn't work

                        if (arrResponse_a.length > 0)
                        {
                            intRecordCount = arrResponse_a[0].recordcount;
							intFiltered = intRecordCount; // JC to remove the filtered message
							//intFiltered = intRecordCount	// JC doesn't work

                            //arrData = arrResponse_a;

                            if ($.isFunction(m_cbDataFormatter)) 
							{
                                arrData = m_cbDataFormatter(arrResponse_a);
                            }
                            else 
							{
                                arrData = _formatData(arrResponse_a);
                            }
                            // setting up page length (limit per page).
                            m_objDataTable.page.len(arrResponse_a[0].limited);
                            
							// JC doesn't work
							//if (strFilter.length > 0)
							//{
								//intFiltered = arrResponse_a[0].limited;
							//}
						}

                        fnCallback_a(
                        {
                            recordsTotal : intFiltered,
                            recordsFiltered : intRecordCount,
                            data : arrData,
                            draw : objData_a.draw
                        });

                    }, os.ajaxError);

                },

                processing : false,
                colReorder : true,
                columns : arrColumns,
				drawCallback: function() { redrawSelection(); },
                createdRow : function(objRow_a, objData_a, intIndex_a) { fnCreatedRow(objRow_a, objData_a, intIndex_a); },
                order : [], //[[intFirstColumnIndexOfOrderableField, 'asc']],
                searching : false,
                serverSide : true,
                //scrollY : '70vh',
                scrollX:        300, // for horizontal scrolling, minimum width to 300px
                responsive : true,
                scrollCollapse : true,
                rowId : function(objData_a) { return formatRowID(objData_a.id); } ,
                //pageLength : 20,
                bLengthChange : false, //hiding "Show entries" of the pagination
                language :
                {
                    emptyTable : "&nbsp;",
                    info : strHasRecords,
                    infoEmpty : strNoRecords,
					xinfoFiltered: "(filtered)"	// JC doesn't work
                }

                /*
                ,
                "aoColumnDefs": [
                    { "sClass": "url", "aTargets": [4 ] }
                  ]
                */
            };

        if (m_blnClearSelectionEnabled)  
		{
            objOptions.dom = 'lfrt<"ge-customfooter-wrapper-xxx"<"ge-selectionclear-xxx">ip>';
        }
		
		objOptions.rowReorder = false;
		if (m_blnRowReorder)
		{			
			if (m_strRowReorderSortorderfield.length > 0)
			{
				objOptions.rowReorder = 
				{
					dataSrc : m_strRowReorderSortorderfield,
					xselector : 'tr'
				};
			}
		}
		
        //apply dataTables
        m_objDataTable = os.element(m_strFormID, '.' + m_strRefreshGUID).DataTable(objOptions);

        if (m_blnClearSelectionEnabled)  
		{
            os.element(m_strFormID, 'div.ge-selectionclear-xxx').html('<input class="btn btn-default gb-button ge-DT-clearselection" type="button" value="Clear Selection">');
            os.element(m_strFormID, '.ge-DT-clearselection').click( _clearSelection );
        }
    }
		
    function _initCallback()
    {
        if ($.isFunction(m_cbPopulateAfter)) 
		{
            m_cbPopulateAfter();
        }

/*         os.element(m_strFormID, '.' + m_strRefreshGUID + ' tbody').on('click', 'tr', function()
        {
            var objElementThis = this;

            m_objTimeout = setTimeout(function()
			{
                // This inner function is called after the delay
                // to handle the 'click-only' event.

                //if (!m_blnDoubleClicked) 
				//{ 
					// do this if double click is not triggred
                    var strRowID = objElementThis.id;

                    if (strRowID !== undefined && strRowID.length > 0)
                    {
                        _setRowSelection(objElementThis);

						if ($.isFunction(m_cbOnClickEvent))
						{
							var strColumnID = ""; // AIAGENT: how to get the column id
							m_cbOnClickEvent(strColumnID, m_arrRowSelection);
						}
					}
				//}
                //else 
				//{
                    m_blnDoubleClicked = false;
                //}

                m_objTimeout = null;

            }, m_intDelay);
        }); */

		os.element(m_strFormID, '.' + m_strRefreshGUID + ' tbody').on('click', 'td', function(event)
		{
			// Prevent this from bubbling up to the row click handler if you use both
			event.stopPropagation();
			
			var objCell = $(this);
			var objRow = objCell.closest('tr')[0];
			var intColumnIndex = objCell.index();
			var objColumn;
			
			// Get column ID from configuration
			if (intColumnIndex >= 0 && intColumnIndex < m_arrGridColumns.length) 
			{
				objColumn = m_arrGridColumns[intColumnIndex];
			}
			
			var strRowID = objRow.id;
			
			if (strRowID !== undefined && strRowID.length > 0)
			{
				_setRowSelection(objRow);
				
				if (objColumn.events && objColumn.events.length > 0)
				{
					var arrEvents = objColumn.events;
					processArray(arrEvents, function(objEvent_a)
					{
						// Find the specific link element within the clicked cell.
						var objLinkElement = $(event.target).closest('.' + objEvent_a.bind);
						var strCommand = objEvent_a.command;
						var arrParameters = objEvent_a.parameters;

						// Check if a link was actually clicked.
						if (objLinkElement.length > 0) 
						{
							if ($.isFunction(m_cbOnClickEvent)) 
							{
								// Pass the correct column, row, and link element to the callback.
								m_cbOnClickEvent(objColumn, m_arrRowSelection, objLinkElement, strCommand, arrParameters);
							}
						}
					});
				}
			}
		});

        os.element(m_strFormID, '.' + m_strRefreshGUID + ' tbody').on('dblclick', 'tr', function()
        {
            if (m_objTimeout)
			{
                // Clear the timeout since this is a double-click and we don't want
                // the 'click-only' code to run.
                clearTimeout(m_objTimeout);
                m_objTimeout = null;
            }

            m_blnDoubleClicked = true;

            var strRowID = this.id;

            if (strRowID !== undefined && strRowID.length > 0)
            {
                m_arrRowSelection = [];

                _setRowSelection(this);

                if ($.isFunction(m_cbOnSelection))
				{
                    m_cbOnSelection(m_arrRowSelection);
                }
            }
        });
			
		if (m_blnRowReorder)
		{
			if ($.isFunction(m_cbOnPreRowReorder)) 
			{
				m_objDataTable.on('pre-row-reorder', m_cbOnPreRowReorder);	
			}
			
			if ($.isFunction(m_cbOnRowReorder)) 
			{
				m_objDataTable.on('row-reorder', m_cbOnRowReorder);	
			}
		}
    }

    function _getResponsivePagesize() 
	{
        var intHeight = os.element(m_strFormID, '.gb-form').height();
        var intHeaderButtonBarHeight = os.element(m_strFormID, '.ge-button-panel').height();

        var availableHeight = intHeight - 230;

        // consider the header button height. if the header button bar is morethan 2 rows or lines.
        // the content overflow. So, we need to adjust it.
        if (intHeaderButtonBarHeight > 40) 
		{ 
			// adjust it if it is 2-liners or more.
			availableHeight -= (intHeaderButtonBarHeight - 50);
        }

        // this is not flexible. it would be good if we can get the row height and not fixed value.
        var intRowHeight = 33; 
        //$( 'tr', objDt_a.table().body() ).eq(0).height();

        var fltPageSize = availableHeight / intRowHeight;	
        // needs to be a separate line for closure compiler
        var pageSize =  Math.floor( fltPageSize ); 
        //10;

        return pageSize;
    }

    function _formatData(arrData_a)
    {
        var arrData = [];

        if (arrData_a.length > 0)
        {
            processArray(arrData_a, function(objData_a, intRowNum_a, intI_a)
            {
                var objData = {};

                $.each(objData_a, function(strKey_a, paramValue_a)
                {

                    var value = paramValue_a;

                    if ($.isArray(value))
                    {
                        var strList = '<ul>';
                        processArray(value, function(strVal_a)
                        {
                            strList += '<li>' + strVal_a + '</li>';
                        });
                        strList += '</ul>';

                        value = strList;
                    }

                    objData[strKey_a] = value;
                });

                arrData.push(objData);
            });
        }

        return arrData;
    }

    // using the field that has order to filter a single field
    function _getFilter(objDT_a)
    {
        var arrFilter = '';

        if ( m_strFilterType === 'string' ) 
		{
            arrFilter =  m_strFilter; 
            // actually a string value rather than an array
        }
        else if (m_strFilterType === 'all') 
		{
            arrFilter = [ { field : 'all', value : m_strFilter } ];
        }
        else if (m_strFilterType === 'array') 
		{
            arrFilter = [];
            var objFilter;

            processArray(m_arrGridColumns, function(objColumn_a) 
			{
                if (os.toBoolean(objColumn_a.sortable)) 
				{
                    objFilter = { field : objColumn_a.field, value : m_strFilter };
                    arrFilter.push(objFilter);
                }
            });
        }
        
        return arrFilter;
    }

    function _getOrder(objDT_a)
    {
        var arrDTorder = objDT_a.order();
        var arrOrder = [];
        var intColumnIndex;
        var strOrder;
        var blnIsAcending;
        var objColumn;

        if (arrDTorder.length > 0) 
		{
            processArray(arrDTorder, function(arrRow_a) 
			{
                intColumnIndex = arrRow_a[0];
                strOrder = arrRow_a[1];

                objColumn = m_arrGridColumns[intColumnIndex];

                if (strOrder === 'asc')
                {
                    blnIsAcending = true;
                }
                else 
				{
                    blnIsAcending = false;
                }

                arrOrder.push( { 'field' : objColumn.field, 'ascending' : blnIsAcending  });
            });
        }

        return arrOrder;
    }

    function _setRowSelection(objRow_a)
    {
		var objRowData = m_objDataTable.rows('#' + objRow_a.id).data()[0];        
        var objOSRow = $(objRow_a);
        var strSelectedRowID = objRowData.id;

        if (!m_blnMultiSelect || m_blnDoubleClicked) 
		{
            objOSRow.parents('tbody').find('tr.selected').removeClass('selected');
        }

        objOSRow.toggleClass('selected');

        if (objOSRow.hasClass('selected'))
        {
            if (m_blnMultiSelect) 
			{
              //m_arrRowSelection.push(strRowID);
              m_arrRowSelection.push(objRowData);
            }
            else 
			{
                //m_arrRowSelection = [strRowID];
                m_arrRowSelection = [objRowData];
            }
        }
        else 
		{
            if (m_blnMultiSelect) 
			{
                var arrSelection = [];

                processArray(m_arrRowSelection, function(objRowData_a) 
				{
                    if (strSelectedRowID !== objRowData_a.id) 
					{
                       arrSelection.push(objRowData_a);
                    }
                });

                m_arrRowSelection = arrSelection;
            }
            else 
			{
                m_arrRowSelection = [];
            }
        }

        if ($.isFunction(m_cbOnSelectionChange)) 
		{
            m_cbOnSelectionChange(m_arrRowSelection);
        }
    }

	// LIMITATION: if arrReturns_a is not provided, then we can get the full selection, but if it is provided, we can only get the visible portion of the selection
	//			   note: this might be ok, if we call getSelection twice for listers.  once without arrReturns for bulk operations such as publish, delete etc.
	//					 and once with arrReturns for operations such as edit or view
	//			   For forms with single selections, if multiple come back, the user should be prompted to unselect something
    function _getSelection(arrReturns_a)
    {
        var arrSelection = [];

        var arrReturnFields = [];

        if (arrReturns_a !== undefined) 
		{
           arrReturnFields = arrReturns_a;
        }
        else if (m_arrReturnFields !== undefined) 
		{
           arrReturnFields = m_arrReturnFields;
        }


        //processArray(m_arrRowSelection, function(strRowID_a)
        processArray(m_arrRowSelection, function(objRowData_a)
        {
            var objSelection = {};

            //arrReturns_a = m_arrReturnFields;
            //var objRowData = m_objDataTable.rows('#' + formatRowID(strRowID_a)).data()[0];            
            
            var objRowData = objRowData_a;

            if (arrReturnFields.length === 0) 
			{ 
				// return all fields
                arrSelection.push(objRowData);
            }
            else 
			{

                processArray(arrReturnFields, function(strAttrib_a)
                {
                    if (objRowData[strAttrib_a] !== undefined)
                    {
                        objSelection[strAttrib_a] = objRowData[strAttrib_a];
                    }
                });
            }

            arrSelection.push(objSelection);
        });

        return arrSelection;
    }

    function redrawSelection()
    {        
        //processArray(m_arrRowSelection, function(strRowID_a)
        processArray(m_arrRowSelection, function(objRowSelection_a)
        {
            var strRowSelectionID = objRowSelection_a.id;
            var objRowData = m_objDataTable.rows('#' + formatRowID(strRowSelectionID)).data()[0];
            
			if (objRowData != undefined)
			{
				if (objRowData.id == strRowSelectionID)
				{
					os.element('#' + formatRowID(strRowSelectionID)).toggleClass('selected');
				}
			}
        }
        );
    }

    function _clearSelection() 
	{
        m_arrRowSelection = [];
        
        _refresh(false);

        os.element(m_strFormID, '.' + m_strRefreshGUID + ' tbody').find('tr.selected').removeClass('selected');

        if ($.isFunction(m_cbOnSelectionChange)) 
        {
            m_cbOnSelectionChange(m_arrRowSelection);
        }
        
        if ($.isFunction(m_cbClearSelection))
        { 
            m_cbClearSelection();
        }
    }
    
    // callback function for Createrow. can attached functions to further format the row. (e.g. add more css class, etc.)
    function fnCreatedRow(objRow_a, objData_a, intIndex_a) 
	{ 
        if ($.isFunction(m_cbRowFormatter)) 
		{
           m_cbRowFormatter(objRow_a, objData_a, intIndex_a); 
        }
    }
    
    // update parameters on run-time, if parameter already exists, it will update, if not yet, it will add. (e.g. filter)
    function refreshWebServiceParameters(arrParameters)  
	{
        var blnFound = false;
        var intIndex = -1;
                
        processArray(arrParameters, function(objParam) 
		{ 
            blnFound = false;
            intIndex = -1;
            
            processArray(m_arrWebServiceParameters, function(objWSParam, intR, intI) 
			{ 
                if (objWSParam.name === objParam.name) 
				{ 
                   blnFound = true; 
                   intIndex = intI;
                }
            });
            
            if (blnFound) 
			{ 
               m_arrWebServiceParameters[intIndex].value = objParam.value;                    
            }
            else 
			{ 
               m_arrWebServiceParameters.push( objParam );
            }
        });
    }
	
	function _getDatatable()
	{
		return m_objDataTable;
	}
	
    function _refresh(blnPreserveSelection_a, blnResetPaging)
    {
		var blnPreserveSelection = blnPreserveSelection_a;

		if (blnPreserveSelection === undefined)
		{
			blnPreserveSelection = true; // default to true
		}
        
        if (blnResetPaging === undefined) 
		{ 
           blnResetPaging = false; 
        }
        
        m_objDataTable.draw(blnResetPaging);

		if (!blnPreserveSelection)
		{
			m_arrRowSelection = [];

            if ($.isFunction(m_cbOnSelectionChange)) 
			{
                m_cbOnSelectionChange(m_arrRowSelection);
            }
		}

        if (m_arrRowSelection.length > 0) 
		{
            redrawSelection();
        }
    }

    function _resize() 
	{
        m_objDataTable.draw(false);
    }

    function _search(strKeyword_a, arrParameters)
    {
        m_strFilter = strKeyword_a;

        if (arrParameters !== undefined && arrParameters.length > 0) 
		{ 
            refreshWebServiceParameters(arrParameters);
        }
        
        _refresh(true, true);
    }
		      
    var objResult =
    {
		clearSelection : _clearSelection,
        getSelection : _getSelection,
		getDatatable : _getDatatable,
        refresh : _refresh,
        search : _search,
        resize : _resize,
        render : _render
    };

    return objResult;
}
