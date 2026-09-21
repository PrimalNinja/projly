<?php

// process schemachanges
function processSchemaChanges($objConn_a)
{
	$strTableNameSchemaChanges = getTableNameEntity("schemachange", false);

	$intTimeStart = microtime(true);

	echo('processing Schema Changes...');
	$intSchemaChangesInRun = 0;
	$MAXSECONDS = 300;

	$blnErrors = false;
	$blnMoreTime = true;
	$blnEndOfChanges = false;
	
	// if we have no errors yet and still more time
	while ((!$blnErrors) && (!$blnEndOfChanges) && ($blnMoreTime))
	{
		// get the next statement
		$strSQL = "select id, jsondata from ~TABLENAMESCHEMACHANGES~ where g523b5f4a_fc16_4f51_ad0c_c1cee86223bd_isdone = 'N' order by id limit 0, 1";
		$strSQL = str_replace("~TABLENAMESCHEMACHANGES~", ff($strTableNameSchemaChanges), $strSQL);
		$objResultSchemaChanges = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		if ($arrRowSchemaChange = dbReadRecord($objResultSchemaChanges))
		{
			$strSchemaChangeID = $arrRowSchemaChange['id'];
			$strJSONData = $arrRowSchemaChange['jsondata'];
			$arrJSONData = json_decode($strJSONData, true);
			
			$strEntityCode = formValueGetBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "ENTITYCODE");
			$strField = formValueGetBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "FIELD");
			$strOperation = formValueGetBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "OPERATION");
			
			$blnUpdateComplete = false;
			dbBeginTrans($objConn_a, __FUNCTION__);
		
			if ($strOperation == 'rebuildextensiondatainit')
			{
				$strTableNameEntityExtension = getTableNameEntityExtension($strEntityCode);
				$strSQL = "update ~TABLENAMEENTITYEXTENSION~ set is_exposed = 'N'";
				$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				$blnUpdateComplete = true;
			}
			else if ($strOperation == 'rebuildextensiondata')
			{
				$arrJSONDataEntity = formTemplateGetFromDBByEntityCode($objConn_a, $strEntityCode);
				$strJSONDataEntity = json_encode($arrJSONDataEntity);
				exposeEntityDataAll($objConn_a, 'systemform', $strEntityCode, $strJSONDataEntity, 100);

				$strTableNameEntityExtension = getTableNameEntityExtension($strEntityCode);
				$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITYEXTENSION~ where is_exposed = 'N'";
				$strSQL = str_replace("~TABLENAMEENTITYEXTENSION~", ff($strTableNameEntityExtension), $strSQL);
				$intTodo = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				$blnUpdateComplete = ($intTodo == 0);
			}

			$blnX = dbEndTrans($objConn_a, __FUNCTION__);
			
			if (dependencies('system/schemaChangeErrorSet'))
			{
				if ($blnX) 
				{
					schemaChangeErrorSet($objConn_a, $strSchemaChangeID, "");
				}
				else
				{ 
					$blnErrors = true; 
					$strErrorDescription = dbErrorDescription(false);
					schemaChangeErrorSet($objConn_a, $strSchemaChangeID, $strErrorDescription . " - " . $strSQL);
				}
			}
			
			if ((!$blnErrors) && ($blnUpdateComplete))
			{
				dbBeginTrans($objConn_a, __FUNCTION__);
		
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "ISDONE", 'Y');
				$strJSONData = json_encode($arrJSONData);
	
				$strSQL = "update ~TABLENAMESCHEMACHANGES~ set jsondata = '~JSONDATA~' where id = ~SCHEMACHANGEID~";
				$strSQL = str_replace("~TABLENAMESCHEMACHANGES~", ff($strTableNameSchemaChanges), $strSQL);
				$strSQL = str_replace("~SCHEMACHANGEID~", ff($strSchemaChangeID), $strSQL);
				$strSQL = str_replace("~JSONDATA~", ff($strJSONData), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				
				exposeEntityData($objConn_a, 'SYSTEMFORM', 'SCHEMACHANGE', $strSchemaChangeID, $strJSONData);
			
				$blnX = dbEndTrans($objConn_a, __FUNCTION__);
				if (!$blnX) { $blnErrors = true; }
				
				$intSchemaChangesInRun++;
			}
		}
		else
		{
			$blnEndOfChanges = true;
		}
		dbCloseRecordset($objResultSchemaChanges);
		
		$intTimeNow = microtime(true);
		$intTime = $intTimeNow - $intTimeStart;
		if ($intTime > $MAXSECONDS)
		{
			$blnMoreTime = false;
		}
	}

	if ($intSchemaChangesInRun == 0)
	{
		echo ('no more Schema Changes...');
	}

	echo ('<br>changed ' . $intSchemaChangesInRun . '. ');
	$intTimeEnd = microtime(true);
    $intTime = $intTimeEnd - $intTimeStart;
    echo "process time: " . $intTime . '. ';
	
	return $intSchemaChangesInRun;
}
