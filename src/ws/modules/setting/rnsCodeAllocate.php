<?php

// allocate an rns code
function rnsCodeAllocate($objConn_a, $strClientID_a, $strRNSIDPrefix_a)
{
    $strResult = '';

    if (dependencies('setting/sequenceAllocate')) 
	{
        $strResult = sequenceAllocate($objConn_a, '', 'CORE', 'RNSID', $strClientID_a, '', $strRNSIDPrefix_a, '', '', __FUNCTION__);
    }

    return $strResult;
}
