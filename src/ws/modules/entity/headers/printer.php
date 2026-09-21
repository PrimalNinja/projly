<?php

function getHeader_printer($objConn_a)
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
        'id'    => 'branch',
        'field' => 'info_branch',
        'name'  => 'Branch',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'settings_description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'purpose',
        'field' => 'settings_printerpurpose',
        'name'  => 'Purpose',
        'sortable' => 'Y'
    );
		
	$arrResult[] = array(
        'id'    => 'is_public',
        'field' => 'is_public',
        'name'  => 'Is Public',
        'sortable' => 'Y'
    );    
    
    $arrResult[] = array(
        'id'    => 'systemprinter',
        'field' => 'settings_systemprinter',
        'name'  => 'System Printer',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'printertype',
        'field' => 'settings_printertype',
        'name'  => 'Type',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'leftmargin',
        'field' => 'settings_leftmargin',
        'name'  => 'Left Margin',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'topmargin',
        'field' => 'settings_topmargin',
        'name'  => 'Top Margin',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'identifier',
        'field' => 'info_identifier',
        'name'  => 'Identifer',
        'sortable' => 'Y'
    );

    return $arrResult;
}
