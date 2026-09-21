<?php

// return the current date
function getDateOnly()
{
	return date("Y-m-d");
}

// return the current date and time ISO format
function getDateTime()
{
	return date("Y-m-d H:i:s");
}

function getToday()
{
	return date("d M Y");
}

?>
