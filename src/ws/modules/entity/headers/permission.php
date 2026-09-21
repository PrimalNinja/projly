<?php

function getHeader_permission($objConn_a)
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
        'id'    => 'code',
        'field' => 'code',
        'name'  => 'Code',
        'sortable' => 'Y'
    );
		
	$arrResult[] = array(
        'id'    => 'permissioncategory',
        'field' => '0000eae3_e5b8_4ebb_a3a8_50220cee15d5_permissioncategory',
        'name'  => 'Category',
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
        'id'    => 'issysadmin',
        'field' => '0000eae3_e5b8_4ebb_a3a8_50220cee15d5_issysadmin',
        'name'  => 'Is Sysadmin?',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'isnonsysadmin',
        'field' => '0000eae3_e5b8_4ebb_a3a8_50220cee15d5_isnonsysadmin',
        'name'  => 'Is Non Sysadmin?',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'islicensed',
        'field' => '0000eae3_e5b8_4ebb_a3a8_50220cee15d5_islicensed',
        'name'  => 'Is Licensed?',
        'sortable' => 'Y'
    );
	
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
