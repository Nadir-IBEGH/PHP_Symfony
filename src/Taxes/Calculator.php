<?php

namespace App\Taxes;

class Calculator
{

    public function __construct(float $tva)
    {
    }

    public function calcul(float $prix): float
    {
          return $prix*(20/100);
    }
}