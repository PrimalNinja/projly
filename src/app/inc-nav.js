function JNav(objOS_a, strStyle_a)
{
    var os = objOS_a;
    var m_objThis = this;
    var m_strClientName = os.getProperty('clientname');
    var m_strStyle = strStyle_a;

    var m_blnIsMobile = os.hasCapability('mobile');

    // note: these flags should not be used for permissions, use the permissions in the permission table for permissions
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
	
    // temp changes
    //m_blnIsIndividual = false;
    //m_blnIsEmployer = false;

    var m_arrMap = [];

    if (m_blnIsPublic)
    {
        m_arrMap = getNavMapPublic(os);
    }
    else if(m_blnIsDeveloper)
    {
        m_arrMap = getNavMapDeveloper(os);
    }
    else if(m_blnIsSysAdmin)
    {
        m_arrMap = getNavMapSysAdmin(os);
    }
    else if (m_blnIsBatchClient || m_blnIsDefaultClient || m_blnIsOwnerClient)
    {
            m_arrMap = getNavMapSysOwner(os);
    }
    else
    {
            m_arrMap = getNavMapClient(os, m_blnIsEmployer, m_blnIsIndividual);
    }

    //var arrMapRow = ['RecentGroup', 'RecentItem1Button', 'RecentItem2Button', 'RecentItem3Button', 'RecentItem4Button', 'RecentItem5Button', 'RecentItem6Button', 'RecentItem7Button'];
    //m_arrMap[0].layout.push(arrMapRow);

    // start of tiles ====================================================================================================

    // root tiles
    // recent tiles
    // group tiles (suffixed with 'Group')
    // action tiles - miscellaneous
    // action tiles - dashboards & reports
    // action tiles - cms forms
    // action tiles - forms
    // action tiles - lists
    // action tiles - projly
    // action tiles - dispatch
    // action tiles - stock control
    // import tiles
    // widget tiles
    // developer tiles
    // developer form builder tiles
    // developer report builder tiles
    // developer RnD Tiles

	var m_arrTiles = [
		// root tiles
		{
                        id : '-',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'blank'
		},
		{
			id : '--',
			caption : ' ',
			classes : getSeparatorStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'blank'
		},
		{
			id : 'MenuGroup',
			caption : 'Menu',
			classes : getRootStyle,	// note add gb-form-handle to allow the menu to be dragged
			permissions : [],
                        significant : true,
			action : 'navigate',
			actionData : 'root',
			tip : 'This is the menu.',
			type : 'menuitem'
		},

		// recent tiles
		{
			id : 'RecentGroup',
			caption : 'My Recent&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'RecentItem1Button',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'blank'
		},
		{
			id : 'RecentItem2Button',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'blank'
		},
		{
			id : 'RecentItem3Button',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'blank'
		},
		{
			id : 'RecentItem4Button',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'blank'
		},
		{
			id : 'RecentItem5Button',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'blank'
		},
		{
			id : 'RecentItem6Button',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'blank'
		},
		{
			id : 'RecentItem7Button',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'blank'
		},

		// group tiles (suffixed with 'Group')
		{
			id : 'ConfigurationGeneralGroup',
			caption : 'Configuration&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'ConfigurationSystemGroup',
			caption : 'Configuration - System&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
        {
			id : 'ConfigurationProjlyGroup',
			caption : 'Configuration - Projly&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'DeveloperGroup',
			caption : 'Developer&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'DeveloperRnDGroup',
			caption : 'Developer R&amp;D&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'DeveloperTestGroup',
			caption : 'Developer Test&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'DevelopmentGroup',
			caption : 'Development&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'DocumentsGroup',
			caption : 'Documents&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'ImportGroup',
			caption : 'Import&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'IntegrationGroup',
			caption : 'Integration&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
        },
        {
			id : 'MyAccountGroup',
			caption : 'My Account&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
        },		
        {
			id : 'ProjectGroup',
			caption : 'Projly&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
        },
		{
			id : 'LicenceManagerGroup',
			caption : 'Licence Manager&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},	
		{
			id : 'ReferenceDataGroup',
			caption : 'Reference&nbsp;Data&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
        {
			id : 'PrintingGroup',
			caption : 'Printing&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'SecurityGroup',
			caption : 'Security&nbsp;>',
			classes : getGroupStyle,
			permissions : [],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'SettingsGroup',
			caption : 'Settings&nbsp;>',
			classes : getGroupStyle,
			permissions : function ()
			{
				return !os.getProperty('isextend');
			},
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'SystemGroup',
			caption : 'System&nbsp;>',
			classes : getGroupStyle,
			permissions : function ()
			{
				return !os.getProperty('isextend');
			},
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},
		{
			id : 'ToolsGroup',
			caption : 'Tools&nbsp;>',
			classes : getGroupStyle,
			permissions : ['VW_TOOLGROUP'],
			action : '',
			significant : false,
			tip : '',
			type : 'toolbarbutton'
		},

		// action tiles - miscellaneous
		{
			id : 'CloseButton',
			caption : 'Close',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return os.isChild() && !os.toBoolean(os.getProperty('public'));
			},
			action : closeTab,
			tip : 'Click here to close this window.'
		},
		// {
			// id : 'LogoutButton',
			// caption : 'Logout',
			// classes : getActionButtonStyle,
			// permissions : function ()
			// {
				// return !os.isChild() && !os.toBoolean(os.getProperty('public'));
			// },
			// action : logout,
			// tip : 'Click here to logout.'
		// },
		{
			id : 'LogoutButton',
			caption : 'Logout',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return !os.isChild() && !os.toBoolean(os.getProperty('public')) && !hasCapability('mobile') && hasPermission(['VW_LOGOUT']);
			},
			action : logout,
			tip : 'Click here to logout.'
		},
		{
			id : 'LogoutMobileButton',
			caption : 'Logout',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return !os.toBoolean(os.getProperty('public')) && hasCapability('mobile') && hasPermission(['VW_LOGOUT']);
			},
			action : logout,
			tip : 'Click here to logout.'
		},
		{
			id : 'ReturnButton',
			caption : 'Return to Client',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return !os.isChild() && !os.toBoolean(os.getProperty('public')) && !hasCapability('mobile') && hasPermission(['VW_RETURN']);
			},
			action : clientReturn,
			tip : 'Click here to return to your previous client.'
		},
		{
			id : 'ReturnMobileButton',
			caption : 'Return to Client',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return !os.toBoolean(os.getProperty('public')) && hasCapability('mobile') && hasPermission(['VW_RETURN']);
			},
			action : clientReturn,
			tip : 'Click here to return to your previous client.'
		},
		{
			id : 'TabExtendButton',
			caption : 'Extend...',
			classes : getActionButtonStyle,
			noncollapsable: true,
			permissions : function ()
			{
				return (!os.getProperty('isextend') && os.canOpenTab() && os.hasCapability('multimon') && (ENABLE_EXTEND === 'TRUE') && (!(m_blnIsEmployer || m_blnIsIndividual)));
			},
			action : os.openTab,
			tip : 'Click here to extend your ' + APP_SHORT_NAME + ' desktop to an additional monitor.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListSpacerButton',
			caption : '',
			classes : getActionButtonStyle,
			noncollapsable: true,
			permissions : [],
			action : function(){},
			tip : '',
			type : 'toolbarbutton'
		},		

		// action tiles - dashboards & reports
        {
			id : 'ProjectDashButton',
			caption : 'Dashboard',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECT'],
			action : function() { os.showForm('dash.frmProject', { title:'Project Dashboard' }, true); },
			actionData: null,
			tip : 'Click here to view the project dashboard.',
			type : 'toolbarbutton'
		},
		{
			id : 'SecurityDashButton',
			caption : 'Security Dashboard',
			classes : getActionButtonStyle,
			permissions : ['VW_SECURITYDASHBOARD'],
			action : function() { os.showForm('dash.frmSecurity', { title:'Security Dashboard' }, true); },
			actionData: null,
			tip : 'Click here to view the security dashboard.',
			type : 'toolbarbutton'
		},
		
		// action tiles - cms forms
		{
			id : 'FormProductAboutButton',
			caption : 'About ', // + APP_SHORT_NAME,
			classes : getActionButtonStyle,
			noncollapsable: m_blnIsPublic,
			permissions : [],
			action : function(objActionData_a)
			{
				os.showForm('entity.frmHTMLForm', objActionData_a, true);
			},
			actionData : { entity:'systemform', formentity:'CMS', formcode:'CMS', mode:'view', title:'About ' + APP_SHORT_NAME, code:'PRODUCT_ABOUT' },
			tip : 'Click here to view about '  + APP_SHORT_NAME + '.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormSupportInformationButton',
			caption : 'Support Information',
			classes : getActionButtonStyle,
			noncollapsable: m_blnIsPublic,
			permissions : [],
			action : function(objActionData_a)
			{
				os.showForm('entity.frmHTMLForm', objActionData_a, true);
			},
			actionData : { entity:'systemform', formentity:'CMS', formcode:'CMS', mode:'view', title:'Support Information', code:'SUPPORT_INFORMATION' },
			tip : 'Click here to view support information.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormPrivacyPolicyButton',
			caption : APP_SHORT_NAME + ' Privacy Policy',
			classes : getActionButtonStyle,
			noncollapsable: m_blnIsPublic,
			permissions : [],
			action : function(objActionData_a)
			{
				os.showForm('entity.frmHTMLForm', objActionData_a, true);
			},
			actionData : { entity:'systemform', formentity:'CMS', formcode:'CMS', mode:'view', title:APP_SHORT_NAME + ' Privacy Policy', code:'PRIVACY_POLICY' },
			tip : 'Click here to view the '  + APP_SHORT_NAME + ' privacy policy.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormTermsConditionsButton',
			caption : APP_SHORT_NAME + ' Terms and Conditions',
			classes : getActionButtonStyle,
			noncollapsable: m_blnIsPublic,
			permissions : [],
			action : function(objActionData_a)
			{
				os.showForm('entity.frmHTMLForm', objActionData_a, true);
			},
			actionData : { entity:'systemform', formentity:'CMS', formcode:'CMS', mode:'view', title:APP_SHORT_NAME + ' Terms and Conditions', code:'TERMS_CONDITIONS' },
			tip : 'Click here to view '  + APP_SHORT_NAME + ' terms and conditions.',
			type : 'toolbarbutton'
		},
		
		{
			id : 'ListContactsButton',
			caption : 'Contacts',
			noncollapsable: true,
			classes : getActionButtonStyle,
			permissions : ['VW_CONTACT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'CONTACT', formentitydescription:'Contact', formcode:'CONTACT', mode:'renderer', title:'Contacts', fixedfilter:[{"field":"is_enabled","value":"Y"}] },
			tip : 'Click here to list contacts.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListContactStatusesButton',
			caption : 'Contact Statuses',
			classes : getActionButtonStyle,
			permissions : ['VW_CONTACTSTATUS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'CONTACTSTATUS', formentitydescription:'Contact Status', formcode:'CONTACTSTATUS', mode:'renderer', title:'Contact Statuses', fixedfilter:[{"field":"is_enabled","value":"Y"}] },
			tip : 'Click here to list contact statuses.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListContactTypesButton',
			caption : 'Contact Types',
			classes : getActionButtonStyle,
			permissions : ['VW_CONTACTTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'CONTACTTYPE', formentitydescription:'Contact Type', formcode:'CONTACTTYPE', mode:'renderer', title:'Contact Types', fixedfilter:[{"field":"is_enabled","value":"Y"}] },
			tip : 'Click here to list contact types.',
			type : 'toolbarbutton'
		},
		
		// action tiles - forms
		{
			id : 'FormChangePasswordButton',
			caption : 'My Password',
			classes : getActionButtonStyle,
			permissions : ['CHPWD_USER'],
			action : os.showForm,
			actionData : 'core.frmChangePassword',
			actionData2 : 'mode=editself',
			tip : 'Click here to change your password.',
			type : 'toolbarbutton'
		},
        {
			id : 'FormCheckoutButton',
			caption : 'View Cart',
			classes : getActionButtonStyle,
			noncollapsable: true,
			permissions : [],
			action : function(objActionData_a)
            {
				os.showFormPopup('core.frmPayment', {
					title:'Purchase Subscription',
					applicantname: os.getProperty('clientname'),
                    mode : 'selectproduct',
					producttypeheading: 'Subscriptions',
					producttype: 'subscription',
					filter: 'PRODUCT',
					//checkout: true,
					multi:true
				}, function() {}, false, true);
            },
			actionData: null,
			tip : 'Click here to purchase a subscription.',
			type : 'toolbarbutton'
		},		
        {
			id : 'FormClientSettingsButton',
			caption : 'Client Settings',
			classes : getActionButtonStyle,
			permissions : ['VW_CLIENTSETTING'],
			action : actionShowEntityForm,
			actionData : { entity:'systemform', formentity:'CLIENTSETTING', formcode:'CLIENTSETTING', mode:'edit', title:'Client Settings', relationship:'children', relativeid:'MYCLIENT', relative:'CLIENTSETTING', id:'MYCLIENT' },
			tip : 'Click here to view client settings.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormLicensingButton',
			caption : 'Licensing',
			classes : getActionButtonStyle,
			permissions : ['VW_LICENSING'],
			action : function ()
			{
				if (hasPermission(['EDT_LICENSING']))
				{
					os.showForm('core.frmLicensing', 'mode=edit');
				}
				else
				{
					os.showForm('core.frmLicensing', 'mode=view');
				}
			},
			tip : 'Click here to view or update your product licensing.',
			type : 'toolbarbutton'
        },
		{
			id : 'FormAccountButton',
			caption : 'Account',
			classes : getActionButtonStyle,
			permissions : ['VW_ACCOUNT'],
			action : actionShowEntityForm,
			actionData : { entity:'systemform', formentity:'ACCOUNT', formcode:'ACCOUNT', mode:'edit', title:'Account', relationship:'children', relativeid:'MYACCOUNT', relative:'ACCOUNT', id:'MYACCOUNT' },
			tip : 'Click here to view account.',
			type : 'toolbarbutton'
		},
        {
			id : 'FormClientDispatchSettingsButton',
			caption : 'Client Despatch Preferences',
			classes : getActionButtonStyle,
			permissions : ['VW_DISPATCHSETTING'],
			action : actionShowEntityForm,
			actionData : { entity:'systemform', formentity:'DISPATCHSETTING', formcode:'DISPATCHSETTING', mode:'edit', title:'Client Despatch Preferences', relationship:'children', relativeid:'MYCLIENT', relative:'DISPATCHSETTING', id:'MYCLIENT' },
			tip : 'Click here to view client despatch preferences.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormMyMessagesButton',
			caption : 'My Messages',
			noncollapsable: true,
			classes : getActionButtonStyle,
			permissions: ['VW_INTERNALMESSAGE'],
			action : function(objActionData_a)
			{
				os.showForm('msg.frmMyMessages');
			},
			tip : 'Click here to view my messages.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormMyUserSettingsButton',
			caption : 'My User Preferences',
			classes : getActionButtonStyle,
			permissions : ['VW_USERSETTING'],
			action : actionShowEntityForm,
			actionData : { entity:'systemform', formentity:'USERSETTING', formcode:'USERSETTING', mode:'edit', title:'My User Preferences', relationship:'children', relativeid:'MYSELF', relative:'USERSETTING', id:'MYSELF' },
			tip : 'Click here to view my user preferences.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormMyUserButton',
			caption : 'My User',
			classes : getActionButtonStyle,
			permissions : ['VW_USER'],
			action : actionShowEntityForm,
			actionData : { entity:'systemform', formentity:'USER', formcode:'USER', mode:'edit', title:'My User', relationship:'children', relativeid:'MYSELF', relative:'USER', id:'MYSELF' },
			tip : 'Click here to view my user.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormThemesButton',
			caption : 'Themes',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (hasPermission(['CHG_THEME']) && os.hasCapability('themes'));
				//return true; // MITSUKIBO - disabled until we do more themes again one day
			},
			action : function() { os.showForm('core.frmThemes', '', true); },
			actionData : null,
			tip : 'Click here for ' + APP_SHORT_NAME + ' themes.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormWelcomeButton',
			caption : 'Home',
			classes : getActionButtonStyle,
			noncollapsable: true,
			permissions : function()
			{
				return ((WELCOMEFORM.length > 0) && m_blnDisplayWelcome);
			},
			action : os.showForm,
			actionData: WELCOMEFORM,
			tip : 'Click here to view the home page.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormHealthButton',
			caption : 'Application Health',
			classes : getActionButtonStyle,
			permissions : [],
			action : os.showForm,
			actionData: 'core.frmHealth',
			tip : 'Click here to view the the application health.',
			type : 'toolbarbutton'
		},

		// action tiles - lists
		{
			id : 'ListAccountButton',
			caption : 'Accounts',
			classes : getActionButtonStyle,
			permissions : ['VW_ACCOUNT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'ACCOUNT', formentitydescription:'Account', formcode:'ACCOUNT', mode:'renderer', title:'Accounts' },
			tip : 'Click here to list accounts.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListAPICallsButton',
			caption : 'API Calls',
			classes : getActionButtonStyle,
			permissions : ['VW_APICALL'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'APICALL', formentitydescription:'API Call', formcode:'APICALL', mode:'renderer', title:'API Calls'},
			tip : 'Click here to list API calls',
			type : 'toolbarbutton'
		},
		{
			id : 'ListAPICallTypesButton',
			caption : 'API Call Types',
			classes : getActionButtonStyle,
			permissions : ['VW_APICALLTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'APICALLTYPE', formentitydescription:'API Call Type', formcode:'APICALLTYPE', mode:'renderer', title:'API Call Types'},
			tip : 'Click here to list API call types',
			type : 'toolbarbutton'
		},
		{
			id : 'ListAPIKeysButton',
			caption : 'API Keys',
			classes : getActionButtonStyle,
			permissions : ['VW_APIKEY'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'APIKEY', formentitydescription:'API Key', formcode:'APIKEY', mode:'renderer', title:'API Keys'},
			tip : 'Click here to list API keys',
			type : 'toolbarbutton'
		},
		{
			id : 'ListApplicationsButton',
			caption : 'Applications',
			classes : getActionButtonStyle,
			permissions : ['VW_APPLICATION'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'APPLICATION', formentitydescription:'Application', formcode:'APPLICATION', mode:'renderer', title:'Applications' },
			tip : 'Click here to list applications.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListBatchJobsButton',
			caption : 'Batch Jobs',
			classes : getActionButtonStyle,
			permissions : ['VW_BATCHJOB'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'BATCHJOB', formentitydescription:'Batch Job', formcode:'BATCHJOB', mode:'renderer', title:'Batch Jobs' },
			tip : 'Click here to list batch jobs.',
			type : 'toolbarbutton'
        },       
		{
			id : 'ListBranchesButton',
			caption : 'Branches',
			classes : getActionButtonStyle,
			permissions : ['VW_BRANCH'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'BRANCH', formentitydescription:'Branch', formcode:'BRANCH', mode:'renderer', title:'Branches' },
			tip : 'Click here to list branches.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListMyBranchesButton',
			caption : 'My Branches',
			classes : getActionButtonStyle,
			permissions : ['VW_MYBRANCH'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'BRANCH', formentitydescription:'My Branch', formcode:'BRANCH', mode:'renderer', title:'My Branches' },
			tip : 'Click here to list branches.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListCheckDigitTypesButton',
			caption : 'Check Digit Types',
			classes : getActionButtonStyle,
			permissions : ['VW_CHECKDIGITTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'CHECKDIGITTYPE', formentitydescription:'Check Digit Type', formcode:'CHECKDIGITTYPE', mode:'renderer', title:'Check Digit Types' },
			tip : 'Click here to list check digit types.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListClientsButton',
			caption : 'Clients',
			classes : getActionButtonStyle,
			permissions : ['VW_CLIENT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'CLIENT', formentitydescription:'Client', formcode:'CLIENT', mode:'renderer', title:'Clients' },
			tip : 'Click here to list clients.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListMyClientsButton',
			caption : 'My Clients',
			classes : getActionButtonStyle,
			permissions : ['VW_MYCLIENT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'MYCLIENT', formentitydescription:'My Client', formcode:'MYCLIENT', mode:'renderer', title:'My Clients' },
			tip : 'Click here to list my clients.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListCMSButton',
			caption : 'CMS',
			classes : getActionButtonStyle,
			permissions : ['VW_CMS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'CMS', formentitydescription:'CMS Page', formcode:'CMS', mode:'renderer', title:'CMS Pages' },
			tip : 'Click here to list CMS pages.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListColoursButton',
			caption : 'Colours',
			classes : getActionButtonStyle,
			permissions : ['VW_COLOUR'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'COLOUR', formentitydescription:'Colours', formcode:'COLOUR', mode:'renderer', title:'Colours' },
			tip : 'Click here to list colours.',
			type : 'toolbarbutton'
        },  		
		{
			id : 'ListConfigsButton',
			caption : 'Config Processes',
			classes : getActionButtonStyle,
			permissions : ['VW_CONFIG'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'CONFIG', formentitydescription:'Config', formcode:'CONFIG', mode:'renderer', title:'Config' },
			tip : 'Click here to list configurations.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListDataAuditLogsButton',
			caption : 'Data Audit Log',
			classes : getActionButtonStyle,
			permissions : ['VW_AUDITLOG'],
			action : actionShowListLegacyActionData,
			actionData : LIST_DATAAUDITLOGS,
			tip : 'Click here to show the data audit log.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListDBProcessButton',
			caption : 'DB Processes',
			classes : getActionButtonStyle,
			permissions : ['VW_DBPROCESS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DBPROCESS', formentitydescription:'Database Process', formcode:'DBPROCESS', mode:'renderer', title:'Database Processes' },
			tip : 'Click here to list database processes.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListDesktopRegionsButton',
			caption : 'Desktop Regions',
			classes : getActionButtonStyle,
			permissions : ['VW_DESKTOPREGION'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DESKTOPREGION', formentitydescription:'Desktop Region', formcode:'DESKTOPREGION', mode:'renderer', title:'Desktop Regions' },
			tip : 'Click here to list desktop regions.',
			type : 'toolbarbutton'
		},		
		{
			id : 'ListDeviceButton',
			caption : 'Devices (All)',
			classes : getActionButtonStyle,
			permissions : ['VW_DEVICE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DEVICE', formentitydescription:'Device', formcode:'DEVICE', mode:'renderer', title:'Devices (All)' },
			tip : 'Click here to list all devices.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListDevicesButton',
			caption : 'Devices',
			classes : getActionButtonStyle,
			permissions : ['VW_DEVICE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DEVICE', formentitydescription:'Device', formcode:'DEVICE', mode:'renderer', title:'Devices' },
			tip : 'Click here to list all devices.',
			type : 'toolbarbutton'
		},

		{
			id : 'ListDeviceCurrentButton',
			caption : 'Devices (Current)',
			classes : getActionButtonStyle,
			permissions : ['VW_DEVICE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DEVICE', formentitydescription:'Device', formcode:'DEVICE', mode:'renderer', title:'Devices (Current)', fixedfilter:[{"field":"is_current","value":"Y"}]},
			tip : 'Click here to list current devices.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListDeviceLogButton',
			caption : 'Device Log',
			classes : getActionButtonStyle,
			permissions : ['VW_DEVICELOG'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DEVICELOG', formentitydescription:'Device', formcode:'DEVICELOG', mode:'renderer', title:'Device Log' },
			tip : 'Click here to show the device log.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListDocumentButton',
			caption : 'Documents',
			classes : getActionButtonStyle,
			permissions : ['VW_DOCUMENT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DOCUMENT', formentitydescription:'Document', formcode:'DOCUMENT', mode:'renderer', title:'Documents' },
			tip : 'Click here to list documents.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListDocumentTaglessButton',
			caption : 'Documents - Tagless',
			classes : getActionButtonStyle,
			permissions : ['VW_DOCUMENT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DOCUMENT', formentitydescription:'Document', formcode:'DOCUMENT', mode:'renderer', title:'Documents - Tagless', fixedfilter:[{"field":"is_tagless","value":"Y"}]},
			tip : 'Click here to list documents.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListDocumentRetentionTypesButton',
			caption : 'Document Retention Types',
			classes : getActionButtonStyle,
			permissions : ['VW_DOCUMENTRETENTIONTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DOCUMENTRETENTIONTYPE', formentitydescription:'Document Retention Type', formcode:'DOCUMENTRETENTIONTYPE', mode:'renderer', title:'Document Retention Types' },
			tip : 'Click here to list document retention types.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListDocumentRepositoryButton',
			caption : 'Repositories',
			classes : getActionButtonStyle,
			permissions : ['VW_DOCUMENTREPOSITORY'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DOCUMENTREPOSITORY', formentitydescription:'Document Repository', formcode:'DOCUMENTREPOSITORY', mode:'renderer', title:'Document Repositories' },
			tip : 'Click here to list document repositories.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListDocumentTypesButton',
			caption : 'Document Types',
			classes : getActionButtonStyle,
			permissions : ['VW_DOCUMENTTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DOCUMENTTYPE', formentitydescription:'Document Type', formcode:'DOCUMENTTYPE', mode:'renderer', title:'Document Types' },
			tip : 'Click here to list document types.',
			type : 'toolbarbutton'
		},
        {
            id : 'ListEmployerSizesButton',
            caption : 'Employer Sizes',
            classes : getActionButtonStyle,
            permissions : ['VW_EMPLOYERSIZE'],
            action : actionShowEntityList,
            actionData : { type:'form', entity:'systemform', formentity:'EMPLOYERSIZE', formentitydescription:'Employer Size', formcode:'EMPLOYERSIZE', mode:'renderer', title:'Employer Sizes'},
            tip : 'Click here to list employer size.',
            type : 'toolbarbutton'
        },
        {
			id : 'ListFAQButton',
			caption : 'FAQs',
			classes : getActionButtonStyle,
			permissions : ['VW_FAQ'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FAQ', formentitydescription:'FAQ', formcode:'FAQ', mode:'renderer', title:'FAQs' },
			tip : 'Click here to list FAQs.',
			type : 'toolbarbutton'
        },       
        {
			id : 'ListFileTypesButton',
			caption : 'File Types',
			classes : getActionButtonStyle,
			permissions : ['VW_FILETYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FILETYPE', formentitydescription:'File Type', formcode:'FILETYPE', mode:'renderer', title:'File Types' },
			tip : 'Click here to list file types.',
			type : 'toolbarbutton'
        },
		{
			id : 'ListFileSystemsButton',
			caption : 'File Systems',
			classes : getActionButtonStyle,
			permissions : ['VW_FILESYSTEM'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FILESYSTEM', formentitydescription:'File System', formcode:'FILESYSTEM', mode:'renderer', title:'File Systems' },
			tip : 'Click here to list file systems.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListGenderButton',
			caption : 'Genders',
			classes : getActionButtonStyle,
			permissions : ['VW_GENDER'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'GENDER', formentitydescription:'Gender', formcode:'GENDER', mode:'renderer', title:'Genders' },
			tip : 'Click here to list genders.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListHonorificButton',
			caption : 'Honorifics',
			classes : getActionButtonStyle,
			permissions : ['VW_HONORIFIC'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'HONORIFIC', formentitydescription:'Honorific', formcode:'HONORIFIC', mode:'renderer', title:'Honorifics' },
			tip : 'Click here to list honorifics.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListIndustryTypesButton',
			caption : 'Industry Types',
			classes : getActionButtonStyle,
			permissions : ['VW_INDUSTRYTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'INDUSTRYTYPE', formentitydescription:'Industry Type', formcode:'INDUSTRYTYPE', mode:'renderer', title:'Industry Types' },
			tip : 'Click here to list industry types.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListIntegrationInboundsButton',
			caption : 'Inbound Integrations',
			classes : getActionButtonStyle,
			permissions : ['VW_INTEGRATIONINBOUND'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'INTEGRATIONINBOUND', formentitydescription:'Inbound Integration', formcode:'INTEGRATIONINBOUND', mode:'renderer', title:'Inbound Integrations' },
			tip : 'Click here to list inbound integrations.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListIntegrationInboundTaskButton',
			caption : 'Inbound Tasks',
			classes : getActionButtonStyle,
			permissions : ['VW_INTEGRATIONTASKINBOUND'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'INTEGRATIONTASKINBOUND', formentitydescription:'Integration Inbound Task', formcode:'INTEGRATIONTASKINBOUND', mode:'renderer', title:'Integration Inbound Tasks' },
			tip : 'Click here to list integration inbound tasks.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListIntegrationOutboundsButton',
			caption : 'Outbound Integrations',
			classes : getActionButtonStyle,
			permissions : ['VW_INTEGRATIONOUTBOUND'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'INTEGRATIONOUTBOUND', formentitydescription:'Outbound Integration', formcode:'INTEGRATIONOUTBOUND', mode:'renderer', title:'Outbound Integrations' },
			tip : 'Click here to list outbound integrations.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListIntegrationOutboundTaskButton',
			caption : 'Outbound Tasks',
			classes : getActionButtonStyle,
			permissions : ['VW_INTEGRATIONTASKOUTBOUND'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'INTEGRATIONTASKOUTBOUND', formentitydescription:'Integration Outbound Task', formcode:'INTEGRATIONTASKOUTBOUND', mode:'renderer', title:'Integration Outbound Tasks'},
			tip : 'Click here to list integration outbound tasks.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListInternalMessageFoldersButton',
			caption : 'Inernal Message Folders',
			classes : getActionButtonStyle,
			permissions : ['VW_INTERNALMESSAGEFOLDER'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'INTERNALMESSAGEFOLDER', formentitydescription:'Internal Message Folder', formcode:'INTERNALMESSAGEFOLDER', mode:'renderer', title:'Internal Message Folders' },
			tip : 'Click here to list internal message folders.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListMapProvidersButton',
			caption : 'Map Providers',
			classes : getActionButtonStyle,
			permissions : ['VW_MAPPROVIDER'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'MAPPROVIDER', formentitydescription:'Map Provider', formcode:'MAPPROVIDER', mode:'renderer', title:'Map Providers' },
			tip : 'Click here to list map providers.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListSoftwareLicencesButton',
			caption : 'Software Licences',
			classes : getActionButtonStyle,
			permissions : ['VW_SOFTWARELICENCE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SOFTWARELICENCE', formentitydescription:'Software Licence', formcode:'SOFTWARELICENCE', mode:'renderer', title:'Software Licences' },
			tip : 'Click here to list software licences.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListSoftwareLicenceTypesButton',
			caption : 'Software Licence Types',
			classes : getActionButtonStyle,
			permissions : ['VW_SOFTWARELICENCETYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SOFTWARELICENCETYPE', formentitydescription:'Software Licence Type', formcode:'SOFTWARELICENCETYPE', mode:'renderer', title:'Software Licence Types' },
			tip : 'Click here to list software licence types.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListMessageButton',
			caption : 'Email List Messages',
			classes : getActionButtonStyle,
			permissions : ['VW_MESSAGE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'MESSAGE', formentitydescription:'Email List Messages', formcode:'MESSAGE', mode:'renderer', title:'Email List Messages' },
			tip : 'Click here to show email list messages.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListMessageProfilesButton',
			caption : 'Message Profiles',
			classes : getActionButtonStyle,
			permissions : ['VW_MESSAGEPROFILE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'MESSAGEPROFILE', formentitydescription:'Message Profile', formcode:'MESSAGEPROFILE', mode:'renderer', title:'Message Profiles' },
			tip : 'Click here to show message profiles.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListMessageTemplatesButton',
			caption : 'Message Templates',
			classes : getActionButtonStyle,
			permissions : ['VW_MESSAGETEMPLATE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'MESSAGETEMPLATE', formentitydescription:'Template', formcode:'MESSAGETEMPLATE', mode:'renderer', title:'Message Templates' },
			tip : 'Click here to show message templates.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListMessageTemplateTypesButton',
			caption : 'Message Template Types',
			classes : getActionButtonStyle,
			permissions : ['VW_MESSAGETEMPLATETYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'MESSAGETEMPLATETYPE', formentitydescription:'Template Type', formcode:'MESSAGETEMPLATETYPE', mode:'renderer', title:'Message Template Types' },
			tip : 'Click here to show message template types.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListMilestoneButton',
			caption : 'Milestones',
			classes : getActionButtonStyle,
			permissions : ['VW_MILESTONE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'MILESTONE', formentitydescription:'Milestone', formcode:'MILESTONE', mode:'renderer', title:'Milestone' },
			tip : 'Click here to list the milestones.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListModulesButton',
			caption : 'Modules',
			classes : getActionButtonStyle,
			permissions : ['VW_MODULE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'MODULE', formentitydescription:'Module', formcode:'MODULE', mode:'renderer', title:'Modules' },
			tip : 'Click here to list modules.',
			type : 'toolbarbutton'
		},
        {
            id : 'ListMyDevicesButton',
            caption : 'My Devices',
            classes : getActionButtonStyle,
            permissions : ['VW_DEVICE'],
            action : actionShowEntityList,
            actionData : { type:'form', entity:'systemform', formentity:'DEVICE', formentitydescription:'Device', formcode:'DEVICE', mode:'renderer', title:'My Devices', fixedfilter:[{"field":"user_id","value":"MYSELF"}] },
            tip : 'Click here to list all devices.',
            type : 'toolbarbutton'
        },
        {
			id : 'ListMyPrivatePrinterQueuesButton',
			caption : 'My Private Printer Queues',
			classes : getActionButtonStyle,
			permissions : ['VW_PRINTQUEUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PRINTQUEUE', formentitydescription:'Printer Queue', formcode:'PRINTQUEUE', mode:'renderer', title:'My Private Printer Queues', fixedfilter:[{"field":"user_id","value":"MYSELF"},{"field":"is_public","value":"N"}] },
			tip : 'Click here to list my private printer queues.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListMyProductsButton',
			caption : 'My Products',
			classes : getActionButtonStyle,
			permissions : ['VW_CLIENTPRODUCT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'CLIENTPRODUCT', formentitydescription:'My Products', formcode:'CLIENTPRODUCT', mode:'renderer', title:'My Products' /*, fixedfilter:[{"field":"is_public","value":"N"}]*/ },
			tip : 'Click here to list my products.',
			type : 'toolbarbutton'
        },	
		{
			id : 'ListMyProfilesButton',
			caption : 'Profiles',
			classes : getActionButtonStyle,
			permissions : ['VW_PROFILE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROFILE', formentitydescription:'Profile', formcode:'PROFILE', mode:'renderer', title:'Profiles', fixedfilter:[{"field":"client_id","value":"MYCLIENT"}] },
			tip : 'Click here to list profiles.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListMyRemindersButton',
			caption : 'Reminders',
			noncollapsable: true,
			classes : getActionButtonStyle,
			permissions : ['VW_REMINDER'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'REMINDER', formentitydescription:'Reminder', formcode:'REMINDER', mode:'renderer', title:'My Reminders', fixedfilter:[{"field":"user_id","value":"MYSELF"}] },
			tip : 'Click here to list my reminders.',
			type : 'toolbarbutton'
		},
        {
            id : 'ListMyDevicesCurrentButton',
            caption : 'My Devices (Current)',
            classes : getActionButtonStyle,
            permissions : ['VW_DEVICE'],
            action : actionShowEntityList,
            actionData : { type:'form', entity:'systemform', formentity:'DEVICE', formentitydescription:'Device', formcode:'DEVICE', mode:'renderer', title:'My Devices (Current)', fixedfilter:[{"field":"user_id","value":"MYSELF"},{"field":"is_current","value":"Y"}] },
            tip : 'Click here to list current devices.',
            type : 'toolbarbutton'
        },
		{
			id : 'ListMyUsersButton',
			caption : 'Users',
			classes : getActionButtonStyle,
			permissions : ['VW_USER'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'USER', formentitydescription:'User', formcode:'USER', mode:'renderer', title:'Users', fixedfilter:[{"field":"client_id","value":"MYCLIENT"}] },
			tip : 'Click here to list users.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListNegotiatedDiscountsButton',
			caption : 'Negotiated Discounts',
			classes : getActionButtonStyle,
			permissions : ['VW_NEGOTIATEDDISCOUNT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'NEGOTIATEDDISCOUNT', formentitydescription:'Negotiated Discount', formcode:'NEGOTIATEDDISCOUNT', mode:'renderer', title:'Negotiated Discounts' },
			tip : 'Click here to list negotiated discounts.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListNegotiatedRatesButton',
			caption : 'Negotiated Rates',
			classes : getActionButtonStyle,
			permissions : ['VW_NEGOTIATEDRATE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'NEGOTIATEDRATE', formentitydescription:'Negotiated Rate', formcode:'NEGOTIATEDRATE', mode:'renderer', title:'Negotiated Rates' },
			tip : 'Click here to list negotiated rates.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListPaymentsButton',
			caption : 'Payments',
			classes : getActionButtonStyle,
			permissions : ['VW_PAYMENT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PAYMENT', formentitydescription:'Payment', formcode:'PAYMENT', mode:'renderer', title:'Payments' },
			tip : 'Click here to list payments.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListPaymentMethodsButton',
			caption : 'Payment Methods',
			classes : getActionButtonStyle,
			permissions : ['VW_PAYMENTMETHOD'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PAYMENTMETHOD', formentitydescription:'Payment Method', formcode:'PAYMENTMETHOD', mode:'renderer', title:'Payment Methods' },
			tip : 'Click here to list payment methods.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListPaymentStatusButton',
			caption : 'Payment Statuses',
			classes : getActionButtonStyle,
			permissions : ['VW_PAYMENTSTATUS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PAYMENTSTATUS', formentitydescription:'Status', formcode:'PAYMENTSTATUS', mode:'renderer', title:'Payment Statuses' },
			tip : 'Click here to list payment status.',
			type : 'toolbarbutton'
		},
		{
		   id : 'ListPendingEmailsButton',
			caption : 'Pending Emails',
			classes : getActionButtonStyle,
			permissions : ['VW_MESSAGEPREPARED'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'MESSAGEPREPARED', formentitydescription:'Pending Email', formcode:'MESSAGEPREPARED', mode:'renderer', title:'Pending Emails', fixedfilter:[{"field":"sent","value":"N"}] },
			tip : 'Click here to list pending emails.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProductsButton',
			caption : 'Products',
			classes : getActionButtonStyle,
			permissions : ['VW_PRODUCT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PRODUCT', formentitydescription:'Product', formcode:'PRODUCT', mode:'renderer', title:'Products' },
			tip : 'Click here to list products.',
			type : 'toolbarbutton'
		},		
		{
			id : 'ListProductTypesButton',
			caption : 'Product Types',
			classes : getActionButtonStyle,
			permissions : ['VW_PRODUCTTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PRODUCTTYPE', formentitydescription:'Product Type', formcode:'PRODUCTTYPE', mode:'renderer', title:'Product Types' },
			tip : 'Click here to list product types.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListPrinterPurposesButton',
			caption : 'Printer Purposes',
			classes : getActionButtonStyle,
			permissions :  ['VW_PRINTERPURPOSE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PRINTERPURPOSE', formentitydescription:'Print Purpose', formcode:'PRINTERPURPOSE', mode:'renderer', title:'Print Purposes' },
			tip : 'Click here to list print purposes.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListPrinterTypesButton',
			caption : 'Printer Types',
			classes : getActionButtonStyle,
			permissions :  ['VW_PRINTERTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PRINTERTYPE', formentitydescription:'Printer Type', formcode:'PRINTERTYPE', mode:'renderer', title:'Printer Types' },
			tip : 'Click here to list printer types.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListPrintJobButton',
			caption : 'Print Jobs',
			classes : getActionButtonStyle,
			permissions : ['VW_PRINTJOB'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PRINTJOB', formentitydescription:'Print Job', formcode:'PRINTJOB', mode:'renderer', title:'Print Jobs' },
			tip : 'Click here to list print jobs.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListPrintJobLogButton',
			caption : 'Print Jobs (Archived)',
			classes : getActionButtonStyle,
			permissions : ['VW_LOG_PRINTJOB'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'LOG_PRINTJOB', formentitydescription:'Print Job', formcode:'LOG_PRINTJOB', mode:'renderer', title:'Print Jobs (Archived)' },
			tip : 'Click here to list archived print jobs.',
			type : 'toolbarbutton'
        },		
        {
			id : 'ListMyPrintersButton',
			caption : 'My Printers',
			classes : getActionButtonStyle,
			permissions :  ['VW_PRINTER'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PRINTER', formentitydescription:'Printer', formcode:'PRINTER', mode:'renderer', title:'My Private Printers', fixedfilter:[{"field":"user_id","value":"MYSELF"},{"field":"is_public","value":"N"}] },
			tip : 'Click here to list my private printers.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListPublicPrintersButton',
			caption : 'Public Printers',
			classes : getActionButtonStyle,
			permissions :  ['VW_PRINTER'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PRINTER', formentitydescription:'Printer', formcode:'PRINTER', mode:'renderer', title:'Public Printers', fixedfilter:[{"field":"is_public","value":"Y"}] },
			tip : 'Click here to list public printers.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListMyPublicPrinterQueuesButton',
			caption : 'My Public Printer Queues',
			classes : getActionButtonStyle,
			permissions : ['VW_PRINTQUEUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PRINTQUEUE', formentitydescription:'Printer Queue', formcode:'PRINTQUEUE', mode:'renderer', title:'My Public Printer Queues', fixedfilter:[{"field":"user_id","value":"MYSELF"},{"field":"is_public","value":"Y"}] },
			tip : 'Click here to list my public printer queues.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListReceiptsButton',
			caption : 'Receipts',
			classes : getActionButtonStyle,
			permissions : ['VW_RECEIPT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'RECEIPT', formentitydescription:'Receipts', formcode:'RECEIPT', mode:'renderer', title:'Receipts' /*, fixedfilter:[{"field":"is_public","value":"N"}]*/ },
			tip : 'Click here to list receipts.',
			type : 'toolbarbutton'
        },				
		{
			id : 'ListRegistrationsButton',
			caption : 'Registrations',
			classes : getActionButtonStyle,
			permissions : ['VW_REGISTRATION'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'REGISTRATION', formentitydescription:'Registrations', formcode:'REGISTRATION', mode:'renderer', title:'Registrations' },
			tip : 'Click here to list account registrations.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListConfirmedRegistrationsButton',
			caption : 'Registrations (Confirmed)',
			classes : getActionButtonStyle,
			permissions : ['VW_REGISTRATION'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'REGISTRATION', formentitydescription:'Registration', formcode:'REGISTRATION', mode:'renderer', title:'Registrations (Confirmed)', fixedfilter:[{"field":"is_confirmed","value":"Y"}] },
			tip : 'Click here to list confirmed account registrations.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListUnconfirmedRegistrationsButton',
			caption : 'Registrations (Pending)',
			classes : getActionButtonStyle,
			permissions : ['VW_REGISTRATION'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'REGISTRATION', formentitydescription:'Registration', formcode:'REGISTRATION', mode:'renderer', title:'Registrations (Pending)', fixedfilter:[{"field":"is_confirmed","value":"N"}] },
			tip : 'Click here to list clients pending account registrations.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListRegistrationTypesButton',
			caption : 'Registration Types',
			classes : getActionButtonStyle,
			permissions : ['VW_REGISTRATIONTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'REGISTRATIONTYPE', formentitydescription:'Registration Type', formcode:'REGISTRATIONTYPE', mode:'renderer', title:'Registration Types' },
			tip : 'Click here to list registration types.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListResetPasswordButton',
			caption : 'Reset Password',
			classes : getActionButtonStyle,
			permissions : ['VW_RESETPASSWORD'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'RESETPASSWORD', formentitydescription:'Reset Passwords', formcode:'RESETPASSWORD', mode:'renderer', title:'Reset Passwords' },
			tip : 'Click here to list reset passwords.',
			type : 'toolbarbutton'
		},		
		{
			id : 'ListSalesPersonCodeButton',
			caption : 'Sales Person Codes',
			classes : getActionButtonStyle,
			permissions : ['VW_SALESPERSONCODE'],
			action : function(objActionData_a)
			{
				os.showForm('entity.frmLister', objActionData_a);
			},
			actionData : { type:'form', entity:'systemform', formentity:'SALESPERSONCODE', formentitydescription:'Sales Person Code', formcode:'SALESPERSONCODE', mode:'renderer', title:'Sales Person Codes' },
			tip : 'Click here to list sales person codes.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListSalesPersonCodeLogButton',
			caption : 'Sales Person Code Log',
			classes : getActionButtonStyle,
			permissions : ['VW_SALESPERSONCODELOG'],
			action : function(objActionData_a)
			{
				os.showForm('entity.frmLister', objActionData_a);
			},
			actionData : { type:'form', entity:'systemform', formentity:'SALESPERSONCODELOG', formentitydescription:'Sales Person Code Log', formcode:'SALESPERSONCODELOG', mode:'renderer', title:'Sales Person Code Log' },
			tip : 'Click here to list the sales person code log.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListSchemaChangesButton',
			caption : 'Schema Changes',
			classes : getActionButtonStyle,
			permissions : ['VW_SCHEMACHANGE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SCHEMACHANGE', formentitydescription:'Schema Changes', formcode:'SCHEMACHANGE', mode:'renderer', title:'Schema Changes' },
			tip : 'Click here to list schema changes.',
			type : 'toolbarbutton'
		},		
		{
			id : 'ListSchemaChangesPendingButton',
			caption : 'Schema Changes (Pending)',
			classes : getActionButtonStyle,
			permissions : ['VW_SCHEMACHANGE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SCHEMACHANGE', formentitydescription:'Schema Change', formcode:'SCHEMACHANGE', mode:'renderer', title:'Schema Changes (Pending)', fixedfilter:[{"field":"g523b5f4a_fc16_4f51_ad0c_c1cee86223bd_isdone","value":"N"}] },
			tip : 'Click here to list pending schema changes.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListSchemaChangesDoneButton',
			caption : 'Schema Changes (Done)',
			classes : getActionButtonStyle,
			permissions : ['VW_SCHEMACHANGE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SCHEMACHANGE', formentitydescription:'Schema Change', formcode:'SCHEMACHANGE', mode:'renderer', title:'Schema Changes (Done)', fixedfilter:[{"field":"g523b5f4a_fc16_4f51_ad0c_c1cee86223bd_isdone","value":"Y"}] },
			tip : 'Click here to list done schema changes.',
			type : 'toolbarbutton'
		},
		{
		   id : 'ListSentEmailsButton',
			caption : 'Sent Emails',
			classes : getActionButtonStyle,
			permissions : ['VW_MESSAGEPREPARED'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'MESSAGEPREPARED', formentitydescription:'Sent Email', formcode:'MESSAGEPREPARED', mode:'renderer', title:'Sent Emails', fixedfilter:[{"field":"sent","value":"Y"}] },
			tip : 'Click here to list sent emails.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListSequencesButton',
			caption : 'Sequences',
			classes : getActionButtonStyle,
			permissions : ['VW_SEQUENCE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SEQUENCE', formentitydescription:'Sequence', formcode:'SEQUENCE', mode:'renderer', title:'Sequences' },
			tip : 'Click here to list sequences.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListSequenceTypesButton',
			caption : 'Sequence Types',
			classes : getActionButtonStyle,
			permissions : ['VW_SEQUENCETYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SEQUENCETYPE', formentitydescription:'Sequence Type', formcode:'SEQUENCETYPE', mode:'renderer', title:'Sequence Types' },
			tip : 'Click here to list sequence types.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListServersButton',
			caption : 'Servers',
			classes : getActionButtonStyle,
			permissions : ['VW_SERVER'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SERVER', formentitydescription:'Server', formcode:'SERVER', mode:'renderer', title:'Servers'},
			tip : 'Click here to list servers',
			type : 'toolbarbutton'
		},
		{
			id : 'ListServerProtocolsButton',
			caption : 'Server Protocols',
			classes : getActionButtonStyle,
			permissions : ['VW_SERVERPROTOCOL'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SERVERPROTOCOL', formentitydescription:'Server Protocol', formcode:'SERVERPROTOCOL', mode:'renderer', title:'Server Protocols'},
			tip : 'Click here to list server protocols',
			type : 'toolbarbutton'
		},
		{
			id : 'ListStartupItemsButton',
			caption : 'Startup Items',
			classes : getActionButtonStyle,
			permissions : ['VW_STARTUPITEM'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'STARTUPITEM', formentitydescription:'Startup Item', formcode:'STARTUPITEM', mode:'renderer', title:'Startup Items' },
			tip : 'Click here to list startup items.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListStatesButton',
			caption : 'States',
			classes : getActionButtonStyle,
			permissions : ['VW_STATE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'STATE', formentitydescription:'State', formcode:'STATE', mode:'renderer', title:'States' },
			tip : 'Click here to list states.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListSuburbsButton',
			caption : 'Suburbs',
			classes : getActionButtonStyle,
			permissions : ['VW_SUBURB'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SUBURB', formentitydescription:'Suburb', formcode:'SUBURB', mode:'renderer', title:'Suburbs' },
			tip : 'Click here to list suburbs.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListSystemFlagValuesButton',
			caption : 'System Flag Values',
			classes : getActionButtonStyle,
			permissions : ['VW_SYSTEMFLAGVALUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SYSTEMFLAGVALUE', formentitydescription:'System Flag Value', formcode:'SYSTEMFLAGVALUE', mode:'renderer', title:m_strClientName + ' System Flag Values' },
			tip : 'Click here to list or change system flag values.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListThemeButton',
			caption : 'Themes',
			classes : getActionButtonStyle,
			permissions : ['VW_THEME'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'THEME', formentitydescription:'Theme', formcode:'THEME', mode:'renderer', title:'Themes' },
			tip : 'Click here to list themes.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListTodoButton',
			caption : 'Todo List',
			noncollapsable: true,
			classes : function() { return getActionButtonStyle(); },
			permissions : ['VW_TODO'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'TODO', formentitydescription:'Todo Item', formcode:'TODO', mode:'renderer', title:'Todo Items' },
			tip : 'Click here to show your todo list.',
			type : 'toolbarbutton'
		},	
        {
			id : 'ListTodoStatusesButton',
			caption : 'Todo Statuses',
			classes : function() { return getActionButtonStyle(); },
			permissions : ['VW_TODOSTATUS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'TODOSTATUS', formentitydescription:'Todo Status', formcode:'TODOSTATUS', mode:'renderer', title:'Todo Statuses' },
			tip : 'Click here to show the todo statuses.',
			type : 'toolbarbutton'
		},				
        {
			id : 'ListTransactionButton',
			caption : 'Transactions',
			classes : getActionButtonStyle,
			permissions : ['VW_TRANSACTION'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'TRANSACTION', formentitydescription:'Transactions', formcode:'TRANSACTION', mode:'renderer', title:'Transactions' },
			tip : 'Click here to list transactions.',
			type : 'toolbarbutton'
        },				
		{
			id : 'ListTransactionHistoryButton',
			caption : 'Transaction History',
			classes : getActionButtonStyle,
			permissions : ['VW_TRANSACTIONHISTORY'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'TRANSACTIONHISTORY', formentitydescription:'Transaction History', formcode:'TRANSACTIONHISTORY', mode:'renderer', title:'Transaction History' },
			tip : 'Click here to list transaction history.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListTransactionPendingButton',
			caption : 'Pending Transactions',
			classes : getActionButtonStyle,
			permissions : ['VW_TRANSACTIONPENDING'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'TRANSACTIONPENDING', formentitydescription:'Pending Transactions', formcode:'TRANSACTIONPENDING', mode:'renderer', title:'Pending Transactions' },
			tip : 'Click here to list pending transactions.',
			type : 'toolbarbutton'
        },				
        {
			id : 'ListTransactionStatusButton',
			caption : 'Transaction Statuses',
			classes : getActionButtonStyle,
			permissions : ['VW_TRANSACTIONSTATUS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'TRANSACTIONSTATUS', formentitydescription:'Status', formcode:'TRANSACTIONSTATUS', mode:'renderer', title:'Transaction Statuses' },
			tip : 'Click here to list transaction status.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListUserFormDeviceButton',
			caption : 'My Form Devices',
			classes : getActionButtonStyle,
			permissions : ['VW_USERFORM_DEVICE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'USERFORM_DEVICE', formentitydescription:'User Form Device', formcode:'USERFORM_DEVICE', mode:'renderer', title:'User Form Devices', fixedfilter:[{"field":"user_id","value":"MYSELF"}] },
			tip : 'Click here to list my form devices.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListUserStatusButton',
			caption : 'User Statuses',
			classes : getActionButtonStyle,
			permissions : ['VW_USERSTATUS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'USERSTATUS', formentitydescription:'User Status', formcode:'USERSTATUS', mode:'renderer', title:'User Statuses' },
			tip : 'Click here to list user statuses.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListVideosButton',
			caption : 'Videos',
			classes : getActionButtonStyle,
			permissions : ['VW_VIDEO'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'VIDEO', formentitydescription:'Video', formcode:'VIDEO', mode:'renderer', title:'Videos' },
			tip : 'Click here to list videos.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListVideoCategoriesButton',
			caption : 'Video Categories',
			classes : getActionButtonStyle,
			permissions : ['VW_VIDEOCATEGORY'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'VIDEOCATEGORY', formentitydescription:'Video Category', formcode:'VIDEOCATEGORY', mode:'renderer', title:'Video Categories' },
			tip : 'Click here to list video categories.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListVideoSourcesButton',
			caption : 'Video Sources',
			classes : getActionButtonStyle,
			permissions : ['VW_VIDEOSOURCE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'VIDEOSOURCE', formentitydescription:'Video Source', formcode:'VIDEOSOURCE', mode:'renderer', title:'Video Sources' },
			tip : 'Click here to list video sources.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListWikiPagesButton',
			caption : 'Online Help',
			noncollapsable: true,
			classes : getActionButtonStyle,
			permissions : [],
			action : function(objActionData_a)
			{
				os.showForm('core.frmWikiForm', objActionData_a, true);
			},
			actionData : { title:APP_SHORT_NAME + ' Online Help', wiki:'index' },
			tip : 'Click here to list online help.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListWorkQueueButton',
			caption : 'Work Queue',
			noncollapsable: true,
			classes : function() { return getActionButtonStyle(); },
			permissions : ['VW_WORKQUEUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'WORKQUEUE', formentitydescription:'Work Queue', formcode:'WORKQUEUE', mode:'renderer', title:'Work Queue' },
			tip : 'Click here to show the work queue.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListWorkQueueStatusesButton',
			caption : 'Work Queue Statuses',
			classes : function() { return getActionButtonStyle(); },
			permissions : ['VW_WORKQUEUESTATUS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'WORKQUEUESTATUS', formentitydescription:'Work Queue Status', formcode:'WORKQUEUESTATUS', mode:'renderer', title:'Work Queue Statuses' },
			tip : 'Click here to show the work queue statuses.',
			type : 'toolbarbutton'
		},		
        {
			id : 'ListWorkQueueItemTypesButton',
			caption : 'Work Queue Item Types',
			classes : function() { return getActionButtonStyle(); },
			permissions : ['VW_WORKQUEUEITEMTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'WORKQUEUEITEMTYPE', formentitydescription:'Work Queue Item Type', formcode:'WORKQUEUEITEMTYPE', mode:'renderer', title:'Work Queue Item Types' },
			tip : 'Click here to show the work queue item type.',
			type : 'toolbarbutton'
		},
		
		// action tiles - projly
        {
			id : 'ListProjectBillingCategoriesButton',
			caption : 'Billing Categories',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTBILLINGCATEGORY'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTBILLINGCATEGORY', formentitydescription:'Project Billing Category', formcode:'PROJECTBILLINGCATEGORY', mode:'renderer', title:'Project Billing Categories' },
			tip : 'Click here to list project billing categories.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectCostCategoriesButton',
			caption : 'Cost Categories',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTCOSTCATEGORY'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTCOSTCATEGORY', formentitydescription:'Project Cost Category', formcode:'PROJECTCOSTCATEGORY', mode:'renderer', title:'Project Cost Categories' },
			tip : 'Click here to list project cost categories.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectButton',
			caption : 'Projects',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECT', formentitydescription:'Project', formcode:'PROJECT', mode:'renderer', title:'Projects' },
			tip : 'Click here to list projects.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectCampaignStatusButton',
			caption : 'Campaign Statuses',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTCAMPAIGNSTATUS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTCAMPAIGNSTATUS', formentitydescription:'Project Campaign Status', formcode:'PROJECTCAMPAIGNSTATUS', mode:'renderer', title:'Project Campaign Statuses' },
			tip : 'Click here to list campaign statuses.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectCampaignsButton',
			caption : 'Campaigns',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTCAMPAIGN'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTCAMPAIGN', formentitydescription:'Project Campaign', formcode:'PROJECTCAMPAIGN', mode:'renderer', title:'Project Campaigns' },
			tip : 'Click here to list project campaigns.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListProjectCampaignTasksButton',
			caption : 'Campaign Tasks',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTCAMPAIGNTASK'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTCAMPAIGNTASK', formentitydescription:'Project Campaign Task', formcode:'PROJECTCAMPAIGNTASK', mode:'renderer', title:'Project Campaign Tasks' },
			tip : 'Click here to list project campaign tasks.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectCampaignTypeButton',
			caption : 'Campaign Types',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTCAMPAIGNTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTCAMPAIGNTYPE', formentitydescription:'Project Campaign Type', formcode:'PROJECTCAMPAIGNTYPE', mode:'renderer', title:'Project Campaign Types' },
			tip : 'Click here to list campaign types.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectIssuePriorityButton',
			caption : 'Issue Priorities',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTISSUEPRIORITY'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTISSUEPRIORITY', formentitydescription:'Project Issue Priority', formcode:'PROJECTISSUEPRIORITY', mode:'renderer', title:'Project Issue Priorities' },
			tip : 'Click here to list issue priorities.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectIssueStatusButton',
			caption : 'Issue Statuses',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTISSUESTATUS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTISSUESTATUS', formentitydescription:'Project Issue Status', formcode:'PROJECTISSUESTATUS', mode:'renderer', title:'Project Issue Statuses' },
			tip : 'Click here to list project issue statuses.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectIssueTypesButton',
			caption : 'Issue Types',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTISSUETYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTISSUETYPE', formentitydescription:'Project Issue Type', formcode:'PROJECTISSUETYPE', mode:'renderer', title:'Project Issue Types' },
			tip : 'Click here to list project issue types.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectParticipantTypesButton',
			caption : 'Participant Types',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTPARTICIPANTTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTPARTICIPANTTYPE', formentitydescription:'Project Participant Type', formcode:'PROJECTPARTICIPANTTYPE', mode:'renderer', title:'Project Participant Types' },
			tip : 'Click here to list participaint types.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectPrioritiesButton',
			caption : 'Project Priorities',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTPRIORITY'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTPRIORITY', formentitydescription:'Project Priority', formcode:'PROJECTPRIORITY', mode:'renderer', title:'Project Priorities' },
			tip : 'Click here to list project priorities.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectResourceTypesButton',
			caption : 'Resource Types',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTRESOURCETYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTRESOURCETYPE', formentitydescription:'Project Resource Type', formcode:'PROJECTRESOURCETYPE', mode:'renderer', title:'Project Resource Types' },
			tip : 'Click here to list resource types.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectStatusButton',
			caption : 'Project Statuses',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTSTATUS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTSTATUS', formentitydescription:'Project Status', formcode:'PROJECTSTATUS', mode:'renderer', title:'Project Statuses' },
			tip : 'Click here to list project statuses.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectTaskPriorityButton',
			caption : 'Task Priorities',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTTASKPRIORITY'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTTASKPRIORITY', formentitydescription:'Project Task Priority', formcode:'PROJECTTASKPRIORITY', mode:'renderer', title:'Project Task Priorities' },
			tip : 'Click here to list task priorities.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectTaskStatusButton',
			caption : 'Task Statuses',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTTASKSTATUS'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTTASKSTATUS', formentitydescription:'Project Task Status', formcode:'PROJECTTASKSTATUS', mode:'renderer', title:'Project Task Statuses' },
			tip : 'Click here to list task statuses.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectTypesButton',
			caption : 'Project Types',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTTYPE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTTYPE', formentitydescription:'Project Type', formcode:'PROJECTTYPE', mode:'renderer', title:'Project Types' },
			tip : 'Click here to list project types.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectRequirementsButton',
			caption : 'Requirements',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTISSUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTISSUE', formentitydescription:'Project Requirement', formcode:'PROJECTISSUE', mode:'renderer', title:'Project Requirements', fixedfilter:[{"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuetype","value":"Requirement"}, {"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_iswishlistitem","value":"N"}, {"field":"is_completed","value":"N"}] },
			tip : 'Click here to list project requirements.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListProjectRequirementsCompletedButton',
			caption : 'Requirements (Completed)',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTISSUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTISSUE', formentitydescription:'Project Requirement', formcode:'PROJECTISSUE', mode:'renderer', title:'Project Requirements (Completed)', fixedfilter:[{"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuetype","value":"Requirement"}, {"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_iswishlistitem","value":"N"}, {"field":"is_completed","value":"Y"}] },
			tip : 'Click here to list completed and verified project requirements.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListProjectBugsButton',
			caption : 'Bugs',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTISSUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTISSUE', formentitydescription:'Project Bug', formcode:'PROJECTISSUE', mode:'renderer', title:'Project Bugs', fixedfilter:[{"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuetype","value":"Bug"}, {"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_iswishlistitem","value":"N"},{"field":"is_completed","value":"N"}] },
			tip : 'Click here to list project bugs.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListProjectBugsCompletedButton',
			caption : 'Bugs (Completed)',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTISSUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTISSUE', formentitydescription:'Project Bug', formcode:'PROJECTISSUE', mode:'renderer', title:'Project Bugs (Completed)', fixedfilter:[{"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuetype","value":"Bug"}, {"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_iswishlistitem","value":"N"},{"field":"is_completed","value":"Y"}] },
			tip : 'Click here to list completed and verified project bugs.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListProjectSupportButton',
			caption : 'Support Issues',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTISSUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTISSUE', formentitydescription:'Project Bug', formcode:'PROJECTISSUE', mode:'renderer', title:'Project Support Issues', fixedfilter:[{"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuetype","value":"Support"}, {"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_iswishlistitem","value":"N"},{"field":"is_completed","value":"N"}] },
			tip : 'Click here to list project support issues.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListProjectSupportCompletedButton',
			caption : 'Support Issues (Completed)',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTISSUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTISSUE', formentitydescription:'Project Bug', formcode:'PROJECTISSUE', mode:'renderer', title:'Project Support Issues (Completed)', fixedfilter:[{"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuetype","value":"Support"}, {"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_iswishlistitem","value":"N"},{"field":"is_completed","value":"Y"}] },
			tip : 'Click here to list completed and verified project support issues.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListProjectWishListButton',
			caption : 'Wish List',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTISSUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTISSUE', formentitydescription:'Project Wish Item', formcode:'PROJECTISSUE', mode:'renderer', title:'Project Wish List', fixedfilter:[{"field":"ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_iswishlistitem","value":"Y"}] },
			tip : 'Click here to list the project wish list.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListProjectsButton',
			caption : 'Projects',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECT', formentitydescription:'Project', formcode:'PROJECT', mode:'renderer', title:'Projects' },
			tip : 'Click here to list projects.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListProjectAllIssuesButton',
			caption : 'All Project Issues',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTISSUE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTISSUE', formentitydescription:'Project Issue', formcode:'PROJECTISSUE', mode:'renderer', title:'All Project Issues' },
			tip : 'Click here to list all project issues.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectTasksButton',
			caption : 'Tasks',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTTASK'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTTASK', formentitydescription:'Project Task', formcode:'PROJECTTASK', mode:'renderer', title:'Project Tasks', fixedfilter:[{"field":"is_completed","value":"N"},{"field":"ffe63e2a32_d72a_474c_82d0_b664eef1268a_isongoing","value":"N"}] },
			tip : 'Click here to list project tasks.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListProjectTasksCompletedButton',
			caption : 'Tasks (Completed)',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTTASK'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTTASK', formentitydescription:'Project Task', formcode:'PROJECTTASK', mode:'renderer', title:'Project Tasks (Completed)', fixedfilter:[{"field":"is_completed","value":"Y"},{"field":"ffe63e2a32_d72a_474c_82d0_b664eef1268a_isongoing","value":"N"}] },
			tip : 'Click here to list completed and verified project tasks.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListProjectTasksOngoingButton',
			caption : 'Tasks (Ongoing)',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTTASK'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTTASK', formentitydescription:'Project Task', formcode:'PROJECTTASK', mode:'renderer', title:'Project Tasks (Ongoing)', fixedfilter:[{"field":"ffe63e2a32_d72a_474c_82d0_b664eef1268a_isongoing","value":"Y"}] },
			tip : 'Click here to list ongoing project tasks.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectParticipantActivityButton',
			caption : 'Participant Activities',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTPARTICIPANTACTIVITY'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTPARTICIPANTACTIVITY', formentitydescription:'Project Participant Activity', formcode:'PROJECTPARTICIPANTACTIVITY', mode:'renderer', title:'Project Participant Activities' },
			tip : 'Click here to list project participant activities.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectParticipantsButton',
			caption : 'Participants',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTPARTICIPANT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTPARTICIPANT', formentitydescription:'Project Participant', formcode:'PROJECTPARTICIPANT', mode:'renderer', title:'Project Participants' },
			tip : 'Click here to list project participants.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectResourceActivityButton',
			caption : 'Resource Activities',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTRESOURCEACTIVITY'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTRESOURCEACTIVITY', formentitydescription:'Project Resource Activity', formcode:'PROJECTRESOURCEACTIVITY', mode:'renderer', title:'Project Resource Activities' },
			tip : 'Click here to list project resource activities.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectResourcesButton',
			caption : 'Resources',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTRESOURCE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTRESOURCE', formentitydescription:'Project Resource', formcode:'PROJECTRESOURCE', mode:'renderer', title:'Project Resources' },
			tip : 'Click here to list project resources.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListProjectStakeholdersButton',
			caption : 'Stakeholders',
			classes : getActionButtonStyle,
			permissions : ['VW_PROJECTSTAKEHOLDER'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PROJECTSTAKEHOLDER', formentitydescription:'Project Stakeholder', formcode:'PROJECTSTAKEHOLDER', mode:'renderer', title:'Project Stakeholders' },
			tip : 'Click here to list project stakeholders.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListRNDGrantButton',
			caption : 'R&D Grants',
			classes : getActionButtonStyle,
			permissions : ['VW_RNDGRANT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'RNDGRANT', formentitydescription:'R&D Grant', formcode:'RNDGRANT', mode:'renderer', title:'R&D Grants' },
			tip : 'Click here to list R&D Grants.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListRNDQuestionButton',
			caption : 'R&D Questions',
			classes : getActionButtonStyle,
			permissions : ['VW_SMQUESTION'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SMQUESTION', formentitydescription:'R&D Question', formcode:'SMQUESTION', mode:'renderer', title:'R&D Questions' },
			tip : 'Click here to list R&D Questions.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListRosterButton',
			caption : 'Roster',
			classes : getActionButtonStyle,
			permissions : ['VW_ROSTER'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'ROSTER', formentitydescription:'Roster', formcode:'ROSTER', mode:'renderer', title:'Roster' },
			tip : 'Click here to list the roster.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListRosterTemplateButton',
			caption : 'Roster Templates',
			classes : getActionButtonStyle,
			permissions : ['VW_ROSTERTEMPLATE'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'ROSTERTEMPLATE', 
			formentitydescription:'Roster Template', formcode:'ROSTERTEMPLATE', mode:'renderer', title:'Roster Template' },
			tip : 'Click here to list the roster.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListSprintsButton',
			caption : 'Sprints',
			classes : getActionButtonStyle,
			permissions : ['VW_SPRINT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SPRINT', formentitydescription:'Sprint', formcode:'SPRINT', mode:'renderer', title:'Sprints' },
			tip : 'Click here to list sprints.',
			type : 'toolbarbutton'
        },
		
		// import tiles
		{
			id : 'ListImportsButton',
			caption : 'Imports',
			classes : getActionButtonStyle,
			permissions : ['VW_IMPORT'],
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'IMPORT', formentitydescription:'Import', formcode:'IMPORT', mode:'renderer', title:'Imports' },
			tip : 'Click here to list imports.',
			type : 'toolbarbutton'
		},	
		{
			id : 'FormImportDeliveriesButton',
			caption : 'Import Deliveries',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (os.hasCapability('jqxhr') && (hasPermission(['IMPORT_RPDELIVERY'])));
			},
			action : actionImport,
			actionData : 'RPDELIVERY',
			tip : 'Click here to import deliveries.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormImportSuburbsButton',
			caption : 'Import Suburbs',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (os.hasCapability('jqxhr') && (hasPermission(['IMP_SUBURB'])));
			},
			action : actionImport,
			actionData : 'SUBURBS',
			tip : 'Click here to import suburbs.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormImportDocumentsButton',
			caption : 'Import Documents',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (os.hasCapability('jqxhr') && (hasPermission(['IMP_FILES'])));
			},
			action : actionImport,
			actionData : 'GENERIC',
			tip : 'Click here to import documents.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormImportImagesButton',
			caption : 'Import Images',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (os.hasCapability('jqxhr') && (hasPermission(['IMP_IMAGES'])));
			},
			action : actionImport,
			actionData : 'IMAGES',
			tip : 'Click here to import images.',
			type : 'toolbarbutton'
		},
		{
			id : 'FormImportDropoffPostcodeZonesButton',
			caption : 'Import Dropoff Postcode Zones',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (os.hasCapability('jqxhr') && (hasPermission(['IMPORT_RPDROPOFFPOSTCODEZONE_SET'])));
			},
			action : actionImport,
			actionData : 'RPDROPOFFPOSTCODEZONE',
			tip : 'Click here to import dropoff postcode zones.',
			type : 'toolbarbutton'
		},

		// widget tiles
		{
			id : 'WidgetCalculatorButton',
			caption : 'Calculator',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return os.isModuleLoaded('widgetcalculator');
			},
			action : actionShowWidget,
			actionData : 'widgetcalculator.wgtCalculator',
			tip : 'Click here to open the calculator.',
			type : 'toolbarbutton'
		},
		{
			id : 'WidgetClockButton',
			caption : 'Clock',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return os.isModuleLoaded('widgetclock');
			},
			action : actionShowWidget,
			actionData : 'widgetclock.wgtClock',
			tip : 'Click here to display the clock.',
			type : 'toolbarbutton'
		},

		// developer tiles
		{
			id : 'ListApplicationFormsButton',
			caption : 'Published Forms',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_SYSTEMFORM']));
			},
			action : actionShowEntityList,
			actionData : { type:'entity', entity:'systemform', formentity:'SYSTEMFORM', mode:'builder', title:'Published Forms' },
			tip : 'Click here to list published forms.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListAuthenticationPluginsButton',
			caption : 'Authentication Plugins',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_AUTHENTICATIONPLUGIN']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'AUTHENTICATIONPLUGIN', formentitydescription:'Authentication Plugin', formcode:'AUTHENTICATIONPLUGIN', mode:'renderer', title:'Authentication Plugins' },
			tip : 'Click here to list authentication plugins.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListChartPluginsButton',
			caption : 'Chart Plugins',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_CHARTPLUGIN']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'CHARTPLUGIN', formentitydescription:'Chart Plugin', formcode:'CHARTPLUGIN', mode:'renderer', title:'Chart Plugins' },
			tip : 'Click here to list chart plugins.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListIntegrationInboundPluginsButton',
			caption : 'Integration Inbound Plugins',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_INTEGRATIONINBOUNDPLUGIN']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'INTEGRATIONINBOUNDPLUGIN', formentitydescription:'Integration Inbound Plugin', formcode:'INTEGRATIONINBOUNDPLUGIN', mode:'renderer', title:'Integration Inbound Plugins' },
			tip : 'Click here to list integration inbound plugins.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListIntegrationOutboundPluginsButton',
			caption : 'Integration Outbound Plugins',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_INTEGRATIONOUTBOUNDPLUGIN']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'INTEGRATIONOUTBOUNDPLUGIN', formentitydescription:'Integration Outbound Plugin', formcode:'INTEGRATIONOUTBOUNDPLUGIN', mode:'renderer', title:'Integration Outbound Plugins' },
			tip : 'Click here to list integration outbound plugins.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListAuthenticationTypesButton',
			caption : 'Authentication Types',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_AUTHENTICATIONTYPE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'AUTHENTICATIONTYPE', formentitydescription:'Authentication Type', formcode:'AUTHENTICATIONTYPE', mode:'renderer', title:'Authentication Types' },
			tip : 'Click here to list authentication types.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListIntegrationInboundTypesButton',
			caption : 'Integration Inbound Types',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_INTEGRATIONINBOUNDTYPE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'INTEGRATIONINBOUNDTYPE', formentitydescription:'Integration Inbound Type', formcode:'INTEGRATIONINBOUNDTYPE', mode:'renderer', title:'Integration In Bound Types' },
			tip : 'Click here to list integration in bound types.',
			type : 'toolbarbutton'
		},		
		{
			id : 'ListIntegrationOutboundTypesButton',
			caption : 'Integration Outbound Types',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_INTEGRATIONOUTBOUNDTYPE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'INTEGRATIONOUTBOUNDTYPE', formentitydescription:'Integration Out Bound Type', formcode:'INTEGRATIONOUTBOUNDTYPE', mode:'renderer', title:'Integration Out Bound Types' },
			tip : 'Click here to list integration out bound types.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListChartsButton',
			caption : 'Charts',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_CHART']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'CHART', formentitydescription:'Chart', formcode:'CHART', mode:'renderer', title:'Charts' },
			tip : 'Click here to list charts.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListSecurityChartsButton',
			caption : 'Security Charts',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_SECURITYCHART']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SECURITYCHART', formentitydescription:'Security Chart', formcode:'SECURITYCHART', mode:'renderer', title:'Security Charts' },
			tip : 'Click here to list security charts.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListChartTypesButton',
			caption : 'Chart Types',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_CHARTTYPE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'CHARTTYPE', formentitydescription:'Chart Type', formcode:'CHARTTYPE', mode:'renderer', title:'Chart Types' },
			tip : 'Click here to list chart types.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListDisplayOrdersButton',
			caption : 'Display Orders',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_DISPLAYORDER']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'DISPLAYORDER', formentitydescription:'Display Order', formcode:'DISPLAYORDER', mode:'renderer', title:'Display Orders' },
			tip : 'Click here to list display orders.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListEntityButton',
			caption : 'Entities',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_ENTITY']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'ENTITY', formentitydescription:'Entity', formcode:'ENTITY', mode:'renderer', title:'Entities' },
			tip : 'Click here to list entities.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListEntityOperationDefaultButton',
			caption : 'Entity Operation Defaults',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_ENTITYOPERATION_DEFAULT']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'ENTITYOPERATION_DEFAULT', formentitydescription:'Entity Operation Default', formcode:'ENTITYOPERATION_DEFAULT', mode:'renderer', title:'Entity Operation Defaults' },
			tip : 'Click here to list entity operation default options.',
			type : 'toolbarbutton'
        }, 
		{
			id : 'ListFormTypesButton',
			caption : 'Form Types',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_FORMTYPE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FORMTYPE', formentitydescription:'FormType', formcode:'FORMTYPE', mode:'renderer', reorder : "Y", title:'Form Type' },
			tip : 'Click here to list FormDataTypes.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListFragmentsFormButton',
			caption : 'Form Fragments',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_FRAGMENT']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FRAGMENT', formentitydescription:'Form Fragment', formcode:'FRAGMENT', mode:'renderer', title:'Form Fragments' },
			tip : 'Click here to list form fragments.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListIconButton',
			caption : 'Icons',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_ICON']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'ICON', formentitydescription:'Icons', formcode:'ICON', mode:'renderer', title:'Icons' },
			tip : 'Click here to list icons.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListMobileFormsButton',
			caption : 'Mobile Forms',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_MOBILEFORM']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'MOBILEFORM', formentitydescription:'Mobile Form', formcode:'MOBILEFORM', mode:'renderer', title:'Mobile Form' },
			tip : 'Click here to list my mobile forms.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListNavItemButton',
			caption : 'Nav Items',
			classes : getActionButtonStyle,
			permissions :function ()
			{
				return (isDeveloper() && hasPermission(['VW_NAVITEM']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'NAVITEM', formentitydescription:'Nav Item', formcode:'NAVITEM', mode:'renderer', title:'Nav Items'},
			tip : 'Click here to list system nav items.',
			type : 'toolbarbutton'
        },
		{
			id : 'ListPermissionCategoriesButton',
			caption : 'Permission Categories',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_PERMISSIONCATEGORY']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PERMISSIONCATEGORY', formentitydescription:'Category', formcode:'PERMISSIONCATEGORY', mode:'renderer', title:'Permission Categories' },
			tip : 'Click here to list permission categories.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListPermissionsButton',
			caption : 'Permissions',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_PERMISSION']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'PERMISSION', formentitydescription:'Permission', formcode:'PERMISSION', mode:'renderer', title:'Permissions' },
			tip : 'Click here to list permissions.',
			type : 'toolbarbutton'
		},		
        {
			id : 'ListSettingModulesButton',
			caption : 'Setting Modules',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_SETTINGMODULE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SETTINGMODULE', formentitydescription:'Setting Module', formcode:'SETTINGMODULE', mode:'renderer', title:'Setting Modules' },
			tip : 'Click here to list setting modules.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListSystemButton',
			caption : 'Systems',
			classes : getActionButtonStyle,
			permissions :function ()
			{
				return (isDeveloper() && hasPermission(['VW_SYSTEM']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SYSTEM', formentitydescription:'System', formcode:'SYSTEM', mode:'renderer', title:'Systems'},
			tip : 'Click here to list systems.',
			type : 'toolbarbutton'
        },
        {
			id : 'ListSystemBuildButton',
			caption : 'System Builds',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_SYSTEMBUILD']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SYSTEMBUILD', formentitydescription:'System Build', formcode:'SYSTEMBUILD', mode:'renderer', title:'System Builds'},
			tip : 'Click here to list system buids.',
			type : 'toolbarbutton'
        },
		{
			id : 'ListSystemFlagTypesButton',
			caption : 'System Flag Types',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_SYSTEMFLAGTYPE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SYSTEMFLAGTYPE', formentitydescription:'System Flag Type', formcode:'SYSTEMFLAGTYPE', mode:'renderer', title:'System Flag Types' },
			tip : 'Click here to list system flag types.',
			type : 'toolbarbutton'
		},
        {
			id : 'ListSystemModuleButton',
			caption : 'System Modules',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_SYSTEMMODULE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'SYSTEMMODULE', formentitydescription:'System Module', formcode:'SYSTEMMODULE', mode:'renderer', reorder:'Y', title:'System Modules'},
			tip : 'Click here to list system modules.',
			type : 'toolbarbutton'
        },

		// developer form builder tiles
		{
			id : 'ListBuilderLayoutButton',
			caption : 'Form Builder Layouts',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_FORMLAYOUT']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FORMLAYOUT', formentitydescription:'Form Layout', formcode:'FORMLAYOUT', mode:'renderer', title:'Form Layouts', fixedfilter:[{"field":"g38e1d8b1_b01e_44d6_bd90_102f0aa92f60_formtype","value":"Form Builder"}] },
			tip : 'Click here to list form layouts.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListFormDataTypesButton',
			caption : 'Form Datatypes',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_FORMDATATYPE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FORMDATATYPE', formentitydescription:'FormDataTypes', formcode:'FORMDATATYPE', mode:'renderer', title:'FormDataTypes' },
			tip : 'Click here to list FormDataTypes.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListFormSectionTemplatesButton',
			caption : 'Form Section Templates',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_FORMSECTIONTEMPLATE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FORMSECTIONTEMPLATE', formentitydescription:'FormSectionTemplate', formcode:'FORMSECTIONTEMPLATE', mode:'renderer', reorder : "Y", title:'FormSectionTemplates' },
			tip : 'Click here to list FormDataTypes.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListFormLayoutButton',
			caption : 'Form Layouts',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_FORMLAYOUT']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FORMLAYOUT', formentitydescription:'Form Layout', formcode:'FORMLAYOUT', mode:'renderer', title:'Form Layouts', fixedfilter:[{"field":"g38e1d8b1_b01e_44d6_bd90_102f0aa92f60_formtype","value":"Data Form"}] },
			tip : 'Click here to list form builder form layouts.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListFormLayoutsAllButton',
			caption : 'Form Layouts (All)',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_FORMLAYOUT']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FORMLAYOUT', formentitydescription:'Form Layout', formcode:'FORMLAYOUT', mode:'renderer', title:'Form Layouts (All)' },
			tip : 'Click here to list all form layouts.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListReportBuilderLayoutButton',
			caption : 'Report Builder Layouts',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_FORMLAYOUT']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FORMLAYOUT', formentitydescription:'Form Layouts', formcode:'FORMLAYOUT', mode:'renderer', title:'Report Builder Layouts', fixedfilter:[{"field":"g38e1d8b1_b01e_44d6_bd90_102f0aa92f60_formtype","value":"Report Builder"}] },
			tip : 'Click here to list report builder form layouts.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListSystemLayoutButton',
			caption : 'Form System Layouts',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_FORMLAYOUT']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'FORMLAYOUT', formentitydescription:'Form Layout', formcode:'FORMLAYOUT', mode:'renderer', title:'System Form Layouts', fixedfilter:[{"field":"g38e1d8b1_b01e_44d6_bd90_102f0aa92f60_formtype","value":"System Form"}] },
			tip : 'Click here to list system form layouts.',
			type : 'toolbarbutton'
		},
		
		// developer report builder tiles
		{
			id : 'ListReportDataTypesButton',
			caption : 'Report Datatypes',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_REPORTDATATYPE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'REPORTDATATYPE', formentitydescription:'ReportDataTypes', formcode:'REPORTDATATYPE', mode:'renderer', title:'Report Datatypes' },
			tip : 'Click here to list report datatypes.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListReportLayoutButton',
			caption : 'Report Layouts',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_REPORTLAYOUT']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'REPORTLAYOUT', formentitydescription:'Report Layout', formcode:'REPORTLAYOUT', mode:'renderer', title:'Report Layouts'},
			tip : 'Click here to list report layouts.',
			type : 'toolbarbutton'
		},
		{
			id : 'ListReportSectionTemplatesButton',
			caption : 'Report Section Templates',
			classes : getActionButtonStyle,
			permissions : function ()
			{
				return (isDeveloper() && hasPermission(['VW_REPORTSECTIONTEMPLATE']));
			},
			action : actionShowEntityList,
			actionData : { type:'form', entity:'systemform', formentity:'REPORTSECTIONTEMPLATE', formentitydescription:'ReportSectionTemplate', formcode:'REPORTSECTIONTEMPLATE', mode:'renderer', reorder : 'Y', title:'Report Section Templates' },
			tip : 'Click here to list report section templates.',
			type : 'toolbarbutton'
		}

		// developer RnD Tiles
	];

	// to cater for future multiple alternate maps
	this.getMap = function(strType_a)
	{
		var arrResult = m_arrMap;

		return arrResult;
	};

	this.getTiles = function()
	{
		return m_arrTiles;
	};

	// HELPERS ============================================================================

	function closeTab()
	{
		var strPrompt = 'Are you sure you wish to close this window?\n\nPlease be sure you have saved your data before continuing.';
		os.dialogConfirm(strPrompt, function ()
		{
			os.setUnloadPrompt(false);
			os.closeTab();
		});
	}

	function getActionButtonStyle()
	{
		var strResult = 'gs-darkgrey-background-colour';

		if (m_strStyle === 'bootstrap')
		{
			strResult = 'gs-menu-action gs-menu-hover';
		}

		return strResult;
	}

	function getGroupStyle()
	{
		var strResult = 'gs-darkblue-background-colour';

		if (m_strStyle === 'bootstrap')
		{
			strResult = 'gs-menu-group gs-menu-hover';
		}

		return strResult;
	}

	function getPageButtonStyle()
	{
		var strResult = 'gs-green-background-colour';

		if (m_strStyle === 'bootstrap')
		{
			strResult = 'gs-green-background-colour';
		}

		return strResult;
	}

	function getRootStyle()
	{
		var strResult = 'gs-lightgrey-background-colour';

		if (m_strStyle === 'bootstrap')
		{
			strResult = 'gs-lightgrey-background-colour';
		}

		return strResult;
	}

	function getSeparatorStyle()
	{
		var strResult = 'gs-separator';

		if (m_strStyle === 'bootstrap')
		{
			strResult = 'gs-separator';
		}

		return strResult;
	}

	function isDeveloper()
	{
		return (hasPermission(['DEVELOPER']) && DEVELOPER === 'TRUE');
		//return (DEVELOPER === 'TRUE');
	}

	function logout()
	{
		var strPrompt = 'Are you sure you wish to logout?<br><br>Please be sure you have saved your data before continuing.';
		os.dialogConfirm(strPrompt, function ()
		{
			//os.closeAllForms(function()
			//{
			var objJSON = os.ajaxRequestCreate('public_logout', []);
			os.ajaxCall(URL_WEBSERVICE, objJSON, logoutSuccess, os.ajaxError);
			//});
		});
	}

	function logoutSuccess(objResponse_a)
	{
		os.logout('', 'inc-nav');
	}

	function clientReturn()
	{
		var strPrompt = 'Are you sure you wish to return to your previous client?<br><br>Please be sure you have saved your data before continuing.';
		os.dialogConfirm(strPrompt, function ()
		{
			//os.closeAllForms(function()
			//{
			var objJSON = os.ajaxRequestCreate('public_return', []);
			os.ajaxCall(URL_WEBSERVICE, objJSON, returnSuccess, os.ajaxError);
			//});
		});
	}

	function returnSuccess(objResponse_a)
	{
		doNothing();
	}

	function openWindow(strActionData_a)
	{
		window.open(strActionData_a, '_blank');
	}

	// ACTIONS ============================================================================

	function actionGoToURL(strURL_a)
	{
		os.gotoURL(strURL_a);
	}

	function actionImport(strMode_a)
	{
		//os.showForm('core.frmUpload', 'mode=' + strMode_a);
		os.showForm('core.frmUpload', 'mode=' + strMode_a, true, 0, 0, true);
	}

	function actionImportBatch(strMode_a)
	{
		//os.showForm('core.frmUpload', 'mode=' + strMode_a);
		os.showForm('core.frmUpload', 'nobatch&mode=' + strMode_a, true, 0, 0, true);
	}
	
	function actionShowEntityForm(objActionData_a)
	{
		os.showForm('entity.frmForm', objActionData_a);
	}

    function actionShowEntityList(arrParams_a)
	{
		os.showForm('entity.frmLister', arrParams_a);
	}
	
	// Data Tables Compatible
    function actionShowEntityList_DEPRECATED(arrParams_a)
	{
        //{ type:'form', entity:'dataform', formentity:'dataform', mode:'builder', title:'Form Templates' },
		var strParameters = 'type=' + encodeURIComponent(arrParams_a.type) +  '&entity=' + encodeURIComponent(arrParams_a.entity) + '&formentity=' + encodeURIComponent(arrParams_a.formentity) + '&mode=' + encodeURIComponent(arrParams_a.mode) + '&formcode=' + encodeURIComponent(arrParams_a.formcode) + '&title=' + encodeURIComponent(arrParams_a.title);

		if (arrParams_a.searchkeyword !== undefined)
		{
			strParameters += '&searchkeyword=' + encodeURIComponent(arrParams_a.searchkeyword);
		}

        if(arrParams_a.fixedfilter !== undefined) {
           strParameters += '&fixedfilter=' +  encodeURIComponent(JSON.stringify(arrParams_a.fixedfilter));
        }

		os.showForm('entity.frmLister',  strParameters );

        //os.showForm('entity.frmLister', 'type=' + encodeURIComponent(arrParams_a.type) + '&entity=' + encodeURIComponent(arrParams_a.entity) + '&mode=' + encodeURIComponent(arrParams_a.mode) + '&title=' + encodeURIComponent(arrParams_a.title));
	}

	function actionShowCustomForm(objActionData_a)
	{
		os.showForm('core.frmCustom', objActionData_a);
	}

	// legacy Lister with parameters
	function actionShowListLegacy(strParams_a)
	{
		os.showForm('core.frmLister', strParams_a);
	}

	// legacy Lister without parameters
	function actionShowListLegacyActionData(strActionData_a)
	{
		os.showForm('core.frmLister', 'mode=' + strActionData_a);
	}

	function actionShowWidget(strActionData_a)
	{
		os.showForm(strActionData_a);
	}

	function actionUploadFileSingle(strActionData_a)
	{
		singleFileUpload(os, "", "import_generic", "");
	}

	function actionUploadFile(strActionData_a)
	{
		os.showFormPopup('core.frmUpload', 'mode=images&flags=select%20nobatch', function (objResult_a)
		{
			//alert(JSON.stringify(objResult_a));

		}, false, true);
	}

	function actionWebServiceInvocation(strActionData_a)
	{
        var objJSON = os.ajaxRequestCreate(strActionData_a.webservicename, strActionData_a.webserviceparameters);
		os.ajaxCall(URL_WEBSERVICE, objJSON, doNothing, os.ajaxError);    
	}

	function actionPrintReportBuilder()
	{
		var objJSON = os.ajaxRequestCreate('print_printstuff',
				[
					{
						"name" : "printjobs",
						"value" :
						[
							{
								"type" : "reportbuilder",
								"preview" : "N",
								"filter" :
								[
									{
										"field" : "reportbuilder_id",
										"value" : "Q0wyVExuN0lLVy9sRGUyNjgxclBjOUhzdkpTblUyVkxzTno0WUc4OW9pRT0="
									},									
									{
										"field" : "reportcode",
										"value" : "REPORTADD1"
									},
									{
										"field" : "parameters",
										"value" : [ 
											        {"name": "USERID",  "value": "5"}
												  ]
									}


								]
							}
						]
					}
				]);
		os.ajaxCall(URL_WEBSERVICE, objJSON, reportBuiderLayoutPrinted, os.ajaxError);
	}	

	function actionTransmitIntegrationTask(strActionData_a)
	{
        var objJSON = os.ajaxRequestCreate('integration_taskcreate', [{
            "name" : "outbound",
            "value" : "AWAFAPI-XMIT-GENDER"
        }]);
        
		os.ajaxCall(URL_WEBSERVICE, objJSON, function(objResponse_a) {
            console.log(objResponse_a);
        }, os.ajaxError);    
	}

	function reportBuiderLayoutPrinted(objResponse_a)
	{
		var strMessage = "none";
		alert('PRINT REPORT BUILDER EXECUTED');
		os.dialogAlert(strMessage, function (){});
		os.dialogAlert(JSON.stringify(objResponse_a), function (){});

		var m_strFormID  = 'xxxxxxxxxxxxxxxx';
		os.broadcast(m_strFormID, 'printer', 'ENTITY_REPORTBUILDER' + 'printed');
		os.broadcast(m_strFormID, 'printer', 'ENTITY_PRINTJOB');
		os.dialogAlert('Report Builder Layout was printed.', function ()
		{}
		
		);		
	}	
}
