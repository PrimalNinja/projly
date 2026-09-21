<?php

function getHeader_batchjob($objConn_a)
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
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'priority',
        'field' => 'ff1a767f7b_1c5d_411f_ae10_a5d486a10328_priority',
        'name'  => 'Priority',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'jobstatus',
        'field' => 'ff1a767f7b_1c5d_411f_ae10_a5d486a10328_jobstatus',
        'name'  => 'Status',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'progress',
        'field' => 'ff1a767f7b_1c5d_411f_ae10_a5d486a10328_progress',
        'name'  => 'Progress',
        'sortable' => 'Y'
    );
    
	$arrResult[] = array(
        'id'    => 'scheduled',
        'field' => 'ff1a767f7b_1c5d_411f_ae10_a5d486a10328_scheduled',
        'name'  => 'Scheduled',
        'sortable' => 'Y'
    );    
    
	$arrResult[] = array(
        'id'    => 'startdatetime',
        'field' => 'ff1a767f7b_1c5d_411f_ae10_a5d486a10328_startdatetime',
        'name'  => 'Started',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'subtask',
        'field' => 'ff1a767f7b_1c5d_411f_ae10_a5d486a10328_subtask',
        'name'  => 'Sub Task',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'error',
        'field' => 'ff1a767f7b_1c5d_411f_ae10_a5d486a10328_error',
        'name'  => 'Error',
        'sortable' => 'Y'
    );   
   
    return $arrResult;
}
