<?php

// logout
function userLogout()
{
    // logout
//file_put_contents("d:\dev\session.log", " userLogout.php:\n", FILE_APPEND);	// DEBUGSESSION
	clearSession();

    return true;
}
