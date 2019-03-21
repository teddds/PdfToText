<?php

namespace PdfToText;

const PDFTOTEXT_BASEPATH = __DIR__;

/*==============================================================================================================

        Custom error reporting functions.

  ==============================================================================================================*/
if (!function_exists('warning')) {
	function warning($message) {
		trigger_error($message, E_USER_WARNING);
	}
}


if (!function_exists('error')) {
	function error($message) {
		if (is_string($message))
			trigger_error($message, E_USER_ERROR);
		else if (is_a($message, '\Exception'))
			throw $message;
	}
}


/*==============================================================================================================

        Backward-compatibility issues.

  ==============================================================================================================*/

// hex2bin -
//	This function appeared only in version 5.4.0
if (!function_exists('hex2bin')) {
	function hex2bin($hexstring) {
		$length = strlen($hexstring);
		$binstring = '';
		$index = 0;

		while ($index < $length) {
			$byte = substr($hexstring, $index, 2);
			$ch = pack('H*', $byte);
			$binstring .= $ch;

			$index += 2;
		}

		return ($binstring);
	}
}

spl_autoload_register(function($class){

	$path = PDFTOTEXT_BASEPATH . '\\' . $class.'.php';
	if(file_exists($path)){
		include_once $path;
	}
});