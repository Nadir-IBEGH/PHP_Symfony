<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    /** @var SessionInterface */
    protected $session;

    /** @var ProductRepository */
    protected $productRepository;

    /**
     * CartService constructor.
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     */
    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }


    /**
     * @return array
     */
    public function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    /**
     * @param array $cart
     */
    public function saveCart(array $cart): void
    {
        $this->session->set('cart', $cart);
    }


    /**
     * @param int $id
     */
    public function addProduct(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }
        $cart[$id]++;
        $this->saveCart($cart);
    }

    /**
     * @return array
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];
        foreach ($this->getCart() as $id => $qty) {
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
        foreach ($this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }
            $total += $product->getPrice();
        }
        return $total;
    }

    /**
     * @param $id
     */
    public function removeProduct($id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        $this->saveCart($cart);
    }

    /**
     * @param int $id
     */
    public function decrement(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        if ($cart[$id] === 1) {
            $this->removeProduct($id);
            return;
        }

        $cart[$id]--;

        $this->saveCart($cart);
    }

}