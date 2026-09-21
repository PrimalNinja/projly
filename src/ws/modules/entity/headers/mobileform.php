<?php

function getHeader_mobileform($objConn_a)
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
		'id'    => 'systemform',
		'field' => 'g1d033c60_b8bd_4925_97ab_41efd975e746_systemform',
		'name'  => 'System Form',
		'sortable' => 'Y'
	);
        
    $arrResult[] = array(
        'id'    => 'validfromdate',
        'field' => 'g1d033c60_b8bd_4925_97ab_41efd975e746_validfromdate',
        'name'  => 'Valid From',
        'sortable' => 'Y'
    );
        	
	$arrResult[] = array(
        'id'    => 'validtodate',
        'field' => 'g1d033c60_b8bd_4925_97ab_41efd975e746_validtodate',
        'name'  => 'Valid To',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );
        
    
    return $arrResult;
}
