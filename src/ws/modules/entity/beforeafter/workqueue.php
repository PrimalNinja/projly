<?php

// $strFormDataID_a = WORKQUEUE

// code in before display add events:
//		default fields within the JSON for display purposes before a user starts filling in a form
//
// code in after events: 
//		exposing fields
//		populating manually created fields
//
// code in before events: 
// 		modifying the json that is to be stored (it is stored automatically)
//		validation such as uniqueness (don't forget to put unique indexes on field combinations you need to be unique)
//
// event order:  
//		before events, saving of json and common fields, after events, transfer of exposed field's values
//
function beforeDisplayAddUpdate_workqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$strTableNameWorkQueue = getTableNameEntity("workqueue", false);
	$strTableNameWorkQueueItemType = getTableNameEntity("workqueueitemtype", false);
	$strTableNameWorkQueueStatus = getTableNameEntity("workqueuestatus", false);

	$arrJSONData = $arrJSONData_a;
	
	if ($blnUpdate_a == false)
	{
		if (dependencies('setting/sequenceTypeGet,setting/workQueueCodeAllocate'))
		{
			// enable sequence for standard override and manual, disable it for others
			// default sequence for all except for manual
			$strSequenceType = sequenceTypeGet($objConn_a, '', 'CORE', 'WORKQUEUEID', $strClientID_a);
			if (($strSequenceType == "manual") || ($strSequenceType == "standardoverride"))
			{
				// enable field
				$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "WORKQUEUEITEMHEADER", "CODE", "N");
			}
			else
			{
				// disable field
				$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "WORKQUEUEITEMHEADER", "CODE", "Y");
			}

			if ($strSequenceType != "manual")
			{
				// allocate sequence
				$strCode = workQueueCodeAllocate($objConn_a, $strClientID_a);
			}
		
			$strWorkQueueItemTypeID = "";
			$strWorkQueueItemTypeDesc = "";

			$strSQL = "select id, description from ~TABLENAMEWORKQUEUEITEMTYPE~ where code = '~CODE~'";
			$strSQL = str_replace('~TABLENAMEWORKQUEUEITEMTYPE~', ff($strTableNameWorkQueueItemType), $strSQL);
			$strSQL = str_replace('~CODE~', ff(WQIT_GENERAL), $strSQL);
			$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			if ($arrRow = dbReadRecord($objResult)) 
			{
				$strWorkQueueItemTypeID = $arrRow['id'];
				$strWorkQueueItemTypeDesc = $arrRow['description'];
			}
			dbCloseRecordset($objResult);

			if (strlen($strWorkQueueItemTypeID) > 0)
			{
				$strSQL = "select description returnvalue from ~TABLENAMEWORKQUEUESTATUS~ where workqueueitemtype_id = ~WORKQUEUEITEMTYPEID~ and code = 'NOTSTARTED'";
				$strSQL = str_replace('~TABLENAMEWORKQUEUESTATUS~', ff($strTableNameWorkQueueStatus), $strSQL);
				$strSQL = str_replace('~WORKQUEUEITEMTYPEID~', ff($strWorkQueueItemTypeID), $strSQL);
				$strWorkQueueStatusDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				
				$arrSelection = selectionListCreateFromDBByEntityCode($objConn_a, $strClientID_a, "WORKQUEUESTATUS", 'workqueueitemtype_id', $strWorkQueueItemTypeID);	
				$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "WORKQUEUEITEMHEADER", "WORKQUEUESTATUS", "NOTSTARTED", $strWorkQueueStatusDescription);	// FRANCIS TO FIX, should put the statusid, not code in the value
				$arrJSONData = formSelectionUpdateBySectionCodeFieldCode($arrJSONData, "WORKQUEUEITEMHEADER", "WORKQUEUESTATUS", $arrSelection);
			}

			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "WORKQUEUEITEMHEADER", "WORKQUEUEITEMTYPE", $strWorkQueueItemTypeID, $strWorkQueueItemTypeDesc);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "WORKQUEUEITEMHEADER", "CODE", $strCode);
		}
	}
	else
	{
		// get the parent id if there is one
		$strSQL = "select parent_id returnvalue from ~TABLENAMEWORKQUEUE~ where id = ~WORKQUEUEID~";
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strFormDataID_a), $strSQL);
		$strParentID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		// if we have a parent id, we use the parent id's code to delegate to the dependency, otherwise we are the actual parent and use our own code
		$strWorkQueueID = $strFormDataID_a;
		if (strlen($strParentID) > 0)
		{
			$strWorkQueueID = $strParentID;
		}
		
		// get the actual code to delegate to
		// $strSQL = "select wqit.ff6155f1a0_0a1d_4130_ac46_4a4f95b3e4df_code returnvalue from ~TABLENAMEWORKQUEUE~ wq, ~TABLENAMEWORKQUEUEITEMTYPE~ wqit where wqit.id = wq.workqueueitemtype_id and wq.id = ~WORKQUEUEID~";
		// $strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		// $strSQL = str_replace('~TABLENAMEWORKQUEUEITEMTYPE~', ff($strTableNameWorkQueueItemType), $strSQL);
		// $strSQL = str_replace('~WORKQUEUEID~', ff($strWorkQueueID), $strSQL);
		// $strWorkQueueParentTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		// $strSQL = "select wqit.ff6155f1a0_0a1d_4130_ac46_4a4f95b3e4df_code returnvalue from ~TABLENAMEWORKQUEUE~ wq, ~TABLENAMEWORKQUEUEITEMTYPE~ wqit where wqit.id = wq.workqueueitemtype_id and wq.id = ~WORKQUEUEID~";
		// $strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		// $strSQL = str_replace('~TABLENAMEWORKQUEUEITEMTYPE~', ff($strTableNameWorkQueueItemType), $strSQL);
		// $strSQL = str_replace('~WORKQUEUEID~', ff($strFormDataID_a), $strSQL);
		// $strWorkQueueChildTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	}
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "WORKQUEUEITEMBODY", "PERSONSTATUS", "");	// no longer archived
	
	return $arrJSONData;
}

function beforeAddUpdate_workqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameWorkQueue = getTableNameEntity("workqueue", false);
	$strTableNameWorkQueueItemType = getTableNameEntity("workqueueitemtype", false);
    $strTableNameWorkQueueStatus = getTableNameEntity("workqueuestatus", false);
	
	$arrJSONData = $arrJSONData_a;

	if ($blnUpdate_a)
	{
		// get the parent id if there is one
		$strSQL = "select parent_id returnvalue from ~TABLENAMEWORKQUEUE~ where id = ~WORKQUEUEID~";
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strFormDataID_a), $strSQL);
		$strParentID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		// if we have a parent id, we use the parent id's code to delegate to the dependency, otherwise we are the actual parent and use our own code
		$strWorkQueueID = $strFormDataID_a;
		if (strlen($strParentID) > 0)
		{
			$strWorkQueueID = $strParentID;
		}
        		
		// get the actual code to delegate to
		// $strSQL = "select wqit.ff6155f1a0_0a1d_4130_ac46_4a4f95b3e4df_code returnvalue from ~TABLENAMEWORKQUEUE~ wq, ~TABLENAMEWORKQUEUEITEMTYPE~ wqit where wqit.id = wq.workqueueitemtype_id and wq.id = ~WORKQUEUEID~";
		// $strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		// $strSQL = str_replace('~TABLENAMEWORKQUEUEITEMTYPE~', ff($strTableNameWorkQueueItemType), $strSQL);
		// $strSQL = str_replace('~WORKQUEUEID~', ff($strWorkQueueID), $strSQL);
		// $strWorkQueueParentTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		// $strSQL = "select wqit.ff6155f1a0_0a1d_4130_ac46_4a4f95b3e4df_code returnvalue from ~TABLENAMEWORKQUEUE~ wq, ~TABLENAMEWORKQUEUEITEMTYPE~ wqit where wqit.id = wq.workqueueitemtype_id and wq.id = ~WORKQUEUEID~";
		// $strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		// $strSQL = str_replace('~TABLENAMEWORKQUEUEITEMTYPE~', ff($strTableNameWorkQueueItemType), $strSQL);
		// $strSQL = str_replace('~WORKQUEUEID~', ff($strFormDataID_a), $strSQL);
		// $strWorkQueueChildTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	}
	
	return $arrJSONData;
}

function afterAddUpdate_workqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameWorkQueue = getTableNameEntity("workqueue", false);
	$strTableNameWorkQueueItemType = getTableNameEntity("workqueueitemtype", false);
	$strTableNameWorkQueueStatus = getTableNameEntity("workqueuestatus", false);
	
	$arrJSONData = $arrJSONData_a;
	
	if ($blnUpdate_a)
	{
		// get the parent id if there is one
		$strSQL = "select parent_id returnvalue from ~TABLENAMEWORKQUEUE~ where id = ~WORKQUEUEID~";
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strFormDataID_a), $strSQL);
		$strParentID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		// if we have a parent id, we use the parent id's code to delegate to the dependency, otherwise we are the actual parent and use our own code
		$strWorkQueueID = $strFormDataID_a;
		if (strlen($strParentID) > 0)
		{
			$strWorkQueueID = $strParentID;
		}
		
		// get the actual code to delegate to
		// $strSQL = "select wqit.ff6155f1a0_0a1d_4130_ac46_4a4f95b3e4df_code returnvalue from ~TABLENAMEWORKQUEUE~ wq, ~TABLENAMEWORKQUEUEITEMTYPE~ wqit where wqit.id = wq.workqueueitemtype_id and wq.id = ~WORKQUEUEID~";
		// $strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		// $strSQL = str_replace('~TABLENAMEWORKQUEUEITEMTYPE~', ff($strTableNameWorkQueueItemType), $strSQL);
		// $strSQL = str_replace('~WORKQUEUEID~', ff($strWorkQueueID), $strSQL);
		// $strWorkQueueParentTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		$strSQL = "select wqit.ff6155f1a0_0a1d_4130_ac46_4a4f95b3e4df_code returnvalue from ~TABLENAMEWORKQUEUE~ wq, ~TABLENAMEWORKQUEUEITEMTYPE~ wqit where wqit.id = wq.workqueueitemtype_id and wq.id = ~WORKQUEUEID~";
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~TABLENAMEWORKQUEUEITEMTYPE~', ff($strTableNameWorkQueueItemType), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strFormDataID_a), $strSQL);
		$strWorkQueueChildTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		// generic status update code
		if (dependencies('workqueue/workQueueItemUpdate'))
		{
			workQueueItemUpdate($objConn_a, $strClientID_a, $strParentID, $strFormDataID_a, $arrJSONData_a, $strWorkQueueChildTypeCode);
		}
	}
	else
	{
		$strParentID = $strRelativeID_a;
		$strParentDescription = "";
		if (strlen($strParentID) > 0)
		{
			$strSQL = "select workqueueitemheader_longdescription returnvalue from ~TABLENAMEWORKQUEUE~ where id = ~PARENTID~";
			$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
			$strSQL = str_replace('~PARENTID~', ff($strParentID), $strSQL);
			$strParentDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		}
		
		$strSQL = "select jsondata returnvalue from ~TABLENAMEWORKQUEUE~ where id = ~WORKQUEUEID~";
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strFormDataID_a), $strSQL);
		$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		$arrJSONData = json_decode($strJSONData, true);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "WORKQUEUEITEMHEADER", "PARENT", $strParentDescription);
		$strJSONData = json_encode($arrJSONData);
		
		$strSQL = "update ~TABLENAMEWORKQUEUE~ set jsondata = '~JSONDATA~', parent_id = ~PARENTID~ where id = ~WORKQUEUEID~";
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strFormDataID_a), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~PARENTID~', ffn($strParentID), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}

	
	if (strlen($strParentID) > 0)
	{
		$strSQL = "select count(*) returnvalue from ~TABLENAMEWORKQUEUE~ wq, ~TABLENAMEWORKQUEUESTATUS~ wqs where wq.workqueuestatus_id = wqs.id and wqs.ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_is_completed = 'Y' and wq.parent_id = ~WORKQUEUEID~";            
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~TABLENAMEWORKQUEUESTATUS~', ff($strTableNameWorkQueueStatus), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strParentID), $strSQL);
		$strChildTasksCompletedCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		$strSQL = "select count(*) returnvalue from ~TABLENAMEWORKQUEUE~ where parent_id = ~WORKQUEUEID~";            
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strParentID), $strSQL);
		$strChildTasksCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		$strProgress = $strChildTasksCompletedCount . ' / ' . $strChildTasksCount;
		
		$strSQL = "update ~TABLENAMEWORKQUEUE~ set progress = '~PROGRESS~' where id = ~WORKQUEUEID~";
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strParentID), $strSQL);
		$strSQL = str_replace('~PROGRESS~', ff($strProgress), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
	
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_workqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameWorkQueue = getTableNameEntity("workqueue", false);
	
	$arrJSONData = $arrJSONData_a;
	
	if ($blnUpdate_a == false)
	{
		$intLevel = 1;
		$strParentID = $strRelativeID_a;
		if (strlen($strParentID) > 0)
		{
			$intLevel = 2;
		}
		
		$strSQL = "update ~TABLENAMEWORKQUEUE~ set createdatetime = '~CREATEDATETIME~', is_completed = 'N', level = ~LEVEL~ where id = ~WORKQUEUEID~";
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~CREATEDATETIME~', getDateTime(), $strSQL);
		$strSQL = str_replace('~LEVEL~', ff($intLevel), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
	
	return $arrJSONData;
}

function beforeDelete_workqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	$strTableNameWorkQueue = getTableNameEntity("workqueue", false);
	$strTableNameWorkQueueStatus = getTableNameEntity("workqueuestatus", false);
	
	$strSQL = "delete from ~TABLENAMEWORKQUEUE~ where parent_id = ~PARENTWORKQUEUEID~";
	$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
	$strSQL = str_replace('~PARENTWORKQUEUEID~', ff($strFormDataID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}

function afterDelete_workqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	$strTableNameWorkQueue = getTableNameEntity("workqueue", false);
	$strTableNameWorkQueueStatus = getTableNameEntity("workqueuestatus", false);
	
	$strParentID = $strRelativeID_a;
	if (strlen($strParentID) > 0)
	{
		$strSQL = "select count(*) returnvalue from ~TABLENAMEWORKQUEUE~ wq, ~TABLENAMEWORKQUEUESTATUS~ wqs where wq.workqueuestatus_id = wqs.id and wqs.ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_is_completed = 'Y' and wq.parent_id = ~WORKQUEUEID~";            
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~TABLENAMEWORKQUEUESTATUS~', ff($strTableNameWorkQueueStatus), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strParentID), $strSQL);
		$strChildTasksCompletedCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		$strSQL = "select count(*) returnvalue from ~TABLENAMEWORKQUEUE~ where parent_id = ~WORKQUEUEID~";            
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strParentID), $strSQL);
		$strChildTasksCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		$strProgress = "";
		if (intval($strChildTasksCount, 10) > 0)
		{
			$strProgress = $strChildTasksCompletedCount . ' / ' . $strChildTasksCount;
		}
		
		$strSQL = "update ~TABLENAMEWORKQUEUE~ set progress = '~PROGRESS~' where id = ~WORKQUEUEID~";
		$strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
		$strSQL = str_replace('~WORKQUEUEID~', ff($strParentID), $strSQL);
		$strSQL = str_replace('~PROGRESS~', ff($strProgress), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_workqueue($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
