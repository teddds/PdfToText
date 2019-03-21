<?php

namespace PdfToText\Capture;

/***
 * Implements a text captured by a rectangle shape.
 * Class PdfToTextCapturedRectangle
 * @package PdfToText\Capture
 */
class  PdfToTextCapturedRectangle extends \PdfToText\Capture\PdfToTextCapturedText {
	public function __construct($page, $name, $text, $left, $top, $right, $bottom, $definition) {
		parent::__construct($page, $name, $text, $left, $top, $right, $bottom, $definition);
	}


	public function __tostring() {
		return ($this->Text);
	}
}