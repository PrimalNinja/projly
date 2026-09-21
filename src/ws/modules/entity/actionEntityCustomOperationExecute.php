<?php

function actionEntityCustomOperationExecute($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strResult = "";	// note that all the custom operation actions themseves return the correct JSON formatted response

	// permission check
	if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}

    $strEntityCode = getJSONParameter($arrParameters_a, 'entitycode');
    $strOperationCode = getJSONParameter($arrParameters_a, 'operationcode');
	$arrSplit = explode('_', $strOperationCode);
	$blnInvoke = ($arrSplit[0] == 'INVOKE');

	if ($blnInvoke)
	{
		$strFunction = strtolower($strOperationCode);
	}
	else
	{
		$strFunction = strtolower($strEntityCode . '_' . $strOperationCode);
	}

	if (dependencies('entity/inc-customactions')) 
	{
		$arrCustomActions = getCustomActions();

		$arrFunction = validateFunction($arrCustomActions, $strFunction);

		//logDebug('attempt to dispatch custom action: ' . $strFunction, '');
		if ($arrFunction !== null) 
		{
			$strResult = dispatchFunction($objConn_a, $strSecurityToken_a, $strDataID_a, $arrFunction, $arrParameters_a);
		}
		else
		{
			logDebug('failed to despatch custom action: ' . $strFunction, '');
		}
	}

	return $strResult;
}
