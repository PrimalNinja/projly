<?php

function pluginEncrypt_BlowfishExternal($strSalt_a, $str_a)
{
    $strResult = '';

    if (dependencies('plugins/cryptplugins/blowfish')) 
	{
        $blowfish = new Blowfish($strSalt_a, strlen($strSalt_a));
        $strTemp = $str_a;

        while (strlen($strTemp) < 24) 
		{
            $strTemp .= $strTemp;
        }
        $strTemp = substr($strTemp, 0, 24);

        $blowfish->Encrypt($strTemp, strlen($strTemp));
        $strResult = $strTemp;
    }

    return $strResult;
}
