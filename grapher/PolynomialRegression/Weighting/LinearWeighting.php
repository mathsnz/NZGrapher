<?php
/*=========================================================================*/
/* Name: LinearWeighting.php                                               */
/* Uses: Weighting mechanism for using the index times a slope.            */
/* Date: 2015/02/10                                                        */
/* Author: Andrew Que (http://www.DrQue.net/)                              */
/* Revisions:                                                              */
/*  1.0 - 2015/02/10 - QUE - Creation.                                     */
/*  1.0.1 - 2015/02/17 - QUE - Bug fix.                                    */
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
 * This class will weight the terms in a linear manner.  The slope can be
 * positive or negative thereby placing more emphasis on the last/first terms
 * respectively.   A slope of 0 has no effect and all terms are weighted
 * equally.
 *
 * NOTE: Does not use BC math which should not be a problem.
 *
 * @package Weighting
 * @author Andrew Que
 * @link http://PolynomialRegression.drque.net/ Project home page.
 * @copyright Copyright (c) 2015, Andrew Que
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.0.1
 */
class LinearWeighting implements WeightingInterface
{
  /**
   * @ignore
   */
  private $slope;

  /**
   * Constructor.
   *
   * Initialize class with the specified weighting slope term.
   * @param float $power The slope for the index.
   */
  public function __construct( $slope )
  {
    $this->slope = $slope;
  }

  /**
   * Get weight for specific index.
   *
   * Returns $index * $slope.
   * @param int $index The index to get weighting.
   */
  public function getWeight( $index )
  {
    $result = 1;
    if ( $this->slope > 0 )
      $result = $this->slope * $index;
    else
    if ( $this->slope < 0 )
      $result = -$this->slope / $index;

    return $result;
  }
}

?>