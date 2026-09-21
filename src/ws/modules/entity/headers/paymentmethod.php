<?php

function getHeader_paymentmethod($objConn_a)
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

    //$arrResult[] = array(
        //'id'    => 'formentitycode',
        //'field' => 'formentitycode',
        //'name'  => 'Entity',
        //'sortable' => 'N'
    //);
        
    $arrResult[] = array(
        'id'    => 'displayorder',
        'field' => 'ffb775d2a9_59b7_453b_91ce_7621548ffe81_displayorder',
        'name'  => 'Display Order',
        'sortable' => 'Y'
    ); 
		       
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
		
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // ); 
        
    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );
        
    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );
	
    return $arrResult;
}
