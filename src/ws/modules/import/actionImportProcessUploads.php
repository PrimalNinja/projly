<?php
// procses uploads
function actionImportProcessUploads($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$arrResult = array(); //array("result"=> STAT_ERROR, "error"=>"Upload Processing Error", "tagtype"=>"", "tag"=> "");
    $blnResult = true;
    $strResult = "";

	$blnNoBatch = false;

    if (dependencies('batch/batchJobAdd,process/processUploads')) 
	{
        // permission check
        if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) { return false; }

        // parameters
        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strUserID = $_SESSION['server_loggedin_userid'];

        //dbBeginTrans($objConn_a, __FUNCTION__);

        $arrUploads = readDirectory(TEMP_UPLOAD_PATH);

        // for each .json file in temp/uploads
        for ($intI = 0; $intI < count($arrUploads); $intI++) 
		{
            $strFilenameJSON = $arrUploads[$intI];
            if (strRight($strFilenameJSON, 5) == '.json') 
			{
                // read the json file
                $strJSON = file_get_contents(TEMP_UPLOAD_PATH . $strFilenameJSON);
                $arrJSON = json_decode($strJSON, true);
                if ($arrJSON['securitytoken'] == $strSecurityToken_a) 
				{
                    // if securitytoken is mine
					$strFlags = $arrJSON['flags'];
					$arrFlags = explode(" ", $strFlags);
					$blnNoBatch = array_search("nobatch", $arrFlags) !== false;

                    // start trans
                    dbBeginTrans($objConn_a, __FUNCTION__);

                    // move file and .json file to pending
                    $strFilenameData = $arrJSON['filename'];
                    if (file_exists(TEMP_UPLOAD_PATH . $strFilenameData)) 
					{
                        if (copy(TEMP_UPLOAD_PATH . $strFilenameData, TEMP_PENDING_PATH . $strFilenameData)) 
						{
                            unlink(TEMP_UPLOAD_PATH . $strFilenameData);
                        }
                    }

                    if (file_exists(TEMP_UPLOAD_PATH . $strFilenameJSON)) 
					{
                        if (copy(TEMP_UPLOAD_PATH . $strFilenameJSON, TEMP_PENDING_PATH . $strFilenameJSON)) 
						{
                            unlink(TEMP_UPLOAD_PATH . $strFilenameJSON);
                        }
                    }

                    // add a batchjob with .json file as metadata
                    $strCode = '';
                    $strDescription = '' . $arrJSON['original_filename'];
					if ($blnNoBatch)
					{
						$arrResultTemp = processUploads($objConn_a, $strClientID, $strJSON, $blnNoBatch);
						$arrResult[] = encodeTagIDs($arrResultTemp);
					}
					else
					{
						$arrResultTemp = batchJobAdd($objConn_a, $strClientID, $strUserID, '3', $strCode, $strDescription, 'PROCESS_UPLOADS', $strJSON, false, false);
						$arrResult[] = encodeTagIDs($arrResultTemp);
					}

                    // commit trans
                    $blnX = dbEndTrans($objConn_a, __FUNCTION__);
					if (!$blnX)
					{
						$blnResult = false;
					}
                }
            }
        }

        //dbEndTrans($objConn_a, __FUNCTION__);

        if ($blnResult) 
		{
			if ($blnNoBatch)
			{
				$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
			}
			else
			{
				$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
			}
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error processing uploads.', array());
        }
    }

    return $strResult;
}
