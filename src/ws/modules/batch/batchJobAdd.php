<?php
// add a batch job
function batchJobAdd($objConn_a, $strClientID_a, $strUserID_a, $strPriority_a, $strCode_a, $strLongDescription_a, $strSubtask_a, $strMetaData_a, $blnImmediate_a, $blnPreview_a)
{
    $arrResult = array("result"=> STAT_ERROR, "error"=>"Batch Job Add Error", "tagtype"=>"", "tag"=>"", "path"=>"");

	$strTableNameBatchJob = getTableNameEntity("batchjob", false);
	$strTableNameUser = getTableNameEntity("user", false);

    $strBatchJobID = '';

    if (dependencies('process/processTask')) 
	{
        if ($blnImmediate_a || $blnPreview_a || !toBoolean(BATCHPROCESSING_ENABLED)) 
		{
            // process job immediately as we are not in batch mode
            $arrResult = processTask($objConn_a, $strClientID_a, $strSubtask_a, $strMetaData_a, $blnPreview_a);
	    } 
		else
		{
			$strDataEntityID = getEntityID($objConn_a, "batchjob");
			$strEntityID = getEntityID($objConn_a, "systemform");     
			$strLogin = $_SESSION['server_loggedin_user'];
			$strCode = "BATCHJOB";
			$strDescription = "Batch Job " . $strCode_a;
		
			$strSQL = "select description returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and id = ~USERID~";
			$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);	
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
			$strUserName = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
            
			$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "BATCHJOB");
			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff1a767f7b-1c5d-411f-ae10-a5d486a10328", "USER", $strUserID_a, $strUserName);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff1a767f7b-1c5d-411f-ae10-a5d486a10328", "LONGDESCRIPTION", $strLongDescription_a);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff1a767f7b-1c5d-411f-ae10-a5d486a10328", "SUBTASK", $strSubtask_a);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff1a767f7b-1c5d-411f-ae10-a5d486a10328", "PRIORITY", $strPriority_a);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff1a767f7b-1c5d-411f-ae10-a5d486a10328", "SCHEDULED", getDateTime());
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff1a767f7b-1c5d-411f-ae10-a5d486a10328", "STARTDATETIME", '');
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff1a767f7b-1c5d-411f-ae10-a5d486a10328", "JOBSTATUS", STAT_PENDING);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff1a767f7b-1c5d-411f-ae10-a5d486a10328", "ERROR", "");
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff1a767f7b-1c5d-411f-ae10-a5d486a10328", "PROGRESS", "");
			$strJSONData = json_encode($arrJSONData);
            
            dbBeginTrans($objConn_a, __FUNCTION__);

            // create the batch job
            $strSQL = "insert into ~TABLENAMEBATCHJOB~ ( client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, metadata)
                       values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~DATACLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', '~METADATA~')";

			$strSQL = str_replace('~TABLENAMEBATCHJOB~', ff($strTableNameBatchJob), $strSQL);	
            $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
            $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
            $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
            $strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
            $strSQL = str_replace('~DESCRIPTION~', ff($strLongDescription_a), $strSQL);
            $strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
            $strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
            $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
            $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
            $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);    
            $strSQL = str_replace('~METADATA~', ff($strMetaData_a), $strSQL);
            dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

            $strBatchJobID = dbLastInsertID($objConn_a, __FUNCTION__);
            exposeEntityData($objConn_a, 'SYSTEMFORM', 'BATCHJOB', $strBatchJobID, $strJSONData);

            if (dbEndTrans($objConn_a, __FUNCTION__))
			{
				$arrResult = array("result"=> STAT_PENDING, "error"=>"", "tagtype"=>"batchjobid", "tag"=> $strBatchJobID, "path"=>"");
			}
        }

    }
    return $arrResult;
}
