<?php

namespace PdfToText\Capture;

use ArrayAccess;
use Countable;
use Iterator;
use PdfToText\Capture\PdfToTextCaptureLinesDefinition;
use PdfToText\Capture\PdfToTextCaptureRectangleDefinition;
use PdfToText\Capture\Dev\PdfToTextCaptures;

/**************************************************************************************************************
 **************************************************************************************************************
 **************************************************************************************************************
 ******                                                                                                  ******
 ******                                                                                                  ******
 ******                                  CAPTURE DEFINITION MANAGEMENT                                   ******
 ******         (none of the classes listed here are meant to be instantiated outside this file)         ******
 ******                                                                                                  ******
 ******                                                                                                  ******
 **************************************************************************************************************
 **************************************************************************************************************
 **************************************************************************************************************/

/****
 * Class PdfToTextCaptureDefinitions
 * @package PdfToText\Capture
 */
class  PdfToTextCaptureDefinitions    // extends		Object 
	implements ArrayAccess, Countable, Iterator {
	// Shape definitions - The actual objects populating this array depend on the definitions supplied
	// (rectangle, etc.)
	protected $ShapeDefinitions = array();

	// Shape field names - used for iteration
	private $ShapeNames;

	// Page count 
	private $PageCount = false;


	/*--------------------------------------------------------------------------------------------------------------
	
	    CONSTRUCTOR -
		Analyzes the XML data defining the areas to be captured.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function __construct($xml_data) {
		$xml = simplexml_load_string($xml_data);
		$root_entry = $xml->getName();

		// Root tag must be <captures>
		if (strcasecmp($root_entry, "captures"))
			error(new \PdfToText\Exceptions\PdfToTextCaptureException ("Root entry must be <captures>, <$root_entry> was found."));

		// Process the child nodes
		foreach ($xml->children() as $child) {
			$tag_name = $child->getName();

			switch (strtolower($tag_name)) {
				// <rectangle> :
				//	An rectangle whose dimensions are given in the <page> subtags.
				case    'rectangle' :
					$shape_object = new PdfToTextCaptureRectangleDefinition ($child);
					break;

				// <columns> :
				//	A definition of columns and their applicable pages.
				case    'lines' :
					$shape_object = new PdfToTextCaptureLinesDefinition ($child);
					break;

				// Complain if an unknown tag is found
				default :
					error(new \PdfToText\Exceptions\PdfToTextCaptureException ("Invalid tag <$tag_name> found in root tag <captures>."));
			}

			// Shape names must be unique within the definitinos
			if (isset ($this->ShapeDefinitions [$shape_object->Name]))
				error(new PdfToTextCaptureLinesDefinition ("The shape named \"{$shape_object -> Name}\" has been defined more than once."));
			else
				$this->ShapeDefinitions [$shape_object->Name] = $shape_object;
		}

		// Build an array of shape names for the iterator interface
		$this->ShapeNames = array_keys($this->ShapeDefinitions);
	}


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        GetCapturedObject - Creates an object reflecting the captured data.
	
	    PROTOTYPE
	        $captures	=  $capture_definitions -> GetCapturedObject ( $document_fragments ) ;
	
	    DESCRIPTION
	        Returns an object of type PdfToTextCapturedData,containing the data that has been captured, based on
		the capture definitions.
	
	    PARAMETERS
	        $document_fragments (type) -
	                Document text fragments collected during the text layout rendering process.
	
	    RETURN VALUE
	        An object of type PdfToTextCaptures, cntaining the captured data.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function GetCapturedObject($document_fragments) {
		$captures = array();

		foreach ($this->ShapeDefinitions as $shape) {
			$capture = $shape->ExtractAreas($document_fragments);

			foreach ($capture as $page => $items) {
				$captures [$page] [] = $items;
			}
		}

		$captured_object = new PdfToTextCaptures ($captures);

		return ($captured_object);
	}


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        SetPageCount - Defines the total number of pages in the document.
	
	    PROTOTYPE
	        $shape -> SetPageCount ( $count ) ;
	
	    DESCRIPTION
	        At the time when XML definitions are processed, the total number of pages in the document is not yet 
		known. Moreover, page ranges or page numbers can be expressed relative to the last page of the 
		document (for example : 1..$-1, which means "from the first page to the last page - 1).
		Setting the page count once it is known allows to process the expressions specified in the "number"
		attribute of the <pages> tag so that the expressions are transformed into actual page numbers.
	
	    PARAMETERS
	        $count (integer) -
	                Number of pages in the document.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function SetPageCount($count) {
		$this->PageCount = $count;

		foreach ($this->ShapeDefinitions as $def) {
			$def->SetPageCount($count);
		}
	}


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        GetNodeAttributes - Retrieves an XML node's attributes.
	
	    PROTOTYPE
	        $result		=  PdfToTextCaptureDefinitions::GetNodeAttributes ( $node, $attributes ) ;
	
	    DESCRIPTION
	        Retrieves the attributes defined for the specified XML node.
	
	    PARAMETERS
	        $node (SimpleXMLElement) -
	                Node whose attributes are to be extracted.

		$attributes (associative array) -
			Associative array whose keys are the attribute names and whose values define a boolean 
			indicating whether the attribute is mandatory or not.
	
	    RETURN VALUE
	        Returns an associative whose key are the attribute names and whose values are the attribute values,
		specified as a string.
		For optional unspecified attributes, the value will be boolean false.
	
	    NOTES
	        The method throws an exception if the node contains an unknown attribute, or if a mandatory attribute
		is missing.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public static function GetNodeAttributes($node, $attributes) {
		$tag_name = $node->getName();

		// Build the initial value for the resulting array
		$result = array();

		foreach (array_keys($attributes) as $name)
			$result    [$name] = false;

		// Loop through node attributes
		foreach ($node->attributes() as $attribute_name => $attribute_value) {
			$attribute_name = strtolower($attribute_name);

			// Check that the attributes exists ; if yes, add it to the resulting array
			if (isset ($attributes [$attribute_name]))
				$result [$attribute_name] = ( string )$attribute_value;
			// Otherwise, throw an exception
			else
				error(new PdfToTextCaptureLinesDefinition ("Undefined attribute \"$attribute_name\" for node <$tag_name>."));
		}

		// Check that all mandatory attributes have been specified
		foreach ($attributes as $attribute_name => $mandatory) {
			if ($mandatory && $result [$attribute_name] === false)
				error(new PdfToTextCaptureLinesDefinition ("Undefined attribute \"$attribute_name\" for node <$tag_name>."));
		}

		// All done, return
		return ($result);
	}


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        GetBooleanAttribute - Returns a boolean value associated to a string.
	
	    PROTOTYPE
	        $bool	=  PdfToTextCaptureDefinitions::GetBooleanValue ( $value ) ;
	
	    DESCRIPTION
	        Returns a boolean value corresponding to a boolean specified as a string.
	
	    PARAMETERS
	        $value (string) -
	                A boolean value represented as a string. 
			The strings 'true', 'yes', 'on' and '1' will be interpreted as boolean true.
			The strings 'false', 'no', 'off' and '0' will be interpreted as boolean false.
	
	    RETURN VALUE
	        The boolean value corresponding to the specified string.
	
	    NOTES
	        An exception is thrown if the supplied string is incorrect.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public static function GetBooleanAttribute($value) {
		$lcvalue = strtolower($value);

		if ($lcvalue === 'true' || $lcvalue === 'on' || $lcvalue === 'yes' || $lcvalue === '1' || $value === true)
			return (true);
		else if ($lcvalue === 'false' || $lcvalue === 'off' || $lcvalue === 'no' || $lcvalue === '0' || $value === false)
			return (false);
		else
			error(new PdfToTextCaptureLinesDefinition ("Invalid boolean value \"$value\"."));
	}


	/*--------------------------------------------------------------------------------------------------------------
	
		Interfaces implementations.

	 *-------------------------------------------------------------------------------------------------------------*/

	// Countable interface
	public function count() {
		return (count($this->ShapeDefinitions));
	}


	// ArrayAccess interface
	public function offsetExists($offset) {
		return (isset ($this->ShapeDefinitions [$offset]));
	}


	public function offsetGet($offset) {
		return ($this->ShapeDefinitions [$offset]);
	}


	public function offsetSet($offset, $value) {
		error(new \PdfToText\Exceptions\PdfToTextException ("Unsupported operation"));
	}


	public function offsetunset($offset) {
		error(new \PdfToText\Exceptions\PdfToTextException ("Unsupported operation"));
	}


	// Iterator interface -
	//	Iteration is made through shape names, which are supplied by the $ShapeNames property
	private $__iterator_index = 0;

	public function rewind() {
		$this->__iterator_index = 0;
	}

	public function valid() {
		return ($this->__iterator_index >= 0 && $this->__iterator_index < count($this->ShapeNames));
	}

	public function key() {
		return ($this->ShapeNames [$this->__iterator_index]);
	}

	public function next() {
		$this->__iterator_index++;
	}

	public function current() {
		return ($this->ShapeDefinitions [$this->ShapeNames [$this->__iterator_index]]);
	}
}