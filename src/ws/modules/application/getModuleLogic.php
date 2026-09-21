<?php

function getModuleLogic($objConn_a, $strModuleID_a)
{
    $strTableNameModule = getTableNameEntity("module", false);

    $strResult = "";

    $strSQL = "select jsondata returnvalue from ~TABLENAMEMODULE~ where id = ~MODULEID~";
    $strSQL = str_replace('~TABLENAMEMODULE~', ff($strTableNameModule), $strSQL);    
    $strSQL = str_replace('~MODULEID~', ff($strModuleID_a), $strSQL);
    $strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
    
    $arrJSONData = json_decode($strJSONData, true);
    $strResult = formValueGetBySectionCodeFieldCode($arrJSONData, 'f7b92ec7-4d8e-462f-8642-1a362e1f8602', 'LOGIC');
    
    return $strResult;
}