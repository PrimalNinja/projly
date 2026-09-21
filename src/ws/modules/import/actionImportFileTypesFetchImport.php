<?php

// fetch file types
function actionImportFileTypesFetchImport($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strTableNameFileType = getTableNameEntity('filetype', false);

    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'VW_FILETYPE', __FUNCTION__, true)) { return false; }	// has specific check below

    // initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];

    $strSQL = "select id, code, description, gc5913c98_7aff_4753_9b43_6f388e7b707f_is_import is_import, gc5913c98_7aff_4753_9b43_6f388e7b707f_is_export is_export, gc5913c98_7aff_4753_9b43_6f388e7b707f_is_definable is_definable, is_enabled from ~TABLENAMEFILETYPE~ where is_enabled = 'Y' and gc5913c98_7aff_4753_9b43_6f388e7b707f_is_import = 'Y' and (gc5913c98_7aff_4753_9b43_6f388e7b707f_applications is null or gc5913c98_7aff_4753_9b43_6f388e7b707f_applications = '' or gc5913c98_7aff_4753_9b43_6f388e7b707f_applications like '%~APPCODE~%') order by gc5913c98_7aff_4753_9b43_6f388e7b707f_description";
	$strSQL = str_replace('~TABLENAMEFILETYPE~', ff($strTableNameFileType), $strSQL);
	$strSQL = str_replace('~APPCODE~', ff(APP_CODE), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    while ($arrRow = dbReadRecord($objResult))
    {
        $strCode = $arrRow['code'];
        $blnHasPermission = false;

        switch ($strCode) {
            case "GENERIC":
                $blnHasPermission = hasPermission($objConn_a, 'IMP_FILES', __FUNCTION__, false);
                break;           

            case "IMAGES":
                $blnHasPermission = hasPermission($objConn_a, 'IMP_IMAGES', __FUNCTION__, false);
                break;            
        }
        
        if ($blnHasPermission)
        {
            $arrResult[] = array(
                "id" => secureEntityValue('FILETYPE', $arrRow['id']),
                "code" => $arrRow['code'],
                "definable" => $arrRow['is_definable'],
                "import" => $arrRow['is_import'],
                "export" => $arrRow['is_export'],
                "description" => $arrRow['description']
            );
        }
    }
    
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
