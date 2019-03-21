<?php

namespace PdfToText\Exceptions;

/***
 * PdfToTextDecryptionException -
 * Thrown when something unexpected is encountered while processing encrypted data
 * Class PdfToTextDecryptionException
 * @package PdfToText\Exceptions
 */
class  PdfToTextDecryptionException extends \PdfToText\Exceptions\PdfToTextException {
	public function __construct($message, $object_id = false) {
		$text = "Pdf decryption error";

		if ($object_id !== false)
			$text .= " (object #$object_id)";

		$text .= " : $message";

		parent::__construct($text);
	}
}