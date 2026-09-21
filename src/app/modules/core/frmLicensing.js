/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/

function core_frmLicensing(objOS_a, strFormID_a, objParameters_a)
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

	var m_strFormFields = 'ge-licencekey-field';

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
			tip : 'Click here to close the licensing form.',
			type : 'toolbarbutton'
		},
		{
			id : 'SaveButton',
			caption : 'Save',
			classes : 'gb-cell-disabled-xxx',
			permissions : ['EDT_LICENSING'],
			action : function ()
			{
				m_objThis.SaveButton_onClick();
			},
			actionData : null,
			tip : 'Click here to update your licensing.',
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
		if (m_objParameters.mode === "edit")
		{
			strFormTitle = 'Licensing - edit licensing';
		}
		else if (m_objParameters.mode === "view")
		{
			strFormTitle = 'Licensing - view licensing';
			m_blnReadonly = true;
		}

		// change fields to editable versions if not in readonly
		if (m_blnReadonly === false)
		{
			os.element(m_strFormID, '.ge-licencekey-panel').show();
		}
		else
		{
			os.element(m_strFormID, '.ge-licencekey-panel').hide();
		}

		os.element(m_strFormID, '.ge-form-title').text(strFormTitle);
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
		var strLicenceCode = os.element(m_strFormID, '.ge-licencekey-field').val();
		if (m_objParameters.mode === 'edit')
		{
			updateLicence(strLicenceCode);
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

		if ($.trim(os.element(m_strFormID, '.ge-licencekey-field').val()).length === 0)
		{
			strError += '<li>Licence Code is required.</li>';
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
		var intExpiryDays = parseInt(os.getProperty('expirydays'), 10);

		// bind fields
		bindDevice();

		initialiseForm();

		os.element(m_strFormID, '.ge-todaysdate-field').val(os.todayISO());
		os.element(m_strFormID, '.ge-expirydate-field').val(os.getProperty('expirydate'));

		if (intExpiryDays > 0)
		{
			os.element(m_strFormID, '.fldWarning').html('(' + intExpiryDays + ' days remaining)');
		}
		else
		{
			os.element(m_strFormID, '.fldWarning').html('');
		}

		// setup taborder
		setTabOrder();
		os.element(m_strFormID, '.ge-licencekey-field').focus();

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

	function licenceUpdated(objResponse_a)
	{
		os.setProperty('licensed', objResponse_a.licensed);
		os.setProperty('expirydate', objResponse_a.expirydate);
		os.setProperty('expirydays', objResponse_a.expirydays);
		setDirty(false);
		os.dialogAlert("Licensing updated, please login again for it to take effect.", function ()
		{}

		);
		populateForm();
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function updateLicence(strLicenceKey_a)
	{
		var objJSON = os.ajaxRequestCreate('security_updatelicence',
				[
					{
						"name" : "licencekey",
						"value" : strLicenceKey_a
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, licenceUpdated, os.ajaxError, afterAjaxError);
	}

	function fetchData()
	{
		m_intToFetch = 0;
		m_intFetched = 0;
		m_intErrors = 0;

		populateForm();
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindDevice()
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

	this.Form_onLoad = function ()
	{
		m_blnReadonly = false;

		initialiseForm();
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

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

	this.SaveButton_onClick = function ()
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
	};

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
		os.element(m_strFormID, '.ge-licencekey-field').focus();
	};
}