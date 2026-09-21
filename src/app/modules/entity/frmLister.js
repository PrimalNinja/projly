/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
/*jsl:import ..\..\inc-osutils-jgrids.js*/
/*jsl:import ..\..\inc-osutils-notminified.js*/

// TODO: enhance entity.frmLister to cater for data selection as per entity.frmEntityChooser / core.frmRecordChooser 
// TODO: modify invokeOperationInstallProfile to use entity.frmLister instead of core.frmRecordChooser
function entity_frmLister(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	// ------------------------------------------------------------------------------------
    
	var m_FORMFORMBUILDER = 'widgetformbuilder.wgtFormBuilder';
	var m_FORMENTITYCHOOSER = 'entity.frmEntityChooser';
	var m_FORMRECORDCHOOSER = 'core.frmRecordChooser';

	var m_FORMDEFAULTCOMPONENT = 'entity.frmForm';
	var m_FORMDEFAULTLISTER = 'entity.frmLister';

    var m_strCurrentList = 'list';
        
	var m_intToFetch = 0;
	var m_intFetched = 0;
	var m_intErrors = 0;

	var m_objGridLayout;
	var m_objGridActions;
	var m_objGrid;

	var m_arrEntityHeaders = [];
	var m_arrOperations = [];
    var m_blnMultiselect = false;
	
	var m_arrReturns = ['id'];
	var m_arrReturnfields = [];
	var m_arrSelection = [];

	// ------------------------------------------------------------------------------------

	m_arrReturnfields = m_objParameters.resultfields;
    if (m_objParameters.resultfields === undefined) 
	{ 
        m_arrReturnfields = [];
    }
    
	var m_strComponent = m_objParameters.component;
	if (m_strComponent === undefined)
	{
		m_strComponent = '';
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

	var m_strFixedFilter = '';
    var m_arrFixedFilter = [];

	try
	{
		if ((m_objParameters.fixedfilter !== undefined)) 
		{ 
			if ((typeof m_objParameters.fixedfilter === 'string') && (m_objParameters.fixedfilter.length > 0))
			{
				m_strFixedFilter = m_objParameters.fixedfilter;
				m_strFixedFilter = os.str_quotes(m_strFixedFilter);
				m_arrFixedFilter = JSON.parse(m_strFixedFilter);
				m_strFixedFilter = JSON.stringify(m_arrFixedFilter);
			}
			else
			{
				m_arrFixedFilter = m_objParameters.fixedfilter;
				m_strFixedFilter = JSON.stringify(m_arrFixedFilter);
			}
		}
	}
	catch(err)
	{
		doNothing();
	}
	
	var m_blnPassFilter = m_objParameters.passfilter;
	
	if (m_blnPassFilter !== undefined)
	{
		m_blnPassFilter = os.toBoolean(m_blnPassFilter);
	}
	else
	{
		m_blnPassFilter = false;
	}

	var m_strPassedFilter;	// must stay undefined
    var m_arrPassedFilter = [];

	try
	{
//alert(m_objParameters.passedfilter);
		if ((m_objParameters.passedfilter === undefined)) 
		{ 
			if (m_blnPassFilter)
			{
				m_arrPassedFilter = m_objParameters.fixedfilter;
				m_strPassedFilter = JSON.stringify(m_arrFixedFilter);
//alert("FILTER PASSED:" + m_strPassedFilter);
			}
		}
		else
		{
			if ((typeof m_objParameters.passedfilter === 'string') && (m_objParameters.passedfilter.length > 0))
			{
				m_strPassedFilter = m_objParameters.passedfilter;
				m_strPassedFilter = os.str_quotes(m_strPassedFilter);
				m_arrPassedFilter = JSON.parse(m_strPassedFilter);
				m_strPassedFilter = JSON.stringify(m_arrPassedFilter);
			}
			else
			{
				m_arrPassedFilter = m_objParameters.passedfilter;
				m_strPassedFilter = JSON.stringify(m_arrPassedFilter);
			}
		}
	}
	catch(err)
	{
		doNothing();
	}
	
	var m_strSearchFields = '';
    var m_arrSearchFields = [];

	try
	{
		if ((m_objParameters.searchfields !== undefined)) 
		{ 
			if ((typeof m_objParameters.searchfields === 'string') && (m_objParameters.searchfields.length > 0))
			{
				m_strSearchFields = m_objParameters.searchfields;
				m_strSearchFields = os.str_quotes(m_strSearchFields);
				m_arrSearchFields = JSON.parse(m_strSearchFields);
				m_strSearchFields = JSON.stringify(m_arrSearchFields);
			}
			else
			{
				m_arrSearchFields = m_objParameters.searchfields;
				m_strSearchFields = JSON.stringify(m_arrSearchFields);
			}
		}
	}
	catch(err)
	{
		doNothing();
	}
	
	var m_blnEnableBranches = os.toBoolean(os.getProperty('enablebranches'));
	var m_strBranchID = '';
	var m_strBranchName = '';

	if (m_blnEnableBranches)
	{
		m_strBranchID = m_objParameters.branchid;
		if (m_strBranchID === undefined)
		{
			m_strBranchID = '';
		}

		m_strBranchName = m_objParameters.branchname;
		if (m_strBranchName === undefined)
		{
			m_strBranchName = '';
		}
		
		if (m_strBranchID.length > 0)
		{
			branchFixedFilter();
		}
	}
	
	//alert('1:' + m_strFixedFilter);
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
    
    m_strCurrentList = m_strFormEntityCode + m_strCurrentList;
    
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
	
	var m_strDispatchDateFrom = "";
	var m_strDispatchDateTo = "";
	var m_strReceiverState = "";

	var m_strTitleTemplate = m_objParameters.title;
	if (m_strTitleTemplate === undefined)
	{
		m_strTitleTemplate = '';
	}
	var m_strTitle = m_strTitleTemplate;
	m_strTitle = insertBranchName(m_strTitleTemplate);
	
	var m_strFormEntityDescription = m_objParameters.formentitydescription; // used for sub forms ADD mode mainly
	if (m_strFormEntityDescription === undefined)
	{
		m_strFormEntityDescription = m_strTitle;
	}

	var m_blnUseLatestForm = m_objParameters.uselatestform;
	if (m_blnUseLatestForm === undefined)
	{
		m_blnUseLatestForm = true;
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
    
    var m_strFlags = m_objParameters.flags;
    if (m_strFlags === undefined)
	{
		m_strFlags = '';
	}
    
    var m_strHideOperations = m_objParameters.hideoperations;
    var m_arrHideOperations = [];

	if (m_strHideOperations === '\"\"')
	{
		m_strHideOperations = "";
	}
	
    if ((m_strHideOperations === undefined)) 
	{
		m_strHideOperations = '';
		m_arrHideOperations = [];
	}
	else
	{ 
		if ((typeof m_strHideOperations === 'string') && (m_strHideOperations.length > 0))
		{
			//m_strHideOperations = m_strHideOperations;
			m_strHideOperations = os.str_quotes(m_strHideOperations);
            
			m_arrHideOperations = JSON.parse(m_strHideOperations);
			m_strHideOperations = JSON.stringify(m_arrHideOperations);
		}
		else
		{
			m_arrHideOperations = m_strHideOperations;
			m_strHideOperations = JSON.stringify(m_arrHideOperations);
		}
	}
	
	var m_blnReorder = m_objParameters.reorder;
	
	if(m_blnReorder !== undefined)
	{
		m_blnReorder = os.toBoolean(m_blnReorder);
	}
	else
	{
		m_blnReorder = false;
	}

	// for rowreorder
	var m_strReorderSourceID = '';
	var m_strReorderDestinationID = '';

	//for MyDevice
	var m_myDeviceRepaintJDock = false;

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
			classes : 'btn-success',
			permissions : [],
			action : function ()
			{
				m_objThis.FormClose_onClick();
			},
			tip : 'Click here to close the ' + m_objParameters.formentitydescription + ' list.',
			type : 'toolbarbutton'
		},
		{
			id : 'BranchButton',
			caption : 'Branches',
			classes : 'btn-primary',
			permissions : ['MYBRANCHSELECT'],
			action : actionShowEntityChooser,
			actionData : { type:'form', entity:'systemform', formentity:'BRANCH', mode:'select', title:'Branch Selection', operation:'mybranchget' },
			tip : 'Click here to change the branch for this ' + m_objParameters.formentitydescription + ' list.',
			type : 'toolbarbutton'
		},
		// {
			// id : 'ClearSelectionButton',
			// caption : 'Clear Selection',
			// classes : 'btn-primary',
			// permissions : [],
			// action : function ()
			// {
				// m_objThis.FormClearSelection_onClick();
			// },
			// tip : 'Click here to clear the ' + m_strCurrentList + ' selection.',
			// type : 'toolbarbutton'
		// },
		{
			id : 'MyDeviceButton',
			caption : 'My Devices',
			classes : 'btn-primary',
			permissions : function ()
			{
				return false;
				
			},
			action : function ()
			{
				return false;
				
			},
			actionDataList : [], // this is populated in myDevicesFetched
			tip : 'Click here to select device',
			type : 'toolbardevicebutton'
		}

	];

	// map layouts by mode

	var m_arrMapLayouts =
	{
		'mode' :
		{
			'select' : ['CloseButton', 'SELECTButton'],											// for listing forms, hardcoded map
			'listitems' : ['CloseButton', 'LISTITEMSButton'],											// for listing forms, hardcoded map
			'builder' : ['CloseButton', 'ADDButton', 'EDTButton', 'DELButton', 'VWButton'],			// for listing templates, hardcoded map
			'renderer' : ['CloseButton', 'ClearSelectionButton', 'ADDButton', 'EDTButton', 'DELButton', 'VWButton', 'MyDeviceButton'],		// for listing data, hardcoded map WHY?  TODO WHY ARE THESE BUTTONS HARDCODED?
			'deleteformgroup' : ['CloseButton', 'DELButton']										// special data maintenance, hardcoded map
		}
	};

	// ====================================================================================
	// NON-DYNAMIC GRIDS ==================================================================

	// operations for the hardcoded maps above
	var m_arrNonDynamicOperations =
	{

		'mode' :
		{
            
			'select' : [
				{
					code : 'SELECT',
					description : 'Select',
					allowmultiple : 'N',
					requiresselection : 'Y',
					permissions : [],
                    prompt : ''
				}
            ],

            'listitems' : [
				{
					code : 'LISTITEMS',
					description : 'List Forms',
					allowmultiple : 'N',
					requiresselection : 'Y',
					permissions : [],
                    prompt : ''
				}
            ],
                        
			'deleteformgroup' : [
				{
					code : 'DEL_DFDATA',
					description : 'Permanently Delete',
					allowmultiple : 'N',
					requiresselection : 'Y',
					permissions : [],
                    prompt : 'Are you sure you want to permanently delete the data?<br />YOU WILL LOSE ALL DATA INCLUDING THE FORM TEMPLATE.'
				},
                {
					code : 'DELDATA_DFDATA',
					description : 'Delete All Data',
					allowmultiple : 'N',
					requiresselection : 'Y',
					permissions : [],
                    prompt : 'Are you sure you want to proceed?<br />This will clear all form submission for this form tempalate'
				}
			]
		},

		'entityType' :
		{
			'form' : [
				{
					code : 'ADD_DFDATA',
					description : 'Add',
					allowmultiple : 'Y',
					requiresselection : 'N',
                    prompt : ''
				},
				{
					code : 'EDT_DFDATA',
					description : 'Edit',
					allowmultiple : 'N',
					requiresselection : 'Y',
                    prompt : ''

				},
				{
					code : 'DEL_DFDATA',
					description : 'Delete',
					allowmultiple : 'Y',
					requiresselection : 'Y',
                    prompt : ''
				},
				{
					code : 'VW_DFDATA',
					description : 'View',
					allowmultiple : 'N',
					requiresselection : 'Y',
                    prompt : ''
				},
				{
					code : 'EXP_DFDATA',
					description : 'Export',
					allowmultiple : 'N',
					requiresselection : 'Y',
                    prompt : ''
				}

			]
		}

	};

	// grid layouts for the hardcoded maps above
	var m_NonDynamicGridLayoutSettings =
	{

		'columns' :
		{
			'mode' :
			{

				'select' : [
					{
						"id" : "rownum",
						"field" : "rownum",
						"name" : "#",
						"sortable" : "N"
					},
					{
						"id" : "code",
						"field" : "code",
						"name" : "Code", // formerly Data Group Name
						"sortable" : "Y"
					},										
					{
						"id" : "description",
						"field" : "description",
						"name" : "Description",
						"sortable" : "Y"
					}
                    
                   /*	JC description is currently confusing as it is currently taken from the form name when the dataformentity is first created but never updated ,                   
					{
						"id" : "isenabled",
						"field" : "isenabled",
						"name" : "Enabled",
						"sortable" : "N"
					}
                    */
				],

				'deleteformgroup' : [
					{
						"id" : "rownum",
						"field" : "rownum",
						"name" : "#",
						"sortable" : "N"
					},
					{
						"id" : "description",
						"field" : "description",
						"name" : "Description",
						"sortable" : "Y"
					},
                    {
						"id" : "modifydatetime",
						"field" : "modifydatetime",
						"name" : "Created",
						"sortable" : "Y"
					}
					/*	JC description is currently confusing as it is currently taken from the form name when the dataformentity is first created but never updated
					,
					{
						"id" : "description",
						"field" : "description",
						"name" : "Description",
						"sortable" : "Y"
					}
					*/
				]
			}
		},

		'returns' :
		{
			'mode' :
			{
				'select' : ['id', 'code', 'formentitycode', 'description'],
				'deleteformgroup' : ['id', 'code', 'formentitycode', 'description'],
				'builder' : ['id', 'code', 'description'],
				'renderer' : m_arrReturns
			}
		}
	};

	// ------------------------------------------------------------------------------------

	function branchFixedFilter()
	{
		var blnFound = false;
		processArray(m_arrFixedFilter, function(obj_a)
		{
			if (obj_a.field == 'branch_id')
			{
				obj_a.value = m_strBranchID;
				blnFound = true;
			}
		});
		
		if (!blnFound)
		{
			m_arrFixedFilter.push({"field":"branch_id","value":m_strBranchID});
		}

		m_strFixedFilter = JSON.stringify(m_arrFixedFilter);
	}
	
	// returns strFixedFilter1_a + strFixedFilter2_a
	function mergeFixedFilter(strFixedFilter1_a, strFixedFilter2_a)
	{
		var blnFound = false;
		var arrFixedFilter1 = JSON.parse(strFixedFilter1_a);
		var arrFixedFilter2 = JSON.parse(strFixedFilter2_a);
		
		processArray(arrFixedFilter2, function(objFilter2_a)
		{
			blnFound = false;
			processArray(arrFixedFilter1, function(objFilter1_a)
			{
				if (objFilter1_a.field === objFilter2_a.field)
				{
					blnFound = true;
					return true;
				}
			});
			
			if (!blnFound)
			{
				arrFixedFilter1.push(objFilter2_a);
			}
		});
		
		return JSON.stringify(arrFixedFilter1);
	}
	
	function initialiseNonDynamicEntityHeaders()
	{
		m_objGridLayout.columns = m_NonDynamicGridLayoutSettings.columns.mode[m_strMode];
		m_objGridLayout.returns = m_NonDynamicGridLayoutSettings.returns.mode[m_strMode];
//alert('initialiseNonDynamicEntityHeaders:' + m_objGridLayout.returns);

		// lets call async. since this function is also invoked in fetchData()
		asyncDataIsFetched();
	}

	function initialiseNonDynamicOperations()
	{
		if (m_strMode === 'select')
		{
			m_arrOperations = m_arrNonDynamicOperations.mode.select;
		}
		else if (m_strMode === 'deleteformgroup')
		{
			m_arrOperations = m_arrNonDynamicOperations.mode.deleteformgroup;
		}
		else if (m_strEntityType === 'form')
		{
			m_arrOperations = m_arrNonDynamicOperations.entityType.form;
		}

		//initialiseReturns();
		initialiseGridLayout();
		initialiseGridActions();  

		// lets call async. since this function is also invoked in fetchData()
		asyncDataIsFetched();
	}

	// ====================================================================================
	// HELPERS ============================================================================

    function applyFlags(objGridOptions_a) 
	{ 
		// defaults
		m_blnMultiselect = objGridOptions_a.multiSelect;

		if(m_strFlags.length > 0) 
		{ 
            var arrFlags = m_strFlags.split(',');
            
            processArray(arrFlags, function(strFlag_a) 
			{ 
                if(strFlag_a === 'multiselect') 
				{ 
                    m_blnMultiselect = true;
                }
                
                if(strFlag_a === 'nomultiselect') 
				{ 
                    m_blnMultiselect = false;
                }
                
            });
        }
		
		objGridOptions_a.multiSelect = m_blnMultiselect;
        
        return objGridOptions_a;
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
    
	function getRequireSelectionButtons()
	{

		var arrRequireSelectionButtons = [];

		processArray(m_arrOperations, function (objOperation_a)
		{

			if (os.toBoolean(objOperation_a.requiresselection))
			{

				var strCode = objOperation_a.code;
				var strButton = strCode + 'Button';

				if (strCode === 'PUB_DATAFORM')
				{
					doNothing();
				}
				else
				{
					arrRequireSelectionButtons.push(strButton);
				}
			}
		}
		);

		return arrRequireSelectionButtons.join(',');
	}

	function hasOperationPermission(strFunction_a)
	{
		var blnResult = false;
        
		processArray(m_objGridActions.actions, function (objAction_a)
		{   
            //alert([objAction_a.id, objAction_a.operationcode, strFunction_a]);
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

	function initialiseGridLayout()
	{
		var strFunction = '';

		if ((m_strMode === 'deleteformgroup')) // || (m_strMode === 'select'))			// JC Temporarily disabled because Add doesn't know what form to add
		{
			strFunction = 'entity_dataformentitylist';
            //strFunction = 'entity_entitydatalist'; // PARAMETERS: entitycode, order(o), filter (o), offset (o)
		}
		else if (m_strEntityType === 'form')
		{
			strFunction = 'entity_formdatalist'; // PARAMETERS: entitycode, formentitycode, order (o), filter (o), offset (o)
		}
		else if (m_strEntityType === 'entity')
		{
			strFunction = 'entity_entitydatalist'; // PARAMETERS: entitycode, order(o), filter (o), offset (o)
		}

		m_objGridLayout =
		{
			id : m_strCurrentList,
			ws : strFunction,
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
					"value" : m_strFixedFilter
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
			columns : [], //m_arrEntityHeaders,
			returns : m_arrReturns,
			title : m_strTitle
		};
		//alert(JSON.stringify(m_arrReturns));
	}

	function initialiseReturns()
	{
		// setup the idfields based on all the operations parameters
		m_arrReturns = ['id', 'code', 'description'];

		processArray(m_arrOperations, function(objOperation_a)
		{
			var strParameters = objOperation_a.parameters;
			if (strParameters.length > 0)
			{
				strParameters = str_replace(strParameters, "%", "__");
				var objParameters = os.urlToJSON(strParameters);

				var strIDField = objParameters.idfield;
				if (strIDField !== undefined)
				{
					m_arrReturns.push(strIDField);
				}

				var strDescriptionField = objParameters.descriptionfield;
				if (strDescriptionField !== undefined)
				{
					m_arrReturns.push(strDescriptionField);
				}

				var strTitleFieldField = objParameters.titlefield;
				if (strTitleFieldField !== undefined)
				{
					m_arrReturns.push(strTitleFieldField);
				}
			}
		});
		
		// TODO: append m_arrReturnfields to m_arrReturns;
	}

	function insertBranchName(str_a)
	{
		var str = str_a;
		var strBranchName = '';
		var strResult = '';
		
		if (m_strBranchName.length > 0)
		{
			strBranchName = m_strBranchName + ' - ';
		}
		else
		{
			strBranchName = '';
		}
		
		strResult = str_replace(str, "~BRANCHNAME~", strBranchName);
		
		return strResult;
	}
	
	function invokeOperation(strAction_a)
	{
		var varSelection; // can be a string or an array
		var strPrompt = '';

		if (hasOperationPermission(strAction_a))
		{
			var objAction = getAction(strAction_a);
			var arrReturns = m_objGridLayout.returns;
            
			var arrSelection = m_objGrid.getSelection(arrReturns);
//alert(JSON.stringify(arrReturns));
//alert(JSON.stringify(arrSelection));

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

           
			// create the prompt
            strPrompt = objAction.prompt;
			
			
			
			// TO DELETE IN FUTURE (when all operations are configured correctly)
            if(strPrompt.length === 0) 
			{ 
                
                var strActionFormentityCode = m_strFormEntityCode.toUpperCase();

				//if (strAction_a === 'DeleteButton' || strAction_a === 'DELETE' || strAction_a === 'DEL' || strAction_a === 'DELDATA' || strAction_a === 'DEL_DFDATA' || strAction_a === 'DEL_' + strActionFormentityCode || strAction_a === 'DELDATA_' + strActionFormentityCode || strAction_a === 'DELETEALL_' + strActionFormentityCode)
				if (strAction_a === 'DeleteButton' || strAction_a === 'DELETE' || strAction_a === 'DEL' || strAction_a === 'DELDATA' || strAction_a === 'DEL_DFDATA' || strAction_a === 'DEL_' + strActionFormentityCode || strAction_a === 'DELDATA_' + strActionFormentityCode)
				{
                       
                    if(m_strMode === 'deleteformgroup') 
					{ 
                        if( strAction_a === 'DELDATA' || 'DELDATA_' + strActionFormentityCode) 
						{
                            strPrompt = 'Are you sure you want to delete the selected item(s)?<br /><br />All data including history will be permanently deleted.';
                        } 
						else 
						{
                            strPrompt = 'Are you sure you want to delete the selected item(s)?<br /><br />Form data and template will be permanently deleted.';
                        }
                    }
                    else 
					{ 
                        strPrompt = 'Continue and delete the ' + m_objGridLayout.title + ' selection?';
                    }
				}
                
            }
			// END OF TO DELETE IN FUTURE

            if ($.isFunction(objAction.action))
            {
                if (strPrompt.length > 0)
                {
                    os.dialogConfirm(strPrompt, function ()
                    {
                        objAction.action(arrSelection);
                    }, objAction.description + ' Confirmation');
                }
                else
                {
                    objAction.action(arrSelection);
                }
            }                        			
		}
	}
    
    function isOperationHidden(strOperation_a) 
	{ 
        var blnIsHidden = false;
        
        if(m_arrHideOperations.length > 0) 
		{ 
           processArray(m_arrHideOperations, function(strHideOperation_a) 
		   { 
               if(strOperation_a === strHideOperation_a) 
			   { 
                  blnIsHidden = true;
                  return;
               }
           });
        }
        
                
        return blnIsHidden;
    }

	// ====================================================================================
	// ACTIONS ============================================================================

	// strParameters_a = a string, objParameters_a = a json object
	function invokeAction(strOperationCode_a, strCommand_a, strFlags_a, strParameters_a, objParameters_a, arrSelection_a)
	{
		var strCommand = strCommand_a;
		var strFlags = strFlags_a;
		var strParameters = strParameters_a;	// grab the configured parameters
		strParameters = str_replace(strParameters, "%", "__");	// URLs the % is reserved and some of our URL functions don't like % so change it

		var objParameterDefaults = {};	// defaults are used when a property is not provided in the operation's parameters
		
		objParameterDefaults.branchid = m_strBranchID;
		objParameterDefaults.branchname = m_strBranchName;
		objParameterDefaults.code = undefined;
		objParameterDefaults.component = m_strComponent;
		objParameterDefaults.description = '';
		objParameterDefaults.descriptionfield = 'description';
		objParameterDefaults.entity = m_strEntityCode;
		objParameterDefaults.entitycode = m_strEntityCode;
		objParameterDefaults.entitydataid = '';
		objParameterDefaults.exclusive = m_strExclusive;
		objParameterDefaults.fixedfilter = m_strFixedFilter;
//alert("DEFAULT:" + objParameterDefaults.fixedfilter);
		objParameterDefaults.passfilter = m_blnPassFilter;
		objParameterDefaults.passedfilter = m_strPassedFilter;
		//objParameterDefaults.searchfields = m_strSearchFields;
		objParameterDefaults.flags = strFlags;
		objParameterDefaults.forcedownload = true;
		objParameterDefaults.formcode = m_strFormCode;
		objParameterDefaults.formentity = m_strFormEntityCode;
		objParameterDefaults.formentitycode = m_strFormEntityCode;
		objParameterDefaults.formentitydescription = m_strFormEntityDescription;
		objParameterDefaults.formentityid = m_strFormEntityID;
		objParameterDefaults.formified = false;
		objParameterDefaults.forreview = false;
		objParameterDefaults.hideoperations = m_strHideOperations;
		objParameterDefaults.id = '';
		objParameterDefaults.idfield = 'id';
		objParameterDefaults.idlist = [];
		objParameterDefaults.mode = 'add';
		objParameterDefaults.operationcode = strOperationCode_a;
		objParameterDefaults.relationship = m_strRelationship;
		objParameterDefaults.relative = m_strRelative;
		objParameterDefaults.relativeid = m_strRelativeID;
		objParameterDefaults.title = m_strTitle;
		objParameterDefaults.titlefield = '';
		objParameterDefaults.type = m_strEntityType;
		objParameterDefaults.uselatestform = m_blnUseLatestForm;
//alert(strOperationCode_a);
		// legacy transformation
		var strOperationPrefix = strOperationCode_a.split('_')[0];
		switch (strOperationPrefix)
		{
			// basic CRUD
			case 'ADD':	
				if (m_strMode === 'builder')
				{
					strCommand = 'ADD';
					objParameterDefaults.component = m_FORMFORMBUILDER;
					objParameterDefaults.mode = 'add';
				}
				else
				{
					strCommand = 'ADD';
					objParameterDefaults.component = m_strComponent;
					objParameterDefaults.mode = 'add';
				}
				break;
				
			case 'DEL':	
			case 'DELETE':
				strCommand = 'DELETE';
				objParameterDefaults.component = '';
				objParameterDefaults.mode = '';
				break;
			
			case 'EDT':	
			case 'EDIT':
				if (m_strMode === 'builder')
				{
					strCommand = 'EDIT';
					objParameterDefaults.component = m_FORMFORMBUILDER;
					objParameterDefaults.mode = 'edit';
				}
				else
				{
					strCommand = 'EDIT';
					objParameterDefaults.component = m_strComponent;
					objParameterDefaults.mode = 'edit';
				}
				break;
				
			case 'EXP':	
			case 'EXPORT':
				if (m_strMode === 'builder')
				{
					strCommand = 'EXPORT';
					objParameterDefaults.component = m_FORMFORMBUILDER;
					objParameterDefaults.mode = 'export';
				}
				else
				{
					strCommand = 'EXPORT';
					objParameterDefaults.component = m_strComponent;
					objParameterDefaults.mode = 'export';
				}
				break;
				
			case 'VW': 
			case 'VIEW':
				if (m_strMode === 'builder')
				{
					strCommand = 'VIEW';
					objParameterDefaults.component = m_FORMFORMBUILDER;
					objParameterDefaults.mode = 'view';
				}
				else
				{
					strCommand = 'VIEW';
					objParameterDefaults.component = m_strComponent;
					objParameterDefaults.mode = 'view';
				}
				break;

			// specific multiple entity operations
			case 'ADDTO': 
				objParameterDefaults.component = strCommand;
				strCommand = 'ADDTO';
				objParameterDefaults.exclusive = 'Y';
				objParameterDefaults.mode = '';
				break;
			
			// specific single entity operations
			//case 'DELDATA':
			//case 'DELETEALL':
				//strCommand = 'DELETEALL';
				//objParameterDefaults.component = '';
				//objParameterDefaults.mode = '';
				//break;
				
			case 'DL': 
			case 'DOWNLOAD':
				strCommand = 'DOWNLOAD';
				objParameterDefaults.component = '';
				objParameterDefaults.mode = '';
				break;

			//case 'INS':
			// case 'INSTALLPROFILE':
				// strCommand = 'INSTALLPROFILE';
				// objParameterDefaults.component = '';
				// objParameterDefaults.mode = '';
				// break;
			
			case 'INVOKE': 
				objParameterDefaults.component = strCommand;
				strCommand = 'INVOKE';
				objParameterDefaults.exclusive = 'N';
				objParameterDefaults.mode = '';
				break;
			
			// listers
			case 'LIST': break;
			
			case 'LISTFORMS':
			case 'LISTITEMS': 
				strCommand = 'LISTFORMS';
				// doesn't cater for selection overriding

				//objParameterDefaults.branchid = '';
				//objParameterDefaults.branchname = '';
				if ($.isArray(arrSelection_a))
				{
					objParameterDefaults.formentity = arrSelection_a[0].formentitycode;
					objParameterDefaults.formentityid = arrSelection_a[0].id;
				}
				else
				{
					objParameterDefaults.formentity = arrSelection_a.formentitycode;
					objParameterDefaults.formentityid = arrSelection_a.id;
				}
				objParameterDefaults.entity = 'dataform';
				objParameterDefaults.mode ='renderer';
				objParameterDefaults.type = 'form';
				break;
			
			// showing forms
			case 'MAKEFORM': 
				objParameterDefaults.component = strCommand;
				strCommand = 'MAKEFORM';
				os.dialogAlert('MAKEFORM is being used, this is to be deprecated.', doNothing);
				break;
				
			case 'OPENFORM': 
				objParameterDefaults.component = strCommand;
				strCommand = 'SHOWFORM';
				break;
			
			case 'SELECT': 
				objParameterDefaults.component = strCommand;
				strCommand = 'SELECT';
				break;
				
			case 'SHOWFORM': 
				objParameterDefaults.component = strCommand;
				strCommand = 'SHOWFORM';
				break;
				
			case 'RUNCODE':
					objParameterDefaults.component = strCommand;
					strCommand = 'RUNCODE';
					break;

			case 'RUNCODETEST':
					objParameterDefaults.component = strCommand;
					strCommand = 'RUNCODETEST';
					break;

			default: 
				if (strCommand.length > 0)
				{
					objParameterDefaults.operationcode = strCommand;
				}
				else
				{
					objParameterDefaults.operationcode = strOperationCode_a;
				}
				break;
		}

		var objParametersTemp = {}; 

		if (objParameters_a !== undefined && objParameters_a !== null) 
		{
			objParametersTemp = objParameters_a;
		} 
		else 
		{
			objParametersTemp = os.urlToJSON(strParameters);
		}	
//console.log("1:" + JSON.stringify(objParametersTemp));
		
		if (objParametersTemp.descriptionfield == undefined) { objParametersTemp.descriptionfield = objParameterDefaults.descriptionfield; }
		if (objParametersTemp.idfield == undefined) { objParametersTemp.idfield = objParameterDefaults.idfield; }
		if (objParametersTemp.titlefield == undefined) { objParametersTemp.titlefield = objParameterDefaults.titlefield; }
//console.log("2:" + JSON.stringify(objParametersTemp));
		if ($.isArray(arrSelection_a))
		{   
			if (m_blnMultiselect)
			{ 
				if (arrSelection_a.length > 0)
				{
					processArray(arrSelection_a, function (objSelection_a)
					{   
						objParameterDefaults.idlist.push(objSelection_a[objParametersTemp.idfield]);
					});
					objParameterDefaults.id = objParameterDefaults.idlist[0];
					objParameterDefaults.entitydataid = objParameterDefaults.id;
				}         
			}
			else
			{   
				if (arrSelection_a.length > 0) 
				{
					objParameterDefaults.idlist.push(arrSelection_a[0][objParametersTemp.idfield]);
					objParameterDefaults.id = arrSelection_a[0][objParametersTemp.idfield];
					objParameterDefaults.description = arrSelection_a[0][objParametersTemp.descriptionfield];
					objParameterDefaults.entitydataid = objParameterDefaults.id;
				}
				
				if (objParametersTemp.titlefield.length > 0)
				{
					objParameterDefaults.title = arrSelection_a[0][objParametersTemp.titlefield];
				}
			}
		}
		else if(arrSelection_a !== undefined && arrSelection_a !== null)
		{                        
			objParameterDefaults.id = arrSelection_a[objParametersTemp.idfield];
			objParameterDefaults.description = arrSelection_a[objParametersTemp.descriptionfield];
			objParameterDefaults.idlist.push(objParameterDefaults.id);
			objParameterDefaults.entitydataid = objParameterDefaults.id;
			if (objParametersTemp.titlefield.length > 0)
			{
				objParameterDefaults.title = arrSelection_a[objParametersTemp.titlefield];
			}
		}

		// variable placeholders
		strParameters = str_replace(strParameters, '%DESCRIPTION%', encodeURIComponent(objParameterDefaults.description));
		strParameters = str_replace(strParameters, '__DESCRIPTION__', encodeURIComponent(objParameterDefaults.description));			
		strParameters = str_replace(strParameters, '%RELATIONSHIP%', encodeURIComponent(m_strRelationship));
		strParameters = str_replace(strParameters, '__RELATIONSHIP__', encodeURIComponent(m_strRelationship));
		strParameters = str_replace(strParameters, '%RELATIVEID%', encodeURIComponent(m_strRelativeID));
		strParameters = str_replace(strParameters, '__RELATIVEID__', encodeURIComponent(m_strRelativeID));
		strParameters = str_replace(strParameters, '%RELATIVE%', encodeURIComponent(m_strRelative));
		strParameters = str_replace(strParameters, '__RELATIVE__', encodeURIComponent(m_strRelative));
		strParameters = str_replace(strParameters, '%SELECTED%', encodeURIComponent(objParameterDefaults.id));
		strParameters = str_replace(strParameters, '__SELECTED__', encodeURIComponent(objParameterDefaults.id));
		strParameters = str_replace(strParameters, '%LIST%', encodeURIComponent(JSON.stringify(objParameterDefaults.idlist)));
		strParameters = str_replace(strParameters, '__LIST__', encodeURIComponent(JSON.stringify(objParameterDefaults.idlist)));
		strParameters = str_replace(strParameters, '%TITLE%', encodeURIComponent(objParameterDefaults.title));
		strParameters = str_replace(strParameters, '__TITLE__', encodeURIComponent(objParameterDefaults.title));
		strParameters = str_replace(strParameters, '__20', " ");

//alert(strParameters);
// 1. Get the parameters from the SUBSTITUTED STRING
var objParametersFromStr = os.urlToJSON(strParameters);
var objParameters = {};

// 2. Prioritize the OBJECT PARAMETER (objParameters_a) if provided.
if (objParameters_a !== undefined && objParameters_a !== null) 
{
	// Start with the object parameter (preserves nested arrays)
	$.extend(true, objParameters, objParameters_a);
}

// 3. Merge the substituted values from the string.
// This handles simple properties like 'relativeid' which might be '__SELECTED__'.
// Crucially, for properties like 'fixedfilter', this merge will prioritize the array
// from objParameters_a, but if objParametersFromStr contains a simple property 
// (e.g., 'id'), it will override the 'id' property in objParameters.
$.extend(true, objParameters, objParametersFromStr);


// MANUALLY APPLY SUBSTITUTION TO FIXED FILTER
// Since the fixedfilter array came from objParameters_a and was NOT a string, 
// the general string replacement missed the placeholders inside its values.
// We must manually substitute the array's contents.
if ($.isArray(objParameters.fixedfilter)) 
{
    var selectedValue = objParameterDefaults.id; // The substituted value
    
    // Iterate through the fixedfilter array and replace placeholders in 'value' property
    processArray(objParameters.fixedfilter, function (filterObj) 
	{
        if (typeof filterObj.value === 'string') 
		{
            filterObj.value = str_replace(filterObj.value, '%SELECTED%', selectedValue);
            filterObj.value = str_replace(filterObj.value, '__SELECTED__', selectedValue);
        }
    });
}
//console.log("P1:" + JSON.stringify(objParameters));
		
		if (objParameters.branchid == undefined) { objParameters.branchid = objParameterDefaults.branchid; }
		if (objParameters.branchname == undefined) { objParameters.branchname = objParameterDefaults.branchname; }
		if (objParameters.code == undefined) { objParameters.code = objParameterDefaults.code; }
		if (objParameters.component == undefined) { objParameters.component = objParameterDefaults.component; }
		if (objParameters.description == undefined) { objParameters.description = objParameterDefaults.description; }
		if (objParameters.descriptionfield == undefined) { objParameters.descriptionfield = objParameterDefaults.descriptionfield; }
		if (objParameters.entity == undefined) { objParameters.entity = objParameterDefaults.entity; }
		if (objParameters.entitycode == undefined) { objParameters.entitycode = objParameterDefaults.entitycode; }
		if (objParameters.entitydataid == undefined) { objParameters.entitydataid = objParameterDefaults.entitydataid; }
		if (objParameters.exclusive == undefined) { objParameters.exclusive = objParameterDefaults.exclusive; }
		if (objParameters.fixedfilter == undefined) { objParameters.fixedfilter = objParameterDefaults.fixedfilter; }
		if (objParameters.passfilter == undefined) { objParameters.passfilter = objParameterDefaults.passfilter; }
		if (objParameters.passedfilter == undefined) { objParameters.passedfilter = objParameterDefaults.passedfilter; }
		if (objParameters.searchfields == undefined) { objParameters.searchfields = objParameterDefaults.searchfields; }
		if (objParameters.flags == undefined) { objParameters.flags = objParameterDefaults.flags; }
		if (objParameters.forcedownload == undefined) { objParameters.forcedownload = objParameterDefaults.forcedownload; }
		if (objParameters.formcode == undefined) { objParameters.formcode = objParameterDefaults.formcode; }
		if (objParameters.formentity == undefined) { objParameters.formentity = objParameterDefaults.formentity; }
		if (objParameters.formentitycode == undefined) { objParameters.formentitycode = objParameterDefaults.formentitycode; }
		if (objParameters.formentitydescription == undefined) { objParameters.formentitydescription = objParameterDefaults.formentitydescription; }
		if (objParameters.formentityid == undefined) { objParameters.formentityid = objParameterDefaults.formentityid; }
		if (objParameters.formified == undefined) { objParameters.formified = objParameterDefaults.formified; }
		if (objParameters.forreview == undefined) { objParameters.forreview = objParameterDefaults.forreview; }
		if (objParameters.hideoperations == undefined) { objParameters.hideoperations = objParameterDefaults.hideoperations; }
		if (objParameters.id == undefined) { objParameters.id = objParameterDefaults.id; }
		if (objParameters.idfield == undefined) { objParameters.idfield = objParameterDefaults.idfield; }
		if (objParameters.idlist == undefined) { objParameters.idlist = objParameterDefaults.idlist; }
		if (objParameters.mode == undefined) { objParameters.mode = objParameterDefaults.mode; }
		if (objParameters.operationcode == undefined) { objParameters.operationcode = objParameterDefaults.operationcode; }
		if (objParameters.relationship == undefined) { objParameters.relationship = objParameterDefaults.relationship; }
		if (objParameters.relative == undefined) { objParameters.relative = objParameterDefaults.relative; }
		if (objParameters.relativeid == undefined) { objParameters.relativeid = objParameterDefaults.relativeid; }
		if (objParameters.title == undefined) { objParameters.title = objParameterDefaults.title; }
		if (objParameters.titlefield == undefined) { objParameters.titlefield = objParameterDefaults.titlefield; }
		if (objParameters.type == undefined) { objParameters.type = objParameterDefaults.type; }
		if (objParameters.uselatestform == undefined) { objParameters.uselatestform = objParameterDefaults.uselatestform; }
//console.log("P2:" + JSON.stringify(objParameters));

		// if (m_blnPassFilter)
		// {
// alert("BEFOREMERGE1:" + objParameters.fixedfilter);
// alert("BEFOREMERGE2:" + objParameterDefaults.fixedfilter);
			// objParameters.fixedfilter = mergeFixedFilter(objParameters.fixedfilter, objParameterDefaults.fixedfilter);
// alert("MERGED:" + objParameters.fixedfilter);
		// }
// alert("PASS:" + m_blnPassFilter);
		
		// permission changes
		switch (strCommand)
		{
			case 'EDIT': 
				objParameters.mode = 'edit';
				var strViewVersion = objParameters.operationcode.replace('EDT_', 'VW_');
				if (hasPermission(strViewVersion))
				{
					strCommand = 'VIEW';
				}
				break;

			default: break;
		}
		
		// invoke actions
		switch (strCommand)
		{
			// basic CRUD
			case 'ADD':	invokeOperationAdd(objParameters); break;
			case 'DELETE': invokeOperationDelete(objParameters); break;
			case 'EDIT': invokeOperationEdit(objParameters); break;
			case 'VIEW': invokeOperationView(objParameters); break;
			case 'EXPORT': invokeOperationExport(); break;

			// specific multiple entity operations
			case 'ADDTO': invokeOperationAddTo(objParameters); break;
			case 'INVOKE': invokeOperationInvoke(objParameters); break;
			
			// specific single entity operations
			case 'DOWNLOAD': invokeOperationDownload(objParameters); break;
			case 'INSTALLFILEFORMAT': invokeOperationInstallFileFormat(); break;
			case 'INSTALLPROFILE': invokeOperationInstallProfile(); break;
			
			// listers
			case 'LIST': invokeOperationShowLister(objParameters); break;
			case 'LISTFORMS': invokeOperationListForms(objParameters); break;	// Lists Data Forms	// TO TEST
			
			// showing forms
			case 'MAKEFORM': invokeOperationMakeForm(objParameters); break;	// in future makeform and showform should become one
			case 'OPENFORM': invokeOperationShowForm(objParameters); break;
			case 'SHOWFORM': invokeOperationShowForm(objParameters); break;

			// select
			case 'SELECT': invokeOperationSelect(objParameters); break;

			// run operation
			case 'RUNCODE' : invokeOperationRunCode(objParameters); break;
			case 'RUNCODETEST' : invokeOperationRunCode(objParameters); break;

			// custom operations
			default: invokeOperationCustom(objParameters); break;
		}
	}

	function initialiseGridActions()
	{

		var arrActions = [];
		var objAction = '';
		var objTile = '';

		var arrMapButtons = ['CloseButton'];
		
		if (m_strBranchID.length > 0)
		{
			arrMapButtons.push('BranchButton');
		}

		//m_arrMap[0].layout[0] = m_arrMapLayouts.mode[m_strMode];

		processArray(m_arrOperations, function (objOperation_a)
		{
			var strOperationCode = objOperation_a.code;
			var strOperationActionCode = strOperationCode.split('_')[0];
            
            var strActionButtonID = strOperationCode + 'Button';

			var arrPermissions = null;

			if (objOperation_a.permissions !== undefined)
			{
				arrPermissions = objOperation_a.permissions;
			}
			else
			{   
				arrPermissions = [objOperation_a.code];
			}
            
            arrMapButtons.push(strActionButtonID);

			objAction =
			{
				id : strActionButtonID,
				description : objOperation_a.description,
				permissions : function() 
				{ 
                    if(arrPermissions.length === 0)
                    {
                        return true;
                    }
                    else
                    {
                        return hasPermission(arrPermissions) && !isOperationHidden(objOperation_a.code);
                    }
				},
				multiSelect : os.toBoolean(objOperation_a.allowmultiple),
				requiresSelection : os.toBoolean(objOperation_a.requiresselection),
				//isinternal : os.toBoolean(objOperation_a.isinternal),
				prompt : objOperation_a.prompt,
				operation : strOperationActionCode,
				operationcode : strOperationCode,
				action : function(arrSelection_a) { invokeAction(strOperationCode, objOperation_a.command, objOperation_a.flags, objOperation_a.parameters, null, arrSelection_a); }
			};

			// also add the operations to tiles
			objTile =
			{
				id : strActionButtonID,
				caption : objOperation_a.description,
				classes : '',
				permissions : function ()
				{ 
                    if (objOperation_a.permissions !== undefined)
                    {
                        if(objOperation_a.permissions.length === 0)
                        {
                            return true;
                        }
                        else
                        {
                            return hasPermission(objOperation_a.permissions) && !isOperationHidden(objOperation_a.code);
                        }
                    }
                    else
                    {
                        return hasOperationPermission(strActionButtonID);
                    }
				},
				action : invokeOperation,
				//actionData : strOperationActionCode,
                actionData : strOperationCode,
				tip : 'Click here to ' + htmlEncode(objOperation_a.description) + '.',
				type : 'toolbarbutton'
			};
            
            arrActions.push(objAction);
            m_arrTiles.push(objTile);

		}
		);
		
		arrMapButtons.push('ClearSelectionButton');

		if(os.toBoolean(ENABLE_XDEVICE))
		{
			arrMapButtons.push('MyDeviceButton');
		}
		
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
	
	function invokeOperationAdd(objParameters_a)
	{ 
		var strComponent = objParameters_a.component;
		if ((strComponent.length === 0) || (strComponent === m_FORMDEFAULTLISTER))
		{
			strComponent = m_FORMDEFAULTCOMPONENT;
		}
//alert('xa:' + objParameters_a.component + ':' + m_strComponent + ':' + strComponent);
		os.showForm(strComponent, objParameters_a);
	}

	function invokeOperationAddTo(objParameters_a)
	{
		var objParameters = objParameters_a;
//alert(objParameters.component);
//alert(JSON.stringify(objParameters));
				
		os.showFormPopup(objParameters.component, objParameters, function(objResult_a) 
		{ 
			if (objResult_a.length >0)
			{     
				objParameters.formentityid = objResult_a[0].id;
				objParameters.id = objResult_a[0].id;
				objParameters.description = objResult_a[0].description;
				objParameters.idlist = objResult_a;
				objParameters.operationcode = objParameters.formentity;
				objParameters.entitycode = 'addto_' + objParameters.relative + '_';
				objParameters.exclusive = "N";
				invokeOperationCustom(objParameters);
			}
		});
	}

	function invokeOperationInvoke(objParameters_a)
	{
		var objParameters = objParameters_a;
//alert(objParameters.component);
//alert(JSON.stringify(objParameters));
		os.showFormPopup(objParameters.component, objParameters, function(objResult_a) 
		{ 
			if (objResult_a.length >0)
			{     
				objParameters.formentityid = objResult_a[0].id;
				objParameters.id = objResult_a[0].id;
				objParameters.description = objResult_a[0].description;
				objParameters.idlist = objResult_a;
				//objParameters.operationcode = objParameters.formentity;
				objParameters.entitycode = 'invoke_' + objParameters.relative + '_';
				objParameters.exclusive = "N";
//alert(JSON.stringify(objParameters));
				invokeOperationCustom(objParameters);
			}
		});
	}

	function invokeOperationRunCode(objParameters_a)
	{
		var arrParameters = os.jsonToNameValueArray(objParameters_a);
		var objJSON = os.ajaxRequestCreate('entity_customoperationexecute', arrParameters);
		
		os.ajaxCall(URL_WEBSERVICE, objJSON, function(objResponse_a)
		{	
			try
			{					
				var strFileURL = objResponse_a.file_url;
				
				if(strFileURL.length > 0)
				{
					os.lazyLoadApp(strFileURL);
				}								
			}
			catch(err_a)
			{
				alert(err_a.message);
				console.log(err_a);
			}

			
		}, 
		os.ajaxError);
	}

	function invokeOperationCustom(objParameters_a)
	{
		var arrParameters = os.jsonToNameValueArray(objParameters_a);
		executeCustomOperation(arrParameters);
	}
	
	function invokeOperationDelete(objParameters_a)
	{
		var arrParameters = os.jsonToNameValueArray(objParameters_a);
		deleteData(arrParameters);
	}

	function invokeOperationDownload(objParameters_a)
	{
		os.viewDocument(objParameters_a.id, objParameters_a.forcedownload);
	}
	
	function invokeOperationEdit(objParameters_a)
	{
		if (objParameters_a.id !== undefined)
		{
			var strComponent = objParameters_a.component;
			if ((strComponent.length === 0) || (strComponent === m_FORMDEFAULTLISTER))
			{
				strComponent = m_FORMDEFAULTCOMPONENT;
			}
//alert('xe:' + objParameters_a.component + ':' + m_strComponent + ':' + strComponent);
			os.showForm(strComponent, objParameters_a);
		}
	}

	function invokeOperationInstallFileFormat()
	{
		//var objParameters = { type:'form', entity:'systemform', formentity:'fileformat', formentitydescription:'File Format', formcode:'FILEFORMAT', mode:'renderer', title:'Installable File Formats' };
		var objParameters = 
		{
			"mode" : LIST_FILEFORMATINSTALLABLE,
			"resultfields" : ['id'],
			"title" : 'Install File Format'
		};
		
		//os.showFormPopup(m_FORMDEFAULTLISTER, objParameters, function (objResult_a)
		//os.showFormPopup(m_FORMENTITYCHOOSER, objParameters, function (objResult_a)
		os.showFormPopup(m_FORMRECORDCHOOSER, objParameters, function (objResult_a)
		{
			if (objResult_a.length > 0)
			{
				var objSelection = objResult_a[0];
				var strFileFormatID = objSelection.id;

				var objJSON = os.ajaxRequestCreate('import_fileformatinstall',
						[
							{
								"name" : "id",
								"value" : strFileFormatID
							}
						]);
						
				os.ajaxCall(URL_WEBSERVICE, 
							objJSON, 
							function() 
							{ 
								m_objGrid.refresh(false);
								//os.broadcast(m_strFormID, 'core', m_strCurrentList + 'installed');
							}, 
							m_objThis.asyncError);
			}
		}
		);
	}

	function invokeOperationInstallProfile()
	{
		//var objParameters = { type:'form', entity:'systemform', formentity:'profile', formentitydescription:'Profile', formcode:'PROFILE', mode:'renderer', title:'Installable Profiles' };
		var objParameters = 
		{
			"mode" : LIST_PROFILEINSTALLABLE,
			"resultfields" : ['id'],
			"title" : 'Install Profile'
		};
		
		//os.showFormPopup(m_FORMDEFAULTLISTER, objParameters, function (objResult_a)
		//os.showFormPopup(m_FORMENTITYCHOOSER, objParameters, function (objResult_a)
		os.showFormPopup(m_FORMRECORDCHOOSER, objParameters, function (objResult_a)
		{
			if (objResult_a.length > 0)
			{
				var objSelection = objResult_a[0];
				var strProfileID = objSelection.id;

				var objJSON = os.ajaxRequestCreate('security_profileinstall',
						[
							{
								"name" : "id",
								"value" : strProfileID
							}
						]);
						
				os.ajaxCall(URL_WEBSERVICE, 
							objJSON, 
							function() 
							{ 
								m_objGrid.refresh(false);
								//os.broadcast(m_strFormID, 'core', m_strCurrentList + 'installed');
							}, 
							m_objThis.asyncError);
			}
		}
		);
	}

	function invokeOperationMakeForm(objParameters_a)
	{
		os.makeForm(objParameters_a.component, objParameters_a);
	}
	
	function invokeOperationListForms(objParameters_a)
	{
		os.showForm(m_FORMDEFAULTLISTER, objParameters_a);
	}
	
	function invokeOperationShowForm(objParameters_a)
	{
		//alert(JSON.stringify(objParameters_a));
		os.showForm(objParameters_a.component, objParameters_a);
	}
	
	function invokeOperationShowLister(objParameters_a)
	{
		//alert(JSON.stringify(objParameters_a));
		os.showForm(objParameters_a.component, objParameters_a);
	}
	
	function invokeOperationView(objParameters_a)
	{
		if (objParameters_a.id !== undefined)
		{
			var strComponent = objParameters_a.component;
			if ((strComponent.length === 0) || (strComponent === m_FORMDEFAULTLISTER))
			{
				strComponent = m_FORMDEFAULTCOMPONENT;
			}
//alert('xv:' + objParameters_a.component + ':' + m_strComponent + ':' + strComponent);
			os.showForm(strComponent, objParameters_a);
		}
    }
    
	function invokeOperationExport()
	{
		exportEntityData();
    }
    
    function invokeOperationSelect(objParameters_a)
    {
        m_arrSelection = m_objGrid.getSelection(m_arrReturns);
        // m_arrSelection already populated upon clicking the image.
        // we just need to close the form to trigger the return selection.
        os.closeForm(m_strFormID);
        
    }
	
    function actionShowEntityChooser(objParameters_a)
	{
		os.showFormPopup('entity.frmEntityChooser', objParameters_a, function (objResult_a)
        {
			if (objResult_a.length > 0)
			{
				var strID = objResult_a[0].id;
				executeCustomOperation2(objParameters_a.entity, objParameters_a.operation, strID, function(objResult_a)
				{
					m_strBranchID = strID;
					m_strBranchName = objResult_a.branchname;
					
					m_strTitle = m_strTitleTemplate;
					m_strTitle = insertBranchName(m_strTitleTemplate);

					branchFixedFilter();
					
					//alert('2:' + m_strFixedFilter);

					fetchData(true); // JPC
				});
			}
			
        }, false, true);
	}

	// ====================================================================================
	// POPULATING =========================================================================

	function populateForm()
	{
		populateDock();

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
		
		os.element(m_strFormID, '.ge-searchbar').hide();
		os.element(m_strFormID, '.ge-dispatchdate').hide();
		os.element(m_strFormID, '.ge-receiverstate').hide();
		if (m_arrSearchFields.length > 0)
		{
			os.element(m_strFormID, '.ge-searchbar').show();

			var blnDispatch = m_arrSearchFields.indexOf('dispatchdate') > -1;
			var blnReceiverState = m_arrSearchFields.indexOf('receiverstate') > -1;

			if (blnDispatch)
			{
				os.element(m_strFormID, '.ge-dispatchdate').show();
			}
			
			if (blnReceiverState)
			{
				os.element(m_strFormID, '.ge-receiverstate').show();
			}
		}

		os.element(m_strFormID, '.ge-search-input').val(m_strSearchKeyword);
		populateGrid();
		m_objGrid.refresh(false);

		if (m_strSearchKeyword.length > 0)
		{
			//var strKeyword = massageKeyword(m_strSearchKeyword);
			searchGrid();
		}
	}

	function populateDock()
	{
		m_objDock = new jDock(os,
			{
				//"alwaysvisiblebuttoncount": 2,
				"map" : m_arrMap,
				"tiles" : m_arrTiles
			}
			);

		m_objDock.render(m_strFormID, '.ge-button-panel');
		m_objDock.disableButtons(getRequireSelectionButtons(), 'btn-primary');
	}
        
	function populateGrid()
	{
        var objGridOptions =
		{
			cbOnGetFormHeight : m_objThis.Grid_onGetFormHeight,
			cbOnSelection : m_objThis.Grid_onDblClick,
			cbOnSelectionChange : m_objThis.Grid_onSelectionChange,
			cbOnRowReorder : m_objThis.Grid_onRowReorder,
			cbOnPreRowReorder : m_objThis.Grid_onPreRowReorder,
			cbOnClickEvent: function(objColumn_a, objRow_a, objLinkElement_a, strCommand_a, arrParameters_a)
			{
				//var strParameters = os.jsonToURL(arrParameters_a, false);	// note: a json version of parameters was added to invokeAction because it's not URL compatible
				//var strParameters = JSON.stringify(arrParameters_a);
				var arrParameters = arrParameters_a;

				// First, unbind any existing handlers on this specific element.
				objLinkElement_a.off('click');

				// Now, bind the new click handler. This sets it up for future clicks.
				objLinkElement_a.on('click', function(objEvent_a) 
				{
					objEvent_a.stopPropagation();
					invokeAction(strCommand_a, strCommand_a, "", "", arrParameters, objRow_a);
				});

				// Finally, trigger the newly bound handler immediately.
				objLinkElement_a.trigger('click');
			},
			columns : m_objGridLayout.columns,
            filterType : 'string',
            parameters : m_objGridLayout.params,
			extraParameters : [],
			multiSelect : false,
			rowReorder : m_blnReorder,
			
			//returnFields : arrFields,
			webServiceFunction : m_objGridLayout.ws            
		};
        
        objGridOptions = applyFlags(objGridOptions);
        
        m_objGrid = new jDatatableRenderer(os, objGridOptions);
        
        m_objGrid.render(m_strFormID, '.ge-entity-grid');
	}

	this.Grid_onGetFormHeight = function()
	{
        var intHeight = os.element(m_strFormID, '.gb-form').height();
        var intHeaderButtonBarHeight = os.element(m_strFormID, '.ge-button-panel').height();

        var availableHeight = intHeight - 230;

		if (m_arrSearchFields.length > 0)
		{
			availableHeight -= 10;
		}

        // consider the header button height. if the header button bar is morethan 2 rows or lines.
        // the content overflow. So, we need to adjust it.
        if (intHeaderButtonBarHeight > 40) 
		{ 
			// adjust it if it is 2-liners or more.
			availableHeight -= (intHeaderButtonBarHeight - 50);
        }
		
		return availableHeight;
	};

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

	function customOperationExecuted(objResponse_a)
	{
		m_objGrid.refresh();
	}

	function deleted(objResponse_a)
	{
        m_objGrid.refresh(false);
        os.broadcast(m_strFormID, 'entity', m_strCurrentList + 'deleted');
	}

	function entityHeadersFetched(objResponse_a)
	{
		m_arrEntityHeaders = objResponse_a.fields;
        
        if(m_strFlags.length > 0) 
		{ 
            m_strFlags += ',' + objResponse_a.flags;
        }
        else 
		{
            m_strFlags = objResponse_a.flags;
        }

		m_objGridLayout.columns = m_arrEntityHeaders;

		asyncDataIsFetched();
	}

	function myDevicesFetched(objResponse_a)
	{	
		var objData;
		var arrActionDataList = [];
		var strCaption;

		objData = {
			id : "DeviceNoneButton",
			caption : "None",
			classes : "",
			action : m_objThis.MyDeviceSelection_onClick,
			actionData : "NONE",
			tip : 'Click here to select device',
			type : ""
		};

		arrActionDataList.push(objData);

		processArray(objResponse_a, function(objRow_a)
		{
			strCaption = objRow_a.device;

			if(os.toBoolean(objRow_a.isselected))
			{
				strCaption += '&nbsp;<i class="fa fa-check" aria-hidden="true"></i>';
			}

			objData = {
				id : "Device-" + getGUID() + "-Button",
				caption : strCaption,
				classes : "",
				action : m_objThis.MyDeviceSelection_onClick,
				actionData : objRow_a.id,
				tip : 'Click here to select device',
				type : ""
			};

			arrActionDataList.push(objData);
		});

		processArray(m_arrTiles, function(objTile_a, intR_a, intI_a)
		{
			if(objTile_a.id === 'MyDeviceButton')
			{
				objTile_a.actionDataList = arrActionDataList;
				m_arrTiles[intI_a] = objTile_a;
			}
		});
		
		if(m_myDeviceRepaintJDock)
		{
			m_objDock.render(m_strFormID, '.ge-button-panel');			
			m_myDeviceRepaintJDock = false;			
		}
	}

	function operationsFetched(objResponse_a)
	{

		m_arrOperations = objResponse_a;

		initialiseReturns();
		initialiseGridLayout();
		initialiseGridActions();
		
		m_intToFetch = 1;
		m_intFetched = 0;
		m_intErrors = 0;
        
		//if (m_strMode === 'select' || m_strMode === 'deleteformgroup')
		if (m_strMode === 'deleteformgroup')
		{
			initialiseNonDynamicEntityHeaders();
		}
		else
		{
			fetchEntityHeaders();
		}
	}

	function userFormDeviceSaved(objResponse_a)
	{
		fetchMyDevices();
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function deleteData(arrParameters_a)
	{
		var strFunction = '';
        
		if (m_strEntityType === 'form')	// deleting data
		{
			strFunction = 'entity_formdatadelete';
		}
		else if (m_strEntityType === 'entity')	// deleting form templates
		{
			strFunction = 'entity_entitydatadelete';
		}

		if (strFunction.length > 0)
		{
			var objJSON = os.ajaxRequestCreate(strFunction, arrParameters_a);
			os.ajaxCall(URL_WEBSERVICE, objJSON, deleted, os.ajaxError);
		}
	}

	function fetchData(blnFetch_a)
	{
		if (blnFetch_a)
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;

			//fetchEntity();

			// this need to refactored for a simpler condition

			//if (m_strMode === 'deleteformgroup' || m_strMode === 'select')
			if (m_strMode === 'deleteformgroup')
			{
                m_intToFetch = 2;
				initialiseNonDynamicOperations();
                initialiseNonDynamicEntityHeaders();
			}
			else if (m_strEntityType === 'form' && m_strEntityCode === 'dataform' || m_strMode === 'select')
			{
                m_intToFetch = 2;
				initialiseNonDynamicOperations();
                fetchEntityHeaders();
			}
			else if (m_strFormEntityCode.length > 0)
			{							
				fetchOperations();				
				
				if(os.toBoolean(ENABLE_XDEVICE))
				{
					m_intToFetch++;
					fetchMyDevices();
				}
			}
			else
			{
				if (m_strEntityType === 'form')
				{
					initialiseNonDynamicOperations();
				}
				else
				{
					fetchOperations();
				}
			}
		}
		else
		{
			m_intToFetch = 2;
			m_intFetched = 0;
			m_intErrors = 0;

			populateForm();
		}
	}
        
	function executeCustomOperation(arrParameters_a)
	{
		//alert(JSON.stringify(arrParameters_a));
        var objJSON = os.ajaxRequestCreate('entity_customoperationexecute', arrParameters_a);
		os.ajaxCall(URL_WEBSERVICE, objJSON, customOperationExecuted, os.ajaxError);
	}

	function executeCustomOperation2(strEntityCode_a, strOperationCode_a, strID_a, cb_a)
	{

		var objJSON = os.ajaxRequestCreate('entity_customoperationexecute', [
					{
						name : 'entitycode',
						value : strEntityCode_a
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

//alert(JSON.stringify(objJSON));
		os.ajaxCall(URL_WEBSERVICE, objJSON, cb_a, os.ajaxError);

	}

	function exportEntityData()
	{
		var strFunction = '';

		if (m_strEntityType === 'form')
		{
			strFunction = 'entity_formdataexport';
		}
		else if (m_strEntityType === 'entity')
		{
			alert("not implemented");
			//strFunction = 'entity_entitydataexport';
		}

		if (strFunction.length > 0)
		{
			var arrParams = m_objGrid.getParams();

			var objJSON = os.ajaxRequestCreate(strFunction, arrParams);
			os.ajaxCall(URL_WEBSERVICE, objJSON, doNothing, os.ajaxError);
		}
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

	function fetchMyDevices()
	{
		var objJSON = os.ajaxRequestCreate('core_getcurrentdevices', [
			{
				name : 'formentity',
				value : m_strFormEntityCode
			}
		]);
			
		os.ajaxCall(URL_WEBSERVICE, objJSON, myDevicesFetched, os.ajaxError);
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

	function saveUserFormDevice(strDeviceID)
	{
		var objJSON = os.ajaxRequestCreate('core_saveuserformdevice', [
			{
				name : 'deviceid',
				value : strDeviceID
			},
			{
				name : 'formentity',
				value : m_strFormEntityCode
			}
		]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, userFormDeviceSaved, os.ajaxError);
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

		os.unbindEvents(m_strFormID, 'ge-dispatchdatefrom-input,ge-dispatchdateto-input,ge-receiverstate-input');
		os.unbindEvents(m_strFormID, 'ge-dispatchdatefromclear-button,ge-dispatchdatetoclear-button,ge-receiverstateclear-button');

		os.bindEvent(m_objThis, m_strFormID, '.ge-searchclear-button', 'btnSearchClear', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-search-input', 'SearchField', 'onEnterKey');

		os.bindEvent(m_objThis, m_strFormID, '.ge-dispatchdatefromclear-button', 'btnDispatchDateFromClear', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-dispatchdatetoclear-button', 'btnDispatchDateToClear', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-receiverstateclear-button', 'btnReceiverStateClear', 'onClick');

		os.bindEvent(m_objThis, m_strFormID, '.ge-dispatchdatefrom-input', 'DispatchDateFrom', 'onChange');
		os.bindEvent(m_objThis, m_strFormID, '.ge-dispatchdateto-input', 'DispatchDateTo', 'onChange');
		os.bindEvent(m_objThis, m_strFormID, '.ge-receiverstate-input', 'ReceiverState', 'onClick');
		os.bindDatePicker(m_strFormID, '.ge-btdatepicker',
		{
			dateFormat : DATE_OUTPUTFORMAT,
			showOtherMonths : true,
			changeMonth : true,
			changeYear : true,
			yearRange : 'c-75:c+5',
			onSelect : function (dateText, inst)
			{
				os.element(this).trigger('change');
            },
            onChangeMonthYear : function(intYear_a, intMonth_a, objDatePicker_a)
            {   
                var newDate = new Date( os.element(this).datepicker('getDate') );
                    newDate.setMonth(intMonth_a - 1);
                    newDate.setYear(intYear_a);
                                
                os.element(this).datepicker('setDate', newDate);
				os.element(this).trigger('change');
            }
		}
		);
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return os.isMDI();
	};

	this.Form_canClose = function ()
	{
		return true;
	};

	this.Form_getDesktopRegion = function()
	{
		return getListerDesktopRegion(m_strFormEntityCode);
	};

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
					varAction();	// JC why is this call is not passing any parameters that are required
				}
				else if (varAction === 'refresh')
				{
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
			if ((m_objParameters.returnfields !== objParameters_a.returnfields) ||
				(m_objParameters.entity !== objParameters_a.entity) ||
				(m_objParameters.entityid !== objParameters_a.entityid) ||
				(m_objParameters.exclusive !== objParameters_a.exclusive) ||
				(m_objParameters.branchid !== objParameters_a.branchid) ||
				(m_objParameters.fixedfilter !== objParameters_a.fixedfilter) ||
				(m_objParameters.passfilter !== objParameters_a.passfilter) ||
				(m_objParameters.passedfilter !== objParameters_a.passedfilter) ||
				(m_objParameters.searchfields !== objParameters_a.searchfields) ||
				(m_objParameters.flags !== objParameters_a.flags) ||
				(m_objParameters.formcode !== objParameters_a.formcode) ||
				(m_objParameters.formentity !== objParameters_a.formentity) ||
				(m_objParameters.formentitydescription !== objParameters_a.formentitydescription) ||
				(m_objParameters.formentityid !== objParameters_a.formentityid) ||
				(m_objParameters.formified !== objParameters_a.formified) ||
                (m_objParameters.hideoperations !== objParameters_a.hideoperations) ||
				(m_objParameters.mode !== objParameters_a.mode) ||
				(m_objParameters.searchkeyword !== objParameters_a.searchkeyword) ||
				(m_objParameters.title !== objParameters_a.title) ||
				(m_objParameters.type !== objParameters_a.type) ||
                (m_objParameters.relationship !== objParameters_a.relationship) ||
                (m_objParameters.relativeid !== objParameters_a.relativeid) ||
                (m_objParameters.relationshiptitle !== objParameters_a.relationshiptitle)) 
			{
				m_objParameters = objParameters_a;

				m_arrReturnfields = m_objParameters.resultfields;
				if (m_objParameters.resultfields === undefined) 
				{ 
					m_arrReturnfields = [];
				}
    
				m_strComponent = m_objParameters.component;
				if (m_strComponent === undefined)
				{
					m_strComponent = '';
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
				              
				m_strFixedFilter = '';
				m_arrFixedFilter = [];
	
				try
				{
					if ((m_objParameters.fixedfilter !== undefined)) 
					{ 
						if ((typeof m_objParameters.fixedfilter === 'string') && (m_objParameters.fixedfilter.length > 0))
						{
							m_strFixedFilter = m_objParameters.fixedfilter;
							m_strFixedFilter = os.str_quotes(m_strFixedFilter);
							m_arrFixedFilter = JSON.parse(m_strFixedFilter);
							m_strFixedFilter = JSON.stringify(m_arrFixedFilter);
						}
						else
						{
							m_arrFixedFilter = m_objParameters.fixedfilter;
							m_strFixedFilter = JSON.stringify(m_arrFixedFilter);
						}
					}
				}
				catch(err)
				{
					doNothing();
				}

				m_blnPassFilter = m_objParameters.passfilter;
				
				if (m_blnPassFilter !== undefined)
				{
					m_blnPassFilter = os.toBoolean(m_blnPassFilter);
				}
				else
				{
					m_blnPassFilter = false;
				}

				try
				{
//alert(m_objParameters.passedfilter);
					if ((m_objParameters.passedfilter === undefined)) 
					{ 
						if (m_blnPassFilter)
						{
							m_arrPassedFilter = m_objParameters.fixedfilter;
							m_strPassedFilter = JSON.stringify(m_arrFixedFilter);
//alert("FILTER PASSED:" + m_strPassedFilter);
						}
					}
					else
					{
						if ((typeof m_objParameters.passedfilter === 'string') && (m_objParameters.passedfilter.length > 0))
						{
							m_strPassedFilter = m_objParameters.passedfilter;
							m_strPassedFilter = os.str_quotes(m_strPassedFilter);
							m_arrPassedFilter = JSON.parse(m_strPassedFilter);
							m_strPassedFilter = JSON.stringify(m_arrPassedFilter);
						}
						else
						{
							m_arrPassedFilter = m_objParameters.passedfilter;
							m_strPassedFilter = JSON.stringify(m_arrPassedFilter);
						}
					}
				}
				catch(err)
				{
					doNothing();
				}

				m_strSearchFields = '';
				m_arrSearchFields = [];
	
				try
				{
					if ((m_objParameters.searchfields !== undefined)) 
					{ 
						if ((typeof m_objParameters.searchfields === 'string') && (m_objParameters.searchfields.length > 0))
						{
							m_strSearchFields = m_objParameters.searchfields;
							m_strSearchFields = os.str_quotes(m_strSearchFields);
							m_arrSearchFields = JSON.parse(m_strSearchFields);
							m_strSearchFields = JSON.stringify(m_arrSearchFields);
						}
						else
						{
							m_arrSearchFields = m_objParameters.searchfields;
							m_strSearchFields = JSON.stringify(m_arrSearchFields);
						}
					}
				}
				catch(err)
				{
					doNothing();
				}

				m_strBranchID = m_objParameters.branchid;
				if (m_strBranchID === undefined)
				{
					m_strBranchID = '';
				}

				m_strBranchName = m_objParameters.branchname;
				if (m_strBranchName === undefined)
				{
					m_strBranchName = '';
				}
				
				if (m_strBranchID.length > 0)
				{
					branchFixedFilter();
				}
				
				//alert('3:' + m_strFixedFilter);
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
				
				m_strDispatchDateFrom = "";
				m_strDispatchDateTo = "";
				m_strReceiverState = "";

				m_strTitleTemplate = m_objParameters.title;
				if (m_strTitleTemplate === undefined)
				{
					m_strTitleTemplate = '';
				}
				m_strTitle = m_strTitleTemplate;
				m_strTitle = insertBranchName(m_strTitleTemplate);

				m_strFormEntityDescription = m_objParameters.formentitydescription; // used for sub forms ADD mode mainly
				if (m_strFormEntityDescription === undefined)
				{
					m_strFormEntityDescription = m_strTitle;
				}

				m_blnUseLatestForm = m_objParameters.uselatestform;
				if (m_blnUseLatestForm === undefined)
				{
					m_blnUseLatestForm = true;
				}
				
				m_strEntityType = m_objParameters.type;
				if (m_strEntityType === undefined)
				{
					m_strEntityType = '';
				}
                
                m_strRelationship = m_objParameters.relationship;
                if (m_strRelationship === undefined)
                {
                    m_strRelationship = '';
                }
                
                m_strRelativeID = m_objParameters.relativeid;
                if (m_strRelativeID === undefined)
                {
                    m_strRelativeID = '';
                }

                m_strRelative = m_objParameters.relative;
                if (m_strRelative === undefined)
                {
                    m_strRelative = '';
                }

                m_strRelationshipTitle = m_objParameters.relationshiptitle;                
                if (m_strRelationshipTitle === undefined)
                {
                    m_strRelationshipTitle = '';
                }
                
				m_strFlags = m_objParameters.flags;
				if (m_strFlags === undefined)
				{
					m_strFlags = '';
				}
                
                m_strHideOperations = m_objParameters.hideoperations;
                m_arrHideOperations = [];
                
				if (m_strHideOperations === '\"\"')
				{
					m_strHideOperations = "";
				}
				
                if ((m_strHideOperations === undefined)) 
				{
					m_strHideOperations = '';
					m_arrHideOperations = [];
				}
				else
                {                     
					if ((typeof m_strHideOperations === 'string') && (m_strHideOperations.length > 0))
					{
						//m_strHideOperations = m_strHideOperations;
						m_strHideOperations = os.str_quotes(m_strHideOperations);
						
						m_arrHideOperations = JSON.parse(m_strHideOperations);
						m_strHideOperations = JSON.stringify(m_arrHideOperations);
					}
					else
					{
						m_arrHideOperations = m_strHideOperations;
						m_strHideOperations = JSON.stringify(m_arrHideOperations);
					}
                }

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

		//m_strCurrentList = m_strEntityCode + 'list';
        
        m_strCurrentList = m_strFormEntityCode + 'list';
        //alert(m_strCurrentList);
		// set mapLayout based on mode but it can be override
		// based on entity permission
		m_arrMap[0].layout[0] = m_arrMapLayouts.mode[m_strMode];

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
		//os.element(m_strFormID, '.ge-entity-grid').height((intHeight_a - 150) + 'px');
		//os.element(m_strFormID, '.ge-status-panel').css('margin-top', (intHeight_a - 20) + 'px');

		// for datatables scrolling issue
		//var osObjGrid = os.element(m_strFormID, '.ge-entity-grid');
        
		m_objGrid.resize();
	};

	this.FormResult = function ()
	{
		return m_arrSelection;
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.FormClearSelection_onClick = function()
	{
		m_objGrid.clearSelection();
	};

	this.FormClose_onClick = function ()
	{
        m_arrSelection = [];
		os.closeForm(m_strFormID);
	};
    
	this.FormExport_onClick = function ()
	{
		exportEntityData();
	};
    
    this.Grid_onDblClick = function (arrSelection_a)
	{
        var strOperationCode = '';
        
        if( m_strFormEntityCode.length > 0 ) 
		{
            strOperationCode = m_strFormEntityCode.toUpperCase();

            if (hasOperationPermission( 'LISTITEMS_' + strOperationCode ))
            {
                invokeOperation('LISTITEMS_' + strOperationCode);
            }
            else if(hasOperationPermission( 'LISTITEMS')) 
            { 
                invokeOperation('LISTITEMS');
            }
            else if(hasOperationPermission( 'SELECT')) 
            { 
                invokeOperation('SELECT');
            }
            else if(hasOperationPermission( 'EDT_' + strOperationCode ))
            {
                invokeOperation('EDT_' + strOperationCode);
            }
            else if(hasOperationPermission( 'EDT_DFDATA' ))
            {
                invokeOperation('EDT_DFDATA');
            }
            else if(hasOperationPermission('EDT')) 
            { 
                invokeOperation('EDT');
            }
            else if(hasOperationPermission( 'EXP_DFDATA' ))
            {
                invokeOperation('EXP_DFDATA');
            }
            else if(hasOperationPermission('EXP')) 
            { 
                invokeOperation('EXP');
            }
            else if(hasOperationPermission( 'VW_' + strOperationCode ))
            {
                invokeOperation('VW_' + strOperationCode);
            }
            else if(hasOperationPermission( 'VW_DFDATA' ))
            {
                invokeOperation('VW_DFDATA');
            }
            else if(hasOperationPermission('VW')) 
            { 
                invokeOperation('VW');
            }
        }

	};
    
    this.Grid_onSelectionChange = function (arrSelection_a)
	{
		if ((m_objDock !== undefined) && (m_objDock !== null))
		{
            if (arrSelection_a.length > 0)
			{
				m_objDock.enableButtons(getRequireSelectionButtons(), 'btn-primary');
			}
			else
			{
				m_objDock.disableButtons(getRequireSelectionButtons(), 'btn-primary');
			}
            			
		}
				
	};
		
	/**
	* for reference - https://datatables.net/reference/event/pre-row-reorder
	* objElement_a - jquery object element 
	* objNode_a - current node of the start of the reorder
	* intIndex_a - current index of the start of the reorder
	*/
	
	this.Grid_onPreRowReorder = function(objElement_a, objNode_a, intIndex_a)
	{
		var objDatatable = m_objGrid.getDatatable();
				
		m_strReorderSourceID = objDatatable.row(objNode_a.index).data().id;
	};
	
	/**
	* for reference - https://datatables.net/reference/event/row-reorder
	* objElement_a - jquery object element 
	* arrDetails_a - An array of change objects for the row's how have had values effected.
	* objEditor_a - This parameter provides the information required for Editor to perform a multi-row edit
	*/
	
	this.Grid_onRowReorder = function(objElement_a, arrDetails_a, objEditor_a)
	{
		var strEntityCode = m_strFormEntityCode;		
		
		var objDatatable = m_objGrid.getDatatable();
		
		var objDataFirst = arrDetails_a[0];
		var objDataLast = arrDetails_a[arrDetails_a.length - 1];
		
		if(objDataFirst.oldData === m_strReorderSourceID)
		{			
			m_strReorderDestinationID = objDataFirst.newData;	
		}
		else if(objDataLast.oldData === m_strReorderSourceID)
		{
			m_strReorderDestinationID = objDataLast.newData;			
		}
								
		var arrParams = [ 
							{
								'name' : 'entitycode',
								'value' : strEntityCode
							},						
							{ 
								'name' : 'sourceid',
								'value' : m_strReorderSourceID
							},
							{ 
								'name' : 'destinationid',
								'value' : m_strReorderDestinationID
							}							
						];
						
		var objJSON = os.ajaxRequestCreate('entity_entityreorder', arrParams);
		
		os.ajaxCall(URL_WEBSERVICE, objJSON, function (arrResponse_a)
		{
			// call draw() or refresh() here
			m_objGrid.refresh();						
		},os.ajaxError);
	};
		
	this.btnSearchClear_onClick = function ()
	{
		m_strSearchKeyword = "";
		searchGrid();
		os.element(m_strFormID, '.ge-search-input').val("");
		os.element(m_strFormID, '.ge-search-input').focus();
	};
    
	this.SearchField_onEnterKey = function (objField_a)
	{
		var osObjField = os.element(objField_a);

		var strKeyword = osObjField.val();
		m_strSearchKeyword = massageKeyword(strKeyword);
		searchGrid();

	};

	this.btnDispatchDateFromClear_onClick = function ()
	{
		os.element(m_strFormID, '.ge-dispatchdatefrom-input').val("");
		os.element(m_strFormID, '.ge-dispatchdatefrom-input').focus();
		m_strDispatchDateFrom = "";
		searchGrid();
	};

	this.btnDispatchDateToClear_onClick = function ()
	{
		os.element(m_strFormID, '.ge-dispatchdateto-input').val("");
		os.element(m_strFormID, '.ge-dispatchdateto-input').focus();
		m_strDispatchDateTo = "";
		searchGrid();
	};

	this.btnReceiverStateClear_onClick = function ()
	{
		os.element(m_strFormID, '.ge-receiverstate-input').val("");
		os.element(m_strFormID, '.ge-receiverstate-input').focus();
		m_strReceiverState = "";
		searchGrid();
	};
	
	this.DispatchDateFrom_onChange = function(objThis_a)
	{
		m_strDispatchDateFrom = os.element(m_strFormID, '.ge-dispatchdatefrom-input').val();
		searchGrid();
	};
	
	this.DispatchDateTo_onChange = function(objThis_a)
	{
		m_strDispatchDateTo = os.element(m_strFormID, '.ge-dispatchdateto-input').val();
		searchGrid();
	};
	
	this.ReceiverState_onClick = function(objThis_a)
	{
		var objParams = { type:'form', entity:'systemform', formentity:'STATE', mode:'select', title:'To State', resultfields : ['id', 'description'] };
        os.showFormPopup('entity.frmEntityChooser', objParams, function (objResult_a)
        {
			if (objResult_a.length > 0)
			{
				m_strReceiverState = objResult_a[0].description;
				os.element(m_strFormID, '.ge-receiverstate-input').val(m_strReceiverState);
				searchGrid();
			}
        }, false, true);
	};
	
	function searchGrid()
	{
		var arrComplexFilter = [];
		
		if (m_strDispatchDateFrom && m_strDispatchDateFrom.length > 0) 
		{
			arrComplexFilter.push({ 'field': 'dispatchdatefrom', 'value': m_strDispatchDateFrom });
		}
		
		if (m_strDispatchDateTo && m_strDispatchDateTo.length > 0) 
		{
			arrComplexFilter.push({ 'field': 'dispatchdateto', 'value': m_strDispatchDateTo });
		}
		
		if (m_strReceiverState && m_strReceiverState.length > 0) 
		{
			arrComplexFilter.push({ 'field': 'receiverstate', 'value': m_strReceiverState });
		}
	
		m_objGrid.search(m_strSearchKeyword, arrComplexFilter, []);
	}

    this.SelectButton_onClick = function ()
	{
		m_arrSelection = m_objGrid.getSelection(m_arrReturns);
        
		os.closeForm(m_strFormID);
	};

	this.MyDeviceSelection_onClick = function(strData_a)
	{	
		saveUserFormDevice(strData_a);
		// this is to close the MyDevice dropdown under More dropdown. 
		//os.element('.ge-toolbardevicebutton-more-menu').parent('.btn-group').removeClass('open');
		m_myDeviceRepaintJDock = true;
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
