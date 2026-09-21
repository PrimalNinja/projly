<?php
function createIntegrationTaskInbound($objConn_a, $strClientID_a, $strCode_a, $strDescription_a, $strStatus_a, $strIsProcessed_a, $strInboundGUID_a,
                                      $strIntegrationInboundID_a, $strIntegrationInboundDescription_a, $strIntegrationInboundTypeID_a, 
                                      $strIntegrationInboundTypeDescription_a, $strServerID_a, $strServerDescription_a, $strParameters_a)
{
   $strTableNameIntegrationTaskInbound = getTableNameEntity("integrationtaskinbound", false);

   // these are required fields
   $strLogin = $_SESSION['server_loggedin_user'];
   
   $strClientID = $strClientID_a;    
   $strCode = $strCode_a;
   $strDescription = $strDescription_a;
   $strStatus = $strStatus_a;
   $strIsProcessed = $strIsProcessed_a;
   $strInboundGUID = $strInboundGUID_a;
   $strIntegrationOutboundID = $strIntegrationInboundID_a;
   $strIntegrationOutboundDescription= $strIntegrationInboundDescription_a;
   $strIntegrationInboundTypeID = $strIntegrationInboundTypeID_a;
   $strIntegrationOutboundTypeDescription = $strIntegrationInboundTypeDescription_a;
   $strServerID = $strServerID_a;
   $strServerDescription= $strServerDescription_a;
   $strParameters = $strParameters_a;

   //fetch plugin info
   $strIntegrationInboundPluginID = "";
   $strIntegrationInboundPluginDescription= "";

   $strTableNameIntegrationInboundType = getTableNameEntity("integrationinboundtype", false);
   $strTableNameIntegrationInboundPlugin = getTableNameEntity("integrationinboundplugin", false);

   $strSQL = "select id, gcecf2cdd_fc49_47df_b9f9_02c951a4d41b_code code, gcecf2cdd_fc49_47df_b9f9_02c951a4d41b_description description 
              from ~TABLENAMEINTEGRATIONINBOUNDPLUGIN~ where id in  
              ( select integrationinboundplugin_id id from ~TABLEINTEGRATIONINBOUNDTYPE~ where id = ~INTEGRATIONINBOUNDTYPEID~ )";

   $strSQL = str_replace('~TABLENAMEINTEGRATIONINBOUNDPLUGIN~', ff($strTableNameIntegrationInboundPlugin), $strSQL);
   $strSQL = str_replace('~TABLEINTEGRATIONINBOUNDTYPE~', ff($strTableNameIntegrationInboundType), $strSQL);
   $strSQL = str_replace('~INTEGRATIONINBOUNDTYPEID~', ff($strIntegrationInboundTypeID), $strSQL);

   $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
   if ($arrRow = dbReadRecord($objResult))
   {
      $strIntegrationInboundPluginID = $arrRow['id'];
      $strIntegrationInboundPluginDescription = $arrRow['description'];
   }

   // these entity ID's are required and cannot be empty
   $strEntityID = getEntityID($objConn_a, "systemform");
   $strDataEntityID = getEntityID($objConn_a, "integrationtaskinbound");

   // every entity table needs the jsondata. when you create a record. you need to get the template or the structure of the jsondata
   $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "INTEGRATIONTASKINBOUND");

   // populating jsondata
   $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ga360982d-60fb-4feb-bae2-35c27318493d', "CODE", $strCode);
   $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ga360982d-60fb-4feb-bae2-35c27318493d', "DESCRIPTION", $strDescription);
   $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ga360982d-60fb-4feb-bae2-35c27318493d', "INBOUNDGUID", $strInboundGUID);
   $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ga360982d-60fb-4feb-bae2-35c27318493d', "PARAMETERS", $strParameters);
   $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'ga360982d-60fb-4feb-bae2-35c27318493d', "INTEGRATIONINBOUND", $strIntegrationInboundID, $strIntegrationInboundDescription);
   $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'ga360982d-60fb-4feb-bae2-35c27318493d', "INTEGRATIONINBOUNDPLUGIN", $strIntegrationInboundPluginID, $strIntegrationInboundPluginDescription);
   $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'ga360982d-60fb-4feb-bae2-35c27318493d', "INTEGRATIONINBOUNDTYPE", $strIntegrationInboundTypeID, $strIntegrationInboundTypeDescription);
   $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'ga360982d-60fb-4feb-bae2-35c27318493d', "SERVER", $strServerID, $strServerDescription);

   // encode the array jsondata for inserting to table
   $strJSONData = json_encode($arrJSONData);
   
   dbBeginTrans($objConn_a, __FUNCTION__);

   // this is the basic format for inserting entity table record. you can copy/paste this section whenever you insert entity table record
   $strSQL = "insert into ~TABLENAMEINTEGRATIONTASKINBOUND~ 
              (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, is_processed, status) 
              values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', '~ISPROCESSED~', '~STATUS~')";

   $strSQL = str_replace('~TABLENAMEINTEGRATIONTASKINBOUND~', ff($strTableNameIntegrationTaskInbound), $strSQL);
   $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
   $strSQL = str_replace('~CODE~', $strCode, $strSQL);
   $strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
   $strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
   $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
   $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
   $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
   $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
   $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
   $strSQL = str_replace('~ISPROCESSED~', $strIsProcessed, $strSQL);
   $strSQL = str_replace('~STATUS~', $strStatus, $strSQL);
   
   dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
   $strIntegrationTaskInboundID = dbLastInsertID($objConn_a);

   exposeEntityData($objConn_a, 'SYSTEMFORM', 'INTEGRATIONTASKINBOUND', $strIntegrationTaskInboundID, $strJSONData);

   dbEndTrans($objConn_a, __FUNCTION__);
   return $strIntegrationTaskInboundID;
}
