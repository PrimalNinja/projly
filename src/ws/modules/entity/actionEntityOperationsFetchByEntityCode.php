<?php

function actionEntityOperationsFetchByEntityCode($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	$strTableNameEntityOperation = getTableNameEntity('entityoperation', false);
	
    $arrResult = array();
    
	// permission check
	if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
	
    $strEntityCode = getJSONParameter($arrParameters_a, 'entitycode');
    
    // fetch entity first
    
    // fetch
    $strSQL = "select op.code, op.description, op.ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_command command, op.ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_parameters parameters, op.ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_flags flags, op.ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_prompt prompt, op.ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_allowmultiple allow_multiple, op.ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_requiresselection requires_selection, op.ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_isinternal is_internal, op.ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_iscustom is_custom, op.is_enabled from ~TABLENAMEENTITYOPERATION~ op, ~TABLENAMEENTITY~ e where op.relatedentity_id = e.id and e.code = '~ENTITYCODE~' and op.is_enabled='Y' order by cast(op.ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_displayorder as unsigned), op.description";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~TABLENAMEENTITYOPERATION~', ff($strTableNameEntityOperation), $strSQL);
    $strSQL = str_replace('~ENTITYCODE~', ffeu($strEntityCode), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    while ($arrRow = dbReadRecord($objResult)) 
	{
        
        $strPrompt = $arrRow['prompt'];
        
        if (is_null($strPrompt)) 
		{ 
           $strPrompt = ''; 
        }
        
        $arrResult[] = array(
            "code" => $arrRow['code'],
            "description" => $arrRow['description'],
            "command" => $arrRow['command'],
            "parameters" => $arrRow['parameters'],
            "flags" => $arrRow['flags'],
            "prompt" => $strPrompt,
            "allowmultiple" => $arrRow['allow_multiple'],
            "requiresselection" => $arrRow['requires_selection'],
            "isinternal" => $arrRow['is_internal'],
            "iscustom" => $arrRow['is_custom'],
            "isenabled" => $arrRow['is_enabled']
        );
    }
    
    dbCloseRecordset($objResult);
    
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
