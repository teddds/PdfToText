<?php

namespace PdfToText\Exceptions;

/***
 * PdfToTextDecodingException -
 * Thrown when unexpected data is encountered while analyzing PDF contents.
 * Class PdfToTextDecodingException
 * @package PdfToText\Exceptions
 */
class  PdfToTextDecodingException extends \PdfToText\Exceptions\PdfToTextException {
	public function __construct($message, $object_id = false) {
		$text = "Pdf decoding error";

		if ($object_id !== false)
			$text .= " (object #$object_id)";

		$text .= " : $message";

		parent::__construct($text);
	}
}