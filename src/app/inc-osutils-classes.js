/*jsl:option explicit*/
/*jsl:import inc-osutils.js*/

// ====================================================================================
// AWAFOS Utils v20241106 =============================================================
// copyright(C) 2012-2024 Mitsukibo, Julian Cassin & Francis Weston. All rights reserved.

function abnFinder(objOS_a, strFormID_a, strABNField_a, strBusinessNameField_a, strFetchBy, blnSearchButton, strSearchButton)
{
	var os = objOS_a;
	var m_objThis = this;

	var m_strOutputFormat = ' %ABN% | %BUSINESSNAME% | %STATE% | %POSTCODE% ';
	var m_strABNID = '!';

	var m_strSearched = '';
	var m_strCurrentABN = '!';
	var m_strCurrentBusinessName = '!';
	var m_strCurrentState = '!';
	var m_strCurrentPostcode = '!';

	// ====================================================================================
	// POPULATION =========================================================================

	this.fetchByBusinessName = function(objRequest_a, cbResponse_a)
	{
		var strTerm = objRequest_a.term;
		
		if (blnSearchButton)
		{
			var blnReady = os.element(strFormID_a, '.' + strBusinessNameField_a).hasClass('gs-abnsearch');
			if (!blnReady)
			{
				return;
			}
		}
		else
		{
			if (strTerm.length < ABNLOOKUP_CHARACTER_LENGTH)
			{
				return;
			}
		}

		function abnByBusinessNameFetched(objData_a)
		{
			var objResponse = [];
			var intI = 0;
			processArray(objData_a, function (objElement_a)
			{
				var strOutput = m_strOutputFormat;
				strOutput = strOutput.replace('%ABN%', objElement_a.id);
				strOutput = strOutput.replace('%BUSINESSNAME%', objElement_a.businessname);
				strOutput = strOutput.replace('%STATE%', objElement_a.state);
				strOutput = strOutput.replace('%POSTCODE%', objElement_a.postcode);

				objResponse[intI] =
				{
					"value" : strOutput,
					"id" : objElement_a.id,
					"businessname" : objElement_a.businessname,
					"state" : objElement_a.state,
					"postcode" : objElement_a.postcode
				};
				intI++;
			}
			);
			cbResponse_a(objResponse);

			if (blnSearchButton)
			{
				os.element(strFormID_a, '.' + strBusinessNameField_a).removeClass('gs-abnsearch');
			}
		}

		function ajaxError()
		{
			cbResponse_a([]);
		}

		var objJSON = os.ajaxRequestCreate('core_businessnamelookup', [
					{
						name : 'filter',
						value : [
							{
								field : 'abn',
								value : strTerm
							}
						]
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, abnByBusinessNameFetched, ajaxError);
	};

	this.fetchByABN = function(objRequest_a, cbResponse_a)
	{
		var strTerm = objRequest_a.term;
		
		function abnByABNFetched(objData_a)
		{
			var objResponse = [];

			var intI = 0;
			processArray(objData_a, function (objElement_a)
			{

				var strOutput = m_strOutputFormat;
				strOutput = strOutput.replace('%ABN%', objElement_a.id);
				strOutput = strOutput.replace('%BUSINESSNAME%', objElement_a.businessname);
				strOutput = strOutput.replace('%STATE%', objElement_a.state);
				strOutput = strOutput.replace('%POSTCODE%', objElement_a.postcode);

				objResponse[intI] =
				{
					"value" : strOutput,
					"id" : objElement_a.id,
					"businessname" : objElement_a.businessname,
					"state" : objElement_a.state,
					"postcode" : objElement_a.postcode
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

		var objJSON = os.ajaxRequestCreate('core_abnlookup', [
					{
						name : 'filter',
						value : [
							{
								field : 'abn',
								value : strTerm
							}
						]
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, abnByABNFetched, ajaxError);
	};

	this.selectByBusinessName = function (objEvent_a, objSelection_a)
	{
		os.after(200, function ()
		{
			os.element(strFormID_a, '.' + strABNField_a).val(objSelection_a.item.id);
			os.element(strFormID_a, '.' + strBusinessNameField_a).val(objSelection_a.item.businessname);
		}
		);
	};

	this.selectByABN = function (objEvent_a, objSelection_a)
	{
		os.after(200, function ()
		{
			os.element(strFormID_a, '.' + strABNField_a).val(objSelection_a.item.id);
			os.element(strFormID_a, '.' + strBusinessNameField_a).val(objSelection_a.item.businessname);
		}
		);
	};

	// ====================================================================================
	// INITIALISATION =====================================================================

	this.bind = function ()
	{
		if (!os.toBoolean(ENABLE_DYNAMICABNSEARCH))
		{
			return;
		}

		if (blnSearchButton)
		{
			os.element(strFormID_a, '.' + strSearchButton).bind('click', function ()
			{
				var strClassName_a = '';
				if (strFetchBy === 'BusinessName')
				{
					strClassName_a = strBusinessNameField_a;
				}
				else
				{
					strClassName_a = strABNField_a;
				}
				var strValue = os.element(strFormID_a, '.' + strClassName_a).val();
				os.element(strFormID_a, '.' + strClassName_a).addClass('gs-abnsearch');
				os.element(strFormID_a, '.' + strClassName_a).autocomplete('search', strValue);

			}
			);
		}

		if (strFetchBy === 'BusinessName')
		{

			os.element(strFormID_a, '.' + strBusinessNameField_a).autocomplete(
			{
				delay : ABNLOOKUP_DELAY_TIME,
				source : m_objThis.fetchByBusinessName,
				select : m_objThis.selectByBusinessName
			}
			);
		}
		else
		{
			os.element(strFormID_a, '.' + strABNField_a).autocomplete(
			{
				delay : ABNLOOKUP_DELAY_TIME,
				source : m_objThis.fetchByABN,
				select : m_objThis.selectByABN
			}
			);
		}
	};

	this.setOutputFormat = function (strOutputFormat_a)
	{
		m_strOutputFormat = strOutputFormat_a;
	};

}

function predictiveTextFinder(objOS_a, strFormID_a, objRenderer_a, strSourceID_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;

	var m_cbBinder;
	var m_blnOpened = false;
	var m_arrFields = [];
	var m_arrOutputFormat = [];
	var m_objRenderer = objRenderer_a;
	var m_strSourceID = strSourceID_a;

	var m_strOutputFormat = '';
	var m_strSearched = '';
	var m_strCurrentState = '!';
	var m_strCurrentPostcode = '!';
	var m_strValueField = '';
	var m_blnCleared = true;

	// ====================================================================================
	// POPULATION =========================================================================

	this.fetchByPredictiveText = function(objRequest_a, cbResponse_a)
	{
		var strTerm = objRequest_a.term;

		var objDataSource = m_objRenderer.getDataSourceByDataSourceID(m_strSourceID);
		var arrDataSource = objDataSource.source.split('|');
		var strSource = arrDataSource[0];

        if (strTerm.length === 0)
        {
			// alert('onClear 1');
			m_objRenderer.raiseRendererEvent('Entity', m_strFormID, objDataSource.locator, 'onClear');
			m_blnCleared = true;
            return;
        }

		m_blnCleared = false;

        if (strTerm.length < MINPREDICTIVECHARS)
        {
			m_objRenderer.raiseRendererEvent('Entity', m_strFormID, objDataSource.locator, 'onChangeStart');
            return;
        }
		
		function predictiveTextFetched(objData_a)
		{
			var objResponse = [];
			var intI = 0;
            
			processArray(objData_a, function (objElement_a)
			{
				var strOutput = m_strOutputFormat;
				processArray(m_arrFields, function (strField_a)
				{
					strOutput = strOutput.replace('%' + strField_a.toUpperCase() + '%', objElement_a[strField_a]);
				});


				objResponse[intI] =
				{
					"value" : strOutput,
					"id" : objElement_a.id
					//"code" : objElement_a.code,
					//"description" : objElement_a.description
				};

				processArray(m_arrFields, function (strField_a)
				{
					objResponse[intI][strField_a] = objElement_a[strField_a];
				});

				//alert(JSON.stringify(objResponse[intI]));

				intI++;
			});
			
			//alert('onChangePending');
			m_objRenderer.raiseRendererEvent('Entity', m_strFormID, objDataSource.locator, 'onChangePending');
			cbResponse_a(objResponse);
		}

		function ajaxError()
		{
			cbResponse_a([]);
		}

		var strFixedFilter = '';

//console.log(JSON.stringify(arrDataSource));
//console.log(arrDataSource.length);
		if (arrDataSource.length === 3)
		{
			strFixedFilter = arrDataSource[2];
			strFixedFilter = str_replace(strFixedFilter, "'", '"');
		}
//console.log(strFixedFilter);

		var objJSON = os.ajaxRequestCreate('entity_formdatalist', [
				{
					"name" : 'entitycode',
					"value" : "systemform"
				},
                {
                    "name" : 'formentityid',
                    "value" : ""
                },
				{
					"name" : 'formentitycode',
					"value" : strSource
				},
				{
					"name" : 'entityid', // REMOVE THIS PARAMETER?
					"value" : ""
				},
				{
					"name" : 'order',
					"value" : ""
				},
				{
					"name" : 'filter',
					"value" : strTerm
				},
				{
					"name" : 'fixedfilter',
					"value" : strFixedFilter
				},
                {
                    "name" : "relationship",
                    "value" : ""
                },
                {
                    "name" : "relativeid",
                    "value" : ""
                },
                {
                    "name" : "relative",
                    "value" : ""
                },
				{
					"name" : 'offset',
					"value" : 0
				},
                {
                    "name" : 'limit',
                    "value" : 0
                }
			]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, predictiveTextFetched, ajaxError);
	};

	this.selectByPredictiveClick = function (objEvent_a, objSelection_a)
	{
		var KEY_ENTER = 13;
		
		var objDataSource = m_objRenderer.getDataSourceByDataSourceID(m_strSourceID);
		var objEvent = $.Event('keypress');
		
		objEvent.which = KEY_ENTER;

		os.element(m_strFormID, objDataSource.locator).trigger(objEvent, objSelection_a);
	};
	
	this.selectByPredictiveText = function (objEvent_a, objSelection_a)
	{
		var objDataSource = m_objRenderer.getDataSourceByDataSourceID(m_strSourceID);

		var objItem = objSelection_a.item;
		if ((objItem !== null) && (objItem !== undefined))
		{
			if (objItem.id.length === 0)
			{
				// alert('onClear 2');
				m_objRenderer.raiseRendererEvent('Entity', m_strFormID, objDataSource.locator, 'onClear');
				m_blnCleared = true;
			}
			else
			{
				m_blnCleared = false;
				
				// we have a binder callback to intercept the bound onChange Event
				if ($.isFunction(m_cbBinder))
				{
					//os.after(200, function ()
					//{
						//alert('onChange:' + JSON.stringify(objItem));
						m_cbBinder('Entity', m_strFormID, objDataSource.locator, 'onChange', objItem.id, objItem.value);
					//});
				}
				else
				{
					os.after(200, function ()
					{
						//alert('onChange:' + JSON.stringify(objItem));
						os.element(m_strFormID, objDataSource.locator + 'epv').val(objItem.id);
						os.element(m_strFormID, objDataSource.locator + 'epd').val(objItem.value);		// note: this was incorrectly hardcoded to description
						m_objRenderer.raiseRendererEvent('Entity', m_strFormID, objDataSource.locator, 'onChange');
					});
				}
			}
		}
		else
		{
			if (!m_blnCleared)
			{
				// alert('onClear 3:' + objDataSource.locator);
				m_objRenderer.raiseRendererEvent('Entity', m_strFormID, objDataSource.locator, 'onClear');
			}
		}
	};

	// ====================================================================================
	// INITIALISATION =====================================================================

	this.bind = function (cbBinder_a, objColumn_a)
	{
		var objDataSource = m_objRenderer.getDataSourceByDataSourceID(m_strSourceID);
		var arrDataSource = objDataSource.source.split('|');
		var strSource = arrDataSource[0];
		var strFields = "['code','description']";
		
		m_cbBinder = cbBinder_a;
		
	    if (arrDataSource.length === 1)
	    {
			strFields = "['code','description']";
	    }
		else
		{
			strFields = arrDataSource[1];
		}

        strFields = str_replace(strFields, "'", '"');       // form builder didn't like " so we used '
        m_arrFields = JSON.parse(strFields);

		m_arrOutputFormat = [];
		processArray(m_arrFields, function (strField_a)
		{
			m_arrOutputFormat.push('%' + strField_a.toUpperCase() + '%');
		});

		m_strOutputFormat = m_arrOutputFormat.join(' | ');
		m_strValueField = m_arrFields[0]; //get first and set it as default value

        os.element(m_strFormID, objDataSource.locator).autocomplete(
        {
			autoFocus : true,
			delay: 200,
			minLength: 0,
            source: m_objThis.fetchByPredictiveText,
			change: m_objThis.selectByPredictiveText,
            select: m_objThis.selectByPredictiveText,
			open: function()
			{
				m_blnOpened = true;
			},
			close: function()
			{
				m_blnOpened = false;
			}
        });

		os.element(m_strFormID, objDataSource.locator).on('keydown', function(e)
		{
			var KEY_DOWN = 40;
			var KEY_UP = 38;
			
			if (m_blnOpened)
			{
				if ((e.originalEvent.keyCode === KEY_DOWN) || (e.originalEvent.keyCode === KEY_UP))
				{
					e.stopPropagation();
					e.preventDefault();
					try 
					{
						e.originalEvent.keyCode = 0;
					} 
					catch (error) 
					{
					}
				}
			}
		});
	};

	this.setOutputFormat = function (strOutputFormat_a)
	{
		m_strOutputFormat = strOutputFormat_a;
	};
}


function suburbFinder(objOS_a, strFormID_a, strSuburbField_a, strStateField_a, strPostcodeField_a, strCountryField_a, strDefaultCountry_a, cbClear_a, cbSelected_a)
{
	var os = objOS_a;
	var m_objThis = this;

	var m_strOutputFormat = '%SUBURB% | %STATE% | %POSTCODE%';
	var m_strSuburbResultFormat = '%SUBURB%';

	var m_strSearched = '';
	var m_strCurrentSuburb = '!';
	var m_strCurrentState = '!';
	var m_strCurrentPostcode = '!';
	var m_strCurrentCountry = '!';

	if (strDefaultCountry_a.length > 0)
	{
		if (m_strCurrentCountry === '!')
		{
			m_strCurrentCountry = strDefaultCountry_a;
		}
	}

	// ====================================================================================
	// POPULATION =========================================================================

	this.fetchByCountry = function(objRequest_a, cbResponse_a)
	{
		var strTerm = objRequest_a.term;
		
		function suburbsFetched(objData_a)
		{
			var objResponse = [];

			var intI = 0;
			processArray(objData_a, function (objElement_a)
			{
				objResponse[intI] =
				{
					"value" : objElement_a.country,
					"id" : objElement_a.id
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

		var objJSON = os.ajaxRequestCreate('core_suburbsearchbycountry', [
					{
						name : 'filter',
						value : [
							{
								field : 'country',
								value : strTerm
							}
						]
					},
					{
						name : 'order',
						value : [
							{
								field : 'country',
								ascending : false
							}
						]
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, suburbsFetched, ajaxError);
	};

	this.fetchByPostcode = function (objRequest_a, cbResponse_a)
	{
		var strTerm = objRequest_a.term;
		// function suburbsFetched(objData_a)
		// {
		// var objResponse = [];

		// var intI = 0;
		// processArray(objData_a, function(objElement_a)
		// {
		// objResponse[intI] = {"value":objElement_a.postcode, "id":objElement_a.id};
		// intI++;
		// });
		// cbResponse_a(objResponse);
		// }

		// function ajaxError()
		// {
		// cbResponse_a([]);
		// }

		// var objJSON = os.ajaxRequestCreate('core_suburbslist', [
		// { name: 'filter', value: [{ field: 'postcode', value: strTerm}] },
		// { name: 'order', value: [{field: 'postcode', ascending: false}] }
		// ]);
		// os.ajaxCall(URL_WEBSERVICE, objJSON, suburbsFetched, ajaxError);
	};

	this.fetchByState = function (objRequest_a, cbResponse_a)
	{
		var strTerm = objRequest_a.term;
		// function suburbsFetched(objData_a)
		// {
		// var objResponse = [];

		// var intI = 0;
		// processArray(objData_a, function(objElement_a)
		// {
		// objResponse[intI] = {"value":objElement_a.state, "id":objElement_a.id};
		// intI++;
		// });
		// cbResponse_a(objResponse);
		// }

		// function ajaxError()
		// {
		// cbResponse_a([]);
		// }

		// var objJSON = os.ajaxRequestCreate('core_suburbslist', [
		// { name: 'filter', value: [{ field: 'state', value: strTerm}] },
		// { name: 'order', value: [{field: 'state', ascending: false}] }
		// ]);
		// os.ajaxCall(URL_WEBSERVICE, objJSON, suburbsFetched, ajaxError);
	};

	this.fetchBySuburb = function(objRequest_a, cbResponse_a)
	{
		var strTerm = objRequest_a.term;

		function suburbsFetched(objData_a)
		{
			var objResponse = [];

			var intI = 0;
			processArray(objData_a, function (objElement_a)
			{
				var strOutput = m_strOutputFormat;
				strOutput = strOutput.replace('%SUBURB%', objElement_a.suburb);
				strOutput = strOutput.replace('%STATE%', objElement_a.state);
				strOutput = strOutput.replace('%POSTCODE%', objElement_a.postcode);
				strOutput = strOutput.replace('%COUNTRY%', objElement_a.country);
				objResponse[intI] =
				{
					"value" : strOutput,
					"id" : objElement_a.id
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

		m_strSearched = strTerm;

		if ($.isFunction(cbClear_a))
		{
			cbClear_a(strTerm);
		}

		if (strTerm.substring(0, 1) === '#')
		{
			//doNothing();
			os.element(strFormID_a, '.' + strSuburbField_a).autocomplete("close");
		}
		else
		{
			var objJSON = os.ajaxRequestCreate('core_suburbsearchbysuburb', [
						{
							name : 'filter',
							value : [
								{
									field : 'suburb',
									value : strTerm
								},
								{
									field : 'country',
									value : m_strCurrentCountry
								}
							]
						},
						{
							name : 'order',
							value : [
								{
									field : 'suburb',
									ascending : false
								}
							]
						}
					]);
			os.ajaxCall(URL_WEBSERVICE, objJSON, suburbsFetched, ajaxError);
		}
	};

	this.fetchBySuburbID = function (strSuburbID_a)
	{
		//alert('fetch by suburb id');
		function suburbsFetched(objData_a)
		{
			m_strCurrentSuburb = objData_a[0].suburb;
			m_strCurrentState = objData_a[0].state;
			m_strCurrentPostcode = objData_a[0].postcode;
			m_strCurrentCountry = objData_a[0].country;

			if ($.isFunction(cbSelected_a))
			{
				cbSelected_a(m_strSearched, strSuburbID_a, m_strCurrentSuburb, m_strCurrentState, m_strCurrentPostcode, m_strCurrentCountry);
			}

			var strSuburbResultFormat = m_strSuburbResultFormat;
			strSuburbResultFormat = strSuburbResultFormat.replace('%SUBURB%', m_strCurrentSuburb);
			strSuburbResultFormat = strSuburbResultFormat.replace('%STATE%', m_strCurrentState);
			strSuburbResultFormat = strSuburbResultFormat.replace('%POSTCODE%', m_strCurrentPostcode);
			strSuburbResultFormat = strSuburbResultFormat.replace('%COUNTRY%', m_strCurrentCountry);

			os.element(strFormID_a, '.' + strSuburbField_a).val(strSuburbResultFormat);
			os.element(strFormID_a, '.' + strStateField_a).val(m_strCurrentState);
			os.element(strFormID_a, '.' + strPostcodeField_a).val(m_strCurrentPostcode);
			os.element(strFormID_a, '.' + strCountryField_a).val(m_strCurrentCountry);
		}

		function ajaxError()
		{
			doNothing();
		}

		var objJSON = os.ajaxRequestCreate('core_suburbfetch',
				[
					{
						"name" : "id",
						"value" : strSuburbID_a
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, suburbsFetched, ajaxError);
	};

	this.selectByCountry = function (objEvent_a, objSelection_a)
	{
		m_strCurrentCountry = objSelection_a.item.id;
		os.element(strFormID_a, '.' + strSuburbField_a).focus();
	};

	this.selectByPostcode = function (objEvent_a, objSelection_a)
	{
		//alert('Postcode Selected');
	};

	this.selectByState = function (objEvent_a, objSelection_a)
	{
		//alert('State Selected');
	};

	this.selectBySuburb = function (objEvent_a, objSelection_a)
	{
		var strSuburbID = objSelection_a.item.id;
		m_objThis.fetchBySuburbID(strSuburbID);
	};

	// ====================================================================================
	// INITIALISATION =====================================================================


	this.bind = function ()
	{
		os.element(strFormID_a, '.' + strCountryField_a).autocomplete(
		{
			delay : 500,
			source : m_objThis.fetchByCountry,
			select : m_objThis.selectByCountry
		}
		);

		// os.element(strFormID_a, '.' + strPostcodeField_a).autocomplete(
		// {
		// delay: 500,
		// source: m_objThis.fetchByPostcode,
		// select: m_objThis.selectByPostcode
		// });

		// os.element(strFormID_a, '.' + strStateField_a).autocomplete(
		// {
		// delay: 500,
		// source: m_objThis.fetchByState,
		// select: m_objThis.selectByState
		// });

		os.element(strFormID_a, '.' + strSuburbField_a).autocomplete(
		{
			autoFocus : true,
			delay : 0,
			source : m_objThis.fetchBySuburb,
			select : m_objThis.selectBySuburb
		}
		);
	};

	this.setOutputFormat = function (strOutputFormat_a)
	{
		m_strOutputFormat = strOutputFormat_a;
	};

	this.setSuburbResultFormat = function (strSuburbResultFormat_a)
	{
		m_strSuburbResultFormat = strSuburbResultFormat_a;
	};
}

function childTabHandler(objOptions_a)
{
	var m_objThis = this;
	var m_objOptions = objOptions_a;
	var m_strHandlerID = getGUID();

	var m_objTab =
	{
		"id" : m_objOptions.id,
		"caption" : 'Child (' + m_objOptions.id + ')',
		"window" : m_objOptions.window,
		"opener" : m_objOptions.window.opener
	};

	$(m_objOptions.window).bind('message', function (objEvent_a)
	{
		if ($.isFunction(m_objOptions.cbOnBroadcast))
		{
			var objJSON = null;
			
	//console.log('before:childTabHandler');
			try
			{
				objJSON = JSON.parse(objEvent_a.originalEvent.data);
			}
			catch (err)
			{
				// do nothing
			}
	//console.log('after1:childTabHandler');

			if (objJSON !== null)
			{
				if (objJSON.queue == undefined)
				{
					doNothing();
				}
				else
				{
					m_objOptions.cbOnBroadcast(objJSON.queue, objJSON.message, objJSON.messagedata);
				}
			}
	//console.log('after2:childTabHandler');
		}
	}
	);

	// ====================================================================================
	// PUBLICS ============================================================================

	m_objThis.broadcast = function (strQueue_a, strMessage_a, objMessageData_a)
	{
		var objJSON =
		{
			"tabid" : m_objTab.id,
			"originid" : m_objTab.id,
			"origindescription" : m_objTab.caption,
			"queue" : strQueue_a,
			"message" : strMessage_a,
			"messagedata" : objMessageData_a
		};
		//alert('child broadcast:' + JSON.stringify(objJSON));
		m_objTab.opener.postMessage(JSON.stringify(objJSON), '*');
	};

	m_objThis.canCreateTab = function ()
	{
		return false;
	};

	m_objThis.getHandlerID = function ()
	{
		return m_strHandlerID;
	};

	m_objThis.getTabInfo = function ()
	{
		return m_objTab;
	};

	m_objThis.getTabs = function ()
	{
		return [
			{
				"id" : m_objTab.id,
				"caption" : m_objTab.caption
			}
		];
	};

	m_objThis.ping = function ()
	{
		//logDebug('child ping: ' + m_objTab.id);
		m_objThis.broadcast('system', 'ping', 'ping');
	};
}

function parentTabHandler(objOptions_a)
{
	var m_objThis = this;
	var m_objOptions = objOptions_a;
	var m_strHandlerID = getGUID();

	var m_arrTabs = [];
	m_arrTabs[0] =
	{
		"id" : "0",
		"caption" : 'Parent',
		"window" : m_objOptions.window,
		"opener" : null
	};
	var m_intNextTabID = 1;

	$(m_objOptions.window).bind('message', function (objEvent_a)
	{
		reAddTab(objEvent_a);
		reBroadcast(objEvent_a);

		if ($.isFunction(m_objOptions.cbOnBroadcast))
		{
			var objJSON = null;
			
	//console.log('before:parentTabHandler');
			try
			{
				objJSON = JSON.parse(objEvent_a.originalEvent.data);
			}
			catch (err)
			{
				// do nothing
			}
	//console.log('after1:parentTabHandler');

			if (objJSON !== null)
			{
				if (objJSON.queue == undefined)
				{
					doNothing();
				}
				else
				{
					m_objOptions.cbOnBroadcast(objJSON.queue, objJSON.message, objJSON.messagedata);
				}
			}
	//console.log('after2:parentTabHandler');
		}
	}
	);

	// ====================================================================================
	// HELPERS ============================================================================

	// re-add the source if they were removed (ie due to a parent tab refresh)
	function reAddTab(objEvent_a)
	{
		var strOrigin = objEvent_a.origin || objEvent_a.originalEvent.origin;
		var objSource = objEvent_a.source || objEvent_a.originalEvent.source;
		var objJSON = null;

//console.log('before:reAddTab');
		try
		{
			objJSON = JSON.parse(objEvent_a.originalEvent.data);
		}
		catch (err)
		{
			// do nothing
		}
//console.log('after1:reAddTab');

		if (objJSON !== null)
		{
			var blnReAdd = true;
			processArray(m_arrTabs, function (objTab_a)
			{
				//alert(objTab_a.id + ":" + objJSON.originid);
				if (objTab_a.id == objJSON.originid)
				{
					blnReAdd = false;
					return true;
				}
			}
			);

			if (blnReAdd)
			{
				//logDebug('parentTabHandler re-added: ' + objJSON.originid);
				var intTabID = m_intNextTabID;
				var objTab =
				{
					"id" : objJSON.originid,
					"caption" : objJSON.origindescription,
					"window" : null,
					"opener" : null
				};
				m_intNextTabID++;
				m_arrTabs[intTabID] = objTab;

				objTab.window = objSource;
			}
		}
//console.log('after2:reAddTab');
	}

	function reBroadcast(objEvent_a)
	{
		var objJSON = null;

//console.log('before:reBroadcast');
		try
		{
			objJSON = JSON.parse(objEvent_a.originalEvent.data);
		}
		catch (err)
		{
			// do nothing
		}
//console.log('after1:reBroadcast');

		if (objJSON !== null)
		{
			processArray(m_arrTabs, function (obj_a)
			{
				if ((obj_a.id !== "0") && (obj_a.id !== objJSON.originid))
				{
					var objJSONNew =
					{
						"tabid" : "0",
						"originid" : objJSON.originid,
						"origindescription" : objJSON.origindescription,
						"queue" : objJSON.queue,
						"message" : objJSON.message,
						"messagedata" : objJSON.messagedata
					};
					obj_a.window.postMessage(JSON.stringify(objJSONNew), '*');
				}
			}
			);
		}
//console.log('after2:reBroadcast');
	}

	// ====================================================================================
	// PUBLICS ============================================================================

	m_objThis.broadcast = function (strQueue_a, strMessage_a, objMessageData_a)
	{
		processArray(m_arrTabs, function (obj_a)
		{
			if (obj_a.id !== "0")
			{
				var objJSON =
				{
					"tabid" : "0",
					"originid" : "0",
					"origindescription" : m_arrTabs[0].caption,
					"queue" : strQueue_a,
					"message" : strMessage_a,
					"messagedata" : objMessageData_a
				};
				obj_a.window.postMessage(JSON.stringify(objJSON), '*');
			}
		}
		);
	};

	m_objThis.canCreateTab = function ()
	{
		return true;
	};

	m_objThis.createTab = function (strURL_a, strForm_a, strParameters_a)
	{
		var intTabID = m_intNextTabID;
		var strTabID = getGUID();

		var strForm = strForm_a;
		if (strForm === undefined)
		{
			strForm = '';
		}

		var strParameters = strParameters_a;
		if (strParameters === undefined)
		{
			strParameters = '';
		}

		var objTab =
		{
			"id" : strTabID,
			"caption" : 'Child (' + strTabID + ')',
			"window" : null,
			"opener" : null
		};
		m_intNextTabID++;
		m_arrTabs[intTabID] = objTab;

		objTab.window = window.open(strURL_a + '?tabid=' + objTab.id + strForm + strParameters, objTab.caption);
		return objTab.window;
	};

	m_objThis.getHandlerID = function ()
	{
		return m_strHandlerID;
	};

	m_objThis.getTabInfo = function (intTabID_a)
	{
		return m_arrTabs[intTabID_a];
	};

	m_objThis.getTabs = function ()
	{
		var arrResult = [];

		processArray(m_arrTabs, function (objTab_a)
		{
			arrResult.push(
			{
				"id" : objTab_a.id,
				"caption" : objTab_a.caption
			}
			);
		}
		);

		return arrResult;
	};

	m_objThis.ping = function ()
	{
		//logDebug('parent ping');
		m_objThis.broadcast('system', 'ping', 'ping');
	};
}
