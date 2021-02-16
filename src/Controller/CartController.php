<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Entity\Product;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    /** @var ProductRepository */
    protected $productRepository;

    /** @var CartService */
    protected $cartService;

    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function add($id, Request $request)
    {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Erreur : le produit $id demandé n'exitste pas");
        }
        $this->cartService->addProduct($product->getId());
        $this->addFlash('success', "Le produit a bien été ajouté au panier.");

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        }


        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'product_slug' => $product->getSlug()]);
    }

    /**
     * @Route("/cart", name="cart_show")
     * @return Response
     */
    public function show(): Response
    {
        $form = $this->createForm(CartConfirmationType::class);

        $detailedCart = $this->cartService->getDetailedCartItems();

        $total = $this->cartService->getTotal();

        return $this->render('cart/index.html.twig',
            [
                'items' => $detailedCart,
                'total' => $total,
                'confirmationForm' => $form->createView()
            ]);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements = {"id": "\d+"})
     * @param $id
     */
    public function delete($id)
    {

        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne peut pas etre supprimé");
        }

        $this->cartService->removeProduct($product->getId());
        $this->addFlash('success', "Le produit a bien été supprimé du panier !!");
        return $this->redirectToRoute('cart_show');
    }


    /**
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements = {"id": "\d+"})
     * @param $id
     */
    public function decrement($id)
    {

        $this->cartService->decrement($id);

        $this->addFlash("success", "Le produit a bien été décrémenté !!");

        return $this->redirectToRoute("cart_show");
    }
}



















