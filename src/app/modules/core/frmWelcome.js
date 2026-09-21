/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\dash\wgtChart.js*/
function core_frmWelcome(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	// ------------------------------------------------------------------------------------

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

	function populateForm()
	{
		populateWidgets();
        registerBroadcaster();
	}

    function populateWidgets() 
	{
    }

    function registerBroadcaster() 
	{
        os.registerServerEvent('dashboard', m_strFormID);
        os.enableServerEventQueue('dasboard');
        os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerbroadcaster', [{ "name" : "eventqueue", "value" : "dashboard" }]), doNothing, doNothing);
        os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerlistener', [{ "name" : "eventqueue", "value" : "dashboard" }]), doNothing, doNothing);
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
		return os.isMDI();
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
		if (os.isMDI())
		{
			// resize the form
			var intViewPortWidth = os.getViewPort().width;
			var intViewPortHeight = os.getViewPort().height;
			os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);
			os.formToFront(m_strFormID, false);
		}

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

		os.setTabOrder(m_strFormID, 'ge-tab-start,ge-tab-end');
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