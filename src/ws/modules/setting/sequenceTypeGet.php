<?php

// get a sequence type
function sequenceTypeGet($objConn_a, $strSequenceID_a, $strModuleCode_a, $strSequenceCode_a, $strClientID_a)
{
	$strTableNameSequence = getTableNameEntity("sequence", false);
	$strTableNameSequenceType = getTableNameEntity("sequencetype", false);

	$strSequenceID = $strSequenceID_a;
	$strSequenceType = '';
	
    if (dependencies('setting/settingGet')) 
	{
		// get the appropriate sequence setting
		if (strlen($strSequenceID) == 0)
		{
			$strSequenceID = settingGet($objConn_a, $strModuleCode_a, $strSequenceCode_a, $strClientID_a, '', '', '', __FUNCTION__);
		}
		
		$strSequenceTypeID = "";	// default
		$strSequenceType = 'guid';	// default
		
		if (strlen($strSequenceID) > 0)
		{
			$strSQL = "select jsondata returnvalue from ~TABLENAMESEQUENCE~ where id = ~ID~";
			$strSQL = str_replace('~TABLENAMESEQUENCE~', ff($strTableNameSequence), $strSQL);
			$strSQL = str_replace('~ID~', ff($strSequenceID), $strSQL);
			$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			$arrJSONData = json_decode($strJSONData, true);
//return $strModuleCode_a . ":" . $strSequenceCode_a . ":" . $strSequenceID;
			$strSequenceTypeID = formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'SEQUENCETYPE');
			$strSequenceType = strtolower(dbReadValueByID($objConn_a, $strTableNameSequenceType, "code", $strSequenceTypeID, __FUNCTION__));
		}
	}
		
	return $strSequenceType;
}
