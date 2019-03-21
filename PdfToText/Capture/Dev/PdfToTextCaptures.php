<?php

namespace PdfToText\Capture\Dev;

use PdfToText\Capture\Dev\PdfToTextLinesCapture;
use PdfToText\Capture\Dev\PdfToTextRectangleCapture;
use stdClass;

/**************************************************************************************************************
 **************************************************************************************************************
 **************************************************************************************************************
 ******                                                                                                  ******
 ******                                                                                                  ******
 ******                               CAPTURE INTERFACE FOR THE DEVELOPER                                ******
 ******         (none of the classes listed here are meant to be instantiated outside this file)         ******
 ******                                                                                                  ******
 ******                                                                                                  ******
 **************************************************************************************************************
 **************************************************************************************************************
 **************************************************************************************************************/
class  PdfToTextCaptures           {
	// Captured objects - May not exactly reflect the PdfToTextCapture*Shape classes
	private $CapturedObjects;
	// Allows faster access by capture name
	private $ObjectsByName = array();


	/*--------------------------------------------------------------------------------------------------------------
	
	    Constructor -
		Instantiates a PdfToTextCaptures object.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function __construct($captures) {
	$this->CapturedObjects = $captures;

	// Build an array of objects indexed by their names
	foreach ($captures as $page => $shapes) {
		foreach ($shapes as $shape)
			$this->ObjectsByName [$shape->Name] [] = $shape;
	}
}


	/*--------------------------------------------------------------------------------------------------------------
	
	    ToCaptures -
		Returns a simplified view of captured objects, with only name/value pairs.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function ToCaptures() {
	$result = new stdClass ();

	foreach ($this->CapturedObjects as $page => $captures) {
		foreach ($captures as $capture) {
			switch ($capture->Type) {
				case    \PdfToText\Capture\PdfToTextCaptureShapeDefinition::SHAPE_RECTANGLE :
					$name = $capture->Name;
					$value = $capture->Text;
					$result->{$name} [$page] = $value;
					break;

				case    \PdfToText\Capture\PdfToTextCaptureShapeDefinition::SHAPE_LINE :
					$name = $capture->Name;

					if (!isset ($result->{$name}))
						$result->{$name} = array();

					foreach ($capture as $line) {
						$columns = new  stdClass;

						foreach ($line as $column) {
							$column_name = $column->Name;
							$column_value = $column->Text;
							$columns->{$column_name} = $column_value;
						}

						$result->{$name} [] = $columns;
					}
			}
		}
	}

	return ($result);
}


	/*--------------------------------------------------------------------------------------------------------------
	
	    __get -
		Retrieves the captured objects by their name, as specified in the XML definition.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function __get($member) {
	$fieldname = "__capture_{$member}__";

	if (!isset ($this->$fieldname)) {
		if (!isset ($this->ObjectsByName [$member]))
			error(new \PdfToText\Exceptions\PdfToTextException ("Undefined property \"$member\"."));

		$this->$fieldname = $this->GetCaptureInstance($member);
	}

	return ($this->$fieldname);
}


	/*--------------------------------------------------------------------------------------------------------------
	
	    GetCapturedObjectsByName -
		Returns an associative array of the captured shapes, indexed by their name.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function GetCapturedObjectsByName() {
	return ($this->ObjectsByName);
}


	/*--------------------------------------------------------------------------------------------------------------
	
	    GetCaptureInstance -
		Returns an object inheriting from the PdfToTextCapture class, that wraps the capture results.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	protected function GetCaptureInstance($fieldname) {
	switch ($this->ObjectsByName [$fieldname] [0]->Type) {
		case    \PdfToText\Capture\PdfToTextCaptureShapeDefinition::SHAPE_RECTANGLE :
			return (new PdfToTextRectangleCapture ($this->ObjectsByName [$fieldname]));

		case    \PdfToText\Capture\PdfToTextCaptureShapeDefinition::SHAPE_LINE :
			return (new PdfToTextLinesCapture ($this->ObjectsByName [$fieldname]));

		default :
			error(new \PdfToText\Exceptions\PdfToTextCaptureException ("Unhandled shape type " . $this->ObjectsByName [$fieldname] [0]->Type . "."));
	}
}


}