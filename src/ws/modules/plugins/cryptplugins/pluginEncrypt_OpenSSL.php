<?php
function pluginEncrypt_OpenSSL($strSalt_a, $str_a)
{
    /*
    * note on how to create an hexiv
    * $strCipher = 'aes-128-cbc';
    * $strKey = openssl_digest($strSalt_a, 'SHA256', TRUE);    
    * $strIV = openssl_random_pseudo_bytes(openssl_cipher_iv_length($strCipher));
    * $strHexIV = bin2hex($strIV);
    */

    $strHexIV = '6dc974385814c74ac6c3b434b9811d45';
    $strCipher = 'aes-128-cbc';
    $strKey = openssl_digest($strSalt_a, 'SHA256', TRUE); 
    $strIV = hex2bin($strHexIV);

    return base64_encode(openssl_encrypt($str_a, $strCipher, $strKey, 0, $strIV));
}