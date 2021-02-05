<?php

namespace App\Cart;
use App\Entity\Product;

class CartItem
{

    public $product;
    protected $quantity;


    /**
     * CartItem constructor.
     * @param Product $product
     * @param int $quantity
     */
    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }


    /**
     * @return mixed
     */
    public function getTotal(): int
    {
        return $this->product->getPrice() * $this->quantity;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

}