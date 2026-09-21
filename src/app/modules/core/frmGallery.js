/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
function core_frmGallery(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

	var m_blnDebug = false; // true for debug on (in the title bar)
	var m_arrImagesLazy = [];
	var m_arrRendered = [];
	var m_strLastFetched = '';
	var m_blnEndOfGallery = false;
	var m_intResizeCount = 0;

	var m_arrSelection = [];

	var m_cbDoResize;
	var m_intLastFitCount = 0;

	var m_intImagesFetchedCount = 0;

	var m_intFramedImageWidth = 195;
	var m_intFramedImageHeight = 180;
	var m_intFrameSideWidth = 30;
	var m_intImageWidth = 120;
	var m_intImageHeight = 120;
    
    var m_strSearchKeyword = '';
	
	var m_JDOCKHEIGHT = 50;

	// ====================================================================================

    var m_objDock;
    var m_arrMap = [
        {
            location : 'root',
            title : 'Home',
            layout : [
                ['CloseButton','SelectButton']
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
            tip : '',
            type : 'blank'
        },
        {
            id : 'CloseButton',
            caption : 'Close',
            classes : 'btn-success',
            permissions : [],
            action : function ()
            {
                m_objThis.FormClose_onClick();
            },
            tip : 'Click here to close the form',
            type : 'toolbarbutton'
        },
        {
            id : 'SelectButton',
            caption : 'Select',
            classes : 'btn-disabled',
            permissions : [],
            action : function ()
            {
                // m_arrSelection already populated upon clicking the image.
                // we just need to close the form to trigger the return selection.
                os.closeForm(m_strFormID);
            },
            tip : 'Click here to select an image',
            type : 'toolbarbutton'
        }
    ];
    
    // HELPERS ============================================================================

	function addImage(objImage_a)
	{
		var blnFound = false;
		processArray(m_arrRendered, function (objRenderedImage_a)
		{
			if (objRenderedImage_a.url == objImage_a.url)
			{
				blnFound = true;
				return true;
			}
		}
		);

		if (!blnFound)
		{
			m_arrRendered.push(objImage_a);
		}

		return !blnFound;
	}

	function showDebug(intLastFetched_a)
	{
		if (m_blnDebug)
		{
			var strDebug = '<font size="4">TOTALFETCHED: ' + m_intImagesFetchedCount + ', LASTFIT: ' + m_intLastFitCount + ', RENDERED: ' + m_arrRendered.length + ', LASTFETCHED: ' + intLastFetched_a + ', ENDOG: ' + m_blnEndOfGallery + '</font>';
			os.element(m_strFormID, '.ge-form-title').html(strDebug);
		}
	}

	function getImageFitColCount()
	{
		var intW = os.element(m_strFormID, '.ge-content-panel').width() - 30; // consider the scrollbar width
		var intCols = parseInt(intW / m_intFramedImageWidth, 10);

		return intCols;
	}

	function getImageFitRowCount()
	{
		var intH = (os.element(m_strFormID, '.ge-content-panel').height() - m_JDOCKHEIGHT);
		// we want to fetch 1 more row than we are able
		var intRows = parseInt(intH / m_intFramedImageHeight, 10)+ 1; 

		return intRows;
	}

	function getImageFitCount()
	{
		var intCols = getImageFitColCount();
		var intRows = getImageFitRowCount();
		var intFitCount = intCols * intRows;

		return intFitCount;
	}

	function getImageResizeFetchCount()
	{
		var intFitCount = getImageFitCount();
		var intLimit = intFitCount - m_intImagesFetchedCount;

		if (intLimit < 0)
		{
			intLimit = 0;
		}

		return intLimit;
	}

	function getImageScrollFetchCount()
	{
		var intCols = getImageFitColCount();
		var intRows = getImageFitRowCount();
		var intLimit = intCols; // fetch 2 rows at a time + any remaining that are caused to have an incomplete row from resizing

		var intRemainder = m_intImagesFetchedCount % intCols;

		intLimit += intCols - intRemainder;

		if (intLimit < 0)
		{
			intLimit = 0;
		}

		return intLimit;
	}
    
    function clearFormGalleryContent() { 
        
        m_arrImagesLazy = [];
        m_arrRendered = [];
        m_strLastFetched = '';
        m_blnEndOfGallery = false;
        m_intResizeCount = 0;
        m_arrSelection = [];
        m_intLastFitCount = 0;
        m_intImagesFetchedCount = 0;
        
        os.element(m_strFormID, '.ge-renderer-content').html('');
    }
    
    function setDirty() { 
        if(m_arrSelection.length > 0) { 
            m_objDock.enableButtons('SelectButton', 'btn-primary');
        }
        else { 
            m_objDock.disableButtons('SelectButton', 'btn-primary');
        }

    }
    
	// ====================================================================================
	// POPULATING =========================================================================

    function populateDock()
	{
		m_objDock = new jDock(os,
		{
			"alwaysvisiblebuttoncount": 2,
			"map" : m_arrMap,
			"tiles" : m_arrTiles
		});

		m_objDock.render(m_strFormID, '.ge-button-panel');
	}
    
	function populateGallery(arrImages_a)
	{
		var strHTML = '';
		processArray(arrImages_a, function (objImage_a)
		{
			if (addImage(objImage_a))
			{
				m_intImagesFetchedCount++;

				var strURL = objImage_a.url;
				if (strURL.length > 0)
				{
					strURL = objImage_a.url;
				}
				else
				{
					strURL = APP_DOMAIN_PATH + 'fetch.php?token=' + encodeURIComponent(SECURITY_TOKEN) + '&image=' + encodeURIComponent(objImage_a.id);
				}
				var strImage = '<img class="ge-lazy-image" imageid="' + htmlEncode(objImage_a.id) + '" src="' + strURL + '" data-original="' + strURL + '" data-description="' + htmlEncode(objImage_a.tags) + '" width="' + m_intImageWidth + '" height="' + m_intImageHeight + '">';
				//var strFramedImage = '<div class="gs-imagepicker gb-button" style="display:inline-block; margin:5px; border:2px; border-style:solid; width:' + (m_intFramedImageWidth - 14) + 'px; height:' + (m_intFramedImageHeight - 14) + 'px;">';
                var strFramedImage = '<div class="gs-imagepicker gb-button" style="display:inline-block; margin:5px; width:' + (m_intFramedImageWidth - 14) + 'px; height:' + (m_intFramedImageHeight - 14) + 'px;">';
					strFramedImage += '<div style="float:left; width:' + (m_intFramedImageWidth - 14) + 'px; height:10px;">&nbsp;</div>';
					strFramedImage += '<div style="float:left; width:' + m_intFrameSideWidth + 'px; height:' + (m_intImageHeight + 10) + 'px;">&nbsp;</div>';
						strFramedImage += '<div style="float:left; width:' + m_intImageWidth + 'px; height:' + m_intImageHeight + 'px; ">' + strImage + '</div>';
					//strFramedImage += '<div style="float:right; width:' + m_intFrameSideWidth + 'px; height:' + (m_intImageHeight + 10) + 'px;">&nbsp;</div>';
					strFramedImage += '<div style="float:left; text-align:center; width:' + (m_intFramedImageWidth - 14) + 'px; font-size:8px; overflow:ellipsis;">' + htmlEncode(objImage_a.tags) + '</div>';
					//strFramedImage += '<span class="gs-imagepicker-zoom gb-imagepicker-zoom glyphicon glyphicon-zoom-in"></span>';	// TEMPORARILY TURNED OFF THE ABILITY TO VIEW IMAGE
                strFramedImage += '</div>';
				strHTML = strHTML + strFramedImage;
				m_strLastFetched = objImage_a.id;
			}
		}
		);

		if (strHTML.length > 0)
		{
			os.element(m_strFormID, '.ge-renderer-content').append(strHTML);

			// bind the images for clicking
			os.unbindEvents(m_strFormID, 'ge-lazy-image,gb-imagepicker-zoom');
            
			os.bindEvent(m_objThis, m_strFormID, '.ge-lazy-image', 'LazyImage', 'onClick');
            os.bindEvent(m_objThis, m_strFormID, '.gb-imagepicker-zoom', 'ZoomImage', 'onClick');
            
			showDebug(arrImages_a.length);
		}
	}

	function populateMoreGallery()
	{
		var intFitCount = getImageFitCount();

		if ((intFitCount !== m_intLastFitCount) || (m_intLastFitCount === 0) || (intFitCount > m_intImagesFetchedCount))
		{
			m_intLastFitCount = intFitCount;

			if ((intFitCount > m_intImagesFetchedCount)) 
			{
				fetchImagesResize();
			}
		}
	}

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function fetchImagesResize()
	{
		function imagesFetched(objResponse_a)
		{
			m_intResizeCount--;
			if (objResponse_a.length === 0)
			{
				m_blnEndOfGallery = true; 
				showDebug(0);
			}

			populateGallery(objResponse_a);

			if ((m_intResizeCount <= 0) && (!m_blnEndOfGallery))
			{
				populateMoreGallery();
			}
		}

		var intFetchCount = getImageResizeFetchCount();
		m_intResizeCount++;

		if ((intFetchCount === 0) || (m_blnEndOfGallery))
		{
			imagesFetched([]);
		}
		else
		{
			var objJSON = os.ajaxRequestCreate('docs_imagethumbsfetchnext',
					[
						{
							"name" : "imagetype",
							"value" : m_objParameters.mode
						},
						{
							"name" : "fetchcount",
							"value" : intFetchCount
						},
                        {
                            "name" : "filter",
                            "value" : m_strSearchKeyword
                        },
						{
							"name" : "lastfetched",
							"value" : m_strLastFetched
						}
					]);

			os.ajaxCall(URL_WEBSERVICE, objJSON, imagesFetched, os.ajaxError, doNothing, true);
		}
	}

	function fetchImagesScroll()
	{
		function imagesFetched(objResponse_a)
		{
			m_arrImagesLazy = objResponse_a;

			if (objResponse_a.length === 0)
			{
				m_blnEndOfGallery = true; 
				showDebug(0);
			}

			populateGallery(objResponse_a);

		}

		if (m_blnEndOfGallery)
		{
			imagesFetched([]);
		}
		else
		{
			if (m_intResizeCount === 0)
			{
				var intFetchCount = getImageScrollFetchCount();

				var objJSON = os.ajaxRequestCreate('docs_imagethumbsfetchnext',
						[
							{
								"name" : "imagetype",
								"value" : m_objParameters.mode
							},
							{
								"name" : "fetchcount",
								"value" : intFetchCount 
							},
                            {
                                "name" : "filter",
                                "value" : m_strSearchKeyword
                            },
							{
								"name" : "lastfetched",
								"value" : m_strLastFetched
							}
						]);

				os.ajaxCall(URL_WEBSERVICE, objJSON, imagesFetched, os.ajaxError, doNothing, true);
			}
		}
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form-close,gb-form,gb-formtitle-inner-panel,ge-content-panel');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-content-panel', 'ContentPanel', 'onScroll');
        
        // more global bindings
		os.unbindEvents(m_strFormID, 'ge-search-input,ge-search-button,ge-searchclear-button');

		os.bindEvent(m_objThis, m_strFormID, '.ge-search-button', 'btnSearch', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-searchclear-button', 'btnSearchClear', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-search-input', 'SearchField', 'onEnterKey');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================
	this.Form_allowMultipleInstances = function ()
	{
		return true;
	};

	this.Form_onBroadcast = function (strQueue_a, strMessage_a, objMessageData_a)
	{
		if ((strQueue_a === 'hash') && (strMessage_a === 'change'))
		{
            os.closeForm(m_strFormID); 
            // it's out of scope (likely as a popup, so close it... behaviour can be improved in future)
		}
	};

	this.Form_canClose = function ()
	{
		return true;
	};

	this.Form_onClick = function ()
	{
		os.setFormFocus(m_objThis, m_strFormID);
	};

	this.Form_onFocus = function ()
	{
		doNothing();
	};

	this.Form_onDblClick = function ()
	{
		os.formToFront(m_strFormID);
	};

	this.Form_onLoad = function ()
	{
		// resize the form
		var intViewPortWidth = os.getViewPort().width;
		var intViewPortHeight = os.getViewPort().height;
		os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);
		//os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.4, intViewPortWidth*0.4, intViewPortHeight*0.4, intViewPortWidth*0.4);
        
        populateDock();
		bindGlobals();

		if (os.hasCapability('regionscroll') === false)
		{
			os.element(m_strFormID, '.ge-renderer-content').attr('style', 'width:100%;');
		}

		os.element(m_strFormID, '.ge-form-title').html(m_objParameters.caption);
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.FormTitle_onClick = function ()
	{
		os.formToFront(m_strFormID);
	};

	this.FormResult = function ()
	{
		return m_arrSelection;
	};

	this.Form_onResize = function (intWidth_a, intHeight_a)
	{
		var intHeight = os.getFormCanvasHeight(m_strFormID) - 20 - m_JDOCKHEIGHT;

		os.element(m_strFormID, '.ge-content-panel').height((intHeight) + 'px');
		os.element(m_strFormID, '.ge-thecontent').height('100%');

		os.after(100, populateMoreGallery);
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.ContentPanel_onScroll = function(objThis_a, objElement_a, objEvent_a)
	{
		var objElement = objEvent_a.target;
		var intScrollHeight = objElement.scrollHeight;
		var intScrollTop = objElement.scrollTop;
		var intClientHeight = objElement.clientHeight;

		if (intScrollHeight - intScrollTop === intClientHeight) 
		{
			fetchImagesScroll();
		}
	};
	
	this.FormClose_onClick = function ()
	{
		m_arrSelection = [];
		os.closeForm(m_strFormID);
	};

	this.ImageSelected_onClick = function (objThis_a)
	{
		var objSelected =
		{
			"id" : os.element(objThis_a).attr('imageid')
		};
		m_arrSelection.push(objSelected);
		os.closeForm(m_strFormID);
	};

	this.LazyImage_onClick = function (objThis_a, objElement_a, objEvent_a, objEventData_a, objData_a)
	{
		var objSelected =
		{
			"id" : $(objThis_a).attr('imageid')
		};
		
        m_arrSelection = [objSelected];
        
        os.element(m_strFormID, '.gs-imagepicker').removeClass('selected');
        
        $(objThis_a).parents('.gs-imagepicker').addClass('selected');
        
        setDirty();
        
        //m_arrSelection.push(objSelected);
		//os.closeForm(m_strFormID);
	};
    
    this.btnSearch_onClick = function ()
	{
		m_strSearchKeyword = os.element(m_strFormID, '.ge-search-input').val();
        clearFormGalleryContent();
        os.after(100, populateMoreGallery);		
	};

	this.btnSearchClear_onClick = function ()
	{
		m_strSearchKeyword = "";
        clearFormGalleryContent();
        os.after(100, populateMoreGallery);		
		os.element(m_strFormID, '.ge-search-input').val("");
		os.element(m_strFormID, '.ge-search-input').focus();
	};
    

	this.SearchField_onEnterKey = function (objField_a)
	{
		var osObjField = os.element(objField_a);

		m_strSearchKeyword = osObjField.val();

        clearFormGalleryContent();
        
        os.after(100, populateMoreGallery);
	};
    
    this.ZoomImage_onClick = function(objThis_a) { 
        
        var objImage = $(objThis_a).parents('.gs-imagepicker').find('img');
    
        var strDescription = objImage.data('description');
        var strImageID = objImage.attr('imageid');
        
        os.showFormPopup('core.frmImageViewer', { "imageid" : strImageID, "title" : strDescription  });
        
    };
}