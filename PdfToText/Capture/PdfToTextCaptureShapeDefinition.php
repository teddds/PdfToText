<?php
namespace PdfToText\Capture;

use PdfToText;
use PdfToText\Capture\PdfToTextCaptureApplicablePages;

/***
 * Base class for capturing shapes.
 * Class PdfToTextCaptureShapeDefinition
 * @package PdfToText\Capture
 */
abstract class  PdfToTextCaptureShapeDefinition {
	const    SHAPE_RECTANGLE = 1;
	const    SHAPE_COLUMN = 2;
	const    SHAPE_LINE = 3;

	// Capture name
	public $Name;
	// Capture type - one of the SHAPE_* constants, assigned by derived classes.
	public $Type;
	// Applicable pages for this capture
	public $ApplicablePages;
	// Areas per page for this shape
	public $Areas = array();
	// Separator used when multiple elements are covered by the same shape
	public $Separator = " ";


	/*--------------------------------------------------------------------------------------------------------------
	
	     Constructor -
		Initializes the base capture class.

	 *-------------------------------------------------------------------------------------------------------------*/
	public function __construct($type) {
	$this->Type = $type;
	$this->ApplicablePages = new PdfToTextCaptureApplicablePages ();
}


	/*--------------------------------------------------------------------------------------------------------------
	
	     SetPageCount -
		Sets the page count, so that all the applicable pages can be determined.
		Derived classes can implement this function if some additional work is needed.

	 *-------------------------------------------------------------------------------------------------------------*/
	public function SetPageCount($count) {
	$this->ApplicablePages->SetPageCount($count);
}


	/*--------------------------------------------------------------------------------------------------------------
	
	     GetFragmentData -
		Extracts data from a text fragment (text + coordinates).

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function GetFragmentData($fragment, &$text, &$left, &$top, &$right, &$bottom) {
	$left = ( double )$fragment ['x'];
	$top = ( double )$fragment ['y'];
	$right = $left + ( double )$fragment ['width'] - 1;
	$bottom = $top - ( double )$fragment ['font-height'];
	$text = $fragment ['text'];
}


	/*--------------------------------------------------------------------------------------------------------------
	
	     GetAttributes -
		Retrieves the attributes of the given XML node. Processes the following attributes, which are common to
		all shapes :
		- Name
		- Separator

	 *-------------------------------------------------------------------------------------------------------------*/
	protected function GetAttributes($node, $attributes = array()) {
	$attributes = array_merge($attributes, array('name' => true, 'separator' => false));
	$shape_attributes = \PdfToText\Capture\PdfToTextCaptureDefinitions::GetNodeAttributes($node, $attributes);
	$this->Name = $shape_attributes ['name'];

	if ($shape_attributes ['separator'] !== false)
		$this->Separator = PdfToText\PdfToText::Unescape($shape_attributes ['separator']);

	return ($shape_attributes);
}


	/*--------------------------------------------------------------------------------------------------------------
	
	     ExtractAreas -
		Extracts text contents from the document fragments.

	 *-------------------------------------------------------------------------------------------------------------*/
	public abstract function ExtractAreas($document_fragments);
}