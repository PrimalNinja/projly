<?php

function pluginEncrypt_SHA256($strSalt_a, $str_a)
{
    return crypt($str_a, '$5$rounds=5000$' . $strSalt_a . '$');
}
