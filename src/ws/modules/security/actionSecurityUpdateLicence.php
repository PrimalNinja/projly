<?php

// update licence
function actionSecurityUpdateLicence($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";

    // permission check
    if (!hasPermission($objConn_a, 'EDT_LICENSING', __FUNCTION__, true)) {return false;}

    $strLicenceKey = getJSONParameter($arrParameters_a, 'licencekey');

    // initialisations
    //$strClientID = $_SESSION['server_loggedin_clientid'];
    //$strUserID = $_SESSION['server_loggedin_userid'];

    if (licenceValidate($objConn_a, PRODUCT_ID, intval(INSTALLATION_ID, 10), $strLicenceKey)) {
        // update the licence key
        dbBeginTrans($objConn_a, __FUNCTION__);
        systemSettingPut($objConn_a, SETTING_KEY_LICENCEKEY, $strLicenceKey);
        if (dbEndTrans($objConn_a, __FUNCTION__)) {
            // licence check
            $strExpiryDate = licenceCheck($objConn_a, PRODUCT_ID, intval(INSTALLATION_ID, 10));
            $blnLicensed = (strlen($strExpiryDate) > 0);
            $strLicensed = 'N';
            if ($blnLicensed) {
                $strLicensed = 'Y';
            }
            $intExpiryDays = 0;

            // expiry in
            if ($blnLicensed) {
                $dteToday = getISODate();
                $dteExpiryDate = date('Y-m-d', strtotime($strExpiryDate));
                $dteWarningDate = getDateMinusDays($dteExpiryDate, intval(LICENCEWARNINGDAYS, 10));

                if ($dteToday > $dteWarningDate) {
                    $intExpiryDays = (strtotime($dteExpiryDate) - strtotime($dteToday)) / 86400;
                }
            }
            $arrResult = array(
                "licensed" => $strLicensed,
                "expirydate" => $strExpiryDate,
                "expirydays" => $intExpiryDays,
            );

            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error saving licence key.', array());
        }
    } 
	else 
	{
        $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Invalid licence key.', array());
    }

    return $strResult;
}
