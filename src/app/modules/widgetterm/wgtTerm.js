/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
function widgetterm_wgtTerm(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	var m_blnFocus = false;

	// ------------------------------------------------------------------------------------

	var m_CLIVERSION = '1.0';

	var m_strCommands = 'help, about, broadcast, browser, clear, debug, debugon, debugoff, logout, maps, modules, os, printer, tabs, timers, version, windows, zoom';
	var m_strNL = '\n';
	var m_strReady = 'ready';
	var m_strInit = 'CLI version ' + m_CLIVERSION + m_strNL + 'commands: ' + m_strCommands + m_strNL + m_strReady + m_strNL;
	var m_ABOUT = APP_NAME + ' ' + APP_VERSION + m_strNL + APP_COPYRIGHT + m_strNL + m_strNL + os.getVersion() + m_strNL + OS_COPYRIGHT + m_strNL;
	var m_HELP = m_strCommands + m_strNL;

	var m_objTerminal = null;
	
	// ====================================================================================
	// HELPERS ============================================================================

	function initialisePrompt()
	{
		function onCommand(objCommand_a, objTerminal_a)
		{
			//objTerminal_a.echo('<font color="yellow">you type command "' + objCommand_a + '"</font>', { raw: true });
			//m_objTerminal = objTerminal_a;
			processLine(objCommand_a);
		}
		
		function onKeyPress(e)
		{
			if (e.which == 96)
			{
				m_blnFocus = !m_blnFocus;
				$('#ge-prompt').slideToggle('fast');
				//$('#ge-prompt').enabled = m_blnFocus;
				//$('#ge-prompt').focus(m_blnFocus);
				return false;
			}
		}

		var objOptions =
		{
			prompt : '> ',
			name : 'prompt',
			xheight : 400,
			xenabled : false,
			greetings: m_ABOUT + m_strNL + m_HELP,
			keypress : onKeyPress
		};

		$('#ge-prompt').hide();
		m_objTerminal = $('#ge-prompt').terminal(onCommand, objOptions);
	}

	// internal commands
	function processInternal(strCmd_a, strParams_a)
	{
		var blnResult = false;
		var intI = 0;

		if (strCmd_a === 'help')
		{
			outputCLI(m_HELP);
			blnResult = true;
		}
		else if (strCmd_a === 'about')
		{
			outputCLI(m_ABOUT);
			blnResult = true;
		}
		else if (strCmd_a === 'broadcast')
		{
			os.broadcast(m_strFormID, 'core', strParams_a);
			blnResult = true;
		}
		else if (strCmd_a === 'browser')
		{
			outputCLI(os.getBrowser().name + ' version ' + os.getBrowserVersion() + ' (' + os.getBrowser().major + ')' + m_strNL + os.getAgent() + m_strNL + 'Support for your browser is ' + os.getBrowser().support + '. ' + os.getBrowserRecommendation() + m_strNL);
			var arrCapabilities = os.getBrowser().capabilities.split(',');

			processArray(arrCapabilities, function (objCapability_a)
			{
				outputCLI(objCapability_a + m_strNL);
			}
			);

			blnResult = true;
		}
		else if (strCmd_a === 'debug')
		{
			if (DEBUG_JS === 'TRUE')
			{
				outputCLI('debug is on' + m_strNL);
			}
			else
			{
				outputCLI('debug is off' + m_strNL);
			}
			blnResult = true;
		}
		else if (strCmd_a === 'debugoff')
		{
			outputCLI('debug is off' + m_strNL);
			DEBUG_JS = 'FALSE';
			blnResult = true;
		}
		else if (strCmd_a === 'debugon')
		{
			if (os.hasCapability('console'))
			{
				outputCLI('debug is on' + m_strNL);
				DEBUG_JS = 'TRUE';
			}
			else
			{
				outputCLI('debug is not supported in this browser' + m_strNL);
			}
			blnResult = true;
		}
		//else if ((strCmd_a === 'endcli') || (strCmd_a === 'exit'))
		//{
			//os.closeForm(m_strFormID);
			//blnResult = true;
		//}
		else if (strCmd_a === 'logout')
		{
			var strPrompt = 'Are you sure you wish to logout?<br><br>Please be sure you have saved your data before continuing.';
			os.dialogConfirm(strPrompt, function ()
			{
				logout();
			}
			);
			blnResult = true;
		}
		else if (strCmd_a === 'maps')
		{
			if (os.getMapProvider().length > 0)
			{
				outputCLI(os.getMapProvider() + m_strNL);
			}
			else
			{
				outputCLI('none' + m_strNL);
			}
			blnResult = true;
		}
		else if (strCmd_a === 'modules')
		{
			var arrModules = CLIENTMODULES.split(',');

			processArray(arrModules, function (objModule_a)
			{
				outputCLI(objModule_a + m_strNL);
			}
			);

			blnResult = true;
		}
		else if (strCmd_a === 'os')
		{
			outputCLI(os.getOS() + m_strNL);
			blnResult = true;
		}
		else if (strCmd_a === 'printer')
		{
			if (os.isPrinterRegistered())
			{
				outputCLI('printer version: ' + os.getPrinterVersion() + m_strNL);
				outputCLI('default printer: ' + os.getDefaultPrinter() + m_strNL);

				var arrPrinters = os.getPrinters();
				processArray(arrPrinters, function (objPrinter_a)
				{
					outputCLI(objPrinter_a.description + m_strNL);
				}
				);
			}
			else
			{
				outputCLI('printer is not registered' + m_strNL);
			}
			blnResult = true;
		}
		else if (strCmd_a === 'tabs')
		{
			if (os.hasCapability('multimon'))
			{
				traverseJSON(os.getTabs(), processKey);
			}
			else
			{
				outputCLI('multimon is not supported' + m_strNL);
			}
			blnResult = true;
		}
		else if (strCmd_a === 'timers')
		{
			traverseJSON(os.getTimers(), processKey);
			blnResult = true;
		}
		else if (strCmd_a === 'version')
		{
			outputCLI(os.getVersion() + m_strNL + OS_COPYRIGHT + m_strNL);
			blnResult = true;
		}
		else if (strCmd_a === 'viewport')
		{
			if (os.hasCapability('viewport'))
			{
				outputCLI(os.getViewPort().width + 'x' + os.getViewPort().height + m_strNL);
			}
			else
			{
				outputCLI('viewport is not supported in this browser' + m_strNL);
			}
			blnResult = true;
		}
		else if (strCmd_a === 'windows')
		{
			traverseJSON(os.getForms(), processKey);
			blnResult = true;
		}
		if (strCmd_a === 'zoom')
		{
			outputCLI(os.getZoom() + '%' + m_strNL);
			blnResult = true;
		}
		else if (strCmd_a == m_strReady)
		{
			blnResult = true;
		}

		return blnResult;
	}

	function processKey(strKey_a, strValue_a)
	{
		outputCLI(strKey_a + ' : ' + strValue_a + m_strNL);
	}

	function processLine(strLine_a)
	{
		var strLine = strLine_a;
		var intE = strLine.indexOf(' ');
		var strCmd = strLine;
		var strParams = '';
		if (intE >= 0)
		{
			strCmd = strLine.substring(0, intE);
			strParams = strLine.substring(intE + 1, strLine.length);
		}

		var strResult = m_strReady;
		if (strCmd.length > 0)
		{
			outputCLI(m_strNL);
			if (processInternal(strCmd, strParams) === false)
			{
				processExternal(strLine_a);
				strResult = '';
			}
		}

		return strResult;
	}

	function traverseJSON(obj_a, cb_a)
	{
		for (var intI in obj_a)
		{
			if (typeof(obj_a[intI]) !== "function")
			{
				cb_a.apply(this, [intI, obj_a[intI]]);
				if (typeof(obj_a[intI]) == "object")
				{
					traverseJSON(obj_a[intI], cb_a);
				}
			}
		}
	}

	// ====================================================================================
	// POPULATING =========================================================================

	function clearCLI()
	{
		os.element(m_strFormID, '.ge-cli').val('');
	}

	function outputCLI(str_a)
	{
		//os.element(m_strFormID, '.ge-cli').val(os.element(m_strFormID, '.ge-cli').val() + str_a);
		m_objTerminal.echo('<font color="yellow">' + str_a + '</font>', { raw: true });
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function commandUnknown()
	{
		outputCLI('invalid command' + m_strNL);
		outputCLI(m_strReady + m_strNL);
	}

	function externalProcessed(objResponse_a)
	{
		if ((objResponse_a !== null) && (objResponse_a !== undefined))
		{
			traverseJSON(objResponse_a, processKey);
			outputCLI(m_strReady + m_strNL);
		}
	}

	function logoutSuccess(objResponse_a)
	{
		os.logout('', 'wgtTerm');
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function processExternal(strLine_a)
	{
		var objJSON = os.ajaxRequestCreate('system_processcommand',
				[
					{
						"name" : "command",
						"value" : strLine_a
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, externalProcessed, commandUnknown);
	}

	function logout()
	{
		var objJSON = os.ajaxRequestCreate('public_logout', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, logoutSuccess, os.ajaxError);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		//os.unbindEvents(m_strFormID, 'gb-form-close,gb-form,gb-formtitle-inner-panel,ge-cli');

		// bindings
		//os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
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

	this.Form_onBroadcast = function (strQueue_a, strMessage_a)
	{
		if (strMessage_a.indexOf('cli ') === 0)
		{
			var strOutput = strMessage_a.substring(4, strMessage_a.length);

			outputCLI(strOutput + m_strNL);
			outputCLI(m_strReady + m_strNL);
		}
	};

	this.Form_onLoad = function ()
	{
		bindGlobals();
		initialisePrompt();
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

}