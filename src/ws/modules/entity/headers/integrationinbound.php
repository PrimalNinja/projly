<?php

function getHeader_integrationinbound($objConn_a)
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
        'field' => 'descriptin',
        'name'  => 'Description',
        'sortable' => 'Y'
     );

     /*
     $arrResult[] = array(
        'id'    => 'is_enabled',
        'field' => 'is_enabled',
        'name'  => 'Is Enabled',
        'sortable' => 'Y'
     );
     */

    $arrResult[] = array(
        'id'    => 'inboundtype',
        'field' => 'g8fbd6661_d8e6_4279_b9a5_5e1042970937_integrationinboundtype',
        'name'  => 'Inbound Type',
        'sortable' => 'Y'
     );
     	
    $arrResult[] = array(
        'id'    => 'usewhitelist',
        'field' => 'g8fbd6661_d8e6_4279_b9a5_5e1042970937_use_whitelist',
        'name'  => 'Use Whitelist?',
        'sortable' => 'Y'
    );
               		               
    return $arrResult;
}
