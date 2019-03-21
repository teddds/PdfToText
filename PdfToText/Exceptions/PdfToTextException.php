<?php

namespace PdfToText\Exceptions;

use Exception;

/**************************************************************************************************************
 *
 * NAME
 * PdfToText.phpclass
 *
 * DESCRIPTION
 * A class for extracting text from Pdf files.
 * Usage is very simple : just instantiate a PdfToText object, specifying an input filename, then use the
 * Text property to retrieve PDF textual contents :
 *
 * $pdf    =  new PdfToText ( 'sample.pdf' ) ;
 * echo $pdf -> Text ;        // or : echo ( string ) $pdf ;
 *
 * Or :
 *
 * $pdf    =  new PdfToText ( ) ;
 * // Modify any property here before loading the file ; for example :
 * // $pdf -> BlockSeparator = " " ;
 * $pdf -> Load ( 'sample.pdf' ) ;
 * echo $pdf -> Text ;
 *
 * AUTHOR
 * Christian Vigh, 04/2016.
 *
 * HISTORY
 * [Version : 1.6.7]    [Date : 2017/05/31]     [Author : CV]
 * . Added CID fonts
 * . Changed the way CID font maps are searched and handled
 *
 * (...)
 *
 * [Version : 1.0]    [Date : 2016/04/16]     [Author : CV]
 * Initial version.
 **************************************************************************************************************/
class  PdfToTextException extends Exception {
	public static $IsObject = false;
}