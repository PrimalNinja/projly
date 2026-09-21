// wrapper for host utils such as qztray
function hostdriver(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;
	
	var m_arrPrinters = [];
	var m_intFindingDone = 0;
	var m_intFindingTodo = 0;
	var m_strDefaultPrinter = '';
	var m_strVersion = '';
	
	// helpers
	
	function findingDone(cb_a)
	{
		m_intFindingDone++;
		
		if (m_intFindingDone === m_intFindingTodo)
		{
			if ($.isFunction(cb_a))
			{
				cb_a(m_strVersion, m_strDefaultPrinter, m_arrPrinters);
			}
		}
	}
	
	// private functions
	
	function QZFindPrinters(cbInitialised_a) 
	{
		m_intFindingDone = 0;
		m_intFindingTodo = 2;
		
		qz.printers.find().then(function(arrPrinters_a) 
		{
			m_arrPrinters = [];
			
			processArray(arrPrinters_a, function(strPrinter_a)
			{
				m_arrPrinters.push({ id:strPrinter_a, description:strPrinter_a }); 
			});
			
			findingDone(cbInitialised_a);
			
			os.broadcast(m_objThis, 'printer', 'printersfetched');	
		}).catch(function(err)  
		{ 
			raiseException('QZFindPrinters', err);
		});
		
		qz.printers.getDefault().then(function(strDefaultPrinter_a)
		{
			m_strDefaultPrinter = strDefaultPrinter_a;
			findingDone(cbInitialised_a);
		}).catch(function(err)
		{
			raiseException('QZFindPrinters', err);
		});
	}
	
	function QZRegister(cbInitialised_a)
	{
		// connect
		qz.websocket.connect().then(function() 
		{
			// setup version
			qz.api.getVersion().then(function(strVersion_a)
			{
				m_strVersion = strVersion_a;

				QZFindPrinters(cbInitialised_a);
			});
		});
	}
	
	// public functions
	
	this.InitialisePrinting = function(cbInitialised_a)
	{
		qz.security.setCertificatePromise(function(resolve, reject) 
		{
			fetch("ws/qz.php?request=CERTIFICATE", {cache: 'no-store', headers: {'Content-Type': 'text/plain'}})
			.then(function(data) 
			{ 
				data.ok ? resolve(data.text()) : reject(data.text()); 
			});
		});

		qz.security.setSignatureAlgorithm("SHA512"); // Since 2.1
		qz.security.setSignaturePromise(function(toSign) 
		{
			return function(resolve, reject) 
			{
				fetch("ws/qz.php?request=" + toSign, {cache: 'no-store', headers: {'Content-Type': 'application/json; charset=utf-8'}})
				.then(function(data) 
				{ 
					data.ok ? resolve(data.text()) : reject(data.text()); 
				});
			};
		});

		QZRegister(cbInitialised_a);
	};

	this.PrintHTML = function(strPrinterName_a, objCurrentPage_a, strPageData_a, cbDonePrinting_a, cbError_a)
	{
		//alert('QZPrintHTML');
		var objConfig = qz.configs.create(strPrinterName_a, {
			legacy: os.toBoolean(objCurrentPage_a.legacy),
			orientation: objCurrentPage_a.orientation
		});
		
		var objData = [{
			type: 'pixel',
			format: 'html',
			flavor: 'plain',
			data: strPageData_a
		}];
		qz.print(objConfig, objData).then(function()
		{
			if ($.isFunction(cbDonePrinting_a))
			{
				cbDonePrinting_a();
			}
		}).catch(function(err) 
		{ 
			if ($isFunction(cbError_a))
			{
				cbError_a(err);
			}
		});
	};
	
	this.PrintRaw = function(strPrinterName_a, objCurrentPage_a, strPageData_a, cbDonePrinting_a, cbError_a)
	{
		var objConfig = qz.configs.create(strPrinterName_a, {
			orientation: objCurrentPage_a.orientation
		});
		
		var objData = [{
			type: 'raw',
			format: 'command',
			flavor: 'plain',
			data: strPageData_a
		}];
		qz.print(objConfig, [strPageData_a]).then(function()
		{
			if ($.isFunction(cbDonePrinting_a))
			{
				cbDonePrinting_a();
			}
		}).catch(function(err) 
		{ 
			if ($isFunction(cbError_a))
			{
				cbError_a(err);
			}
		});
	};
}