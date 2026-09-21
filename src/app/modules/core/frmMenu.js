/*jsl:option explicit*/
/*jsl:import ..\..\inc-nav.js*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/

function core_frmMenu(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var nav = new JNav(os, 'bootstrap');
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	// ------------------------------------------------------------------------------------

	var m_objDock;
	var m_arrRecent = ['RecentItem1Button', 'RecentItem2Button', 'RecentItem3Button', 'RecentItem4Button', 'RecentItem5Button', 'RecentItem6Button', 'RecentItem7Button'];
	
	var m_intHeight = 0;

	var m_arrMap = nav.getMap('menu');
	
	// ====================================================================================
	// HELPERS ============================================================================

	function adjustFormHeight(intHeight_a)
	{  
		var intHeight = os.element(m_strFormID, '.ge-menu-panel').height();
		if (intHeight > m_intHeight)
		{
			os.element(m_strFormID, '.ge-navbar').css('overflow-y', 'scroll');
		}
		else
		{
			os.element(m_strFormID, '.ge-navbar').css('overflow-y', 'hidden');
		}
	}

	function isDeveloper()
	{
		//return (hasPermission(['DEVELOPER']) && DEVELOPER === 'TRUE');
		return (DEVELOPER === 'TRUE');
	}

	// ====================================================================================
	// POPULATING =========================================================================

	function populateDock()
	{
		os.registerMenu(m_objThis, m_strFormID);
		
		m_objDock = new jDock(os,
			{
                "addtorecent" : false, // for menu. turning it on will collapse the menu when clicking submenu for the first time
				"cbOnRender" : adjustFormHeight,
				"closeonselect": os.hasCapability("mobile"),
				"map" : m_arrMap,
                "mode" : "verticalmenu",
				"renderblanks": true,
				"tiles" : nav.getTiles(),
				"recent" : m_arrRecent
			}
			);
		m_objDock.render(m_strFormID, '.ge-menu-panel');
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function logoutSuccess(objResponse_a)
	{
		os.logout('', 'frmMenu');
	}

	function returnSuccess(objResponse_a)
	{
		os.clientReturn('', 'frmMenu');
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function logout()
	{
		var objJSON = os.ajaxRequestCreate('public_logout', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, logoutSuccess, os.ajaxError);
	}

	function clientReturn()
	{
		var objJSON = os.ajaxRequestCreate('public_return', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, returnSuccess, os.ajaxError);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// defaulting
		os.element(m_strFormID, '.ge-form-title').text('');

		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form,gb-formtitle-inner-panel');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_canClose = function ()
	{
		return (m_arrMap.length === 0);
	};

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

	this.Form_onBroadcast = function (strQueue_a, strMessage_a)
	{
		if ((strQueue_a === 'core') && (strMessage_a === 'loginFormSuccess'))
		{
			// reinitialise the menu
			m_arrRecent = ['RecentItem1Button', 'RecentItem2Button', 'RecentItem3Button', 'RecentItem4Button', 'RecentItem5Button', 'RecentItem6Button', 'RecentItem7Button'];
			m_intHeight = 0;
			m_arrMap = nav.getMap('menu');
			populateDock();
		}
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

	this.Form_onFocus = function ()
	{
		setTabOrder();
		if (os.hasCapability('mobile'))
		{
			doNothing();
		}
		else
		{
			if (os.toBoolean(os.getProperty('public')))
			{
				os.hideMenu();
				os.destroyMenu();
			}
			else
			{
				if (os.toBoolean(os.getProperty('isindividual')))
				{
					os.hideMenu();
				}
				else
				{
					os.showMenu();
				}
			}
		}
	};

	this.Form_onFocusLost = function ()
	{};

	this.Form_onLoad = function ()
	{
		if (m_arrMap.length === 0)
		{
			os.closeForm(m_strFormID);
		}
		else
		{
			// defaulting
			if (os.isModuleLoaded('widgettube') && os.hasCapability('tube'))
			{
				os.element(m_strFormID, '.gb-form').removeClass('gi-background-form');
				os.element(m_strFormID, '.gb-form').addClass('gi-background-transparent');
			}

			// bindings
			bindGlobals();

			//fetchDynamicButtons();

			// initial state
            populateDock();
			os.createMenu();
		}
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.Form_onResize = function (intWidth_a, intHeight_a)
	{  
		m_intHeight = intHeight_a;
		
        os.element(m_strFormID, '.gb-form').height(m_intHeight + 'px');
		os.element(m_strFormID, '.ge-navbar').height((m_intHeight - 4) + 'px');

		adjustFormHeight();
	};
	
	this.FormTitle_onClick = function ()
	{
		os.formToFront(m_strFormID);
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.setTabOrder(m_strFormID, 'ge-tab-start,ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		doNothing();
	};

	this.TabStart_onFocus = function ()
	{
		doNothing();
	};
}
