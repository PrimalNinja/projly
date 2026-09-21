<?php

function processBatchRoster($objConn_a)
{
    $strTableNameRosterTemplate = getTableNameEntity("rostertemplate", false);

    $blnResult = false;

    echo('<br />processing Roster creation from template...');

    if (dependencies('projly/rosterTemplateCreateRoster'))
    {
        $strSQL = "select id, client_id from ~TABLENAMEROSTERTEMPLATE~";
        $strSQL = str_replace("~TABLENAMEROSTERTEMPLATE~", ff($strTableNameRosterTemplate), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        while ($arrRow = dbReadRecord($objResult))
        {
            $strRosterTemplateID = $arrRow['id'];
            $strClientID = $arrRow['client_id'];
            
            if (!rosterTemplateCreateRoster($objConn_a, $strClientID, $strRosterTemplateID))
			{
                echo "<br />Error processing create roster<br />";
            }
        }
		
		dbCloseRecordset($objResult);

        $blnResult = true;
    }

    echo('<br />Done processing roster creation...');

    return $blnResult;
}