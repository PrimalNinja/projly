/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
function widgetdock_wgtDock(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	//var m_blnSpeechOn = false;
	var m_blnMobile = os.hasCapability('mobile');
	
	var m_blnEnableBranches = os.toBoolean(os.getProperty('enablebranches'));
	var m_strBranchName = '';
	if (m_blnEnableBranches)
	{
		m_strBranchName = str_replace(os.getProperty('branchname'), ' ', '&nbsp;');
		if (m_strBranchName.length === 0)
		{
			m_strBranchName = 'Branches';
		}
	}

	// ------------------------------------------------------------------------------------

	var m_objNotificationnDockLeft;
	var m_objNotificationnDockRight;

	var m_arrIconMapAll = [
		{
			location : 'root',
			title : 'Icons',
			layout : [
				['icoConnection', 'icoTransmission', 'icoPrinter', 'icoClock', 'icoFullscreen', 'icoZoom', 'icoSizer']
			]
		}
	];

	var m_arrIconMapMini = [
		{
			location : 'root',
			title : 'Icons',
			layout : [
				['icoConnection', 'icoTransmission', 'icoPrinter', 'icoFullscreen', 'icoZoom']
			]
		}
	];

	var m_arrIconMapRight = [
		{
			location : 'root',
			title : 'Icons',
			layout : [
				['icoBranch']
			]
		}
	];

	var m_arrIconTiles = [
		{
			id : '-',
			caption : '',
			classes : '',
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
			permissions : function ()
			{
				return (os.toBoolean(m_objParameters.clock) && os.isModuleLoaded('widgetclock'));
			},
			action : os.showForm,
			actionData : 'widgetclock.wgtClock',
			tip : 'Click here to show the clock.',
			toptip: true,
			type : 'toolbarbutton'
		},
		{
			id : 'icoConnection',
			caption : '',
			classes : 'gb-indicator-connection gi-connectiongreen',
			permissions : function()
			{
				return os.toBoolean(m_objParameters.connection);
			},
			action : 'tip',
			actionData : null,
			tip : 'This indicator goes red when connection with the server goes down.',
			toptip: true,
			type : 'toolbarbutton'
		},
		{
			id : 'icoFullscreen',
			caption : '',
			classes : 'gi-fullscreen',
			permissions : function ()
			{
				return os.toBoolean(m_objParameters.fullscreen);
			},
			action : toggleFullscreen,
			actionData : null,
			tip : 'Click here to switch between full screen mode and normal window mode.',
			toptip: true,
			type : 'toolbarbutton'
		},
		{
			id : 'icoPrinter',
			caption : '',
			classes : 'gi-printer',
			permissions : function ()
			{
				return (os.toBoolean(m_objParameters.printer) && hasPermission(['VW_PRINTJOB']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PRINTJOB', formentitydescription:'Print Job', formcode:'PRINTJOB', mode:'renderer', title:'Print Jobs' },
			tip : 'Click here to view print jobs.',
			toptip: true,
			type : 'toolbarbutton'
		},
		{
			id : 'icoSizer',
			caption : '',
			classes : 'gi-desktopsizer',
			permissions : function ()
			{
				return (os.toBoolean(m_objParameters.sizer) && os.isMDI() && os.isModuleLoaded('widgetdesktopsizer'));
			},
			action : os.showForm,
			actionData : 'widgetdesktopsizer.wgtDesktopSizer',
			tip : 'Click here to define a desktop area.',
			toptip: true,
			type : 'toolbarbutton'
		},
		{
			id : 'icoTransmission',
			caption : '',
			classes : 'gb-indicator-transmission gi-xmitgrey',
			permissions : function()
			{
				return os.toBoolean(m_objParameters.transmission);
			},
			action : 'tip',
			actionData : null,
			tip : 'This indicator blinks green when communicating with the server.',
			toptip: true,
			type : 'toolbarbutton'
		},
		{
			id : 'icoZoom',
			caption : '',
			classes : 'gi-showdesktop',
			permissions : function ()
			{
				return (os.toBoolean(m_objParameters.zoom) && os.hasCapability('zoom') && os.isMDI());
			},
			action : os.zoomInOut,
			actionData : null,
			tip : 'Click here to zoom.',
			toptip: true,
			type : 'toolbarbutton'
		},
		{
			id : 'icoBranch',
			caption : m_strBranchName,
			classes : 'gi-text',
			captionclasses : 'gs-buttontextbold ge-branchcaption',
			permissions : function() {
				return m_blnEnableBranches && hasPermission(['MYBRANCHSELECT']);
			},
			action : actionShowEntityChooser,
			actionData : { type:'form', entity:'systemform', formentity:'BRANCH', mode:'select', title:'Branch Selection', operation:'mybranchselect' },
			tip : function()
			{
				return m_strBranchName;
			},
			toptip: true,
			reversetip: true,
			type : 'menuitem'
		}
	];

	// ====================================================================================
	// HELPERS ============================================================================

	function populateDocks()
	{
		if (os.hasCapability('widgets') && (ENABLE_WIDGETS === 'TRUE'))
		{
			m_objNotificationnDockLeft = new jDock(os,
				{
					"map" : m_arrIconMapAll,
					"tiles" : m_arrIconTiles,
					"mode" : "horizontalmenu",
                    "alwayshidemorebutton" : true
				}
				);
		}
		else
		{
			m_objNotificationnDockLeft = new jDock(os,
				{
					"map" : m_arrIconMapMini,
					"tiles" : m_arrIconTiles,
					"mode" : "horizontalmenu",
                    "alwayshidemorebutton" : true
				}
				);
		}

		m_objNotificationnDockRight = new jDock(os,
			{
				"map" : m_arrIconMapRight,
				"tiles" : m_arrIconTiles,
				"mode" : "horizontalmenu",
				"alwayshidemorebutton" : true
			}
			);

		m_objNotificationnDockLeft.render(m_strFormID, '.ge-notification-panelleft');
		
		if (!m_blnMobile)
		{
			m_objNotificationnDockRight.render(m_strFormID, '.ge-notification-panelright');
		}

		//if (os.hasCapability('viewport'))
		//{
			//os.element(m_strFormID, '.ge-form-bottom').show();
			//resize();
			//window.onresize = function() { resize(); };
		//}
	}

	function populateForm()
	{
		os.registerAndUpdateFooter(m_objThis, m_strFormID);
		os.registerMicrophoneStatus(m_objThis, m_strFormID);

		bindGlobals();
		populateDocks();

		os.registerServerEvent('branchchange', m_strFormID);
		os.enableServerEventQueue('branchchange');
		os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerbroadcaster', [{ "name" : "eventqueue", "value" : "branchchange" }]), doNothing, doNothing);
		os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerlistener', [{ "name" : "eventqueue", "value" : "branchchange" }]), doNothing, doNothing);
	}

	//function resize()
	//{
		//var intTop = os.getViewPort().height - 62.5;
		//os.element(m_strFormID, '.ge-form-bottom').css(
		//{
			//"left" : "0px",
			//"top" : intTop + 'px'
		//}
		//);
	//}

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
		
	function toggleFullscreen()
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
	
    function actionShowEntityChooser(arrParams_a)
	{
		os.showFormPopup('entity.frmEntityChooser', arrParams_a, function (objResult_a)
        {
			if (objResult_a.length > 0)
			{
				var strID = objResult_a[0].id;
				executeCustomOperation(arrParams_a.entity, arrParams_a.operation, strID, function(objResult_a)
				{
					os.setProperty('branchid', strID);
					os.setProperty('branchname', objResult_a.branchname);
					var strBranchName = str_replace(os.getProperty('branchname'), ' ', '&nbsp;');
					os.element(m_strFormID, '.ge-branchcaption').html(strBranchName);
				});
			}
			
        }, false, true);
	}
	
    function actionShowEntityList(arrParams_a)
	{
		os.showForm('entity.frmLister', arrParams_a);
	}
	
	// function speechOn()
	// {
		// os.speechOn();
		// os.element(m_strFormID, '.gb-indicator-speech').removeClass('gi-speechoff');
		// os.element(m_strFormID, '.gb-indicator-speech').addClass('gi-speechon');
		// m_blnSpeechOn = true;
		
		// os.broadcast(m_strFormID, 'speak', 'speech system on');
	// }
	
	// function speechOff()
	// {
		// os.speechOff();
		// os.element(m_strFormID, '.gb-indicator-speech').removeClass('gi-speechon');
		// os.element(m_strFormID, '.gb-indicator-speech').addClass('gi-speechoff');
		// m_blnSpeechOn = false;

		// os.broadcast(m_strFormID, 'speak', 'speech system off');
	// }

	// function toggleSpeech()
	// {
		// if (m_blnSpeechOn)
		// {
			// speechOff();
		// }
		// else
		// {
			// speechOn();
		// }
	// }

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function executeCustomOperation(strEntityCode_a, strOperationCode_a, strID_a, cb_a)
	{

		var objJSON = os.ajaxRequestCreate('entity_customoperationexecute', [
					{
						name : 'entitycode',
						value : strEntityCode_a
					},
					{
						name : 'id',
						value : strID_a
					},
					{
						name : 'operationcode',
						value : strOperationCode_a
					}
				]);

//alert(JSON.stringify(objJSON));
		os.ajaxCall(URL_WEBSERVICE, objJSON, cb_a, os.ajaxError);

	}
	
	function updateBranch(strBranchID_a, strBranchName_a)
	{
		os.setProperty('branchid', strBranchID_a);
		os.setProperty('branchname', strBranchName_a);
		var strBranchName = str_replace(os.getProperty('branchname'), ' ', '&nbsp;');
		m_strBranchName = strBranchName;
		os.element(m_strFormID, '.ge-branchcaption').html(strBranchName);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
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

	this.Form_onBroadcast = function (strQueue_a, strMessage_a, objMessageData_a)
	{
		if ((strQueue_a === 'core') && (strMessage_a === 'loginFormSuccess'))
		{
			populateDocks();
		}
		
		// if (strQueue_a === 'speech')
		// {
			// if (strMessage_a === 'speech system on')
			// {
				// speechOn();
			// }
			// else if (strMessage_a === 'speech system off')
			// {
				// speechOff();
			// }
		// }

		if (strQueue_a === 'branchchange') // && (strMessageData_a.length > 0))
		{
			updateBranch(objMessageData_a.branchid, objMessageData_a.branchname);
		}

		if ((strQueue_a === 'viewport') && (strMessage_a === 'change'))
		{
			os.registerAndUpdateFooter(m_objThis, m_strFormID);
		}
	};

	this.Form_onClick = function ()
	{
		os.formToFront(m_strFormID);
	};

	this.Form_onLoad = function ()
	{
		populateForm();
	};

	// this.Form_onMicrophone = function (strSpeech_a)
	// {
		// if (m_blnSpeechOn)
		// {
			// os.showTip(m_strFormID, '.gb-indicator-speech', 'bottom center', 'top center', 1, strSpeech_a, true);
		// }
		//else
		//{
			//os.dialogAlert(strSpeech_a, function ()  {}

			//);
		//}
	// };

	this.Form_onPermissionCheck = function ()
	{
		//return !os.hasCapability('mobile');
		return true;
	};
}