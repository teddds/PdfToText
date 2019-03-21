<?php

namespace PdfToText\Images;

use PdfToText;

/**
 *  Handles encoded JPG images.
 * Class PdfJpegImage
 * @package PdfToText\Images
 */
class  PdfJpegImage extends PdfToText\PdfImage {
	public function __construct($image_data, $autosave) {
		parent::__construct($image_data, $autosave);
	}


	protected function CreateImageResource($image_data) {
		return (imagecreatefromstring($image_data));
	}
}