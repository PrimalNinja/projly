<?php

function runTask($objConn_a, $strTaskName_a, $strTask_a)
{	
    if (dependencies('developer/tasks'))
    {
        $blnResult = false;
        $blnContinue = true;
        $intProcessLimit = 10;
        $intConfigTaskLimit = 10;
        $strNow = getDateTime();
                                    
        $strStartTime = time();
            
        $strTaskName = $strTaskName_a;
        $objTask = json_decode($strTask_a, true);
                                                        
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
    }

    $blnResult = true;

    return $blnResult;
}