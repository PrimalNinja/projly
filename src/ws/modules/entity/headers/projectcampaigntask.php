<?php

function getHeader_projectcampaigntask($objConn_a)
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
        'id'    => 'projecttaskpriority',
        'field' => 'g4ec1b214_f01d_4c37_beda_aa6a8dbbf78c_projecttaskpriority',
        'name'  => 'Task Priority',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projectcampaign',
        'field' => 'g4ec1b214_f01d_4c37_beda_aa6a8dbbf78c_projectcampaign',
        'name'  => 'Campaign',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projecttaskstatus',
        'field' => 'g4ec1b214_f01d_4c37_beda_aa6a8dbbf78c_projecttaskstatus',
        'name'  => 'Task Status',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'targetdate',
        'field' => 'g4ec1b214_f01d_4c37_beda_aa6a8dbbf78c_targetdate',
        'name'  => 'Target Date',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'assignedto',
        'field' => 'g4ec1b214_f01d_4c37_beda_aa6a8dbbf78c_assignedto',
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
