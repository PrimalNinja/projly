<?php

function getHeader_blogger($objConn_a)
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
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'date',
        'field' => 'g95ca647e_5947_49c4_876e_49c7f68e980b_date',
        'name'  => 'Date',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'systemform',
        'field' => 'g95ca647e_5947_49c4_876e_49c7f68e980b_systemform',
        'name'  => 'System Form',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'text1',
        'field' => 'text1',
        'name'  => 'Text 1',
        'sortable' => 'Y'
    );

        
    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );
        

    return $arrResult;
}
