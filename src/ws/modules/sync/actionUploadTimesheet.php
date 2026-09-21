<?php

function actionUploadTimesheet($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) { 
    
    $blnResult = true;
    $strResult = "";
    /*
    if (dependencies('sync/timesheetSyncAdd')) {
        // permission check
        if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

		// parameters
        $arrTimesheetList = getJSONParameter($arrParameters_a, 'timesheetlist');

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strUserID = $_SESSION['server_loggedin_userid'];

		dbBeginTrans($objConn_a, __FUNCTION__);

        for ($intI = 0; $intI < count($arrTimesheetList); $intI++) {
			$strGUID = $arrTimesheetList[$intI]['guid'];
            $strCode = $arrTimesheetList[$intI]['code'];
            $strTitle = $arrTimesheetList[$intI]['title'];
            $strOnDuty = $arrTimesheetList[$intI]['onduty'];
            $strNotes = $arrTimesheetList[$intI]['notes'];
            $strStatus = $arrTimesheetList[$intI]['status'];
            $strSynced = "Y";
            $strCreateDateTime = $arrTimesheetList[$intI]['createdatetime'];
            $strModifyDateTime = $arrTimesheetList[$intI]['modifydatetime'];

			$strSQL = "select id returnvalue from sync_tbltimesheet where client_id = ~CLIENTID~ and user_id = ~USERID~ and code = '~GUID~'";
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
			$strSQL = str_replace('~GUID~', ff($strGUID), $strSQL);
			$strTimesheetID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			if (strlen($strTimesheetID) == 0)
			{
				$strID = timesheetSyncAdd($objConn_a, $strClientID, $strUserID, $strGUID, $strCode, $strTitle, $strOnDuty, $strNotes, "N", $strCreateDateTime, $strModifyDateTime);
			}
		}
		
        $blnResult = dbEndTrans($objConn_a, __FUNCTION__);

        if ($blnResult) 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error uploading timesheets.', array());
        }
    }
    
    */
    
    $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());

    return $strResult;
}
