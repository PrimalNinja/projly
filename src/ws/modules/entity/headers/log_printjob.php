<?php

function getHeader_log_printjob($objConn_a)
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
        'id'    => 'jobdatetime',
        'field' => 'gb398c9db_2272_4748_907c_d9685b924329_jobdatetime',
        'name'  => 'Date/Time',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'status',
        'field' => 'gb398c9db_2272_4748_907c_d9685b924329_status',
        'name'  => 'Status',
        'sortable' => 'Y'
    );
               
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'device',
        'field' => 'gb398c9db_2272_4748_907c_d9685b924329_device',
        'name'  => 'Device',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'printer',
        'field' => 'gb398c9db_2272_4748_907c_d9685b924329_printer',
        'name'  => 'Printer',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'queueowner',
        'field' => 'gb398c9db_2272_4748_907c_d9685b924329_queueowner',
        'name'  => 'Queue Owner',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'printqueue',
        'field' => 'gb398c9db_2272_4748_907c_d9685b924329_printqueue',
        'name'  => 'Printer Queue',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'moreinfo',
        'field' => 'gb398c9db_2272_4748_907c_d9685b924329_moreinfo',
        'name'  => 'More Info',
        'sortable' => 'Y'
    );
               
    //$arrResult[] = array(
    //    'id'    => 'isenabled',
    //    'field' => 'isenabled',
    //    'name'  => 'Enabled',
    //    'sortable' => 'Y'
    //);
        
    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );
        
    return $arrResult;
}
