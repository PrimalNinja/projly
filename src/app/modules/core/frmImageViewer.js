/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
function core_frmImageViewer(objOS_a, strFormID_a, objParameters_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strFormID = strFormID_a;
	var m_objParameters = objParameters_a;

    var m_strTitle = '';
    var m_strImageID = '';
    
    var m_intRenderContentHeight = 0;
    
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
        }
    ];
    
    // HELPERS ============================================================================

	    
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
    
	function populateForm() { 
    
        os.element(m_strFormID, '.ge-form-title').html( htmlEncode(m_strTitle) );
        
        var strHtmlContent = '<img class="ge-image" src="fetch.php?token=' + encodeURIComponent(SECURITY_TOKEN) + '&image=' + htmlEncode(m_strImageID) +'" />';
        
        os.element(m_strFormID,'.ge-renderer-content').html(strHtmlContent);
        
        
        os.unbindEvents(m_strFormID, 'ge-image');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.ge-image', 'Image', 'onLoad');
    }

	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	
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

		//if (os.hasCapability('regionscroll') === false)
		//{
			//os.element(m_strFormID, '.ge-renderer-content').attr('style', 'width:100%;');
		//}

        os.element(m_strFormID, '.ge-renderer-content').css("width", "100%");
        m_strTitle = m_objParameters.title;
        
        if(m_strTitle === undefined) { 
           m_strTitle = ''; 
        }
        
        m_strImageID = m_objParameters.imageid;
        
        populateForm();
		
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
		var intHeight = os.getFormCanvasHeight(m_strFormID);
        
        m_intRenderContentHeight = intHeight-110;
        
		os.element(m_strFormID, '.ge-content-panel').height(m_intRenderContentHeight + 'px');
        os.element(m_strFormID, '.ge-renderer-content').height(m_intRenderContentHeight + 'px');
		//os.element(m_strFormID, '.ge-thecontent').height('100%');
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

		
	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

    this.Image_onLoad = function(objThis_a) { 
        
        var intWidth = objThis_a.width;
        var intHeight = objThis_a.height;
        
        var intAspectRatio = intWidth / intHeight;
        
        // by making width or height 100%, image will display on its aspect ratio w/o distoring
        // the trick is to determine if the image is wide or long
        // added by Jhun
        // reset width/height set before
        $(objThis_a).css("width", ""); 
        $(objThis_a).css("height", ""); 
        
        
        if(intAspectRatio > 1) { 
            // landscape or image is wide
            
            $(objThis_a).css("width", "100%"); 

        }
        else { 
            // portrait or image is long
           $(objThis_a).css("height", "100%");  
        }
        
        
    };
	
}