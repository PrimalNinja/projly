<?php

function buildNavItems($objConn_a, $strSystemModuleID_a, $strSystemBuildID_a)
{
    $strTableNameSystemBuild = getTableNameEntity("systembuild", false);
    $strTableNameSystemModuleNavItem = getTableNameEntity("systemmodule_navitem", false);
    $strTableNameNavItem = getTableNameEntity("navitem", false);

    $blnResult = true;

    $strSQL = "select n.jsondata from ~TABLENAMESYSTEMMODULENAVITEM~ sn, ~TABLENAMENAVITEM~ n where sn.navitem_id=n.id and sn.systemmodule_id = ~SYSTEMMODULEID~";
    $strSQL = str_replace('~TABLENAMESYSTEMMODULENAVITEM~', ff($strTableNameSystemModuleNavItem), $strSQL);
    $strSQL = str_replace('~TABLENAMENAVITEM~', ff($strTableNameNavItem), $strSQL);
    $strSQL = str_replace('~SYSTEMMODULEID~', ff($strSystemModuleID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    $arrTiles = [];

    while ($arrRow = dbReadRecord($objResult))
    {
        $arrJSONData = json_decode($arrRow['jsondata'], true);

        $strParameters = formValueGetBySectionCodeFieldCode($arrJSONData, 'g871c4f64-236d-4e98-939d-8d5a88b7d62b', "PARAMETERS");

        $arrTiles[] = $strParameters;  //json_decode($strParameters, true);
    }

    dbCloseRecordset($objResult);

    if (count($arrTiles) > 0)
    {
        $strSQL = "select g3addd7a5_6125_499d_b87a_c827161eae5f_folder returnvalue from ~TABLENAMESYSTEMBUILD~ where id = ~SYSTEMBUILDID~";
        $strSQL = str_replace('~TABLENAMESYSTEMBUILD~', ff($strTableNameSystemBuild), $strSQL);
        $strSQL = str_replace("~SYSTEMBUILDID~", ff($strSystemBuildID_a), $strSQL);
        $strBuildFolder = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        $strPathBuild = BUILD_PATH . $strBuildFolder;

        $strResult = implode(',', $arrTiles);

        $strPathFile = $strPathBuild  . '/app/inc-nav.json';

        if (!file_exists($strPathBuild . '/app'))
        {
            createFolder($strPathBuild . '/app', true);
        }

        saveFile($strPathFile, $strResult);
    }
    
    return $blnResult;
}