/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function widgetpanel_wgtPanel(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	
	function populateForm()
	{
		os.openTab('#widgetpanel.wgtContent','&fullscreen=true');
	}
	
	function fullScreenOn()
	{
		if (document.documentElement.requestFullscreen)
		{
			document.documentElement.requestFullscreen();
		}
		else if (document.documentElement.msRequestFullscreen)
		{
			document.documentElement.msRequestFullscreen();
		}
		else if (document.documentElement.mozRequestFullscreen)
		{
			document.documentElement.mozRequestFullscreen();
		}
		else if (document.documentElement.webkitRequestFullscreen)
		{
			document.documentElement.webkitRequestFullscreen(element.ALLOW_KEYBOARD_INPUT);
		}
	}
	
	function fullScreenOff()
	{
		if (document.exitFullscreen)
		{
			document.exitFullscreen();
		}
		else if (document.msExitFullscreen)
		{
			document.msExitFullscreen();
		}
		else if (document.mozCancelFullscreen)
		{
			document.mozCancelFullscreen();
		}
		else if (document.webkitExitFullscreen)
		{
			document.webkitExitFullscreen();
		}
	}

	function toggleFullScreen()
	{
		if (!document.FullscreenElement && !document.mozFullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement)
		{
			fullScreenOn();
		}
		else
		{
			fullScreenOff();
		}
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onMouseEnter');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onMouseLeave');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

	this.Form_onBroadcast = function (strQueue_a, strMessage_a, arrParameters_a)
	{
		if (strQueue_a === 'panel')
		{
			if (strMessage_a === 'actionShowEntityList')
			{
				os.showForm('entity.frmLister', arrParameters_a);
			}
			else if (strMessage_a === 'actionShowForm')
			{
				os.showForm(arrParameters_a);
			}
			else if (strMessage_a === 'actionToggleFullScreen')
			{
				os.setFocus();
				toggleFullScreen();
			}
			else if (strMessage_a === 'actionZoom')
			{
				os.setFocus();
				os.zoomInOut();
			}
        }
	};

	this.Form_canClose = function ()
	{
		return false;
	};

	this.Form_onDblClick = function ()
	{
		os.formToFront(m_strFormID);
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

	this.Form_onLoad = function ()
	{
		bindGlobals();
		populateForm();

		os.element(m_strFormID, '.gb-form').draggable(
		{
			handle : '.gb-form-handle'
		}
		);
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};
}