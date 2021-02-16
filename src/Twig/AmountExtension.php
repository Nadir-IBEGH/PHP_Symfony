<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension {

    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this,'amount'])
        ];
    }

    public function amount($value, string $symbol = '€', string $descep=',', string $thousandsep= ' '){
        $finalValue = number_format($value,2 ,$descep, $thousandsep);
        return $finalValue.''.$symbol;
    }


    // version simple elle s'applique sans paramètre donc elle mis 1299 en 12,99 €
/*    public function amount($value){
        $finalValue = number_format($value,2 ,',', ' ');
        return $finalValue. ' €';
    }*/
}

