<?php
/*=========================================================================*/
/* Name: UniqueWeighting.php                                               */
/* Uses: Weighting mechanism for using a unique value for each index.      */
/* Date: 2015/02/10                                                        */
/* Author: Andrew Que (http://www.DrQue.net/)                              */
/* Revisions:                                                              */
/*  1.0 - 2015/02/10 - QUE - Creation.                                     */
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
require_once( 'RootDirectory.inc.php' );
require_once( $RootDirectory . 'Includes/PolynomialRegression/PolynomialRegression.php' );

/**
 * This class will weight regression input on a term-by-term basis.  Typically
 * this would be done using a table of weighting terms with one term for each
 * data point.
 *
 * <pre>
 * $regression = new PolynomialRegression( $numberOfCoefficients );
 * $weighting = new UniqueWeighting();
 * // ...
 * foreach ( $data as $dataPoint )
 * {
 *   $weighting->setWeight( $dataPoint[ "weight" ] );
 *   $regression->addData( $dataPoint[ "x" ], $dataPoint[ "y" ] );
 * }
 * </pre>
 *
 * @package Weighting
 * @author Andrew Que
 * @link http://PolynomialRegression.drque.net/ Project home page.
 * @copyright Copyright (c) 2015, Andrew Que
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.0
 */
class UniqueWeighting implements WeightingInterface
{
  /**
   * @ignore
   */
  private $weight;

  /**
   * Set the weight for the next index.
   *
   * Should be called directly before adding data with the weight for the next term.
   * @param float $weight Weight to use for next term.
   */
  public function setWeight( $weight )
  {
    $this->weight = $weight;
  }

  /**
   * Get weight for specific index.
   *
   * Returns the last weight entered.
   * @param int $index The index to get weighting (ignored).
   */
  public function getWeight( $index )
  {
    return $this->weight;
  }
}

?>