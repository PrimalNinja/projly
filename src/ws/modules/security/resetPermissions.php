<?php
 
function resetPermissions($objConn_a, $strClientID_a) 
{ 
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	$strTableNameSystemForm = getTableNameEntity("systemform", false);
	
	$blnResult = false;

	$strLogin = $_SESSION['server_loggedin_user'];
	
	$arrPermissionFields = [];
	$arrProfilePermissionJSON = [];

	$strProfiles = PROFILEDEFAULTS_SYSTEM;
	if (strlen(PROFILEDEFAULTS_EXTRA) > 0)
	{
		$strProfiles .= "," . PROFILEDEFAULTS_EXTRA;
	}
	
	$arrProfiles = explode("," , $strProfiles);
	

	dbBeginTrans($objConn_a, __FUNCTION__);
	
	// fetch fields in permission for check if a profile field already exists
	$strSQL = "show columns from ~TABLENAMEPERMISSION~";
	$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);               
	while ($arrRow = dbReadRecord($objResult)) 
	{   
		$arrPermissionFields[] = $arrRow['Field'];
	}
	dbCloseRecordset($objResult);
	
	foreach ($arrProfiles as $strProfileCode)
	{
		$strFieldName = "def_" . $strProfileCode;
		$strFieldName = strtolower($strFieldName);

		$strProfilePermissionJSONFile = WS_PATH . 'modules/security/profiles/' . $strFieldName . '.json';

		if (file_exists($strProfilePermissionJSONFile))
		{
			$arrProfilePermissionJSONResult = json_decode(loadFile($strProfilePermissionJSONFile), true);
			$arrProfilePermissionJSON[$strFieldName] = $arrProfilePermissionJSONResult['permissions'];                             
		}
		else
		{
logDebug('file does not exist:' . $strProfilePermissionJSONFile, '');
die();
		}

		// add the column field in permission table if does not exists
		if (!in_array($strFieldName, $arrPermissionFields))
		{
			$strSQL = "alter table ~TABLENAMEPERMISSION~ add column ~FIELDNAME~ varchar(1) null";
			$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
			$strSQL = str_replace('~FIELDNAME~', ff($strFieldName), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__); 
		}  
		
		if (isset($arrProfilePermissionJSON[$strFieldName]))
		{
			$arrPermissions = $arrProfilePermissionJSON[$strFieldName];
			$strPermissionCodeList = "'" . implode('\', \'', $arrPermissions) . "'";
			
			// mark Y field in permission table                
			$strSQL = "update ~TABLENAMEPERMISSION~ set ~FIELDNAME~ = 'Y' where code in (~PERMISSIONCODELIST~)";
			$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
			$strSQL = str_replace('~FIELDNAME~', $strFieldName, $strSQL);
			$strSQL = str_replace('~PERMISSIONCODELIST~', $strPermissionCodeList, $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);   
		}
	}

	$strSQL = "select id from ~TABLENAMECLIENT~ order by client_id";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$objResultClient = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	while ($arrRowClient = dbReadRecord($objResultClient)) 
	{
		$strClientID = $arrRowClient['id'];

		foreach ($arrProfiles as $strProfileCode)
		{
			$strFieldName = "def_" . $strProfileCode;
			$strFieldName = strtolower($strFieldName);
							
			$strSQL = "select id returnvalue from ~TABLENAMEPROFILE~ where client_id = ~CLIENTID~ and code = '~PROFILECODE~'";
			$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strSQL = str_replace('~PROFILECODE~', ff($strProfileCode), $strSQL);
			$strProfileID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			if (strlen($strProfileID) > 0)
			{
				// reset the profiles across all clients
				$strSQL = "delete from ~TABLENAMEPROFILEPERMISSION~ where client_id = ~CLIENTID~ and profile_id = ~PROFILEID~";
				$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
				$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
				$strSQL = str_replace('~PROFILEID~', ff($strProfileID), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				
				if (isset($arrProfilePermissionJSON[$strFieldName]))
				{
					$arrPermissions = $arrProfilePermissionJSON[$strFieldName];
					$strPermissionCodeList = "'" . implode('\', \'', $arrPermissions) . "'";

					$strSQL = 
					"
					insert into ~TABLENAMEPROFILEPERMISSION~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, profile_id, permission_id)
					select pro.client_id, perm.entity_id, perm.dataentity_id, perm.code, perm.description, 'Y', pro.client_id, null, '~MODIFYUSER~', '~MODIFYDATETIME~', pro.id, perm.id
					from ~TABLENAMEPROFILE~ pro, ~TABLENAMEPERMISSION~ perm where perm.code in (~PERMISSIONCODELIST~) and pro.id = ~PROFILEID~
					";
					$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
					$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
					$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
					$strSQL = str_replace('~PERMISSIONCODELIST~', $strPermissionCodeList, $strSQL);
					$strSQL = str_replace('~PROFILEID~', ff($strProfileID), $strSQL);
					$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
					$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);                                                                      
				}
				
			}
		}
	}
	dbCloseRecordset($objResultClient);
			
	$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
	
	return $blnResult;
}