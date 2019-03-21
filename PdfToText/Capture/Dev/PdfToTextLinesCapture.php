<?php

namespace PdfToText\Capture\Dev;

/***
 *  Represents a lines capture, without indexation to their page number.
 * Class PdfToTextLinesCapture
 * @package PdfToText\Capture\Dev
 */
class  PdfToTextLinesCapture extends \PdfToText\Capture\Dev\PdfToTextCapture {
	/*--------------------------------------------------------------------------------------------------------------
	
	    Constructor -
		"flattens" the supplied object list, by removing the PdfToTextCapturedLines class level, so that lines
		can be iterated whatever their page number is.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function __construct($objects) {
		$new_objects = array();

		foreach ($objects as $object) {
			foreach ($object as $line)
				$new_objects [] = $line;
		}

		parent::__construct($new_objects);
	}
}