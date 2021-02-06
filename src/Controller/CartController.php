<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return Response
     */
    public function add($id, ProductRepository $productRepository, CartService $cartService,
    Request $request)
    {
        /** @var Product $product */
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Erreur : le produit $id demandé n'exitste pas");
        }

        $cartService->addProduct($product->getId());
        $this->addFlash('success', "Le produit a bien été ajouté au panier.");

        if($request->query->get('returnToCart')){
            return $this->redirectToRoute('cart_show');
        }


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

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements = {"id": "\d+"})
     * @param $id
     * @param ProductRepository $productRepository
     * @param CartService $cartService
     */
    public function delete($id, ProductRepository $productRepository, CartService $cartService){

        $product = $productRepository->find($id);
        if(!$product){
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne peut pas etre supprimé");
        }

        $cartService->removeProduct($product->getId());
        $this->addFlash('success',"Le produit a bien été supprimé du panier !!");
        return $this->redirectToRoute('cart_show');
    }


    /**
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements = {"id": "\d+"})
     * @param $id
     * @param CartService $cartService
     * @param ProductRepository $productRepository
     */
    public function decrement($id, CartService $cartService, ProductRepository $productRepository)
    {

         $cartService->decrement($id);

         $this->addFlash("success","Le produit a bien été décrémenté !!");

         return $this->redirectToRoute("cart_show");
    }
}



















