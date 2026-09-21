<?php

function getHeader_userform_device($objConn_a)
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

 	$arrResult[] = array(
        'id'    => 'form',
        'field' => 'gb044f0b5_48c7_48d6_8d6c_b1f8af44a652_systemform',
        'name'  => 'Form',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'device',
        'field' => 'gb044f0b5_48c7_48d6_8d6c_b1f8af44a652_device',
        'name'  => 'Device',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
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
