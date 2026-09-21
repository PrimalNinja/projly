<?php

function getHeader_entity($objConn_a)
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
        'id'    => 'fixedstatus',
        'field' => 'ffe65a2521_c3d0_42fc_8d25_dedd43876fff_fixedstatus',
        'name'  => 'Fixed Status',
        'sortable' => 'Y'
    );

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
        
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );

    $arrResult[] = array(
        'id'    => 'permissioncategory',
        'field' => 'ffe65a2521_c3d0_42fc_8d25_dedd43876fff_permissioncategory',
        'name'  => 'Category',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'isextended',
        'field' => 'ffe65a2521_c3d0_42fc_8d25_dedd43876fff_isextended',
        'name'  => 'Extended',
        'sortable' => 'Y'
    );
	
    $arrResult[] = array(
        'id'    => 'deferredpopulation',
        'field' => 'ffe65a2521_c3d0_42fc_8d25_dedd43876fff_deferredpopulation',
        'name'  => 'Deferred Population',
        'sortable' => 'Y'
    );
	
    $arrResult[] = array(
        'id'    => 'ishidden',
        'field' => 'ffe65a2521_c3d0_42fc_8d25_dedd43876fff_ishidden',
        'name'  => 'Hidden',
        'sortable' => 'Y'
    );
	
    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'liverowcount',
        'field' => 'ffe65a2521_c3d0_42fc_8d25_dedd43876fff_liverowcount',
        'name'  => '#Live Rows',
        'sortable' => 'Y'
    );
	
    $arrResult[] = array(
        'id'    => 'historyrowcount',
        'field' => 'ffe65a2521_c3d0_42fc_8d25_dedd43876fff_historyrowcount',
        'name'  => '#History Rows',
        'sortable' => 'Y'
    );
	
    // $arrResult[] = array(
        // 'id'    => 'modified',
        // 'field' => 'modified',
        // 'name'  => 'Modified',
        // 'sortable' => 'Y'
    // );
        
    return $arrResult;
}
