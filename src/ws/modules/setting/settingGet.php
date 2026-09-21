<?php
// get a setting
// note: this should only be used in the same transaction as the settingPut to ensure that no-one else updated in middle of the operation where updates are required
function settingGet($objConn_a, $strModuleCode_a, $strSettingCode_a, $strClientID_a, $strUserID_a, $strPrinterID_a, $strDeviceID_a, $strNotes_a)
{
	$varResult = "";
	$strEntity = "";

	// legacy settings, the following functions honor the legacy settings: settingFind, settingGet, settingPut
	// MAKE SURE THE SUMMARY IN settingFind is UPDATED AS REQUIRED
	
	$strSettingCode = $strModuleCode_a . '_' . $strSettingCode_a;
	
    if (dependencies('setting/settingFind')) 
	{
		$strLocation = settingFind($objConn_a, $strModuleCode_a, $strSettingCode_a, $strClientID_a, $strUserID_a, $strPrinterID_a, $strDeviceID_a, $strNotes_a);
		if (strlen($strLocation) > 0)
		{
			logSetting("settingGet attempt with: " . $strSettingCode . " at location: " . $strLocation . ", " . $strNotes_a);	
			$arrLocation = explode("_", $strLocation);
			$strScope = $arrLocation[0];		// combinations of CL U D, i.e. CL (client), CLU (client+user), CLD (client+device)
			$strEntity = $arrLocation[1];
			$strSectionCode = $arrLocation[2];
			$strSequenceCode = $strSectionCode;
			$strFieldCode = "";
			if (count($arrLocation) > 3)
			{
				$strFieldCode = $arrLocation[3];
			}
			$strComplexity = "";
			if (count($arrLocation) > 4)
			{
				$strComplexity = $arrLocation[4];	// V = value, D = description, VD = value+description
			}
			
			if ($strEntity == 'SEQUENCE')
			{
				$strTableNameSequence = getTableNameEntity(strtolower($strEntity), false);
				$strSQL = "select id returnvalue from ~TABLENAMESQUENCE~";
				$strWhere = "";
				
				if ($strScope == 'CL')
				{
					$strWhere = "where client_id = ~CLIENTID~ and code = '~CODE~'";
				}
				else
				{
					logSetting("settingGet failed with: " . $strSettingCode . ", invalid scope, " . $strNotes_a);	
					dbRaiseCustomError($objConn_a, "Setting get failed: " . $strSettingCode . ", invalid scope.");
				}

				if (strlen($strWhere) > 0)
				{
					$strSQL = $strSQL . " " . $strWhere;
					$strSQL = str_replace('~TABLENAMESQUENCE~', ff($strTableNameSequence), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
					$strSQL = str_replace('~CODE~', ff($strSequenceCode), $strSQL);
					$varResult = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
					logSetting("settingGet success: " . $strSettingCode . ", value: " . $varResult . ", sql: " . $strSQL . ", " . $strNotes_a);	
				}
			}
			else
			{
				$strTableNameSetting = getTableNameEntity(strtolower($strEntity), false);
				$strSQL = "select jsondata returnvalue from ~TABLENAMESETTING~";
				$strWhere = "";
				
				if ($strScope == 'CL')
				{
					$strWhere = "where client_id = ~CLIENTID~";
				}
				else if ($strScope == 'CLU')
				{
					$strWhere = "where client_id = ~CLIENTID~ and user_id = ~USERID~";
				}
				else if ($strScope == 'CLP')
				{
					$strWhere = "where client_id = ~CLIENTID~ and id = ~PRINTERID~";
				}
				else if ($strScope == 'CLD')
				{
					$strWhere = "where client_id = ~CLIENTID~ and id = ~DEVICEID~";
				}
				else
				{
					logSetting("settingGet failed with: " . $strSettingCode . ", invalid scope, " . $strNotes_a);	
					dbRaiseCustomError($objConn_a, "Setting get failed: " . $strSettingCode . ", invalid scope.");
				}
				
				if (strlen($strWhere) > 0)
				{
					$strSQL = $strSQL . " " . $strWhere;
					$strSQL = str_replace('~TABLENAMESETTING~', ff($strTableNameSetting), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
					$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
					$strSQL = str_replace('~PRINTERID~', ff($strPrinterID_a), $strSQL);
					$strSQL = str_replace('~DEVICEID~', ff($strDeviceID_a), $strSQL);
					$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
					
					$arrJSONData = json_decode($strJSONData, true);
					if ($strComplexity == 'D')
					{
						$varResult = formDescriptionGetBySectionCodeFieldCode($arrJSONData, $strSectionCode, $strFieldCode);
					}
					else
					{
						$varResult = formValueGetBySectionCodeFieldCode($arrJSONData, $strSectionCode, $strFieldCode);
					}
					
					if (is_array($varResult))
					{
						logSetting("settingGet success: " . $strSettingCode . ", value: " . print_r($varResult, true) . ", sql: " . $strSQL . ", " . $strNotes_a);	
					}
					else
					{
						logSetting("settingGet success: " . $strSettingCode . ", value: '" . $varResult . "', sql: " . $strSQL . ", " . $strNotes_a);	
						//logSetting("jsondata: " . print_r($arrJSONData, true));
					}
				}
			}
		}
    }

    return $varResult;
}
