<?php

namespace PdfToText\Capture\Dev;


/***
 * Implements a rectangle capture, from the caller point of view.
 * Class PdfToTextRectangleCapture
 * @package PdfToText\Capture\Dev
 */
class  PdfToTextRectangleCapture extends \PdfToText\Capture\Dev\PdfToTextCapture {
	/*--------------------------------------------------------------------------------------------------------------
	
	    Constructor -
		Builds an object array indexed by page number.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function __construct($objects) {
		$new_objects = array();

		foreach ($objects as $object)
			$new_objects [$object->Page] = $object;

		parent::__construct($new_objects);
	}
}

