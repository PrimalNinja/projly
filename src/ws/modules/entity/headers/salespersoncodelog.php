<?php

function getHeader_salespersoncodelog($objConn_a)
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
        'id'    => 'date',
        'field' => 'g8f690085_6d2f_4296_a16a_ff1d1d43b0bb_date',
        'name'  => 'Date',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'salespersoncode',
        'field' => 'g8f690085_6d2f_4296_a16a_ff1d1d43b0bb_salespersoncode',
        'name'  => 'Code',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'exception',
        'field' => 'g8f690085_6d2f_4296_a16a_ff1d1d43b0bb_exception',
        'name'  => 'Exception',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'soldtoclient',
        'field' => 'g8f690085_6d2f_4296_a16a_ff1d1d43b0bb_soldtoclient',
        'name'  => 'Sold To',
        'sortable' => 'Y'
    );
        
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );
        
    // $arrResult[] = array(
        // 'id'    => 'isenabled',
        // 'field' => 'isenabled',
        // 'name'  => 'Enabled',
        // 'sortable' => 'Y'
    // );
        
    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );
        
    return $arrResult;
}
