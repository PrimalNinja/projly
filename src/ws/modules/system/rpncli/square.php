<?php

class rpnCLI_square
{
	function execute($objRPNCLI)
	{
		$numOperand = $objRPNCLI->popNumber();
		$objRPNCLI->pushNumber($numOperand * $numOperand);
	}
}

?>
