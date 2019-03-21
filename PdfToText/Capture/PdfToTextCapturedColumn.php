<?php

namespace PdfToText\Capture;

/***
 * Class PdfToTextCapturedColumn
 * Implements a text captured by a lines/column shape.
 * Actually behaves like the PdfToTextCapturedRectangle class
 * @package PdfToText\Capture
 */
class  PdfToTextCapturedColumn extends \PdfToText\Capture\PdfToTextCapturedText {
	public function __construct($page, $name, $text, $left, $top, $right, $bottom, $definition) {
		parent::__construct($page, $name, $text, $left, $top, $right, $bottom, $definition);
	}


	public function __tostring() {
		return ($this->Text);
	}
}