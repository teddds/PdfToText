<?php

namespace PdfToText\Images;

use PdfToText;

/***
 * Handles encoded CCITT Fax images.
 * Class PdfFaxImage
 * @package PdfToText\Images
 */
class  PdfFaxImage extends PdfToText\PdfImage {
	public function __construct($image_data) {
		parent::__construct($image_data);
	}


	protected function CreateImageResource($image_data) {
		warning(new \PdfToText\Exceptions\PdfToTextDecodingException ("Decoding of CCITT Fax image format is not yet implemented."));
		//return ( imagecreatefromstring ( $image_data ) ) ;
	}
}