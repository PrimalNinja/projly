/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
/*jsl:import ..\..\inc-os.js*/
function core_frmWikiForm(objOS_a, strFormID_a, objParameters_a)
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

	var m_strFormFields = 'ge-form-title';
	var m_strModifyUser = '';
	var m_strModifyDateTime = '';

	// ------------------------------------------------------------------------------------

	var m_strWikiCode = m_objParameters.wiki; // eg: systemform or dataform
	if (m_strWikiCode === undefined)
	{
		m_strWikiCode = '';
	}

	var m_strFormTitle = m_objParameters.title; // optional form title
	if (m_strFormTitle === undefined)
	{
		m_strFormTitle = ""; // + ' ' + m_strFormEntityCode;
	}
    
	var m_strWikiCodeHome = m_strWikiCode;
	var m_strFormTitleHome = m_strFormTitle;
	
	var m_strWikiContent = "";

//alert(JSON.stringify(m_objParameters));
	// ------------------------------------------------------------------------------------

	var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : [
				['CloseButton', 'HomeButton']
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
			tip : 'Click here to close the help form.',
			type : 'toolbarbutton'
		},
		{
			id : 'HomeButton',
			caption : 'Home',
			classes : 'gs-darkblue-background-colour gs-glow-focusborder',
			permissions : [],
			action : function ()
			{
				m_objThis.HomeButton_onClick();
			},
			actionData : null,
			tip : 'Click here to return to the home page of this help.',
			type : 'toolbarbutton'
		}
	];

	// ====================================================================================
	// HELPERS ============================================================================

	function afterAjaxError()
	{
		doNothing();
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

		m_objDock.render(m_strFormID, '.ge-button-panel');
	}

	function populateForm()
	{
        os.element(m_strFormID, '.ge-form-title').text(htmlEncode(m_strFormTitle));

		var arrPlaceholders = [
			{ placeholder: "%APPNAME%", value: APP_NAME }
		];
		
		//var strInternalPageURL = 'ws/server.php?token=' + SECURITY_TOKEN + '&wiki=';
		var strInternalPageURL = 'wiki=';
		var strInternalImageURL = 'wiki/images/';
		var strHtml = cyborgWikiToHtml(m_strWikiContent, strInternalPageURL, strInternalImageURL, arrPlaceholders);
		os.element(m_strFormID, ".ge-thecontent").html(strHtml);
			
		os.unbindEvents(m_strFormID, 'ge-cancel-button');

		os.bindEvent(m_objThis, m_strFormID, '.ge-cancel-button', 'CancelButton', 'onClick');
		os.resizeDockedForm(m_strFormID);

		os.unbindEvents(m_strFormID, 'gecw-internallink');
		os.bindEvent(m_objThis, m_strFormID, '.gecw-internallink', 'InternalLink', 'onClick');

		// setup taborder
		setTabOrder();
	}

	this.InternalLink_onClick = function(objThis_a, objElement_a, objEvent_a) 
	{
		m_strWikiCode = objElement_a.attr('wiki');
		m_strFormTitle = objElement_a.attr('title');
		fetchData();
	};

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function formFetched(objResponse_a)
	{
		m_strWikiContent = objResponse_a[0].wiki;

		populateForm();
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function fetchData()
	{
		// PARAMETERS: entitycode, entitydataid (o), formcode (o)
		var objJSON = os.ajaxRequestCreate('public_fetchwiki',
				[
					{
						"name" : "wiki",
						"value" : m_strWikiCode
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, formFetched, os.error, os.afterAjaxError);
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
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		//return false;
		return os.isMDI();
	};

	this.Form_canClose = function ()
	{
		return true;
	};

	this.Form_getDesktopRegion = function()
	{
		return "R";
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
			if ((m_objParameters.wiki !== objParameters_a.wiki) ||
				(m_objParameters.title !== objParameters_a.title))
			{
				m_objParameters = objParameters_a;

				m_strWikiCode = m_objParameters.wiki; // eg: systemform or dataform
				if (m_strWikiCode === undefined)
				{
					m_strWikiCode = '';
				}

				m_strFormTitle = m_objParameters.title; // optional form title
				if (m_strFormTitle === undefined)
				{
					m_strFormTitle = ''; // + ' ' + m_strFormEntityCode;
				}

				m_strWikiCodeHome = m_strWikiCode;
				m_strFormTitleHome = m_strFormTitle;

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

		populateDock();
		bindGlobals();

		fetchData();
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

	this.HomeButton_onClick = function ()
	{
		m_strWikiCode = m_strWikiCodeHome;
		m_strFormTitle = m_strFormTitleHome;
		fetchData();
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