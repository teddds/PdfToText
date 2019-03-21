<?php

namespace PdfToText\Forms;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use PdfToText\Forms\PdfToTextFormDefinition;

/**************************************************************************************************************
 **************************************************************************************************************
 **************************************************************************************************************
 ******                                                                                                  ******
 ******                                                                                                  ******
 ******                                       FORM DATA MANAGEMENT                                       ******
 ******                                                                                                  ******
 ******                                                                                                  ******
 **************************************************************************************************************
 **************************************************************************************************************
 **************************************************************************************************************/
class  PdftoTextFormDefinitions
	implements ArrayAccess, Countable, IteratorAggregate {
	static private $ClassDefinitionCount = 0;

	// Class name, as specified in the XML template
	protected $ClassName;
	// Form definitions (a template may contain several versions of the same for definition)
	protected $Definitions;
	// Form definitions coming from the PDF file
	protected $PdfDefinitions;


	/*--------------------------------------------------------------------------------------------------------------

	    Constructor -
		Parses the supplied XML template.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function __construct($xml_data, $pdf_xml_data) {
		// Get PDF XML form data definitions
		$this->__get_pdf_form_definitions($pdf_xml_data);

		// Create XML data from scratch, if none specified
		if (!$xml_data)
			$xml_data = $this->__create_default_xml_data($this->PdfDefinitions);

		// Decode XML the hard way, without XSD
		$xml = simplexml_load_string($xml_data);
		$root_entry = $xml->getName();
		$definitions = array();
		$class_name = "PdfFormData";

		if (strcasecmp($root_entry, "forms"))
			error(new \PdfToText\Exceptions\PdfToTextFormException ("Root entry must be <forms>, <$root_entry> was found."));

		// Get the attribute values of the <forms> tag
		foreach ($xml->attributes() as $attribute_name => $attribute_value) {
			switch (strtolower($attribute_name)) {
				case    'class' :
					$class_name = ( string )$attribute_value;

					if (class_exists($class_name, false))
						error(new \PdfToText\Exceptions\PdfToTextFormException ("Class \"$class_name\" specified in XML template already exists."));

					break;

				default :
					error(new \PdfToText\Exceptions\PdfToTextFormException ("Invalid attribute \"$attribute_name\" in <forms> tag."));
			}
		}

		// Don't know if it will be useful, but try to avoid class name collisions by appending a sequential number if necessary
		if (class_exists($class_name, false)) {
			self::$ClassDefinitionCount++;
			$class_name .= '_' . self::$ClassDefinitionCount;
		}

		// Loop through each child <form> entry
		foreach ($xml->children() as $child) {
			$child_name = $child->getName();

			switch (strtolower($child_name)) {
				case    'form' :
					$definitions [] = new PdfToTextFormDefinition ($class_name, $child, $this->PdfDefinitions);
					break;

				default :
					error(new \PdfToText\Exceptions\PdfToTextFormException ("Invalid tag <$child_name>."));
			}
		}

		// Ensure that there is at least one form definition
		if (!count($definitions))
			error(new \PdfToText\Exceptions\PdfToTextFormException ("No <form> definition found."));

		// Save to properties
		$this->ClassName = $class_name;
		$this->Definitions = $definitions;
	}


	/*--------------------------------------------------------------------------------------------------------------

		Internal methods.

	 *-------------------------------------------------------------------------------------------------------------*/

	// __get_pdf_form_definitions -
	//	Retrieves the form field definitions coming from the PDF file.
	private function __get_pdf_form_definitions($pdf_data) {
		preg_match_all('#(?P<field> <field .*? </field \s* >)#imsx', $pdf_data, $matches);

		foreach ($matches ['field'] as $field) {
			$xml_field = simplexml_load_string($field);

			foreach ($xml_field->attributes() as $attribute_name => $attribute_value) {
				switch (strtolower($attribute_name)) {
					case    'name' :
						$field_name = ( string )$attribute_value;

						if (isset ($this->PdfDefinitions [$field_name]))
							$this->PdfDefinitions [$field_name] ['occurrences']++;
						else {
							$this->PdfDefinitions [$field_name] = array
							(
								'name' => $field_name,
								'occurrences' => 1
							);
						}

						break;
				}
			}
		}
	}


	// __create_default_xml_data -
	//	When no XML template has been specified, creates a default one based of the form definitions located in the PDF file.
	private function __create_default_xml_data($pdf_definitions) {
		$result = "<forms>" . PHP_EOL .
			"\t<form version=\"1.0\">" . PHP_EOL;

		foreach ($pdf_definitions as $name => $field) {
			$name = str_replace('-', '_', $name);        // Just in case of
			$result .= "\t\t<field name=\"$name\" form-field=\"$name\" type=\"string\"/>" . PHP_EOL;
		}

		$result .= "\t</form>" . PHP_EOL .
			"</forms>" . PHP_EOL;

		return ($result);
	}


	/*--------------------------------------------------------------------------------------------------------------

		Interfaces implementations to retrieve form definitions.

	 *-------------------------------------------------------------------------------------------------------------*/
	public function count() {
		return (count($this - Definitions));
	}


	public function getIterator() {
		return (new ArrayIterator ($this->Definitions));
	}


	public function offsetExists($offset) {
		return ($offset >= 0 && $offset < count($this->Definitions));
	}


	public function offsetGet($offset) {
		return ($this->Definitions [$offset]);
	}


	public function offsetSet($offset, $value) {
		error(new \PdfToText\Exceptions\PdfToTextException ("Unsupported operation."));
	}


	public function offsetunset($offset) {
		error(new \PdfToText\Exceptions\PdfToTextException ("Unsupported operation."));
	}
}