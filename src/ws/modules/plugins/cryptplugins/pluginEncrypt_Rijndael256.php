<?php

function pluginEncrypt_Rijndael256($strSalt_a, $str_a)
{
    $strSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
    $strIV = mcrypt_create_iv($strSize, MCRYPT_RAND);
    $strKey = hash('sha256', $strSalt_a, true);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $strKey, $str_a, MCRYPT_MODE_ECB, $strIV));
}
