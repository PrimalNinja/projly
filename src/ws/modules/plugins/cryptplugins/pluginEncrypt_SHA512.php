<?php

function pluginEncrypt_SHA512($strSalt_a, $str_a)
{
    return crypt($str_a, '$6$rounds=5000$' . $strSalt_a . '$');
}
