<?php

function getHeader_inbound_whitelist($objConn_a)
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
        'id'    => 'ipaddress',
        'field' => 'gff06200b_9c01_4408_a038_d07c85e5132e_ipaddress',
        'name'  => 'IP Address',
        'sortable' => 'Y'
     );
	 
	$arrResult[] = array(
        'id'    => 'description',
        'field' => 'gff06200b_9c01_4408_a038_d07c85e5132e_description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'is_enabled',
        'field' => 'is_enabled',
        'name'  => 'Is Enabled',
        'sortable' => 'Y'
     );
                		               
    return $arrResult;
}
