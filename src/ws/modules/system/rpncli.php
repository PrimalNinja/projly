<?php

class rpncli
{
	private $m_arrOutput = array();
	private $m_arrScript = array();
	private $m_arrStack = array();
	private $m_arrSubroutines = array();
	private $m_arrExternals = array();
	
	function output($str_a)
	{
		array_push($this->m_arrOutput, $str_a);
	}

	function identifyType($str_a)
	{
		$strResult = 'number';
		
		if (strlen($str_a) > 2)
		{
			if ((substr($str_a, 0, 1) == "'") && (substr($str_a, -1) == "'"))
			{
				$strResult = 'string';
			}
			else if ((substr($str_a, 0, 1) == '"') && (substr($str_a, -1) == '"'))
			{
				$strResult = 'string';
			}
			else if ((substr($str_a, 0, 1) == "[") && (substr($str_a, -1) == "]"))
			{
				$strResult = 'array';
			}
			else if ((substr($str_a, 0, 1) == "{") && (substr($str_a, -1) == "}"))
			{
				$strResult = 'json';
			}
		}
		
		return $strResult;
	}
	
	function stripString($str_a)
	{
		$strResult = $str_a;

		if ((strlen($strResult) > 2) && (substr($strResult, 0, 1) == "'") && (substr($strResult, -1) == "'"))
		{
			$strResult = substr($strResult, 1, -1);
		}
		else if ((strlen($strResult) > 2) && (substr($strResult, 0, 1) == '"') && (substr($strResult, -1) == '"'))
		{
			$strResult = substr($strResult, 1, -1);
		}

		return $strResult;
	}

	// START of command set
	function alert()
	{
		$this->output(array_pop($this->m_arrStack)['value']);
	}
	
	function debug()
	{
		for ($intI = 0; $intI < count($this->m_arrStack); $intI++)
		{
			$objAction = $this->m_arrStack[$intI];
			$this->output($objAction['type'] . ': ' . $objAction['value']);
		}
	}
	
	function divide()
	{
		$this->pushNumber(2);
		$this->reverse();
		$this->pushNumber($this->popNumber() / $this->popNumber());
	}
	
	function multiply()
	{
		$this->pushNumber($this->popNumber() * $this->popNumber());
	}
	
	function sys_print()
	{
		$this->output(array_pop($this->m_arrStack)['value']);
	}
	
	function reverse()
	{
		$intCount = $this->popNumber();
		$intI = 0;
		$arrTemp = array();
		
		for ($intI = 0; $intI < $intCount; $intI++)
		{
			array_push($arrTemp, array_pop($this->m_arrStack));
		}

		for ($intI = 0; $intI < count($arrTemp); $intI++)
		{
			array_push($this->m_arrStack, $arrTemp[$intI]);
		}
	}
	
	function rotate()
	{
		$intCount = $this->popNumber();
		$intI = 0;
		$arrTemp = array();
		
		for ($intI = 0; $intI < $intCount; $intI++)
		{
			array_push($arrTemp, array_pop($this->m_arrStack));
		}

		$objLast = array_pop($arrTemp);

		$intCount = count($arrTemp);
		for ($intI = 0; $intI < $intCount; $intI++)
		{
 			array_push($this->m_arrStack, array_pop($arrTemp));
		}
		
		array_push($this->m_arrStack, $objLast);
	}
	
	function subtract()
	{
		$this->pushNumber(2);
		$this->reverse();
		$this->pushNumber($this->popNumber() - $this->popNumber());
	}
	
	function sum()
	{
		$this->pushNumber($this->popNumber() + $this->popNumber());
	}
	// END of command set
		
	function identifyAction($strOperand_a, $strHint_a)
	{
		$strResult = '';
		
		if ($strHint_a == 'subroutine')
		{
			$strResult = 'subroutine';
		}
		else if ($strHint_a == 'json')
		{
			$strResult = 'pushJSON';
		}
		else if ($strHint_a == 'number')
		{
			$strResult = 'pushNumber';
		}
		else if ($strHint_a == 'string')
		{
			$strResult = 'pushString';
		}
		else if (is_numeric($strOperand_a) == false)
		{
			$strResult = 'command';
		}
		else
		{
			$strResult = 'pushNumber';
		}

		return $strResult;
	}
	
	function prepare($str_a)
	{
		$str = $str_a;
		$blnInJSON = false;
		$blnInQuote = false;
		$blnSlash = false;
		$intI = 0;
		$intJSONNest = 0;
		$strChar = '';
		$strToken = '';

		$arrResult = array();

		for ($intI = 0; $intI < strlen($str); $intI++)
		{
			$strChar = substr($str, $intI, 1);
			if ($blnInJSON)
			{
				if (($strChar == '}') || ($strChar == ']'))
				{
					$intJSONNest--;
					$strToken = $strToken . $strChar;
					if ($intJSONNest == 0)
					{
						$blnInJSON = false;
						if (strlen($strToken) > 0) 
						{ 
							$arrResult[] = array('action' => $this->identifyAction($strToken, 'json'), 'operand' => $strToken);
						}
						$strToken = '';
					}
				}
				else if (($strChar == '{') || ($strChar == '['))
				{
					$intJSONNest++;
					$strToken = $strToken . $strChar;
				}
				else
				{
					$strToken = $strToken . $strChar;
				}
			}
			else if ($blnInQuote)
			{
				if ($blnSlash)
				{
					$strToken = $strToken . $strChar;
					$blnSlash = false;
				}
				else
				{
					if ($strChar == "\\")
					{
						$blnSlash = true;
					}
					else if (($strChar == "'") || ($strChar == '"'))
					{
						$strToken = $strToken . $strChar;
						$blnInQuote = false;
						if (strlen($strToken) > 0) 
						{ 
							$arrResult[] = array('action' => $this->identifyAction($strToken, 'string'), 'operand' => $strToken);
						}
						$strToken = '';
					}
					else
					{
						$strToken = $strToken . $strChar;
					}
				}
			}
			else
			{
				if ($strChar == ":")
				{
					$strToken = $strToken . $strChar;
					if (strlen($strToken) > 0) 
					{ 
						$arrResult[] = array('action' => $this->identifyAction($strToken, 'subroutine'), 'operand' => $strToken);
					}
					$strToken = '';
				}
				else if (($strChar == "'") || ($strChar == '"'))
				{
					if (strlen($strToken) > 0) 
					{ 
						$arrResult[] = array('action' => $this->identifyAction($strToken, ''), 'operand' => $strToken);
					}
					$strToken = $strChar;
					$blnInQuote = true;
				}
				else if (($strChar == '{') || ($strChar == '['))
				{
					if (strlen($strToken) > 0) 
					{ 
						$arrResult[] = array('action' => $this->identifyAction($strToken, ''), 'operand' => $strToken);
					}
					$strToken = $strChar;
					$blnInJSON = true;
					$intJSONNest = 1;
				}
				else if ($strChar == ' ')
				{
					if (strlen($strToken) > 0) 
					{ 
						$arrResult[] = array('action' => $this->identifyAction($strToken, ''), 'operand' => $strToken);
					}
					$strToken = '';
				}
				else
				{
					$strToken = $strToken . $strChar;
				}
			}
		}
		if (strlen($strToken) > 0) 
		{ 
			$arrResult[] = array('action' => $this->identifyAction($strToken, ''), 'operand' => $strToken);
		}
		
		return $arrResult;
	}

	function registerCommands($arrCommands_a)
	{
		for ($intI = 0; $intI < count($arrCommands_a); $intI++)
		{
			$strCommand = $arrCommands_a[$intI];
			array_push($this->m_arrExternals, $strCommand);
		}
	}
	
	function execute($arrScript_a)
	{
		$this->m_arrOutput = array();
		$this->m_arrScript = $arrScript_a;

		// build subroutine index
		$intAddress = 0;
		for ($intI = 0; $intI < count($this->m_arrScript); $intI++)
		{
			$objAction = $this->m_arrScript[$intI];

			if ($objAction['action'] == 'subroutine')
			{
				array_push($this->m_arrSubroutines, array('name' => substr($objAction['operand'], 0, strlen($objAction['operand']) - 1), 'entry' => $intAddress));
			}
			
			$intAddress++;
		}

		// start at main
		$intMain = $this->findEntry('main');
		if ($intMain >= 0)
		{
			$this->processScript($intMain);
		}
		else
		{
			// error
			$this->output('main not found');
		}

		return $this->m_arrOutput;
	}
	
	function findEntry($strSubroutine_a)
	{
		$intResult = -1;

		for ($intI = 0; $intI < count($this->m_arrSubroutines); $intI++)
		{
			$objAction = $this->m_arrSubroutines[$intI];

			if ($objAction['name'] == $strSubroutine_a)
			{
				$intResult = $objAction['entry'] + 1;
			}
		}
		
		return $intResult;
	}

	function popData()
	{
		$objResult = array_pop($this->m_arrStack);
		if (($objResult['type'] == "json") && (gettype($objResult['value']) == "string"))
		{
			$objResult['value'] = json_decode($objResult['value'], true);
		}
		
		return $objResult;
	}

	function popJSON()
	{
		$objResult = array_pop($this->m_arrStack)['value'];
		if (gettype($objResult) == "string")
		{
			$objResult = json_decode($objResult, true);
		}
		
		return $objResult;
	}
	
	function popNumber()
	{
		return floatval(array_pop($this->m_arrStack)['value']);
	}
	
	function popString()
	{
		return array_pop($this->m_arrStack)['value'];
	}
	
	// result is where to return to
	function processScript($intEntry_a)
	{
		$blnExecute = true;
		$intPC = $intEntry_a;
		
		while ($blnExecute)
		{
			$objAction = $this->m_arrScript[$intPC];
			$strOperand = $objAction['operand'];
			
			// aliases
			if ($strOperand == '*') { $strOperand = 'multiply'; }
			if ($strOperand == '/') { $strOperand = 'divide'; }
			if ($strOperand == '+') { $strOperand = 'sum'; }
			if ($strOperand == '-') { $strOperand = 'subtract'; }
			if ($strOperand == '.') { $strOperand = 'endsubroutine'; }
			
			if ($strOperand == 'endsubroutine')
			{
				// exit the processing of this subroutine
				$blnExecute = false;
			}
			else if ($objAction['action'] == 'command')
			{
				// first check if its a subroutine if so execute that instead
				$intSubroutine = $this->findEntry($strOperand);
				if ($intSubroutine >= 0)
				{
					$this->processScript($intSubroutine);
				}
				else
				{
					// handle reserved words
					if ($strOperand == 'print')
					{
						$strOperand = 'sys_print';
					}

					try
					{
						if (in_array($strOperand, $this->m_arrExternals))
						{
							// try external if it is there
							$strClassName = 'rpnCLI_' . $strOperand;
							$objClass = new $strClassName();
							call_user_func(array($objClass, 'execute'), $this);
						}
						else
						{
							// try an inbuilt command
							call_user_func(array($this, $strOperand));
						}
					}
					catch(Exception $e)
					{
						$this->output('invalid action: ' . $objAction['action']);
					}
				}
			}
			else if ($objAction['action'] == 'pushJSON')
			{
				$this->pushJSON($strOperand);
			}
			else if ($objAction['action'] == 'pushNumber')
			{
				$this->pushNumber($strOperand);
			}
			else if ($objAction['action'] == 'pushString')
			{
				$this->pushString($strOperand);
			}
			else
			{	
				$this->output('invalid action: ' . $objAction['action']);
			}
			
			$intPC++;
		}
	}

	function pushJSON($varOperand_a)
	{
		array_push($this->m_arrStack, array('type' => "json", 'value' => $varOperand_a));
	}
	
	function pushNumber($varOperand_a)
	{
		array_push($this->m_arrStack, array('type' => "number", 'value' => $varOperand_a));
	}
	
	function pushString($varOperand_a)
	{
		array_push($this->m_arrStack, array('type' => "string", 'value' => $varOperand_a));
	}
}

// example: 
// 
// $objRPNCLI = new rpncli();
// $arr = $objRPNCLI->prepare("get5: 5 . main: [1, 2, 3, 4, 5] { object: { id:1, name:\"fred\", data:[1, 2, 3, 4, 5] } } print print 'a string' print 3 5 + print 3 5 + 10 * 2 / 5 - print 1 2 3 4 5 debug 2 rotate debug get5 alert .");
// 
//var_dump($arr);	
// 
// $arrOutput = $objRPNCLI->execute($arr);

?>

