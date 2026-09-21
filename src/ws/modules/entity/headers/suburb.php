<?php

function getHeader_suburb($objConn_a)
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
        
    // $arrResult[] = array(
        // 'id'    => 'code',
        // 'field' => 'code',
        // 'name'  => 'Code',
        // 'sortable' => 'Y'
    // );
	
	$arrResult[] = array(
        'id'    => 'suburb',
        'field' => 'suburb',
        'name'  => 'Suburb',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'state',
        'field' => 'state',
        'name'  => 'State',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'postcode',
        'field' => 'postcode',
        'name'  => 'Postcode',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'country',
        'field' => 'country',
        'name'  => 'Country',
        'sortable' => 'Y'
    );
		
	// $arrResult[] = array(
        // 'id'    => 'description',
        // 'field' => 'description',
        // 'name'  => 'Description',
        // 'sortable' => 'Y'
    // );
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );
        
    // $arrResult[] = array(
        // 'id'    => 'isenabled',
        // 'field' => 'isenabled',
        // 'name'  => 'Enabled',
        // 'sortable' => 'Y'
    // );
        
    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );
	
    return $arrResult;
}
