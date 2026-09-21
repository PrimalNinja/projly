<?php

function getHeader_device($objConn_a)
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
        'id'    => 'user',
        'field' => 'fff26af3dd_710e_4510_8153_058ed948e34e_user',
        'name'  => 'User',
        'sortable' => 'Y'
    );

 	$arrResult[] = array(
        'id'    => 'description2',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'isauthenticated',
        'field' => 'is_authenticated',
        'name'  => 'Authenticated',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );

 	$arrResult[] = array(
        'id'    => 'pq4or',
        'field' => 'queueing_pq4or',
        'name'  => 'Report Queue',
        'sortable' => 'Y'
    );
	
 	$arrResult[] = array(
        'id'    => 'p4or',
        'field' => 'printing_p4or',
        'name'  => 'Report Printer',
        'sortable' => 'Y'
    );
	
 	$arrResult[] = array(
        'id'    => 'printfromq',
        'field' => 'printing_printfromq',
        'name'  => 'Print From',
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
