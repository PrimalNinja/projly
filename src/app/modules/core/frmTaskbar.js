/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/

function core_frmTaskbar(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	var PUBLICHIDEHAMBURGER = true;

	// ------------------------------------------------------------------------------------

	var m_objHamburgerDock;
	var m_objMenuDock;
	var m_objSignupDock;

	var m_blnLogin = false;
	var m_strLoginAs = os.getProperty('loginas');
	var m_strClientName = os.getProperty('clientname');
	var m_strUserName = os.getProperty('username');

	var m_blnIsMobile = os.hasCapability('mobile');

	var m_strDesktopFormConnote = '';
	if (!m_blnIsMobile)
	{
		m_strDesktopFormConnote = 'fms.frmConnote';
	}

	var m_arrMenuMap = [
		{
			location : 'hamburger',
			title : 'Home',
			layout : [
				['HamburgerMenu', 'SearchMenuItem', 'MyMessagesMenuItem']
			]
		},
		{
			location : 'menu',
			title : 'Home',
			layout : [
				['SearchMenuItem768', 'MyMessagesMenuItem768', 'FormSupportMenuItem768', 'LogoutMenuItem768', 'ReturnMenuItem768']
			]
		},
		{
			location : 'signup',
			title : 'Home',
			layout : [
				['FormSupportMenuItem', 'JoinMenuItem', 'LoginMenuItem', 'LogoutMenuItem', 'ReturnMenuItem']
			]
		},
		// 768 is the mobile version
		{
			location : 'signup768',
			title : 'Home',
			layout : [
				['HamburgerMenu768']
			]
		}
	];

	var m_arrMenuTiles = [];

	// ------------------------------------------------------------------------------------

	var m_strServerEventTimerID;

	var m_strMenuClasses = 'ge-burgermenu gi-burgermenu gs-burgermenu';
	if (os.toBoolean(os.getProperty('public')) && PUBLICHIDEHAMBURGER)
	{
		m_strMenuClasses = 'ge-burgermenu gi-burgermenu gs-burgermenu hidden';
		// os.hideMenu();
		// os.destroyMenu(m_strFormID);
	}

	// ====================================================================================
	// HELPERS ============================================================================

	function initialiseDocks()
	{
		m_arrMenuTiles = m_arrMenuTiles = [
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
		// tiles
		{
			id : 'HamburgerMenu',
			caption : '',
			classes : m_strMenuClasses,
			permissions : [],
			action : m_objThis.Burger_onClick,
			tip : 'Click here to open the menu.',
			type : 'hamburgermenu'
		},
		{
			id : 'MyMessagesMenuItem',
			caption : '<i class="glyphicon glyphicon glyphicon-envelope"></i>&nbsp;My Messages&nbsp;<span class="ge-unread-badge badge gb-hidden"></span>',
			classes : 'gs-transparent-background-colour gs-menuitem',
			permissions : function ()
			{
				return !os.toBoolean(os.getProperty('public')) && hasPermission(['VW_ACCOUNT']) && hasPermission(['VW_INTERNALMESSAGE']);
			},
			action : openMyMessages,
			tip : 'Click here to see your messages.',
			type : 'menuitem'
		},
		{
			id : 'SearchMenuItem',
			caption : '<div class="input-group" style="width:150px"><input type="text" class="form-control ge-search-input" placeholder="Search..." style="xwidth:100px"/><span class="input-group-btn"><button class="btn btn-default ge-searchclear-button" type="button">X</button></span></div>',
			classes : 'gs-transparent-background-colour gs-menuitem',
			permissions : ['DISABLED'],
			action : doNothing,
			tip : 'Search',
			type : 'menuitem'
		},
		{
			id : 'FormSupportMenuItem',
			caption : '<i class="glyphicon glyphicon-earphone"></i>&nbsp;Support',
			classes : 'gs-transparent-background-colour gs-menuitem',
			permissions : [],
			action : openWindow,
			actionData : APP_SUPPORT,
			tip : 'Click here for ' + APP_SHORT_NAME + ' support.',
			reversetip: true,
			type : 'menuitem'
		},
		{
			id : 'JoinMenuItem',
			caption : 'Register',
			classes : 'gs-transparent-background-colour gs-menuitem',
			permissions : function ()
			{
				return (os.toBoolean(os.getProperty('public')) && (ENABLE_REGISTER === 'TRUE') && (CLIENT_ALLOWJOIN === 'TRUE'));
			},
			action : register,
			tip : 'Click here to register.',
			type : 'menuitem',
			reversetip: true
		},
		{
			id : 'LoginMenuItem',
			caption : 'Log In',
			classes : 'gs-transparent-background-colour gs-menuitem',
			permissions : function ()
			{
				return os.toBoolean(os.getProperty('public'));
			},
			action : login,
			tip : 'Click here to log in.',
			reversetip: true,
			type : 'menuitem'
		},
		{
			id : 'LogoutMenuItem',
			caption : '<i class="glyphicon glyphicon-log-out"></i>&nbsp;Logout (' + m_strUserName + ')',
			classes : 'gs-transparent-background-colour gs-menuitem gs-from768',
			permissions : function ()
			{
				return !os.toBoolean(os.getProperty('public')) && hasPermission(['VW_LOGOUT']);
			},
			action : logout,
			tip : 'Click here to logout.',
			reversetip: true,
			type : 'menuitem'
		},
		{
			id : 'ReturnMenuItem',
			caption : '<i class="glyphicon glyphicon-log-out"></i>&nbsp;Return to Client',
			classes : 'gs-transparent-background-colour gs-menuitem gs-from768',
			permissions : function ()
			{
				return !os.toBoolean(os.getProperty('public')) && hasPermission(['VW_RETURN']);
			},
			action : clientReturn,
			tip : 'Click here to return to your previous client.',
			reversetip: true,
			type : 'menuitem'
		},
		// {
			// id : 'MyAccountMenuItem',
			// caption : '<i class="glyphicon glyphicon-user"></i>&nbsp;' + htmlEncode(m_strClientName) + ' Account<br/>&nbsp;',
			// classes : 'gs-transparent-background-colour gs-menuitem gs-from768',
			// permissions : function ()
			// {
				// return !os.toBoolean(os.getProperty('public')) && hasPermission(['VW_ACCOUNT']);
			// },
			// action : openMyAccount,
			// tip : 'Click here to view the ' + htmlEncode(m_strClientName) + ' account.',
			// reversetip: true,
			// type : 'menuitem'
		// },
		// 768 is the mobile version
		{
			id : 'HamburgerMenu768',
			caption : '',
			classes : m_strMenuClasses,
			permissions : [],
			action : m_objThis.Burger_onClick,
			tip : 'Click here to open the menu.',
			type : 'hamburgermenu'
		},
		{
			id : 'MyMessagesMenuItem768',
			caption : '<i class="glyphicon glyphicon glyphicon-envelope"></i>&nbsp;My Messages&nbsp;<span class="ge-unread-badge badge gb-hidden"></span>',
			classes : 'gs-transparent-background-colour gs-menuitem',
			permissions : function ()
			{
				return !os.toBoolean(os.getProperty('public')) && hasPermission(['VW_ACCOUNT']) && hasPermission(['VW_INTERNALMESSAGE']);
			},
			action : openMyMessages,
			tip : 'Click here to see your messages.',
			type : 'menuitem'
		},
		{
			id : 'SearchMenuItem768',
			caption : '<div class="input-group" style="width:150px"><input type="text" class="form-control ge-search-input" placeholder="Search..." style="xwidth:100px"/><span class="input-group-btn"><button class="btn btn-default ge-searchclear-button" type="button">X</button></span></div>',
			classes : 'gs-transparent-background-colour gs-menuitem',
			permissions : ['DISABLED'],
			action : doNothing,
			tip : 'Search',
			type : 'menuitem'
		},
		{
			id : 'FormSupportMenuItem768',
			caption : '<i class="glyphicon glyphicon-earphone"></i>&nbsp;Support',
			classes : 'gs-transparent-background-colour gs-menuitem',
			permissions : [],
			action : openWindow,
			actionData : APP_SUPPORT,
			tip : 'Click here for ' + APP_SHORT_NAME + ' support.',
			type : 'menuitem'
		},
		{
			id : 'JoinMenuItem768',
			caption : 'Register',
			classes : 'gs-transparent-background-colour gs-menuitem',
			permissions : function ()
			{
				return (os.toBoolean(os.getProperty('public')) && (ENABLE_REGISTER === 'TRUE') && (CLIENT_ALLOWJOIN === 'TRUE'));
			},
			action : register,
			tip : 'Click here to register.',
			type : 'menuitem'
		},
		{
			id : 'LoginMenuItem768',
			caption : 'Log In',
			classes : 'gs-transparent-background-colour gs-menuitem',
			permissions : function ()
			{
				return os.toBoolean(os.getProperty('public'));
			},
			action : login,
			tip : 'Click here to log in.',
			type : 'menuitem'
		},
		{
			id : 'LogoutMenuItem768',
			caption : '<i class="glyphicon glyphicon-log-out"></i>&nbsp;Logout (' + m_strUserName + ')',
			classes : 'gs-transparent-background-colour gs-menuitem gs-to768',
			permissions : function ()
			{
				return !os.toBoolean(os.getProperty('public')) && hasPermission(['VW_LOGOUT']);
			},
			action : logout,
			tip : 'Click here to logout.',
			type : 'menuitem'
		},
		{
			id : 'ReturnMenuItem768',
			caption : '<i class="glyphicon glyphicon-log-out"></i>&nbsp;Return to Client',
			classes : 'gs-transparent-background-colour gs-menuitem gs-to768',
			permissions : function ()
			{
				return !os.toBoolean(os.getProperty('public')) && hasPermission(['VW_RETURN']);
			},
			action : clientReturn,
			tip : 'Click here to return to your previous client.',
			type : 'menuitem'
		}
		// {
			// id : 'MyAccountMenuItem768',
			// caption : '<i class="glyphicon glyphicon-person"></i>&nbsp;' + htmlEncode(m_strClientName) + ' Account<br/>&nbsp;',
			// classes : 'gs-transparent-background-colour gs-menuitem gs-to768',
			// permissions : function ()
			// {
				// return !os.toBoolean(os.getProperty('public')) && hasPermission(['VW_ACCOUNT']);
			// },
			// action : openMyAccount,
			// tip : 'Click here view the ' + htmlEncode(m_strClientName) + ' account.',
			// type : 'menuitem'
		// }
		];

		m_objHamburgerDock = new jDock(os,
			{
				"map" : m_arrMenuMap,
				"tiles" : m_arrMenuTiles,
                "alwayshidemorebutton" : true
			}
			);
		m_objMenuDock = new jDock(os,
			{
				"map" : m_arrMenuMap,
				"tiles" : m_arrMenuTiles,
				"mode" : "horizontalmenu",
                "alwayshidemorebutton" : true
			}
			);
		m_objSignupDock = new jDock(os,
			{
				"map" : m_arrMenuMap,
				"tiles" : m_arrMenuTiles,
				"mode" : "horizontalmenu",
                "alwayshidemorebutton" : true
			}
			);
	}

	function login()
	{
		os.gotoURL('#!core.frmLogin');
	}

	function openMyMessages()
	{
		os.showForm('msg.frmMyMessages');
	}

	function openWindow(strActionData_a)
	{
		window.open(strActionData_a, '_blank');
	}

	function populateDocks()
	{
		var blnIsPublic = os.toBoolean(os.getProperty('public'));

		initialiseDocks();

		//os.showElement(m_strFormID, '.ge-burger-panel', !blnIsPublic);
		//os.showElement(m_strFormID, '.ge-burger-spacer', blnIsPublic);

		m_objHamburgerDock.render(m_strFormID, '.ge-burger-panel', 'hamburger');
		m_objMenuDock.render(m_strFormID, '.ge-menu-panel', 'menu');

		m_objSignupDock.render(m_strFormID, '.ge-signup-panel', 'signup');
		m_objSignupDock.render(m_strFormID, '.ge-signup-panel768', 'signup768');

		// currently commented out as we don't YET want a bottom taskbar
		// if (os.hasCapability('viewport'))
		// {
		// os.element(m_strFormID, '.ge-form-bottom').show();
		// resize();
		// window.onresize = function() { resize(); };
		// }
	}

	function populateForm()
	{
		// if ((m_strLoginAs.length > 0) && (m_strLoginAs !== 'Public'))
		if (m_strLoginAs !== 'Public')
		{
			var strLoginAs = '';
			
			os.element(m_strFormID, '.ge-loginas').removeClass('gb-hidden');
			if (m_strLoginAs === 'System')
			{
				os.element(m_strFormID, '.ge-loginas').css('background-color', 'red');
				os.element(m_strFormID, '.ge-loginas').css('color', 'yellow');
				strLoginAs = 'You are logged in as: ' + m_strLoginAs + ' - ' + m_strClientName + ' - ' + m_strUserName;
			}
			else if (m_strLoginAs === 'Client')
			{
				os.element(m_strFormID, '.ge-loginas').css('background-color', 'blue');
				os.element(m_strFormID, '.ge-loginas').css('color', 'yellow');
				strLoginAs = 'You are logged in as: ' + m_strLoginAs + ' - ' + m_strClientName + ' - ' + m_strUserName;
			}
			else
			{
				os.element(m_strFormID, '.ge-loginas').css('background-color', 'green');
				os.element(m_strFormID, '.ge-loginas').css('color', 'yellow');
				strLoginAs = 'You are logged in as: ' + m_strClientName + ' - ' + m_strUserName;
			}
			
			os.element(m_strFormID, '.ge-loginas').html(strLoginAs);
		}

		os.registerTaskbar(m_objThis, m_strFormID);

		os.element(m_strFormID, '.ge-taskbar').show();

		if (os.hasCapability('timers'))
		{
			// setup timers
			if (os.hasCapability('mobile'))
			{
				m_strServerEventTimerID = os.createTimer(os.checkServerEvents, TIMER_SERVEREVENTS_FREQUENCY_MOB, true);
			}
			else
			{
				m_strServerEventTimerID = os.createTimer(os.checkServerEvents, TIMER_SERVEREVENTS_FREQUENCY, true);
			}

			os.registerServerEvent('messages', m_strFormID);
			os.enableServerEventQueue('messages');
			os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerbroadcaster', [{ "name" : "eventqueue", "value" : "messages" }]), doNothing, doNothing);
			os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerlistener', [{ "name" : "eventqueue", "value" : "messages" }]), doNothing, doNothing);
		}

		bindGlobals();
		populateDocks();
		fetchMessageStats();
        bindModuleEvents();

		if (ENABLE_DEVELOPER_NOTES === 'TRUE')
		{
			os.dialogAlertScroll(DEVELOPER_NOTES, function ()  {} );
		}
	}

	function register()
	{
		os.gotoURL('#!core.frmRegister');
	}

	function resize()
	{
		var intTop = os.getViewPort().height - 62;
		os.element(m_strFormID, '.ge-form-bottom').css(
		{
			"top" : intTop + 'px'
		}
		);
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function logoutSuccess(objResponse_a)
	{
		os.logout('', 'frmTaskbar');
	}

	function returnSuccess(objResponse_a)
	{
		os.clientReturn('', 'frmTaskbar');
	}

	function messageStatsFetched(objResponse_a)
	{
		var objMessageStats = objResponse_a[0];

		if (objMessageStats.unreadcount > 0)
		{
			os.element(m_strFormID, '.ge-unread-badge').show();
			os.element(m_strFormID, '.ge-unread-badge').text(objMessageStats.unreadcount);
		}
		else
		{
			os.element(m_strFormID, '.ge-unread-badge').hide();
		}
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function fetchMessageStats()
	{
		var objJSON = os.ajaxRequestCreate('msg_messagestatsfetch', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, messageStatsFetched, os.ajaxError);
	}

	function logout()
	{
		var strPrompt = 'Are you sure you wish to logout?<br><br>Please be sure you have saved your data before continuing.';
		os.dialogConfirm(strPrompt, function ()
		{
			//os.closeAllForms(function()
			//{
			var objJSON = os.ajaxRequestCreate('public_logout', []);
			os.ajaxCall(URL_WEBSERVICE, objJSON, logoutSuccess, os.ajaxError);
			//});
		}
		);
	}

	function clientReturn()
	{
		var strPrompt = 'Are you sure you wish to return to your previous client?<br><br>Please be sure you have saved your data before continuing.';
		os.dialogConfirm(strPrompt, function ()
		{
			//os.closeAllForms(function()
			//{
			var objJSON = os.ajaxRequestCreate('public_return', []);
			os.ajaxCall(URL_WEBSERVICE, objJSON, returnSuccess, os.ajaxError);
			//});
		}
		);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// defaulting
		os.element(m_strFormID, '.fldFormTitle').text('');

		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
	}

    // modular events
    function bindModuleEvents()
	{
        // unbindings
		os.unbindEvents(m_strFormID, 'ge-search-input,ge-searchclear-button');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.ge-search-input', 'SearchField', 'onEnterKey');
		os.bindEvent(m_objThis, m_strFormID, '.ge-searchclear-button', 'SearchClearButton', 'onClick');
    }

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

	this.Burger_onClick = function ()
	{
		os.toggleMenu();
	};

	this.Form_canClose = function ()
	{
		return false;
	};

	this.Form_onBroadcast = function (strQueue_a, strMessage_a, objJSON_a)
	{
		if ((strQueue_a === 'core') && (strMessage_a === 'loginFormSuccess'))
		{
			populateDocks();
			if (os.toBoolean(os.getProperty('public')))
			{
				os.hideMenu();
				os.destroyMenu(m_strFormID);
			}
			else
			{
				os.createMenu(m_strFormID);
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

		if ((strQueue_a === 'messages') && (strMessage_a === 'messagecount'))
		{
			fetchMessageStats();
		}
	};

	this.Form_onClick = function ()
	{
		os.formToFront(m_strFormID);
	};

	this.Form_onLoad = function ()
	{
		populateForm();
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

    this.SearchField_onEnterKey = function (objField_a)
	{
        var objField = os.element(objField_a);
        var strKeyword = objField.val();

        if( $.trim(strKeyword.length) > 0 ) 
		{
			var objActionData = { type:'form', entity:'systemform', formentity:'CONNOTE', formentitydescription:'Consignment', formcode:'CONNOTE', mode:'renderer', title:'Consignments (All)', component: m_strDesktopFormConnote, searchkeyword : strKeyword, fixedfilter:[{"field":"is_deleted","value":"N"}] };
            os.showForm('entity.frmLister', objActionData);
        }
	};
	
	this.SearchClearButton_onClick = function()
	{
		os.element(m_strFormID, '.ge-search-input').val("");
		os.element(m_strFormID, '.ge-search-input').focus();
	};
}
