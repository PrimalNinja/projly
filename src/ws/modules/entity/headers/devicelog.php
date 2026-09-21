<?php

function getHeader_devicelog($objConn_a)
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
        'id'    => 'clientcode',
        'field' => 'clientcode',
        'name'  => 'Account Code',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'login',
        'field' => 'login',
        'name'  => 'Login',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'code',
        'field' => 'code',
        'name'  => 'Device Code',
        'sortable' => 'Y'
    );

 	$arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );

 	$arrResult[] = array(
        'id'    => 'ipaddress',
        'field' => 'ipaddress',
        'name'  => 'IP Address',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'notes',
        'field' => 'notes',
        'name'  => 'Notes',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'modified',
        'field' => 'modified',
        'name'  => 'Date / Time',
        'sortable' => 'Y'
    );

    return $arrResult;
}
