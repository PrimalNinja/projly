<?php

function getHeader_project($objConn_a)
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
        'id'    => 'projectpriority',
        'field' => 'ff304902c1_b136_4ea9_b67d_72eecc322697_projectpriority',
        'name'  => 'Priority',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projecttype',
        'field' => 'ff304902c1_b136_4ea9_b67d_72eecc322697_projecttype',
        'name'  => 'Type',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'associatedproject',
        'field' => 'ff304902c1_b136_4ea9_b67d_72eecc322697_associatedproject',
        'name'  => 'Associated',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projectstakeholder',
        'field' => 'ff304902c1_b136_4ea9_b67d_72eecc322697_projectstakeholder',
        'name'  => 'Stakeholder',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projectstatus',
        'field' => 'ff304902c1_b136_4ea9_b67d_72eecc322697_projectstatus',
        'name'  => 'Project Status',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'startdate',
        'field' => 'ff304902c1_b136_4ea9_b67d_72eecc322697_startdate',
        'name'  => 'Start Date',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'targetdate',
        'field' => 'ff304902c1_b136_4ea9_b67d_72eecc322697_targetdate',
        'name'  => 'Target Date',
        'sortable' => 'Y'
    );
        
    // $arrResult[] = array(
        // 'id'    => 'version',
        // 'field' => 'version',
        // 'name'  => 'Version',
        // 'sortable' => 'N'
    // );
        
    $arrResult[] = array(
        'id'    => 'isenabled',
        'field' => 'isenabled',
        'name'  => 'Enabled',
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
