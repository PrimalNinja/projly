/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
/*jsl:import ..\..\inc-osutils-jgrids.js*/

/**
 * frmEntityChooser has the same funtionality as entity.frmLister
 * main difference, 
 * frmEntityChooser ist o be called from a parent form and expects to return a selected item via a callback
 * And also uses boostrap datatables grid
 */
function entity_frmEntityChooser(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	var m_strCurrentList = '';
    var m_strFlags = '';
//alert(JSON.stringify(m_objParameters));
	// ------------------------------------------------------------------------------------

	var m_intToFetch = 0;
	var m_intFetched = 0;
	var m_intErrors = 0;
        
	var m_objGridLayout;
	var m_objGridActions;
	var m_objGrid;

	var m_objEntity;

    // ------------------------------------------------------------------------------------

	var m_arrEntityHeaders = [];
	var m_arrOperations = [];
    var m_arrReturnfields = [];
    var m_arrSelection = [];
    
    if(m_objParameters.resultfields !== undefined && m_objParameters.resultfields.length > 0) { 
        m_arrReturnfields = m_objParameters.resultfields;
    }
    
	var m_strOperationCode = m_objParameters.operationcode;
	if (m_strOperationCode === undefined)
	{
		m_strOperationCode = '';
	}

	var m_strEntityCode = m_objParameters.entity;
	if (m_strEntityCode === undefined)
	{
		m_strEntityCode = '';
	}

	var m_strEntityID = m_objParameters.entityid;
	if (m_strEntityID === undefined)
	{
		m_strEntityID = '';
	}

	var m_arrFixedFilter = m_objParameters.fixedfilter;
	if (m_objParameters.fixedfilter === undefined)
	{
		m_arrFixedFilter = [];
	}

	var m_arrPassedFilter = m_objParameters.passedfilter;
	if (m_objParameters.passedfilter === undefined)
	{
		m_arrPassedFilter = [];
	}
	
	var m_strExclusive = m_objParameters.exclusive;
	if (m_strExclusive === undefined)
	{
		m_strExclusive = 'N';
	}

	var m_strFormCode = m_objParameters.formcode;
	if (m_strFormCode === undefined)
	{
		m_strFormCode = '';
	}

	var m_strFormEntityCode = m_objParameters.formentity;
	if (m_strFormEntityCode === undefined)
	{
		m_strFormEntityCode = '';
	}
    
	var m_strFormEntityID = m_objParameters.formentityid;
	if (m_strFormEntityID === undefined)
	{
		m_strFormEntityID = '';
	}

	var m_strMode = m_objParameters.mode;
	if (m_strMode === undefined)
	{
		m_strMode = 'renderer';
	}

	var m_strSearchKeyword = m_objParameters.searchkeyword;
	if (m_strSearchKeyword === undefined)
	{
		m_strSearchKeyword = '';
	}

	var m_strTitle = m_objParameters.title;
	if (m_strTitle === undefined)
	{
		m_strTitle = '';
	}

	var m_strEntityType = m_objParameters.type;
	if (m_strEntityType === undefined)
	{
		m_strEntityType = '';
	}
    
    var m_strRelationship = m_objParameters.relationship;
    if (m_strRelationship === undefined)
	{
		m_strRelationship = '';
	}
    
    var m_strRelationshipID = m_objParameters.relationshipid;
    if (m_strRelationshipID === undefined)
	{
		m_strRelationshipID = '';
	}
    
    var m_strRelativeID = m_objParameters.relativeid;
    if (m_strRelativeID === undefined)
	{
		m_strRelativeID = '';
	}
    
    var m_strRelative = m_objParameters.relative;
    if (m_strRelative === undefined)
	{
		m_strRelative = '';
	}
    
    var m_strRelationshipTitle = m_objParameters.relationshiptitle;
    if (m_strRelationshipTitle === undefined)
	{
		m_strRelationshipTitle = '';
	}
    

	// ------------------------------------------------------------------------------------

	var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : [
				//['CloseButton', 'ADDButton', 'EDTButton', 'DELButton', 'VWButton']
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
			tip : '',
			type : 'blank'
		},

		{
			id : 'CloseButton',
			caption : 'Close',
			classes : '',
			permissions : [],
			action : function ()
			{
				m_objThis.FormClose_onClick();
			},
			tip : 'Click here to close the ' + m_strCurrentList + ' list.',
			type : 'toolbarbutton'
		}

	];

	// map layouts by mode

	var m_arrMapLayouts =
	{
		'mode' :
		{
			'select' : ['CloseButton', 'ENTITYCHOOSERButton'],
			'builder' : ['CloseButton', 'ENTITYCHOOSERButton'],
			'renderer' : ['CloseButton', 'ENTITYCHOOSERButton']
		}
	};

	var m_arrNonDynamicOperations =
	{

		'mode' :
		{
			'select' : [
				{
					code : 'ENTITYCHOOSERSELECT',
					description : 'Select',
					allowmultiple : 'N',
					requiresselection : 'Y',
					permissions : []
				}
			],

			'invoke' : [
				{
					code : m_strOperationCode,
					description : 'Select',
					allowmultiple : 'N',
					requiresselection : 'Y',
					permissions : [m_strOperationCode]
				}
			],

			'deleteformgroup' : [
				{
					code : 'DEL_DFDATA',
					description : 'Delete',
					allowmultiple : 'N',
					requiresselection : 'Y',
					permissions : []
				}
			]
		},

		'entityType' :
		{
			'form' : [
                {
					code : 'LISTITEMS',
					description : 'Choose',
					allowmultiple : 'N',
					requiresselection : 'Y',
					permissions : []
				}

			]
		}

	};

	var m_NonDynamicGridLayoutSettings =
	{

		'columns' : {},		
		'returns' :
		{
			'mode' :
			{
				'select' : ['id'], //['id', 'code', 'formentitycode', 'description'],
				'invoke' : ['id'], //['id', 'code', 'formentitycode', 'description'],
				'deleteformgroup' : ['id', 'code', 'description'],
				'builder' : ['id'],
				'renderer' : ['id']
			}
		}
	};

	// ------------------------------------------------------------------------------------

	//var m_arrGridLayouts = [];


	// ------------------------------------------------------------------------------------

	//var m_arrGridActions = [ ];

	// ====================================================================================
	// HELPERS ============================================================================

    function applyFlags(objGridOptions_a) { 
        
        if(m_strFlags.length > 0) { 
        
            var arrFlags = m_strFlags.split(',');
            
            processArray(arrFlags, function(strFlag_a) { 
                if(strFlag_a === 'nomultiselect') { 
                    objGridOptions_a.multiSelect = false;
                }
                if(strFlag_a === 'multiselect') { 
                    objGridOptions_a.multiSelect = true;
                }
            });
        
        }
        
        return objGridOptions_a;
    }
    
	function checkAction(strFunction_a)
	{
		var blnResult = false;

		processArray(m_objGridActions.actions, function (objAction_a)
		{
			if (objAction_a.id == strFunction_a || objAction_a.operationcode == strFunction_a)
			{
				if ($.isFunction(objAction_a.permissions))
				{
					blnResult = objAction_a.permissions();
				}
				else
				{
					if (objAction_a.permissions.length === 0)
					{
						// empty permissions means full permissions
						blnResult = true;
					}
					else
					{
						blnResult = hasPermission(objAction_a.permissions);
					}
				}
			}

			return blnResult;
		}
		);

		return blnResult;
	}

	function getAction(strFunction_a)
	{
		var objResult;

		processArray(m_objGridActions.actions, function (objAction_a)
		{
			var blnResult = false;

			if (objAction_a.id == strFunction_a || objAction_a.operationcode == strFunction_a)
			{
				objResult = objAction_a;
				blnResult = true;
			}

			return blnResult;
		}
		);

		return objResult;
	}
	
	function invokeAction(strAction_a)
	{
		var varSelection; // can be a string or an array
		var strPrompt = '';

		if (checkAction(strAction_a))
		{
			var objAction = getAction(strAction_a);
			var arrReturns = m_objGridLayout.returns;

			var arrSelection = m_objGrid.getSelection(arrReturns);

			var blnMultiSelect = objAction.multiSelect;

			if (blnMultiSelect === undefined)
			{
				blnMultiSelect = false;
			}

			var blnRequiresSelection = objAction.requiresSelection;

			if (blnRequiresSelection === undefined)
			{
				blnRequiresSelection = true;
			}

			var blnPrompt = objAction.prompt;
			if (blnPrompt === undefined)
			{
				blnPrompt = false;
			}

			// create the prompt
			if (blnPrompt)
			{
				if (strAction_a === 'DeleteButton' || strAction_a === 'DELETE' || strAction_a === 'DEL')
				{
					strPrompt = 'Continue and delete the ' + m_objGridLayout.title + ' selection?';
				}
			}

			if (blnRequiresSelection)
			{
//alert('1:' + JSON.stringify(objAction));
				if (arrSelection.length > 0)
				{
					// functions such as view, edit, delete
					// get the selection
					if (blnMultiSelect)
					{
						varSelection = [];
						processArray(arrSelection, function (objRow_a)
						{
							if (arrReturns === undefined)
							{
								varSelection.push(objRow_a.id);
							}
							else
							{
								varSelection.push(objRow_a);
							}
						}
						);
					}
					else
					{
						if (arrReturns === undefined)
						{
							varSelection = arrSelection[0].id;
						}
						else
						{
							varSelection = arrSelection[0];
						}
					}

					// invoke the action
					if (varSelection !== undefined)
					{
						if ($.isFunction(objAction.action))
						{
							if (strPrompt.length > 0)
							{
								os.dialogConfirm(strPrompt, function ()
								{
									objAction.action(strAction_a, varSelection, objAction);
								}
								);
							}
							else
							{
								objAction.action(strAction_a, varSelection, objAction);
							}
						}
					}
				}
			}
			else
			{
//alert('2:' + JSON.stringify(objAction));
				// functions such as add, just invoke the action
				if ($.isFunction(objAction.action))
				{

					if (strPrompt.length > 0)
					{
						os.dialogConfirm(strPrompt, function ()
						{
							objAction.action(strAction_a, null, objAction);
						}
						);
					}
					else
					{
						objAction.action(strAction_a, null, objAction);
					}
				}
			}
//alert('3:' + JSON.stringify(objAction));
		}
	}

	function getRequireSelectionButtons()
	{

		var arrRequireSelectionButtons = [];

		processArray(m_arrOperations, function (objOperation_a)
		{

			if (os.toBoolean(objOperation_a.requiresselection))
			{

				var strCode = objOperation_a.code;

				var strButton = strCode.split('_')[0] + 'Button';

				arrRequireSelectionButtons.push(strButton);
			}
		}
		);

		return arrRequireSelectionButtons.join(',');
	}

	function setGridLayout()
	{

		var strWs = null;

		if (m_strMode === 'deleteformgroup')
		{
			strWs = 'entity_dataformentitylist';
		}
		else if (m_strEntityType === 'form')
		{
			strWs = 'entity_formdatalist'; // PARAMETERS: entitycode, formentitycode, order (o), filter (o), offset (o)
		}
		else if (m_strEntityType === 'entity')
		{
			strWs = 'entity_entitydatalist'; // PARAMETERS: entitycode, order(o), filter (o), offset (o)
		}

		m_objGridLayout =
		{
			id : m_strCurrentList,
			ws : strWs,
			params : [
				{
					"name" : 'entitycode',
					"value" : m_strEntityCode
				},
                { 
                    "name" : 'formentityid',
                    "value" : m_strFormEntityID
                },
				{
					"name" : 'formentitycode',
					"value" : m_strFormEntityCode
				},
				{
					"name" : 'entityid', // REMOVE THIS PARAMETER?
					"value" : m_strEntityID
				},
				{
					"name" : 'order',
					"value" : ""
				},
				{
					"name" : 'filter',
					"value" : ""
				},
				{
					"name" : 'fixedfilter',
					"value" : m_arrFixedFilter
				},
				{
					"name" : 'passedfilter',
					"value" : m_arrPassedFilter
				},
				{
					"name" : 'exclusive',
					"value" : m_strExclusive
				},
                {
                    "name" : "relationship",
                    value : m_strRelationship                
                },
                {
                    "name" : "relativeid",
                    "value" : m_strRelativeID                
                },
                {
                    "name" : "relative",
                    "value" : m_strRelative
                },
				{
					"name" : 'offset',
					"value" : 0
				},
                { 
                    "name" : 'limit',
                    "value" : 0
                }
			],
			columns : [],
			returns : ['id'],
			title : m_strTitle
		};

	}

	// operations are based on permissions

	function setNonDynamicOperations()
	{
		
		if (m_strMode === 'deleteformgroup')
		{
			m_arrOperations = m_arrNonDynamicOperations.mode.deleteformgroup;
		}
		else if (m_strMode === 'invoke')
		{
			m_arrOperations = m_arrNonDynamicOperations.mode.invoke;
		}
		else
		{
			//m_arrOperations = m_arrNonDynamicOperations.entityType.form;            
            m_arrOperations = m_arrNonDynamicOperations.mode.select;

		}

		setGridAction();

		// lets call async. since this function is also invoked in fetchData()
		asyncDataIsFetched();
	}

	function setNonDynamicEntityHeaders()
	{

		m_objGridLayout.columns = m_NonDynamicGridLayoutSettings.columns.mode[m_strMode];
		//m_objGridLayout.returns = m_NonDynamicGridLayoutSettings.returns.mode[m_strMode];
        
        if(m_arrReturnfields.length > 0) { 
            m_objGridLayout.returns = m_arrReturnfields;
        }
        else {
            m_objGridLayout.returns = m_NonDynamicGridLayoutSettings.returns.mode[m_strMode];
        }

		// lets call async. since this function is also invoked in fetchData()
		asyncDataIsFetched();
	}

	function setGridAction()
	{

		var arrActions = [];
		var objAction = '';
		var objTile = '';

		var arrMapButtons = ['CloseButton'];

		//m_arrMap[0].layout[0] = m_arrMapLayouts.mode[m_strMode];

		processArray(m_arrOperations, function (objOperation_a)
		{

			var strOperationCode = objOperation_a.code;
			var strOperationActionCode = strOperationCode.split('_')[0];
            
            var strActionButtonID = strOperationCode + 'Button';

			var strPermissions = null;

			if (objOperation_a.permissions !== undefined)
			{
				strPermissions = objOperation_a.permissions;
			}
			else
			{
				strPermissions = []; //[objOperation_a.code];
			}
			// add tile to arrMap see - m_arrMap[0].layout[0])

			arrMapButtons.push(strActionButtonID);

			objAction =
			{
				id : strActionButtonID,
				permissions : strPermissions,
				multiSelect : os.toBoolean(objOperation_a.allowmultiple),
				requiresSelection : os.toBoolean(objOperation_a.requiresselection),
				//isinternal : os.toBoolean(objOperation_a.isinternal),
				prompt : true,
				operation : strOperationActionCode,
				operationcode : strOperationCode,

				action : function (strOperation_a, argSelection_a, objAction_a)
				{

					var strID = '';
					var strFormEntityCode = '';
					var strTitle = m_strTitle;

					switch (strOperation_a)
					{
					
					case 'ENTITYCHOOSERSELECT':
                                                
                        //m_arrSelection = argSelection_a;                        
						m_objThis.SelectButton_onClick();

						break;

					default: // all undefind actions will goes here. including custom actions/operations

						//var blnIsinternal = os.toBoolean(objAction_a.isinternal);
						var strOperationCode = objAction_a.operationcode;
						// check if operation is internal

						strID = '';

						if (argSelection_a !== undefined && argSelection_a !== null)
						{

							if ($.isArray(argSelection_a))
							{
								strID = argSelection_a[0].id;
							}
							else
							{
								strID = argSelection_a.id;
							}
						}

						//if(!blnIsinternal) {
						executeCustomOperation(strOperationCode, strID);
						//}


						break;

					}
				}
			};
//alert(JSON.stringify(objAction));

			// also add the operations to tiles
			objTile =
			{
				id : strActionButtonID,
				caption : objOperation_a.description,
				classes : '',
				permissions : function ()
				{
					return checkAction(strActionButtonID);
				},
				action : invokeAction,
				actionData : strOperationCode,
				tip : 'Click here to ' + objOperation_a.description + '.',
				type : 'toolbarbutton'
			};

			arrActions.push(objAction);
			m_arrTiles.push(objTile);

		}
		);

		m_objGridActions =
		{
			id : m_strCurrentList,
			actions : arrActions,
			broadcasts : [
				{
					messages : ['added', 'deleted', 'edited'],
					action : 'refresh'
				}
			]
		};

		// now add the operations button to arrMap
		m_arrMap[0].layout[0] = arrMapButtons;

	}

	// ====================================================================================
	// POPULATING =========================================================================

	function populateForm()
	{

		populateDock();

		populateGrid();

		var strTitle = '';
		if (m_strTitle.length > 0)
		{
			strTitle = m_strTitle;
		}
		else
		{
			strTitle = m_objGridLayout.title;
		}

		os.element(m_strFormID, '.ge-form-title').text(strTitle);

		if (!os.hasCapability("mobile"))
		{
			os.element(m_strFormID, '.ge-search-input').focus();
		}
	}

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

		m_objDock.disableButtons(getRequireSelectionButtons(), 'disabled');

		//m_objDock.enableButtons('NewButton,', 'gs-darkblue-background-colour gs-glow-focusborder');
		//m_objDock.enableButtons('NewButton');

	}

	function populateGrid()
	{

		var objGridOptions =
		{
			cbOnSelection : m_objThis.Grid_onDblClick,
			cbOnSelectionChange : m_objThis.Grid_onSelectionChange,
			columns : m_objGridLayout.columns,
            filterType : 'string',
            parameters : m_objGridLayout.params,
			extraParameters : [],
			//multiSelect : true,
			returnFields : m_objGridLayout.returns,
			webServiceFunction : m_objGridLayout.ws            
		};
        
        objGridOptions = applyFlags(objGridOptions);
        
        m_objGrid = new jDatatableRenderer(os, objGridOptions);
        
        m_objGrid.render(m_strFormID, '.ge-entity-grid');
                

	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function asyncError()
	{
		if (m_intErrors === 0)
		{
			os.ajaxError();
		}
		m_intErrors++;
	}

	function asyncDataIsFetched()
	{
		m_intFetched++;

		if (m_intToFetch == m_intFetched)
		{
			populateForm();
		}
	}

	// not used
	function closed(objResponse_a)
	{
		m_objGrid.render(m_strFormID, '.ge-entity-grid');

		//os.dialogAlert('Completed closing of ' + m_objGridLayout.title + '.', function ()
		//{
			os.broadcast(m_strFormID, 'entity', m_strCurrentList + 'closed');
		//}
		//);
	}

	function deleted(objResponse_a)
	{
		//m_objGrid.render(m_strFormID, '.ge-entity-grid');
		m_objGrid.refresh();

		//os.dialogAlert('Completed deletion of ' + m_objGridLayout.title + '.', function ()
		//{
			os.broadcast(m_strFormID, 'entity', m_strCurrentList + 'deleted');
		//}
		//);
	}

	function opened(objResponse_a)
	{
		m_objGrid.render(m_strFormID, '.ge-entity-grid');
		//m_objGrid.refresh();
		//os.dialogAlert('Completed opening of ' + m_objGridLayout.title + '.', function ()
		//{
			os.broadcast(m_strFormID, 'entity', m_strCurrentList + 'opened');
		//}
		//);
	}

	function entityHeadersFetched(objResponse_a)
	{

        m_arrEntityHeaders = objResponse_a.fields;
        m_strFlags = objResponse_a.flags;
        
		m_objGridLayout.columns = m_arrEntityHeaders;
        
        if(m_arrReturnfields.length > 0) { 
            m_objGridLayout.returns = m_arrReturnfields;
        }
        else {
            m_objGridLayout.returns = m_NonDynamicGridLayoutSettings.returns.mode[m_strMode];
        }

		asyncDataIsFetched();
	}

	function operationsFetched(objResponse_a)
	{

		m_arrOperations = objResponse_a;

		setGridAction();

		asyncDataIsFetched();
	}

	function publishedDataFormAdded()
	{
		m_objGrid.refresh();
	}

	function customOperationExecuted(objResponse_a)
	{
		m_objGrid.refresh();
		//doNothing();
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================


	function fetchData(blnFetch_a)
	{

		if (blnFetch_a)
		{
			m_intToFetch = 2;
			m_intFetched = 0;
			m_intErrors = 0;

			//fetchEntity();

			// this need to refactored for a simpler condition
			                
            fetchEntityHeaders();
            
            setNonDynamicOperations();
                        
		}
		else
		{
			m_intToFetch = 1;
			m_intFetched = 1;
			m_intErrors = 0;

			populateForm();
		}
	}

	function executeCustomOperation(strOperationCode_a, strID_a)
	{

		var objJSON = os.ajaxRequestCreate('entity_customoperationexecute', [
					{
						name : 'entitycode',
						value : m_strEntityCode
					},
					{
						name : 'id',
						value : strID_a
					},
					{
						name : 'operationcode',
						value : strOperationCode_a
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, customOperationExecuted, os.ajaxError);

	}

	function fetchEntityHeaders()
	{

		var strFunction = '';

		if (m_strEntityType === 'form')
		{
			strFunction = 'entity_formdataheadersfetchbyentitycode';
		}
		else if (m_strEntityType === 'entity')
		{
			strFunction = 'entity_entitydataheadersfetchbyentitycode';
		}

		var objJSON = os.ajaxRequestCreate(strFunction, [
					{
						name : 'entitycode',
						value : m_strEntityCode
					},
					{
						name : 'formentitycode',
						value : m_strFormEntityCode
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, entityHeadersFetched, os.ajaxError);
	}

	function fetchOperations()
	{

		var strEntityCode = '';

		if (m_strFormEntityCode.length > 0)
			strEntityCode = m_strFormEntityCode;
		else
			strEntityCode = m_strEntityCode;

		var objJSON = os.ajaxRequestCreate('entity_entityoperationsfetchbyentitycode', [
					{
						name : 'entitycode',
						value : strEntityCode
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, operationsFetched, os.ajaxError);
	}

	function delete1(strID_a)
	{
		var strFunction = '';
		var strDataID = '';
		var strID = '';
		var strFormEntityCode = '';

		if (m_strEntityType === 'form')
		{
			strFunction = 'entity_formdatadelete'; // PARAMETERS: entitycode, formentitycode, formentitydataid
			strDataID = 'formentitydataid';
		}
		else if (m_strEntityType === 'entity')
		{
			strFunction = 'entity_entitydatadelete'; // PARAMETERS: entitycode, entitydataid
			strDataID = 'entitydataid';
		}

		strFormEntityCode = m_strFormEntityCode;

		var arrParams = [

			{
				name : 'entitycode',
				value : m_strEntityCode
			},
			{
				name : 'formentitycode',
				value : strFormEntityCode
			},
			{
				name : 'entityid', // REMOVE THIS PARAMETER
				value : m_strEntityID
			},
			{
				name : strDataID,
				value : strID_a
			}
		];

		var objJSON = os.ajaxRequestCreate(strFunction, arrParams);

		os.ajaxCall(URL_WEBSERVICE, objJSON, deleted, os.ajaxError);
	}

	function open1(strFunction_a, strType_a, strFilterType_a, strID_a)
	{
		var objJSON = os.ajaxRequestCreate(strFunction_a,
				[
					{
						"name" : "type",
						"value" : strType_a
					},
					{
						"name" : "field",
						"value" : strFilterType_a
					},
					{
						"name" : "id",
						"value" : strID_a
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, opened, os.ajaxError);
	}

	function preview1(strForm_a, strParameters_a)
	{
		os.openTab(strForm_a, strParameters_a);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// defaulting
		os.element(m_strFormID, '.ge-form-title').text('');

		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form,gb-formtitle-inner-panel');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');

		// more global bindings
		os.unbindEvents(m_strFormID, 'ge-search-input,ge-searchclear-button');

		os.bindEvent(m_objThis, m_strFormID, '.ge-searchclear-button', 'btnSearchClear', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-search-input', 'SearchField', 'onEnterKey');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_onBroadcast = function (strQueue_a, strMessage_a, objMessageData_a)
	{
		//if ((strQueue_a === 'viewport') && (strMessage_a === 'change'))
		//{
			//os.resizeDockedForm(m_strFormID);		
            
			// recalibrate the grid here
			//m_objGrid.resize();
		//}
		//else
		//{
			// the action can be a function or a string
			var varAction;

			processArray(m_objGridActions.broadcasts, function (objBroadcast_a)
			{
				processArray(objBroadcast_a.messages, function (strToFind_a)
				{
					// first look for broadcasts constructed such as <listid>+<action>
					var strMessageToFind = m_strCurrentList + strToFind_a;
					if (strMessageToFind.toLowerCase() == strMessage_a.toLowerCase())
					{
						varAction = objBroadcast_a.action;
					}
					else
					{
						// then look for ones of the literal whole name which should have been constructed using <entityid>+<action>
						strMessageToFind = strToFind_a;
						if (strMessageToFind.toLowerCase() == strMessage_a.toLowerCase())
						{
							varAction = objBroadcast_a.action;
						}
					}
					return varAction !== undefined;
				}
				);

				return varAction !== undefined;
			}
			);

			if (varAction !== undefined)
			{
				if ($.isFunction(varAction))
				{
					varAction();
				}
				else if (varAction === 'refresh')
				{
					//m_objGrid.render(m_strFormID, '.ge-entity-grid');
					m_objGrid.refresh();
				}
			}
		//}
	};

	// on_click gives form the focus, setup all the tabs
	this.Form_onClick = function ()
	{
		os.setFormFocus(m_objThis, m_strFormID);
	};

	this.Form_onDblClick = function (objThis_a, objElement_a, objEvent_a)
	{
		os.formToFront(m_strFormID);

		objEvent_a.stopPropagation();
		os.zoomFormInOut(m_strFormID);
	};

	this.Form_onFocus = function (objParameters_a)
	{
		if ((objParameters_a !== undefined) && (objParameters_a !== null))
		{
            
            
                
			if ((m_objParameters.entity !== objParameters_a.entity) ||
				(m_objParameters.entityid !== objParameters_a.entityid) ||
				(m_objParameters.fixedfilter !== objParameters_a.fixedfilter) ||
				(m_objParameters.exclusive !== objParameters_a.exclusive) ||
				(m_objParameters.formcode !== objParameters_a.formcode) ||
				(m_objParameters.formentity !== objParameters_a.formentity) ||
				(m_objParameters.formentityid !== objParameters_a.formentityid) ||
				(m_objParameters.mode !== objParameters_a.mode) ||
				(m_objParameters.searchkeyword !== objParameters_a.searchkeyword) ||
				(m_objParameters.title !== objParameters_a.title) ||
                (m_objParameters.title !== objParameters_a.title) ||
                (m_objParameters.resultfields !== objParameters_a.resultfields) ||
				(m_objParameters.type !== objParameters_a.type))
			{
				m_objParameters = objParameters_a;
                
                if(m_objParameters.resultfields !== undefined && m_objParameters.resultfields.length > 0) { 
                    m_arrReturnfields = m_objParameters.resultfields;
                }
            
				m_strEntityCode = m_objParameters.entity;
				if (m_strEntityCode === undefined)
				{
					m_strEntityCode = '';
				}

				m_strEntityID = m_objParameters.entityid;
				if (m_strEntityID === undefined)
				{
					m_strEntityID = '';
				}

				m_arrFixedFilter = m_objParameters.fixedfilter;
				if (m_objParameters.fixedfilter === undefined)
				{
					m_arrFixedFilter = [];
				}

				m_arrPassedFilter = m_objParameters.passedfilter;
				if (m_objParameters.passedfilter === undefined)
				{
					m_arrPassedFilter = [];
				}

				m_strExclusive = m_objParameters.exclusive;
				if (m_strExclusive === undefined)
				{
					m_strExclusive = 'N';
				}

				m_strFormCode = m_objParameters.formcode;
				if (m_strFormCode === undefined)
				{
					m_strFormCode = '';
				}

				m_strFormEntityCode = m_objParameters.formentity;
				if (m_strFormEntityCode === undefined)
				{
					m_strFormEntityCode = '';
				}

				m_strFormEntityID = m_objParameters.formentityid;
				if (m_strFormEntityID === undefined)
				{
					m_strFormEntityID = '';
				}

				m_strMode = m_objParameters.mode;
				if (m_strMode === undefined)
				{
					m_strMode = 'renderer';
				}

				m_strSearchKeyword = m_objParameters.searchkeyword;
				if (m_strSearchKeyword === undefined)
				{
					m_strSearchKeyword = '';
				}

				m_strTitle = m_objParameters.title;
				if (m_strTitle === undefined)
				{
					m_strTitle = '';
				}

				m_strEntityType = m_objParameters.type;
				if (m_strEntityType === undefined)
				{
					m_strEntityType = '';
				}
                /*
                m_strRelationship = m_objParameters.relationship;
                if (m_strRelationship === undefined)
                {
                    m_strRelationship = '';
                }
                
                m_strRelationshipID = m_objParameters.relationshipid;
                if (m_strRelationshipID === undefined)
                {
                    m_strRelationshipID = '';
                }

                m_strRelationshipTitle = m_objParameters.relationshiptitle;                
                if (m_strRelationshipTitle === undefined)
                {
                    m_strRelationshipTitle = '';
                }
                */

				// same as Form_onLoad
				m_objThis.Form_onLoad();
				//os.resizeDockedForm(m_strFormID);
			}
		}

		setTabOrder();
	};

	this.Form_onFocusLost = function ()
	{};

	this.Form_onLoad = function ()
	{
		bindGlobals();
		
		// resize the form
		var intViewPortWidth = os.getViewPort().width;
		var intViewPortHeight = os.getViewPort().height;
		os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);

		m_strCurrentList = m_strEntityCode + 'list';
        
		// set mapLayout based on mode but it can be override
		// based on entity permission
		m_arrMap[0].layout[0] = m_arrMapLayouts.mode[m_strMode];

		setGridLayout();

		fetchData(true);
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
    
    this.Grid_onDblClick = function (arrSelection_a)
	{
		if (m_strMode === 'invoke')
		{
			if (checkAction(m_strOperationCode))
			{
				invokeAction(m_strOperationCode);
			}
		}
		else
		{
			if (checkAction('ENTITYCHOOSERSELECT'))
			{
				invokeAction('ENTITYCHOOSERSELECT');
			}
		}

	};
    
    this.Grid_onSelectionChange = function (arrSelection_a)
	{
		if ((m_objDock !== undefined) && (m_objDock !== null))
		{
            if (arrSelection_a.length > 0)
			{
				m_objDock.enableButtons(getRequireSelectionButtons(), 'disabled');
			}
			else
			{
				m_objDock.disableButtons(getRequireSelectionButtons(), 'disabled');
			}
            			
		}
				
	};

	this.btnSearchClear_onClick = function ()
	{
		m_objGrid.search("");
		os.element(m_strFormID, '.ge-search-input').val("");
		os.element(m_strFormID, '.ge-search-input').focus();
	};
    
    this.SearchField_onEnterKey = function (objField_a)
	{
		var osObjField = os.element(objField_a);

		var strKeyword = osObjField.val();

		m_objGrid.search(strKeyword);

	};
    
    this.SelectButton_onClick = function ()
	{
		m_arrSelection = m_objGrid.getSelection();
        
		os.closeForm(m_strFormID);
	};

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		var strMapLayoutButtons = m_arrMap[0].layout[0].join(',');

		os.setTabOrder(m_strFormID, 'ge-tab-start,' + strMapLayoutButtons + ',ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		os.element(m_strFormID, '.CloseButton').focus();
	};

	this.TabStart_onFocus = function ()
	{
		//os.element(m_strFormID, '.').focus();
	};
}