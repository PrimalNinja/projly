<?php

// function summary:

// getDocumentTypeIDByCode($objConn_a, $strDocumentTypeCode_a)

function getDocumentTypeIDByCode($objConn_a, $strDocumentTypeCode_a)
{
	$strTableNameDocumentType = getTableNameEntity("documenttype", false);
	
	$strSQL = "select id returnvalue from ~TABLENAMEDOCUMENTTYPE~ where code = '~DOCUMENTTYPECODE~'";
	$strSQL = str_replace('~TABLENAMEDOCUMENTTYPE~', ff($strTableNameDocumentType), $strSQL);
	$strSQL = str_replace('~DOCUMENTTYPECODE~', ff($strDocumentTypeCode_a), $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

