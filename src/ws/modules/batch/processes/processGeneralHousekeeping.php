<?php

function processGeneralHousekeeping($objConn_a)
{
	$intTimeStart = microtime(true);

    echo('<br />processing housekeeping...');
    
    $intInRun = 0;

    if (toBoolean(CLEAN_ESB))
    {   
        dbBeginTrans($objConn_a, __FUNCTION__);

        echo ('<br />');
        echo('<br />ESB tables Housekeeping...');
        
        $strSQL = "SET FOREIGN_KEY_CHECKS = 0";
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                
        echo('<br />truncating esblistener table...');
        $strSQL = "truncate table ~TABLENAMEESBLISTENER~";
		$strSQL = str_replace("~TABLENAMEESBLISTENER~", CORE_ESBLISTENER, $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        echo('<br />esblistener table truncated...');
        $intInRun++;
        
        echo('<br />truncating esbbroadcaster table...');        
        $strSQL = "truncate table ~TABLENAMEESBBROADCASTER~";
		$strSQL = str_replace("~TABLENAMEESBBROADCASTER~", CORE_ESBBROADCASTER, $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        echo('<br />esbbroadcaster table truncated...');
        $intInRun++;

        echo('<br />truncating esb table...');
        $strSQL = "truncate table ~TABLENAMEESB~";
		$strSQL = str_replace("~TABLENAMEESB~", CORE_ESB, $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        echo('<br />esb table truncated...');
        $intInRun++;

        $strSQL = "SET FOREIGN_KEY_CHECKS = 1";
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        echo('<br />ESB tables Housekeeping done...');

        dbEndTrans($objConn_a, __FUNCTION__);        
    }

    if (toBoolean(CLEAN_TEMP))
    {
        dbBeginTrans($objConn_a, __FUNCTION__);

        echo ('<br />');
        echo('<br />temp tables Housekeeping...');
        
        $strSQL = "SET FOREIGN_KEY_CHECKS = 0";
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
          
        /*
        echo('<br />truncating t_selected table...');
        $strSQL = "truncate table t_selected";
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        echo('<br />t_selected table truncated...');
        $intInRun++;
        */
        
        /*
        echo('<br />truncating t_sessions table...');        
        $strSQL = "truncate table t_sessions";
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        echo('<br />t_sessions table truncated...');
        $intInRun++;
        */

        $strSQL = "SET FOREIGN_KEY_CHECKS = 1";
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        echo('<br />temp tables Housekeeping done...');

        dbEndTrans($objConn_a, __FUNCTION__);
    }
    
    echo ('<br>processed ' . $intInRun . '. ');
	$intTimeEnd = microtime(true);
    $intTime = $intTimeEnd - $intTimeStart;
    echo "<br />process time: " . $intTime . '. <br />';
}