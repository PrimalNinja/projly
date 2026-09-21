// os entry is here
var g_strMyTabID = location.search.replace('?tabid=', '');

$(document).ready(function()
{
	var blnForceVerticalScroll = false;
	
	// os entry is here ("progresscolour": PROGRESSCOLOUR, "progress": "ge-progress", "percentage": "ge-percentage")
	os({ "progresscolour": PROGRESSCOLOUR, "progress": "ge-progress", "percentage": "ge-percentage", "container": "ge-form-container", "orientation": Window_onOrientationChange, "dependencies": "osfull", "keepalive": false, "unloadprompt": true, "child":true, "tabID":g_strMyTabID }, function()
	{
		// read permissions
		getUserInfo();
	});

	getUserInfo = function()
	{
		var objJSON = os().ajaxRequestCreate('security_userinfo', []);
		os().ajaxCall(URL_WEBSERVICE, objJSON, userInfoFetched, os().ajaxError, doNothing, true);
	};

	userInfoFetched = function(objResponse_a)
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
		os().setProperty('isextend', true);
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

		if (os().toBoolean(os().getProperty('sysadmin')))
		{
			os().setBackground('gi-sysadmin-background');
		}
		else
		{
			os().setBackground('gi-desktop-background');
		}

		$(window).hashchange(function(objEvent_a) { Window_onHashChange(objEvent_a); } );
		var objParameters = Window_onHashChange();

		if (!os().toBoolean(objParameters.fullscreen))
		{
			os().showTaskbar('core.frmTaskbarExtend', '', false, function()
			{
				initialiseStartupItems(os());
			});
		}
	};

	function Window_onHashChange(objEvent_a)
	{
		return os().hashChange(location.hash);
	}

	function Window_onOrientationChange(intOrientation_a)
	{
		os().initialiseViewPort();
	}
});
