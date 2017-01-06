<?php 
function encodeIt($string)
{
	/*
	 *  ` = !1
	 *  " = !2
	 *  \ = !3
	 *  + = !4
	 *  ' = !5
	 *  - = !6
	 *  # = !7
	 *  . = !8
	 */
	$tempString = str_replace("`",	"!0000!",$string);
	$tempString = str_replace("\"",	"!0001!",$tempString);
	$tempString = str_replace("\\",	"!0010!",$tempString);
	$tempString = str_replace("+",	"!0011!",$tempString);
	$tempString = str_replace("\'",	"!0100!",$tempString);
	$tempString = str_replace("-",	"!0101!",$tempString);
	$tempString = str_replace("#",	"!0110!",$tempString);
	$tempString = str_replace(".",	"!0111!",$tempString);
	$tempString = str_replace("&",	"!1000!",$tempString);
	
	return $tempString;

}

function decodeIt($string)
{
	$tempString = str_replace("!0000!",	"`",	$string);
	$tempString = str_replace("!0001!",	"\"",	$tempString);
	$tempString = str_replace("!0010!",	"\\",	$tempString);
	$tempString = str_replace("!0011!",	"+"	,	$tempString);
	$tempString = str_replace("!0100!",	"'",	$tempString);
	$tempString = str_replace("!0101!",	"-",	$tempString);
	$tempString = str_replace("!0110!",	"#",	$tempString);
	$tempString = str_replace("!0111!",	".",	$tempString);
	$tempString = str_replace("!1000!",	"&",	$tempString);

	return $tempString;
}

?>