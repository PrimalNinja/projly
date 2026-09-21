/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
function core_frmUpload(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	// ------------------------------------------------------------------------------------

	var m_intToFetch = 0;
	var m_intFetched = 0;
	var m_intErrors = 0;

	var m_strMode = m_objParameters.mode;

	if (m_strMode === undefined)
	{
		m_strMode = 'GENERIC'; // set to generic
	}
	else
	{
		m_strMode = m_strMode.toUpperCase();
	}

	var m_strFlags = m_objParameters.flags;
	if (m_strFlags === undefined)
	{
		m_strFlags = '';
	}

    //var m_strFormTitle = m_objParameters.title;

    //if (m_strFormTitle === undefined)
	//{
		//m_strFormTitle = '';
	//}

	var m_strDefinable = 'N';

	var m_blnNoBatch = m_strFlags.split(' ').indexOf('nobatch') >= 0;
	var m_blnSelect = m_strFlags.split(' ').indexOf('select') >= 0;
    var m_blnFormDirty = false;
    var m_blnAutoclose = false;
	var m_strFormFields = 'ge-filetypedropdown-field,ge-fileformatdropdown-field';

	var m_arrFileList = [];
    var m_intTotalFilesAdded = 0;
    var m_intCountCurrentUploadFiles = 0;
    var m_intProgressAll = 0;

	var m_objJQXHR;

	var m_strFileTypeID = '';
	var m_arrFileTypes = null;

	var m_strFileFormatID = '';
	var m_objFileFormats = null;

	var m_blnHasFiles = false;
	var m_arrResult = [];

	// ------------------------------------------------------------------------------------


    var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : [
				['CloseButton', 'StartUploadButton', 'CancelUploadButton', 'AddFileButton', 'RemoveAllFilesButton']
			]
		}
	];
    
	var m_arrTiles = [
		// miscellaneous tiles
		{
			id : '-',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			actionData : null,
			tip : '',
			type : 'blank'
		},
		// toolbar tiles

		{
			id : 'CloseButton',
			caption : 'Close',
			classes : 'gs-green-background-colour',
			permissions : [],
			action : function ()
			{
				m_objThis.FormClose_onClick();
			},
			actionData : null,
			tip : 'Click here to close the form builder.',
			type : 'toolbarbutton'
		},
        
        {
			id : 'AddFileButton',
			caption : 'Add Files',
			classes : 'gs-darkblue-background-colour',
			permissions : [],
			action : function ()
			{
				m_objThis.AddFilesButton_onClick();
			},
			actionData : null,
			tip : 'Click here to add files.',
			type : 'toolbarbutton'
		},
        
        {
			id : 'StartUploadButton',
			caption : 'Start Upload',
			classes : 'gs-darkblue-background-colour',
			permissions : [],
			action : function ()
			{
				//m_objThis.FormClose_onClick();
                m_objThis.StartUploadButton_onClick();
			},
			actionData : null,
			tip : 'Click here to start the upload.',
			type : 'toolbarbutton'
		},
                        
        {
			id : 'CancelUploadButton',
			caption : 'Cancel Upload',
			classes : 'gs-red-background-colour',
			permissions : [],
			action : function ()
			{
				m_objThis.CancelUploadButton_onClick();
			},
			actionData : null,
			tip : 'Click here to start the upload.',
			type : 'toolbarbutton'
		},
        
        {
			id : 'RemoveAllFilesButton',
			caption : 'Remove All',
			classes : 'gs-darkblue-background-colour',
			permissions : [],
			action : function ()
			{
				m_objThis.RemoveAllButton_onClick();
			},
			actionData : null,
			tip : 'Click here to remove all files.',
			type : 'toolbarbutton'
		}
        
    ];




		// ====================================================================================
	// HELPERS ============================================================================

	function afterAjaxError()
	{
		doNothing();
	}

	function bytesToSize(intBytes_a, intPrecision_a)
	{
		var strResult = '';
		var intKB = 1024;
		var intMB = intKB * 1024;
		var intGB = intMB * 1024;
		var intTB = intGB * 1024;

		if ((intBytes_a >= 0) && (intBytes_a < intKB))
		{
			strResult = intBytes_a + ' B';
		}
		else if ((intBytes_a >= intKB) && (intBytes_a < intMB))
		{
			strResult = (intBytes_a / intKB).toFixed(intPrecision_a) + ' KB';
		}
		else if ((intBytes_a >= intMB) && (intBytes_a < intGB))
		{
			strResult = (intBytes_a / intMB).toFixed(intPrecision_a) + ' MB';
		}
		else if ((intBytes_a >= intGB) && (intBytes_a < intTB))
		{
			strResult = (intBytes_a / intGB).toFixed(intPrecision_a) + ' GB';
		}
		else if (intBytes_a >= intTB)
		{
			strResult = (intBytes_a / intTB).toFixed(intPrecision_a) + ' TB';
		}
		else
		{
			strResult = intBytes_a + ' B';
		}

		return strResult;
	}

	function checkButtons()
	{
		if (os.toBoolean(m_strDefinable))
		{
			//if ((m_arrFileList.length > 0) && (m_strFileTypeID.length > 0) && (m_strFileFormatID.length > 0))
			if ((m_arrFileList.length > 0) && (m_strFileTypeID.length > 0))
			{
				setUploaderDirty(true);
				m_blnHasFiles = true;
			}
			else
			{
				setUploaderDirty(false);
				m_blnHasFiles = false;
			}
		}
		else
		{
			if ((m_arrFileList.length > 0) && (m_strFileTypeID.length > 0))
			{
				setUploaderDirty(true);
				m_blnHasFiles = true;
			}
			else
			{
				//m_objDock.disableButtons('StartUploadButton,RemoveAllButton', 'gs-darkblue-background-colour');
                setUploaderDirty(false);
				m_blnHasFiles = false;
			}
		}
	}

	function getDisplayIcon(strExtension_a)
	{
		var strResult = '';
		var strValidTypes = 'JS,ASP,BMP,CSS,CSV,FLA,GIF,HTM,INFO,JPEG,JPG,JS,PHP,PNG,SWF,XLS,XML,';

		if (strValidTypes.indexOf(',' + strExtension_a + ',') >= 0)
		{
			if ((strExtension_a === 'JPG') || (strExtension_a === 'JPEG'))
			{
				strResult = 'JPG.png';
			}
			else
			{
				strResult = strExtension_a + '.png';
			}
		}
		else
		{
			strResult = 'OTH.png';
		}

		return strResult;
	}

	function getFileTypeByFileTypeID(strFileTypeID_a)
	{
		var objResult;

		processArray(m_arrFileTypes, function (objFileType_a)
		{
			if (objFileType_a.id == strFileTypeID_a)
			{
				objResult = objFileType_a;
				return true;
			}
		}
		);

		return objResult;
	}

	function getFileTypeByMode(strMode_a)
	{
		var objResult;

		processArray(m_arrFileTypes, function (objFileType_a)
		{
			if (objFileType_a.code == strMode_a)
			{
				objResult = objFileType_a;
				return true;
			}
		}
		);

		return objResult;
	}

    function setProgressAllBarProgress(intProgress_a) {

        os.element(m_strFormID, '.ge-progressallbar .progress-bar').attr('aria-valuenow', intProgress_a);
        os.element(m_strFormID, '.ge-progressallbar .progress-bar').css('width', intProgress_a + "%");
        os.element(m_strFormID, '.ge-progressallbar .progress-bar').html( intProgress_a + "%");

    }

	function setUploaderDirty(blnIsDirty_a) {
        
        /*
        if(blnIsDirty_a) {
            os.element(m_strFormID, '.ge-startupload-button,.ge-cancelupload-button,.ge-removeallupload-button').removeClass('disabled');
            os.element(m_strFormID, '.ge-startupload-button,.ge-cancelupload-button,.ge-removeallupload-button').removeAttr('disabled');

        }
        else {
            os.element(m_strFormID, '.ge-startupload-button,.ge-cancelupload-button,.ge-removeallupload-button').addClass('disabled');
            os.element(m_strFormID, '.ge-startupload-button,.ge-cancelupload-button,.ge-removeallupload-button').attr('disabled', 'disabled');
        }
        */
       
       setDirty(blnIsDirty_a);
    }
    
    function setDirty(blnDirty_a)
	{
		m_blnFormDirty = blnDirty_a;

        if (m_blnFormDirty)
        {
            m_objDock.enableButtons('StartUploadButton,RemoveAllFilesButton', 'btn-primary');            
            m_objDock.enableButtons('CancelUploadButton', 'btn-danger');            
        }
        else
        {
            m_objDock.disableButtons('StartUploadButton,RemoveAllFilesButton', 'btn-primary');            
            m_objDock.disableButtons('CancelUploadButton', 'btn-danger');  
            
        }
		
	}

    function setUploadProgressIsActive(blnIsUploadProcessActive_a, blnShowProgressStatusText_a, strProgressStatusText_a) {

        if(blnIsUploadProcessActive_a) {
           os.element(m_strFormID, '.ge-progressallbar').removeClass('gb-hidden');
        }
        else {
           os.element(m_strFormID, '.ge-progressallbar').addClass('gb-hidden');
        }

        if(blnShowProgressStatusText_a) {
           os.element(m_strFormID, '.ge-progressalltext').removeClass('gb-hidden');
           os.element(m_strFormID, '.ge-progressalltext').html(strProgressStatusText_a);
        }
        else {
           os.element(m_strFormID, '.ge-progressalltext').addClass('gb-hidden');
           os.element(m_strFormID, '.ge-progressalltext').html("");
        }
    }

	function singleFile()
	{
		// defaulting
		updateFunction();

		os.element(m_strFormID, '.ge-flags-field').val(m_strFlags);
		os.element(m_strFormID, '.ge-securitytoken-field').val(SECURITY_TOKEN);
		os.element(m_strFormID, '.ge-clientversion-field').val(CLIENT_VERSION);
		os.element(m_strFormID, '.ge-callerid-field').val(CALLERID_PUBLIC);
	}

	function initialiseForm()
	{
		// defaulting
		updateFunction();

		os.element(m_strFormID, '.ge-flags-field').val(m_strFlags);
		os.element(m_strFormID, '.ge-securitytoken-field').val(SECURITY_TOKEN);
		os.element(m_strFormID, '.ge-clientversion-field').val(CLIENT_VERSION);
		os.element(m_strFormID, '.ge-callerid-field').val(CALLERID_PUBLIC);

        os.element(m_strFormID, '.ge-maxfilesize-label').html('Maximum file size allowed for upload: ' + bytesToSize(MAXUPLOADFILESIZE) );
	}

	function removeAllFiles()
	{
		m_blnHasFiles = false;

		m_arrFileList = [];
        m_intTotalFilesAdded = 0;

		os.element(m_strFormID, '.file').remove();

        checkButtons();
	}

	function updateFunction()
	{
		var strFormTitle = '';

        //if(m_strFormTitle.length > 0) {
            //strFormTitle = m_strFormTitle;
        //}
		
		if (m_strMode == "GENERIC")
		{
			strFormTitle = 'Import Files';
			os.element(m_strFormID, '.ge-function-field').val("import_generic");
			//m_strDefinable = 'N';
			//os.element(m_strFormID, '.ge-functionid-field').val(m_strFileFormatID);
		}
		else if (m_strMode == "SUBURBS")
		{
			strFormTitle = 'Import Suburbs';
            os.element(m_strFormID, '.ge-function-field').val("import_suburbs");
            //m_strDefinable = 'Y';
			//os.element(m_strFormID, '.ge-functionid-field').val(m_strFileFormatID);
		}
		else if (m_strMode == "IMAGES")
		{
			strFormTitle = 'Import Images';
			os.element(m_strFormID, '.ge-function-field').val("import_images");
			//m_strDefinable = 'N';
			//os.element(m_strFormID, '.ge-functionid-field').val(m_strFileFormatID);
        }
        else if (m_strMode == "ADDRESS")
		{
			strFormTitle = 'Import Addresses';
			os.element(m_strFormID, '.ge-function-field').val("import_address");
			//m_strDefinable = 'Y';
			//os.element(m_strFormID, '.ge-functionid-field').val(m_strFileFormatID);
		}
        else if (m_strMode == "RATESCHEDULEBUY")
		{
			strFormTitle = 'Import Buy Rate Schedules';
			os.element(m_strFormID, '.ge-function-field').val("import_rateschedulebuy");
			//m_strDefinable = 'Y';
			//os.element(m_strFormID, '.ge-functionid-field').val(m_strFileFormatID);
		}
        else if (m_strMode == "RATESCHEDULESELL")
		{
			strFormTitle = 'Import Sell Rate Schedules';
			os.element(m_strFormID, '.ge-function-field').val("import_rateschedulesell");
			//m_strDefinable = 'Y';
			//os.element(m_strFormID, '.ge-functionid-field').val(m_strFileFormatID);
		}
		
        else if (m_strMode == "SURCHARGESCHEDULEBUY")
		{
			strFormTitle = 'Import Buy Surcharge Schedules';
			os.element(m_strFormID, '.ge-function-field').val("import_surchargeschedulebuy");
			//m_strDefinable = 'Y';
			//os.element(m_strFormID, '.ge-functionid-field').val(m_strFileFormatID);
		}
        else if (m_strMode == "SURCHARGESCHEDULESELL")
		{
			strFormTitle = 'Import Sell Surcharge Schedules';
			os.element(m_strFormID, '.ge-function-field').val("import_surchargeschedulesell");
			//m_strDefinable = 'Y';
			//os.element(m_strFormID, '.ge-functionid-field').val(m_strFileFormatID);
		}

        else if (m_strMode == "ZONESCHEDULEBUY")
		{
			strFormTitle = 'Import Buy Zone Schedules';
			os.element(m_strFormID, '.ge-function-field').val("import_zoneschedulebuy");
		}
        else if (m_strMode == "ZONESCHEDULESELL")
		{
			strFormTitle = 'Import Sell Zone Schedules';
			os.element(m_strFormID, '.ge-function-field').val("import_zoneschedulesell");
		}	


        os.element(m_strFormID, '.ge-functionid-field').val(m_strFileFormatID);
		os.element(m_strFormID, '.ge-form-title').text(strFormTitle);
	}

	// ====================================================================================
	// POPULATING =========================================================================
    
    
    function populateDock()
	{
		m_objDock = new jDock(os,
			{
				"alwaysvisiblebuttoncount": 2,
                "enablemobiledropdown" : true,
				"map" : m_arrMap,
				"tiles" : m_arrTiles
			}
			);

			m_objDock.render(m_strFormID, '.ge-button-panel');
                        
	}
    
	function populateForm()
	{
		// Initialize the jQuery File Upload widget:
		var objDropzone = os.element(m_strFormID, '.ge-dropzone');
		var objUploader = os.element(m_strFormID, '.ge-uploader');
		os.element(m_strFormID, '.ge-uploader').fileupload();
		os.element(m_strFormID, '.ge-uploader').fileupload('option',
		{
			url : 'ws/upload.php',
			disableImageResize : /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
			maxFileSize : MAXUPLOADFILESIZE,
			acceptFileTypes : /(\.|\/)(gif|jpe?g|png)$/i,
			dropZone : objDropzone
		}
		);

		initialiseForm();

        populateFileTypes();

		// setup taborder
		//setTabOrder();

		// bind the uploader
		bindUploader();

        setUploaderDirty(false);

	}

    function populateFileTypes() {

        processArray(m_arrFileTypes, function (objFileType_a)
        {
            os.element(m_strFormID, '.ge-filetypes-list').append('<li><a class="ge-filetype-option gb-button" data-id="' + htmlEncode(objFileType_a.id) + '">' + htmlEncode(objFileType_a.description) + '</a></li>');
        }
        );
		os.populateList(m_strFormID, 'ge-filetypedropdown-field', m_arrFileTypes, true, false);

        var objFileType = getFileTypeByMode(m_strMode);

		if (objFileType !== undefined)
		{
            m_strFileTypeID = objFileType.id;
            m_strDefinable = objFileType.definable;
            //m_strFormTitle = 'Import ' + objFileType.description;

            os.element(m_strFormID, '.ge-filetypeselected-label').html(htmlEncode(objFileType.description));


			if (os.toBoolean(m_strDefinable))
			{
				os.element(m_strFormID, '.ge-fileformatdropdown').show();
				fetchFileFormats(); 
				
				updateFunction();		
				checkButtons();
			}
			else
			{
				os.element(m_strFormID, '.ge-fileformatdropdown').hide();
				doNothing();
			}
		}

    }

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function fileFormatsFetched(objResponse_a)
	{
		m_objFileFormats = objResponse_a;
                		        
        //populate
        os.populateList(m_strFormID, 'ge-fileformatdropdown-field', m_objFileFormats, true, false);

        if (m_objFileFormats.length > 0)
		{ 
            m_strFileFormatID = m_objFileFormats[0].id;	
            os.element(m_strFormID, '.ge-fileformatselected-label').html(m_objFileFormats[0].description);		
        }
        else
        {
            os.element(m_strFormID, '.ge-fileformatselected-label').html("File Format");
        }

        updateFunction();
        checkButtons();

        bindFileformatDropdownEvents();
	}

	function fileTypesFetched(objResponse_a)
	{
		m_arrFileTypes = objResponse_a;
		populateForm();

		// note: for some reason this click does not trigger
        /*
		if (m_blnSelect)
		{
			os.after(3000, function ()
			{
				//os.element(m_strFormID, '.ge-files-field').trigger('click');
				m_objThis.AddFilesButton_onClick();
			}
			);
		}
        */
	}

	function uploadsProcessed(arrResponse_a)
	{

        setUploadProgressIsActive(false,true,"<font color='green'><b>Files(s) successfully uploaded and processed</b></font>");

		removeAllFiles();

		m_arrResult = arrResponse_a;

        // close the form when the uploads finished

        if(m_blnAutoclose) {
            os.closeForm(m_strFormID);
        }
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function fetchData()
	{
		m_intToFetch = 1;
		m_intFetched = 0;
		m_intErrors = 0;

		fetchFileTypes();
	}

	function fetchFileFormats()
	{
		var objJSON = os.ajaxRequestCreate('import_fileformatsfetchbyfiletypeid', [
					{
						"name" : "filetype_id",
						"value" : m_strFileTypeID
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, fileFormatsFetched, m_objThis.asyncError);
	}

	function fetchFileTypes()
	{
		var objJSON = os.ajaxRequestCreate('import_filetypesfetchimport', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, fileTypesFetched, m_objThis.asyncError);
	}

	function processUploads()
	{
		//m_objDock.disableButtons('CancelUploadButton,RemoveAllButton,AddFilesButton,StartUploadButton', 'gs-darkblue-background-colour');

        setUploadProgressIsActive(true,true,"<font color='black'><b>Files(s) processing...</b></font>");

		var objJSON = os.ajaxRequestCreate('import_processuploads', []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, uploadsProcessed, os.ajaxError, afterAjaxError);

        checkButtons();
		//removeAllFiles();
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form,gb-formtitle-inner-panel,xge-filetypedropdown-field,xge-fileformatdropdown-field');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
	}

	function bindUploader()
	{
		// unbindings
		//os.unbindEvents(m_strFormID, 'ge-uploader,ge-close-button,ge-removefile-button,ge-addfilesupload-button,ge-startupload-button,ge-cancelupload-button,ge-removeallupload-button,ge-filetypedropdown-field-option');
        
        os.unbindEvents(m_strFormID, 'ge-uploader,ge-close-button,ge-filetypedropdown-field-option,ge-removefile-button,ge-addfilesupload-button');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.ge-uploader', 'Uploader', 'onFileUploadAdd');
		os.bindEvent(m_objThis, m_strFormID, '.ge-uploader', 'Uploader', 'onFileUploadDone');
		os.bindEvent(m_objThis, m_strFormID, '.ge-uploader', 'Uploader', 'onFileUploadSend');
        os.bindEvent(m_objThis, m_strFormID, '.ge-uploader', 'Uploader', 'onFileUploadProgress');
		os.bindEvent(m_objThis, m_strFormID, '.ge-uploader', 'Uploader', 'onFileUploadProgressAll');

        os.bindEvent(m_objThis, m_strFormID, '.ge-close-button', 'CloseButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-removefile-button', 'RemoveFileButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-addfilesupload-button', 'AddFilesButton', 'onClick');
        
        //os.bindEvent(m_objThis, m_strFormID, '.ge-startupload-button', 'StartUploadButton', 'onClick');
        //os.bindEvent(m_objThis, m_strFormID, '.ge-cancelupload-button', 'CancelUploadButton', 'onClick');
        //os.bindEvent(m_objThis, m_strFormID, '.ge-removeallupload-button', 'RemoveAllButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-filetypedropdown-field-option', 'FileTypeOption', 'onClick');


	}

    function bindFileformatDropdownEvents() {

        // unbindings
		os.unbindEvents(m_strFormID, 'ge-fileformatdropdown-field-option');

        //bindings
        os.bindEvent(m_objThis, m_strFormID, '.ge-fileformatdropdown-field-option', 'FileFormatOption', 'onClick');
    }

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

	this.Form_onBroadcast = function (strQueue_a, strMessage_a, objMessageData_a)
	{
		if ((strQueue_a === 'hash') && (strMessage_a === 'change'))
		{
			os.closeForm(m_strFormID); // it's out of scope (likely as a popup, so close it... behaviour can be improved in future)
		}
	};

	this.Form_onClick = function ()
	{
		os.setFormFocus(m_objThis, m_strFormID);
	};

	this.Form_onDblClick = function ()
	{
		os.formToFront(m_strFormID);
	};

	this.Form_onFocus = function ()
	{
		setTabOrder();
	};

	this.Form_onLoad = function ()
	{
		//if (m_objParameters.mode == undefined)
		//{
		//	os.element(m_strFormID, '.ge-filemode-options').show();
        //}
        
        os.element(m_strFormID, '.ge-filemode-options').show();

        if( os.toBoolean(m_objParameters.autoclose) ) {
            m_blnAutoclose = true;
        }

		// resize the form
		var intViewPortWidth = os.getViewPort().width;
		var intViewPortHeight = os.getViewPort().height;
		os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);

		populateDock();
		bindGlobals();
		fetchData();
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.FormTitle_onClick = function ()
	{
		os.formToFront(m_strFormID);
	};


    this.Form_onResize = function (intWidth_a, intHeight_a)
	{
		os.element(m_strFormID, '.gb-panel-resize').css('height', (intHeight_a - 100) + 'px');
    };

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.AddFilesButton_onClick = function ()
	{
		os.element(m_strFormID, '.ge-files-field').trigger('click');
	};

	this.CloseButton_onClick = function()
	{
		os.closeForm(m_strFormID);
	};

	this.CancelUploadButton_onClick = function ()
	{
		if (m_blnHasFiles)
		{
			if (m_objJQXHR !== undefined)
			{
				m_objJQXHR.abort();
			}

			os.element(m_strFormID, '.ge-progressallbar').hide();
			//os.element(m_strFormID, '.ge-progressalltext').hide();

			os.element(m_strFormID, '.ge-progressalltext').css(
			{
				'margin-top' : '19px'
			}
			);

            setUploadProgressIsActive(false, true, "<font color='red'><b>File(s) upload cancelled</b></font>");

            os.element(m_strFormID, '.lblStatus').html("<font color='red'><b>File(s) upload cancelled</b></font>");

			removeAllFiles();
		}
	};

	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

	this.FormResult = function ()
	{
		return m_arrResult;
	};

	//this.RemoveAllButton_onClick = function (objThis_a, objElement_a, objEvent_a, objEventData_a)
    this.RemoveAllButton_onClick = function ()
	{
		removeAllFiles();
	};

	this.RemoveFileButton_onClick = function (objThis_a, objElement_a, objEvent_a, objEventData_a)
	{
		var intArrayIdx = $(objThis_a).closest('.file').attr('arrayidx');

		// remove item from array, remove it from DOM and reorder DOM ids
		m_arrFileList.splice(intArrayIdx, 1);

		// remove from DOM
		$(objThis_a).closest('.file').remove();

		// reorder
		var intI = 0;
		os.element(m_strFormID, '.file').each(function ()
		{
			$(this).attr('arrayidx', intI);
			intI++;
		}
		);

        m_intTotalFilesAdded = m_arrFileList.length;

		if (m_arrFileList.length === 0)
		{
			//m_objDock.disableButtons('StartUploadButton', 'gs-darkblue-background-colour');
			removeAllFiles();
		}

        checkButtons();
	};

	this.StartUploadButton_onClick = function ()
	{ 
		if (m_blnHasFiles)
		{
            var arrToUploadFiles = [];

            // process files by lot based on maximum upload files
            if(m_arrFileList.length > MAXUPLOADFILES) {
                arrToUploadFiles = m_arrFileList.slice(0, MAXUPLOADFILES);

                // update array. remove elements that are are going to send for uploads
                m_arrFileList = m_arrFileList.slice( -(m_arrFileList.length - MAXUPLOADFILES) );
            }
            else {
                arrToUploadFiles = m_arrFileList;
                m_arrFileList = [];
            }

            m_intCountCurrentUploadFiles = arrToUploadFiles.length;

            setUploadProgressIsActive(true,true,"Uploading...");

			m_objJQXHR = os.element(m_strFormID, '.ge-uploader').fileupload('send',
				{
					//files : m_arrFileList
                    files : arrToUploadFiles
				}
				).error(function (objJQXHR_a, strStatus_a, strError_a)
				{
					if (strError_a === 'abort')
					{
						// done but incomplete
						//m_objDock.disableButtons('CancelUploadButton,RemoveAllButton', 'gs-darkblue-background-colour');
						//m_objDock.enableButtons('AddFilesButton,StartUploadButton', 'gs-darkblue-background-colour gs-glow-focusborder');
						//alert('File Upload has been canceled');
                        setUploaderDirty(false);

                        //os.element(m_strFormID, '.ge-startupload-button').removeClass('disabled');
                        //os.element(m_strFormID, '.ge-startupload-button').removeAttr('disabled');

					}
					else
					{
						os.dialogAlert(strError_a, function() {});
					}
				}
				);
		}
	};

	// adds a single file
	this.Uploader_onFileUploadAdd = function (objThis_a, objElement_a, objEvent_a, objEventData_a)
	{
		//os.element(m_strFormID, '.ge-progressalltext').hide();

        setUploadProgressIsActive(false,false,'');
        setProgressAllBarProgress(0);
        m_intProgressAll = 0;

		os.element(m_strFormID, '.ge-uploadlist-panel').fadeTo("fast", 1);

		var strFileListCode = "";
		var blnHasFiles = false;

        /*
		if (m_arrFileList.length >= MAXUPLOADFILES)
		{
			os.dialogAlert('The maximum files you can upload at a time is ' + MAXUPLOADFILES + '.', doNothing);
		}
		else
		{
        */
			m_arrFileList = $.merge(m_arrFileList, objEventData_a.files);

            m_intTotalFilesAdded = m_arrFileList.length;

			var intI = m_arrFileList.length - 1;
			//processArray(m_arrFileList, function(objFile_a)
			//{
			//var strFilename = objFile_a.name;
			var strFilename = m_arrFileList[intI].name;
			var intFileSize = m_arrFileList[intI].size;
			blnHasFiles = true;

            var strFileID = getGUID();

            objEventData_a.files[0].fileId = strFileID;

			strFileListCode = "<div class='file file-"+strFileID+"' arrayidx='" + intI + "'>"+
                "<table width='100%' cellpadding='0px' cellspacing='0px' style='margin-top:5px;'><tr>" +
				"<td rowspan='2' width='10%'><img src='" + DYNAMIC_APP_DIR_URL + "images/themes/default/filetypeicons/" + getDisplayIcon(strFilename.split('.').pop().toUpperCase()) + "' border='0' /></td>" +
				"<td width='84%'>" + strFilename + "&nbsp;&nbsp;" + strFilename.split('.').pop().toUpperCase() + "&nbsp;&nbsp;" + bytesToSize(intFileSize, 2) +
				"<td width='5%' style='text-align:right;'></td>" +
				"<td rowspan='2' width='1%'>&nbsp;</td>" +
				"</tr>" +
				"<tr>" +
				"<td><div class='lblStatus'><font color='black'><b>Ready to upload</b></font></div></td>" +
				"<td style='text-align:right;'><div class='ge-removefile-button' style='cursor:pointer;'><u>Remove</u></div></td>" +
				"</tr>" +
				"<tr>" +
				"<td colspan='4' style='border-bottom:1px solid #888888;'></td>" +
				"</tr></table></div>";
			//intI++;
			//});

			// add the file
			os.element(m_strFormID, '.ge-uploadfiles-field').append(strFileListCode);

            checkButtons();

			// re-bind the uploader as new content is now available
			bindUploader();

		//} // else

		//if (m_blnSelect)
		//{
		//	m_objThis.StartUploadButton_onClick();
		//}		
	};

	this.Uploader_onFileUploadDone = function ()
	{
        // if files are not yet exhausted. process next lot
        if(m_arrFileList.length > 0) {
            this.StartUploadButton_onClick();
        }
        else {

            setUploadProgressIsActive(false,true,"<font color='green'><b>File(s) uploaded successfully</b></font>");

            setProgressAllBarProgress(100);

            os.element(m_strFormID, '.ge-removefile-button').show();

            processUploads();
        }
	};

	this.Uploader_onFileUploadSend = function ()
	{
        setUploaderDirty(false);
        //os.element(m_strFormID, '.ge-cancelupload-button').removeClass('disabled');
        //os.element(m_strFormID, '.ge-cancelupload-button').removeAttr('disabled');

	};

    this.Uploader_onFileUploadProgress = function (objThis_a, objElement_a, objEvent_a, objEventData_a)
	{
        var progress = 0; // = parseInt(objEventData_a.loaded / objEventData_a.total * 100, 10);

        processArray(objEventData_a.files,function(objFile_a) {

            progress = parseInt(objEventData_a.loaded / objEventData_a.total * 100, 10);

            os.element(m_strFormID, '.file-'+objFile_a.fileId).find('.progress .bar').css('width', progress+'%');

        });
    };

	this.Uploader_onFileUploadProgressAll = function (objThis_a, objElement_a, objEvent_a, objEventData_a)
	{
		var progress = parseInt(objEventData_a.loaded / objEventData_a.total * 100, 10);

        // we need to calculate the overall progress
        // that will include all added files and not only the progress of current uploaded file ( per lot - eg. 20)

        m_intProgressAll += parseInt( (m_intCountCurrentUploadFiles / m_intTotalFilesAdded) * progress, 10);

        // sometimes progress goes beyond 100%.
        if(m_intProgressAll > 100) {
           m_intProgressAll = 100;
        }

        setProgressAllBarProgress(m_intProgressAll);

        os.element(m_strFormID, '.lblStatus').html("<font color='black'><b>Uploading...</b></font>");

	};

    this.FileFormatOption_onClick = function(objThis_a) {
        var objThis = os.element(objThis_a);

        m_strFileFormatID = objThis.attr('value');
        os.element(m_strFormID, '.ge-fileformatselected-label').html(objThis.html());

        updateFunction();
	};

    this.FileTypeOption_onClick = function(objThis_a) {

        var objThis = os.element(objThis_a);

        m_strFileTypeID = objThis.attr('value');
        os.element(m_strFormID, '.ge-filetypeselected-label').html(objThis.html());

		var objFileType = getFileTypeByFileTypeID(m_strFileTypeID);
		if (objFileType !== undefined)
		{
			m_strMode = objFileType.code;
            m_strDefinable = objFileType.definable;
            //m_strFormTitle = 'Import ' + objFileType.description;

            m_strFileFormatID = '';
			m_objFileFormats = [];
		
            if (m_strFileTypeID.length > 0)
            {
                if (os.toBoolean(m_strDefinable))
                {
                    os.element(m_strFormID, '.ge-fileformatdropdown').show();
                    fetchFileFormats();
                }
                else
                {   
                    os.element(m_strFormID, '.ge-fileformatdropdown').hide();
                    os.populateList(m_strFormID, 'ge-fileformatdropdown-field', m_objFileFormats, true, false);
                    os.element(m_strFormID, '.ge-fileformatselected-label').html("File Format");
                    checkButtons();
                    updateFunction();
                }
            }
            else
            {
                os.populateList(m_strFormID, 'ge-fileformatdropdown-field', m_objFileFormats, true, false);
                os.element(m_strFormID, '.ge-fileformatselected-label').html("File Format");
                checkButtons();
                updateFunction();
            }            
        }
    };

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,CloseButton,AddFileButton,StartUploadButton,CancelUploadButton,RemoveAllFilesButton,' + m_strFormFields + ',ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		os.element(m_strFormID, '.CloseButton').focus();
	};

	this.TabStart_onFocus = function ()
	{
		os.element(m_strFormID, '.ge-fileformatdropdown-field').focus();
	};
}