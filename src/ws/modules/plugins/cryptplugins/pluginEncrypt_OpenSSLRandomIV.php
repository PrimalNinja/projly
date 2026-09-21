<?php
function pluginEncrypt_OpenSSLRandomIV($strSalt_a, $str_a)
{ 
    $strCipher = 'aes-128-cbc';
    $strKey = openssl_digest($strSalt_a, 'SHA256', TRUE); 
    $strIV = openssl_random_pseudo_bytes(openssl_cipher_iv_length($strCipher));

    return base64_encode(openssl_encrypt($str_a, $strCipher, $strKey, 0, $strIV)) . '.' . base64_encode(bin2hex($strIV));
}