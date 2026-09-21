<?php

function processConfig($objConn_a)
{
	global $g_arrDefaultDictionary;
	global $g_arrDictionary;

    $strTableNameConfig = getTableNameEntity("config", false);
    $strTableNameConfigTask = getTableNameEntity("configtask", false);

    $blnContinue = true;
	
	if (dependencies('batch/processes/tasks'))
	{
		echo('<br />');
		echo('start processing config ...');
		
		// not sure where to load this dictionary.json ????
        if (file_exists("dictionary.json"))
        {
            $strDictionary = loadFile("dictionary.json");
            if (strlen($strDictionary) > 0)
            {
                $g_arrDictionary = json_decode($strDictionary, true);
			}			
        }
        //else
        //{                
            // system dictionary entries
            addDefaultDictionary('%SYS_DBCLIENTMAIN_DATABASENAME%', DBCLIENTMAIN_DATABASENAME);
            addDefaultDictionary('%SYS_DBCLIENTMAIN_HOSTNAME%', DBCLIENTMAIN_HOSTNAME);
            addDefaultDictionary('%SYS_DBCLIENTMAIN_LOGIN%', DBCLIENTMAIN_LOGIN);
            addDefaultDictionary('%SYS_DBCLIENTMAIN_PASSWORD%', DBCLIENTMAIN_PASSWORD);

            addDefaultDictionary('%SYS_DBCLIENTTEMP_DATABASENAME%', DBCLIENTTEMP_DATABASENAME);
            addDefaultDictionary('%SYS_DBCLIENTTEMP_HOSTNAME%', DBCLIENTTEMP_HOSTNAME);
            addDefaultDictionary('%SYS_DBCLIENTTEMP_LOGIN%', DBCLIENTTEMP_LOGIN);
            addDefaultDictionary('%SYS_DBCLIENTTEMP_PASSWORD%', DBCLIENTTEMP_PASSWORD);

            addDefaultDictionary('%SYS_DBSYSTEMMAIN_DATABASENAME%', DBSYSTEMMAIN_DATABASENAME);
            addDefaultDictionary('%SYS_DBSYSTEMMAIN_HOSTNAME%', DBSYSTEMMAIN_HOSTNAME);
            addDefaultDictionary('%SYS_DBSYSTEMMAIN_LOGIN%', DBSYSTEMMAIN_LOGIN);
            addDefaultDictionary('%SYS_DBSYSTEMMAIN_PASSWORD%', DBSYSTEMMAIN_PASSWORD);

            addDefaultDictionary('%SYS_DBSYSTEMTEMP_DATABASENAME%', DBSYSTEMTEMP_DATABASENAME);
            addDefaultDictionary('%SYS_DBSYSTEMTEMP_HOSTNAME%', DBSYSTEMTEMP_HOSTNAME);
            addDefaultDictionary('%SYS_DBSYSTEMTEMP_LOGIN%', DBSYSTEMTEMP_LOGIN);
            addDefaultDictionary('%SYS_DBSYSTEMTEMP_PASSWORD%', DBSYSTEMTEMP_PASSWORD);

            addDefaultDictionary('%SYS_YYYYMMDDHHNNSS%', getFileDateTime());
            //addDefaultDictionary('%SYS_FILESELECTED%', $strUpdate, $g_arrDefaultDictionary);
            addDefaultDictionary('%SYS_TODAY%', getToday());
            addDefaultDictionary('%SYS_PATH_CONFIG%', SYS_PATH_CONFIG);

           // $g_arrDictionary = array_merge($g_arrDefaultDictionary, $g_arrDictionary);
		//}
				
		while ($blnContinue)
		{
			dbBeginTrans($objConn_a, __FUNCTION__);

			$strSQL = "select id, jsondata from ~TABLENAMECONFIG~ where gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_is_ready = 'Y' and gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_is_processed = 'N' order by id limit 1";
			$strSQL = str_replace('~TABLENAMECONFIG~', ff($strTableNameConfig), $strSQL);
			$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

			if ($arrRow = dbReadRecord($objResult))
			{
				$strConfigID = $arrRow['id'];        
				$strConfigJSONData = $arrRow['jsondata'];
				
				$strSQL = "select id, jsondata from ~TABLENAMECONFIGTASK~ where g15aeaa60_835c_4974_87c5_e82ec903629f_is_processed = 'N' and config_id = ~CONFIGID~ order by id limit 1";
				$strSQL = str_replace('~TABLENAMECONFIGTASK~', ff($strTableNameConfigTask), $strSQL);
				$strSQL = str_replace('~CONFIGID~', ff($strConfigID), $strSQL);
				$objResultConfigTask = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

				if ($arrRowConfigTask = dbReadRecord($objResultConfigTask))
				{
					$strConfigTaskID = $arrRowConfigTask['id'];
					$strConfigTaskJSONData = $arrRowConfigTask['jsondata'];
					$arrConfigTaskJSONData = json_decode($strConfigTaskJSONData, true);

					// start do task logic process //
					
					$strTaskName = formValueGetBySectionCodeFieldCode($arrConfigTaskJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "COMMAND");
					$strParameters = formValueGetBySectionCodeFieldCode($arrConfigTaskJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "PARAMETERS");

					$strStartTime = time();

					$objTask = json_decode($strParameters, true);

					if (JSON_ERROR_NONE !== json_last_error()) 
                    {
                        $blnContinue = false;
                        throw new RuntimeException('Unable to parse response JSON: ' . json_last_error());
                    }
                

					echo '<br />';
					echo "Processing " . $objTask['exec'] . '...';
					echo '<br />';
								
					if ($strTaskName == 'getExportedFiles')	// getExportedFiles is not impemented with optionsets, only tasks
					{
						$arrResult = configTask($objTask); //processTask($objTask);

						$arrSubTasks = [];

						foreach ($arrResult as $objResult)
						{							
							$objSubTask = [
								'exec' => 'importFile',
								'tablename' => $objResult['tablename'],
								'source' => $objResult['filename'],
								'chunk' => $objResult['chunk'],
								'chunkof' => $objResult['chunkof']
							];

							$arrSubTasks[] = $objSubTask;
						}

						// run subtasks here. how???
						// maybe iterate to arrsubtasks and call configTask()?
						
						foreach ($arrSubTasks as $objSubTask)
						{
							configTask($objSubTask);
						}
						
					}
					else if ($strTaskName == 'getSchemaElements')	// getSchemaElements is not impemented with optionsets, only tasks
					{
						$arrResult = configTask($objTask); //processTask($objTask);

						if (isset($arrResult['tables']) && count($arrResult['tables']) > 0)
						{	
							$intChunkCountTemp = 0;
							$arrSubTasks = [];

							foreach ($arrResult['tables'] as $objTable)
							{
								$strTableName = $objTable['tablename'];
								$intRecordCount = $objTable['recordcount'];
								$intChunkCount = 1;
																
								if (intval($objTask['chunksize'], 10) > 0)
								{
									$intChunkCountTemp = $intRecordCount / intval($objTask['chunksize'], 10);
									$intChunkCount = intval($intChunkCountTemp, 10);

									if ($intChunkCountTemp > $intChunkCount)
									{
										$intChunkCount++;
									}
								}

								if (getFlag($objTask['flags'], 'data'))
								{
									for ($intC = 1; $intC <= $intChunkCount; $intC++)
									{
										$objSubTask = $objTask;										
										
										//if (g_strSubTasks === "exportData")
										//{
										//	objSubTask.exec = "exportTable";
										//}
										
										$objSubTask['exec'] = 'exportData';
										$objSubTask['tablename'] = $strTableName;
										$objSubTask['chunk'] = $intC;
										$objSubTask['chunkof'] = $intChunkCount;
										$objSubTask['chunksize'] = $objTask['chunksize'];
										
										$arrSubTasks[] = $objSubTask;
									}
								}

								if (getFlag($objTask['flags'], 'schema'))
								{									
									$objSubTask = $objTask;									
									
									//if (g_strSubTasks === "exportData")
									//{
									//	objSubTask.exec = "exportTable";
									//}
									
									$objSubTask['exec'] = 'exportData';
									$objSubTask['tablename'] = $strTableName;
									$objSubTask['chunk'] = 0;
									$objSubTask['chunkof'] = 0;
									$objSubTask['chunksize'] = 0;
									
									$arrSubTasks[] = $objSubTask;									
								}

								// run subtasks here. how???
								// maybe iterate to arrsubtasks and call configTask()?
								
								foreach ($arrSubTasks as $objSubTask)
								{
									configTask($objSubTask);
								}
								
							}
						}						
					}
					else
					{
						configTask($objTask); //processTask($objTask);
					}
					
					// end do task logic process //

					//  update config tasks is_process to Y		
					
					$strEndTime = time();
					
					$arrConfigTaskJSONData = formValueUpdateBySectionCodeFieldCode($arrConfigTaskJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "IS_PROCESSED", 'Y');
					$arrConfigTaskJSONData = formValueUpdateBySectionCodeFieldCode($arrConfigTaskJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "STARTTIME", $strStartTime);
					$arrConfigTaskJSONData = formValueUpdateBySectionCodeFieldCode($arrConfigTaskJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "ENDTIME", $strEndTime);
					$strConfigTaskJSONData = json_encode($arrConfigTaskJSONData);

					$strSQL = "update ~TABLENAMECONFIGTASK~ set jsondata = '~JSONDATA~' where id = ~CONFIGTASKID~";
					$strSQL = str_replace('~TABLENAMECONFIGTASK~', ff($strTableNameConfigTask), $strSQL);
					$strSQL = str_replace('~JSONDATA~', ff($strConfigTaskJSONData), $strSQL);
					$strSQL = str_replace('~CONFIGTASKID~', ff($strConfigTaskID), $strSQL);
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
					
					exposeEntityData($objConn_a, 'SYSTEMFORM', 'CONFIGTASK', $strConfigTaskID, $strConfigTaskJSONData);
				}
				
				dbCloseRecordset($objResultConfigTask);

				$strSQL = "select count(*) returnvalue from ~TABLENAMECONFIGTASK~ where g15aeaa60_835c_4974_87c5_e82ec903629f_is_processed = 'N' and config_id = ~CONFIGID~";
				$strSQL = str_replace('~TABLENAMECONFIGTASK~', ff($strTableNameConfigTask), $strSQL);
				$strSQL = str_replace('~CONFIGID~', ff($strConfigID), $strSQL);
				$strCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

				//  update config tasks is_process to Y
				if (intval($strCount) === 0)
				{        
					$arrConfigJSONData = json_decode($strConfigJSONData, true);
					$arrConfigJSONData = formValueUpdateBySectionCodeFieldCode($arrConfigJSONData, 'gb018840e-bc4f-45fc-b8a6-2c6338ca1be1', "IS_PROCESSED", 'Y');
					$strConfigJSONData = json_encode($arrConfigJSONData);

					$strSQL = "update ~TABLENAMECONFIG~ set jsondata = '~JSONDATA~' where id = ~CONFIGID~";
					$strSQL = str_replace('~TABLENAMECONFIG~', ff($strTableNameConfig), $strSQL);
					$strSQL = str_replace('~JSONDATA~', ff($strConfigJSONData), $strSQL);
					$strSQL = str_replace('~CONFIGID~', ff($strConfigID), $strSQL);
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
					
					exposeEntityData($objConn_a, 'SYSTEMFORM', 'CONFIG', $strConfigID, $strConfigJSONData);  

					echo ("<br />");
					echo ("Config tasks has been processed completely...");
				}      
			}
			else
			{
				$blnContinue = false;
			}

			dbCloseRecordset($objResult);

			$blnResultTransaction = dbEndTrans($objConn_a, __FUNCTION__);

			if ($blnContinue && !$blnResultTransaction)
			{
				$blnContinue = false;
			}

		} // while loop
		
		saveFile("dictionary.json", json_encode($g_arrDictionary));

		echo ("<br />");
		echo ('end processing config.');
		echo ("<br />");
	
	} // dependencies
}