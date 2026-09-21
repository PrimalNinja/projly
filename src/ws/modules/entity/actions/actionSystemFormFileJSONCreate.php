<?php
function actionSystemFormFileJSONCreate($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strTableNameSystemForm = getTableNameEntity("systemform", false);

    $strResult = '';

    if (dependencies('entity/entitySystemFormFileJSONCreate'))
    {
        $strID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        $strSQL = "select code, jsondata from ~TABLENAMESYSTEMFORM~ where id = ~SYSTEMFORMID~";
        $strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
        $strSQL = str_replace('~SYSTEMFORMID~', ff($strID), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        if ($arrRow = dbReadRecord($objResult)) 
		{
            $strSystemFormCode = $arrRow['code'];
            $arrJSONData = json_decode($arrRow['jsondata'], true);

            entitySystemFormFileJSONCreate($strSystemFormCode, $arrJSONData);
        }
        
        dbCloseRecordset($objResult);

        $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, "System Form File JSON Created", array());
    }

    	
	return $strResult;
}