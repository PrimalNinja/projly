/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function core_frmPublic(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	// ====================================================================================
	// HELPERS ============================================================================

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	// ====================================================================================
	// BINDINGS ===========================================================================

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

	this.Form_canClose = function ()
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
		var strForm = os.getFormFromHash(location.hash);

		if (strForm.length === 0)
		{
			os.showForm('core.frmLogin', '', true);
		}
		else
		{
			os.hashChange(location.hash);
		}
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	// ====================================================================================
	// OTHER EVENTS =======================================================================

	// ====================================================================================
	// TABBING ============================================================================

}