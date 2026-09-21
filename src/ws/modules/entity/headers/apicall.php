<?php

function getHeader_apicall($objConn_a)
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
        'id'    => 'datetime',
        'field' => 'modifydatetime',
        'name'  => 'Date/Time',
        'sortable' => 'Y'
    );   

    $arrResult[] = array(
        'id'    => 'clientname',
        'field' => 'ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_clientname',
        'name'  => 'Client',
        'sortable' => 'Y'
    );   

    $arrResult[] = array(
        'id'    => 'apicalltype',
        'field' => 'ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_apicalltype',
        'name'  => 'API Call Type',
        'sortable' => 'Y'
    );   

    $arrResult[] = array(
        'id'    => 'subtype',
        'field' => 'ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_subtype',
        'name'  => 'API Sub Type',
        'sortable' => 'Y'
    );   

    $arrResult[] = array(
        'id'    => 'callcount',
        'field' => 'ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_callcount',
        'name'  => 'Call Count',
        'sortable' => 'Y'
    );   

    $arrResult[] = array(
        'id'    => 'issuccessful',
        'field' => 'ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_issuccessful',
        'name'  => 'Successful',
        'sortable' => 'Y'
    );   

    $arrResult[] = array(
        'id'    => 'notes',
        'field' => 'ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_notes',
        'name'  => 'Notes',
        'sortable' => 'Y'
    );   

    return $arrResult;
}
