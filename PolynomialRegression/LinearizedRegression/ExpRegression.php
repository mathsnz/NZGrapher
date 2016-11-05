<?php
/*=========================================================================*/
/* Name: ExpRegression.php                                                 */
/* Uses: Calculates and returns coefficients for a * exp( x * b ).         */
/* Date: 2015/02/07                                                        */
/* Author: Andrew Que (http://www.DrQue.net/)                              */
/* Revisions:                                                              */
/*  0.9ß - 2015/02/07 - QUE - Creation.                                    */
/*                                                                         */
/* This project is maintained at:                                          */
/*    http://PolynomialRegression.drque.net/                               */
/*                                                                         */
/* ----------------------------------------------------------------------- */
/*                                                                         */
/* Polynomial regression class.                                            */
/* Copyright (C) 2015 Andrew Que                                           */
/*                                                                         */
/* This program is free software: you can redistribute it and/or modify    */
/* it under the terms of the GNU General Public License as published by    */
/* the Free Software Foundation, either version 3 of the License, or       */
/* (at your option) any later version.                                     */
/*                                                                         */
/* This program is distributed in the hope that it will be useful,         */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of          */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           */
/* GNU General Public License for more details.                            */
/*                                                                         */
/* You should have received a copy of the GNU General Public License       */
/* along with this program.  If not, see <http://www.gnu.org/licenses/>.   */
/*                                                                         */
/* ----------------------------------------------------------------------- */
/*                                                                         */
/*                           (C) Copyright 2015                            */
/*                               Andrew Que                                */
/*=========================================================================*/
require_once( './PolynomialRegression/PolynomialRegression/PolynomialRegression.php' ); 

/**
 * Used for calculating least-square coefficients for data conforming to the function:
 *    y = a * exp( b * x )
 *
 * Note: Although this function overloads the polynomial regression class, the log and
 * exponential function are not done using BC math and therefor do not have the accuracy.
 *
 * Quick example:
 *
 * <pre>
 * $regression = new ExpRegression();
 * // ...
 * $regression->addData( $x, $y );
 * // ...
 * $coefficients = $regression->getCoefficients();
 * // ...
 * $y = $regression->interpolate( $coefficients, $x );
 * </pre>
 *
 * @package LinearizedRegression
 * @author Andrew Que
 * @link http://PolynomialRegression.drque.net/ Project home page.
 * @copyright Copyright (c) 2015, Andrew Que
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 0.9b
 */
class ExpRegression extends PolynomialRegression
{
  /**
   * Constructor
   *
   * Create new class.
   */
  public function __construct()
  {
    parent::__construct( 2 );
  }

  /**
   * Add data.
   *
   * Add a data point to calculation.
   * @param float $x Some real value.
   * @param float $y Some real value corresponding to $x.
   */
  public function addData( $x, $y )
  {
    // Take the natural log of y before adding this data.
    $y = log( $y );

    parent::addData( $x, $y );
  }

  /**
   * Get coefficients.
   *
   * Calculate and return coefficients based on current data.
   * @param int $numberOfCoefficient Ignored.
   * @return array Array of coefficients (as BC strings).
   */
  public function getCoefficients( $numberOfCoefficient = -1 )
  {
    $result = parent::getCoefficients( 2 );
    $result[ 0 ] = exp( $result[ 0 ] );

    return $result;
  }

  /**
   * Interpolate
   *
   * Return y point for given x and coefficient set.  Function is static as it
   * does not require any instance data to operate.
   * @param array $coefficients Coefficients as calculated by 'getCoefficients'.
   * @param float $x X-coordinate from which to calculate Y.
   * @return float Y-coordinate (as floating-point).
   */
  static public function interpolate( $coefficients, $x )
  {
      $y = $coefficients[ 0 ] * exp( $x * $coefficients[ 1 ] );

      return $y;
  }

}

// "Mathematicians stand on each other's shoulders." -- Carl Friedrich Gauss


?>