<?php
function actionSystemFormFileJSONCreateAll($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strTableNameSystemForm = getTableNameEntity("systemform", false);

    $strResult = '';

    if (dependencies('entity/entitySystemFormFileJSONCreate'))
    {
        $strSQL = "select code, jsondata from ~TABLENAMESYSTEMFORM~";
        $strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        while ($arrRow = dbReadRecord($objResult)) 
		{
            $strSystemFormCode = $arrRow['code'];
            $arrJSONData = json_decode($arrRow['jsondata'], true);

            entitySystemFormFileJSONCreate($strSystemFormCode, $arrJSONData);
        }
        
        dbCloseRecordset($objResult);

        $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, "System Forms File JSON Created", array());
    }

    	
	return $strResult;
}