<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
            ['slug' => $slug]
        );
        if (!$category) {
            throw  $this->createNotFoundException("La categorie demandé n'existe pas");
        }

        return $this->render('product/category.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{product_slug}",  name="product_show" )
     * @param $product_slug
     * @param ProductRepository $productRepository
     * @return Response
     */

    public function showProduct($product_slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(
            ['slug' => $product_slug]
        );

        if (!$product) {
            throw  $this->createNotFoundException("Le produit demandé n'existe pas");
        }

        return $this->render('product/show.html.twig', [
            'product' => $product]);
    }

    /**
     * @Route("admin/product/{id}/edit")
     * @param $id
     * @param ProductRepository $productRepository
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $product = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('product_show',
                [
                    'category_slug' => $product->getCategory()->getSlug(),
                    'product_slug' => $product->getSlug()]);
        }
        $formView = $form->createView();
        return $this->render('product/edit.html.twig', ['product' => $product, 'formView' => $formView]);
    }


    /**
     * @Route("/admin/product/create", name="product_create")
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $form = $this->createForm(ProductType::class);
        // on demande au formualire de gérer la requeste
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            $product->setSlug(strtolower($slugger->slug($product->getName())));

            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_show',
                [
                    'product_slug' => $product->getSlug(),
                    'category_slug' => $product->getCategory()->getSlug()
                ]);
            //  dd($product);
        }
        $formView = $form->createView();

        return $this->render('product/create.html.twig', ['formView' => $formView]);
    }
}
