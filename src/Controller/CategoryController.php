<?php

namespace App\Controller;

use App\Entity\Category;
use App\Event\ProductViewEvent;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CategoryController extends AbstractController
{
    /**
     * @Route("admin/category/create", name="category_create")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();
        return $this->render('category/create.html.twig', ['formView' => $formView]);
    }

    /**
     * @Route("admin/category/{id}/edit", name="category_edit")
     * @param $id
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @param EntityManagerInterface $em
     * @param SluggerInterface $slugger
     * @param Security $security
     * @return Response
     */
    public function edit($id, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em,
                         SluggerInterface $slugger, Security $security)
    {

        $category = $categoryRepository->find($id);
        if (!$category) {
            throw new NotFoundHttpException('Cette catégorie n\'existe pas');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($slugger->slug(strtolower($category->getName())));
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        $formView = $form->createView();
        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/{category_slug}/{product_slug}",  name="product_show", priority=-1 )
     * @param $product_slug
     * @param ProductRepository $productRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @return Response
     */

    public function showProduct($product_slug, ProductRepository $productRepository, EventDispatcherInterface $eventDispatcher)
    {
        $product = $productRepository->findOneBy(
            ['slug' => $product_slug]
        );

        if (!$product) {
            throw  $this->createNotFoundException("Le produit demandé n'existe pas");
        }

        $purchaseViewEvent = new ProductViewEvent($product);
        $eventDispatcher->dispatch($purchaseViewEvent, 'product.view');

        return $this->render('product/show.html.twig', [
            'product' => $product]);
    }

}
