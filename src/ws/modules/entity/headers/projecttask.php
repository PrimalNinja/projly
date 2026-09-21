<?php

function getHeader_projecttask($objConn_a)
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
        'id'    => 'sprint',
        'field' => 'ffe63e2a32_d72a_474c_82d0_b664eef1268a_sprint',
        'name'  => 'Sprint',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'taskid',
        'field' => 'ffe63e2a32_d72a_474c_82d0_b664eef1268a_code',
        'name'  => 'Task ID',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projecttaskpriority',
        'field' => 'ffe63e2a32_d72a_474c_82d0_b664eef1268a_projecttaskpriority',
        'name'  => 'Task Priority',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'project',
        'field' => 'ffe63e2a32_d72a_474c_82d0_b664eef1268a_project',
        'name'  => 'Project',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projectissue',
        'field' => 'ffe63e2a32_d72a_474c_82d0_b664eef1268a_projectissue',
        'name'  => 'Issue',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'allocatedhours',
        'field' => 'ffe63e2a32_d72a_474c_82d0_b664eef1268a_allocatedhours',
        'name'  => 'Allocated Hours',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projecttaskstatus',
        'field' => 'ffe63e2a32_d72a_474c_82d0_b664eef1268a_projecttaskstatus',
        'name'  => 'Task Status',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'targetdate',
        'field' => 'ffe63e2a32_d72a_474c_82d0_b664eef1268a_targetdate',
        'name'  => 'Target Date',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'createdate',
        'field' => 'ffe63e2a32_d72a_474c_82d0_b664eef1268a_createdate',
        'name'  => 'Create Date',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projectparticipant',
        'field' => 'ffe63e2a32_d72a_474c_82d0_b664eef1268a_projectparticipant',
        'name'  => 'Assigned To',
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
