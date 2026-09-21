<?php

function getHeader_faq($objConn_a)
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
        'id'    => 'question',
        'field' => 'g29a00f7a_6ca5_40d5_8e68_bd8259559f30_question',
        'name'  => 'Question',
        'sortable' => 'Y'
    );

    $arrResult[] = array(
        'id'    => 'answer',
        'field' => 'g29a00f7a_6ca5_40d5_8e68_bd8259559f30_answer',
        'name'  => 'Answer',
        'sortable' => 'Y'
    );
		
    return $arrResult;
}
