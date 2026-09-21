<?php

function pluginDecrypt_Rijndael128($strSalt_a, $str_a)
{
    $strSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $strIV = mcrypt_create_iv($strSize, MCRYPT_RAND);
    $strKey = hash('sha256', $strSalt_a, true);
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $strKey, base64_decode($str_a), MCRYPT_MODE_ECB, $strIV));
}
