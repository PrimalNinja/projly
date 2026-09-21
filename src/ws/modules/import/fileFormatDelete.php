<?php

// delete a file format
function fileFormatDelete($objConn_a, $strClientID_a, $strFileFormatIDList_a)
{
    $strTableNameFileFormat = getTableNameEntity('fileformat', false);

    dbBeginTrans($objConn_a, __FUNCTION__);

    $strSQL = "delete from ~TABLENAMEFILEFORMAT~ where client_id = ~CLIENTID~ and id in (~FILEFORMATIDLIST~)";
    $strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~FILEFORMATIDLIST~', $strFileFormatIDList_a, $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

    return dbEndTrans($objConn_a, __FUNCTION__);
}