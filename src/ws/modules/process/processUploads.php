<?php
// returns an error if there is one, or an empty string if no error unless in nobatch mode it returns the document ids
// this should be split into two functions, one for nobatch and one for batch so that results are consistent with each function
function processUploads($objConn_a, $strClientID_a, $strMetaData_a, $blnNoBatch_a)
{
	$arrResult = array("result"=> STAT_ERROR, "error"=>"Upload Processing Error", "tagtype"=>"", "tag"=> "");

    if (dependencies('batch/dropTempTable') &&
        dependencies('import/fileFormatFetch') &&
        dependencies('import/importUploadedGeneric') &&
        dependencies('import/importUploadedImages') &&
        dependencies('utils/import')) 
	{
        $arrMetadata = json_decode($strMetaData_a, true);

        $objConnSystemTemp = dbOpen(DBSYSTEMTEMP_HOSTNAME, DBSYSTEMTEMP_LOGIN, DBSYSTEMTEMP_PASSWORD, DBSYSTEMTEMP_DATABASENAME);
        if ($objConnSystemTemp) 
		{
            // "securitytoken":"520ae4958cd7f1-92140929"
            // "clientversion":"0.1"
            // "callerid":"public"
            // "function":"import_rates"
            // "functionid":"1"
            // "filename":"520af5216a2297.71514858"
            // "original_filename":"80708578_L_and_X_combined.csv"
            // "size":2294336
            // "type":"application\/vnd.ms-excel"

            $strFunction = $arrMetadata['function'];
            $strFileFormatID = $arrMetadata['functionid'];
            $strFilename = $arrMetadata['filename'];
            $strOriginalFilename = $arrMetadata['original_filename'];
            
            // unzip the file
            unzipFile(TEMP_PENDING_PATH . $strFilename, TEMP_PENDING_PATH);

            $objFileFormat = null;

			if ($blnNoBatch_a)
			{
				// only documents that are not to be processed are supported by nobatch
				if ($strFunction == 'import_images') 
				{
					$arrResult = importUploadedImages($objConn_a, TEMP_PENDING_PATH . $strFilename, $strFilename, $strOriginalFilename, $strClientID_a);
				}
				else if ($strFunction == 'import_generic') 
				{
					$arrResult = importUploadedGeneric($objConn_a, TEMP_PENDING_PATH . $strFilename, $strFilename, $strOriginalFilename, $strClientID_a);
				}
				if ($arrResult["result"] == STAT_COMPLETED) 
				{
					// delete uploaded file if there was no processing error
					unlink(TEMP_PENDING_PATH . $strFilename);
					unlink(TEMP_PENDING_PATH . $strFilename . '.json');
				}
			}
			else
			{
                if ($strFunction != 'import_images' && $strFunction != 'import_generic') 
				{
					$objFileFormat = fileFormatFetch($objConn_a, $strClientID_a, $strFileFormatID, true);

					if (strtolower($objFileFormat->formattype) == "fw") 
					{
						// if we have a fixed width format
						$objFileFormatCSV = internalCSVFormatGet($objConn_a, $strClientID_a, $strFileFormatID);

						// translate datafile from user file format into internal format
						convertFWtoCSV(TEMP_PENDING_PATH, $strFilename, $objFileFormat, $objFileFormatCSV);
						//import based on the internal format
						$objFileFormat = $objFileFormatCSV;
					}
				}

				if ($strFunction == 'import_images') 
				{
					$arrResult = importUploadedImages($objConn_a, TEMP_PENDING_PATH . $strFilename, $strFilename, $strOriginalFilename, $strClientID_a);
				}
				else if ($strFunction == 'import_generic') 
				{
					$arrResult = importUploadedGeneric($objConn_a, TEMP_PENDING_PATH . $strFilename, $strFilename, $strOriginalFilename, $strClientID_a);

				} 

				if ($arrResult["result"] == STAT_COMPLETED) 
				{
					// delete uploaded file if there was no processing error
					unlink(TEMP_PENDING_PATH . $strFilename);
					unlink(TEMP_PENDING_PATH . $strFilename . '.json');
				}
			}

            dbClose($objConnSystemTemp);
        }
    }

    return $arrResult;
}
