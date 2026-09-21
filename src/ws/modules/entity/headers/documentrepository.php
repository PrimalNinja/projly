<?php

function getHeader_documentrepository($objConn_a)
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
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
	
    $arrResult[] = array(
        'id'    => 'documentretentiontype',
        'field' => 'g13c0b91f_45bd_492c_8b47_ce82b1757837_documentretentiontype',
        'name'  => 'Retention',
        'sortable' => 'Y'
    );
	
    $arrResult[] = array(
        'id'    => 'documentcount',
        'field' => 'g13c0b91f_45bd_492c_8b47_ce82b1757837_documentcount',
        'name'  => 'Document Count',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'storageused',
        'field' => 'g13c0b91f_45bd_492c_8b47_ce82b1757837_storageused',
        'name'  => 'Storage Used',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'deletedstoragesize',
        'field' => 'g13c0b91f_45bd_492c_8b47_ce82b1757837_deletestoragesize',
        'name'  => 'Deleted Storage Size',
        'sortable' => 'Y'
    );
    
    return $arrResult;
}
