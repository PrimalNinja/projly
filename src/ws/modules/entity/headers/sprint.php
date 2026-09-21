<?php

function getHeader_sprint($objConn_a)
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
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'estimatedhours',
        'field' => 'ga627d4c2_b700_48ab_8722_e19c42b41036_estimatedhours',
        'name'  => 'Estimated Hours',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'estimatedminutes',
        'field' => 'ga627d4c2_b700_48ab_8722_e19c42b41036_estimatedminutes',
        'name'  => 'Estimated Minutes',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'plannedstartdate',
        'field' => 'ga627d4c2_b700_48ab_8722_e19c42b41036_plannedstartdate',
        'name'  => 'Planned Start',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'startdate',
        'field' => 'ga627d4c2_b700_48ab_8722_e19c42b41036_startdate',
        'name'  => 'Start',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'plannedenddate',
        'field' => 'ga627d4c2_b700_48ab_8722_e19c42b41036_plannedenddate',
        'name'  => 'Planned End',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'enddate',
        'field' => 'ga627d4c2_b700_48ab_8722_e19c42b41036_enddate',
        'name'  => 'End',
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
