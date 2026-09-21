<?php

// drop a temporary table
function dropTempTable($objConn_a, $strTableTemp_a)
{
    $strSQL = 'drop table ~DATABASETEMP~.~TABLENAMETEMP~';
    $strSQL = str_replace('~DATABASETEMP~', DBSYSTEMTEMP_DATABASENAME, $strSQL);
    $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableTemp_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}
