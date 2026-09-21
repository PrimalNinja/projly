<?php

function getHeader_server($objConn_a)
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
        'id'    => 'code',
        'field' => 'code',
        'name'  => 'Code',
        'sortable' => 'Y'
    );
		        
 	$arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );

 	$arrResult[] = array(
        'id'    => 'serverprotocol',
        'field' => 'gafb65c33_7df8_4b01_a919_4df125c08f0e_serverprotocol',
        'name'  => 'Protocol',
        'sortable' => 'Y'
    );
	
    $arrResult[] = array(
        'id'    => 'connectionurl',
        'field' => 'gafb65c33_7df8_4b01_a919_4df125c08f0e_connectionurl',
        'name'  => 'URL',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'connectionport',
        'field' => 'gafb65c33_7df8_4b01_a919_4df125c08f0e_connectionport',
        'name'  => 'Port',
        'sortable' => 'Y'
    );
        
    return $arrResult;
}
