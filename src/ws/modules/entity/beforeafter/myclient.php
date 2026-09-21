<?php

// $strFormDataID_a = MYCLIENT

// code in before display add events:
//		default fields within the JSON for display purposes before a user starts filling in a form
//
// code in after events: 
//		exposing fields
//		populating manually created fields
//
// code in before events: 
// 		modifying the json that is to be stored (it is stored automatically)
//		validation such as uniqueness (don't forget to put unique indexes on field combinations you need to be unique)
//
// event order:  
//		before events, saving of json and common fields, after events, transfer of exposed field's values
//
function beforeDisplayAddUpdate_myclient($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_myclient($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_myclient($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameMyClient = getTableNameEntity("myclient", false);

	$arrJSONData = $arrJSONData_a;
	$strClientID = '';
	
	$blnSystem = $_SESSION['server_loggedin_system'];
	
	if (dependencies('security/clientInitialise,security/accountUpdateFromClient'))
	{
		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "g52b14f56-ab19-4418-bf74-b25b5c2519ee", "CODE");
		$strCode = $arrJSONField['p_value'];

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "g52b14f56-ab19-4418-bf74-b25b5c2519ee", "DESCRIPTION");
		$strDescription = $arrJSONField['p_value'];

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "g52b14f56-ab19-4418-bf74-b25b5c2519ee", "EMAILADDRESS");
		$strEmailAddress = $arrJSONField['p_value'];

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "g52b14f56-ab19-4418-bf74-b25b5c2519ee", "PHONENUMBER");
		$strPhoneNumber = $arrJSONField['p_value'];

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "g52b14f56-ab19-4418-bf74-b25b5c2519ee", "ISENABLED");
		$strIsEnabled = $arrJSONField['p_value'];

		if (!$blnSystem)
		{
			if ($blnUpdate_a) 
			{
				$strClientID = $strFormDataID_a;	// TODO get clientid from myclientid
				// accountUpdateFromClient($objConn_a, $strClientID, $strCode, $strDescription, $strEmailAddress, $strPhoneNumber, $strIsEnabled);
			}
			else
			{
				if (ENABLE_CLIENTDATABASES == 'TRUE')
				{
					$objConnClient = dbOpen(DBCLIENTMAIN_HOSTNAME, DBCLIENTMAIN_LOGIN, DBCLIENTMAIN_PASSWORD, DBCLIENTMAIN_DATABASENAME);		
					$strClientID = clientInitialise($objConnClient, true, "", $strCode, $strDescription, $strEmailAddress, $strPhoneNumber, $strIsEnabled, DEFAULTADMINLOGIN, '', DEFAULTADMINLOGIN, DEFAULTADMINPASSWORD, "", "");
					
					$strSQL = "select code returnvalue from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
					$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
					$strClientCode = dbReadValue($objConnClient, $strSQL, __FUNCTION__);
					dbClose($objConnClient);

					// store the actualhost, actualdb and actual client_id in MYCLIENT
					$strSQL = "update ~TABLENAMEMYCLIENT~ set code = '~CLIENTCODE~', is_defined = 'Y', actualhostname = '~HOSTNAME~', actualdbname = '~DBNAME~', actualclient_id = ~CLIENTID~ where id = ~MYCLIENTID~";
					$strSQL = str_replace('~TABLENAMEMYCLIENT~', ff($strTableNameMyClient), $strSQL);
					$strSQL = str_replace('~MYCLIENTID~', ff($strFormDataID_a), $strSQL);
					$strSQL = str_replace('~HOSTNAME~', ff(DBCLIENTMAIN_HOSTNAME), $strSQL);
					$strSQL = str_replace('~DBNAME~', ff(DBCLIENTMAIN_DATABASENAME), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
					$strSQL = str_replace('~CLIENTCODE~', ff($strClientCode), $strSQL);
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				}
				else
				{
					$strClientID = clientInitialise($objConn_a, true, "", $strCode, $strDescription, $strEmailAddress, $strPhoneNumber, $strIsEnabled, DEFAULTADMINLOGIN, '', DEFAULTADMINLOGIN, DEFAULTADMINPASSWORD, "", "");
					
					$strSQL = "select code returnvalue from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
					$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
					$strClientCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

					// store the actualhost, actualdb and actual client_id in MYCLIENT
					$strSQL = "update ~TABLENAMEMYCLIENT~ set code = '~CLIENTCODE~', is_defined = 'Y', actualhostname = '~HOSTNAME~', actualdbname = '~DBNAME~', actualclient_id = ~CLIENTID~ where id = ~MYCLIENTID~";
					$strSQL = str_replace('~TABLENAMEMYCLIENT~', ff($strTableNameMyClient), $strSQL);
					$strSQL = str_replace('~MYCLIENTID~', ff($strFormDataID_a), $strSQL);
					$strSQL = str_replace('~HOSTNAME~', ff(DBSYSTEMMAIN_HOSTNAME), $strSQL);
					$strSQL = str_replace('~DBNAME~', ff(DBSYSTEMMAIN_DATABASENAME), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
					$strSQL = str_replace('~CLIENTCODE~', ff($strClientCode), $strSQL);
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				}
			}
		}
	} 

	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_myclient($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_myclient($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	$blnSystem = $_SESSION['server_loggedin_system'];
	
	if (!$blnSystem)
	{
		// TODO delete from client db
		$strTableNameAccount = getTableNameEntity("account", false);
		$strTableNameClient = getTableNameEntity("client", false);
		$strTableNameClientProduct = getTableNameEntity("clientproduct", false);
		$strTableNameDevice = getTableNameEntity("device", false);
		$strTableNameDeviceLog = getTableNameEntity("devicelog", false);
		$strTableNameReceipt = getTableNameEntity("receipt", false);
		$strTableNameDocument = getTableNameEntity("document", false);
		$strTableNamePrintQueue = getTableNameEntity("printqueue", false);
		$strTableNameProfile = getTableNameEntity("profile", false);
		$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
		$strTableNameResetPassword = getTableNameEntity("resetpassword", false);
		$strTableNameUser = getTableNameEntity("user", false);
		$strTableNameUserBranch = getTableNameEntity("user_branch", false);
		$strTableNameUserProfile = getTableNameEntity("user_profile", false);
		$strTableNameClientSetting = getTableNameEntity("clientsetting", false);
		$strTableNameDispatchSetting = getTableNameEntity("dispatchsetting", false);

		$strTableNameUserSetting = getTableNameEntity("usersetting", false);
		$strTableNameTransaction = getTableNameEntity("transaction", false);
		$strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
		$strTableNameTransactionLine = getTableNameEntity("transactionline", false);
		$strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
		$strTableNameVideo = getTableNameEntity("video", false);
		$strTableNameVideoCategory = getTableNameEntity("videocategory", false);
		$strTableNameVideoSource = getTableNameEntity("videosource", false);
		$strTableNameMessageTemplate = getTableNameEntity("messagetemplate", false);
		$strTableNameProjectType = getTableNameEntity("projecttype", false);
		$strTableNameProject = getTableNameEntity("project", false);
		
		$strTableNameAccountHistory = getTableNameEntity("account", true);
		$strTableNameClientHistory = getTableNameEntity("client", true);
		$strTableNameClientProductHistory = getTableNameEntity("clientproduct", true);
		$strTableNameDeviceHistory = getTableNameEntity("device", true);
		$strTableNameDeviceLogHistory = getTableNameEntity("devicelog", true);
		$strTableNameDocumentHistory = getTableNameEntity("document", true);
		$strTableNamePrintQueueHistory = getTableNameEntity("printqueue", true);
		$strTableNameProfileHistory = getTableNameEntity("profile", true);
		$strTableNameProfilePermissionHistory = getTableNameEntity("profile_permission", true);
		$strTableNameResetPasswordHistory = getTableNameEntity("resetpassword", true);
		$strTableNameUserHistory = getTableNameEntity("user", true);
		$strTableNameUserBranchHistory = getTableNameEntity("user_branch", true);
		$strTableNameUserProfileHistory = getTableNameEntity("user_profile", true);
		$strTableNameClientSettingHistory = getTableNameEntity("clientsetting", true);
		$strTableNameDispatchSettingHistory = getTableNameEntity("dispatchsetting", true);
		$strTableNameUserSettingHistory = getTableNameEntity("usersetting", true);
		$strTableNameTransactionHistory = getTableNameEntity("transaction", true);
		$strTableNameTransactionPendingHistory = getTableNameEntity("transactionpending", true);
		$strTableNameTransactionLineHistory = getTableNameEntity("transactionline", true);
		$strTableNameTransactionLinePendingHistory = getTableNameEntity("transactionlinepending", true);	
		$strTableNameVideoSourceHistory = getTableNameEntity("videosource", true);
		$strTableNameVideoHistory = getTableNameEntity("video", true);
		$strTableNameVideoCategoryHistory = getTableNameEntity("videocategory", true);
		$strTableNameMessageTemplateHistory = getTableNameEntity("messagetemplate", true);
		$strTableNameProjectTypeHistory = getTableNameEntity("projecttype", true);
		$strTableNameProjectHistory = getTableNameEntity("project", true);	
																		   
		//temp table
		
		$blnResult = false;
		// todo alert if client is not is_defined
		

		$strSQL = "select is_defined returnvalue from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
		$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
		$blnIsDefined = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

		if ($blnIsDefined) 
		{
			// delete the history tables first (note commented out tables are due to them not yet being entity-based)

			$strSQL = "delete from ~TABLENAMECLIENTSETTINGHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMECLIENTSETTINGHISTORY~', ff($strTableNameClientSettingHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);	

			$strSQL = "delete from ~TABLENAMEDISPATCHSETTINGHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEDISPATCHSETTINGHISTORY~', ff($strTableNameDispatchSettingHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);	

			$strSQL = "delete from ~TABLENAMEPROJECTHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEPROJECTHISTORY~', ff($strTableNameProjectHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEPROJECTTYPEHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEPROJECTTYPEHISTORY~', ff($strTableNameProjectTypeHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEDOCUMENTHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEDOCUMENTHISTORY~', ff($strTableNameDocumentHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);			

			$strSQL = "delete from ~TABLENAMEVIDEOHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEVIDEOHISTORY~', ff($strTableNameVideoHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMEVIDEOSOURCEHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEVIDEOSOURCEHISTORY~', ff($strTableNameVideoSourceHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEVIDEOCATEGORYHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEVIDEOCATEGORYHISTORY~', ff($strTableNameVideoCategoryHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEMESSAGETEMPLATEHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEMESSAGETEMPLATEHISTORY~', ff($strTableNameMessageTemplateHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMETRANSACTIONLINEPENDINGHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDINGHISTORY~', ff($strTableNameTransactionLinePendingHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMETRANSACTIONLINEHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINEHISTORY~', ff($strTableNameTransactionLineHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMETRANSACTIONPENDINGHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMETRANSACTIONPENDINGHISTORY~', ff($strTableNameTransactionPendingHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);		

			$strSQL = "delete from ~TABLENAMETRANSACTIONHISTORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMETRANSACTIONHISTORY~', ff($strTableNameTransactionHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		

			$strSQL = "delete from ~TABLENAMECLIENTSETTING~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMECLIENTSETTING~', ff($strTableNameClientSetting), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);			

			$strSQL = "delete from ~TABLENAMEDISPATCHSETTING~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEDISPATCHSETTING~', ff($strTableNameDispatchSetting), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);			

			$strSQL = "delete from ~TABLENAMEUSERBRANCH~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEUSERBRANCH~', ff($strTableNameUserBranchHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEUSERPROFILE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEUSERPROFILE~', ff($strTableNameUserProfileHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEDEVICELOG~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEDEVICELOG~', ff($strTableNameDeviceLogHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDeviceHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMECLIENTPRODUCT~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProductHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccountHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEUSERSETTING~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEUSERSETTING~', ff($strTableNameUserSettingHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEPROFILEPERMISSION~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermissionHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMERESETPASSWORD~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMERESETPASSWORD~', ff($strTableNameResetPasswordHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMEPROFILE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfileHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMEPRINTQUEUE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEPRINTQUEUE~', ff($strTableNamePrintQueueHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMEUSER~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUserHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
					
			$strSQL = "delete from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClientHistory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			
			
			
			// delete the non-history tables
			$strSQL = "delete from ~TABLENAMEPROJECT~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEPROJECT~', ff($strTableNameProject), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);		

			$strSQL = "delete from ~TABLENAMETRANSACTIONLINEPENDING~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMETRANSACTIONLINE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMETRANSACTIONPENDING~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);		

			$strSQL = "delete from ~TABLENAMETRANSACTION~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
			$strSQL = "delete from ~TABLENAMERECEIPT~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMERECEIPT~', ff($strTableNameReceipt), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEDOCUMENT~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMEPROJECTTYPE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEPROJECTTYPE~', ff($strTableNameProjectType), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);	

			$strSQL = "delete from ~TABLENAMEVIDEO~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEVIDEO~', ff($strTableNameVideo), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);		

			$strSQL = "delete from ~TABLENAMEVIDEOSOURCE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEVIDEOSOURCE~', ff($strTableNameVideoSource), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);			

			$strSQL = "delete from ~TABLENAMEVIDEOCATEGORY~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEVIDEOCATEGORY~', ff($strTableNameVideoCategory), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEMESSAGETEMPLATE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEMESSAGETEMPLATE~', ff($strTableNameMessageTemplate), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
					
			$strSQL = "delete from ~TABLENAMEDATACHANGE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEDATACHANGE~', CORE_DATACHANGE, $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMECLIENTPRODUCT~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMEUSERBRANCH~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEUSERBRANCH~', ff($strTableNameUserBranch), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEUSERPROFILE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEUSERPROFILE~', ff($strTableNameUserProfile), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "update ~TABLENAMEACCOUNT~ set user_id = null where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEDEVICELOG~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEDEVICELOG~', ff($strTableNameDeviceLog), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEESB~ where client_id = ~CLIENTID~";
			$strSQL = str_replace("~TABLENAMEESB~", CORE_ESB, $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEESBBROADCASTER~ where client_id = ~CLIENTID~";
			$strSQL = str_replace("~TABLENAMEESBBROADCASTER~", CORE_ESBBROADCASTER, $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEESBLISTENER~ where client_id = ~CLIENTID~";
			$strSQL = str_replace("~TABLENAMEESBLISTENER~", CORE_ESBLISTENER, $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMEUSERSETTING~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEUSERSETTING~', ff($strTableNameUserSetting), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEPROFILEPERMISSION~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMERESETPASSWORD~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMERESETPASSWORD~', ff($strTableNameResetPassword), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMEPROFILE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMEPRINTQUEUE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEPRINTQUEUE~', ff($strTableNamePrintQueue), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMEUSER~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		}
	}
}

function afterDelete_myclient($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_myclient($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	// $strTableNameClient = getTableNameEntity("client", false);

	// $strSQL = "select id from ~TABLENAMECLIENT~ where jsondata is null";
	// $strSQL = str_replace("~TABLENAMECLIENT~", ff($strTableNameClient), $strSQL);
	// $objResultLoop = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	// while ($arrRowLoop = dbReadRecord($objResultLoop)) {
		// $strClientID = $arrRowLoop['id'];

		// $strCode = "";
		// $strDescription = "";
		// $strEmailAddress = "";
		// $strIsEnabled = "";

		// $strSQL = "select 8d9bfb3a_3eb0_4620_ad86_af7b4e222325_code code, 8d9bfb3a_3eb0_4620_ad86_af7b4e222325_description description, 8d9bfb3a_3eb0_4620_ad86_af7b4e222325_emailaddress email_address, 8d9bfb3a_3eb0_4620_ad86_af7b4e222325_isenabled is_enabled, is_defined from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
		// $strSQL = str_replace("~TABLENAMECLIENT~", ff($strTableNameClient), $strSQL);
		// $strSQL = str_replace("~CLIENTID~", ff($strClientID), $strSQL);
		// $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		// if ($arrRow = dbReadRecord($objResult)) {
			// $strCode = $arrRow['code'];
			// $strDescription = $arrRow['description'];
			// $strEmailAddress = $arrRow['email_address'];
			// $strIsEnabled = $arrRow['is_enabled'];
		// }
		// dbCloseRecordset($objResult);

		// $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "CLIENT");
		
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g52b14f56-ab19-4418-bf74-b25b5c2519ee", "CODE", $strCode);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g52b14f56-ab19-4418-bf74-b25b5c2519ee", "DESCRIPTION", $strDescription);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g52b14f56-ab19-4418-bf74-b25b5c2519ee", "EMAILADDRESS", $strEmailAddress);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g52b14f56-ab19-4418-bf74-b25b5c2519ee", "ISENABLED", $strIsEnabled);

		// $strJSONData = json_encode($arrJSONData);

		// dbBeginTrans($objConn_a, __FUNCTION__);

		// $strSQL = "update ~TABLENAMECLIENT~ set jsondata = '~JSONDATA~' where id = ~CLIENTID~";
		// $strSQL = str_replace("~TABLENAMECLIENT~", ff($strTableNameClient), $strSQL);
		// $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		// $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		// dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		// exposeEntityData($objConn_a, 'SYSTEMFORM', 'CLIENT', $strClientID, $strJSONData);
		
		// dbEndTrans($objConn_a, __FUNCTION__);
	// }
	// dbCloseRecordset($objResultLoop);
}

