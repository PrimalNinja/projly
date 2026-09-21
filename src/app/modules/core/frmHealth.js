/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function core_frmHealth(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	
	// ------------------------------------------------------------------------------------

	var m_blnIsBatchClient = os.toBoolean(os.getProperty('batch'));
	var m_blnIsDefaultClient = os.toBoolean(os.getProperty('default'));
	var m_blnIsEmployer = os.toBoolean(os.getProperty('isemployer'));
	var m_blnIsIndividual = os.toBoolean(os.getProperty('isindividual'));
	var m_blnIsOwnerClient = os.toBoolean(os.getProperty('owner'));
	var m_blnIsPublic = os.toBoolean(os.getProperty('public'));
	var m_blnIsDeveloper = os.toBoolean(os.getProperty('developer'));
	var m_blnIsSysAdmin = os.toBoolean(os.getProperty('sysadmin'));
	
	var m_blnIsMobile = os.hasCapability('mobile');
	var m_blnJavaCapable = os.isModuleLoaded("widgetprintertray");
	
	os.after(1000, function()
	{
		$('#tubular-container').remove();
	});

	//if (!os.isMDI())
	//{
		//os.element(m_strFormID, '.gb-form-close').removeClass('gi-form-close');
	//}
	
    var m_blnForceVerticalScroll = ((TESTSCROLL == 'TRUE')  && !os.hasCapability('regionscroll') && os.hasCapability('mobile'));
    if (m_blnForceVerticalScroll)
    {        
        os.element(m_strFormID, '.ge-content-panel').removeClass('gb-scrollable-panel');
        os.element(m_strFormID, '.ge-thecontent').removeClass('gb-scrollable-content');
        os.element(m_strFormID, '.gb-form').removeClass('gb-resizable');

        os.element(m_strFormID).css( { 'position' : 'relative', 'height' : 'auto' });                    
    }
    
	// ====================================================================================
	// HELPERS ============================================================================

	function closeMe()
	{
		os.closeForm(m_strFormID);
	}

	// ====================================================================================
	// POPULATING =========================================================================

	function populateForm()
	{
		var strBuild = 'Build: ' + APP_SHORT_NAME + ' ' + APP_VERSION + '.';
		var strBrowser = 'OS: ' + os.getOS() + '. Browser: ' + os.getBrowser().name + ' version ' + os.getBrowserVersion() + ' (' + os.getBrowser().major + ').';
		strBrowser += ' Screen size: ' + screen.width + 'x' + screen.height + '.';
		if (os.hasCapability('viewport'))
		{
			strBrowser += ' Viewport size: ' + os.getViewPort().width + 'x' + os.getViewPort().height + '.';
		}
		//strBrowser +=  ' Zoom ' + os.zoom() + '%.';
		strBrowser += ' Support for your browser is ' + os.getBrowser().support + '. ' + os.getBrowserRecommendation();

		var strHealth = os.getHealth();
		var strInCapabilities = os.getBrowser().incapabilities;
 		var strAgent = 'Agent: ' + os.getAgent();
		var strMapsProvider = os.getMapProvider();
		if (strMapsProvider.length === 0)
		{
			strMapsProvider = 'none';
		}
		var strPrinterProvider = os.getPrinterProvider();
		if (strPrinterProvider.length === 0)
		{
			strPrinterProvider = 'none';
		}
		var strSpeechProvider = os.getSpeechProvider();
		if (strSpeechProvider.length === 0)
		{
			strSpeechProvider = 'none';
		}

		var strOther = "Map Provider: " + strMapsProvider + ", Printer Provider: " + strPrinterProvider + ", Speech Provider: " + strSpeechProvider;

		os.element(m_strFormID, '.ge-health-content').html('<font color="black">&nbsp;' + htmlEncode(strBuild) + '<br><br>&nbsp;' + htmlEncode(strBrowser) + '<br>&nbsp;' + htmlEncode(strAgent) + '<br>&nbsp;' + htmlEncode(strOther) + '<br><br>&nbsp;' + strInCapabilities + '<br>&nbsp;' + strHealth + '</font>');

        if(m_blnForceVerticalScroll)
        {            
            os.element(m_strFormID, '.gb-form').addClass('gs-content-autoheight');                             
        }
        else
        {
            os.resizeDockedForm(m_strFormID);
        }
	}
	
	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form');
		os.unbindEvents(m_strFormID, 'gb-form-close');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		//os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

	this.Form_canClose = function ()
	{
		return true; //os.isMDI();
	};

	this.Form_onBroadcast = function (strQueue_a, strMessage_a)
	{
		if (((strQueue_a === 'orientation') || (strQueue_a === 'viewport')) && (strMessage_a === 'change'))
		{
			m_objThis.Form_onResize();
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

	this.Form_onFocus = function ()
	{
		setTabOrder();
		m_objThis.Form_onResize();
	};

	this.Form_onLoad = function ()
	{
		// bindings
		bindGlobals();
		populateForm();
		setTabOrder();
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.Form_onResize = function (intWidth_a, intHeight_a)
	{
		var intHeight = os.getFormCanvasHeight(m_strFormID) - 20;
        
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

	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

	// ====================================================================================
	// OTHER EVENTS =======================================================================
	
	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,ge-close-button,ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		//os.element(m_strFormID, '.cmdClose').focus();
	};

	this.TabStart_onFocus = function ()
	{
		//os.element(m_strFormID, '.cmdClose').focus();
	};
}
