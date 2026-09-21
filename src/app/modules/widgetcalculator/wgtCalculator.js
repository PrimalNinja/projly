/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function widgetcalculator_wgtCalculator(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onMouseEnter');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onMouseLeave');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

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
		bindGlobals();

		os.element(m_strFormID, '.gb-form').draggable(
		{
			handle : '.gb-form-handle'
		}
		);
	};

	this.Form_onMouseEnter = function ()
	{
		os.showMDIClose(m_strFormID);
	};

	this.Form_onMouseLeave = function ()
	{
		os.hideMDIClose(m_strFormID);
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};
}