<?php

function getHeader_video($objConn_a)
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
        'id'    => 'videocategory',
        'field' => 'gd04f7001_3055_42b5_8a1d_20f79967c656_videocategory',
        'name'  => 'Category',
        'sortable' => 'Y'
    );
	
	$arrResult[] = array(
        'id'    => 'videosource',
        'field' => 'gd04f7001_3055_42b5_8a1d_20f79967c656_videosource',
        'name'  => 'Source',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'description',
        'field' => 'description',
        'name'  => 'Description',
        'sortable' => 'Y'
    );
        
    // $arrResult[] = array(
        // 'id'    => 'url',
        // 'field' => 'gd04f7001_3055_42b5_8a1d_20f79967c656_url',
        // 'name'  => 'URL',
        // 'sortable' => 'Y'
    // );
        
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
