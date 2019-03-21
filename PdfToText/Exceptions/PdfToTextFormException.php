<?php
namespace PdfToText\Exceptions;

/***
 * Class PdfToTextFormException
 * Thrown if the xml template passed to the GetFormData() method contains an error.
 * @package PdfToText\Exceptions
 */
class  PdfToTextFormException extends \PdfToText\Exceptions\PdfToTextException {
	public function __construct($message) {
		$text = "Pdf form template error";

		$text .= " : $message";

		parent::__construct($text);
	}
}