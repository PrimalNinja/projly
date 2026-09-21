/*jsl:option explicit*/
/*jsl:import inc-osutils.js*/
/*jsl:import inc-osutils-classes.js*/

// ====================================================================================
// AWAFOS v20241106 ===================================================================
// copyright(C) 2012-2024 Mitsukibo, Julian Cassin & Francis Weston. All rights reserved.

// options:		eg: { "container": "ge-form-container" }
//	container - the application container
//	background - background class for which to add as a background


// message queues:
//  Internal message queues: crud, speech, print, system, transmit
//  Server message queues: ?

// initial os entry point (before API is setup)
function osEntry(objArgs_a, cbOnReady_a)
{
	//var m_intBytes = 0;	// elementary performance checking
	var m_strVersion = "AWAFOS v20241106.0";
	var m_objArgs = objArgs_a;
	var m_objThis = this;
	var m_blnChild = false;

	if (m_objArgs.background === undefined)
	{
		m_objArgs.background = '';
	}
	if (m_objArgs.child === undefined)
	{
		m_objArgs.child = false;
	}
	if (m_objArgs.container === undefined)
	{
		m_objArgs.container = '';
	}
	if (m_objArgs.dependencies === undefined)
	{
		m_objArgs.dependencies = '';
	}
	if (m_objArgs.orientation === undefined)
	{
		m_objArgs.orientation = '';
	}
	if (m_objArgs.keepalive === undefined)
	{
		m_objArgs.keepalive = false;
	}
	if (m_objArgs.unloadprompt === undefined)
	{
		m_objArgs.unloadprompt = true;
	}
	
	var m_FORMPLACEMENTXTOLERANCE = 1366;
	var m_FORMPLACEMENTYTOLERANCE = 768;
	//var m_FORMOFFSETMAXY = 300;
	var m_CHILDTABPINGFREQUENCY = 10000;
	var m_DEBUGZORDERS = false;
	var m_DEFAULTFORMHEIGHT = 608; //608;
	var m_DEFAULTFORMWIDTH = 1000; //1000;
	var m_FORMCLOSEOFFSET = 28;
	var m_FORMTITLEOFFSET = 30;
	var m_FORMOFFSETMAXVIEWPORTYPERCENT = 10;
	var m_FORMOFFSETMAXX = 500;
	var m_FORMOFFSETX = 15;
	var m_FORMOFFSETY = 10;
	var m_MAXCALIBRATEDZORDERS = 1000;
	var m_MENUFORMWIDTH = 250;
	var m_MINIMUMFORMWIDTH = 500; //1000;
	var m_SYSTEMZORDERS = 10;

	// quirks
	var m_CANVASTOPQUIRK = 1;
	var m_DOCKABLEFORMQUIRK = 1;
	var m_FORMSHORTENINGQUIRK = 10;
	var m_MENUQUIRK = 5;
	var m_SCROLLBARQUIRK = 20;	// 20 for mdi, 0 for sdi

	// for modals (at present only 1 level of modal form supported)
	// var m_objPopupForm;
	var m_objPopupForm = [];
	var m_strPopupFormID = ''; // used by close form
	// var m_cbReturn = null; // used by close form
	var m_cbReturn = []; // used by close form
	// var m_objOldFocusForm; // used by close form
	var m_objOldFocusForm = []; // used by close form
	// var m_strOldFocusFormID; // used by close form
	var m_strOldFocusFormID = []; // used by close form

	var m_intBusyIndicator = 0;
	var m_intBusyIndicatorDeferral = 250;
	var m_strDeviceIDCookie = '';
	var m_strInstanceID = getGUID();
	var m_objPrinter = null;
	var m_strPrinterProvider = '';
	var m_objSpeech = null;
	var m_strSpeechProvider = '';

	var m_blnMenuCreated = false;
	var m_blnMenuOpened = false;
	var m_objMenuForm;
	var m_strMenuFormID = '';
	var m_objMicrophoneForm;
	var m_strMicrophoneForm;
	var m_objTaskbarForm;
	var m_strTaskbarFormID = '';
	var m_objFooterForm;
	var m_strFooterFormID = '';
	var m_intFooterHeight = m_FORMSHORTENINGQUIRK;
	var m_objFocusForm;
	var m_strFocusFormID = '';
	var m_strBounceFocus = ''; // needed due to javascripts insistence on rendering after all logic is processed
	var m_blnTipsEnabled = true;

	var m_intCanvasTop = 0; // number of pixels taken at the top
	var m_intCanvasBottom = m_FORMSHORTENINGQUIRK; // number of pixels taken at the bottom
	var m_intViewPortChangeCount = 0;	// just a stat
	var m_objProperties = [];
	var m_objRegisteredForms = [];
	var m_objRegisteredServerEvents = [];
	var m_objRegisteredTimers = [];
	var m_objResizeQueue = [];
	var m_objSDIFormStack = [];
	var m_objTabHandler;
	var m_strChildTabPingTimerID;

	var m_objBrowser =
	{
		OS : 'unknown',
		name : 'unknown',
		version : 'unknown',
		major : -1,
		capabilities : '',
		incapabilities : '',
		support : 'unavailable'
	};

	var m_intNewFormOffsetX = 0; // so the first form starts at 0
	var m_intNewFormOffsetY = 0;
	var m_intNextDataID = 1;
	var m_intNestedAJAX = 0;
	var m_intAJAXErrorCount = 0;

	var m_intZoom100 = 0; // the spacing when the zoom is at 100%
	var m_blnZoomed = false;

	// browser stuff
	var m_strOldHash = '';
	var m_strUA = navigator.userAgent; 
	
	// uncomment for specific phone
	// samsung phone
	//m_strUA = "Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-N950F) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/11.2 Chrome/75.0.3770.143 Mobile Safari/537.36";

	// apple iPhone
	//m_strUA = "Mozilla/5.0 (iPhone; CPU OS 11_0 like Mac OS X) AppleWebKit/604.1.25 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1";

	// client profile
	var m_blnJavaWorking = false;
	var m_strBrowserCapabilities;

    // memoization / caching
    var m_arrEntityCache = [];
	
	// timeframe is in seconds
    var m_arrMemoizationEntities = [
        {
            name : "SYSTEMFORM",
            timeframe : 5
        },
        {
            name : "xLVBARCODETYPE",
            timeframe : 5
        },
        {
            name : "xLVBOOKINGSTATUS",
            timeframe : 5
        },
        {
            name : "xLVBOOKINGTYPE",
            timeframe : 5
        },
        {
            name : "xLVCASENOTECATEGORY",
            timeframe : 5
        },
        {
            name : "xLVCASENOTESTATUS",
            timeframe : 5
        },
        {
            name : "xLVPERSONSTATUS",
            timeframe : 5
        },
        {
            name : "xLVPHONENUMBERASSOC",
            timeframe : 5
        }
    ];

	// ====================================================================================
	// PRIVATES ===========================================================================

	function detectBrowserInfo()
	{
		var lngMajor = 0;
		try
		{
			// m_objBrowser.version = $.browser.version;
			// var arrVersion = m_objBrowser.version.split('.');
			// var lngMajor = parseInt(arrVersion[0], 10);
			// m_objBrowser.major = lngMajor;

			// we use our name in preference to BrowserDetect (at least for now)
			//m_objBrowser.OS = BrowserDetect.OS;
			//m_objBrowser.version = BrowserDetect.version;
			//lngMajor = BrowserDetect.version;
			//m_objBrowser.major = lngMajor;

			var objUAParser = new UAParser(m_strUA);
			var objUAParserInfo = objUAParser.getResult();
			m_objBrowser.OS = objUAParserInfo.os.name;
			m_objBrowser.name = objUAParserInfo.browser.name;
			m_objBrowser.version = objUAParserInfo.browser.major;
			lngMajor = objUAParserInfo.browser.major;
			m_objBrowser.major = lngMajor;
		}
		catch (err)
		{
			doException('detectBrowserInfo', err);
		}

		// capabilities can be:
		var strFullCapabilities = FULLBROWSERCAPABILITY;

		processArray(g_objBrowsers, function (objBrowser_a)
		{
			var objREOS = new RegExp(objBrowser_a.osid);
			var objREBrowser = new RegExp(objBrowser_a.browserid);
			if ((m_objBrowser.OS.match(objREOS)) && (m_objBrowser.name.match(objREBrowser)) && (lngMajor >= objBrowser_a.major))
			{
				m_objBrowser.name = objBrowser_a.name;
				m_objBrowser.capabilities = objBrowser_a.capabilities;
				m_objBrowser.support = objBrowser_a.supportlevel;
				return true;
			}
		}
		);

		if ((m_objBrowser.support === 'unavailable') && (BYPASSBROWSERCHECK === 'TRUE'))
		{
			m_objBrowser.support = 'minimal';
			m_objBrowser.capabilities = '';
		}

		m_objBrowser.incapabilities = diffString(str_replace(strFullCapabilities, ',', ' '), str_replace(m_objBrowser.capabilities, ',', ' '));
	}

	function testBrowser()
	{
		var blnResult = true;

		//m_objBrowser.support = 'unavailable'; // uncomment this when testing to force the dialog on JS-capable browsers

		if (m_objBrowser.support === 'unavailable')
		{
			blnResult = false;

			var strBrowserMessage = '<font color="red"><b>Support for this browser is currently ' + m_objBrowser.support + '.</b></font><br><br>';
			strBrowserMessage += '<font color="red"><b>Your browser is identified as ' + m_objBrowser.name + ' version ' + m_objBrowser.version + ' on ' + m_objBrowser.OS + '</b></font><br><br>';
			strBrowserMessage += '<font color="red"><b>User Agent "' + m_strUA + '"</b></font><br><br>';

			// display the browsers choice
			document.getElementById('ge-browser-message').innerHTML = strBrowserMessage;
			document.getElementById('ge-browsers').style.visibility = 'visible';
			document.bgColor = 'white';
		}

		return blnResult;
	}

	// return the browser viewport size
	function testViewPort()
	{
		var intHeight = 0;
		var intWidth = 0;
		var objPerspective = window;
		var strPrefix = 'inner';

		if (!('innerWidth' in window))
		{
			strPrefix = 'client';
			objPerspective = document.documentElement || document.body;
		}

		intHeight = objPerspective[strPrefix + 'Height'];
		intWidth = objPerspective[strPrefix + 'Width'];

		var objResult = 
		{
			width : intWidth,
			height : intHeight
		};
		
		return objResult;
	}

	detectBrowserInfo();

	if (m_objArgs.background.length > 0)
	{
		$('html').addClass(m_objArgs.background);
	}

	// redefined os entry point which includes the API
	osEntry = function ()
	{
		// ====================================================================================
		// INITIALISATION POST INSTANTIATION ==================================================

		function sleep(intMilliseconds_a) 
		{
		  var intStart = new Date().getTime();
		  for (var intI = 0; intI < 1e7; intI++) 
		  {
			if ((new Date().getTime() - intStart) > intMilliseconds_a)
			{
			  break;
			}
		  }
		}

		m_objThis.initialise = function ()
		{
			m_blnChild = os().getArgs().child;
			var strTabID = os().getArgs().tabID;

			// remove browser page
			m_objThis.element('#ge-browsers').remove();

			// system shortcuts
			m_objThis.shortcutAdd(g_objShortcuts.keycloseform, function ()
			{
				m_objThis.closeFormFocus();
			}
			);

			processArray(g_objShortcutsOverride, function (objShortcut_a)
			{
				m_objThis.shortcutAdd(objShortcut_a.keys, doNothing);
			}
			);

			// system wide backspace behaviour (negatively impacts slickgrid's select2 editor)
			$(document).unbind('keydown').bind('keydown', function (objEvent_a)
			{
				var doPrevent = false;
				if (objEvent_a.keyCode === 8)
				{
					var objElement = objEvent_a.srcElement || objEvent_a.target;
					if ((objElement.tagName.toUpperCase() === 'INPUT' && (objElement.type.toUpperCase() === 'TEXT' || objElement.type.toUpperCase() === 'PASSWORD')) || objElement.tagName.toUpperCase() === 'TEXTAREA')
					{
						doPrevent = objElement.readOnly || objElement.disabled;
					}
					else
					{
						doPrevent = true;
					}
				}

				if (doPrevent)
				{
					objEvent_a.preventDefault();
				}
			}
			);

			// system wide exit
			$(window).bind('beforeunload', function ()
			{
				if (m_objArgs.unloadprompt)
				{
					return APP_NAME + '\n\nPlease be sure you have saved your data before continuing.';
				}
			}
			);

			// tab handlers
			if (m_objThis.hasCapability('multimon'))
			{
				if (m_blnChild)
				{
					m_objTabHandler = new childTabHandler(
						{
							"id" : strTabID,
							"cbOnBroadcast" : onTabBroadcast,
							"window" : window
						}
						);

					m_strChildTabPingTimerID = m_objThis.createTimer(m_objTabHandler.ping, m_CHILDTABPINGFREQUENCY, true);
				}
				else
				{
					m_objTabHandler = new parentTabHandler(
						{
							"cbOnBroadcast" : onTabBroadcast,
							"window" : window
						}
						);
				}
			}

			// read the PC Name, if not found, create one
			if (DEVICEIDCOOKIE.length > 0)
			{
				m_strDeviceIDCookie = DEVICEIDCOOKIE;
				//alert('w:' + m_strDeviceIDCookie);
			}
			else
			{
				m_strDeviceIDCookie = getCookie(COOKIE_DEVICENAME);
				//alert('x:' + m_strDeviceIDCookie);
				if (m_strDeviceIDCookie === null)
				{
					m_strDeviceIDCookie = '';
				}
				if (m_strDeviceIDCookie.length === 0)
				{
					var dteExpiry = new Date();
					dteExpiry.setYear(parseInt(dteExpiry.getFullYear(), 10) + COOKIE_EXPIRY);
					m_strDeviceIDCookie = getGUID();
					setCookie(COOKIE_DEVICENAME, m_strDeviceIDCookie, dteExpiry);
					//alert('y:' + m_strDeviceIDCookie);
				}
				//alert('z:' + m_strDeviceIDCookie);
			}

			// zoom detection
			try
			{
				m_objThis.element('#ge-zoom2-container').get(0).style.left = m_objThis.element('#ge-zoom1-container').get(0).offsetLeft + 'px';
				m_intZoom100 = m_objThis.element('#ge-zoom1-container').get(0).offsetLeft;
			}
			catch (err)
			{
				doException('initialise', err);
			}

			// orientation changes
			if ($.isFunction(m_objArgs.orientation))
			{
				var checkOrientation = function ()
				{
					var intOrientation = m_objThis.getOrientation();

					if (intOrientation !== g_intPreviousOrientation)
					{
						g_intPreviousOrientation = intOrientation;
						m_objArgs.orientation(intOrientation);
						m_objThis.broadcast('system', 'orientation', 'change');
					}
				};

				var onResize = function (strFormID)
				{
					processForms(function (objForm_a)
					{
						if (objForm_a.resizable)
						{
							onFormResizeAfterDelay(objForm_a.id);
						}
					});
					onFormResizeAfterDelay(m_strMenuFormID);
					m_objThis.menuToFront();
				};

				window.addEventListener("resize", checkOrientation, false);
				window.addEventListener("resize", onResize, false);
				window.addEventListener("orientationchange", checkOrientation, false);
				setInterval(checkOrientation, 2000);
				//setInterval(onResize, 2000);
			}
			
			// keepalive
			if (m_objArgs.keepalive && m_objThis.hasCapability('timers'))
			{
				m_objThis.registerServerEvent('keepalive');
			}
		};

		// ====================================================================================
		// SYSTEM =============================================================================

		m_objThis = this;

		// adds a unique id to an element so that 3rd party components that are hardcoded to use IDs can work
		this.addUniqueID = function (objElement_a)
		{
			var strResult = 'elementid-' + getGUID();
			objElement_a.attr('id', strResult);
			return strResult;
		};

		this.getArgs = function ()
		{
			return m_objArgs;
		};

		this.getForms = function ()
		{
			return m_objRegisteredForms;
		};

		this.getTabs = function ()
		{
			var arrResult = [];

			if (m_objThis.hasCapability('multimon'))
			{
				arrResult = m_objTabHandler.getTabs();
			}

			return arrResult;
		};

		this.getTimers = function ()
		{
			return m_objRegisteredTimers;
		};

		// return the os version
		this.getVersion = function ()
		{
			return m_strVersion;
		};

		this.logout = function(strParameters_a, strSource_a)
		{
			m_objThis.setUnloadPrompt(false);
			if (m_objThis.hasCapability('timers'))
			{
				m_objThis.deleteTimersAll();
			}

			m_objThis.after(500, function()
			{
				m_objThis.gotoURL(APP_LOGOUT, strParameters_a, strSource_a);
			});
		};
		
		this.clientReturn = function(strParameters_a, strSource_a)
		{
			m_objThis.setUnloadPrompt(false);
			if (m_objThis.hasCapability('timers'))
			{
				m_objThis.deleteTimersAll();
			}

			m_objThis.after(500, function()
			{
				m_objThis.gotoURL(APP_RETURN, strParameters_a, strSource_a);
			});
		};
		
		// restart os safely
		this.reboot = function(strParameters_a, strSource_a)
		{
			m_objThis.setUnloadPrompt(false);
			if (m_objThis.hasCapability('timers'))
			{
				m_objThis.deleteTimersAll();
			}

			m_objThis.after(500, function()
			{
				m_objThis.gotoURL(APP_HOME, strParameters_a, strSource_a);
			});
		};
		
		this.isChild = function()
		{
			return m_blnChild;
		};
		
		function processURL(objURL_a)
		{
			var blnNewTab = objURL_a.newtab;
			var blnShowForm = objURL_a.showform;
			var strURL = objURL_a.url;
			var arrParams = objURL_a.parameters;
			
			if (blnNewTab)
			{
				window.open(strURL,'_blank');
			}
			else if (blnShowForm)
			{
				m_objThis.showForm(strURL, arrParams);
			}
			else
			{
				m_objThis.gotoURL(strURL, arrParams, 'Server');
			}
		}
		
		// go to a URL
		this.gotoURL = function (strURL_a, strParameters_a, strSource_a)
		{
			var strSource = strSource_a;
			if (strSource === undefined) { strSource = ''; }
			
			if (strSource.length > 0)
			{
				logDebug('URL redirect from:' + strSource + ' to: ' + strURL_a);
				//alert('URL redirect from:' + strSource + ' to: ' + strURL_a);
			}
			
			var strDestination = m_objThis.makeURL(strURL_a, strParameters_a);
			
			if (strSource != strDestination)
			{
				location = strDestination;
			}
			//window.location.replace(m_objThis.makeURL(strURL_a, strParameters_a));
		};

		this.isModuleLoaded = function (strModuleName_a)
		{
			var strLoadedModules = ',' + CLIENTMODULES + ',';

			return (strLoadedModules.indexOf(',' + strModuleName_a + ',') >= 0);
		};

		this.makeURL = function (strURL_a, strParameters_a)
		{
			var strURL = strURL_a;

			if ((strParameters_a !== undefined) && (strParameters_a.length > 0))
			{
				var arrParameters = strParameters_a.split('&');
				for (var intI = 0; intI < arrParameters.length; intI++)
				{
					var arrParameter = arrParameters[intI].split('=');
					strURL = addURLParameter(strURL, arrParameter[0], arrParameter[1]);
				}
			}

			return strURL;
		};
		
		this.isApp = function(arrCodes_a)
		{
			var blnResult = false;
			
			processArray(arrCodes_a, function(strCode_a)
			{
				if (strCode_a.toUpperCase() == APP_CODE.toUpperCase())
				{
					blnResult = true;
					return true;
				}
			});

			return blnResult;
		};

		this.setBackground = function (strClass_a)
		{
			$('html').addClass(strClass_a);
		};
		
		this.setFocus = function()
		{
			window.focus();
		};

		// ====================================================================================
		// AJAX ===============================================================================

        this.entityDataCache = new function()
        {
			var m_objThis = this;
			
            function flushCache(strEntity_a, intIndex_a)
            {   
				// expired, flush it from the cache
				m_arrEntityCache = m_arrEntityCache.filter(function(el_a, intI_a) 
				{
					return intI_a !== intIndex_a;
				});
				logDebug('%c' + strEntity_a + ' cached purged', 'color:red;');
            }

            function getEntityFromParameters(arrParameters_a)
            {
                var strEntity = '';

                processArray(arrParameters_a, function(objParameter_a) 
                {
                    if(objParameter_a.name === 'formentitycode')
                    {
                        strEntity = objParameter_a.value;
                    }
                });

                return strEntity;
            }

            function getMemoizationEntity(strEntity_a)
            {
                var objResult;

                processArray(m_arrMemoizationEntities, function(objMemoiEntity) 
				{
                    if(objMemoiEntity.name === strEntity_a)
                    {
                        objResult = objMemoiEntity;
                        return;
                    }
                });

                return objResult;
            }

            this.addToCache = function(objJSONRequest_a, objResponse_a)
            {
				var intIndex = m_objThis.findCachedData(objJSONRequest_a);
				var arrParameters = objJSONRequest_a.AWAFOS.parameters;
				var strParameters = JSON.stringify(arrParameters);
				var strFunction = objJSONRequest_a.AWAFOS['function'];
				var strEntity = getEntityFromParameters(arrParameters);
                                
				if ((intIndex === -1) && (strEntity.length > 0))
				{
					m_arrEntityCache.push({
						"function" : strFunction,
						entity   : strEntity,
						request  : strParameters,
						response : objResponse_a,
						time : new Date()
					});
					
					logDebug('%c' + strEntity + ' data cached', 'color:green;');
				}
            };
			
            this.findCachedData = function(objJSONRequest_a)
            {   
                var intResult = -1;
                var arrParameters = objJSONRequest_a.AWAFOS.parameters;
                var strParameters = JSON.stringify(arrParameters);    
                var strFunction = objJSONRequest_a.AWAFOS['function']; // need to do this because .function throws error in js lint
                var strEntity = getEntityFromParameters(arrParameters);

                if (m_objThis.isCachable(objJSONRequest_a) && m_arrEntityCache.length > 0)
                {
                    processArray(m_arrEntityCache, function(objDataCached_a, intRow_a, intIndex_a) 
					{
                        if ((strEntity.length > 0) && (objDataCached_a.entity === strEntity) && (objDataCached_a['function'] === strFunction) && (objDataCached_a.request === strParameters))
                        {                                       
							intResult = intIndex_a;
							return true;
                        }
                    });
                }
                
                return intResult;
            };

            this.isCachable = function(objJSONRequest_a)
            {
                var blnResult = false;
                var strEntity = getEntityFromParameters(objJSONRequest_a.AWAFOS.parameters);

                if ((strEntity.length > 0) && (m_arrMemoizationEntities.length > 0))
                {
                    processArray(m_arrMemoizationEntities, function(objMemoiEntity) 
					{
                        if(objMemoiEntity.name === strEntity)
                        {
                            blnResult = true;
                            return true;
                        }
                    });
                }

                return blnResult;
            };

            this.readCache = function(objJSONRequest_a)
            {   
                var objResult;
				var arrParameters = objJSONRequest_a.AWAFOS.parameters;
				var intIndex = m_objThis.findCachedData(objJSONRequest_a);
                var strEntity = getEntityFromParameters(arrParameters);

                if (intIndex >= 0)
                {
                    var objMemoiEntity = getMemoizationEntity(strEntity);
                    var objCachedEntity = m_arrEntityCache[intIndex];

					if (((new Date()) - objCachedEntity.time) / 1000 > objMemoiEntity.timeframe)
					{
						// expired, flush it from the cache
						flushCache(strEntity, intIndex);
					}
					else
					{
						// return cached item
						objResult = objCachedEntity.response;
					}
                }
                
                return objResult;
            };
        };

		this.ajaxCall = function (strWebServiceURL_a, objJSONRequest_a, cbSuccess_a, cbError_a, cbFailure_a, blnNoBlock_a)
		{
			var blnError = false;
			var blnNoBlock = blnNoBlock_a;
            var objDataCachedResponse;

			if (blnNoBlock === undefined)
			{
				blnNoBlock = false;
			}

			m_objThis.transmissionIndicator(true);
            
            objDataCachedResponse = m_objThis.entityDataCache.readCache(objJSONRequest_a);
                        
			function ajaxSuccess(objResponse_a, blnDoNotCache_a)
			{
				var strErrorMessage = '';
				var blnDoNotCache = blnDoNotCache_a;
				
				if (blnDoNotCache !== undefined) { blnDoNotCache = false; }

				function ajaxSuccessContinue()
				{
					try
					{
						if ($.isFunction(cbSuccess_a))
						{
							cbSuccess_a(objResponse_a.AWAFOS.response);
                        }
                        
                        if (!blnDoNotCache && m_objThis.entityDataCache.isCachable(objJSONRequest_a))
                        {
                            m_objThis.entityDataCache.addToCache(objJSONRequest_a, objResponse_a);
                        }
					}
					catch (err)
					{
						doException('ajaxSuccessContinue', err);
					}
				}

				function ajaxErrorContinue(strErrorMessage_a)
				{
					try
					{
						if ($.isFunction(cbError_a))
						{
							cbError_a(cbFailure_a, strErrorMessage_a);
						}
					}
					catch (err)
					{
						doException('ajaxErrorContinue', err);
					}
				}

				m_objThis.transmissionIndicator(false);

				if (m_intAJAXErrorCount !== 0)
				{
					m_intAJAXErrorCount = 0;
					m_objThis.connectionIndicator(true);
				}

				if (blnError === false)
				{
					if (blnNoBlock === false)
					{
						$('body').css('cursor', 'auto');
						unblockAll();
					}

					if ((objResponse_a === undefined) || (objResponse_a === null))
					{
						ajaxErrorContinue('Undefined or Null Response');
					}
					else
					{
						logDebug('response:' + JSON.stringify(objResponse_a));
						logDebug('========== END REQUEST');

						try
						{
							switch (parseInt(objResponse_a.AWAFOS.responsecode, 10))
							{
							case 0:
								// success with message (originally was without message, effectively this is the same as 1 now)
								m_objThis.dialogAlert(objResponse_a.AWAFOS.message, doNothing);
								ajaxSuccessContinue();
								break;

							case 1:
								// deprecated
								m_objThis.dialogAlert(objResponse_a.AWAFOS.message, doNothing);
								ajaxSuccessContinue();
								break;

							case 2:
								// error with message
								blnError = true;
								ajaxErrorContinue(objResponse_a.AWAFOS.message);
								break;

							case 3:
								// forced logout
								m_objThis.deleteTimersAll();
								m_objArgs.unloadprompt = false;
								m_objThis.dialogAlert(objResponse_a.AWAFOS.message, doNothing);

								//location = URL_FORCEDLOGOUT_REDIRECT; //APP_LOGOUT;
								location = APP_LOGOUT;
								//window.location.replace(APP_LOGOUT);
								blnError = true;
								return;

								break;

							case 4:
								// document
								var blnDownload = objResponse_a.AWAFOS.response.download;
								if (blnDownload === undefined) { blnDownload = true; }
								
								m_objThis.viewDocument(objResponse_a.AWAFOS.response.document_id, blnDownload);
								ajaxSuccessContinue();
								break;

							case 5:
								// url
								if (objResponse_a.AWAFOS.message.length > 0)
								{
									m_objThis.dialogAlert(objResponse_a.AWAFOS.message, function()
									{
										m_objArgs.unloadprompt = false;
										processURL(objResponse_a.AWAFOS.response);
										//m_objThis.gotoURL(objResponse_a.AWAFOS.response, '', 'Server');
									});
								}
								else
								{
									m_objArgs.unloadprompt = false;
									processURL(objResponse_a.AWAFOS.response);
									//m_objThis.gotoURL(objResponse_a.AWAFOS.response, '', 'Server');
								}
								break;

							case 6:
								// reload
								m_objThis.reboot('', 'Server');
								break;

							case 7:
								// broadcast printjob
								m_objThis.broadcast('system', 'printer', ENTITY_PRINTJOB);
								m_objThis.dialogAlert(objResponse_a.AWAFOS.message, doNothing);
								ajaxSuccessContinue();
								break;

							default:
								blnError = true;
								ajaxErrorContinue('Undefined Error');
								break;
							}
						}
						catch (err)
						{
							//alert(JSON.stringify(objResponse_a));
							ajaxErrorContinue('System Error');
						}
					}
				}
			}

			m_objThis.viewDocument = function(strDocumentID_a, blnForceDownload_a) 
			{

				var strURL = m_objThis.getFileDownloadURL(encodeURIComponent(strDocumentID_a));

				if(blnForceDownload_a) 
				{
					strURL += "&download=true";
				}
				else
				{
					strURL += "&download=false";
				}

				if (strURL.length > 0)
				{
					m_objThis.setUnloadPrompt(false);
					//location = strURL;		// note: why does this work only?
					window.open(strURL);	// note: should be this

					// turn on the unload prompt after a timeframe because otherwise it seems to turn on before the download started
					m_objThis.after(3000, function ()
					{
						m_objThis.setUnloadPrompt(true);
					}
					);
				}
			};

			// this and the flag stuff to help IE7 not send both a success and failure callback
			function ajaxRaiseError()
			{
				logDebug('========== END REQUEST DUE TO ERROR');

				m_intAJAXErrorCount++;
				if (m_intAJAXErrorCount >= ERROR_INDICATOR_FAILURECOUNT)
				{
					m_objThis.connectionIndicator(false);
				}

				m_objThis.transmissionIndicator(false);

				if (blnNoBlock === false)
				{
					$('body').css('cursor', 'auto');
					unblockAll();
				}

				blnError = true;

				try
				{
					if ($.isFunction(cbError_a))
					{
						cbError_a(cbFailure_a);
					}
				}
				catch (err)
				{
					doException('ajaxRaiseError', err);
				}
			}

            if(objDataCachedResponse !== undefined)
            {
                logDebug('%ccache hit', 'color:green;');
                logDebug('========== START CACHE FETCH');
                logDebug('request:' + JSON.stringify(objJSONRequest_a));
				
				objDataCachedResponse.AWAFOS.dataid = objJSONRequest_a.AWAFOS.dataid;

                try
                {
                    if ($.isFunction(cbSuccess_a))
                    {
                        ajaxSuccess(objDataCachedResponse, true);	// don't re-cache cached data
                    }                    
                }
                catch(err) {}
            }
			else if (USEJSONP)
			{
                logDebug('%ccache miss', 'color:red;');
				logDebug('========== START AJAX JSONP');
				logDebug('request:' + JSON.stringify(objJSONRequest_a));

				// note: we may need to encode the JSONP JSON object on the URL
				if (blnNoBlock === false)
				{
					blockAll(false);
					$('body').css('cursor', 'wait');
				}

				$.ajax(
				{
					type : 'GET',
					async : true,
					url : strWebServiceURL_a + '?json=' + encodeURIComponent(JSON.stringify(objJSONRequest_a)) + '&callback=?',
					data : JSON.stringify(objJSONRequest_a),
					contentType : 'application/x-www-form-urlencoded; charset=utf-8',
					dataType : 'jsonp',
					success : ajaxSuccess,
					error : ajaxRaiseError
				}
				);
			}
			else
			{
                logDebug('%ccache miss', 'color:red;');
				logDebug('========== START AJAX JSON');
				logDebug('request:' + JSON.stringify(objJSONRequest_a));

				if (blnNoBlock === false)
				{
					blockAll(false);
					$('body').css('cursor', 'wait');
				}

				$.ajax(
				{
					type : 'POST',
					async : true,
					url : strWebServiceURL_a,
					data : JSON.stringify(objJSONRequest_a),
					contentType : 'application/x-www-form-urlencoded; charset=utf-8',
					dataType : 'json',
					success : ajaxSuccess,
					error : ajaxRaiseError
				}
				);
			}
		};

		// re-usable error dialog if forms are too lazy to create their own
		this.ajaxError = function (cbFailure_a, strErrorMessage_a)
		{
			var strErrorMessage = strErrorMessage_a;
			if (strErrorMessage === undefined)
			{
				strErrorMessage = 'Connection Error';
			}

			$('body').css('cursor', 'auto');
			unblockAll();
			m_objThis.hideBusyIndicator();
			m_objThis.dialogAlert('Attention: ' + strErrorMessage, doNothing);

			if ($.isFunction(cbFailure_a))
			{
				cbFailure_a();
			}
		};

		// wraps our request with a header
		this.ajaxRequestCreate = function (strFunction_a, objParameters_a, strDataID_a)
		{
			var strDataID = strDataID_a;
			if (strDataID === undefined)
			{
				strDataID = 'sys' + m_intNextDataID;
				m_intNextDataID++;
			}

			var objJSON =
			{
				"AWAFOS" :
				{
					"securitytoken" : SECURITY_TOKEN,
					"clientversion" : CLIENT_VERSION,
					"deviceidcookie" : m_strDeviceIDCookie,
					"payload" : PAYLOAD,
					"callerid" : CALLERID_PUBLIC,
					"dataid" : strDataID,
					"function" : strFunction_a,
					"parameters" : objParameters_a
				}
			};

			return objJSON;
		};

		// NOT USED
		this.fileDownload = function (strSuffix_a)
		{
			var strURL = m_objThis.getFileDownloadURL(strSuffix_a);
			$.fileDownload(strURL).done(doNothing).fail(function ()
			{
				m_objThis.ajaxError('File not found.');
			}
			);
		};

		this.getFileDownloadURL = function (strSuffix_a)
		{
			var strURL = 'ws/server.php?token=' + SECURITY_TOKEN + '&document=' + strSuffix_a;
			return strURL;
		};

		// ====================================================================================
		// BROWSER ============================================================================

		function initialiseMDI(strFormID_a, blnResizable_a, blnDockable_a)
		{
			if (blnDockable_a)
			{
				m_objThis.resizeDockedForm('#' + strFormID_a);
				m_objThis.formobject = true;
			}
			else
			{
				m_objThis.element('#' + strFormID_a, '.gb-form').draggable(
				{
					handle : '.gb-form-handle'
				}
				);
			}

			if ((blnResizable_a) && (!blnDockable_a))
			{
				m_objThis.element('#' + strFormID_a, '.gb-form').resizable({ 
					minHeight: 200,
					minWidth: m_MINIMUMFORMWIDTH,
					resize: function(objEvent_a, objUI_a) 
					{ 
						onFormResize(strFormID_a);
					} 
				});
			}
				
			onFormResize(strFormID_a);
			m_objThis.broadcast('system', 'viewport', 'change');
			m_intViewPortChangeCount++; 
			logDebug('ViewPortChangeCount2:' + m_intViewPortChangeCount);
			
			m_objThis.showMDIClose('#' + strFormID_a);
		}

		this.initialiseScrollbars = function(blnForceVertical_a)
		{
			if (m_objThis.isMDI() || blnForceVertical_a)
			{
				$('body').css('overflow', 'auto');
				m_SCROLLBARQUIRK = 20;
			}
			else
			{
				$('body').css('overflow-x', 'hidden');
				$('body').css('overflow-y', 'hidden');  // TODO: need to somehow make the taskbar.htm always stay on top layer and also don't make menu scroll with body.  Without y scrolling, long forms like registration can't get to bottom fields.
				m_SCROLLBARQUIRK = 0;
			}
		};
		
		this.repositionForm = function(strFormID_a, intTop_a, intLeft_a)
		{
			var intTop = intTop_a; // - m_intCanvasTop - m_FORMSHORTENINGQUIRK;
			var intLeft = intLeft_a;
			//m_objThis.element(strFormID_a, '.gb-form').removeClass('gb-resizable');
			//m_objThis.element(strFormID_a, '.gb-form').removeClass('gs-shadow');
			//m_objThis.element(strFormID_a, '.gb-form').removeClass('gs-form-standard');
			//m_objThis.element(strFormID_a, '.gb-notdockable').remove();
			m_objThis.element(strFormID_a, '.gb-form').css('top', intTop + 'px');
			m_objThis.element(strFormID_a, '.gb-form').css('left', intLeft + 'px');
			//m_objThis.element(strFormID_a, '.gb-form').css('height', intHeight + 'px');
			//m_objThis.element(strFormID_a, '.gb-form').css('width', intWidth + 'px');
			//m_objThis.element(strFormID_a, '.gb-formtitle-panel').css('width', (intWidth - m_FORMTITLEOFFSET) + 'px');
			//m_objThis.element(strFormID_a, '.ge-panel-content').css('height', (intHeight-160) + 'px');	// should be calculated by individual forms
			//m_objThis.element(strFormID_a, '.ge-panel-overflow').css('height', '100%');	// should be calculated by individual forms
		};
		
		this.resizeDockedForm = function(strFormID_a)
		{
			var blnResult = false;
			
			if (m_objThis.isDockable(strFormID_a))
			{
				var intTop = m_intCanvasTop; // + 4;
				var intLeft = 0;
				if (m_blnMenuOpened)
				{
					intLeft = m_MENUFORMWIDTH;
				}
				
				var intHeight = m_objThis.getViewPort().height - intTop - m_intFooterHeight + m_DOCKABLEFORMQUIRK;
                var intWidth = m_objThis.getViewPort().width - intLeft - m_SCROLLBARQUIRK;
                
                var blnWidthResponsive = ((TESTSCROLL == 'TRUE')  && !m_objThis.hasCapability('regionscroll') && m_objThis.hasCapability('mobile'));

				//m_objThis.element(strFormID_a, '.gb-form').removeClass('gb-resizable');
				m_objThis.element(strFormID_a, '.gb-form').removeClass('gs-shadow');
				m_objThis.element(strFormID_a, '.gb-form').removeClass('gs-form-standard');
				//m_objThis.element(strFormID_a, '.gb-notdockable').remove();
				m_objThis.element(strFormID_a, '.gb-form').css('top', intTop + 'px');
                m_objThis.element(strFormID_a, '.gb-form').css('left', intLeft + 'px');
                                
                if(blnWidthResponsive)
                {               
                    m_objThis.element(strFormID_a, '.gb-form').css('width', '100%');  
                }
                else
                {
                    m_objThis.element(strFormID_a, '.gb-form').css('width', intWidth + 'px');
                }

                m_objThis.element(strFormID_a, '.gb-form').css('height', intHeight + 'px');
				m_objThis.element(strFormID_a, '.gb-formtitle-panel').css('width', (intWidth - m_FORMTITLEOFFSET) + 'px');
				//m_objThis.element(strFormID_a, '.ge-panel-content').css('height', (intHeight-160) + 'px');	// should be calculated by individual forms
				//m_objThis.element(strFormID_a, '.ge-panel-overflow').css('height', '100%');	// should be calculated by individual forms
				
                blnResult = true;                                
			}
			
			return blnResult;
		};
		
		this.resizeForm = function(strFormID_a, intHeight_a, intWidth_a)
		{
			//var intTop = intTop_a; // - m_intCanvasTop - m_FORMSHORTENINGQUIRK;
			//var intLeft = intLeft_a;
			var intHeight = intHeight_a;
			var intWidth = intWidth_a;
			//m_objThis.element(strFormID_a, '.gb-form').removeClass('gb-resizable');
			//m_objThis.element(strFormID_a, '.gb-form').removeClass('gs-shadow');
			//m_objThis.element(strFormID_a, '.gb-form').removeClass('gs-form-standard');
			//m_objThis.element(strFormID_a, '.gb-notdockable').remove();
			//m_objThis.element(strFormID_a, '.gb-form').css('top', intTop + 'px');
			//m_objThis.element(strFormID_a, '.gb-form').css('left', intLeft + 'px');
			m_objThis.element(strFormID_a, '.gb-form').css('height', intHeight + 'px');
			m_objThis.element(strFormID_a, '.gb-form').css('width', intWidth + 'px');
			m_objThis.element(strFormID_a, '.gb-formtitle-panel').css('width', (intWidth - m_FORMTITLEOFFSET) + 'px');
			//m_objThis.element(strFormID_a, '.ge-panel-content').css('height', (intHeight-160) + 'px');	// should be calculated by individual forms
			//m_objThis.element(strFormID_a, '.ge-panel-overflow').css('height', '100%');	// should be calculated by individual forms
		};
		
		this.resizeAndRepositionForm = function(strFormID_a, intTop_a, intLeft_a, intHeight_a, intWidth_a)
		{
			var intTop = intTop_a; // - m_intCanvasTop - m_FORMSHORTENINGQUIRK;
			var intLeft = intLeft_a;
			var intHeight = intHeight_a;
			var intWidth = intWidth_a;
			//m_objThis.element(strFormID_a, '.gb-form').removeClass('gb-resizable');
			//m_objThis.element(strFormID_a, '.gb-form').removeClass('gs-shadow');
			//m_objThis.element(strFormID_a, '.gb-form').removeClass('gs-form-standard');
			//m_objThis.element(strFormID_a, '.gb-notdockable').remove();
			m_objThis.element(strFormID_a, '.gb-form').css('top', intTop + 'px');
			m_objThis.element(strFormID_a, '.gb-form').css('left', intLeft + 'px');
			m_objThis.element(strFormID_a, '.gb-form').css('height', intHeight + 'px');
			m_objThis.element(strFormID_a, '.gb-form').css('width', intWidth + 'px');
			m_objThis.element(strFormID_a, '.gb-formtitle-panel').css('width', (intWidth - m_FORMTITLEOFFSET) + 'px');
			//m_objThis.element(strFormID_a, '.ge-panel-content').css('height', (intHeight-160) + 'px');	// should be calculated by individual forms
			//m_objThis.element(strFormID_a, '.ge-panel-overflow').css('height', '100%');	// should be calculated by individual forms
		};
		
		function resizeMenu()
		{
			var blnResult = false;
			
			if (m_strMenuFormID.length > 0)
			{
				var intTop = m_intCanvasTop; // + 4;
				var intHeight = m_objThis.getViewPort().height - intTop - m_intFooterHeight + m_MENUQUIRK;	// the -4 is to prevent a page vertical scrollbar from appearing 
				m_objThis.element('#' + m_strMenuFormID, '.gb-form').css('height', intHeight + 'px');
				blnResult = true;
			}
			
			return blnResult;
		}
		
		this.getAgent = function ()
		{
			return m_strUA;
		};

		this.getBrowser = function ()
		{
			return m_objBrowser;
		};

		this.getBrowserCapabilities = function()
		{
			var arrCapabilities = m_objThis.getBrowser().incapabilities.split(' ');
			var intCapability = 2; // start at 2 because the word capabilities: takes up space
			var strCapabilities = '';

			if (m_strBrowserCapabilities === undefined)
			{
				processArray(arrCapabilities, function (strCapability_a)
				{
					var strCapability = strCapability_a;
					strCapability = strCapability.replace("</del>", "");
					strCapability = strCapability.replace("<del>", "-");
					if ($.trim(strCapability).length > 0)
					{
						if ((intCapability % 20) === 0)
						{
							strCapabilities = $.trim(strCapabilities + ' ' + strCapability) + '\r\n';
						}
						else
						{
							strCapabilities = strCapabilities + ' ' + strCapability;
						}
						intCapability++;
					}
				}
				);

				m_strBrowserCapabilities = strCapabilities;
			}

			return m_strBrowserCapabilities;
		};

		this.getBrowserCapabilityInfo = function (strCode_a)
		{
			var objResult;

			processArray(g_objCapabilities, function (objCapability_a)
			{
				if ((objCapability_a.code == strCode_a) && (objCapability_a.description !== 'hidden'))	// to check why is it not gb-hidden?
				{
					objResult = objCapability_a;
					return true;
				}
			}
			);

			return objResult;
		};

		this.getBrowserRecommendation = function ()
		{
			var strResult = '';

			if (m_objBrowser.support === 'passable')
			{
				strResult = 'A browser upgrade is recommended.';
			}
			else if (m_objBrowser.support === 'unsupported')
			{
				strResult = 'A browser upgrade is recommended.';
			}

			return strResult;
		};

		this.getBrowserVersion = function ()
		{
			return m_objBrowser.version;
		};

		this.getDeviceIDCookie = function ()
		{
			return m_strDeviceIDCookie;
		};

		this.getHealth = function()
		{
			var strUserAgent = 'Agent: ' + m_objThis.getAgent();
			var strMapsProvider = m_objThis.getMapProvider();
			if (strMapsProvider.length === 0)
			{
				strMapsProvider = 'none';
			}
			var strPrinterProvider = m_objThis.getPrinterProvider();
			if (strPrinterProvider.length === 0)
			{
				strPrinterProvider = 'none';
			}
			var strSpeechProvider = m_objThis.getSpeechProvider();
			if (strSpeechProvider.length === 0)
			{
				strSpeechProvider = 'none';
			}

			// var strCapabilitiesTable = '<table style="width:80%; margin:auto;" border="1"><thead><tr><td><b>&nbsp;Feature</b></td><td style="text-align:center;"><b>&nbsp;Enabled&nbsp;</b></td><td style="text-align:center;"><b>&nbsp;Disabled&nbsp;</b></td><td><b>&nbsp;Notes</b></td></tr></thead><tbody>';
				
			var strCapabilitiesTable = '<table style="width:80%; margin:auto;" border="1"><thead style="background-color:yellow"><tr><td style="text-align:left;"><b>&nbsp;Feature</b></td><td style="text-align:center;"><b>&nbsp;Enabled&nbsp;</b></td><td style="text-align:center;"><b>&nbsp;Disabled&nbsp;</b></td><td style="text-align:center;"><b>&nbsp;Notes</b></td></tr></thead><tbody>';				
				
			var strCapabilities = '';
			var arrCapabilities = m_objThis.getBrowser().incapabilities.split(' ');
			var intCapability = 2; // start at 2 because the word capabilities: takes up space
			processArray(arrCapabilities, function (strCapability_a)
			{
				var strCapability = strCapability_a;
				var blnFeature = strCapability.indexOf('<del>') < 0;
				var strAdditionalNotes = '';

				strCapability = strCapability.replace("</del>", "");
				strCapability = strCapability.replace("<del>", "");
				strCapability = $.trim(strCapability);

				if (strCapability.length > 0)
				{
					var objCapability = m_objThis.getBrowserCapabilityInfo(strCapability);

					if (objCapability !== undefined)
					{
						if (objCapability.code === 'maps')
						{
							if (strMapsProvider === 'none')
							{
								strAdditionalNotes = '<b><font color="red"><br><br>Mapping is unavailable. A map provider is not configured.</font></b><hr>';
								blnFeature = false;
							}
						}
						else if (objCapability.code === 'speech')
						{
							if (strSpeechProvider === 'none')
							{
								strAdditionalNotes = '<b><font color="red"><br><br>Speech recognition is unavailable. A map speech recognition is not configured.</font></b><hr>';
								blnFeature = false;
							}
						}

						if (objCapability.code === 'java')
						{
							if (m_blnJavaWorking)
							{
								blnFeature = true;
							}
							else
							{
								strAdditionalNotes = '<b><font color="red"><br><br>For certain features to be available, please ensure that Java is correctly installed on this device <a href="' + URL_VERIFY_JAVA + '" target="_blank">verify your Java version</a>.</font></b><hr>';
								blnFeature = false;
							}
						}
						
						if (objCapability.code === 'printer')
						{
							if (strPrinterProvider === 'none')
							{
								// strAdditionalNotes = '<b><font color="red"><br><br>Printing is unavailable. A printer provider is not configured.</font></b><hr>';

								strAdditionalNotes = '<b><font color="red"><br><br>Printing is unavailable. Please be sure that the Java Printing Utility is installed. Refer to your software vendor for information.</font></b><hr>';
								blnFeature = false;
							}
						}

						// setup the table row
						strCapabilitiesTable = strCapabilitiesTable + '<tr>';

						strCapabilitiesTable = strCapabilitiesTable + '<td style="vertical-align:top; text-align:left; width:200px;">&nbsp;' + objCapability.description + '</td>';
						if (blnFeature)
						{
							strCapabilitiesTable = strCapabilitiesTable + '<td style="vertical-align:top; text-align:center;"><i class="fa fa-check" aria-hidden="true"></i></td><td>&nbsp;</td>';
						}
						else
						{
							strCapabilitiesTable = strCapabilitiesTable + '<td>&nbsp;</td><td style="vertical-align:top; text-align:center;"><b>X</b></td>';
						}
						strCapabilitiesTable = strCapabilitiesTable + '<td style="text-align:left;">&nbsp;' + objCapability.notes + ' ' + strAdditionalNotes + '</td>';

						strCapabilitiesTable = strCapabilitiesTable + '</tr>';
					}
				}
			}
			);

			strCapabilitiesTable = strCapabilitiesTable + '</tbody></table>';

			return strCapabilitiesTable;
		};

		this.getOrientation = function ()
		{
			var intResult = 0;

			try
			{
				if (window.orientation === undefined)
				{
					intResult = 0;
				}
				else
				{
					intResult = parseInt(window.orientation, 10);
				}
			}
			catch (err)
			{
				doNothing();
			}

			return intResult;
		};

		this.getOS = function ()
		{
			return m_objBrowser.OS;
		};

		this.getViewPort = function ()
		{
			return testViewPort();
		};

		this.hasCapability = function (strCapability_a)
		{
			var strCapabilities = ',' + m_objBrowser.capabilities + ',';
			var blnResult = false;
			
			if (strCapability_a === 'geolocation')
			{
				if (navigator.geolocation)
				{
					blnResult = true;
				}
			}
			else
			{
				blnResult = (strCapabilities.indexOf(',' + strCapability_a + ',') >= 0);
			}
			return blnResult;
		};

		// initialises the viewport based on the screen dimensions this is to prevent the annoying automatic zooming of some devices where the inner window size automatically changes
		this.initialiseViewPort = function ()
		{
			//if (m_objThis.hasCapability('viewport'))
			//{
			//var intOrientation = Math.abs(m_objThis.getOrientation() / 90);
			//var objViewPort = m_objThis.getViewPort();

			//var fltScaleX = screen.width / DEVICE_WIDTH;
			//var fltScaleY = objViewPort.height / 650;
			//var fltMinScale = Math.min(fltScaleX, fltScaleY);
			//var fltMinScale = fltScaleX;
			var fltMinScale = '';
			if (DEVICE_WIDTH === 'device-width')
			{
				fltMinScale = '1.0';
			}

			var arrMeta = document.getElementsByTagName('meta');
			for (var intI = 0; intI < arrMeta.length; intI++)
			{
				if (arrMeta[intI].name == "viewport")
				{
					arrMeta[intI].content = "width=" + DEVICE_WIDTH + ", initial-scale=" + fltMinScale + ", minimum-scale=" + fltMinScale + ", maximum-scale=3.0";
					m_objThis.after(500, function ()
					{
						m_objThis.broadcast('system', 'viewport', 'change');
						m_intViewPortChangeCount++; 
						logDebug('ViewPortChangeCount3:' + m_intViewPortChangeCount);
					}
					);
				}
			}
			//}
		};

		this.getCanvasHeight = function()
		{
			return m_objThis.getViewPort().height - m_intCanvasTop - m_intFooterHeight + m_DOCKABLEFORMQUIRK;
		};
		
		this.getCanvasLeft = function()
		{
			var intLeft = 0;
			if (m_blnMenuOpened)
			{
				intLeft = m_MENUFORMWIDTH;
			}
			
			return intLeft;
		};
		
		this.getCanvasTop = function()
		{
			return m_intCanvasTop;
		};
		
		this.getCanvasWidth = function()
		{
			var intLeft = m_objThis.getCanvasLeft();
			return m_objThis.getViewPort().width - intLeft - m_SCROLLBARQUIRK;
		};
		
		this.getFormHeight = function(strFormID_a)
		{
			return parseInt(m_objThis.element(strFormID_a, '.gb-form').height(), 10);
		};
		
		this.getFormCanvasHeight = function(strFormID_a)
		{
			var blnFormTitlePanel = (m_objThis.element(strFormID_a, '.gb-form').find('.gb-formtitle-inner-panel').length > 0);
			var intFormCavnasHeight = m_objThis.getFormHeight(strFormID_a);
			
			if (blnFormTitlePanel)
			{
				var intHeadingPanelHeight = parseInt(m_objThis.element(strFormID_a, '.gb-form').find('.gb-formtitle-inner-panel').height(), 10);
				intFormCavnasHeight -= intHeadingPanelHeight;
			}
			
			return intFormCavnasHeight;
		};
		
		this.isMDI = function()
		{
			var blnMDI = m_objThis.toBoolean(m_objThis.getProperty('ismdi'));
			var blnIsMobile = m_objThis.hasCapability('mobile');
			return (blnMDI && !blnIsMobile);
		};
		
		this.resetViewPort = function ()
		{
			if (m_objThis.hasCapability('viewport'))
			{
				var arrMeta = document.getElementsByTagName('meta');
				for (var intI = 0; intI < arrMeta.length; intI++)
				{
					if (arrMeta[intI].name == "viewport")
					{
						arrMeta[intI].content = VIEWPORTINITIALSTATE;
					}
				}
			}
		};
		
		// ====================================================================================
		// BINDING ============================================================================

		// jQuery event redirector
		function bindEvent2(objForm_a, strFormID_a, strLocator_a, strElementName_a, strEventName_a, objData_a)
		{
			var strEventName = m_objThis.str_right(strEventName_a, strEventName_a.length - 'on'.length);
			var objElement = m_objThis.element(strFormID_a, strLocator_a);
			objElement.bind(strEventName.toLowerCase(), function (objEvent_a, objEventData_a)
			{
				raiseEvent(objForm_a, this, objElement, strElementName_a, strEventName_a, objEvent_a, objEventData_a, objData_a);
			}
			);
		}

		function customEventOnKeyEnter(objForm_a, strFormID_a, strLocator_a, strElementName_a, objData_a)
		{
			var objElement = m_objThis.element(strFormID_a, strLocator_a);
			objElement.bind('keypress', function (objEvent_a, objEventData_a)
			{
				// act on enter (char 13)
				if (objEvent_a.which === 13)
				{
					raiseEvent(objForm_a, this, objElement, strElementName_a, 'onEnterKey', objEvent_a, objEventData_a, objData_a);
				}
			}
			);
		}

		function onDirtyPreProcess(objEvent_a, cbDirty_a)
		{
			// ignore tab (char 9)
			if (objEvent_a.which !== 9)
			{
				cbDirty_a();
			}
		}

		// objThis_a is the actual instance of an element that raised the event, it can be accessed as $(objThis_a) via jQuery
		// objElement_a is the lot of bound elements that one of raised the event, the lot can be accessed as objElement_a via jQuery
		function raiseEvent(objForm_a, objThis_a, objElement_a, strElementName_a, strEventName_a, objEvent_a, objEventData_a, objData_a)
		{
			var varResult;
			
			try
			{
				var strFunction = strElementName_a + '_' + strEventName_a;
				varResult = objForm_a[strFunction](objThis_a, objElement_a, objEvent_a, objEventData_a, objData_a);
			}
			catch (err)
			{
				try
				{
					varResult = objForm_a[strEventName_a](strElementName_a, objThis_a, objElement_a, objEvent_a, objEventData_a, objData_a);
				}
				catch (err)
				{
					doException('raiseEvent', err);
				}
			}
			
			return varResult;
		}

		function unbindEvent(strFormID_a, strLocator_a)
		{
			try
			{
				m_objThis.element(strFormID_a).find(strLocator_a).unbind();
			}
			catch (err)
			{
				doException('raiseEvent', err);
			}
		}
        
/*         // dependencies boostrap datetimepicker
        // bundle/bootstrap-datetimepicker.css
        // bundle/moment-with-locales.js
        // bundle/bootstrap-datetimepicker.js
		this.bindDatePicker = function (strFormOrLocator_a, strLocator_a, objConfig_a)
		{               
            // refer here for more options
            // http://eonasdan.github.io/bootstrap-datetimepicker/Options
            var objDateOption = { 
                format : "MMMM YYYY",
                widgetPositioning : { horizontal : "auto", vertical : "bottom" }
            };
            
            if(objConfig_a.dateFormat !== undefined) { 
               objDateOption.format = format(objConfig_a.dateFormat);
            }
                     
            // refer here for all valid formats:
            // http://momentjs.com/docs/#/displaying/format/
            function format(strDateFormat_a) 
			{ 
                //default
                var strDateFormat = 'MMMM YYYY';
                
                switch(strDateFormat_a) 
				{ 
                    case 'dd/mm/yy':
					{
						strDateFormat = 'DD/MM/YYYY';
						break;
					}
                    case 'dd-mm-yy':
					{
						strDateFormat = 'DD-MM-YYYY';
						break;
					}
                    case 'mm/dd/yy':
					{
                        strDateFormat = 'MM/DD/YYYY'; 
						break;
					}
                    case 'mm-dd-yy':
					{
						strDateFormat = 'MM-DD-YYYY'; 
						break;
					}
                    case 'yy-mm-dd':
					{
						strDateFormat = 'YYYY-MM-DD'; 
						break;
					}
					default:
					{
						break;
					}
                }
                               
                return strDateFormat;
            }
            
			// the removeClass is needed due to the fact datepicker refuses to re-bind to a control even after it's unbound
			m_objThis.element(strFormOrLocator_a, strLocator_a).removeClass('hasDatepicker');
			//m_objThis.element(strFormOrLocator_a, strLocator_a).datepicker(objConfig_a);
            var objDatePicker = m_objThis.element(strFormOrLocator_a, strLocator_a).datetimepicker(objDateOption);
            
            if($.isFunction(objConfig_a.onSelect)) {
                objDatePicker.on("dp.change", objConfig_a.onSelect);
            }    
		}; */
		this.bindDatePicker = function (strFormOrLocator_a, strLocator_a, objConfig_a)
		{
			// the removeClass is needed due to the fact datepicker refuses to re-bind to a control even after it's unbound
			m_objThis.element(strFormOrLocator_a, strLocator_a).removeClass('hasDatepicker');
			m_objThis.element(strFormOrLocator_a, strLocator_a).datepicker(objConfig_a);

			var lngZOrder = parseInt(m_objThis.element(strFormOrLocator_a, strLocator_a).closest('.gb-form').css('z-index'), 10);
			m_objThis.element(strFormOrLocator_a, strLocator_a).css('z-index', lngZOrder);

			m_objThis.element(strFormOrLocator_a, strLocator_a).bind('click', function (objEvent_a, objEventData_a)
			{
				var lngTop = parseInt($(this).css('top'), 10);
				lngZOrder = parseInt($(this).closest('.gb-form').css('z-index'), 10);

				m_objThis.$(objEvent_a.srcElement).css('z-index', lngZOrder);
				m_objThis.$(objEvent_a.srcElement).css('top', lngTop);
				m_objThis.$(objEvent_a.srcElement).datepicker("show");
			});
		};
        
        // dependencies jquery timeentry
        // bundle/timeentry/jquery.plugin.min.js
        // bundle/timeentry/jquery.timeentry.min
        // bundle/timeentry/jquery.timeentry.css
        this.bindTimePicker = function (strFormOrLocator_a, strLocator_a, objConfig_a)
		{       
            var cbOnchange;
            
            if(!isTimeFieldSupported()) { // load if html5 time field is not supported by the browser
                
                if($.isFunction(objConfig_a.onSelect)) { 
                   cbOnchange = objConfig_a.onSelect; 
                }
                else 
                { 
                   cbOnchange = doNothing;
                }
                // I just added a wrapper because the I make the controller icon position absolute. (.timeentry_control)
                m_objThis.element(strFormOrLocator_a, strLocator_a).wrap('<div style="position:relative"></div>');
                
                m_objThis.element(strFormOrLocator_a, strLocator_a).timeEntry( 
				{ 
                   spinnerIncDecOnly : true,
                   onChange : cbOnchange
                });             
                                
            }
            else { 
                doNothing(); // let html5 time field handle it
            }
            
            function isTimeFieldSupported() {

                var objInput = document.createElement('input');

                objInput.setAttribute('type','date');

                var strNotATimeValue = 'not-a-time';

                objInput.setAttribute('value', strNotATimeValue);

                return (objInput.value !== strNotATimeValue);
            }
          
                
		};

		// handles custom events
		this.bindEvent = function (objForm_a, strFormID_a, strLocator_a, strElementName_a, strEventName_a, objData_a)
		{
			var strCustomEvent = strEventName_a.toLowerCase();
			if (strCustomEvent === 'onenterkey')
			{
				customEventOnKeyEnter(objForm_a, strFormID_a, strLocator_a, strElementName_a, objData_a);
			}
			else
			{
				bindEvent2(objForm_a, strFormID_a, strLocator_a, strElementName_a, strEventName_a, objData_a);
			}
		};

		this.onDirty = function (strFormID_a, strFields_a, cbDirty_a)
		{
			if ($.isFunction(cbDirty_a))
			{
				var arrFields = strFields_a.split(',');
				processArray(arrFields, function (strField_a)
				{
					try
					{
						m_objThis.element(strFormID_a, '.' + strField_a).change(function (objEvent_a)
						{
                            if(this.readOnly !== true)
                            {
                                onDirtyPreProcess(objEvent_a, cbDirty_a);
                            }
						}
						);
						m_objThis.element(strFormID_a, '.' + strField_a).keydown(function (objEvent_a)
						{
                            if(this.readOnly !== true)
                            {
                                onDirtyPreProcess(objEvent_a, cbDirty_a);
                            }
						}
						);
						m_objThis.element(strFormID_a, '.' + strField_a).keyup(function (objEvent_a)
						{
                            if(this.readOnly !== true)
                            {
                                onDirtyPreProcess(objEvent_a, cbDirty_a);
                            }
						}
						);
					}
					catch (err)
					{
						logDebug('setting onDirty on field ' + strFormID_a + '.' + strField_a + ' failed.');
					}
				}
				);
			}
		};

		this.unbindEvents = function (strFormID_a, strFields_a)
		{
			if (strFields_a.length > 0)
			{
				var arrFields = strFields_a.split(',');
				processArray(arrFields, function (strField_a)
				{
					unbindEvent(strFormID_a, '.' + strField_a);
				}
				);
			}
		};

		// ====================================================================================
		// BLOCKING ===========================================================================

		// block a div
		function block(strDiv_a, blnVisible_a)
		{
			if ($.isFunction($(strDiv_a).block))
			{
				if (blnVisible_a)
				{
					$(strDiv_a).block(
					{
						message : null,
						overlayCSS :
						{
							opacity : 0.1
						}
					}
					);
				}
				else
				{
					$(strDiv_a).block(
					{
						message : null,
						overlayCSS :
						{
							opacity : 0
						}
					}
					);
				}
			}
		}

		// block all forms
		function blockAll(blnVisible_a)
		{
			block('#' + m_objArgs.container, blnVisible_a);
		}

		// unblock a div
		function unblock(strDiv_a)
		{
			if ($.isFunction($(strDiv_a).unblock))
			{
				$(strDiv_a).unblock();
			}
		}

		// unblock all forms
		function unblockAll()
		{
			unblock('#' + m_objArgs.container);
		}

		// ====================================================================================
		// MAPS ===============================================================================

		this.getMapProvider = function ()
		{
			var strMapProvider = $('#ge-mapprovider-container').html();
			return strMapProvider;
		};

		// ====================================================================================
		// PRINTING ===========================================================================

		this.getDefaultPrinter = function ()
		{
			var strResult = '';

			if (m_objThis.isPrinterRegistered())
			{
				strResult = m_objPrinter.getDefaultPrinter();
			}

			return strResult;
		};
		
		this.getPrinterProvider = function()
		{
			return m_strPrinterProvider;
		};

		this.getPrinters = function ()
		{
			var arrResult = [];

			if (m_objThis.isPrinterRegistered())
			{
				arrResult = m_objPrinter.getPrinters();
			}

			return arrResult;
		};

		this.getPrinterVersion = function ()
		{
			var strResult = '';

			if (m_objThis.isPrinterRegistered())
			{
				strResult = m_objPrinter.getVersion();
			}

			return strResult;
		};

		this.isPrinterRegistered = function ()
		{
			var blnResult = false;

			if (m_objPrinter !== null)
			{
				blnResult = true;
			}

			return blnResult;
		};

		this.registerPrinter = function (strPrinterProvider_a, objPrinterClass_a)
		{
			m_strPrinterProvider = strPrinterProvider_a;
			m_objPrinter = objPrinterClass_a;
			m_blnJavaWorking = true;
		};

		this.setPrinter = function (strPrinterName_a)
		{
			if (m_objThis.isPrinterRegistered())
			{
				m_objPrinter.setPrinter(strPrinterName_a);
			}
		};

		// ====================================================================================
		// SPEECH =============================================================================

		function showSpeech(strSpeech_a, strSpeechFull_a)
		{
			try
			{
				logDebug(strSpeechFull_a);
				m_objMicrophoneForm.Form_onMicrophone(strSpeech_a);
			}
			catch (err)
			{
				doNothing();
			}
		}

		this.copyToClipboard = function (strText_a)
		{
			//window.prompt('Copy to clipboard: Ctrl+C, Enter', strText_a);
			navigator.clipboard.writeText(strText_a).then(function()
			{
				document.execCommand("paste");
				m_objThis.dialogAlert('Copied to clipboard: "' + strText_a + '"', doNothing);
			}, doNothing);
		};

		this.getSpeechProvider = function ()
		{
			return m_strSpeechProvider;
		};

		this.isSpeechRegistered = function ()
		{
			return (m_strSpeechProvider.length > 0);
		};

		this.processSpeech = function (strSpeech_a, blnDictating_a)
		{
			// return original speech if no action found yet
			var blnResult = false;
			var strAction = '';

			var strSpeech = '...' + m_objThis.str_right(strSpeech_a, 50);
			showSpeech(strSpeech, strSpeech_a);

			strSpeech = ' ' + strSpeech_a + ' ';
			processArray(g_objVocab, function (objPhrase_a)
			{
				if (strSpeech.indexOf(' ' + objPhrase_a.phrase + ' ') >= 0)
				{
					strAction = objPhrase_a.action;
					if (blnDictating_a)
					{
						if (strAction === 'speech dictate off')
						{
							strSpeech = '';
						}
						else
						{
							strAction = '';
						}
					}
					else
					{
						strSpeech = '';
					}
				}
			}
			);

			if (strAction.length > 0)
			{
				m_objThis.broadcast('system', 'speech', strAction);
				blnResult = true;
			}

			// os-based actions
			if (strAction === 'speech window close')
			{
				m_objThis.closeFormFocus();
			}

			return blnResult;
		};

		this.registerMicrophoneStatus = function (objForm_a, strFormID_a)
		{
			m_objMicrophoneForm = objForm_a;
			m_strMicrophoneForm = strFormID_a;
			if (m_strTaskbarFormID.length > 0)
			{
				var objIndicator = m_objThis.element(m_strMicrophoneForm, '.gb-indicator-connection');
			}
		};

		this.registerSpeech = function (objSpeechClass_a, strSpeechProvider_a)
		{
			m_objSpeech = objSpeechClass_a;
			m_strSpeechProvider = strSpeechProvider_a;
		};

		this.speechOn = function ()
		{
			if (m_objThis.isSpeechRegistered())
			{
				try
				{
					m_objSpeech.Form_onBroadcast('speech', 'speech system on');
				}
				catch (err)
				{
					doNothing();
				}
			}
		};

		this.speechOff = function ()
		{
			if (m_objThis.isSpeechRegistered())
			{
				try
				{
					m_objSpeech.Form_onBroadcast('speech', 'speech system off');
				}
				catch (err)
				{
					doNothing();
				}
			}
        };
        
        this.speak = function(strQueue_a, str_a)
        {
            m_objThis.broadcast(strQueue_a, 'speak',  str_a);
        };

		// ====================================================================================
		// FORMS ==============================================================================

		function allowFormXPlacement()
		{
			return (m_objThis.getViewPort().width >= m_FORMPLACEMENTXTOLERANCE);
		}
		
		function allowFormYPlacement()
		{
			return (m_objThis.getViewPort().height >= m_FORMPLACEMENTYTOLERANCE);
		}
		
		function broadcastToForm(strFormID_a, strQueue_a, strMessage_a, objMessageData_a)
		{
			logDebug('========== START BROADCAST TO FORM');
			processForms(function (objForm_a)
			{
				// broadcast to every form except the calling form (in the case of 'system' then all forms are broadcast to
				if ('#' + objForm_a.id == strFormID_a)
				{
					try
					{
						logDebug('broadcast ' + strQueue_a + '.' + strMessage_a + ' (' + JSON.stringify(objMessageData_a) + ') to: ' + objForm_a.id);
						objForm_a.formobject.Form_onBroadcast(strQueue_a, strMessage_a, objMessageData_a);
					}
					catch (err)
					{
						doException('broadcastToForm', err);
					}
					return true;
				}
			}
			);
			logDebug('========== END BROADCAST TO FORM');
		}

		// is the form formified?
		this.isFormified = function(strFormID_a)
		{
			var blnResult = false;
			
			processForms(function (objForm_a)
			{
				if ('#' + objForm_a.id == strFormID_a)
				{
					blnResult = objForm_a.formified;
					return true;
				}
			}
			);
			
			return blnResult;
		};

		this.hideForm = function(strFormID_a)
		{
			processForms(function (objForm_a)
			{
				if ('#' + objForm_a.id === strFormID_a)
				{
					objForm_a.tag = objForm_a.visible;
					objForm_a.visible = false;
					m_objThis.element('#' + objForm_a.id).hide();
					return true;
				}
			}
			);
		};

		this.reshowForm = function(strFormID_a)
		{
			processForms(function (objForm_a)
			{
				if ('#' + objForm_a.id === strFormID_a)
				{
					objForm_a.tag = objForm_a.visible;
					objForm_a.visible = true;
					m_objThis.element('#' + objForm_a.id).show();
					return true;
				}
			}
			);
		};

		function canFormOpen(strFormName_a, blnAllowMultiple_a)
		{
			var blnResult = false;

			if (blnAllowMultiple_a === true)
			{
				blnResult = true;
			}
			else
			{
				var blnExists = false;
				processForms(function (objForm_a)
				{
					if (objForm_a.name == strFormName_a)
					{
						blnExists = true;
						return true;
					}
				}
				);

				blnResult = (blnExists === false);
			}

			return blnResult;
		}

		function closeForm2(strFormID_a, blnDataSaved_a)
		{
			//if (m_strPopupFormID == strFormID_a)
			//{
				// unregister form
				unregisterFormInstance(strFormID_a);
				
				// remove it from dom
				m_objThis.element(strFormID_a).replaceWith('');

				// for modals we need to get the result
				var objResult = null;
				var objPopupForm = m_objPopupForm.pop();
				if ($.isFunction(objPopupForm.FormResult))
				{
					objResult = objPopupForm.FormResult();
				}
				// if ($.isFunction(m_objPopupForm.FormResult))
				// {
				// 	objResult = m_objPopupForm.FormResult();
				// }

				// set focus to the previous focussed form
				var objOldFocusForm = m_objOldFocusForm.pop();
				var strOldFocusFormID = m_strOldFocusFormID.pop();
				m_objThis.setFormFocus(objOldFocusForm, strOldFocusFormID, true);
				// m_objThis.setFormFocus(m_objOldFocusForm, m_strOldFocusFormID, true);

				// for modals, we need to call the callback

				// if ($.isFunction(m_cbReturn))
				// {
				// 	m_cbReturn(objResult, blnDataSaved_a);
				// }
				
				var cbReturn = m_cbReturn.pop();
				if ($.isFunction(cbReturn))
				{
					cbReturn(objResult, blnDataSaved_a);
				}

				// m_objOldFocusForm = null;
				// m_strOldFocusFormID = '';

				// for modals, extra cleanup
				// m_objPopupForm = null;
				m_strPopupFormID = '';
				// m_cbReturn = null;
				$('#ge-modal-layer').addClass('gb-hidden');
			//}
			//else
			//{
				// unregister form
				//unregisterFormInstance(strFormID_a);
				
				// remove it from dom
				//m_objThis.hideForm(strFormID_a);
				//m_objThis.element(strFormID_a).replaceWith('');

				//var objResult = null;
				//if ($.isFunction(m_objPopupForm.FormResult))
				//{
					//objResult = m_objPopupForm.FormResult();
				//}

				// set focus to the previous focussed form
				//m_objThis.setFormFocus(m_objOldFocusForm, m_strOldFocusFormID, true);

				// for modals, we need to call the callback
				//if ($.isFunction(m_cbReturn))
				//{
					//m_cbReturn(objResult, blnDataSaved_a);
				//}

				//m_objOldFocusForm = null;
				//m_strOldFocusFormID = '';

				// for modals, extra cleanup (really this shouldn't happen but if we are here close the popup anyway)
				//m_objPopupForm = null;
				//m_strPopupFormID = '';
				//m_cbReturn = null;
				//$('#ge-modal-layer').addClass('gb-hidden');
			//}
		}

		this.closeFormFocus = function()
		{
			var blnCanClose = true;

			var objForm = null;

			processForms(function (objForm_a)
			{
				if ('#' + objForm_a.id == m_strFocusFormID)
				{
					objForm = objForm_a.formobject;
					return true;
				}
			}
			);

			try
			{
				if (objForm !== null)
				{
					blnCanClose = objForm.Form_canClose();
				}
			}
			catch (err)
			{
				blnCanClose = true;
			}

			if (blnCanClose)
			{
				m_objThis.closeForm(m_strFocusFormID);
			}
		};

		function getFirstForm(strFormName_a)
		{
			var strResult = '';

			processForms(function (objForm_a)
			{
				if (objForm_a.name == strFormName_a)
				{
					strResult = objForm_a.id;
					return true;
				}
			}
			);

			return strResult;
		}

		// used by help only
		function hideFormsExcept(strFormID_a)
		{
			var strFormID = str_replace(strFormID_a, '#', '');

			processForms(function (objForm_a)
			{
				if (objForm_a.id === strFormID)
				{
					objForm_a.tag = objForm_a.visible;
				}
				else
				{
					objForm_a.tag = objForm_a.visible;
					objForm_a.visible = false;
					m_objThis.element('#' + objForm_a.id).hide();
				}
			}
			);
		}

		// used by help and other things
		function hideFormI(strFormID_a)
		{
			var strFormID = str_replace(strFormID_a, '#', '');

			processForms(function (objForm_a)
			{
				if (objForm_a.id == strFormID)
				{
					//alert('show:' + JSON.stringify(objForm_a));
					objForm_a.visible = false;
					m_objThis.element('#' + strFormID_a).hide();
					return true;
				}
			}
			);
			
			debugZOrders();
		}

		// used by help and other things
		function showFormI(strFormID_a)
		{
			var strFormID = str_replace(strFormID_a, '#', '');

			processForms(function (objForm_a)
			{
				if (objForm_a.id == strFormID)
				{
					//alert('show:' + JSON.stringify(objForm_a));
					objForm_a.visible = true;
					m_objThis.element('#' + strFormID_a).show();
					return true;
				}
			}
			);
			
			debugZOrders();
		}

		// used by help only
		function restoreForms()
		{
			processForms(function (objForm_a)
			{
				objForm_a.visible = objForm_a.tag;
				if (objForm_a.visible)
				{
					m_objThis.element('#' + objForm_a.id).show();
				}
			}
			);
		}

		function logForms()
		{
			logDebug('========== START FORMS');
			processAllForms(function (objForm_a)
			{
				if ('#' + objForm_a.id == m_strFocusFormID)
				{
					logDebug('id: ' + objForm_a.id + ', name: ' + objForm_a.name + ' (focus)' + ', top: ' + objForm_a.top + ', left: ' + objForm_a.left);
				}
				else
				{
					logDebug('id: ' + objForm_a.id + ', name: ' + objForm_a.name + ', top: ' + objForm_a.top + ', left: ' + objForm_a.left);
				}
			}
			);
			logDebug('========== END FORMS');
		}

		function onFormResize(strFormID_a)
		{
			var blnResized = false;
			var objForm = null;
			var intHeight = 0;
			//var intLeft = 0;
			//var intTop = 0;
			var intWidth = 0;
			
			processForms(function (objForm_a)
			{
				if ('#' + objForm_a.id == '#' + strFormID_a)
				{
					objForm = objForm_a.formobject;
				}
			}
			);

			try
			{
				if (objForm !== null)
				{
					//intTop = m_objThis.element('#' + strFormID_a, '.gb-form').css('top').replace('px', '');
					//intLeft = m_objThis.element('#' + strFormID_a, '.gb-form').css('left').replace('px', '');
					intHeight = m_objThis.element('#' + strFormID_a, '.gb-form').height();
					if (objForm.fullwidth)
					{
						var intLeft = 0;
						if (m_blnMenuOpened)
						{
							intLeft = m_MENUFORMWIDTH;
						}

						intWidth = m_objThis.getViewPort().width - intLeft - m_SCROLLBARQUIRK;
					}
					else
					{
						intWidth = m_objThis.element('#' + strFormID_a, '.gb-form').width();
					}
					blnResized = true;	// this should be before Form_onResize because that can fail...

                    if($.isFunction(objForm.Form_onResize))
                    {
                        objForm.Form_onResize(intWidth, intHeight);
                    }
				}
			}
			catch (err)
			{
				doException(err);
			}

			if (blnResized)
			{
				m_objThis.element('#' + strFormID_a, '.gb-formclose-panel').css('left', (intWidth - m_FORMCLOSEOFFSET) + 'px');
				m_objThis.element('#' + strFormID_a, '.gb-formtitle-panel').css('width', (intWidth - m_FORMTITLEOFFSET) + 'px');
				m_objThis.element('#' + strFormID_a, '.gb-formtitle-inner-panel').css('width', (intWidth - m_FORMTITLEOFFSET) + 'px');
				//m_objThis.element('#' + strFormID_a, '.gb-panel-resize').css('height', (intHeight - 108) + 'px');
			}
		}
		
		function onFormResizeAfterDelay(strFormID_a, intDelay_a)
		{
			var blnFound = false;
			var intDelay = intDelay_a;
			if (intDelay === undefined) { intDelay = 500; }
			
			// is the form already in the queue being processed?
			processArray(m_objResizeQueue, function (objForm_a)
			{
				if ((objForm_a.id == strFormID_a) && (objForm_a.active))
				{
					blnFound = true;
					return;
				}
			});
			
			if (!blnFound)
			{
				// is the form in the queue but not being processed? if so, then activate it
				blnFound = false;
				processArray(m_objResizeQueue, function (objForm_a)
				{
					if ((objForm_a.id == strFormID_a) && (!objForm_a.active))
					{
						blnFound = true;
						objForm_a.active = true;
						return;
					}
				});
				
				// if still not found, then add it to the queue ready to be processed
				if (!blnFound)
				{
					m_objResizeQueue.push({id:strFormID_a, active:true});
				}
				
				// a delay so that resizing doesn't cause too many things to occur every drag point while dragging
				// future: add the formid to resize to a collection until it is resized, disallow 2 resizes at the same time, only the initial one will occur after the delay
				m_objThis.after(intDelay, function()
				{
					if (m_objThis.resizeDockedForm('#' + strFormID_a))
					{
						onFormResize(strFormID_a);
					}

					if (resizeMenu())
					{
						onFormResize(m_strMenuFormID);
					}

					// broadcast once if there is anything in the queue
					processArray(m_objResizeQueue, function (objForm_a)
					{
						if (objForm_a.active)
						{
							m_objThis.broadcast('system', 'viewport', 'change');
							m_intViewPortChangeCount++; 
							logDebug('ViewPortChangeCount:' + m_intViewPortChangeCount);
							return true;
						}
					});
					
					// remove processed forms
					processArray(m_objResizeQueue, function (objForm_a)
					{
						if (objForm_a.active)
						{
							objForm_a.active = false;
						}
					});
				});
			}
		}

		function onTabBroadcast(strQueue_a, strMessage_a, objMessageData_a)
		{
			logDebug('tab broadcast received: ' + strQueue_a + '.' + strMessage_a + ' (' + JSON.stringify(objMessageData_a) + ')');
			if ((strQueue_a === 'system') && (strMessage_a === 'ping'))
			{
				doNothing();
			}
			else
			{
				m_objThis.broadcast('system', strQueue_a, strMessage_a, objMessageData_a, true);
			}
		}

		function processAllForms(cb_a)
		{
			processArray(m_objRegisteredForms, function (objForm_a)
			{
				if ($.isFunction(cb_a))
				{
					cb_a(objForm_a);
				}
			}
			);
		}

		function processForms(cb_a)
		{
			processArray(m_objRegisteredForms, function (objForm_a)
			{
				if (objForm_a.id !== 'unregistered')
				{
					if ($.isFunction(cb_a))
					{
						var blnAbort = cb_a(objForm_a);
						if (blnAbort) { return true; }
					}
				}
			}
			);
		}

		function registerFormInstance(strFormID_a, strFormName_a, objForm_a, blnLanding_a, blnTaskbar_a, blnFooter_a, blnResizable_a, blnDockable_a, blnFormify_a)
		{
			var strFormID = str_replace(strFormID_a, '#', '');

			// top_store and left_store are used when we move the form temporarily (such as when the help is displayed)
			var objForm =
			{
				canvasbottom : m_intCanvasBottom,
				canvastop : m_intCanvasTop,
				dockable: blnDockable_a,
				footer: blnFooter_a,
				formified: blnFormify_a,
				formobject : objForm_a,
				id : strFormID,
				landing : blnLanding_a,
				left : 0,
				left_store : 0,
				menu : false,
				name : strFormName_a,
				resizable : blnResizable_a, 
				tag : null,
				taskbar : blnTaskbar_a,
				top : 0,
				top_store : 0,
				visible : true
			};

			var objVue = null;
			try
			{
				objVue = raiseEvent(objForm_a, objForm_a, objForm_a, 'Form', 'getVue');
				if (objVue !== null && objVue !== undefined)
				{
                    objVue.el = '#' + strFormID;
                    objForm_a.vue = new Vue(objVue);
				}
			}
			catch (err)
			{
				doException('registerFormInstance', err);
			}
			
			m_objRegisteredForms.push(objForm);
				
			logForms();
		}

		function removeTabs(arrElements_a)
		{
			// remove all tabbing from the form
			processArray(arrElements_a, function (objElement_a)
			{
				try
				{
					if ($(objElement_a).hasClass('gb-preservetab'))
					{
						// note: bug 1, setting the tab order to 0 for the last element on the form was a workaround to allow tabbing within the grids to not get lost when changing the z-order of forms
						$(objElement_a).attr('tabindex', '99');
						//doNothing();
					}
					else
					{
						$(objElement_a).removeAttr('tabindex');
					}
				}
				catch (err)
				{
					doException('removeTabs', err);
				}
			}
			);
		}

		function unregisterFormInstance(strFormID_a)
		{
			var strFormID = str_replace(strFormID_a, '#', '');

			processForms(function (objForm_a)
			{
				if (objForm_a.id == strFormID)
				{
					// note: 
					// below are commented out because we still want to know what some of the properties are when unregisetered
					// but some properties cause issues if we had multiple taskbars (for example)
					//
					//objForm_a.canvastop = '';
					//objForm_a.dockable = false;
					//objForm_a.formified = false;
					//objForm_a.left = 0;
					//objForm_a.left_store = 0;
					//objForm_a.resizable = false;
					//objForm_a.top = 0;
					//objForm_a.top_store = 0;
					objForm_a.footer = false;
					objForm_a.formobject = false;
					objForm_a.fullwidth = false;
					objForm_a.id = 'unregistered';
					objForm_a.landing = false;
					objForm_a.menu = false;
					objForm_a.name = 'unregistered';
					objForm_a.taskbar = false;
					objForm_a.visible = false;
					objForm_a.tag = null; // used for various things such as preservation of other properties
					return true;
				}
			}
			);

			logForms();
		}

		// bind already loaded landing form with it's already loaded JS
		this.bindLandingForm = function (strFormID_a, strFullFormName_a, varParameters_a)
		{
			var objForm = null;
			var varParameters = {};

			if (varParameters_a === undefined)
			{
				varParameters = {};
			}
			else if (typeof varParameters_a === 'string')
			{
				varParameters = m_objThis.urlToJSON(varParameters_a);
			}
			else
			{
				varParameters = varParameters_a;
			}

			var strFullFormName = str_replace(strFullFormName_a, '-', '.');
			var arrFormName = strFullFormName.split('.');
			var strModule = arrFormName[0];
			var strFormName = strModule + '_' + arrFormName[1];
			var strFormID = strFormID_a;

			try
			{
				m_objThis.lazyLoadDependencies(g_arrDependencies, strFullFormName, function (blnSuccess_a)
				{
					objForm = new window[strFormName](m_objThis, '#' + strFormID, varParameters); //instead of var strJavaScript = 'objForm = new ' + strFormName + '(m_objThis, "#" + strFormID, varParameters); '; eval(strJavaScript);

					var blnAllowMultiple = true;
					try
					{
						blnAllowMultiple = objForm.Form_allowMultipleInstances();
					}
					catch (err)
					{
						blnAllowMultiple = true;
					}

					var blnOpenForm = canFormOpen(strFormName, blnAllowMultiple);
					if (blnOpenForm === true)
					{
						registerFormInstance(strFormID, strFormName, objForm, true, false, false, false, false, false);
						raiseEvent(objForm, objForm, objForm, 'Form', 'onLoad');
						//initialiseMDI(strFormID, false, false);		// caused the taskbar to vanish
					}
					else
					{
						strFormID = getFirstForm(strFormName);
					}

					showFormI(strFormID);
					m_objThis.setFormFocus(objForm, '#' + strFormID, true, varParameters);
					analytics(strFormName);
				}
				);
			}
			catch (err)
			{
				doException('bindLandingForm', err);
			}
		};

		// usually this broadcasts to all internal forms then external tabs unless the os receives a
		// broadcast from an external tab then it will force an internal only broadcast from it to
		// avoid a ping pong effect
		this.broadcast = function (strFormID_a, strQueue_a, strMessage_a, objMessageData_a, blnInternal_a)
		{
			if (strQueue_a == undefined)
			{
				doNothing();
			}
			
			var objMessageData = objMessageData_a;
			if (objMessageData === undefined)
			{
				objMessageData = {};
			}

			var blnInternal = blnInternal_a;
			if (blnInternal === undefined)
			{
				blnInternal = false;
			}

			logDebug('========== START BROADCAST');
			processForms(function (objForm_a)
			{
				// broadcast to every form except the calling form (in the case of 'system' then all forms are broadcast to
				if ('#' + objForm_a.id != strFormID_a)
				{
					try
					{
						logDebug('broadcast ' + strQueue_a + '.' + strMessage_a + ' (' + JSON.stringify(objMessageData) + ') to: ' + objForm_a.id);
						objForm_a.formobject.Form_onBroadcast(strQueue_a, strMessage_a, objMessageData);
					}
					catch (err)
					{
						doException('broadcast', err);
					}
				}
			}
			);

			if ((blnInternal === false) && (m_objThis.hasCapability('multimon')))
			{
				logDebug('broadcast ' + strQueue_a + '.' + strMessage_a + ' (' + JSON.stringify(objMessageData) + ') to other tabs');
				m_objTabHandler.broadcast(strQueue_a, strMessage_a, objMessageData);
			}
			logDebug('========== END BROADCAST');
		};

		this.canOpenTab = function ()
		{
			var blnResult = false;

			if (m_objThis.hasCapability('multimon'))
			{
				blnResult = m_objTabHandler.canCreateTab();
			}

			return blnResult;
		};

		// doesn't seem to work (form is unregistered but won't remove from DOM)
		this.closeAllForms = function (cbAfter_a)
		{
			processForms(function (objForm_a)
			{
				closeForm2(objForm_a.id, false);
			}
			);

			if ($.isFunction(cbAfter_a))
			{
				cbAfter_a();
			}

			logForms();
		};

		// doesn't seem to work (form is unregistered but won't remove from DOM)
		this.closeDockableFormsExcept = function(strFormID_a, cbAfter_a)
		{
			processForms(function (objForm_a)
			{
				if ((objForm_a.dockable) && (objForm_a.id !== strFormID_a))
				{
					//m_objThis.after(500, function() {
						closeForm2(objForm_a.id, false);
					//});
				}
			}
			);

			if ($.isFunction(cbAfter_a))
			{
				cbAfter_a();
			}

			logForms();
		};
		
		// close a form
		this.closeForm = function (strFormID_a, blnDataSaved_a)
		{
			var objForm = null;
			var blnDataSaved = blnDataSaved_a;
			if (blnDataSaved === undefined) { blnDataSaved = false; }

			processForms(function (objForm_a)
			{
				if ('#' + objForm_a.id == strFormID_a)
				{
					objForm = objForm_a.formobject;
					return true;
				}
			}
			);

			if (objForm !== null)
			{
				// check if the form is allowed to be closed and prevent it if it isn't
				var blnCanClose = true;

				try
				{
					blnCanClose = objForm.Form_canClose();
				}
				catch (err)
				{
					doNothing();
				}

				var blnIsDirty = false;

				try
				{
					blnIsDirty = objForm.Form_isDirty();
				}
				catch (err)
				{
					doNothing();
				}

				if (blnCanClose)
				{
					if (blnIsDirty)
					{
						m_objThis.dialogConfirm('<b>Are you sure you want to close this form?</b><br><br>You have unsaved changes.<br>To stay on this page and save your changes, click <b>Cancel</b>.', function ()
						{
							closeForm2(strFormID_a, blnDataSaved);
						}
						);
					}
					else
					{
						closeForm2(strFormID_a, blnDataSaved);
					}
				}
			}
		};
		
		this.closeTab = function ()
		{
			var objPerspective = window;
			objPerspective.close();
		};

		this.dialogAlert = function (strMessage_a, cb_a, blnWide_a)
		{
			var blnWide = blnWide_a;
			var strSize = BootstrapDialog.SIZE_NORMAL;
			
			if (blnWide === undefined)
			{
				blnWide = false;
			}
			
			if (blnWide)
			{
				strSize = BootstrapDialog.SIZE_WIDE;
			}
			
			if (strMessage_a.length > 0)
			{
				if (cb_a === undefined)
				{
					alert(strMessage_a);
				}
				else
				{
					BootstrapDialog.show(
					{
						size: strSize,
						title : APP_NAME + ' Attention',
						message : strMessage_a,
						buttons : [
							{
								label : 'Close',
								action : function (objDialog_a)
								{
									if ($.isFunction(cb_a))
									{
										objDialog_a.close();
										cb_a();
									}
								}
							}
						]
					}
					);
				}
			}
		};

		// TODO: temporarily we are calling the non-scroll version
		this.dialogAlertScroll = function (strMessage_a, cb_a)
		{
			m_objThis.dialogAlert(strMessage_a, cb_a);
		};

		this.dialogConfirm = function (strMessage_a, cb_a, strTitle_a)
		{
			var strTitle = strTitle_a;
			if (strTitle === undefined)
			{
				strTitle = APP_NAME + ' Confirmation';
			}
			
			if (cb_a === undefined)
			{
				doNothing();
			}
			else
			{
				BootstrapDialog.show(
				{
					title : strTitle,
					message : strMessage_a,
					buttons : [
						{
							label : 'OK',
							action : function (objDialog_a)
							{
								if ($.isFunction(cb_a))
								{
									objDialog_a.close();
									cb_a();
								}
							}
						},
						{
							label : 'Cancel',
							action : function (objDialog_a)
							{
								objDialog_a.close();
							}
						}
					]
				}
				);
			}
		};

		this.element = function (strFormOrLocator_a, strLocator_a)
		{
			var objResult = null;

			if (strLocator_a === undefined)
			{
				objResult = $(strFormOrLocator_a);
			}
			else
			{
				objResult = $(strLocator_a, strFormOrLocator_a);
			}

			return objResult;
		};
		
		// refactor z-index's below m_MAXCALIBRATEDZORDERS
		function refactorZOrders()
		{
			var arrZOrders = [];
			
			$('.gb-forminstance', '#ge-form-container').each(function ()
			{
				var lngZOrder = parseInt($(this).children('.gb-form').css('z-index'), 10);
				if (!lngZOrder)
				{
					lngZOrder = 0;
				}
				
				if (lngZOrder < m_MAXCALIBRATEDZORDERS)
				{
					var objForm = {
						"formid" : $(this).attr('id'),
						"zorder" : lngZOrder
					};
					arrZOrders.push(objForm);
				}
			});

			arrZOrders.sort(function (a, b)
			{
				return a.zorder > b.zorder;
			}
			);

			var intNewIndex = m_SYSTEMZORDERS;
			processArray(arrZOrders, function(objForm_a)
			{
				m_objThis.element('#' + objForm_a.formid, '.gb-form').css('z-index', intNewIndex);
				intNewIndex++;
			});
			
			intNewIndex = m_SYSTEMZORDERS + m_MAXCALIBRATEDZORDERS;
			$('#ge-busyindicator').css('z-index', intNewIndex);
			//alert(JSON.stringify(arrZOrders));
		}
		
		function debugZOrders()
		{
			if (m_DEBUGZORDERS)
			{
				processForms(function (objForm_a)
				{
					var intIndex = m_objThis.element('#' + objForm_a.id, '.gb-form').css('z-index');
					m_objThis.element('#' + objForm_a.id, '.zorderdebug').remove();
					m_objThis.element('#' + objForm_a.id, '.gb-form').append('<font class="zorderdebug" style="color:red;"><b>' + intIndex + '</b></font>');
				});
			}
		}

		// bring a form to the front of the zorder if it is not dockable
		this.formToFront = function (strFormID_a, blnModal_a)
		{
			if (!m_objThis.isDockable(strFormID_a))
			{
				formToFrontInternal(strFormID_a, blnModal_a);
			}
		};
		
		function formToFrontInternal(strFormID_a, blnModal_a)
		{
			var blnModal = blnModal_a;
			if (blnModal === undefined) { blnModal = false; }
			
			var lngZOrder = 0;
			var objForm = null;

			if (m_strPopupFormID == strFormID_a)
			{
				blnModal = true;
			}
			
			refactorZOrders();

			// get the highest z-index that is below m_MAXCALIBRATEDZORDERS
			$('div.gb-form').each(function ()
			{
				var lngThisZ = parseInt($(this).css('z-index'), 10);
				if ((lngThisZ > lngZOrder) && (lngThisZ < m_MAXCALIBRATEDZORDERS))
				{
					lngZOrder = lngThisZ;
				}
			}
			);

			$('div.gb-page').each(function ()
			{
				var lngThisZ = parseInt($(this).css('z-index'), 10);
				if ((lngThisZ > lngZOrder) && (lngThisZ < m_MAXCALIBRATEDZORDERS))
				{
					lngZOrder = lngThisZ;
				}
			}
			);

			lngZOrder++;

			if (blnModal)
			{
				// if we are showing a form modally, we add this modal layer above all existing forms, then... put the form we want 1 above this layer
				$('#ge-modal-layer').css('z-index', lngZOrder);
				$('#ge-modal-layer').removeClass('gb-hidden');
				lngZOrder++;
			}
			else
			{
				if ($('#ge-modal-layer').hasClass('gb-hidden'))
				{
					doNothing();
				}
				else
				{
					$('#ge-modal-layer').addClass('gb-hidden');
				}
			}

			m_objThis.element(strFormID_a, '.gb-form').css('z-index', lngZOrder);

			// raise onFormToFront event
			processForms(function (objForm_a)
			{
				if ('#' + objForm_a.id == strFormID_a)
				{
					objForm = objForm_a.formobject;
					return true;
				}
			}
			);

			if (objForm !== null)
			{
				try
				{
					objForm.Form_onBringToFront();
				}
				catch (err)
				{
					doNothing();
				}
			}
			
			var strTop = m_objThis.element(strFormID_a, '.gb-form').css('top');
			if (strTop != undefined)
			{
				strTop = strTop.replace('px', '');
				var intTop = parseInt(strTop, 10);
				if (intTop < 0)
				{
					m_objThis.element(strFormID_a, '.gb-form').css('top', m_intCanvasTop + 'px');
				}
			}

			debugZOrders();
		}

		this.getDirtyDockableOpenForms = function()
		{
			var strResult = '';
			
			processForms(function (objForm_a)
			{
				var blnIsDirty = false;

				if (objForm_a.dockable)
				{
					var objForm = objForm_a.formobject;
					try
					{
						blnIsDirty = objForm.Form_isDirty();
					}
					catch (err)
					{
						doNothing();
					}
				}

				if (blnIsDirty)
				{
					strResult = objForm_a.id;
					return true;
				}
			});
			
			return strResult;
		};

		this.getFormFromHash = function(strHash_a)
		{
			var strHash = strHash_a.replace(/^#/, '');

			if (strHash.substring(0, 1) === '!')
			{
				strHash = m_objThis.str_right(strHash, strHash.length - 1);
			}
			
			if ((strHash === undefined) || (strHash.length === 0))
			{
				strHash = '';
			}

			//var strParameters = m_objThis.getAncorParameters(strHash);
			//var objParameters = m_objThis.urlToJSON(strParameters);

			strHash = m_objThis.getStrippedAnchor(strHash);

			return strHash;
		};
		
		this.getMenu = function ()
		{
			return '#' + m_strMenuFormID;
		};

		this.getTaskbar = function ()
		{
			return m_strTaskbarFormID;
		};

		this.hashChange = function(strHash_a)
		{
			var strHash = strHash_a.replace(/^#/, '');

			if (strHash.substring(0, 1) === '!')
			{
				strHash = m_objThis.str_right(strHash, strHash.length - 1);
			}
			
			if ((strHash === undefined) || (strHash.length === 0))
			{
				strHash = '';
			}

			var strParameters = m_objThis.getAncorParameters(strHash);
			var objParameters = m_objThis.urlToJSON(strParameters);

			strHash = m_objThis.getStrippedAnchor(strHash);
			m_objThis.showForm(strHash, strParameters, false, 0, 0);

			m_objThis.broadcast('system', 'hash', 'change', { "old":m_strOldHash, "new":strHash_a });
			m_strOldHash = strHash_a;

			return objParameters;
		};
		
		this.hideLanding = function (strFormID_a)
		{
			$('#' + strFormID_a).hide();
		};

		this.hideMDIClose = function (strFormID_a)
		{
			m_objThis.element(strFormID_a, '.gb-form-close').hide();
		};

		this.isButtonEnabled = function (strFormID_a, strField_a)
		{
			var blnDisabled = m_objThis.element(strFormID_a, '.' + strField_a).hasClass('gb-cell-disabled-xxx');
			return !blnDisabled;
		};

		this.isDockable = function (strFormID_a)
		{
			var blnResult = false;
			var strFormID = str_replace(strFormID_a, '#', '');

			processForms(function (objForm_a)
			{
				if (objForm_a.id == strFormID)
				{
					blnResult = objForm_a.dockable;
					return true;
				}
			}
			);

			return blnResult;
		};
		
		this.isFormVisible = function (strFormID_a)
		{
			var blnResult = false;
			var strFormID = str_replace(strFormID_a, '#', '');

			processForms(function (objForm_a)
			{
				if (objForm_a.id == strFormID)
				{
					blnResult = objForm_a.visible;
					return true;
				}
			}
			);

			return blnResult;
		};

		this.limitInput = function (strFormID_a, strLocator_a, intLimit_a, blnNoAlert_a)
		{
			if (blnNoAlert_a === undefined)
			{
				blnNoAlert_a = false;
			}

			if (blnNoAlert_a)
			{
				m_objThis.element(strFormID_a, strLocator_a).inputlimiter(
				{
					limit : intLimit_a,
					remText : '',
					remFullText : '',
					limitText : '',
					boxAttach : false,
					boxId : 'ge-temp-container'
				}
				);
			}
			else
			{
				m_objThis.element(strFormID_a, strLocator_a).inputlimiter(
				{
					limit : intLimit_a,
					remText : '%n character%s remaining...',
					remFullText : 'No characters remaining.',
					limitText : ''
				}
				);
			}
		};

		this.openTab = function (strForm_a, strParameters_a)
		{
			if (m_objThis.hasCapability('multimon') && m_objTabHandler.canCreateTab())
			{
				//var objPerspective = window;
				//objPerspective.open(EXTEND_PAGE, '_blank');
				m_objTabHandler.createTab(EXTEND_PAGE, strForm_a, strParameters_a);
			}
		};

		this.populateCombo = function (strFormID_a, strComboField_a, arrData_a, blnSort_a, blnNoEmpty_a, cbInclude_a)
		{
			var blnSort = blnSort_a;
			if (blnSort === undefined)
			{
				blnSort = false;
			}

			var blnNoEmpty = blnNoEmpty_a;
			if (blnNoEmpty === undefined)
			{
				blnNoEmpty = false;
			}

			var strData = '';
			if (blnNoEmpty === false)
			{
				strData = '<option value=""></option>';
			}

			m_objThis.element(strFormID_a, '.' + strComboField_a).find('option').remove().end();

			if (arrData_a !== null)
			{
				if (blnSort)
				{
					// sort in alphabetical order
					arrData_a.sort(function (a, b)
					{
						return a.description > b.description;
					}
					);
				}

				processArray(arrData_a, function (objData_a)
				{
					var strID = objData_a.id;
					var strDescription = objData_a.description;
                    
                    if(strDescription === undefined || strDescription === null || strDescription.length === 0)
                    {
                        strDescription = objData_a.fieldname;
                    }
                    
					var blnInclude = true;
					if ($.isFunction(cbInclude_a))
					{
						blnInclude = cbInclude_a(objData_a);
					}

					if (blnInclude)
					{
						if (strID === "[")
						{
							strData += '<optgroup label="' + htmlEncode(strDescription) + '">';
						}
						else if (strID === "]")
						{
							strData += '</optgroup>';
						}
						else
						{
							strData += '<option value="' + htmlEncode(strID) + '">' + htmlEncode(strDescription) + '</option>';
						}
					}
				}
				);
			}

			m_objThis.element(strFormID_a, '.' + strComboField_a).append(strData);
		};

		this.populateList = function (strFormID_a, strComboField_a, arrData_a, blnSort_a, blnNoEmpty_a, cbInclude_a)
		{
			var blnSort = blnSort_a;
			if (blnSort === undefined)
			{
				blnSort = false;
			}

			var blnNoEmpty = blnNoEmpty_a;
			if (blnNoEmpty === undefined)
			{
				blnNoEmpty = false;
			}

			var strData = '';
			if (blnNoEmpty === false)
			{
				//strData = '<option value=""></option>';
				doNothing();
			}

			m_objThis.element(strFormID_a, '.' + strComboField_a).find('li').remove().end();

			if (arrData_a !== null)
			{
				if (blnSort)
				{
					// sort in alphabetical order
					arrData_a.sort(function (a, b)
					{
						return a.description > b.description;
					}
					);
				}

				processArray(arrData_a, function (objData_a)
				{
					var strID = objData_a.id;
					var strDescription = objData_a.description;

					var blnInclude = true;
					if ($.isFunction(cbInclude_a))
					{
						blnInclude = cbInclude_a(objData_a);
					}

					if (blnInclude)
					{
						strData += '<li><a class="' + strComboField_a + '-option gb-button" value="' + htmlEncode(strID) + '">' + htmlEncode(strDescription) + '</a></li>';
					}
				}
				);
			}

			m_objThis.element(strFormID_a, '.' + strComboField_a).append(strData);
		};

		this.registerMenu = function (objForm_a, strFormID_a)
		{
			m_objMenuForm = objForm_a;
			m_strMenuFormID = str_replace(strFormID_a, '#', '');
			m_objThis.element('#' + m_strMenuFormID, '.gb-form').css('top', m_intCanvasTop + 'px');
			m_objThis.element('#' + m_strMenuFormID, '.gb-form').css('left', '0px');

			processForms(function (objForm_a)
			{
				if ('#' + objForm_a.id == strFormID_a)
				{
					objForm_a.menu = true;
					objForm_a.fullwidth = false;
					resizeMenu();
					onFormResize(m_strMenuFormID);
					return true;
				}
			});
		};

		this.registerAndUpdateFooter = function (objForm_a, strFormID_a)
		{
			m_objFooterForm = objForm_a;
			m_strFooterFormID = strFormID_a;
			m_intFooterHeight = parseInt(m_objThis.element(m_strFooterFormID, '.gb-form').css('height').replace("px",""), 10); // + m_FORMSHORTENINGQUIRK;
			var intTop = m_objThis.getViewPort().height - m_intFooterHeight;
			
			m_objThis.element(m_strFooterFormID, '.gb-form').css('left', '0px');	// out of view at the start so it doesn't cause a vertical scrollbar
			m_objThis.element(m_strFooterFormID, '.gb-form').css('top', intTop + 'px');	// out of view at the start so it doesn't cause a vertical scrollbar
		};

		this.registerTaskbar = function (objForm_a, strFormID_a)
		{
			m_objTaskbarForm = objForm_a;
			m_strTaskbarFormID = strFormID_a;
			m_objThis.element(m_strTaskbarFormID, '.gb-form').css('top', '0px');
		};

		this.setFormFocus = function (objForm_a, strFormID_a, blnBounceOther_a, varParameters_a)
		{
			if (blnBounceOther_a === undefined)
			{
				blnBounceOther_a = false;
			}

			// bounce focus by other non-related events
			if (blnBounceOther_a)
			{
				m_strBounceFocus = strFormID_a;
			}

			if ((m_strBounceFocus.length > 0) && (m_strBounceFocus != strFormID_a))
			{
				logDebug('focus bounced: ' + strFormID_a);
				m_strBounceFocus = '';
			}
			else
			{
				//if (m_strFocusFormID != strFormID_a)
				//{
				if (m_strFocusFormID.length > 0)
				{
					// call old focus form's focuslost event
					if (m_objFocusForm === undefined)
					{
						doNothing();
					}
					else
					{
						try
						{
							m_objFocusForm.Form_onFocusLost();
						}
						catch (err)
						{
							doException('setFormFocus', err);
						}
					}
				}

				m_objFocusForm = objForm_a;
				m_strFocusFormID = strFormID_a;
				processForms(function (objForm_a)
				{
					var objFormTitle = m_objThis.element('#' + objForm_a.id, '.gb-formtitle-inner-panel');
					if (objFormTitle !== undefined)
					{
						if ('#' + objForm_a.id == strFormID_a)
						{
							objFormTitle.removeClass('gs-black-background-colour');
							objFormTitle.addClass('gs-navyblue-background-colour');
							logDebug('focus: ' + objForm_a.id + ', name: ' + objForm_a.name);
						}
						else
						{
							objFormTitle.removeClass('gs-navyblue-background-colour');
							objFormTitle.addClass('gs-black-background-colour');
						}
					}
				}
				);

				// call new focus form's focus event
				try
				{
					m_objFocusForm.Form_onFocus(varParameters_a);
				}
				catch (err)
				{
					doException('setFormFocus', err);
				}
				//}
				//else
				//{
				//	m_strBounceFocus = '';
				//}
			}
		};
		
		// note: perhaps this should have a mutex or something
		this.removeStyle = function (strFormID_a, strClass_a, strStyle_a)
		{
			// check if style exists already
			var strStyles = m_objThis.element(strFormID_a, strClass_a).attr('style');
			var strNewStyles = strStyles;

			// find style with a space
			var intStylePos = strStyles.indexOf(strStyle_a + ' ');
			if (intStylePos === -1)
			{
				// find style with a =
				intStylePos = strStyles.indexOf(strStyle_a + ':');
			}
		
			if (intStylePos >= 0)
			{
				var intStyleEnd = strStyles.indexOf(';', intStylePos);

				if (intStyleEnd >= 0)
				{
					// remove the existing style
					strNewStyles = $.trim(strStyles.substring(0, intStylePos)) + ' ' + $.trim(strStyles.substring(intStyleEnd + 1, strStyles.length));
				}
			}
			
			m_objThis.element(strFormID_a, strClass_a).attr('style', strNewStyles);
		};
		
		// note: perhaps this should have a mutex or something
		this.addStyle = function (strFormID_a, strClass_a, strStyle_a, strValue_a)
		{
			// remove style before adding it again
			m_objThis.removeStyle(strFormID_a, strClass_a, strStyle_a);
			
			// add the style
			var strStyles = m_objThis.element(strFormID_a, strClass_a).attr('style');
			strStyles += ' ' + strStyle_a + ':' + strValue_a + ';';
			m_objThis.element(strFormID_a, strClass_a).attr('style', strStyles);
		};
		
		this.setFormHeight = function (strFormID_a, strClass_a, intHeight_a)
		{
			var intHeight = intHeight_a;
			var intVPHeight = m_objThis.getViewPort().height;
			var intSpacerHeight = m_objThis.element(strFormID_a, '.gb-spacer').height();

			if (intHeight !== undefined)
			{
				//intHeight += m_intCanvasTop;

				if (intHeight < intVPHeight)
				{
					var intGrowBy = parseInt((intVPHeight - intHeight) / intSpacerHeight, 10);
					intHeight = intVPHeight;
					
					var strNewSpacer = '&nbsp;<br>';
					var strNewSpacers = strNewSpacer;
					for(var intI = 0; intI < intGrowBy; intI++)
					{
						strNewSpacers += strNewSpacer;
					}
					m_objThis.element(strFormID_a, '.gb-spacer').html(strNewSpacers);
					
					//m_objThis.addStyle(strFormID_a, 'body', 'overflow-y', 'hidden');
				}
				
				m_objThis.addStyle(strFormID_a, strClass_a, 'height', intHeight + 'px');
			}
		};

		this.setTabOrder = function (strFormID_a, strTabOrder_a)
		{
//return;
			removeTabs(m_objThis.element('html', '*'));

			// put specific tabs on the form
			var arrFields = strTabOrder_a.split(',');
			var objLastElement = null;
			var intI = 1;
			processArray(arrFields, function (strField_a)
			{
				var strField = $.trim(strField_a);
				if ((strField.length > 0) && (m_objThis.element(strFormID_a, '.' + strField).hasClass('gb-readonly') === false))
				{
					try
					{
						//logDebug('setting tabOrder on field ' + strFormID_a + '.' + strField + ' to ' + intI.toString() + '.');
						objLastElement = m_objThis.element(strFormID_a, '.' + strField);
						objLastElement.attr('tabindex', intI.toString());
//objLastElement.val('T' + intI.toString());
						intI++;
					}
					catch (err)
					{
						//logDebug('setting tabOrder on field ' + strFormID_a + '.' + strField + ' failed.');
//objLastElement.val('E' + intI.toString());
					}
				}
				else
				{
//objLastElement.val('S' + intI.toString());
					doNothing();
				}
			}
			);

			// note: bug 1, setting the tab order to 0 for the last element on the form was a workaround to allow tabbing within the grids to not get lost when changing the z-index of forms
			objLastElement.attr('tabindex', '0');
		};
		
		this.isDocked = function(strFormID_a)
		{
			return m_objThis.element(strFormID_a, '.gb-form').hasClass('gb-docked');
		};

		this.hideBusyIndicator = function()
		{            
			if (m_intBusyIndicator > 0)
			{
				m_intBusyIndicator--;                

				if (m_intBusyIndicator === 0)
				{
					$('#ge-busyindicator').hide();
				}
			}
		};
		
		this.showBusyIndicator = function(strForm_a)
		{
			if (m_intBusyIndicator === 0)
			{
				if (m_intBusyIndicatorDeferral > 0)
				{
					m_objThis.after(m_intBusyIndicatorDeferral, function()
					{
						if (m_intBusyIndicator > 0)
						{
							$('#ge-busyindicator').show();
						}
					});
				}
				else
				{
					$('#ge-busyindicator').show();
				}
			}

			m_intBusyIndicator++;
		};
		
		this.showElement = function (strFormOrLocator_a, strLocator_a, blnVisible_a)
		{
			if (blnVisible_a)
			{
				m_objThis.element(strFormOrLocator_a, strLocator_a).removeClass('gb-hidden');
				m_objThis.element(strFormOrLocator_a, strLocator_a).show();
			}
			else
			{
				m_objThis.element(strFormOrLocator_a, strLocator_a).addClass('gb-hidden');
				m_objThis.element(strFormOrLocator_a, strLocator_a).hide();
			}
		};

		this.showLanding = function (strFormID_a)
		{
			$('#' + strFormID_a).show();
		};

		this.destroyMenu = function(strParentFormID_a)
		{
			m_objThis.element(strParentFormID_a, '.ge-burgermenu').addClass('hidden');
			m_blnMenuCreated = false;
			//alert('destroyMenu');
		};
		
		this.createMenu = function(strParentFormID_a)
		{
			m_objThis.element(strParentFormID_a, '.ge-burgermenu').removeClass('hidden');
			m_blnMenuCreated = true;
			//alert('createMenu');
		};

		this.hideMenu = function()
		{
			hideFormI(m_strMenuFormID);
			m_blnMenuOpened = false;
			
			processForms(function (objForm_a)
			{
				if (objForm_a.resizable)
				{
					onFormResizeAfterDelay(objForm_a.id, 100);
				}
			});
			onFormResizeAfterDelay(m_strMenuFormID, 100);
			//alert('hideMenu');
		};
		
		this.showMenu = function()
		{
			if (m_blnMenuCreated)
			{
				showFormI(m_strMenuFormID);
				m_blnMenuOpened = true;
				
				processForms(function (objForm_a)
				{
					if (objForm_a.resizable)
					{
						onFormResizeAfterDelay(objForm_a.id, 100);
					}
				});
				onFormResizeAfterDelay(m_strMenuFormID, 100);
				formToFrontInternal('#' + m_strMenuFormID, false);
				//alert('showMenu');
			}
		};

		this.menuToFront = function()
		{
			formToFrontInternal('#' + m_strMenuFormID, false);
		};
		
		this.toggleMenu = function()
		{
			if (m_blnMenuCreated)
			{
				if (m_blnMenuOpened)
				{
					m_objThis.hideMenu();
				}
				else
				{
					m_objThis.showMenu();
				}
			}
			else
			{
				m_objThis.hideMenu();
			}
		};
		
		this.makeForm = function (strFullFormName_a, varParameters_a, blnCentre_a, lngFormOffsetX_a, lngFormOffsetY_a, blnBypassDirtyCheck_a, cbOnLoad_a)
		{
			m_objThis.dialogAlert('os.makeForm is now deprecated', doNothing);
			//if (strFullFormName_a.length > 0)
			//{
				//createForm(strFullFormName_a, varParameters_a, null, blnCentre_a, false, false, false, lngFormOffsetX_a, lngFormOffsetY_a, true, blnBypassDirtyCheck_a, cbOnLoad_a);
			//}
		};

		// either create a form or go to a URL depending on whether we want a new page and if MDI is enabled or not
		this.showForm = function (strFullFormName_a, varParameters_a, blnCentre_a, lngFormOffsetX_a, lngFormOffsetY_a, blnBypassDirtyCheck_a, cbOnLoad_a)
		{
			if (strFullFormName_a.length > 0)
			{
				if (m_objThis.toBoolean(ENABLE_XDEVICE))
				{
					showFormLocal2(strFullFormName_a, varParameters_a, blnCentre_a, lngFormOffsetX_a, lngFormOffsetY_a, blnBypassDirtyCheck_a, function()
					{
						createForm(strFullFormName_a, varParameters_a, null, blnCentre_a, false, false, false, lngFormOffsetX_a, lngFormOffsetY_a, false, blnBypassDirtyCheck_a, cbOnLoad_a);
					});
				}
				else
				{
					createForm(strFullFormName_a, varParameters_a, null, blnCentre_a, false, false, false, lngFormOffsetX_a, lngFormOffsetY_a, false, blnBypassDirtyCheck_a, cbOnLoad_a);
				}
			}
		};

		// no networking version
		this.showFormLocal = function (strFullFormName_a, varParameters_a, cbReturn_a, blnCentre_a, lngFormOffsetX_a, lngFormOffsetY_a, blnBypassDirtyCheck_a, cbOnLoad_a)
		{
			if (strFullFormName_a.length > 0)
			{
				createForm(strFullFormName_a, varParameters_a, cbReturn_a, blnCentre_a, false, false, false, lngFormOffsetX_a, lngFormOffsetY_a, false, blnBypassDirtyCheck_a, cbOnLoad_a);
			}
		};

		function showFormLocal2(strFullFormName_a, varParameters_a, blnCentre_a, lngFormOffsetX_a, lngFormOffsetY_a, blnBypassDirtyCheck_a, cbOnShowFormLocal_a)
		{
			var blnResult = true;
			var varParameters;

			if (varParameters_a === undefined)
			{
				varParameters = {};
			}
			else if (typeof varParameters_a === 'string')
			{
				varParameters = m_objThis.urlToJSON(varParameters_a);
			}
			else
			{
				varParameters = varParameters_a;
			}
		
			var objJSON = this.ajaxRequestCreate('core_showform_device', 
					[
						{ 
							"name" : "formname", 
							"value" : strFullFormName_a 
						},
						{ 
							"name" : "params", 
							"value" : varParameters 
						},
						{ 
							"name" : "center", 
							"value" : blnCentre_a
						},
						{ 
							"name" : "formoffsetx", 
							"value" : lngFormOffsetX_a 
						},
						{ 
							"name" : "formoffsety", 
							"value" : lngFormOffsetY_a 
						},
						{ 
							"name" : "bypassdirtycheck", 
							"value" : blnBypassDirtyCheck_a 
						}
					]);
			
			this.ajaxCall(URL_WEBSERVICE, objJSON, function(objResponse_a) 
			{
				var blnResult = objResponse_a.result; //this.toBoolean(objResponse_a.result);
				
				if (blnResult && $.isFunction(cbOnShowFormLocal_a))
				{
					cbOnShowFormLocal_a();
				}
								
			}, doNothing);
									
		}

		this.showFormPopup = function (strFullFormName_a, varParameters_a, cbReturn_a, blnFormifyToDeprecate_a, blnBypassDirtyCheck_a, cbOnLoad_a)
		{
			if (m_strPopupFormID.length === 0)
			{
				createForm(strFullFormName_a, varParameters_a, cbReturn_a, true, true, false, false, 0, 0, false, blnBypassDirtyCheck_a, cbOnLoad_a);
			}
			else
			{
				m_objThis.dialogAlert('A modal dialog is already open.', doNothing);
			}
		};

		this.showMDIClose = function (strFormID_a)
		{
			m_objThis.element(strFormID_a, '.gb-form-close').show();
		};

		this.closeExclusive = function(strFormID_a)
		{
			var strFormID = strFormID_a;
			if (strFormID === undefined) { strFormID = ''; }
			
			strFormID = str_replace(strFormID, '#', '');
			
			processForms(function (objForm_a)
			{
				if (m_objThis.element('#' + objForm_a.id, '.gb-form').hasClass('gb-nonexclusive'))
				{
					if ((objForm_a.id === strFormID) || ((objForm_a.visible) && (!objForm_a.taskbar)))
					{
						var strTemp = '';
						strTemp = str_replace(m_objThis.element(strFormID, '.gb-form').css('top'), 'px', '');
						var intTop = parseInt(strTemp, 10);
					
						if (objForm_a.id === strFormID)
						{
							objForm_a.visible = true;
							$('#' + objForm_a.id).show();
						}

						if (intTop < m_intCanvasTop)
						{
							m_objThis.element('#' + objForm_a.id, '.gb-form').css('top', m_intCanvasTop + 'px');
						}
					}
				}
				else
				{
					if (objForm_a.id === strFormID)
					{
						objForm_a.visible = true;
						$('#' + objForm_a.id).show();
						m_objThis.element('#' + objForm_a.id, '.gb-form').css('top', m_intCanvasTop + 'px');
					}
					else
					{
						objForm_a.visible = false;
						$('#' + objForm_a.id).hide();
					}
				}
			});
			
			m_objThis.menuToFront();
		};

		this.showTaskbar = function (strFullFormName_a, varParameters_a, blnBypassDirtyCheck_a, cbOnLoad_a)
		{
			var strURL = '#' + str_replace(strFullFormName_a, '.', '-');
			var varParameters = varParameters_a;

			createForm(strFullFormName_a, varParameters_a, null, false, false, true, false, 0, 0, false, blnBypassDirtyCheck_a, cbOnLoad_a);
		};

		this.setUnloadPrompt = function (bln_a)
		{
			m_objArgs.unloadprompt = bln_a;
		};

		// ====================================================================================
		// ZOOMING ============================================================================

		this.fullZoom = function (strFormID_a)
		{
			var objViewPort = m_objThis.getViewPort();

			var intVPWidth = objViewPort.width;
			var intFormWidth = m_objThis.element(strFormID_a, '.gb-form').width();
			var fltHZoom = intVPWidth / intFormWidth;

			var intVPHeight = objViewPort.height;
			var intFormHeight = m_objThis.element(strFormID_a, '.gb-form').height();
			var fltVZoom = intVPHeight / intFormHeight;

			var fltZoom = Math.min(fltHZoom, fltVZoom) - 0.01;

			// screwing around with margins because of MSIE which then doesn't like Firefox
			m_objThis.element(strFormID_a, '.gb-form').css('margin-left', '0px');
			//alert(m_objThis.element(strFormID_a, '.gb-form').css('margin-left'));

			m_objThis.element(strFormID_a, '.gb-form').css('zoom', fltZoom);
			//m_objThis.element(strFormID_a, '.gb-form').css('-ms-zoom', fltZoom);
			//m_objThis.element(strFormID_a, '.gb-form').css('-webkit-zoom', fltZoom);
			m_objThis.element(strFormID_a, '.gb-form').css('-moz-transform', 'scale(' + fltZoom + ',' + fltZoom + ')');
			m_objThis.element(strFormID_a, '.gb-form').css('-moz-transform-origin', 'left top');

			// center within viewport
			fltZoom = ((parseInt(((fltZoom + 0.05) * 10) + '', 10)) / 10);
			var fltFormWidth = m_objThis.element(strFormID_a, '.gb-form').width() * fltZoom;
			var strLeft = ((objViewPort.width * 0.5) - (fltFormWidth * 0.5)) + '';
			var intLeft = parseInt(strLeft, 10);

			//alert(m_objThis.element(strFormID_a, '.gb-form').css('margin-left'));
			//alert("Z:" + fltZoom + ", VPW:" + objViewPort.width + ", FW:" + fltFormWidth + ", " + intLeft + "px");
			m_objThis.element(strFormID_a, '.gb-form').css('margin-left', intLeft + 'px');
		};

		// returns the current zoom ratio based on 100% being when the window was opened
		this.getZoom = function ()
		{
			var intResult = 100;

			try
			{
				intResult = m_objThis.element('#ge-zoom2-container').get(0).offsetLeft / m_objThis.element('#ge-zoom1-container').get(0).offsetLeft;
				var intZoomAbsolute = Math.floor(m_intZoom100 * Math.floor(intResult * 100) * 0.01);
				intResult = Math.floor((intZoomAbsolute / m_intZoom100) * 100);
			}
			catch (err)
			{
				doException('getZoom', err);
			}

			return intResult;
		};

		// zoom a form in and out
		this.zoomFormInOut = function (strFormID_a)
		{
			var blnZoomedOut = m_objThis.element(strFormID_a, '.gb-form').hasClass('gb-zoomedout');

			if (blnZoomedOut)
			{
				m_objThis.zoomInOut();
				$('html, body').animate(
				{
					scrollTop : m_objThis.element(strFormID_a, '.gb-form').offset().top,
					scrollLeft : m_objThis.element(strFormID_a, '.gb-form').offset().left
				}, 0);
			}
		};

		// zoom all forms in and out
		this.zoomInOut = function ()
		{
			processForms(function (objForm_a)
			{
				var strFormID = '#' + objForm_a.id;

				if (m_objThis.element(strFormID, '.gb-form').hasClass('gb-nozoom'))
				{
					doNothing();
				}
				else
				{
					var fltScale = 1;

					var strTemp = '';
					strTemp = str_replace(m_objThis.element(strFormID, '.gb-form').css('top'), 'px', '');
					objForm_a.top = parseInt(strTemp, 10);
					strTemp = str_replace(m_objThis.element(strFormID, '.gb-form').css('left'), 'px', '');
					objForm_a.left = parseInt(strTemp, 10);

					if (m_blnZoomed)
					{
						if (m_objThis.element(strFormID, '.gb-form').hasClass('gb-nozoomxy'))
						{
							fltScale = 4;
						}
						else
						{
							// zoom in
							if (m_objThis.element(strFormID, '.gb-form').hasClass('gb-zoomedout'))
							{
								m_objThis.element(strFormID, '.gb-form').removeClass('gb-zoomedout');
								fltScale = 4;
							}
						}

						// put the form into the view if it is too far to the left or top
						m_objThis.element(strFormID, '.gb-form').css('top', (((objForm_a.top - m_intCanvasTop) * fltScale) + m_intCanvasTop) + 'px');
						m_objThis.element(strFormID, '.gb-form').css('left', (objForm_a.left * fltScale) + 'px');
					}
					else
					{
						if (m_objThis.element(strFormID, '.gb-form').hasClass('gb-nozoomxy'))
						{
							fltScale = 0.25;
						}
						else
						{
							// zoom out
							if (m_objThis.element(strFormID, '.gb-form').hasClass('gb-zoomedout') === false)
							{
								m_objThis.element(strFormID, '.gb-form').addClass('gb-zoomedout');
								fltScale = 0.25;
							}
						}

						// put the form into the view if it is too far to the left or top
						m_objThis.element(strFormID, '.gb-form').css('top', (((objForm_a.top - m_intCanvasTop) * fltScale) + m_intCanvasTop) + 'px');
						m_objThis.element(strFormID, '.gb-form').css('left', (objForm_a.left * fltScale) + 'px');
					}

					if ((objForm_a.top * fltScale) < 0)
					{
						m_objThis.element(strFormID, '.gb-form').css('top', m_intCanvasTop + 'px');
					}
					if ((objForm_a.left * fltScale) < 0)
					{
						m_objThis.element(strFormID, '.gb-form').css('left', m_intCanvasTop + 'px');
					}
				}
			}
			);

			if (m_blnZoomed)
			{
				m_blnZoomed = false;
			}
			else
			{
				m_blnZoomed = true;
			}
		};

		// ====================================================================================
		// COOKIES ============================================================================

		function getCookie(strName_a)
		{
			var strArgument;
			var intArgumentLength;
			var intCookieLength;
			var intEndString;
			var intI;
			var intJ;

			strArgument = strName_a + '=';
			intArgumentLength = strArgument.length;
			intCookieLength = document.cookie.length;
			intI = 0;
			
			//alert('find:' + strName_a + ' in:' + document.cookie);
			while (intI < intCookieLength)
			{
				intJ = intI + intArgumentLength;
				if (document.cookie.substring(intI, intJ) == strArgument)
				{
					intEndString = document.cookie.indexOf(';', intJ);
					if (intEndString == -1)
					{
						intEndString = document.cookie.length;
					}
					
					//alert(document.cookie.substring(intJ, intEndString));
					return unescape(document.cookie.substring(intJ, intEndString));
				}

				intI = document.cookie.indexOf(' ', intI) + 1;
				if (intI === 0)
				{
					break;
				}
			}
			return (null);
		}

		function setCookie(strName_a, strValue_a)
		{
			//alert('setCookie 1:' + strName_a);
			var intArgumentCount;
			var arrArgumentValues;
			var strCookie;
			var strDomain;
			var strExpires;
			var strPath;
			var blnSecure;

			arrArgumentValues = setCookie.arguments;
			intArgumentCount = setCookie.arguments.length;

			strExpires = (intArgumentCount > 2) ? arrArgumentValues[2] : null;
			strPath = (intArgumentCount > 3) ? arrArgumentValues[3] : null;
			strDomain = (intArgumentCount > 4) ? arrArgumentValues[4] : null;
			blnSecure = (intArgumentCount > 5) ? arrArgumentValues[5] : false;

			strCookie = strName_a + '=' + escape(strValue_a) + ((strExpires === null) ? '' : ('; expires=' + strExpires.toGMTString())) + ((strPath === null) ? '' : ('; path=' + strPath)) + ((strDomain === null) ? '' : ('; domain=' + strDomain)) + ((blnSecure === true) ? '; secure' : ''); // + '; Partitioned';
			
			//alert('setCookie 2:' + strCookie);
			
			document.cookie = strCookie;
		}

		// ====================================================================================
		// INDICATORS =========================================================================

		//this.alarmIndicator = function(blnOn_a)
		//{
		//	$('#divAlarmIndicator').removeClass('gi-alarm');

		//	if (blnOn_a)
		//	{
		//		$('#divAlarmIndicator').addClass('gi-alarm');
		//	}
		//};

		this.connectionIndicator = function (blnOK_a)
		{
			if (m_strTaskbarFormID.length > 0)
			{
				var objIndicator = m_objThis.element(m_strTaskbarFormID, '.gb-indicator-connection');
				objIndicator.removeClass('gi-connectionamber');
				objIndicator.removeClass('gi-connectiongreen');
				objIndicator.removeClass('gi-connectionred');

				if (blnOK_a)
				{
					objIndicator.addClass('gi-connectiongreen');
				}
				else
				{
					objIndicator.addClass('gi-connectionred');
				}
			}
		};

		//this.printerIndicator = function(blnOn_a)
		//{
		//	$('#divPrinterIndicator').removeClass('gi-printer');

		//	if (blnOn_a)
		//	{
		//		$('#divPrinterIndicator').addClass('gi-printer');
		//	}
		//};

		this.transmissionIndicator = function (blnOn_a)
		{
			if (m_strTaskbarFormID.length > 0)
			{
				var objIndicator = m_objThis.element('.gb-form', '.gb-indicator-transmission');
				if (blnOn_a)
				{
					if (m_intNestedAJAX === 0)
					{
						objIndicator.removeClass('gi-xmitgrey');
						objIndicator.removeClass('gi-xmitgreen');
						objIndicator.removeClass('gi-xmitred');

						objIndicator.addClass('gi-xmitgreen');
					}
					m_intNestedAJAX++;
				}
				else
				{
					m_intNestedAJAX--;
					if (m_intNestedAJAX <= 0)
					{
						objIndicator.removeClass('gi-xmitgrey');
						objIndicator.removeClass('gi-xmitgreen');
						objIndicator.removeClass('gi-xmitred');

						objIndicator.addClass('gi-xmitgrey');
						m_intNestedAJAX = 0;
					}
				}
			}
		};

		// ====================================================================================
		// KEYBOARD SHORTCUTES ================================================================

		this.shortcutAdd = function (strShortcut_a, cb_a)
		{
			var arrShortcuts = strShortcut_a.split(',');
			processArray(arrShortcuts, function (strShortcut_a)
			{
				shortcut.add(strShortcut_a, cb_a);
			}
			);
		};

		this.shortcutRemove = function (strShortcut_a)
		{
			var arrShortcuts = strShortcut_a.split(',');
			processArray(arrShortcuts, function (strShortcut_a)
			{
				shortcut.remove(strShortcut_a);
			}
			);
		};

		this.shortcuts = function ()
		{
			return g_objShortcuts;
		};

		// ====================================================================================
		// FORM CREATION ======================================================================

		function analytics(strFormName_a)
		{
			ga('send', 'pageview', '#' + strFormName_a);
		}

		function createForm(strFullFormName_a, varParameters_a, cbReturn_a, blnCentre_a, blnPopup_a, blnTaskbar_a, blnFooter_a, lngFormOffsetX_a, lngFormOffsetY_a, blnFormify_a, blnBypassDirtyCheck_a, cbOnLoad_a)
		{
			var strResult = '';
			
			m_objThis.lazyLoadForm(strFullFormName_a, function ()
			{
				strResult = createForm2(strFullFormName_a, varParameters_a, cbReturn_a, blnCentre_a, blnPopup_a, blnTaskbar_a, blnFooter_a, lngFormOffsetX_a, lngFormOffsetY_a, blnFormify_a, blnBypassDirtyCheck_a);
				if ($.isFunction(cbOnLoad_a))
				{
					cbOnLoad_a(strResult);
				}
			}
			);
			
			return strResult;
		}

		function createForm2(strFullFormName_a, varParameters_a, cbReturn_a, blnCentre_a, blnPopup_a, blnTaskbar_a, blnFooter_a, lngFormOffsetX_a, lngFormOffsetY_a, blnFormify_a, blnBypassDirtyCheck_a)
		{
			m_objThis.showBusyIndicator();
			
			var blnFormify = blnFormify_a;
			if (blnFormify === undefined)
			{
				blnFormify = false;
			}

			var blnBypassDirtyCheck = blnBypassDirtyCheck_a;
			if (blnBypassDirtyCheck === undefined)
			{
				blnBypassDirtyCheck = false;
			}

			var blnModal = false;
			var objForm = null;
			var strResult = '';
			var varParameters;

			if (varParameters_a === undefined)
			{
				varParameters = {};
			}
			else if (typeof varParameters_a === 'string')
			{
				varParameters = m_objThis.urlToJSON(varParameters_a);
			}
			else
			{
				varParameters = varParameters_a;
			}

			var strFullFormName = str_replace(strFullFormName_a, '-', '.');
			var arrFormName = strFullFormName.split('.');
			var strModule = arrFormName[0];
			var strFormName = strModule + '_' + arrFormName[1];
            
			try
			{
				// even though we may not require this instance of the form, we need to check if it allows multiple instances from within this instance
				var strFormID = 'form_' + getGUID();
				var strDirtyFormID = '';
				
				objForm = new window[strFormName](m_objThis, '#' + strFormID, varParameters); //instead of var strJavaScript = 'objForm = new ' + strFormName + '(m_objThis, "#" + strFormID, varParameters); '; eval(strJavaScript);

				var blnAllowMultiple = true;
				// m_objOldFocusForm = m_objFocusForm;
				m_objOldFocusForm.push(m_objFocusForm);
				// m_strOldFocusFormID = m_strFocusFormID;
				m_strOldFocusFormID.push(m_strFocusFormID);

				// m_cbReturn = cbReturn_a;	// popup forms and showformlocal support this
				m_cbReturn.push(cbReturn_a);	// popup forms and showformlocal support this
				// m_objPopupForm = objForm;
				m_objPopupForm.push(objForm);

				// if we want a popup but we are not in a popup
				if ((blnPopup_a) && (m_strPopupFormID.length === 0))
				{
					m_strPopupFormID = '#' + strFormID;
					blnModal = true;
				}

				var blnFormTitlePanel = (m_objThis.element('#' + strFormName + 'Template', '.gb-form').find('.gb-formtitle-inner-panel').length > 0);
				var blnDockable = m_objThis.element('#' + strFormName + 'Template', '.gb-form').hasClass('gb-dockable');
				//var blnBringToFront = m_objThis.element('#' + strFormName + 'Template', '.gb-form').hasClass('gb-bringtofront');
				var blnAlwaysDocked = m_objThis.element('#' + strFormName + 'Template', '.gb-form').hasClass('gb-alwaysdocked');
				if (!blnAlwaysDocked)
				{
					blnDockable = blnDockable && !m_objThis.isMDI();	// can dock if window is dockable and docking is enabled
				}
				
				// if we want to disallow multiple of the same window open at once
				//if (blnDockable)
				//{
					//blnAllowMultiple = false;
				//}
				//else
				//{
					try
					{
						blnAllowMultiple = objForm.Form_allowMultipleInstances();
					}
					catch (err)
					{
						blnAllowMultiple = true;
					}
				//}
				
				var strDesktopRegion = "";
				
				try
				{
					strDesktopRegion = objForm.Form_getDesktopRegion();
				}
				catch (err)
				{
					doNothing();
				}
				
				if (strDesktopRegion.length === 0)
				{
					strDesktopRegion = "CAS";	// default
					if (blnDockable && !m_objThis.isMDI())
					{
						strDesktopRegion = "FS";
					}
				}

				var blnOpenForm = true;
				var blnFormCancelled = false;
				
				// restrict forms to a single one at a time
				if ((blnDockable) && (!blnBypassDirtyCheck))
				{
					strDirtyFormID = m_objThis.getDirtyDockableOpenForms();
					if (strDirtyFormID.length > 0)
					{
						blnOpenForm = false;
						blnFormCancelled = true;
						m_objThis.hideBusyIndicator();
						m_objThis.dialogAlert('You are currently editing a form, please save or cancel your changes first.', doNothing);
					}
					else
					{
						blnOpenForm = true;
					}
				}

				if (blnOpenForm)
				{
					blnOpenForm = blnOpenForm && canFormOpen(strFormName, blnAllowMultiple);
					if (blnOpenForm === true)
					{
						if (objForm.Form_onPermissionCheck())
						{
							//var blnResizable = blnFormify && m_objThis.element('#' + strFormName + 'Template', '.gb-form').hasClass('gb-resizable');
							var blnResizable = m_objThis.element('#' + strFormName + 'Template', '.gb-form').hasClass('gb-resizable');
							//blnResizable = blnResizable && !blnDockable;	// cannot be resized if docked
							createFormLayout('#' + strFormName + 'Template', strFormID, strFormName, blnCentre_a, blnModal, blnTaskbar_a, blnFormTitlePanel, lngFormOffsetX_a, lngFormOffsetY_a, blnResizable, blnFormify, strDesktopRegion, varParameters_a);
							registerFormInstance(strFormID, strFormName, objForm, false, blnTaskbar_a, blnFooter_a, blnResizable, blnDockable, blnFormify);
							raiseEvent(objForm, objForm, objForm, 'Form', 'onLoad');
							initialiseMDI(strFormID, blnResizable, blnDockable);

							if (blnDockable)
							{
								m_objThis.element('#' + strFormID, '.gb-form').addClass('gb-docked');
								//m_objThis.closeDockableFormsExcept(strFormID, doNothing);
							}
						}
						else
						{
							// note: this isn't a permission check to refuse usage of the app, it is a permission check to not open a form
							doNothing();
						}
					}
				}
				
				if (!blnFormCancelled)
				{
					if (!blnOpenForm)
					{
						if (strDirtyFormID.length > 0)
						{
							strFormID = strDirtyFormID;
						}
						else
						{
							strFormID = getFirstForm(strFormName);
						}

						// get the form
						processForms(function (objForm_a)
						{
							if ('#' + objForm_a.id == '#' + strFormID)
							{
								objForm = objForm_a.formobject;
								return true;
							}
						}
						);

						formToFrontInternal('#' + strFormID, false);
					}

					if (strFormID == m_strMenuFormID)
					{
						hideFormI(strFormID);
					}
					else
					{
						showFormI(strFormID);
						
						if (blnTaskbar_a)
						{
							m_intCanvasTop = parseInt(m_objThis.element(m_strTaskbarFormID, '.gb-form').css('height'), 10) - m_CANVASTOPQUIRK;
						} 
						
						// not yet used as m_intCanvasBottom is set in the post form load register function
						//if (blnFooter_a)
						//{
							//m_intCanvasBottom = parseInt(m_objThis.element(m_strFooterFormID, '.gb-form').css('height'), 10);
						//}
					}
					m_objThis.setFormFocus(objForm, '#' + strFormID, true, varParameters);
					
					//if (blnBringToFront)
					//{
						formToFrontInternal('#' + strFormID, false);	// always bring new forms to front
						//formToFrontInternal('#' + m_strMenuFormID, false);	// menu always on top
					//}

					analytics(strFormName);
				}
			}
			catch (err)
			{                
				doException('createForm2', err);
			}
			
			m_objThis.hideBusyIndicator();

			strResult = strFormID;

			return strResult;
		}

		// create a form layout from a template
		function createFormLayout(strLocatorFrom_a, strFormID_a, strFormName_a, blnCentre_a, blnModal_a, blnTaskbar_a, blnFormTitlePanel_a, lngFormOffsetX_a, lngFormOffsetY_a, blnResizable_a, blnFormify_a, strDesktopRegion_a, varParameters_a)
		{
            var blnFormify = blnFormify_a;
            var strContainer = m_objArgs.container;
            var blnHasParentContainer = false;

			if (blnFormify == undefined)
			{
				blnFormify = false;
			}
			var blnForceTopOfViewport = false;
			
			if ((lngFormOffsetX_a === 0) && (lngFormOffsetY_a === 0))
			{
				blnForceTopOfViewport = true;
				window.scrollTo(0, 0);
			}
			
			var strHTML = '';
			var lngFormOffsetX = lngFormOffsetX_a;
			if (lngFormOffsetX == undefined)
			{
				if (m_objThis.isMDI())
				{
					lngFormOffsetX = m_FORMOFFSETX;
				}
				else
				{
					lngFormOffsetX = 0;
				}
			}

			var lngFormOffsetY = lngFormOffsetY_a;
			if (lngFormOffsetY == undefined)
			{
				lngFormOffsetY = m_FORMOFFSETY;
			}

			var blnInvisible = m_objThis.element(strLocatorFrom_a, '.gb-form').hasClass('gb-invisible');

            if(varParameters_a !== undefined)
            {
                if(varParameters_a.containerid !== undefined && varParameters_a.containerid.length > 0)
                {
                    strContainer = varParameters_a.containerid;
                    blnHasParentContainer = true;
                    console.log('container id is : ' + strContainer);
                }
            }
                        
			// create form layout HTML
			if (blnInvisible === true)
			{
				// invisible forms are useful for applets we don't want to see
				strHTML = '<div id="' + strFormID_a + '" class="gb-invisible">' + m_objThis.element(strLocatorFrom_a).html() + '</div>';
			}
			else
			{
				var strFormBody = '';
				if (blnFormify)
				{
					// convert client-facing into a form
					strFormBody = m_objThis.element(strLocatorFrom_a, '.wr-body').html();
					if (strFormBody == undefined)
					{
						strFormBody = m_objThis.element(strLocatorFrom_a).html();
						strHTML = '<div id="' + strFormID_a + '" class="gb-forminstance">' + strFormBody + '</div>';
					}
					else
					{
						strHTML = '<div id="' + strFormID_a + '" class="gb-forminstance ">';
							strHTML += '<div class="gb-form gi-background-form gs-form-standard gs-shadow" style="width:' + m_DEFAULTFORMWIDTH + 'px; height:' + m_DEFAULTFORMHEIGHT + 'px;">';
								strHTML += strFormBody;
							strHTML += '</div>';
						strHTML += '</div>';
					}
				}
				else
				{
					strFormBody = m_objThis.element(strLocatorFrom_a).html();
					strHTML = '<div id="' + strFormID_a + '" class="gb-forminstance">' + strFormBody + '</div>';
				}
			}

			// add form layout to DOM
            //m_objThis.element('#' + m_objArgs.container).append(strHTML);
            
            if(blnHasParentContainer)
            {
                m_objThis.element('#' + strContainer, '.ge-container-panel-content').append('<div style="position:relative">' +  strHTML + '</div>');
            }
            else
            {
			    m_objThis.element('#' + strContainer).append(strHTML);
            }

			if (blnFormify)
			{
				m_objThis.element('#' + strFormID_a, '.gb-notformifiedonly').remove();
				m_objThis.element('#' + strFormID_a, '.gb-panel-resize').css('overflow-x', 'hidden');	// to check why is it not gb-hidden?
				m_objThis.element('#' + strFormID_a, '.gb-panel-resize').css('overflow-y', 'auto');
			}
			else
			{
				m_objThis.element('#' + strFormID_a, '.gb-formifiedonly').remove();
			}
			
			// cater for the form heading
			if (blnFormTitlePanel_a)
			{
				m_objThis.element('#' + strFormID_a, '.gb-form').prepend('<div class="gs-to768"><div style="height:50px;"></div></div><div class="gs-from768"><div style="height:50px;"></div></div>');
			}
			
			fixDynamicImages(strFormID_a);

			if (blnCentre_a === undefined)
			{
				blnCentre_a = false;
			}

			var intLeft = 0;
			var intTop = m_intCanvasTop;
			if (blnTaskbar_a)
			{
				doNothing();
			}
			else if (blnCentre_a && m_objThis.hasCapability('viewport'))
			{
				intLeft = (m_objThis.getViewPort().width * 0.5) - (m_objThis.element('#' + strFormID_a, '.gb-form').width() * 0.5);
				intTop = m_intCanvasTop;
			}
			else
			{
				if (blnForceTopOfViewport)
				{
					intLeft = 0;
					intTop = 0;
				}
				else
				{
					// form open staggering logic
					if (m_objThis.isMDI() && (m_intNewFormOffsetX === 0))
					{
						m_intNewFormOffsetX = m_MENUFORMWIDTH;
					}
					
					m_intNewFormOffsetX += lngFormOffsetX;

					if (m_objThis.isMDI())
					{
						if (m_intNewFormOffsetX > (m_MENUFORMWIDTH + m_FORMOFFSETMAXX))
						{
							m_intNewFormOffsetX = m_MENUFORMWIDTH;
						}
						
						m_intNewFormOffsetY += lngFormOffsetY;
						if (m_intNewFormOffsetY > (m_FORMOFFSETMAXVIEWPORTYPERCENT * m_objThis.getViewPort().height / 100))
						{
							m_intNewFormOffsetY = 0;
						}
					}
					else
					{
						m_intNewFormOffsetX = 0;
						m_intNewFormOffsetY = 0;
					}

					// add the scroll bar positions
					intLeft = m_intNewFormOffsetX + $(document).scrollLeft();
					intTop = m_intCanvasTop + m_intNewFormOffsetY + $(document).scrollTop();
				}
			}

			// form sizing
			if (m_objThis.element('#' + strFormID_a).hasClass('gb-invisible') === true)
			{
				m_objThis.element('#' + strFormID_a).css('position', 'absolute').css('top', 0).css('left', 0).css('width', 0).css('height', 0);
				m_objThis.element('#' + strFormID_a, '.gb-form').css('position', 'absolute');
			}
			else if (m_objThis.element('#' + strFormID_a).hasClass('gs-form-standard') === true)
			{
				m_objThis.element('#' + strFormID_a).css('position', 'absolute').css('top', 0).css('left', 0).css('width', 0).css('height', 0);
				if (blnResizable_a)
				{
					m_objThis.element('#' + strFormID_a, '.gs-form-standard').css('position', 'absolute').css('width', m_DEFAULTFORMWIDTH + 'px').css('height', m_DEFAULTFORMHEIGHT + 'px');
				}
				formToFrontInternal('#' + strFormID_a, blnModal_a);
			}
			else if (m_objThis.element('#' + strFormID_a).hasClass('gs-form-headerless') === true)
			{
				m_objThis.element('#' + strFormID_a).css('position', 'absolute').css('top', 0).css('left', 0).css('width', 0).css('height', 0);
				if (blnResizable_a)
				{
					m_objThis.element('#' + strFormID_a, '.gs-form-standard').css('position', 'absolute').css('width', m_DEFAULTFORMWIDTH + 'px').css('height', m_DEFAULTFORMHEIGHT + 'px');
				}
				formToFrontInternal('#' + strFormID_a, blnModal_a);
			}
			else
			{
				// note: non-resizable might mean docked or default form size
				//m_objThis.element('#' + strFormID_a).css('position', 'absolute').css('top', 0).css('left', 0).css('width', '99%').css('height', 0);
				m_objThis.element('#' + strFormID_a).css('position', 'absolute').css('top', 0).css('left', 0).css('width', '100%').css('height', 0);
				m_objThis.element('#' + strFormID_a, '.gb-form').css('position', 'absolute').css('top', intTop).css('left', intLeft);

				if (blnResizable_a)
				{
					var intWidth = 0;
					var intHeight = 0;
					var strDesktopRegion = strDesktopRegion_a;
					
					if (!allowFormXPlacement())
					{
						strDesktopRegion = str_replace(strDesktopRegion, "L", "");
						strDesktopRegion = str_replace(strDesktopRegion, "R", "");
					}
					if (!allowFormYPlacement())
					{
						strDesktopRegion = str_replace(strDesktopRegion, "T", "");
						strDesktopRegion = str_replace(strDesktopRegion, "B", "");
					}
					
					if ((strDesktopRegion.length === 0) && (strDesktopRegion_a.length > 0))
					{
						// if we have dynamically modified our desktop regions then make the form FS if there is no valid dividing of regions
						strDesktopRegion = "FS";
					}
				
					if (strDesktopRegion == "TL")
					{
						intTop = m_objThis.getCanvasTop();
						intLeft = m_objThis.getCanvasLeft();
						intWidth = m_objThis.getCanvasWidth() * 0.5;
						intHeight = m_objThis.getCanvasHeight() * 0.5;
					}
					else if (strDesktopRegion == "TR")
					{
						intTop = m_objThis.getCanvasTop();
						intLeft = m_objThis.getCanvasLeft() + m_objThis.getCanvasWidth() * 0.5;
						intWidth = m_objThis.getCanvasWidth() * 0.5;
						intHeight = m_objThis.getCanvasHeight() * 0.5;
					}
					else if (strDesktopRegion == "BL")
					{
						intTop = m_objThis.getCanvasTop() + m_objThis.getCanvasHeight() * 0.5;
						intLeft = m_objThis.getCanvasLeft();
						intWidth = m_objThis.getCanvasWidth() * 0.5;
						intHeight = m_objThis.getCanvasHeight() * 0.5;
					}
					else if (strDesktopRegion == "BR")
					{
						intTop = m_objThis.getCanvasTop() + m_objThis.getCanvasHeight() * 0.5;
						intLeft = m_objThis.getCanvasLeft() + m_objThis.getCanvasWidth() * 0.5;
						intWidth = m_objThis.getCanvasWidth() * 0.5;
						intHeight = m_objThis.getCanvasHeight() * 0.5;
					}
					else if (strDesktopRegion == "T")
					{
						intTop = m_objThis.getCanvasTop();
						intLeft = m_objThis.getCanvasLeft();
						intWidth = m_objThis.getCanvasWidth();
						intHeight = m_objThis.getCanvasHeight() * 0.5;
					}
					else if (strDesktopRegion == "B")
					{
						intTop = m_objThis.getCanvasTop() + m_objThis.getCanvasHeight() * 0.5;
						intLeft = m_objThis.getCanvasLeft();
						intWidth = m_objThis.getCanvasWidth();
						intHeight = m_objThis.getCanvasHeight() * 0.5;
					}
					else if (strDesktopRegion == "L")
					{
						intTop = m_objThis.getCanvasTop();
						intLeft = m_objThis.getCanvasLeft();
						intWidth = m_objThis.getCanvasWidth() * 0.5;
						intHeight = m_objThis.getCanvasHeight();
					}
					else if (strDesktopRegion == "R")
					{
						intTop = m_objThis.getCanvasTop();
						intLeft = m_objThis.getCanvasLeft() + m_objThis.getCanvasWidth()  * 0.5;
						intWidth = m_objThis.getCanvasWidth() * 0.5;
						intHeight = m_objThis.getCanvasHeight();
					}
					else if (strDesktopRegion == "FS")
					{
						intTop = m_objThis.getCanvasTop();
						intLeft = m_objThis.getCanvasLeft();
						intWidth = m_objThis.getCanvasWidth();
						intHeight = m_objThis.getCanvasHeight();
					}
					else if (strDesktopRegion == "C")
					{
						// centre within Canvas
						//intTop = m_objThis.getCanvasTop() + m_objThis.getCanvasHeight() * 0.1;
						//intLeft = m_objThis.getCanvasLeft() + m_objThis.getCanvasWidth() * 0.1;
						//intWidth = m_objThis.getCanvasWidth() * 0.8;
						//intHeight = m_objThis.getCanvasHeight() * 0.8;
						
						// centre within ViewPort
						intTop = m_objThis.getViewPort().height * 0.1;
						intLeft = m_objThis.getViewPort().width * 0.1;
						intWidth = m_objThis.getViewPort().width * 0.8;
						intHeight = m_objThis.getViewPort().height * 0.8;
					}
					else
					{
						intWidth = m_DEFAULTFORMWIDTH;
						intHeight = m_DEFAULTFORMHEIGHT;
					}
					
					m_objThis.element('#' + strFormID_a, '.gs-form-standard').css('position', 'absolute').css('top', intTop).css('left', intLeft).css('width', intWidth + 'px').css('height', intHeight + 'px');	
				}
								
				formToFrontInternal('#' + strFormID_a, blnModal_a);
			}
		}

		function fixDynamicImages(strFormID_a)
		{
			m_objThis.element('#' + strFormID_a, 'img').each(function (intIndex_a, objElement_a)
			{
				try
				{
					var strSrc = $(objElement_a).attr('dynamicsrc');
					if (strSrc.length > 0)
					{
						strSrc = str_replace(strSrc, '%DYNAMIC_BRANDING%', DYNAMIC_BRANDING);
						strSrc = str_replace(strSrc, '%DYNAMIC_APP_DIR_URL%', DYNAMIC_APP_DIR_URL);
						$(objElement_a).attr('src', strSrc);
					}
				}
				catch (err)
				{
					doNothing();
				}
			}
			);
		}

		function getDependencies(arrDependencies_a, strID_a)
		{
			var objResult = [];
			var intI = 0;

			processArray(arrDependencies_a, function (objDependency_a)
			{
				if ((objDependency_a.moduleid == strID_a) && (objDependency_a.loaded === false))
				{
					objResult[intI] = objDependency_a;
					intI++;
				}
			}
			);

			return objResult;
		}

		// valid types: 'js', 'html'
		function lazyLoad(strID_a, strURL_a, strType_a, cbSuccess_a)
		{
			function lazyLoadError()
			{
				m_objThis.transmissionIndicator(false);
				if ($.isFunction(cbSuccess_a))
				{
					cbSuccess_a(false);
				}
			}

			function lazyLoadSuccess()
			{
				m_objThis.transmissionIndicator(false);
				if ($.isFunction(cbSuccess_a))
				{
					cbSuccess_a(true);
				}
			}

			m_objThis.transmissionIndicator(true);
			if (strType_a === 'js')
			{
				$.ajax(
				{
					url : strURL_a,
					dataType : 'script',
					cache : true,
					success : lazyLoadSuccess,
					//complete: function(objResponse_a)
					//{
						//m_intBytes += objResponse_a.responseText.length;
					//},
					error : lazyLoadError
				}
				);
			}
			else if (strType_a === 'html')
			{
				var strTemplateName = 'lazy_' + strID_a;
				var strForm = $('#' + strTemplateName).html();
				if ((strForm === undefined) || (strForm.length === 0))
				{
					$('#ge-lazyload-container').append('<div id="' + strTemplateName + '" class="gb-hidden"></div>');
					$('#' + strTemplateName).load(strURL_a, function ()
					{
						logDebug('Lazy Load Success: ' + strID_a);
						lazyLoadSuccess();
					}
					);
				}
				else
				{
					lazyLoadSuccess();
				}
			}
		}

		// handles sequencial loading of asynchronous stuff
		this.lazyLoadDependencies = function (arrDependencies_a, strID_a, cbSuccess_a)
		{
			var objDependencies = getDependencies(arrDependencies_a, strID_a);
			var intDependency = 0;
			var intDependencyCount = objDependencies.length;
			var intProgressIconX = 0;
			var intProgressPercentage = 0;

			if (objDependencies.length === 0)
			{
				cbSuccess_a(true);
			}
			else
			{
				function lazyLoadDependency(objDependency_a, cbNext_a)
				{
					var strSource = objDependency_a.dependency;
					var strDebug = objDependency_a.debug;
					if (strDebug === undefined)
					{
						strDebug = '';
					}

					if ((DEBUG_SOURCE === 'TRUE') && (strDebug.length > 0))
					{
						strSource = strDebug;
					}

					lazyLoad('', strSource, objDependency_a.type, function (blnSuccess_a)
					{
						if (blnSuccess_a)
						{
							objDependency_a.loaded = true;
						}
						else
						{
							alert('lazy load failed: ' + strSource);
							doException('lazyLoadDependencies', 'failed to load ' + strSource);
						}
						cbNext_a();
					}
					);
				}

				var strColour = m_objArgs.progresscolour;
				if (strColour === undefined) { strColour = 'white'; }
				function doNext()
				{
					if (objDependencies.length > 0)
					{
						intProgressPercentage = parseInt((intDependency / intDependencyCount) * 100, 10);
						intDependency++;

						if (m_objArgs.percentage != undefined)
						{
							$('#' + m_objArgs.percentage).html(intProgressPercentage + '%');
						}

						// move progressicon
						if (m_objArgs.progress != undefined)
						{
							intProgressIconX = (m_objThis.getViewPort().width / 100 * intProgressPercentage);
							
							//$('#' + m_objArgs.progress).css({ "margin-left": intProgressIconX});
							$('#' + m_objArgs.progress).css({ 'width': intProgressIconX} );
							$('#' + m_objArgs.progress).css({ 'background-color': strColour });
							$('#' + m_objArgs.percentage).css({ 'color': strColour });
						}
						
						var objDependency = objDependencies.shift();
						lazyLoadDependency(objDependency, doNext);
					}
					else
					{
						if (m_objArgs.percentage != undefined)
						{
							$('#' + m_objArgs.percentage).html('100%');
						}

						// finished, so hide the progressicon
						if (m_objArgs.progress != undefined)
						{
							intProgressIconX = m_objThis.getViewPort().width;
							$('#' + m_objArgs.progress).css({ 'width': intProgressIconX} );
							$('#' + m_objArgs.progress).css({ 'background-color': strColour });
							$('#' + m_objArgs.percentage).css({ 'color': strColour });
						}

						cbSuccess_a(true);
					}
				}

				doNext();
			}
		};

		this.lazyLoadApp = function (strAppPath_a, cbOnLoaded_a)
		{
			var strAppPath = strAppPath_a;
			strAppPath = str_replace(strAppPath, '.js', '');	// remove the .js
			
			// first lazyload the shrunken version of the js if it exists
			lazyLoad("", strAppPath + '.z.js', 'js', function (blnSuccess_a)
			{
				if (blnSuccess_a)
				{
					doNothing();
				}
				else
				{
					lazyLoad("", strAppPath + '.js', 'js', function (blnSuccess_a)
					{
						doNothing();
					}
					);
				}
			}
			);
		};
		
		this.lazyLoadForm = function (strFullFormName_a, cbOnLoaded_a)
		{
			var strFullFormName = str_replace(strFullFormName_a, '-', '.');
			var arrFormName = strFullFormName.split('.');
			var strModule = arrFormName[0];
			var strFormName = arrFormName[1];
			var strModulePath = DYNAMIC_APP_DIR_URL + 'modules/';

			var strID = str_replace(strFullFormName, '.', '_');

			m_objThis.lazyLoadDependencies(g_arrDependencies, strFullFormName, function (blnSuccess_a)
			{
				if (DEBUG_SOURCE === 'TRUE')
				{
					lazyLoad(strID, strModulePath + strModule + '/' + strFormName + '.js', 'js', function (blnSuccess_a)
					{
						lazyLoad(strID, strModulePath + strModule + '/' + strFormName + '.htm', 'html', cbOnLoaded_a);
					}
					);
				}
				else
				{
					// first lazyload the shrunken version of the js if it exists
					lazyLoad(strID, strModulePath + strModule + '/' + strFormName + '.z.js', 'js', function (blnSuccess_a)
					{
						if (blnSuccess_a)
						{
							lazyLoad(strID, strModulePath + strModule + '/' + strFormName + '.z.htm', 'html', cbOnLoaded_a);
						}
						else
						{
							lazyLoad(strID, strModulePath + strModule + '/' + strFormName + '.js', 'js', function (blnSuccess_a)
							{
								lazyLoad(strID, strModulePath + strModule + '/' + strFormName + '.htm', 'html', cbOnLoaded_a);
							}
							);
						}
					}
					);
				}
			}
			);
		};

		this.lazyLoadLogic = function (strEntityName_a, cbOnLoaded_a)
		{
			var strEntityName = strEntityName_a.toLowerCase();
			var strModule = "entitylogic";
			var strModulePath = DYNAMIC_APP_DIR_URL + 'modules/';

			if (DEBUG_SOURCE === 'TRUE')
			{
				lazyLoad("", strModulePath + strModule + '/' + strEntityName + '.js', 'js', cbOnLoaded_a);
			}
			else
			{
				// first lazyload the shrunken version of the js if it exists
				lazyLoad("", strModulePath + strModule + '/' + strEntityName + '.z.js', 'js', function (blnSuccess_a)
				{
					if (blnSuccess_a)
					{
						cbOnLoaded_a(false);
					}
					else
					{
						lazyLoad("", strModulePath + strModule + '/' + strEntityName + '.js', 'js', cbOnLoaded_a);
					}
				}
				);
			}
		};

		this.massageClassName = function(strSectionName_a, strPName_a)
		{
			var strResult = $.trim(strSectionName_a + '_' + strPName_a);

			strResult = str_replace(strResult, ' ', '');
			strResult = str_replace(strResult, '<', '');
			strResult = str_replace(strResult, '>', '');
			strResult = str_replace(strResult, '/', '');
			strResult = str_replace(strResult, '-', '');
			strResult = str_replace(strResult, '&', '');
			strResult = str_replace(strResult, "'", '');
			strResult = str_replace(strResult, '"', '');

			return strResult;
		};

		// ====================================================================================
		// PROPERTIES =========================================================================

		function findProperty(strKey_a)
		{
			var objResult;

			m_objThis.processArray(m_objProperties, function (objProperty_a)
			{
				if (objProperty_a.key == strKey_a)
				{
					objResult = objProperty_a;
				}
			}
			);

			return objResult;
		}

		this.getProperty = function (strKey_a)
		{
			var objResult;

			m_objThis.processArray(m_objProperties, function (objProperty_a)
			{
				if (objProperty_a.key == strKey_a)
				{
					objResult = objProperty_a.value;
				}
			}
			);

			return objResult;
		};

		this.setProperty = function (strKey_a, objValue_a)
		{
			var objProperty = findProperty(strKey_a);

			if (objProperty === undefined)
			{
				objProperty =
				{
					key : strKey_a,
					value : objValue_a
				};
				m_objProperties.push(objProperty);
				logDebug('property added: ' + objProperty.key);
			}
			else
			{
				objProperty.value = objValue_a;
				logDebug('property changed: ' + objProperty.key);
			}
			
			//if (strKey_a === 'ismdi')
			//{
				//initialiseScrollbars();
			//}
		};

		// ====================================================================================
		// SERVER EVENTS ======================================================================

		function getServerEventBroadcastFormID(strEventQueue_a) //, strEventMessage_a)
		{
			var strResult = '';

			processArray(m_objRegisteredServerEvents, function (objServerEvent_a)
			{
				if ((objServerEvent_a.eventqueue == strEventQueue_a)) // && (objServerEvent_a.event == strEventMessage_a))
				{
					strResult = objServerEvent_a.broadcastformid;
					return true;
				}
			}
			);

			return strResult;
		}
				
		function processSystemEvents(strQueue_a, strMessage_a, objJSON_a)
		{
			if ((strQueue_a === 'formqueue') && (strMessage_a === 'showform'))
			{
				m_objThis.showForm(objJSON_a.formname, objJSON_a.params, objJSON_a.center, objJSON_a.formoffsetx, objJSON_a.formoffsety, objJSON_a.bypassdirtycheck);
			}
		}

		function serverEventsChecked(objResponse_a)
		{
			var objJSON = {};
			processArray(objResponse_a, function (objServerEvent_a)
			{
				var strBroadcastFormID = getServerEventBroadcastFormID(objServerEvent_a.eventqueue); //, objServerEvent_a.event);
				if (strBroadcastFormID.length > 0)
				{
					objJSON = {};
					if (objServerEvent_a.eventdata.length > 0)
					{
						try
						{
							objJSON = JSON.parse(objServerEvent_a.eventdata);
						}
						catch(err)
						{
							doNothing();
						}
					}

					if (strBroadcastFormID == 'system')
					{
						// process system events
						processSystemEvents(objServerEvent_a.eventqueue, objServerEvent_a.eventname, objJSON);
					}
					else
					{
						broadcastToForm(strBroadcastFormID, objServerEvent_a.eventqueue, objServerEvent_a.eventname, objJSON);
					}
				}
				else
				{
					objJSON = JSON.parse(objServerEvent_a.eventdata);
					m_objThis.broadcast('system', objServerEvent_a.eventqueue, objServerEvent_a.eventname, objJSON);
				}
			}
			);
		}

		this.checkServerEvents = function ()
		{
			var strServerEventQueues = '';

			processArray(m_objRegisteredServerEvents, function (objServerEvent_a)
			{
				if (objServerEvent_a.enabled === true)
				{
					strServerEventQueues += objServerEvent_a.eventqueue + ' ';
				}
			}
			);

			strServerEventQueues = $.trim(strServerEventQueues);

			if (strServerEventQueues.length > 0)
			{
				var objJSON = m_objThis.ajaxRequestCreate('core_servereventscheck',
						[
							{
								"name" : "eventqueuelist",
								"value" : strServerEventQueues
							}
						]);

				//m_objThis.ajaxCall(URL_WEBSERVICE, objJSON, serverEventsChecked, function() { m_objThis.ajaxError('Cannot check server events.'); }, true);
				// note we don't want to display an error here as the connection indicator will go red instead
				m_objThis.ajaxCall(URL_WEBSERVICE, objJSON, serverEventsChecked, function ()
				{
					doNothing();
				}, function ()
				{
					doNothing();
				}, true);
			}
		};

		this.disableServerEventQueue = function (strEventQueue_a)
		{
			processArray(m_objRegisteredServerEvents, function (objServerEvent_a)
			{
				if (objServerEvent_a.eventqueue == strEventQueue_a)
				{
					objServerEvent_a.enabled = false;
					logDebug('server event disabled: ' + objServerEvent_a.eventqueue);
				}
			}
			);
		};

		this.enableServerEventQueue = function (strEventQueue_a)
		{
			processArray(m_objRegisteredServerEvents, function (objServerEvent_a)
			{
				if (objServerEvent_a.eventqueue == strEventQueue_a)
				{
					objServerEvent_a.enabled = true;
					logDebug('server event enabled: ' + objServerEvent_a.eventqueue);
				}
			}
			);
		};

		this.registerServerEvent = function (strEventQueue_a, strBroadcastFormID_a)
		{
			var strBroadcastFormID = strBroadcastFormID_a;
			if (strBroadcastFormID === undefined)
			{
				strBroadcastFormID = '';
			}

			var blnExists = false;
			processArray(m_objRegisteredServerEvents, function (objServerEvent_a)
			{
				if (objServerEvent_a.eventqueue == strEventQueue_a)
				{
					blnExists = true;
					return true;
				}
			}
			);
			
			if (!blnExists)
			{
				var objServerEvent =
				{
					eventqueue : strEventQueue_a,
					enabled : true,
					broadcastformid : strBroadcastFormID
				};
				m_objRegisteredServerEvents.push(objServerEvent);
				logDebug('server event registered: ' + objServerEvent.eventqueue);
			}
		};

		// ====================================================================================
		// TIMERS =============================================================================

		function deleteTimer(strTimerID_a)
		{
			unregisterTimer(strTimerID_a);
			m_objThis.element('#ge-timer-container').find('#' + strTimerID_a).remove();
		}

		function processTimers(cb_a)
		{
			processArray(m_objRegisteredTimers, function (objTimer_a)
			{
				if ($.isFunction(cb_a))
				{
					cb_a(objTimer_a);
				}
			}
			);
		}

		function registerTimer(strTimerID_a, objTimer_a)
		{
			var strTimerID = str_replace(strTimerID_a, '#', '');

			var objTimer =
			{
				id : strTimerID,
				timerobject : objTimer_a
			};
			m_objRegisteredTimers.push(objTimer);
			logDebug('timer registered: ' + objTimer.id);
		}

		function unregisterTimer(strTimerID_a)
		{
			var strTimerID = str_replace(strTimerID_a, '#', '');

			processTimers(function (objTimer_a)
			{
				if (objTimer_a.id == strTimerID)
				{
					objTimer_a.timerobject.stop();
					logDebug('timer unregistered: ' + objTimer_a.id);
					objTimer_a.id = 'unregistered';
					objTimer_a.timerobject = null;
				}
			}
			);

			logForms();
		}

		this.after = function (intInterval_a, cb_a)
		{
			if ($.isFunction(cb_a))
			{
				var intHandle = window.setInterval(function ()
					{
						try
						{
							cb_a();
						}
						catch(err)
						{
							doNothing();
						}
						intHandle = window.clearInterval(intHandle);
					}, intInterval_a);
			}
		};

		this.createTimer = function (fnTimer_a, intFrequency_a, blnAutoStart_a)
		{
			var strTimerID = 'timer_' + getGUID();
			var strHTML = '<div id="' + strTimerID + '" class="gb-hidden"></div>';
			m_objThis.element('#ge-timer-container').append(strHTML);

			var objTimer = null;
			
			try
			{
				objTimer = $.timer(onTimer, intFrequency_a, blnAutoStart_a);
				registerTimer(strTimerID, objTimer);
			}
			catch(err)
			{
				m_objThis.dialogAlert('Timer Error: ' + err, doNothing);
			}

			function onTimer()
			{
				if (($.isFunction(fnTimer_a)) && (intFrequency_a > 0))
				{
					objTimer.pause();
					fnTimer_a();
					objTimer.play();
				}
			}

			return strTimerID;
		};

		this.deleteTimersAll = function ()
		{
			processTimers(function (objTimer_a)
			{
				if (objTimer_a.id != 'unregistered')
				{
					deleteTimer(objTimer_a.id);
				}
			}
			);
		};

		// ====================================================================================
		// HELP ===============================================================================

		// turn into help suitable for intro
		function refactorHelp(arrHelp_a)
		{
			var arrResult = [];

			processArray(arrHelp_a, function (objHelp_a)
			{
				var objElement = objHelp_a.element;
				var strHelp = objHelp_a.help;
				var strPosition = objHelp_a.position;

				var objHelpFragment = {};
				if (objElement === undefined)
				{
					objHelpFragment =
					{
						intro : strHelp
					};
					arrResult.push(objHelpFragment);
				}
				else
				{
					if (objElement.is(":visible"))
					{
						objHelpFragment =
						{
							element : objElement[0],
							intro : strHelp,
							position : strPosition
						};
						arrResult.push(objHelpFragment);
					}
				}
			}
			);

			//alert(JSON.stringify(arrResult));
			return arrResult;
		}

		this.showHelp = function (strFormID_a, arrHelp_a, cbAfter_a)
		{
			if (m_objThis.hasCapability('help'))
			{
				function afterHelp()
				{
					restoreForms();
					if ($.isFunction(cbAfter_a))
					{
						cbAfter_a();
					}

					m_objThis.setUnloadPrompt(true);
				}

				hideFormsExcept(strFormID_a);
				var arrHelp = refactorHelp(arrHelp_a);

				m_objThis.setUnloadPrompt(false);
				var objIntro = introJs();
				objIntro.setOptions(
				{
					skipLabel : 'Close',
					steps : arrHelp
				}
				);
				objIntro.oncomplete(afterHelp);
				objIntro.onexit(afterHelp);
				objIntro.start();
			}
		};

		// ====================================================================================
		// TIPS ===============================================================================

		this.enableTips = function (blnEnable_a)
		{
			m_blnTipsEnabled = blnEnable_a;
		};

		// note: importance is essentially a weighting to style the tooltip in a different way based on importance, 1 being most important, 2 is less important, 3 is for notifications and cannot be disabled
		this.showTip = function (strFormID_a, strLocator_a, strTipLocation_a, strTargetLocation_a, intImportance_a, strMessage_a, blnPopup_a, blnReady_a)
		{
			var blnPopup = blnPopup_a;
			if (blnPopup === undefined)
			{
				blnPopup = false;
			}

			var blnReady = blnReady_a;
			if (blnReady === undefined)
			{
				blnReady = false;
			}
            
			var objTarget = m_objThis.element(strFormID_a, strLocator_a);

			if (m_objThis.hasCapability('tips'))
			{     
				var strStyle = 'qtip-green qtip-shadow qtip-rounded';
				if (intImportance_a === 1)
				{
					strStyle = 'qtip-red qtip-shadow qtip-rounded';
				}
				else if (intImportance_a === 2)
				{
					strStyle = 'qtip-blue qtip-shadow qtip-rounded';
				}

				if ((m_blnTipsEnabled === true) || (intImportance_a === 3))
				{
					var objTip;

					if (blnPopup)
					{
						objTip =
						{
							content :
							{
								text : strMessage_a
							},
							hide :
							{
								fixed : true,
								leave : false,
								event : false,
								inactive : 2000
							},
							position :
							{
								my : strTipLocation_a,
								at : strTargetLocation_a,
								target : objTarget
							},
							show :
							{
								when : false,
								ready : true
							},
							style :
							{
								classes : strStyle
							}
						};

						try
						{
                            objTarget.qtip(objTip);
						}
						catch (err)
						{
							doNothing();
						}
					}
					else
					{

						objTip =
						{
							content :
							{
								text : strMessage_a
							},
							hide :
							{
								fixed : true,
								leave : false,
								xevent : false,
								event : 'mouseleave click',
								xinactive : 2000
							},
							position :
							{
								my : strTipLocation_a,
								at : strTargetLocation_a,
								target : objTarget
							},
							show :
							{
								when : false,
								ready : blnReady,
								event : 'mouseover'
							},
							style :
							{
								classes : strStyle
							}
						};

						try
						{
                            objTarget.qtip(objTip);
						}
						catch (err)
						{
							doNothing();
						}
					}
				}
			}
		};

		// ====================================================================================
		// FORM PARAMETERS ====================================================================

		// should use encodeURIComponent (this function creates urls with parameters that are NOT anchors)
		function addURLParameter(strURL_a, strNewKey_a, strNewValue_a)
		{
			var strSite = strURL_a;
			var strSeparator = '?';
			var strOldParameters = '';
			var intI = 0;

			if (strSite.indexOf('#') === 0)
			{
				strSeparator = '&';
			}

			// get our anchor
			intI = strSite.indexOf('?');
			if (intI != -1)
			{
				strSeparator = '&';
			}

			// get our parameters
			if (strOldParameters.length > 0)
			{
				var arrParameters = strOldParameters.split('&');
				var strNewParameters = '';
				for (intI = 0; intI < arrParameters.length; intI++)
				{
					var arrParameter = arrParameters[intI].split('=');

					if (arrParameter[0] == strNewKey_a)
					{
						continue;
					}

					if (strNewParameters.length > 0)
					{
						strNewParameters += '&';
					}
					strNewParameters += arrParameter[0] + '=' + arrParameter[1];
				}
				//strOldParameters = strNewParameters + '&' + strNewKey_a + '=' + encodeURIComponent(strNewValue_a); // parameters should already be URL encoded
				strOldParameters = strNewParameters + '&' + strNewKey_a + '=' + strNewValue_a;
			}
			else
			{
				//strOldParameters = strNewKey_a + '=' + encodeURIComponent(strNewValue_a); // parameters should already be URL encoded
				strOldParameters = strNewKey_a + '=' + strNewValue_a;
			}

			return strSite + strSeparator + strOldParameters;
		}

		this.getStrippedAnchor = function (strAnchor_a)
		{
			var strResult = strAnchor_a;
			var intI = 0;

			// get our anchor
			intI = strResult.indexOf('&');
			if (intI != -1)
			{
				strResult = strResult.substring(0, intI);
			}

			return strResult;
		};

		this.getAncorParameters = function (strAnchor_a)
		{
			var strResult = '';
			var strAnchor = strAnchor_a;
			var intI = 0;

			// get our anchor
			intI = strAnchor.indexOf('&');
			if (intI != -1)
			{
				strResult = strAnchor.substring(intI + 1);
			}

			return strResult;
		};

		this.jsonToNameValueArray = function (objParameters_a)
		{
			var arrResult = [];
			
			for(var objProperty in objParameters_a)
			{
				arrResult.push({
					name : objProperty,
					value : objParameters_a[objProperty]
				});
			}
			
			return arrResult;
		};
		
		this.jsonToURL = function (objParameters_a, blnExtend_a)
		{
			var strResult = '';
			
			for(var objProperty in objParameters_a)
			{
				if ((strResult.length > 0) || blnExtend_a)
				{
					strResult += '&';
				}
				strResult += encodeURIComponent(objProperty) + '=' + encodeURIComponent(objParameters_a[objProperty]);
			}
			
			return strResult;
		};
		
		// convert url parameters to JSON
		this.urlToJSON = function (strParameters_a)
		{
			var objJSON = {};

			if (strParameters_a.length > 0)
			{
				objJSON = strParameters_a.split('&').reduce(function (prev, curr, i, arr)
					{
						var p = curr.split('=');
						prev[decodeURIComponent(p[0])] = decodeURIComponent(p[1]);
						return prev;
					}, {}

					);
			}

			return objJSON;
		};

		// ====================================================================================
		// STRING UTILS =======================================================================

		this.str_left = function (str_a, intLen_a, strAppend_a)
		{
			var strResult = '';
			var strAppend = strAppend_a;
			if (strAppend === undefined)
			{
				strAppend = '';
			}

			if (intLen_a > 0)
			{
				if (intLen_a > str_a.length)
				{
					strResult = str_a + strAppend;
				}
				else
				{
					strResult = str_a.substring(0, intLen_a) + strAppend;
				}
			}

			return strResult;
		};

		this.str_right = function (str_a, intLen_a)
		{
			var strResult = '';

			if (intLen_a > 0)
			{
				if (intLen_a > str_a.length)
				{
					strResult = str_a;
				}
				else
				{
					strResult = str_a.substring(str_a.length - intLen_a, str_a.length);
				}
			}

			return strResult;
		};

		this.str_quotes = function(str_a)
		{
			var intLen = 0;
			var strResult = str_a;
			
			intLen = strResult.length;
			if (intLen >= 1)
			{
				if ((m_objThis.str_left(strResult, 1) === "'") || (m_objThis.str_left(strResult, 1) === '"'))
				{
					strResult = m_objThis.str_right(strResult, intLen - 1);
				}
			}
			
			intLen = strResult.length;
			if (intLen >= 1)
			{
				if ((m_objThis.str_right(strResult, 1) === "'") || (m_objThis.str_right(strResult, 1) === '"'))
				{
					strResult = m_objThis.str_left(strResult, intLen - 1);
				}
			}
			
			return strResult;
		};

		this.toBoolean = function (str_a)
		{
			var str = str_a + '';
			str = str.toUpperCase();

			return ((str === '1') || (str === 'Y') || (str === 'YES') || (str === 'T') || (str === 'TRUE'));
		};

		// ====================================================================================
		// GENERAL UTILS ======================================================================

		// best effort conversion of a date to ISO format
		this.convertDateToISO = function(strFieldName_a, strDate_a, cb_a)
		{
			var strResult = '';
			
			var strDate = str_replace(strDate_a, '/', '-');
			var arrParts = strDate.split('-');

			if (arrParts.length === 3)
			{
				//alert(arrParts[0].length + ", " + arrParts[1].length + ", " + arrParts[2].length);
				if ((arrParts[0].length === 2) && (arrParts[1].length === 2) && (arrParts[2].length === 4))	// dd-mm-yyyy
				{
					// convert the date
					var dteResult = new Date();
					dteResult.setMonth(parseInt(arrParts[1], 10) - 1);
					dteResult.setDate(parseInt(arrParts[0], 10));
					dteResult.setFullYear(parseInt(arrParts[2], 10));
		
					strResult = $.datepicker.formatDate('yy-mm-dd', dteResult);
				}
				else if ((arrParts[0].length === 4) && (arrParts[1].length === 2) && (arrParts[2].length === 2))	// yyyy-mm-dd
				{
					// valid
					strResult = strDate_a;
				}
				else	
				{
					// invalid
					doNothing();
				}
			}
			
			if (strDate_a !== strResult)
			{
				if ($.isFunction(cb_a))
				{
					cb_a(strFieldName_a, strDate_a, strResult);
				}
			}
					
			return strResult;
		};
		
		this.convertISOToDate = function(strDate_a)
		{
			var dteResult = new Date();
			
			var strDateTemp = str_replace(strDate_a, '/', '-');
			var arrParts = strDateTemp.split('-');

			if (arrParts.length === 3)
			{
				//alert(arrParts[0].length + ", " + arrParts[1].length + ", " + arrParts[2].length);
				if ((arrParts[0].length === 4) && (arrParts[1].length === 2) && (arrParts[2].length === 2))	// yyyy-mm-dd
				{
					// valid
					dteResult.setMonth(parseInt(arrParts[1], 10) - 1);
					dteResult.setDate(parseInt(arrParts[2], 10));
					dteResult.setFullYear(parseInt(arrParts[0], 10));
					dteResult.setHours(0, 0, 0);
				}
				else	
				{
					// invalid
					doNothing();
				}
			}
			
			return dteResult;
		};
		
		this.copyArray = function (arr_a)
		{
			return JSON.parse(JSON.stringify(arr_a));
		};

		// convert form yyyy-mm-dd to the specified format
		this.dateFromISO = function (strDateFormat_a, strDate_a)
		{
			var strDateFormat = strDateFormat_a.toLowerCase();
			var strDate = '';
			var strResult = '';

			if (strDate_a.length > 0)
			{
				strDate = strDate_a;
			}
			else
			{
				strDate = $.datepicker.formatDate('yy-mm-dd', new Date());
			}

			if ((strDateFormat === 'dd/mm/yy') || (strDateFormat === 'dd/mm/yyyy'))
			{
				strResult = strDate.substr(8, 2) + '/' + strDate.substr(5, 2) + '/' + strDate.substr(0, 4);
			}
			else if ((strDateFormat === 'dd-mm-yy') || (strDateFormat === 'dd-mm-yyyy'))
			{
				strResult = strDate.substr(8, 2) + '-' + strDate.substr(5, 2) + '-' + strDate.substr(0, 4);
			}
			else if ((strDateFormat === 'mm/dd/yy') || (strDateFormat === 'mm/dd/yyyy'))
			{
				strResult = strDate.substr(5, 2) + '/' + strDate.substr(8, 2) + '/' + strDate.substr(0, 4);
			}
			else if ((strDateFormat === 'mm-dd-yy') || (strDateFormat === 'mm-dd-yyyy'))
			{
				strResult = strDate.substr(5, 2) + '-' + strDate.substr(8, 2) + '-' + strDate.substr(0, 4);
			}
			else if ((strDateFormat === 'yy/mm/dd') || (strDateFormat === 'yyyy/mm/dd'))
			{
				strResult = strDate.substr(0, 4) + '/' + strDate.substr(5, 2) + '/' + strDate.substr(8, 2);
			}
			else if ((strDateFormat === 'yy-mm-dd') || (strDateFormat === 'yyyy-mm-dd'))
			{
				strResult = strDate.substr(0, 4) + '-' + strDate.substr(5, 2) + '-' + strDate.substr(8, 2);
			}

			return strResult;
		};

		// convert form the specified format to yyyy-mm-dd
		this.dateToISO = function (strDateFormat, strDate_a)
		{
			var strResult = '1900-00-00';

			if ((strDateFormat === 'dd/mm/yy') || (strDateFormat === 'dd-mm-yy'))
			{
				strResult = strDate_a.substr(6, 4) + '-' + strDate_a.substr(3, 2) + '-' + strDate_a.substr(0, 2);
			}
			else if ((strDateFormat === 'mm/dd/yy') || (strDateFormat === 'mm-dd-yy'))
			{
				strResult = strDate_a.substr(6, 4) + '-' + strDate_a.substr(0, 2) + '-' + strDate_a.substr(3, 2);
			}
			else if ((strDateFormat === 'yy/mm/dd') || (strDateFormat === 'yy-mm-dd'))
			{
				strResult = strDate_a.substr(0, 4) + '-' + strDate_a.substr(5, 2) + '-' + strDate_a.substr(8, 2);
			}

			return strResult;
		};

		// validation
		this.isInt = function (str_a)
		{
			var blnResult = false;

			if (!isNaN(parseInt(str_a, 10)))
			{
				if (parseInt(str_a, 10) == parseFloat(str_a))
				{
					blnResult = true;
				}
			}

			return blnResult;
		};

		this.isIntRange = function (str_a, intMin_a, intMax_a)
		{
			var blnResult = false;

			if (m_objThis.isInt(str_a))
			{
				var intStr = parseInt(str_a, 10);
				if ((intStr >= intMin_a) && (intStr <= intMax_a))
				{
					blnResult = true;
				}
			}

			return blnResult;
		};

		this.isFloat = function (str_a)
		{
			var blnResult = false;

			if (!isNaN(parseFloat(str_a, 10)))
			{
				blnResult = true;
			}

			return blnResult;
		};

		this.isFloatRange = function (str_a, fltMin_a, fltMax_a)
		{
			var blnResult = false;

			if (m_objThis.isFloat(str_a))
			{
				var fltStr = parseFloat(str_a, 10);
				if ((fltStr >= fltMin_a) && (fltStr <= fltMax_a))
				{
					blnResult = true;
				}
			}

			return blnResult;
		};

		this.isEmailAddress = function (str_a)
		{
			var objRE = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return objRE.test(str_a);
		};

		this.formatMoney = function (str_a, blnShowSymbol_a, blnShowCents_a)
		{
            var blnShowCents = true;
            var strResult = '';
            var floatAmount = parseFloat(str_a);

            	floatAmount = floatAmount.toFixed(2);

            if(blnShowCents_a !== undefined)
            {
                blnShowCents = blnShowCents_a;
            }
            
            if(blnShowCents)
            {	
            	var floatDecimalAmount =  (floatAmount % 1).toFixed(2);

            	if(floatDecimalAmount > 0)
            	{           	
                	strResult = m_objThis.roundFloat(floatAmount, 2);
                }
                else
                {
                	strResult = m_objThis.roundFloat(floatAmount, 0);	
                }
            }
            else
            {
                strResult = m_objThis.roundFloat(floatAmount, 0);
            }
            
			//(Math.round(objOrderItem_a.priceinctax*Math.pow(10,2))/Math.pow(10,2)).toFixed(2)
			if (blnShowSymbol_a)
			{
				strResult = '$' + strResult;
			}
            
			return strResult;
		};

		this.roundInt = function (str_a)
		{
			var strResult = '';

			if (m_objThis.isInt(str_a))
			{
				var intStr = parseInt(str_a, 10);
				strResult = intStr + '';
			}

			return strResult;
		};

		// found a float to the specified number of decimal places
		this.roundFloat = function (str_a, intDP_a)
		{
			var strResult = '';

			if (m_objThis.isFloat(str_a))
			{
				var fltStr = parseFloat(str_a);
				var intFactor = Math.pow(10, intDP_a);
				fltStr = fltStr * intFactor;
				fltStr = parseInt(fltStr, 10);
				fltStr = fltStr / intFactor;
				strResult = fltStr.toFixed(intDP_a) + '';
			}

			return strResult;
		};

		// return a new grid row state based on an existing
		this.rowStateGet = function (strCurrentState_a, strAction_a)
		{
			var strResult = strCurrentState_a;

			if (strCurrentState_a == 'RETRIEVED') // retrieved & untouched items
			{
				if (strAction_a == 'ADDED')
				{
					doNothing();
				}
				else if (strAction_a == 'DELETED')
				{
					strResult = 'DELETED';
				}
				else if (strAction_a == 'UPDATED')
				{
					strResult = 'UPDATED';
				}
			}
			else if (strCurrentState_a == 'DELETED') // retrieved & deleted items
			{
				if (strAction_a == 'ADDED')
				{
					strResult = 'UPDATED';
				}
				else if (strAction_a == 'DELETED')
				{
					doNothing();
				}
				else if (strAction_a == 'UPDATED')
				{
					strResult = 'UPDATED';
				}
			}
			else if (strCurrentState_a == 'UPDATED') // retrieved & updated items (may have been deleted and readded)
			{
				if (strAction_a == 'ADDED')
				{
					doNothing();
				}
				else if (strAction_a == 'DELETED')
				{
					strResult = 'DELETED';
				}
				else if (strAction_a == 'UPDATED')
				{
					doNothing();
				}
			}
			else if (strCurrentState_a == 'ADDED') // added but not yet committed
			{
				if (strAction_a == 'ADDED')
				{
					doNothing();
				}
				else if (strAction_a == 'DELETED')
				{
					doNothing();
				}
				else if (strAction_a == 'UPDATED')
				{
					doNothing();
				}
			}

			return strResult;
		};

		this.nowAU = function ()
		{
			var objDate = new Date();
			var strYear = objDate.getFullYear().toString();
			var intMonth = objDate.getMonth() + 1;
			var strMonth = intMonth.toString();
			if (strMonth.length === 1)
			{
				strMonth = '0' + strMonth;
			}
			var strDay = objDate.getDate().toString();
			if (strDay.length === 1)
			{
				strDay = '0' + strDay;
			}
			var strHour = objDate.getHours().toString();
			if (strHour.length === 1)
			{
				strHour = '0' + strHour;
			}
			var strMinute = objDate.getMinutes().toString();
			if (strMinute.length === 1)
			{
				strMinute = '0' + strMinute;
			}
			var strSecond = objDate.getSeconds().toString();
			if (strSecond.length === 1)
			{
				strSecond = '0' + strSecond;
			}
			
			var strResult = strDay + '/' + strMonth + '/' + strYear + ' ' + strHour + ':' + strMinute + ':' + strSecond;

			return strResult;
		};

		this.nowISO = function ()
		{
			var objDate = new Date();
			var strYear = objDate.getFullYear().toString();
			var intMonth = objDate.getMonth() + 1;
			var strMonth = intMonth.toString();
			if (strMonth.length === 1)
			{
				strMonth = '0' + strMonth;
			}
			var strDay = objDate.getDate().toString();
			if (strDay.length === 1)
			{
				strDay = '0' + strDay;
			}
			var strHour = objDate.getHours().toString();
			if (strHour.length === 1)
			{
				strHour = '0' + strHour;
			}
			var strMinute = objDate.getMinutes().toString();
			if (strMinute.length === 1)
			{
				strMinute = '0' + strMinute;
			}
			var strSecond = objDate.getSeconds().toString();
			if (strSecond.length === 1)
			{
				strSecond = '0' + strSecond;
			}
			
			var strResult = strYear + '-' + strMonth + '-' + strDay + ' ' + strHour + ':' + strMinute + ':' + strSecond;

			return strResult;
		};

		this.timeISO = function ()
		{
			var objDate = new Date();
			var strYear = objDate.getFullYear().toString();
			var intMonth = objDate.getMonth() + 1;
			var strMonth = intMonth.toString();
			if (strMonth.length === 1)
			{
				strMonth = '0' + strMonth;
			}
			var strDay = objDate.getDate().toString();
			if (strDay.length === 1)
			{
				strDay = '0' + strDay;
			}
			var strHour = objDate.getHours().toString();
			if (strHour.length === 1)
			{
				strHour = '0' + strHour;
			}
			var strMinute = objDate.getMinutes().toString();
			if (strMinute.length === 1)
			{
				strMinute = '0' + strMinute;
			}
			var strSecond = objDate.getSeconds().toString();
			if (strSecond.length === 1)
			{
				strSecond = '0' + strSecond;
			}
			
			var strResult = strHour + ':' + strMinute + ':' + strSecond;

			return strResult;
		};

		this.todayISO = function ()
		{
			var objDate = new Date();
			var strYear = objDate.getFullYear().toString();
			var intMonth = objDate.getMonth() + 1;
			var strMonth = intMonth.toString();
			if (strMonth.length === 1)
			{
				strMonth = '0' + strMonth;
			}
			var strDay = objDate.getDate().toString();
			if (strDay.length === 1)
			{
				strDay = '0' + strDay;
			}
			var strResult = strYear + '-' + strMonth + '-' + strDay;

			return strResult;
		};

		return this;
	};

	// ====================================================================================
	// INSTANTIATION ======================================================================

	// point the os entry point to the API version
	os = osEntry;

	// when document is ready, then call the callback
	try
	{
		if (m_objArgs.progress != undefined)
		{
			$('#' + m_objArgs.progress).show();
		}

		if (m_objArgs.percentage != undefined)
		{
			$('#' + m_objArgs.percentage).show();
		}

		var strDependencies = os().getArgs().dependencies;
		//m_intBytes = 0;
		os().lazyLoadDependencies(g_arrDependencies, strDependencies, function (blnSuccess_a)
		{
			os().after(500, function ()
			{
				if (m_objArgs.progress != undefined)
				{
					//$('#' + m_objArgs.progress).css({ "height": 0});
					$('#' + m_objArgs.progress).hide();
					m_objArgs.progress = undefined;
				}

				if (m_objArgs.percentage != undefined)
				{
					//$('#' + m_objArgs.percentage).css({ "height": 0});
					$('#' + m_objArgs.percentage).hide();
					m_objArgs.percentage = undefined;
				}
				
				//alert(m_intBytes);

				if (testBrowser())
				{
					// instantiate and initialise
					os().initialise();

					if ($.isFunction(cbOnReady_a))
					{
						os().initialiseViewPort();

						// ready callback
						cbOnReady_a();
						$(document).trigger('osready');
					}
				}
			}
			);
		}
		);
	}
	catch (err)
	{
		location = APP_LOGIN;
		//window.location.replace(APP_LOGIN);
	}
}

// os entry point
var os = osEntry;
