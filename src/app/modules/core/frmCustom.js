/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function core_frmCustom(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	var m_strFrameID = getGUID();

	var m_strTitle = m_objParameters.title;
	var m_strType = m_objParameters.type;
	var m_strURL = m_objParameters.url;
	var m_strBarcodeType = m_objParameters.barcodetype;
	var m_strBarcodeAction = m_objParameters.barcodeaction;
	var m_strBarcodeContent = m_objParameters.barcodecontent;
	
	if (m_strType === "barcode")
	{
		m_strURL = str_replace(m_strURL, 'URL_2DBARCODE_GENERATOR', URL_2DBARCODE_GENERATOR);
		
		if (m_strBarcodeType === "default")
		{
			if (m_strBarcodeAction.length > 0)
			{
				m_strURL += "?action=" + m_strBarcodeAction;
			}
			else if (m_strBarcodeContent.length > 0)
			{
				m_strURL += "?content=" + m_strBarcodeContent;
			}
		}
	}

	// ====================================================================================
	// HELPERS ============================================================================

	function initialiseForm()
	{
		// defaulting
		var strFormTitle = m_strTitle;
		if ((strFormTitle === undefined) || (strFormTitle.length === 0))
		{
			strFormTitle = m_strURL;
		}

		os.element(m_strFormID, '.ge-form-title').text(strFormTitle);
	}

	// ====================================================================================
	// POPULATING =========================================================================

	function populateForm()
	{
		// initialise custom content
		var strHTML = '<iframe src="' + m_strURL + '" width="100%" height="100%" id="' + m_strFrameID + '" name="' + m_strFrameID + '"></iframe>';
		os.element(m_strFormID, '.ge-custom-content').html(strHTML);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form-print,gb-form,gb-formtitle-inner-panel');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-print', 'FormPrint', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.cmdClose', 'cmdClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.cmdClose', 'cmdClose', 'onEnterKey');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');

		// tips
		os.showTip(m_strFormID, '.cmdClose', 'top left', 'bottom right', 1, 'Click here to close the form.');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_getDesktopRegion = function()
	{
		return "C";
	};
	
	this.Form_isDirty = function ()
	{
		return false;
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

	this.Form_onLoad = function ()
	{
		initialiseForm();
		bindGlobals();
		populateForm();
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

	this.FormPrint_onClick = function ()
	{
		var objBrowser = os.getBrowser();
        if (objBrowser.name == "Microsoft Edge")
        {
            parent.document.getElementsByName(m_strFrameID)[0].contentWindow.document.execCommand("print", false, null);
        }
        else
        {
            window.frames[m_strFrameID].focus();
            window.frames[m_strFrameID].print();
        }
	};
}