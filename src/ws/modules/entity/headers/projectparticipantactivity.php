<?php

function getHeader_projectparticipantactivity($objConn_a)
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
        'id'    => 'projectparticipant',
        'field' => 'ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_projectparticipant',
        'name'  => 'Participant',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projecttask',
        'field' => 'ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_projecttask',
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
        'id'    => 'roster',
        'field' => 'ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_roster',
        'name'  => 'Roster',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'activitydate',
        'field' => 'ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_activitydate',
        'name'  => 'Date',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'starttime',
        'field' => 'ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_starttime',
        'name'  => 'Start Time',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'endtime',
        'field' => 'ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_endtime',
        'name'  => 'End Time',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'totalhours',
        'field' => 'ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_totalhours',
        'name'  => 'Hours',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'totalminutes',
        'field' => 'ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_totalminutes',
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
