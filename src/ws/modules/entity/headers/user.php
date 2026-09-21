<?php

function getHeader_user($objConn_a)
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
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Full Name',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'login',
        'field' => '1d3fbeab_d440_4329_9b9c_aa93f6fe7c16_login',
        'name'  => 'Login',
        'sortable' => 'Y'
    );

	$arrResult[] = array(
        'id'    => 'userstatus',
        'field' => '1d3fbeab_d440_4329_9b9c_aa93f6fe7c16_userstatus',
        'name'  => 'Status',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );
        
	$arrResult[] = array(
        'id'    => 'email_address',
        'field' => 'email_address',
        'name'  => 'Email Address',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'code',
        'field' => 'code',
        'name'  => 'User ID',
        'sortable' => 'Y'
    );
	
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );
        
    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );
	
    return $arrResult;
}
