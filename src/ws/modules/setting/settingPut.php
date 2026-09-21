<?php

// put a setting value
// note: this should only be used in the same transaction as the settingPut to ensure that no-one else updated in middle of the operation where updates are required
function settingPut($objConn_a, $strModuleCode_a, $strSettingCode_a, $strClientID_a, $strUserID_a, $strPrinterID_a, $strDeviceID_a, $strSettingValue_a, $strSettingDescription_a, $strNotes_a)
{
	$strDescription = strNormalize($strSettingValue_a);
	
	// legacy settings, the following functions honor the legacy settings: settingFind, settingGet, settingPut
	// MAKE SURE THE SUMMARY IN settingFind is UPDATED AS REQUIRED

	$strSettingCode = $strModuleCode_a . '_' . $strSettingCode_a;
	
    if (dependencies('setting/settingFind')) 
	{
		$strLocation = settingFind($objConn_a, $strModuleCode_a, $strSettingCode_a, $strClientID_a, $strUserID_a, $strPrinterID_a, $strDeviceID_a, $strNotes_a);
		if (strlen($strLocation) > 0)
		{
			// currently only the theme is updatable via code, the rest are set via forms
			$arrLocation = explode("_", $strLocation);
			$strScope = $arrLocation[0];		// combinations of CL U A Q D C S, i.e. CL (client), CLU (client+user), CLD (client+device)
			$strEntity = $arrLocation[1];
			$strSectionCode = $arrLocation[2];
			$strFieldCode = $arrLocation[3];
			$strComplexity = $arrLocation[4];	// V = value, VD = value+description
			
			if ($strEntity == 'SEQUENCE')
			{
				logSetting("settingPut failed with: " . $strSettingCode);	
				dbRaiseCustomError($objConn_a, "Setting get failed: " . $strSettingCode);
			}
			else
			{
				// CL_CLIENTSETTING_GENERALSETTINGS_THEME_VD
				$strTableNameSetting = getTableNameEntity(strtolower($strEntity), false);
				$strSQL = "select id returnvalue from ~TABLENAMESETTING~";
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
					logSetting("settingPut failed with: " . $strSettingCode . ", invalid scope.");	
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
					$strSettingID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
					
					if (strlen($strSettingID) > 0)
					{
						$strSQL = "select jsondata returnvalue from ~TABLENAMESETTING~ where id = ~SETTINGID~";
						$strSQL = str_replace('~TABLENAMESETTING~', ff($strTableNameSetting), $strSQL);
						$strSQL = str_replace('~SETTINGID~', ff($strSettingID), $strSQL);
						$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
						
						$arrJSONData = json_decode($strJSONData, true);
						
						if ($strComplexity == "V")
						{
							$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionCode, $strFieldCode, $strSettingValue_a);
						}
						else if ($strComplexity == "VD")
						{
							$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, $strSectionCode, $strFieldCode, $strSettingValue_a, $strSettingDescription_a);
						}
						else
						{
							logSetting("settingPut failed with: " . $strSettingCode . ", invalid complexity.");	
							dbRaiseCustomError($objConn_a, "Setting get failed: " . $strSettingCode . ", invalid complexity.");
						}

						$strJSONData = json_encode($arrJSONData);

						$strLogin = $_SESSION['server_loggedin_user'];
						
						$strSQL = "update ~TABLENAMESETTING~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~SETTINGID~";
						$strSQL = str_replace('~TABLENAMESETTING~', ff($strTableNameSetting), $strSQL);	
						$strSQL = str_replace('~SETTINGID~', ff($strSettingID), $strSQL);
						$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
						$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
						$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);    
						dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
						exposeEntityData($objConn_a, 'SYSTEMFORM', $strEntity, $strSettingID, $strJSONData);
						
						if (strlen(dbErrorDescription(false)) == 0)
						{
							logSetting("settingPet success: " . $strSettingCode);	
						}
					}
				}
			}
		}
    }
}
