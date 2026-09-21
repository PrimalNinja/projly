<?php


// delete an entity
function entityDelete($objConn_a, $strEntityID_a)
{
	$strTableNameEntity = getTableNameEntity("entity", false);
	$strTableNameEntityOperation = getTableNameEntity("entityoperation", false);
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	$strTableNameSystemForm = getTableNameEntity("systemform", false);
	$strTableNameSystemFormHistory = getTableNameEntity("systemform", true);
	
	$blnResult = false;

	if (dependencies('entity/dropEntityTables'))
	{
		$strSQL = "select code returnvalue from ~TABLENAMEENTITY~ where id = ~ENTITYID~";
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		$strEntityCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		dropEntityTables($objConn_a, $strEntityCode);

		dbBeginTrans($objConn_a, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEPROFILEPERMISSION~ where permission_id in ( select id from ~TABLENAMEPERMISSION~ where code in (select code from ~TABLENAMEENTITYOPERATION~ where relatedentity_id = ~ENTITYID~) )";
		$strSQL = str_replace('~TABLENAMEENTITYOPERATION~', ff($strTableNameEntityOperation), $strSQL);
		$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
		$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEPERMISSION~ where code in (select code from ~TABLENAMEENTITYOPERATION~ where relatedentity_id = ~ENTITYID~)";
		$strSQL = str_replace('~TABLENAMEENTITYOPERATION~', ff($strTableNameEntityOperation), $strSQL);
		$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEENTITYOPERATION~ where relatedentity_id = ~ENTITYID~";
		$strSQL = str_replace('~TABLENAMEENTITYOPERATION~', ff($strTableNameEntityOperation), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMESYSTEMFORMHISTORY~ where dataentity_id = ~ENTITYID~";
		$strSQL = str_replace('~TABLENAMESYSTEMFORMHISTORY~', ff($strTableNameSystemFormHistory), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMESYSTEMFORM~ where dataentity_id = ~ENTITYID~";
		$strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEENTITY~ where id = ~ENTITYID~";
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
	}

	return $blnResult;
}
