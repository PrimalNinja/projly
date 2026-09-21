/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/

// notes:
// the formbuilder jquery component uses a temporary dom element fb-temp that it manipulates and stores all of it's properties within
// wgtFormBuilder monitors the changes in this event so that it knows when to update it's internal json structures
function widgetformbuilder_wgtFormBuilder(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	// ------------------------------------------------------------------------------------

	var m_blnReadonly = false;
	var m_blnFormDirty = false;

	var m_intToFetch = 0;
	var m_intFetched = 0;
	var m_intErrors = 0;

    var m_intFormHeight = 0;

	var m_strFormFields = 'ge-form-title,fb-temp';

	var m_objFormBuilder = null;
	var m_blnClosed = false;

	var m_arrFormData = null;
	var m_arrFormDataPrevious = null;
	if (m_objParameters.currentsection !== undefined)
	{
		m_arrFormData = JSON.parse(m_objParameters.currentsection);
		m_arrFormDataPrevious = m_arrFormData;
		preserveSectionCodes();
	}

	if (m_objParameters.internalmode === undefined)
	{
		m_objParameters.internalmode = 'form';
	}

	var m_strSectionType = m_objParameters.sectiontype;
	if (m_strSectionType === undefined)
	{
		m_strSectionType = "";
	}

	var m_strID = m_objParameters.id;
	if (m_strID === undefined)
	{
		m_strID = "";
	}

	var m_strEntityCode = m_objParameters.entity;
	if (m_strEntityCode === undefined)
	{
		m_strEntityCode = '';
	}

	var m_strFormEntityDescription = m_objParameters.formentitydescription; // eg: koalaform
	if (m_strFormEntityDescription === undefined)
	{
		m_strFormEntityDescription = '';
	}

	var m_strFormEntityCode = m_objParameters.formentity;
    if (m_strFormEntityCode === undefined)
    {
        m_strFormEntityCode = '';
    }

	var m_strFormTitle = '';
	var m_objFBoptions = '';

	// ------------------------------------------------------------------------------------

	var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : [
				['CloseButton', 'SaveButton', 'RenderButton']
			]
		}
	];

	if(m_objParameters.internalmode === 'form')
	{
		m_arrMap[0].layout = [['CloseButton', 'SaveButton', 'CopyButton', 'RenderButton']];
	}

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
			id : 'CopyButton',
			caption : 'Copy',
			classes : 'gs-darkblue-background-colour gs-glow-focusborder',
			permissions : [],
			action : function ()
			{
				m_objThis.CopyButton_onClick();
			},
			actionData : null,
			tip : 'Click here to copy form.',
			type : 'toolbarbutton'
		},
		{
			id : 'CloseButton',
			caption : function() { var strResult = 'Close'; if (m_objParameters.internalmode === "formsection") { strResult = 'Cancel'; } return strResult; },
			classes : 'gs-green-background-colour gs-glow-focusborder',
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
			id : 'RenderButton',
			caption : 'Render',
			classes : 'gs-darkblue-background-colour gs-glow-focusborder',
			permissions : ['DEVELOPER'],
			action : function ()
			{
				m_objThis.RenderButton_onClick();
			},
			actionData : null,
			tip : 'Click here to render form.',
			type : 'toolbarbutton'
		},
		{
			id : 'SaveButton',
			caption : function() { var strResult = 'Save'; if (m_objParameters.internalmode === "formsection") { strResult = 'OK'; } return strResult; },
			classes : 'gb-cell-disabled',
			permissions : [],
			action : function ()
			{
				m_objThis.SaveButton_onClick();
			},
			actionData : null,
			tip : function() { var strResult = 'Click here to save.'; if (m_objParameters.internalmode === "formsection") { strResult = 'Click here to keep changes.'; } return strResult; } ,
			type : 'toolbarbutton'
		},
		{
			id : 'ClearButton',
			caption : 'Clear',
			classes : 'gs-darkblue-background-colour gs-glow-focusborder',
			permissions : [],
			action : function ()
			{
				m_objThis.ClearButton_onClick();
			},
			actionData : null,
			tip : 'Click here to clear form.',
			type : 'toolbarbutton'
		}

	];

	// ====================================================================================
	// JSON HELPERS ============================================================================

	function getDefaultForm()
	{
		var strDataHeaderGUID = getGUID('g');
		var strFormHeaderGUID = getGUID('g');
		var strFormFieldsGUID = getGUID('g');
		var strDataGroupNameGUID = '';
		var strInternalUseFormHeaderGUID = "INTERNALUSEFORMHEADER";	// note: this is not really a GUID because we are programatically creating these also from PHP and they must match

		if (m_strEntityCode !== "systemform")
		{
			strDataGroupNameGUID = getGUID('g');
		}

		var objResult = [
			{
				"sectionclasses" : "btn btn-default fb-section-button",
				"sectioncode" : strFormHeaderGUID,
				"sectioncodetemp" : strFormHeaderGUID,
				"sectioncolour" : "",
				"sectionref" : "",
				"sectiontitle" : "Form Header",
				"sectiontype" : "FORMHEADER",
				"fields" : [
					{
						"p_classes" : "form-control",
						"p_colour" : "",
						"p_datatype" : "d_text",
						"p_info" : "",
						"p_label" : "Data Group Name",
						"p_length" : "20",
						"p_name" : "ENTITY",
						"p_quantity" : "1",
						"p_readonly" : "N",
						"p_required" : "N",
						"p_searchable" : "N",
						"p_sortable" : "N",
						"p_source" : "",
						"p_style" : "",
						"p_value" : strDataGroupNameGUID,
						"p_valuedescription" : ""
					},
					{
                        "p_classes" : "form-control",
						"p_colour" : "",
						"p_datatype" : "d_version",
                        "p_info" : "",
                        "p_label" : "Form Version",
                        "p_length" : "",
                        "p_name" : "FORMVERSION",
                        "p_quantity" : "1",
                        "p_readonly" : "Y",
                        "p_required" : "N",
                        "p_searchable" : "N",
                        "p_sortable" : "N",
						"p_source" : "",
						"p_style" : "",
                        "p_value" : "1",
						"p_valuedescription" : ""
                    },
					{
						"p_classes" : "form-control",
						"p_colour" : "",
						"p_datatype" : "d_text",
						"p_info" : "",
						"p_label" : "Code",
						"p_length" : "40",
						"p_name" : "CODE",
						"p_quantity" : "1",
						"p_readonly" : "N",
						"p_required" : "Y",
						"p_searchable" : "Y",
						"p_sortable" : "Y",
						"p_source" : "",
						"p_style" : "",
						"p_value" : "",
						"p_valuedescription" : ""
					},
					{
						"p_classes" : "form-control",
						"p_colour" : "",
						"p_datatype" : "d_text",
						"p_info" : "",
						"p_label" : "Description",
						"p_length" : "40",
						"p_name" : "DESCRIPTION",
						"p_quantity" : "1",
						"p_readonly" : "N",
						"p_required" : "Y",
						"p_searchable" : "Y",
						"p_sortable" : "Y",
						"p_source" : "",
						"p_style" : "",
						"p_value" : "",
						"p_valuedescription" : ""
					},
					{
						"p_classes" : "d_yesno",
						"p_colour" : "",
						"p_datatype" : "d_yesno",
						"p_info" : "",
						"p_label" : "Is Enabled?",
						"p_length" : "1",
						"p_name" : "ISENABLED",
						"p_quantity" : "1",
						"p_readonly" : "N",
						"p_required" : "N",
						"p_searchable" : "N",
						"p_sortable" : "N",
						"p_source" : "",
						"p_style" : "",
						"p_value" : "Y",
						"p_valuedescription" : ""
					}
				]
			},
			{
				"sectionclasses" : "btn btn-default fb-section-button",
				"sectioncode" : strDataHeaderGUID,
				"sectioncodetemp" : strDataHeaderGUID,
				"sectioncolour" : "",
				"sectionref" : "",
				"sectiontitle" : "Data Header",
				"sectiontype" : "DATAHEADER",
				"fields" : [
					{
						"p_classes" : "form-control",
						"p_colour" : "",
						"p_datatype" : "d_version",
						"p_info" : "",
						"p_label" : "Data Version",
						"p_length" : "",
						"p_name" : "DATAVERSION",
						"p_quantity" : "1",
						"p_readonly" : "Y",
						"p_required" : "N",
						"p_searchable" : "N",
						"p_sortable" : "N",
						"p_source" : "",
						"p_style" : "",
						"p_value" : "1",
						"p_valuedescription" : ""
					}
				]
			},
			{
				"sectionclasses" : "btn btn-default fb-section-button",
				"sectioncode" : strInternalUseFormHeaderGUID,
				"sectioncodetemp" : strInternalUseFormHeaderGUID,
				"sectioncolour" : "",
				"sectionref" : "",
				"sectiontitle" : "Internal Use Form header",
				"sectiontype" : "INTERNALUSEFORMHEADER",
				"fields" : [
					{
						"p_colour" : "",
						"p_source" : "",
                        "p_classes" : "form-control",
                        "p_datatype" : "d_text",
                        "p_info" : "",
                        "p_label" : "Submitted Date Time",
                        "p_length" : "20",
                        "p_name" : "SUBMITTEDDATETIME",
                        "p_quantity" : "1",
                        "p_readonly" : "N",
                        "p_required" : "N",
                        "p_searchable" : "N",
                        "p_sortable" : "N",
                        "p_style" : "",
                        "p_value" : "",
						"p_valuedescription" : ""
                    },
					{
						"p_colour" : "",
						"p_source" : "",
                        "p_classes" : "form-control",
                        "p_datatype" : "d_text",
                        "p_info" : "",
                        "p_label" : "Submitted By Client",
                        "p_length" : "20",
                        "p_name" : "SUBMITTEDBYCLIENT",
                        "p_quantity" : "1",
                        "p_readonly" : "N",
                        "p_required" : "N",
                        "p_searchable" : "N",
                        "p_sortable" : "N",
                        "p_style" : "",
                        "p_value" : "",
						"p_valuedescription" : ""
                    },
                    {
						"p_colour" : "",
						"p_source" : "",
                        "p_classes" : "form-control",
                        "p_datatype" : "d_text",
                        "p_info" : "",
                        "p_label" : "Submitted By User",
                        "p_length" : "20",
                        "p_name" : "SUBMITTEDBYUSER",
                        "p_quantity" : "1",
                        "p_readonly" : "N",
                        "p_required" : "N",
                        "p_searchable" : "N",
                        "p_sortable" : "N",
                        "p_style" : "",
                        "p_value" : "",
						"p_valuedescription" : ""
                    }
				]
			},
			{
				"sectionclasses" : "btn btn-default fb-section-button",
				"sectioncode" : strFormFieldsGUID,
				"sectioncodetemp" : strFormFieldsGUID,
				"sectioncolour" : "",
				"sectionref" : "",
				"sectiontitle" : "Form Fields",
				"sectionquantity" : "1",
				"sectiontype" : "DATA",
				"fields" : [
					{
						"p_classes" : "form-control",
						"p_colour" : "",
						"p_datatype" : "d_text",
						"p_info" : "",
						"p_label" : "Code",
						"p_length" : "40",
						"p_name" : "CODE",
						"p_quantity" : "1",
						"p_readonly" : "N",
						"p_required" : "Y",
						"p_searchable" : "Y",
						"p_sortable" : "Y",
						"p_source" : "",
						"p_style" : "",
						"p_value" : "",
						"p_valuedescription" : ""
					},
					{
						"p_classes" : "form-control",
						"p_colour" : "",
						"p_datatype" : "d_text",
						"p_info" : "",
						"p_label" : "Description",
						"p_length" : "40",
						"p_name" : "DESCRIPTION",
						"p_quantity" : "1",
						"p_readonly" : "N",
						"p_required" : "Y",
						"p_searchable" : "Y",
						"p_sortable" : "Y",
						"p_source" : "",
						"p_style" : "",
						"p_value" : "",
						"p_valuedescription" : ""
					},
					{
						"p_classes" : "d_yesno",
						"p_colour" : "",
						"p_datatype" : "d_yesno",
						"p_info" : "",
						"p_label" : "Is Enabled?",
						"p_length" : "1",
						"p_name" : "ISENABLED",
						"p_quantity" : "1",
						"p_readonly" : "N",
						"p_required" : "N",
						"p_searchable" : "N",
						"p_sortable" : "N",
						"p_source" : "",
						"p_style" : "",
						"p_value" : "Y",
						"p_valuedescription" : ""
					}
				]
			}
		];

		return objResult;
	}

	function getFields(arrParentFields_a, strSectionCode_a)
	{
		var arrResult = [];

		processArray(arrParentFields_a, function (objSection_a)
		{
			if (objSection_a.sectioncodetemp == strSectionCode_a)	// ?
			{
				arrResult = objSection_a.fields;
			}
		}
		);

		return arrResult;
	}

	function getFormFieldCount()
	{
		var intResult = 0;

        processArray(m_arrFormData, function (objSection_a)
        {
			var arrFields = objSection_a.fields;
			processArray(arrFields, function(objField_a)
			{
				intResult++;
			});
        });

		return intResult;
	}

	function setFields(arrParentFields_a, strSectionCode_a, arrFields_a)
	{
		var intSection = -1;
		var intSectionFound = -1;

		processArray(arrParentFields_a, function (objSection_a)
		{
			intSection++;
			if (objSection_a.sectioncodetemp == strSectionCode_a)	// ?
			{
				intSectionFound = intSection;
			}
		}
		);

		if (intSectionFound >= 0)
		{
			arrParentFields_a[intSectionFound].fields = arrFields_a;
		}
	}

	function JSONSetFieldValue(strName_a, strValue_a)
	{
		var blnFound = false;

		processArray(m_arrFormData, function(objField_a)
		{
			if (objField_a.p_name == strName_a)
			{
				objField_a.p_value = strValue_a;
				objField_a.p_valuedescription = "";	// for now we don't have much to do with this in builder, but make sure the property is here
				blnFound = true;
			}
		});

		//if (blnFound)
		//{
			//logDebug('success to update field: ' + strName_a + ' with value ' + strValue_a);
		//}
		//else
		//{
			//logDebug('failed to update field: ' + strName_a + ' with value ' + strValue_a);
		//}
	}

	function JSONtoXML(arrJSON_a)
	{
		var strResult = '';
		var objResult = {};
		var objConverter = new X2JS({useDoubleQuotes:true, keepCData:true});

		if (arrJSON_a.length > 0)
		{
			objResult =
			{
				'form-template' :
				{
					'fields' :
					{
						'field' : []
					}
				}
			};

			processArray(arrJSON_a, function (objField_a)
			{
				objResult["form-template"]["fields"]["field"].push(JSONtoXMLField(objField_a));
				//objResult.sectionfields['form-template'].fields.field.push(objResult);
			}
			);

			strResult = objConverter.json2xml_str(objResult);
		}
		return strResult;
	}

	function JSONtoXMLField(objField_a)
	{
		var objResult = {};

		if (m_objParameters.internalmode === "form")
		{
			objResult._class = JSONtoXMLMassageField(objField_a.sectionclasses);
			objResult._label = JSONtoXMLMassageField(objField_a.sectiontitle);
			objResult._quantity = JSONtoXMLMassageField(objField_a.sectionquantity);
			objResult._name = JSONtoXMLMassageField(objField_a.sectioncode);
			objResult._colour = JSONtoXMLMassageField(objField_a.sectioncolour);
			objResult._sectionref = JSONtoXMLMassageField(objField_a.sectionref);
			objResult._sectiontype = JSONtoXMLMassageField(objField_a.sectiontype);

			if (objField_a.sectiontype === 'DATA') // data
			{
				objResult._type = 'datasection';
			}
			else if (objField_a.sectiontype === 'DATAHEADER')
			{
				objResult._type = 'dataheadersection';
			}
			else if (objField_a.sectiontype === 'FORMHEADER')
			{
				objResult._type = 'formheadersection';
			}
			else if (objField_a.sectiontype === 'INTERNALUSEFORMHEADER')
			{
				objResult._type = 'internaluseformheadersection';
			}
			else if (objField_a.sectiontype === 'INTERNALUSEONLY')	// internal use only data
			{
				objResult._type = 'internaluseonlysection';
			}

			objResult._tempname = JSONtoXMLMassageField(objField_a.sectioncodetemp);	// ok
		}
		else
		{
			objResult._class = JSONtoXMLMassageField(objField_a.p_classes);
			objResult._colour = JSONtoXMLMassageField(objField_a.p_colour);
			objResult._fieldsource = JSONtoXMLMassageField(objField_a.p_source);
			objResult._fieldstyle = JSONtoXMLMassageField(objField_a.p_style);
			objResult._infovalue = JSONtoXMLMassageField(objField_a.p_info);
			objResult._label = JSONtoXMLMassageField(objField_a.p_label);
			objResult._length = JSONtoXMLMassageField(objField_a.p_length);
			objResult._lines = JSONtoXMLMassageField(objField_a.p_lines);
			objResult._name = JSONtoXMLMassageField(objField_a.p_name);
			objResult._quantity = JSONtoXMLMassageField(objField_a.p_quantity);
			objResult._readonly = JSONtoXMLMassageField(objField_a.p_readonly);
			objResult._required = JSONtoXMLMassageField(objField_a.p_required);
			objResult._searchable = JSONtoXMLMassageField(objField_a.p_searchable);
			objResult._sortable = JSONtoXMLMassageField(objField_a.p_sortable);
			objResult._subtype = JSONtoXMLMassageField(objField_a.p_datatype);	// this _subtype is something to do with form-builder.js
			objResult._type = JSONtoXMLMassageField(objField_a.p_datatype);
			objResult._value = JSONtoXMLMassageField(objField_a.p_value);
			objResult._valuedescription = JSONtoXMLMassageField(objField_a.p_valuedescription);

			// JULIAN note: at present we are not able to easily work out which section type this field is in, so a quick and dirty additional check of the label is done.
			// ideally we should only add this class for ENTITY within the FORMHEADER
			if ((objResult._name === 'ENTITY') && (objResult._label === 'Data Group Name'))
			{
				// remove duplicates also if there are
				var strClass = objResult._class;
				strClass = str_replace(strClass, 'ge-dataformgroup-field', '');
				strClass = str_replace(strClass, '  ', ' ');
				strClass += ' ge-dataformgroup-field';
				objResult._class = strClass;
			}

			var intK;
            objResult.option = [];
            var objOption = {};

            for (intK in objField_a.p_selection)
            {
                if(objResult._type === 'd_relatedlinks')
                {
                    objOption = JSONtoXMLFieldLinks(objField_a.p_selection[intK]);
                }
                else
                {
                    objOption = JSONtoXMLFieldOptions(objField_a.p_selection[intK]);
                }
                objResult.option.push(objOption);
            }
		}

		if (objResult._fieldsource == undefined)
		{
			objResult._fieldsource = '';
		}

		return objResult;
	}

	function JSONtoXMLMassageField(strValue_a)
	{
		var strResult = strValue_a;

		if (typeof(strValue_a) == "string")
		{
			strResult = str_replace(strResult, '>', '__GT__');
			strResult = str_replace(strResult, '<', '__LT__');
			strResult = str_replace(strResult, '"', '__QUOT__');
			strResult = str_replace(strResult, "'", '__APOS__');
			strResult = str_replace(strResult, '&', '__AMP__');
		}

		if (strResult == undefined) { strResult = ''; }

		return strResult;
	}

	function JSONtoXMLFieldOptions(objOption_a)
	{
		var objResult = {};

		if (objOption_a.p_code)
		{
			objResult._value = JSONtoXMLMassageField(objOption_a.p_code);
		}

		if (objOption_a.p_value)
		{
			objResult.__text = JSONtoXMLMassageField(objOption_a.p_value);
		}

		return objResult;
	}

	function JSONtoXMLFieldLinks(objLink_a)
	{
		var objResult = {};

		if (objLink_a.p_label)
		{
			objResult.__text = JSONtoXMLMassageField(objLink_a.p_label);
		}

		if (objLink_a.p_permissions)
		{
			objResult._permissions = JSONtoXMLMassageField(objLink_a.p_permissions);
		}

		if (objLink_a.p_command)
		{
			objResult._command = JSONtoXMLMassageField(objLink_a.p_command);
		}

		if (objLink_a.p_parameters)
		{
			objResult._parameters = JSONtoXMLMassageField(objLink_a.p_parameters);
		}

		return objResult;
	}

	function XMLtoJSON(strXML_a)
	{
		var arrResult = [];

        if(strXML_a.length > 0) {

            var objConverter = new X2JS();
            var objJSON = objConverter.xml_str2json(strXML_a);

            var objField = objJSON["form-template"]["fields"]["field"];
            var arrFields = [];
            if ($.isArray(objField))
            {
                arrFields = objField;
            }
            else
            {
                arrFields.push(objField);
            }

            if (m_objParameters.internalmode === "form")
            {
                // for forms we have to preserve the fields that were assigned
                processArray(arrFields, function (objField_a)
                {
                    var arrFieldsTemp = getFields(m_arrFormDataPrevious, objField_a._tempname);	// ?

                    arrResult.push(XMLtoJSONField(objField_a));

                    setFields(arrResult, objField_a._tempname, arrFieldsTemp);	// ?
                }
                );
            }
            else
            {
                processArray(arrFields, function (objField_a)
                {
                    arrResult.push(XMLtoJSONField(objField_a));
                }
                );
            }

            m_arrFormDataPrevious = arrResult;
            preserveSectionCodes();
        }

		return arrResult;
	}

	function XMLtoJSONField(objField_a)
	{
		var objResult = {};

		if (m_objParameters.internalmode === "form")
		{
			objResult =
			{
				"sectionclasses" : "",
				"sectioncode" : "",
				"sectioncodetemp" : "",
				"sectioncolour" : "",
				"sectionquantity" : "",
				"sectionref" : "",
				"sectiontitle" : "",
				"sectiontype" : "",
				"fields" : []
			};
		}
		else
		{
			objResult =
			{
				"p_classes" : "",
				"p_colour" : "",
				"p_datatype" : "",
				"p_info" : "",
				"p_label" : "",
				"p_length" : "",
				"p_lines" : "",
				"p_name" : "",
				"p_quantity" : "",
				"p_readonly" : "",
				"p_required" : "",
				"p_searchable" : "",
				"p_selection" : "",
				"p_sortable" : "",
				"p_source" : "",
				"p_style" : "",
				"p_value" : "",
				"p_valuedescription" : ""
				//"p_relatedlinks" : "",
			};
		}

		if (m_objParameters.internalmode === "form")
		{
			//if (objField_a._sectiontype)
			//{
				//objResult.sectiontype = objField_a._sectiontype;
			//}
			if (objField_a._class)
			{
				objResult.sectionclasses = XMLtoJSONMassageField(objField_a._class);
			}

			if (objField_a._label)
			{
				objResult.sectiontitle = XMLtoJSONMassageField(objField_a._label);
			}

			if (objField_a._name)
			{
				objResult.sectioncode = XMLtoJSONMassageField(objField_a._name);
			}

			if (objField_a._quantity)
			{
				objResult.sectionquantity = XMLtoJSONMassageField(objField_a._quantity);
			}

			if (objField_a._colour)
			{
				objResult.sectioncolour = XMLtoJSONMassageField(objField_a._colour);
			}

			if (objField_a._sectionref)
			{
				objResult.sectionref = XMLtoJSONMassageField(objField_a._sectionref);
			}

			if (objField_a._tempname)
			{
				objResult.sectioncodetemp = XMLtoJSONMassageField(objField_a._tempname);
			}

			if (objField_a._type)
			{
				if (objField_a._type === 'datasection') // data
				{
					objResult.sectiontype = 'DATA';
				}
				else if (objField_a._type === 'dataheadersection')
				{
					objResult.sectiontype = 'DATAHEADER';
				}
				else if (objField_a._type === 'formheadersection')
				{
					objResult.sectiontype = 'FORMHEADER';
				}
				else if (objField_a._type === 'internaluseformheadersection')
				{
					objResult.sectiontype = 'INTERNALUSEFORMHEADER';
				}
				else if (objField_a._type === 'internaluseonlysection')	// internal use only data
				{
					objResult.sectiontype = 'INTERNALUSEONLY';
				}
			}
		}
		else
		{
			if (objField_a._class)
			{
				objResult.p_classes = XMLtoJSONMassageField(objField_a._class);
			}

			if (objField_a._colour)
			{
				objResult.p_colour = XMLtoJSONMassageField(objField_a._colour);
			}

			if (objField_a._type)
			{
				objResult.p_datatype = XMLtoJSONMassageField(objField_a._type);
			}

			if (objField_a._infovalue)
			{
				objResult.p_info = XMLtoJSONMassageField(objField_a._infovalue);
			}

			if (objField_a._label)
			{
				objResult.p_label = XMLtoJSONMassageField(objField_a._label);
			}

			if (objField_a._length)
			{
				objResult.p_length = XMLtoJSONMassageField(objField_a._length);
			}

			if (objField_a._lines)
			{
				objResult.p_lines = XMLtoJSONMassageField(objField_a._lines);
			}

			if (objField_a._name)
			{
				objResult.p_name = XMLtoJSONMassageField(objField_a._name);
			}

			if (objField_a._quantity)
            {
                objResult.p_quantity = XMLtoJSONMassageField(objField_a._quantity);
            }

			if (objField_a._readonly)
			{
				objResult.p_readonly = XMLtoJSONFieldCheck(objField_a._readonly);
			}

			if (objField_a._required)
			{
				objResult.p_required = XMLtoJSONFieldCheck(objField_a._required);
			}

			if (objField_a._searchable)
			{
				objResult.p_searchable = XMLtoJSONFieldCheck(objField_a._searchable);
			}

			if (objField_a._sortable)
			{
				objResult.p_sortable = XMLtoJSONFieldCheck(objField_a._sortable);
			}

			if (objField_a._fieldsource)
			{
				objResult.p_source = XMLtoJSONMassageField(objField_a._fieldsource);
			}

			if (objField_a._fieldstyle)
			{
				objResult.p_style = XMLtoJSONMassageField(objField_a._fieldstyle);
			}

			if (objField_a._value)
			{
				objResult.p_value = XMLtoJSONMassageField(objField_a._value);
				objResult.p_valuedescription = "";	// for now we don't have much to do with this in builder, but make sure the property is here
			}

            if (objField_a._type === "d_relatedlinks" )
            {
                if (objField_a.option)
                {
                    objResult.p_selection = [];

                    var objLink = {};
                    if(objField_a.option.length > 1)
                    {
                        for (var y in objField_a.option)
                        {
                            objLink = XMLtoJSONFieldLinks(objField_a.option[y]);
							if (Object.keys(objLink).length)
							{
								objResult.p_selection.push(objLink);
							}
						}
                    }
                    else
                    {
                        objLink = XMLtoJSONFieldLinks(objField_a.option);
						if (Object.keys(objLink).length)
						{
							objResult.p_selection.push(objLink);
						}
                    }
                }
            }
			else
			{
			    if (objField_a.option)
                {
                    objResult.p_selection = [];

                    var objOption = {};
                    for (var x in objField_a.option)
                    {
                        objOption = XMLtoJSONFieldOptions(objField_a.option[x]);
						if (Object.keys(objOption).length)
						{
							objResult.p_selection.push(objOption);
						}
                    }
                }
    		}
		}

		return objResult;
	}

	function XMLtoJSONFieldOptions(objOption_a)
	{
		var objResult = {};

		if (objOption_a._value)
		{
			objResult.p_code = XMLtoJSONMassageField(objOption_a._value);
		}

		if (objOption_a.__text)
		{
			objResult.p_value = XMLtoJSONMassageField(objOption_a.__text);
			objResult.p_valuedescription = "";	// for now we don't have much to do with this in builder, but make sure the property is here
		}

		return objResult;
	}

	function XMLtoJSONFieldLinks(objLink_a)
	{
		var objResult = {};

		if (objLink_a.__text)
		{
			objResult.p_label = XMLtoJSONMassageField(objLink_a.__text);
		}

		if (objLink_a._permissions)
		{
			objResult.p_permissions = XMLtoJSONMassageField(objLink_a._permissions);
		}

		if (objLink_a._command)
		{
			objResult.p_command = XMLtoJSONMassageField(objLink_a._command);
		}

		if (objLink_a._parameters)
		{
			objResult.p_parameters = XMLtoJSONMassageField(objLink_a._parameters);
		}

		return objResult;
	}

	function XMLtoJSONFieldCheck(objCheck_a)
	{
		if(objCheck_a === undefined)
		{
			return XMLtoJSONMassageField('N');
		}

		return XMLtoJSONMassageField('Y');

	}

	function XMLtoJSONMassageField(strValue_a)
	{
		var strResult = strValue_a;

		if (typeof(strValue_a) == "string")
		{
			strResult = str_replace(strResult, '__AMP__', '&');
			strResult = str_replace(strResult, '__APOS__', "'");
			strResult = str_replace(strResult, '__QUOT__', '"');
			strResult = str_replace(strResult, '__LT__', '<');
			strResult = str_replace(strResult, '__GT__', '>');
		}

		if (strResult == undefined) { strResult = ''; }

		return strResult;
	}

	function updateFormJSON()
	{
		var objTempData = m_objFormBuilder.data('formBuilder').formData;

		if (objTempData.location === undefined) // JC, what is this .location check for?
		{
            m_arrFormData = XMLtoJSON(objTempData);
 			//m_strFormBuilderData = objTempData;
		}
		else
		{
			objTempData = os.element(m_strFormID, '.fb-temp').val();
            m_arrFormData = XMLtoJSON(objTempData);
		}
	}

	// ====================================================================================
	// HELPERS ============================================================================

	function afterAjaxError()
	{
		setDirty(true);
	}

	function initialiseForm()
	{
		resetForm();

		// resize the form
		var intViewPortWidth = os.getViewPort().width;
		var intViewPortHeight = os.getViewPort().height;
		os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);

		m_blnReadonly = false;

		if (m_objParameters.internalmode === "form")	// lets you edit the form (ie: which sections are on the form)
		{
			m_strFormTitle = 'Form Editor';

			m_objFBoptions =
			{
				disableFields : [
					'formheadersection',
					'xinternaluseformheadersection',
					'dataheadersection',
					'internaluseformheadersection',

                    'd_abnlookup',
				    'd_audio',
                    'd_barcode',
                    'd_button',
                    'd_chart',
					'd_codeeditor',
					'd_date',
					'd_description',
					'd_document',
					'd_gps',
					'd_heading',
					'd_html',
					'd_texthtml',
					'd_image',
					'd_list',
					'd_metadata',	// used for business logic, never rendered
					'd_multilinetext',
					'd_multilist',
					'd_number',
					'd_password',
					'd_relatedlinks',
					'd_text',
					'd_spacer',
					'd_time',
					'd_url',
					'd_version',
					'd_video',
					'd_yesno',

					'autocomplete',
					'button',
					'checkbox',
					'checkbox-group',
					'color',
					'date',
					'email',
					'file',
					'header',
					'hidden',
					'limitRole',
					'mitsukibo',
					'paragraph',
					'password',
					'radio-group',
					'rich-text',
					'roles',
					'select',
					'styles',
					'submit',
					'text',
					'textarea'
				],
				messages :
				{
					xsection : 'Data Section',
					label : 'Title',
					name : 'Code',
					xsectionref : 'Ref'
				},
				entitycode : m_strEntityCode,
				internalmode : m_objParameters.internalmode,
				sectiontype : m_strSectionType
			};
		}
		else if (m_objParameters.internalmode === "formsection")	// lets you edit the formsection (ie: which datatypes are in a section)
		{
			m_strFormTitle = 'Section Editor';

			if (m_strEntityCode === "systemform")
			{
				m_objFBoptions =
				{
					disableFields : [
						'autocomplete',
						'button',
						'checkbox',
						'checkbox-group',
						'color',
						'd_abnlookup',
						'd_audio',
						'xd_metadata',	// used for business logic, never rendered
						'd_version',
						'd_video',
						'dataheadersection',
						'datasection',
						'date',
						'email',
						'file',
						'formheadersection',
						'header',
						'hidden',
						'internaluseformheadersection',
						'internaluseonlysection',
						'limitRole',
						'mitsukibo',
						'paragraph',
						'password',
						'radio-group',
						'rich-text',
						'roles',
						'select',
						'styles',
						'submit',
						'text',
						'textarea'
					],
					xmessages :
					{
						section : 'Section'
					},
					entitycode : m_strEntityCode,
					internalmode : m_objParameters.internalmode,
					sectiontype : m_strSectionType
				};
			}
			else	// dataforms
			{
				if (m_strSectionType === 'formheadersection')
				{
					m_objFBoptions =
					{
						disableFields : [
							'autocomplete',
							'button',
							'checkbox',
							'checkbox-group',
							'color',
							'd_abnlookup',
							'd_audio',
                            'd_barcode',
                            'd_button',
                            'd_chart',
							'd_codeeditor',
							'd_date',
							'd_description',
							'd_document',
							'd_gps',
							'd_heading',
							'd_html',
							'd_texthtml',
							'd_image',
							'd_list',
							'd_metadata',	// used for business logic, never rendered
							'd_multilinetext',
							'd_multilist',
							'd_number',
							'd_password',
							'd_relatedlinks',
							'd_spacer',
							'd_text',
							'd_texthtml',
							'd_time',
							'd_url',
							'd_version',
							'd_video',
							'd_yesno',
							'dataheadersection',
							'datasection',
							'date',
							'email',
							'file',
							'formheadersection',
							'header',
							'hidden',
							'internaluseformheadersection',
							'internaluseonlysection',
							'limitRole',
							'mitsukibo',
							'paragraph',
							'password',
							'radio-group',
							'rich-text',
							'roles',
							'select',
							'styles',
							'submit',
							'text',
							'textarea'
						],
						xmessages :
						{
							section : 'Section'
						},
						entitycode : m_strEntityCode,
						internalmode : m_objParameters.internalmode,
						sectiontype : m_strSectionType
					};
				}
				else
				{
					m_objFBoptions =
					{
						disableFields : [
							'autocomplete',
							'button',
							'checkbox',
							'checkbox-group',
							'color',
							'd_abnlookup',
							'd_audio',
							'd_codeeditor',
							'd_document',
							'd_html',
							'd_texthtml',
							'd_metadata',	// used for business logic, never rendered
							'd_multilist',
							'd_password',
							'd_relatedlinks',
							'd_version',
							'd_video',
							'dataheadersection',
							'datasection',
							'date',
							'email',
							'file',
							'formheadersection',
							'header',
							'hidden',
							'internaluseformheadersection',
							'internaluseonlysection',
							'limitRole',
							'mitsukibo',
							'paragraph',
							'password',
							'radio-group',
							'rich-text',
							'roles',
							'select',
							'styles',
							'submit',
							'text',
							'textarea'
						],
						xmessages :
						{
							section : 'Section'
						},
						entitycode : m_strEntityCode,
						internalmode : m_objParameters.internalmode,
						sectiontype : m_strSectionType
					};
				}
			}
		}

		os.element(m_strFormID, '.gb-overflow').css('overflow-y', 'hidden');
		os.element(m_strFormID, '.gb-overflow').css('overflow-x', 'hidden');

	}

	function fetchEntity(objRequest_a, cbResponse_a)
	{
		function entityFetched(objData_a)
		{
			var objResponse = [];

			var intI = 0;

			processArray(objData_a, function (objElement_a)
			{
				objResponse[intI] =
				{
					"value" : objElement_a.code
				};
				intI++;
			}
			);
			cbResponse_a(objResponse);
		}

		function ajaxError()
		{
			cbResponse_a([]);
		}

		var objJSON = os.ajaxRequestCreate('entity_entitysearchbycode',
				[
				{
					name : "entitycode",
					value : m_strEntityCode
				},
				{
					name : 'filter',
					value : [
						{
							field : "code",
							value : objRequest_a.term
						}
					]
				},
				{
					name : "order",
					value : [
						{
							field : 'code',
							ascending : true
						}
					]
				}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, entityFetched, os.ajaxError, doNothing, true);
	}

	function selectEntity(objEvent_a, objSelection_a)
	{
		var strFormEntityCode = objSelection_a.item.value;
		JSONSetFieldValue('ENTITY', strFormEntityCode);
		os.element(m_strFormID, '.ge-dataformgroup-field').focus();
	}

    /**
	 * init recipient field predictive text
	 * @returns {void}
	 */
	function initFieldAutocomplete()
	{
		os.element(m_strFormID, '.ge-dataformgroup-field').autocomplete(
			{
				autoFocus : true,
				xdelay : 500,
				source : fetchEntity,
				select : selectEntity
			}
		);

	}

	function resetForm()
	{
		if (m_objParameters.internalmode === "form")
		{
			m_arrFormData = getDefaultForm();
			preserveSectionCodes();
			m_arrFormDataPrevious = m_arrFormData;
			preserveSectionCodes();
		}
	}

	function saveData()
	{
		// JC performance issue
		// note: was put as a performance enhancement instead of the updateFormJSONs elsewhere, but here isn't the correct place, it loses the default that you type in
		// updateFormJSON();	// do this at the start of saving data so that we have a JSON result

		var strErrors = validate();

		if (strErrors.length > 0)
		{
			// have an error, so display it
			os.dialogAlertScroll(strErrors, function ()  {}

			);
		}
		else
		{
			setDirty(false);
			saveData2();
		}
	}

	function saveData2()
	{
		if (m_objParameters.internalmode === "form")
		{
			if (m_objParameters.mode === 'add')
			{
				addForm();
			}
			else if (m_objParameters.mode === 'edit')
			{
				updateForm();
			}
			else if (m_objParameters.mode === 'copy')
			{
				addForm();
			}
		}
		else if (m_objParameters.internalmode === "formsection")
		{
			m_blnClosed = true;
			os.closeForm(m_strFormID);
		}
	}

	function setDirty(blnDirty_a)
	{
		m_blnFormDirty = blnDirty_a;

		if (m_blnReadonly)
		{
			m_objDock.disableButtons('SaveButton', 'gs-darkblue-background-colour gs-glow-focusborder gs-red-background-colour gs-glow-dirty');
		}
		else
		{
			if (m_blnFormDirty)
			{
				m_objDock.disableButtons('SaveButton', 'gs-darkblue-background-colour');
				m_objDock.enableButtons('SaveButton', 'gs-red-background-colour gs-glow-focusborder gs-glow-dirty');

				m_objDock.disableButtons('CopyButton', 'gs-darkblue-background-colour gs-glow-focusborder gs-red-background-colour gs-glow-dirty');
			}
			else
			{
				m_objDock.disableButtons('SaveButton', 'gs-red-background-colour gs-glow-dirty');
				m_objDock.enableButtons('SaveButton', 'gs-darkblue-background-colour gs-glow-focusborder');

				m_objDock.disableButtons('CopyButton', 'gs-red-background-colour gs-glow-dirty');
				m_objDock.enableButtons('CopyButton', 'gs-darkblue-background-colour gs-glow-focusborder');
			}
		}
	}

	function showSection(objThis_a)
	{
		var strTitle = os.element(m_strFormID, objThis_a).attr('label');
		var strSectionCode = os.element(m_strFormID, objThis_a).attr('sectionguid');
		var strSectionType = os.element(m_strFormID, objThis_a).attr('type');

		var objSectionFields = [];
		var intSection = -1;
		var intSectionFound = -1;

		processArray(m_arrFormData, function (objSection_a)
		{
			intSection++;
			if (objSection_a.sectioncode == strSectionCode)
			{
				objSectionFields = objSection_a.fields;
				intSectionFound = intSection;
			}
		}
		);

		var strSectionFields = JSON.stringify(objSectionFields);

		m_blnClosed = false;
		os.showFormPopup('widgetformbuilder.wgtFormBuilder', 'internalmode=formsection&title=' + encodeURIComponent(strTitle) + '&entity=' + encodeURIComponent(m_strEntityCode) + '&sectiontype=' + encodeURIComponent(strSectionType) + '&sectioncode=' + encodeURIComponent(strSectionCode) + '&currentsection=' + encodeURIComponent(strSectionFields), function (objResult_a)
		{
			if (objResult_a.isClosed)
			{
				m_arrFormData[intSectionFound].fields = objResult_a.Data;
				setDirty(true);
			}
		}, false, true);
	}

	function getSectionFieldValue(arrParentFields_a, strSectionType_a, strFieldName_a)
    {
		var blnFound = false;
        var strResult = '';

        processArray(arrParentFields_a, function (objSection_a)
        {
            if (objSection_a.sectiontype == strSectionType_a)
            {
                var arrFields = objSection_a.fields;
                processArray(arrFields, function(objField_a)
                {
                    if (objField_a.p_name == strFieldName_a)
                    {
                        strResult = objField_a.p_value;
						blnFound = true;
						return true;
                    }
                });

				if (blnFound)
				{
					return true;
				}
            }
        }
        );

        return strResult;
    }

	function preserveSectionCodes()
	{
		if (m_objParameters.internalmode === "form")
		{
			processArray(m_arrFormData, function (objSection_a)
			{
				objSection_a.sectioncodetemp = objSection_a.sectioncode;	// ok
			}
			);
		}
	}

	function validate()
	{
		var strError = '';
        var intCountFields = 0;
        var strSectionName = "";

		if (m_objParameters.internalmode === "form")
        {

            processArray(m_arrFormData, function(objSection_a) {

                intCountFields = objSection_a.fields.length;
                strSectionName = objSection_a.sectiontitle;

                if(intCountFields === 0) {
                    strError += "<li>There are no fields in the section " + strSectionName+"</li>";
                }
            });


            var strFormEntityCode = getSectionFieldValue(m_arrFormData, 'FORMHEADER', 'ENTITY');
            var strFormCode = getSectionFieldValue(m_arrFormData, 'FORMHEADER', 'CODE');
			var strFormDescription = getSectionFieldValue(m_arrFormData, 'FORMHEADER', 'DESCRIPTION');

            if(strFormEntityCode.length === 0)
            {
                strError += '<li>Form Header Entity is required.</li>';
            }

            if(strFormCode.length === 0)
            {
                strError += '<li>Form Header Code is required.</li>';
            }

            if(strFormDescription.length === 0)
            {
                strError += '<li>Form Header Description is required.</li>';
            }

            var intFormFieldCount = getFormFieldCount();
			if (intFormFieldCount > MAX_DATAFORM_FIELD_LIMIT)
			{
                strError += '<li>You have exceeded the ' + MAX_DATAFORM_FIELD_LIMIT + ' field form limit by ' + (intFormFieldCount - MAX_DATAFORM_FIELD_LIMIT) + ' fields.</li>';
            }
        }

		if (strError.length > 0)
		{
			strError = '<ul>' + strError + '</ul>';
		}

		return strError;
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
		var strXML = JSONtoXML(m_arrFormData);
		os.element(m_strFormID, '.fb-temp').html(strXML);

		m_objFormBuilder = os.element(m_strFormID, '.fb-temp').formBuilder(os, m_strFormID, m_objFBoptions);

		//populateSectionNotes();

		os.unbindEvents(m_strFormID, 'ge-cancel-button,ge-save-button,ge-section-button');

		os.element(m_strFormID, '.view-data').hide();
		os.element(m_strFormID, '.form-builder-save').hide();
		os.element(m_strFormID, '.clear-all').hide();

		os.element(m_strFormID, '.cb-wrap').css( {'overflow-x':'hidden', 'overflow-y' : 'scroll'  });
		os.element(m_strFormID, '.stage-wrap').css( { 'overflow-x' : 'hidden', 'overflow-y' : 'scroll' } );

		// bind fields
		bindFormBuilder();
		bindSectionEvents();

		// setup taborder
		setTabOrder();
		os.element(m_strFormID, '.fb-temp').focus();

		if(m_objParameters.internalmode === "form")
		{
		    setDirty(false);
		}

		if (m_objParameters.title !== undefined)
        {
            m_strFormTitle = m_strFormTitle + ' - ' + m_objParameters.title;
        }

        if( m_objParameters.internalmode === 'form')
        {
            var strFormCode_a = getSectionFieldValue(m_arrFormData, 'FORMHEADER', 'CODE');

            if (m_objParameters.mode === "add")
            {
                m_strFormTitle = m_objParameters.title + ' - New';
            }
            else if (m_objParameters.mode === "edit")
            {
                m_strFormTitle = m_objParameters.title + ' - Edit ' + strFormCode_a;
            }
            else if (m_objParameters.mode === "view")
            {
                m_strFormTitle = m_objParameters.title + ' - View ' + strFormCode_a;
            }
        }

        os.element(m_strFormID, '.ge-form-title').text(m_strFormTitle);

        //os.unbindEvents(m_strFormID, 'ge-dataformgroup-field');	// unbinding this removes the setting of the form dirty
        initFieldAutocomplete();

		// note: this resize code is needed here because the 3rd party control is resizing without any notification to us
		os.element(m_strFormID, '.stage-wrap').height( (m_intFormHeight - 150) + 'px');
        os.element(m_strFormID, '.cb-wrap').height( (m_intFormHeight - 150) + 'px');
	}

	//function populateSectionNotes()
	//{
		//if (m_objParameters.internalmode === "form")
		//{
			//processArray(m_arrFormData, function(objSection_a)
			//{
				//os.element(m_strFormID, '.' + objSection_a.sectioncode).children('.fe-sectionnotes-field').html('test');
			//});
		//}
	//}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function asyncDataIsFetched()
	{
		m_intFetched++;
		if (m_intToFetch == m_intFetched)
		{
			populateForm();
		}
	}

	function asyncError()
	{
		if (m_intErrors === 0)
		{
			os.ajaxError();
		}
		m_intErrors++;
	}

	function formAdded(objResponse_a)
	{
		os.broadcast(m_strFormID, 'entity', m_strFormEntityCode + 'list' + 'added');
		m_objParameters.mode = 'edit';
		m_strID = objResponse_a[0].id;
		if( m_objParameters.internalmode === 'form')
		{
		    var strFormCode_a = getSectionFieldValue(m_arrFormData, 'FORMHEADER', 'CODE');
			m_strFormTitle = m_objParameters.title + ' - Edit ' + strFormCode_a;
			os.element(m_strFormID, '.ge-form-title').text(m_strFormTitle);
		}
	}

	function formFetched(objResponse_a)
	{
		m_arrFormData = objResponse_a[0].jsondata;
		m_arrFormDataPrevious = m_arrFormData;
		preserveSectionCodes();

		asyncDataIsFetched();
	}

	function formUpdated(objResponse_a)
	{
		os.broadcast(m_strFormID, 'entity', m_strFormEntityCode + 'list' + 'edited');
		if( m_objParameters.internalmode === 'form')
		{
		    var strFormCode_a = getSectionFieldValue(m_arrFormData, 'FORMHEADER', 'CODE');
			m_strFormTitle = m_objParameters.title + ' - Edit ' + strFormCode_a;
			os.element(m_strFormID, '.ge-form-title').text(m_strFormTitle);
		}

		fetchData(true);
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================
	//

	function addForm()
	{
		var strFormEntityCode = getSectionFieldValue(m_arrFormData, 'FORMHEADER', 'ENTITY');

		// PARAMETERS: entitycode, formentitycode, jsondata
		var objJSON = os.ajaxRequestCreate('entity_formadd',
				[
					{
						"name" : "entitycode",
						"value" : m_strEntityCode
					},
					{
						"name" : "formentitycode",
						"value" : strFormEntityCode
					},
					{
						"name" : "jsondata",
						"value" : m_arrFormData
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, formAdded, os.ajaxError, afterAjaxError);
	}

	function fetchData(blnFetchData_a)
	{
		if (blnFetchData_a)
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;

			m_intToFetch = 1;
			fetchForm();
		}
		else
		{
			m_intToFetch = 0;
			m_intFetched = 0;
			m_intErrors = 0;

			populateForm();
		}
	}

	function fetchForm()
	{
		// PARAMETERS: entitycode, entitydataid (o), formcode (o)
		var objJSON = os.ajaxRequestCreate('entity_formfetch',
				[
					{
                        "name" : "entitycode",
                        "value" : m_strEntityCode
                    },
					{
						"name" : "entitydataid",
						"value" : m_strID
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, formFetched, os.ajaxError, afterAjaxError);
	}

	function updateForm()
	{
		var strFormEntityCode = getSectionFieldValue(m_arrFormData, 'FORMHEADER', 'ENTITY');

		// PARAMETERS: entitycode, entitydataid, formentitycode, jsondata
		var objJSON = os.ajaxRequestCreate('entity_formupdate',
				[
					{
						"name" : "entitycode",
						"value" : m_strEntityCode
					},
					{
						"name" : "entitydataid",
						"value" : m_strID
					},
					{
						"name" : "formentitycode",
						"value" : strFormEntityCode
					},
					{
						"name" : "jsondata",
						"value" : m_arrFormData
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, formUpdated, os.ajaxError, afterAjaxError);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindFormBuilder()
	{
		// unbindings
		//os.unbindEvents(m_strFormID, m_strFormFields);

		// dirty bindings
		os.onDirty(m_strFormID, m_strFormFields, function ()
		{
			setDirty(true);
		}
		);
	}

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form,gb-formtitle-inner-panel');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.fb-temp', 'FormBuilder', 'onChange');
	}

	function bindSectionEvents()
	{
		os.unbindEvents(m_strFormID, 'fb-section-button,section-field,form-control');
		os.bindEvent(m_objThis, m_strFormID, '.fb-section-button', 'SectionButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.section-field', 'SectionField', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.form-control', 'FormControl', 'onChange');
		os.bindEvent(m_objThis, m_strFormID, '.form-control', 'FormControl', 'onKeyDown');
        os.bindEvent(m_objThis, m_strFormID, '.ge-yesno', 'YesNoControl', 'onChange');
		//os.bindEvent(m_objThis, m_strFormID, '.form-control', 'FormControl', 'onKeyPress');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_isDirty = function ()
	{
		return m_blnFormDirty;
	};

	this.Form_onBroadcast = function (strQueue_a, strMessage_a, objMessageData_a)
	{
		if ((strQueue_a === 'hash') && (strMessage_a === 'change'))
		{
			os.closeForm(m_strFormID); // it's out of scope (likely as a popup, so close it... behaviour can be improved in future)
		}
	};

	// on_click gives form the focus, setup all the tabs
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
		m_blnReadonly = false;

		initialiseForm();
		populateDock();

		if (m_objParameters.internalmode === "form" && (m_objParameters.mode === "edit" || m_objParameters.mode === "view"))
		{
			fetchData(true);
		}
		else
		{
			fetchData(false);
		}
		bindGlobals();

	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.FormTitle_onClick = function ()
	{
		os.formToFront(m_strFormID);
	};

	// this is public for the OS to grab the result of a popup modal form and return it to the parent
	this.FormResult = function ()
	{
		var objResult = {
			"isClosed" : m_blnClosed,
			"Data" : m_arrFormData
		};

		return objResult;
	};

    this.Form_onResize = function(intWidth_a, intHeight_a)
	{
		m_intFormHeight = intHeight_a;
		os.element(m_strFormID, '.stage-wrap').height( (m_intFormHeight - 150) + 'px');
        os.element(m_strFormID, '.cb-wrap').height( (m_intFormHeight - 150) + 'px');
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.ClearButton_onClick = function ()
	{
		os.element(m_strFormID, '.clear-all').trigger("click");
	};

	this.CopyButton_onClick = function ()
	{
		setDirty(true);
		m_objParameters.mode = 'copy';

		var strFormCode_a = getSectionFieldValue(m_arrFormData, 'FORMHEADER', 'CODE');
		m_strFormTitle = m_objParameters.title + ' - Copy '  +  strFormCode_a;
		os.element(m_strFormID, '.ge-form-title').text(m_strFormTitle);
	};

	// called whenever the form fields change
	this.FormBuilder_onChange = function ()
	{
		setTabOrder();
		bindSectionEvents();

		//if (m_objParameters.internalmode === "form")
		//{
			updateFormJSON();
		//}
	};

	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

	this.RenderButton_onClick = function ()
	{
		//var strID = os.element(m_strFormID, param).attr('fieldid');

		//m_arrFormData.sections = xml2json(m_strFormBuilderData);
		//m_arrFormData.formsections = m_arrFormSectionJSONFB;

		var strJSONData = JSON.stringify(m_arrFormData);

		os.showForm('entity.frmForm', 'mode=add&entity=' + encodeURI(m_strEntityCode) + '&formentity=' + encodeURI(m_strFormEntityCode) + '&formentityid=' + encodeURI(m_strID) + '&title=' + encodeURI(m_strFormEntityDescription) +  '&jsondata=' + strJSONData, function (objResult_a)
		{}

		);
	};

	this.SaveButton_onClick = function ()
	{
		if (m_objThis.Form_isDirty())
		{
			try
			{
				saveData();
			}
			catch (err)
			{
				setDirty(true);
			}
		}
		//else
		//{
		//saveData(); // temporary here until dirty form is working
		//os.closeForm(m_strFormID);
		//}
	};

	this.FormControl_onChange = function (objThis_a)
	{
		var strValue = os.element(m_strFormID, objThis_a).val();
		var strName  = os.element(m_strFormID, objThis_a).parent().siblings('.frm-holder').find('.form-elements .name-wrap .form-control').val();
        var strType  = os.element(m_strFormID, objThis_a).prop('type');

		os.element(m_strFormID, objThis_a).attr('value', strValue);
		os.element(m_strFormID, objThis_a).parent().siblings('.frm-holder').find('.form-elements .value-wrap .form-control').attr('value', strValue);

		// likely a reasonable fix is to update the form JSON only for the field that has changed, enhance the updateFormJSON function to be faster or more efficient
		// with regard to the parent, maybe only the section that changed
		updateFormJSON();										// JC performance issue

		JSONSetFieldValue(strName, strValue);
//logDebug(JSON.stringify(m_arrFormData));
		var strXML = JSONtoXML(m_arrFormData);					// JC performance issue
		os.element(m_strFormID, '.fb-temp').html(strXML);		// JC performance issue
		setDirty(true);
	};

    /**
     * this is almost the same in FormControl.
     * css class .form-control in checkbox changes the appearance of the checkbox. (due to bootstrap styling)
     * better to use another class. I use .ge-yesno
     */
    this.YesNoControl_onChange = function (objThis_a)
	{
		var strValue = os.element(m_strFormID, objThis_a).val();
		var strName  = os.element(m_strFormID, objThis_a).parent().siblings('.frm-holder').find('.form-elements .name-wrap .form-control').val();
        var strType  = os.element(m_strFormID, objThis_a).prop('type');

        // this is for checkbox value update
        if(strType === 'checkbox') {

           if(objThis_a.checked) {
              strValue = 'Y';
           }
           else {
              strValue = 'N';
           }
        }

		os.element(m_strFormID, objThis_a).attr('value', strValue);
		os.element(m_strFormID, objThis_a).parent().siblings('.frm-holder').find('.form-elements .value-wrap .form-control').attr('value', strValue);

		// likely a reasonable fix is to update the form JSON only for the field that has changed, enhance the updateFormJSON function to be faster or more efficient
		// with regard to the parent, maybe only the section that changed
		updateFormJSON();										// JC performance issue
		JSONSetFieldValue(strName, strValue);
		var strXML = JSONtoXML(m_arrFormData);					// JC performance issue
		os.element(m_strFormID, '.fb-temp').html(strXML);		// JC performance issue
		setDirty(true);

	};

	this.FormControl_onKeyDown = function (objThis_a)
	{
		//var strValue = os.element(m_strFormID, objThis_a).val();
		//os.element(m_strFormID, objThis_a).attr('value', strValue)
		setDirty(true);
	};

	//this.FormControl_onKeyPress	= function (objThis_a)
	//{
		//var strValue = os.element(m_strFormID, objThis_a).val();
		//os.element(m_strFormID, objThis_a).attr('value', strValue)
	//};

	this.SectionButton_onClick = function (objThis_a)
	{
		showSection(objThis_a);
	};

	this.SectionField_onDblClick = function (objThis_a)
	{
		showSection(objThis_a);
	};

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,CloseButton,' + m_strFormFields + ',ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		os.element(m_strFormID, '.CloseButton').focus();
	};

	this.TabStart_onFocus = function ()
	{
		os.element(m_strFormID, '.ge-code-field').focus();
	};
}