<?php

function getHeader_systemmoduletask($objConn_a)
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
        'id'    => 'command',
        'field' => 'g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_command',
        'name'  => 'Command',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'parameters',
        'field' => 'g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_parameters',
        'name'  => 'Parameters',
        'sortable' => 'Y'
    );

    /*
    $arrResult[] = array(
        'id'    => 'isprocessed',
        'field' => 'g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_is_processed',
        'name'  => 'Processed?',
        'sortable' => 'Y'
    );
    */
    
    /*
    $arrResult[] = array(
        'id'    => 'starttime',
        'field' => 'g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_starttime',
        'name'  => 'Start Time',
        'sortable' => 'Y'
    );
    */

    /*
    $arrResult[] = array(
        'id'    => 'endtime',
        'field' => 'g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_endtime',
        'name'  => 'End Time',
        'sortable' => 'Y'
    );
    */
    /*
    $arrResult[] = array(
        'id'    => 'sortorder',
        'field' => 'sortorder',
        'name'  => 'Sort Order',
        'sortable' => 'Y'
    );
    */

        
    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );        
    return $arrResult;
}
