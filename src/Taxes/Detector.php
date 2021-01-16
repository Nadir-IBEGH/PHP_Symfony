<?php

namespace App\Taxes;

class Detector {

    public $tva;

    public function __construct(float $tva)
    {
        $this->tva=$tva;
    }

    /**
     * @param float $price
     * @return bool
     */
    public function detect(float $price):bool {
        if($price>$this->tva)
            return true;
        return false;
    }
}