<?php

class rpnCLI_subtract
{
	function execute($objRPNCLI)
	{
		$numOperand1 = $objRPNCLI->popNumber();
		$numOperand2 = $objRPNCLI->popNumber();
		$objRPNCLI->pushNumber($numOperand2 - $numOperand1);
	}
}

?>
