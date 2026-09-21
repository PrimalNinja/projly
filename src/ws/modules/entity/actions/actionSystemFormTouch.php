<?php
 
function actionSystemFormTouch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{	
	$strTableNameEntity = getTableNameEntity("entity", false);
	$strTableNameSystemForm = getTableNameEntity("systemform", false);
	
	$blnResult = true;
	$strDescription = "";
	$strResult = "";
	
    if (dependencies('entity/entityDataUpdate,entity/entitySystemFormFileJSONCreate,entity/entitySystemFormFileJSON')) 
	{ 
        // permission check
        if (!hasPermission($objConn_a, 'TOUCH_SYSTEMFORM', __FUNCTION__, true)) {return false;}

		$strSystemFormID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        // initialisations
		$strClientID = $_SESSION['server_loggedin_clientid'];    
		
		$strSQL = "select id, code, jsondata from ~TABLENAMESYSTEMFORM~ where id = ~SYSTEMFORMID~";
		$strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
		$strSQL = str_replace('~SYSTEMFORMID~', ff($strSystemFormID), $strSQL);
		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		
		if ($arrRow = dbReadRecord($objResult)) 
		{
			$strEntityDataID = $arrRow['id'];
			$strFormEntityCode = $arrRow['code'];
			$strJSONData = $arrRow['jsondata'];
			$arrJSONData = json_decode($strJSONData, true);
			
            if (toBoolean(FETCHSYSTEMFORMSFROMFILE))
            {
                $strJSONData = entitySystemFormFileJSON($strFormEntityCode);

                if (strlen($strJSONData) == 0)
                {
                    $strJSONData = $arrRow['jsondata'];
                }
            }

			$arrJSONData = json_decode($strJSONData, true);

			dbBeginTrans($objConn_a, __FUNCTION__);
			$blnResult = entityDataUpdate($objConn_a, $strClientID, "SYSTEMFORM", $strFormEntityCode, $strEntityDataID, $arrJSONData, "", "", "") ;
            
            if ($blnResult)
            {
                entitySystemFormFileJSONCreate($strFormEntityCode, $arrJSONData);
            }
            
            if ($blnResult && dbEndTrans($objConn_a, __FUNCTION__)) 
			{            
				$strDescription = $strFormEntityCode . " touched!";
			} 
			else
			{
				$strDescription = $strFormEntityCode . " touch failed!";
			}
		}
		
		dbCloseRecordset($objResult);
	}
	
	if ($blnResult) 
	{            
		$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strDescription, array());
	} 
	else 
	{
		$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
	}
        
    return $strResult;
}
