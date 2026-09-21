<?php

function getHeader_projectissue($objConn_a)
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
        'id'    => 'sprint',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_sprint',
        'name'  => 'Sprint',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'issueid',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_code',
        'name'  => 'Issue ID',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projectissuepriority',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuepriority',
        'name'  => 'Issue Priority',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'project',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_project',
        'name'  => 'Project',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projectissuetype',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuetype',
        'name'  => 'Issue Type',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projectissuestatus',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuestatus',
        'name'  => 'Issue Status',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'targetdate',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_targetdate',
        'name'  => 'Target Date',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'estimatedhours',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_estimatedhours',
        'name'  => 'Estimated Hours',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'estimatedminutes',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_minutes',
        'name'  => 'Estimated Minutes',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'actualhours',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_actualhours',
        'name'  => 'Actual Hours',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'actualminutes',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_actualminutes',
        'name'  => 'Actual Minutes',
        'sortable' => 'Y'
    );
    
    $arrResult[] = array(
        'id'    => 'createdate',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_createdate',
        'name'  => 'Create Date',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'projectparticipant',
        'field' => 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectparticipant',
        'name'  => 'Issue Owner',
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
