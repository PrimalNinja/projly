if (REFRESH)
{
	//location = APP_HOME;
	//alert(location.protocol + '//' + location.host + location.pathname);
	var strSearch = location.search;
	strSearch = removeURLParameter(strSearch, 'loggedin');
	strSearch = removeURLParameter(strSearch, 'theme');
	//alert("1:" + location.protocol + '//' + location.host + location.pathname + strSearch);
	location = location.protocol + '//' + location.host + location.pathname + strSearch;
}
else
{
	$(document).ready(function()
	{
		var blnKeepAlive = (KEEPALIVE === 'TRUE');
		var blnStopHashChange = (STOP_HASHCHANGE === 'TRUE');
		var blnForceVerticalScroll = false;
		
		if (LOGGEDIN === 'TRUE')
		{
			os({ "progresscolour": PROGRESSCOLOUR, "progress": "ge-progress", "percentage": "ge-percentage", "container": "ge-form-container", "orientation": Window_onOrientationChange, "dependencies": "osfull", "keepalive": blnKeepAlive, "unloadprompt": true }, function()
			{
				//alert("2:" + SECURITY_TOKEN);
				initialiseUserInfo();
			});
		}
		else
		{
			os({ "progresscolour": PROGRESSCOLOUR, "progress": "ge-progress", "percentage": "ge-percentage", "container": "ge-form-container", "orientation": Window_onOrientationChange, "dependencies": "osfull", "keepalive": blnKeepAlive, "unloadprompt": false }, function()
			{
				if (RESPONSEMESSAGE.length > 0)
				{
					os().dialogAlert(RESPONSEMESSAGE, doNothing);
				}
			
				//alert("3:" + SECURITY_TOKEN);
				initialiseUserInfo();
			});
		}

		function initialiseUserInfo()
		{
			if (ALLOW_STAY_LOGGEDIN === 'TRUE')
			{
				if (LOGGEDIN === 'TRUE')
				{
					getUserInfo(postLogin);
				}
				else
				{
					authenticationCheck();
				}
			}
			else
			{
				if (LOGGEDIN === 'TRUE')
				{
					getUserInfo(postLogin);
				}
				else
				{
					loginPublic();
				}
			}
		}

		function authenticatedRefresh()
		{
			var strTheme = os().getProperty('theme');
			
			if (os().hasCapability('themes') && strTheme.length > 0)
			{
				//location = APP_HOME + '?loggedin=true&theme=' + encodeURIComponent(strTheme);
				//alert("2:" + location.protocol + '//' + location.host + location.pathname + '?loggedin=true&theme=' + encodeURIComponent(strTheme));
				location = location.protocol + '//' + location.host + location.pathname + '?loggedin=true&theme=' + encodeURIComponent(strTheme);
			}
			else
			{
				postLogin();
			}
		}
		
		function authenticationCheck()
		{
			var strUserAgent = os().getAgent();
			var strBrowserCapabilities = os().getBrowserCapabilities();

			function authenticationCheck2()
			{
				getUserInfo(authenticatedRefresh);
			}
			
			var objJSON = os().ajaxRequestCreate('public_authenticationcheck',
				[
					{ "name" : "useragent", "value" : strUserAgent },
					{ "name" : "capabilities", "value" : strBrowserCapabilities }
				]);
			os().ajaxCall(URL_WEBSERVICE, objJSON, authenticationCheck2, loginPublic, doNothing, true);
		}

		function getUserInfo(cbPost_a)
		{
			function getUserInfo2(objResult_a)
			{
				userInfoFetched(cbPost_a, objResult_a);
			}
			
			// read user info
			var objJSON = os().ajaxRequestCreate('security_userinfo', []);
			os().ajaxCall(URL_WEBSERVICE, objJSON, getUserInfo2, os().ajaxError, doNothing, true);
		}

		function loginPublic()
		{
			var strClientCode = DEFAULT_ENTITY;
			var strLogin = DEFAULT_LOGIN;
			var strPassword = DEFAULT_PASSWORD;
			var strStayLoggedIn = 'N';
			var strUserAgent = os().getAgent();
			var strBrowserCapabilities = os().getBrowserCapabilities();

			function loginPublic2()
			{
				getUserInfo(postLogin);
			}
			
			var objJSON = os().ajaxRequestCreate('public_login',
				[
					{ "name" : "clientcode", "value" : strClientCode },
					{ "name" : "login", "value" : strLogin },
					{ "name" : "password", "value" : strPassword },
					{ "name" : "stayloggedin", "value" : strStayLoggedIn },
					{ "name" : "useragent", "value" : strUserAgent },
					{ "name" : "capabilities", "value" : strBrowserCapabilities }
				]);
			os().ajaxCall(URL_WEBSERVICE, objJSON, loginPublic2, os().ajaxError, doNothing, true);
		}

		function postLogin()
		{
			// register system events
			os().registerServerEvent('formqueue', 'system');
			os().enableServerEventQueue('formqueue');
			os().ajaxCall(URL_WEBSERVICE, os().ajaxRequestCreate('esb_registerbroadcaster', [{ "name" : "eventqueue", "value" : "formqueue" }]), doNothing, doNothing);
			os().ajaxCall(URL_WEBSERVICE, os().ajaxRequestCreate('esb_registerlistener', [{ "name" : "eventqueue", "value" : "formqueue" }]), doNothing, doNothing);

			if (blnStopHashChange)
			{
				doNothing();
			}
			else
			{
				$(window).hashchange(function(objEvent_a) { Window_onHashChange(objEvent_a); } );
				os().showTaskbar('core.frmTaskbar', '', false, function()
				{
					initialiseStartupItems(os(), function()
					{
						// no anchor so we must be on the landing page
						if (LOGGEDIN === 'TRUE')
						{
							os().hideLanding(PUBLIC_LANDINGPAGEID);
							//os().showLanding(PUBLIC_LANDINGPAGEID, '', true);
							//os().bindLandingForm(PUBLIC_LANDINGPAGEID, PUBLIC_LANDINGPAGE, '');
							//Window_onHashChange();
						}
						else
						{
							if (os().hasCapability('mobile'))
							{
								doNothing();
							}
							else
							{
								if ((LANDINGPAGE_VIDEOID.length > 0) && os().isModuleLoaded('widgettube') && os().hasCapability('tube'))
								{
									os().showForm('widgettube.wgtTube', { "target": "#ge-widgettube-container" });
								}
							}
				
							os().showLanding(PUBLIC_LANDINGPAGEID, '', true);
							os().bindLandingForm(PUBLIC_LANDINGPAGEID, PUBLIC_LANDINGPAGE, '');
							Window_onHashChange();
						}
					});
				});
			}
		}

		function userInfoFetched(cbPost_a, objResponse_a)
		{
			setDesktopRegions(objResponse_a.desktopregions);
			setPermissions(objResponse_a.permissions);
			setProducts(objResponse_a.products);
			os().setProperty('clientdb', objResponse_a.clientdb);
			os().setProperty('loginas', objResponse_a.loginas);
			os().setProperty('client', objResponse_a.clientid);
			os().setProperty('clientname', objResponse_a.clientname);
			os().setProperty('businessname', objResponse_a.businessname);
			os().setProperty('login', objResponse_a.login);
			os().setProperty('username', objResponse_a.username);
			os().setProperty('sysadmin', objResponse_a.sysadmin);
			os().setProperty('developer', objResponse_a.developer);
			os().setProperty('public', objResponse_a.ispublic);
			os().setProperty('batch', objResponse_a.isbatch);
			os().setProperty('default', objResponse_a.isdefault);
			os().setProperty('owner', objResponse_a.isowner);
			os().setProperty('licensed', objResponse_a.licensed);
			os().setProperty('expirydate', objResponse_a.expirydate);
			os().setProperty('expirydays', objResponse_a.expirydays);
			os().setProperty('selectedtheme', SELECTED_THEME);
			os().setProperty('isextend', false);
			os().setProperty('ismdi', objResponse_a.ismdi);

			blnForceVerticalScroll = ((TESTSCROLL == 'TRUE') && !os().hasCapability('regionscroll') && os().hasCapability('mobile'));
			os().initialiseScrollbars(blnForceVerticalScroll);

			os().setProperty('defaultcountry', objResponse_a.defaultcountry);
			os().setProperty('devicename', objResponse_a.devicename);
			os().setProperty('theme', objResponse_a.theme);
			//os().setProperty('uselocalapplet', objResponse_a.uselocalapplet);
			os().setProperty('isemployer', objResponse_a.isemployer);
			os().setProperty('isindividual', objResponse_a.isindividual);
			os().setProperty('isrto', objResponse_a.isrto);
			os().setProperty('isvalidassessor', objResponse_a.isvalidassessor);
			os().setProperty('enablebranches', objResponse_a.enablebranches);
			os().setProperty('branchid', objResponse_a.branchid);
			os().setProperty('branchname', objResponse_a.branchname);

			var blnEnableTips = os().toBoolean(objResponse_a.displaytooltips);
			os().enableTips(blnEnableTips);
			
			var blnPublic = os().toBoolean(getProperty('public'));
			if (blnPublic)
			{
				LOGGEDIN = 'FALSE';
			}
			else
			{
				LOGGEDIN = 'TRUE';
			}

			if (os().toBoolean(os().getProperty('sysadmin')))
			{
				os().setBackground('gi-sysadmin-background');
			}
			else if ((LOGGEDIN === 'FALSE') && (LANDINGPAGE_VIDEOID.length > 0) && os().isModuleLoaded('widgettube') && os().hasCapability('tube'))
			{
				doNothing();
			}
			else
			{
				os().setBackground('gi-desktop-background');
			}
			
			$(document).trigger('ospropertiesloaded');

			if ($.isFunction(cbPost_a))
			{
				cbPost_a();
			}
		}

		function Window_onHashChange(objEvent_a)
		{
			return os().hashChange(location.hash);
		}

		function Window_onOrientationChange(intOrientation_a)
		{
			os().initialiseViewPort();
		}
	});
}

function removeURLParameter(strParameters_a, strRemove_a)
{
	var strParameters = strParameters_a;
	if (strParameters.indexOf('?') === 0)
	{
		strParameters = strParameters.substr(1);
	}
	var objParameters = urlToJSONWithOmission(strParameters, strRemove_a);
	strParameters = jsonToURL(objParameters);
	
	if (strParameters.length > 0)
	{
		strParameters = '?' + strParameters.substr(1);
	}
	else
	{
		strParameters = '?uuid=' + getGUID();	// ? added to the end to prevent chrome from caching redirects, refer to here: https://superuser.com/questions/304589/how-can-i-make-chrome-stop-caching-redirects and https://bugs.chromium.org/p/chromium/issues/detail?id=91740
	}
	
	return strParameters;
}

function jsonToURL(objParameters_a)
{
	var strResult = '';
	
	for(var objProperty in objParameters_a)
	{
		strResult += '&' + encodeURIComponent(objProperty) + '=' + encodeURIComponent(objParameters_a[objProperty]);
	}
	
	return strResult;
}

// convert url parameters to JSON
function urlToJSONWithOmission(strParameters_a, strOmit_a)
{
	var objJSON = {};

	if (strParameters_a.length > 0)
	{
		objJSON = strParameters_a.split('&').reduce(function (prev, curr, i, arr)
			{
				var p = curr.split('=');
				if (p[0] != strOmit_a)
				{
					prev[decodeURIComponent(p[0])] = decodeURIComponent(p[1]);
				}
				return prev;
			}, {}

			);
	}

	return objJSON;
}
