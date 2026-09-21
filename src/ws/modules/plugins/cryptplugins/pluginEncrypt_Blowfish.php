<?php

function pluginEncrypt_Blowfish($strSalt_a, $str_a)
{
    // note: php's inbuilt blowfish doesn't work on a mac
    return crypt($str_a, '$2a$07$' . $strSalt_a . '$');
}
