<?php

// $strFormDataID_a = PROJECTISSUE
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
function beforeDisplayAddUpdate_projectissue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;

	if (dependencies('setting/sequenceTypeGet,setting/projectIssueCodeAllocate'))
	{
		// enable sequence for standard override and manual, disable it for others
		// default sequence for all except for manual
		$strSequenceType = sequenceTypeGet($objConn_a, '', 'PROJLY', 'PROJECTISSUEID', $strClientID_a);
		if (($strSequenceType == "manual") || ($strSequenceType == "standardoverride"))
		{
			// enable field
			$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "ffa99b8231-dfd1-424f-be14-c9052b2bdbe6", "CODE", "N");
		}
		else
		{
			// disable field
			$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "ffa99b8231-dfd1-424f-be14-c9052b2bdbe6", "CODE", "Y");
		}

		if ($blnUpdate_a == false)
		{
			if ($strSequenceType != "manual")
			{
				// allocate sequence
				$strCode = projectIssueCodeAllocate($objConn_a, $strClientID_a);
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffa99b8231-dfd1-424f-be14-c9052b2bdbe6", "CODE", $strCode);
			}
		}
	}
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffa99b8231-dfd1-424f-be14-c9052b2bdbe6", "CREATEDATE", getISODate());
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffa99b8231-dfd1-424f-be14-c9052b2bdbe6", "REPORTEDDATE", getISODate());

	return $arrJSONData;
}

function beforeAddUpdate_projectissue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_projectissue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameProjectIssue = getTableNameEntity("projectissue", false);
	$strTableNameProjectIssueStatus = getTableNameEntity("projectissuestatus", false);
        
	$arrJSONData = $arrJSONData_a;
    
    $strSQL = "select id returnvalue from ~TABLENAMEPROJECTISSUESTATUS~ where code = 'CANCELLED'";
    $strSQL = str_replace('~TABLENAMEPROJECTISSUESTATUS~', ff($strTableNameProjectIssueStatus), $strSQL);
    $strProjectIssueStatusCancelledID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    $strSQL = "select id returnvalue from ~TABLENAMEPROJECTISSUESTATUS~ where code = 'COMPLETED'";
    $strSQL = str_replace('~TABLENAMEPROJECTISSUESTATUS~', ff($strTableNameProjectIssueStatus), $strSQL);
    $strProjectIssueStatusCompletedID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    $strSQL = "select id returnvalue from ~TABLENAMEPROJECTISSUESTATUS~ where code = 'VERIFIED'";
    $strSQL = str_replace('~TABLENAMEPROJECTISSUESTATUS~', ff($strTableNameProjectIssueStatus), $strSQL);
    $strProjectIssueStatusVerifiedID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    $strProjectIssueStatusID = formValueGetBySectionCodeFieldCode($arrJSONData, "ffa99b8231-dfd1-424f-be14-c9052b2bdbe6", "PROJECTISSUESTATUS");

    // update is_completed if issue status is cancelled, completed or verified
    if (($strProjectIssueStatusID === $strProjectIssueStatusCancelledID)  || ($strProjectIssueStatusID === $strProjectIssueStatusCompletedID)  || ($strProjectIssueStatusID === $strProjectIssueStatusVerifiedID) )
	{
        $strIsCompleted = 'Y';
    }
    else 
	{ 
        $strIsCompleted = 'N';
    }

    $strSQL = "update ~TABLENAMEPROJECTISSUE~ set is_completed = '~ISCOMPLETED~' where id = ~ID~";
    $strSQL = str_replace('~TABLENAMEPROJECTISSUE~', ff($strTableNameProjectIssue), $strSQL);
    $strSQL = str_replace('~ISCOMPLETED~', ff($strIsCompleted), $strSQL);
    $strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);

    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_projectissue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_projectissue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_projectissue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_projectissue($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
