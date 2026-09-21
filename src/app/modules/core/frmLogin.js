/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function core_frmLogin(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	var m_blnLogoutFirst = m_objParameters.logoutFirst;
	var m_loginParameters = '';
	if (m_blnLogoutFirst == undefined)
	{
		m_blnLogoutFirst = false;
	}
	
	// JC to remove
	// var m_objVue = {
		// data: {
			// formid: m_strFormID,
			// test: 'this is a test'
		// },
		// computed: {
			// calculatedValue() { return 10 * 10; }
		// }
	// };
	
	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function loginSuccess(objResponse_a)
	{
		if (os.hasCapability('themes'))
		{
			getUserInfoRedirect();
		}
		else
		{
			getUserInfo();
		}
	}

	function logoutSuccess(objResponse_a)
	{
		os.setUnloadPrompt(false);
		// shut down timers so we don't get odd connection errors at the moment of the refresh
		if (os.hasCapability('timers'))
		{
			os.deleteTimersAll();
		}

		login();
	}

	function processUserInfo(objResponse_a, blnRedirect_a)
	{
		var strParameters = 'loggedin=true';

		setDesktopRegions(objResponse_a.desktopregions);
		setPermissions(objResponse_a.permissions);
		setProducts(objResponse_a.products);
		os.setProperty('clientdb', objResponse_a.clientdb);
		os.setProperty('loginas', objResponse_a.loginas);
		os.setProperty('client', objResponse_a.clientid);
		os.setProperty('clientname', objResponse_a.clientname);
		os.setProperty('businessname', objResponse_a.businessname);
		os.setProperty('login', objResponse_a.login);
		os.setProperty('username', objResponse_a.username);
		os.setProperty('sysadmin', objResponse_a.sysadmin);
		os.setProperty('developer', objResponse_a.developer);
		//os.setProperty('products', objResponse_a.products);
		os.setProperty('public', objResponse_a.ispublic);
		os.setProperty('batch', objResponse_a.isbatch);
		os.setProperty('default', objResponse_a.idefault);
		os.setProperty('owner', objResponse_a.isowner);
		os.setProperty('licensed', objResponse_a.licensed);
		os.setProperty('expirydate', objResponse_a.expirydate);
		os.setProperty('expirydays', objResponse_a.expirydays);
		os.setProperty('selectedtheme', SELECTED_THEME);

		os.setProperty('defaultcountry', objResponse_a.defaultcountry);
		os.setProperty('devicename', objResponse_a.devicename);
		os.setProperty('theme', objResponse_a.theme);
		//os.setProperty('uselocalapplet', objResponse_a.uselocalapplet);
		os.setProperty('isemployer', objResponse_a.isemployer);
		os.setProperty('isindividual', objResponse_a.isindividual);

		var blnEnableTips = os.toBoolean(objResponse_a.displaytooltips);
		os.enableTips(blnEnableTips);
//blnRedirect_a = false;

		if (blnRedirect_a)
		{
			// to support themes we must redirect to load our required theme before anything is rendered
			var strTheme = os.getProperty('theme');

			if (!os.hasCapability('themes'))
			{
				strTheme = DEFAULT_THEME;
			}

			if (strTheme.length > 0)
			{
				strParameters += '&theme=' + encodeURIComponent(strTheme);
			}
		}
		else
		{
			// this is the no redirect option, and we don't have support of changing themes here
			//os.closeForm(m_strFormID);
			//LOGGEDIN = 'TRUE';
			//os.broadcast(m_strFormID, 'core', 'loginFormSuccess');
			//initialiseStartupItems(os, doNothing);
			doNothing();
		}

		os.reboot(strParameters, 'frmLogin');
	}

	function userInfoFetched(objResponse_a)
	{
		processUserInfo(objResponse_a, false);
	}

	function userInfoFetchedRedirect(objResponse_a)
	{
		processUserInfo(objResponse_a, true);
	}

	function processUser2FACode(objResponse_a)
	{
		login2();
	}		

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function getUserInfo()
	{
		// read permissions
		var objJSON = os.ajaxRequestCreate('security_userinfo', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, userInfoFetched, os.ajaxError, doNothing, true);
	}

	function getUserInfoRedirect()
	{
		// read permissions
		var objJSON = os.ajaxRequestCreate('security_userinfo', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, userInfoFetchedRedirect, os.ajaxError, doNothing, true);
	}

	function login()
	{
		var strErrors = validate(false);
		var strAccountCode = os.element(m_strFormID, '.ge-accountcode-field').val();

		if (strErrors.length > 0)
		{
			// have an error, so display it
			os.dialogAlertScroll(strErrors, function () {});
		}
		else
		{
			login2();
		}
	}

	function login2()
	{
		var strAccountCode = os.element(m_strFormID, '.ge-accountcode-field').val();
		var strLogin = os.element(m_strFormID, '.ge-login-field').val();
		var strPassword = os.element(m_strFormID, '.ge-password-field').val();
		var strStayLoggedIn = 'N';
		if (os.element(m_strFormID, '.ge-stayloggedin-field').is(':checked'))
		{
			strStayLoggedIn = 'Y';
		}

		var strUserAgent = os.getAgent();
		var strBrowserCapabilities = os.getBrowserCapabilities();

		var objJSON = os.ajaxRequestCreate('public_login',
				[
					{
						"name" : "clientcode",
						"value" : strAccountCode
					},
					{
						"name" : "login",
						"value" : strLogin
					},
					{
						"name" : "password",
						"value" : strPassword
					},
					{
						"name" : "stayloggedin",
						"value" : strStayLoggedIn
					},
					{
						"name" : "useragent",
						"value" : strUserAgent
					},
					{
						"name" : "capabilities",
						"value" : strBrowserCapabilities
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, loginSuccess, loginPublic, doNothing, true);
	}

	function loginPublic(cbFailure_a, strErrorMessage_a)
	{
		var strAccountCode = DEFAULT_ENTITY;
		var strLogin = DEFAULT_LOGIN;
		var strPassword = DEFAULT_PASSWORD;
		var strStayLoggedIn = 'N';
		var strUserAgent = os.getAgent();
		var strBrowserCapabilities = os.getBrowserCapabilities();

		// display the original error
		os.ajaxError(cbFailure_a, strErrorMessage_a);

		// re-login as public
		var objJSON = os.ajaxRequestCreate('public_login',
				[
					{
						"name" : "clientcode",
						"value" : strAccountCode
					},
					{
						"name" : "login",
						"value" : strLogin
					},
					{
						"name" : "password",
						"value" : strPassword
					},
					{
						"name" : "stayloggedin",
						"value" : strStayLoggedIn
					},
					{
						"name" : "useragent",
						"value" : strUserAgent
					},
					{
						"name" : "capabilities",
						"value" : strBrowserCapabilities
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, getUserInfo, os.ajaxError, doNothing, true);
	}

	function logout(blnPrompt_a)
	{
		if (blnPrompt_a)
		{
			var strPrompt = 'Are you sure you wish to logout and login as another user?<br><br>Please be sure you have saved your data before continuing.';
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
		else
		{
			var objJSON = os.ajaxRequestCreate('public_logout', []);
			os.ajaxCall(URL_WEBSERVICE, objJSON, logoutSuccess, os.ajaxError);
		}
	}

	function resetPassword(){
		var strErrors = validate(true);

		if (strErrors.length > 0)
		{
			os.dialogAlertScroll(strErrors, function ()  {});
		}
		else
		{
			var strAccountCode = os.element(m_strFormID, '.ge-accountcode-field').val();
			var strLogin = os.element(m_strFormID, '.ge-login-field').val();

			var objJSON = os.ajaxRequestCreate('public_passwordreset',
				[
					{
						"name" : "clientcode",
						"value" : strAccountCode
					},
					{
						"name" : "login",
						"value" : strLogin
					}
				]);
			os.ajaxCall(URL_WEBSERVICE, objJSON, doNothing, os.ajaxError, doNothing, true);

		}
	}

	function validate(blnIsResetPassword_a)
	{
		var strError = '';

		if ($.trim(os.element(m_strFormID, '.ge-accountcode-field').val()).length === 0)
		{
			strError = '<li>Account Code is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-login-field').val()).length === 0)
		{
			strError += '<li>Email Address / Login is required.</li>';
		}

		if(!blnIsResetPassword_a)
		{
			if ($.trim(os.element(m_strFormID, '.ge-password-field').val()).length === 0)
			{
				strError += '<li>Password is required.</li>';
			}
		}

		if (strError.length > 0)
		{
			strError = '<ul>' + strError + '</ul>';
		}

		return strError;
	}

	function validate2FACode(blnPanel1_a)
	{
		var strError = '';

		var strFieldName = '.ge-2factorcode-register-field';
		if(!blnPanel1_a)
		{
			strFieldName = '.ge-2factorcode-verify-field';		
		}		

		if ($.trim(os.element(m_strFormID, strFieldName).val()).length === 0)
		{
			strError = '<li>6 Digit code is required.</li>';
		}

		if (strError.length > 0)
		{
			strError = '<ul>' + strError + '</ul>';
		}

		return strError;
	}	

	function verify(blnPanel1_a)
	{
		try
		{
			function showInvalidCodeError(objResponse_a)
			{
				var strPrompt = 'Code Entered is no longer valid, Please enter a new one';
				os.dialogConfirm(strPrompt, doNothing);
			}

			var strAccountCode = os.element(m_strFormID, '.ge-accountcode-field').val();
			var strLogin = os.element(m_strFormID, '.ge-login-field').val();
			var strPassword = os.element(m_strFormID, '.ge-password-field').val();
			var strStayLoggedIn = 'N';

			var strFieldName = '.ge-2factorcode-register-field';
			if(!blnPanel1_a)
			{
				strFieldName = '.ge-2factorcode-verify-field';		
			}

			var str2FACode = os.element(m_strFormID, strFieldName).val();

			if (os.element(m_strFormID, '.ge-stayloggedin-field').is(':checked'))
			{
				strStayLoggedIn = 'Y';
			}
	
			var strUserAgent = os.getAgent();
			var strBrowserCapabilities = os.getBrowserCapabilities();

			function loginSuccess2(objResponse_a){
				alert(JSON.stringify(objResponse_a));
			}
	
			var objJSON = os.ajaxRequestCreate('public_login',
					[
						{
							"name" : "clientcode",
							"value" : strAccountCode
						},
						{
							"name" : "login",
							"value" : strLogin
						},
						{
							"name" : "password",
							"value" : strPassword
						},
						{
							"name" : "stayloggedin",
							"value" : strStayLoggedIn
						},
						{
							"name" : "useragent",
							"value" : strUserAgent
						},
						{
							"name" : "capabilities",
							"value" : strBrowserCapabilities
						},
						{
							"name" : "twofactorcode",
							"value" : str2FACode
						}						
					]);

			os.ajaxCall(URL_WEBSERVICE, objJSON, loginSuccess, showInvalidCodeError, doNothing, true);


		}
		catch(ex)
		{
			alert(ex);
		}
		
		
	}		

	// ====================================================================================
	// FORMLOAD EVENT =====================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return true;
	};

    this.Form_canClose = function ()
	{
		return false;
	};
	
	// JC to remove
	//this.Form_getVue = function()
	//{
		//return m_objVue;
	//};

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

	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

	this.Form_onFocus = function ()
	{
		setTabOrder();
		os.closeExclusive(m_strFormID);
	};

	this.Form_onFocusLost = function ()
	{};

	this.Form_onLoad = function ()
	{
		if (os.hasCapability('mobile'))
		{
			os.element(m_strFormID, '.ge-nonmobile').remove();
		}
		else
		{
			os.element(m_strFormID, '.ge-nonmobile').removeClass('gb-hidden');
		}

		os.element(m_strFormID, '.ge-form').show();
		os.element(m_strFormID, '.ge-form-title').html('Account Login');
		//os.element(m_strFormID, '.ge-appname-field').html(APP_NAME);

		if (ALLOW_STAY_LOGGEDIN === 'TRUE')
		{
			os.element(m_strFormID, '.ge-stayloggedin-panel').show();
		}
		else
		{
			os.element(m_strFormID, '.ge-stayloggedin-panel').hide();
		}

		if ((ENABLE_REGISTER === 'TRUE') && (CLIENT_ALLOWJOIN === 'TRUE'))
		{
			//os.element(m_strFormID, '.ge-login-link').hide();
			os.element(m_strFormID, '.ge-register-link').show();
		}
		else
		{
			os.element(m_strFormID, '.ge-register-link').hide();
			//os.element(m_strFormID, '.ge-login-link').show();
		}
		//os.element(m_strFormID, '.ge-login-field').attr('placeholder', 'Registration Email Address');

		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,ge-login-button,gb-form,ge-password-field');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-login-button', 'LoginButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-login-button', 'LoginButton', 'onEnterKey');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-password-field', 'PasswordField', 'onEnterKey');
		os.bindEvent(m_objThis, m_strFormID, '.ge-resetpassword-button', 'ResetPasswordButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-resetpassword-button', 'ResetPasswordButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-verify-button', 'VerifyButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-twofactor-verify-button', 'VerifyOneTimeCodeButton', 'onClick');

		os.limitInput(m_strFormID, '.ge-accountcode-field', 40, true);
		os.limitInput(m_strFormID, '.ge-login-field', 100, true);
		os.limitInput(m_strFormID, '.ge-password-field', 20, true);

		setTabOrder();

		os.element(m_strFormID, '.ge-accountcode-field').focus();

		os.formToFront(m_strFormID);
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	//this.FormTitle_onClick = function ()
	//{
		//os.formToFront(m_strFormID);
	//};

	// this form is not a normal form and doesn't dock and doesn't take the full screen height so we
	// have to calculate the height we want it to be and force a scrollbar if the form is too big for the canvas
	this.Form_onResize = function (intWidth_a, intHeight_a)
	{
		var intHeight = os.getCanvasHeight();

		if (os.hasCapability('mobile'))
		{
			os.element(m_strFormID, '.ge-top-formgap').remove();
		}
		else
		{
			intHeight = intHeight - 60; // that is reserved in the htm to push the form down
		}

		if (intHeight >= 550)
		{
			intHeight = 550;
		}

		os.element(m_strFormID, '.ge-panel-content').height((intHeight) + 'px');
		os.element(m_strFormID, '.ge-panel-content').css('overflow-x','hidden');
		os.element(m_strFormID, '.ge-panel-content').css('overflow-y','auto');
	};

	// ====================================================================================
	// OTHER EVENTS =======================================================================

	this.LoginButton_onClick = function ()
	{
		if (m_blnLogoutFirst)
		{
			logout(false);
		}
		else
		{
			login();
		}
	};

	this.LoginButton_onEnterKey = function ()
	{
		if (m_blnLogoutFirst)
		{
			logout(false);
		}
		else
		{
			login();
		}
	};

	this.PasswordField_onEnterKey = function ()
	{
		if (m_blnLogoutFirst)
		{
			logout(false);
		}
		else
		{
			login();
		}
	};

	this.ResetPasswordButton_onClick = function ()
	{
		resetPassword();
		//m_objVue.data.test = "ResetPasswordButton_onClick";	// JC to remove
	};

	this.VerifyButton_onClick = function ()
	{
		var strErrors = validate2FACode(true);

		if (strErrors.length > 0)
		{
			os.dialogAlertScroll(strErrors, function (){});
		}
		else
		{
			verify(true);
		}
	};	

	this.VerifyOneTimeCodeButton_onClick = function ()
	{
		var strErrors = validate2FACode(false);

		if (strErrors.length > 0)
		{
			os.dialogAlertScroll(strErrors, function (){});
		}
		else
		{
			verify(false);
		}
	};	
	

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,ge-accountcode-field,ge-login-field,ge-password-field,ge-login-button,ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		os.element(m_strFormID, '.ge-accountcode-field').focus();
	};

	this.TabStart_onFocus = function ()
	{
		os.element(m_strFormID, '.ge-login-button').focus();
	};
}