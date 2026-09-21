<?php

function getHeader_sequence($objConn_a)
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
        
    $arrResult[] = array(
        'id'    => 'module',
        'field' => 'g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_module',
        'name'  => 'Module',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'sequencetype',
        'field' => 'g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_sequencetype',
        'name'  => 'Sequence Type',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'prefix',
        'field' => 'g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_prefix',
        'name'  => 'Prefix',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'start',
        'field' => 'g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_start',
        'name'  => 'Start',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'end',
        'field' => 'g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_end',
        'name'  => 'End',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'next',
        'field' => 'g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_next',
        'name'  => 'Next',
        'sortable' => 'Y'
    );
        
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );
        
    // $arrResult[] = array(
        // 'id'    => 'isenabled',
        // 'field' => 'isenabled',
        // 'name'  => 'Enabled',
        // 'sortable' => 'Y'
    // );
        
    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );
        
    return $arrResult;
}
