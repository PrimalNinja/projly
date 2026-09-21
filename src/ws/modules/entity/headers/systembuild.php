<?php

function getHeader_systembuild($objConn_a)
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
        'id'    => 'folder',
        'field' => 'g3addd7a5_6125_499d_b87a_c827161eae5f_folder',
        'name'  => 'Folder',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'date',
        'field' => 'g3addd7a5_6125_499d_b87a_c827161eae5f_date',
        'name'  => 'Date',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'time',
        'field' => 'g3addd7a5_6125_499d_b87a_c827161eae5f_time',
        'name'  => 'Time',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'status',
        'field' => 'g3addd7a5_6125_499d_b87a_c827161eae5f_status',
        'name'  => 'Status',
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
