<?php
 
// $strFormEntityID_a = SYSTEMMODULE
// $strRelativeID_a = system
function rosterTemplate_ProjectParticipantAddTo($objConn_a, $strClientID_a, $strFormEntityIDList_a, $strRelativeID_a, $strRelationship_a) 
{     
    $strTableNameProjectParticipant = getTableNameEntity("projectparticipant", false);
    $strTableNameRosterTemplateProjectParticipant = getTableNameEntity("rostertemplate_projectparticipant", false);
	
    $strResult = '';
	
    $strLogin = $_SESSION['server_loggedin_user'];
	
	// validations
	$strSQL = "select count(*) returnvalue from ~TABLENAMEROSTERTEMPLATEPROJECTPARTICIPANT~ where client_id = ~CLIENTID~ and projectparticipant_id in (~PROJECTPARTICIPANTIDLIST~) and rostertemplate_id = ~ROSTERTEMPLATEID~";
	$strSQL = str_replace("~TABLENAMEROSTERTEMPLATEPROJECTPARTICIPANT~", ff($strTableNameRosterTemplateProjectParticipant), $strSQL);
	$strSQL = str_replace("~CLIENTID~", ff($strClientID_a), $strSQL);
	$strSQL = str_replace("~PROJECTPARTICIPANTIDLIST~", $strFormEntityIDList_a, $strSQL);
	$strSQL = str_replace("~ROSTERTEMPLATEID~", ff($strRelativeID_a), $strSQL);
	$intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__), 10);

	if ($intCount > 0)
	{
		dbRaiseCustomError($objConn_a, "A duplicate project participant is found.");
	}
	else
	{
	
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		$strSQL = "
	insert into ~TABLENAMEROSTERTEMPLATEPROJECTPARTICIPANT~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, projectparticipant_id, rostertemplate_id)
	select ~CLIENTID~, entity_id, dataentity_id, code, description, is_enabled, ~CLIENTID~, null, '~MODIFYUSER~', '~MODIFYDATETIME~', id, ~RELATIVEID~ from ~TABLENAMEPROJECTPARTICIPANT~ where id in (~PROJECTPARTICIPANTIDLIST~)
	";
		
		$strSQL = str_replace('~TABLENAMEPROJECTPARTICIPANT~', ff($strTableNameProjectParticipant), $strSQL);
		$strSQL = str_replace('~TABLENAMEROSTERTEMPLATEPROJECTPARTICIPANT~', ff($strTableNameRosterTemplateProjectParticipant), $strSQL);
		$strSQL = str_replace('~PROJECTPARTICIPANTIDLIST~', $strFormEntityIDList_a, $strSQL);
		$strSQL = str_replace('~RELATIVEID~', ff($strRelativeID_a), $strSQL);
		$strSQL = str_replace("~CLIENTID~", ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);

		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strResult = dbLastInsertID($objConn_a);  
		
		dbEndTrans($objConn_a, __FUNCTION__);
	}

    return $strResult;
    
}
