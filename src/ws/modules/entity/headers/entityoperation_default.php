<?php

function getHeader_entityoperation_default($objConn_a)
{	
    $arrResult = array();     
    
	if (toBoolean(ENABLE_ROWNUM))
	{
		$arrResult[] = array(
			'id'    => 'rownum',
			'field' => 'rownum',
			'name'  => '#',
			'sortable' => 'N'
		);
	}

    $arrResult[] = array(
        'id'    => 'code',
        'field' => 'code',
        'name'  => 'Code',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );

    $arrResult[] = array(
        'id'    => 'command',
        'field' => 'ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_command',
        'name'  => 'Command',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'isinternal',
        'field' => 'ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_isinternal',
        'name'  => 'Is Internal',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'requiresselection',
        'field' => 'ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_requiresselection',
        'name'  => 'Requires Selection',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'allowmultiple',
        'field' => 'ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_allowmultiple',
        'name'  => 'Allow Multiple',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'iscustom',
        'field' => 'ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_iscustom',
        'name'  => 'Is Custom',
        'sortable' => 'Y'
    );
	
    $arrResult[] = array(
        'id'    => 'displayorder',
        'field' => 'ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_displayorder',
        'name'  => 'Display Order',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );
        
    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );
        
    return $arrResult;
}
