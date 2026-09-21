<?php
 
function actionMyBranchSelect($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameBranch = getTableNameEntity("branch", false);

    $strResult = "";
	
	if (dependencies('esb/esbBroadcast'))
	{
		// permission check
		if (!hasPermission($objConn_a, 'MYBRANCHSELECT', __FUNCTION__, true)) {return false;}

		// initialisations
		$strClientID = $_SESSION['server_loggedin_clientid'];
		$strDeviceID = $_SESSION['server_deviceid'];
		
		$strBranchIDEncoded = getJSONParameter($arrParameters_a, 'id');
		$strBranchID = revertSecuredValue($strBranchIDEncoded, 'id', true);

		$strSQL = "select id returnvalue from ~TABLENAMEBRANCH~ where id = ~BRANCHID~";
		$strSQL = str_replace('~TABLENAMEBRANCH~', ff($strTableNameBranch), $strSQL);
		$strSQL = str_replace('~BRANCHID~', ff($strBranchID), $strSQL);
		$strBranchID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		$strBranchName = dbGetDescriptionFromID($objConn_a, $strTableNameBranch, $strBranchID, __FUNCTION__);

		if (strlen($strBranchName) > 0)
		{
			$_SESSION['server_loggedin_branchid'] = $strBranchID;
			$_SESSION['server_loggedin_branchname'] = $strBranchName;
			
			$strEventData = json_encode(array('branchid' => $strBranchIDEncoded, 'branchname' => $strBranchName));

			esbBroadcast($objConn_a, $strClientID, $strDeviceID, '', 'branchchange', $strBranchName, $strEventData, true, false);

			$strDescription = "";	//broadcast handles this currently
			//$strDescription = "Branch '" . $strBranchName . "' selected.";
			$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strDescription, array('branchname' => $strBranchName));
		}
		else
		{
			$strDescription = "Error selecting branch, branch not changed.";
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
		}
	}

    return $strResult;
}
