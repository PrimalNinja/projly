<?php

function getHeader_account($objConn_a)
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
        'id'    => 'registrationtypedesc',
        'field' => 'ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accounttype',
        'name'  => 'Account Type',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'clientcode',
        'field' => 'ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_clientcode',
        'name'  => 'Account Code',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'accountname',
        'field' => 'ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountname',
        'name'  => 'Account Name',
        'sortable' => 'Y'
    );
	
    // $arrResult[] = array(
        // 'id'    => 'code',
        // 'field' => 'f8d9bfb3a_3eb0_4620_ad86_af7b4e222325_code',
        // 'name'  => 'Code',
        // 'sortable' => 'Y'
    // );
	
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
