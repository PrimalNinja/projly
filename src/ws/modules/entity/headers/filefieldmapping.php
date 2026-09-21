<?php

function getHeader_filefieldmapping($objConn_a)
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
        'id'    => 'from',
        'field' => 'g3b85cacf_9b3f_464b_b76b_2f2fa7b5fcc0_from',
        'name'  => 'From',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'to',
        'field' => 'g3b85cacf_9b3f_464b_b76b_2f2fa7b5fcc0_to',
        'name'  => 'To',
        'sortable' => 'Y'
    );
 
    return $arrResult;
}
