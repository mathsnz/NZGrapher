<?php
/*=========================================================================*/
/* Name: ExponentiationWeighting.php                                       */
/* Uses: Weighting mechanism for using the index to a given power.         */
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
 * This class will weight the terms for the regression by raising the index of the term to
 * a given power.  When the power term is positive this means those indices at the end will
 * command more of the outcome than those at the beginning.  The reverse is true for negative
 * powers.  A power of 0 has no effect and all terms are weighted equally.
 *
 * NOTE: Does not use BC math.  This is done so the weighting power can be fractional which
 * is not supported by BC math.  Because BC math isn't use the useful range of this class is
 * limited.
 *
 * @package Weighting
 * @author Andrew Que
 * @link http://PolynomialRegression.drque.net/ Project home page.
 * @copyright Copyright (c) 2015, Andrew Que
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.0
 */
class ExponentiationWeighting implements WeightingInterface
{
  /**
   * @ignore
   */
  private $power;

  /**
   * Constructor.
   *
   * Initialize class with the specified weighting power term.
   * @param float $power What power to raise the index.
   */
  public function __construct( $power )
  {
    $this->power = $power;
  }

  /**
   * Get weight for specific index.
   *
   * Returns $index^$power.
   * @param int $index The index to get weighting.
   */
  public function getWeight( $index )
  {
    return pow( $index, $this->power );
  }
}

?>