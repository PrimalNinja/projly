<?php

function getHeader_fileformattype($objConn_a)
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
        'id'    => 'is_userdefined',
        'field' => 'g60c467c2_432e_4dff_90f2_4963ae121143_is_userdefined',
        'name'  => 'Is User-defined?',
        'sortable' => 'Y'
    );
                    
    return $arrResult;
}
