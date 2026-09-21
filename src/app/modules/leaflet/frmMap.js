/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
function leaflet_frmMap(objOS_a, strFormID_a, objParameters_a)
{
	var MAPSPROVIDER = 'GOOGLE'; // OPENMAPS or GOOGLE

	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

    var m_blnForceVerticalScroll = ((TESTSCROLL == 'TRUE')  && !os.hasCapability('regionscroll') && os.hasCapability('mobile'));
    if (m_blnForceVerticalScroll)
    {        
        os.element(m_strFormID, '.ge-content-panel').removeClass('gb-scrollable-panel');
        os.element(m_strFormID, '.ge-thecontent').removeClass('gb-scrollable-content');
        os.element(m_strFormID, '.gb-form').removeClass('gb-resizable');

        os.element(m_strFormID).css( { 'position' : 'relative', 'height' : 'auto' });                    
    }
    
	var m_strCaption = m_objParameters.caption;
	var m_strMode = m_objParameters.mode;
	var m_strSearch = m_objParameters.search;
	
	var m_arrAvailableColours = ['red', 'orange', 'magenta', 'purple', 'pink', 'brown', 'gray', 'black', 'turquoise', 'yellow',
		'darkred', 'coral', 'hotpink', 'violet', 'crimson', 'sienna', 'olive', 'teal', 'salmon', 'gold',
		'firebrick', 'tomato', 'orchid', 'plum', 'fuchsia', 'peru', 'tan', 'khaki', 'lime', 'greenyellow',
		'darkorange', 'orangered', 'deeppink', 'mediumvioletred', 'maroon', 'chocolate', 'saddlebrown', 'darkkhaki', 'lawngreen', 'chartreuse',
		'darkgreen', 'forestgreen', 'limegreen', 'mediumseagreen', 'seagreen', 'darkseagreen', 'mediumaquamarine', 'aquamarine', 'cadetblue', 'lightseagreen',
		'yellowgreen', 'springgreen', 'mediumspringgreen', 'green', 'bluegreen', 'blue', 'blueviolet', 'darkslateblue', 'slateblue', 'mediumslateblue',
		'indigo', 'darkblue', 'steelblue', 'royalblue', 'dodgerblue', 'skyblue', 'lightblue', 'powderblue', 'cornflowerblue', 'lightsteelblue',
		'mediumpurple', 'darkmagenta', 'palevioletred', 'mediumorchid', 'darkviolet', 'purple', 'darkgray', 'olivedrab', 'burlywood', 'rosybrown',
		'sandybrown', 'lightsalmon', 'lightcoral', 'palegreen', 'thistle', 'mistyrose', 'seashell', 'peachpuff', 'navajowhite', 'lemonchiffon',
		'honeydew', 'mintcream', 'azure', 'lightcyan', 'paleturquoise', 'gainsboro', 'whitesmoke', 'lightgray', 'aliceblue', 'ghostwhite'
	];
	var m_arrColours = m_arrAvailableColours.slice();
	var m_arrUsedColours = [];
		
	var m_strMapDataID = "";
	var m_objMapData;

	var m_strGroupID = "";
	
	var m_arrRenderedLocalities = [];	// contains: group & grouplocality IDs
	var m_arrGroupVisibility = [];	// are groups visible or not

	// TODO: setup m_strMapDataID based on what we are looking the dataup with a vehicle route? an asset class?
	// m_strMapDataID = 

	// ------------------------------------------------------------------------------------

	var m_objMap;
	var m_objMarker = null;
	var m_objLegend;
	var m_strFoundMessage = '';

	// ------------------------------------------------------------------------------------

	var m_strFormFields = '';
	
	// ------------------------------------------------------------------------------------

	var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : [
				['CloseButton','ToggleLegendButton']
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
			classes : 'gs-green-background-colour gs-glow-focusborder',
			permissions : [],
			action : function()
			{
				m_objThis.FormClose_onClick();
			},
			actionData : null,
			tip : 'Click here to close map.',
			type : 'toolbarbutton'
		},
        {
			id : 'ToggleLegendButton',
			caption : 'Toggle Legend',
			classes : '',
			permissions : [],
			action : function ()
			{
				m_objThis.ToggleLegend_onClick();
			},
			actionData : null,
			tip : 'Click here to show or hide the legend.',
			type : 'toolbarbutton'
		}
	];

	// ====================================================================================
	// HELPERS ============================================================================

	function decodePolyline(strEncoded_a) 
	{
		var arrResult = [];
		var intI = 0;
		var intLength = strEncoded_a.length;
		var fltLat = 0;
		var fltLng = 0;

		function decodeValue() 
		{
			var intResult = 0;
			var intShift = 0;
			var strByte;

			while (true) 
			{
				strByte = strEncoded_a.charCodeAt(intI) - 63;
				intI++;
				intResult += (strByte & 0x1f) * Math.pow(2, intShift);
				intShift += 5;

				if (strByte < 0x20) break;
			}

			if (intResult & 1) 
			{
				intResult = ~(intResult >> 1);
			} 
			else 
			{
				intResult = intResult >> 1;
			}

			return intResult;
		}

		while (intI < intLength) 
		{
			fltLat += decodeValue();
			fltLng += decodeValue();
			arrResult.push([fltLat * 0.00001, fltLng * 0.00001]);
		}

		return arrResult;
	}

	function findColour(strGroupID_a)
	{
		var strResult = "";

		processArray(m_arrUsedColours, function(objColour_a) 
		{
			if (objColour_a.groupid === strGroupID_a) 
			{
				strResult = objColour_a.colour;
				return true;
			}
		});
	
		return strResult;
	}
	
	function findGroup(strGroupID_a)
	{
		var strResult = "";

		processArray(m_arrRenderedLocalities, function(objLocality_a) 
		{
			if (objLocality_a.groupid === strGroupID_a) 
			{
				strResult = objLocality_a.groupid;
				return true;
			}
		});
	
		return strResult;
	}
	
	function findGroupByGroupLocalityID(strGroupLocalityID_a)
	{
		var strResult = "";

		processArray(m_arrRenderedLocalities, function(objLocality_a) 
		{
			if ((objLocality_a.grouplocalityid === strGroupLocalityID_a) || (objLocality_a.grouplocalityid === htmlEncode(strGroupLocalityID_a)))
			{
				strResult = objLocality_a.groupid;
				return true;
			}
		});
	
		return strResult;
	}
	
	function findGroupLocalityByGroupLocalityID(strGroupLocalityID_a)
	{
		var strResult = "";

		processArray(m_arrRenderedLocalities, function(objLocality_a) 
		{
			if ((objLocality_a.grouplocalityid === strGroupLocalityID_a) || (objLocality_a.grouplocalityid === htmlEncode(strGroupLocalityID_a)))
			{
				strResult = objLocality_a.grouplocalityid;
				return true;
			}
		});
	
		return strResult;
	}
	
	function findGroupVisiblityIndex(strGroupID_a)
	{
		var intResult = -1;

		var intI = 0;
		processArray(m_arrGroupVisibility, function(objGroup_a) 
		{
			if (objGroup_a.groupid === strGroupID_a) 
			{
				intResult = intI;
			}
			
			intI++;
		});
	
		return intResult;
	}
	
    function registerBroadcaster() 
	{
        os.registerServerEvent('mapping', m_strFormID);
        os.enableServerEventQueue('mapping');
        os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerbroadcaster', [{ "name" : "eventqueue", "value" : "mapping" }]), doNothing, doNothing);
        os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerlistener', [{ "name" : "eventqueue", "value" : "mapping" }]), doNothing, doNothing);
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
	
	function showAddress(objSelectedAddress_a)
	{
		// remove marker if there is one
		if (m_objMarker)
		{
			m_objMarker.remove();
		}
		
		m_objMarker = L.marker([objSelectedAddress_a.lat, objSelectedAddress_a.lon]).addTo(m_objMap);
		if (objSelectedAddress_a.bbox && objSelectedAddress_a.bbox.lat1 !== objSelectedAddress_a.bbox.lat2 && objSelectedAddress_a.bbox.lon1 !== objSelectedAddress_a.bbox.lon2) 
		{
			m_objMap.fitBounds([[objSelectedAddress_a.bbox.lat1, objSelectedAddress_a.bbox.lon1], [objSelectedAddress_a.bbox.lat2, objSelectedAddress_a.bbox.lon2]], { padding: [100, 100] });
		} else {
			m_objMap.setView([objSelectedAddress_a.lat, objSelectedAddress_a.lon], 18);
		}
	}	

	// https://www.geoapify.com/leaflet-geocoding-plugin
	function showMap()
	{
		var strMapID = os.addUniqueID(os.element(m_strFormID, '.ge-map'));

		m_objMap = L.map(strMapID).setView([-37.813628, 144.963058], 18);
		
		//var strMapURL = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		//var strMapURL = L.Browser.retina
		//	? 'https://maps.geoapify.com/v1/tile/{mapStyle}/{z}/{x}/{y}.png?apiKey={apiKey}'
		//	: 'https://maps.geoapify.com/v1/tile/{mapStyle}/{z}/{x}/{y}@2x.png?apiKey={apiKey}';

		var strMapURL;
		var strAttribution;
		if (MAPSPROVIDER === 'OPENMAPS') 
		{
			strMapURL = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
			strAttribution = 'Powered by Geoapify | © OpenMapTiles © OpenStreetMap contributors';
		} 
		else if (MAPSPROVIDER === 'GOOGLE') 
		{
			strMapURL = 'https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}';
			strAttribution = 'Powered by Google Maps. Map data ©2025 Google.';
		}
	
		L.tileLayer(strMapURL, {
		maxZoom: 18,
		attribution: strAttribution,
		apiKey: GEOAPIFY_APIKEY,
		mapStyle: "osm-bright-smooth"
		}).addTo(m_objMap);
		
		// Add Geoapify Address Search control
		var objAddressSearchControl = L.control.addressSearch(GEOAPIFY_APIKEY, 
		{
			position: 'topright',
			value: m_strSearch,
			resultCallback: showAddress,
			suggestionsCallback: function(arrResponse_a)
			{
				if (arrResponse_a.length > 0)
				{
					showAddress(arrResponse_a[0]);
				}
			}
		});
		m_objMap.addControl(objAddressSearchControl);
		//L.control.zoom({ position: 'bottomright' }).addTo(m_objMap);
		
		// legend
		m_objLegend = L.control({ position: 'topleft' });

		m_objLegend.onAdd = function(objMap_a) 
		{
			var objDiv = L.DomUtil.create('div', 'ge-legend');

			objDiv.style.background = 'white';
			objDiv.style.padding = '10px';
			objDiv.style.borderRadius = '5px';
			objDiv.style.boxShadow = '0 0 15px rgba(0,0,0,0.2)';
			objDiv.style.maxHeight = '70%';
			objDiv.style.overflowY = 'auto';
			objDiv.innerHTML = '';

			return objDiv;
		};
		
		m_objLegend.addTo(m_objMap);
	
		getMapData();
		m_objMap.setZoom(10);

		registerBroadcaster();
	}

	function renderGroup(objGroup_a, intGroupIndex_a, strColour_a, blnVisible_a)
	{
		var arrEncodedPolylines;
		var arrPolylineDataChunks;
		var objStartMarker;
		var objStartGeolocation;
		var objEndGeolocation;
		var strPolylineDataFormat = objGroup_a.polylinedataformat;
		
		try
		{
			if (objGroup_a.startgeolocation.length > 0)
			{
				objStartGeolocation = JSON.parse(objGroup_a.startgeolocation);
			}

			if (objGroup_a.endgeolocation.length > 0)
			{
				objEndGeolocation = JSON.parse(objGroup_a.endgeolocation);
			}
			
			if (objGroup_a.polylinedata.length > 0)
			{
				arrPolylineDataChunks = JSON.parse(objGroup_a.polylinedata);
			}
		}
		catch(err)
		{
			os.dialogAlertScroll(err, function ()  {});
		}
		
		if (blnVisible_a)
		{
			if (arrPolylineDataChunks !== undefined)
			{
				if (strPolylineDataFormat === 'GOOGLE')
				{
					arrEncodedPolylines = [];
					processArray(arrPolylineDataChunks, function(objPolylineDataChunk_a)
					{
						if (objPolylineDataChunk_a.routes.length > 0)
						{
							arrEncodedPolylines.push(objPolylineDataChunk_a.routes[0].polyline.encodedPolyline);
						}
					});
				}
			}

			if ((objStartGeolocation !== undefined) && (objEndGeolocation !== undefined))
			{
				// draw start / end markers
				if (objStartGeolocation.lat === objEndGeolocation.lat && objStartGeolocation.lng === objEndGeolocation.lng) 
				{
					objStartMarker = L.marker([objStartGeolocation.lat, objStartGeolocation.lng], 
					{
						icon: L.divIcon(
						{
							className: 'custom-icon',
							html: '<div style="cursor:default; width: 20px; height: 20px; font-size: 12px; color: yellow; background-color: #ff0000; border-radius: 50%; text-align: center; line-height: 20px;">SE</div>',
							iconSize: [20, 20]
						})
					}).addTo(m_objMap);
				} 
				else 
				{
					objStartMarker = L.marker([objStartGeolocation.lat, objStartGeolocation.lng], 
					{
						icon: L.divIcon(
						{
							className: 'custom-icon',
							html: '<div style="cursor:default; width: 20px; height: 20px; font-size: 12px; color: yellow; background-color: #ff0000; border-radius: 50%; text-align: center; line-height: 20px;">S</div>',
							iconSize: [20, 20]
						})
					}).addTo(m_objMap);

					var objEndMarker = L.marker([objEndGeolocation.lat, objEndGeolocation.lng], 
					{
						icon: L.divIcon(
						{
							className: 'custom-icon',
							html: '<div style="cursor:default; width: 20px; height: 20px; font-size: 12px; color: yellow; background-color: #ff0000; border-radius: 50%; text-align: center; line-height: 20px;">E</div>',
							iconSize: [20, 20]
						})
					}).addTo(m_objMap);
				}
			}

			// draw localities
			processArray(objGroup_a.localities, function(objLocality_a)
			{
				var objWaypoint = JSON.parse(objLocality_a.geolocation);
				var objMarker = L.marker([objWaypoint.lat, objWaypoint.lng], 
				{
					icon: L.divIcon(
					{
						className: 'custom-icon',
						html: '<div class="ge-grouplocality" style="width: 20px; height: 20px; font-size: 12px; color: yellow; background-color: ' + strColour_a + '; border-radius: 50%; text-align: center; line-height: 20px;" grouplocalityid="' + htmlEncode(objLocality_a.id) + '">' + htmlEncode(objLocality_a.sequence) + '</div>',
						iconSize: [20, 20]
					})
				}).addTo(m_objMap);
				
				m_arrRenderedLocalities.push({ groupid:objGroup_a.groupid, grouplocalityid:objLocality_a.id });
			});

			// render polylines
			if (strPolylineDataFormat === 'GOOGLE')
			{
				if (arrEncodedPolylines !== undefined)
				{
//alert(arrEncodedPolylines.length);
					processArray(arrEncodedPolylines, function(arrEncodedPolyline_a)
					{
						var arrDecodedPolyline = decodePolyline(arrEncodedPolyline_a);
						if (arrDecodedPolyline.length > 0) 
						{
							L.polyline(arrDecodedPolyline, { color: strColour_a }).addTo(m_objMap);
						}
					});
				}
			}
		}
		
		// draw the legend
		var strChecked = "";
		if (blnVisible_a)
		{
			strChecked = "checked";
		}

		var strHTML = '<div style="display: flex; align-items: center; margin-bottom: 10px;">';

			strHTML += '<div class="ge-colour-circle group-color-circle" groupindex="' + intGroupIndex_a + '" style="cursor:pointer; width: 20px; height: 20px; background-color: ' + strColour_a + '; border-radius: 50%; margin-right: 5px;"></div><input class="ge-togglegroup" groupindex="' + intGroupIndex_a + '" style="vertical-align:top; margin-right: 5px;" type="checkbox"' + strChecked + '>';
			strHTML += '<div>';

				// TODO: render the legend based on the m_strMode, vehicle route? asset class?
				// strHTML += '<div></div>';

			strHTML += '</div>';
		strHTML += '</div>';
		m_objLegend.getContainer().innerHTML += strHTML;


		// Add an event listener for the color circle
		$('.ge-colour-circle').on('click', function() 
		{
			var intGroupIndex = parseInt($(this).attr('groupindex'), 10);
			var objGroup = m_objMapData.groups[intGroupIndex];
			if (objGroup.localities.length > 0) 
			{
				var objWaypoint = JSON.parse(objGroup.localities[0].geolocation);
				m_objMap.panTo([objWaypoint.lat, objWaypoint.lng]);
			}
		});
	}
	
	function renderGroups(blnToggleAll_a)
	{
		m_objLegend.getContainer().innerHTML = ''; // clear the legend

		//m_arrRenderedLocalities = [];		// don't reset these as it will prevent them from coming back when turned on

		// remove all groups
		m_objMap.eachLayer(function(objLayer_a) 
		{
			if (objLayer_a instanceof L.Polyline || objLayer_a instanceof L.Marker) 
			{
				m_objMap.removeLayer(objLayer_a);
			}
		});

		if (m_objMapData.groups.length > 0)
		{
			var strChecked = "";
			if (blnToggleAll_a)
			{
				strChecked = "checked";
			}

			var strHTML = '<div style="display: flex; align-items: center; margin-bottom: 10px;">';

				strHTML += '<input class="ge-toggleall" style="vertical-align:top; margin-right: 5px;" type="checkbox"' + strChecked + '>';
				strHTML += '<div>';
					strHTML += '<div><b>Toggle All</b></div>';
				strHTML += '</div>';

			strHTML += '</div>';
			m_objLegend.getContainer().innerHTML += strHTML;
		}
	
		processArray(m_objMapData.groups, function(objGroup_a)
		{
			var strColour = findColour(objGroup_a.groupid);
			if (strColour.length === 0)
			{
				strColour = m_arrColours.shift();
				m_arrUsedColours.push({ groupid:objGroup_a.groupid, colour:strColour });
			}
			
			var blnVisible = true;
			var intGroupIndex = findGroupVisiblityIndex(objGroup_a.groupid);
			if (intGroupIndex >= 0)
			{
				blnVisible = m_arrGroupVisibility[intGroupIndex].visible;
			}
			else
			{
				intGroupIndex = m_arrGroupVisibility.length;
				m_arrGroupVisibility.push({ groupid:objGroup_a.groupid, visible:true });	// true by default
			}

			try
			{
				renderGroup(objGroup_a, intGroupIndex, strColour, blnVisible);
			}
			catch(err)
			{
				os.dialogAlertScroll(err, function ()  {});
			}
		});

		// bindings
		os.unbindEvents(m_strFormID, 'ge-grouplocality,ge-toggleall,ge-togglegroup');

		os.bindEvent(m_objThis, m_strFormID, '.ge-grouplocality', 'GroupLocality', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-toggleall', 'ToggleAll', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-togglegroup', 'ToggleGroup', 'onClick');
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	function getMapDataResponse(objResponse_a)
	{
		m_objMapData = objResponse_a[0];
		
		m_strMapDataID = m_objMapData.mapdataid;

		// TODO: based m_strMode, change caption and form title
		// m_strCaption = 'Run Manifest: ' + m_objMapData.description + ' Asset Classes';
		// os.element(m_strFormID, '.ge-form-title').text(m_strCaption);
		
		renderGroups(true);
	}

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================
	
	function getMapData()
	{
		var objJSON;

		// TODO: based onm_strMode, fetch approprate map data
		// objJSON = os.ajaxRequestCreate('maps_getassetclasses',
				// [
 					// {
						// "name" : "id",
						// "value" : m_strMapDataID
					// }
				// ]);
		//os.ajaxCall(URL_WEBSERVICE, objJSON, getMapDataResponse, os.ajaxError, doNothing);
		getMapDataResponse();
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form,gb-formtitle-inner-panel,ge-legend');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
		
		os.bindEvent(m_objThis, m_strFormID, '.ge-legend', 'Legend', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-legend', 'Legend', 'onDblClick');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_onBroadcast = function(strQueue_a, strMessage_a, objJSON_a)
	{
		var strMapDataID = "";
		var strGroupID = "";
		var strGroupLocalityID = "";
		
		// TODO: fetch map data base on queue and message
		// getMapData();
	};

	this.Form_onClick = function()
	{
		os.setFormFocus(m_objThis, m_strFormID);
	};

	this.FormClose_onClick = function()
	{
		os.closeForm(m_strFormID);
	};
	
	this.Form_onDblClick = function()
	{
		os.formToFront(m_strFormID);
	};

	this.Form_onResize = function(intWidth_a, intHeight_a)
	{
		//var intHeight = 0;

		//if (m_strMode === 'html')
		//{
			//intHeight = intHeight_a - 10;
		//}
		//else
		//{
//			intHeight = intHeight_a - 140;
		//}

		//os.element(m_strFormID, '.ge-panel-content').height((intHeight) + 'px');
		//os.element(m_strFormID, '.ge-panel-overflow').height('100%');
		//os.element(m_strFormID, '.ge-panel-overflow').css('overflow-y','auto');

		var intHeight = os.getFormCanvasHeight(m_strFormID) - 80;
        
        if (m_blnForceVerticalScroll)
        {        
            doNothing();
        }        
        else
        {
            os.element(m_strFormID, '.ge-content-panel').height((intHeight) + 'px');
            os.element(m_strFormID, '.ge-content-panel').width('100%');
            os.element(m_strFormID, '.ge-thecontent').height('100%');
            os.element(m_strFormID, '.ge-thecontent').width('100%');
			
			m_objMap.invalidateSize();
        }
	};

	this.Form_onLoad = function()
	{
		populateDock();

		// optional caption
		if (m_strCaption !== undefined)
		{
			os.element(m_strFormID, '.ge-form-title').text(m_strCaption);
		}

		var intTop = os.getCanvasTop();
		var intHeight = os.getCanvasHeight();
		var intLeft ;
		var intWidth ; 
		// full width in mobile
		if(os.getCanvasWidth() > 768)
		{
			intLeft = os.getCanvasLeft() + os.getCanvasWidth() * 0.5;
			intWidth = os.getCanvasWidth() * 0.5;
		}
		else
		{
			intLeft = os.getCanvasLeft() ;
			intWidth = os.getCanvasWidth();
		}
		os.element(m_strFormID, '.gs-form-standard').css('position', 'absolute').css('top', intTop).css('left', intLeft).css('width', intWidth + 'px').css('height', intHeight + 'px');	

		showMap();
		bindGlobals();
	};

	this.Form_onPermissionCheck = function()
	{
		return true;
	};

	this.FormTitle_onClick = function()
	{
		os.formToFront(m_strFormID);
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.GroupLocality_onClick = function(objThis_a)
	{
		var objThis = os.element(objThis_a);
		var strGroupLocalityID = objThis.attr('grouplocalityid');

		strGroupLocalityID = findGroupLocalityByGroupLocalityID(strGroupLocalityID);
		var strGroupID = findGroupByGroupLocalityID(strGroupLocalityID);

		// TODO: based on m_strMode, perform an action
	};

	this.Legend_onClick = function(objThis_a, objElement_a, objEvent_a)
	{
		objEvent_a.stopPropagation();
	};

	this.Legend_onDblClick = function(objThis_a, objElement_a, objEvent_a)
	{
		objEvent_a.stopPropagation();
	};

	this.ToggleLegend_onClick = function()
	{
		var objLegend = os.element(m_strFormID, '.ge-legend');
		
		if (objLegend.is(':visible')) 
		{
			objLegend.hide();
		} 
		else 
		{
			objLegend.show();
		}	
	};
	
	this.ToggleAll_onClick = function(objThis_a)
	{
		var objThis = os.element(objThis_a);
		var blnChecked = objThis.prop('checked');
		
		processArray(m_arrGroupVisibility, function(objGroup_a) 
		{
			objGroup_a.visible = blnChecked;
		});

 		renderGroups(blnChecked);
	};
	
	this.ToggleGroup_onClick = function(objThis_a)
	{
		var objThis = os.element(objThis_a);
		var intGroupIndex = parseInt(objThis.attr('groupindex'), 10);

		m_arrGroupVisibility[intGroupIndex].visible = !m_arrGroupVisibility[intGroupIndex].visible;
 		renderGroups(false);
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

	this.TabEnd_onFocus = function()
	{
		os.element(m_strFormID, '.CloseButton').focus();
	};

	this.TabStart_onFocus = function()
	{
		os.element(m_strFormID, '.ge-periodend-field').focus();
	};
}