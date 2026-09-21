<?php
function uploadProjlyWorkingForm($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strClientID_a, $strUserID_a, $objWorkingForm_a)
{
    $strTableNameSystemForm = getTableNameEntity("systemform", false);
    $strTableNamePhoneRegistration = getTableNameEntity("phoneregistration", false);

    if (dependencies('security/userRegister'))
    {
        // initialisations
        $strLogin = $_SESSION['server_loggedin_user'];

        $strClientID = $strClientID_a;
        $strUserID = $strUserID_a;
        $objWorkingForm = $objWorkingForm_a;

        $strGUID = $objWorkingForm['guid'];
        $arrSections = $objWorkingForm['sections'];
        $arrImages = $objWorkingForm['images'];
        $strCreateDateTime = $objWorkingForm['createdatetime'];
        $strModifyDateTime = $objWorkingForm['modifydatetime'];

        $strEntityCode = formValueGetBySectionTypeFieldCode($arrSections, 'FORMHEADER', 'CODE');
        $strDescription = formValueGetBySectionTypeFieldCode($arrSections, 'FORMHEADER', 'DESCRIPTION');

        if ($strEntityCode === 'PHONEREGISTRATION')
        {

            $strFullName = formValueGetBySectionCodeFieldCode($arrSections, 'ge0572f30-2d40-4a7a-a0f4-299585085c48', 'FULLNAME');
            $strEmailAddress = formValueGetBySectionCodeFieldCode($arrSections, 'ge0572f30-2d40-4a7a-a0f4-299585085c48', 'EMAILADDRESS');
            $strPassword = formValueGetBySectionCodeFieldCode($arrSections, 'ge0572f30-2d40-4a7a-a0f4-299585085c48', 'PASSWORD');
            $strPhonenumber = formValueGetBySectionCodeFieldCode($arrSections, 'ge0572f30-2d40-4a7a-a0f4-299585085c48', 'PHONENUMBER');

            $strAccountName = $strEmailAddress;
            $strClientCode = $strEmailAddress;
            
            $strUserAgent = ''; //getJSONParameter($arrParameters_a, 'useragent');
            $strRegistrationTypeCode = 'OTH'; //employee
            $strIPAddress = $_SERVER["REMOTE_ADDR"];

            // create registration data
            $arrRegistrationData = [
                [
                    "name" => "accountname",
                    "value" => $strAccountName
                ],
                [
                    "name" => "fullname",
                    "value" => $strFullName
                ],
                [
                    "name" => "contactemailaddress",
                    "value" => $strEmailAddress
                ],
                [
                    "name" => "contactphonenumber",
                    "value" => $strPhonenumber
                ]
            ];

            $strRegistrationData = json_encode($arrRegistrationData);

            $strResult = userRegister($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strRegistrationTypeCode, $strClientCode, $strEmailAddress, $strPassword, $strUserAgent, $strIPAddress, $strRegistrationData);

            if (strlen($strResult) === 0)
            {
                // get the entity ids
                $strEntityID = getEntityID($objConn_a, "systemform");
                $strDataEntityID = getEntityID($objConn_a, "phoneregistration");
                
                $strJSONData = json_encode($arrSections);

                $strSQL = "insert into ~TABLENAMEPHONEREGISTRATION~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime)
                            values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~ENTITYCODE~', '~DESCRIPTION~', 'Y', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~')";
                $strSQL = str_replace('~TABLENAMEPHONEREGISTRATION~', ff($strTableNamePhoneRegistration), $strSQL);
                $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
                $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
                $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
                $strSQL = str_replace('~ENTITYCODE~', ffeu($strEntityCode), $strSQL);
                $strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
                $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
                $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
                $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);

                dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                $strPhoneRegistrationID = dbLastInsertID($objConn_a);

                exposeEntityData($objConn_a, 'SYSTEMFORM', 'PHONEREGISTRATION', $strPhoneRegistrationID, $strJSONData);        
            }
            
        }
        else
        {
            $strTableNameEntity = getTableNameEntity(strtolower($strEntityCode), false);

            // get the entity ids
            $strEntityID = getEntityID($objConn_a, "systemform");
            $strDataEntityID = getEntityID($objConn_a, $strEntityCode);
            
            $strJSONData = json_encode($arrSections);

            $strSQL = "insert into ~TABLENAMEENTITY~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime)
                        values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~ENTITYCODE~', '~DESCRIPTION~', 'Y', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~')";
            $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
            $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
            $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
            $strSQL = str_replace('~ENTITYCODE~', ffeu($strEntityCode), $strSQL);
            $strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
            $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
            $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
            $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);

            dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
            $strID = dbLastInsertID($objConn_a);

            exposeEntityData($objConn_a, 'SYSTEMFORM', $strEntityCode, $strID, $strJSONData); 
        }
    }
}