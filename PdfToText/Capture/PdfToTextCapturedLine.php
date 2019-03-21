<?php

namespace PdfToText\Capture;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/***
 * Implements a text captured by a lines shape.
 *
 * Class PdfToTextCapturedLine
 * @package PdfToText\Capture
 */
class  PdfToTextCapturedLine extends \PdfToText\Capture\PdfToTextCapturedText
	implements ArrayAccess, Countable, IteratorAggregate {
	// Column objects
	public $Columns;
	// Array of column names, to allow access by either index or column name
	private $ColumnsByNames = array();


	/*--------------------------------------------------------------------------------------------------------------
	
	    Constructor -
		Builds a Line object based on the supplied columns.
		Also builds the Text property, which contains the columns text separated by the separator string 
		specified in the XML definition.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function __construct($page, $name, $columns, $left, $top, $right, $bottom, $definition) {
		// Although the Columns property is most likely to be used, build a text representation of the whole ine
		$text = array();
		$count = 0;

		foreach ($columns as $column) {
			$text [] = $column->Text;
			$this->ColumnsByNames [$column->Name] = $count++;
		}

		// Provide this information to the parent constructor
		parent::__construct($page, $name, implode($definition->Separator, $text), $left, $top, $right, $bottom, $definition);

		// Store the column definitions
		$this->Columns = $columns;
	}


	/*--------------------------------------------------------------------------------------------------------------
	
	    __get -
		Returns access to a column by its name.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function __get($member) {
		if (isset ($this->ColumnsByNames [$member]))
			return ($this->Columns [$this->ColumnsByNames [$offset]]);
		else
			trigger_error("Undefined property \"$member\".");
	}


	/*--------------------------------------------------------------------------------------------------------------
	
		Interfaces implementations.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function count() {
		return ($this->Columns);
	}


	public function getIterator() {
		return (new ArrayIterator ($this->Columns));
	}


	public function offsetExists($offset) {
		if (is_numeric($offset))
			return ($offset >= 0 && $offset < count($this->Columns));
		else
			return (isset ($this->ColumnsByNames [$offset]));
	}


	public function offsetGet($offset) {
		if (is_numeric($offset))
			return ($this->Columns [$offset]);
		else
			return ($this->Columns [$this->ColumnsByNames [$offset]]);
	}


	public function offsetSet($offset, $value) {
		error(new \PdfToText\Exceptions\PdfToTextCaptureException ("Unsupported operation."));
	}


	public function offsetUnset($offset) {
		error(new \PdfToText\Exceptions\PdfToTextCaptureException ("Unsupported operation."));
	}
}