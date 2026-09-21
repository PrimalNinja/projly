<?php

function getHeader_workqueuestatus($objConn_a)
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
        'id'    => 'group',
        'field' => 'ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_group',
        'name'  => 'Group',
        'sortable' => 'Y'
    );
           
    $arrResult[] = array(
        'id'    => 'workqueueitemtype',
        'field' => 'ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_workqueueitemtype',
        'name'  => 'Item Type',
        'sortable' => 'Y'
    );
        
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
           
    $arrResult[] = array(
        'id'    => 'notes',
        'field' => 'ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_notes',
        'name'  => 'Notes',
        'sortable' => 'Y'
    );
           
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );
        
    $arrResult[] = array(
        'id'    => 'iscompleted',
        'field' => 'ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_is_completed',
        'name'  => 'Completed',
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
