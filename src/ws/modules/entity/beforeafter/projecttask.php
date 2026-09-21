<?php

// $strFormDataID_a = PROJECTTASK
// $strRelativeID_a = PROJECT

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
function beforeDisplayAddUpdate_projecttask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;

	if (dependencies('setting/sequenceTypeGet,setting/projectTaskCodeAllocate'))
	{
		// enable sequence for standard override and manual, disable it for others
		// default sequence for all except for manual
		$strSequenceType = sequenceTypeGet($objConn_a, '', 'PROJLY', 'PROJECTTASKID', $strClientID_a);
		if (($strSequenceType == "manual") || ($strSequenceType == "standardoverride"))
		{
			// enable field
			$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "ffe63e2a32-d72a-474c-82d0-b664eef1268a", "CODE", "N");
		}
		else
		{
			// disable field
			$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "ffe63e2a32-d72a-474c-82d0-b664eef1268a", "CODE", "Y");
		}

		if ($blnUpdate_a == false)
		{
			if ($strSequenceType != "manual")
			{
				// allocate sequence
				$strCode = projectTaskCodeAllocate($objConn_a, $strClientID_a);
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffe63e2a32-d72a-474c-82d0-b664eef1268a", "CODE", $strCode);
			}
		}
	}

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffe63e2a32-d72a-474c-82d0-b664eef1268a", "CREATEDATE", getISODate());
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffe63e2a32-d72a-474c-82d0-b664eef1268a", "RAISEDDATE", getISODate());
	
	return $arrJSONData;
}

function beforeAddUpdate_projecttask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_projecttask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameProjectTask = getTableNameEntity("projecttask", false);
	$strTableNameProjectTaskStatus = getTableNameEntity("projecttaskstatus", false);
        
	$arrJSONData = $arrJSONData_a;
    
    $strSQL = "select id returnvalue from ~TABLENAMEPROJECTTASKSTATUS~ where code = 'CANCELLED'";
    $strSQL = str_replace('~TABLENAMEPROJECTTASKSTATUS~', ff($strTableNameProjectTaskStatus), $strSQL);
    $strProjectTaskStatusCancelledID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    $strSQL = "select id returnvalue from ~TABLENAMEPROJECTTASKSTATUS~ where code = 'COMPLETED'";
    $strSQL = str_replace('~TABLENAMEPROJECTTASKSTATUS~', ff($strTableNameProjectTaskStatus), $strSQL);
    $strProjectTaskStatusCompletedID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    $strSQL = "select id returnvalue from ~TABLENAMEPROJECTTASKSTATUS~ where code = 'VERIFIED'";
    $strSQL = str_replace('~TABLENAMEPROJECTTASKSTATUS~', ff($strTableNameProjectTaskStatus), $strSQL);
    $strProjectTaskStatusVerifiedID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    $strProjectTaskStatusID = formValueGetBySectionCodeFieldCode($arrJSONData, "ffe63e2a32-d72a-474c-82d0-b664eef1268a", "PROJECTTASKSTATUS");

    // update is_completed if task status is cancelled, completed or verified
    if (($strProjectTaskStatusID === $strProjectTaskStatusCancelledID)  || ($strProjectTaskStatusID === $strProjectTaskStatusCompletedID)  || ($strProjectTaskStatusID === $strProjectTaskStatusVerifiedID) )
	{
        $strIsCompleted = 'Y';
    }
    else 
	{ 
        $strIsCompleted = 'N';
    }

    $strSQL = "update ~TABLENAMEPROJECTTASK~ set is_completed = '~ISCOMPLETED~' where id = ~ID~";
    $strSQL = str_replace('~TABLENAMEPROJECTTASK~', ff($strTableNameProjectTask), $strSQL);
    $strSQL = str_replace('~ISCOMPLETED~', ff($strIsCompleted), $strSQL);
    $strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);

    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_projecttask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_projecttask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_projecttask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_projecttask($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
