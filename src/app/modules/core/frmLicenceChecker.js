/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function core_frmLicenceChecker(objOS_a, strFormID_a, objParameters_a)
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
		return true;
	};

	this.Form_onLoad = function ()
	{
		if (os.toBoolean(os.getProperty('licensed')))
		{
			var intExpiryDays = parseInt(os.getProperty('expirydays'), 10);
			if (intExpiryDays > 0)
			{
				os.dialogAlert('Your licence will expire in ' + intExpiryDays + ' days, please contact support to renew your licence.', function ()
				{
					os.closeForm(m_strFormID);
				});
			}
		}
		else
		{
			os.dialogAlert('Your licence has expired, please contact support to renew your licence.', function ()
			{
				os.closeForm(m_strFormID);				
			});
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

	this.TabEnd_onFocus = function ()
	{
	};

	this.TabStart_onFocus = function ()
	{
	};
}