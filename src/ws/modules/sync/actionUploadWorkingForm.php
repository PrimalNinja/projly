<?php

function actionUploadWorkingForm($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) { 
    
    $blnResult = true;
    $strResult = "";
    
    if (dependencies('sync/uploadWorkingForm,sync/uploadProjlyWorkingForm')) 
	{
        // permission check
        if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

		// parameters
        $arrWorkingFormList = getJSONParameter($arrParameters_a, 'workingformlist');

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strUserID = $_SESSION['server_loggedin_userid'];
		
		//logDebug($arrWorkingFormList, '');
		dbBeginTrans($objConn_a, __FUNCTION__);

        for ($intI = 0; $intI < count($arrWorkingFormList); $intI++) 
		{
			$objWorkingForm = $arrWorkingFormList[$intI];

			if (APP_CODE === 'qims')
			{
				uploadWorkingForm($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strClientID, $strUserID, $objWorkingForm);
			}
			else // projlyfms
			{
                uploadProjlyWorkingForm($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strClientID, $strUserID, $objWorkingForm);
				
			}			
		}
		
        $blnResult = dbEndTrans($objConn_a, __FUNCTION__);

        if ($blnResult) 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error uploading working forms.', array());
        }
    }
    
    return $strResult;
}
