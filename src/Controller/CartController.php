<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     * @param $id
     * @param ProductRepository $productRepository
     * @param CartService $cartService
     * @return Response
     */
    public function add($id, ProductRepository $productRepository, CartService $cartService)
    {
        /** @var Product $product */
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Erreur : le produit $id demandé n'exitste pas");
        }

        $cartService->addProduct($product->getId());
        $this->addFlash('success', "Le produit a bien été ajouté au panier.");

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'product_slug' => $product->getSlug()]);
    }

    /**
     * @Route("/cart", name="cart_show")
     * @param CartService $cartService
     * @return Response
     */
    public function show(CartService $cartService): Response
    {
        $detailedCart = $cartService->getDetailedCartItems();
        $total = $cartService->getTotal();

        return $this->render('cart/index.html.twig',
            ['items' => $detailedCart,
                'total' => $total]);
    }
}

