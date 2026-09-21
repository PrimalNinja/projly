<?php

class rpnCLI_add
{
	function execute($objRPNCLI)
	{
		$numOperand1 = $objRPNCLI->popNumber();
		$numOperand2 = $objRPNCLI->popNumber();
		$objRPNCLI->pushNumber($numOperand2 + $numOperand1);
	}
}

?>
