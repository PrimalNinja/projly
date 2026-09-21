<?php
function pluginDecrypt_OpenSSL($strSalt_a, $str_a)
{    
    /**
     * hexIV must match the value in pluginEncrypt_OpenSSL
     */
    $strHexIV = '6dc974385814c74ac6c3b434b9811d45';
    $strCipher = 'aes-128-cbc';
    $strKey = openssl_digest($strSalt_a, 'SHA256', TRUE); 
    $strIV = hex2bin($strHexIV);
    
    return openssl_decrypt(base64_decode($str_a), $strCipher, $strKey, 0, $strIV);   
}