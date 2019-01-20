<?php

namespace Larowlan\RomanNumeral;

/**
 * Defines a class for generating roman numerals from integers.
 */
class RomanNumeralGenerator {

  const SYMBOLS = ['I', 'V', 'X', 'L', 'C', 'D', 'M'];

  // symbol set for each supported decimal place
  const SYMBOL_SETS = [
    [0, 1, 2], // symbols for ones
    [2, 3, 4], // symbols for tens
    [4, 5, 6], // symbols for hundreds
  ];

  // symbol indices representing the patterns for 0~9
  const PATTERNS = [
    [],           // 0
    [0],          // 1
    [0, 0],       // 2
    [0, 0, 0],    // 3
    [0, 1],       // 4
    [1],          // 5
    [1, 0],       // 6
    [1, 0, 0],    // 7
    [1, 0, 0, 0], // 8
    [0, 2]        // 9
  ];

  /**
   * Generates a roman numeral from an integer.
   *
   * @param int $number
   *   Integer to convert.
   * @param bool $lowerCase
   *   (optional) Pass TRUE to convert to lowercase. Defaults to FALSE.
   *
   * @return string
   *   Roman numeral representing the passed integer.
   */
  public function generate(int $number, bool $lowerCase = FALSE) : string {

    $numberStr = (string)$number;

    $len = strlen($numberStr);
    $pos = $len;
    $return = '';

    // Travese from ones up to thousands decimal place
    $lowerBound = $len - count(self::SYMBOL_SETS);
    if ($lowerBound < 0) {
      $lowerBound = 0;
    }

    while($pos > $lowerBound) {
      // use symbols set for current decimal place
      $symbols = self::SYMBOL_SETS[$len - $pos];

      $pos--;

      // current digit
      $digit = (int)$numberStr[$pos];

      $pattern = self::PATTERNS[$digit];

      // construct string for current digit
      $part = array_map(function($n) use ($symbols) {
        return self::SYMBOLS[$symbols[$n]];
      }, $pattern);

      $return = implode('', $part) . $return;
    }

    // Digits after hundreds will simply be padded with the last symbol
    if ($pos) {
      // pad with the last symbol
      $lastSymbol = self::SYMBOLS[count(self::SYMBOLS) - 1];

      // extract thousand value
      $value = (int)substr($numberStr, 0, $pos);

      $return = str_repeat($lastSymbol, $value) . $return;
    }

    return $lowerCase ? strtolower($return) : $return;
  }
}
