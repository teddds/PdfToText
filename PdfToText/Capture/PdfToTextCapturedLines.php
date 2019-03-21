<?php

namespace PdfToText\Capture;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/***
 * Implements a set of lines.
 * Class PdfToTextCapturedLines
 * @package PdfToText\Capture
 */
class  PdfToTextCapturedLines
	implements ArrayAccess, Countable, IteratorAggregate {
	// Capture name, as specified by the "name" attribute of the <lines> tag
	public $Name;
	// Page number of the capture
	public $Page;
	// Captured lines
	public $Lines;
	// Content type (mimics a little bit the PdfToTextCapturedText class)
	public $Type = \PdfToText\Capture\PdfToTextCaptureShapeDefinition::SHAPE_LINE;


	/*--------------------------------------------------------------------------------------------------------------
	
	    Constructor -
		Instantiates a PdfToTextCapturedLines object.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function __construct($name, $page, $lines) {
		$this->Name = $name;
		$this->Page = $page;
		$this->Lines = $lines;
	}


	/*--------------------------------------------------------------------------------------------------------------
	
		Interfaces implementations.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function count() {
		return ($this->Lines);
	}


	public function getIterator() {
		return (new ArrayIterator ($this->Lines));
	}


	public function offsetExists($offset) {
		return ($offset >= 0 && $offset < count($this->Lines));
	}


	public function offsetGet($offset) {
		return ($this->Captures [$offset]);
	}


	public function offsetSet($offset, $value) {
		error(new \PdfToText\Exceptions\PdfToTextCaptureException ("Unsupported operation."));
	}


	public function offsetUnset($offset) {
		error(new \PdfToText\Exceptions\PdfToTextCaptureException ("Unsupported operation."));
	}
}