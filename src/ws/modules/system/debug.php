<?php

if (DEBUG_PHPCONSOLE == 'TRUE') 
{
    if (dependencies('3p/PhpConsole/Auth,3p/PhpConsole/Connector,3p/PhpConsole/Dispatcher,3p/PhpConsole/Dumper,3p/PhpConsole/EvalProvider,3p/PhpConsole/Handler,3p/PhpConsole/Helper,3p/PhpConsole/OldVersionAdapter,3p/PhpConsole/Dispatcher/Debug,3p/PhpConsole/Dispatcher/Errors,3p/PhpConsole/Dispatcher/Evaluate')) 
	{
        try
        {
            PhpConsole::start();

            //PhpConsole\Connector::getInstance()->setServerEncoding('cp1251');
            //PhpConsole\Helper::register();
        } 
		catch (Exception $e) 
		{
            // do nothing
        }
    }
}
