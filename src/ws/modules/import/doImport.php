<?php

function doImport($objConn_a, $strFileTypeID_a, $arrFilesToImportID_a)
{
    $strTableNameFileType = getTableNameEntity('filetype', false);

	$blnResult = false;
	
	if (dependencies('entity/dataaccess/filetype'))
	{
		if (count($arrFilesToImportID_a) > 0)
		{
			$strFileToImportID = $arrFilesToImportID_a[0]['documentid'];
			
			$strFileTypeCode = fetchValue_filetype($objConn_a, "code", $strFileTypeID_a, []);

			dbRaiseCustomError($objConn_a, "filetype: " . $strFileTypeCode . ", documentid: " . $strFileToImportID);
		}
	}
	
	return $blnResult;
}
