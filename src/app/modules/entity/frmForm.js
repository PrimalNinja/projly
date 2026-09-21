/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
/*jsl:import ..\..\inc-osutils-jformrenderer.js*/
/*jsl:import ..\..\inc-os.js*/
function entity_frmForm(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	var m_objFormLogic = null;
//alert(JSON.stringify(objParameters_a));
//logDebug('debug:' + JSON.stringify(objParameters_a));
	// ------------------------------------------------------------------------------------

	var m_blnReadonly = false;
	var m_blnFormDirty = false;

	var m_intToFetch = 0;
	var m_intFetched = 0;
	var m_intErrors = 0;

	var m_objJSONData = null;
	var m_strFormFields = 'ge-form-title';
	var m_strModifyUser = '';
	var m_strModifyDateTime = '';

	var m_objJFormRenderer = null;

	//var strIsPrevOrNext = '';
	var m_strHistoryID = '';
	var m_strPrevHistoryID = '';
	var m_strNextHistoryID = '';
	var m_strAllowNextButton = 'N';

	var m_blnClickedSaveAndClose = false;
    
    var m_arrOperations = [];
console.log("ENTITY_FRMFORM:" + JSON.stringify(objParameters_a));
	// ------------------------------------------------------------------------------------
				
	var m_strEntityCode = m_objParameters.entity; // eg: systemform or dataform
	if (m_strEntityCode === undefined)
	{
		m_strEntityCode = '';
	}

	var m_strFormCode = m_objParameters.formcode; // form code to fetch
	if (m_strFormCode === undefined)
	{
		m_strFormCode = '';
	}

	var m_strFormEntityCode = m_objParameters.formentity; // eg: kl1 (for koala form 1)
	if (m_strFormEntityCode === undefined)
	{
		m_strFormEntityCode = '';
	}

	var m_strFormEntityDescription = m_objParameters.formentitydescription; // eg: koalaform
	if (m_strFormEntityDescription === undefined)
	{
		m_strFormEntityDescription = '';
	}

	var m_strFormEntityID = m_objParameters.formentityid; // id for koalaform
	if (m_strFormEntityID === undefined)
	{
		m_strFormEntityID = '';
	}

	var m_blnForReview = os.toBoolean(m_objParameters.forreview); //are we opening the form for review purposes (ie no new button)
	if (m_blnForReview === undefined)
	{
		m_blnForReview = false;
	}

	var m_strID = m_objParameters.id;
	if (m_strID === undefined)
	{
		m_strID = '';
	}

	var m_strCode = m_objParameters.code;
	if (m_strCode === undefined)
	{
		m_strCode = '';
	}

	var m_strMode = m_objParameters.mode; // add, edit or view mode
	if (m_strMode === undefined)
	{
		m_strMode = '';
	}

	var m_blnUseLatestForm = m_objParameters.uselatestform;
	if (m_blnUseLatestForm === "true")
	{
		m_blnUseLatestForm = true;
	}
	else if (m_blnUseLatestForm === "false")
	{
		m_blnUseLatestForm = false;
	}
	else if (m_blnUseLatestForm === undefined)
	{
		m_blnUseLatestForm = true;
	}

	var m_strFormTitle = m_objParameters.title; // optional form title
	if (m_strFormTitle === undefined)
	{
		m_strFormTitle = ""; // + ' ' + m_strFormEntityCode;
	}
	
	if (m_strFormTitle.length === 0)
	{
		m_strFormTitle = m_strFormEntityDescription;
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
//alert(JSON.stringify(m_objParameters));

    var m_blnForceVerticalScroll = ((TESTSCROLL == 'TRUE')  && !os.hasCapability('regionscroll') && os.hasCapability('mobile'));
    if (m_blnForceVerticalScroll)
    {        
        os.element(m_strFormID, '.ge-content-panel').removeClass('gb-scrollable-panel');
        os.element(m_strFormID, '.ge-thecontent').removeClass('gb-scrollable-content');
        os.element(m_strFormID, '.gb-form').removeClass('gb-resizable');

        os.element(m_strFormID).css( { 'position' : 'relative', 'height' : 'auto' });                    
    }
    
    // for button Save & New
    var m_blnEnableSaveAndNew = false;
    var m_blnEnableControlToggleSaveAndNew = true;
    if(m_objParameters.saveandnew !== undefined && m_objParameters.saveandnew.length > 0)
    {
        m_blnEnableControlToggleSaveAndNew = os.toBoolean(m_objParameters.saveandnew);
    }
	
	var arrDefaults = m_objParameters.defaults;
	if (arrDefaults === undefined)
	{
		arrDefaults = [];
	}
    
	// ------------------------------------------------------------------------------------

	var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : [
				['CloseButton', 'SaveButton','SaveCloseButton', 'SaveNewButton', 'NewButton', 'PrintPreviewButton', 'PrintButton']
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
			classes : 'gs-green-background-colour gs-glow-focusborder',
			permissions : [],
			action : function()
			{
				m_objThis.FormClose_onClick();
			},
			actionData : null,
			tip : 'Click here to close the form.',
			type : 'toolbarbutton'
		},
		{
			id : 'NewButton',
			caption : 'New',
			classes : 'gs-darkblue-background-colour gs-glow-focusborder',
			//permissions : [ 'ADD_DFDATA', 'ADD_' + m_strFormEntityCode.toUpperCase() ],
			permissions : function() {
                return hasPermission(['ADD_DFDATA','ADD_' + m_strFormEntityCode.toUpperCase()]);                
            },
			action : function()
			{
				m_objThis.NewButton_onClick();
			},
			actionData : null,
			tip : 'Click here to create a new data.',
			type : 'toolbarbutton'
		},
		{
			id : 'SaveButton',
			caption : 'Save',
			classes : '',
			//permissions : [ 'ADD_DFDATA',  'EDT_DFDATA', 'ADD_' + m_strFormEntityCode.toUpperCase(), 'EDT_' + m_strFormEntityCode.toUpperCase() ],
            permissions : function() {
                return hasPermission(['ADD_DFDATA','EDT_DFDATA','EDT_' + m_strFormEntityCode.toUpperCase()]);
            },
            action : function()
			{
				m_objThis.SaveButton_onClick();
			},
			actionData : null,
			tip : 'Click here to save.',
			type : 'toolbarbutton'
		},
		{
			id : 'SaveCloseButton',
			caption : 'Save &amp; Close',
			classes : '',
			//permissions : [ 'ADD_DFDATA',  'EDT_DFDATA', 'ADD_' + m_strFormEntityCode.toUpperCase(), 'EDT_' + m_strFormEntityCode.toUpperCase() ],
			permissions : function() {
                return hasPermission(['ADD_DFDATA','EDT_DFDATA','EDT_' + m_strFormEntityCode.toUpperCase()]);
            },
			action : function()
			{
				m_objThis.SaveCloseButton_onClick();
			},
			actionData : null,
			tip : 'Click here to save &amp; close.',
			type : 'toolbarbutton'
        },
        {
			id : 'SaveNewButton',
			caption : 'Save &amp; New',
			classes : '',
			//permissions : [ 'ADD_DFDATA',  'EDT_DFDATA', 'ADD_' + m_strFormEntityCode.toUpperCase(), 'EDT_' + m_strFormEntityCode.toUpperCase() ],
			permissions : function() {
                return hasPermission(['ADD_DFDATA','EDT_DFDATA']) || (hasPermission(['ADD_' + m_strFormEntityCode.toUpperCase()]) && hasPermission(['EDT_' + m_strFormEntityCode.toUpperCase()]));
            },
			action : function()
			{
				m_objThis.SaveNewButton_onClick();
			},
			actionData : null,
			tip : 'Click here to save &amp; close.',
			type : 'toolbarbutton'
		},
		{
			id : 'PrevVersionButton',
			caption : 'Previous version',
			classes : '',
			permissions : [],
			action : function()
			{
				m_objThis.PrevVersionButton_onClick();
			},
			actionData : null,
			tip : 'Click here to view previous version.',
			type : 'toolbarbutton'
		},
		{
			id : 'NextVersionButton',
			caption : 'Next version',
			classes : '',
			permissions : [],
			action : function()
			{
				m_objThis.NextVersionButton_onClick();
			},
			actionData : null,
			tip : 'Click here to view next version.',
			type : 'toolbarbutton'
		},
		{
			id : 'PrintPreviewButton',
			caption : 'Print Preview',
			classes : 'gs-darkblue-background-colour gs-glow-focusborder',
			permissions : function()
			{
				return false; //isDeveloper();	// REPORT POC button
			},
			action : function()
			{
				m_objThis.PrintPreviewButton_onClick();
			},
			actionData : null,
			tip : 'Click here to print preview.',
			type : 'toolbarbutton'
		},
		{
			id : 'PrintButton',
			caption : 'Print',
			classes : 'gs-darkblue-background-colour gs-glow-focusborder',
			permissions : function()
			{
				return false; //isDeveloper();	// REPORT POC button
			},
			action : function()
			{
				m_objThis.PrintButton_onClick();
			},
			actionData : null,
			tip : 'Click here to print.',
			type : 'toolbarbutton'
		}
	];

	// ====================================================================================
	// HELPERS ============================================================================

	function afterAjaxError()
	{
		m_objThis.setDirty(true);
	}

	function closeAfterSave()
	{
		var blnResult = false;

		if(ENABLE_SAVEANDCLOSE === 'TRUE')
		{
			if(m_blnClickedSaveAndClose)
			{
				blnResult = true;
				m_blnClickedSaveAndClose = false;
			}
		}

		return blnResult;
    }
    
    function newAfterSave()
    {
        var blnResult = false;

        if(m_blnEnableSaveAndNew)
        {
            blnResult = true;
        }

        return blnResult;
    }
    
	function initialiseForm()
	{
		// defaulting
		m_blnReadonly = false;

        if ((m_strMode === 'html') || (m_strMode === 'view') || (m_strMode === 'viewonly'))
		{
            m_blnReadonly = true;
        }
        
		var objOptions =
		{
			cbRenderComplete : m_objThis.renderComplete,
			cbSetDirty : m_objThis.setDirty,
			flags: m_objParameters.flags,
			formified: false,
			htmlmode: false, //(m_strMode === 'html'),
			printpreview : false,
			readonly : m_blnReadonly,
			style : 'accordion'
		};

		m_objJFormRenderer = new jFormRenderer(os, objOptions, m_objThis);

        initialiseLayout();
        
		//os.element(m_strFormID, '.gb-overflow').css('height', '600px');
		//os.element(m_strFormID, '.gb-overflow').css('overflow-y', 'scroll');
		//os.element(m_strFormID, '.gb-overflow').css('overflow-x', 'hidden');

    }
    
    function initialiseLayout()
    {
        var arrLayout = [['CloseButton', 'SaveButton', 'NewButton', 'PrintPreviewButton', 'PrintButton']];

        if(m_blnEnableSaveAndNew)
        {
            arrLayout = [['CloseButton', 'SaveNewButton', 'NewButton', 'PrintPreviewButton', 'PrintButton']];
        }
		else if (ENABLE_SAVEANDCLOSE === 'TRUE')
		{            
			arrLayout = [['CloseButton', 'SaveCloseButton', 'NewButton', 'PrintPreviewButton', 'PrintButton']];
		}

		if ((m_strMode === 'html') || (m_strMode === 'view'))
		{

			if (hasPermission(['HIST_DATAFORM']))
			{
				arrLayout = [['CloseButton', 'SaveButton', 'NewButton', 'PrintPreviewButton', 'PrintButton', 'PrevVersionButton', 'NextVersionButton']];

                if(m_blnEnableSaveAndNew)
                {
                    arrLayout = [['CloseButton', 'SaveNewButton', 'NewButton', 'PrintPreviewButton', 'PrintButton', 'PrevVersionButton', 'NextVersionButton']];
                }
				else if (ENABLE_SAVEANDCLOSE === 'TRUE')
				{
					arrLayout = [['CloseButton', 'SaveCloseButton', 'NewButton', 'PrintPreviewButton', 'PrintButton', 'PrevVersionButton', 'NextVersionButton']];
				}
			}
        }
        else if(m_strMode === 'viewonly')
        {
            arrLayout = [['CloseButton', 'PrintPreviewButton', 'PrintButton']];
        }

		if (m_blnForReview)
		{
			if (hasPermission(['HIST_DATAFORM']))
			{
				arrLayout = [['CloseButton', 'SaveButton', 'PrintPreviewButton', 'PrintButton', 'PrevVersionButton', 'NextVersionButton']];

                if(m_blnEnableSaveAndNew)
                {
                    arrLayout = [['CloseButton', 'SaveNewButton', 'PrintPreviewButton', 'PrintButton', 'PrevVersionButton', 'NextVersionButton']];
                }
				else if (ENABLE_SAVEANDCLOSE === 'TRUE')
				{
					arrLayout = [['CloseButton', 'SaveCloseButton', 'PrintPreviewButton', 'PrintButton', 'PrevVersionButton', 'NextVersionButton']];
				}
			}
			else
			{
				arrLayout = [['CloseButton', 'SaveButton', 'PrintPreviewButton', 'PrintButton']];

                if(m_blnEnableSaveAndNew)
                {
                    arrLayout = [['CloseButton', 'SaveNewButton', 'PrintPreviewButton', 'PrintButton']];
                }
				else if (ENABLE_SAVEANDCLOSE === 'TRUE')
				{
					arrLayout = [['CloseButton', 'SaveCloseButton', 'PrintPreviewButton', 'PrintButton']];
				}
			}
        }        
        
        m_arrMap = [
			{
				location : 'root',
				title : 'Home',
				layout : arrLayout
			}
		];
    }

	function isDeveloper()
	{
		return (hasPermission(['DEVELOPER']) && DEVELOPER === 'TRUE');
		//return (DEVELOPER === 'TRUE');
	}

	function saveData()
	{
		var strErrors = validate();

		if (strErrors.length > 0)
		{
			// have an error, so display it
			os.dialogAlertScroll(strErrors, function()  {}
			);
			m_blnClickedSaveAndClose = false;
		}
		else
		{
			m_objThis.setDirty(false);
			saveData2();
		}
	}

	function saveData2()
	{

		if (m_strMode === 'add')
		{
			addFormData(formDataAdded);

		}
		else if (m_strMode === 'edit')
		{
			updateDataForm(dataFormUpdated);
		}
	}

	function validate()
	{

		var strError = m_objJFormRenderer.validate();

		if (strError.length > 0)
		{
			strError = '<ul>' + strError + '</ul>';
		}

		return strError;
	}

	function toggleVersionButtons()
	{

		if (m_strPrevHistoryID.length > 0)
		{
			m_objDock.enableButtons('PrevVersionButton', 'gs-darkblue-background-colour gs-glow-focusborder');
		}
		else
		{
			m_objDock.disableButtons('PrevVersionButton', 'gs-darkblue-background-colour');
		}

		if (m_strNextHistoryID.length > 0 || m_strAllowNextButton === "Y")
		{
			m_objDock.enableButtons('NextVersionButton', 'gs-darkblue-background-colour gs-glow-focusborder');
		}
		else
		{
			m_objDock.disableButtons('NextVersionButton', 'gs-darkblue-background-colour');
		}

	}

	// ====================================================================================
	// POPULATING =========================================================================

	function populateDock()
	{
		m_objDock = new jDock(os,
			{
				//"alwaysvisiblebuttoncount": 2,
                //"enablemobiledropdown" : true,
				"map" : m_arrMap,
				"tiles" : m_arrTiles
			});

		//if (m_strMode !== 'html')
		//{
			m_objDock.render(m_strFormID, '.ge-button-panel');
            m_objThis.setDirty(m_blnFormDirty);
		//}

		//if ((m_objParameters.mode === 'edit') || (m_objParameters.mode === 'view'))
		//{
			//m_objDock.enableButtons('CloseButton,SaveButton,NewButton,PrintPreviewButton,PrintButton,PrevVersionButton,NextVersionButton', 'celldarkblue glowfocusborder');
		//}
		//else
		//{
			//m_objDock.disableButtons('CloseButton,SaveButton,NewButton,PrintPreviewButton,PrintButton,PrevVersionButton,NextVersionButton', 'celldarkblue glowfocusborder');
		//}
	}

	function sanitizeHTMLEmptyImage(){
		os.element(m_strFormID, '.ge-renderer-content').find('img[src="images/noimage.png"]').addClass('ge-noimage');
		os.element(m_strFormID, '.ge-noimage').parent('a').unbind('click');
	}

	function populateForm()
	{
        populateDock();

	    m_objJFormRenderer.setID(m_strFormEntityID);
		m_objJFormRenderer.setDataID(m_strID);
		m_objJFormRenderer.render(m_strFormID, '.ge-renderer-content', m_blnReadonly);

		sanitizeHTMLEmptyImage();

		var strHTMLModified = '';
		if (m_strMode !== 'html' && m_strModifyUser.length > 0)
		{
			strHTMLModified = '<strong>Last Modified on </strong>' + m_strModifyDateTime + ' by ' + m_strModifyUser;
		}
		var strDataCode = m_objJFormRenderer.getSectionFieldByType('DATA', 'CODE');

		var strFormTitle = '';
		if (m_strMode === "add")
        {
            strFormTitle = m_strFormEntityDescription + ' - New';
        }
        else if (m_strMode === "edit")
        {
            strFormTitle = m_strFormTitle + ' - Edit ' + strDataCode;
        }
        else if (m_strMode === "view" || m_strMode === 'viewonly')
        {
            strFormTitle = m_strFormTitle + ' - View ' + strDataCode;
        }
        else { // html or any other code
            strFormTitle = m_strFormTitle;
        }

        os.element(m_strFormID, '.ge-form-title').text(htmlEncode(strFormTitle));

        os.element(m_strFormID, '.ge-lastmodified-field').html(strHTMLModified);

        if (m_blnEnableControlToggleSaveAndNew && m_strMode === 'add' && hasPermission([ 'ADD_DFDATA', 'ADD_' + m_strFormEntityCode.toUpperCase() ]))
        {
            os.element(m_strFormID, '.ge-liner-savenewtoggle').show();
            os.element(m_strFormID, '.ge-savenewtoggle-text-entityname').html(htmlEncode(m_strFormEntityDescription));

            // unbindings
            os.unbindEvents(m_strFormID, 'ge-fieldcontrol-savenandnew');
            // bindings            
            os.bindEvent(m_objThis, m_strFormID, '.ge-fieldcontrol-savenandnew', 'FieldControlSaveAndNewToggle', 'onChange');
        }

		// bind fields
		bindFormRenderer();

		// enabling/disabling prev/next button
		toggleVersionButtons();

		os.unbindEvents(m_strFormID, 'ge-cancel-button');

        os.bindEvent(m_objThis, m_strFormID, '.ge-cancel-button', 'CancelButton', 'onClick');
        
        if(m_blnForceVerticalScroll)
        {            
            os.element(m_strFormID, '.gb-form').addClass('gs-content-autoheight');                             
        }
        else
        {
            os.resizeDockedForm(m_strFormID);
        }
		
		// populate defaults
		processArray(arrDefaults, function(arrDefault)
		{
			var strSection = arrDefault.section;
			var strField = arrDefault.field;
			var strValue = arrDefault.value;
			var strDescription = arrDefault.description;
			
			if (strValue.length > 0)
			{
				var strMassagedField = os.massageClassName(strSection, strField);

				if (strDescription.length > 0)
				{
					os.element(m_strFormID, '.' + strMassagedField + 'epv').val(strValue);
					os.element(m_strFormID, '.' + strMassagedField + 'tds').val(strDescription);
					os.element(m_strFormID, '.' + strMassagedField + 'epd').val(strDescription);
				}
				else
				{
					os.element(m_strFormID, '.' + strMassagedField).val(strValue);
				}
			}
		});

		// setup taborder
		setTabOrder();
		m_objThis.setDirty(false);
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function asyncDataIsFetched()
	{
		m_intFetched++;
		if (m_intToFetch == m_intFetched)
		{
			populateForm();
		}
	}

	function asyncError()
	{
		if (m_intErrors === 0)
		{
			os.ajaxError();
		}
		m_intErrors++;
	}

	function dataFormUpdated(objResponse_a)
	{
		os.broadcast(m_strFormID, m_objParameters.type, m_strFormEntityCode + 'list' + 'edited', m_strID);
		if (closeAfterSave())
		{
			os.closeForm(m_strFormID);
        }
        else if(newAfterSave())
        {
            m_objThis.NewButton_onClick();
        }
		else
		{
			fetchData(true);
		}
	}

	function formDataAdded(objResponse_a)
	{
		m_strID = objResponse_a[0].id;
		os.broadcast(m_strFormID, m_objParameters.type, m_strFormEntityCode + 'list' + 'added', m_strID);
		if (closeAfterSave())
		{
			os.closeForm(m_strFormID);
        }
        else if(newAfterSave())
        {
            m_objThis.NewButton_onClick();
        }
		else
		{
			m_strMode = 'edit';
			fetchData(true);
		}
	}

	function formDataFetched(objResponse_a)
	{
		os.hideBusyIndicator();

		m_objJFormRenderer.clear();
		m_objJFormRenderer.append(objResponse_a[0].jsondata);

		m_strModifyUser = objResponse_a[0].modifyuser;
		m_strModifyDateTime = objResponse_a[0].modifydatetime;

		m_strPrevHistoryID = objResponse_a[0].previd;
		m_strNextHistoryID = objResponse_a[0].nextid;

		asyncDataIsFetched();
	}

	function formFetched(objResponse_a)
	{
		os.hideBusyIndicator();

		m_objJFormRenderer.clear();
		m_objJFormRenderer.append(objResponse_a[0].jsondata);
		m_strFormEntityID = objResponse_a[0].id;

		asyncDataIsFetched();
    }
    
    function operationsFetched(objResponse_a)
	{

		m_arrOperations = objResponse_a;

        asyncDataIsFetched();
        
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function addFormData(cb_a)
	{
		var strSubmitToClient = '';

		// PARAMETERS: entitycode, formentitycode, jsondata, submittoclientid (o)
		var objJSON = os.ajaxRequestCreate('entity_formdataadd',
				[
					{
						"name" : "entitycode",
						"value" : m_strEntityCode
					},
					{
						"name" : "formentitycode",
						"value" : m_strFormEntityCode
					},
					{
						"name" : "submittoclientid",
						"value" : strSubmitToClient
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
						"name" : "relationship",
						"value" : m_strRelationship
					},
					{
						"name" : "jsondata",
						"value" : m_objJFormRenderer.getData()
					}

				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, cb_a, os.ajaxError, afterAjaxError);
	}

	function fetchData(blnFetchForm_a)
	{
		if (blnFetchForm_a)
		{
			os.showBusyIndicator();

			m_intToFetch = 2;
			m_intFetched = 0;
			m_intErrors = 0;

            fetchOperations();

			if (m_strMode === 'add')
			{
                m_intToFetch++;
				fetchForm();
				fetchLogic();
			}
			else if (m_strMode === 'edit' || m_strMode === 'html' || m_strMode === 'view' || m_strMode === 'viewonly')
			{
                m_intToFetch++;
				fetchFormData();
				fetchLogic();
			}
		}
		else
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;

			populateForm();
			fetchLogic();
		}
	}

	function fetchForm()
	{
		// PARAMETERS: entitycode, entitydataid (o), formcode (o)
		var objJSON = os.ajaxRequestCreate('entity_formfetch',
				[
					{
						"name" : "entitycode",
						"value" : m_strEntityCode
					},
					{
						"name" : "entitydataid",
						"value" : m_strFormEntityID
					},
					{
						"name" : "formcode",
						"value" : m_strFormCode
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
						"name" : "relationship",
						"value" : m_strRelationship
					},
					{
						"name" : "mode",
						"value" : m_strMode
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, formFetched, asyncError, afterAjaxError);
	}

	function fetchFormData()
	{
		var strUseLatestForm = 'Y';

		if ((m_strMode === 'html') || (m_strMode === 'view') || (m_strMode === 'viewonly') || (m_blnUseLatestForm === false))
		{
			strUseLatestForm = 'N';
		}

		var strSubmitToClient = '';

		// PARAMETERS: entitycode, formentitycode, uselatestform, formentitydataid (o), submittoclientid (o), relativeid, relative, relationship ,  historyid (o)
		var objJSON = os.ajaxRequestCreate('entity_formdatafetch',
				[
					{
						"name" : "entitycode",
						"value" : m_strEntityCode
					},
					{
						"name" : "formentitycode",
						"value" : m_strFormEntityCode
					},
					{
						"name" : "uselatestform",
						"value" : strUseLatestForm
					},
					{
						"name" : "formentitydataid",
						"value" : m_strID
					},
					{
						"name" : "formentitydatacode",
						"value" : m_strCode
					},
					{
						"name" : "submittoclientid",
						"value" : strSubmitToClient
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
						"name" : "relationship",
						"value" : m_strRelationship
					},
					{
						"name" : "historyid",
						"value" : m_strHistoryID
					},
					{
						"name" : "mode",
						"value" : m_strMode
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, formDataFetched, asyncError, afterAjaxError);
    }
    
	function fetchLogic()
	{
		function logicFetched(blnSuccess_a)
		{
			if (blnSuccess_a)
			{
				var strLogicName = 'entityform_' + m_strFormEntityCode.toLowerCase();
				m_objFormLogic = new window[strLogicName](objOS_a, strFormID_a, objParameters_a);
			}
			asyncDataIsFetched();
		}
		
		os.lazyLoadLogic(m_strFormEntityCode, logicFetched);
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

	function updateDataForm(cb_a)
	{
		var strSubmitToClient = '';

		// PARAMETERS: entitycode, formentitycode, jsondata, formentitydataid (o), submittoclientid (o)
		var objJSON = os.ajaxRequestCreate('entity_formdataupdate',
				[
					{
						"name" : "entitycode",
						"value" : m_strEntityCode
					},
					{
						"name" : "formentitycode",
						"value" : m_strFormEntityCode
					},
					{
						"name" : "formentitydataid",
						"value" : m_strID
					},
					{
						"name" : "submittoclientid",
						"value" : strSubmitToClient
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
						"name" : "relationship",
						"value" : m_strRelationship
					},
					{
						"name" : "jsondata",
						"value" : m_objJFormRenderer.getData()
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, cb_a, os.ajaxError, afterAjaxError);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================


	function bindFormRenderer()
	{
		// unbindings
		os.unbindEvents(m_strFormID, m_strFormFields);

		// dirty bindings
		os.onDirty(m_strFormID, m_strFormFields, function()
		{
			m_objThis.setDirty(true);
		}
		);
	}

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form-print,gb-form,gb-formtitle-inner-panel');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-print', 'FormPrint', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
        os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
	}

	// ====================================================================================
	// RENDERER EVENTS ====================================================================

	this.Renderer_onEvent = function(strType_a, strFormID_a, strLocator_a, strEventName_a)
	{
		if (m_objFormLogic !== null)
		{
			if ($.isFunction(m_objFormLogic.Renderer_onEvent))
			{
				m_objFormLogic.Renderer_onEvent(strType_a, strFormID_a, strLocator_a, strEventName_a);
			}
		}
	};

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function()
	{
		return os.isMDI();
	};

	this.Form_canClose = function()
	{
		return true;
	};

	this.Form_getDesktopRegion = function()
	{
		return getFormDesktopRegion(m_strFormEntityCode);
	};

	this.Form_isDirty = function()
	{
		return m_blnFormDirty;
	};

	this.Form_onBroadcast = function (strQueue_a, strMessage_a, objMessageData_a)
	{
	};

	// on_click gives form the focus, setup all the tabs
	this.Form_onClick = function()
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
				(m_objParameters.flags !== objParameters_a.flags) ||
				(m_objParameters.formcode !== objParameters_a.formcode) ||
				(m_objParameters.formentity !== objParameters_a.formentity) ||
				(m_objParameters.formentitydescription !== objParameters_a.formentitydescription) ||
				(m_objParameters.formentityid !== objParameters_a.formentityid) ||
				(m_objParameters.forreview !== objParameters_a.forreview) ||
				(m_objParameters.id !== objParameters_a.id) ||
				(m_objParameters.mode !== objParameters_a.mode) ||
				(m_objParameters.title !== objParameters_a.title))
			{
				m_objParameters = objParameters_a;

				m_strEntityCode = m_objParameters.entity; // eg: systemform or dataform
				if (m_strEntityCode === undefined)
				{
					m_strEntityCode = '';
				}

				m_strFormCode = m_objParameters.formcode; // form code to fetch
				if (m_strFormCode === undefined)
				{
					m_strFormCode = '';
				}

				m_strFormEntityCode = m_objParameters.formentity; // eg: koalaform
				if (m_strFormEntityCode === undefined)
				{
					m_strFormEntityCode = '';
				}

				m_strFormEntityDescription = m_objParameters.formentitydescription; // eg: koalaform
				if (m_strFormEntityDescription === undefined)
				{
					m_strFormEntityDescription = '';
				}

				m_strFormEntityID = m_objParameters.formentityid; // id for koalaform
				if (m_strFormEntityID === undefined)
				{
					m_strFormEntityID = '';
				}

				m_blnForReview = os.toBoolean(m_objParameters.forreview); //are we opening the form for review purposes (ie no new button)
				if (m_blnForReview === undefined)
				{
					m_blnForReview = false;
				}

				m_strID = m_objParameters.id;
				if (m_strID === undefined)
				{
					m_strID = '';
				}

				m_strCode = m_objParameters.code;
				if (m_strCode === undefined)
				{
					m_strCode = '';
				}

				m_strMode = m_objParameters.mode; // add, edit or view mode
				if (m_strMode === undefined)
				{
					m_strMode = '';
				}

				m_blnUseLatestForm = m_objParameters.uselatestform;
				if (m_blnUseLatestForm === "true")
				{
					m_blnUseLatestForm = true;
				}
				else if (m_blnUseLatestForm === "false")
				{
					m_blnUseLatestForm = false;
				}
				else if (m_blnUseLatestForm === undefined)
				{
					m_blnUseLatestForm = true;
				}

				m_strFormTitle = m_objParameters.title; // optional form title
				if (m_strFormTitle === undefined)
				{
					m_strFormTitle = ''; // + ' ' + m_strFormEntityCode;
				}

				if (m_strFormTitle.length === 0)
				{
					m_strFormTitle = m_strFormEntityDescription;
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
				
				arrDefaults = m_objParameters.defaults;
				if (arrDefaults === undefined)
				{
					arrDefaults = [];
				}

				// same as Form_onLoad
				m_objThis.Form_onLoad();
				//os.resizeDockedForm(m_strFormID);
			}
		}

		setTabOrder();
		//if (m_strMode === 'html')
		//{
			//os.closeExclusive(m_strFormID);
		//}
	};

	this.Form_onLoad = function()
	{
		m_blnReadonly = false;

		initialiseForm();
		//populateDock();
		bindGlobals();

		fetchData(true);
	};

	this.Form_onPermissionCheck = function()
	{
		return true;
	};

	this.FormTitle_onClick = function()
	{
		os.formToFront(m_strFormID);
	};

	this.Form_onResize = function (intWidth_a, intHeight_a)
	{
		//var intHeight = 0;

		//if (m_strMode === 'html')
		//{
			//intHeight = intHeight_a - 10;
		//}
		//else
		//{
//			intHeight = intHeight_a - 140;
		//}

		//os.element(m_strFormID, '.ge-panel-content').height((intHeight) + 'px');
		//os.element(m_strFormID, '.ge-panel-overflow').height('100%');
		//os.element(m_strFormID, '.ge-panel-overflow').css('overflow-y','auto');

		var intHeight = os.getFormCanvasHeight(m_strFormID) - 80;
        
        if (m_blnForceVerticalScroll)
        {        
            doNothing();
        }        
        else
        {
            os.element(m_strFormID, '.ge-content-panel').height((intHeight) + 'px');
            os.element(m_strFormID, '.ge-thecontent').height('100%');
        }
	};

	this.renderComplete = function()
	{
		// bind form logic
		if (m_objFormLogic !== null)
		{
			if ($.isFunction(m_objFormLogic.onRegister))
			{
				m_objFormLogic.onRegister(m_objThis, m_objJFormRenderer);
			}
		}
	};
	
	this.setDirty = function (blnDirty_a)
	{
		m_blnFormDirty = blnDirty_a;

		if (m_blnReadonly)
		{
			m_objDock.disableButtons('SaveButton', 'gs-darkblue-background-colour gs-glow-focusborder gs-red-background-colour gs-glow-dirty');
			m_objDock.disableButtons('SaveCloseButton', 'gs-darkblue-background-colour gs-glow-focusborder gs-red-background-colour gs-glow-dirty');
			m_objDock.disableButtons('SaveNewButton', 'gs-darkblue-background-colour gs-glow-focusborder gs-red-background-colour gs-glow-dirty');
		}
		else
		{
			if (m_blnFormDirty)
			{
				m_objDock.disableButtons('PrintPreviewButton,PrintButton', 'gs-darkblue-background-colour');
				m_objDock.enableButtons('SaveButton', 'gs-red-background-colour gs-glow-focusborder gs-glow-dirty');
				m_objDock.enableButtons('SaveCloseButton', 'gs-red-background-colour gs-glow-focusborder gs-glow-dirty');
				m_objDock.enableButtons('SaveNewButton', 'gs-red-background-colour gs-glow-focusborder gs-glow-dirty');
			}
			else
			{
				m_objDock.disableButtons('SaveButton', 'gs-red-background-colour gs-glow-dirty');
				m_objDock.disableButtons('SaveCloseButton', 'gs-red-background-colour gs-glow-dirty');
				m_objDock.disableButtons('SaveNewButton', 'gs-red-background-colour gs-glow-dirty');
				m_objDock.enableButtons('PrintPreviewButton,PrintButton', 'gs-darkblue-background-colour gs-glow-focusborder');
			}
		}
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.CancelButton_onClick = function()
	{
		if (m_objThis.Form_isDirty())
		{
			os.dialogConfirm('Are you sure you want to continue?<br><br>Your changes have not been saved. To stay on the form so that you can save your changes, click Cancel.', function()
			{
				m_objThis.setDirty(false);
				os.closeForm(m_strFormID);
			}
			);
		}
		else
		{
			os.closeForm(m_strFormID);
		}
	};

	this.FormClose_onClick = function()
	{
		os.closeForm(m_strFormID);
	};

	this.FormPrint_onClick = function()
	{
		var objElementToPrint = os.element(m_strFormID, '.ge-thecontent').clone();
		var objIFrame = $('<iframe id="print-iframe" style="display:none;"></iframe>');
		$('body').append(objIFrame);
		var objIFrameDoc = objIFrame[0].contentDocument || objIFrame[0].contentWindow.document;
		objIFrameDoc.body.appendChild(objElementToPrint[0]);
		objIFrame[0].contentWindow.print();
		objIFrame.remove();
	};

	this.NewButton_onClick = function()
	{
		if (m_objThis.Form_isDirty())
		{
			if ((m_strMode !== 'html') && (m_strMode !== 'view') && (m_strMode !== 'viewonly'))
			{
				os.dialogConfirm('Are you sure you want to continue?<br><br>Your changes have not been saved. To stay on the form so that you can save your changes, click Cancel.', function()
				{
					m_blnReadonly = false;
					m_strID = '';
					m_strMode = 'add';
					fetchData(true);
					os.element('.ge-thecontent').animate({ scrollTop: (0) }, 'slow');
				}
				);
			}
			else
			{
				m_blnReadonly = false;
				m_strID = '';
				m_strMode = 'add';
				fetchData(true);
			}

		}
		else
		{
			m_blnReadonly = false;
			m_strID = '';
			m_strMode = 'add';
			fetchData(true);
		}
	};

	this.SaveButton_onClick = function()
	{

		if (m_objThis.Form_isDirty())
		{

			try
			{
				saveData();
			}
			catch (err)
			{
				m_objThis.setDirty(true);
			}
		}
		//else
		//{
		//saveData(); // temporary here until dirty form is working
		//os.closeForm(m_strFormID);
		//}
	};

	this.SaveCloseButton_onClick = function()
	{
		m_blnClickedSaveAndClose = true;

		if (m_objThis.Form_isDirty())
		{

			try
			{
				saveData();
			}
			catch (err)
			{
				m_objThis.setDirty(true);
			}
		}
    };
    
    this.SaveNewButton_onClick = function()
	{
		m_blnClickedSaveAndClose = false;

		if (m_objThis.Form_isDirty())
		{
			try
			{
				saveData();
			}
			catch (err)
			{
				m_objThis.setDirty(true);
			}
		}
	};

	this.PrintPreviewButton_onClick = function()
	{
		if (!m_objThis.Form_isDirty())
		{
			printPreviewFormReport(true);
		}
	};

	this.PrintButton_onClick = function()
	{
		if (!m_objThis.Form_isDirty())
		{
			printPreviewFormReport(false);
		}
	};

	function printPreviewFormReport(blnPreview_a)
	{
        // var strFormReportHTML = m_objJFormRenderer.getHTML(false);

		// function formReportPrintPreview(objResponse_a)
		// {
			// var strID = objResponse_a.tag;
			// var strURL = APP_DOMAIN_PATH + objResponse_a.path + objResponse_a.tag + '.html';

			// os.showForm('core.frmPrintPreview',
			// {
				// "title" : 'Form Report',
				// "url" : strURL,
				// "hidden" : !blnPreview_a
			// }
			// );
		// }

		// var objJSON = os.ajaxRequestCreate("print_printstuff",
				// [
					// {
						// "name" : "printjobs",
						// "value" :
						// [
							// {
								// "type" : "formreport",
								// "preview" : "Y",
								// "filter" :
								// [
									// {
										// "field" : "formreport_id",
										// "value" : ''
									// },
									// {
										// "field" : "html",
										// "value" : B64.encode(strFormReportHTML)
									// }
								// ]
							// }
						// ]
					// }
				// ]);

		// os.ajaxCall(URL_WEBSERVICE, objJSON, formReportPrintPreview, os.ajaxError);
	}

	this.NextVersionButton_onClick = function()
	{

		if (m_strNextHistoryID.length > 0 || m_strAllowNextButton === "Y")
		{

			if (m_strNextHistoryID.length === 0)
			{
				m_strAllowNextButton = "N";
			}

			m_strHistoryID = m_strNextHistoryID;

			fetchData(true);

		}
	};

	this.PrevVersionButton_onClick = function()
	{

		if (m_strPrevHistoryID.length > 0)
		{

			m_strAllowNextButton = "Y";
			m_strHistoryID = m_strPrevHistoryID;

			fetchData(true);
		}
    };
    
    this.FieldControlSaveAndNewToggle_onChange = function(objThis_a)
    {
        if(objThis_a.checked)
        {
            m_blnEnableSaveAndNew = true;
        }
        else
        {
            m_blnEnableSaveAndNew = false;
        }

        initialiseLayout();
        populateDock();

    };

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,CloseButton,NewButton,SaveButton,SaveCloseButton,PrevVersionButton,NextVersionButton,' + m_strFormFields + ',ge-tab-end');
	}

	this.TabEnd_onFocus = function()
	{
		os.element(m_strFormID, '.CloseButton').focus();
	};

	this.TabStart_onFocus = function()
	{
		os.element(m_strFormID, '.ge-code-field').focus();
	};
}
