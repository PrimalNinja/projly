<?php

function actionMessageFoldersFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) { 
    
    $strTableNameInternalMessageFolder = getTableNameEntity("internalmessagefolder", false);

    $arrResult = array();
            
    // permission check
    if (!hasPermission($objConn_a, 'VW_INTERNALMESSAGEFOLDER', __FUNCTION__, true)) {return false;}
    
    // fetch
    $strSQL = "select id, code, description from ~TABLENAMEINTERNALMESSAGEFOLDER~ where is_enabled = 'Y' order by cast(gc37b1759_00e2_4ef2_a20e_f8b43637ac05_displayorder as unsigned)";
    $strSQL = str_replace('~TABLENAMEINTERNALMESSAGEFOLDER~', ff($strTableNameInternalMessageFolder), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
                
    while ($arrRow = dbReadRecord($objResult)) {
		$strInbox = 'N';
		$strSystem = 'N';
		$strSent = 'N';
        $strArchive = 'N';
		
		if ($arrRow['code'] == 'INBOX')
		{
			$strInbox = 'Y';
		}
		
		if ($arrRow['code'] == 'SYS')
		{
			$strSystem = 'Y';
		}
		
		if ($arrRow['code'] == 'SENT')
		{
			$strSent = 'Y';
		}
        
        if ($arrRow['code'] == 'ARCHIVE')
		{
			$strArchive = 'Y';
		}
		
        $arrResult[] =  array(
            'id' => secureEntityValue('INTERNALMESSAGEFOLDER', $arrRow['id']),
			'is_inbox' => $strInbox,
			'is_system' => $strSystem,
			'is_sent' => $strSent,
            'is_archive' => $strArchive,
            'description' => $arrRow['description']
		);
    }
    
    dbCloseRecordset($objResult);
        
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', array('result' => $arrResult));
}
