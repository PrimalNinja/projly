<?php

function getApplicationModules($objConn_a, $strApplicationID_a)
{
    $strTableNameApplicationModule = getTableNameEntity("applicationmodule", false);

    $arrResult = [];

    $strSQL = "select id from ~TABLENAMEAPPLICATIONMODULE~ where application_id = ~APPLICATIONID~ and is_enabled = 'Y'";
    $strSQL = str_replace('~TABLENAMEAPPLICATIONMODULE~', ff($strTableNameApplicationModule), $strSQL);
    $strSQL = str_replace('~APPLICATIONID~', ff($strApplicationID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    while ($arrRow = dbReadRecord($objResult))
    {
        $arrResult[] = $arrRow['id'];
    }
    dbCloseRecordset($objResult);

    return $arrResult;
}
