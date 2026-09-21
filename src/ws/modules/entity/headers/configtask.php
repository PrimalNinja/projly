<?php

function getHeader_configtask($objConn_a)
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

    /*
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
    */
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );
        
    /*
    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );
    */

    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );
       
    $arrResult[] = array(
        'id'    => 'command',
        'field' => 'g15aeaa60_835c_4974_87c5_e82ec903629f_command',
        'name'  => 'Command',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'parameters',
        'field' => 'g15aeaa60_835c_4974_87c5_e82ec903629f_parameters',
        'name'  => 'Parameters',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'isprocessed',
        'field' => 'g15aeaa60_835c_4974_87c5_e82ec903629f_is_processed',
        'name'  => 'Processed',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'starttime',
        'field' => 'g15aeaa60_835c_4974_87c5_e82ec903629f_starttime',
        'name'  => 'Start Time',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'endtime',
        'field' => 'g15aeaa60_835c_4974_87c5_e82ec903629f_endtime',
        'name'  => 'End Time',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'sortorder',
        'field' => 'g15aeaa60_835c_4974_87c5_e82ec903629f_sortorder',
        'name'  => 'Sort Order',
        'sortable' => 'Y'
    );
    
    return $arrResult;
}
