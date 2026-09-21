<?php

function getApplicationModuleLogic($objConn_a, $strApplicationModule_a)
{   
    $strTableNameApplicationModule = getTableNameEntity("applicationmodule", false);

    $strResult = "";

    $strSQL = "select jsondata returnvalue from ~TABLENAMEAPPLICATIONMODULE~ where id = ~APPLICATIONMODULEID~";
    $strSQL = str_replace('~TABLENAMEAPPLICATIONMODULE~', ff($strTableNameApplicationModule), $strSQL);    
    $strSQL = str_replace('~APPLICATIONMODULEID~', ff($strApplicationModule_a), $strSQL);
    $strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
    
    $arrJSONData = json_decode($strJSONData, true);
    $strResult = formValueGetBySectionCodeFieldCode($arrJSONData, 'g3afa0f8b-7930-4d98-b5e1-413215a5ce4e', 'LOGIC');
    
    return $strResult;

}