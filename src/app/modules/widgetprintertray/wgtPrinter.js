/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-hostdriver.js*/

function widgetprintertray_wgtPrinter(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	var m_arrPrinters = [];
	var m_blnBusy = false;
	var m_objCurrentPage = null;
	var m_objHostDriver = new hostdriver(objOS_a, strFormID_a, objParameters_a);
	var m_objLocalQueue = new Array();
	var m_strDefaultPrinter = '';
	var m_strPageData = '';
	var m_strPrinterName = '';
	var m_strVersion = '';
	
	// ====================================================================================
	// HELPERS ============================================================================

	function disableProcessing()
	{
		m_blnBusy = true;
		// disable server checking for printjob events while printing
		os.disableServerEventQueue('printer');
//alert('disableProcessing');
	}

	this.donePrinting = function()
	{
		if (m_objCurrentPage.page == m_objCurrentPage.pages)
		{
			// update the printjob status
			completePrintJob(m_objCurrentPage.printjob_id);
		}
		else
		{
			printNextPage();
		}
	};

	function enableProcessing()
	{
//alert('enableProcessing');
		os.enableServerEventQueue('printer');
		m_blnBusy = false;
	}

	function printingError(strLocation_a)
	{
		os.dialogAlert("Attention: Print System Error " + strLocation_a, function()  {}

		);
	}

	function afterAjaxError()
	{
		enableProcessing();
	}

	function raiseException(strCallback_a, strError_a)
	{
		console.error(strCallback_a + " Exception: " + strError_a);
	}

	function printHTML(strHTML_a)
	{
		setPageData("" + strHTML_a + "");

		// it appears that the applet doesn't automatically call onDoneAppending if we call appendHTMLString or append.
		m_objThis.readyToPrint();
	}

	function clearContent()
	{
		os.element(m_strFormID, ".ge-content").html('');
	}

	function printHTMLViaJS(strHTML_a)
	{
		// initialise custom content
		var strFrameID = getGUID();
		var strHTML = '<iframe width="100%" height="100%" id="' + strFrameID + '" name="' + strFrameID + '"></iframe>';
		os.element(m_strFormID, '.ge-content').html(strHTML);
		os.element(m_strFormID, '#' + strFrameID).contents().find('body').html(strHTML_a);

		//window.frames[strFrameID].focus();
		window.frames[strFrameID].print();

		clearContent();
		m_objThis.readyToPrint();
	}

	function processBroadcasts()
	{
		if (m_blnBusy === false)
		{
			disableProcessing();

			// fetch a printjob
			fetchPrintJob();
		}
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function printFetchedPage(objResponse_a)
	{
//alert('a:' + m_objCurrentPage.printer);
		m_objThis.setPrinter(m_objCurrentPage.printer);

//alert(JSON.stringify(objResponse_a));
		var strPage = objResponse_a.page;
		
//alert(strPage);

//alert(m_objCurrentPage.documenttype);

		try
		{
			if ((m_objCurrentPage.documenttype.toUpperCase() === 'RECEIPT') || (m_objCurrentPage.documenttype.toUpperCase() === 'RECEIPTS'))
			{
				// for receipt
				//printHTML(strPage);
				printHTMLViaJS(strPage);
			}
		}
		catch (err)
		{
			alert(err);
			printingError(1);
		}
	}

	function printJobCompleted(objResponse_a)
	{
		// else re-enable the server checking for printjobs
		enableProcessing();

		os.broadcast(m_objThis, 'printer', m_objCurrentPage.documenttype + 'printed');

		// try fetch the next one right away
		fetchPrintJob();
	}

	function printJobFetched(objResponse_a)
	{
		var strPrintJobID = '';

//alert(JSON.stringify(objResponse_a));

		m_objLocalQueue = new Array();
		processArray(objResponse_a, function(objPage_a)
		{
			strPrintJobID = objPage_a.printjob_id;
			if (strPrintJobID.length > 0)
			{
				m_objLocalQueue.push(objPage_a);
//alert(JSON.stringify(objPage_a));
			}
		});

		printNextPage();
	}

	// ====================================================================================
	// WEBSERVICES ========================================================================

	function completePrintJob(strPrintJobID_a)
	{
		var objJSON = os.ajaxRequestCreate('print_printjobcompleted', [
					{
						"name" : 'printjob_id',
						"value" : strPrintJobID_a
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, printJobCompleted, os.ajaxError, afterAjaxError, false, true);
	}

	function fetchPrintJob()
	{
		var objJSON = os.ajaxRequestCreate('print_printjobfetchnext', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, printJobFetched, os.ajaxError, afterAjaxError, false, true);
	}
	
	function printNextPage()
	{
		m_objCurrentPage = m_objLocalQueue.shift();

		if ((m_objCurrentPage !== null) && (m_objCurrentPage !== undefined))
		{
//alert('1');
			// print the printjob
			//var strPath = m_objCurrentPage.path;
			var strDocumentID = m_objCurrentPage.documentid;
			var strFilename = m_objCurrentPage.filename;

			var objJSON = os.ajaxRequestCreate('print_fetchpage', [
						{
							"name" : 'documentid',
							"value" : strDocumentID
						},
						{
							"name" : 'pagename',
							"value" : strFilename
						}
					]);
//alert(JSON.stringify(objJSON));
			os.ajaxCall(URL_WEBSERVICE, objJSON, printFetchedPage, os.ajaxError, afterAjaxError, false, true);
		}
		else
		{
//alert('2');
			enableProcessing();
		}
	}
	
	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function()
	{
		return false;
	};

	this.Form_onBroadcast = function(strQueue_a, strMessage_a)
	{
		if (strQueue_a === 'printer')
		{
			if (strMessage_a === ENTITY_PRINTJOB)
			{
				// register the applet
				if (os.isPrinterRegistered())
				{
					// the applet had already been initialised, so perform the processBroadcasts
					processBroadcasts();
				}
			}
		}
	};

	this.Form_onPermissionCheck = function()
	{
		return true;
	};

	this.Form_onLoad = function()
	{
		m_objHostDriver.InitialisePrinting(function(strVersion_a, strDefaultPrinter_a, arrPrinters_a)
		{
			m_strVersion = strVersion_a;
			//alert(m_strVersion);
			m_strDefaultPrinter = strDefaultPrinter_a;
			//alert(m_strDefaultPrinter);
			m_arrPrinters = arrPrinters_a;
			//alert(JSON.stringify(m_arrPrinters));

			// register printer widget
			var strPrinterProvider = "QZ-Tray " + strVersion_a;
			os.registerPrinter(strPrinterProvider, m_objThis);
			os.registerServerEvent('printer', m_strFormID);
		});
	};

	// ====================================================================================
	// OS CALLBACKS =======================================================================

	this.getDefaultPrinter = function()
	{
		return m_strDefaultPrinter;
	};

	this.getPrinters = function()
	{
		return m_arrPrinters;
	};

	this.getVersion = function()
	{
		return m_strVersion;
	};

	this.readyToPrint = function()
	{
		try
		{
			if ((m_objCurrentPage.documenttype.toUpperCase() === 'RECEIPT') || (m_objCurrentPage.documenttype.toUpperCase() === 'RECEIPTS'))
			{
				// for receipts
				dummyAppletPrint();
			}
		}
		catch (err)
		{
			alert(err);
			printingError(2);
		}
	};

	function setPageData(strPageData_a)
	{
		m_strPageData = strPageData_a;
	}
	
	this.setPrinter = function(strPrinterName_a)
	{
		var strPrinterName = strPrinterName_a;
		if (strPrinterName.length === 0)
		{
			strPrinterName = m_strDefaultPrinter;
		}

		var intPrinter = -1;
		var intI = 0;
		processArray(m_arrPrinters, function(objPrinter_a)
		{
			//alert(strPrinterName + ":" + objPrinter_a.description);

			if (objPrinter_a.description.toUpperCase() == strPrinterName.toUpperCase())
			{
				intPrinter = intI;
			}
			intI++;
		});

		if (intPrinter > -1)
		{
			try
			{
				m_strPrinterName = strPrinterName;
			}
			catch (err)
			{
				alert(err);
				printingError(3);
			}
		}
	};

	// ====================================================================================
	// DUMMY EVENTS ======================================================================

	// dummy applet print
	function dummyAppletPrint()
	{
		m_objThis.donePrinting();
	}
}
