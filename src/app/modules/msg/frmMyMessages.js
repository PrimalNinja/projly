/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
/*jsl:import ..\..\inc-osutils-jgrids.js*/

function msg_frmMyMessages(objOS_a, strFormID_a, objParameters_a)
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

	// ------------------------------------------------------------------------------------
	var m_strFormTitle = 'My Messages';
	var m_arrMessages = [];

	var m_intMessageUnreadCount = 0;
	var m_intOffset = 0;
	var m_clearFormList = false;

	//var m_filterParams = m_filterDefaultParams;
	var m_arrFilters = [];
	var m_arrFolders = [];
	var m_strFolderID = '';
	var m_strInboxFolderID = '';
	var m_strSystemFolderID = '';
    var m_strArchiveFolderID = '';

	var m_blnAppendMessages = false;

    
    
    ////
    var m_objGridLayout;
    var m_objGrid;
    var m_arrFixedFilter;
    var m_strTitle = 'Indox';

	// ====================================================================================
	// HELPERS ============================================================================

	function afterAjaxError()
	{
		setDirty(true);
	}

	function reloadForm()
	{
		
        os.element(m_strFormID, '.ge-form-title').html(m_strFormTitle);
        
        setGridLayout();
        populateForm();
		populateBreadCrumbs();
        
	}

	function initialiseForm()
	{
		os.element(m_strFormID, '.ge-appname-field').html(APP_NAME);
		//os.element(m_strFormID, '.ge-businessname-field').html(m_objAccount.businessname);

		// set folder variables
		var strFolderID = m_objParameters.folderid;		
		var m_strFirstFolderID = '';
        
        m_strFormTitle = '';
		m_strFolderID = '';
		m_strInboxFolderID = '';
		m_strSystemFolderID = '';

		// work out which folder we are using
		processArray(m_arrFolders, function (objFolder_a)
		{
			if (objFolder_a.id == strFolderID)
			{
				m_strFormTitle = objFolder_a.description;
				m_strFolderID = objFolder_a.id;
			}

			if (m_strFirstFolderID.length === 0)
			{
				m_strFirstFolderID = objFolder_a.id;
			}

			if (m_strFormTitle.length === 0)
			{
				m_strFormTitle = objFolder_a.description;
			}

			if (os.toBoolean(objFolder_a.is_inbox))
			{
				m_strInboxFolderID = objFolder_a.id;
			}

			if (os.toBoolean(objFolder_a.is_system))
			{
				m_strSystemFolderID = objFolder_a.id;
			}

            if (os.toBoolean(objFolder_a.is_archive))
			{
				m_strArchiveFolderID = objFolder_a.id;
			}
		}
		);

		// set our folder to the first one if none is chosen or found
		if (m_strFolderID.length === 0)
		{
			m_strFolderID = m_strInboxFolderID;

			if (m_strFolderID.length === 0)
			{
				m_strFolderID = m_strFirstFolderID;
			}
		}

		// render output
		var strBtnTabs = '';
        
        // close button        
        strBtnTabs += '<a class="ge-button gb-button btn btn-success ge-close-button">Close</a>';
        
		processArray(m_arrFolders, function (objFolder_a)
		{
			var strTab = getGUID();
			var strCurrentTab = '';

			if (objFolder_a.id == m_strFolderID)
			{
				strCurrentTab = ' btn-primary';
			}

			if (os.toBoolean(objFolder_a.is_inbox))
			{
				if (strCurrentTab.length > 0)
				{
					strBtnTabs += '<a data-folderid="' + htmlEncode(objFolder_a.id) + '" class="ge-button gb-button btn ge-tablink ' + strCurrentTab + '">' + htmlEncode(objFolder_a.description) + '&nbsp;<span class="ge-unread-badge badge gb-hidden"></span></a>';
				}
				else
				{
					strBtnTabs += '<a data-folderid="' + htmlEncode(objFolder_a.id) + '" class="ge-button gb-button btn btn-default ge-tablink ge-tablink-' + strTab + strCurrentTab + '">' + htmlEncode(objFolder_a.description) + '&nbsp;<span class="ge-unread-badge badge gb-hidden"></span></a>';
				}
			}
			else
			{
				if (strCurrentTab.length > 0)
				{
					strBtnTabs += '<a data-folderid="' + htmlEncode(objFolder_a.id) + '" class="ge-button gb-button btn ge-tablink' + strCurrentTab + '">' + htmlEncode(objFolder_a.description) + '</a>';
				}
				else
				{
					strBtnTabs += '<a data-folderid="' + htmlEncode(objFolder_a.id) + '" class="ge-button gb-button  btn btn-default ge-tablink ge-tablink-' + strTab + strCurrentTab + '">' + htmlEncode(objFolder_a.description) + '</a>';
				}
			}
		}
		);
		// for compose button
		//strBtnTabs += '<a href="#!msg.frmMyComposeMessage" class="btn btn-default">Compose</a>';

        strBtnTabs += '<a class="ge-button gb-button btn btn-default ge-compose-button">Compose</a>';

		os.element(m_strFormID, '.ge-messagefolder-buttongroup').html(strBtnTabs);
		os.element(m_strFormID, '.ge-form-title').html(m_strFormTitle);

        setGridLayout();

		populateBreadCrumbs();

		bindButtonEvent();
	}

	function setDirty(blnDirty_a)
	{
		m_blnFormDirty = blnDirty_a;

		if (m_blnReadonly)
		{
			//m_objDock.disableButtons('SaveButton', 'gs-darkblue-background-colour gs-glow-focusborder gs-red-background-colour gs-glow-dirty');
			doNothing();
		}
		else
		{
			if (m_blnFormDirty)
			{
				//m_objDock.disableButtons('SaveButton', 'gs-darkblue-background-colour');
				//m_objDock.enableButtons('SaveButton', 'gs-red-background-colour gs-glow-focusborder gs-glow-dirty');
				doNothing();
			}
			else
			{
				//m_objDock.disableButtons('SaveButton', 'gs-red-background-colour gs-glow-dirty');
				//m_objDock.enableButtons('SaveButton', 'gs-darkblue-background-colour gs-glow-focusborder');
				doNothing();
			}
		}
	}

	function openViewMessage(strMsgID_a) {

		os.showForm('msg.frmMyMessage', 'id=' + encodeURIComponent(strMsgID_a) + '&folderid=' + encodeURIComponent(m_strFolderID));
    }

	// ====================================================================================
	// POPULATING =========================================================================

	function populateBreadCrumbs()
	{
		var arrCrumbs = [
			{
				caption : 'My Messages',
				url : '#!msg.frmMyMessages'
			},
			{
				caption : m_strFormTitle,
				url : ''
			}
		];

		renderBreadCrumbs(os, m_strFormID, '.ge-breadcrumbs', arrCrumbs);
	}

    
    function populateForm() {
        populateGrid();
    }
	
	// ====================================================================================
	// WEBSERVICE RETURNS =================================================================

	// after all asynchronous fetching we go to here
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


	function messageFoldersFetched(objResponse_a)
	{
		m_arrFolders = objResponse_a.result;

		initialiseForm();
		//fetchMessages();
        
        asyncDataIsFetched();
	}


	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function fetchMessageFolders()
	{
		var objJSON = os.ajaxRequestCreate("msg_messagefoldersfetch", []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, messageFoldersFetched, os.ajaxError, doNothing, true);
	}

        
    function setGridLayout() { 
        
		var strWS = '';

		if (m_strFolderID == m_strInboxFolderID)
		{
			strWS = 'msg_messageinboxlist';
		}
		else if (m_strFolderID == m_strSystemFolderID)
		{
			strWS = 'msg_messagesystemlist';
		}
        else if (m_strFolderID == m_strArchiveFolderID)
		{
			strWS = 'msg_messagearchivelist';
		}
		else
		{
			strWS = 'msg_messagesentlist';
		}
                
        m_objGridLayout =
		{
			id : 'messages_list',
			ws : strWS,
			params : [				
				{
					"name" : 'order',
					"value" : ""
				},                
				{
					"name" : 'filter',
					"value" : m_arrFilters
				},
                /*
				{
					"name" : 'fixedfilter',
					"value" : m_arrFixedFilter
				}, 
                */
				{
					"name" : 'offset',
					"value" : 0
				},
                { 
                    "name" : 'limit',
                    "value" : 0
                }
			],
			columns : 
                [

					{
						"id" : "fromclient",
						"field" : "fromclient",
						"name" : "Sender/Recipient",
						"sortable" : "Y"
					},
					{
						"id" : "subject",
						"field" : "subject",
						"name" : "Subject", // formerly Data Group Name
						"sortable" : "Y"
					},										
					{
						"id" : "message",
						"field" : "message",
						"name" : "Message",
						"sortable" : "N"
					},
                    {
						"id" : "sentdatetime",
						"field" : "sentdatetime",
						"name" : "Date/Time",
						"sortable" : "Y"
					},
					{
						"id" : "is_flagged",
						"field" : "is_flagged",
						"name" : "Flagged",
						"sortable" : "Y"
					}
				], //m_arrEntityHeaders,
			returns : ['id'],
			title : m_strTitle
		};
    }
    
    function populateGrid()
	{
        
        var objGridOptions =
		{
			cbOnSelection : m_objThis.Grid_onDblClick,
			cbOnSelectionChange : m_objThis.Grid_onSelectionChange,
            cbRowFormatter : rowFormatter,
			columns : m_objGridLayout.columns,
            filterType : 'normal',
            parameters : m_objGridLayout.params,
			extraParameters : [],
			multiSelect : false,
			//returnFields : arrFields,
			webServiceFunction : m_objGridLayout.ws            
		};
        
        //objGridOptions = applyFlags(objGridOptions);
        
        m_objGrid = new jDatatableRenderer(os, objGridOptions);
        
        m_objGrid.render(m_strFormID, '.ge-entity-grid');
                
	}

	
	function fetchData(blnFetchAccount_a)
	{
		if (blnFetchAccount_a)
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;

			fetchMessageFolders();
			//fetchMessages();

		}
		else
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;

			populateForm();
		}
	}
    
    
    // callback function to format row. This time, to add a class to mark the message as unread.
    
    function rowFormatter(objRow_a, objData_a, intIndex_a) { 
        
        if(objData_a.isunread === 'Y') { 
            $(objRow_a).addClass('gb-DT-unread');
        }
        
        
        if ((m_strFolderID === m_strInboxFolderID) || (m_strFolderID === m_strSystemFolderID))
        {
            //assuming the first column is the Sender/Recipient
            $(objRow_a).find('td:first-child').html(objData_a.fromuser);
        }
        else
        {
            $(objRow_a).find('td:first-child').html(objData_a.touser);
        }
        
    }

	// ====================================================================================
	// BINDINGS ===========================================================================


	function bindGlobals()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'gb-form,ge-row-thread');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
	}

	function bindButtonEvent()
	{
		//unbind
		os.unbindEvents(m_strFormID, 'ge-compose-button,ge-showmore-button,ge-filtermsglist-option,ge-tablink,ge-search-input,ge-close-button');
		os.unbindEvents(m_strFormID, 'ge-search-input,ge-searchclear-button');

		// bindings
        os.bindEvent(m_objThis, m_strFormID, '.ge-compose-button', 'btnComposeButton', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-showmore-button', 'btnShowMore', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-filtermsglist-option', 'dropdownFilterMsgListOption', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tablink', 'btnTabLink', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-close-button', 'ButtonClose', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-searchclear-button', 'btnSearchClear', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-search-input', 'SearchField', 'onEnterKey');
        
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return false;
	};

	this.Form_canClose = function ()
	{
		return true;
	};

	this.Form_getDesktopRegion = function()
	{
		return "C";
	};
	
	this.Form_isDirty = function ()
	{
		return m_blnFormDirty;
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

	this.Form_onFocus = function (objParameters_a)
	{
		setTabOrder();

		if ((objParameters_a.folderid !== undefined) && (m_strFolderID.length > 0) && (m_strFolderID !== objParameters_a.folderid))
		{
			m_objParameters = objParameters_a;
			m_strFolderID = '';
		}

		m_intOffset = 0;
		m_arrFilters = [];
		//fetchData(true);
	};

	this.Form_onLoad = function ()
	{
		m_blnReadonly = false; // note: this should be based on whether we are looking at our own profile or another
		bindGlobals();

		// resize the form
		//var intViewPortWidth = os.getViewPort().width;
		//var intViewPortHeight = os.getViewPort().height;
		//os.resizeForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);

		fetchData(true);
	};

	this.Form_onPermissionCheck = function ()
	{
		return true;
	};

	this.FormTitle_onClick = function ()
	{
		os.formToFront(m_strFormID);
	};

	this.Form_onResize = function(intWidth_a, intHeight_a)
	{
	};

	// ====================================================================================
	// OTHER EVENTS =======================================================================

    this.btnComposeButton_onClick = function() {

        //os.closeForm(m_strFormID);
		os.showForm('msg.frmMyComposeMessage');

    };

	this.btnSearchClear_onClick = function ()
	{
		os.element(m_strFormID, '.ge-search-input').val("");
		os.element(m_strFormID, '.ge-search-input').focus();
		
        setMessageFilters();
        
		m_clearFormList = true;
		m_intFetched = 0;
		m_intOffset = 0;
	};
    
    this.SearchField_onEnterKey = function() {
        setMessageFilters();
        
		m_clearFormList = true;
		m_intFetched = 0;
		m_intOffset = 0;
    };
    
    function setMessageFilters() { 
        
        var strTerm = $.trim(os.element(m_strFormID, '.ge-search-input').val());
        var strFilterMsgStatus = os.element(m_strFormID, '.ge-filtermsg-value').val();
        
        var arrFilters = [
			{
				field : 'subject',
				value : strTerm
			}
		];
        
        if(strFilterMsgStatus === 'flagged' || strFilterMsgStatus === 'unflagged') {

            var strIsFlagged = 'N';

            if(strFilterMsgStatus === 'flagged') {
               strIsFlagged = 'Y';
            }

            arrFilters.push( {
                    field : 'is_flagged',
                    value : strIsFlagged
                });
                
            
        }
        else if(strFilterMsgStatus !== '') {

            arrFilters.push(
                {
                    field : 'is_unread',
                    value : strFilterMsgStatus
                }
            );
        }
        		                		
                                     
       m_objGrid.search(strTerm, [ { 'name' : 'filter', 'value' : arrFilters } ]);      
    }
    
	this.dropdownFilterMsgListOption_onClick = function (objThis_a)
	{

		var osObjThis = os.element(objThis_a);
        
        var strFilterMsgStatus = osObjThis.attr('data-optionvalue');
        
        os.element(m_strFormID, '.ge-filtermsg-value').val(strFilterMsgStatus);
        
		setMessageFilters();

		m_clearFormList = true;
		m_intFetched = 0;
		m_intOffset = 0;
		//fetchData(true);
		//fetchMessages();

		osObjThis.parents('.ge-filtermsglist-dropdown').find('.dropdown-filtermsglist-buttonlabel').text(osObjThis.attr('data-label'));
        
        //setGridLayout();
        //populateForm();

	};

	this.btnTabLink_onClick = function (objThis_a)
	{
        var strFolderID = htmlDecode(os.element(objThis_a).attr('data-folderid'));
        var strFormTitle = htmlDecode(os.element(objThis_a).html());
        var blnFetchMessages = false;

		m_strFolderID = strFolderID;
		m_strFormTitle = strFormTitle;
		blnFetchMessages = true;

		os.element(m_strFormID, '.ge-search-input').val('');
		m_arrFilters = [];

		// unset primary tab
		os.element(m_strFormID, '.ge-tablink.btn-primary').removeClass('btn-primary').addClass('btn-default');

		os.element(objThis_a).addClass('btn-primary').removeClass('btn-default');

        if(blnFetchMessages) {
            reloadForm();
        }

	};

    this.MessageRow_onClickxxxx = function(objThis_a) {

        var strMsgID =  os.element(objThis_a).attr('data-msgid');

        // mark the row read
        os.element(objThis_a).removeClass('wr-message-unread').addClass('wr-message-read');

        openViewMessage(strMsgID);
    };
    
    this.ButtonClose_onClick = function() { 
        m_objThis.FormClose_onClick();
    };
    
    this.Grid_onDblClick = function (arrSelection_a)
	{
        doNothing();

	};
    
    this.Grid_onSelectionChange = function (arrSelection_a)
	{
        
       if(arrSelection_a.length > 0) {
            openViewMessage(arrSelection_a[0].id);
        }
				
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'gb-tab-star,ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		//os.element(m_strFormID, '.CloseButton').focus();
	};

	this.TabStart_onFocus = function ()
	{
		//os.element(m_strFormID, '.ge-businessname-field').focus();
	};
}
