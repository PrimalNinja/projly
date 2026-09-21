<?php
function pluginDecrypt_OpenSSLRandomIV($strSalt_a, $str_a)
{
    $arrStr = explode('.', $str_a);
    $strToken = $arrStr[0];    
    $strIV = isset($arrStr[1]) ? hex2bin(base64_decode($arrStr[1])) : base64_decode('');

    $strCipher = 'aes-128-cbc';
    $strKey = openssl_digest($strSalt_a, 'SHA256', TRUE); 
    
    return openssl_decrypt(base64_decode($strToken), $strCipher, $strKey, 0, $strIV);  
}