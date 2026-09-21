/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
/*jsl:import ..\..\inc-osutils-jgrids.js*/

function core_frmRecordChooser(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	var m_intFormHeight = 511;
	//return;

	// ------------------------------------------------------------------------------------

	var m_arrSelection = [];
    
    var m_arrResultFields = ['id'];

	var m_objGrid;

	// ------------------------------------------------------------------------------------


    if(m_objParameters.resultfields !== undefined && m_objParameters.resultfields.length > 0) { 
        m_arrResultFields = m_objParameters.resultfields;
    }
    
	var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : [
				['CloseButton', 'SelectButton']
			]
		}
	];

	var m_arrTiles = [
		// miscellaneous tiles
		{
			id : '-',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			actionData : null,
			tip : '',
			type : 'blank'
		},
		// toolbar tiles
		{
			id : 'CloseButton',
			caption : 'Close',
			classes : '',
			permissions : [],
			action : function ()
			{
				m_objThis.FormClose_onClick();
			},
			actionData : null,
			tip : 'Click here to close the select form.',
			type : 'toolbarbutton'
		},
		{
			id : 'SelectButton',
			caption : 'Select',
			classes : 'disabled',
			permissions : [],
			action : function ()
			{
				m_objThis.SelectButton_onClick();
			},
			actionData : null,
			tip : 'Click here to select.',
			type : 'toolbarbutton'
		}
	];

	// ====================================================================================
	// HELPERS ============================================================================

	function initialiseForm()
	{
		// defaulting
		var strFormTitle = m_objParameters.title;
		os.element(m_strFormID, '.ge-form-title').text(strFormTitle);
		
		// resize the form
		var intViewPortWidth = os.getViewPort().width;
		var intViewPortHeight = os.getViewPort().height;
		os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);
	}

	function outputStatus(strDivName_a, intIndexX_a, intIndexY_a, str_a)
	{
		// clear the outputStatus
		os.element(m_strFormID, '.' + strDivName_a).text('');
		var str = str_a;
		if (os.hasCapability('regionscroll') === false)
		{
			str = '';
		}

		str = '<div class="gs-canvastext gs-cell-xxx ' + strDivName_a + '" style="top:' + intIndexY_a + 'px; left:' + intIndexX_a + 'px">' + str + '</div>';

		os.element(m_strFormID, '.ge-status-panel').append(str);
	}

	// ====================================================================================
	// POPULATING =========================================================================

	function populateDock()
	{
		m_objDock = new jDock(os,
			{
				"alwaysvisiblebuttoncount": 2,
                "enablemobiledropdown" : true,
				"map" : m_arrMap,
				"tiles" : m_arrTiles
			}
			);
		m_objDock.render(m_strFormID, '.ge-button-panel');
        
        m_objDock.disableButtons('SelectButton','disabled');
	}

	function populateForm()
	{
		var objGridColumns = [];

		if (m_objParameters.mode === LIST_FILEFORMATINSTALLABLE)
		{
			objGridColumns = [
				{
					id : "rownum",
					name : "#",
					field : "rownum",
					behavior : "select",
					cssClass : "gs-gridcell-selection",
					width : 40,
					cannotTriggerInsert : true,
					resizable : false,
					selectable : false
				},
				{
					id : "code",
					name : "Code",
					field : "code",
					sortable : true,
					editor : Slick.Editors.Text
				},
				{
					id : "description",
					name : "Description",
					field : "description",
					sortable : true,
					editor : Slick.Editors.Text
				},
				{
					id : "default",
					name : "Default",
					field : "default",
					sortable : true,
					editor : Slick.Editors.Text
				},
				{
					id : "enabled",
					name : "Enabled",
					field : "enabled",
					sortable : true,
					editor : Slick.Editors.Text
				}
			];
		}
		else if (m_objParameters.mode === LIST_PROFILEINSTALLABLE)
		{
			objGridColumns = [
				{
					id : "rownum",
					name : "#",
					field : "rownum",
					behavior : "select",
					cssClass : "gs-gridcell-selection",
					width : 40,
					cannotTriggerInsert : true,
					resizable : false,
					selectable : false
				},
				{
					id : "description",
					name : "Description",
					field : "description",
					sortable : true,
					editor : Slick.Editors.Text
				},
				{
					id : "admin",
					name : "Admin",
					field : "admin",
					sortable : true,
					editor : Slick.Editors.Text
				},
				{
					id : "default",
					name : "Default",
					field : "default",
					sortable : true,
					editor : Slick.Editors.Text
				},
				{
					id : "enabled",
					name : "Enabled",
					field : "enabled",
					sortable : true,
					editor : Slick.Editors.Text
				}
			];
		}

		if (m_objParameters.mode === LIST_FILEFORMATINSTALLABLE)
		{
			populateGrid('import_fileformatinstallablelist', objGridColumns);
		}
		else if (m_objParameters.mode === LIST_PROFILEINSTALLABLE)
		{
			populateGrid('security_profilesinstallablelist', objGridColumns);
		}

		if (!os.hasCapability("mobile"))
		{
			os.element(m_strFormID, '.ge-search-input').focus();
		}
	}

	function populateGrid(strWebService_a, objGridColumns_a, arrParamaters_a)
	{
		var objGridOptions =
		{
			//autoHeight : (os.hasCapability('regionscroll') === false),
			//cbOnFetchData : m_objThis.Grid_onFetchData,
			cbOnSelection : m_objThis.Grid_onDblClick,
			cbOnSelectionChange : m_objThis.Grid_onSelectionChange,
			columns : objGridColumns_a,
			//containerClass : 'gs-cellcontent-1000x500-xxx',
			filterType : 'all',
            parameters : [],
			extraParameters : [],
			//multiSelect : true,
			returnFields : m_arrResultFields,
			//showHeaderRow : true,
			webServiceFunction : strWebService_a
		};

                        
		if (arrParamaters_a !== undefined && arrParamaters_a.length > 0)
		{
            objGridOptions.parameters = $.merge( objGridOptions.parameters, arrParamaters_a );
                                                        
		}
        
		os.element(m_strFormID, '.panelHome').hide();
		os.element(m_strFormID, '.ge-entity-grid').show();

		populateDock();
        
        m_objGrid = new jDatatableRenderer(os, objGridOptions);
        
        m_objGrid.render(m_strFormID, '.ge-entity-grid');
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form,gb-formtitle-inner-panel');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
        
        // more global bindings
		os.unbindEvents(m_strFormID, 'ge-search-input,ge-search-button');

		os.bindEvent(m_objThis, m_strFormID, '.ge-search-button', 'btnSearch', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-search-input', 'SearchField', 'onEnterKey');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_onBroadcast = function (strQueue_a, strMessage_a, objMessageData_a)
	{
		if ((strQueue_a === 'hash') && (strMessage_a === 'change'))
		{
			os.closeForm(m_strFormID); // it's out of scope (likely as a popup, so close it... behaviour can be improved in future)
		}
	};

	// on_click gives form the focus, setup all the tabs
	this.Form_onClick = function ()
	{
		os.setFormFocus(m_objThis, m_strFormID);
	};

	this.Form_onDblClick = function ()
	{
		os.formToFront(m_strFormID);
	};

	this.Form_onFocus = function (objParameters_a)
	{
        if ((objParameters_a !== undefined) && (objParameters_a !== null))
		{
            if ((m_objParameters.resultfields !== objParameters_a.resultfields)) {
            
                m_objParameters = objParameters_a;
            
                if(m_objParameters.resultfields !== undefined && m_objParameters.resultfields.length > 0) { 
                    m_arrResultFields = m_objParameters.resultfields;
                }
            }
        }
        
		setTabOrder();
	};

	this.Form_onLoad = function ()
	{
		initialiseForm();
		populateDock();
		bindGlobals();
		populateForm();
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.FormTitle_onClick = function ()
	{
		os.formToFront(m_strFormID);
	};

	this.Form_onResize = function (intWidth_a, intHeight_a)
	{
		os.element(m_strFormID, '.ge-entity-grid').width((intWidth_a - 20) + 'px');
		os.element(m_strFormID, '.ge-entity-grid').height((intHeight_a - 150) + 'px');
		//os.element(m_strFormID, '.ge-status-panel').css('margin-top', (intHeight_a - 20) + 'px');

		// for datatables scrolling issue
		var osObjGrid = os.element(m_strFormID, '.ge-entity-grid');
		
		m_objGrid.resize();
	};
    
	this.FormResult = function ()
	{
		return m_arrSelection;
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.FormClose_onClick = function ()
	{
		m_arrSelection = [];
		os.closeForm(m_strFormID);
	};

	this.Grid_onSelectionChange = function (arrSelection_a)
	{
		if ((m_objDock !== undefined) && (m_objDock !== null))
		{
			if (arrSelection_a.length > 0)
			{
				m_objDock.enableButtons('SelectButton', 'disabled');
			}
			else
			{
				m_objDock.disableButtons('SelectButton', 'disabled');
			}
		}
	};

	this.Grid_onDblClick = function (arrSelection_a)
	{
		m_objThis.SelectButton_onClick();
	};

	this.Grid_onFetchData = function (objData_a)
	{
		if (objData_a === undefined || objData_a === null || objData_a.length === 0)
		{
			outputStatus('divStatus', 5, (m_intFormHeight - 35), 'No records shown.');
		}
		else
		{
			var gridRowCount = objData_a.length;
			var recordCount = objData_a[0].recordcount;
			var limitedCount = objData_a[0].limited;

			outputStatus('divStatus', 5, (m_intFormHeight - 35), 'Showing ' + gridRowCount + ' of ' + recordCount + ' records&nbsp;(max. ' + limitedCount + ' records are shown at a time).');
		}
	};

	this.SelectButton_onClick = function ()
	{        
		m_arrSelection = m_objGrid.getSelection();        
		os.closeForm(m_strFormID);
	};
    
    this.btnSearch_onClick = function ()
	{

		var strKeyword = os.element(m_strFormID, '.ge-search-input').val();

		m_objGrid.search(strKeyword);
	};

	this.SearchField_onEnterKey = function (objField_a)
	{
		var osObjField = os.element(objField_a);

		var strKeyword = osObjField.val();

		m_objGrid.search(strKeyword);

	};

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,CloseButton,SelectButton,grdEntities,grdEntitiesEOG,ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		os.element(m_strFormID, '.CloseButton').focus();
	};

	this.TabStart_onFocus = function ()
	{
		os.element(m_strFormID, '.SelectButton').focus();
	};
}