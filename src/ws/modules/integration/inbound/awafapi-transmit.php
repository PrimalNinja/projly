<?php
function receiveData($objConn_a, $strIntegrationInboundPluginCode_a, $arrParameters_a)
{
    $strTableNameIntegrationInbound = getTableNameEntity("integrationinbound", false);
    $strTableNameIntegrationInboundPlugin = getTableNameEntity("integrationinboundplugin", false);
    $strTableNameIntegrationInboundType = getTableNameEntity("integrationinboundtype", false);
    $strTableNameInboundData = getTableNameEntity("inbounddata", false);
    $strTableNameServer = getTableNameEntity("server", false);    

    $blnResult = false;

    // these are required fields
    $strLogin = $_SESSION['server_loggedin_user'];
    $strGUID = getJSONParameter($arrParameters_a, 'guid'); 
    $strIntegrationTaskData = getJSONParameter($arrParameters_a, 'integrationtaskdata');
    $strEntityDataID = getJSONParameter($arrParameters_a, 'entitydataid');
    $strCode = getJSONParameter($arrParameters_a, 'code');
    $strClientID = getJSONParameter($arrParameters_a, 'client_id');
    $strDescription = getJSONParameter($arrParameters_a, 'description');
    $strInboundData = getJSONParameter($arrParameters_a, 'inbounddata');
            
    if (dependencies('integration/createIntegrationTaskInbound,integration/integrationInboundIPWhiteListed'))
    {
        $strIntegrationInboundID = "";
        $strIntegrationInboundPluginID = "";
        $strIntegrationInboundPluginDescription = "";

        $strSQL = "select id, client_id, g8fbd6661_d8e6_4279_b9a5_5e1042970937_description description, jsondata 
                   from ~TABLENAMEINTEGRATIONINBOUND~ where g8fbd6661_d8e6_4279_b9a5_5e1042970937_inboundguid = '~GUID~'";

        $strSQL = str_replace('~TABLENAMEINTEGRATIONINBOUND~', ff($strTableNameIntegrationInbound), $strSQL);
        $strSQL = str_replace('~GUID~', ff($strGUID), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        if ($arrRow = dbReadRecord($objResult))
        {
            $strInboundGUID = $strGUID;

            $strIntegrationInboundID = $arrRow['id'];
            $strClientID = $arrRow['client_id'];
            $strIntegrationInboundDescription = $arrRow['description'];
            $arrIntegrationInboundJSONData = json_decode($arrRow['jsondata'], true);
            

            $arrField = formFieldGetBySectionCodeFieldCode($arrIntegrationInboundJSONData, 'g8fbd6661-d8e6-4279-b9a5-5e1042970937', 'INTEGRATIONINBOUNDTYPE');
            $strIntegrationInboundTypeID = $arrField['p_value'];
            $strIntegrationInboundTypeDescription = $arrField['p_valuedescription'];

            $arrField = formFieldGetBySectionCodeFieldCode($arrIntegrationInboundJSONData, 'g8fbd6661-d8e6-4279-b9a5-5e1042970937', 'SERVER');
            $strServerID = $arrField['p_value'];
            $strServerDescription = $arrField['p_valuedescription'];

            if (integrationInboundIPWhiteListed($objConn_a, $strIntegrationInboundID, $_SERVER['REMOTE_ADDR']))
            {
                //PLUGIN DATA
                $strSQL = "select id, gcecf2cdd_fc49_47df_b9f9_02c951a4d41b_description description from ~TABLENAMEINTEGRATIONINBOUNDPLUGIN~ where gcecf2cdd_fc49_47df_b9f9_02c951a4d41b_code = '~INTEGRATIONINBOUNDPLUGINCODE~'";
                $strSQL = str_replace('~TABLENAMEINTEGRATIONINBOUNDPLUGIN~', ff($strTableNameIntegrationInboundPlugin), $strSQL);
                $strSQL = str_replace('~INTEGRATIONINBOUNDPLUGINCODE~', ff($strIntegrationInboundPluginCode_a), $strSQL);
                $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

                if ($arrRow = dbReadRecord($objResult))
                { 
                    $strIntegrationInboundPluginID = $arrRow['id'];
                    $strIntegrationInboundPluginDescription = $arrRow['description'];
                }        
                dbCloseRecordset($objResult);

                $strIntegrationTaskID = createIntegrationTaskInbound($objConn_a, $strClientID, "RECEIVESTEP2", "Receive Data Step 2", "PENDING", "N", $strInboundGUID, 
                                                                     $strIntegrationInboundID, $strIntegrationInboundDescription, $strIntegrationInboundTypeID, 
                                                                     $strIntegrationInboundTypeDescription,$strServerID, $strServerDescription, $strIntegrationTaskData);

                // these entity ID's are required and cannot be empty
                $strEntityID = getEntityID($objConn_a, "systemform");
                $strDataEntityID = getEntityID($objConn_a, "inbounddata");
                $strJSONData = $strInboundData;

                dbBeginTrans($objConn_a, __FUNCTION__);

                // this is the basic format for inserting entity table record. you can copy/paste this section whenever you insert entity table record
                $strSQL = "insert into ~TABLENAMEINBOUNDDATA~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, entitydata_id, integrationtaskinbound_id) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', ~ENTITYDATAID~, ~INTEGRATIONTASKID~)";
                $strSQL = str_replace('~TABLENAMEINBOUNDDATA~', ff($strTableNameInboundData), $strSQL);
                $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
                $strSQL = str_replace('~CODE~', $strCode, $strSQL);
                $strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
                $strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
                $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
                $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
                $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
                $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
                $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
                $strSQL = str_replace('~ENTITYDATAID~', $strEntityDataID, $strSQL);
                $strSQL = str_replace('~INTEGRATIONTASKID~', $strIntegrationTaskID, $strSQL);
                dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

                $strInboundDataID = dbLastInsertID($objConn_a);
                $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
            }              

        }
    }

    return $blnResult;
}

function receiveStep2($objConn_a, $strIntegrationTaskID_a)
{
    $strTableNameIntegrationTaskInbound = getTableNameEntity("integrationtaskinbound", false);
    $strTableNameInboundData = getTableNameEntity("inbounddata", false);

    $blnResult = false;

    dbBeginTrans($objConn_a, __FUNCTION__);

    $strSQL = "select ga360982d_60fb_4feb_bae2_35c27318493d_parameters parameters, jsondata from ~TABLENAMEINTEGRATIONTASKINBOUND~ where id = ~INTEGRATIONTASKID~";
    $strSQL = str_replace('~TABLENAMEINTEGRATIONTASKINBOUND~', ff($strTableNameIntegrationTaskInbound), $strSQL);
    $strSQL = str_replace('~INTEGRATIONTASKID~', ff($strIntegrationTaskID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        
    if ($arrRow = dbReadRecord($objResult))
    {   
        $strParameters = $arrRow['parameters'];
        $arrParameters = json_decode($strParameters, true);

        $arrBehaviour = [];
        $strEntityName = "";
        $arrParameters = $arrParameters[0]; // the current structure of the json is in array and not an object that is why i put an index of zero
        
        if (isset($arrParameters['destination']))
        {
            $strEntityName = $arrParameters['destination'];
        }

        if (isset($arrParameters['behaviour']))
        {
            $arrBehaviour = $arrParameters['behaviour'];
        }

        if (isset($arrParameters['join']))
        {
            $strEntityJoinField = $arrParameters['join'];
            $strInboundDataJoinField = $arrParameters['join'];
        }
        else
        {
            $strEntityJoinField = 'id';
            $strInboundDataJoinField = 'entitydata_id';
        }

        if (in_array("UPDATE", $arrBehaviour))
        {
            if (strlen($strEntityName) > 0)
            {
                $strTableNameEntity = getTableNameEntity(strtolower($strEntityName), false);

                $strSQL = "update ~TABLENAMEENTITY~ e, ~TABLENAMEINBOUNDDATA~ d set e.code=d.code, e.description=d.description, e.jsondata=d.jsondata where e.~ENTITYJOINFIELD~=d.~INBOUNDDATAJOINFIELD~ and d.integrationtaskinbound_id = ~INTEGRATIONTASKID~";
                $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
                $strSQL = str_replace('~TABLENAMEINBOUNDDATA~', ff($strTableNameInboundData), $strSQL);
                $strSQL = str_replace('~INTEGRATIONTASKID~', ff($strIntegrationTaskID_a), $strSQL);
                $strSQL = str_replace('~ENTITYJOINFIELD~', ff($strEntityJoinField), $strSQL);
                $strSQL = str_replace('~INBOUNDDATAJOINFIELD~', ff($strInboundDataJoinField), $strSQL);
                dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
            }
        }

        if (in_array("ADD", $arrBehaviour))
        {
            if (strlen($strEntityName) > 0)
            {
                $strTableNameEntity = getTableNameEntity(strtolower($strEntityName), false);

                // these entity ID's are required and cannot be empty
                $strEntityID = getEntityID($objConn_a, "systemform");
                $strDataEntityID = getEntityID($objConn_a, strtolower($strEntityName));

                $strSQL = "insert into ~TABLENAMEENTITY~ 
                           (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime)             
                           select client_id, ~ENTITYID~, ~DATAENTITYID~, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime 
                           from ~TABLENAMEINBOUNDDATA~ 
                           where integrationtaskinbound_id = ~INTEGRATIONTASKID~ 
                           and ~INBOUNDDATAJOINFIELD~ not in (select ~ENTITYJOINFIELD~ from ~TABLENAMEENTITY~)";

                $strSQL = str_replace('~TABLENAMEINBOUNDDATA~', ff($strTableNameInboundData), $strSQL);
                $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
                $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
                $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
                $strSQL = str_replace('~INTEGRATIONTASKID~', $strIntegrationTaskID_a, $strSQL);
                $strSQL = str_replace('~ENTITYJOINFIELD~', ff($strEntityJoinField), $strSQL);
                $strSQL = str_replace('~INBOUNDDATAJOINFIELD~', ff($strInboundDataJoinField), $strSQL);
                dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
            }
        }
    }
    
    $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
    return $blnResult;
}