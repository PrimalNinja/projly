/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function widgetpanel_wgtContent(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	
	var m_blnEnableBranches = os.toBoolean(os.getProperty('enablebranches'));
	var m_strBranchName = str_replace(os.getProperty('branchname'), ' ', '&nbsp;');
	if (m_strBranchName.length === 0)
	{
		m_strBranchName = 'Branches';
	}

	var m_objDock;
	
	var m_arrIconMapAll = [
		{
			location : 'root',
			title : 'Icons',
			layout : [
				['icoClock', 'icoZoom'] // 'icoFullscreen'
			]
		}
	];

	var m_arrIconTiles = [
		{
			id : '-',
			caption : '',
			classes : '',
			style : 'width:235px; height:235px; background-size: contain;',
			permissions : [],
			action : '',
			actionData : null,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'icoClock',
			caption : '',
			classes : 'gi-alarm',
			style : 'width:235px; height:235px; background-size: contain;',
			permissions : function ()
			{
				return (os.isModuleLoaded('widgetclock'));
			},
			action : actionShowForm,
			actionData : 'widgetclock.wgtClock',
			tip : 'Click here to show the clock.',
			toptip: true,
			type : 'toolbarbutton'
		},
		{
			id : 'icoFullscreen',
			caption : '',
			classes : 'gi-fullscreen',
			style : 'width:235px; height:235px; background-size: contain;',
			permissions : [],
			action : actionToggleFullScreen,
			actionData : null,
			tip : 'Click here to switch between full screen mode and normal window mode.',
			toptip: true,
			type : 'toolbarbutton'
		},
		{
			id : 'icoZoom',
			caption : '',
			classes : 'gi-showdesktop',
			style : 'width:235px; height:235px; background-size: contain;',
			permissions : [],
			action : actionZoom,
			actionData : null,
			tip : 'Click here to zoom.',
			toptip: true,
			type : 'toolbarbutton'
		}
	];

	function populateForm()
	{
		m_objDock = new jDock(os,
			{
				"map" : m_arrIconMapAll,
				"tiles" : m_arrIconTiles,
				"mode" : "horizontalmenu",
				"alwayshidemorebutton" : true
			}
			);

		m_objDock.render(m_strFormID, '.ge-content');
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
		
    function actionShowEntityList(arrParams_a)
	{
		os.broadcast(m_strFormID, 'panel', 'actionShowEntityList', arrParams_a, false);
	}
	
	function actionShowForm(arrParams_a)
	{
		os.broadcast(m_strFormID, 'panel', 'actionShowForm', arrParams_a, false);
	}
	
	function actionToggleFullScreen()
	{
		os.broadcast(m_strFormID, 'panel', 'actionToggleFullScreen', '', false);
	}
	
	function actionZoom()
	{
		os.broadcast(m_strFormID, 'panel', 'actionZoom', '', false);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,ge-content,gb-form');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onMouseEnter');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onMouseLeave');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		
		os.bindEvent(m_objThis, m_strFormID, '.ge-content', 'Content', 'onClick');
	}

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
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

	this.Content_onClick = function()
	{
		fullScreenOn();
	};
}
