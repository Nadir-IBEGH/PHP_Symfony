<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function addProduct(int $id)
    {
        $cart = $this->session->get('cart', []);
        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }

    /**
     * @return array
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];
        foreach ($this->session->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }
            $cartItem = new CartItem($product, $qty);
            $detailedCart [] = $cartItem;
        }
        return $detailedCart;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        $total = 0;
        foreach ($this->session->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }
            $total += $product->getPrice();
        }
        return $total;
    }

}