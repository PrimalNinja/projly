<?php

function getHeader_salespersoncode($objConn_a)
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
        'id'    => 'companyother',
        'field' => 'g7ea44fdf_b30a_47df_80d4_18057254752e_companyother',
        'name'  => 'Company',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'fullname',
        'field' => 'g7ea44fdf_b30a_47df_80d4_18057254752e_fullname',
        'name'  => 'Full Name',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'phonenumber',
        'field' => 'g7ea44fdf_b30a_47df_80d4_18057254752e_phonenumber',
        'name'  => 'Phone Number',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'emailaddress',
        'field' => 'g7ea44fdf_b30a_47df_80d4_18057254752e_emailaddress',
        'name'  => 'Email Address',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'code',
        'field' => 'code',
        'name'  => 'Code',
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
