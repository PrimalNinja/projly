<?php

// install a file format
// note: installing a sysadmin will only copy the non-sysadmin permissions
function fileFormatInstall($objConn_a, $strClientIDFrom_a, $strClientIDTo_a, $strFileFormatIDFrom_a)
{
	$strTableNameFileFieldMapping = getTableNameEntity("filefieldmapping", false);
	$strTableNameFileFieldExclusion = getTableNameEntity("filefieldexclusion", false);
	$strTableNameFileFormat = getTableNameEntity("fileformat", false);
	$strTableNameFileFormatField = getTableNameEntity("fileformat_field", false);

    $strFileFormatIDTo = "";

    dbBeginTrans($objConn_a, __FUNCTION__);

    $strLogin = $_SESSION['server_loggedin_user'];

    // create the file format
    $strSQL =
        "
insert into ~TABLENAMEFILEFORMAT~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime,
g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_code, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_description, fileformattemplate_id, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattemplate,
fileformattype_id, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattype, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_filename, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_headerrows, 
g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_import, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_export, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_default, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_has_eol)
select ~CLIENTIDTO~, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, '~MODIFYUSER~', '~MODIFYDATETIME~',
g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_code, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_description, fileformattemplate_id, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattemplate,
fileformattype_id, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattype, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_filename, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_headerrows, 
g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_import, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_export, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_default, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_has_eol
from ~TABLENAMEFILEFORMAT~ where client_id = ~CLIENTIDFROM~ and id = ~FILEFORMATIDFROM~
";
	$strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
    $strSQL = str_replace('~CLIENTIDFROM~', ff($strClientIDFrom_a), $strSQL);
    $strSQL = str_replace('~FILEFORMATIDFROM~', ff($strFileFormatIDFrom_a), $strSQL);
    $strSQL = str_replace('~CLIENTIDTO~', ff($strClientIDTo_a), $strSQL);
    $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
    $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    $strFileFormatIDTo = dbLastInsertID($objConn_a);
	
	$strSQL = "select id, client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, fileformat_id, 
				gdca0c616_9e33_4f80_adb1_c964e9f44713_code, gdca0c616_9e33_4f80_adb1_c964e9f44713_description, filedatatype_id, gdca0c616_9e33_4f80_adb1_c964e9f44713_filedatatype,
				gdca0c616_9e33_4f80_adb1_c964e9f44713_is_mandatory, gdca0c616_9e33_4f80_adb1_c964e9f44713_position, gdca0c616_9e33_4f80_adb1_c964e9f44713_default, gdca0c616_9e33_4f80_adb1_c964e9f44713_length,
				gdca0c616_9e33_4f80_adb1_c964e9f44713_format, gdca0c616_9e33_4f80_adb1_c964e9f44713_multiplier from ~TABLENAMEFILEFORMATFIELD~ where client_id = ~CLIENTIDFROM~ and fileformat_id = ~FILEFORMATIDFROM~";
	$strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
    $strSQL = str_replace('~CLIENTIDFROM~', ff($strClientIDFrom_a), $strSQL);
    $strSQL = str_replace('~FILEFORMATIDFROM~', ff($strFileFormatIDFrom_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) 
	{
		$strFileFormatFieldIDFrom = $arrRow['id'];
		
		// fileformatfield
		$strSQL =
			"
	insert into ~TABLENAMEFILEFORMATFIELD~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, fileformat_id,
	gdca0c616_9e33_4f80_adb1_c964e9f44713_code, gdca0c616_9e33_4f80_adb1_c964e9f44713_description, filedatatype_id, gdca0c616_9e33_4f80_adb1_c964e9f44713_filedatatype,
	gdca0c616_9e33_4f80_adb1_c964e9f44713_is_mandatory, gdca0c616_9e33_4f80_adb1_c964e9f44713_position, gdca0c616_9e33_4f80_adb1_c964e9f44713_default, gdca0c616_9e33_4f80_adb1_c964e9f44713_length,
	gdca0c616_9e33_4f80_adb1_c964e9f44713_format, gdca0c616_9e33_4f80_adb1_c964e9f44713_multiplier)
	select ~CLIENTIDTO~, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, '~MODIFYUSER~', '~MODIFYDATETIME~', ~FILEFORMATIDTO~,
	gdca0c616_9e33_4f80_adb1_c964e9f44713_code, gdca0c616_9e33_4f80_adb1_c964e9f44713_description, filedatatype_id, gdca0c616_9e33_4f80_adb1_c964e9f44713_filedatatype,
	gdca0c616_9e33_4f80_adb1_c964e9f44713_is_mandatory, gdca0c616_9e33_4f80_adb1_c964e9f44713_position, gdca0c616_9e33_4f80_adb1_c964e9f44713_default, gdca0c616_9e33_4f80_adb1_c964e9f44713_length,
	gdca0c616_9e33_4f80_adb1_c964e9f44713_format, gdca0c616_9e33_4f80_adb1_c964e9f44713_multiplier
	from ~TABLENAMEFILEFORMATFIELD~ where client_id = ~CLIENTIDFROM~ and id = ~FILEFORMATFIELDIDFROM~
	";
		$strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
		$strSQL = str_replace('~CLIENTIDFROM~', ff($strClientIDFrom_a), $strSQL);
		$strSQL = str_replace('~FILEFORMATFIELDIDFROM~', ff($strFileFormatFieldIDFrom), $strSQL);
		$strSQL = str_replace('~CLIENTIDTO~', ff($strClientIDTo_a), $strSQL);
		$strSQL = str_replace('~FILEFORMATIDTO~', ff($strFileFormatIDTo), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strFileFormatFieldIDTo = dbLastInsertID($objConn_a);
		
		// filefieldmapping
		$strSQL =
			"
	insert into ~TABLENAMEFILEFIELDMAPPING~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, fileformat_field_id,
	g3b85cacf_9b3f_464b_b76b_2f2fa7b5fcc0_from, g3b85cacf_9b3f_464b_b76b_2f2fa7b5fcc0_to)
	select ~CLIENTIDTO~, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, '~MODIFYUSER~', '~MODIFYDATETIME~', ~FILEFORMATFIELDIDTO~,
	g3b85cacf_9b3f_464b_b76b_2f2fa7b5fcc0_from, g3b85cacf_9b3f_464b_b76b_2f2fa7b5fcc0_to
	from ~TABLENAMEFILEFIELDMAPPING~ where client_id = ~CLIENTIDFROM~ and id = ~FILEFORMATFIELDIDFROM~
	";
		$strSQL = str_replace('~TABLENAMEFILEFIELDMAPPING~', ff($strTableNameFileFieldMapping), $strSQL);
		$strSQL = str_replace('~CLIENTIDFROM~', ff($strClientIDFrom_a), $strSQL);
		$strSQL = str_replace('~FILEFORMATFIELDIDFROM~', ff($strFileFormatFieldIDFrom), $strSQL);
		$strSQL = str_replace('~CLIENTIDTO~', ff($strClientIDTo_a), $strSQL);
		$strSQL = str_replace('~FILEFORMATFIELDIDTO~', ff($strFileFormatFieldIDTo), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		// filefieldexclusion
		$strSQL =
			"
	insert into ~TABLENAMEFILEFIELDEXCLUSION~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, fileformat_field_id,
	g44107c60_2977_4385_9eb0_2bfc71bb76e4_exclusion)
	select ~CLIENTIDTO~, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, '~MODIFYUSER~', '~MODIFYDATETIME~', ~FILEFORMATFIELDIDTO~,
	g44107c60_2977_4385_9eb0_2bfc71bb76e4_exclusion
	from ~TABLENAMEFILEFIELDEXCLUSION~ where client_id = ~CLIENTIDFROM~ and id = ~FILEFORMATFIELDIDFROM~
	";
		$strSQL = str_replace('~TABLENAMEFILEFIELDEXCLUSION~', ff($strTableNameFileFieldExclusion), $strSQL);
		$strSQL = str_replace('~CLIENTIDFROM~', ff($strClientIDFrom_a), $strSQL);
		$strSQL = str_replace('~FILEFORMATFIELDIDFROM~', ff($strFileFormatFieldIDFrom), $strSQL);
		$strSQL = str_replace('~CLIENTIDTO~', ff($strClientIDTo_a), $strSQL);
		$strSQL = str_replace('~FILEFORMATFIELDIDTO~', ff($strFileFormatFieldIDTo), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
	dbCloseRecordset($objResult);
	
    dbEndTrans($objConn_a, __FUNCTION__);

    return $strFileFormatIDTo;
}
