/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function core_frmVideo(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	var m_strFrameID = getGUID();

	var m_strTitle = m_objParameters.title;
	var m_strURL = m_objParameters.id;
	
	var m_blnSharing = false; // currently turn off sharing

	// ====================================================================================
	// HELPERS ============================================================================

	function initialiseForm()
	{
		// resize the form
		//var intViewPortWidth = os.getViewPort().width;
		//var intViewPortHeight = os.getViewPort().height;
		os.resizeForm(m_strFormID, 500, 500);
		
		if (!m_blnSharing)
		{
			os.element(m_strFormID, '.ge-formshare-panel').remove();
		}
		

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
		var strHTML = '<iframe src="' + m_strURL + '" width="100%" height="100%" id="' + m_strFrameID + '" name="' + m_strFrameID + '" allowfullscreen allow="autoplay"></iframe>';
		os.element(m_strFormID, '.ge-video-panel').html(strHTML);
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function sharesFetched(objResponse_a)
	{
		var arrDevices = objResponse_a;
		
		var strNavItemOptions = '<ul class="scrollbarstyle" style="width:400px; height:500px; overflow:auto; overflow-x: hidden; backgroun-color:red;">';
		var strSeparator = '';
		processArray(arrDevices, function(objDevice_a)
		{
			if (strNavItemOptions.length > 0)
			{
				strSeparator = '<hr>';
			}
			
			var strDeviceID = objDevice_a.id;
			var strDeviceDesc = strSeparator + htmlEncode(objDevice_a.user) + '<br>(' + htmlEncode(objDevice_a.device) + ')';
			strNavItemOptions += '<a class="dropdown-item" deviceid=' + htmlEncode(strDeviceID) + ' href="#">' + strDeviceDesc + '</a><br>';
		});
		strNavItemOptions += '</ul>';
		os.element(m_strFormID, '.ge-nav-item-options').html(strNavItemOptions);
	
		os.element(m_strFormID, '.ge-nav-item').dropdown('toggle');
	}
	
	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function fetchShares()
	{
		var objJSON = os.ajaxRequestCreate('devicesfetchall', [	]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, sharesFetched, os.ajaxError, doNothing, true);
	}
	
	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form-share,gb-form,gb-formtitle-inner-panel');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-share', 'FormShare', 'onClick');
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

	this.Form_onResize = function (intWidth_a, intHeight_a)
	{ 
		os.element(m_strFormID, '.ge-video-panel').width((intWidth_a) + 'px');
		os.element(m_strFormID, '.ge-video-panel').height((intHeight_a - 60) + 'px');
		os.element(m_strFormID, '.ge-formshare-panel').css('left', (intWidth_a - 30) + 'px');
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
	
	this.FormShare_onClick = function (objThis_a, objElement_a, objEvent_a)
	{
		if (os.element(m_strFormID, '.dropdown').find('.dropdown-menu').is(":hidden"))
		{
			//os.element(m_strFormID, '.ge-popup-panel').removeClass('gb-hidden');
			objEvent_a.stopPropagation();
			fetchShares();
		}
	};
}