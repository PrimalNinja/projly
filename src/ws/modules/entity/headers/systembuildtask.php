<?php

function getHeader_systembuildtask($objConn_a)
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
        'field' => 'gd8fac874_b432_4019_b5b0_abefded76d05_command',
        'name'  => 'Command',
        'sortable' => 'Y'
    );

    /*
    $arrResult[] = array(
        'id'    => 'parameters',
        'field' => 'gd8fac874_b432_4019_b5b0_abefded76d05_parameters',
        'name'  => 'Parameters',
        'sortable' => 'Y'
    );
    */
    
    $arrResult[] = array(
        'id'    => 'isprocessed',
        'field' => 'gd8fac874_b432_4019_b5b0_abefded76d05_is_processed',
        'name'  => 'Processed?',
        'sortable' => 'Y'
    );
    
    
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
