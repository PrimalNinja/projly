<?php

function getHeader_rostertemplate_detail($objConn_a)
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

    // $arrResult[] = array(
        // 'id'    => 'code',
        // 'field' => 'code',
        // 'name'  => 'Code',
        // 'sortable' => 'Y'
    // );
       
    $arrResult[] = array(
        'id'    => 'rosterday',
        'field' => 'g842ac7fc_e994_459f_b510_48b8f8e74239_rosterday',
        'name'  => 'Roster Day',
        'sortable' => 'Y'
    );
                    
    $arrResult[] = array(
        'id'    => 'plannedstarttime',
        'field' => 'g842ac7fc_e994_459f_b510_48b8f8e74239_plannedstarttime',
        'name'  => 'Planned Start',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'plannedendtime',
        'field' => 'g842ac7fc_e994_459f_b510_48b8f8e74239_plannedendtime',
        'name'  => 'Planned End',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'plannedtotalhours',
        'field' => 'g842ac7fc_e994_459f_b510_48b8f8e74239_plannedtotalhours',
        'name'  => 'Planned Hours',
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
