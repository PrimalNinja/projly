/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
function msg_frmMyComposeMessage(objOS_a, strFormID_a, objParameters_a)
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

	var m_strFormFields = 'ge-recipient-field,ge-subject-field,ge-message-field';

	// ------------------------------------------------------------------------------------

	var m_strMessageID = null;
	var m_objMessage = null;

	//var m_arrFolders = [];
	var m_strFolderID = '';
	var m_strInboxFolderID = '';

    
    
    // ------------------------------------------------------------------------------------
    
    var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : [
				['CloseButton', 'SendButton', 'BackButton']
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
			id : 'SendButton',
			caption : 'Send',
			classes : '',
			permissions : [],
			action : function ()
			{
				m_objThis.SendButton_onClick();
			},
			actionData : null,
			tip : 'Click here to send.',
			type : 'toolbarbutton'
		}
    ];
    
	// ====================================================================================
	// HELPERS ============================================================================

	function afterAjaxError()
	{
		setDirty(true);
	}

    /**
	 * try to send the message. but validate first
	 * @returns {void}
	 */
	function attemptSendMessage()
	{

		var strErrors = validateComposeMessageForm();

		if (strErrors.length > 0)
		{
			// have an error, so display it
			os.dialogAlertScroll(strErrors, function ()  {}

			);
		}
		else
		{

			m_objMessage =
			{
				subject : os.element(m_strFormID, '.ge-subject-field').val(),
				recipient : os.element(m_strFormID, '.ge-recipient-field').val(),
				recipient_id : os.element(m_strFormID, '.ge-recipientid-field').val(),
				message : os.element(m_strFormID, '.ge-message-field').val()
			};

			setDirty(true);
			sendMessage();
		}
	}
    
	function resetForm()
	{
		m_objMessage =
		{
			id : '',
			sender_id : '',
			sender : '',
			recipient_id : '',
			recipient : '',
			subject : '',
			message : ''
		};
	}

	function setDirty(blnDirty_a)
	{
		m_blnFormDirty = blnDirty_a;

		if (m_blnReadonly)
		{
			m_objDock.disableButtons('SendButton', 'gs-red-background-colour');
			//doNothing();
		}
		else
		{
			if (m_blnFormDirty)
			{
                m_objDock.enableButtons('SendButton', 'gs-red-background-colour');
				//doNothing();
			}
			else
			{
				m_objDock.disableButtons('SendButton', 'gs-red-background-colour');
				//doNothing();
			}
		}
	}

	/**
	 * init recipient field predictive text
	 * @returns {void}
	 */
	function initFieldAutocomplete()
	{
		os.element(m_strFormID, '.ge-recipient-field').autocomplete(
		{
			source : function (objRequest_a, cbResponse_a)
			{

				function recipientFetched(objData_a)
				{
					var objResponse = [];

					var intI = 0;

					processArray(objData_a, function (objElement_a)
					{
						objResponse[intI] =
						{
							"value" : objElement_a.recipient,
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

				var objJSON = os.ajaxRequestCreate('msg_messagerecipientsfetch',
						[
							{
								"name" : 'recipient',
								"value" : objRequest_a.term
							}
						]);
				os.ajaxCall(URL_WEBSERVICE, objJSON, recipientFetched, ajaxError);
			},

			select : function (objEvent_a, objSelection_a)
			{
				os.element(m_strFormID, '.ge-recipientid-field').val(objSelection_a.item.id);
				os.element(m_strFormID, '.ge-recipient-field').focus();
			}
		}
		);

		os.element(m_strFormID, '.ge-recipient-field').focus();
	}
    
    function validateComposeMessageForm()
	{
		var strError = '';

		if ($.trim(os.element(m_strFormID, '.ge-recipient-field').val()).length === 0)
		{
			strError += '<li>Recipient is required.</li>';
		}

		if ($.trim(os.element(m_strFormID, '.ge-subject-field').val()).length === 0)
		{
			strError += '<li>Subject is required.</li>';
		}

		if ($.trim(os.element(m_strFormID, '.ge-message-field').val()).length === 0)
		{
			strError += '<li>Message is required.</li>';
		}

		if (strError.length > 0)
		{
			strError = '<ul>' + strError + '</ul>';
		}

		return strError;
	}

	// ============POPULATING =============================================================

	function populateBreadCrumbs()
	{
		var arrCrumbs = [
			{
				caption : 'My Home',
				url : '#bbp.frmHome'
			},
			{
				caption : 'My Messages',
				url : '#!msg.frmMyMessages'
			},
			{
				caption : 'Compose Message',
				url : ''
			}
		];

		renderBreadCrumbs(os, m_strFormID, '.ge-breadcrumbs', arrCrumbs);
	}
    
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
	
	
// ============WEBSERVICE RETURNS =====================================================

	function messageSent(objResponse_a)
	{
		m_strMessageID = objResponse_a[0].id;
		os.broadcast(m_strFormID, 'msg', 'Your Message has been Sent');
		setDirty(false);

		//fetchData(true);

		os.dialogAlert("Message Sent", doNothing);
		os.closeForm(m_strFormID);
		//os.showForm('msg.frmMyMessages');
        
		os.showForm('msg.frmMyMessages');
	}

	

	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function sendMessage()
	{

		var objJSON = os.ajaxRequestCreate('msg_messagesend',
				[
					{
						"name" : "message",
						"value" : m_objMessage
					}
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, messageSent, os.ajaxError, afterAjaxError);
	}

	// ====================================================================================
	// BINDINGS ===========================================================================

    function bindFunctionEvents() { 
        
        // unbindings
		os.unbindEvents(m_strFormID, m_strFormFields);

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
		os.unbindEvents(m_strFormID, 'gb-form,ge-row-thread,ge-close-button');
 
		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
		//os.bindEvent(m_objThis, m_strFormID, '.ge-send-button', 'SendButton', 'onClick');
        //os.bindEvent(m_objThis, m_strFormID, '.ge-back-button', 'BackButton', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');

        os.bindEvent(m_objThis, m_strFormID, '.ge-close-button', 'ButtonClose', 'onClick');
	}

	// ====================================================================================
	// FORM EVENTS ========================================================================

	this.Form_allowMultipleInstances = function ()
	{
		return true;
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

	this.Form_onFocus = function ()
	{
		setTabOrder();
	};

	this.Form_onLoad = function ()
	{
		m_blnReadonly = false; // note: this should be based on whether we are looking at our own profile or another

		// resize the form
		var intViewPortWidth = os.getViewPort().width;
		var intViewPortHeight = os.getViewPort().height;
		os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);

		bindGlobals();
		populateBreadCrumbs();
        populateDock();
		resetForm();
		//fetchData(true);
        
        bindFunctionEvents();
        
		initFieldAutocomplete();
        
        setDirty(false);
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
		var intHeight = os.getFormCanvasHeight(m_strFormID) - 80;

		os.element(m_strFormID, '.ge-content-panel').height((intHeight) + 'px');
		os.element(m_strFormID, '.ge-thecontent').height('100%');
	};

    this.FormClose_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};

	// ====================================================================================
	// OTHER EVENTS =======================================================================

	this.SendButton_onClick = function ()
	{
		attemptSendMessage();
	};

    this.BackButton_onClick = function ()
	{
		os.closeForm(m_strFormID);
	};
    
    this.ButtonClose_onClick = function() { 
        m_objThis.FormClose_onClick();
    };

	// ====================================================================================
	// TABBING ============================================================================

	function setTabOrder()
	{
		os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
		os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

		os.setTabOrder(m_strFormID, 'ge-tab-start,' + m_strFormFields + ',ge-tab-end');
	}

	this.TabEnd_onFocus = function ()
	{
		os.element(m_strFormID, '.ge-inbox-link').focus();
	};

	this.TabStart_onFocus = function ()
	{
		os.element(m_strFormID, '.gb-send-button').focus();
	};
}