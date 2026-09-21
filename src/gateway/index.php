<?php

$objFileIn = fopen("php://input", "r");
$objFileOut = fopen("httpputs/" . uniqid() . ".zip", "w");

while ($strData = fread($objFileIn, 1024))
{
	fwrite($objFileOut, $strData);
}

fclose($objFileOut);
fclose($objFileIn);

?>
