<?php

namespace PdfToText\Maps\Adobe;

use PdfToText;
use PdfToText\Maps;

/***
 * Abstract class to handle Adobe-specific Win Ansi fonts.
 *
 * Class PdfTexterAdobeWinAnsiMap
 * @package PdfToText
 */
class    PdfTexterAdobeWinAnsiMap extends Maps\Adobe\PdfTexterAdobeMap {
	// Windows Ansi mapping to Unicode. Only substitutions that have no direct equivalent are listed here
	// Source : https://msdn.microsoft.com/en-us/goglobal/cc305145.aspx
	// Only characters from 0x80 to 0x9F have no direct translation
	public static $WinAnsiCharacterMap = array
	(
		// Normal WinAnsi mapping
		0 => array
		(
			0x80 => 0x20AC,
			0x82 => 0x201A,
			0x83 => 0x0192,
			0x84 => 0x201E,
			0x85 => 0x2026,
			0x86 => 0x2020,
			0x87 => 0x2021,
			0x88 => 0x02C6,
			0x89 => 0x2030,
			0x8A => 0x0160,
			0x8B => 0x2039,
			0x8C => 0x0152,
			0x8E => 0x017D,
			0x91 => 0x2018,
			0x92 => 0x2019,
			0x93 => 0x201C,
			0x94 => 0x201D,
			0x95 => 0x2022,
			0x96 => 0x2013,
			0x97 => 0x2014,
			0x98 => 0x02DC,
			0x99 => 0x2122,
			0x9A => 0x0161,
			0x9B => 0x203A,
			0x9C => 0x0153,
			0x9E => 0x017E,
			0x9F => 0x0178
		),
		// Cyrillic (IS08859-5)
		1 => array
		(
			0x93 => 0x0022,    // Quotes
			0x94 => 0x0022,
			0xC0 => 0x0410,
			0xC1 => 0x0411,
			0xC2 => 0x0412,
			0xC3 => 0x0413,
			0xC4 => 0x0414,
			0xC5 => 0x0415,
			0xC6 => 0x0416,
			0xC7 => 0x0417,
			0xC8 => 0x0418,
			0xC9 => 0x0419,
			0xCA => 0x041A,
			0xCB => 0x041B,
			0xCC => 0x041C,
			0xCD => 0x041D,
			0xCE => 0x041E,
			0xCF => 0x041F,
			0xD0 => 0x0420,
			0xD1 => 0x0421,
			0xD2 => 0x0422,
			0xD3 => 0x0423,
			0xD4 => 0x0424,
			0xD5 => 0x0425,
			0xD6 => 0x0426,
			0xD7 => 0x0427,
			0xD8 => 0x0428,
			0xD9 => 0x0429,
			0xDA => 0x042A,
			0xDB => 0x042B,
			0xDC => 0x042C,
			0xDD => 0x042D,
			0xDE => 0x042E,
			0xDF => 0x042F,
			0xE0 => 0x0430,
			0xE1 => 0x0431,
			0xE2 => 0x0432,
			0xE3 => 0x0433,
			0xE4 => 0x0434,
			0xE5 => 0x0435,
			0xE6 => 0x0436,
			0xE7 => 0x0437,
			0xE8 => 0x0438,
			0xE9 => 0x0439,
			0xEA => 0x043A,
			0xEB => 0x043B,
			0xEC => 0x043C,
			0xED => 0x043D,
			0xEE => 0x043E,
			0xEF => 0x043F,
			0xF0 => 0x0440,
			0xF1 => 0x0441,
			0xF2 => 0x0442,
			0xF3 => 0x0443,
			0xF4 => 0x0444,
			0xF5 => 0x0445,
			0xF6 => 0x0446,
			0xF7 => 0x0447,
			0xF8 => 0x0448,
			0xF9 => 0x0449,
			0xFA => 0x044A,
			0xFB => 0x044B,
			0xFC => 0x044C,
			0xFD => 0x044D,
			0xFE => 0x044E,
			0xFF => 0x044F
		)
	);

	public function __construct($object_id, $font_variant) {
		parent::__construct($object_id, $font_variant, self::$WinAnsiCharacterMap);
	}
}