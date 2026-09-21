<?php

function getApplicationModuleModules($objConn_a, $strApplicationID_a)
{
    $strTableNameApplicationModule = getTableNameEntity("applicationmodule", false);
    $strTableNameApplicationModuleModule = getTableNameEntity("applicationmodule_module", false);

    $arrResult = [];

    $strSQL = "select amm.module_id from ~TABLENAMEAPPLICATIONMODULE~ am, ~TABLENAMEAPPLICATIONMODULEMODULE~ amm where amm.applicationmodule_id=am.id and am.application_id = ~APPLICATIONID~ and amm.is_enabled = 'Y'";
    $strSQL = str_replace('~TABLENAMEAPPLICATIONMODULE~', ff($strTableNameApplicationModule), $strSQL);
    $strSQL = str_replace('~TABLENAMEAPPLICATIONMODULEMODULE~', ff($strTableNameApplicationModuleModule), $strSQL);
    $strSQL = str_replace('~APPLICATIONID~', ff($strApplicationID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    while ($arrRow = dbReadRecord($objResult))
    {
        $arrResult[] = $arrRow['module_id'];
    }
    dbCloseRecordset($objResult);

    return $arrResult;
}