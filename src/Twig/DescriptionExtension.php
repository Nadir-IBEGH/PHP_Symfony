<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DescriptionExtension extends AbstractExtension {

    public function getFilters()
    {
        return [
            new TwigFilter('description', [$this,'description'])
        ];
    }

    public function description($description, int $length = 90){
        $description = substr($description,0, $length-1);
        return $description.' ...' ;
    }

}

