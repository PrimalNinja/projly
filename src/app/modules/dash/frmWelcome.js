/*jsl:option explicit*/
/*jsl:import ..\..\inc-osutils-jdock.js*/
/*jsl:import ..\..\inc-osutils.js*/
/*jsl:import ..\..\inc-os.js*/
/*jsl:import ..\dash\wgtChart.js*/
function dash_frmWelcome(objOS_a, strFormID_a, objParameters_a)
{
    var os = objOS_a;
    var m_objThis = this;
    var m_strFormID = strFormID_a;
    var m_objParameters = objParameters_a;

    // ------------------------------------------------------------------------------------

    var m_blnIsBatchClient = os.toBoolean(os.getProperty('batch'));
    var m_blnIsDefaultClient = os.toBoolean(os.getProperty('default'));
    var m_blnIsEmployer = os.toBoolean(os.getProperty('isemployer'));
    var m_blnIsIndividual = os.toBoolean(os.getProperty('isindividual'));
    var m_blnIsOwnerClient = os.toBoolean(os.getProperty('owner'));
    var m_blnIsPublic = os.toBoolean(os.getProperty('public'));
    var m_blnIsDeveloper = os.toBoolean(os.getProperty('developer'));
    var m_blnIsSysAdmin = os.toBoolean(os.getProperty('sysadmin'));

    var m_blnDisplayWelcome = !(m_blnIsBatchClient || m_blnIsDefaultClient || m_blnIsDeveloper || m_blnIsSysAdmin);

	var m_blnEnableBranches = os.toBoolean(os.getProperty('enablebranches'));
	var m_strBranchID = "";
	var m_strBranchName = "";
	if (m_blnEnableBranches)
	{
		m_strBranchID = os.getProperty('branchid');
		m_strBranchName = os.getProperty('branchname');
	}

    var m_blnIsMobile = os.hasCapability('mobile');

    // ------------------------------------------------------------------------------------

    var m_intVerticalChartCount = 2;
    var m_intVerticalChartHeight = 500;
    
    var m_intToFetch = 0;
    var m_intFetched = 0;
    var m_intErrors = 0;

    var m_arrCharts = [];

    var m_objWgtChart1;
    var m_objWgtChart2;
    var m_objWgtChart3;
    var m_objWgtChart4;

    // ------------------------------------------------------------------------------------

    //if (!os.isMDI())
    //{
            //os.element(m_strFormID, '.gb-form-close').removeClass('gi-form-close');
    //}

    var m_blnForceVerticalScroll = ((TESTSCROLL == 'TRUE')  && !os.hasCapability('regionscroll') && os.hasCapability('mobile'));
    if (m_blnForceVerticalScroll)
    {        
        os.element(m_strFormID, '.ge-content-panel').removeClass('gb-scrollable-panel');
        os.element(m_strFormID, '.ge-thecontent').removeClass('gb-scrollable-content');
        os.element(m_strFormID, '.gb-form').removeClass('gb-resizable');

        os.element(m_strFormID).css( { 'position' : 'relative', 'height' : 'auto' });                    
    }
    
    var m_objDock;
    var m_arrMap = [
        {
            location : 'root',
            title : 'Home',
            layout : [
                ['SettingsButton','AccountButton','PrintingButton']
            ]
        },
        {
            location : 'settings',
            title : 'Settings',
            layout : [
                ['SettingsMyDeviceButton','SettingsMyDevicesButton','SettingsMyPasswordButton','SettingsUserPreferencesButton','SettingsMyUserButton','SettingsMyClientsButton','BackButton']
            ]
        },
        {
            location : 'account',
            title : 'Settings',
            layout : [
                ['SettingsMyAccountButton','SettingsMyClientButton','SettingsMyProductsButton','SettingsViewCartButton','BackButton']
            ]
        },
        {
            location : 'printing',
            title : 'Printing',
            layout : [
                ['PrintingPrintJobsButton','PrintingPrintJobArchiveButton','PrintingMyDeviceButton','PrintingMyDevicesButton','PrintingPrintersMineButton','PrintingPrintersPublicButton','PrintingPrinterQueuesMineButton','PrintingPrinterQueuesPublicButton','PrintingApplicationHealthButton','BackButton']
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
        // toolbar top navigation tiles
        {
            id : 'BackButton',
            caption : '< Back',
            classes : '',
            style: '',
            permissions : [],
            action : 'navigate',
            actionData : 'root',
            tip : 'Click here to go back.',
            type : 'linkitem'
        },
        {
            id : 'SettingsButton',
            caption : 'Settings >',
            classes : '',
            style: '',
            permissions : [],
            action : 'navigate',
            actionData : 'settings',
            tip : 'Click here for easy configuration options.',
            type : 'linkitem'
        },
        {
            id : 'AccountButton',
            caption : 'Account >',
            classes : '',
            style: '',
            permissions : [],
            action : 'navigate',
            actionData : 'account',
            tip : 'Click here for account options.',
            type : 'linkitem'
        },
        {
            id : 'PrintingButton',
            caption : 'Printing and Devices >',
            classes : '',
            style: '',
            permissions : ['VW_PRINTER','VW_PRINTERSTATUS','VW_PRINTSTATUS'],
            action : 'navigate',
            actionData : 'printing',
            tip : 'Click here for easy printing options.',
            type : 'linkitem'
        },
        // settings
        {
            id : 'SettingsMyDeviceButton',
            caption : 'Edit My Current Device',
            classes : '',
            style: '',
            permissions : ['VW_DEVICE'],
            action : function()
            {
                    m_objThis.SettingsMyDevice_onClick();
            },
            actionData : null,
            tip : 'Click here to edit my current device.',
            type : 'linkitem'
        },
        {
            id : 'SettingsMyDevicesButton',
            caption : 'My Devices',
            classes : '',
            style: '',
            permissions : ['VW_DEVICE'],
            action : function()
            {
                    m_objThis.SettingsMyDevices_onClick();
            },
            actionData : null,
            tip : 'Click here to list my devices.',
            type : 'linkitem'
        },
        {
            id : 'SettingsUserPreferencesButton',
            caption : 'Change My User Preferences',
            classes : '',
            style: '',
            permissions : ['VW_USERSETTING'],
            action : function()
            {
                    m_objThis.SettingsUserPreferences_onClick();
            },
            actionData : null,
            tip : 'Click here to change my user preferences.',
            type : 'linkitem'
        },
        {
            id : 'SettingsMyPasswordButton',
            caption : 'Change My Password',
            classes : '',
            style: '',
            permissions : ['CHPWD_USER'],
            action : function()
            {
                    m_objThis.SettingsMyPassword_onClick();
            },
            actionData : null,
            tip : 'Click here to change my password.',
            type : 'linkitem'
        },
        {
            id : 'SettingsMyUserButton',
            caption : 'Edit My User',
            classes : '',
            style: '',
            permissions : ['VW_USER'],
            action : function()
            {
                    m_objThis.SettingsMyUser_onClick();
            },
            actionData : null,
            tip : 'Click here to edit my user.',
            type : 'linkitem'
        },
        {
            id : 'SettingsMyClientButton',
            caption : 'Edit Client Settings',
            classes : '',
            style: '',
            permissions : ['VW_CLIENT'],
            action : function()
            {
                    m_objThis.SettingsMyClient_onClick();
            },
            actionData : null,
            tip : 'Click here to edit client settings.',
            type : 'linkitem'
        },
        {
            id : 'SettingsMyAccountButton',
            caption : 'Edit Account',
            classes : '',
            style: '',
            permissions : ['VW_ACCOUNT'],
            action : function()
            {
                    m_objThis.SettingsMyAccount_onClick();
            },
            actionData : null,
            tip : 'Click here to edit account.',
            type : 'linkitem'
        },
        {
            id : 'SettingsMyProductsButton',
            caption : 'My Products',
            classes : '',
            style: '',
            permissions : ['xVW_CLIENTPRODUCT'],	// disabled for now
            action : function()
            {
                    m_objThis.SettingsMyProducts_onClick();
            },
            actionData : null,
            tip : 'Click here to list my products.',
            type : 'linkitem'
        },
        {
            id : 'SettingsViewCartButton',
            caption : 'View Cart',
            classes : '',
            style: '',
            permissions : ['VW_CART'],
            action : function()
            {
                    m_objThis.SettingsViewCart_onClick();
            },
            actionData : null,
            tip : 'Click here to view cart.',
            type : 'linkitem'
        },
        {
            id : 'SettingsMyClientsButton',
            caption : 'View My Clients',
            classes : '',
            style: '',
            permissions : ['VW_MYCLIENT'],
            action : function()
            {
                    m_objThis.SettingsMyClients_onClick();
            },
            actionData : null,
            tip : 'Click here to view my clients.',
            type : 'linkitem'
        },
        // printing
        {
            id : 'PrintingPrintJobsButton',
            caption : 'Print Jobs',
            classes : '',
            style: '',
            permissions : ['VW_PRINTJOB'],
            action : function()
            {
                    m_objThis.PrintingPrintJobs_onClick();
            },
            actionData : null,
            tip : 'Click here to list print jobs.',
            type : 'linkitem'
        },
        {
            id : 'PrintingPrintJobArchiveButton',
            caption : 'Archived Print Jobs',
            classes : '',
            style: '',
            permissions : ['VW_LOG_PRINTJOB'],
            action : function()
            {
                    m_objThis.PrintingPrintJobArchive_onClick();
            },
            actionData : null,
            tip : 'Click here to list archived print jobs.',
            type : 'linkitem'
        },
        {
            id : 'PrintingMyDeviceButton',
            caption : 'Edit My Current Device',
            classes : '',
            style: '',
            permissions : ['VW_DEVICE'],
            action : function()
            {
                    m_objThis.PrintingMyDevice_onClick();
            },
            actionData : null,
            tip : 'Click here to edit my current device.',
            type : 'linkitem'
        },
        {
            id : 'PrintingMyDevicesButton',
            caption : 'My Devices',
            classes : '',
            style: '',
            permissions : ['VW_DEVICE'],
            action : function()
            {
                    m_objThis.PrintingMyDevices_onClick();
            },
            actionData : null,
            tip : 'Click here to list my devices.',
            type : 'linkitem'
        },
        {
            id : 'PrintingPrintersMineButton',
            caption : 'My Printers',
            classes : '',
            style: '',
            permissions : ['VW_PRINTER'],
            action : function()
            {
                    m_objThis.PrintingPrintersMine_onClick();
            },
            actionData : null,
            tip : 'Click here to list my printers.',
            type : 'linkitem'
        },
        {
            id : 'PrintingPrintersPublicButton',
            caption : 'Public Printers',
            classes : '',
            style: '',
            permissions : ['VW_PRINTER'],
            action : function()
            {
                    m_objThis.PrintingPrintersPublic_onClick();
            },
            actionData : null,
            tip : 'Click here to list public printers.',
            type : 'linkitem'
        },
        {
            id : 'PrintingPrinterQueuesMineButton',
            caption : 'My Private Printer Queues',
            classes : '',
            style: '',
            permissions : ['VW_PRINTQUEUE'],
            action : function()
            {
                    m_objThis.PrintingPrinterQueuesMine_onClick();
            },
            actionData : null,
            tip : 'Click here to list my private printer queues.',
            type : 'linkitem'
        },
        {
            id : 'PrintingPrinterQueuesPublicButton',
            caption : 'Public Printer Queues',
            classes : '',
            style: '',
            permissions : ['VW_PRINTQUEUE'],
            action : function()
            {
                    m_objThis.PrintingPrinterQueuesPublic_onClick();
            },
            actionData : null,
            tip : 'Click here to list public printer queues.',
            type : 'linkitem'
        },
        {
            id : 'PrintingApplicationHealthButton',
            caption : 'Check Application Health',
            classes : '',
            style: '',
            permissions : ['xVW_PRINTER'],	// disabled for now
            action : function()
            {
                    m_objThis.PrintingApplicationHealth_onClick();
            },
            actionData : null,
            tip : 'Click here to check application health.',
            type : 'linkitem'
        }
    ];

    // ====================================================================================
    // HELPERS ============================================================================

    function closeMe()
    {
        os.closeForm(m_strFormID);
    }

    function initialiseForm()
    {
        // defaulting
        var strFormTitle = m_objParameters.title;

        os.element(m_strFormID, '.ge-form-title').text(strFormTitle);
    }

    function initWidgets() 
	{		
        m_objWgtChart1 = new dash_wgtChart(os, m_strFormID, { chart: m_arrCharts['chart1'] });
        m_objWgtChart2 = new dash_wgtChart(os, m_strFormID, { chart: m_arrCharts['chart2'] });
        m_objWgtChart3 = new dash_wgtChart(os, m_strFormID, { chart: m_arrCharts['chart3'] });
        m_objWgtChart4 = new dash_wgtChart(os, m_strFormID, { chart: m_arrCharts['chart4'] });
    }

    function populateWidgets()
    {
        m_objWgtChart1.render(".ge-cell-1-1");
        m_objWgtChart2.render(".ge-cell-1-2");
        m_objWgtChart3.render(".ge-cell-2-1");
        m_objWgtChart4.render(".ge-cell-2-2");
    }

    function registerBroadcaster() 
    {
        //os.registerServerEvent('dashboard', m_strFormID);
        //os.enableServerEventQueue('dasboard');
        //os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerbroadcaster', [{ "name" : "eventqueue", "value" : "dashboard" }]), doNothing, doNothing);
        //os.ajaxCall(URL_WEBSERVICE, os.ajaxRequestCreate('esb_registerlistener', [{ "name" : "eventqueue", "value" : "dashboard" }]), doNothing, doNothing);
    }

    function asyncDataIsFetched()
    {
        m_intFetched++;
        if (m_intToFetch === m_intFetched)
        {
                populateForm();
        }
    }

    // ====================================================================================
    // POPULATING =========================================================================

    function populateForm()
    {
        populateDock();
        //os.element(m_strFormID, '.ge-as-default').show();

        if(m_blnForceVerticalScroll)
        {            
            os.element(m_strFormID, '.gb-form').addClass('gs-content-autoheight');                             
        }
        else
        {
            os.resizeDockedForm(m_strFormID);
        }

        initWidgets();
        populateWidgets();
        registerBroadcaster();
        initialiseForm();
    }

    function populateDock()
    {
        m_objDock = new jDock(os,
        {
                "map" : m_arrMap,
                "tiles" : m_arrTiles,
                "alwayshidemorebutton": true,
                "mode": "verticallinks"
        });

        m_objDock.render(m_strFormID, '.ge-button-panel');
    }

    // ====================================================================================
    // WEBSERVICE RETURNS =================================================================

    function chartsFetched(objResponse_a) 
    { 
        m_arrCharts = objResponse_a;

        asyncDataIsFetched();
    }

    // ====================================================================================
    // WEBSERVICE CALLS ===================================================================

    function fetchData(blnFetchData_a)
    {
        if (blnFetchData_a)
        {
            m_intToFetch = 1;
            m_intFetched = 0;
            m_intErrors = 0;

            //getCharts();
			populateForm();
        }
        else
        {
            m_intToFetch = 1;
            m_intFetched = 0;
            m_intErrors = 0;

            populateForm();
        }
    }

    function getCharts()
    {
        //var objJSON = os.ajaxRequestCreate('dash_fetchcharts',[]);
        //os.ajaxCall(URL_WEBSERVICE, objJSON, chartsFetched, os.ajaxError, doNothing, true);                
    }

    // ====================================================================================
    // BINDINGS ===========================================================================

    function bindGlobals()
    {
        // unbindings
        os.unbindEvents(m_strFormID, 'gb-form');
        os.unbindEvents(m_strFormID, 'gb-form-close');

        // bindings
        os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.gb-form-close', 'FormClose', 'onClick');
        os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Form', 'onDblClick');
        //os.bindEvent(m_objThis, m_strFormID, '.gb-formtitle-inner-panel', 'FormTitle', 'onClick');
    }

    // ====================================================================================
    // FORM EVENTS ========================================================================

    this.Form_allowMultipleInstances = function ()
    {
        return false;
    };

    this.Form_canClose = function ()
    {
        return true; //os.isMDI();
    };

    this.Form_onBroadcast = function (strQueue_a, strMessage_a)
    {
        if (((strQueue_a === 'orientation') || (strQueue_a === 'viewport')) && (strMessage_a === 'change'))
        {
            m_objThis.Form_onResize();
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
        m_objThis.Form_onResize();
    };

    this.Form_onLoad = function ()
    {
        if (!m_blnDisplayWelcome)
        {
            os.closeForm(m_strFormID);
        }
        else
        {
            //if (os.isMDI())
            //{
                    // resize the form
                    //var intViewPortWidth = os.getViewPort().width;
                    //var intViewPortHeight = os.getViewPort().height;
                    //os.resizeAndRepositionForm(m_strFormID, intViewPortHeight*0.1, intViewPortWidth*0.1, intViewPortHeight*0.8, intViewPortWidth*0.8);
                    //os.formToFront(m_strFormID, false);
            //}

            // bindings
            //populateForm();
            fetchData(true);
            bindGlobals();
            //populateForm();
            setTabOrder();
        }
    };

    this.Form_onPermissionCheck = function ()
    {
        return true;
    };

    this.Form_onResize = function (intWidth_a, intHeight_a)
    {
        var intHeight = os.getFormCanvasHeight(m_strFormID) - 20;

        if (m_blnForceVerticalScroll)
        {        
            doNothing();
        }        
        else
        {
            os.element(m_strFormID, '.ge-content-panel').height((intHeight) + 'px');
            os.element(m_strFormID, '.ge-thecontent').height('100%');
        }
    };

    this.FormClose_onClick = function ()
    {
        os.closeForm(m_strFormID);
    };

    // ====================================================================================
    // OTHER EVENTS =======================================================================

    this.SettingsMyDevice_onClick = function()
    {
        EditMyDevice();
    };

    this.SettingsMyDevices_onClick = function()
    {
        ListMyDevices();
    };

    this.SettingsDispatchPreferences_onClick = function()
    {
        os.showForm('entity.frmForm', { entity:'systemform', formentity:'DISPATCHSETTING', formcode:'DISPATCHSETTING', mode:'edit', title:'Client Despatch Preferences', relationship:'children', relativeid:'MYCLIENT', relative:'DISPATCHSETTING', id:'MYCLIENT' });
    };

    this.SettingsUserPreferences_onClick = function()
    {
        os.showForm('entity.frmForm', { entity:'systemform', formentity:'USERSETTING', formcode:'USERSETTING', mode:'edit', title:'My User Preferences', relationship:'children', relativeid:'MYSELF', relative:'USERSETTING', id:'MYSELF' });
    };

    this.SettingsMyPassword_onClick = function()
    {
        os.showForm('core.frmChangePassword', { mode:'editself' });
    };

    this.SettingsMyUser_onClick = function()
    {
        os.showForm('entity.frmForm', { entity:'systemform', formentity:'USER', formcode:'USER', mode:'edit', title:'My User', relationship:'children', relativeid:'MYSELF', relative:'USER', id:'MYSELF' });
    };

    this.SettingsMyClient_onClick = function()
    {
        os.showForm('entity.frmForm', { entity:'systemform', formentity:'CLIENTSETTING', formcode:'CLIENTSETTING', mode:'edit', title:'Client Settings', relationship:'children', relativeid:'MYCLIENT', relative:'CLIENTSETTING', id:'MYCLIENT' });
    };

    this.SettingsMyAccount_onClick = function()
    {
        os.showForm('entity.frmForm', { entity:'systemform', formentity:'ACCOUNT', formcode:'ACCOUNT', mode:'edit', title:'Account', relationship:'children', relativeid:'MYACCOUNT', relative:'ACCOUNT', id:'MYACCOUNT' });
    };

    this.SettingsMyProducts_onClick = function()
    {
        os.showForm('entity.frmLister', { type:'form', entity:'systemform', formentity:'CLIENTPRODUCT', formentitydescription:'My Products', formcode:'CLIENTPRODUCT', mode:'renderer', title:'My Products' /*, fixedfilter:[{"field":"is_public","value":"N"}]*/ });
    };

    this.SettingsViewCart_onClick = function()
    {
        os.showForm('core.frmPayment', {
                                title:'Purchase Subscription',
                                applicantname: os.getProperty('clientname'),
            mode : 'selectproduct',
                                producttypeheading: 'Subscriptions',
                                producttype: 'subscription',
                                filter: 'PRODUCT',
                                //checkout: true,
            //productid : '4',
                                multi:false
                        });
    };

    this.SettingsMyClients_onClick = function()
    {
        os.showForm('entity.frmLister', { type:'form', entity:'systemform', formentity:'MYCLIENT', formentitydescription:'My Client', formcode:'MYCLIENT', mode:'renderer', title:'My Clients' });
    };

    this.PrintingPrintJobs_onClick = function()
    {
        os.showForm('entity.frmLister', { type:'form', entity:'systemform', formentity:'PRINTJOB', formentitydescription:'Print Job', formcode:'PRINTJOB', mode:'renderer', title:'Print Jobs' });
    };

    this.PrintingPrintJobArchive_onClick = function()
    {
        os.showForm('entity.frmLister', { type:'form', entity:'systemform', formentity:'LOG_PRINTJOB', formentitydescription:'Print Job', formcode:'LOG_PRINTJOB', mode:'renderer', title:'Print Jobs (Archived)' });
    };

    this.PrintingMyDevice_onClick = function()
    {
        EditMyDevice();
    };

    this.PrintingMyDevices_onClick = function()
    {
        ListMyDevices();
    };

    this.PrintingPrintersMine_onClick = function()
    {
        os.showForm('entity.frmLister', { type:'form', entity:'systemform', formentity:'PRINTER', formentitydescription:'Printer', formcode:'PRINTER', mode:'renderer', title:'My Private Printers', fixedfilter:[{"field":"user_id","value":"MYSELF"},{"field":"is_public","value":"N"}] });
    };

    this.PrintingPrintersPublic_onClick = function()
    {
        os.showForm('entity.frmLister', { type:'form', entity:'systemform', formentity:'PRINTER', formentitydescription:'Printer', formcode:'PRINTER', mode:'renderer', title:'Public Printers', fixedfilter:[{"field":"is_public","value":"Y"}] });
    };

    this.PrintingPrinterQueuesMine_onClick = function()
    {
        os.showForm('entity.frmLister', { type:'form', entity:'systemform', formentity:'PRINTQUEUE', formentitydescription:'Printer Queue', formcode:'PRINTQUEUE', mode:'renderer', title:'My Private Printer Queues', fixedfilter:[{"field":"user_id","value":"MYSELF"},{"field":"is_public","value":"N"}] });
    };

    this.PrintingPrinterQueuesPublic_onClick = function()
    {
        os.showForm('entity.frmLister', { type:'form', entity:'systemform', formentity:'PRINTQUEUE', formentitydescription:'Printer Queue', formcode:'PRINTQUEUE', mode:'renderer', title:'My Public Printer Queues', fixedfilter:[{"field":"user_id","value":"MYSELF"},{"field":"is_public","value":"Y"}] });
    };

    this.PrintingApplicationHealth_onClick = function()
    {
        os.showForm('core.frmHealth');
    };

    function EditMyDevice()
    {
        os.showForm('entity.frmForm', { entity:'systemform', formentity:'DEVICE', formcode:'DEVICE', mode:'edit', title:'My Device', relationship:'children', relativeid:'MYDEVICE', relative:'DEVICE', id:'MYDEVICE' });
    }

    function ListMyDevices()
    {
        os.showForm('entity.frmLister', { type:'form', entity:'systemform', formentity:'DEVICE', formentitydescription:'Device', formcode:'DEVICE', mode:'renderer', title:'My Devices', fixedfilter:[{"field":"user_id","value":"MYSELF"}] });
    }

    this.NavOption_onClick = function(objThis_a)
    {
        var strNavChild = $(objThis_a).attr("navchild");
        os.element(m_strFormID, '.ge-option').hide();
        os.element(m_strFormID, '.' + strNavChild).show();

        //os.element(m_strFormID, '.ge-navoption').css('background-color','#FFFFFF');
        //$(objThis_a).css('background-color','#DDDDDD');
    };

    this.NavOption_onHover = function(objThis_a)
    {
        os.element(m_strFormID, '.ge-navoption').css('font-weight','normal');
        $(objThis_a).css('font-weight','bolder');
    };

    this.Button_onHover = function(objThis_a)
    {
        os.element(m_strFormID, '.ge-button').css('font-weight','normal');
        $(objThis_a).css('font-weight','bolder');

        os.element(m_strFormID, '.ge-button').css('background-color','#f9f9f9');
        $(objThis_a).css('background-color','#edeae5');
    };

    function actionShowOptions(arrParams_a)
    {
        os.element(m_strFormID, '.ge-option').hide();
        os.element(m_strFormID, '.' + arrParams_a).show();
    }

    // ====================================================================================
    // TABBING ============================================================================

    function setTabOrder()
    {
        os.unbindEvents(m_strFormID, 'ge-tab-start,ge-tab-end');
        os.bindEvent(m_objThis, m_strFormID, '.ge-tab-start', 'TabStart', 'onFocus');
        os.bindEvent(m_objThis, m_strFormID, '.ge-tab-end', 'TabEnd', 'onFocus');

        os.setTabOrder(m_strFormID, 'ge-tab-start,ge-close-button,ge-tab-end');
    }

    this.TabEnd_onFocus = function ()
    {
            //os.element(m_strFormID, '.cmdClose').focus();
    };

    this.TabStart_onFocus = function ()
    {
            //os.element(m_strFormID, '.cmdClose').focus();
    };
}
