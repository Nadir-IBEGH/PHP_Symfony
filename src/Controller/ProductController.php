<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category")
     * @param $slug
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(
            ['slug'=>$slug]
        );
        if(!$category){
           throw  $this->createNotFoundException("La categorie demandé n'existe pas");
        };

        return $this->render('product/category.html.twig', [
            'category'=>$category
        ]);
    }

    /**
     * @Route("/{category_slug}/{product_slug}",  name="product_show" )
     * @param $product_slug
     * @param ProductRepository $productRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */

    public function showProduct($product_slug,ProductRepository $productRepository){
        $product = $productRepository->findOneBy(
            ['slug'=>$product_slug]
        );

        if(!$product){
            throw  $this->createNotFoundException("Le produit demandé n'existe pas");
        };

        return $this->render('product/show.html.twig', [
            'product'=>$product]);
    }
}
