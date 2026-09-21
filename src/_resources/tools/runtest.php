<?php

$a = null;
$b = 2;

$a || $a = $b;

var_dump($a);

?>