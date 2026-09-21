<?php

function getHeader_integrationoutbound($objConn_a)
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
        'id'    => 'outboundtype',
        'field' => 'g561d6970_cd14_4e7b_8ac4_460ae9f6a750_integrationoutboundtype',
        'name'  => 'Outbound Type',
        'sortable' => 'Y'
     );
     	
	$arrResult[] = array(
        'id'    => 'authenticationtype',
        'field' => 'g561d6970_cd14_4e7b_8ac4_460ae9f6a750_authenticationtype',
        'name'  => 'Authentication',
        'sortable' => 'Y'
    );
          		               
    return $arrResult;
}
