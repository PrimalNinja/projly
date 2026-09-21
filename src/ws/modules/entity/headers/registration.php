<?php

function getHeader_registration($objConn_a)
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
        'id'    => 'registrationtypedesc',
        'field' => 'ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_registrationtypedesc',
        'name'  => 'Account Type',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'clientcode',
        'field' => 'ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_clientcode',
        'name'  => 'Account Code',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'accountname',
        'field' => 'ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_accountname',
        'name'  => 'Account Name',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'ipaddress',
        'field' => 'ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_ipaddress',
        'name'  => 'IP Address',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'is_confirmed',
        'field' => 'ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_isconfirmed',
        'name'  => 'Confirmed',
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
