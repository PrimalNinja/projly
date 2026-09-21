<?php

function getHeader_roster($objConn_a)
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
        'id'    => 'date',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_date',
        'name'  => 'Date',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projectparticipant',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_projectparticipant',
        'name'  => 'Participant',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'plannedstarttime',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_plannedstarttime',
        'name'  => 'Planned Start',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'plannedendtime',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_plannedendtime',
        'name'  => 'Planned End',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'plannedtotalhours',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_plannedtotalhours',
        'name'  => 'Planned Hours',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'clockontime',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_clockontime',
        'name'  => 'Clock On',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'clockofftime',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_clockofftime',
        'name'  => 'Clock Off',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'totalhours',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_totalhours',
        'name'  => 'Actual Hours',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'totalminutes',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_totalminutes',
        'name'  => 'Actual Minutes',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'cancelled',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_cancelled',
        'name'  => 'Cancelled',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'managersignoff',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_managersignoff',
        'name'  => 'Man Sign Off',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'hrsignoff',
        'field' => 'g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_hrsignoff',
        'name'  => 'HR Sign Off',
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
