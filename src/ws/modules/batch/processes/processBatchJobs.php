<?php

define("STAT_STARTED", 'STARTED');

// process batch jobs
function processBatchJobs($objConn_a)
{
	$strTableNameBatchJob = getTableNameEntity("batchjob", false);

	$blnResult = false;
	$strError = "";
	$strLogin = $_SESSION['server_loggedin_user'];

	$intJobsProcessed = 0;
	for ($intJobRun = 0; $intJobRun < intval(BATCH_JOB_RUNS, 10); $intJobRun++) 
	{
		for ($intJob = 0; $intJob < intval(BATCH_JOBS_PER_RUN, 10); $intJob++) 
		{
			// get the next job that isn't completed of the highest priority
			$strSQL = "
select id, client_id, jsondata, ff1a767f7b_1c5d_411f_ae10_a5d486a10328_subtask subtask, ff1a767f7b_1c5d_411f_ae10_a5d486a10328_priority priority, ff1a767f7b_1c5d_411f_ae10_a5d486a10328_startdatetime startdatetime, ff1a767f7b_1c5d_411f_ae10_a5d486a10328_jobstatus jobstatus, metadata
from ~TABLENAMEBATCHJOB~
where ff1a767f7b_1c5d_411f_ae10_a5d486a10328_jobstatus <> '~STATUSCOMPLETED~' and ff1a767f7b_1c5d_411f_ae10_a5d486a10328_jobstatus <> '~STATUSERROR~' and ff1a767f7b_1c5d_411f_ae10_a5d486a10328_jobstatus <> '~STATUSPAUSED~'
order by ff1a767f7b_1c5d_411f_ae10_a5d486a10328_priority, id limit 1
";
			$strSQL = str_replace('~TABLENAMEBATCHJOB~', ff($strTableNameBatchJob), $strSQL);
			$strSQL = str_replace('~STATUSCOMPLETED~', STAT_COMPLETED, $strSQL);
			$strSQL = str_replace('~STATUSERROR~', STAT_ERROR, $strSQL);
			$strSQL = str_replace('~STATUSPAUSED~', STAT_PAUSED, $strSQL);

			$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			if ($arrRow = dbReadRecord($objResult)) 
			{
				$strOutput = 'running job (id: ~id~, subtask: ~subtask~, metadata: ~metadata~...';
				$strOutput = str_replace('~id~', $arrRow['id'], $strOutput);
				$strOutput = str_replace('~subtask~', $arrRow['subtask'], $strOutput);
				$strOutput = str_replace('~metadata~', $arrRow['metadata'], $strOutput);
				echo ($strOutput);
				
				$strJobID = $arrRow['id'];
				$strClientID = $arrRow['client_id'];
				$strJSONData = $arrRow['jsondata'];
				$strSubtask = $arrRow['subtask'];
				$strJobStatus = $arrRow['jobstatus'];
				$strMetaData = $arrRow['metadata'];

				dbCloseRecordset($objResult);

				
				if ($strJobStatus == STAT_STARTED) 
				{
					// do nothing for now, but we might email ourselves after a timeframe if it isn't completed
					safetyDie('still processing job: ' . $strJobID);
				} 
				else 
				{
					$strStartDateTime = getDateTime();

					// start the job
					$arrJSONData = json_decode($strJSONData, true);
					$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff1a767f7b-1c5d-411f-ae10-a5d486a10328', "STARTDATETIME", $strStartDateTime);
					$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff1a767f7b-1c5d-411f-ae10-a5d486a10328', "JOBSTATUS", STAT_STARTED);
					$strJSONData = json_encode($arrJSONData);
					
					$strSQL = "update ~TABLENAMEBATCHJOB~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~BATCHJOBID~ limit 1";
					$strSQL = str_replace('~TABLENAMEBATCHJOB~', ff($strTableNameBatchJob), $strSQL);
					$strSQL = str_replace('~BATCHJOBID~', ff($strJobID), $strSQL);
					$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
					$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
					$strSQL = str_replace('~MODIFYDATETIME~', ff(getDateTime()), $strSQL);
					dbBeginTrans($objConn_a, __FUNCTION__);
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
					exposeEntityData($objConn_a, 'SYSTEMFORM', 'BATCHJOB', $strJobID, $strJSONData);
					dbEndTrans($objConn_a, __FUNCTION__);

					$arrResult = array();

					// process job
					try
					{
						$arrResult = processTask($objConn_a, $strClientID, $strSubtask, $strMetaData, false);
					}
					catch (Exception $e)
					{
						$arrResult["result"] = STAT_ERROR;
						$arrResult["error"] = $e->getMessage();
					}

					$blnError = false;

					// complete the job
					$arrJSONData = json_decode($strJSONData, true);
					if ($arrResult["result"] == STAT_COMPLETED) 
					{
						$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff1a767f7b-1c5d-411f-ae10-a5d486a10328', "JOBSTATUS", STAT_COMPLETED);
					} 
					else 
					{
						$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff1a767f7b-1c5d-411f-ae10-a5d486a10328', "JOBSTATUS", STAT_ERROR);
						$blnError = true;
					}
					$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff1a767f7b-1c5d-411f-ae10-a5d486a10328', "ERROR", $arrResult["error"]);
					$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff1a767f7b-1c5d-411f-ae10-a5d486a10328', "PROGRESS", '100');
					$strJSONData = json_encode($arrJSONData);

					$strSQL = "update ~TABLENAMEBATCHJOB~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~BATCHJOBID~ limit 1";
					$strSQL = str_replace('~TABLENAMEBATCHJOB~', ff($strTableNameBatchJob), $strSQL);
					$strSQL = str_replace('~BATCHJOBID~', ff($strJobID), $strSQL);
					$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
					$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
					$strSQL = str_replace('~MODIFYDATETIME~', ff(getDateTime()), $strSQL);
					dbBeginTrans($objConn_a, __FUNCTION__);
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
					exposeEntityData($objConn_a, 'SYSTEMFORM', 'BATCHJOB', $strJobID, $strJSONData);
					dbEndTrans($objConn_a, __FUNCTION__);

					if ($blnError) 
					{
						$strError .= $arrResult["error"];
		
						$strTo = FAIL_EMAIL_ADDRESSTO;
						$strSubject = 'processbatch failed on ' . FAIL_EMAIL_DOMAIN . ' @ ' . getDateTime() . ' subtask: ' . $strSubtask;
						$strMessage = 'processbatch failed on ' . FAIL_EMAIL_DOMAIN . ' @ ' . getDateTime() . ' subtask: ' . $strSubtask . ' error: ' . $strError;
						sendFailureMessage($objConn_a, FAIL_EMAIL_DOMAIN, FAIL_EMAIL_ADDRESS, FAIL_EMAIL_FROMNAME, $strTo, $strSubject, $strMessage);
					}
				}

				$intJobsProcessed++;
			} 
			else 
			{
				dbCloseRecordset($objResult);
				echo ('no job...');
				//safetyDie('no job...');
			}
		}

		echo ('<br>processed ' . $intJobsProcessed . '. sleeping for ' . BATCH_PAUSE_BETWEEN_RUNS . ' seconds...');
		if ($intJobRun < intval(BATCH_JOB_RUNS, 10) - 1) 
		{
			sleep(intval(BATCH_PAUSE_BETWEEN_RUNS, 10));
		}
	}
}
