<?php

function getHeader_dbprocess($objConn_a)
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
        'id'    => 'processid',
        'field' => 'g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_id',
        'name'  => 'Process ID',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'user',
        'field' => 'g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_user',
        'name'  => 'User',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'host',
        'field' => 'g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_host',
        'name'  => 'Host',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'db',
        'field' => 'g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_db',
        'name'  => 'Database',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'command',
        'field' => 'g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_command',
        'name'  => 'Command',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'time',
        'field' => 'g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_time',
        'name'  => 'Seconds',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'state',
        'field' => 'g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_state',
        'name'  => 'State',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'info',
        'field' => 'g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_info',
        'name'  => 'Info',
        'sortable' => 'Y'
    );
        
    return $arrResult;
}
