<?php

// process entitystats
function processEntityStats($objConn_a, $objConnHistory_a)
{
	$strTableNameEntity = getTableNameEntity("entity", false);

	$intTimeStart = microtime(true);

	echo('processing Entity Stats...');
	$intEntityStatsInRun = 0;

	$strSQL = "select id, code, jsondata from ~TABLENAMEENTITY~ order by code";
	$strSQL = str_replace("~TABLENAMEENTITY~", ff($strTableNameEntity), $strSQL);
	$objResultEntities = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	while ($arrRowEntity = dbReadRecord($objResultEntities))
	{
		$strEntityID = $arrRowEntity['id'];
		$strEntityCode = $arrRowEntity['code'];
		$strJSONData = $arrRowEntity['jsondata'];
		$arrJSONData = json_decode($strJSONData, true);
		
		$strTableName = getTableNameEntity($strEntityCode, false);
		$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~";
		$strSQL = str_replace("~TABLENAMEENTITY~", ff($strTableName), $strSQL);
		$intLiveRowCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		$strTableName = getTableNameEntity($strEntityCode, true);
		$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~";
		$strSQL = str_replace("~TABLENAMEENTITY~", ff($strTableName), $strSQL);
		$intHistoryRowCount = dbReadValue($objConnHistory_a, $strSQL, __FUNCTION__);
		
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffe65a2521-c3d0-42fc-8d25-dedd43876fff", "LIVEROWCOUNT", $intLiveRowCount);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffe65a2521-c3d0-42fc-8d25-dedd43876fff", "HISTORYROWCOUNT", $intHistoryRowCount);
		$strJSONData = json_encode($arrJSONData);
		
		dbBeginTrans($objConn_a, __FUNCTION__);
		dbBeginTrans($objConnHistory_a, __FUNCTION__);
	
		$strSQL = "update ~TABLENAMEENTITY~ set jsondata = '~JSONDATA~' where id = ~ENTITYID~";
		$strSQL = str_replace("~TABLENAMEENTITY~", ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace("~ENTITYID~", ff($strEntityID), $strSQL);
		$strSQL = str_replace("~JSONDATA~", ff($strJSONData), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		exposeEntityData($objConn_a, 'SYSTEMFORM', 'ENTITY', $strEntityID, $strJSONData);
	
		$blnX = dbEndTrans($objConnHistory_a, __FUNCTION__);
		$blnX = dbEndTrans($objConn_a, __FUNCTION__);
		
		$intEntityStatsInRun++;
	}
	dbCloseRecordset($objResultEntities);

	if ($intEntityStatsInRun == 0)
	{
		echo ('no more Entity Stats...');
	}

	echo ('<br>updated ' . $intEntityStatsInRun . '. ');
	$intTimeEnd = microtime(true);
    $intTime = $intTimeEnd - $intTimeStart;
    echo "process time: " . $intTime . '. ';
	
	return $intEntityStatsInRun;
}
