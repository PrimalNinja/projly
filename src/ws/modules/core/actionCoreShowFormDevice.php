<?php

function actionCoreShowFormDevice($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameDevice = getTableNameEntity("device", false);
	$strTableNameSystemForm = getTableNameEntity("systemform", false);
	$strTableNameUserFormDevice = getTableNameEntity("userform_device", false);
	
    $arrResult = array();
	$blnResult = false;
	$intConfiguredOther = 0;
	$intConfiguredSelf = 0;
	$arrEventData = array();
	
	if (dependencies('esb/esbBroadcast')) 
	{
		// permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
		
		// session variables
		$strClientID = $_SESSION['server_loggedin_clientid'];
		$strUserID = $_SESSION['server_loggedin_userid'];
		$strDeviceID = $_SESSION['server_deviceid'];
		
		$strFormName = getJSONParameter($arrParameters_a, 'formname');
		$arrParams = getJSONParameter($arrParameters_a, 'params');
		$strCenter = getJSONParameter($arrParameters_a, 'center');
		$strFormOffsetX = getJSONParameter($arrParameters_a, 'formoffsetx');
		$strFormOffsetY = getJSONParameter($arrParameters_a, 'formoffsety');
		$strByPassDirtyCheck = getJSONParameter($arrParameters_a, 'bypassdirtycheck');
		$strFormEntityCode = "";
		
		if (isset($arrParams['formentity']))
		{
			$strFormEntityCode = strtoupper($arrParams['formentity']);
		}
			
		if (strlen($strFormEntityCode) > 0)
		{
			// setup event data
			$arrEventData['formname'] = $strFormName;
			$arrEventData['params'] = $arrParams;
			$arrEventData['center'] = $strCenter;
			$arrEventData['formoffsetx'] = $strFormOffsetX;
			$arrEventData['formoffsety'] = $strFormOffsetY;
			$arrEventData['bypassdirtycheck'] = $strByPassDirtyCheck;			
			$strEventData = json_encode($arrEventData);
			
			// get the system form to check
			$strSQL = "select id returnvalue from ~TABLENAMESYSTEMFORM~ where code = '~ENTITYCODE~'";
			$strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
			$strSQL = str_replace('~ENTITYCODE~', ffeu($strFormEntityCode), $strSQL);
			$strSystemFormID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			if (strlen($strSystemFormID) > 0)
			{
				// check is the form configured for any current device other than this device?
				$strSQL = "select d.id device_id 
							from ~TABLENAMEUSERFORMDEVICE~ ud, ~TABLENAMEDEVICE~ d 
							where 
							ud.device_id = d.id and 
							d.is_current = 'Y' and 
							ud.systemform_id = ~SYSTEMFORMID~ and 
							d.user_id = ~USERID~ and 
							d.client_id = ~CLIENTID~ and
							d.id <> ~DEVICEID~";
				$strSQL = str_replace('~TABLENAMEUSERFORMDEVICE~', ff($strTableNameUserFormDevice), $strSQL);
				$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
				$strSQL = str_replace('~SYSTEMFORMID~', ff($strSystemFormID), $strSQL);
				$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
				$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
				$strSQL = str_replace('~DEVICEID~', ff($strDeviceID), $strSQL);
				$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

				while ($arrRow = dbReadRecord($objResult))
				{
					$strOtherDeviceID = $arrRow['device_id'];
					//debug('processing ' . $strOtherDeviceID);				
					if ($strDeviceID == $strOtherDeviceID)
					{
						$intConfiguredSelf++;
					}
					else
					{
					//debug('from ' . $strDeviceID . ' to ' . $strOtherDeviceID);
						esbBroadcast($objConn_a, $strClientID, $strDeviceID, $strOtherDeviceID, 'formqueue', 'showform', $strEventData, false, false);
						$intConfiguredOther++;
					}
				}
				
				dbCloseRecordset($objResult);
			}
		}
//debug($intConfiguredSelf . "|" . $intConfiguredOther);		
		if (($intConfiguredSelf >= 1) || ($intConfiguredOther == 0))
		{
			$blnResult = true;
		}
		
		$arrResult['result'] = $blnResult;
	}
	
	return createJSONResponse($strDataID_a, RESPONSE_OK, "", $arrResult);
}	