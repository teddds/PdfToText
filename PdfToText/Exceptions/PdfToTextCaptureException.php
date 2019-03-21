<?php

namespace PdfToText\Exceptions;

/***
 * Class PdfToTextCaptureException
 *
 * Thrown if the xml template passed to the SetCaptures() method contains an error.
 * @package PdfToText\Exceptions
 */
class  PdfToTextCaptureException extends \PdfToText\Exceptions\PdfToTextException {
	public function __construct($message) {
		$text = "Pdf capture template error";

		$text .= " : $message";

		parent::__construct($text);
	}
}