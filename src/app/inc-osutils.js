/*jsl:option explicit*/

// ====================================================================================
// AWAFOS Utils v20240412 =============================================================
// copyright(C) 2012-2024 Mitsukibo, Julian Cassin & Francis Weston. All rights reserved.

var g_arrDesktopRegions = new Array; // global form regions
var g_arrPermissions = new Array; // global permissions
var g_arrProducts = new Array; // global products

var FULLBROWSERCAPABILITY = 'canvas,console,geolocation,google,help,html5,java,jqxhr,maps,mobile,multimon,multitouch,printer,regionscroll,speech,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom';
var USEJSONP = false;
var VIEWPORTINITIALSTATE = "width=" + DEVICE_WIDTH + ", initial-scale=1.0, maximum-scale=3.0, minimum-scale=0.25";

var g_intPreviousOrientation; // tracks orientation changes
if (window.orientation === undefined)
{
	g_intPreviousOrientation = 0;
}
else
{
	g_intPreviousOrientation = parseInt(window.orientation, 10);
}

// lazy loaded module dependencies (note: the ones suffixed with _SKIP currently cannot lazyload for various reasons)
var g_arrDependencies = [
	// { "moduleid":"osmin", "type":"js", "dependency": DYNAMIC_APP_DIR_URL + "bundle/3p.z.js", "debug": DYNAMIC_APP_DIR_URL + "bundle/3p.js", "loaded":false },

	// { "moduleid":"osfull", "type":"js", "dependency": DYNAMIC_APP_DIR_URL + "bundle/3p.z.js", "debug": DYNAMIC_APP_DIR_URL + "bundle/3p.js", "loaded":false },
	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "inc-osutils-classes.z.js",
		"debug" : DYNAMIC_APP_DIR_URL + "inc-osutils-classes.js",
		"loaded" : false
	},
	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "inc-osutils-jdock.z.js",
		"debug" : DYNAMIC_APP_DIR_URL + "inc-osutils-jdock.js",
		"loaded" : false
	},
	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "inc-osutils-jeditor.z.js",
		"debug" : DYNAMIC_APP_DIR_URL + "inc-osutils-jeditor.js",
		"loaded" : false
	},
	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "inc-osutils-jcodeeditor.z.js",
		"debug" : DYNAMIC_APP_DIR_URL + "inc-osutils-jcodeeditor.js",
		"loaded" : false
	},
	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "inc-osutils-jformrenderer.z.js",
		"debug" : DYNAMIC_APP_DIR_URL + "inc-osutils-jformrenderer.js",
		"loaded" : false
	},
	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "inc-osutils-jgrids.z.js",
		"debug" : DYNAMIC_APP_DIR_URL + "inc-osutils-jgrids.js",
		"loaded" : false
	},
	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "inc-osutils-notminified.js",
		"loaded" : false
	},
	// {
		// "moduleid" : "osfull",
		// "type" : "js",
		// "dependency" : DYNAMIC_APP_DIR_URL + "bundle/3p-app.z.js",
		// "debug" : DYNAMIC_APP_DIR_URL + "bundle/3p-app.js",
		// "loaded" : false
	// },
	// {
		// "moduleid" : "osfull",
		// "type" : "js",
		// "dependency" : DYNAMIC_APP_DIR_URL + "bundle/3p-nomin.js",
		// "debug" : DYNAMIC_APP_DIR_URL + "bundle/3p-nomin.js",
		// "loaded" : false
	// },
	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "js/base64v1_0.js",
		"loaded" : false
	},
	// {
		// "moduleid" : "osfull",
		// "type" : "js",
		// "dependency" : DYNAMIC_APP_DIR_URL + "js/qz-tray.js",
		// "loaded" : false
	// },
	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "js/jpeg_encoder_basic.js",
		"loaded" : false
	},
	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "js/fileupload/cors/jquery.xdr-transport.js",
		"loaded" : false
	},

	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "inc-hostdriver.js",
		"loaded" : false
	},

	{
		"moduleid" : "osfull",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "modules/widgetclock/clock/clock.js",
		"loaded" : false
	},

	{
		"moduleid" : "widgetcalculator.wgtCalculator",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "modules/widgetcalculator/js/calculator.js",
		"loaded" : false
	},

	{
		"moduleid" : "widgetclock.wgtClock",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "modules/widgetclock/clock/clock.js",
		"loaded" : false
	},

	{
		"moduleid" : "widgettube.wgtTube",
		"type" : "js",
		"dependency" : DYNAMIC_APP_DIR_URL + "modules/widgettube/tabular/jquery.tubular.1.0.js",
		"loaded" : false
	}
];

// note: a description of hidden will prevent them from being retrieved
var g_objCapabilities =
	[
	{
		"code" : 'canvas',
		"description" : 'Canvas',
		"notes" : 'Allows advanced drawing including sign on glass.'
	},
	{
		"code" : 'console',
		"description" : 'hidden',
		"notes" : ''
	},
	{
		"code" : 'geolocation',
		"description" : 'Geo Locations',
		"notes" : 'The ability to locate your position using GPS coordinates or wifi hotspots.'
	},
	{
		"code" : 'google',
		"description" : 'hidden',
		"notes" : ''
	},
	{
		"code" : 'help',
		"description" : 'Interactive Help',
		"notes" : 'Interactive help can guide new users in a friendly tutorial like manner.'
	},
	{
		"code" : 'html5',
		"description" : 'HTML5 Support',
		"notes" : 'Allows for advanced HTML5 facilities typically such as enhanced visual effects.'
	},
	{
		"code" : 'java',
		"description" : 'Java Support',
		"notes" : 'Allows automatic importing as well as direct to printer printing including thermal printers.'
	},
	{
		"code" : 'jqxhr',
		"description" : 'Multiple File Upload',
		"notes" : 'Allows multiple files to be queued for upload in a single go.'
	},
	{
		"code" : 'maps',
		"description" : 'Mapping',
		"notes" : 'Indicates whether mapping services are available.  This facility requires a mapping provider to be configured.'
	},
	{
		"code" : 'mobile',
		"description" : 'hidden',
		"notes" : ''
	},
	{
		"code" : 'multimon',
		"description" : 'Multiple Monitor',
		"notes" : 'You can increase your productivity by having multiple monitors connected to your computer.'
	},
	{
		"code" : 'multitouch',
		"description" : 'Multi-Touch',
		"notes" : 'Incidates whether the divice supports multi-touch features.'
	},
	{
		"code" : 'printer',
		"description" : 'Printing',
		"notes" : 'Indicates whether printing facilities are available.'
	},
	{
		"code" : 'regionscroll',
		"description" : 'hidden',
		"notes" : ''
	},
	{
		"code" : 'speech',
		"description" : 'Speech Recognition',
		"notes" : 'Indicates whether speech recognition services are available.  This facility requires a speech provider to be configured.'
	},
	{
		"code" : 'symbols',
		"description" : 'hidden',
		"notes" : ''
	},
	{
		"code" : 'themes',
		"description" : 'Themable',
		"notes" : 'Incidates whether custom themes are supported.'
	},
	{
		"code" : 'timers',
		"description" : 'Background Processing',
		"notes" : 'With advanced background processing you can often perform multiple tasks at the same time.'
	},
	{
		"code" : 'tips',
		"description" : 'Tool Tips',
		"notes" : 'Popup tool tips are a useful guide to more information if you hover over certain buttons and other features.'
	},
	{
		"code" : 'touch',
		"description" : 'Touch Screen',
		"notes" : 'If your touch screen monitor is supported.'
	},
	{
		"code" : 'tube',
		"description" : 'Youtube Video',
		"notes" : 'Support for youtube video playback services.'
	},
	{
		"code" : 'video',
		"description" : 'Video Support',
		"notes" : 'Support for video playback.'
	},
	{
		"code" : 'viewport',
		"description" : 'hidden',
		"notes" : ''
	},
	{
		"code" : 'widgets',
		"description" : 'Widgets',
		"notes" : 'Widgets can provide you useful tools that can be left on the screen for easy access or visual display.'
	},
	{
		"code" : 'zoom',
		"description" : 'Zooming',
		"notes" : 'On low resolution monitors the ability to zoom can give you an effective larger working area for multiple forms.'
	}
];

var g_objBrowsers =
	[
	// statuses best, passable, unsupported, unavailable
	// MOBILE

	// samsung
	{
		"osid" : 'Android',
		"browserid" : 'Samsung Browser',
		"name" : 'Samsung Browser for Android',
		"capabilities" : 'canvas,google,html5,maps,mobile,multitouch,symbols,timers,touch',
		"uncapabilities" : 'console,help,java,jqxhr,multimon,printer,regionscroll,speech,themes,tips,tube,video,widgets,zoom,viewport',
		"supportlevel" : 'passable',
		"major" : 0
	},

	// android browser
	{
		"osid" : 'Android',
		"browserid" : 'Android Browser',
		"name" : 'Google Android Browser',
		"capabilities" : 'canvas,google,html5,jqxhr,maps,mobile,multitouch,timers,touch,viewport',
		"uncapabilities" : 'console,help,java,multimon,printer,regionscroll,speech,symbols,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'passable',
		"major" : 4 // 20160204 unconfirmed whether this is the latest version, perhaps a later version more functions are now available
	},
	{
		"osid" : 'Android',
		"browserid" : 'Android Browser',
		"name" : 'Google Android Browser',
		"capabilities" : '',
		"uncapabilities" : 'canvas,console,google,help,html5,java,jqxhr,maps,mobile,multimon,multitouch,printer,regionscroll,speech,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"supportlevel" : 'unavailable',
		"major" : 0
	},

	// chrome
	{
		"osid" : 'Android',
		"browserid" : 'Chrome',
		"name" : 'Google Chrome for Android',
		"capabilities" : 'canvas,geolocation,google,html5,jqxhr,maps,mobile,multitouch,timers,touch,viewport', // 20160204 unconfirmed whether more functions are now available
		"uncapabilities" : 'console,help,java,multimon,printer,regionscroll,speech,symbols,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'best',
		"major" : 47
	},
	{
		"osid" : 'Android',
		"browserid" : 'Chrome',
		"name" : 'Google Chrome for Android',
		"capabilities" : 'canvas,google,html5,jqxhr,maps,mobile,multitouch,timers,touch,viewport',
		"uncapabilities" : 'console,help,java,multimon,printer,regionscroll,speech,symbols,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'passable',
		"major" : 0
	},

	{
		"osid" : 'iOS',
		"browserid" : 'Chrome',
		"name" : 'Google Chrome for iOS',
		"capabilities" : 'canvas,geolocation,google,html5,jqxhr,maps,mobile,multitouch,timers,touch,viewport', // 20160204 unconfirmed whether more functions are now available
		"uncapabilities" : 'console,help,java,multimon,printer,regionscroll,speech,symbols,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'best',
		"major" : 47
	},
	{
		"osid" : 'iOS',
		"browserid" : 'Chrome',
		"name" : 'Google Chrome for iOS',
		"capabilities" : 'canvas,google,html5,jqxhr,maps,mobile,multitouch,timers,touch,viewport',
		"uncapabilities" : 'console,help,java,multimon,printer,regionscroll,speech,symbols,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'passable',
		"major" : 0
	},

	// edge
	{
		"osid" : 'Android',
		"browserid" : 'Edge',
		"name" : 'Microsoft Edge for Android',
		"capabilities" : 'canvas,google,html5,maps,mobile,multitouch,symbols,timers,touch,viewport',
		"uncapabilities" : 'console,help,java,jqxhr,multimon,printer,regionscroll,speech,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'passable',
		"major" : 0
	},

	{
		"osid" : 'iOS',
		"browserid" : 'Edge',
		"name" : 'Microsoft Edge for iOS',
		"capabilities" : 'canvas,google,html5,maps,mobile,multitouch,symbols,timers,touch,viewport',
		"uncapabilities" : 'console,help,java,jqxhr,multimon,printer,regionscroll,speech,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'passable',
		"major" : 0
	},

	// firefox
	{
		"osid" : 'Android',
		"browserid" : 'Firefox',
		"name" : 'Mozilla Firefox for Android',
		"capabilities" : 'canvas,geolocation,google,html5,maps,mobile,multitouch,symbols,timers,touch,viewport',
		"uncapabilities" : 'console,help,java,jqxhr,multimon,printer,regionscroll,speech,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'best',
		"major" : 0
	},

	{
		"osid" : 'iOS',
		"browserid" : 'Firefox',
		"name" : 'Mozilla Firefox for iOS',
		"capabilities" : 'canvas,geolocation,google,html5,maps,mobile,multitouch,symbols,timers,touch,viewport',
		"uncapabilities" : 'console,help,java,jqxhr,multimon,printer,regionscroll,speech,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'best',
		"major" : 0
	},

	// opera
	{
		"osid" : 'Android',
		"browserid" : 'Opera',
		"name" : 'Opera for Android',
		"capabilities" : 'canvas,google,html5,maps,mobile,multitouch,symbols,timers,touch,viewport',
		"uncapabilities" : 'console,help,java,jqxhr,multimon,printer,regionscroll,speech,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'passable',
		"major" : 0
	},

	{
		"osid" : 'iOS',
		"browserid" : 'Opera',
		"name" : 'Opera for iOS',
		"capabilities" : 'canvas,google,html5,maps,mobile,multitouch,symbols,timers,touch,viewport',
		"uncapabilities" : 'console,help,java,jqxhr,multimon,printer,regionscroll,speech,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'passable',
		"major" : 0
	},

	// blackberry
	{
		"osid" : 'BlackBerry',
		"browserid" : 'Mobile Safari',
		"name" : 'Blackberry Browser',
		"capabilities" : 'canvas,geolocation,google,html5,maps,mobile,multitouch,symbols,timers,touch,viewport',
		"uncapabilities" : 'console,help,java,jqxhr,multimon,printer,regionscroll,speech,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'best',
		"major" : 0
	},

	// ie mobile
	{
		"osid" : 'Windows Phone',
		"browserid" : 'IEMobile',
		"name" : 'IEMobile for Windows Phone',
		"capabilities" : 'canvas,google,html5,jqxhr,maps,mobile,multitouch,timers,touch,viewport', // 20160204 unconfirmed whether more functions are now available
		"uncapabilities" : 'console,help,java,multimon,printer,regionscroll,speech,symbols,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'passable',
		"major" : 10
	},
	{
		"osid" : 'Windows Phone',
		"browserid" : 'IEMobile',
		"name" : 'IEMobile for Windows Phone',
		"capabilities" : 'canvas,google,html5,jqxhr,maps,mobile,multitouch,timers,touch,viewport', // 20160204 unconfirmed whether more functions are now available
		"uncapabilities" : 'console,help,java,multimon,printer,regionscroll,speech,symbols,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'passable',
		"major" : 0
	},

	// safari
	{
		"osid" : 'iOS',
		"browserid" : 'Mobile Safari',
		"name" : 'Mobile Safari',
		"capabilities" : 'canvas,geolocation,google,html5,maps,mobile,multitouch,symbols,timers,touch,viewport', // 20160204 unconfirmed whether more functions are now available
		"uncapabilities" : 'console,help,java,jqxhr,multimon,printer,regionscroll,speech,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'best',
		"major" : 8
	},
	{
		"osid" : 'iOS',
		"browserid" : 'Mobile Safari',
		"name" : 'Mobile Safari',
		"capabilities" : 'canvas,geolocation,google,html5,maps,mobile,multitouch,symbols,timers,touch,viewport',
		"uncapabilities" : 'console,help,java,jqxhr,multimon,printer,regionscroll,speech,themes,tips,tube,video,widgets,zoom',
		"supportlevel" : 'best',
		"major" : 0
	},
	
	// DESKTOP

	// chrome
	{
		"osid" : 'Mac OS',
		"browserid" : 'Chrome',
		"name" : 'Google Chrome',
		"capabilities" : 'canvas,console,geolocation,google,help,html5,java,jqxhr,maps,multimon,multitouch,printer,speech,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"uncapabilities" : 'mobile',
		"supportlevel" : 'best',
		"major" : 45
	},
	{
		"osid" : 'Windows',
		"browserid" : 'Chrome',
		"name" : 'Google Chrome',
		"capabilities" : 'canvas,console,geolocation,google,help,html5,java,jqxhr,maps,multimon,multitouch,printer,regionscroll,speech,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"uncapabilities" : 'mobile',
		"supportlevel" : 'best',
		"major" : 48
	},
	{
		"osid" : 'Windows',
		"browserid" : 'Chrome',
		"name" : 'Google Chrome',
		"capabilities" : 'canvas,console,google,help,html5,java,jqxhr,maps,multimon,multitouch,printer,regionscroll,speech,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"uncapabilities" : 'mobile',
		"supportlevel" : 'passable',
		"major" : 17
	},
	{
		"osid" : 'Windows',
		"browserid" : 'Chrome',
		"name" : 'Google Chrome',
		"capabilities" : '',
		"uncapabilities" : 'canvas,console,google,help,html5,java,jqxhr,maps,mobile,multimon,multitouch,printer,regionscroll,speech,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"supportlevel" : 'unavailable',
		"major" : 0
	},

	// edge
	{
		"osid" : 'Windows',
		"browserid" : 'Edge',
		"name" : 'Microsoft Edge',
		"capabilities" : 'canvas,geolocation,google,html5,java,jqxhr,maps,multimon,multitouch,printer,regionscroll,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"uncapabilities" : 'console,help,mobile,speech',
		"supportlevel" : 'best',
		"major" : 12
	},
	{
		"osid" : 'Windows',
		"browserid" : 'Edge',
		"name" : 'Microsoft Edge',
		"capabilities" : '',
		"uncapabilities" : 'canvas,console,google,help,html5,java,jqxhr,maps,mobile,multimon,multitouch,printer,regionscroll,speech,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"supportlevel" : 'unavailable',
		"major" : 0
	},

	// firefox
	{
		"osid" : 'Windows',
		"browserid" : 'Firefox',
		"name" : 'Mozilla Firefox',
		"capabilities" : 'canvas,geolocation,google,help,html5,java,jqxhr,maps,multimon,multitouch,printer,regionscroll,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"uncapabilities" : 'console,mobile,speech',
		"supportlevel" : 'best',
		"major" : 43
	},
	{
		"osid" : 'Windows',
		"browserid" : 'Firefox',
		"name" : 'Mozilla Firefox',
		"capabilities" : 'canvas,google,help,html5,java,jqxhr,maps,multimon,multitouch,printer,regionscroll,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"uncapabilities" : 'console,mobile,speech',
		"supportlevel" : 'passable',
		"major" : 10
	},
	{
		"osid" : 'Windows',
		"browserid" : 'Firefox',
		"name" : 'Mozilla Firefox',
		"capabilities" : '',
		"uncapabilities" : 'canvas,console,google,help,html5,java,jqxhr,maps,mobile,multimon,multitouch,printer,regionscroll,speech,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"supportlevel" : 'unavailable',
		"major" : 0
	},

	// internet explorder
	{
		"osid" : 'Windows',
		"browserid" : 'IE',
		"name" : 'Microsoft Internet Explorer',
		"capabilities" : 'canvas,geolocation,google,html5,java,jqxhr,maps,multimon,multitouch,printer,regionscroll,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"uncapabilities" : 'console,help,mobile,speech',
		"supportlevel" : 'best',
		"major" : 10
	},
	{
		"osid" : 'Windows',
		"browserid" : 'IE',
		"name" : 'Microsoft Internet Explorer',
		"capabilities" : 'google,html5,java,maps,multimon,multitouch,printer,regionscroll,symbols,themes,timers,tips,touch,tube,video,viewport,widgets',
		"uncapabilities" : 'canvas,console,help,jqxhr,mobile,speech,zoom',
		"supportlevel" : 'passable',
		"major" : 9
	},
	{
		"osid" : 'Windows',
		"browserid" : 'IE',
		"name" : 'Microsoft Internet Explorer',
		"capabilities" : '',
		"uncapabilities" : 'canvas,console,google,help,html5,java,jqxhr,maps,mobile,multimon,multitouch,printer,regionscroll,speech,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"supportlevel" : 'unavailable',
		"major" : 0
	},

	// safari
	{
		"osid" : 'Mac OS',
		"browserid" : 'Safari',
		"name" : 'Safari',
		"capabilities" : 'canvas,google,help,html5,java,jqxhr,maps,multimon,multitouch,printer,regionscroll,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"uncapabilities" : 'console,mobile,speech',
		"supportlevel" : 'passable',
		"major" : 8
	},
	{
		"osid" : 'Mac OS',
		"browserid" : 'Safari',
		"name" : 'Safari',
		"capabilities" : 'canvas,google,help,html5,java,jqxhr,maps,multimon,multitouch,printer,regionscroll,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"uncapabilities" : 'console,mobile,speech',
		"supportlevel" : 'passable',
		"major" : 6
	},
	{
		"osid" : 'Mac OS',
		"browserid" : 'Safari',
		"name" : 'Safari',
		"capabilities" : 'google,help,html5,java,maps,multimon,multitouch,printer,regionscroll,symbols,themes,timers,tips,touch,tube,video,viewport,widgets',
		"uncapabilities" : 'canvas,console,jqxhr,mobile,speech,zoom',
		"supportlevel" : 'passable',
		"major" : 5
	},
	{
		"osid" : 'Mac OS',
		"browserid" : 'Safari',
		"name" : 'Safari',
		"capabilities" : '',
		"uncapabilities" : 'canvas,console,google,help,html5,java,jqxhr,maps,mobile,multimon,multitouch,printer,regionscroll,speech,symbols,themes,timers,tips,touch,tube,video,viewport,widgets,zoom',
		"supportlevel" : 'unavailable',
		"major" : 0
	}
];

// preserved:
//                  F12 (debugger)
//                  Ctrl+F5 (refresh), Ctrl+T (refresh), Ctrl+Z (undo), Ctrl+X (cut), Ctrl+C (copy), Ctrl+V (paste), Ctrl+Y (redo)

// recommended (browser):
//                  Alt+0, Alt+1, Alt+2, Alt+3, Alt+4, Alt+5, Alt+6, Alt+7, Alt+8, Alt+9
//                  Alt+G, Alt+I, Alt+J, Alt+K, Alt+L, Alt+M
//                  Alt+N, Alt+O, Alt+P, Alt+Q, Alt+R, Alt+U, Alt+W, Alt+X, Alt+Y

//                  Ctrl+0, Ctrl+1, Ctrl+2, Ctrl+3, Ctrl+4, Ctrl+5, Ctrl+6, Ctrl+7, Ctrl+8, Ctrl+9

// recommended (container):
//                  F1, F2, F3, F4, F6, F7, F8, F9, F10, F11, F12 (if enabled)

//                  Alt+0, Alt+1, Alt+2, Alt+3, Alt+4, Alt+5, Alt+6, Alt+7, Alt+8, Alt+9
//                  Alt+A, Alt+B, Alt+C, Alt+D, Alt+E, Alt+F, Alt+G, Alt+H, Alt+I, Alt+J, Alt+K, Alt+L, Alt+M
//                  Alt+N, Alt+O, Alt+P, Alt+Q, Alt+R, Alt+S, Alt+T, Alt+U, Alt+V, Alt+W, Alt+X, Alt+Y, Alt+Z

//                  Ctrl+0, Ctrl+1, Ctrl+2, Ctrl+3, Ctrl+4, Ctrl+5, Ctrl+6, Ctrl+7, Ctrl+8, Ctrl+9

// also usable:
//                  F2, F6, F7, F8, F9, F12 (if enabled)

//                  Ctrl+F2, Ctrl+F3, Ctrl+F6, Ctrl+F7, Ctrl+F8, Ctrl+F9, Ctrl+F10, Ctrl+F11, Ctrl+F12
//                  Ctrl+A, Ctrl+E, Ctrl+F, Ctrl+G, Ctrl+J, Ctrl+K, Ctrl+M

// avoid using:
//                  F1, F3, F4, F5, F11

//                  Alt+F1, Alt+F2, Alt+F3, Alt+F4, Alt+F5, Alt+F6, Alt+F7, Alt+F8, Alt+F9, Alt+F10, Alt+F11, Alt+F12
//                  Alt+A, Alt+B, Alt+C, Alt+D, Alt+E, Alt+F, Alt+H
//                  Alt+S, Alt+T, Alt+V, Alt+Z

//                  Ctrl+F1, Ctrl+F4
//                  Ctrl+B, Ctrl+C, Ctrl+D, Ctrl+H, Ctrl+I, Ctrl+L
//                  Ctrl+N, Ctrl+O, Ctrl+P, Ctrl+Q, Ctrl+R, Ctrl+S, Ctrl+T, Ctrl+U, Ctrl+V, Ctrl+W, Ctrl+X, Ctrl+Y, Ctrl+Z

// below provides an enumeration of standard shortcut keys to bind
var g_objShortcuts =
{
	"keycloseform" : 'Escape',
	"keynew" : 'Alt+N',
	"keypallets" : 'Alt+P',
	"keysave" : 'Alt+S',
	"keyfunction1" : 'Ctrl+1',
	"keyfunction2" : 'Ctrl+2',
	"keyfunction3" : 'Ctrl+3',
	"keyfunction4" : 'Ctrl+4',
	"keyfunction5" : 'Ctrl+5',
	"keyfunction6" : 'Ctrl+6',
	"keyfunction7" : 'Ctrl+7',
	"keyfunction8" : 'Ctrl+8'
};

// below are overridden system-wide to do nothing if the browser supports it
var g_objShortcutsOverride =
[
	{
		"keys" : 'F1'
	},
	{
		"keys" : 'F2'
	},
	{
		"keys" : 'F3'
	},
	{
		"keys" : 'F4'
	},
	{
		"keys" : 'F5'
	},
	{
		"keys" : 'F6'
	},
	{
		"keys" : 'F7'
	},
	{
		"keys" : 'F8'
	},
	{
		"keys" : 'F9'
	},
	{
		"keys" : 'F10'
	},
	{
		"keys" : 'F11'
	},
	{
		"keys" : 'Alt+F1'
	},
	{
		"keys" : 'Alt+F2'
	},
	{
		"keys" : 'Alt+F3'
	},
	{
		"keys" : 'Alt+F4'
	},
	{
		"keys" : 'Alt+F6'
	},
	{
		"keys" : 'Alt+F7'
	},
	{
		"keys" : 'Alt+F8'
	},
	{
		"keys" : 'Alt+F9'
	},
	{
		"keys" : 'Alt+F10'
	},
	{
		"keys" : 'Alt+F11'
	},
	{
		"keys" : 'Alt+F12'
	},
	{
		"keys" : 'Ctrl+F1'
	},
	{
		"keys" : 'Ctrl+F2'
	},
	{
		"keys" : 'Ctrl+F3'
	},
	{
		"keys" : 'Ctrl+F4'
	},
	{
		"keys" : 'Ctrl+F6'
	},
	{
		"keys" : 'Ctrl+F7'
	},
	{
		"keys" : 'Ctrl+F8'
	},
	{
		"keys" : 'Ctrl+F9'
	},
	{
		"keys" : 'Ctrl+F10'
	},
	{
		"keys" : 'Ctrl+F11'
	},
	{
		"keys" : 'Ctrl+F12'
	},

	{
		"keys" : 'Alt+0'
	},
	{
		"keys" : 'Alt+1'
	},
	{
		"keys" : 'Alt+2'
	},
	{
		"keys" : 'Alt+3'
	},
	{
		"keys" : 'Alt+4'
	},
	{
		"keys" : 'Alt+5'
	},
	{
		"keys" : 'Alt+6'
	},
	{
		"keys" : 'Alt+7'
	},
	{
		"keys" : 'Alt+8'
	},
	{
		"keys" : 'Alt+9'
	},
	{
		"keys" : 'Ctrl+0'
	},
	{
		"keys" : 'Ctrl+1'
	},
	{
		"keys" : 'Ctrl+2'
	},
	{
		"keys" : 'Ctrl+3'
	},
	{
		"keys" : 'Ctrl+4'
	},
	{
		"keys" : 'Ctrl+5'
	},
	{
		"keys" : 'Ctrl+6'
	},
	{
		"keys" : 'Ctrl+7'
	},
	{
		"keys" : 'Ctrl+8'
	},
	{
		"keys" : 'Ctrl+9'
	},

	{
		"keys" : 'Alt+A'
	},
	{
		"keys" : 'Alt+B'
	},
	{
		"keys" : 'Alt+C'
	},
	{
		"keys" : 'Alt+D'
	},
	{
		"keys" : 'Alt+E'
	},
	{
		"keys" : 'Alt+F'
	},
	{
		"keys" : 'Alt+G'
	},
	{
		"keys" : 'Alt+H'
	},
	{
		"keys" : 'Alt+I'
	},
	{
		"keys" : 'Alt+J'
	},
	{
		"keys" : 'Alt+K'
	},
	{
		"keys" : 'Alt+L'
	},
	{
		"keys" : 'Alt+M'
	},
	{
		"keys" : 'Ctrl+A'
	},
	{
		"keys" : 'Ctrl+B'
	},
	{
		"keys" : 'Ctrl+D'
	},
	{
		"keys" : 'Ctrl+E'
	},
	{
		"keys" : 'Ctrl+F'
	},
	{
		"keys" : 'Ctrl+G'
	},
	{
		"keys" : 'Ctrl+H'
	},
	{
		"keys" : 'Ctrl+I'
	},
	{
		"keys" : 'Ctrl+J'
	},
	{
		"keys" : 'Ctrl+K'
	},
	{
		"keys" : 'Ctrl+L'
	},
	{
		"keys" : 'Ctrl+M'
	},

	{
		"keys" : 'Alt+N'
	},
	{
		"keys" : 'Alt+O'
	},
	{
		"keys" : 'Alt+P'
	},
	{
		"keys" : 'Alt+Q'
	},
	{
		"keys" : 'Alt+R'
	},
	{
		"keys" : 'Alt+S'
	},
	{
		"keys" : 'Alt+T'
	},
	{
		"keys" : 'Alt+U'
	},
	{
		"keys" : 'Alt+V'
	},
	{
		"keys" : 'Alt+W'
	},
	{
		"keys" : 'Alt+X'
	},
	{
		"keys" : 'Alt+Y'
	},
	{
		"keys" : 'Alt+Z'
	},
	{
		"keys" : 'Ctrl+N'
	},
	{
		"keys" : 'Ctrl+O'
	},
	{
		"keys" : 'Ctrl+P'
	},
	{
		"keys" : 'Ctrl+Q'
	},
	{
		"keys" : 'Ctrl+R'
	},
	{
		"keys" : 'Ctrl+S'
	},
	{
		"keys" : 'Ctrl+U'
	},
	{
		"keys" : 'Ctrl+W'
	}
];

// speech vocab, phrase/broadcast pairs (note: the actions turn into broadcasts, no need to change them if they work well - but alternate phrases or re-worded phrases can help a lot with the quality of recognition)
var g_objVocab =
[
	{
		'phrase' : 'start recording',
		'action' : 'speech dictate on'
	},
	{
		'phrase' : 'stop recording',
		'action' : 'speech dictate off'
	},
	{
		'phrase' : 'window close',
		'action' : 'speech window close'
	},
	{
		'phrase' : 'what is the time now',
		'action' : 'what is the time now'
	},
	{
		'phrase' : 'show about',
		'action' : 'speech form about'	// not implemented
	},
	{
		'phrase' : 'log out',
		'action' : 'speech log out'	// not implemented
	},
	{
		'phrase' : 'speech system off',	
		'action' : 'speech system off'
	}
];

// ====================================================================================
// UTILS ==============================================================================

// compatability
if (window.Prototype)
{
	delete Object.prototype.toJSON;
	delete Array.prototype.toJSON;
	delete Hash.prototype.toJSON;
	delete String.prototype.toJSON;
}

if (!Array.prototype.reduce)
{
	Array.prototype.reduce = function reduce(accumulator)
	{
		if (this === null || this === undefined)
			throw new TypeError('Object is null or undefined');
		var i = 0,
		l = this.length >> 0,
		curr;

		if (typeof accumulator !== 'function') // ES5 : "If IsCallable(callbackfn) is false, throw a TypeError exception."
			throw new TypeError('First argument is not callable');

		if (arguments.length < 2)
		{
			if (l === 0)
				throw new TypeError('Array length is 0 and no second argument');
			curr = this[0];
			i = 1; // start accumulating at the second element
		}
		else
			curr = arguments[1];
		while (i < l)
		{
			if (i in this)
				curr = accumulator.call(undefined, curr, this[i], i, this);
			++i;
		}

		return curr;
	};
}

if (!String.prototype.toRegExp)
{
	String.prototype.toRegExp = function ()
	{
		var result = this.replace(/([\/\(\)\[\]\.\?])/g, '\\$1');
		result = result.replace('*', '.*');
		return new RegExp(result);
	};
}

// below is deprecated, use os.formatMoney instead
//function currency(var_a)
//{
//  var strResult = parseFloat(var_a).toFixed(2);
//
//  if (isNaN(strResult))
//  {
//      strResult = "";
//  }
//
//  return strResult;
//}

// certain exceptions are raised out of completeness even though we want to suppress them.
// this allows us to choose when not to suppress them in a central location when necessary during development
// by changing the handler below.
function doException(strSource_a, objErr_a)
{
	var arrSuppress = ['broadcast', 'raiseEvent', 'setFormFocus'];
	var strExceptionStyle = 'color:red; font-weight:bold;';
	var blnSuppress = false;

	processArray(arrSuppress, function (str_a)
	{
		if (strSource_a == str_a)
		{
			blnSuppress = true;
		}
	}
	);

	if (blnSuppress === false)
	{
		var strException = '%cException: ' + objErr_a + " raised in '" + strSource_a + "'";
		logDebug(strException, strExceptionStyle);
	}
}

function doNothing()
{
	// do nothing
}

// prefix added because mysql doesn't like certain patterns of GUIDs for field names
// eg: 6e4 gets confused by mysql as a standard form number
function getGUID(strPrefix_a)
{
	var strPrefix = strPrefix_a;
	if (strPrefix == undefined) { strPrefix = ''; }

	return strPrefix + 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c)
	{
		var r = Math.random() * 16 | 0,
		v = c == 'x' ? r : (r & 0x3 | 0x8);
		return v.toString(16);
	}
	);
}

function hasFlag(strFlags_a, strFlag_a)
{
	return ((' ' + strFlags_a + ' ').indexOf(' ' + strFlag_a + ' ') >= 0);
}

function getFormDesktopRegion(strEntity_a)
{
	var strResult = "";

	processArray(g_arrDesktopRegions, function(objDesktopRegion)
	{
		if (objDesktopRegion.entitycode === strEntity_a)
		{
			strResult = objDesktopRegion.formdesktopregion;
		}
	});

	return strResult;
}

function getListerDesktopRegion(strEntity_a)
{
	var strResult = "";

	processArray(g_arrDesktopRegions, function(objDesktopRegion)
	{
		if (objDesktopRegion.entitycode === strEntity_a)
		{
			strResult = objDesktopRegion.listerdesktopregion;
		}
	});

	return strResult;
}

function getProducts()
{
	return g_arrProducts;
}

function hasPermission(arrValidPermissions_a)
{
	var blnResult = false;

	var intP = 0;
	while ((intP < arrValidPermissions_a.length) && (blnResult === false))
	{
		var strValidPermission = arrValidPermissions_a[intP];

		var intC = 0;
		while ((intC < g_arrPermissions.length) && (blnResult === false))
		{
			var strPermission = g_arrPermissions[intC];

			if (strValidPermission == strPermission)
			{
				blnResult = true;
			}

			intC++;
		}
		intP++;
	}

	return blnResult;
}

function hasProduct(arrValidProducts_a, strProductStatus_a)
{
	var blnResult = false;

	var intP = 0;
	while ((intP < arrValidProducts_a.length) && (blnResult === false))
	{
		var strValidProduct = arrValidProducts_a[intP];
		var arrProducts = getProducts();

		var intC = 0;
		while ((intC < arrProducts.length) && (blnResult === false))
		{
			var strProduct = arrProducts[intC].productcode;
			var strProductStatus = arrProducts[intC].productstatus;

			if (strValidProduct === '*')
			{
				if (strProductStatus == strProductStatus_a)
				{
					blnResult = true;
				}
			}
			else
			{
				if (strValidProduct == strProduct)
				{
					if (strProductStatus == strProductStatus_a)
					{
						blnResult = true;
					}
				}
			}

			intC++;
		}
		intP++;
	}

	return blnResult;
}

function htmlDecode(str_a)
{
	var strResult = '';

	if (str_a)
	{
		strResult = $('<div />').html(str_a).text();
	}

	return strResult;
}

function htmlEncode(str_a)
{
	var strResult = '';

	if (str_a)
	{
		strResult = $('<div />').text(str_a).html();
	}

	return strResult;
}

function isNullOrUndefined(str_a)
{
	return ((str_a === undefined) || (str_a === null));
}

function logDebug(str_a, strStyle_a)
{
	if (DEBUG_JS === 'TRUE')
	{
		try
		{
			if (strStyle_a === undefined)
			{
				console.log(str_a);
			}
			else
			{
				console.log(str_a, strStyle_a);
			}
		}
		catch (err)
		{
			doNothing();
		}
	}
}

function processArray(arr_a, cb_a)
{
	if (arr_a !== null)
	{
		var intRowNum = 1;
		var intI = 0;
		var blnAbort = false;
		while ((!blnAbort) && (intI < arr_a.length))
		{
			if (arr_a[intI] !== undefined)
			{
				if ($.isFunction(cb_a))
				{
					blnAbort = cb_a(arr_a[intI], intRowNum, intI);
				}
			}
			intRowNum++;
			intI++;
		}
	}
}

function inArray(needle_a, arrHaystack_a)
{
    var blnFound = false;

    processArray(arrHaystack_a, function(item_a)
    {
        if(needle_a === item_a)
        {
            blnFound = true;
            return;
        }
    });

    return blnFound;
}

function randomRange(intFrom_a, intTo_a)
{
	return Math.floor(Math.random() * (intTo_a - intFrom_a + 1) + intFrom_a);
}

function searchArray(arr_a, strSearchField_a, strSearchValue_a)
{
	var objResult = null;

	if (arr_a !== null)
	{
		var blnFound = false;
		var intI = 0;
		while ((blnFound === false) && (intI < arr_a.length))
		{
			var obj = arr_a[intI];
			if (obj !== undefined)
			{
				var strCompareValue;

				try
				{
					strCompareValue = obj[strSearchField_a]; // instead of var strJavaScript = 'strCompareValue = obj.' + strSearchField_a; eval(strJavaScript);
				}
				catch (err)
				{}

				if (strCompareValue == strSearchValue_a)
				{
					objResult = obj;
				}
			}

			intI++;
		}
	}

	return objResult;
}

function setDesktopRegions(arrDesktopRegions_a)
{
	g_arrDesktopRegions = arrDesktopRegions_a;
}

function setPermissions(arrPermissions_a)
{
	g_arrPermissions = arrPermissions_a;
}

function setProducts(arrProducts_a)
{
	g_arrProducts = arrProducts_a;
}

function str_replace(str_a, strOld_a, strNew_a)
{
	var blnSubset = false;
	var strOld = strOld_a + '';	// force them to strings
	var strNew = strNew_a + '';	// force them to strings
	var strResult = str_a + '';	// force them to strings

	if (strOld === undefined)
	{
		strOld = '';
	}

	if (strNew === undefined)
	{
		strNew = '';
	}

	if (strResult === undefined)
	{
		strResult = '';
	}

	// if the old is a subset of the new, we would get into an infinite loop, so we need ot make it temporarily not a subset
	if (strNew.indexOf(strOld) >= 0)
	{
		strNew = str_replace(strNew, strOld, '__OLD__');
		blnSubset = true;
	}

	while (strResult.indexOf(strOld) >= 0)
	{
		strResult = strResult.replace(strOld, strNew);
	}

	// if we had a subset, revert it
	if (blnSubset)
	{
		strResult = str_replace(strResult, '__OLD__', strOld);
	}

	return strResult;
}

function stripChars(str_a, strStrip_a)
{
	var strResult = str_a;
	var strStrip = strStrip_a;

	if (strStrip === undefined)
	{
		strStrip = " `~!@#$%^&*()-_=+[{]}\|;:'<,>.?/" + '"';
	}

	for (var intI = 0; intI < strStrip.length; intI++)
	{
		var strChar = strStrip.substr(intI, 1);
		strResult = str_replace(strResult, strChar, '');
	}

	return strResult;
}

function renderBreadCrumbs(objOS_a, strFormID_a, strTarget_a, arrCrumbs_a)
{
	var strHTML = '';
	var intCrumb = 1;
	var intCrumbs = arrCrumbs_a.length;

	processArray(arrCrumbs_a, function (objCrumb_a)
	{
		if (strHTML.length > 0)
		{
			strHTML += '&nbsp; /&nbsp; ';
		}

		var strCaption = '';
		if ($.isFunction(objCrumb_a.caption))
		{
			strCaption = objCrumb_a.caption();
		}
		else
		{
			strCaption = objCrumb_a.caption;
		}

		if (intCrumb == intCrumbs)
		{
			strHTML += '<span class="gs-buttontext">' + htmlEncode(strCaption) + '</span>';
		}
		else
		{
			strHTML += '<a class="gs-buttontext" href="' + objCrumb_a.url + '">' + htmlEncode(strCaption) + '</a>';
		}

		intCrumb++;
	}
	);

	if (strHTML.length > 0)
	{
		strHTML = '<hr style="width: 100%; color: #cccccc; height: 1px; background-color:#cccccc; margin:5px; padding: 0px;" />' + strHTML + '<hr style="width: 100%; color: #cccccc; height: 1px; background-color:#cccccc; margin:5px; padding: 0px;" />';
	}

	objOS_a.element(strFormID_a, strTarget_a).html(strHTML);
}

function singleFileUpload(objOS_a, strFlags_a, strFunction_a, strFunctionID_a, cbSuccess_a, cbError_a)
{
	var os = objOS_a;

	// reset the uploader
	try
	{
		$('#ge-global-uploader').trigger('reset');
		$('#ge-global-uploader').unbind('click');
		$('#ge-global-files-field').unbind('click');
		$('#ge-global-uploader').fileupload('destroy');
	}
	catch (err)
	{
		doNothing();
	}

	// create a new form based on a templated one
	$('#ge-global-uploader-container').html($('#ge-global-uploader-template-container').html());

	// setup the uploader
	$('#ge-global-flags-field').val(strFlags_a);
	$('#ge-global-securitytoken-field').val(SECURITY_TOKEN);
	$('#ge-global-clientversion-field').val(CLIENT_VERSION);
	$('#ge-global-callerid-field').val(CALLERID_PUBLIC);
	$('#ge-global-function-field').val(strFunction_a);
	$('#ge-global-functionid-field').val(strFunctionID_a);

	try
	{
		$('#ge-global-uploader').fileupload(
		{
			url : 'ws/upload.php',
			dateType : 'json',
			autoUpload : true,
			add : function (e, data)
			{
				data.submit().success(doNothing).error(doNothing).complete(doNothing);
			},
			send : function ()
			{
				doNothing();
			},
			done : function (e, data)
			{
				var objJSON = os.ajaxRequestCreate('import_processuploads', []);

				os.ajaxCall(URL_WEBSERVICE, objJSON, function (objResponse_a)
				{
					if ($.isFunction(cbSuccess_a))
					{
						cbSuccess_a(objResponse_a);
					}
				}, os.ajaxError);
			},
			always : function ()
			{
				doNothing();
			}
		}
		);

		$('#ge-global-files-field').trigger('click');
	}
	catch (err)
	{
		if ($.isFunction(cbError_a))
		{
			cbError_a();
		}
	}
}

function isValidTime(strTime_a)
{
	var strValidTimeRegex = /^\s*([01]?\d|2[0-3]):?([0-5]\d)\s*$/;
	return strTime_a.match(strValidTimeRegex) !== null;
}

function isValidDate(dte_a)
{
	return dte_a instanceof Date && !isNaN(dte_a.getTime());
}

function initialiseStartupItems(os, cbDone_a)
{
	var intDelay = 0;
	function initialiseStartupItems2(arrResponse_a)
	{
		processArray(arrResponse_a, function (objStartupItem_a)
		{
			var blnCentre = objStartupItem_a.flags.split(' ').indexOf('centre') >= 0;
			var strCommand = objStartupItem_a.command;
			var strParameters = objStartupItem_a.parameters;
			var strFlags = objStartupItem_a.flags + ' onstartup';

			strFlags = $.trim(strFlags);
			strFlags = strFlags.replace('  ', ' ');
			strFlags = strFlags.replace(' ', '=true&');
			strFlags = strFlags + '=true';
			if (strParameters.length > 0)
			{
				if (strFlags.length > 0)
				{
					strParameters += '&' + strFlags;
				}
			}
			else
			{
				strParameters = strFlags;
			}

			strCommand = strCommand.replace('%WELCOMEFORM%', WELCOMEFORM);
			strCommand = strCommand.replace('#', '');

			os.after(intDelay, function()
			{
				os.showForm(strCommand, strParameters, blnCentre);
			});
			intDelay += 100;
		}
		);
		//os.showForm('core.frmMenu');	// if the startup items failed or someone deleted it
		if ($.isFunction(cbDone_a))
		{
			cbDone_a();
		}
	}

	var objJSON = os.ajaxRequestCreate('core_startupitemfetchactive', []);
	os.ajaxCall(URL_WEBSERVICE, objJSON, initialiseStartupItems2);
}
