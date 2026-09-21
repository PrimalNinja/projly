<?php

function getHeader_projectresourceactivity($objConn_a)
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
        'id'    => 'projectresource',
        'field' => 'ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_projectresource',
        'name'  => 'Resource',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projecttask',
        'field' => 'ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_projecttask',
        'name'  => 'Task',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'activitydate',
        'field' => 'ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_activitydate',
        'name'  => 'Date',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'starttime',
        'field' => 'ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_starttime',
        'name'  => 'Start Time',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'endtime',
        'field' => 'ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_endtime',
        'name'  => 'End Time',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'totalhours',
        'field' => 'ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_totalhours',
        'name'  => 'Hours',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'totalminutes',
        'field' => 'ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_totalminutes',
        'name'  => 'Minutes',
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
