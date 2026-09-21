<?php

function getHeader_rndgrant($objConn_a)
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

    // $arrResult[] = array(
        // 'id'    => 'code',
        // 'field' => 'code',
        // 'name'  => 'Code',
        // 'sortable' => 'Y'
    // );
        
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'g6677fd35_4344_4a1f_8947_fc7bac647014_description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projectparticipant',
        'field' => 'g6677fd35_4344_4a1f_8947_fc7bac647014_projectparticipant',
        'name'  => 'Applicant',
        'sortable' => 'Y'
    );
                   
    $arrResult[] = array(
        'id'    => 'dateapplied',
        'field' => 'g6677fd35_4344_4a1f_8947_fc7bac647014_dateapplied',
        'name'  => 'Date Applied',
        'sortable' => 'Y'
    );
      
    $arrResult[] = array(
        'id'    => 'dateapproved',
        'field' => 'g6677fd35_4344_4a1f_8947_fc7bac647014_dateapproved',
        'name'  => 'Date Approved',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'dategranted',
        'field' => 'g6677fd35_4344_4a1f_8947_fc7bac647014_dategranted',
        'name'  => 'Date Granted',
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
