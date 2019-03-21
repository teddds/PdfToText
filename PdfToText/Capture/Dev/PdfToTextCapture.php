<?php

namespace PdfToText\Capture\Dev;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/***
 *  Base class for all capture classes accessible to the caller.
 * Class PdfToTextCapture
 * @package PdfToText\Capture\Dev
 */
class  PdfToTextCapture
	implements ArrayAccess, Countable, IteratorAggregate {
	protected $Captures;


	/*--------------------------------------------------------------------------------------------------------------
	
	    Constructor -
		Instantiates a PdfToTextCapture object.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function __construct($objects) {
		//parent::__construct ( ) ;

		$this->Captures = $objects;
	}


	/*--------------------------------------------------------------------------------------------------------------
	
		Interfaces implementations.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function count() {
		return ($this->Captures);
	}


	public function getIterator() {
		return (new ArrayIterator ($this->Captures));
	}


	public function offsetExists($offset) {
		return ($offset >= 0 && $offset < count($this->Captures));
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