<?php

// open a document
function documentOpen($objConn_a, $strDocumentID_a)
{
	if (dependencies('entity/dataaccess/document'))
	{
		$arrDBFields = [["s", "is_committed", "N"]];
		update_document($objConn_a, [], $arrDBFields, $strDocumentID_a, []);
	}
}
