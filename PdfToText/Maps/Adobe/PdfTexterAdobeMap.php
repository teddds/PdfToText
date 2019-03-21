<?php

namespace PdfToText\Maps\Adobe;

use PdfToText;

abstract class  PdfTexterAdobeMap extends PdfToText\Maps\PdfTexterCharacterMap {
	// Font variant ; one of the PdfTexterFont::FONT_VARIANT_* constants
	public $Variant;
	// To be declared by derived classes :
	public $Map;


	public function __construct($object_id, $font_variant, $map) {
		parent::__construct($object_id);

		$this->HexCharWidth = 2;
		$this->Variant = $font_variant;
		$this->Map = $map;

		if (!isset ($map [$font_variant]))
			error(new  \PdfToText\Exceptions\PdfToTextDecodingException ("Undefined font variant #$font_variant."));
	}


	/*--------------------------------------------------------------------------------------------------------------

	        Interface implementations.

	 *-------------------------------------------------------------------------------------------------------------*/
	public function count() {
		return (count($this->$Map [$this->Variant]));
	}


	public function offsetExists($offset) {
		return (isset ($this->Map [$this->Variant] [$offset]));
	}


	public function offsetGet($offset) {
		if (isset ($this->Map [$this->Variant] [$offset]))
			$ord = $this->Map [$this->Variant] [$offset];
		else
			$ord = $offset;

		return ($this->CodePointToUtf8($ord));
	}
}