<?php

function getHeader_rostertemplate_projectparticipant($objConn_a)
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
        'name'  => 'Participant',
        'sortable' => 'Y'
    );
       
    return $arrResult;
}
