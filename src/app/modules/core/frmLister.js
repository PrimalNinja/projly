/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
/*jsl:import ..\..\inc-osutils-jgrids.js*/
/*jsl:import ..\..\inc-osutils-notminified.js*/

function core_frmLister(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	var m_strCurrentList = '';

	// ------------------------------------------------------------------------------------

	var m_objGridLayout;
	var m_objGridActions;
	var m_objGrid;

	var m_strServerEventTimerID;

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

	var m_arrFixedFilter = [];
	if ((m_objParameters.fixedfilter !== undefined) && (m_objParameters.fixedfilter.length > 0)) { 
		m_arrFixedFilter = JSON.parse( decodeURIComponent(m_objParameters.fixedfilter));
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
				['CloseButton', 'ViewButton', 'EditButton', 'DeleteButton']
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
		// toolbar tiles
		{
			id : 'CloseButton',
			caption : 'Close',
			classes : 'btn-success',
			permissions : [],
			action : function ()
			{
				m_objThis.FormClose_onClick();
			},
			tip : 'Click here to close the ' + m_strCurrentList + ' list.',
			type : 'toolbarbutton'
		},
		{
			id : 'DeleteButton',
			caption : 'Delete',
			classes : 'gb-cell-disabled',
			permissions : function ()
			{
				return checkAction('DeleteButton');
			},
			action : invokeAction,
			actionData : 'DeleteButton',
			tip : 'Click here to delete.',
			type : 'toolbarbutton'
		},
		{
			id : 'EditButton',
			caption : 'Edit',
			classes : 'gb-cell-disabled',
			permissions : function ()
			{
				return checkAction('EditButton');
			},
			action : invokeAction,
			actionData : 'EditButton',
			tip : 'Click here to edit.',
			type : 'toolbarbutton'
		},
		{
			id : 'ViewButton',
			caption : 'View',
			classes : 'gb-cell-disabled',
			permissions : function ()
			{
				return checkAction('ViewButton');
			},
			action : invokeAction,
			actionData : 'ViewButton',
			tip : 'Click here to view.',
			type : 'toolbarbutton'
		}

	];

	// ------------------------------------------------------------------------------------

	var m_arrGridLayouts = [
		{
			id : LIST_DATAAUDITLOGS,
			title : 'Data Audit Logs',
			ws : 'core_dataauditlogslist',
			columns : [
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
					id : "client",
					name : "Client",
					field : "client",
					sortable : true,
					editor : Slick.Editors.Text
				},
				{
					id : "user",
					name : "User",
					field : "user",
					sortable : true,
					editor : Slick.Editors.Text
				},
				{
					id : "category",
					name : "Category",
					field : "category",
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
					id : "modifydatetime",
					name : "Date Modified",
					field : "modifydatetime",
					sortable : true,
					editor : Slick.Editors.Text
				}
			]
		}
	];

	// ------------------------------------------------------------------------------------

	var strMode;
	var m_arrGridActions = [
		{
			id : LIST_DATAAUDITLOGS,
			actions : [],
			broadcasts : [
				{
					messages : ['deleted', 'edited'],
					action : 'refresh'
				}
			]
		}
	];

	// ====================================================================================
	// HELPERS ============================================================================


	function checkAction(strFunction_a)
	{
		var blnResult = false;

		processArray(m_objGridActions.actions, function (objAction_a)
		{
			if (objAction_a.id == strFunction_a)
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

			if (objAction_a.id == strFunction_a)
			{
				objResult = objAction_a;
				blnResult = true;
			}

			return blnResult;
		}
		);

		return objResult;
	}

	function getGridActions(strList_a)
	{
		var objResult = null;

		processArray(m_arrGridActions, function (objGridLayout_a)
		{
			if (objGridLayout_a.id == strList_a)
			{
				objResult = objGridLayout_a;
			}
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
				if (strAction_a === 'DeleteButton')
				{
					strPrompt = 'Continue and delete the ' + m_objGridLayout.title + ' selection?';
				}
			}

			if (blnRequiresSelection)
			{
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
									objAction.action(varSelection);
								}
								);
							}
							else
							{
								objAction.action(varSelection);
							}
						}
					}
				}
			}
			else
			{
				// functions such as add, just invoke the action
				if ($.isFunction(objAction.action))
				{
					if (strPrompt.length > 0)
					{
						os.dialogConfirm(strPrompt, function ()
						{
							objAction.action();
						}
						);
					}
					else
					{
						objAction.action();
					}
				}
			}
		}
	}

	// ====================================================================================
	// POPULATING =========================================================================

	function getGridLayout(strList_a)
	{
		var objResult = null;

		processArray(m_arrGridLayouts, function (objGridLayout_a)
		{
			if (objGridLayout_a.id == strList_a)
			{
				objResult = objGridLayout_a;
			}
		}
		);

		return objResult;
	}

	function populateDock()
	{
		m_objDock = new jDock(os,
			{
				"alwaysvisiblebuttoncount": 2,
				"map" : m_arrMap,
				"tiles" : m_arrTiles
			}
			);
		m_objDock.render(m_strFormID, '.ge-button-panel');

        m_objDock.disableButtons('ViewButton,EditButton,DeleteButton', 'disabled');
	}

	function populateList(strTitle_a, strParentIDValue_a)
	{
		var strTitle = strTitle_a;
		if (strTitle === undefined)
		{
			strTitle = '';
		}

		var strParentIDField = m_objGridLayout.parent_id;
		if (strParentIDField === undefined)
		{
			strParentIDField = '';
		}

		populateGrid(strTitle, m_objGridLayout.ws, strParentIDField, strParentIDValue_a, m_objGridLayout.columns, m_objGridLayout.returns, m_objGridLayout.params);

		if (!os.hasCapability("mobile"))
		{
			os.element(m_strFormID, '.ge-search-input').focus();
		}
	}

	function populateGrid(strTitle_a, strWebService_a, strParentIDField_a, strParentIDValue_a, objGridColumns_a, arrFields_a, arrParameters_a)
	{
		var arrFields = arrFields_a;
		if (arrFields === undefined)
		{
			arrFields = ['id'];
		}

		var objGridOptions =
		{
			autoHeight : false,// (os.hasCapability('regionscroll') === false),
			//cbOnFetchData : m_objThis.Grid_onFetchData,
			cbOnSelection : m_objThis.Grid_onDblClick,
			cbOnSelectionChange : m_objThis.Grid_onSelectionChange,
			columns : objGridColumns_a,
			//containerClass : 'gs-cellcontent-1000x500-xxx',
            filterType : 'all',
            parameters : [],
			extraParameters : [],
			//multiSelect : true,
			returnFields : arrFields,
			//showHeaderRow : true,
			webServiceFunction : strWebService_a
		};

        if(arrParameters_a !== undefined) {
           objGridOptions.parameters = arrParameters_a;
        }

		if (strParentIDField_a.length > 0)
		{
            objGridOptions.parameters = $.merge(
                            objGridOptions.parameters,
                            [
                                {
                                    "name" : strParentIDField_a,
                                    "value" : strParentIDValue_a
                                }
                            ]);
		}

		os.element(m_strFormID, '.panelHome').hide();
		os.element(m_strFormID, '.ge-entity-grid').show();

		populateDock();

        m_objGrid = new jDatatableRenderer(os, objGridOptions);

        m_objGrid.render(m_strFormID, '.ge-entity-grid');

		var strTitle = strTitle_a;
		if (strTitle.length === 0)
		{
			strTitle = m_objGridLayout.title;
		}

		os.element(m_strFormID, '.ge-form-title').text(strTitle);
		var intHeight = os.element(m_strFormID, '.gb-form').height();
		var intWidth = os.element(m_strFormID, '.gb-form').width();
		//alert(intWidth + ":" + intHeight);
		m_objThis.Form_onResize(intWidth, intHeight);
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function closed(objResponse_a)
	{
		m_objGrid.refresh();
		//m_objGrid.refresh();
		//os.dialogAlert('Completed closing of ' + m_objGridLayout.title + '.', function ()
		//{
			os.broadcast(m_strFormID, 'core', m_strCurrentList + 'closed');
		//}
		//);
	}

	function deleted(objResponse_a)
	{
		m_objGrid.refresh(false);

		//os.dialogAlert('Completed deletion of ' + m_objGridLayout.title + '.', function ()
		//{
			//os.broadcast(m_strFormID, 'core', m_strCurrentList + 'deleted');
            //doNothing();
		//}
		//);
	}

	function installed(objResponse_a)
	{
		m_objGrid.refresh();

		//os.dialogAlert('Completed install of ' + m_objGridLayout.title + '.', function ()
		//{
			os.broadcast(m_strFormID, 'core', m_strCurrentList + 'installed');
		//}
		//);
	}

	function opened(objResponse_a)
	{
		m_objGrid.refresh();

		//os.dialogAlert('Completed opening of ' + m_objGridLayout.title + '.', function ()
		//{
			os.broadcast(m_strFormID, 'core', m_strCurrentList + 'opened');
		//}
		//);
	}

	function syncDataFormsProcessed(objResponse_a)
	{
		m_objGrid.refresh();

		//os.dialogAlert('Completed processing of synced forms.', function ()
		//{
			os.broadcast(m_strFormID, 'core', m_strCurrentList + 'edited');
		//}
		//);
	}

	function syncDataFormsErrorsProcessed(objResponse_a)
	{
		m_objGrid.refresh();

		//os.dialogAlert('Completed processing of errored synced forms.', function ()
		//{
			os.broadcast(m_strFormID, 'core', m_strCurrentList + 'edited');
		//}
		//);
	}

	function syncDataFormsDeleteErrorsProcessed(objResponse_a)
	{
		m_objGrid.refresh();

		//os.dialogAlert('Completed deleting of errored synced forms.', function ()
		//{
			os.broadcast(m_strFormID, 'core', m_strCurrentList + 'deleted');
		//}
		//);
	}

	function scheduleRun(objResponse_a)
	{
		os.dialogAlert('The selected task has been run.', doNothing);
	}




	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function close1(strFunction_a, strType_a, strFilterType_a, strID_a)
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

		os.ajaxCall(URL_WEBSERVICE, objJSON, closed, os.ajaxError);
	}

	function delete1(strFunction_a, strID_a)
	{
		var objJSON = os.ajaxRequestCreate(strFunction_a,
				[
					{
						"name" : "id",
						"value" : strID_a
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, deleted, os.ajaxError);
	}

	function deleteMany(strFunction_a, arrIDList_a)
	{
		var objJSON = objJSON = os.ajaxRequestCreate(strFunction_a,
				[
					{
						"name" : "idlist",
						"value" : arrIDList_a
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, deleted, os.ajaxError);
	}

	function downloadDocument(strDocumentID_a, blnForceDownload_a)
	{
        var strURL = os.getFileDownloadURL(encodeURIComponent(strDocumentID_a));

        if(blnForceDownload_a) {
            strURL += "&download=true";
        }

		if (strURL.length > 0)
		{
			os.setUnloadPrompt(false);
			//location = strURL;
			window.open(strURL);

			// turn on the unload prompt after a timeframe because otherwise it seems to turn on before the download started
			os.after(3000, function ()
			{
				os.setUnloadPrompt(true);
			}
			);
		}
	}

	function installFileFormat()
	{
		var objParameters =
		{
			"mode" : LIST_FILEFORMATINSTALLABLE,
			"resultfields" : ['id'],
			"title" : 'Install File Format'
		};
		os.showFormPopup('core.frmRecordChooser', objParameters, function (objResult_a)
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
				os.ajaxCall(URL_WEBSERVICE, objJSON, installed, m_objThis.asyncError);
			}
		}
		);
	}

	function installProfile()
	{
		var objParameters =
		{
			"mode" : LIST_PROFILEINSTALLABLE,
			"resultfields" : ['id'],
			"title" : 'Install Profile'
		};
		os.showFormPopup('core.frmRecordChooser', objParameters, function (objResult_a)
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
				os.ajaxCall(URL_WEBSERVICE, objJSON, installed, m_objThis.asyncError);
			}
		}
		);
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

    function processSyncDataForms()
    {
		var objJSON = os.ajaxRequestCreate("core_processsyncdataforms", []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, syncDataFormsProcessed, os.ajaxError);
    }

    function processErrorsSyncDataForms()
    {
		var objJSON = os.ajaxRequestCreate("core_processerrorssyncdataforms", []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, syncDataFormsErrorsProcessed, os.ajaxError);
    }

    function processDeleteErrorsSyncDataForms()
    {
		var objJSON = os.ajaxRequestCreate("core_processdeleteerrorssyncdataforms", []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, syncDataFormsDeleteErrorsProcessed, os.ajaxError);
    }

	function runSchedule(strTypeId, strTaskId)
	{
		var objJSON = os.ajaxRequestCreate('core_runschedule', [
					{
						"name" : "tasktype_id",
						"value" : strTypeId
					},
					{
						"name" : "task_id",
						"value" : strTaskId
					}

				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, scheduleRun, os.ajaxError);
	}

	function whereIs()
	{
		var arrSelection = m_objGrid.getSelection(['id', 'suburb', 'state', 'country']);

		if (arrSelection.length > 0)
		{
			var arrClickedRow = arrSelection[0];

			var strSuburb = arrClickedRow.suburb;
			var strState = arrClickedRow.state;
			var strCountry = arrClickedRow.country;

			var strSearch = strSuburb + ', ' + strState + ', ' + strCountry;

			if (os.isModuleLoaded('maps'))
			{
				os.showForm('maps.frmMap', 'mode=search&search=' + encodeURIComponent(strSearch));
			}
			else if (os.isModuleLoaded('openmaps'))
			{
				os.showForm('openmaps.frmMap', 'mode=search&search=' + encodeURIComponent(strSearch));
			}
		}
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
		os.unbindEvents(m_strFormID, 'ge-search-input,ge-search-button,ge-searchclear-button');

		os.bindEvent(m_objThis, m_strFormID, '.ge-search-button', 'btnSearch', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-searchclear-button', 'btnSearchClear', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-search-input', 'SearchField', 'onEnterKey');
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

	this.Form_onBroadcast = function (strQueue_a, strMessage_a)
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
				(m_objParameters.formcode !== objParameters_a.formcode) ||
				(m_objParameters.formentity !== objParameters_a.formentity) ||
				(m_objParameters.formentityid !== objParameters_a.formentityid) ||
				(m_objParameters.mode !== objParameters_a.mode) ||
				(m_objParameters.searchkeyword !== objParameters_a.searchkeyword) ||
				(m_objParameters.title !== objParameters_a.title) ||
				(m_objParameters.type !== objParameters_a.type))
			{
				m_objParameters = objParameters_a;

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

				m_arrFixedFilter = [];
                if ((m_objParameters.fixedfilter !== undefined) && (m_objParameters.fixedfilter.length > 0)) { 
                    m_arrFixedFilter = JSON.parse( decodeURIComponent(m_objParameters.fixedfilter));
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

		// initial state
		m_strCurrentList = m_objParameters.mode;
		m_objGridLayout = getGridLayout(m_strCurrentList);
		m_objGridActions = getGridActions(m_strCurrentList);

		populateList(m_objParameters.title, m_objParameters.parent_id);
		setTabOrder();
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.FormTitle_onClick = function ()
	{
		os.formToFront(m_strFormID);
	};

	this.Form_onResize = function(intWidth_a, intHeight_a)
	{
		//alert(intWidth_a + ":" + intHeight_a);

        os.element(m_strFormID, '.ge-entity-grid').width((intWidth_a - 20) + 'px');
		os.element(m_strFormID, '.ge-entity-grid').height((intHeight_a - 150) + 'px');
		//os.element(m_strFormID, '.ge-status-panel').css('margin-top', (intHeight_a - 20) + 'px');

		// for datatables scrolling issue
		var osObjGrid = os.element(m_strFormID, '.ge-entity-grid');

		m_objGrid.resize();
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

	this.Grid_onDblClick = function (arrSelection_a)
	{
		if (checkAction('EditButton'))
		{
			invokeAction('EditButton');
		}
        else if (checkAction('LISTITEMS'))
        {
            invokeAction('LISTITEMS');
        }
        else if (checkAction('EDT')) {
            invokeAction('EDT');
        }
		else
		{
			invokeAction('ViewButton');
		}
	};

	this.Grid_onFetchData = function (objData_a)
	{
		var strMessage = '';

		if (objData_a === undefined || objData_a === null || objData_a.length === 0)
		{
			strMessage = 'No records shown.';
		}
		else
		{
			var intGridRows = objData_a.length;
			var intTotalRows = objData_a[0].recordcount;
			var intRowLimit = objData_a[0].limited;

			strMessage = 'Showing ' + intGridRows + ' of ' + intTotalRows + ' records (max. ' + intRowLimit + ' records are shown at a time).';
		}
	};

	this.Grid_onSelectionChange = function (arrSelection_a)
	{
		if ((m_objDock !== undefined) && (m_objDock !== null))
		{
			if (arrSelection_a.length > 0)
			{
				m_objDock.enableButtons('ViewButton,EditButton,DeleteButton', 'gs-darkblue-background-colour gs-glow-focusborder');
			}
			else
			{
				m_objDock.disableButtons('ViewButton,EditButton,DeleteButton', 'gs-darkblue-background-colour gs-glow-focusborder');
			}
		}
	};

	this.btnSearch_onClick = function ()
	{
		var strKeyword = os.element(m_strFormID, '.ge-search-input').val();
		strKeyword = massageKeyword(strKeyword);
		m_objGrid.search(strKeyword);
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
		strKeyword = massageKeyword(strKeyword);
		m_objGrid.search(strKeyword);

	};

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,CloseButton,ViewButton,EditButton,DeleteButton,ge-tab-end');
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
