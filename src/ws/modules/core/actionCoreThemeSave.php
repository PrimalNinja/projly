<?php

// save a theme
function actionCoreThemeSave($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameTheme = getTableNameEntity("theme", false);

    $strResult = "";

    if (dependencies('setting/settingPut')) 
	{
        // permission check
        if (!hasPermission($objConn_a, 'EDT_SETTINGVALUE', __FUNCTION__, true)) { return false; }

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strUserID = $_SESSION['server_loggedin_userid'];
        $strThemeCode = getJSONParameter($arrParameters_a, 'themecode');
		$strThemeID = "";
		$strThemeDescription = "";

        // update the setting
        dbBeginTrans($objConn_a, __FUNCTION__);
		
		if (strlen($strThemeCode) > 0)
		{
			$strSQL = "select id returnvalue from ~TABLENAMETHEME~ where code = '~THEMECODE~'";
			$strSQL = str_replace('~TABLENAMETHEME~', ff($strTableNameTheme), $strSQL);
			$strSQL = str_replace('~THEMECODE~', ff($strThemeCode), $strSQL);
			$strThemeID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			$strThemeDescription = dbGetDescriptionFromID($objConn_a, $strTableNameTheme, $strThemeID, __FUNCTION__);
		}
		
		settingPut($objConn_a, 'CORE', 'THEME', $strClientID, $strUserID, '', '', '', $strThemeID, $strThemeDescription, __FUNCTION__);
        if (dbEndTrans($objConn_a, __FUNCTION__)) 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', '');
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error saving theme.', array());
        }
    }

    return $strResult;
}
