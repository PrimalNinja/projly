/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/

function core_frmChangePassword(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	// ------------------------------------------------------------------------------------

	var m_blnReadonly = false;
	var m_blnFormDirty = false;

	var m_intToFetch = 0;
	var m_intFetched = 0;
	var m_intErrors = 0;

	var m_strFormFields = 'ge-password-field,ge-confirmpassword-field';

	// ------------------------------------------------------------------------------------

	var m_strID = '';
	var m_strUserCode = '';

	var m_objUser = null;

	// ------------------------------------------------------------------------------------

	var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : [
				['CloseButton', 'SaveButton']
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
			tip : 'Click here to close the password.',
			type : 'toolbarbutton'
		},
		{
			id : 'SaveButton',
			caption : 'Save',
			classes : 'gs-darkblue-background-colour gs-glow-focusborder',
			permissions : ['CHPWD_USER'],
			action : function ()
			{
				m_objThis.SaveButton_onClick();
			},
			actionData : null,
			tip : 'Click here to save the password.',
			type : 'toolbarbutton'
		}
	];

	// ====================================================================================
	// HELPERS ============================================================================

	function afterAjaxError()
	{
		setDirty(true);
	}

	function initialiseForm()
	{
		// defaulting
		var strFormTitle = '';

		m_blnReadonly = false;
		if (m_objParameters.mode === "account")
		{
			strFormTitle = 'Password - change ' + m_strUserCode;
		}
		else if (m_objParameters.mode === "user")
		{
			strFormTitle = 'Password - change ' + m_strUserCode;
		}
		else if (m_objParameters.mode === "edit")
		{
			strFormTitle = 'Password - change ' + m_strUserCode;
		}
		else if (m_objParameters.mode === "editself")
		{
			strFormTitle = 'Password - change my password';
		}
		else if (m_objParameters.mode === "view")
		{
			strFormTitle = 'Password - view ' + m_strUserCode;
			m_blnReadonly = true;
		}

		// change fields to editable versions if not in readonly
		if (m_blnReadonly === false)
		{
			os.element(m_strFormID, '.ge-password-field').prop('disabled', false);
			os.element(m_strFormID, '.ge-confirmpassword-field').prop('disabled', false);

			os.limitInput(m_strFormID, '.ge-password-field', 20);
			os.limitInput(m_strFormID, '.ge-confirmpassword-field', 20);
		}
		else
		{
			os.element(m_strFormID, '.ge-password-field').prop('disabled', true);
			os.element(m_strFormID, '.ge-confirmpassword-field').prop('disabled', true);
		}

		os.element(m_strFormID, '.ge-form-title').text(strFormTitle);
	}

	function resetForm()
	{
		m_objUser =
		{
			id : "",
			login : "",
			password : ""
		};
	}

	function saveData()
	{
		var strErrors = validate();

		if (strErrors.length > 0)
		{
			// have an error, so display it
			os.dialogAlertScroll(strErrors, function ()  {}

			);
		}
		else
		{
			setDirty(false);
			saveData2();
		}
	}

	function saveData2()
	{
		m_objUser.id = m_strID;
		m_objUser.login = os.element(m_strFormID, '.ge-login-field').val();
		m_objUser.password = os.element(m_strFormID, '.ge-password-field').val();

		if (m_objParameters.mode === 'account')
		{
			editUser();
		}
		else if (m_objParameters.mode === "user")
		{
			editUser();
		}
		else if (m_objParameters.mode === 'edit')
		{
			editUser();
		}
		else if (m_objParameters.mode === 'editself')
		{
			editSelf();
		}
	}

	function setDirty(blnDirty_a)
	{
		m_blnFormDirty = blnDirty_a;

		if (m_blnReadonly)
		{
			m_objDock.disableButtons('SaveButton', 'gs-darkblue-background-colour gs-glow-focusborder gs-red-background-colour gs-glow-dirty');
		}
		else
		{
			if (m_blnFormDirty)
			{
				m_objDock.disableButtons('SaveButton', 'gs-darkblue-background-colour');
				m_objDock.enableButtons('SaveButton', 'gs-red-background-colour gs-glow-focusborder gs-glow-dirty');
			}
			else
			{
				m_objDock.disableButtons('SaveButton', 'gs-red-background-colour gs-glow-dirty');
				m_objDock.enableButtons('SaveButton', 'gs-darkblue-background-colour gs-glow-focusborder');
			}
		}
	}

	function validate()
	{
		var strError = '';

		if ($.trim(os.element(m_strFormID, '.ge-login-field').val()).length === 0)
		{
			strError += '<li>User Name is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-password-field').val()).length === 0)
		{
			strError += '<li>Password is required.</li>';
		}
		if ($.trim(os.element(m_strFormID, '.ge-confirmpassword-field').val()).length === 0)
		{
			strError += '<li>Password confirmation is required.</li>';
		}
		if (($.trim(os.element(m_strFormID, '.ge-confirmpassword-field').val())) != ($.trim(os.element(m_strFormID, '.ge-password-field').val())))
		{
			strError += '<li>Passwords do not match.</li>';
		}

		if (strError.length > 0)
		{
			strError = '<ul>' + strError + '</ul>';
		}

		return strError;
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
			}
			);
		m_objDock.render(m_strFormID, '.ge-button-panel');
	}

	function populateForm()
	{
		// bind fields
		bindUser();

		m_strUserCode = m_objUser.login;

		initialiseForm();

		if (m_objParameters.mode === "editself")
		{
			os.element(m_strFormID, '.ge-login-field').val(os.getProperty('login'));
		}
		else
		{
			os.element(m_strFormID, '.ge-login-field').val(m_objUser.login);
		}

		os.element(m_strFormID, '.ge-password-field').val(m_objUser.password);
		os.element(m_strFormID, '.ge-confirmpassword-field').val('');

		// setup taborder
		setTabOrder();
		os.element(m_strFormID, '.ge-password-field').focus();

		setDirty(false);
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

	function userFetched(objResponse_a)
	{
		m_objUser = objResponse_a[0];
		asyncDataIsFetched();
	}

	function passwordChanged(objResponse_a)
	{
		//m_strID = objResponse_a[0].id;
		setDirty(false);
		os.closeForm(m_strFormID);
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function editSelf()
	{
		var strWS = 'security_userpasswordupdate';
		if (m_objParameters.mode === 'account')
		{
			strWS = 'security_userpasswordupdatebyaccount';
		}
		
		var objJSON = os.ajaxRequestCreate(strWS,
				[
					{
						"name" : "id",
						"value" : ''
					},
					{
						"name" : "login",
						"value" : m_objUser.login
					},
					{
						"name" : "password",
						"value" : m_objUser.password
					},
					{
						"name" : "editself",
						"value" : true
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, passwordChanged, os.ajaxError, afterAjaxError);
	}

	function editUser()
	{
		var strWS = 'security_userpasswordupdate';
		if (m_objParameters.mode === 'account')
		{
			strWS = 'security_userpasswordupdatebyaccount';
		}
		
		var objJSON = os.ajaxRequestCreate(strWS,
				[
					{
						"name" : "id",
						"value" : m_strID
					},
					{
						"name" : "login",
						"value" : m_objUser.login
					},
					{
						"name" : "password",
						"value" : m_objUser.password
					},
					{
						"name" : "editself",
						"value" : false
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, passwordChanged, os.ajaxError, afterAjaxError);
	}

	function fetchData(blnFetchUser_a)
	{
		if (blnFetchUser_a)
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;

			fetchUser();
		}
		else
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;

			populateForm();
		}
	}

	function fetchUser()
	{
		var strWS = 'security_userfetch';
		if (m_objParameters.mode === 'account')
		{
			strWS = 'security_userfetchbyaccount';
		}
		
		var objJSON = os.ajaxRequestCreate(strWS,
				[
					{
						"name" : "id",
						"value" : m_strID
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, userFetched, asyncError);
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

	function bindUser()
	{
		// unbindings
		os.unbindEvents(m_strFormID, m_strFormFields);

		// dirty bindings
		os.onDirty(m_strFormID, m_strFormFields, function ()
		{
			setDirty(true);
		}
		);
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_isDirty = function ()
	{
		return m_blnFormDirty;
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
	};
    
    this.Form_onResize = function (intWidth_a, intHeight_a)
	{

		var intHeight = os.getFormCanvasHeight(m_strFormID) - 80;

		os.element(m_strFormID, '.ge-content-panel').height((intHeight) + 'px');
		os.element(m_strFormID, '.ge-thecontent').height('100%');
	};

	this.Form_onLoad = function ()
	{
		m_blnReadonly = false;
		m_strID = m_objParameters.id; // can be unspecified
		if (m_strID === undefined)
		{
			m_strID = '';
		}

		initialiseForm();
		populateDock();
		bindGlobals();

		// populate the user form
		if (m_objParameters.mode === 'account')
		{
			resetForm();
			fetchData(true);
		}
		else if (m_objParameters.mode === 'user')
		{
			resetForm();
			fetchData(true);
		}
		else if (m_objParameters.mode === 'edit')
		{
			resetForm();
			fetchData(true);
		}
		else if (m_objParameters.mode === 'editself')
		{
			resetForm();
			fetchData(false);
		}
		else if (m_objParameters.mode === 'view')
		{
			fetchData(true);
		}
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
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

	this.SaveButton_onClick = function ()
	{
		var strErrors = isValid();

		if (strErrors.length === 0)
		{
			if (m_objThis.Form_isDirty())
			{
				try
				{
					saveData();
				}
				catch (err)
				{
					setDirty(true);
				}
			}
			//else
			//{
			//saveData(); // temporary here until dirty form is working
			//os.closeForm(m_strFormID);
			//}
		}
		else
		{
			os.dialogAlert(strErrors, function ()  {}

			);
		}
	};

	// ====================================================================================
	// VALIDATION ============================================================================

	function isValid()
	{
		var strResult = '';
		var password1 = os.element(m_strFormID, '.ge-password-field').val();
		var password2 = os.element(m_strFormID, '.ge-confirmpassword-field').val();

		if (password1 != password2)
		{
			strResult = 'Please ensure passwords match';
			//os.dialogAlert(strResult);
		}

		return strResult;
	}

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,CloseButton,SaveButton,' + m_strFormFields + ',ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		os.element(m_strFormID, '.CloseButton').focus();
	};

	this.TabStart_onFocus = function ()
	{
		os.element(m_strFormID, '.ge-confirmpassword-field').focus();
	};
}