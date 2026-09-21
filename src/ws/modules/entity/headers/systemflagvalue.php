<?php

function getHeader_systemflagvalue($objConn_a)
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
        'id'    => 'systemflagtype',
        'field' => 'ge13ca38f_41a4_4363_a9ea_c9262cfc65ac_systemflagtype',
        'name'  => 'System Flag Type',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'value',
        'field' => 'ge13ca38f_41a4_4363_a9ea_c9262cfc65ac_value',
        'name'  => 'Value',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'notes',
        'field' => 'ge13ca38f_41a4_4363_a9ea_c9262cfc65ac_notes',
        'name'  => 'Notes',
        'sortable' => 'Y'
    );
        
    return $arrResult;
}
