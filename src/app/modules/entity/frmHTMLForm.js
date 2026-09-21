/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
/*jsl:import ..\..\inc-osutils-jformrenderer.js*/
/*jsl:import ..\..\inc-os.js*/
function entity_frmHTMLForm(objOS_a, strFormID_a, objParameters_a)
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

	var m_intToFetch = 0;
	var m_intFetched = 0;
	var m_intErrors = 0;

	var m_strFormFields = 'ge-form-title';
	var m_objJSONData = null;
	var m_strModifyUser = '';
	var m_strModifyDateTime = '';

	var m_objJFormRenderer = null;

	//var strIsPrevOrNext = '';
	var m_strHistoryID = '';
	var m_strPrevHistoryID = '';
	var m_strNextHistoryID = '';
	var m_strAllowNextButton = 'N';

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
	// ------------------------------------------------------------------------------------

	var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : [
				['CloseButton', 'PrintPreviewButton', 'PrintButton']
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
			action : function ()
			{
				m_objThis.FormClose_onClick();
			},
			actionData : null,
			tip : 'Click here to close the form.',
			type : 'toolbarbutton'
		},
		{
			id : 'PrevVersionButton',
			caption : 'Previous version',
			classes : 'gb-cell-disabled',
			permissions : [],
			action : function ()
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
			classes : 'gb-cell-disabled',
			permissions : [],
			action : function ()
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
			permissions : function ()
			{
				return false; //isDeveloper();
			},
			action : function ()
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
			permissions : function ()
			{
				return false; //isDeveloper();
			},
			action : function ()
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
		doNothing();
	}

	function initialiseForm()
	{
		// defaulting
		m_blnReadonly = false;

		var arrLayout = [['CloseButton', 'PrintPreviewButton', 'PrintButton']];

		m_blnReadonly = true;

		if (hasPermission(['HIST_DATAFORM']))
		{
			arrLayout = [['CloseButton', 'PrintPreviewButton', 'PrintButton', 'PrevVersionButton', 'NextVersionButton']];
		}

		if (m_blnForReview)
		{
			if (hasPermission(['HIST_DATAFORM']))
			{
				arrLayout = [['CloseButton', 'PrintPreviewButton', 'PrintButton', 'PrevVersionButton', 'NextVersionButton']];
			}
			else
			{
				arrLayout = [['CloseButton', 'PrintPreviewButton', 'PrintButton']];
			}

		}

		var objOptions =
		{
			flags: m_objParameters.flags,
			formified: false,
			htmlmode: true,
			printpreview : false,
			readonly : m_blnReadonly,
			style : 'accordion'
		};

		m_objJFormRenderer = new jFormRenderer(os, objOptions, m_objThis);

		m_arrMap = [
			{
				location : 'root',
				title : 'Home',
				layout : arrLayout
			}
		];

		//os.element(m_strFormID, '.gb-overflow').css('height', '600px');
		//os.element(m_strFormID, '.gb-overflow').css('overflow-y', 'scroll');
		//os.element(m_strFormID, '.gb-overflow').css('overflow-x', 'hidden');

	}

	function isDeveloper()
	{
		return (hasPermission(['DEVELOPER']) && DEVELOPER === 'TRUE');
		//return (DEVELOPER === 'TRUE');
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
				"alwaysvisiblebuttoncount": 2,
                "enablemobiledropdown" : true,
				"map" : m_arrMap,
				"tiles" : m_arrTiles
			});

		//if (m_strMode !== 'html')
		//{
			m_objDock.render(m_strFormID, '.ge-button-panel');
		//}

		//if ((m_objParameters.mode === 'edit') || (m_objParameters.mode === 'view'))
		//{
			//m_objDock.enableButtons('CloseButton,PrintPreviewButton,PrintButton,PrevVersionButton,NextVersionButton', 'celldarkblue glowfocusborder');
		//}
		//else
		//{
			//m_objDock.disableButtons('CloseButton,PrintPreviewButton,PrintButton,PrevVersionButton,NextVersionButton', 'celldarkblue glowfocusborder');
		//}
	}

	function sanitizeHTMLEmptyImage(){
		os.element(m_strFormID, '.ge-renderer-content').find('img[src="images/noimage.png"]').addClass('ge-noimage');
		os.element(m_strFormID, '.ge-noimage').parent('a').unbind('click');
	}

	function populateForm()
	{
	    m_objJFormRenderer.setID(m_strFormEntityID);
		m_objJFormRenderer.setDataID(m_strID);
		m_objJFormRenderer.render(m_strFormID, '.ge-renderer-content', m_blnReadonly);

		sanitizeHTMLEmptyImage();

		var strHTMLModified = '';
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
        else if (m_strMode === "view")
        {
			if (m_objParameters.title.length > 0)
			{
				strFormTitle = m_objParameters.title;
			}
			else
			{
				strFormTitle = m_strFormTitle + ' - View ' + strDataCode;
			}
        }
        else { // html or any other
            strFormTitle = m_strFormTitle;
        }
        
        os.element(m_strFormID, '.ge-form-title').text(htmlEncode(strFormTitle));

        os.element(m_strFormID, '.ge-lastmodified-field').html(strHTMLModified);

		// bind fields
		bindFormRenderer();

		// bind form logic
		if ($.isFunction(m_objFormLogic.onRegister))
		{
			m_objFormLogic.onRegister(m_objThis, m_objJFormRenderer);
		}

		// enabling/disabling prev/next button
		toggleVersionButtons();

		os.unbindEvents(m_strFormID, 'ge-cancel-button');

		os.bindEvent(m_objThis, m_strFormID, '.ge-cancel-button', 'CancelButton', 'onClick');
		os.resizeDockedForm(m_strFormID);

		// setup taborder
		setTabOrder();
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

	function formDataFetched(objResponse_a)
	{
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
		m_objJFormRenderer.clear();
		m_objJFormRenderer.append(objResponse_a[0].jsondata);
		m_strFormEntityID = objResponse_a[0].id;

		asyncDataIsFetched();
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function fetchData(blnFetchForm_a)
	{
		if (blnFetchForm_a)
		{
			m_intToFetch = 2;
			m_intFetched = 0;
			m_intErrors = 0;

			fetchFormData();
			fetchLogic();
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
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, formFetched, asyncError, afterAjaxError);
	}

	function fetchFormData()
	{
		var strUseLatestForm = 'N';
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

	// ====================================================================================
	// BINDINGS ===========================================================================


	function bindFormRenderer()
	{
		// unbindings
		os.unbindEvents(m_strFormID, m_strFormFields);
	}

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form,gb-formtitle-inner-panel');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
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
		return "C";
	};
	
	this.Form_isDirty = function ()
	{
		return false;
	};

	this.Form_onBroadcast = function (strQueue_a, strMessage_a, objMessageData_a)
	{
		//if ((strQueue_a === 'viewport') && (strMessage_a === 'change'))
		//{
			//os.resizeDockedForm(m_strFormID);
		//}
		doNothing();
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
	
	this.Form_onLoad = function ()
	{
		m_blnReadonly = false;

		initialiseForm();
		populateDock();
		bindGlobals();

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
		//var intContentHeight = 100;

		os.element(m_strFormID, '.ge-content-panel').height((intHeight) + 'px');
		os.element(m_strFormID, '.ge-thecontent').height('100%');
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.CancelButton_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};
	
	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

	this.PrintPreviewButton_onClick = function ()
	{
		printPreviewFormReport(true);
	};

	this.PrintButton_onClick = function ()
	{
		printPreviewFormReport(false);
	};

	function printPreviewFormReport(blnPreview_a)
	{
		var strFormReportHTML = m_objJFormRenderer.getHTML(false);

		function formReportPrintPreview(objResponse_a)
		{
			var strID = objResponse_a.tag;
			var strURL = APP_DOMAIN_PATH + objResponse_a.path + objResponse_a.tag + '.html';

			os.showForm('core.frmPrintPreview',
			{
				"title" : 'Form Report',
				"url" : strURL,
				"hidden" : !blnPreview_a
			}
			);
		}

		var objJSON = os.ajaxRequestCreate("print_printstuff",
				[
					{
						"name" : "printjobs",
						"value" :
						[
							{
								"type" : "formreport",
								"preview" : "Y",
								"filter" :
								[
									{
										"field" : "formreport_id",
										"value" : ''
									},
									{
										"field" : "html",
										"value" : B64.encode(strFormReportHTML)
									}
								]
							}
						]
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, formReportPrintPreview, os.ajaxError);
	}

	this.NextVersionButton_onClick = function ()
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

	this.PrevVersionButton_onClick = function ()
	{

		if (m_strPrevHistoryID.length > 0)
		{

			m_strAllowNextButton = "Y";
			m_strHistoryID = m_strPrevHistoryID;

			fetchData(true);
		}
	};

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,CloseButton,PrevVersionButton,NextVersionButton,' + m_strFormFields + ',ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		os.element(m_strFormID, '.CloseButton').focus();
	};

	this.TabStart_onFocus = function ()
	{
		os.element(m_strFormID, '.ge-code-field').focus();
	};
}