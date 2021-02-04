<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function add($id, Request $request, ProductRepository $productRepository)
    {
        /** @var Product $product */
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Erreur : le produit $id demandé n'exitste pas");
        }

        $cart = $request->getSession()->get('cart', []);

        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $request->getSession()->set('cart', $cart);

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'product_slug' => $product->getSlug()]);

    }
}
