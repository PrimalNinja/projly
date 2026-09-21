<?php

function getModuleModules($objConn_a, $strModuleID_a)
{
    $strTableNameModuleModule = getTableNameEntity("module_module", false);

    $arrResult = [];

    $strSQL = "select usedmodule_id from ~TABLENAMEMODULEMODULE~ where module_id = ~MODULEID~ and is_enabled = 'Y'";
    $strSQL = str_replace('~TABLENAMEMODULEMODULE~', ff($strTableNameModuleModule), $strSQL);
    $strSQL = str_replace('~MODULEID~', ff($strModuleID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    while ($arrRow = dbReadRecord($objResult))
    {
        $arrResult[] = $arrRow['usedmodule_id'];
    }
    dbCloseRecordset($objResult);

    return $arrResult;
}
