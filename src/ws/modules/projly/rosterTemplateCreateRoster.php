<?php
function rosterTemplateCreateRoster($objConn_a, $strClientID_a, $strRosterTemplateID_a)
{
    $strTableNameProjectParticipant = getTableNameEntity("projectparticipant", false);
    $strTableNameRoster = getTableNameEntity("roster", false);
    $strTableNameRosterTemplate = getTableNameEntity("rostertemplate", false);
    $strTableNameRosterTemplateProjectParticipant = getTableNameEntity("rostertemplate_projectparticipant", false);
    $strTableNameRosterTemplateDetail = getTableNameEntity("rostertemplate_detail", false);
	
    $strResult = '';
	    
    $strLogin = $_SESSION['server_loggedin_user'];
    $strClientID = $strClientID_a;
    $strEntityID = getEntityID($objConn_a, "systemform");
    
    dbBeginTrans($objConn_a, __FUNCTION__);

    $strSQL = "select g796a111e_0b17_4965_8683_af5528feab09_effectivedate returnvalue from ~TABLENAMEROSTERTEMPLATE~ where id=~ROSTERTEMPLATEID~";
    $strSQL = str_replace("~TABLENAMEROSTERTEMPLATE~", ff($strTableNameRosterTemplate), $strSQL);
	$strSQL = str_replace("~ROSTERTEMPLATEID~", ff($strRosterTemplateID_a), $strSQL);
    $strEffectiveDate = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    // prepare the dates of the week
    
    $intOffsetDay = 1 - date('w'); // getting the monday offset
    $strStartDate = date("Y-m-d", strtotime("{$intOffsetDay} days"));
    
    if (strlen($strEffectiveDate) > 0 && $strEffectiveDate > $strStartDate)
    {
        $strStartDate = $strEffectiveDate;
    }
           
    $arrWeekDays = [
        'MON' => $strStartDate,
        'TUE' => getDateAddDays($strStartDate, 1),
        'WED' => getDateAddDays($strStartDate, 2),
        'THU' => getDateAddDays($strStartDate, 3),
        'FRI' => getDateAddDays($strStartDate, 4),
        'SAT' => getDateAddDays($strStartDate, 5),
        'SUN' => getDateAddDays($strStartDate, 6)
    ];

    $strSQL = "select id, jsondata from ~TABLENAMEROSTERTEMPLATEDETAIL~ where rostertemplate_id = ~ROSTERTEMPLATEID~";
    $strSQL = str_replace("~TABLENAMEROSTERTEMPLATEDETAIL~", ff($strTableNameRosterTemplateDetail), $strSQL);
	$strSQL = str_replace("~ROSTERTEMPLATEID~", ff($strRosterTemplateID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    while ($arrRow = dbReadRecord($objResult))
    {
        $arrJSONData = json_decode($arrRow['jsondata'], true);

        //$arrRosterDayField = formFieldGetBySectionCodeFieldCode($arrJSONData, 'g842ac7fc-e994-459f-b510-48b8f8e74239', "ROSTERDAY");
        $strRosterDay = formValueGetBySectionCodeFieldCode($arrJSONData, 'g842ac7fc-e994-459f-b510-48b8f8e74239', "ROSTERDAY");
        $strPlannedStartTime = formValueGetBySectionCodeFieldCode($arrJSONData, 'g842ac7fc-e994-459f-b510-48b8f8e74239', "PLANNEDSTARTTIME");
        $strPlannedEndTime = formValueGetBySectionCodeFieldCode($arrJSONData, 'g842ac7fc-e994-459f-b510-48b8f8e74239', "PLANNEDENDTIME");
        $strPlannedHours = formValueGetBySectionCodeFieldCode($arrJSONData, 'g842ac7fc-e994-459f-b510-48b8f8e74239', "PLANNEDTOTALHOURS");

        $strDate = $arrWeekDays[$strRosterDay];
        
        $strSQL = "select p.id, p.description from ~TABLENAMEPROJECTPARTICIPANT~ p, ~TABLENAMEROSTERTEMPLATEPROJECTPARTICIPANT~ rp where p.id=rp.projectparticipant_id and rp.rostertemplate_id=~ROSTERTEMPLATEID~";
        $strSQL = str_replace("~TABLENAMEPROJECTPARTICIPANT~", ff($strTableNameProjectParticipant), $strSQL);
        $strSQL = str_replace("~TABLENAMEROSTERTEMPLATEPROJECTPARTICIPANT~", ff($strTableNameRosterTemplateProjectParticipant), $strSQL);
        $strSQL = str_replace("~ROSTERTEMPLATEID~", ff($strRosterTemplateID_a), $strSQL);
        $objResultParticipant = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        while ($arrRowParticipant = dbReadRecord($objResultParticipant))
        {
            $strProjectParticipantID = $arrRowParticipant['id'];
            $strProjectParticipantDescription = $arrRowParticipant['description'];

            $strSQL = "select count(*) returnvalue from ~TABLENAMEROSTER~ where client_id=~CLIENTID~ and projectparticipant_id=~PROJECTPARTICIPANTID~ and g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_date = '~DATE~'";
            $strSQL = str_replace("~TABLENAMEROSTER~", ff($strTableNameRoster), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
            $strSQL = str_replace("~PROJECTPARTICIPANTID~", ff($strProjectParticipantID), $strSQL);
            $strSQL = str_replace("~DATE~", ff($strDate), $strSQL);
            $strRosterCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            if (intval($strRosterCount) == 0) // it means roster does not exists yet
            {
                $arrJSONDataRoster = formTemplateGetFromDBByEntityCode($objConn_a, "ROSTER");

                $strRosterCode = "ROSTER";
                $strRosterDescription = "Roster";
                $arrJSONDataRoster = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONDataRoster, 'g7b9e72db-4138-48fc-9ef1-24595d3e3fb9', "PROJECTPARTICIPANT", $strProjectParticipantID, $strProjectParticipantDescription);				
                $arrJSONDataRoster = formValueUpdateBySectionCodeFieldCode($arrJSONDataRoster, 'g7b9e72db-4138-48fc-9ef1-24595d3e3fb9', "DATE", $strDate);				
                $arrJSONDataRoster = formValueUpdateBySectionCodeFieldCode($arrJSONDataRoster, 'g7b9e72db-4138-48fc-9ef1-24595d3e3fb9', "PLANNEDSTARTTIME", $strPlannedStartTime);				
                $arrJSONDataRoster = formValueUpdateBySectionCodeFieldCode($arrJSONDataRoster, 'g7b9e72db-4138-48fc-9ef1-24595d3e3fb9', "PLANNEDENDTIME", $strPlannedEndTime);				
                $arrJSONDataRoster = formValueUpdateBySectionCodeFieldCode($arrJSONDataRoster, 'g7b9e72db-4138-48fc-9ef1-24595d3e3fb9', "PLANNEDTOTALHOURS", $strPlannedHours);	
                
                $strSONDataRoster = json_encode($arrJSONDataRoster);
                    
                $strSQL = "insert into ~TABLENAMEROSTER~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~')";
                $strSQL = str_replace('~TABLENAMEROSTER~', ff($strTableNameRoster), $strSQL);
                $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
                $strSQL = str_replace('~CODE~', $strRosterCode, $strSQL);
                $strSQL = str_replace('~DESCRIPTION~', $strRosterDescription, $strSQL);
                $strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
                $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
                $strSQL = str_replace('~DATAENTITYID~', ff(getEntityID($objConn_a, "roster")), $strSQL);
                $strSQL = str_replace('~JSONDATA~', ff($strSONDataRoster), $strSQL);
                $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
                $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
                dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                $strRosterID = dbLastInsertID($objConn_a);
                
                exposeEntityData($objConn_a, 'SYSTEMFORM', 'ROSTER', $strRosterID, $strSONDataRoster);
            }
        }

        dbCloseRecordset($objResultParticipant);
    }

    dbCloseRecordset($objResult);

    $blnResult = dbEndTrans($objConn_a, __FUNCTION__);

    //return $strResult;
    return $blnResult;
}