/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/

function core_frmThemes(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	// ------------------------------------------------------------------------------------

	var m_blnFormDirty = false;

	var m_intToFetch = 0;
	var m_intFetched = 0;
	var m_intErrors = 0;

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
			tip : 'Click here to close the theme selection form.',
			type : 'toolbarbutton'
		},
		{
			id : 'SaveButton',
			caption : 'Save',
			classes : 'gs-darkblue-background-colour gs-glow-focusborder',
			permissions : [],
			action : function ()
			{
				m_objThis.SaveButton_onClick();
			},
			actionData : null,
			tip : 'Click here to save the chosen theme.',
			type : 'toolbarbutton'
		}
	];

	// ====================================================================================
	// HELPERS ============================================================================

	function initialiseForm()
	{
		// resize the form
		var intViewPortWidth = os.getViewPort().width;
		var intViewPortHeight = os.getViewPort().height;
		os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);

		// defaulting
		var strFormTitle = 'Themes - selection';
		os.element(m_strFormID, '.ge-form-title').text(strFormTitle);
	}

	function saveData()
	{
		var strSelectedTheme = os.getProperty('selectedtheme');
		themeSave(strSelectedTheme);
	}

	function selectTheme(strThemeName_a, strThemeCode_a)
	{
		os.dialogConfirm("Would you like to choose the '" + strThemeName_a + "' theme?<br><br>Please be sure you have saved your data before continuing.", function ()
		{
			var strParameters = '';
			var strTheme = strThemeCode_a;

			//if (os.hasCapability('mobile'))
			//{
				//strParameters = 'mobile=true';
			//}

			if (strTheme.length > 0)
			{
				if (strParameters.length > 0)
				{
					strParameters += '&theme=' + encodeURIComponent(strTheme);
				}
				else
				{
					strParameters = 'theme=' + encodeURIComponent(strTheme);
				}
			}

			os.reboot(strParameters, 'frmThemes');
		}
		);
	}

	function setDirty(blnDirty_a)
	{
		m_blnFormDirty = blnDirty_a;

		if (m_blnFormDirty)
		{
			os.element(m_strFormID, '.SaveButton').addClass('gs-glow-dirty');
			os.element(m_strFormID, '.SaveButton').removeClass('gs-darkblue-background-colour');
			os.element(m_strFormID, '.SaveButton').addClass('gs-red-background-colour');
		}
		else
		{
			os.element(m_strFormID, '.SaveButton').removeClass('gs-glow-dirty');
			os.element(m_strFormID, '.SaveButton').removeClass('gs-red-background-colour');
			os.element(m_strFormID, '.SaveButton').addClass('gs-darkblue-background-colour');
		}
	}

	// ====================================================================================
	// POPULATING =========================================================================

	function populateDock()
	{
		m_objDock = new jDock(os,
			{
				"alwaysvisiblebuttoncount": 2,
                //"enablemobiledropdown" : true,
				//"alwayshidemorebutton" : true,
				"map" : m_arrMap,
				"tiles" : m_arrTiles
			}
			);
		m_objDock.render(m_strFormID, '.ge-button-panel');

		setDirty(m_blnFormDirty);
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	this.themeSaved = function (objResponse_a)
	{
		setDirty(false);
	};

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function themeSave(strThemeCode_a)
	{
		var objJSON = os.ajaxRequestCreate('core_themesave',
				[
					{
						"name" : "themecode",
						"value" : strThemeCode_a
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, m_objThis.themeSaved, os.ajaxError);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form,gb-formtitle-inner-panel,ge-default-theme');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');

		os.bindEvent(m_objThis, m_strFormID, '.ge-default-theme', 'DefaultTheme', 'onClick');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

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
		// if we just had a theme change, revert to the themes subform
		if (os.toBoolean(m_objParameters.onstartup))
		{
			if (hasPermission(['CHG_THEME']) && os.hasCapability('themes') && (os.getProperty('theme').length > 0) && (os.getProperty('selectedtheme') != os.getProperty('theme')))
			{
				setDirty(true);
				
				os.dialogAlert("Your theme is now set to '" + os.getProperty('selectedtheme') + "'.<br><br>Remember to save your current theme.", function ()
				{}
				);
			}
			else
			{
				os.closeForm(m_strFormID);
			}
		}

		initialiseForm();
		bindGlobals();
		populateDock();
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.Form_onResize = function (intWidth_a, intHeight_a)
	{
		var intHeight = intHeight_a - 150;

		os.element(m_strFormID, '.ge-panel-content').height((intHeight) + 'px');
		os.element(m_strFormID, '.ge-panel-overflow').height('100%');
		os.element(m_strFormID, '.ge-panel-overflow').css('overflow-y','auto');
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
				//setDirty(false);
				saveData();
			}
			catch (err)
			{
				setDirty(true);
			}
		}
		else
		{
			saveData(); // temporary here until dirty form is working
			os.closeForm(m_strFormID);
		}
	};

	this.DefaultTheme_onClick = function ()
	{
		selectTheme('Default', DEFAULT_THEME);
	};

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,CloseButton,SaveButton,ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		os.element(m_strFormID, '.CloseButton').focus();
	};

	this.TabStart_onFocus = function ()
	{
		os.element(m_strFormID, '.SaveButton').focus();
	};
}