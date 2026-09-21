<?php

function getHeader_softwarelicence($objConn_a)
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
        'id'    => 'software',
        'field' => 'g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_software',
        'name'  => 'Software',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'version',
        'field' => 'g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_version',
        'name'  => 'Version',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'licencetype',
        'field' => 'g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_licencetype',
        'name'  => 'Licence Type',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'ownerother',
        'field' => 'g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_ownerother',
        'name'  => 'Owner',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'filename',
        'field' => 'g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_filename',
        'name'  => 'Archive',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'lastauditdate',
        'field' => 'g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_lastauditdate',
        'name'  => 'Audit Date',
        'sortable' => 'Y'
    );
        
    $arrResult[] = array(
        'id'    => 'auditor',
        'field' => 'g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_auditor',
        'name'  => 'Auditor',
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
        'name'  => 'Active',
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
