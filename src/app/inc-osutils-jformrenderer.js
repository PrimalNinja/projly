/*jsl:option explicit*/
/*jsl:import inc-osutils.js*/
/*jsl:import inc-osutils-classes.js*/
/*jsl:import inc-osutils-jeditor.js*/
/*jsl:import inc-osutils-jcodeeditor.js*/

// ====================================================================================
// CroakOS Utils v20241106 ============================================================
// copyright(C) 2012-2024 Mitsukibo, Julian Cassin & Francis Weston. All rights reserved.

function jFormRenderer(objOS_a, objOptions_a, objParentForm_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_blnDirty = false;
    var m_strFormID = '';
    var m_strFormElementClass = '';
	var m_strFocusField = '';
	var m_objParentForm = null;

	var SOURCENOFILTERLENGTH = 2;
	
	var m_blnInvalidDate = false;
	var m_strInvalidDates = "";

	// default options
	var m_objOptions =
	{
		flags: '',
		formified: false,
		htmlmode: false,
		printpreview : false,
		readonly : true,
		showaccordion: false,
		style : 'accordion'
	};
	
	if (objParentForm_a !== undefined)
	{
		m_objParentForm = objParentForm_a;
	}

	if (objOptions_a.flags !== undefined)
	{
		m_objOptions.flags = objOptions_a.flags;
	}

	if (objOptions_a.formified !== undefined)
	{
		m_objOptions.formified = objOptions_a.formified;
	}

	if (objOptions_a.htmlmode !== undefined)
	{
		m_objOptions.htmlmode = objOptions_a.htmlmode;
	}

	if (objOptions_a.printpreview !== undefined)
	{
		m_objOptions.printpreview = objOptions_a.printpreview;
	}

	if (objOptions_a.readonly !== undefined)
	{
		m_objOptions.readonly = objOptions_a.readonly;
	}

	if (objOptions_a.showaccordion !== undefined)
	{
		m_objOptions.showaccordion = objOptions_a.showaccordion;
	}

	if (objOptions_a.style !== undefined)
	{
		m_objOptions.style = objOptions_a.style; // should be able to cater for accordion or tabs
	}
	
	var m_blnPrinters = ((!m_objOptions.readonly) && (os.hasCapability('printer') && os.isPrinterRegistered()));

	var m_cbRenderComplete = objOptions_a.cbRenderComplete;
	var m_cbSetDirty = objOptions_a.cbSetDirty;

	var m_arrFormSections = [];
	var m_arrDataSources = [];
	var m_arrABNLists = [];
	var m_intABNListID = 0;
    var m_arrPredictiveTextSources = [];
    var m_intPredictiveTextSourceID = 0;
	var m_intDataSourceID = 0;
	var m_arrHTMLEditors = [];
    var m_arrCodeEditors = [];
    var m_arrMetaData = [];
	var m_strID = '';
	var m_strDataID = '';
	var m_strFormFields = 'formtitle';
	var m_strDirtyFields = '';

	// ====================================================================================
	// HELPERS ============================================================================

	// note: internal to class private helper functions

	function changeURLLink(strFieldName_a,strValue_a)
	{
	   var strURL = '';
	   strURL = strValue_a;
       if ((os.str_left(strURL, 7).toLowerCase() !== 'http://') && (os.str_left(strURL, 8).toLowerCase() !== 'https://'))
       {
            strURL = 'http://' + strURL;
       }

	   os.element(m_strFormID, '.' + htmlEncode(strFieldName_a)).attr('href', strURL);
	}

	function fixValueDescription(strValue_a, strValueDescription_a, objSelection_a)
	{
		var strResult = '';

		if (strValueDescription_a.length > 0)
		{
			strResult = strValueDescription_a;
		}
		else if (strValue_a.length > 0)
		{
			// look up the value description as we don't have it (isn't supported on some version of QIMS on Android & IOS)
			processArray(objSelection_a, function (objOption_a)
			{
				if(strValue_a == objOption_a.p_code)
				{
					strResult = objOption_a.p_value;
					return true;
				}
			}
			);
		}

		return strResult;
	}

	function getFormHeaderField(strFieldName_a)
	{
		var strResult = '';

        processArray(m_arrFormSections, function (objSection_a)
        {
            if (objSection_a.sectiontype.toUpperCase() === 'FORMHEADER')
            {
                processArray(objSection_a.fields, function (objField_a)
                {
                    var strPName = massagePName(objSection_a.sectiontype, objField_a.p_name);
					var strFieldName = massagePName('FORMHEADER', strFieldName_a);

                    if (strPName.toUpperCase() === strFieldName.toUpperCase())
                    {
                        strResult = objField_a.p_value;
						return true;
                    }
                }
                );
            }
        }
        );

		return strResult;
	}

	function getFormName()
	{
		return getFormHeaderField('DESCRIPTION');
	}

	function getListIDByDescription(arrList_a, strDescription_a)
	{
		var strResult = '';

		processArray(arrList_a, function (objItem_a)
		{
			if (objItem_a.description == strDescription_a)
			{
				strResult = objItem_a.id;
				return true;
			}
		}
		);

		return strResult;
	}

	function massageFilename(strFilename_a)
	{
		var strResult = strFilename_a;
		
		strResult = str_replace(strResult, "'", '');
		strResult = str_replace(strResult, '"', '');

		return strResult;
	}
	
	function massagePName(strSectionName_a, strPName_a)
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
	}

	function populateABNLookup()
	{
	    processArray(m_arrABNLists, function(objABNList_a)
        {
            var m_objBusinessNameFinder = new abnFinder(os, m_strFormID, htmlEncode(objABNList_a.fieldname) + '_ABN', htmlEncode(objABNList_a.fieldname) + '_COMPANYNAME', 'BusinessName', true, htmlEncode(objABNList_a.fieldname) + '-ge-abnsearch-button');

            m_objBusinessNameFinder.bind();
        });
	}

    function populatePredictiveText()
    {
        processArray(m_arrPredictiveTextSources, function(objPredictiveTextSource_a)
        {
            objPredictiveTextSource_a.finder = new predictiveTextFinder(os, m_strFormID, m_objThis, objPredictiveTextSource_a.sourceid);
            objPredictiveTextSource_a.finder.bind();
        });
    }

	function populateCheckbox()
    {
        processArray(m_arrDataSources, function(objDataSource_a)
        {
			var arrSource = [];
			var strSource = "";
			var arrFields = ["description"];

			if (objDataSource_a.source.length > 0)
			{
				arrSource = objDataSource_a.source.split("|");
			}

			if (arrSource.length > 0)

			{
				strSource = arrSource[0];
			}

			if (arrSource.length > 1)
			{
				var strFields = arrSource[1];
				strFields = str_replace(strFields, "'", '"');		// form builder didn't like " so we used '
				arrFields = JSON.parse(strFields);
			}

            var strHTML = '';
            var strName = os.element(m_strFormID, '.' + objDataSource_a.sourceid + 'cb').attr('name');

            var arrValue = objDataSource_a.selected;
            
            if(arrValue === null)
            {
                arrValue = [];
            }
            
            processArray(objDataSource_a.data, function (objData_a)
            {
                var strSelected = '';
                if(arrValue.length > 0)
                {
					processArray(arrValue, function (strValue_a)
					{
						if (strSource === "SYSTEMPRINTERS")
						{
							if(  htmlEncode(strValue_a) === htmlEncode(objData_a.description) )
							{
								strSelected = 'checked="checked"';
							}
						}
						else
						{
							if(  htmlEncode(strValue_a) === htmlEncode(objData_a.id) )
							{
								strSelected = 'checked="checked"';
							}
						}
					});
                }

				var strFieldHTML = "";
				processArray(arrFields, function(strField_a)
				{
					if (strFieldHTML.length > 0)
					{
						strFieldHTML += " | ";
					}
					strFieldHTML += htmlEncode(objData_a[strField_a]);
				});

                strHTML += '<label><input type="checkbox" class=" ' + htmlEncode(strName) + '" value="' + htmlEncode(objData_a.id) + '" checkboxlabel="' + strFieldHTML +'" name="' + htmlEncode(strName) + '" ' + strSelected + '><span style="margin-right:10px;"> ' + htmlEncode(objData_a.description) + '</span></label>';
            }
            );

            os.element(m_strFormID, '.' + objDataSource_a.sourceid + 'cb').html(strHTML);
        });
    }

	function populateDropdowns()
	{   
		processArray(m_arrDataSources, function(objDataSource_a)
		{
			var arrSource = [];
			var strSource = "";
			var arrFields = ["description"];
            
			if (objDataSource_a.source.length > 0)
			{
				arrSource = objDataSource_a.source.split("|");
			}

			if (arrSource.length > 0)
			{
				strSource = arrSource[0];
			}

			if (arrSource.length > 1)
			{
				var strFields = arrSource[1];
				strFields = str_replace(strFields, "'", '"');		// form builder didn't like " so we used '
				arrFields = JSON.parse(strFields);
			}

			var strHTML = '<option value=" "></option>';
            var objField = os.element(m_strFormID, '.' + objDataSource_a.sourceid + 'dd');
            var strValue = objField.val();

			processArray(objDataSource_a.data, function(objData_a)
			{
				var strSelected = '';

				if (strSource === "SYSTEMPRINTERS")
				{
					if (m_blnPrinters)
					{
						//alert("populateDropdowns");
						if( (htmlEncode(objDataSource_a.selected) === htmlEncode(objData_a.description)) || strValue === objData_a.description)
						{
							strSelected = 'selected';
						}
					}
					else
					{
						doNothing();
					}
				}
				else
				{
					if( (htmlEncode(objDataSource_a.selected) === htmlEncode(objData_a.id)) || strValue === objData_a.id)
					{
						strSelected = 'selected';
					}
				}

				var strFieldHTML = "";
				processArray(arrFields, function(strField_a)
				{
					if (strFieldHTML.length > 0)
					{
						strFieldHTML += " | ";
					}
					strFieldHTML += htmlEncode(objData_a[strField_a]);
				});

				strHTML += '<option value="' + htmlEncode(objData_a.id) + '" ' + strSelected + '>' + strFieldHTML + '</option>';
			});
            
            objField.html(strHTML);
            
		});
	}

	function populateDropdownsMultiple()
    {
        processArray(m_arrDataSources, function(objDataSource_a)
        {
			var arrSource = [];
			var strSource = "";
			var arrFields = ["description"];

			if (objDataSource_a.source.length > 0)
			{
				arrSource = objDataSource_a.source.split("|");
			}

			if (arrSource.length > 0)
			{
				strSource = arrSource[0];
			}

			if (arrSource.length > 1)
			{
				var strFields = arrSource[1];
				strFields = str_replace(strFields, "'", '"');		// form builder didn't like " so we used '
				arrFields = JSON.parse(strFields);
			}

            var arrValue = objDataSource_a.selected;
            var strHTML = '<option value=" "></option>';

            if(arrValue === null)
            {
                arrValue = [];
            }

            processArray(objDataSource_a.data, function(objData_a)
            {
                var strSelected = '';
                if(arrValue.length > 0)
                {
					processArray(arrValue, function (strValue_a)
					{
						if (strSource === "SYSTEMPRINTERS")
						{
							// alert("populateDropdownsMultiple");
							if( htmlEncode(strValue_a) === htmlEncode(objData_a.description) )
							{
								strSelected = 'selected';
							}
						}
						else
						{
							if( htmlEncode(strValue_a) === htmlEncode(objData_a.id) )
							{
								strSelected = 'selected';
							}
						}
					});
                }

				var strFieldHTML = "";
				processArray(arrFields, function(strField_a)
				{
					if (strFieldHTML.length > 0)
					{
						strFieldHTML += " | ";
					}
					strFieldHTML += htmlEncode(objData_a[strField_a]);
				});

                strHTML += '<option value="' + htmlEncode(objData_a.id) + '" ' + strSelected + '>' + strFieldHTML + '</option>';
            });

            os.element(m_strFormID, '.' + objDataSource_a.sourceid + 'md').html(strHTML);
        });
    }

	function populateRadiobuttons()
    {
        processArray(m_arrDataSources, function(objDataSource_a)
        {
			var arrSource = [];
			var strSource = "";
			var arrFields = ["description"];

			if (objDataSource_a.source.length > 0)
			{
				arrSource = objDataSource_a.source.split("|");
			}

			if (arrSource.length > 0)
			{
				strSource = arrSource[0];
			}

			if (arrSource.length > 1)
			{
				var strFields = arrSource[1];
				strFields = str_replace(strFields, "'", '"');		// form builder didn't like " so we used '
				arrFields = JSON.parse(strFields);
			}

            var strHTML = '';
			var strName = os.element(m_strFormID, '.' + objDataSource_a.sourceid + 'rb').attr('name');

            processArray(objDataSource_a.data, function (objData_a)
            {
                var strSelected = '';
				if (strSource === "SYSTEMPRINTERS")
				{
					// alert("populateRadiobuttons");
					if(htmlEncode(objDataSource_a.selected) === htmlEncode(objData_a.description))
					{
						strSelected = 'checked';
					}
				}
				else
				{
					if(htmlEncode(objDataSource_a.selected) === htmlEncode(objData_a.id))
					{
						strSelected = 'checked';
					}
				}

				var strFieldHTML = "";
				processArray(arrFields, function(strField_a)
				{
					if (strFieldHTML.length > 0)
					{
						strFieldHTML += " | ";
					}
					strFieldHTML += htmlEncode(objData_a[strField_a]);
				});

				strHTML += '<label><input type="radio" class=" ' + htmlEncode(strName) + '" value="' + htmlEncode(objData_a.id) + '" radiolabel="' + strFieldHTML +'" name="' + htmlEncode(strName) + '" ' + strSelected + '><span style="margin-right:10px;"> ' + htmlEncode(objData_a.description) + '</span></label>';
            }
            );

            os.element(m_strFormID, '.' + objDataSource_a.sourceid + 'rb').html(strHTML);
        });
    }

    function selectDocument(strName_a)
    {
        var arrFilter = [];
        var objParams = { type:'form', entity:'systemform', formentity:'document', mode:'select', title:'Document Selection', fixedfilter:arrFilter, resultfields : ['id', 'description', 'tagtype', 'tag', 'result'] };
		os.showFormPopup('entity.frmEntityChooser', objParams, function (objResult_a)
        {

            if (objResult_a.length > 0)
            {
                var blnDocumentsAdded = false;
                var strDocumentValue = '';
                var objDocumentValue;

                processArray(objResult_a, function(objDocument_a) {
                    //if ((objDocument_a.result === 'COMPLETED') && (objDocument_a.tagtype === 'documentid')) {
                    if( objDocument_a.id !== undefined) {

                        //strDocumentValue = objDocument_a.id + ';' + objDocument_a.description;

                        objDocumentValue = { "documentid" : objDocument_a.id, "filename" : massageFilename(objDocument_a.description) };

                        addDocument(strName_a, objDocumentValue);

                        blnDocumentsAdded = true;
                    }
                });

                if (blnDocumentsAdded)
                {
					m_objThis.raiseRendererEvent('Document', m_strFormID, '.' + strName_a, 'onChange');
                    setDirty();
                    populateDocuments(strName_a, false);
                }
            }
        }, false, true);
    }
	
	function setDirty()
	{
		if (!m_blnDirty)
		{
			if (!m_objOptions.readonly)
			{
				m_blnDirty = true;
				if ($.isFunction(m_cbSetDirty))
				{
					m_cbSetDirty(true);
				}
			}
		}
	}

    function addDocument(strDocumentWrapperName_a, objDocumentValue_a) 
	{
        var objElementDocument = os.element(m_strFormID, '.' + htmlEncode(strDocumentWrapperName_a));
        var strDocumentsValue = objElementDocument.val();
        var arrObjDocuments = [];

        if(strDocumentsValue.length > 0) {
           //strDocumentsValue += ',' + strDocumentValue_a;

           arrObjDocuments = JSON.parse(strDocumentsValue);
        }
        /*
        else {
           strDocumentsValue = strDocumentValue_a;
        }
        */

        arrObjDocuments.push(objDocumentValue_a);

       objElementDocument.val( JSON.stringify(arrObjDocuments) );

		m_objThis.raiseRendererEvent('Document', m_strFormID, '.' + htmlEncode(strDocumentWrapperName_a), 'onChange');
		setDirty();

    }

    function createDocumentLink(strDocumentWrapperName_a, strDocumentID_a, strDocumentDescription_a, blnReadonly_a, blnPrintPreview_a)
	{
        if(blnPrintPreview_a === undefined) {
           blnPrintPreview_a = false;
        }

        /*
        var strDocumentLink  = '<span class="ge-documentlink">';
            strDocumentLink += '<a style="cursor: pointer;" class="fb-view-button document-' + htmlEncode(strDocumentWrapperName_a) + '" documentid="' + htmlEncode(strDocumentID_a) + '" >' + htmlEncode(strDocumentDescription_a) + '</a>';

            if(!blnPrintPreview_a) {
                strDocumentLink += '&nbsp;<span title="Download" style="cursor: pointer; color:#069;" class="fb-download-button" documentid="' + htmlEncode(strDocumentID_a) + '" ><i class="glyphicon glyphicon glyphicon-circle-arrow-down" ></i></span>';
				if (!blnReadonly_a)
				{
					strDocumentLink += '&nbsp;<span title="Remove" style="cursor: pointer; color:red;" class="ge-d_document-remove-button" document="' + htmlEncode(strDocumentWrapperName_a) + '" documentid="' + htmlEncode(strDocumentID_a) + '"><i class="glyphicon glyphicon-remove"></i></span>';
				}
            }

            strDocumentLink += '</span><br>';
        */

        var strDocumentLink = '<button class="form-control fb-view-button document-' + htmlEncode(strDocumentWrapperName_a) + '" documentid="' + htmlEncode(strDocumentID_a) + '" >';
            strDocumentLink += '<span>' + strDocumentDescription_a + '</span>';

            if(!blnPrintPreview_a) 
            {
                strDocumentLink += '&nbsp;<span title="Download" style="cursor: pointer; color:#069;" class="fb-download-button" documentid="' + htmlEncode(strDocumentID_a) + '" ><i class="glyphicon glyphicon glyphicon-circle-arrow-down"></i></span>';
            }

            strDocumentLink += '</button>';

            if(!blnPrintPreview_a && !blnReadonly_a) 
            {
                strDocumentLink += '&nbsp;&nbsp;';
                strDocumentLink += '<a><span title="Remove" style="cursor: pointer; color:red;" class="ge-d_document-remove-button" document="' + htmlEncode(strDocumentWrapperName_a) + '" documentid="' + htmlEncode(strDocumentID_a) + '"><i class="glyphicon glyphicon-remove"></i></span></a>';
            }
            
        return strDocumentLink;
    }

    function populateDocuments(strDocumentWrapperName_a, blnPrintPreview_a) {

        var objElementDocument = os.element(m_strFormID, '.' + htmlEncode(strDocumentWrapperName_a));

        var strDocumentsValue = objElementDocument.val();

        if(strDocumentsValue.length > 0) {

            //var arrDocuments = strDocumentsValue.split(',');
            var arrDocuments = JSON.parse(strDocumentsValue);

            var arrDocumentLinks = [];
            var arrDocumentInfo;
            var strDocumentID;
            var strDocumentDescription;

            processArray(arrDocuments, function(objDocument_a){

                //arrDocumentInfo = strDocument_a.split(';');

                strDocumentID = objDocument_a.documentid;
                strDocumentDescription = objDocument_a.filename; //arrDocumentInfo[1];

                arrDocumentLinks.push( createDocumentLink(strDocumentWrapperName_a, strDocumentID, strDocumentDescription, false, blnPrintPreview_a) );
            });

            os.element(m_strFormID, '.documentDL-' + htmlEncode(strDocumentWrapperName_a)).html( arrDocumentLinks.join("&nbsp;&nbsp;") );
        }
        else {
            os.element(m_strFormID, '.documentDL-' + htmlEncode(strDocumentWrapperName_a)).html("");
        }


        bindForm();
    }

	function processPlaceholders(strValue_a)
	{
		var strResult = strValue_a;

		if (strResult === undefined)
		{
			strResult = '';
		}

		if (typeof(strResult) === "string")
		{
			var strClient = os.getProperty('client');
			if (strClient === undefined)
			{
				strClient = 'none';
			}

			var strLogin = os.getProperty('login');
			if (strLogin === undefined)
			{
				strLogin = 'none';
			}

			var strMapsProvider = os.getMapProvider();
			if (strMapsProvider.length === 0)
			{
				strMapsProvider = 'none';
			}
			var strSpeechProvider = os.getSpeechProvider();
			if (strSpeechProvider.length === 0)
			{
				strSpeechProvider = 'none';
			}
			var strPrinterProvider = os.getPrinterVersion();
			if (strPrinterProvider.length === 0)
			{
				strPrinterProvider = 'none';
			}

			strResult = str_replace(strResult, '[AWAF_AGENT]', os.getAgent());	// eg: Mozilla 5.0/...
			strResult = str_replace(strResult, '[AWAF_APPNAME]', APP_SHORT_NAME);	// eg: QIMS
			strResult = str_replace(strResult, '[AWAF_APPVERSION]', APP_VERSION);	// eg: 1.0
			strResult = str_replace(strResult, '[AWAF_BROWSERCAPABILITIES]', os.getBrowserCapabilities());	// eg: canvas zoom...
			strResult = str_replace(strResult, '[AWAF_BROWSERRECOMMENDATION]', os.getBrowserRecommendation());	// eg: upgrade your browser.
			strResult = str_replace(strResult, '[AWAF_BROWSERSUPPORTLEVEL]', os.getBrowser().support);	// eg: best
			strResult = str_replace(strResult, '[AWAF_BROWSERVERSION]', os.getBrowserVersion() + ' (' + os.getBrowser().major + ')');	// eg: 60(60)
			strResult = str_replace(strResult, '[AWAF_CLIENTBROWSER]', os.getBrowser().name);	// eg: Google Chrome
			strResult = str_replace(strResult, '[AWAF_CLIENTOS]', os.getOS());	// eg: Windows
			strResult = str_replace(strResult, '[AWAF_CLIENT]', strClient);	// eg: system
			strResult = str_replace(strResult, '[AWAF_HEALTH]', os.getHealth());	// eg: an html table full of details
			strResult = str_replace(strResult, '[AWAF_LOGIN]', strLogin);		// eg: sysadmin
			strResult = str_replace(strResult, '[AWAF_MAPSPROVIDER]', strMapsProvider);	// eg: google
			strResult = str_replace(strResult, '[AWAF_PRINTERPROVIDER]', strPrinterProvider);	// eg: wgtPrinter
			strResult = str_replace(strResult, '[AWAF_RESOLUTION]', screen.width + 'x' + screen.height);
			strResult = str_replace(strResult, '[AWAF_SPEECHPROVIDER]', strSpeechProvider);	// eg: google
			strResult = str_replace(strResult, '[AWAF_VIEWPORT]', os.getViewPort().width + 'x' + os.getViewPort().height);
		}

		return strResult;
	}

    function removeDocument(objThis_a, strDocumentWrapperName_a, strDocumentID_a) {

        var objElementDocument = os.element(m_strFormID, '.' + htmlEncode(strDocumentWrapperName_a));

        var strDocumentsValue = objElementDocument.val();

        if(strDocumentsValue.length > 0) {

            var arrDocuments = JSON.parse(strDocumentsValue);

            var intIndex = -1;

            processArray(arrDocuments, function(objDocument_a, intI_a, intJ_a){

                if(objDocument_a.documentid === strDocumentID_a) {
                    intIndex = intJ_a;
                    return;
                }
            });

            if(intIndex > -1) {

                arrDocuments.splice(intIndex,1);

                if(arrDocuments.length > 0) {
                    objElementDocument.val( JSON.stringify(arrDocuments) );
                }
                else {
                    objElementDocument.val("");
                }
            }

			m_objThis.raiseRendererEvent('Document', m_strFormID, '.' + htmlEncode(strDocumentWrapperName_a), 'onClear');
			m_objThis.raiseRendererEvent('Document', m_strFormID, '.' + htmlEncode(strDocumentWrapperName_a), 'onChange');
            setDirty();
        }
    }

	function showImage(strImageID_a, strName_a)
    {
        var strURL = '';

        if (strImageID_a !== "")
        {
            strURL = 'fetch.php?token=' + encodeURIComponent(SECURITY_TOKEN) + '&image=' + encodeURIComponent(strImageID_a);
        }
        else
        {
            strURL = 'images/noimage.png';
        }
        os.element(m_strFormID, '.' + htmlEncode(strName_a)).attr('src', strURL);
        os.element(m_strFormID, '.' + htmlEncode(strName_a)).attr('documentid', htmlEncode(strImageID_a));
        os.element(m_strFormID, '.' + htmlEncode(strName_a)).val(htmlEncode(strImageID_a));
    }

	function showNoImageAvailable(strName_a)
    {
        var strURL = 'images/noimage.png';

        os.element(m_strFormID, '.' + htmlEncode(strName_a)).attr('src', strURL);
    }


	function updateJSONwithElementValues()
	{
        processArray(m_arrFormSections, function (objSection_a)	// for each section
        {
            if (objSection_a.sectiontype.toUpperCase() === 'FORMHEADER' || objSection_a.sectiontype.toUpperCase() === 'DATAHEADER')
            {
                doNothing();
            }
            else
            {
				// if section types is one of DATA, INTERNALUSEFORMHEADER, INTERNALUSEONLY
                if ((objSection_a.sectiontype.toUpperCase() === 'DATA') || (objSection_a.sectiontype.toUpperCase() === 'INTERNALUSEFORMHEADER' && hasPermission(['IUSE_DATAFORM'])) || (objSection_a.sectiontype.toUpperCase() === 'INTERNALUSEONLY' && hasPermission(['IUSE_DATAFORM'])))
                {
					// for each field in the section
                    processArray(objSection_a.fields, function (objField_a)
                    {
						var blnReadonly = os.toBoolean(objField_a.p_readonly);
                        var strPName = massagePName(objSection_a.sectioncode, objField_a.p_name);
                        var strDataType = objField_a.p_datatype;
						var strLabel = objField_a.p_label;
						var strStyle = objField_a.p_style;

						try
						{
							var blnHidden = false;
							if (isNullOrUndefined(strStyle))
							{
								strStyle = '';
							}
							strStyle = ' ' + strStyle + ' ';

							if (strStyle.indexOf(' gb-hidden ') >= 0)
							{
								blnHidden = true;
							}
						}
						catch (err)
						{
							doException('updateJSONwithElementValues', err);
						}

						if ((!blnHidden) && (!blnReadonly))
						{
							var strSource = objField_a.p_source;
							var strPredictiveTextValue = '';

							if (strSource === undefined)
							{
								strSource = '';
							}

							objField_a.p_valuedescription = '';
							if (strDataType === 'd_abnlookup')
							{
								var strABNValue = os.element(m_strFormID, '.' + htmlEncode(strPName) + '_ABN').val();
								var strBusinessNameValue = os.element(m_strFormID, '.' + htmlEncode(strPName) + '_COMPANYNAME').val();
								var arrABNLookupValue  = {"companyvalue": strBusinessNameValue, "abnvalue": strABNValue };
								objField_a.p_value = arrABNLookupValue;
							}
							else if (strDataType === 'd_date')
							{
								var strValue = os.element(m_strFormID, '.' + htmlEncode(strPName)).val();
								if (strValue.length > 10)
								{
									strValue = os.str_left(strValue, 10);
								}
								strValue = os.convertDateToISO(strLabel, strValue, doNothing);
								objField_a.p_value = strValue;
							}
							else if (strDataType === 'd_representation')
							{
								if ((objField_a.p_classes.indexOf('d_singleradio') >= 0) || (objField_a.p_style === 'd_singleradio'))
								{
									objField_a.p_value = os.element(m_strFormID, '.' + htmlEncode(strPName) + ':checked').val();
									objField_a.p_valuedescription = os.element(m_strFormID, '.' + htmlEncode(strPName) + ':checked').attr('radiolabel');
								}
								else if ((objField_a.p_classes.indexOf('d_singledropdown') >= 0) || (objField_a.p_style === 'd_singledropdown'))
								{
									objField_a.p_value = os.element(m_strFormID, '.' + htmlEncode(strPName)).val();
									objField_a.p_valuedescription = os.element(m_strFormID, '.' + htmlEncode(strPName) + ' option:selected').text();
								}
                                // else if (objField_a.p_classes.indexOf('d_predictivetext') >= 0)
                                // {
                                    // strPredictiveTextValue = os.element(m_strFormID, '.' + htmlEncode(strPName) ).val();
                                    // objField_a.p_value = strPredictiveTextValue;
                                    // objField_a.p_valuedescription = strPredictiveTextValue;
                                // }
								else
								{
									objField_a.p_value = os.element(m_strFormID, '.' + htmlEncode(strPName + 'epv')).val();
									objField_a.p_valuedescription = os.element(m_strFormID, '.' + htmlEncode(strPName + 'epd')).val();
								}
							}
							else if (strDataType === 'd_list')
							{
								if ((objField_a.p_classes.indexOf('d_singleradio') >= 0) || (objField_a.p_style === 'd_singleradio'))
								{
									objField_a.p_value = os.element(m_strFormID, '.' + htmlEncode(strPName) + ':checked').val();
									objField_a.p_valuedescription = os.element(m_strFormID, '.' + htmlEncode(strPName) + ':checked').attr('radiolabel');
								}
								else if ((objField_a.p_classes.indexOf('d_singledropdown') >= 0) || (objField_a.p_style === 'd_singledropdown'))
								{
									objField_a.p_value = os.element(m_strFormID, '.' + htmlEncode(strPName)).val();
									objField_a.p_valuedescription = os.element(m_strFormID, '.' + htmlEncode(strPName) + ' option:selected').text();
									// to cater for lookups converted to textboxes such as printers, may not be the ideal fix
									if (objField_a.p_valuedescription.length === 0)
									{
										objField_a.p_valuedescription = objField_a.p_value;
									}
								}
                                // else if (objField_a.p_classes.indexOf('d_predictivetext') >= 0)
                                // {
                                    // strPredictiveTextValue = os.element(m_strFormID, '.' + htmlEncode(strPName) ).val();
                                    // objField_a.p_value = strPredictiveTextValue;
                                    // objField_a.p_valuedescription = strPredictiveTextValue;
                                // }
								else
								{
									objField_a.p_value = os.element(m_strFormID, '.' + htmlEncode(strPName + 'epv')).val();
									objField_a.p_valuedescription = os.element(m_strFormID, '.' + htmlEncode(strPName + 'epd')).val();
								}
							}
							else if (strDataType === 'd_multilist')
							{
								var arrSelectedValue  = [];
								var arrSelectedDecription = [];

								if ((objField_a.p_classes.indexOf('d_multicheckbox') >= 0) || (objField_a.p_style === 'd_multicheckbox'))
								{
									os.element(m_strFormID, '.' + htmlEncode(strPName) + ' :checkbox').each(function () {
										   if(this.checked)
										   {
												arrSelectedValue.push($(this).val());
												arrSelectedDecription.push($(this).attr('checkboxlabel'));
										   }
									  });

									objField_a.p_value =  arrSelectedValue;
									objField_a.p_valuedescription = arrSelectedDecription;
								}
								else
								{
									arrSelectedValue = os.element(m_strFormID, '.' + htmlEncode(strPName)).val();

									os.element(m_strFormID, '.' + htmlEncode(strPName) + ' option:selected').each(function()
									{
										arrSelectedDecription.push($(this).text());
									});
                                    
                                    if(arrSelectedValue === null)
                                    {
                                        objField_a.p_value = [];
                                    }
                                    else
                                    {
                                        objField_a.p_value = arrSelectedValue;
                                    }

                                    if(arrSelectedDecription === null)
                                    {
                                        objField_a.p_valuedescription = [];
                                    }
                                    else
                                    {
                                        objField_a.p_valuedescription = arrSelectedDecription;
                                    }
								}

							}
							else if (strDataType === 'd_html')
							{
								objField_a.p_value = getHTMLEditorContent(strPName);
							}
                            else if (strDataType === 'd_codeeditor')
                            {
                                objField_a.p_value = getCodeEditorContent(strPName);
                            }
							else if (strDataType === 'd_multilinetext')
							{
								objField_a.p_value = os.element(m_strFormID, '.' + htmlEncode(strPName)).val();
							}
							else if (strDataType === 'd_yesno')
							{
								if (os.element(m_strFormID, '.' + htmlEncode(strPName)).is(":checked") === true)
								{
									objField_a.p_value = 'Y';
								}
								else
								{
									objField_a.p_value = 'N';
								}
							}
							else if (strDataType === 'd_document')
							{

								var strTempPValue = os.element(m_strFormID, '.' + htmlEncode(strPName)).val();
								var arrObjPValue = [];

								if(strTempPValue.length > 0) {
									arrObjPValue = JSON.parse(strTempPValue);
								}

								objField_a.p_value = arrObjPValue;

							}
							else
							{
								objField_a.p_value = os.element(m_strFormID, '.' + htmlEncode(strPName)).val();
							}
						}
                    });
                }
                else
                {
                    doNothing();
                }
            }
        }
        );
	}

	function uploadDocument(strName_a)
    {
        os.showFormPopup('core.frmUpload', 'mode=generic&flags=select%20nobatch&autoclose=true', function (objResult_a)
        {
            if (m_objOptions.readonly === false)
            {
                if (objResult_a.length > 0)
                {
                    var blnDocumentsAdded = false;
                    var strDocumentValue;
                    var objDocumentValue;

                    processArray(objResult_a, function(objDocument_a) {

                        if ((objDocument_a.result === 'COMPLETED') && (objDocument_a.tagtype === 'documentid')) {

                            //strDocumentValue = objDocument_a.tag + ';' + objDocument_a.description;
                            objDocumentValue = { "documentid" : objDocument_a.tag, "filename" : massageFilename(objDocument_a.description) };

                            addDocument(strName_a, objDocumentValue);
                            blnDocumentsAdded = true;
                        }
                    });

					if (blnDocumentsAdded)
					{
						m_objThis.raiseRendererEvent('Document', m_strFormID, '.' + htmlEncode(strName_a), 'onChange');
						setDirty();
                        populateDocuments(strName_a, false);

					}
				}
			}
        }, false, true);
    }

	// ====================================================================================
	// PUBLIC HELPERS =====================================================================

	this.getDataSourceByDataSourceID = function(strDataSourceID_a)
	{
		var objResult = null;

		processArray(m_arrDataSources, function(objDataSource_a)
		{
			if (objDataSource_a.sourceid == strDataSourceID_a)
			{
				objResult = objDataSource_a;
				return true;
			}
		});
		
		return objResult;
	};
	
	this.raiseRendererEvent = function(strType_a, strFormID_a, strLocator_a, strEventName_a)
	{
		if (m_objParentForm !== null)
		{
			try
			{
				var varResult = m_objParentForm['Renderer_onEvent'](strType_a, strFormID_a, strLocator_a, strEventName_a);
			}
			catch (err)
			{
				// doException('raiseEvent', err);
			}
		}
	};

	// ====================================================================================
	// RENDERING ==========================================================================

	function renderField(objSection_a, objField_a, blnPrintPreview_a, blnExpand_a)
	{
		try
		{
			var blnFocusable = false;
			var strHTML = '';
			var strFocusField = m_strFocusField;

			//attributes initialise Encode

			var strDataType = objField_a.p_datatype;
			if (isNullOrUndefined(objField_a.p_datatype))
			{
				strDataType = '';
			}

			var blnReadonly = false;
			var strIsReadOnly = '';
			var strIsReadOnlyCheckBox = '';
			var strClassInputLimit = '';
			if (os.toBoolean(objField_a.p_readonly))
			{
				strIsReadOnly = 'readonly';
				strIsReadOnlyCheckBox = 'readonly onclick="return false;"';
				blnReadonly = true;
			}

			if ((m_objOptions.readonly) || (blnPrintPreview_a))
			{
				strIsReadOnly = 'readonly';
				strIsReadOnlyCheckBox = 'readonly onclick="return false;"';
				blnReadonly = true;
			}

			var strIsRequired = '';
			var strAsterisk = '';
			if (!blnReadonly && os.toBoolean(objField_a.p_required))
			{
				strIsRequired = 'required';
				strAsterisk = '<span class="required-asterisk" style="display:inline"> *</span>';
			}

			var strMaxLength = objField_a.p_length;
			if (isNullOrUndefined(objField_a.p_length))
			{
				strMaxLength = '';
			}

			var strLabel = objField_a.p_label;
			if (isNullOrUndefined(strLabel))
			{
				strLabel = '';
			}
			
			// if (strDataType !== 'd_description')
			// {
				// if (strLabel.length > 0)
				// {
					// strLabel += ':';
				// }
			// }

			var strStyle = objField_a.p_style;
			if (isNullOrUndefined(strStyle))
			{
				strStyle = '';
			}

			var strClass = objField_a.p_classes;
			if (isNullOrUndefined(strClass))
			{
				strClass = '';
			}

			strClass += ' ' + strStyle;

			switch (strDataType)
			{
				case 'd_abnlookup':
				case 'd_button':
				case 'd_chart':
				case 'd_predictivetext':
				case 'd_date':
				case 'd_document':
				case 'd_gps':
				//case 'd_image':
				case 'd_password':
				case 'd_text':
				case 'd_barcode':
				case 'd_number':	// note: this is temporarily a text type instead of number type because it is misbehaviing: the backspace key doesn't work and up and down keeps changing the number instead of moving to the start and end.  It should behave like a text type with restricted input
				case 'd_time':
				case 'd_yesno':
				case 'd_url':
				case 'd_representation':
				case 'd_list':
				case 'd_multilinetext':
				case 'd_multilist':
					blnFocusable = true;
					if (blnExpand_a && (strFocusField.length > 0))
					{
						strClass = strClass + ' ' + strFocusField;
					}
					break;

				default:
					break;
			}

			var strColour = objField_a.p_colour;
			if (isNullOrUndefined(strColour))
			{
				strColour = '';
			}
			else
			{
				var arrColour = strColour.split('|');
				if (!isNullOrUndefined(arrColour[2]))
				{
					strColour = "background-color:" + arrColour[2] + "; ";
				}
				if (!isNullOrUndefined(arrColour[3]))
				{
					strColour += "color:" + arrColour[3] + "; background-image:none;";
				}
			}

			var strName = massagePName(objSection_a.sectioncode, objField_a.p_name);
			if (isNullOrUndefined(strName))
			{
				strName = '';
			}
			
			if (m_strDirtyFields.length > 0)
			{
				m_strDirtyFields += ',';
			}
			m_strDirtyFields += strName;

			var strLines = objField_a.p_lines;
			if (isNullOrUndefined(strLines))
			{
				strLines = '1';
			}

			var strValue = objField_a.p_value;
			if (isNullOrUndefined(strValue))
			{
				strValue = '';
			}

			var strValueDescription = objField_a.p_valuedescription;
			if (isNullOrUndefined(strValueDescription))
			{
				strValueDescription = '';
			}
			
			// to cater for printer names that don't have a value
			if (strValue.length === 0)
			{
				strValue = strValueDescription;
			}				

			var strSource = objField_a.p_source;
			if (isNullOrUndefined(strSource))
			{
				strSource = '';
			}

			var objSelection = objField_a.p_selection;
			if (objSelection === undefined)
			{
				objSelection = [];
			}

			//strValueDescription = fixValueDescription(strValue, strValueDescription, objSelection);

			var blnContinue = true;
			if (m_objOptions.htmlmode)
			{
				blnContinue = false;
				if (strDataType === 'd_html')
				{
					blnContinue = true;
				}
			}

			if (blnReadonly)
			{
				strFocusField = '';
			}

			var strDataSourceID = '';
			var strURL = '';

			if (hasFlag(m_objOptions.flags, 'noplaceholdersubstitution'))
			{
				doNothing();
			}
			else
			{
				strValue = processPlaceholders(strValue);
			}

			if (strDataType === 'd_html')
			{
				// see server for details within transactionline files
				strValue = str_replace(strValue, "clickevent", "onclick");
			}

			if (blnContinue)
			{
				switch (strDataType)
				{
					case 'd_abnlookup':
						if (true)
						{
							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
							strHTML += '<div class="col-md-9 small"><div style="width:100%;" class="row">';
							strHTML += '<div class="col-md-10 small">';
							strHTML += '<input type="text" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '_COMPANYNAME" name="' + htmlEncode(strName) + '_COMPANYNAME"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValue.companyvalue) + '" placeholder="Business Name" type="text" autocorrect="off" autocapitalize="none" >';
							strHTML += '</div>';
							strHTML += '<div class="col-md-2 small">';
							strHTML += '<button class="form-control ' + htmlEncode(strName) + '-ge-abnsearch-button" btnid="' + htmlEncode(strName) + '">Search</button>';
							strHTML += '</div>';
							strHTML += '</div>';
							strHTML += '<div class="row" style="margin-top:10px">';
							strHTML += '<div class="col-md-10 small">';
							strHTML += '<input type="text" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '_ABN" name="' + htmlEncode(strName) + '_ABN"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValue.abnvalue) + '" placeholder="ABN" type="text" autocorrect="off" autocapitalize="none">';
							strHTML += '</div>';
							strHTML += '</div>';
							strHTML += '</div></div>';

							m_arrABNLists.push({"fieldname":htmlEncode(strName)});
							m_intABNListID++;

							m_strFormFields += ',' + htmlEncode(strName) + '_ABN,' +  htmlEncode(strName) + '_COMPANYNAME,' + htmlEncode(strName) + '-ge-abnsearch-button';
						}
						break;

					case 'd_button':
						if (true)
						{
							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + '</label></div>';

							if (blnPrintPreview_a)
							{
								doNothing();
							}
							else
							{
								strHTML += '<div class="col-md-7 small" style="text-align:left;">';
								strHTML += '<div class="form-control gb-button" style="vertical-align:top; text-align:left;"><div class="' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '">' + htmlEncode(strValue) + '</div></div>';
								strHTML += '</div>';

							}
							strHTML += '</div>';
						}
						break;

					case 'd_chart':
						if (true)
						{
							//strURL = strValue;
							var URL_CHART_PATH = 'reports/report/?chart_only=true&content_only=true&report=~CHARTGUID~.report';
							var strChartGUID = objField_a.p_source;

							strURL = URL_CHART_PATH.replace('~CHARTGUID~', strChartGUID);

							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							strHTML +=      '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
							strHTML +=      '<iframe src="' + strURL + '" style="width:100%;height:230px"></iframe>';
							
							strHTML += '</div>';
						}
						break;

					case 'd_date':
						if (true)
						{
							var strDateValue = "";

							if (strValue.length > 0)
							{
								// here we convert we might be converting for readonly purposes
								if (strValue.length > 10)
								{
									strValue = os.str_left(strValue, 10);
								}
								strValue = os.convertDateToISO(strLabel, strValue, function(strFieldName_a)
								{
									m_blnInvalidDate = true;

									if (m_strInvalidDates.length > 0)
									{
										m_strInvalidDates += ', ';
									}

									m_strInvalidDates += strFieldName_a;
								});

								if (strValue.length > 0)
								{
									strDateValue = os.dateFromISO(DATE_OUTPUTFORMAT, strValue);
								}
							}

							strHTML += '<div class="form-group" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="date">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';

							if(blnReadonly)
							{
								 strHTML += '<div class="col-md-3 small"><input type="text" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strDateValue) + '" ></div>';
							}
							else
							{
								 strHTML += '<div class="col-md-9 small">';
								 strHTML += '<div class="input-group">';
								 strHTML += '   <input type="text" class="form-control ge-btdatepicker ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strDateValue) + '" placeholder="' + DATE_OUTPUTFORMATDESC + '" >';
								 strHTML += '   <div class="input-group-btn">';
								 strHTML += '      <button class="btn btn-default todaysdate-button" btnid="' + htmlEncode(strName) + '">Today</button>&nbsp;';
								 strHTML += '      <button class="btn btn-default cleardate-button" btnid="' + htmlEncode(strName) + '">Clear</button>';
								 strHTML += '    </div>';
								 strHTML += '</div>';
								 strHTML += '</div>';
							}

							strHTML += '</div>';
						}
						break;

					case 'd_document':
						if (true)
						{
							var arrDocuments = [];

							// strValue is not actually a string but array objects

							if( strValue.length > 0) {
							   arrDocuments = strValue; //strValue.split(',');
							   processArray(arrDocuments, function(objDocument_a)
							   {
								   objDocument_a.filename = massageFilename(objDocument_a.filename);
							   });
							}

							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
								
								strHTML += '<div class="col-md-9 small">';
									strHTML += '<input type="hidden" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' value=\'' + JSON.stringify(arrDocuments) + '\'>';
									//strHTML += '<center>';
									strHTML += '<div class="text-left documentDL-' + htmlEncode(strName) + '" >';

										if(arrDocuments.length > 0) {

											//var arrDocumentInfo = [];
											var arrDocumentLinks = [];
											var strDocumentLink = '';

											processArray(arrDocuments, function(objDocument_a) {
												//arrDocumentInfo =  strDocument_a.split(';',2);

												strDocumentLink = createDocumentLink(strName, objDocument_a.documentid, objDocument_a.filename, blnReadonly, blnPrintPreview_a);

												arrDocumentLinks.push(strDocumentLink);
											});

											strHTML += arrDocumentLinks.join('&nbsp;&nbsp;');
										}

									strHTML += '</div>';
									
									if (blnReadonly)
									{
										doNothing();
									}
									else
									{                            
										//strHTML += '<div class="col-md-3"><button class="form-control ge-uploaddocument-button" btnid="' + htmlEncode(strName) + '">Upload</button>';
										//strHTML += '&nbsp;&nbsp;<button class="form-control ge-selectdocument-button" btnid="' + htmlEncode(strName) + '">Select</button></div>';

										strHTML += '<div class="text-left" style="margin-top:5px">';
										strHTML += '<button class="form-control ge-uploaddocument-button" btnid="' + htmlEncode(strName) + '">Upload</button>';
										strHTML += '&nbsp;<button class="form-control ge-selectdocument-button" btnid="' + htmlEncode(strName) + '">Select</button>';
										strHTML += '</div>';
									}

								//strHTML += '</center>';
								strHTML += '</div>';

							strHTML += '</div>';
						}
						break;

					case 'd_gps':
						if (true)
						{
							strHTML += '<div class="form-group" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
								strHTML += '<div class="col-md-9 small">';
									if (blnPrintPreview_a)
									{
										strHTML += '<a class="form-control fb-map-link" style="display: none;" href="' + htmlEncode(strValue.replace(' ', '')) + '">Map</a>';
									}
									else
									{
										strHTML += '<div class="input-group">';
											strHTML += '<input type="text" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValue) + '">';
							
											strHTML += '<div class="input-group-btn">';
												if (os.hasCapability('geolocation'))
												{
													strHTML += '<button class="btn btn-default fb-here-btn" btnid="' + htmlEncode(strName) + '">Here</button>&nbsp;';
												}
												if (os.hasCapability('maps'))
												{
													strHTML += '<button class="btn btn-default fb-map-btn" btnid="' + htmlEncode(strName) + '">Map</button>&nbsp;';
												}

											strHTML += '</div>';
										strHTML += '</div>';
									}
								strHTML += '</div>';
							strHTML += '</div>';
						}
						break;

					case 'd_image':
						if (true)
						{
							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							strHTML += '<div class="col-md-3 small">';
							strHTML += '<label for="text" style="width:100%;" >' + htmlEncode(strLabel) + strAsterisk + '</label>';
							strHTML += '</div>';
							strHTML += '<div class="col-md-4 small move-left">';

							if (blnReadonly)
							{
								doNothing();
							}
							else
							{
								strHTML += '<button class="form-control fb-imagepicker-btn" btnid="' + htmlEncode(strName) + '">Upload</button>';
								strHTML += '&nbsp;&nbsp;';
								strHTML += '<button class="form-control fb-imagegallery-btn" btnid="' + htmlEncode(strName) + '">Select</button>';
								strHTML += '&nbsp;&nbsp;';
								strHTML += '<button class="form-control fb-imagedelete-btn" btnid="' + htmlEncode(strName) + '">Delete</button>';

							}

							strHTML += '</div>';
							strHTML += '<div class="col-md-5 small">';
							strHTML += '<input type="hidden" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValue) + '">';
							strURL = 'images/noimage.png';
							if (strValue.length > 0)
							{
								strURL = 'fetch.php?token=' + encodeURIComponent(SECURITY_TOKEN) + '&image=' + encodeURIComponent(strValue);
							}

							if (blnPrintPreview_a)
							{
								if (strValue.length > 0)
								{
									strHTML += '<div class="ge-form-banner" style="width:100%; color:white; background-color:red;"></div><div style="height:238px; width:238px;"><img style="height:100%;max-width:238px" class="ge-image-field ' + htmlEncode(strName) + '" src="' + strURL + '"><br></div>';
								}
							}
							else
							{
								strHTML += '<div class="ge-form-banner" style="width:100%; color:white; background-color:red;"></div><div style="height:238px; width:238px;"><a class="gb-button fb-view-button ' + htmlEncode(strName) + '" documentid="' + htmlEncode(strValue) + '"><img class="ge-image-field ' + htmlEncode(strName) + '" src="' + strURL + '"></a><br></div>';
							}

							strHTML += '</div>';
							// strHTML += '<div class="col-md-2">';
							// strHTML += '<button class="form-control fb-choose-btn" btnid="'+ strName +'"  >Choose</button>';
							// strHTML += '</div>';
							strHTML += '</div>';
						}
						break;

					case 'd_password':
						if (true)
						{
							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
							strHTML += '<div class="col-md-9 small"><input style="width:100%;" type="password" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValue) + '"></div>';
							strHTML += '</div>';
						}
						break;

					case 'd_metadata':  // used for business logic so not in the renderer
						if (true)
						{
							strValue = getMetadataByType('logic', strValue);

							if (strValue.length > 0)
							{
								// our value is a comma delimmitered list of new meta datas
								var arrMetadata = strValue.split(',');
								processArray(arrMetadata, function(strNewMetaData_a)
								{
									// search the existing metadata list to ensure it doesn't already exist
									var blnFound = false;
									processArray(m_arrMetaData, function(strMetaData_a)
									{
										if (strMetaData_a == strNewMetaData_a)
										{
											blnFound = true;
											return true;
										}
									});

									// if not already found, then add it
									if (!blnFound)
									{
										m_arrMetaData.push(strNewMetaData_a);
									}
								});
							}
						}
						break;

					case 'd_text':
					case 'd_barcode':
					case 'd_number':	// note: this is temporarily a text type instead of number type because it is misbehaviing: the backspace key doesn't work and up and down keeps changing the number instead of moving to the start and end.  It should behave like a text type with restricted input
						if (true)
						{
							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
							strHTML += '<div class="col-md-9 small"><input style="width:100%;" type="text" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValue) + '" ></div>';
							strHTML += '</div>';
						}
						break;

					case 'd_time':
						if (true)
						{
							strHTML += '<div class="form-group" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="time">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';

							if(blnReadonly)
							{
								strHTML += '<div class="col-md-9 small"><input type="time" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValue) + '" ></div>';
							}
							else
							{

								strHTML += '<div class="col-md-9 small">';
								strHTML +=    '<div class="input-group">';
								strHTML +=       '<input type="time" class="form-control ge-bttimepicker ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValue) + '" >';
								strHTML +=       '<div class="input-group-btn">';
								strHTML +=            '<button class="btn btn-default currenttime-button" btnid="' + htmlEncode(strName) + '">Now</button>';
								strHTML += 			  '<button class="btn btn-default cleartime-button" btnid="' + htmlEncode(strName) + '">Clear</button>';
								strHTML +=       '</div>';
								strHTML +=     '</div>';
								strHTML += '</div>';
							}

							strHTML += '</div>';
						}
						break;

					case 'd_yesno':
						if (true)
						{
							var strIsChecked = '';
							if (os.toBoolean(strValue))
							{
								strIsChecked = 'checked';
							}
							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small">';
									strHTML += '<label class="" style="width:100%;' + strColour + '" for="label">' + htmlEncode(strLabel) + '</label></div><div class="col-md-1 text-left" style="text-align:left;"><input class="move-left ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" type="checkbox" name="' + htmlEncode(strName) + '"  ' + strIsReadOnlyCheckBox + ' ' + strIsChecked + ' >';
								strHTML += '</div>';
							strHTML += '</div>';
						}
						break;

					case 'd_url':
						if (true)
						{
							strURL = strValue;

							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';

							if ((os.str_left(strURL, 7).toLowerCase() !== 'http://') && (os.str_left(strURL, 8).toLowerCase() !== 'https://'))
							{
								strURL = 'http://' + strURL;
							}

							if (blnPrintPreview_a)
							{
								strHTML += htmlEncode(strURL);
							}
							else
							{
								if (blnReadonly)
								{
									if (strValue.length > 0)
									{
										strHTML += '<div class="col-md-7 small" style="text-align:left;">';
										strHTML += '<div class="form-control gb-button" style="vertical-align:top; text-align:left;"><a class="' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '" href="' + htmlEncode(strURL) + '" target="_blank" >' + htmlEncode(strURL)  + '&nbsp;<i class="glyphicon glyphicon-circle-arrow-up gb-button"></i></a></div>';
										strHTML += '</div>';
									}
								}
								else
								{
									strHTML += '<div class="col-md-7 small" style="text-align:left;">';
									strHTML += '<input type="text" style="width:95%;" class="form-control fb-url-field ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValue) + '">&nbsp;&nbsp;&nbsp;';
									strHTML += '</div>';
									strHTML += '<div class="col-md-2 ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" style="vertical-align:top; text-align:left; margin-top:5px;"><a href="' + htmlEncode(strURL) + '" target="_blank"><i class="glyphicon glyphicon-circle-arrow-up gb-button"></i></a></div>';
								}

							}
							strHTML += '</div>';
						}
						break;

					case 'd_heading':
						if (true)
						{
							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							strHTML += '<div class="col-md-12 small"><h3 style="width:100%; text-align:left; font-weight:bold;" name="' + htmlEncode(strName) + '" class="' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" >' + htmlEncode(strLabel) + '</h3></div>';
							strHTML += '</div>';
						}
						break;

					case 'd_representation':
						if (true)
						{
							if (blnReadonly)
							{
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
								strHTML += '<div class="col-md-9 small"><input style="width:100%;" type="text" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValueDescription) + '"></div>';
								strHTML += '</div>';
							}
							else
							{
								strDataSourceID = '';
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + ' ' + htmlEncode(strName) + '_fg" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';

								//if (strStyle === 'd_singleradio')	//TO FIX 1
								if (strClass.indexOf('d_singleradio') >= 0)
								{

									if (strSource.length > 0)
									{
										strDataSourceID = 'datasource-' + m_intDataSourceID;
										strHTML += '<div class="col-md-9 small"><div class="clear"></div><fieldset style="width:100%; border:0; box-shadow: none; text-align:left; padding:0;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'rb') + ' ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '" >';
										m_arrDataSources.push({"fetched":false, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});
										m_intDataSourceID++;
									}
									else
									{

										strHTML += '<div class="col-md-9 small"><fieldset style="width:100%; border:none; background:none; text-align:left; padding:0;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '">';
										processArray(objSelection, function (objOption_a)
										{
											var strSelected = '';
											if(strValue === objOption_a.p_code)
											{
												strSelected = 'checked="checked"';
											}
											strHTML += '<label><input type="radio" class=" ' + htmlEncode(strName) + '" value="' + htmlEncode(objOption_a.p_code) + '" radiolabel="' +htmlEncode(objOption_a.p_value)+'" name="' + htmlEncode(strName) + '" ' + strSelected + '><span style="margin-right:10px;"> ' + htmlEncode(objOption_a.p_value) + '</span></label>';
										}
										);

									}
									strHTML += '</fieldset></div>';
								}
								//else if ((strStyle === 'd_singledropdown') || (strStyle.length === 0) || (strSource.length === 0))	//TO FIX 2
								else if (strClass.indexOf('d_singledropdown') >= 0)
								{

									if (strSource.length > 0)
									{
										strDataSourceID = 'datasource-' + m_intDataSourceID;
										strHTML += '<div class="col-md-7 small" style="text-align:left;"><select style="width:98%;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'dd') + ' ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' >';

										m_arrDataSources.push({"fetched":false, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});

										m_intDataSourceID++;
									}
									else
									{
										strHTML += '<div class="col-md-7 small" style="text-align:left;"><select style="width:98%;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' >';
										processArray(objSelection, function (objOption_a)
										{
											var strSelected = '';
											if(strValue === objOption_a.p_code)
											{
												strSelected = 'selected';
											}
											strHTML += '<option value="' + htmlEncode(objOption_a.p_code) + '" ' + strSelected + '>' + htmlEncode(objOption_a.p_value) + '</option>';
										}
										);
									}
									if (strSource.length === 0)
									{
										strHTML += '</select></div>';
									}
									else
									{
										strHTML += '</select></div><div class="col-md-2" style="vertical-align:top; text-align:left; margin-top:5px;"><i class="glyphicon glyphicon-search gb-button ge-d_representation-search" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" targettitle="' + htmlEncode(strLabel) + '"></i></div>';
									}
								}
								else if (strClass.indexOf('d_predictivetext') >= 0)
								{
									if (strSource.length > 0)
									{
										strDataSourceID = 'datasource-' + m_intDataSourceID;
										strHTML += '<div class="col-md-7 small" style="text-align:left;">';
										// strHTML += '<input type="text" style="width:100%;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + ' PREDICTIVETEXT" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValueDescription) + '" placeholder="" type="text" autocorrect="off" autocapitalize="none" >';
										strHTML += '<input type="text" style="width:0%; display:none;" readonly="readonly" class="' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'epv') + ' ' + htmlEncode(strName) + ' ' + htmlEncode(strName + 'epv') + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' value="' + htmlEncode(strValue) + '">';
										strHTML += '<input type="text" style="width:0%; display:none;" readonly="readonly" class="' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'epd') + ' ' + htmlEncode(strName) + ' ' + htmlEncode(strName + 'epd') + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' value="' + htmlEncode(strValueDescription) + '">';
										strHTML += '<input type="text" style="width:98%;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'tds') + ' ' + htmlEncode(strName) + ' ' + htmlEncode(strName + 'tds') + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' value="' + htmlEncode(strValueDescription) + '">';

										m_arrDataSources.push({"fetched":true, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});
										m_arrPredictiveTextSources.push({"fieldname":htmlEncode(strName), "sourceid":strDataSourceID, "source":strSource});
										m_intDataSourceID++;
										m_intPredictiveTextSourceID++;
									}

									if (strSource.length === 0)
									{
										strHTML += '</div>';
									}
									else
									{
										strHTML += '</div>';
										strHTML += '<div class="col-md-2 small" style="vertical-align:top; text-align:left; margin-top:5px;">';
											strHTML += '<i class="glyphicon glyphicon-remove-circle gb-button ENTITYPICKERCLEAR ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"></i>&nbsp;&nbsp;';
											strHTML += '<i class="glyphicon glyphicon-search gb-button ENTITYPICKER ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '" targettitle="' + htmlEncode(strLabel) + '"></i>&nbsp;';
										strHTML += '</div>';
									}
								}
								else	// become an entity picker
								{
									if (strSource.length > 0)
									{
										strDataSourceID = 'datasource-' + m_intDataSourceID;
										strHTML += '<div class="col-md-8 small" style="text-align:left;">';
										strHTML += '<input type="text" style="width:98%; display:none;" readonly="readonly" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'epv') + ' ' + htmlEncode(strName)  + ' ' + htmlEncode(strName + 'epv') + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' value="' + htmlEncode(strValue) + '">';
										strHTML += '<input type="text" style="width:98%;" readonly="readonly" class="form-control ENTITYPICKER ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'epd') + ' ' + htmlEncode(strName)  + ' ' + htmlEncode(strName + 'epd') + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' value="' + htmlEncode(strValueDescription) + '">';
										
										m_arrDataSources.push({"fetched":true, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});
										m_intDataSourceID++;
									}
									//else	// this bit is no longer called because the || (strSource.length === 0)) for d_singledropdown
									//{
										//strHTML += '<div class="col-md-8">';
										//strHTML += '<input type="text" style="width:100%; display:none;" readonly="readonly" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' >';
										//strHTML += '<input type="text" style="width:100%;" readonly="readonly" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' >';
									//}
									if (strSource.length === 0)
									{
										strHTML += '</div>';
									}
									else
									{
										strHTML += '</div>';
										strHTML += '<div style="vertical-align:top; text-align:left; margin-top:5px;">';
											strHTML += '<i class="glyphicon glyphicon-remove-circle gb-button ENTITYPICKERCLEAR ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"></i>&nbsp;&nbsp;';
											strHTML += '<i class="glyphicon glyphicon-search gb-button ENTITYPICKER ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '" targettitle="' + htmlEncode(strLabel) + '"></i>&nbsp;';
										strHTML += '</div>';
									}
								 }
								 strHTML += '</div>';
							}
						}
						break;

					case 'd_list':
						if (true)
						{
							if (blnReadonly)
							{
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
								strHTML += '<input type="text" style="width:98%; display:none;" readonly="readonly" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'epv') + ' ' + htmlEncode(strName) + ' ' + htmlEncode(strName + 'epv') + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' value="' + htmlEncode(strValue) + '">';
								strHTML += '<div class="col-md-9 small"><input style="width:100%;" type="text" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValueDescription) + '"></div>';
								strHTML += '</div>';
							}
							else if (strSource === "SYSTEMPRINTERS")
							{
								if (m_blnPrinters)
								{
									strDataSourceID = '';
									strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + ' ' + htmlEncode(strName) + '_fg" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
									strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';

									strDataSourceID = 'datasource-' + m_intDataSourceID;
									strHTML += '<div class="col-md-9 small" style="text-align:left;"><select style="width:98%;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'dd') + ' ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' >';

									strHTML += '</select></div>';

									strHTML += '</div>';

									m_arrDataSources.push({"fetched":false, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});

									m_intDataSourceID++;
								}
								else
								{
									strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
									strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + '</label></div>';
									strHTML += '<div class="col-md-9 small"><input style="width:100%;" type="text" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'dd') + ' ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  value="' + htmlEncode(strValue) + '"></div>';
									strHTML += '</div>';

									m_arrDataSources.push({"fetched":false, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});

									m_intDataSourceID++;								}
							}
							else
							{
								strDataSourceID = '';
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + ' ' + htmlEncode(strName) + '_fg" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';

								//if (strStyle === 'd_singleradio')	//TO FIX 1
								if (strClass.indexOf('d_singleradio') >= 0)
								{

									if (strSource.length > 0)
									{
										strDataSourceID = 'datasource-' + m_intDataSourceID;
										strHTML += '<div class="col-md-9 small"><div class="clear"></div><fieldset style="width:100%; border:0; box-shadow: none; text-align:left; padding:0;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'rb') + ' ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '" >';
										
										m_arrDataSources.push({"fetched":false, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});
										m_intDataSourceID++;
									}
									else
									{

										strHTML += '<div class="col-md-9 small"><fieldset style="width:100%; border:none; background:none; text-align:left; padding:0;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '">';
										processArray(objSelection, function (objOption_a)
										{
											var strSelected = '';
											if(strValue === objOption_a.p_code)
											{
												strSelected = 'checked="checked"';
											}
											strHTML += '<label><input type="radio" class=" ' + htmlEncode(strName) + '" value="' + htmlEncode(objOption_a.p_code) + '" radiolabel="' +htmlEncode(objOption_a.p_value)+'" name="' + htmlEncode(strName) + '" ' + strSelected + '><span style="margin-right:10px;"> ' + htmlEncode(objOption_a.p_value) + '</span></label>';
										}
										);

									}
									strHTML += '</fieldset></div>';
								}
								//else if ((strStyle === 'd_singledropdown') || (strStyle.length === 0) || (strSource.length === 0))	//TO FIX 2
								else if (strClass.indexOf('d_singledropdown') >= 0)
								{

									if (strSource.length > 0)
									{
										strDataSourceID = 'datasource-' + m_intDataSourceID;
										strHTML += '<div class="col-md-7 small" style="text-align:left;"><select style="width:98%;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'dd') + ' ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' >';

										m_arrDataSources.push({"fetched":false, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});

										m_intDataSourceID++;
									}
									else
									{
										strHTML += '<div class="col-md-7 small" style="text-align:left;"><select style="width:98%;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' >';
										processArray(objSelection, function (objOption_a)
										{
											var strSelected = '';
											if(strValue === objOption_a.p_code)
											{
												strSelected = 'selected';
											}
											strHTML += '<option value="' + htmlEncode(objOption_a.p_code) + '" ' + strSelected + '>' + htmlEncode(objOption_a.p_value) + '</option>';
										}
										);
									}
									
									if (strSource.length === 0)
									{
										strHTML += '</select></div>';
									}
									else
									{
										strHTML += '</select></div><div class="col-md-2" style="vertical-align:top; text-align:left; margin-top:5px;"><i class="glyphicon glyphicon-search gb-button ge-d_list-search" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" targettitle="' + htmlEncode(strLabel) + '"></i></div>';
									}
								}
								else if (strClass.indexOf('d_predictivetext') >= 0)
								{
									if (strSource.length > 0)
									{
										strDataSourceID = 'datasource-' + m_intDataSourceID;
										strHTML += '<div class="col-md-7 small" style="text-align:left;">';
										// strHTML += '<input type="text" style="width:100%;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + ' PREDICTIVETEXT" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' value="' + htmlEncode(strValueDescription) + '" placeholder="" type="text" autocorrect="off" autocapitalize="none" >';
										strHTML += '<input type="text" style="width:0%; display:none;" readonly="readonly" class="' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'epv') + ' ' + htmlEncode(strName) + ' ' + htmlEncode(strName + 'epv') + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' value="' + htmlEncode(strValue) + '">';
										strHTML += '<input type="text" style="width:0%; display:none;" readonly="readonly" class="' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'epd') + ' ' + htmlEncode(strName) + ' ' + htmlEncode(strName + 'epd') + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' value="' + htmlEncode(strValueDescription) + '">';
										strHTML += '<input type="text" style="width:98%;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'tds') + ' ' + htmlEncode(strName) + ' ' + htmlEncode(strName + 'tds') + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' value="' + htmlEncode(strValueDescription) + '">';

										m_arrDataSources.push({"fetched":true, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});
										m_arrPredictiveTextSources.push({"fieldname":htmlEncode(strName), "sourceid":strDataSourceID, "source":strSource});
										m_intDataSourceID++;
										m_intPredictiveTextSourceID++;
									}

									if (strSource.length === 0)
									{
										strHTML += '</div>';
									}
									else
									{
										strHTML += '</div>';
										strHTML += '<div class="col-md-2 small" style="vertical-align:top; text-align:left; margin-top:5px;">';
											strHTML += '<i class="glyphicon glyphicon-remove-circle gb-button ENTITYPICKERCLEAR ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"></i>&nbsp;&nbsp;';
											strHTML += '<i class="glyphicon glyphicon-search gb-button ENTITYPICKER ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '" targettitle="' + htmlEncode(strLabel) + '"></i>&nbsp;';
										strHTML += '</div>';
									}
								 }
								else	// become an entity picker
								{
									if (strSource.length > 0)
									{
										strDataSourceID = 'datasource-' + m_intDataSourceID;
										strHTML += '<div class="col-md-8 small" style="text-align:left;">';
										strHTML += '<input type="text" style="width:98%; display:none;" readonly="readonly" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'epv') + ' ' + htmlEncode(strName) + ' ' + htmlEncode(strName + 'epv') + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' value="' + htmlEncode(strValue) + '">';
										strHTML += '<input type="text" style="width:98%;" readonly="readonly" class="form-control ENTITYPICKER ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'epd') + ' ' + htmlEncode(strName)  + ' ' + htmlEncode(strName + 'epd') + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' value="' + htmlEncode(strValueDescription) + '">';
										m_arrDataSources.push({"fetched":true, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});
										m_intDataSourceID++;
									}
									//else	// this bit is no longer called because the || (strSource.length === 0)) for d_singledropdown
									//{
										//strHTML += '<div class="col-md-8">';
										//strHTML += '<input type="text" style="width:100%; display:none;" readonly="readonly" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' >';
										//strHTML += '<input type="text" style="width:100%;" readonly="readonly" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' >';
									//}
									if (strSource.length === 0)
									{
										strHTML += '</div>';
									}
									else
									{
										strHTML += '</div>';
										strHTML += '<div style="vertical-align:top; text-align:left; margin-top:5px;">';
											strHTML += '<i class="glyphicon glyphicon-remove-circle gb-button ENTITYPICKERCLEAR ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '"></i>&nbsp;&nbsp;';
											strHTML += '<i class="glyphicon glyphicon-search gb-button ENTITYPICKER ' + htmlEncode(strName) + '" targetdatasourceid="' + htmlEncode(strDataSourceID) + '" name="' + htmlEncode(strName) + '" targettitle="' + htmlEncode(strLabel) + '"></i>&nbsp;';
										strHTML += '</div>';
									}
								 }
								 strHTML += '</div>';
							}
						}
						break;

					case 'd_spacer':
						if (true)
						{
							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							strHTML += '<div class="col-md-12 small"></br></div>';
							strHTML += '</div>';
						}
						break;

					case 'd_description':
						if (true)
						{
							strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
							// strHTML += '<div class="col-md-12 small"><label style="width:100%;' + strColour + '">' + htmlEncode(strLabel) + '</label></div>';
							strHTML += '<div class="col-md-12 small"><label style="width:100%; height:auto; font-weight:normal; border:0px;" class="' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '">' + strLabel + '</label></div><br><br>';
							strHTML += '</div>';
						}
						break;

					case 'd_html':
						if (true)
						{
							if (blnReadonly)
							{
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								// note strValue is not htmlEncoded here because we want to render the actual HTML, not the sourcecode of the HTML
								strHTML += '<div class="col-md-12 small"><label style="width:100%; height:auto; font-weight:normal; border:0px;" class="' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '">' + strValue + '</label></div>';
								strHTML += '</div>';
							}
							else
							{
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="textarea">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
								strHTML += '<div class="col-md-9 small"><textarea style="width:100%;" name="' + htmlEncode(strName) + '" class="' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' rows="' + strLines + '" >' + htmlEncode(strValue) + '</textarea></div>';
								strHTML += '</div>';
								m_arrHTMLEditors.push({"fetched":true, "sourceid":htmlEncode(strName), "source":null, "type":"d_html"});
							}
						}
						break;

					case 'd_texthtml':
						if (true)
						{
							if (blnReadonly)
							{
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="label">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';

								// note strValue is not htmlEncoded here because we want to render the actual HTML, not the sourcecode of the HTML
								strHTML += '<div class="col-md-9 small"><label style="width:100%; height:auto; word-wrap:break-word" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '">' + strValue + '</label></div>';
								strHTML += '</div>';
							}
							else
							{
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="textarea">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
								strHTML += '<div class="col-md-9 small"><textarea style="width:100%;" name="' + htmlEncode(strName) + '" class="' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' rows="' + strLines + '" >' + htmlEncode(strValue) + '</textarea></div>';
								strHTML += '</div>';
								m_arrHTMLEditors.push({"fetched":true, "sourceid":htmlEncode(strName), "source":null, "type":"d_texthtml"});
							}
						}
						break;

					case 'd_codeeditor':
						if (true)
						{
							if (blnReadonly)
							{
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%; height: 300px;' + strColour + '" for="label">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';

								// note strValue is not htmlEncoded here because we want to render the actual HTML, not the sourcecode of the HTML
								strHTML += '<div class="col-md-9 small"><label style="width:100%; height:auto; word-wrap:break-word" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '">' + strValue + '</label></div>';
								strHTML += '</div>';
							}
							else
							{
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="codeeditor">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
								strHTML += '<div class="col-md-9 small"><div style="width:100%; height: 300px;" name="' + htmlEncode(strName) + '" class="' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' rows="' + strLines + '" >' + htmlEncode(strValue) + '</div></div>';
								strHTML += '</div>';
								m_arrCodeEditors.push({"fetched":true, "sourceid":htmlEncode(strName), "source":null, "type":"d_codeeditor"});
							}
						}
						break;

					case 'd_multilinetext':
						if (true)
						{
							if (blnReadonly)
							{
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="label">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';

								strValue = htmlEncode(strValue);
								strValue = str_replace(strValue, "\r", "");
								strValue = str_replace(strValue, "\n", "<br />");
								strHTML += '<div class="col-md-9 small"><label style="width:100%; height:auto; word-wrap:break-word" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '">' + strValue + '</label></div>';
								strHTML += '</div>';
							}
							else
							{
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="textarea">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';
								strHTML += '<div class="col-md-9 small"><textarea style="width:100%;" name="' + htmlEncode(strName) + '" class="' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '"  maxlength="' + htmlEncode(strMaxLength) + '"  ' + strIsRequired + ' ' + strIsReadOnly + ' rows="' + strLines + '" >' + htmlEncode(strValue) + '</textarea></div>';
								strHTML += '</div>';
							}
						}
						break;

				   case 'd_multilist':
						if (true)
						{
							var strOnClick = '';
							if (blnReadonly)
							{
								// strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								// strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="label">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';

								// strHTML += '<div class="col-md-9 small"><label style="width:100%; height:auto; word-wrap:break-word" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '">' + htmlEncode(strValueDescription) + '</label></div>';
								// strHTML += '</div>';
								strOnClick = ' onclick="return false;" ';
							}
							
							// else
							// {
								var arrValue = '';
								strDataSourceID = '';
								strHTML += '<div class="form-group form-inline ' + htmlEncode(strName + 'fld') + '" style="margin:0px; padding:1px 1px 1px 1px;' + strColour + '">';
								strHTML += '<div class="col-md-3 small"><label style="width:100%;' + strColour + '" for="text">' + htmlEncode(strLabel) + strAsterisk + '</label></div>';

								//if(strStyle === 'd_multicheckbox')	//TO FIX 3
								if (strClass.indexOf('d_multicheckbox') >= 0)
								{
									strHTML += '<div class="col-md-9 small"><div class="clear"></div>';

									if (strSource.length > 0)
									{
										strDataSourceID = 'datasource-' + m_intDataSourceID;
										strHTML += '<fieldset ' + strOnClick + ' style="width:100%; border:0; box-shadow: none; height:auto; text-align:left; padding:0;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'cb') + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '" >';
										m_arrDataSources.push({"fetched":false, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});
										m_intDataSourceID++;
									}
									else
									{
										if(strValue === null)
										{
											arrValue = [];
										}
										else
										{
											arrValue = strValue;
										}

										strHTML += '<fieldset ' + strOnClick + ' style="width:100%; height:auto; overflow:auto; max-height:170px; border:0; box-shadow: none; text-align:left;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '">';
										processArray(objSelection, function (objOption_a)
										{
											var strSelected = '';
											if(arrValue.length > 0)
											{
												processArray(arrValue, function (strValue_a)
												{
													if( strValue_a === objOption_a.p_code )
													{
														strSelected = 'checked="checked"';
													}
												});
											}
											strHTML += '<div style="text-align:left"><label><input type="checkbox" class=" ' + htmlEncode(strName) + '" value="' + htmlEncode(objOption_a.p_code) + '" checkboxlabel="' +htmlEncode(objOption_a.p_value)+'" name="' + htmlEncode(strName) + '" ' + strSelected + '><span style="margin-right:10px;"> ' + htmlEncode(objOption_a.p_value) + '</span></label></div>';
										}
										);

									}
									strHTML += '</fieldset></div>';
								}
								else
								{
									if (strSource.length > 0)
									{
										strDataSourceID = 'datasource-' + m_intDataSourceID;
										strHTML += '<div class="col-md-9 small"><select style="width:100%;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'md') + ' ' + htmlEncode(strName) + '" multiple name="' + htmlEncode(strName) + '"  ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' ></div>';
										m_arrDataSources.push({"fetched":false, "locator":'.' + strName, "sourceid":strDataSourceID, "source":strSource, "data":null, "selected":strValue});
										m_intDataSourceID++;
									}
									else
									{
										if(strValue === null)
										{
											arrValue = [];
										}
										else
										{
											arrValue = strValue;
										}

										strHTML += '<div class="col-md-9 small"><select style="width:100%;" class="form-control ' + htmlEncode(strClass) + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '"  multiple ' + strIsRequired + ' ' + strIsReadOnlyCheckBox + ' >';
										processArray(objSelection, function (objOption_a)
										{
											var strSelected = '';
											if(arrValue.length > 0)
											{
												processArray(arrValue, function (strValue_a)
												{
													if( strValue_a === objOption_a.p_code )
													{
														strSelected = 'selected';
													}
												});
											}

											strHTML += '<option value="' + htmlEncode(objOption_a.p_code) + '" ' + strSelected + '>' + htmlEncode(objOption_a.p_value) + '</option>';
										}
										);
									}
									strHTML += '</select></div>';
								}

								strHTML += '</div>';
							//}
						}
						break;

				   case 'd_relatedlinks':
						if (true)
						{
							if ((blnPrintPreview_a) || (m_strDataID.length === 0))
							{
								doNothing();
							}
							else
							{
								strHTML += '<div class="form-group">';
								//strHTML += '<label for="time">' + htmlEncode(strLabel) + strAsterisk + '</label>';
								strHTML += '<div class="col-md-12 small"><div class="clear"></div>';
								strDataSourceID = 'datasource-' + m_intDataSourceID;
								strHTML += '<fieldset class="' + htmlEncode(strClass) + ' ' + htmlEncode(strDataSourceID + 'rf') + ' ' + htmlEncode(strName) + '" name="' + htmlEncode(strName) + '" >';

								processArray(objSelection, function (objOption_a)
								{
									if ((objOption_a.p_permissions === undefined) || (objOption_a.p_permissions.length === 0) || (hasPermission(JSON.parse(str_replace(objOption_a.p_permissions, "'", '"')))))
									{
										strHTML += '<label class=" fb-relatedlink-btn ' + htmlEncode(strName) + '" style="cursor: pointer; color:#069;" command="' + htmlEncode(objOption_a.p_command) + '" parameters="'+htmlEncode(objOption_a.p_parameters)+'" name="' + htmlEncode(strName) + '"><span style="margin-right:10px;"> ' + htmlEncode(objOption_a.p_label) + '</span></label>';
									}
								}
								);

								strHTML += '</fieldset></div>';
							}
						}
						break;

					default:
						if (true)
						{
							doNothing();
						}
						break;
				}

				m_strFormFields += ',' + strClass.split(" ");
			}

			if (blnFocusable && blnExpand_a && (strFocusField.length > 0) && (strFocusField == m_strFocusField))
			{
				m_strFocusField = ''; // we already gave a field focus so no more
			}
		}
		catch (err)
		{
			doException('renderField:' + objField_a.p_name, err);
		}

		return strHTML;
	}

	function renderSection(objSection_a, blnPrintPreview_a, blnExpand_a)
	{
		var strHTML = '';

		strHTML += '<div>';

		processArray(objSection_a.fields, function (objField_a)
		{
			if (objField_a === null)
			{
				doException('renderSection', 'null field exception');
			}
			else
			{
				try
				{
					var strStyle = objField_a.p_style;
					if (isNullOrUndefined(strStyle))
					{
						strStyle = '';
					}
					strStyle = ' ' + strStyle + ' ';

					if (strStyle.indexOf(' gb-hidden ') < 0)
					{
						strHTML += renderField(objSection_a, objField_a, blnPrintPreview_a, blnExpand_a);
					}
				}
				catch (err)
				{
					doException('renderSection', err);
				}
			}
		}
		);

		strHTML += '</div>';

		return strHTML;
	}

	function renderSections(arrSections_a, blnPrintPreview_a)
	{
		var blnExpand = false;
		var strHTML = '';
		var strCollapseIN = '';
		//var intActiveTab = 0;
		var strAccordionID = '';
		var strAccordionParentID = getGUID();

		m_arrABNLists = [];
		m_arrDataSources = [];// re-initialise this list as it is rebuilt every time we render sections
		m_intABNListID = 0;
		m_intDataSourceID = 0;
		m_strFocusField = 'ge-focusfield';

		if (m_objOptions.htmlmode)
		{
			strHTML += '<div>';

			processArray(arrSections_a, function (objSection_a)
			{
				var strColour = objSection_a.sectioncolour;
				if (isNullOrUndefined(strColour))
				{
					strColour = '';
				}
				else
				{
					var arrColour = strColour.split('|');
					if (!isNullOrUndefined(arrColour[2]))
					{
						strColour = "background-color:" + arrColour[2] + "; ";
					}
					if (!isNullOrUndefined(arrColour[3]))
					{
						strColour += "color:" + arrColour[3] + "; background-image:none;";
					}
				}

				if (objSection_a.sectiontype.toUpperCase() === 'DATA')
				{
					strHTML += '<div>';
					strHTML += renderSection(objSection_a, blnPrintPreview_a);
					strHTML += '</div>';
				}
				else
				{
					doNothing();
				}

			}
			);

			strHTML += '</div>';
		}
		else
		{
			var intVisibleSections = 0;
			
			// work out the visible sections
			processArray(arrSections_a, function (objSection_a)
			{
				if (objSection_a.sectiontype.toUpperCase() === 'FORMHEADER' || objSection_a.sectiontype.toUpperCase() === 'DATAHEADER')
				{
					doNothing();
				}
				else
				{
					if ((objSection_a.sectiontype.toUpperCase() === 'DATA') || (objSection_a.sectiontype.toUpperCase() === 'INTERNALUSEFORMHEADER' && hasPermission(['IUSE_DATAFORM'])) || (objSection_a.sectiontype.toUpperCase() === 'INTERNALUSEONLY' && hasPermission(['IUSE_DATAFORM'])))
					{
						intVisibleSections++;
					}
				}
			});
            
			// start accordion if required
			if ((m_objOptions.showaccordion) || (intVisibleSections > 1))
			{
				strHTML += '<div class="panel-group ge-formsection-group" id="' + strAccordionParentID + '" role="tablist" aria-multiselectable="true">';
			}
			else
			{
				strHTML += '<div>';
			}
			
			// render the form
			processArray(arrSections_a, function (objSection_a)
			{
                strHTML += renderFormSection(objSection_a, (m_objOptions.showaccordion) || (intVisibleSections > 1), blnPrintPreview_a, strAccordionParentID);                
			});

			strHTML += '</div>';
		}

		return strHTML;
    }
    
    function renderFormSection(objSection_a, blnShowFormSectionHeading_a, blnPrintPreview_a, strAccordionParentID_a)
    {
        var blnExpand = false;
		var strHTML = '';
        var strCollapseIN = '';
        var strAccordionParentID = getGUID();
        var strAccordionID = getGUID();

        var strColour = objSection_a.sectioncolour;

        if(strAccordionParentID_a !== undefined && strAccordionParentID_a.length > 0)
        {
            strAccordionParentID = strAccordionParentID_a;
        }
        
        if (isNullOrUndefined(strColour))
        {
            strColour = '';
        }
        else
        {
            var arrColour = strColour.split('|');
			if (!isNullOrUndefined(arrColour[2]))
			{
				strColour = "background-color:" + arrColour[2] + "; ";
			}
			if (!isNullOrUndefined(arrColour[3]))
			{
				strColour += "color:" + arrColour[3] + "; background-image:none;";
			}
        }

        //if ((intActiveTab === 0) && (objSection_a.sectiontype.toUpperCase() === 'DATA'))
        //{
            //intActiveTab = intTab;
        //}

        if (objSection_a.sectiontype.toUpperCase() === 'FORMHEADER' || objSection_a.sectiontype.toUpperCase() === 'DATAHEADER')
        {
            doNothing();
        }
        else
        {
            if ((objSection_a.sectiontype.toUpperCase() === 'DATA') || (objSection_a.sectiontype.toUpperCase() === 'INTERNALUSEFORMHEADER' && hasPermission(['IUSE_DATAFORM'])) || (objSection_a.sectiontype.toUpperCase() === 'INTERNALUSEONLY' && hasPermission(['IUSE_DATAFORM'])))
            {
                //if ((intTab == intActiveTab) || (objSection_a.sectiontype.toUpperCase() === 'DATA'))
                if (objSection_a.sectiontype.toUpperCase() === 'DATA')
                {
                    strCollapseIN = 'in';
                    blnExpand = true;
                }
                else
                {
                    strCollapseIN = '';
                }

            // TODO colour support
                //strHTML += '<div class="panel panel-default" style="page-break-after: always">';
                
                strHTML += '<div class="panel panel-default gs-panel-default ge-formsection">';

                //if ((m_objOptions.showaccordion) || (intVisibleSections > 1))
                if (blnShowFormSectionHeading_a)
                {
                    strHTML += '<div class="panel-heading gs-panel-heading-relative" style="' + strColour + '" role="tab" >';
                    strHTML += '<h4 class="panel-title">';
                    strHTML += '<a style="display: block; width: 100%;" role="button" data-toggle="collapse" data-parent="#' + strAccordionParentID + '" href="#' + strAccordionID + '" aria-expanded="true" aria-controls="' + strAccordionID + '">';
                    strHTML += htmlEncode(objSection_a.sectiontitle);                                                       
                    strHTML += '</a>';                                                        
                    strHTML += '</h4>';

                    strHTML += '<div class="gs-headingsection-controls">';

                    if(objSection_a.sectionquantity !== undefined && objSection_a.sectionquantity === '1+')
                    {
                        strHTML += '<a data-sectioncode="' + objSection_a.sectioncode + '" class="gs-headingsection-control ge-control-addcurrentsection">+</a>';                                
                    }

                    if(objSection_a.sectionref !== undefined && objSection_a.sectionref.length > 0)
                    {
                        strHTML += '<a style="margin-left:5px" data-sectioncode="' + objSection_a.sectioncode + '" class="gs-headingsection-control ge-control-removecurrentsection">-</a>';
                    }

                    strHTML += '</div>';

                    strHTML += '</div>';
                    strHTML += '<div id="' + strAccordionID + '" class="panel-collapse collapse ' + strCollapseIN + '" role="tabpanel" >';
                    strHTML += '<div id="fb-render-' + strAccordionID + '" class="panel-body fb-render-' + strAccordionID + '">';
                }
                
                strHTML += renderSection(objSection_a, blnPrintPreview_a, blnExpand);
                
                if (blnShowFormSectionHeading_a)
                {
                    strHTML += '</div>';
                    strHTML += '</div>';
                }

                strHTML += '</div>';
            }
            else
            {
                doNothing();
            }
        }

        return strHTML;
    }

	// ====================================================================================
	// BINDINGS ===========================================================================

    function getMetadataByType(strType_a, strValue_a){
        //var strValue_a = 'logic[hello1,hello2]|blah[blah]|logic[hello3]|nologic[test1,test2]|logic2[demo1,demo2]';
        //var strType_a = 'logic';

        var arrRawValue = strValue_a.split('|');
        var arrValues = [];

        for(var i = 0; i <= arrRawValue.length - 1; i++)
        {
            var strRawValue = arrRawValue[i];

            var strLogic = strRawValue.split('[')[0];
            var strFunctions = strRawValue.split('[')[1].replace(']', '');

            //console.log(strLogic);
            //console.log(strFunctions);

            if(strLogic == strType_a)
            {
                var arrFunctions = strFunctions.split(',');

                for(var x = 0; x <= arrFunctions.length - 1; x++)
                {
                    arrValues[arrFunctions[x]] = '';
                }
            }
        }

        arrValues = Object.keys(arrValues);
        strValue_a = arrValues.join(',');

        return strValue_a;
    }

    function bindCheckbox()
    {
        // unbind & bind
        processArray(m_arrDataSources, function(objDataSource_a)
        {
            os.unbindEvents(m_strFormID, objDataSource_a.sourceid + 'cb');

            os.onDirty(m_strFormID, objDataSource_a.sourceid + 'cb', function()
			{
				m_objThis.raiseRendererEvent('Checkbox', m_strFormID, objDataSource_a.locator, 'onChange');
				setDirty();
			});
        });
    }

	function bindDropdowns()
	{
		// unbind & bind
		processArray(m_arrDataSources, function(objDataSource_a)
		{
			os.unbindEvents(m_strFormID, objDataSource_a.sourceid + 'dd');

            os.onDirty(m_strFormID, objDataSource_a.sourceid + 'dd', function()
			{
				m_objThis.raiseRendererEvent('Dropdown', m_strFormID, objDataSource_a.locator, 'onChange');
				setDirty();
			});
		});
	}

	function bindDropdownsMultiple()
    {
        // unbind & bind
        processArray(m_arrDataSources, function(objDataSource_a)
        {
            os.unbindEvents(m_strFormID, objDataSource_a.sourceid + 'md');

            os.onDirty(m_strFormID, objDataSource_a.sourceid + 'md', function()
			{
				m_objThis.raiseRendererEvent('DropdownMulti', m_strFormID, objDataSource_a.locator, 'onChange');
				setDirty();
			});
        });
    }

    function bindCodeEditors()
    {
        // unbind & bind
        processArray(m_arrCodeEditors, function(objCodeEditor_a)
        {
            try
            {


            objCodeEditor_a.source = new jCodeEditor(os, m_strFormID, '.' + objCodeEditor_a.sourceid,
            {
                defaultContent : '',
                element : 'fldCode' + '-' + objCodeEditor_a.sourceid
            });

           }
           catch (err)
           {
                doException('bindCodeEditors', err);
           }


        });
    }

	function bindHTMLEditors()
	{
        // unbind & bind
        processArray(m_arrHTMLEditors, function(objHTMLEditor_a)
        {
			objHTMLEditor_a.source = new jEditor(os, m_strFormID, '.' + objHTMLEditor_a.sourceid,
			{
				defaultContent : '',
				element : 'fldCode' + '-' + objHTMLEditor_a.sourceid,
                toolbar : 'run | load | reset | clear | imagepicker | searchreplace | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullscreen',

				cbOnClear : function (strContentHTML_a, strContent_a)
				{
					objHTMLEditor_a.source.setContent('');
					return true;
				},

				cbOnDirty : function (blnDirty_a)
				{
					m_objThis.raiseRendererEvent('Editor', m_strFormID, '.' + objHTMLEditor_a.sourceid, 'onChange');
					setDirty(blnDirty_a);
				},

				cbOnFileBrowse : function (objWindow_a, strFieldName_a, strType_a)
				{
					objWindow_a.document.getElementById(strFieldName_a).value = strType_a;
				},

				cbOnReset : function (strContentHTML_a, strContent_a)
				{
					objHTMLEditor_a.source.setContent('');
					return true;
				},

				cbOnSave : function (strContentHTML_a, strContent_a)
				{
					os.dialogAlert('Save failed: ' + os.encodeHTML(strContent_a), doNothing);
					//m_objEditorDataHTML = strContentHTML_a;
					//m_objEditorDataAsText = strContent_a;
					//saveData();

					return false;
				}
			});
		});
	}

	function bindRadioButtons()
	{
		// unbind
		processArray(m_arrDataSources, function(objDataSource_a)
		{
			os.unbindEvents(m_strFormID, objDataSource_a.sourceid + 'rb');

            os.onDirty(m_strFormID, objDataSource_a.sourceid + 'rb', function()
			{
				m_objThis.raiseRendererEvent('Radiobutton', m_strFormID, objDataSource_a.locator, 'onChange');
				setDirty();
			});
		});
	}

	// note: make any bindings call a callback passed in objOptions_a, see jGrid for example
	function bindForm()
	{
		// bind only if used
		// unbindings
		os.unbindEvents(m_strFormID, m_strFormFields+',fb-relatedlink-btn,fb-imagepicker-btn,fb-imagegallery-btn,fb-imagedelete-btn,fb-choose-btn,fb-download-button,fb-map-btn,ge-d_document-remove-button,ge-d_representation-search,ge-d_list-search,ENTITYPICKER,ge-uploaddocument-button,fb-url-field,ge-selectdocument-button,ge-image-field,ge-control-addcurrentsection,ge-control-removecurrentsection');

		os.bindEvent(m_objThis, m_strFormID, '.fb-imagedelete-btn', 'ImageDeleteButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.fb-imagepicker-btn', 'ImagePickerButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.fb-imagegallery-btn', 'ImageGalleryButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.fb-choose-btn', 'ChooseButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.fb-download-button', 'DownloadButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.fb-here-btn', 'HereButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.fb-map-btn', 'MapButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.fb-view-button', 'ViewButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-d_document-remove-button', 'DocumentRemoveButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-uploaddocument-button', 'UploadButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-selectdocument-button', 'SelectButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.fb-url-field', 'UrlField', 'onChange');
        os.bindEvent(m_objThis, m_strFormID, '.fb-relatedlink-btn', 'RelatedLinkButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-d_representation-search', 'DropdownSearch', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-d_list-search', 'DropdownSearch', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ENTITYPICKER', 'EntityPicker', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ENTITYPICKERCLEAR', 'EntityPickerClear', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-image-field', 'ImageField', 'onLoad');
        os.bindEvent(m_objThis, m_strFormID, '.ge-control-addcurrentsection', 'SectionAddCurrent', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-control-removecurrentsection', 'SectionRemoveCurrent', 'onClick');

        // dirty bindings
		var arrFormFields = m_strDirtyFields.split(',');
		processArray(arrFormFields, function(strField_a)
		{
			os.onDirty(m_strFormID, strField_a, function()
			{
				m_objThis.raiseRendererEvent('Field', m_strFormID, '.' + htmlEncode(strField_a), 'onChange');
				setDirty();
			});
		});
    }
    
    function bindFormEvents(strFormID_a, strFormElementClass_a)
    {
        fetchDropdownData();
		populateABNLookup();
		bindForm();
		bindCheckbox();
		bindDropdowns();
		bindDropdownsMultiple();
		bindNowButton();
		bindRadioButtons();
		bindTodaysButton();
		bindHTMLEditors();
        bindCodeEditors();
		limitInputs(strFormID_a, strFormElementClass_a);
        populatePredictiveText();
        fetchMetaData();
    }

	function bindTodaysButton()
	{
	   os.unbindEvents(m_strFormID,'todaysdate-button,cleardate-button');
	   os.bindEvent(m_objThis, m_strFormID, '.todaysdate-button', 'TodaysButton', 'onClick');
	   os.bindEvent(m_objThis, m_strFormID, '.cleardate-button', 'ClearDateButton', 'onClick');

		os.bindDatePicker(m_strFormID, '.ge-btdatepicker',
		{
			dateFormat : DATE_OUTPUTFORMAT,
			showOtherMonths : true,
			changeMonth : true,
			changeYear : true,
			yearRange : 'c-75:c+5',
			onSelect : function (dateText, inst)
			{
				m_objThis.raiseRendererEvent('Date', m_strFormID, '.ge-btdatepicker', 'onChange');
				setDirty(true);
            },
            onChangeMonthYear : function(intYear_a, intMonth_a, objDatePicker_a)
            {   
                var newDate = new Date( os.element(this).datepicker('getDate') );
                    newDate.setMonth(intMonth_a - 1);
                    newDate.setYear(intYear_a);
                                
                os.element(this).datepicker('setDate', newDate);
                
				m_objThis.raiseRendererEvent('Date', m_strFormID, '.ge-btdatepicker', 'onChange');
                setDirty(true);
            }
		}
		);

		os.onDirty(m_strFormID, '.todaysdate-button', function()
		{
			m_objThis.raiseRendererEvent('Date', m_strFormID, '.ge-btdatepicker', 'onChange');
			setDirty();
		});

		os.onDirty(m_strFormID, '.cleardate-button', function()
		{
			m_objThis.raiseRendererEvent('Date', m_strFormID, '.ge-btdatepicker', 'onClear');
			m_objThis.raiseRendererEvent('Date', m_strFormID, '.ge-btdatepicker', 'onChange');
			setDirty();
		});
	}

	function bindNowButton()
    {
		os.unbindEvents(m_strFormID,'currenttime-button');
		os.bindEvent(m_objThis, m_strFormID, '.currenttime-button', 'NowButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.cleartime-button', 'ClearTimeButton', 'onClick');
        //os.bindEvent(m_objThis, m_strFormID, '.ge-bttimepicker', 'TimePicker', 'onChange');
        //os.bindEvent(m_objThis, m_strFormID, '.ge-bttimepicker', 'TimePicker', 'onFocus');

		os.bindTimePicker(m_strFormID, '.ge-bttimepicker',
		{
            onSelect : function (objE_a)
			{
				m_objThis.raiseRendererEvent('Time', m_strFormID, '.ge-bttimepicker', 'onChange');
				setDirty(true);
			}
        });

		os.onDirty(m_strFormID, '.currenttime-button', function()
		{
			m_objThis.raiseRendererEvent('Time', m_strFormID, '.ge-bttimepicker', 'onChange');
			setDirty();
		});

		os.onDirty(m_strFormID, '.cleartime-button', function()
		{
			m_objThis.raiseRendererEvent('Time', m_strFormID, '.ge-bttimepicker', 'onClear');
			m_objThis.raiseRendererEvent('Time', m_strFormID, '.ge-bttimepicker', 'onChange');
			setDirty();
		});
    }

    function limitInputs(strFormID_a, strFormElementClass_a)
	{
		var arrInputs = os.element(strFormID_a, '.bs-input-limit');

		processArray(arrInputs, function(obj_a)
		{
			if (obj_a.maxLength)
			{
				if (obj_a.maxLength > 0)
				{
					var strFieldName = $.trim(obj_a.name);
					var strID = getGUID();
					var objOptions = {};
					objOptions.color = 'red';
					objOptions.counter = '#' + strID;
					objOptions.maxLength = obj_a.maxLength;
					objOptions.removeMaxLengthAttr = false;
					objOptions.threshold = 2;

					os.element(m_strFormID, '.' + strFieldName).after('<div class="form-inline small" style="font-size:10px; text-align:right;">&nbsp;&nbsp;<span id="' + strID + '"></span><span> : characters left</span></div>');
					os.element(m_strFormID, '.' + strFieldName).limit(objOptions);
				}
			}
		});
    }

	function getHTMLEditorContent(strSourceID_a)
	{
		var strResult = "";

        processArray(m_arrHTMLEditors, function(objHTMLEditor_a)
        {
			if (objHTMLEditor_a.sourceid == strSourceID_a)
			{
				strResult = objHTMLEditor_a.source.getContent(true);
				return true;
			}
		});

		return strResult;
	}

    function getCodeEditorContent(strSourceID_a)
    {
        var strResult = "";
        //alert('source id ' + strSourceID_a);

        processArray(m_arrCodeEditors, function(objCodeEditor_a)
        {
            if (objCodeEditor_a.sourceid == strSourceID_a)
            {
                //alert('match code editor')
                strResult = objCodeEditor_a.source.getContent(true);
                return true;
            }
        });

        //alert(strResult);
        return strResult;
    }

    function fetchMetaData()
	{
		if (m_arrMetaData.length > 0)
		{
			$.ajax({
				url: "fetch.php?token=" + encodeURIComponent(SECURITY_TOKEN) + "&metadata=" + m_arrMetaData.join(','),
				dataType: "script",
				success: doNothing
			});
		}
    }

	// ====================================================================================
	// WEBSERVICE CALLS & RETURNS =========================================================

	// note: here are some notes on how we only fetch datasources once no matter how often they are used on a form
	// whenever a datasource is required, it is added to an array of required datasources.  the array has also a unique datasourceid (which belongs to a single control only)
	// and that datasourceid is assigned to that specific control.  eg: two controls 1 & 2 can both want the datasource 'gender' they will be given unique datasource ids, even though their datasource is the same.
	// when we iterate through the array of datasources to fetch, when we fetch a datasource we flag it as fetched (even thought it is not yet). before we fetch any datasource, we iterate through the array to
	// ensure it isn't already fetched (or fetching) and skip it.  So if gender is to be fetched twice, the first time we start to fetch it we flag it as fetched.  The second time we skip.
	// when the datasource is ultimately fetched... we iterate through the entire array and assign the returned data to every datasourceid of that same datasource.
	function fetchDropdownData()
	{
		var intToFetch = m_arrDataSources.length;

		var intFetched = 0;
		var intErrors = 0;

		function asyncDataIsFetched()
		{
			intFetched++;

			if (intToFetch == intFetched)
			{
				// populate the file format form
				populateCheckbox();
				populateDropdowns();
				populateDropdownsMultiple();
				populateRadiobuttons();
				
				if ($.isFunction(m_cbRenderComplete))
				{
					os.after(100, function()
					{
						m_cbRenderComplete();
					});
				}
			}
		}

		function asyncError()
		{
			if (intErrors === 0)
			{
				os.ajaxError();
			}
			intErrors++;
		}

		function dataFetched(strSourceID_a, strSource_a, objResponse_a)
		{
			processArray(m_arrDataSources, function(objDataSource_a)
			{  
				if (objDataSource_a.sourceid == strSourceID_a)
				{
					objDataSource_a.data = objResponse_a;
					return true;
				}
			});
			
			asyncDataIsFetched();
		}

		function isFetched(strSourceID_a)
		{
			var blnResult = false;

			processArray(m_arrDataSources, function(objDataSource_a)
			{
				if ((objDataSource_a.sourceid == strSourceID_a) && (objDataSource_a.fetched))
				{
					blnResult = true;
					return true;
				}
			});

			return blnResult;
		}

		function setFetched(strSourceID_a)
		{
			processArray(m_arrDataSources, function(objDataSource_a)
			{
				if (objDataSource_a.sourceid == strSourceID_a)
				{
					objDataSource_a.fetched = true;
					return true;
				}
			});
		}

		function fetchData(strSourceID_a, strSource_a)
		{
			if (isFetched(strSourceID_a))
			{
				// fetched already so fake the fetch
				asyncDataIsFetched();
			}
			else
			{
				setFetched(strSourceID_a);	// setfetched before we even really fetch so next fetches of the same entity can be faked
				
				if (strSource_a === "SYSTEMPRINTERS") // special source for local printers
				{
					var m_arrPrinters = os.getPrinters();
					dataFetched(strSourceID_a, "SYSTEMPRINTERS", m_arrPrinters);
				}
				else
				{
					var arrSource = strSource_a.split("|");	// AWAF_PATTERN: fixed filter on forms. source is in the format of GENDER|['code','description']|[{'field':'enabled','value':'Y'}]
					var strSource = "";
					var arrFilter = [];

					if (arrSource.length > 0)
					{
						strSource = arrSource[0];
					}

					if (arrSource.length > SOURCENOFILTERLENGTH)
					{
						var strFilter = arrSource[2];
						strFilter = str_replace(strFilter, "'", '"');		// form builder didn't like " so we used '
	//strFilter = str_replace(strFilter, "%SELECTED%", m_strDataID);
	//alert("1:" + strFilter);
						arrFilter = JSON.parse(strFilter);
					}

					var objJSON = os.ajaxRequestCreate('entity_entitydatalist',
							[
						{
							"name" : 'entitycode',
							"value" : strSource
						},
						{
							"name" : 'order',
							"value" : ""
						},
						{
							"name" : 'fixedfilter',
							"value" : arrFilter
						},
						{
							"name" : 'offset',
							"value" : 0
						}
					]);

					os.ajaxCall(URL_WEBSERVICE, objJSON, function(objResponse_a)
					{
						dataFetched(strSourceID_a, strSource_a, objResponse_a);
					}, asyncError);
				}
			}
		}

		if (intToFetch > 0)
		{
			processArray(m_arrDataSources, function(objDataSource_a)
			{
				fetchData(objDataSource_a.sourceid, objDataSource_a.source);
			});
		}
		else
		{
			if ($.isFunction(m_cbRenderComplete))
			{
				os.after(100, function()
				{
					m_cbRenderComplete();
				});
			}
		}
	}

	// ====================================================================================
	// INTERNAL EVENTS ====================================================================

    this.ChooseButton_onClick = function (objThis_a)
    {
        //function for Choose
        //alert('Choose');
    };

    this.DownloadButton_onClick = function (objThis_a)
    {
        var objThis = os.element(objThis_a);
        var strDocumentID = htmlDecode(objThis.attr('documentid'));

        os.viewDocument(strDocumentID, true);
    };

    this.DocumentRemoveButton_onClick = function(objThis_a) 
	{
		var objThis = os.element(objThis_a);
		var strDocumentID = htmlDecode( objThis.attr('documentid') );
		var strDocumentWrapperName = htmlDecode( objThis.attr('document') );

		os.dialogConfirm('Are you sure you want to remove the selected document?', function ()
		{
			//objThis.parents('.ge-documentlink').remove();

			removeDocument(objThis_a, strDocumentWrapperName, strDocumentID);

			populateDocuments(strDocumentWrapperName);
		});
    };

	this.DropdownSearch_onClick = function(objThis_a)
	{
        var objThis = os.element(objThis_a);
        var strTargetDataSourceID = htmlDecode( objThis.attr('targetdatasourceid') );
		var strTitle = htmlDecode( objThis.attr('targettitle') );
		var objDataSource = m_objThis.getDataSourceByDataSourceID(strTargetDataSourceID);

		// found the datasource so get the info
		var arrSource = [];
		var strSource = "";
		var arrFields = ["description"];
		var arrFilter = [];

		if (objDataSource.source.length > 0)
		{
			arrSource = objDataSource.source.split("|");
		}

		if (arrSource.length > 0)
		{
			strSource = arrSource[0];
		}

		if (arrSource.length > 1)
		{
			var strFields = arrSource[1];
			strFields = str_replace(strFields, "'", '"');		// form builder didn't like " so we used '
			arrFields = JSON.parse(strFields);
		}

		if (arrSource.length > SOURCENOFILTERLENGTH)
		{
			var strFilter = arrSource[2];
//strFilter = str_replace(strFilter, "%SELECTED%", m_strDataID);
//alert("2:" + strFilter);
			strFilter = str_replace(strFilter, "'", '"');		// form builder didn't like " so we used '
			arrFilter = JSON.parse(strFilter);
		}

		strTitle = strTitle + " chooser";

		var objParams = { type:'form', entity:'systemform', formentity:strSource, mode:'select', title:strTitle, fixedfilter:arrFilter, resultfields : ['id'] };

        os.showFormPopup('entity.frmEntityChooser', objParams, function (objResult_a)
        {
			if (objResult_a.length > 0)
			{
				var strChosenID = objResult_a[0].id;
				os.element(m_strFormID, '.' + strTargetDataSourceID + 'dd').val(strChosenID);

				m_objThis.raiseRendererEvent('Entity', m_strFormID, objDataSource.locator, 'onChange');
				setDirty();
				os.element(m_strFormID, '.' + strTargetDataSourceID + 'dd').focus();
			}
        }, false, true);
	};

	this.EntityPicker_onClick = function(objThis_a)
	{
        var objThis = os.element(objThis_a);
        var strTargetDataSourceID = htmlDecode( objThis.attr('targetdatasourceid') );
		var strTitle = htmlDecode( objThis.attr('targettitle') );
		var objDataSource = m_objThis.getDataSourceByDataSourceID(strTargetDataSourceID);

		// found the datasource so get the info
		var arrSource = [];
		var strSource = "";
		var arrFields = ["description"];
		var arrFilter = [];
		var arrResultFields = [];
		
		if (objDataSource.source.length > 0)
		{
			arrSource = objDataSource.source.split("|");
		}

		if (arrSource.length > 0)
		{
			strSource = arrSource[0];
		}

		if (arrSource.length > 1)
		{
			var strFields = arrSource[1];
			strFields = str_replace(strFields, "'", '"');		// form builder didn't like " so we used '
			strFields = strFields.toLowerCase();
			arrFields = JSON.parse(strFields);
			arrResultFields = JSON.parse(strFields);
		}
		if (arrSource.length > SOURCENOFILTERLENGTH)
		{
			var strFilter = arrSource[2];
//strFilter = str_replace(strFilter, "%SELECTED%", m_strDataID);
//alert("3:" + strFilter);
			strFilter = str_replace(strFilter, "'", '"');		// form builder didn't like " so we used '
			arrFilter = JSON.parse(strFilter);
		}

		arrFields.push('id');	// we need our fields to be returned plus the id
		strTitle = strTitle + " chooser";

		var objParams = { type:'form', entity:'systemform', formentity:strSource, mode:'select', title:strTitle, fixedfilter:arrFilter, resultfields : arrFields };
        os.showFormPopup('entity.frmEntityChooser', objParams, function (objResult_a)
        {
			if (objResult_a.length > 0)
			{
				var strDescription = "";

				processArray(arrResultFields, function(strField_a)
				{
					if (strDescription.length > 0)
					{
						strDescription += " | ";
					}

					strDescription += objResult_a[0][strField_a];
				});

				var strChosenID = objResult_a[0].id;
				var strChosenDescription = strDescription;

				os.element(m_strFormID, '.' + strTargetDataSourceID + 'epv').val(strChosenID);
				os.element(m_strFormID, '.' + strTargetDataSourceID + 'epd').val(strChosenDescription);
				os.element(m_strFormID, '.' + strTargetDataSourceID + 'tds').val(strChosenDescription);

				m_objThis.raiseRendererEvent('Entity', m_strFormID, objDataSource.locator, 'onChange');
				setDirty();
				os.element(m_strFormID, '.' + strTargetDataSourceID + 'tds').focus();
			}
        }, false, true);
	};

    this.EntityPickerClear_onClick = function(objThis_a)
    {
        var objThis = os.element(objThis_a);
        var strTargetDataSourceID = htmlDecode( objThis.attr('targetdatasourceid') );
        var strName = htmlDecode( objThis.attr('name') );
		var objDataSource = m_objThis.getDataSourceByDataSourceID(strTargetDataSourceID);

        os.element(m_strFormID, '.' + strTargetDataSourceID + 'epv').val("");
        os.element(m_strFormID, '.' + strTargetDataSourceID + 'epd').val("");
		os.element(m_strFormID, '.' + strTargetDataSourceID + 'tds').val("");
		
		m_objThis.raiseRendererEvent('Entity', m_strFormID, '.' + strName, 'onClear');
		m_objThis.raiseRendererEvent('Entity', m_strFormID, '.' + strName, 'onChange');
        setDirty();
		os.element(m_strFormID, '.' + strTargetDataSourceID + 'tds').focus();
	};

    this.ImageGalleryButton_onClick = function (objThis_a)
    {
        var strName = os.element(objThis_a).attr('btnid');

        os.showFormPopup('core.frmGallery', 'mode=chooser&caption=Image Selector', function (objResult_a)
        {
            if (m_objOptions.readonly === false)
            {
                if (objResult_a.length > 0)
                {
                    var strImageID = objResult_a[0].id;
                    showImage(strImageID, strName);

					m_objThis.raiseRendererEvent('Image', m_strFormID, '.' + strName, 'onChange');
					setDirty();
                }
            }
        }, false, true);
    };

	this.ImagePickerButton_onClick = function (objThis_a)
    {
        var strName = os.element(objThis_a).attr('btnid');

        singleFileUpload(os, "nobatch", "import_images", "",  function (objResult_a)
        {
            if(objResult_a.length)
            {
                var strImageID = objResult_a[0].tag;
                showImage(strImageID, strName);

				m_objThis.raiseRendererEvent('Image', m_strFormID, '.' + strName, 'onChange');
				setDirty();
            }
        });
    };

	this.ImageDeleteButton_onClick = function (objThis_a)
    {
    	try
    	{
	        var strName = os.element(objThis_a).attr('btnid');

			os.dialogConfirm('Are you sure you want to remove the selected image?', function ()
			{
				os.element(m_strFormID, ' .' + htmlEncode(strName)).attr('value', '');

				showNoImageAvailable(strName);

				m_objThis.raiseRendererEvent('Image', m_strFormID, '.' + strName, 'onClear');
				m_objThis.raiseRendererEvent('Image', m_strFormID, '.' + strName, 'onChange');
				setDirty(true);
			});
    	}
    	catch (err)
    	{
    		doException('ImageDeleteButton_onClick', err);
    	}
    };

    this.HereButton_onClick = function (objThis_a)
    {
		// runs when location is captured
		function locationCaptured(objPosition_a)
		{
			var strCoordinates = objPosition_a.coords.latitude + ', ' + objPosition_a.coords.longitude;
			var objThis = os.element(objThis_a);
	        var strName = os.element(objThis_a).attr('name');
			var objInput = objThis.parent().siblings('input');
			objInput.val(strCoordinates);

			m_objThis.raiseRendererEvent('Location', m_strFormID, '.' + strName, 'onChange');
			setDirty(true);
			objInput.focus();
		}

		function locationFailed()
		{
			os.dialogAlert('Location not found.', doNothing);
		}

		navigator.geolocation.getCurrentPosition(locationCaptured, locationFailed);
    };

    this.MapButton_onClick = function (objThis_a)
    {
        //function for Map
        var strName = os.element(m_strFormID, objThis_a).attr('btnid');
        var strLocation = os.element(m_strFormID, '.'+strName+'').val();

        if (os.isModuleLoaded('maps'))
        {
           os.showForm('maps.frmMap', 'mode=search&search=' + encodeURIComponent(strLocation));
        }
        else if (os.isModuleLoaded('openmaps'))
        {
           os.showForm('openmaps.frmMap', 'mode=search&search=' + encodeURIComponent(strLocation));
        }
    };

    // this.TimePicker_onChange = function (objThis_a){
        // var a = os.element(m_strFormID, objThis_a).val();
        // console.log(a);
        // alert('aaaaaaaa');
    // };

    // this.TimePicker_onFocus = function (objThis_a)
	// {
        // var a = os.element(m_strFormID, objThis_a).val();
        // console.log('focus');
        // console.log('value => ' + a);
        // $.Event("keydown", { keyCode: 39});
        // $.Event("keydown", { keyCode: 39});
        // $.Event("keydown", { keyCode: 39});
        // $.Event("keydown", { keyCode: 40});

        //alert('focus');
        // os.element(m_strFormID, objThis_a).val("--:-- AM");
        // os.element(m_strFormID, objThis_a).dispatchEvent(new KeyboardEvent('keypress', {'key':'ArrowRight'}));
        // os.element(m_strFormID, objThis_a).dispatchEvent(new KeyboardEvent('keypress', {'key':'ArrowRight'}));
        // os.element(m_strFormID, objThis_a).dispatchEvent(new KeyboardEvent('keypress', {'key':'ArrowRight'}));
        // os.element(m_strFormID, objThis_a).dispatchEvent(new KeyboardEvent('keypress', {'key':'ArrowRight'}));
        // os.element(m_strFormID, objThis_a).dispatchEvent(new KeyboardEvent('keypress', {'key':'ArrowDown'}));

        // var code = $(this).data('code');
        // $('#input').trigger(
            // $.Event( 'keydown', { keyCode: code, which: code } )
        // );
    // };

    this.NowButton_onClick = function (objThis_a)
    {
        function isTimeFieldSupported() {

            var objInput = document.createElement('input');

            objInput.setAttribute('type','date');

            var strNotATimeValue = 'not-a-time';

            objInput.setAttribute('value', strNotATimeValue);

            return (objInput.value !== strNotATimeValue);
        }

        var objThis = os.element(objThis_a);
        var strName = htmlDecode(objThis.attr('btnid'));

        var objD = new Date();
        var intHour = objD.getHours();
        var intMinutes = objD.getMinutes();

        var strHour = "";
        var strMinutes = "";
        var strAmPm = "";
		
		if (intHour < 10) { strHour = "0" + intHour; } else { strHour = "" + intHour; }
		if (intMinutes < 10) { strMinutes = "0" + intMinutes; } else { strMinutes = "" + intMinutes; }

        var strCurrentTime = strHour + ":" + strMinutes;

        if(!isTimeFieldSupported())
        {
			if (intHour > 11) { strAmPm = "PM"; } else {strAmPm = "AM"; }

            if(intHour > 11) {
               intHour = intHour - 12;
            }

			if (intHour < 10) { strHour = "0" + intHour; } else { strHour = "" + intHour; }
			if (intMinutes < 10) { strMinutes = "0" + intMinutes; } else { strMinutes = "" + intMinutes; }

            strCurrentTime = strHour + ":" + strMinutes + " " + strAmPm;
        }

        os.element(m_strFormID, '.'+strName).val(strCurrentTime);

		m_objThis.raiseRendererEvent('Time', m_strFormID, '.' + strName, 'onChange');
		setDirty(true);
		os.element(m_strFormID, '.'+strName).focus();
    };

    this.RelatedLinkButton_onClick = function (objThis_a)
    {
        var strCommand = os.element(m_strFormID, objThis_a).attr('command');
        var strParameters = os.element(m_strFormID, objThis_a).attr('parameters');

		strParameters = str_replace(strParameters, "|", '&');						// form builder didn't like & so we used |
		strParameters = str_replace(strParameters, "%SELECTED%", "/SELECTED/");		// urlToJSON doesn't like %

		var objParams = os.urlToJSON(strParameters);
		objParams.fixedfilter = str_replace(objParams.fixedfilter, "'", '"');		// form builder didn't like " so we used '
		objParams.fixedfilter = str_replace(objParams.fixedfilter, "/SELECTED/", m_strDataID);
		objParams.relativeid = str_replace(objParams.relativeid, "/SELECTED/", m_strDataID);

        os.showForm(strCommand, objParams);
    };

    this.SelectButton_onClick = function (objThis_a)
    {
        var strFieldName = htmlDecode(os.element(m_strFormID, objThis_a).attr("btnid"));
        selectDocument(strFieldName);
    };

    this.UploadButton_onClick = function (objThis_a)
    {
        var strFieldName = htmlDecode(os.element(m_strFormID, objThis_a).attr("btnid"));
        uploadDocument(strFieldName);
    };

    this.UrlField_onChange = function (objThis_a, objElement_a)
    {
        var strFieldName = os.element(m_strFormID, objThis_a).attr("name");
        var strValue = os.element(m_strFormID, objThis_a).val();

        changeURLLink(strFieldName,strValue);
    };

    this.ViewButton_onClick = function (objThis_a)
    {
        var objThis = os.element(objThis_a);
        var strDocumentID = htmlDecode(objThis.attr('documentid'));

        os.viewDocument(strDocumentID, false);
    };

    this.ClearDateButton_onClick = function (objThis_a)
    {
        var objThis = os.element(objThis_a);
        var strName = htmlDecode(objThis.attr('btnid'));

        os.element(m_strFormID, '.' + strName).val("");
		
		m_objThis.raiseRendererEvent('Date', m_strFormID, '.' + strName, 'onClear');
		m_objThis.raiseRendererEvent('Date', m_strFormID, '.' + strName, 'onChange');
        setDirty();
		os.element(m_strFormID, '.' + strName).focus();
    };

    this.ClearTimeButton_onClick = function (objThis_a)
    {
        var objThis = os.element(objThis_a);
        var strName = htmlDecode(objThis.attr('btnid'));

        os.element(m_strFormID, '.' + strName).val("");
		
		m_objThis.raiseRendererEvent('Time', m_strFormID, '.' + strName, 'onClear');
		m_objThis.raiseRendererEvent('Time', m_strFormID, '.' + strName, 'onChange');
        setDirty();
		os.element(m_strFormID, '.' + strName).focus();
    };

    this.TodaysButton_onClick = function (objThis_a)
    {
        var objThis = os.element(objThis_a);
        var strName = htmlDecode(objThis.attr('btnid'));

        var currentDate = new Date();
        var day = currentDate.getDate();
        var month = currentDate.getMonth() + 1;
        var year = currentDate.getFullYear();

        if(month.toString().length == 1)
        {
        	month = '0' + month;
        }

        if(day.toString().length == 1)
        {
        	day = '0' + day;
        }

        var strTodaysDate = year + '-' + month + '-' + day;

        //os.element(m_strFormID, '.' + strName).val(strTodaysDate.toString());
		var strDateValue = "";
		if (strTodaysDate.length >= 2)	// we need more than two -- together
		{
			strDateValue = os.dateFromISO(DATE_OUTPUTFORMAT, strTodaysDate.toString());
		}

        os.element(m_strFormID, '.' + strName).val(strDateValue);

		m_objThis.raiseRendererEvent('Date', m_strFormID, '.' + strName, 'onChange');
        setDirty();
		os.element(m_strFormID, '.' + strName).focus();
    };

	// ====================================================================================
	// PUBLICS ============================================================================

	this.append = function (arrFormSections_a)
	{
		// append form sections here, note we can append multiple dataforms, but only keep the formheader of the first appended form
		processArray(arrFormSections_a, function (arrFormSection_a)
		{
			//check if already has a FormHeader Section. skip if exist
			if (m_arrFormSections.length >= 1 && arrFormSection_a.sectiontype === 'FORMHEADER')
			{
				doNothing();
			}
			else
			{
				m_arrFormSections.push(arrFormSection_a);
			}

		}
		);
	};

	this.clear = function ()
	{
		// remove all form sections
		m_arrFormSections = [];
		m_blnDirty = false;
	};

	this.clearData = function ()
	{
		// reset form to have no data
		processArray(m_arrFormSections, function (objSection_a)
		{

			if (objSection_a.sectiontype.toUpperCase() === 'FORMHEADER' || objSection_a.sectiontype.toUpperCase() === 'DATAHEADER')
			{
				doNothing();
			}
			else
			{
				processArray(objSection_a.fields, function (objField_a)
				{
					var strPName = massagePName(objSection_a.sectioncode, objField_a.p_name);
					var strDataType = objField_a.p_datatype;
					if (strDataType === 'd_yesno')
					{
						objField_a.p_value = 'Y';
					}
					else
					{
						objField_a.p_value = '';
					}

				}
				);
			}

		}
		);

		m_blnDirty = false;
	};

	this.getData = function ()
	{
		// return all appended sections as a single form JSON with any entered data in place
		if (m_blnDirty)
		{
			updateJSONwithElementValues();
			m_blnDirty = false;
		}

		return m_arrFormSections;
	};

	this.getHTML = function (blnIncludeTitle_a)
	{
		var blnIncludeTitle = blnIncludeTitle_a;
		if (blnIncludeTitle == undefined) { blnIncludeTitle = true; }
		
		m_blnInvalidDate = false;
		m_strInvalidDates = "";

		// render the form at the provided form element
		if (m_blnDirty)
		{
			updateJSONwithElementValues();
			m_blnDirty = false;
		}

		m_strDirtyFields = '';
		var strForm = renderSections(m_arrFormSections, true);
		var strBusinessName = os.getProperty('businessname');
		var strFormName = getFormName();

		var strFormTitle = "";
		if (blnIncludeTitle)
		{
			if (strBusinessName.length > 0)
			{
				strFormTitle += "<h1>" + htmlEncode(strBusinessName) + "</h1>";
			}

			if (strFormName.length > 0)
			{
				strFormTitle += "<h1>" + htmlEncode(strFormName) + "</h1>";
			}
		}

		var strFormFooter = REPORT_PRINTEDBY;
		return strFormTitle + strForm + strFormFooter;
	};

	this.getSectionField = function(strSectionCode_a, strFieldName_a)
    {
		var strResult = '';

        processArray(m_arrFormSections, function (objSection_a)
        {
            if (objSection_a.sectioncode.toUpperCase() === strSectionCode_a.toUpperCase())
            {
                processArray(objSection_a.fields, function (objField_a)
                {
                    var strPName = massagePName(objSection_a.sectioncode, objField_a.p_name);
					var strFieldName = massagePName(strSectionCode_a, strFieldName_a);

                    if (strPName.toUpperCase() === strFieldName.toUpperCase())
                    {
                        strResult = objField_a.p_value;
						return true;
                    }
                }
                );
            }
        }
        );

		return strResult;
    };

	// used by the parent form to retrieve things such as the data code for the form title
    this.getSectionFieldByType = function(strSectionType_a, strFieldName_a)
    {
        var strResult = '';

        processArray(m_arrFormSections, function (objSection_a)
        {
            if (objSection_a.sectiontype.toUpperCase() === strSectionType_a.toUpperCase())
            {
                processArray(objSection_a.fields, function (objField_a)
                {
                    var strPName = massagePName(objSection_a.sectiontype, objField_a.p_name);
					var strFieldName = massagePName(strSectionType_a, strFieldName_a);

                    if (strPName.toUpperCase() === strFieldName.toUpperCase())
                    {
                        strResult = objField_a.p_value;
                        return true;
                    }
                }
                );
            }
        }
        );

        return strResult;
    };

	// JAYSON: RETURN A LIST OF FORM FIELDS WHICH YOU CALL IN wgtForm.js setTabOrder in place of m_strFormFields
	this.getTabableFields = function()
	{
	};

	this.setData = function (arrFormData_a)
	{
		// set data within the form's sections based on what is provided in arrFormData_a.  we can still setData if the form is readonly, just the user cannot type in the controls if the form is readonly
		processArray(arrFormData_a, function (objSection_a)
		{
			if (objSection_a.sectiontype.toUpperCase() === 'FORMHEADER' || objSection_a.sectiontype.toUpperCase() === 'DATAHEADER')
			{
				doNothing();
			}
			else
			{
				if ((objSection_a.sectiontype.toUpperCase() === 'DATA') || (objSection_a.sectiontype.toUpperCase() === 'INTERNALUSEFORMHEADER' && hasPermission(['IUSE_DATAFORM'])) || (objSection_a.sectiontype.toUpperCase() === 'INTERNALUSEONLY' && hasPermission(['IUSE_DATAFORM'])))
				{
					processArray(objSection_a.fields, function (objField_a)
					{
						m_objThis.setSectionField(objSection_a.sectioncode, objField_a.p_name, objField_a.p_value);
					}
					);
				}
				else
				{
					doNothing();
				}
			}
		}
		);
	};
    
	this.render = function (strFormID_a, strFormElementClass_a, blnReadOnly_a)
	{
		// render the form at the provided form element
		m_blnInvalidDate = false;
		m_strInvalidDates = "";

		m_objOptions.readonly = blnReadOnly_a;
        m_strFormID = strFormID_a;
        m_strFormElementClass = strFormElementClass_a;

		m_strDirtyFields = '';
		var strHTML = renderSections(m_arrFormSections, m_objOptions.html);

		os.element(strFormID_a, strFormElementClass_a).html(strHTML);
        
        bindFormEvents(m_strFormID);

		if(m_objOptions.readonly)
        {
            os.element(m_strFormID, '.fb-imagepicker-btn').hide();
            os.element(m_strFormID, '.fb-imagegallery-btn').hide();
			os.element(m_strFormID, '.fb-imagedelete-btn').hide();
            os.element(m_strFormID, '.ge-uploaddocument-button').hide();
            os.element(m_strFormID, '.ge-selectdocument-button').hide();
        }
        else
        {
            os.element(m_strFormID, '.fb-imagepicker-btn').show();
            os.element(m_strFormID, '.fb-imagegallery-btn').show();
			os.element(m_strFormID, '.fb-imagedelete-btn').show();
            os.element(m_strFormID, '.ge-uploaddocument-button').show();
            os.element(m_strFormID, '.ge-selectdocument-button').show();
        }

        if (hasPermission(['VW_MAPS']))
        {
            os.element(m_strFormID, '.fb-map-btn').show();
        }
        else
        {
            os.element(m_strFormID, '.fb-map-btn').hide();
        }

		if (m_blnInvalidDate && !blnReadOnly_a)
		{
			var strMessage = "Attention: The following fields were updated to a new data format:<br><br>" + m_strInvalidDates + "<br><br>It is recommended you save this form.";
			os.dialogAlert(strMessage, doNothing);
			os.after(500, setDirty);	// set it dirty after it's rendered
		}

        if(!blnReadOnly_a)
        {
            os.element(m_strFormID, '.ge-focusfield').focus();
        }

	};

	//set form ID
	this.setID = function(strID_a)
	{
	    m_strID = strID_a;
	};

	//set form data ID
	this.setDataID = function(strDataID_a)
	{
	    m_strDataID = strDataID_a;
	};

	this.setSectionField = function(strSectionCode_a, strFieldName_a, strValue_a)
    {
        processArray(m_arrFormSections, function (objSection_a)
        {
            if (objSection_a.sectioncode.toUpperCase() === strSectionCode_a.toUpperCase())
            {
                processArray(objSection_a.fields, function (objField_a)
                {
                    var strPName = massagePName(objSection_a.sectioncode, objField_a.p_name);
					var strFieldName = massagePName(strSectionCode_a, strFieldName_a);

                    if (strPName.toUpperCase() === strFieldName.toUpperCase())
                    {
                        objField_a.p_value = strValue_a;
                    }
                }
                );
            }
        }
        );
    };

	//RETURN AN <ul> OF ALL THE NON-PROVIDED MANDATORY FIELDS '<fieldname> is requried.' ETC AS PER validateJSONFieldValue (this function should not exist in the widget and the normal validate() should call jFormRenderer.validate()
	this.validate = function()
	{
	    var strError = '';

		if (m_blnDirty)
		{
			updateJSONwithElementValues();
			m_blnDirty = false;
		}

        processArray(m_arrFormSections, function (objSection_a)
        {

            if ( objSection_a.sectiontype.toUpperCase() === 'FORMHEADER' || objSection_a.sectiontype.toUpperCase() === 'DATAHEADER' )
            {
                doNothing();
            }
            else
            {
                if( (objSection_a.sectiontype.toUpperCase() === 'DATA') || ( objSection_a.sectiontype.toUpperCase() === 'INTERNALUSEFORMHEADER' && hasPermission(['IUSE_DATAFORM']) ) || ( objSection_a.sectiontype.toUpperCase() === 'INTERNALUSEONLY' && hasPermission(['IUSE_DATAFORM']) )  )
                {
                    processArray(objSection_a.fields, function (objField_a)
                    {
                        var strPName = '';
                        var strPValue = '';
                        var strDataType = objField_a.p_datatype;
						var strIsRequired = objField_a.p_required;
                        var strLabel = htmlEncode(objField_a.p_label);

                        if (strDataType === 'd_yesno' || strDataType === 'd_spacer' || strDataType === 'd_heading' || strDataType === 'd_description')
                        {
                            doNothing();
                        }
                        else if ((strDataType === 'd_list') || (strDataType === 'd_predictivetext'))
                        {
							if(os.toBoolean(strIsRequired))
                            {
								strPName = massagePName(objSection_a.sectioncode, objField_a.p_name + 'epv');
								strPValue = $.trim(objField_a.p_value);

								if ( strPValue.length === 0  )
								{
									strError += '<li>' + htmlEncode(strLabel) + ' is required.</li>';
								}
							}
                        }
                        else
                        {
							if(os.toBoolean(strIsRequired))
                            {
								strPName = massagePName(objSection_a.sectioncode, objField_a.p_name);
								strPValue = $.trim(objField_a.p_value);

								var blnIsHidden = os.element(m_strFormID, '.' + strPName).is(":hidden");
								if(!blnIsHidden)
								{
									if ( strPValue.length === 0  )
									{
										strError += '<li>' + htmlEncode(strLabel) + ' is required.</li>';
									}
								}

                            }
                        }
                    });
                }
                else
                {
                    doNothing();
                }
            }
        });
		
        return strError;
	};

    // capturing onload event of the image  (.ge-image-field)
    this.ImageField_onLoad = function(objThis_a) {
        var intWidth = objThis_a.width;
        var intHeight = objThis_a.height;

        var intAspectRatio = intWidth / intHeight;

        // by making width or height 100%, image will display on its aspect ratio w/o distoring
        // the trick is to determine if the image is wide or long
        // added by Jhun
        //
        // reset width/height set before
        $(objThis_a).css("width", "");
        $(objThis_a).css("height", "");

        if(intAspectRatio > 1) { // landscape or image is wide
           $(objThis_a).css("width", "100%");
        }
        else { // portrait or image is long
           $(objThis_a).css("height", "100%");
        }

    };

    this.SectionAddCurrent_onClick = function(objThis_a)
    {        
        var strSectionCode = os.element(objThis_a).data('sectioncode');
        var objNewSection = null;
        var arrFormSections = [];

        processArray(m_arrFormSections, function (objSection_a)
        {
            arrFormSections.push(objSection_a);

            if(objSection_a.sectioncode === strSectionCode)
            {   
                // prevents assigning by referrence
                objNewSection = JSON.parse(JSON.stringify(objSection_a));    
                
                objNewSection.sectionquantity = "1";
                objNewSection.sectioncode = getGUID();
                objNewSection.sectionref = strSectionCode;

                processArray(objNewSection.fields, function (objField_a)
				{
                    // make all fields of cloned section non-searchable
                    objField_a.p_searchable = "";
				}
                );
                
                // push new section here so that it will be next to the parent section
                arrFormSections.push(objNewSection);
            }
            
        });
        
        m_arrFormSections = arrFormSections;
                
        var strAccordionParentID = os.element(objThis_a).parents('.ge-formsection-group').attr('id');
        var strHTML = renderFormSection(objNewSection, true, m_objOptions.html, strAccordionParentID);

        os.element(objThis_a).parents('.ge-formsection').after(strHTML);

        bindFormEvents(m_strFormID, m_strFormElementClass);
        setDirty();
    };

    this.SectionRemoveCurrent_onClick = function(objThis_a)
    {
        
        var strSectionCode = os.element(objThis_a).data('sectioncode');
        var arrFormSections = [];

        processArray(m_arrFormSections, function (objSection_a)
        {
            if(objSection_a.sectioncode !== strSectionCode)
            {
                arrFormSections.push(objSection_a);
            }
        });
        
        m_arrFormSections = arrFormSections;

        os.element(objThis_a).parents('.ge-formsection').remove();
        setDirty();
    };
}
