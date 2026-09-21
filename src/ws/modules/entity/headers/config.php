<?php

function getHeader_config($objConn_a)
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
        'id'    => 'isprocessed',
        'field' => 'gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_is_processed',
        'name'  => 'Processed',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'isready',
        'field' => 'gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_is_ready',
        'name'  => 'Ready',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'starttime',
        'field' => 'gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_starttime',
        'name'  => 'Start Time',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'endtime',
        'field' => 'gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_endtime',
        'name'  => 'End Time',
        'sortable' => 'Y'
    );
       
    return $arrResult;
}
