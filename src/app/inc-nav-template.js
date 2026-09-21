function JNav(objOS_a, strStyle_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strClientName = os.getProperty('clientname');
	var m_strStyle = strStyle_a;

	// note: these flags should not be used for permissions, use the permissions in the permission table for permissions
	var m_blnIsBatchClient = os.toBoolean(os.getProperty('batch'));
	var m_blnIsDefaultClient = os.toBoolean(os.getProperty('default'));
	var m_blnIsEmployer = os.toBoolean(os.getProperty('isemployer'));
	var m_blnIsIndividual = os.toBoolean(os.getProperty('isindividual'));
	var m_blnIsOwnerClient = os.toBoolean(os.getProperty('owner'));
	var m_blnIsPublic = os.toBoolean(os.getProperty('public'));
	var m_blnIsDeveloper = os.toBoolean(os.getProperty('developer'));
	var m_blnIsSysAdmin = os.toBoolean(os.getProperty('sysadmin'));

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

	var m_arrTiles = [%INC-NAV-JSON%];

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

	function actionShowForm(strActionData_a)
	{
		os.showForm(strActionData_a);
	}

	function actionShowList(strActionData_a)
	{
		os.showForm('core.frmLister', 'mode=' + strActionData_a);
	}

	function actionShowListParams(strParams_a)
	{
		os.showForm('core.frmLister', strParams_a);
	}

    /*
     * this is just to frmLister using DataTables grid lib
     */
    function actionShowEntityList(arrParams_a)
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

    /*
     * this is just to frmLister using DataTables grid lib
     */
    // function actionShowEntityList(arrParams_a)
	// {
        // var strMode   = '';
        // var strType   = arrParams_a.type;
        // var strEntity = arrParams_a.entity;

        // if( arrParams_a.mode !== undefined ) {
            // strMode = arrParams_a.mode;
        // }



        // os.showForm('entity.frmLister', 'type=' + encodeURIComponent(strType) + '&entity=' + encodeURIComponent(strEntity) + '&mode=' + encodeURIComponent(strMode));
	// }

	function actionShowCustomForm(objActionData_a)
	{
		os.showForm('core.frmCustom', objActionData_a);
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

	function closeTab()
	{
		var strPrompt = 'Are you sure you wish to close this window?\n\nPlease be sure you have saved your data before continuing.';
		os.dialogConfirm(strPrompt, function ()
		{
			os.setUnloadPrompt(false);
			os.closeTab();
		}
		);
	}

	function logoutSuccess(objResponse_a)
	{
		os.logout('', 'inc-nav');
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
		}
		);
	}

	function openWindow(strActionData_a)
	{
		window.open(strActionData_a, '_blank');
	}

}
