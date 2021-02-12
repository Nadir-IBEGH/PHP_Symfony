<?php

namespace App\Event;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductViewEvent extends Event {

    private $product ;

    /**
     * ProductViewEvent constructor.
     * @param Product $product
     */
    public function __construct(Product $product){
        $this->product =$product;
    }

    /**
     * @return Product
     */
    public function getProduct() : Product{
        return $this->product;
    }
}
