<?php
 
function actionMyClientLoginAs($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameMyClient = getTableNameEntity("myclient", false);

    $strResult = "";

    if (dependencies('security/loginAs'))
    {
        // permission check
        if (!hasPermission($objConn_a, 'MYCLIENTLOGIN_AS', __FUNCTION__, true)) {return false;}

        $strMyClientID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

		$strSQL = "select actualclient_id returnvalue from ~TABLENAMEMYCLIENT~ where id = ~MYCLIENTID~";
		$strSQL = str_replace('~TABLENAMEMYCLIENT~', ff($strTableNameMyClient), $strSQL);
		$strSQL = str_replace('~MYCLIENTID~', ff($strMyClientID), $strSQL);
		$strClientID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		if (ENABLE_CLIENTDATABASES == 'TRUE')
		{
			$objConnClient = dbOpen(DBCLIENTMAIN_HOSTNAME, DBCLIENTMAIN_LOGIN, DBCLIENTMAIN_PASSWORD, DBCLIENTMAIN_DATABASENAME);		
			dbBeginTrans($objConnClient, __FUNCTION__);
			$strResult = loginAs($objConnClient, $strClientID, true, "Client");

			if (dbEndTrans($objConnClient, __FUNCTION__)) 
			{
				updateSessionDB("client", "Client", __FUNCTION__);	// temporary, in future it might be a lookup to cater for multiple hosts
				logSecurity('DB is client', '');
				$strResult = createJSONResponse($strDataID_a, RESPONSE_RELOAD, '', array());
			}
			else
			{
				updateSessionDB("system", "Session Lost", __FUNCTION__);	// temporary, in future it might be a lookup to cater for multiple hosts
				logSecurity('DB is system', '');
				
				$strDescription = dbErrorDescription(true);
				if (strlen($strDescription) == 0)
				{
					$strDescription = "Error logging in as.";
				}
				$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
			}
			dbClose($objConnClient);
		}
		else
		{
			dbBeginTrans($objConn_a, __FUNCTION__);
			$strResult = loginAs($objConn_a, $strClientID, true, "Client");

			if (dbEndTrans($objConn_a, __FUNCTION__)) 
			{
				updateSessionDB("client", "Client", __FUNCTION__);	// temporary, in future it might be a lookup to cater for multiple hosts
				logSecurity('DB is client', '');
				$strResult = createJSONResponse($strDataID_a, RESPONSE_RELOAD, '', array());
			}
			else
			{
				updateSessionDB("system", "Session Lost", __FUNCTION__);	// temporary, in future it might be a lookup to cater for multiple hosts
				logSecurity('DB is system', '');
				
				$strDescription = dbErrorDescription(true);
				if (strlen($strDescription) == 0)
				{
					$strDescription = "Error logging in as.";
				}
				$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
			}
		}
    }

    return $strResult;
}
