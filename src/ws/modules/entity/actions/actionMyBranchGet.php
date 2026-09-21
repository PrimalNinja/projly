<?php
 
function actionMyBranchGet($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameBranch = getTableNameEntity("branch", false);

    $strResult = "";
	
	// permission check
	if (!hasPermission($objConn_a, 'MYBRANCHSELECT', __FUNCTION__, true)) {return false;}

	// initialisations
	
	$strBranchID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);
	$strBranchName = '';

	$strSQL = "select id returnvalue from ~TABLENAMEBRANCH~ where id = ~BRANCHID~";
	$strSQL = str_replace('~TABLENAMEBRANCH~', ff($strTableNameBranch), $strSQL);
	$strSQL = str_replace('~BRANCHID~', ff($strBranchID), $strSQL);
	$strBranchID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	$strBranchName = dbGetDescriptionFromID($objConn_a, $strTableNameBranch, $strBranchID, __FUNCTION__);

	if (strlen($strBranchName) > 0)
	{
		$strDescription = "";	//broadcast handles this currently
		//$strDescription = "Branch '" . $strBranchName . "' selected.";
		$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strDescription, array('branchname' => $strBranchName));
	}
	else
	{
		$strDescription = "Error getting branch.";
		$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
	}

    return $strResult;
}
