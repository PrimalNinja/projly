/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\dash\wgtChart.js*/
function dash_frmSecurity(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
    var m_intToFetch = 0;
	var m_intFetched = 0;
    var m_intErrors = 0;
    
	// ------------------------------------------------------------------------------------

	var m_intVerticalChartCount = 2;
	var m_intVerticalChartHeight = 500;
    
    var m_arrCharts = [];

	var m_objWgtChart1;
	var m_objWgtChart2;
	var m_objWgtChart3;
	var m_objWgtChart4;

	// ====================================================================================
	// HELPERS ============================================================================

	function initialiseForm()
	{
		// defaulting
		var strFormTitle = m_objParameters.title;

		os.element(m_strFormID, '.ge-form-title').text(strFormTitle);
	}
    
    function initWidgets() 
	{		
    	m_objWgtChart1 = new dash_wgtChart(os, m_strFormID, { chart: m_arrCharts['logins_past4weeks'] });
        m_objWgtChart2 = new dash_wgtChart(os, m_strFormID, { chart: m_arrCharts['login_failed_past2weeks'] });
        m_objWgtChart3 = new dash_wgtChart(os, m_strFormID, { chart: m_arrCharts['registrations_all'] });
        m_objWgtChart4 = new dash_wgtChart(os, m_strFormID, { chart: m_arrCharts['registrations_past4weeks'] });
    }

	function populateForm()
	{
        initWidgets();
		populateWidgets();
        registerBroadcaster();
        initialiseForm();
	}

    function populateWidgets()
	{
		m_objWgtChart1.render(".ge-cell-1-1");
		m_objWgtChart2.render(".ge-cell-1-2");
		m_objWgtChart3.render(".ge-cell-2-1");
        m_objWgtChart4.render(".ge-cell-2-2");
    }

    function registerBroadcaster() 
	{
        os.registerServerEvent('dashboard', m_strFormID);
        os.enableServerEventQueue('dasboard');
        os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerbroadcaster', [{ "name" : "eventqueue", "value" : "dashboard" }]), doNothing, doNothing);
        os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerlistener', [{ "name" : "eventqueue", "value" : "dashboard" }]), doNothing, doNothing);
    }
    
    function asyncDataIsFetched()
	{
		m_intFetched++;
		if (m_intToFetch === m_intFetched)
		{
			populateForm();
		}
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================
    
    function chartsFetched(objResponse_a) 
	{ 
        m_arrCharts = objResponse_a;

        asyncDataIsFetched();
    }
        
	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================
    
    function fetchData(blnFetchData_a)
	{
		if (blnFetchData_a)
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;
            
            getCharts();
		}
		else
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;

			populateForm();
		}
	}
    
    function getCharts() {
        
        var objJSON = os.ajaxRequestCreate('security_fetchcharts',[]);
        os.ajaxCall(URL_WEBSERVICE, objJSON, chartsFetched, os.ajaxError, doNothing, true);                
    }
    
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
		return true;
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
		bindGlobals();
		//populateForm();
        fetchData(true);
		setTabOrder();
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.Form_onResize = function(intWidth_a, intHeight_a)
	{
		var intHeight = os.getFormCanvasHeight(m_strFormID) - 20;
		var intContentHeight = m_intVerticalChartHeight * m_intVerticalChartCount;

		os.element(m_strFormID, '.ge-content-panel').height((intHeight) + 'px');
		os.element(m_strFormID, '.ge-thecontent').height((intContentHeight) + 'px');
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