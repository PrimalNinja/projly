<?php

function getHeader_contact($objConn_a)
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
        'field' => 'g5ccad758_2962_475f_bc56_bb9b2d584efd_branch',
        'name'  => 'Branch',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'contacttype',
        'field' => 'g5ccad758_2962_475f_bc56_bb9b2d584efd_contacttype',
        'name'  => 'Contact Type',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'status',
        'field' => 'g5ccad758_2962_475f_bc56_bb9b2d584efd_status',
        'name'  => 'Status',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'fullname',
        'field' => 'g5ccad758_2962_475f_bc56_bb9b2d584efd_fullname',
        'name'  => 'Full Name',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'mobile',
        'field' => 'g5ccad758_2962_475f_bc56_bb9b2d584efd_mobile',
        'name'  => 'Mobile',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'phone',
        'field' => 'g5ccad758_2962_475f_bc56_bb9b2d584efd_phone',
        'name'  => 'Phone',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'emailaddress',
        'field' => 'g5ccad758_2962_475f_bc56_bb9b2d584efd_emailaddress',
        'name'  => 'Email Address',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'postcode',
        'field' => 'g5ccad758_2962_475f_bc56_bb9b2d584efd_postcode',
        'name'  => 'Postcode',
        'sortable' => 'Y'
    );
		
    $arrResult[] = array(
        'id'    => 'country',
        'field' => 'g5ccad758_2962_475f_bc56_bb9b2d584efd_country',
        'name'  => 'Country',
        'sortable' => 'Y'
    );
		
    return $arrResult;
}

