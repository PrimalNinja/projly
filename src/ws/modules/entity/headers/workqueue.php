<?php

function getHeader_workqueue($objConn_a)
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
        'id'    => 'workqueuecode',
        'field' => 'workqueueitemheader_code',
        'name'  => 'Code',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'assignedto',
        'field' => 'workqueueitemheader_assignedto',
        'name'  => 'Assigned To',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'longdescription',
        'field' => 'workqueueitemheader_longdescription',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );
        
    $arrResult[] = array(
        'id'    => 'progress',
        'field' => 'progress',
        'name'  => 'Progress',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'workqueuestatus',
        'field' => 'workqueueitemheader_workqueuestatus',
        'name'  => 'Status',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'created',
        'field' => 'created',
        'name'  => 'Created',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'modified',
        'field' => 'modified',
        'name'  => 'Modified',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'modifyuser',
        'field' => 'modifyuser',
        'name'  => 'Modified By',
        'sortable' => 'Y'
    );
        
    return $arrResult;
}
