<?php

function pluginEncrypt_MD5($strSalt_a, $str_a)
{
    return md5($strSalt_a . $str_a);
}
