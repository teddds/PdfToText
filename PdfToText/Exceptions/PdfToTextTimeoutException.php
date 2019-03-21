<?php

namespace PdfToText\Exceptions;

/***
 * Class PdfToTextTimeoutException
 * Thrown when the PDFOPT_ENFORCE_EXECUTION_TIME or PDFOPT_ENFORCE_GLOBAL_EXECUTION_TIME option is set, and
 * the script took longer than the allowed execution time limit.
 * @package PdfToText\Exceptions
 */
class  PdfToTextTimeoutException extends \PdfToText\Exceptions\PdfToTextException {
	// Set to true if the reason why the max execution time was reached because of too many invocations of the Load() method
	// Set to false if the max execution time was reached by simply processing one PDF file
	public $GlobalTimeout;

	public function __construct($message, $global, $php_setting, $class_setting) {
		$text = "PdfToText max execution time reached ";

		if (!$global)
			$text .= "for one single file ";

		$text .= "(php limit = {$php_setting}s, class limit = {$class_setting}s) : $message";

		$this->GlobalTimeout = $global;

		parent::__construct($text);
	}
}