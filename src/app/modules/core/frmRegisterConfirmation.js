/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function core_frmRegisterConfirmation(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	// ====================================================================================
	// HELPERS ============================================================================

	// ====================================================================================
	// POPULATING =========================================================================

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function registrationDetailsFetched(objResponse_a)
	{
		var strClientCode = objResponse_a[0].clientcode;
		var strEmailAddress = objResponse_a[0].emailaddress;
		
		if ((strClientCode.length > 0) && (strEmailAddress.length > 0))
		{
			os.element(m_strFormID, ".ge-clientcode-field").html(htmlEncode(strClientCode));
			os.element(m_strFormID, ".ge-emailaddress-field").html(htmlEncode(strEmailAddress));
			os.element(m_strFormID, ".ge-logindetails-panel").show();
			os.element(m_strFormID, ".ge-logindetails-panel").removeClass('hidden');
		}
	}
	
	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function fetchRegistrationDetails()
	{
		var objJSON = os.ajaxRequestCreate('public_registrationfetch', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, registrationDetailsFetched, os.ajaxError, doNothing, true);
	}
	
	// ====================================================================================
	// BINDINGS ===========================================================================

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return true;
	};

	this.Form_canClose = function ()
	{
		return true;
	};

	this.Form_isDirty = function ()
	{
		return false;
	};

	this.Form_onFocus = function ()
	{
		os.closeExclusive(m_strFormID);
	};

	this.Form_onLoad = function ()
	{
		os.element(m_strFormID, '.ge-appname-field').html(APP_NAME);
		fetchRegistrationDetails();
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