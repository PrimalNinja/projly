<?php
function uploadWorkingForm($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strClientID_a, $strUserID_a,  $objWorkingForm_a)
{
    $blnResult = false;

    if (dependencies('sync/workingFormAdd,process/processImportedImages'))
    {
        $strClientID = $strClientID_a;
        $strUserID = $strUserID_a;
        $objWorkingForm = $objWorkingForm_a;

        $strGUID = $objWorkingForm['guid'];
        $arrSections = $objWorkingForm['sections'];
        $arrImages = $objWorkingForm['images'];
        $strCreateDateTime = $objWorkingForm['createdatetime'];
        $strModifyDateTime = $objWorkingForm['modifydatetime'];

        // does it exist already?
        $strSQL = "select id returnvalue from sync_tblworkingform where client_id = ~CLIENTID~ and user_id = ~USERID~ and guid = '~GUID~'";
        $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
        $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
        $strSQL = str_replace('~GUID~', ff($strGUID), $strSQL);
        $strWorkingFormID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        
        if (strlen($strWorkingFormID) == 0)
        {
            $arrImagesMetaDataJSON = array();
            
            for ($intJ = 0; $intJ < count($arrImages); $intJ++) {
                
                $objImage = $arrImages[$intJ];
                
                $strImagePath = $objImage['imagepath'];
                $strImageName = $strClientID . "_" . $strUserID . "_" . basename($strImagePath);
                //$strImageName = basename($strImagePath);
                $strBase64EncodedImage = $objImage['imageencoded'];
            
                // now decode the image
                //$img = $objPOD_a;
                $success = file_put_contents(TEMP_PENDING_PATH . $strImageName, base64_decode($strBase64EncodedImage));
                
                // next store the image in the repository and get back a document_id
                $arrResult = processImportedImages($objConn_a, TEMP_PENDING_PATH . $strImageName, $strImageName, basename($strImagePath), $strClientID);
                
                if (($arrResult["result"] == STAT_COMPLETED) && ($arrResult["tagtype"] == "documentid"))
                {
                    unlink(TEMP_PENDING_PATH . $strImageName);
                    
                    $strDocumentID = $arrResult["tag"];
                    
                    $arrImagesMetaDataJSON[] = array(
                        "document_id" => $strDocumentID,
                        "imagepath" => $strImagePath
                    );
                }				
            }
            $strDescription = formValueGetBySectionTypeFieldCode($arrSections, 'FORMHEADER', 'DESCRIPTION');
            
            $strID = workingFormAdd($objConn_a, $strClientID, $strUserID, $strGUID, json_encode($arrSections), json_encode($arrImagesMetaDataJSON), "N", $strDescription, $strCreateDateTime, $strModifyDateTime);

            if (strlen($strID) > 0)
            {
                $blnResult = true;
            }
        }         
    }

    return $blnResult;
}