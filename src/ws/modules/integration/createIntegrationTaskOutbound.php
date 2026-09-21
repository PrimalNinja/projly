<?php
function createIntegrationTaskOutbound($objConn_a, $strClientID_a, $strCode_a, $strDescription_a, $strStatus_a, $strIsProcessed_a, $strOutboundGUID_a
,  $strIntegrationOutboundID_a
, $strIntegrationOutboundDescription_a, $strIntegrationOutboundTypeID_a, $strIntegrationOutboundTypeDescription_a, $strServerID_a, $strServerDescription_a, $strParameters_a)
{
   $strTableNameIntegrationTaskOutbound = getTableNameEntity("integrationtaskoutbound", false);

   // these are required fields
   $strLogin = $_SESSION['server_loggedin_user'];
   
   $strClientID = $strClientID_a;    
   $strCode = $strCode_a;
   $strDescription = $strDescription_a;
   $strStatus = $strStatus_a;
   $strIsProcessed = $strIsProcessed_a;
   $strOutboundGUID = $strOutboundGUID_a;
   $strIntegrationOutboundID = $strIntegrationOutboundID_a;
   $strIntegrationOutboundDescription= $strIntegrationOutboundDescription_a;
   $strIntegrationOutboundTypeID = $strIntegrationOutboundTypeID_a;
   $strIntegrationOutboundTypeDescription = $strIntegrationOutboundTypeDescription_a;
   $strServerID = $strServerID_a;
   $strServerDescription= $strServerDescription_a;
   $strParameters = $strParameters_a;

   //fetch plugin info
   $strIntegrationOutboundPluginID = "";
   $strIntegrationOutboundPluginDescription= "";

   $strTableNameIntegrationOutboundType = getTableNameEntity("integrationoutboundtype", false);
   $strTableNameIntegrationOutboundPlugin = getTableNameEntity("integrationoutboundplugin", false);

   $strSQL = "select id, gdaa1313b_b447_4e21_9dd6_7984dd3b554c_code code, gdaa1313b_b447_4e21_9dd6_7984dd3b554c_description description 
              from ~TABLENAMEINTEGRATIONOUTBOUNDPLUGIN~ where id in  
              ( select integrationoutboundplugin_id id from ~TABLEINTEGRATIONOUTBOUNDTYPE~ where id = ~INTEGRATIONOUTBOUNDTYPEID~ )";

   $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUNDPLUGIN~', ff($strTableNameIntegrationOutboundPlugin), $strSQL);
   $strSQL = str_replace('~TABLEINTEGRATIONOUTBOUNDTYPE~', ff($strTableNameIntegrationOutboundType), $strSQL);
   $strSQL = str_replace('~INTEGRATIONOUTBOUNDTYPEID~', ff($strIntegrationOutboundTypeID), $strSQL);

   
   $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
   if ($arrRow = dbReadRecord($objResult))
   {
      $strIntegrationOutboundPluginID = $arrRow['id'];
      $strIntegrationOutboundPluginDescription = $arrRow['description'];
   }

   // these entity ID's are required and cannot be empty
   $strEntityID = getEntityID($objConn_a, "systemform");
   $strDataEntityID = getEntityID($objConn_a, "integrationtaskoutbound");

   // every entity table needs the jsondata. when you create a record. you need to get the template or the structure of the jsondata
   $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "INTEGRATIONTASKOUTBOUND");

   // populating jsondata
   $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', "CODE", $strCode);
   $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', "DESCRIPTION", $strDescription);
   $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', "OUTBOUNDGUID", $strOutboundGUID);
   $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', "PARAMETERS", $strParameters);
   $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', "INTEGRATIONOUTBOUND", $strIntegrationOutboundID, $strIntegrationOutboundDescription);
   $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', "INTEGRATIONOUTBOUNDPLUGIN", $strIntegrationOutboundPluginID, $strIntegrationOutboundPluginDescription);
   $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', "INTEGRATIONOUTBOUNDTYPE", $strIntegrationOutboundTypeID, $strIntegrationOutboundTypeDescription);
   $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g0db1c356-65dc-4b1b-8fb9-ebb5fc3df916', "SERVER", $strServerID, $strServerDescription);

   // encode the array jsondata for inserting to table
   $strJSONData = json_encode($arrJSONData);
   
   dbBeginTrans($objConn_a, __FUNCTION__);

   // this is the basic format for inserting entity table record. you can copy/paste this section whenever you insert entity table record
   $strSQL = "insert into ~TABLENAMEINTEGRATIONTASKOUTBOUND~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, is_processed, status) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', '~ISPROCESSED~', '~STATUS~')";
   $strSQL = str_replace('~TABLENAMEINTEGRATIONTASKOUTBOUND~', ff($strTableNameIntegrationTaskOutbound), $strSQL);
   $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
   $strSQL = str_replace('~CODE~', $strCode, $strSQL);
   $strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
   $strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
   $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
   $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
   $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
   $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
   $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);

   // custom fields
   $strSQL = str_replace('~ISPROCESSED~', $strIsProcessed, $strSQL);
   $strSQL = str_replace('~STATUS~', $strStatus, $strSQL);

   dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

   $strIntegrationTaskOutboundID = dbLastInsertID($objConn_a);
   
   // call this after inserting. this function takes care of populating exposed fields (eg. gd554dd80_b3d7_4406_b011_168477f243d9_code)
   exposeEntityData($objConn_a, 'SYSTEMFORM', 'INTEGRATIONTASKOUTBOUND', $strIntegrationTaskOutboundID, $strJSONData);

   dbEndTrans($objConn_a, __FUNCTION__);
   
   return $strIntegrationTaskOutboundID;
}
