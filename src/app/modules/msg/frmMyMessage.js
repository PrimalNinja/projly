/*jsl:option explicit*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
function msg_frmMyMessage(objOS_a, strFormID_a, objParameters_a)
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
	var m_strFormTitle = 'My Message';

	var m_arrFolders = [];
	var m_strFolderID = '';
	var m_strInboxFolderID = '';
	var m_strSystemFolderID = '';
    var m_strArchiveFolderID = '';

	var m_strMessageID = m_objParameters.id;
    
	var m_objMessage = null;


	// ====================================================================================
    
    /*
        os.bindEvent(m_objThis, m_strFormID, '.ge-back-button', 'buttonBack', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-reply-button', 'buttonReply', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-send-button', 'buttonSend', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-archive-button', 'buttonArchive', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-flag-button', 'buttonFlag', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-close-button', 'ButtonClose', 'onClick');
        */
       
    var m_objDock;
	var m_arrMap = [
		{
			location : 'root',
			title : 'Home',
			layout : []
		}
	];
            
    var m_arrOperations = {
        'view' : ['CloseButton', 'ReplyButton', 'FlagButton', 'ArchiveButton'],
        'reply' : ['CloseButton', 'SendButton']
    };
    
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
			id : 'ReplyButton',
			caption : 'Reply',
			classes : '',
			permissions : [],
			action : function ()
			{
				m_objThis.ButtonReply_onClick();
			},
			actionData : null,
			tip : 'Click here to reply.',
			type : 'toolbarbutton'
		},
        
        {
			id : 'FlagButton',
			caption : 'Flag',
			classes : 'ge-flag-button',
			permissions : [],
			action : function ()
			{
				m_objThis.ButtonFlag_onClick();
			},
			actionData : null,
			tip : 'Click here to flag.',
			type : 'toolbarbutton'
		},
        
        {
			id : 'ArchiveButton',
			caption : 'Archive',
			classes : 'ge-archive-button',
			permissions : [],
			action : function ()
			{
				m_objThis.ButtonArchive_onClick();
			},
			actionData : null,
			tip : 'Click here to Archive.',
			type : 'toolbarbutton'
		},
        {
			id : 'SendButton',
			caption : 'Send',
			classes : 'gb-cell-disabled',
			permissions : [],
			action : function ()
			{
				m_objThis.ButtonSend_onClick();
			},
			actionData : null,
			tip : 'Click here to reply.',
			type : 'toolbarbutton'
		}
    ];
    
    // HELPERS ============================================================================

	function afterAjaxError()
	{
		setDirty(true);
	}

	function initialiseForm()
	{

		
		// set folder variables
		var strFolderID = m_objParameters.folderid;
		m_strFormTitle = '';
		m_strFolderID = '';
		var m_strFirstFolderID = '';
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
        
        os.element(m_strFormID, '.ge-form-title').html(m_strFormTitle);
		bindButtonEvent();
	}

	function setDirty(blnDirty_a)
	{
		m_blnFormDirty = blnDirty_a;

		if (m_blnReadonly)
		{
			m_objDock.disableButtons('SendButton', 'gs-darkblue-background-colour gs-glow-focusborder gs-red-background-colour gs-glow-dirty');
			doNothing();
		}
		else
		{
			if (m_blnFormDirty)
			{
				m_objDock.disableButtons('SendButton', 'gs-darkblue-background-colour');
				m_objDock.enableButtons('SendButton', 'gs-red-background-colour gs-glow-focusborder gs-glow-dirty');
				doNothing();
			}
			else
			{
				m_objDock.disableButtons('SendButton', 'gs-red-background-colour gs-glow-dirty');
				m_objDock.enableButtons('SendButton', 'gs-darkblue-background-colour gs-glow-focusborder');
				doNothing();
			}
		}
	}

	function updateUnreadMessageStatus()
	{
		if (os.toBoolean(m_objMessage.isunread))
		{
			setMessageUnreadStatus('N');
		}
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

			var strRecipientID = m_objMessage.sender_id;

			m_objMessage =
			{
				subject : os.element(m_strFormID, '.ge-subject-field').val(),
				recipient : os.element(m_strFormID, '.ge-recipient-field').val(),
				recipient_id : strRecipientID,
				message : os.element(m_strFormID, '.ge-message-field').val()
			};

			setDirty(true);
			sendMessage();
		}
	}

    function toggleArchiveButton(blnMsgIsArchived) {

        // if message has been archived. show 'Unarchive' button instead
        if(blnMsgIsArchived) {
            os.element(m_strFormID, '.ge-archive-button').text('Unarchive');
        }
        else {
            os.element(m_strFormID, '.ge-archive-button').text("Archive");
        }

        /*
		if ((m_strFolderID == m_strInboxFolderID) || (m_strFolderID == m_strArchiveFolderID))
		{
			os.element(m_strFormID, '.ge-archive-button').show();
		}
		else
		{
			os.element(m_strFormID, '.ge-archive-button').hide();
		}
        */
    }

    function toggleFlagButton(blnMsgIsFlagged) {

        // if message has been archived. show 'Unflag' button instead
        if(blnMsgIsFlagged) {
            os.element(m_strFormID, '.ge-flag-button').text('Unflag');
        }
        else {
            os.element(m_strFormID, '.ge-flag-button').text("Flag");
        }

        //os.element(m_strFormID, '.ge-flag-button').show();
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

	// ====================================================================================
	// POPULATING =========================================================================

	function populateBreadCrumbs()
	{
		var arrCrumbs = [
			{
				caption : 'My Home',
				url : '#bbp.frmHome'
			},
			{
				caption : "My Messages",
				url : '#msg.frmMyMessages'
			},
			{
				caption : m_strFormTitle,
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

	function populateForm()
	{
		populateBreadCrumbs();
		initialiseForm();
		populateMessage();
		updateUnreadMessageStatus();

        toggleFlagButton( os.toBoolean(m_objMessage.isflagged) );
        toggleArchiveButton( os.toBoolean(m_objMessage.isarchived) );
        
        bindFunctionEvents();
        
        os.resizeDockedForm(m_strFormID);
	}

	function populateMessage()
	{
		os.element(m_strFormID, '.ge-label-subject').html(m_objMessage.subject);
		os.element(m_strFormID, '.ge-label-recipient').html(m_objMessage.recipient);
		os.element(m_strFormID, '.ge-label-sender').html(m_objMessage.sender);
		os.element(m_strFormID, '.ge-label-sentdatetime').html(m_objMessage.sentdatetime);
		os.element(m_strFormID, '.ge-label-message').html(m_objMessage.message);
	}

	function populateReplyForm()
	{
                        
        var strSubject = '';
        
        if(m_objMessage.subject.indexOf('Re') < 0)
        {
            strSubject = 'Re: ' + m_objMessage.subject;
        }
        else
        {
            strSubject = m_objMessage.subject;
        }

		os.element(m_strFormID, '.ge-label-subject').html(htmlEncode(strSubject));

		os.element(m_strFormID, '.ge-subject-field').val(strSubject);

                /*
                 * the sender will be the recipient now
                */
		os.element(m_strFormID, '.ge-recipient-field').val(m_objMessage.sender);
        
        os.element(m_strFormID, '.ge-form-title').html("Reply");
        
        m_arrMap[0].layout = [m_arrOperations.reply];
        
        populateDock();
        
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

    function messageArchived(objResponse_a) {


        toggleArchiveButton( os.toBoolean(m_objMessage.isarchived) );

    }

    function messageFlagged(objResponse_a) {


        toggleFlagButton( os.toBoolean(m_objMessage.isflagged) );

    }


	function messageFetched(objResponse_a)
	{
		m_objMessage = objResponse_a[0];

		asyncDataIsFetched();

	}

	function messageFoldersFetched(objResponse_a)
	{
		m_arrFolders = objResponse_a.result;

		initialiseForm();

		fetchMessage();
	}

	function messageSent(objResponse_a)
	{
		m_strMessageID = objResponse_a[0].id;
		os.broadcast(m_strFormID, 'msg', 'Your Message has been Sent');
		setDirty(false);

		//fetchData(true);

		os.dialogAlertScroll("Message Sent", doNothing);
		os.closeForm(m_strFormID);
		os.showForm('msg.frmMyMessages');
	}


	// ====================================================================================
	// WEBSERVICE CALLS ===================================================================

	function fetchMessageFolders()
	{
		var objJSON = os.ajaxRequestCreate("msg_messagefoldersfetch", []);
		os.ajaxCall(URL_WEBSERVICE, objJSON, messageFoldersFetched, os.ajaxError, doNothing, true);
	}

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

	function fetchMessage()
	{
		var objJSON = os.ajaxRequestCreate('msg_messagefetch', [
					{
						name : 'id',
						value : m_strMessageID
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, messageFetched, os.ajaxError, doNothing, true);
	}

	function fetchData(blnFetchAccount_a)
	{
		if (blnFetchAccount_a)
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;
			fetchMessageFolders();

		}
		else
		{
			m_intToFetch = 1;
			m_intFetched = 0;
			m_intErrors = 0;

			populateForm();
		}
	}

	function setMessageUnreadStatus(strStatus_a)
	{
		var objJSON = os.ajaxRequestCreate('msg_messageupdatestatus', [
					{
						name : 'params',
						value :
						{
							id : m_strMessageID,
							'status' : strStatus_a
						}
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, doNothing, os.ajaxError, doNothing, true);
	}

    function archiveMessage() {

        var objJSON = os.ajaxRequestCreate('msg_messagearchiveset', [
					{
						name : 'id',
						value :m_strMessageID
					},
                    {
                        name  : 'isarchived',
                        value : m_objMessage.isarchived
                    }
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, messageArchived, os.ajaxError, doNothing, true);

    }

    function flagMessage() {

        var objJSON = os.ajaxRequestCreate('msg_messageflagset', [
					{
						name : 'id',
						value :m_strMessageID
					},
                    {
                        name  : 'isflagged',
                        value : m_objMessage.isflagged
                    }
				]);

		os.ajaxCall(URL_WEBSERVICE, objJSON, messageFlagged, os.ajaxError, doNothing, true);

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
		os.unbindEvents(m_strFormID, 'gb-form,ge-row-thread');

		// bindings
		os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
		//os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
        os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');

	}

	function bindButtonEvent()
	{
		// unbindings
		os.unbindEvents(m_strFormID, 'ge-send-button,ge-close-button');
        
        os.bindEvent(m_objThis, m_strFormID, '.ge-send-button', 'ButtonSend', 'onClick');
/*
		os.bindEvent(m_objThis, m_strFormID, '.ge-back-button', 'buttonBack', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-reply-button', 'buttonReply', 'onClick');
		os.bindEvent(m_objThis, m_strFormID, '.ge-send-button', 'buttonSend', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-archive-button', 'buttonArchive', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-flag-button', 'buttonFlag', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.ge-close-button', 'ButtonClose', 'onClick'); */
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

	//this.Form_onDblClick = function ()
	//{
		//os.formToFront(m_strFormID);
	//};

	this.Form_onFocus = function (objParameters_a)
	{
		setTabOrder();
        var strID = objParameters_a.id;
        
        
        if( strID !== m_strMessageID) { 
            
            m_strMessageID = strID;
            
            fetchData(true);
        }
	};

	this.Form_onLoad = function ()
	{
		m_blnReadonly = false; // note: this should be based on whether we are looking at our own profile or another
        
		// resize the form
        /*
		var intViewPortWidth = os.getViewPort().width;
		var intViewPortHeight = os.getViewPort().height;
		os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);
        */
        
        m_arrMap[0].layout = [m_arrOperations.view];
        
        populateDock();
        
		bindGlobals();
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

	this.ButtonBack_onClick = function ()
	{
		//this prevents cached issue.
		// need to close the form. so that the next the
		// the form is open, onLoad is sure to get fire.
		os.closeForm(m_strFormID);
	};

	this.ButtonReply_onClick = function ()
	{

		populateReplyForm();

		os.element(m_strFormID, '.ge-viewform-panel').hide();
		os.element(m_strFormID, '.ge-replyform-panel').show();

	};

	this.ButtonSend_onClick = function ()
	{
		attemptSendMessage();
	};

    this.ButtonArchive_onClick = function ()
	{
        if( os.toBoolean(m_objMessage.isarchived) ) {
            m_objMessage.isarchived = 'N';
        }
        else {
            m_objMessage.isarchived = 'Y';
        }

        archiveMessage();
	};

    this.ButtonFlag_onClick = function ()
	{
        if( os.toBoolean(m_objMessage.isflagged) ) {
            m_objMessage.isflagged = 'N';
        }
        else {
            m_objMessage.isflagged = 'Y';
        }

        flagMessage();
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