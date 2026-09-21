<?php
function fetchProjlyForms($objConn_a, $strClientID_a)
{
	$strTableNameMobileForm = getTableNameEntity("mobileform", false);
	$strTableNameSystemForm = getTableNameEntity("systemform", false);

    $arrResult = array();
	
	$strClientID = $strClientID_a;
	
	$strSystemOwnerClientID = getSystemOwnerClientID($objConn_a);

	$strEntityCode = "";

	if ($strClientID <= $strSystemOwnerClientID)
	{
		$strEntityCode = "PHONEREGISTRATION";

		$strSQL = "select s.id, s.entity_id, s.client_id, s.code, s.description, s.is_enabled, s.jsondata, s.modifyuser, s.modifydatetime
    			from ~TABLENAMESYSTEMFORM~ s  where code '~ENTITYCODE~'";
		$strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
		$strSQL = str_replace('~ENTITYCODE~', $strEntityCode, $strSQL);

		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	}
	else
	{
		$strValidDate = date("Y-m-d");

		$strSQL = "select s.id, s.entity_id, s.client_id, s.code, s.description, s.is_enabled, s.jsondata, s.modifyuser, s.modifydatetime
				from ~TABLENAMESYSTEMFORM~ s, ~TABLENAMEMOBILEFORM~ m where s.id=m.systemform_id and m.is_enabled = 'Y' 
				and 
				(m.g1d033c60_b8bd_4925_97ab_41efd975e746_validtodate is null 
				or
				m.g1d033c60_b8bd_4925_97ab_41efd975e746_validtodate = ''
				or
				(g1d033c60_b8bd_4925_97ab_41efd975e746_validfromdate >= '~VALIDDATE~' and g1d033c60_b8bd_4925_97ab_41efd975e746_validtodate <= '~VALIDDATE~'))";
		$strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
		$strSQL = str_replace('~TABLENAMEMOBILEFORM~', ff($strTableNameMobileForm), $strSQL);
		$strSQL = str_replace('~VALIDDATE~', ff($strValidDate), $strSQL);
		
		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	}
	
        
	while ($arrRow = dbReadRecord($objResult)) {

		$arrResult[] = array(
			"id" => secureEntityValue('SYSTEMFORM', $arrRow['id']),
			"entity_id" => secureEntityValue('ENTITY', $arrRow['entity_id']),
			"client_id" => secureEntityValue('CLIENT', $arrRow['client_id']),
			"code" => $arrRow['code'],
			"description" => $arrRow['description'],
			"is_enabled" => $arrRow['is_enabled'],
			"jsondata" => formSecureIDs(json_decode($arrRow['jsondata'], true)),
			"modifyuser" => $arrRow['modifyuser'],
			"modifydatetime" => $arrRow['modifydatetime']
		);
	}

    dbCloseRecordset($objResult);
    
    return $arrResult;
}