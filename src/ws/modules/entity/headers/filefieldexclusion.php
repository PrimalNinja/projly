<?php

function getHeader_filefieldexclusion($objConn_a)
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
        'id'    => 'exclusion',
        'field' => 'g44107c60_2977_4385_9eb0_2bfc71bb76e4_exclusion',
        'name'  => 'Exclusion',
        'sortable' => 'Y'
    );

    return $arrResult;
}
