<?php

namespace App\Controller;

use App\Entity\Product;
use App\Event\ProductViewEvent;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category", priority=-1)
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
     * @Route("/admin/product/show/{id}",  name="admin_product_show", priority=-1 )
     * @param ProductRepository $productRepository
     * @return Response
     */

    public function showProduct($id, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(
            ['id' => $id]
        );

        if (!$product) {
            throw  $this->createNotFoundException("Le produit demandé n'existe pas 1");
        }

        return $this->render('admin/product/show.html.twig', [
            'product' => $product]);
    }

    /**
     * @Route("admin/product/{id}/edit", name="admin_product_edit")
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
            return $this->redirectToRoute('admin_product_show', ['id'=>$product->getId()]);
        }
        $formView = $form->createView();
        return $this->render('admin/product/edit.html.twig', ['product' => $product, 'formView' => $formView]);
    }


    /**
     * @Route("/admin/product/create", name="admin_product_new")
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
            return $this->redirectToRoute('admin_product_show',
                ['id'=>$product->getId()]);
            //  dd($product);
        }
        $formView = $form->createView();

        return $this->render('admin/product/create.html.twig', ['formView' => $formView]);
    }

    /**
     * @Route("/admin/product", name="admin_index")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function products(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();     //d($products);
        return $this->render('admin/index.html.twig', ['products' => $products]);
    }
}

