<?php
namespace PdfToText\Maps;

/***
 *  A class for mapping IDENTITY-H CID fonts (or trying to...).
 * Class PdfTexterIdentityHCIDMap
 * @package PdfToText\Maps
 */
class  PdfTexterIdentityHCIDMap extends \PdfToText\Maps\PdfTexterCIDMap {
	public function __construct($object_id, $font_variant) {
		parent::__construct($object_id, 'IDENTITY-H', $font_variant);
	}
}